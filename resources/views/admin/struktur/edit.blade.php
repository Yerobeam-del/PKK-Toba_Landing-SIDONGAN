@extends('admin.layouts.app')
@section('title', 'Edit Anggota Struktur')
@section('page-title', 'Edit Anggota')

@section('content')

{{-- Header --}}
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
    <div>
        <h1 style="font-size:1.5rem;font-weight:800;color:var(--text-dark);margin:0 0 0.25rem 0">Edit Anggota</h1>
        <p style="color:var(--text-muted);margin:0;font-size:0.9rem">Perbarui data anggota struktur organisasi</p>
    </div>
    <a href="{{ route('admin.struktur.index') }}" class="btn" style="background:#f8fafc;color:var(--text-dark)">← Kembali</a>
</div>

{{-- Form Card --}}
<div class="card">
    <form action="{{ route('admin.struktur.update', $struktur) }}" method="POST" enctype="multipart/form-data" id="editForm">
        @csrf
        @method('PUT')
        
        @php
            $currentGroup = is_null($struktur->pokja_id) ? 'pengurus' : 'pokja' . $struktur->pokja_id;
            $currentPosition = $struktur->position;
        @endphp

        {{-- Group & Position Row --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:1.5rem">
            <div>
                <label style="font-weight:600;display:block;margin-bottom:0.5rem;font-size:0.9rem">1. Kelompok *</label>
                <select name="group" id="groupSelect" class="form-control" required onchange="updatePositions()">
                    <option value="">-- Pilih Kelompok --</option>
                    <option value="pengurus" {{ $currentGroup == 'pengurus' ? 'selected' : '' }}>Pengurus Inti</option>
                    <option value="pokja1" {{ $currentGroup == 'pokja1' ? 'selected' : '' }}>Pokja I</option>
                    <option value="pokja2" {{ $currentGroup == 'pokja2' ? 'selected' : '' }}>Pokja II</option>
                    <option value="pokja3" {{ $currentGroup == 'pokja3' ? 'selected' : '' }}>Pokja III</option>
                    <option value="pokja4" {{ $currentGroup == 'pokja4' ? 'selected' : '' }}>Pokja IV</option>
                </select>
                <small style="color:var(--text-muted);display:block;margin-top:0.4rem;font-size:0.8rem">Menentukan bagian struktur tempat anggota ini muncul</small>
            </div>
            
            <div>
                <label style="font-weight:600;display:block;margin-bottom:0.5rem;font-size:0.9rem">2. Jabatan *</label>
                <select name="position" id="positionSelect" class="form-control" required>
                    <option value="">-- Pilih Kelompok Dulu --</option>
                </select>
                <small style="color:var(--text-muted);display:block;margin-top:0.4rem;font-size:0.8rem">Opsi jabatan menyesuaikan kelompok yang dipilih</small>
            </div>
        </div>

        {{-- Name --}}
        <div style="margin-bottom:1.5rem">
            <label style="font-weight:600;display:block;margin-bottom:0.5rem;font-size:0.9rem">3. Nama Lengkap *</label>
            <input type="text" name="name" class="form-control" 
                   value="{{ old('name', $struktur->name) }}" 
                   required 
                   placeholder="Contoh: INDAH KARUNIA PRATIWI SITUMEANG, SH">
        </div>

        {{-- Description --}}
        <div style="margin-bottom:1.5rem">
            <label style="font-weight:600;display:block;margin-bottom:0.5rem;font-size:0.9rem">Deskripsi / Catatan (Opsional)</label>
            <textarea name="description" class="form-control" rows="3" placeholder="Misal: NIP, riwayat singkat, atau catatan internal">{{ old('description', $struktur->description) }}</textarea>
        </div>

        {{-- Photo Upload with Crop --}}
        <div style="margin-bottom:2rem">
            <label style="font-weight:600;display:block;margin-bottom:0.5rem;font-size:0.9rem">Foto Anggota</label>
            <input type="file" id="photoInput" class="form-control" accept="image/*" onchange="handlePhotoUpload(event)">
            <small style="color:var(--text-muted);display:block;margin-top:0.4rem">JPG/PNG, maksimal 2MB. Klik foto untuk mengatur crop.</small>
            
            {{-- Preview Container --}}
            <div id="previewContainer" style="margin-top:1rem;">
                <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1rem">
                    <img id="photoPreview" 
                         src="{{ $struktur->photo_path ? asset('storage/'.$struktur->photo_path) : '#' }}" 
                         style="width:80px;height:80px;border-radius:12px;object-fit:cover;background:#f8fafc;cursor:pointer;{{ !$struktur->photo_path ? 'display:none' : '' }}" 
                         onclick="openCropModal()">
                    <div>
                        <div style="font-weight:600;font-size:0.9rem">{{ $struktur->photo_path ? 'Foto saat ini' : 'Belum ada foto' }}</div>
                        <div style="font-size:0.85rem;color:var(--text-muted)">Klik foto untuk atur crop</div>
                    </div>
                    @if($struktur->photo_path)
                    <button type="button" onclick="removePhoto()" style="margin-left:auto;background:#fef2f2;color:#ef4444;border:none;padding:0.5rem 1rem;border-radius:6px;cursor:pointer;font-size:0.85rem">Hapus</button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Hidden input for cropped image --}}
        <input type="hidden" name="cropped_photo" id="croppedPhoto">

        {{-- Action Buttons --}}
        <div style="display:flex;gap:0.75rem;justify-content:flex-end;padding-top:1rem;border-top:1px solid rgba(0,0,0,0.04)">
            <a href="{{ route('admin.struktur.index') }}" class="btn" style="background:#f8fafc;color:var(--text-dark)">Batal</a>
            <button type="submit" class="btn btn-primary">Update Data</button>
        </div>
    </form>
</div>

{{-- Crop Modal --}}
<div id="cropModal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.8);z-index:2000;align-items:center;justify-content:center;padding:1rem">
    <div style="background:#fff;border-radius:16px;max-width:700px;width:100%;max-height:90vh;overflow-y:auto">
        <div style="padding:1.25rem 1.5rem;border-bottom:1px solid rgba(0,0,0,0.06);display:flex;justify-content:space-between;align-items:center">
            <h3 style="margin:0;font-size:1.1rem;font-weight:700">Atur Foto Profil</h3>
            <button onclick="closeCropModal()" style="background:none;border:none;font-size:1.5rem;cursor:pointer;color:var(--text-muted)">&times;</button>
        </div>
        <div style="padding:1.5rem">
            <div style="background:#f8fafc;border-radius:12px;overflow:hidden;position:relative;min-height:300px">
                <img id="cropImage" style="max-width:100%;display:block">
            </div>
            <div style="margin-top:1rem;display:flex;gap:0.5rem;justify-content:center;flex-wrap:wrap">
                <button type="button" onclick="rotateImage(-90)" class="btn" style="background:#f8fafc">↺ Putar Kiri</button>
                <button type="button" onclick="rotateImage(90)" class="btn" style="background:#f8fafc">Putar Kanan ↻</button>
                <button type="button" onclick="resetCrop()" class="btn" style="background:#f8fafc">Reset</button>
            </div>
            <div style="margin-top:0.5rem;text-align:center;font-size:0.85rem;color:var(--text-muted)">
                Drag untuk geser, scroll untuk zoom
            </div>
        </div>
        <div style="padding:1.25rem 1.5rem;border-top:1px solid rgba(0,0,0,0.06);display:flex;gap:0.75rem;justify-content:flex-end">
            <button type="button" onclick="closeCropModal()" class="btn" style="background:#f8fafc;color:var(--text-dark)">Batal</button>
            <button type="button" onclick="applyCrop()" class="btn btn-primary">Terapkan Crop</button>
        </div>
    </div>
</div>

<script>
// Cropper variables
let cropper = null;
let originalFile = null;
let existingPhotoUrl = '{{ $struktur->photo_path ? asset("storage/".$struktur->photo_path) : "" }}';

const positions = {
    pengurus: ['Ketua Pembina', 'Ketua TP PKK', 'Staf Ahli', 'Sekretaris', 'Bendahara', 'Ketua I', 'Ketua II', 'Ketua III', 'Ketua IV'],
    pokja1: ['Ketua', 'Wakil Ketua', 'Sekretaris', 'Anggota'],
    pokja2: ['Ketua', 'Wakil Ketua', 'Sekretaris', 'Anggota'],
    pokja3: ['Ketua', 'Wakil Ketua', 'Sekretaris', 'Anggota'],
    pokja4: ['Ketua', 'Wakil Ketua', 'Sekretaris', 'Anggota']
};

function updatePositions() {
    const group = document.getElementById('groupSelect').value;
    const posSelect = document.getElementById('positionSelect');
    posSelect.innerHTML = '<option value="">-- Pilih Jabatan --</option>';
    
    if (group && positions[group]) {
        positions[group].forEach(pos => {
            const opt = document.createElement('option');
            opt.value = pos;
            opt.textContent = pos;
            posSelect.appendChild(opt);
        });
    }
}

// Handle photo upload (NEW file)
function handlePhotoUpload(event) {
    const file = event.target.files[0];
    if (!file) return;
    
    if (file.size > 2 * 1024 * 1024) {
        alert('Ukuran foto terlalu besar. Maksimal 2MB.');
        event.target.value = '';
        return;
    }
    
    originalFile = file;
    const reader = new FileReader();
    reader.onload = function(e) {
        const preview = document.getElementById('photoPreview');
        preview.src = e.target.result;
        preview.style.display = 'block';
        
        // Update text
        const textDiv = preview.nextElementSibling;
        textDiv.querySelector('div:first-child').textContent = 'Foto dipilih';
        
        // Add remove button if not exists
        if (!document.querySelector('button[onclick="removePhoto()"]')) {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.onclick = removePhoto;
            btn.style.cssText = 'margin-left:auto;background:#fef2f2;color:#ef4444;border:none;padding:0.5rem 1rem;border-radius:6px;cursor:pointer;font-size:0.85rem';
            btn.textContent = 'Hapus';
            textDiv.parentElement.appendChild(btn);
        }
    };
    reader.readAsDataURL(file);
}

// Remove photo
function removePhoto() {
    document.getElementById('photoInput').value = '';
    const preview = document.getElementById('photoPreview');
    preview.style.display = 'none';
    preview.src = '#';
    document.getElementById('croppedPhoto').value = '';
    originalFile = null;
    
    // Reset text
    const textDiv = preview.nextElementSibling;
    textDiv.querySelector('div:first-child').textContent = 'Belum ada foto';
    
    // Remove button
    const btn = document.querySelector('button[onclick="removePhoto()"]');
    if (btn) btn.remove();
    
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
}

// Open crop modal - FIXED to handle existing photo
function openCropModal() {
    console.log('Opening crop modal...');
    console.log('Existing photo URL:', existingPhotoUrl);
    console.log('Original file:', originalFile);
    
    // Check if there's a photo to crop
    const hasExistingPhoto = existingPhotoUrl && existingPhotoUrl !== '#' && existingPhotoUrl !== '';
    const hasNewFile = originalFile !== null;
    
    if (!hasExistingPhoto && !hasNewFile) {
        alert('Silakan upload foto terlebih dahulu.');
        return;
    }
    
    if (typeof Cropper === 'undefined') {
        alert('Cropper.js belum ter-load. Silakan refresh halaman.');
        console.error('Cropper.js is not loaded!');
        return;
    }
    
    // Determine which image to use
    let imageSource;
    if (hasNewFile && originalFile) {
        // Use newly uploaded file
        const reader = new FileReader();
        reader.onload = function(e) {
            initializeCropper(e.target.result);
        };
        reader.readAsDataURL(originalFile);
    } else if (hasExistingPhoto) {
        // Use existing photo from server
        initializeCropper(existingPhotoUrl);
    }
}

// Initialize cropper (helper function)
function initializeCropper(imageSrc) {
    const cropImage = document.getElementById('cropImage');
    cropImage.src = imageSrc;
    
    // Show modal
    document.getElementById('cropModal').style.display = 'flex';
    
    // Destroy existing cropper
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
    
    // Initialize cropper after image loads
    cropImage.onload = function() {
        try {
            cropper = new Cropper(cropImage, {
                aspectRatio: 1,
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 0.8,
                restore: false,
                guides: true,
                center: true,
                highlight: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false,
                minContainerWidth: 300,
                minContainerHeight: 300
            });
            console.log('Cropper initialized successfully');
        } catch (error) {
            console.error('Error initializing cropper:', error);
            alert('Gagal menginisialisasi crop tool.');
        }
    };
}

// Close crop modal
function closeCropModal() {
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
    document.getElementById('cropModal').style.display = 'none';
}

// Rotate image
function rotateImage(degrees) {
    if (cropper) cropper.rotate(degrees);
}

// Reset crop
function resetCrop() {
    if (cropper) cropper.reset();
}

// Apply crop
function applyCrop() {
    if (!cropper) {
        alert('Crop tool belum siap. Silakan coba lagi.');
        return;
    }
    
    try {
        const canvas = cropper.getCroppedCanvas({
            width: 400,
            height: 400,
            imageSmoothingQuality: 'high',
            fillColor: '#fff'
        });
        
        const croppedDataUrl = canvas.toDataURL('image/jpeg', 0.9);
        
        // Set to preview
        document.getElementById('photoPreview').src = croppedDataUrl;
        document.getElementById('croppedPhoto').value = croppedDataUrl;
        
        // Update text to show it's been cropped
        const preview = document.getElementById('photoPreview');
        const textDiv = preview.nextElementSibling;
        textDiv.querySelector('div:first-child').textContent = 'Foto dipilih (sudah di-crop)';
        
        closeCropModal();
    } catch (error) {
        console.error('Error applying crop:', error);
        alert('Gagal menerapkan crop. Silakan coba lagi.');
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('Edit page loaded');
    console.log('Current group:', @json($currentGroup));
    console.log('Current position:', @json($currentPosition));
    
    // 1. Set group & populate positions
    const groupSelect = document.getElementById('groupSelect');
    const currentGroup = @json($currentGroup);
    const currentPosition = @json($currentPosition);
    
    if (currentGroup) {
        groupSelect.value = currentGroup;
        updatePositions();
        
        // 2. Set selected position AFTER options are populated
        setTimeout(() => {
            const posSelect = document.getElementById('positionSelect');
            if (currentPosition) {
                // Handle special case for Sekretaris Pokja
                let positionToSelect = currentPosition;
                if (currentGroup !== 'pengurus' && currentPosition === 'Sekretaris Pokja') {
                    positionToSelect = 'Sekretaris';
                }
                
                // Find and select the option
                const optionExists = Array.from(posSelect.options).some(opt => opt.value === positionToSelect);
                if (optionExists) {
                    posSelect.value = positionToSelect;
                    console.log('Position set to:', positionToSelect);
                } else {
                    console.warn('Position option not found:', positionToSelect);
                }
            }
        }, 100);
    }
});
</script>

@endsection