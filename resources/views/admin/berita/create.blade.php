@extends('admin.layouts.app')
@section('title', 'Tambah Berita')
@section('page-title', 'Tambah Berita Baru')

@section('content')

{{-- Header --}}
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
    <div>
        <h1 style="font-size:1.5rem;font-weight:800;color:var(--text-dark);margin:0 0 0.25rem 0">Tambah Berita</h1>
        <p style="color:var(--text-muted);margin:0;font-size:0.9rem">Buat artikel berita baru untuk website PKK Kabupaten Toba</p>
    </div>
    <a href="{{ route('admin.berita.index') }}" class="btn" style="background:#f8fafc;color:var(--text-dark)">← Kembali</a>
</div>

{{-- Form Card --}}
<div class="card">
    <form action="{{ route('admin.berita.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        {{-- Judul --}}
        <div style="margin-bottom:1.5rem">
            <label style="font-weight:600;display:block;margin-bottom:0.5rem;font-size:0.9rem">Judul Berita *</label>
            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required placeholder="Contoh: PKK Toba Gelar Sosialisasi Kesehatan Ibu dan Anak">
        </div>

        {{-- Kategori & Tanggal --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:1.5rem">
            <div>
                <label style="font-weight:600;display:block;margin-bottom:0.5rem;font-size:0.9rem">Kategori *</label>
                <input type="text" name="category" class="form-control" value="{{ old('category') }}" required placeholder="Contoh: Kegiatan, Program, Prestasi">
                <small style="color:var(--text-muted);display:block;margin-top:0.4rem;font-size:0.8rem">Kategori untuk pengelompokan berita</small>
            </div>
            <div>
                <label style="font-weight:600;display:block;margin-bottom:0.5rem;font-size:0.9rem">Tanggal Publikasi</label>
                <input type="date" name="published_at" class="form-control" value="{{ old('published_at', date('Y-m-d')) }}">
                <small style="color:var(--text-muted);display:block;margin-top:0.4rem;font-size:0.8rem">Tanggal berita akan ditampilkan</small>
            </div>
        </div>

        {{-- Excerpt dengan Character Counter --}}
        <div style="margin-bottom:1.5rem">
            <label style="font-weight:600;display:block;margin-bottom:0.5rem;font-size:0.9rem">Ringkasan (Excerpt) *</label>
            
            <!-- maxlength="160" membatasi input secara native di browser -->
            <textarea 
                name="excerpt" 
                id="excerptInput" 
                class="form-control" 
                rows="3" 
                maxlength="160" 
                required 
                placeholder="Ringkasan singkat yang akan muncul di listing berita"
            >{{ old('excerpt', $berita->excerpt ?? '') }}</textarea>
            
            <!-- Counter Container -->
            <div style="display:flex; justify-content:space-between; align-items:center; margin-top:0.4rem;">
                <small style="color:var(--text-muted); font-size:0.8rem">Maksimal 160 karakter untuk preview optimal</small>
                <span id="excerptCounter" style="font-size:0.8rem; font-weight:600; color:var(--text-muted); transition: color 0.2s;">0/160</span>
            </div>
        </div>

        {{-- CKEditor Content --}}
        <div style="margin-bottom:1.5rem">
            <label style="font-weight:600;display:block;margin-bottom:0.5rem;font-size:0.9rem">Konten Lengkap *</label>
            <!-- CKEditor akan mengubah textarea ini menjadi Editor -->
            <textarea name="content" id="contentEditor" class="form-control" rows="10" required>{{ old('content') }}</textarea>
            <small style="color:var(--text-muted);display:block;margin-top:0.4rem;font-size:0.8rem">
                Gunakan toolbar di atas untuk memformat teks, membuat list, atau menambahkan gambar.
            </small>
        </div>

        {{-- Image Upload --}}
        <div style="margin-bottom:1.5rem">
            <label style="font-weight:600;display:block;margin-bottom:0.5rem;font-size:0.9rem">Gambar Berita</label>
            <input type="file" name="image" class="form-control" accept="image/*" id="imageInput">
            
            {{-- Preview --}}
            <div id="imagePreview" style="margin-top:1rem;display:none">
                <img id="previewImg" src="" style="width:100%;max-width:400px;height:auto;border-radius:12px;object-fit:cover;background:#f8fafc">
                <span style="display:block;font-size:0.8rem;color:var(--text-muted);margin-top:0.4rem">Preview Gambar</span>
            </div>
            
            <small style="color:var(--text-muted);display:block;margin-top:0.4rem;font-size:0.8rem">
                Format: JPG/PNG/WebP, maksimal 2MB. Ukuran direkomendasikan: 1200x630px
            </small>
        </div>

        {{-- Publish Checkbox --}}
        <div style="margin-bottom:2rem">
            <label style="display:flex;align-items:center;gap:0.75rem;cursor:pointer">
                <input type="checkbox" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }} style="width:18px;height:18px;cursor:pointer">
                <span style="font-weight:600;font-size:0.95rem">Publikasikan sekarang</span>
            </label>
            <small style="color:var(--text-muted);display:block;margin-top:0.4rem;margin-left:25px;font-size:0.85rem">
                Jika tidak dicentang, berita akan tersimpan sebagai draft
            </small>
        </div>

        {{-- Action Buttons --}}
        <div style="display:flex;gap:0.75rem;justify-content:flex-end;padding-top:1rem;border-top:1px solid rgba(0,0,0,0.04)">
            <a href="{{ route('admin.berita.index') }}" class="btn" style="background:#f8fafc;color:var(--text-dark)">Batal</a>
            <button type="submit" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:0.5rem">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                    <polyline points="17 21 17 13 7 13 7 21"/>
                    <polyline points="7 3 7 8 15 8"/>
                </svg>
                Simpan Berita
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

@push('scripts')
<!-- CKEditor 5 Classic Build dengan Alignment -->
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Konfigurasi Alignment
    const alignmentConfig = {
        options: [
            { name: 'left', title: 'Rata Kiri', icon: 'left', value: 'left' },
            { name: 'center', title: 'Rata Tengah', icon: 'center', value: 'center' },
            { name: 'right', title: 'Rata Kanan', icon: 'right', value: 'right' },
            { name: 'justify', title: 'Rata Kiri-Kanan', icon: 'justify', value: 'justify' }
        ]
    };

    ClassicEditor
        .create(document.querySelector('#contentEditor'), {
            // Toolbar dengan Alignment
            toolbar: [
                'heading', '|',
                'bold', 'italic', 'link', '|',
                'bulletedList', 'numberedList', '|',
                'alignment',  // ✅ Tombol Alignment
                '|',
                'blockQuote', '|',
                'undo', 'redo'
            ],
            
            // Heading options
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                    { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
                ]
            },
            
            // Alignment configuration
            alignment: alignmentConfig,
            
            // Height
            height: 500,
            
            // Allowed content
            allowedContent: true
        })
        .then(editor => {
            console.log('✅ CKEditor 5 initialized with alignment!', editor);
            
            // Simpan instance ke window untuk debugging
            window.editor = editor;
        })
        .catch(error => {
            console.error('❌ CKEditor 5 error:', error);
            console.error('Stack:', error.stack);
        });
});

{{-- Script Character Counter untuk Excerpt --}}
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('excerptInput');
    const counter = document.getElementById('excerptCounter');
    const maxLength = 160;

    // Fungsi update counter
    function updateCounter() {
        const currentLength = textarea.value.length;
        counter.textContent = `${currentLength}/${maxLength}`;

        // Ubah warna berdasarkan jumlah karakter
        if (currentLength >= maxLength) {
            counter.style.color = '#ef4444'; // Merah saat penuh
        } else if (currentLength >= maxLength * 0.85) {
            counter.style.color = '#f59e0b'; // Kuning/Orange saat mendekati batas
        } else {
            counter.style.color = 'var(--text-muted, #6b7280)'; // Normal
        }
    }

    // Jalankan saat mengetik
    textarea.addEventListener('input', updateCounter);
    
    // Jalankan saat halaman dimuat (penting untuk halaman Edit agar counter awal benar)
    updateCounter();
});

        // Script untuk Image Preview (Tetap dipertahankan)
        document.getElementById('imageInput')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran gambar terlalu besar. Maksimal 2MB.');
                    e.target.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImg').src = e.target.result;
                    document.getElementById('imagePreview').style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush

@endsection