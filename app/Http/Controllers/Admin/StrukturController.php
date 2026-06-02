<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pokja;
use App\Models\StrukturMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class StrukturController extends Controller
{
    protected $sortMap = [
        'Ketua Pembina' => 1, 'Ketua TP PKK' => 2, 'Staf Ahli' => 3,
        'Sekretaris' => 4, 'Bendahara' => 5,
        'Ketua I' => 6, 'Ketua II' => 7, 'Ketua III' => 8, 'Ketua IV' => 9,
        'Ketua' => 10, 'Wakil Ketua' => 11, 'Sekretaris Pokja' => 12, 'Anggota' => 13
    ];

    public function index()
    {
        $pengurusInti = StrukturMember::whereNull('pokja_id')
            ->orderByRaw("CASE position 
                WHEN 'Ketua Pembina' THEN 1 WHEN 'Ketua TP PKK' THEN 2 WHEN 'Staf Ahli' THEN 3
                WHEN 'Sekretaris' THEN 4 WHEN 'Bendahara' THEN 5 WHEN 'Ketua I' THEN 6
                WHEN 'Ketua II' THEN 7 WHEN 'Ketua III' THEN 8 WHEN 'Ketua IV' THEN 9 ELSE 99 END")
            ->get();
            
        $pokjaList = Pokja::withCount('members')->orderBy('id')->get();
        
        return view('admin.struktur.index', compact('pengurusInti', 'pokjaList'));
    }

    public function create()
    {
        return view('admin.struktur.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'group' => 'required|in:pengurus,pokja1,pokja2,pokja3,pokja4',
            'position' => 'required|string|max:50',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'photo' => 'nullable|image|max:2048',
            'cropped_photo' => 'nullable|string',
        ]);

        // === HANDLE PHOTO UPLOAD (FIXED LOGIC) ===
        $photoPath = null;
        
        // 1. Prioritaskan cropped photo (base64)
        if (!empty($validated['cropped_photo'])) {
            $photoPath = $this->saveCroppedPhoto($validated['cropped_photo']);
        } 
        // 2. Jika tidak ada cropped, cek regular file upload
        elseif ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $photoPath = $request->file('photo')->store('struktur', 'public');
        }
        // ==========================================

        // Map group to pokja_id
        $pokjaId = $validated['group'] === 'pengurus' ? null : (int)str_replace('pokja', '', $validated['group']);
        
        // Auto sort_order
        $sortOrder = $this->sortMap[$validated['position']] ?? 99;
        
        // Normalisasi jabatan
        $position = $validated['position'];
        if ($pokjaId && $position === 'Sekretaris') {
            $position = 'Sekretaris Pokja';
        }

        // Create dengan photo_path yang sudah diproses
        StrukturMember::create([
            'pokja_id' => $pokjaId,
            'position' => $position,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'sort_order' => $sortOrder,
            'photo_path' => $photoPath, // <-- INI YANG SEBELUMNYA HILANG
        ]);

        return redirect()->route('admin.struktur.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit(StrukturMember $struktur)
    {
        return view('admin.struktur.edit', compact('struktur'));
    }

    public function update(Request $request, StrukturMember $struktur)
    {
        $validated = $request->validate([
            'group' => 'required|in:pengurus,pokja1,pokja2,pokja3,pokja4',
            'position' => 'required|string|max:50',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'photo' => 'nullable|image|max:2048',
            'cropped_photo' => 'nullable|string',
        ]);

        // === HANDLE PHOTO UPDATE (FIXED LOGIC) ===
        $photoPath = $struktur->photo_path; // Keep existing by default
        
        // 1. Jika ada cropped photo baru
        if (!empty($validated['cropped_photo'])) {
            // Hapus foto lama
            if ($photoPath) Storage::disk('public')->delete($photoPath);
            $photoPath = $this->saveCroppedPhoto($validated['cropped_photo']);
        } 
        // 2. Jika ada regular file upload baru
        elseif ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            // Hapus foto lama
            if ($photoPath) Storage::disk('public')->delete($photoPath);
            $photoPath = $request->file('photo')->store('struktur', 'public');
        }
        // ==========================================

        $pokjaId = $validated['group'] === 'pengurus' ? null : (int)str_replace('pokja', '', $validated['group']);
        $sortOrder = $this->sortMap[$validated['position']] ?? 99;
        
        $position = $validated['position'];
        if ($pokjaId && $position === 'Sekretaris') {
            $position = 'Sekretaris Pokja';
        }

        $struktur->update([
            'pokja_id' => $pokjaId,
            'position' => $position,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'sort_order' => $sortOrder,
            'photo_path' => $photoPath, // <-- INI YANG SEBELUMNYA HILANG
        ]);

        return redirect()->route('admin.struktur.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(StrukturMember $struktur)
    {
        if ($struktur->photo_path) Storage::disk('public')->delete($struktur->photo_path);
        $struktur->delete();
        return redirect()->route('admin.struktur.index')->with('success', 'Data berhasil dihapus.');
    }

    /**
     * Helper: Simpan foto base64 hasil crop
     */
    private function saveCroppedPhoto($base64Image)
    {
        try {
            // Remove data:image/jpeg;base64, prefix
            if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
                $base64Image = substr($base64Image, strlen($type[0]));
            }
            
            // Decode base64
            $imageData = base64_decode($base64Image);
            
            if ($imageData === false) {
                Log::error('Failed to decode base64 image');
                return null;
            }
            
            // Generate unique filename
            $imageName = 'struktur_' . time() . '_' . uniqid() . '.jpg';
            $path = 'struktur/' . $imageName;
            
            // Save to storage
            Storage::disk('public')->put($path, $imageData);
            
            Log::info('Cropped photo saved: ' . $path);
            return $path;
            
        } catch (\Exception $e) {
            Log::error('Error saving cropped photo: ' . $e->getMessage());
            return null;
        }
    }
}