@extends('sidongan.layouts.app')
@section('title', 'Detail Laporan Kegiatan - SIDONGAN')

@section('content')
<style>
    /* Gallery Overlay dengan Blur */
    .gallery-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0, 0, 0, 0.85);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .gallery-overlay.active {
        display: flex;
        opacity: 1;
    }
    
    /* Gallery Container */
    .gallery-container {
        position: relative;
        max-width: 85vw;
        max-height: 85vh;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    
    /* Gallery Image dengan Animasi */
    .gallery-image-wrapper {
        position: relative;
        overflow: hidden;
        border-radius: 0.75rem;
        box-shadow: 0 25px 50px rgba(0,0,0,0.4);
        max-width: 80vw;
        max-height: 70vh;
    }
    
    .gallery-image {
        display: block;
        max-width: 80vw;
        max-height: 70vh;
        object-fit: contain;
        transition: opacity 0.3s ease, transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }
    
    .gallery-image.slide-left { animation: slideLeft 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94); }
    .gallery-image.slide-right { animation: slideRight 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94); }
    .gallery-image.fade-in { animation: fadeIn 0.3s ease; }
    
    @keyframes slideLeft { 0% { opacity: 0; transform: translateX(60px) scale(0.95); } 100% { opacity: 1; transform: translateX(0) scale(1); } }
    @keyframes slideRight { 0% { opacity: 0; transform: translateX(-60px) scale(0.95); } 100% { opacity: 1; transform: translateX(0) scale(1); } }
    @keyframes fadeIn { 0% { opacity: 0; transform: scale(0.9); } 100% { opacity: 1; transform: scale(1); } }
    
    .gallery-close {
        position: fixed;
        top: 20px;
        right: 20px;
        width: 44px;
        height: 44px;
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 50%;
        color: white;
        font-size: 1.25rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
        backdrop-filter: blur(4px);
        z-index: 10000;
    }
    .gallery-close:hover {
        background: rgba(255,255,255,0.3);
        transform: rotate(90deg) scale(1.1);
    }
    
    .gallery-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 44px;
        height: 44px;
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 50%;
        color: white;
        font-size: 1rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
        backdrop-filter: blur(4px);
        z-index: 10;
    }
    .gallery-nav:hover {
        background: rgba(255,255,255,0.35);
        transform: translateY(-50%) scale(1.15);
    }
    .gallery-nav.prev { left: -60px; }
    .gallery-nav.next { right: -60px; }
    
    .gallery-bottom-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        margin-top: 1rem;
        padding: 0 0.5rem;
    }
    
    .gallery-counter {
        color: rgba(255,255,255,0.8);
        font-size: 0.875rem;
        font-weight: 600;
        background: rgba(255,255,255,0.1);
        padding: 0.35rem 0.85rem;
        border-radius: 9999px;
        backdrop-filter: blur(4px);
    }
    
    .gallery-download-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1.25rem;
        background: rgba(59, 130, 246, 0.8);
        border: 1px solid rgba(59, 130, 246, 0.5);
        border-radius: 0.5rem;
        color: white;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        backdrop-filter: blur(4px);
    }
    .gallery-download-btn:hover {
        background: rgba(59, 130, 246, 1);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    }
    
    /* Thumbnails - Compact */
    .gallery-thumbnails {
        display: flex;
        gap: 0.5rem;
        margin-top: 0.5rem;
        justify-content: center;
        flex-wrap: wrap;
        max-width: 80vw;
    }
    
    .gallery-thumb {
        width: 48px;
        height: 48px;
        border-radius: 0.375rem;
        overflow: hidden;
        border: 2px solid rgba(255,255,255,0.2);
        cursor: pointer;
        transition: all 0.3s;
        opacity: 0.5;
        flex-shrink: 0;
    }
    .gallery-thumb.active {
        border-color: #3b82f6;
        opacity: 1;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
    }
    .gallery-thumb:hover { opacity: 0.9; transform: scale(1.1); }
    .gallery-thumb img { width: 100%; height: 100%; object-fit: cover; }
    
    @media (max-width: 768px) {
        .gallery-container { max-width: 95vw; }
        .gallery-image { max-width: 95vw; max-height: 60vh; }
        .gallery-image-wrapper { max-width: 95vw; }
        .gallery-nav { width: 36px; height: 36px; font-size: 0.875rem; }
        .gallery-nav.prev { left: 8px; }
        .gallery-nav.next { right: 8px; }
        .gallery-close { width: 36px; height: 36px; font-size: 1rem; top: 12px; right: 12px; }
        .gallery-thumbnails { gap: 0.35rem; }
        .gallery-thumb { width: 40px; height: 40px; }
        .gallery-bottom-bar { flex-direction: column; gap: 0.5rem; }
    }
</style>

<div style="max-width: 1000px; margin: 0 auto;">
    {{-- Header --}}
    <div style="background: linear-gradient(135deg, #0891b2, #14b8a6); padding: 1.25rem 1.5rem; border-radius: 0.75rem; margin-bottom: 1.5rem; color: white;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem;">
            <div>
                <h1 style="font-size: 1.25rem; font-weight: 700; margin: 0;">Detail Laporan Kegiatan</h1>
                <p style="font-size: 0.85rem; opacity: 0.9; margin: 0.25rem 0 0 0;">Informasi lengkap laporan kegiatan</p>
            </div>
            
            <div style="display: flex; gap: 0.75rem; align-items: center;">
                @php
                    $currentUser = auth()->guard('sidongan')->user();
                    // Cek apakah user adalah Ketua dan status laporan masih menunggu
                    $isKetua = $currentUser && $currentUser->hasSidonganRole('ketua');
                    $canVerify = $isKetua && $report->status === 'menunggu_verifikasi';
                @endphp

                {{-- ✅ TOMBOL VERIFIKASI SHORTCUT (Hanya muncul jika Ketua & Status Menunggu) --}}
                @if($canVerify)
                <a href="{{ route('sidongan.verifikasi.form', $report->id) }}" 
                   style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1rem; background: #7c3aed; color: white; text-decoration: none; border-radius: 0.5rem; font-weight: 600; transition: all 0.2s; box-shadow: 0 2px 4px rgba(124, 58, 237, 0.3);" 
                   onmouseover="this.style.background='#6d28d9'; this.style.transform='translateY(-1px)'" 
                   onmouseout="this.style.background='#7c3aed'; this.style.transform='translateY(0)'">
                    <i class="fas fa-clipboard-check"></i>
                    <span>Verifikasi Laporan</span>
                </a>
                @endif
                
                <a href="{{ route('sidongan.lapor_kegiatan.index') }}" 
                   style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1rem; background: rgba(255,255,255,0.2); color: white; text-decoration: none; border-radius: 0.5rem; font-weight: 600; transition: all 0.2s;" 
                   onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali ke Daftar</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Content Grid --}}
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
        
        {{-- KOLOM KIRI: Informasi Kegiatan --}}
        <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; overflow: hidden; display: flex; flex-direction: column;">
            <div style="padding: 1rem 1.5rem; background: linear-gradient(135deg, #dcfce7, #bbf7d0); border-bottom: 1px solid #86efac; flex-shrink: 0;">
                <h2 style="font-size: 0.95rem; font-weight: 700; color: #1e293b; margin: 0;">
                    <i class="fas fa-clipboard-list" style="color: #16a34a; margin-right: 0.5rem;"></i>
                    Informasi Kegiatan
                </h2>
            </div>
            <div style="padding: 1.5rem; flex: 1;">
                <div style="margin-bottom: 1rem;">
                    <span style="display: block; font-size: 0.7rem; color: #64748b; margin-bottom: 0.25rem; font-weight: 600;">Nama Kegiatan</span>
                    <p style="font-size: 0.9rem; font-weight: 600; color: #0f172a; margin: 0;">{{ $report->kegiatan_nama }}</p>
                </div>
                <div style="margin-bottom: 1rem;">
                    <span style="display: block; font-size: 0.7rem; color: #64748b; margin-bottom: 0.25rem; font-weight: 600;">Tanggal Kegiatan</span>
                    <p style="font-size: 0.9rem; font-weight: 600; color: #0f172a; margin: 0;">
                        <i class="fas fa-calendar" style="color: #3b82f6; margin-right: 0.35rem;"></i>
                        {{ $report->kegiatan_tanggal->locale('id')->translatedFormat('d M Y') }}
                    </p>
                </div>
                @if($report->lokasi)
                <div style="margin-bottom: 1rem;">
                    <span style="display: block; font-size: 0.7rem; color: #64748b; margin-bottom: 0.25rem; font-weight: 600;">Lokasi</span>
                    <p style="font-size: 0.9rem; color: #0f172a; margin: 0;">
                        <i class="fas fa-map-marker-alt" style="color: #ef4444; margin-right: 0.35rem;"></i>
                        {{ $report->lokasi }}
                    </p>
                </div>
                @endif
                <div style="margin-bottom: 1rem;">
                    <span style="display: block; font-size: 0.7rem; color: #64748b; margin-bottom: 0.25rem; font-weight: 600;">Deskripsi Kegiatan</span>
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 0.85rem;">
                        <p style="font-size: 0.825rem; color: #334155; margin: 0; line-height: 1.7;">{!! nl2br(e($report->deskripsi)) !!}</p>
                    </div>
                </div>
                
                {{-- Dokumentasi Foto --}}
                @php
                    $fotosArray = is_string($report->fotos) ? json_decode($report->fotos, true) : $report->fotos;
                    $fotosArray = is_array($fotosArray) ? $fotosArray : [];
                @endphp
                @if(count($fotosArray) > 0)
                <div style="margin-top: auto;">
                    <span style="display: block; font-size: 0.7rem; color: #64748b; margin-bottom: 0.5rem; font-weight: 600;">
                        <i class="fas fa-camera" style="margin-right: 0.35rem;"></i>
                        Dokumentasi Kegiatan ({{ count($fotosArray) }} foto)
                    </span>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(70px, 1fr)); gap: 0.5rem;">
                        @foreach($fotosArray as $index => $foto)
                        <div onclick="openGallery({{ $index }})" 
                             style="cursor: pointer; border-radius: 0.375rem; overflow: hidden; border: 2px solid #e2e8f0; transition: all 0.2s; aspect-ratio: 1;"
                             onmouseover="this.style.borderColor='#3b82f6'; this.style.transform='scale(1.05)'" 
                             onmouseout="this.style.borderColor='#e2e8f0'; this.style.transform='scale(1)'">
                            <img src="{{ asset('storage/' . $foto) }}" alt="Dokumentasi {{ $index + 1 }}" 
                                 style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- KOLOM KANAN: Surat Terkait & Status --}}
        <div>
            @if($report->document)
            <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 1.5rem;">
                <div style="padding: 1rem 1.5rem; background: linear-gradient(135deg, #f0f9ff, #e0f2fe); border-bottom: 1px solid #bae6fd;">
                    <h2 style="font-size: 0.95rem; font-weight: 700; color: #0c4a6e; margin: 0;">
                        <i class="fas fa-envelope" style="color: #0284c7; margin-right: 0.5rem;"></i>
                        Surat Terkait
                    </h2>
                </div>
                <div style="padding: 1.5rem;">
                    <div style="margin-bottom: 0.75rem;">
                        <span style="display: block; font-size: 0.7rem; color: #64748b; margin-bottom: 0.2rem;">Nomor Agenda</span>
                        <p style="font-size: 0.85rem; font-weight: 600; color: #0f172a; margin: 0;">{{ $report->document->agenda_number }}</p>
                    </div>
                    <div style="margin-bottom: 0.75rem;">
                        <span style="display: block; font-size: 0.7rem; color: #64748b; margin-bottom: 0.2rem;">Judul Surat</span>
                        <p style="font-size: 0.85rem; color: #0f172a; margin: 0; line-height: 1.5;">{{ $report->document->subject ?? $report->document->title }}</p>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <span style="display: block; font-size: 0.7rem; color: #64748b; margin-bottom: 0.2rem;">Pengirim</span>
                        <p style="font-size: 0.85rem; color: #0f172a; margin: 0;">{{ $report->document->sender }}</p>
                    </div>
                    <a href="{{ route('sidongan.documents.show', $report->document) }}" 
                       style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; background: #dbeafe; color: #2563eb; text-decoration: none; border-radius: 0.375rem; font-size: 0.8rem; font-weight: 600;"
                       onmouseover="this.style.background='#bfdbfe'" onmouseout="this.style.background='#dbeafe'">
                        <i class="fas fa-eye"></i>
                        <span>Lihat Detail Surat</span>
                    </a>
                </div>
            </div>
            @endif

            {{-- Status Laporan --}}
            <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; overflow: hidden;">
                <div style="padding: 1rem 1.5rem; background: #fffbeb; border-bottom: 1px solid #fde68a;">
                    <h2 style="font-size: 0.95rem; font-weight: 700; color: #92400e; margin: 0;">
                        <i class="fas fa-info-circle" style="color: #d97706; margin-right: 0.5rem;"></i>
                        Status Laporan
                    </h2>
                </div>
                <div style="padding: 1.5rem;">
                    @php
                        $statusConfig = [
                            'draft' => ['bg' => '#f1f5f9', 'text' => '#64748b', 'label' => 'Draft'],
                            'menunggu_verifikasi' => ['bg' => '#fef3c7', 'text' => '#92400e', 'label' => 'Menunggu Verifikasi'],
                            'disetujui' => ['bg' => '#d1fae5', 'text' => '#065f46', 'label' => 'Disetujui'],
                            'ditolak' => ['bg' => '#fee2e2', 'text' => '#991b1b', 'label' => 'Ditolak'],
                        ];
                        $status = $statusConfig[$report->status] ?? $statusConfig['draft'];
                    @endphp
                    <div style="margin-bottom: 1.5rem;">
                        <span style="display: block; font-size: 0.7rem; color: #64748b; margin-bottom: 0.5rem; font-weight: 600;">STATUS SAAT INI</span>
                        <span style="display: inline-block; padding: 0.5rem 1rem; background: {{ $status['bg'] }}; color: {{ $status['text'] }}; border-radius: 0.5rem; font-size: 0.9rem; font-weight: 700; width: 100%; text-align: center; border: 1px solid {{ $status['text'] }}20;">
                            {{ $status['label'] }}
                        </span>
                    </div>

                    <div style="margin-bottom: 0.75rem;">
                        <span style="display: block; font-size: 0.7rem; color: #64748b; margin-bottom: 0.2rem;">Dibuat oleh</span>
                        <p style="font-size: 0.85rem; color: #0f172a; margin: 0;">
                            <i class="fas fa-user" style="color: #3b82f6; margin-right: 0.35rem;"></i>
                            {{ $report->creator->name ?? 'Unknown' }}
                        </p>
                    </div>
                    <div style="margin-bottom: 0.75rem;">
                        <span style="display: block; font-size: 0.7rem; color: #64748b; margin-bottom: 0.2rem;">Tanggal Pembuatan</span>
                        <p style="font-size: 0.85rem; color: #0f172a; margin: 0;">
                            <i class="fas fa-clock" style="color: #3b82f6; margin-right: 0.35rem;"></i>
                            {{ $report->created_at->locale('id')->translatedFormat('d M Y, H.i') }}
                        </p>
                    </div>
                    
                    @if($report->catatan_verifikasi)
                    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px dashed #e2e8f0;">
                        <span style="display: block; font-size: 0.7rem; color: #64748b; margin-bottom: 0.2rem;">Catatan Verifikasi</span>
                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 0.85rem;">
                            <p style="font-size: 0.825rem; color: #334155; margin: 0; font-style: italic;">{{ $report->catatan_verifikasi }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL GALLERY --}}
<div id="galleryOverlay" class="gallery-overlay" onclick="closeGallery(event)">
    <button class="gallery-close" onclick="closeGallery()">
        <i class="fas fa-times"></i>
    </button>
    
    <div class="gallery-container" onclick="event.stopPropagation()">
        <div class="gallery-image-wrapper">
            <img id="galleryImage" class="gallery-image" src="" alt="Dokumentasi">
        </div>
        
        <button class="gallery-nav prev" onclick="navigateGallery(-1)">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="gallery-nav next" onclick="navigateGallery(1)">
            <i class="fas fa-chevron-right"></i>
        </button>
        
        <div class="gallery-bottom-bar">
            <span id="galleryCounter" class="gallery-counter">1 / 1</span>
            <a id="galleryDownload" class="gallery-download-btn" href="" download>
                <i class="fas fa-download"></i>
                <span>Unduh Foto</span>
            </a>
        </div>
        
        <div id="galleryThumbnails" class="gallery-thumbnails"></div>
    </div>
</div>

<script>
    const galleryFotos = @json($fotosArray ?? []);
    let currentIndex = 0;
    let isAnimating = false;
    
    function openGallery(index) {
        currentIndex = index;
        updateGalleryImage('fade-in');
        updateGalleryUI(); 
        document.getElementById('galleryOverlay').classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    function closeGallery(event) {
        if (event && event.target !== document.getElementById('galleryOverlay')) return;
        const overlay = document.getElementById('galleryOverlay');
        overlay.style.opacity = '0';
        setTimeout(() => {
            overlay.classList.remove('active');
            overlay.style.opacity = '';
        }, 300);
        document.body.style.overflow = '';
    }
    
    function navigateGallery(direction) {
        if (isAnimating || galleryFotos.length <= 1) return;
        isAnimating = true;
        
        const animClass = direction > 0 ? 'slide-left' : 'slide-right';
        const nextIndex = (currentIndex + direction + galleryFotos.length) % galleryFotos.length;
        
        const img = document.getElementById('galleryImage');
        
        img.style.opacity = '0';
        img.style.transform = direction > 0 ? 'translateX(-40px) scale(0.95)' : 'translateX(40px) scale(0.95)';
        
        setTimeout(() => {
            currentIndex = nextIndex;
            img.src = '{{ asset("storage") }}/' + galleryFotos[currentIndex];
            img.className = 'gallery-image ' + animClass;
            
            setTimeout(() => {
                img.style.opacity = '1';
                img.style.transform = 'translateX(0) scale(1)';
            }, 50);
            
            updateGalleryUI();
            
            setTimeout(() => {
                isAnimating = false;
                img.className = 'gallery-image';
            }, 400);
        }, 200);
    }
    
    function updateGalleryImage(animClass = 'fade-in') {
        const img = document.getElementById('galleryImage');
        img.src = '{{ asset("storage") }}/' + galleryFotos[currentIndex];
        img.className = 'gallery-image ' + animClass;
        updateGalleryUI();
    }
    
    function updateGalleryUI() {
        document.getElementById('galleryCounter').textContent = `${currentIndex + 1} / ${galleryFotos.length}`;
        document.getElementById('galleryDownload').href = '{{ asset("storage") }}/' + galleryFotos[currentIndex];
        
        // Sembunyikan tombol panah jika hanya ada 1 foto
        const prevBtn = document.querySelector('.gallery-nav.prev');
        const nextBtn = document.querySelector('.gallery-nav.next');
        
        if (galleryFotos.length <= 1) {
            prevBtn.style.display = 'none';
            nextBtn.style.display = 'none';
        } else {
            prevBtn.style.display = 'flex';
            nextBtn.style.display = 'flex';
        }
        
        updateThumbnails();
    }
    
    function updateThumbnails() {
        const container = document.getElementById('galleryThumbnails');
        container.innerHTML = '';
        galleryFotos.forEach((foto, index) => {
            const thumb = document.createElement('div');
            thumb.className = 'gallery-thumb' + (index === currentIndex ? ' active' : '');
            thumb.innerHTML = `<img src="{{ asset('storage') }}/${foto}" alt="Thumb">`;
            thumb.onclick = () => {
                if (index !== currentIndex) {
                    navigateGallery(index - currentIndex);
                }
            };
            container.appendChild(thumb);
        });
    }
    
    document.addEventListener('keydown', (e) => {
        const overlay = document.getElementById('galleryOverlay');
        if (!overlay.classList.contains('active')) return;
        if (e.key === 'Escape') closeGallery();
        if (e.key === 'ArrowLeft') navigateGallery(-1);
        if (e.key === 'ArrowRight') navigateGallery(1);
    });
</script>
@endsection