<?php

namespace App\Http\Controllers\Sidongan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->guard('sidongan')->user();
        
        // 1. Data untuk List Card (Surat yang harus dilapor)
        $documents = \App\Models\Document::whereIn('status', ['berjalan', 'menunggu_verifikasi'])
            ->where(function($query) use ($user) {
                $query->whereJsonContains('disposisi_data->target_roles', 'sekretaris')
                    ->orWhere('created_by', $user->id);
            })
            ->with(['creator'])
            ->latest()
            ->get();

        // 2. Stats: Laporan yang sudah dibuat (Submitted)
        $totalLaporan = \App\Models\ActivityReport::where('created_by', $user->id)->count();
        $menungguVerifikasi = \App\Models\ActivityReport::where('created_by', $user->id)
            ->where('status', 'menunggu_verifikasi')->count();
        $disetujui = \App\Models\ActivityReport::where('created_by', $user->id)
            ->where('status', 'disetujui')->count();
        $ditolak = \App\Models\ActivityReport::where('created_by', $user->id)
            ->where('status', 'ditolak')->count();
        
        // 3. Stats: Pekerjaan yang belum selesai (Perlu Dilaporkan)
        $perluDilaporkan = $documents->count();

        return view('sidongan.lapor-kegiatan.index', compact(
            'user',
            'documents',
            'totalLaporan',
            'menungguVerifikasi',
            'disetujui',
            'ditolak',
            'perluDilaporkan' // Kirim variabel baru ini
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($document_id = null)
    {
        $document = null;
        
        if ($document_id) {
            $document = \App\Models\Document::findOrFail($document_id);
            
            // Cek apakah user berhak membuat laporan untuk surat ini
            $user = auth()->guard('sidongan')->user();
            $dispo = is_string($document->disposisi_data) 
                ? json_decode($document->disposisi_data, true) 
                : $document->disposisi_data;
            
            if (!in_array($user->sidongan_role, $dispo['target_roles'] ?? []) && $document->created_by != $user->id) {
                abort(403, 'Anda tidak berhak membuat laporan untuk surat ini');
            }
        }
        
        return view('sidongan.lapor-kegiatan.create', compact('document'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'document_id' => 'required|exists:sidongan_documents,id',
            'kegiatan_nama' => 'required|string|max:255',
            'kegiatan_tanggal' => 'required|date',
            'deskripsi' => 'required|string',
            'lokasi' => 'nullable|string|max:255',
            'fotos.*' => 'nullable|file|mimes:jpeg,png,jpg,heic|max:5120',
        ]);

        // 1. Handle Upload Foto
        $fotoPaths = [];
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $foto) {
                $filename = time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
                $path = $foto->storeAs('activity-reports', $filename, 'public');
                $fotoPaths[] = $path;
            }
        }

        // 2. SIMPAN LAPORAN
        \App\Models\ActivityReport::create([
            'document_id'    => $validated['document_id'],
            'created_by'     => auth()->guard('sidongan')->id(),
            'kegiatan_nama'  => $validated['kegiatan_nama'],
            'kegiatan_tanggal' => $validated['kegiatan_tanggal'],
            'deskripsi'      => $validated['deskripsi'],
            'lokasi'         => $validated['lokasi'] ?? null,
            'fotos'          => json_encode($fotoPaths),
            'status'         => 'menunggu_verifikasi',
        ]);

        // 3. UPDATE STATUS SURAT INDUK MENJADI 'MENUNGGU VERIFIKASI'
        $document = \App\Models\Document::find($validated['document_id']);
        if ($document) {
            $document->update([
                'status' => 'menunggu_verifikasi'
            ]);
        }

        // 4. Redirect
        return redirect()->route('sidongan.lapor_kegiatan.index')
            ->with('success', 'Laporan kegiatan berhasil dikirim dan menunggu verifikasi!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Ambil laporan dengan relasi document dan creator
        $report = \App\Models\ActivityReport::with(['document', 'creator'])
            ->findOrFail($id);
        
        return view('sidongan.lapor-kegiatan.show', compact('report'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Nanti ambil dari database
        // $report = ActivityReport::findOrFail($id);
        return view('sidongan.lapor-kegiatan.edit', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'kegiatan_nama' => 'required|string|max:255',
            'kegiatan_tanggal' => 'required|date',
            'deskripsi' => 'required|string',
            'lokasi' => 'nullable|string|max:255',
        ]);

        // Update database (nanti)
        // $report = ActivityReport::findOrFail($id);
        // $report->update($validated);

        return redirect()->route('sidongan.lapor_kegiatan.index')
            ->with('success', 'Laporan kegiatan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Delete dari database (nanti)
        // $report = ActivityReport::findOrFail($id);
        // $report->delete();

        return redirect()->route('sidongan.lapor_kegiatan.index')
            ->with('success', 'Laporan kegiatan berhasil dihapus!');
    }
}