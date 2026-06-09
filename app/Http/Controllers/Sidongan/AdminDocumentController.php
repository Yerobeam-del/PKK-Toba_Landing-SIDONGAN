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

        if (!$user) {
            return redirect()->route('sidongan.login');
        }
        
        $statsQuery = Document::query();
        
        if ($user->hasSidonganRole('sekretaris')) {
            $statsQuery->where('created_by', $user->id);
        }
        
        // ✅ RECENT DOCUMENTS - Dengan sorting prioritas status
        $recentDocuments = (clone $statsQuery)
            ->with(['creator', 'activityReports' => function($q) {
                $q->with('creator')->latest();
            }])
            ->orderByRaw("
                CASE 
                    WHEN status = 'menunggu_disposisi' THEN 1
                    WHEN status = 'berjalan' THEN 2
                    WHEN status = 'menunggu_verifikasi' THEN 3
                    WHEN status = 'selesai' THEN 4
                    WHEN status = 'diarsipkan' THEN 5
                    ELSE 6
                END
            ")
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // NOTIFIKASI: HANYA yang BELUM DIBACA
        $notifications = Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->latest()
            ->take(5)
            ->get();

        $unreadCount = Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->count();

        return view('sidongan.dashboard', [
            'totalSurat' => (clone $statsQuery)->count(),
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
        $query = Document::with(['category', 'creator', 'activityReports' => function($q) {
            $q->with('creator')->latest();
        }]);
        
        // Filter Role
        if ($user->hasSidonganRole('sekretaris')) {
            $query->where('created_by', $user->id);
        }

        // Hitung Total Dokumen
        $totalDocuments = (clone $query)->count();
        
        // Hitung Stats
        $statSelesai = (clone $query)->where('status', 'selesai')->count();
        $statBerjalan = (clone $query)->where('status', 'berjalan')->count();
        $statMenungguDisposisi = (clone $query)->where('status', 'menunggu_disposisi')->count();
        $statMenungguVerifikasi = (clone $query)->where('status', 'menunggu_verifikasi')->count();
        
        // 2. Filter Pencarian
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        
        // 3. Filter Status & Kategori
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 4. SORTING CUSTOM
        $query->orderByRaw("
            CASE 
                WHEN status = 'menunggu_disposisi' THEN 1
                WHEN status = 'berjalan' THEN 2
                WHEN status = 'menunggu_verifikasi' THEN 3
                WHEN status = 'selesai' THEN 4
                WHEN status = 'diarsipkan' THEN 5
                ELSE 6
            END
        ");
        
        $query->orderBy('created_at', 'desc');

        // 5. PAGINATION DENGAN PER PAGE DYNAMIC
        $perPage = $request->get('per_page', 10); // Default 10
        $allowedPerPages = [5, 10, 15, 25, 50];
        if (!in_array($perPage, $allowedPerPages)) {
            $perPage = 10;
        }

        $documents = $query->paginate($perPage)->withQueryString();
        $categories = DocumentCategory::where('is_active', true)->orderBy('name')->get();

        return view('sidongan.documents.index', [
            'documents' => $documents,
            'categories' => $categories,
            'totalDocuments' => $totalDocuments,
            'statSelesai' => $statSelesai,
            'statBerjalan' => $statBerjalan,
            'statMenungguDisposisi' => $statMenungguDisposisi,
            'statMenungguVerifikasi' => $statMenungguVerifikasi,
            'currentPerPage' => $perPage,
            'allowedPerPages' => $allowedPerPages, // ✅ TAMBAHKAN INI
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
        $user = auth()->guard('sidongan')->user();

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

        // 2. Handle archive
        if ($request->has('archive') && $request->archive === '1') {
            if (!$user || !$user->hasSidonganRole('sekretaris')) {
                abort(403, 'Akses ditolak.');
            }
            
            if ($document->status !== 'selesai') {
                return back()->with('error', 'Hanya dokumen yang sudah selesai yang dapat diarsipkan.');
            }
            
            $document->update([
                'status' => 'diarsipkan',
                'updated_by' => $user->id,
            ]);
            
            return redirect()->route('sidongan.documents.show', $document)
                ->with('success', 'Surat berhasil diarsipkan!');
        }

        // 3. VALIDASI
        $validated = $request->validate([
            'sender' => 'required|string|max:255',
            'document_number' => 'required|string|max:100',
            'document_date' => 'required|date',
            'subject' => 'required|string|max:255',
            'suggestion' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);

        // 4. HANDLE UPLOAD FILE BARU
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

        // 5. UPDATE TEXT FIELDS
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
     * Mark notification as read + AUTO DELETE (AJAX)
     */
    public function markNotificationAsRead($id)
    {
        $user = auth()->guard('sidongan')->user();
        
        // Cari notifikasi milik user ini
        $notification = Notification::where('user_id', $user->id)->findOrFail($id);
        
        // ✅ LANGSUNG HAPUS (karena sudah "dibaca")
        $notification->delete();
        
        return response()->json([
            'success' => true, 
            'message' => 'Notifikasi dihapus'
        ]);
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
        // Exclude ketua (tidak bisa disposisi ke diri sendiri) dan sekretaris (karena sekretaris yang upload)
        unset($roles['ketua']);
        unset($roles['sekretaris']);
        
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
            'target_roles' => 'required|array|min:1',
            'target_roles.*' => 'in:bendahara,pengurus_1,pengurus_2,pengurus_3,pengurus_4,sekretaris,staf_ahli_1,staf_ahli_2',
            'action' => 'required|string',
            'custom_action' => 'nullable|string|max:255',
            'comment' => 'nullable|string',
        ], [
            'target_roles.required' => 'Anda wajib memilih minimal satu tujuan disposisi.',
            'target_roles.min' => 'Pilih minimal satu tujuan disposisi.',
            'action.required' => 'Tindakan/Instruksi wajib dipilih.',
        ]);
        
        // 2. TENTUKAN TINDAKAN FINAL
        $finalAction = $validated['action'];
        
        // Jika "Lainnya" dipilih, gunakan custom_action
        if ($validated['action'] === 'Lainnya') {
            if (empty(trim($validated['custom_action'] ?? ''))) {
                return back()->withErrors(['custom_action' => 'Tindakan/Instruksi lainnya wajib diisi.'])->withInput();
            }
            $finalAction = trim($validated['custom_action']);
        }
        
        // 3. UPDATE DOKUMEN
        $document->update([
            'status' => 'berjalan',
            'disposisi_data' => json_encode([
                'target_roles' => $validated['target_roles'],
                'action' => $finalAction,
                'action_type' => $validated['action'], // Simpan tipe: 'Lainnya' atau pilihan standar
                'comment' => $validated['comment'] ?? null,
                'disposed_by' => $user->id,
                'disposed_at' => now(),
            ])
        ]);
        
        // 4. NOTIFIKASI
        $rolesMap = [
            'sekretaris' => 'Sekretaris PKK',
            'bendahara' => 'Bendahara PKK',
            'staf_ahli_1' => 'Staf Ahli I',
            'staf_ahli_2' => 'Staf Ahli II',
            'pengurus_1' => 'Ketua Pengurus I',
            'pengurus_2' => 'Ketua Pengurus II',
            'pengurus_3' => 'Ketua Pengurus III',
            'pengurus_4' => 'Ketua Pengurus IV',
        ];

        foreach ($validated['target_roles'] as $role) {
            $targetUser = \App\Models\User::where('sidongan_role', $role)->first();
            
            if ($targetUser) {
                \App\Models\Notification::create([
                    'user_id' => $targetUser->id,
                    'type' => 'disposisi.received',
                    'title' => 'Disposisi Baru',
                    'message' => "Anda menerima disposisi dari Ketua PKK untuk surat {$document->agenda_number}: {$document->subject}. Tindakan: {$finalAction}",
                    'related_id' => $document->id,
                    'related_type' => \App\Models\Document::class,
                ]);
            }
        }
        
        // 5. REDIRECT
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
        
        // HANYA ambil surat yang sudah diarsipkan (status = 'diarsipkan')
        $query = Document::with(['category', 'creator', 'activityReports' => function($q) {
            $q->with('creator')->latest();
        }])
        ->where('status', 'diarsipkan');
        
        // Filter
        if (request('search')) {
            $query->search(request('search'));
        }
        if (request('category')) {
            $query->where('category_id', request('category'));
        }
        if (request('year')) {
            $query->whereYear('document_date', request('year'));
        }
        
        $documents = $query->latest()->paginate(15);
        
        // Stats
        $totalArsip = (clone $query)->count();
        $arsipBulanIni = (clone $query)->whereMonth('created_at', now()->month)->count();
        $arsipTahunIni = (clone $query)->whereYear('created_at', now()->year)->count();
        
        $categories = DocumentCategory::where('is_active', true)->orderBy('name')->get();
        
        return view('sidongan.arsip.index', compact(
            'documents', 
            'totalArsip', 
            'arsipBulanIni', 
            'arsipTahunIni',
            'categories'
        ));
    }

    /**
     * Archive document (hanya untuk Sekretaris)
     */
    public function archive(Document $document)
    {
        $user = auth()->guard('sidongan')->user();
        
        // Cek akses - hanya Sekretaris yang bisa archive
        if (!$user || !$user->hasSidonganRole('sekretaris')) {
            abort(403, 'Akses ditolak. Hanya Sekretaris yang dapat mengarsipkan surat.');
        }
        
        // Hanya dokumen yang statusnya 'selesai' yang bisa diarsipkan
        if ($document->status !== 'selesai') {
            return back()->with('error', 'Hanya dokumen yang sudah selesai yang dapat diarsipkan.');
        }
        
        // Update status menjadi diarsipkan
        $document->update([
            'status' => 'diarsipkan',
            'updated_by' => $user->id,
        ]);
        
        // Buat notifikasi
        \App\Models\Notification::create([
            'user_id' => $user->id,
            'type' => 'document.archived',
            'title' => 'Surat Diarsipkan',
            'message' => "Surat {$document->agenda_number} berhasil diarsipkan.",
            'related_id' => $document->id,
            'related_type' => Document::class,
        ]);
        
        return redirect()->route('sidongan.documents.show', $document)
            ->with('success', 'Surat berhasil diarsipkan!');
    }

    /**
     * Print Lembar Disposisi
     */
    public function printDisposisi(Document $document)
    {
        return view('sidongan.documents.disposisi-print', compact('document'));
    }

    /**
     * Halaman Notifikasi - HANYA tampilkan yang belum dibaca
     */
    public function notifications()
    {
        $user = auth()->guard('sidongan')->user();
        
        // ✅ HANYA ambil notifikasi yang BELUM dibaca (read_at = null)
        $notifications = Notification::where('user_id', $user->id)
            ->whereNull('read_at')  // ← Filter hanya yang belum dibaca
            ->latest()
            ->paginate(15);

        // Count = total yang ditampilkan (karena hanya unread)
        $unreadCount = $notifications->total();

        return view('sidongan.notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Mark ALL notifications as read + AUTO DELETE ALL
     */
    public function markAllNotificationsAsRead()
    {
        $user = auth()->guard('sidongan')->user();
        
        if ($user) {
            // ✅ HAPUS SEMUA notifikasi user ini yang belum dibaca
            $count = Notification::where('user_id', $user->id)
                ->whereNull('read_at')  // Hanya yang belum dibaca
                ->delete();             // Langsung hapus
            
            return response()->json([
                'success' => true,
                'message' => "{$count} notifikasi dihapus",
                'count' => $count
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'User tidak ditemukan'
        ], 404);
    }
}