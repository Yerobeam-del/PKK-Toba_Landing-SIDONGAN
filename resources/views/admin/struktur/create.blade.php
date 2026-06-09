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
            <input type="file" id="photoInput" name="photo" class="form-control" accept="image/*" onchange="handlePhotoUpload(event)">
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
            <button type="submit" class="btn btn-primary" id="submitBtn">Simpan Data</button>
        </div>
    </form>
</div>

{{-- Crop Modal --}}
<div id="cropModal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.8);z-index:2000;align-items:center;justify-content:center;padding:1rem">
    <div style="background:#fff;border-radius:16px;max-width:700px;width:100%;height:90vh;display:flex;flex-direction:column;overflow:hidden">
        {{-- Header --}}
        <div style="padding:1.25rem 1.5rem;border-bottom:1px solid rgba(0,0,0,0.06);display:flex;justify-content:space-between;align-items:center;flex-shrink:0">
            <h3 style="margin:0;font-size:1.1rem;font-weight:700">Atur Foto Profil</h3>
            <button onclick="closeCropModal()" style="background:none;border:none;font-size:1.5rem;cursor:pointer;color:var(--text-muted)">&times;</button>
        </div>
        
        {{-- Image Container (Scrollable) --}}
        <div style="flex:1;overflow:hidden;position:relative;background:#f8fafc;padding:1.5rem;display:flex;align-items:center;justify-content:center">
            <div style="max-height:100%;overflow:auto;display:flex;align-items:center;justify-content:center;width:100%">
                <img id="cropImage" style="max-width:100%;display:block">
            </div>
        </div>
        
        {{-- Controls (Fixed at bottom) --}}
        <div style="padding:1rem 1.5rem;background:#fff;border-top:1px solid rgba(0,0,0,0.06);flex-shrink:0">
            <div style="display:flex;gap:0.5rem;justify-content:center;margin-bottom:0.75rem;flex-wrap:wrap">
                <button type="button" onclick="rotateImage(-90)" class="btn" style="background:#f8fafc;white-space:nowrap">↺ Putar Kiri</button>
                <button type="button" onclick="rotateImage(90)" class="btn" style="background:#f8fafc;white-space:nowrap">Putar Kanan ↻</button>
                <button type="button" onclick="resetCrop()" class="btn" style="background:#f8fafc;white-space:nowrap">Reset</button>
            </div>
            <div style="text-align:center;font-size:0.85rem;color:var(--text-muted);margin-bottom:1rem">
                Drag untuk geser, scroll untuk zoom
            </div>
            <div style="display:flex;gap:0.75rem;justify-content:flex-end">
                <button type="button" onclick="closeCropModal()" class="btn" style="background:#f8fafc;color:var(--text-dark);white-space:nowrap">Batal</button>
                <button type="button" onclick="applyCrop()" class="btn btn-primary" style="white-space:nowrap">Terapkan Crop</button>
            </div>
        </div>
    </div>
</div>

<script>
// Cropper variables
let cropper = null;
let originalFile = null;
let isSubmitting = false;

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
        document.getElementById('previewContainer').style.display = 'block';
    };
    reader.readAsDataURL(file);
}

// Remove photo
function removePhoto() {
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
    if (!originalFile) {
        alert('Silakan upload foto terlebih dahulu.');
        return;
    }
    
    if (typeof Cropper === 'undefined') {
        alert('Cropper.js belum ter-load. Silakan refresh halaman.');
        return;
    }
    
    const reader = new FileReader();
    reader.onload = function(e) {
        const cropImage = document.getElementById('cropImage');
        cropImage.src = e.target.result;
        
        document.getElementById('cropModal').style.display = 'flex';
        
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        
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
            } catch (error) {
                console.error('Error initializing cropper:', error);
                alert('Gagal menginisialisasi crop tool.');
            }
        };
    };
    reader.readAsDataURL(originalFile);
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
        
        if (!canvas) {
            alert('Gagal membuat hasil crop.');
            return;
        }
        
        const croppedDataUrl = canvas.toDataURL('image/jpeg', 0.9);
        document.getElementById('photoPreview').src = croppedDataUrl;
        document.getElementById('croppedPhoto').value = croppedDataUrl;
        
        closeCropModal();
    } catch (error) {
        console.error('Error applying crop:', error);
        alert('Gagal menerapkan crop. Silakan coba lagi.');
    }
}

// AUTO-CROP saat form submit jika user belum crop
document.getElementById('mainForm').addEventListener('submit', function(e) {
    // Jika sudah submitting, lanjutkan
    if (isSubmitting) return true;
    
    const croppedPhoto = document.getElementById('croppedPhoto').value;
    
    // Jika sudah ada cropped photo, lanjutkan submit
    if (croppedPhoto) {
        return true;
    }
    
    // Jika ada file tapi belum di-crop, auto-crop
    if (originalFile) {
        e.preventDefault();
        isSubmitting = true;
        
        const form = this;
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Memproses...';
        
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const img = new Image();
            img.onload = function() {
                // Buat canvas untuk auto-crop (center square)
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                
                canvas.width = 400;
                canvas.height = 400;
                
                // Hitung crop area (center square)
                const size = Math.min(img.width, img.height);
                const x = (img.width - size) / 2;
                const y = (img.height - size) / 2;
                
                // Draw cropped image
                ctx.fillStyle = '#fff';
                ctx.fillRect(0, 0, 400, 400);
                ctx.drawImage(img, x, y, size, size, 0, 0, 400, 400);
                
                // Convert to base64
                const croppedDataUrl = canvas.toDataURL('image/jpeg', 0.9);
                document.getElementById('croppedPhoto').value = croppedDataUrl;
                
                // Submit form setelah auto-crop
                setTimeout(() => form.submit(), 100);
            };
            img.src = e.target.result;
        };
        
        reader.readAsDataURL(originalFile);
        return false;
    }
    
    // Jika tidak ada foto, lanjutkan submit
    return true;
});
</script>

@endsection