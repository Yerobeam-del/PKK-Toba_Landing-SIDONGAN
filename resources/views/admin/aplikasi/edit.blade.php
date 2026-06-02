@extends('admin.layouts.app')
@section('title', 'Edit Aplikasi')
@section('page-title', 'Edit Aplikasi')

@section('content')

{{-- Header --}}
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
    <div>
        <h1 style="font-size:1.5rem;font-weight:800;color:var(--text-dark);margin:0 0 0.25rem 0">Edit Aplikasi</h1>
        <p style="color:var(--text-muted);margin:0;font-size:0.9rem">Perbarui data aplikasi yang sudah ada</p>
    </div>
    <a href="{{ route('admin.aplikasi.index') }}" class="btn" style="background:#f8fafc;color:var(--text-dark)">← Kembali</a>
</div>

{{-- Form Card --}}
<div class="card">
    <form action="{{ route('admin.aplikasi.update', $aplikasi) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        {{-- Nama Lengkap --}}
        <div style="margin-bottom:1.5rem">
            <label style="font-weight:600;display:block;margin-bottom:0.5rem;font-size:0.9rem">Nama Aplikasi Lengkap *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $aplikasi->name) }}" required placeholder="Contoh: SIEDA - Sistem Informasi E-Dasawisma">
        </div>

        {{-- Short Name & Category --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:1.5rem">
            <div>
                <label style="font-weight:600;display:block;margin-bottom:0.5rem;font-size:0.9rem">Nama Singkat *</label>
                <input type="text" name="short_name" class="form-control" value="{{ old('short_name', $aplikasi->short_name) }}" required placeholder="Contoh: SIEDA" style="text-transform:uppercase">
            </div>
            <div>
                <label style="font-weight:600;display:block;margin-bottom:0.5rem;font-size:0.9rem">Kategori *</label>
                <select name="category" class="form-control" required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="layanan" {{ old('category', $aplikasi->category) == 'layanan' ? 'selected' : '' }}>Layanan</option>
                    <option value="aplikasi" {{ old('category', $aplikasi->category) == 'aplikasi' ? 'selected' : '' }}>Aplikasi</option>
                </select>
            </div>
        </div>

        {{-- Description --}}
        <div style="margin-bottom:1.5rem">
            <label style="font-weight:600;display:block;margin-bottom:0.5rem;font-size:0.9rem">Deskripsi Lengkap *</label>
            <textarea name="description" class="form-control" rows="3" required placeholder="Deskripsi detail tentang aplikasi, fitur, dan fungsionalitas">{{ old('description', $aplikasi->description) }}</textarea>
        </div>

        {{-- Features --}}
        <div style="margin-bottom:1.5rem">
            <label style="font-weight:600;display:block;margin-bottom:0.5rem;font-size:0.9rem">Poin-Poin Fitur Aplikasi</label>
            <small style="color:var(--text-muted);display:block;margin-bottom:1rem;font-size:0.8rem">Tambahkan 2-5 poin keunggulan/fitur aplikasi</small>
            
            <div id="features-container">
                @php
                    $features = old('features', $aplikasi->features ?? [
                        'Terintegrasi dengan data PKK',
                        'Akses real-time 24/7',
                        'Keamanan data terjamin'
                    ]);
                @endphp
                
                @foreach($features as $index => $feature)
                <div class="feature-item" style="display:flex;gap:0.75rem;margin-bottom:0.75rem">
                    <input type="text" name="features[]" class="form-control" value="{{ $feature }}" placeholder="Masukkan poin fitur" required>
                    <button type="button" class="btn" onclick="removeFeature(this)" 
                            style="background:#f8fafc;color:#ef4444;padding:0.6rem;min-width:40px;border-radius:8px" 
                            title="Hapus poin" 
                            {{ count($features) <= 2 ? 'disabled' : '' }}>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"/>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                            <line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/>
                        </svg>
                    </button>
                </div>
                @endforeach
            </div>
            
            <button type="button" id="add-feature-btn" class="btn" onclick="addFeature()" 
                    style="margin-top:0.5rem;width:100%;background:#f8fafc;color:var(--text-dark);display:inline-flex;align-items:center;justify-content:center;gap:0.5rem;{{ count($features) >= 5 ? 'display:none' : '' }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Tambah Poin Fitur
            </button>
            
            <small id="features-warning" style="color:#ef4444;display:none;margin-top:0.5rem;font-size:0.85rem">
                ⚠️ Maksimal 5 poin fitur
            </small>
        </div>

        {{-- Status & URL --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:1.5rem">
            <div>
                <label style="font-weight:600;display:block;margin-bottom:0.5rem;font-size:0.9rem">Status Aplikasi *</label>
                <select name="status" class="form-control" required onchange="toggleUrlField(this.value)">
                    <option value="active" {{ old('status', $aplikasi->status) == 'active' ? 'selected' : '' }}>Aktif - Siap Digunakan</option>
                    <option value="maintenance" {{ old('status', $aplikasi->status) == 'maintenance' ? 'selected' : '' }}>Dalam Maintenance - Sedang Perbaikan</option>
                    <option value="development" {{ old('status', $aplikasi->status) == 'development' ? 'selected' : '' }}>Dalam Pengembangan - Coming Soon</option>
                </select>
            </div>
            <div id="urlField">
                <label style="font-weight:600;display:block;margin-bottom:0.5rem;font-size:0.9rem">URL Aplikasi</label>
                <input type="url" name="url" class="form-control" value="{{ old('url', $aplikasi->url !== '#' ? $aplikasi->url : '') }}" {{ $aplikasi->status == 'development' ? 'disabled' : '' }} placeholder="https://example.com">
            </div>
        </div>

        {{-- Icon & Sort Order --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:1.5rem">
            <div>
                <label style="font-weight:600;display:block;margin-bottom:0.5rem;font-size:0.9rem">Icon/Logo Aplikasi</label>
                <input type="file" name="icon" class="form-control" accept="image/*">
                
                @if($aplikasi->icon)
                <div style="margin-top:1rem;display:flex;align-items:center;gap:1rem">
                    <img src="{{ asset('storage/'.$aplikasi->icon) }}" style="width:80px;height:80px;border-radius:12px;object-fit:cover;background:#f8fafc">
                    <span style="font-size:0.85rem;color:var(--text-muted)">Icon saat ini <small style="color:#94a3b8">(upload baru untuk mengganti)</small></span>
                </div>
                @endif
            </div>
            <div>
                <label style="font-weight:600;display:block;margin-bottom:0.5rem;font-size:0.9rem">Urutan Tampil</label>
                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $aplikasi->sort_order) }}" min="0">
                <small style="color:var(--text-muted);display:block;margin-top:0.4rem;font-size:0.8rem">Semakin kecil angka, semakin awal tampil</small>
            </div>
        </div>

        {{-- Is Active Checkbox --}}
        <div style="margin-bottom:2rem">
            <label style="display:flex;align-items:center;gap:0.75rem;cursor:pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $aplikasi->is_active) ? 'checked' : '' }} style="width:18px;height:18px;cursor:pointer">
                <span style="font-weight:600;font-size:0.95rem">Tampilkan di Website</span>
            </label>
            <small style="color:var(--text-muted);display:block;margin-top:0.4rem;margin-left:25px;font-size:0.85rem">
                Jika dicentang, aplikasi akan tampil di landing page. Jika tidak, aplikasi disembunyikan sementara.
            </small>
        </div>

        {{-- Action Buttons --}}
        <div style="display:flex;gap:0.75rem;justify-content:flex-end;padding-top:1rem;border-top:1px solid rgba(0,0,0,0.04)">
            <a href="{{ route('admin.aplikasi.index') }}" class="btn" style="background:#f8fafc;color:var(--text-dark)">Batal</a>
            <button type="submit" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:0.5rem">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                    <polyline points="17 21 17 13 7 13 7 21"/>
                    <polyline points="7 3 7 8 15 8"/>
                </svg>
                Update Aplikasi
            </button>
        </div>
    </form>
    
    {{-- Validation Errors --}}
    @if($errors->any())
    <div style="margin-top:1.5rem;padding:1rem;background:#fef2f2;border-radius:10px;color:#991b1b">
        <strong style="display:block;margin-bottom:0.5rem;font-weight:600">Periksa kembali input berikut:</strong>
        <ul style="padding-left:1.25rem;margin:0;font-size:0.9rem">
            @foreach($errors->all() as $err) <li style="margin-bottom:0.25rem">{{ $err }}</li> @endforeach
        </ul>
    </div>
    @endif
</div>

<script>
// Feature functions
function addFeature() {
    const container = document.getElementById('features-container');
    const currentCount = container.querySelectorAll('.feature-item').length;
    
    if (currentCount >= 5) {
        document.getElementById('features-warning').style.display = 'block';
        return;
    }
    
    document.getElementById('features-warning').style.display = 'none';
    
    const div = document.createElement('div');
    div.className = 'feature-item';
    div.style.cssText = 'display:flex;gap:0.75rem;margin-bottom:0.75rem';
    div.innerHTML = `
        <input type="text" name="features[]" class="form-control" placeholder="Masukkan poin fitur" required>
        <button type="button" class="btn" onclick="removeFeature(this)" 
                style="background:#f8fafc;color:#ef4444;padding:0.6rem;min-width:40px;border-radius:8px" title="Hapus poin">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="3 6 5 6 21 6"/>
                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                <line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/>
            </svg>
        </button>
    `;
    container.appendChild(div);
    updateDeleteButtons();
}

function removeFeature(btn) {
    const container = document.getElementById('features-container');
    const currentCount = container.querySelectorAll('.feature-item').length;
    
    if (currentCount <= 2) {
        alert('Minimal harus ada 2 poin fitur');
        return;
    }
    
    btn.closest('.feature-item').remove();
    updateDeleteButtons();
}

function updateDeleteButtons() {
    const container = document.getElementById('features-container');
    const items = container.querySelectorAll('.feature-item');
    const addBtn = document.getElementById('add-feature-btn');
    
    items.forEach(item => {
        const deleteBtn = item.querySelector('button[onclick="removeFeature(this)"]');
        if (items.length <= 2) {
            deleteBtn.disabled = true;
            deleteBtn.style.opacity = '0.4';
            deleteBtn.style.cursor = 'not-allowed';
        } else {
            deleteBtn.disabled = false;
            deleteBtn.style.opacity = '1';
            deleteBtn.style.cursor = 'pointer';
        }
    });
    
    if (items.length >= 5) {
        addBtn.style.display = 'none';
    } else {
        addBtn.style.display = 'inline-flex';
    }
}

function toggleUrlField(status) {
    const urlField = document.getElementById('urlField');
    const urlInput = urlField.querySelector('input');
    
    if (status === 'development') {
        urlField.style.opacity = '0.5';
        urlInput.disabled = true;
        urlInput.value = '#';
    } else {
        urlField.style.opacity = '1';
        urlInput.disabled = false;
        if (urlInput.value === '#') urlInput.value = '';
    }
}

// Init on load
document.addEventListener('DOMContentLoaded', () => {
    const statusSelect = document.querySelector('select[name="status"]');
    if (statusSelect) toggleUrlField(statusSelect.value);
    updateDeleteButtons();
});
</script>

@endsection