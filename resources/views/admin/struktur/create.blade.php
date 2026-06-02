@extends('admin.layouts.app')
@section('title', 'Tambah Anggota Struktur')
@section('page-title', 'Tambah Anggota')

@section('content')

{{-- Header --}}
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
    <div>
        <h1 style="font-size:1.5rem;font-weight:800;color:var(--text-dark);margin:0 0 0.25rem 0">Tambah Anggota</h1>
        <p style="color:var(--text-muted);margin:0;font-size:0.9rem">Tambahkan data anggota baru ke dalam struktur organisasi</p>
    </div>
    <a href="{{ route('admin.struktur.index') }}" class="btn" style="background:#f8fafc;color:var(--text-dark)">← Kembali</a>
</div>

{{-- Form Card --}}
<div class="card">
    <form action="{{ route('admin.struktur.store') }}" method="POST" enctype="multipart/form-data" id="mainForm">
        @csrf
        
        {{-- Group & Position --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:1.5rem">
            <div>
                <label style="font-weight:600;display:block;margin-bottom:0.5rem;font-size:0.9rem">1. Kelompok *</label>
                <select name="group" id="groupSelect" class="form-control" required onchange="updatePositions()">
                    <option value="">-- Pilih Kelompok --</option>
                    <option value="pengurus">Pengurus Inti</option>
                    <option value="pokja1">Pokja I</option>
                    <option value="pokja2">Pokja II</option>
                    <option value="pokja3">Pokja III</option>
                    <option value="pokja4">Pokja IV</option>
                </select>
            </div>
            <div>
                <label style="font-weight:600;display:block;margin-bottom:0.5rem;font-size:0.9rem">2. Jabatan *</label>
                <select name="position" id="positionSelect" class="form-control" required>
                    <option value="">-- Pilih Kelompok Dulu --</option>
                </select>
            </div>
        </div>

        {{-- Name --}}
        <div style="margin-bottom:1.5rem">
            <label style="font-weight:600;display:block;margin-bottom:0.5rem;font-size:0.9rem">3. Nama Lengkap *</label>
            <input type="text" name="name" class="form-control" required placeholder="Contoh: INDAH KARUNIA PRATIWI SITUMEANG, SH">
        </div>

        {{-- Description --}}
        <div style="margin-bottom:1.5rem">
            <label style="font-weight:600;display:block;margin-bottom:0.5rem;font-size:0.9rem">Deskripsi / Catatan (Opsional)</label>
            <textarea name="description" class="form-control" rows="3" placeholder="Misal: NIP, riwayat singkat, atau catatan internal"></textarea>
        </div>

        {{-- Photo Upload with Crop --}}
        <div style="margin-bottom:2rem">
            <label style="font-weight:600;display:block;margin-bottom:0.5rem;font-size:0.9rem">Foto Anggota</label>
            <input type="file" id="photoInput" class="form-control" accept="image/*" onchange="handlePhotoUpload(event)">
            <small style="color:var(--text-muted);display:block;margin-top:0.4rem">JPG/PNG, maksimal 2MB. Klik foto untuk mengatur crop.</small>
            
            {{-- Preview Container --}}
            <div id="previewContainer" style="margin-top:1rem;display:none">
                <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1rem">
                    <img id="photoPreview" style="width:80px;height:80px;border-radius:12px;object-fit:cover;background:#f8fafc;cursor:pointer" onclick="openCropModal()">
                    <div>
                        <div style="font-weight:600;font-size:0.9rem">Foto dipilih</div>
                        <div style="font-size:0.85rem;color:var(--text-muted)">Klik foto untuk atur crop</div>
                    </div>
                    <button type="button" onclick="removePhoto()" style="margin-left:auto;background:#fef2f2;color:#ef4444;border:none;padding:0.5rem 1rem;border-radius:6px;cursor:pointer;font-size:0.85rem">Hapus</button>
                </div>
            </div>
        </div>

        {{-- Hidden input for cropped image --}}
        <input type="hidden" name="cropped_photo" id="croppedPhoto">

        {{-- Action Buttons --}}
        <div style="display:flex;gap:0.75rem;justify-content:flex-end;padding-top:1rem;border-top:1px solid rgba(0,0,0,0.04)">
            <a href="{{ route('admin.struktur.index') }}" class="btn" style="background:#f8fafc;color:var(--text-dark)">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Data</button>
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
                <div id="loadingIndicator" style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);display:none">
                    <div style="background:#fff;padding:1rem 2rem;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,0.15)">
                        Loading...
                    </div>
                </div>
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

// Handle photo upload
function handlePhotoUpload(event) {
    const file = event.target.files[0];
    if (!file) return;
    
    console.log('File selected:', file.name, file.size);
    
    if (file.size > 2 * 1024 * 1024) {
        alert('Ukuran foto terlalu besar. Maksimal 2MB.');
        event.target.value = '';
        return;
    }
    
    originalFile = file;
    const reader = new FileReader();
    reader.onload = function(e) {
        console.log('File loaded, setting preview...');
        const preview = document.getElementById('photoPreview');
        preview.src = e.target.result;
        preview.style.display = 'block';
        document.getElementById('previewContainer').style.display = 'block';
        console.log('Preview set successfully');
    };
    reader.onerror = function(error) {
        console.error('Error reading file:', error);
        alert('Gagal membaca file.');
    };
    reader.readAsDataURL(file);
}

// Remove photo
function removePhoto() {
    console.log('Removing photo...');
    document.getElementById('photoInput').value = '';
    document.getElementById('previewContainer').style.display = 'none';
    document.getElementById('croppedPhoto').value = '';
    document.getElementById('photoPreview').style.display = 'none';
    originalFile = null;
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
}

// Open crop modal
function openCropModal() {
    console.log('Opening crop modal...');
    console.log('Original file:', originalFile);
    console.log('Cropper.js loaded:', typeof Cropper !== 'undefined');
    
    if (!originalFile) {
        alert('Silakan upload foto terlebih dahulu.');
        return;
    }
    
    if (typeof Cropper === 'undefined') {
        alert('Cropper.js belum ter-load. Silakan refresh halaman.');
        console.error('Cropper.js is not loaded!');
        return;
    }
    
    const reader = new FileReader();
    reader.onload = function(e) {
        console.log('Setting crop image source...');
        const cropImage = document.getElementById('cropImage');
        cropImage.src = e.target.result;
        
        // Show modal
        document.getElementById('cropModal').style.display = 'flex';
        
        // Destroy existing cropper
        if (cropper) {
            console.log('Destroying existing cropper...');
            cropper.destroy();
            cropper = null;
        }
        
        // Initialize cropper after image loads
        cropImage.onload = function() {
            console.log('Image loaded, initializing cropper...');
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
                    minContainerHeight: 300,
                    ready: function() {
                        console.log('Cropper is ready!');
                    }
                });
                console.log('Cropper initialized successfully');
            } catch (error) {
                console.error('Error initializing cropper:', error);
                alert('Gagal menginisialisasi crop tool.');
            }
        };
        
        cropImage.onerror = function() {
            console.error('Failed to load image for cropping');
            alert('Gagal memuat gambar.');
        };
    };
    reader.onerror = function() {
        console.error('Failed to read file');
        alert('Gagal membaca file.');
    };
    reader.readAsDataURL(originalFile);
}

// Close crop modal
function closeCropModal() {
    console.log('Closing crop modal...');
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
    document.getElementById('cropModal').style.display = 'none';
}

// Rotate image
function rotateImage(degrees) {
    console.log('Rotating image:', degrees);
    if (cropper) {
        cropper.rotate(degrees);
    }
}

// Reset crop
function resetCrop() {
    console.log('Resetting crop...');
    if (cropper) {
        cropper.reset();
    }
}

// Apply crop
function applyCrop() {
    console.log('Applying crop...');
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
        
        if (!canvas) {
            alert('Gagal membuat hasil crop.');
            return;
        }
        
        const croppedDataUrl = canvas.toDataURL('image/jpeg', 0.9);
        console.log('Cropped image size:', croppedDataUrl.length);
        
        // Set to preview
        document.getElementById('photoPreview').src = croppedDataUrl;
        document.getElementById('croppedPhoto').value = croppedDataUrl;
        
        closeCropModal();
        console.log('Crop applied successfully');
    } catch (error) {
        console.error('Error applying crop:', error);
        alert('Gagal menerapkan crop. Silakan coba lagi.');
    }
}

// Check if Cropper.js is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded');
    console.log('Cropper.js available:', typeof window.Cropper !== 'undefined');
    
    if (typeof window.Cropper === 'undefined') {
        console.error('WARNING: Cropper.js is not loaded!');
    }
});
</script>

@endsection