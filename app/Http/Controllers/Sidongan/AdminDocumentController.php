<?php

namespace App\Http\Controllers\Sidongan;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\DocumentTag;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminDocumentController extends Controller
{
    /**
     * Dashboard SIDONGAN (Stats + Recent Documents + Notifications)
     */
    public function dashboard()
    {
        $user = auth()->guard('sidongan')->user();

        // Safety check
        if (!$user) {
            return redirect()->route('sidongan.login');
        }
        
        // Stats query
        $statsQuery = Document::query();
        
        // Filter stats based on role
        if ($user->hasSidonganRole('sekretaris')) {
            $statsQuery->where('created_by', $user->id);
        }
        
        $totalDocuments = $statsQuery->count();
        $publishedDocuments = (clone $statsQuery)->where('status', 'published')->count();
        $draftDocuments = (clone $statsQuery)->where('status', 'draft')->count();
        $berjalanDocuments = (clone $statsQuery)->where('status', 'berjalan')->count();
        $menungguDocuments = (clone $statsQuery)->whereIn('status', ['menunggu_disposisi', 'menunggu_verifikasi'])->count();
        $selesaiDocuments = (clone $statsQuery)->where('status', 'selesai')->count();
        $arsipDocuments = (clone $statsQuery)->where('status', 'diarsipkan')->count();
        
        // Recent documents for dashboard
        $recentDocuments = (clone $statsQuery)
            ->with(['creator'])
            ->latest()
            ->take(5)
            ->get();

        // NOTIFICATIONS: Ambil notifikasi untuk user ini
        $notifications = Notification::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $unreadCount = Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->count();

        return view('sidongan.dashboard', [
            'totalSurat' => $statsQuery->count(),
            'sedangBerjalan' => (clone $statsQuery)->where('status', 'berjalan')->count(),
            'menungguProses' => (clone $statsQuery)->whereIn('status', ['menunggu_disposisi', 'menunggu_verifikasi'])->count(),
            'selesai' => (clone $statsQuery)->where('status', 'selesai')->count(),
            'diarsipkan' => (clone $statsQuery)->where('status', 'diarsipkan')->count(),
            'recentDocuments' => $recentDocuments,
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }

    /**
     * List Documents (Table View)
     */
    public function index(Request $request)
    {
        $user = auth()->guard('sidongan')->user();
        
        // 1. Buat Query Dasar
        $query = Document::with(['category', 'creator']);
        
        // Filter Role (Sekretaris hanya lihat surat buatannya)
        if ($user->hasSidonganRole('sekretaris')) {
            $query->where('created_by', $user->id);
        }

        // LANGKAH PENTING: Hitung Total Dokumen DI SINI (sebelum filter search)
        $totalDocuments = (clone $query)->count();
        
        // 2. Terapkan Filter Pencarian (hanya untuk tabel, bukan untuk kartu total)
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        
        // 3. Terapkan Filter Status & Kategori
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $documents = $query->latest()->paginate(15)->withQueryString();
        $categories = DocumentCategory::where('is_active', true)->orderBy('name')->get();

        return view('sidongan.documents.index', [
            'documents' => $documents,
            'categories' => $categories,
            'totalDocuments' => $totalDocuments
        ]);
    }

    public function create()
    {
        $categories = DocumentCategory::where('is_active', true)->orderBy('name')->get();
        return view('sidongan.documents.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // SAFETY CHECK: Pastikan user login via guard sidongan
        $user = auth()->guard('sidongan')->user();
        
        if (!$user) {
            \Log::error('SIDONGAN Store: User not authenticated');
            return redirect()->route('sidongan.login')
                ->withErrors(['auth' => 'Session expired. Silakan login ulang.']);
        }
        
        // Validasi input
        $validated = $request->validate([
            // Data Pengirim
            'sender' => 'required|string|max:255',
            'document_number' => 'required|string|max:100',
            'document_date' => 'required|date',
            'subject' => 'required|string|max:255',
            
            // Data Agenda (otomatis, tapi bisa di-override)
            'agenda_number' => 'nullable|string|max:100|unique:sidongan_documents,agenda_number',
            'agenda_date' => 'nullable|date',
            
            // Saran Sekretaris
            'suggestion' => 'required|string',
            
            // Upload File
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120', // Max 5MB
            
            // Kategori (opsional)
            'category_id' => 'nullable|exists:sidongan_categories,id',
        ]);

        // Handle upload file
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('sidongan/documents', $filename, 'public');
        } else {
            return back()->withErrors(['file' => 'File surat wajib diupload.'])->withInput();
        }

        // Buat dokumen baru
        $document = Document::create([
            'title' => $validated['subject'],
            'description' => $validated['suggestion'],
            'sender' => $validated['sender'],
            'document_number' => $validated['document_number'],
            'agenda_number' => $validated['agenda_number'] ?? Document::generateAgendaNumber(),
            'document_date' => $validated['document_date'],
            'subject' => $validated['subject'],
            'suggestion' => $validated['suggestion'],
            'status' => 'menunggu_disposisi',
            'category_id' => $validated['category_id'] ?? null,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'file_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'is_public' => false,
            'created_by' => $user->id,
        ]);

        // NOTIFICATION: Buat notifikasi untuk semua user dengan role 'ketua'
        $ketuaUsers = User::where('sidongan_role', 'ketua')->get();

        foreach ($ketuaUsers as $ketua) {
            Notification::create([
                'user_id' => $ketua->id,
                'type' => 'document.created',
                'title' => 'Surat Masuk Baru',
                // FORMAT BARU: Sesuai contoh Anda
                'message' => "Surat baru dengan No. Agenda {$document->agenda_number} menunggu disposisi",
                'related_id' => $document->id,
                'related_type' => Document::class,
            ]);
        }

        return redirect()->route('sidongan.documents.index')
            ->with('success', 'Surat masuk berhasil disimpan dan dikirim ke Ketua untuk disposisi!');
    }

    public function edit(Document $document)
    {
        $categories = DocumentCategory::where('is_active', true)->orderBy('name')->get();
        return view('sidongan.documents.edit', compact('document', 'categories'));
    }

    public function update(Request $request, Document $document)
    {
        // 1. HANDLE HAPUS FILE
        if ($request->has('delete_file') && $request->delete_file == '1') {
            if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }
            
            $document->update([
                'file_path' => null,
                'file_name' => null,
                'file_type' => null,
                'file_size' => 0,
            ]);

            return back()->with('success', 'File berhasil dihapus!');
        }

        // 2. VALIDASI
        $validated = $request->validate([
            'sender' => 'required|string|max:255',
            'document_number' => 'required|string|max:100',
            'document_date' => 'required|date',
            'subject' => 'required|string|max:255',
            'suggestion' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);

        // 3. HANDLE UPLOAD FILE BARU
        if ($request->hasFile('file')) {
            if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }
            
            $file = $request->file('file');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('sidongan/documents', $filename, 'public');
            
            $document->update([
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
            ]);
        }

        // 4. UPDATE TEXT FIELDS
        $document->update([
            'sender' => $validated['sender'],
            'document_number' => $validated['document_number'],
            'document_date' => $validated['document_date'],
            'subject' => $validated['subject'],
            'suggestion' => $validated['suggestion'] ?? $document->suggestion,
        ]);

        return redirect()->route('sidongan.documents.index')->with('success', 'Dokumen berhasil diperbarui!');
    }

    public function destroy(Document $document)
    {
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }
        $document->delete();
        return redirect()->route('sidongan.documents.index')->with('success', 'Dokumen berhasil dihapus!');
    }

    public function download(Document $document)
    {
        return Storage::disk('public')->download($document->file_path, $document->file_name);
    }

    public function show(Document $document)
    {
        // Load document dengan relasi yang diperlukan
        $document->load(['creator', 'category', 'tags']);
        
        // Ambil activity reports untuk dokumen ini
        $activityReports = \App\Models\ActivityReport::where('document_id', $document->id)
            ->with(['creator'])
            ->latest()
            ->get();
        
        return view('sidongan.documents.show', compact('document', 'activityReports'));
    }

    /**
     * Mark notification as read (AJAX)
     */
    public function markNotificationAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        
        // Hanya update jika belum dibaca
        if (is_null($notification->read_at)) {
            $notification->update(['read_at' => now()]);
        }
        
        return response()->json(['success' => true]);
    }

    /**
     * Halaman Disposisi Surat (untuk Ketua PKK)
     */
    public function disposisi()
    {
        $user = auth()->guard('sidongan')->user();
        
        if (!$user->hasSidonganRole('ketua')) {
            abort(403, 'Akses ditolak');
        }
        
        $documents = Document::with(['category', 'creator'])
            ->where('status', 'menunggu_disposisi')
            ->latest()
            ->paginate(15);
        
        return view('sidongan.disposisi.index', compact('documents'));
    }

    /**
     * Form Disposisi
     */
    public function showDisposisiForm(Document $document)
    {
        $user = auth()->guard('sidongan')->user();
        
        if (!$user->hasSidonganRole('ketua')) {
            abort(403, 'Akses ditolak');
        }
        
        $roles = User::getSidonganRoles();
        unset($roles['bupati'], $roles['ketua'], $roles['sekretaris']); // Exclude certain roles
        
        return view('sidongan.disposisi.form', compact('document', 'roles'));
    }

    /**
     * Store Disposisi
     */
    public function storeDisposisi(Request $request, Document $document)
    {
        $user = auth()->guard('sidongan')->user();
        
        // Cek akses
        if (!$user->hasSidonganRole('ketua')) {
            abort(403, 'Akses ditolak');
        }
        
        // 1. VALIDASI INPUT
        $validated = $request->validate([
            'target_roles' => 'required|array|min:1', // Wajib pilih minimal 1
            'target_roles.*' => 'in:bendahara,pokja1,pokja2,pokja3,pokja4,sekretaris',
            'action' => 'required|string',
            'comment' => 'nullable|string',
        ], [
            'target_roles.required' => 'Anda wajib memilih minimal satu tujuan disposisi.',
            'target_roles.min' => 'Pilih minimal satu tujuan disposisi.',
            'action.required' => 'Tindakan/Instruksi wajib dipilih.',
        ]);
        
        // 2. UPDATE DOKUMEN
        $document->update([
            'status' => 'berjalan', // Ubah status menjadi berjalan
            'disposisi_data' => json_encode([
                'target_roles' => $validated['target_roles'],
                'action' => $validated['action'],
                'comment' => $validated['comment'] ?? null,
                'disposed_by' => $user->id,
                'disposed_at' => now(),
            ])
        ]);
        
        // 3. NOTIFIKASI (Opsional: Beri tahu pihak yang didisposisi)
        $rolesMap = [
            'sekretaris' => 'Sekretaris PKK',
            'bendahara' => 'Bendahara PKK',
            'pokja1' => 'Ketua POKJA 1',
            'pokja2' => 'Ketua POKJA 2',
            'pokja3' => 'Ketua POKJA 3',
            'pokja4' => 'Ketua POKJA 4',
        ];

        foreach ($validated['target_roles'] as $role) {
            // Cari user berdasarkan role
            $targetUser = \App\Models\User::where('sidongan_role', $role)->first();
            
            if ($targetUser) {
                \App\Models\Notification::create([
                    'user_id' => $targetUser->id,
                    'type' => 'disposisi.received',
                    'title' => 'Disposisi Baru',
                    'message' => "Anda menerima disposisi dari Ketua PKK untuk surat {$document->agenda_number}: {$document->subject}. Tindakan: {$validated['action']}",
                    'related_id' => $document->id,
                    'related_type' => \App\Models\Document::class,
                ]);
            }
        }
        
        // 4. REDIRECT KE HALAMAN DISPOSISI DENGAN PESAN SUKSES
        return redirect()->route('sidongan.disposisi')
            ->with('success', 'Disposisi surat berhasil dikirim!');
    }

    /**
     * Halaman Verifikasi Laporan
     */
    public function verifikasi()
    {
        $user = auth()->guard('sidongan')->user();
        
        if (!$user->hasSidonganRole('ketua')) {
            abort(403, 'Akses ditolak');
        }
        
        $documents = Document::with(['category', 'creator'])
            ->where('status', 'menunggu_verifikasi')
            ->latest()
            ->paginate(15);
        
        return view('sidongan.verifikasi.index', compact('documents'));
    }

    /**
     * Verifikasi Laporan
     */
    public function storeVerifikasi(Request $request, Document $document)
    {
        $user = auth()->guard('sidongan')->user();
        
        if (!$user->hasSidonganRole('ketua')) {
            abort(403, 'Akses ditolak');
        }
        
        $validated = $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'comment' => 'nullable|string',
        ]);
        
        $document->update([
            'status' => $validated['status'] === 'disetujui' ? 'selesai' : 'draft',
            'verifikasi_data' => json_encode([
                'status' => $validated['status'],
                'comment' => $validated['comment'] ?? null,
                'verified_by' => $user->id,
                'verified_at' => now(),
            ])
        ]);
        
        return redirect()->route('sidongan.verifikasi')
            ->with('success', 'Verifikasi berhasil disimpan!');
    }

    /**
     * Halaman Arsip Surat
     */
    public function arsip()
    {
        $user = auth()->guard('sidongan')->user();
        
        $query = Document::with(['category', 'creator'])
            ->whereIn('status', ['selesai', 'diarsipkan']);
        
        if ($user->hasSidonganRole('sekretaris')) {
            $query->where('created_by', $user->id);
        }
        
        $documents = $query->latest()->paginate(15);
        
        return view('sidongan.arsip.index', compact('documents'));
    }

    /**
     * Halaman Notifikasi
     */
    public function notifications()
    {
        $user = auth()->guard('sidongan')->user();
        
        $notifications = Notification::where('user_id', $user->id)
            ->latest()
            ->paginate(15);

        // Kirim $unreadCount ke view
        $unreadCount = Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->count();

        return view('sidongan.notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Mark all notifications as read
     */
    public function markAllNotificationsAsRead()
    {
        $user = auth()->guard('sidongan')->user();
        
        if ($user) {
            // Update semua notifikasi user menjadi sudah dibaca
            $count = Notification::where('user_id', $user->id)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
            
            return response()->json([
                'success' => true,
                'message' => "{$count} notifikasi ditandai sebagai sudah dibaca",
                'count' => $count
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'User tidak ditemukan'
        ], 404);
    }
}