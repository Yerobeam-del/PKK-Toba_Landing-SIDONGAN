<?php

namespace App\Http\Controllers\Sidongan;

use App\Http\Controllers\Controller;
use App\Models\ActivityReport;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    /**
     * Display a listing of reports waiting for verification.
     */
    public function index(Request $request)
    {
        $user = auth()->guard('sidongan')->user();
        
        // Cek akses (Hanya Ketua)
        if (!$user->hasSidonganRole('ketua')) {
            abort(403, 'Akses ditolak');
        }
        
        // 1. Query Dasar
        $query = \App\Models\ActivityReport::with(['document', 'creator']);
        
        // 2. Filter Pencarian
        if ($request->filled('search')) {
            $query->where('kegiatan_nama', 'like', '%' . $request->search . '%');
        }
        
        // 3. Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Default: hanya tampilkan yang menunggu verifikasi
            $query->where('status', 'menunggu_verifikasi');
        }
        
        // 4. Eksekusi Query
        $documents = $query->latest()->paginate(10);
        
        // 5. Append query parameters ke pagination links
        $documents->appends($request->except('page'));
        
        // 6. Kirim ke View
        return view('sidongan.verifikasi.index', compact('documents'));
    }
    
    /**
     * Show verification form for a specific report.
     */
    public function form($id)
    {
        $user = auth()->guard('sidongan')->user();
        
        if (!$user->hasSidonganRole('ketua')) {
            abort(403, 'Akses ditolak');
        }
        
        $report = ActivityReport::with(['document', 'creator'])->findOrFail($id);
        
        return view('sidongan.verifikasi.form', compact('report'));
    }
    
    /**
     * Process verification (approve/reject).
     */
    public function store(Request $request, $id)
    {
        $user = auth()->guard('sidongan')->user();
        
        if (!$user->hasSidonganRole('ketua')) {
            abort(403, 'Akses ditolak');
        }
        
        $validated = $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'catatan_verifikasi' => 'nullable|string|max:500',
        ]);
        
        $report = ActivityReport::findOrFail($id);
        $report->update([
            'status' => $validated['status'],
            'catatan_verifikasi' => $validated['catatan_verifikasi'] ?? null,
            'verified_by' => $user->id,
            'verified_at' => now(),
        ]);
        
        // Update status surat jika disetujui
        if ($validated['status'] === 'disetujui' && $report->document) {
            $report->document->update(['status' => 'selesai']);
        }
        
        return redirect()->route('sidongan.verifikasi')
            ->with('success', 'Verifikasi berhasil disimpan!');
    }
}