@extends('sidongan.layouts.app')
@section('title', 'Detail Surat - SIDONGAN')

@section('content')
@php
    $currentUser = auth()->guard('sidongan')->user();
    $disposisiData = is_string($document->disposisi_data ?? '') ? json_decode($document->disposisi_data, true) : $document->disposisi_data;
@endphp

<style>
    /* =========================================
       Gallery Preview Styles (Dari Lapor Kegiatan)
       ========================================= */
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
    
    .gallery-container {
        position: relative;
        max-width: 85vw;
        max-height: 85vh;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    
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

<div>
    {{-- Header --}}
    <div style="background: linear-gradient(135deg, #0891b2, #14b8a6); padding: 1.5rem; border-radius: 0.75rem; margin-bottom: 1.5rem; color: white;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h1 style="font-size: 1.25rem; font-weight: 700; margin: 0;">Detail Surat</h1>
            
            {{-- Action Buttons --}}
            <div style="display: flex; gap: 0.75rem;">
                <a href="{{ route('sidongan.documents.index') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1rem; background: rgba(255,255,255,0.2); color: white; text-decoration: none; border-radius: 0.5rem; font-weight: 600; transition: all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali ke Daftar</span>
                </a>
                
                {{-- Tombol Edit (Hanya untuk Sekretaris & Status Menunggu Disposisi) --}}
                @if($currentUser && $currentUser->hasSidonganRole('sekretaris') && $document->status === 'menunggu_disposisi')
                <a href="{{ route('sidongan.documents.edit', $document) }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1rem; background: white; color: #0891b2; text-decoration: none; border-radius: 0.5rem; font-weight: 600; transition: all 0.2s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.2)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                    <i class="fas fa-edit"></i>
                    <span>Edit Surat</span>
                </a>
                @endif
                
                {{-- TOMBOL DISPOSISI (Hanya untuk Ketua & Status Menunggu) --}}
                @if($currentUser && $currentUser->hasSidonganRole('ketua') && $document->status === 'menunggu_disposisi')
                <a href="{{ route('sidongan.disposisi.form', $document) }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1rem; background: #f97316; color: white; text-decoration: none; border-radius: 0.5rem; font-weight: 600; transition: all 0.2s; box-shadow: 0 2px 8px rgba(249,115,22,0.3);" onmouseover="this.style.background='#ea580c'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(249,115,22,0.4)'" onmouseout="this.style.background='#f97316'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(249,115,22,0.3)'">
                    <i class="fas fa-paper-plane"></i>
                    <span>Disposisi</span>
                </a>
                @endif

                {{-- TOMBOL CETAK LEMBAR DISPOSISI (Muncul jika sudah ada disposisi) --}}
                @php
                    $disposisiData = is_string($document->disposisi_data ?? '') ? json_decode($document->disposisi_data, true) : $document->disposisi_data;
                @endphp
                @if(is_array($disposisiData) && isset($disposisiData['action']))
                <a href="{{ route('sidongan.documents.disposisi-print', $document) }}" 
                style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1rem; background: #10b981; color: white; text-decoration: none; border-radius: 0.5rem; font-weight: 600; transition: all 0.2s; box-shadow: 0 2px 8px rgba(16,185,129,0.3);" 
                onmouseover="this.style.background='#059669'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(16,185,129,0.4)'" 
                onmouseout="this.style.background='#10b981'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(16,185,129,0.3)'">
                    <i class="fas fa-print"></i>
                    <span>Cetak Disposisi</span>
                </a>
                @endif

                {{-- TOMBOL ARCHIVE (Hanya untuk Sekretaris & Status Selesai) --}}
                @if($currentUser && $currentUser->hasSidonganRole('sekretaris') && $document->status === 'selesai')
                <form action="{{ route('sidongan.documents.archive', $document) }}" method="POST" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin mengarsipkan surat ini?\n\nSurat yang sudah diarsipkan akan dipindahkan ke arsip dan tidak akan muncul di daftar surat aktif.')">
                    @csrf
                    @method('PATCH')
                    <button type="submit" 
                            style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1.25rem; background: #8b5cf6; color: white; text-decoration: none; border: none; border-radius: 0.5rem; font-weight: 600; font-size: 0.95rem; cursor: pointer; transition: all 0.2s; box-shadow: 0 2px 8px rgba(139,92,246,0.3);" 
                            onmouseover="this.style.background='#7c3aed'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(139,92,246,0.4)'" 
                            onmouseout="this.style.background='#8b5cf6'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(139,92,246,0.3)'">
                        <i class="fas fa-archive" style="font-size: 1rem;"></i>
                        <span>Arsipkan</span>
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>

    {{-- Status & Agenda Number --}}
    <div style="background: white; border-radius: 0.75rem; padding: 1.25rem; margin-bottom: 1.5rem; border: 1px solid #e2e8f0;">
        <div style="display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap; margin-bottom: 1rem;">
            <span style="display: inline-block; padding: 0.375rem 0.75rem; background: #dbeafe; color: #1e40af; border-radius: 0.375rem; font-size: 0.75rem; font-weight: 600; font-family: monospace;">
                {{ $document->agenda_number ?? 'AG/01/2024/001' }}
            </span>
            @php
                $statusConfig = [
                    'menunggu_disposisi' => ['bg' => '#fef3c7', 'text' => '#92400e', 'label' => 'Menunggu Disposisi Ketua'],
                    'berjalan' => ['bg' => '#dbeafe', 'text' => '#1e40af', 'label' => 'Sedang Berjalan'],
                    'menunggu_verifikasi' => ['bg' => '#ede9fe', 'text' => '#6b21a8', 'label' => 'Menunggu Verifikasi'],
                    'selesai' => ['bg' => '#d1fae5', 'text' => '#065f46', 'label' => 'Selesai'],
                    'diarsipkan' => ['bg' => '#f3e8ff', 'text' => '#7c3aed', 'label' => 'Diarsipkan'],
                ];
                $status = $statusConfig[$document->status] ?? ['bg' => '#f1f5f9', 'text' => '#475569', 'label' => $document->status];
            @endphp
            <span style="display: inline-block; padding: 0.375rem 0.75rem; background: {{ $status['bg'] }}; color: {{ $status['text'] }}; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">
                {{ $status['label'] }}
            </span>
        </div>
        <h2 style="font-size: 1.25rem; font-weight: 700; color: #0f172a; margin: 0;">
            {{ $document->subject ?? $document->title }}
        </h2>
    </div>

    {{-- Main Content - 2 Columns --}}
    <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; padding: 1.5rem; margin-bottom: 1.5rem;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            {{-- Data Surat --}}
            <div>
                <h3 style="font-size: 0.875rem; font-weight: 700; color: #0891b2; margin: 0 0 1rem 0; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-envelope" style="color: #14b8a6;"></i>
                    Data Surat
                </h3>
                <div style="display: grid; gap: 1rem;">
                    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 0.75rem; padding: 0.75rem 0; border-bottom: 1px solid #f1f5f9;">
                        <span style="font-size: 0.875rem; color: #64748b;">Pengirim</span>
                        <span style="font-size: 0.875rem; color: #0f172a; font-weight: 500;">{{ $document->sender ?? '-' }}</span>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 0.75rem; padding: 0.75rem 0; border-bottom: 1px solid #f1f5f9;">
                        <span style="font-size: 0.875rem; color: #64748b;">Nomor Surat</span>
                        <span style="font-size: 0.875rem; color: #0f172a; font-weight: 500;">{{ $document->document_number ?? '-' }}</span>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 0.75rem; padding: 0.75rem 0; border-bottom: 1px solid #f1f5f9;">
                        <span style="font-size: 0.875rem; color: #64748b;">Tanggal Surat</span>
                        <span style="font-size: 0.875rem; color: #0f172a; font-weight: 500;">{{ $document->document_date ? \Carbon\Carbon::parse($document->document_date)->locale('id')->translatedFormat('d F Y') : '-' }}</span>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 0.75rem; padding: 0.75rem 0;">
                        <span style="font-size: 0.875rem; color: #64748b;">Perihal</span>
                        <span style="font-size: 0.875rem; color: #0f172a; font-weight: 500;">{{ $document->subject ?? $document->title }}</span>
                    </div>
                </div>
            </div>

            {{-- Data Agenda --}}
            <div>
                <h3 style="font-size: 0.875rem; font-weight: 700; color: #0891b2; margin: 0 0 1rem 0; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-clipboard-list" style="color: #14b8a6;"></i>
                    Data Agenda
                </h3>
                <div style="display: grid; gap: 1rem;">
                    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 0.75rem; padding: 0.75rem 0; border-bottom: 1px solid #f1f5f9;">
                        <span style="font-size: 0.875rem; color: #64748b;">Nomor Agenda</span>
                        <span style="font-size: 0.875rem; color: #3b82f6; font-weight: 600; font-family: monospace;">{{ $document->agenda_number ?? '-' }}</span>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 0.75rem; padding: 0.75rem 0; border-bottom: 1px solid #f1f5f9;">
                        <span style="font-size: 0.875rem; color: #64748b;">Tanggal Agenda</span>
                        <span style="font-size: 0.875rem; color: #0f172a; font-weight: 500;">{{ $document->created_at ? \Carbon\Carbon::parse($document->created_at)->locale('id')->translatedFormat('d F Y') : '-' }}</span>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 0.75rem; padding: 0.75rem 0; border-bottom: 1px solid #f1f5f9;">
                        <span style="font-size: 0.875rem; color: #64748b;">Dibuat oleh</span>
                        <span style="font-size: 0.875rem; color: #0f172a; font-weight: 500;">{{ $document->creator->name ?? 'Sekretaris PKK' }}</span>
                    </div>
                    <div style="display: grid; gap: 0.5rem; padding: 0.75rem 0;">
                        <span style="font-size: 0.875rem; color: #64748b;">Saran Sekretaris:</span>
                        <div style="background: #eff6ff; border-radius: 0.5rem; padding: 0.75rem; font-size: 0.875rem; color: #1e40af;">
                            {{ $document->suggestion ?? $document->description ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Lampiran Surat (Membuka di Tab Baru) --}}
    @if($document->file_path)
    <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; padding: 1.5rem; margin-bottom: 1.5rem;">
        <h3 style="font-size: 0.875rem; font-weight: 700; color: #0891b2; margin: 0 0 1rem 0; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-paperclip" style="color: #14b8a6;"></i>
            Lampiran Surat
        </h3>
        <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" rel="noopener noreferrer" style="text-decoration: none; display: block;">
            <div style="background: #f8fafc; border-radius: 0.5rem; padding: 1rem; display: flex; align-items: center; gap: 1rem; transition: all 0.2s; border: 1px solid transparent; cursor: pointer;" 
                onmouseover="this.style.background='#f1f5f9'; this.style.borderColor='#e2e8f0'" 
                onmouseout="this.style.background='#f8fafc'; this.style.borderColor='transparent'">
                <div style="width: 3rem; height: 3rem; background: #fee2e2; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="fas fa-file-pdf" style="color: #ef4444; font-size: 1.25rem;"></i>
                </div>
                <div style="flex: 1; min-width: 0;">
                    <p style="font-size: 0.875rem; font-weight: 600; color: #0f172a; margin: 0 0 0.125rem 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ $document->file_name }}
                    </p>
                    <p style="font-size: 0.75rem; color: #64748b; margin: 0;">
                        {{ $document->file_size ? round($document->file_size / 1024, 2) . ' KB' : 'File surat' }}
                    </p>
                </div>
                <div style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1rem; background: #dbeafe; color: #2563eb; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 600;">
                    <i class="fas fa-external-link-alt"></i>
                    <span>Buka</span>
                </div>
            </div>
        </a>
    </div>
    @else
    <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; padding: 1.5rem; margin-bottom: 1.5rem; text-align: center;">
        <i class="fas fa-paperclip" style="color: #cbd5e1; font-size: 2rem; margin-bottom: 0.5rem;"></i>
        <p style="color: #64748b; margin: 0; font-size: 0.875rem;">Tidak ada lampiran file untuk surat ini.</p>
    </div>
    @endif

    {{-- DISPOSISI KETUA (Layout Diperbaiki) --}}
    @php
        $dispo = is_string($document->disposisi_data ?? '') ? json_decode($document->disposisi_data, true) : $document->disposisi_data;
    @endphp
    @if(is_array($dispo) && isset($dispo['action']))
    <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; padding: 1.5rem; margin-bottom: 1.5rem;">
        <h3 style="font-size: 0.875rem; font-weight: 700; color: #0891b2; margin: 0 0 1.25rem 0; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-share-square" style="color: #14b8a6;"></i>
            Disposisi Ketua
        </h3>
        
        <div style="display: grid; gap: 1.25rem;">
            {{-- Row 1: Didisposisikan kepada & Tindakan (Side by Side) --}}
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
                {{-- Didisposisikan kepada --}}
                <div>
                    <span style="font-size: 0.8rem; color: #64748b; display: block; margin-bottom: 0.5rem;">Didisposisikan kepada:</span>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                        @php
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
                        @endphp
                        @foreach($dispo['target_roles'] as $role)
                        <span style="display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.5rem 0.75rem; background: #dbeafe; color: #1e40af; border-radius: 0.5rem; font-size: 0.8rem; font-weight: 600;">
                            <i class="fas fa-user-group" style="font-size: 0.7rem;"></i>
                            {{ $rolesMap[$role] ?? ucfirst(str_replace('_', ' ', $role)) }}
                        </span>
                        @endforeach
                    </div>
                </div>
                
                {{-- Tindakan --}}
                <div>
                    <span style="font-size: 0.8rem; color: #64748b; display: block; margin-bottom: 0.5rem;">Tindakan:</span>
                    <span style="display: inline-block; padding: 0.5rem 1rem; background: #f3e8ff; color: #7c3aed; border-radius: 0.5rem; font-weight: 700; font-size: 0.9rem;">
                        {{ $dispo['action'] }}
                    </span>
                </div>
            </div>
            
            {{-- Row 2: Komentar Disposisi --}}
            @if(isset($dispo['comment']) && $dispo['comment'])
            <div style="background: #f8fafc; border-left: 4px solid #cbd5e1; border-radius: 0 0.5rem 0.5rem 0; padding: 1rem;">
                <span style="font-size: 0.8rem; color: #475569; font-weight: 600; display: block; margin-bottom: 0.25rem;">Komentar Disposisi:</span>
                <p style="font-size: 0.9rem; color: #334155; margin: 0; font-style: italic;">"{{ $dispo['comment'] }}"</p>
            </div>
            @endif
            
            {{-- Footer: Didisposisikan oleh User yang Sebenarnya --}}
            <div style="margin-top: 0.5rem; padding-top: 1rem; border-top: 1px solid #e2e8f0; font-size: 0.8rem; color: #94a3b8; text-align: right;">
                @php
                    $disposedBy = null;
                    if (isset($dispo['disposed_by'])) {
                        $disposedBy = \App\Models\User::find($dispo['disposed_by']);
                    }
                @endphp
                Didisposisikan oleh <strong style="color: #f97316;">{{ $disposedBy->name ?? 'Ketua PKK' }}</strong> pada 
                {{ isset($dispo['disposed_at']) ? \Carbon\Carbon::parse($dispo['disposed_at'])->locale('id')->translatedFormat('d M Y, H.i') : $document->updated_at->locale('id')->translatedFormat('d M Y, H.i') }}
            </div>
        </div>
    </div>
    @endif

    {{-- ✅ LAPORAN KEGIATAN (Dynamic Colors) --}}
    @if(isset($activityReports) && $activityReports->count() > 0)
    <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; padding: 1.5rem; margin-bottom: 1.5rem;">
        <h3 style="font-size: 0.875rem; font-weight: 700; color: #0891b2; margin: 0 0 1rem 0; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-clipboard-list" style="color: #14b8a6;"></i>
            Laporan Kegiatan
        </h3>
        
        <div style="display: grid; gap: 1rem;">
            @foreach($activityReports as $report)
                @php
                    // Konfigurasi Warna Berdasarkan Status
                    $theme = [
                        'draft' => ['bg' => '#eff6ff', 'border' => '#bfdbfe', 'color' => '#1e40af', 'btn' => '#3b82f6', 'label' => 'Draft'],
                        'menunggu_verifikasi' => ['bg' => '#fff7ed', 'border' => '#fed7aa', 'color' => '#9a3412', 'btn' => '#f97316', 'label' => 'Menunggu Verifikasi'],
                        'disetujui' => ['bg' => '#f0fdf4', 'border' => '#bbf7d0', 'color' => '#166534', 'btn' => '#22c55e', 'label' => 'Disetujui'],
                        'ditolak' => ['bg' => '#fef2f2', 'border' => '#fecaca', 'color' => '#991b1b', 'btn' => '#ef4444', 'label' => 'Ditolak'],
                    ];
                    
                    // Ambil style berdasarkan status, fallback ke 'menunggu_verifikasi'
                    $style = $theme[$report->status] ?? $theme['menunggu_verifikasi'];
                @endphp
                
                {{-- Card Laporan dengan Warna Dinamis --}}
                <div style="background: {{ $style['bg'] }}; border: 2px solid {{ $style['border'] }}; border-radius: 0.75rem; padding: 1.25rem; margin-bottom: 1rem;">
                    
                    {{-- Header Card --}}
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; padding-bottom: 0.75rem; border-bottom: 1px solid {{ $style['border'] }};">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            {{-- Avatar dengan Warna Status --}}
                            <div style="width: 40px; height: 40px; background: {{ $style['color'] }}; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.9rem; flex-shrink: 0;">
                                {{ substr($report->creator->name ?? 'U', 0, 1) }}
                            </div>
                            <div>
                                <p style="font-weight: 700; color: {{ $style['color'] }}; margin: 0; font-size: 0.9rem;">{{ $report->creator->name ?? 'Sekretaris PKK' }}</p>
                                <p style="font-size: 0.75rem; color: #64748b; margin: 0;">{{ $report->created_at->locale('id')->translatedFormat('d M Y, H.i') }}</p>
                            </div>
                        </div>
                        
                        {{-- Badge Status (Background Putih) --}}
                        <span style="padding: 0.25rem 0.75rem; background: white; color: {{ $style['color'] }}; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; border: 1px solid {{ $style['border'] }};">
                            {{ $style['label'] }}
                        </span>
                    </div>
                    
                    {{-- Detail Info --}}
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <div>
                            <span style="font-size: 0.75rem; color: {{ $style['color'] }}; font-weight: 600; display: block; margin-bottom: 0.25rem;">Tanggal Kegiatan:</span>
                            <p style="font-size: 0.9rem; color: #334155; margin: 0;">{{ $report->kegiatan_tanggal->locale('id')->translatedFormat('d M Y') }}</p>
                        </div>
                        <div>
                            <span style="font-size: 0.75rem; color: {{ $style['color'] }}; font-weight: 600; display: block; margin-bottom: 0.25rem;">Lokasi:</span>
                            <p style="font-size: 0.9rem; color: #334155; margin: 0;">{{ $report->lokasi ?? '-' }}</p>
                        </div>
                    </div>
                    
                    {{-- Deskripsi (Background Putih untuk kontras) --}}
                    <div style="background: white; padding: 1rem; border-radius: 0.5rem; border: 1px solid {{ $style['border'] }}; margin-bottom: 1rem;">
                        <p style="font-size: 0.9rem; color: #374151; margin: 0; line-height: 1.6;">
                            {{ $report->deskripsi }}
                        </p>
                    </div>
                    
                    {{-- Catatan Verifikasi (Jika Ada) --}}
                    @php
                        $vData = $report->verifikasi_data ?? null;
                        if (is_string($vData)) $vData = json_decode($vData, true);
                        $catatan = $vData['comment'] ?? null;
                    @endphp
                    @if($catatan)
                    <div style="margin-bottom: 1rem; padding: 0.75rem 1rem; border-radius: 0.5rem; background: rgba(255,255,255,0.6); border-left: 4px solid {{ $style['color'] }};">
                        <span style="font-size: 0.75rem; font-weight: 700; color: {{ $style['color'] }}; display: block; margin-bottom: 0.25rem;">
                            <i class="fas fa-{{ $report->status == 'disetujui' ? 'check-circle' : 'times-circle' }}"></i>
                            {{ $report->status == 'disetujui' ? 'Catatan Verifikasi:' : 'Alasan Penolakan:' }}
                        </span>
                        <p style="font-size: 0.85rem; color: #4b5563; margin: 0; line-height: 1.4; font-style: italic;">
                            "{{ $catatan }}"
                        </p>
                    </div>
                    @endif
                    
                    {{-- Foto Dokumentasi --}}
                    @php
                        $fotos = is_string($report->fotos ?? '') ? json_decode($report->fotos, true) : $report->fotos;
                        $fotosArray = is_array($fotos) ? $fotos : [];
                    @endphp
                    @if(count($fotosArray) > 0)
                    <div style="margin-bottom: 1rem;">
                        <span style="font-size: 0.75rem; color: {{ $style['color'] }}; font-weight: 600; display: block; margin-bottom: 0.5rem;">Dokumentasi ({{ count($fotosArray) }} foto):</span>
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(70px, 1fr)); gap: 0.5rem;">
                            @foreach($fotosArray as $index => $foto)
                            <div onclick="openReportGallery({{ $report->id }}, {{ $index }})" 
                                style="cursor: pointer; border-radius: 0.5rem; overflow: hidden; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: all 0.2s; aspect-ratio: 1;"
                                onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.15)'" 
                                onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">
                                <img src="{{ asset('storage/' . $foto) }}" alt="Dokumentasi" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    {{-- Tombol Lihat Detail (Warna Dinamis) --}}
                    <div style="text-align: right;">
                        <a href="{{ route('sidongan.lapor_kegiatan.show', $report->id) }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; background: {{ $style['btn'] }}; color: white; text-decoration: none; border-radius: 0.5rem; font-size: 0.85rem; font-weight: 600; transition: all 0.2s;" onmouseover="this.style.opacity='0.9'; this.style.transform='scale(1.05)'" onmouseout="this.style.opacity='1'; this.style.transform='scale(1)'">
                            <i class="fas fa-eye"></i>
                            <span>Lihat Detail</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Alur Kegiatan - Timeline Style --}}
    <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; padding: 1.5rem;">
        <h3 style="font-size: 0.875rem; font-weight: 700; color: #0891b2; margin: 0 0 1.5rem 0; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-stream" style="color: #14b8a6;"></i>
            Alur Kegiatan
        </h3>
        
        <div style="display: flex; flex-direction: column; gap: 0;">
            
            @php
                $reports = $activityReports ?? collect();
                $hasDisposisi = !empty($document->disposisi_data);
                $dispo = $hasDisposisi ? (is_string($document->disposisi_data) ? json_decode($document->disposisi_data, true) : $document->disposisi_data) : null;
                
                // Hitung total item timeline
                $totalItems = 1; // Upload
                if ($hasDisposisi) $totalItems++;
                foreach ($reports as $r) {
                    $totalItems++; // Laporan
                    if (in_array($r->status ?? '', ['disetujui', 'ditolak'])) {
                        $totalItems++; // Verifikasi
                    }
                }
                $currentItem = 0;
                
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
            @endphp
            
            {{-- Timeline Item 1: Sekretaris Upload --}}
            @php $currentItem++; @endphp
            <div style="display: flex; gap: 1.25rem; position: relative;">
                <div style="display: flex; flex-direction: column; align-items: center; flex-shrink: 0;">
                    <div style="width: 2.5rem; height: 2.5rem; background: #3b82f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; position: relative; z-index: 2;">
                        <i class="fas fa-user" style="color: white; font-size: 0.875rem;"></i>
                    </div>
                    @if($currentItem < $totalItems)
                        <div style="width: 2px; flex: 1; background: linear-gradient(to bottom, #3b82f6, #e2e8f0); min-height: 2rem; margin: 0.25rem 0;"></div>
                    @endif
                </div>
                
                <div style="flex: 1; padding-bottom: {{ $currentItem < $totalItems ? '1.5rem' : '0' }};">
                    <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 0.25rem;">
                        <h4 style="font-size: 0.875rem; font-weight: 600; color: #0f172a; margin: 0;">Sekretaris PKK</h4>
                        <span style="font-size: 0.75rem; color: #94a3b8;">{{ $document->created_at->locale('id')->translatedFormat('d M Y, H.i') }}</span>
                    </div>
                    <p style="font-size: 0.875rem; color: #64748b; margin: 0;">
                        Membuat agenda dan mengupload surat dari {{ $document->sender ?? 'Pengirim' }}
                    </p>
                </div>
            </div>

            {{-- Timeline Item 2: Disposisi --}}
            @if($hasDisposisi)
                @php $currentItem++; @endphp
                <div style="display: flex; gap: 1.25rem; position: relative;">
                    <div style="display: flex; flex-direction: column; align-items: center; flex-shrink: 0;">
                        <div style="width: 2.5rem; height: 2.5rem; background: #f97316; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; position: relative; z-index: 2;">
                            <i class="fas fa-share-alt" style="color: white; font-size: 0.875rem;"></i>
                        </div>
                        @if($currentItem < $totalItems)
                            <div style="width: 2px; flex: 1; background: linear-gradient(to bottom, #f97316, #e2e8f0); min-height: 2rem; margin: 0.25rem 0;"></div>
                        @endif
                    </div>
                    
                    <div style="flex: 1; padding-bottom: {{ $currentItem < $totalItems ? '1.5rem' : '0' }};">
                        <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 0.25rem;">
                            <h4 style="font-size: 0.875rem; font-weight: 600; color: #0f172a; margin: 0;">Ketua PKK</h4>
                            <span style="font-size: 0.75rem; color: #94a3b8;">
                                {{ isset($dispo['disposed_at']) ? \Carbon\Carbon::parse($dispo['disposed_at'])->locale('id')->translatedFormat('d M Y, H:i') : '-' }}
                            </span>
                        </div>
                        <p style="font-size: 0.875rem; color: #64748b; margin: 0;">
                            Melakukan disposisi kepada:
                            @if(isset($dispo['target_roles']))
                                @foreach($dispo['target_roles'] as $role)
                                    <span style="display: inline-block; background: #dbeafe; color: #1e40af; padding: 0.125rem 0.5rem; border-radius: 0.25rem; font-size: 0.75rem; margin: 0.125rem;">
                                        {{ $rolesMap[$role] ?? ucfirst(str_replace('_', ' ', $role)) }}
                                    </span>
                                @endforeach
                            @endif
                        </p>
                        @if(isset($dispo['comment']) && $dispo['comment'])
                        <p style="font-size: 0.875rem; color: #475569; margin: 0.5rem 0 0 0; font-style: italic;">
                            "{{ $dispo['comment'] }}"
                        </p>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Timeline Item 3: Laporan Kegiatan + Verifikasi --}}
            @forelse($reports as $report)
                @php
                    $currentItem++;
                    // ✅ CEK VERIFIKASI DARI FIELD STATUS (bukan JSON verifikasi_data)
                    $isVerified = in_array($report->status ?? '', ['disetujui', 'ditolak']);
                    $verifStatus = $report->status ?? null;
                    $verifColor = $verifStatus === 'disetujui' ? '#10b981' : '#ef4444';
                    $verifIcon = $verifStatus === 'disetujui' ? 'check' : 'times';
                    $verifComment = $report->catatan_verifikasi ?? null;
                    $verifAt = $report->verified_at ?? $report->updated_at;
                @endphp

                {{-- Timeline: Sekretaris Buat Laporan --}}
                <div style="display: flex; gap: 1.25rem; position: relative;">
                    <div style="display: flex; flex-direction: column; align-items: center; flex-shrink: 0;">
                        <div style="width: 2.5rem; height: 2.5rem; background: #22c55e; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; position: relative; z-index: 2;">
                            <i class="fas fa-clipboard-list" style="color: white; font-size: 0.875rem;"></i>
                        </div>
                        @if($currentItem < $totalItems)
                            <div style="width: 2px; flex: 1; background: linear-gradient(to bottom, #22c55e, {{ $isVerified ? $verifColor : '#e2e8f0' }}); min-height: 2rem; margin: 0.25rem 0;"></div>
                        @endif
                    </div>
                    
                    <div style="flex: 1; padding-bottom: {{ $currentItem < $totalItems ? '1.5rem' : '0' }};">
                        <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 0.25rem;">
                            <h4 style="font-size: 0.875rem; font-weight: 600; color: #0f172a; margin: 0;">
                                {{ $report->creator->name ?? 'Sekretaris PKK' }}
                            </h4>
                            <span style="font-size: 0.75rem; color: #94a3b8;">
                                {{ $report->created_at->locale('id')->translatedFormat('d M Y, H:i') }}
                            </span>
                        </div>
                        <p style="font-size: 0.875rem; color: #64748b; margin: 0;">
                            Membuat laporan kegiatan: <strong style="color: #0f172a;">{{ $report->kegiatan_nama }}</strong>
                        </p>
                        @if($report->lokasi)
                        <p style="font-size: 0.8rem; color: #64748b; margin: 0.25rem 0 0 0;">
                            <i class="fas fa-map-marker-alt" style="margin-right: 0.25rem;"></i>
                            {{ $report->lokasi }}
                        </p>
                        @endif
                    </div>
                </div>

                {{-- ✅ Timeline: Ketua Verifikasi Laporan --}}
                @if($isVerified)
                    @php $currentItem++; @endphp
                    <div style="display: flex; gap: 1.25rem; position: relative;">
                        <div style="display: flex; flex-direction: column; align-items: center; flex-shrink: 0;">
                            <div style="width: 2.5rem; height: 2.5rem; background: {{ $verifColor }}; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; position: relative; z-index: 2; box-shadow: 0 0 0 4px {{ $verifColor }}30;">
                                <i class="fas fa-{{ $verifIcon }}" style="color: white; font-size: 0.875rem;"></i>
                            </div>
                            @if($currentItem < $totalItems)
                                <div style="width: 2px; flex: 1; background: linear-gradient(to bottom, {{ $verifColor }}, #e2e8f0); min-height: 2rem; margin: 0.25rem 0;"></div>
                            @endif
                        </div>
                        
                        <div style="flex: 1; padding-bottom: {{ $currentItem < $totalItems ? '1.5rem' : '0' }};">
                            <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 0.25rem;">
                                <h4 style="font-size: 0.875rem; font-weight: 600; color: #0f172a; margin: 0;">
                                    Ketua PKK
                                </h4>
                                <span style="font-size: 0.75rem; color: #94a3b8;">
                                    {{ \Carbon\Carbon::parse($verifAt)->locale('id')->translatedFormat('d M Y, H:i') }}
                                </span>
                            </div>
                            
                            @if($verifStatus === 'disetujui')
                                <p style="font-size: 0.875rem; color: #64748b; margin: 0;">
                                    <span style="display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.25rem 0.65rem; background: #d1fae5; color: #065f46; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; margin-right: 0.5rem;">
                                        <i class="fas fa-check-circle" style="font-size: 0.7rem;"></i>
                                        Menyetujui
                                    </span>
                                    laporan kegiatan <strong style="color: #0f172a;">{{ $report->kegiatan_nama }}</strong>
                                </p>
                            @elseif($verifStatus === 'ditolak')
                                <p style="font-size: 0.875rem; color: #64748b; margin: 0;">
                                    <span style="display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.25rem 0.65rem; background: #fee2e2; color: #991b1b; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; margin-right: 0.5rem;">
                                        <i class="fas fa-times-circle" style="font-size: 0.7rem;"></i>
                                        Menolak
                                    </span>
                                    laporan kegiatan <strong style="color: #0f172a;">{{ $report->kegiatan_nama }}</strong>
                                </p>
                            @endif
                            
                            @if($verifComment)
                            <div style="margin-top: 0.5rem; padding: 0.5rem 0.75rem; background: {{ $verifStatus === 'disetujui' ? '#f0fdf4' : '#fef2f2' }}; border-left: 3px solid {{ $verifColor }}; border-radius: 0.25rem; font-size: 0.8rem; color: #475569; font-style: italic;">
                                "{{ $verifComment }}"
                            </div>
                            @endif
                        </div>
                    </div>
                @endif
            @empty
                {{-- Jika tidak ada laporan, tutup garis dari disposisi --}}
                @if($hasDisposisi && $currentItem < $totalItems)
                <div style="display: flex; gap: 1.25rem;">
                    <div style="width: 2.5rem; display: flex; flex-direction: column; align-items: center; flex-shrink: 0;">
                        <div style="width: 2px; height: 2rem; background: linear-gradient(to bottom, #f97316, transparent);"></div>
                    </div>
                    <div style="flex: 1;"></div>
                </div>
                @endif
            @endforelse
        </div>
    </div>
</div>

{{-- MODAL GALLERY PREVIEW --}}
<div id="galleryOverlay" class="gallery-overlay" onclick="closeGallery(event)">
    <button class="gallery-close" onclick="closeGallery()">
        <i class="fas fa-times"></i>
    </button>
    
    <div class="gallery-container" onclick="event.stopPropagation()">
        <div class="gallery-image-wrapper">
            <img id="galleryImage" class="gallery-image" src="" alt="Lampiran Surat">
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
    // ============================================
    // GALLERY UNIVERSAL - Satu Sistem untuk Semua
    // ============================================
    
    // Data foto dari server
    const documentFoto = @json($document->file_path ? [$document->file_path] : []);
    
    @php
        $allReportFotos = [];
        if(isset($activityReports)) {
            foreach($activityReports as $report) {
                $fotos = is_string($report->fotos ?? '') ? json_decode($report->fotos, true) : $report->fotos;
                $fotosArray = is_array($fotos) ? $fotos : [];
                if(count($fotosArray) > 0) {
                    $allReportFotos[$report->id] = $fotosArray;
                }
            }
        }
    @endphp
    const reportFotosData = @json($allReportFotos);
    
    // State gallery
    let currentGallery = {
        fotos: [],
        currentIndex: 0,
        isAnimating: false
    };
    
    // ============================================
    // FUNGSI: Buka Gallery untuk Lampiran Surat
    // ============================================
    function openGallery(index) {
        if (documentFoto.length === 0) return;
        currentGallery.fotos = documentFoto;
        currentGallery.currentIndex = index;
        showGalleryModal();
    }
    
    // ============================================
    // FUNGSI: Buka Gallery untuk Foto Laporan
    // ============================================
    function openReportGallery(reportId, index) {
        if (!reportFotosData[reportId] || reportFotosData[reportId].length === 0) return;
        currentGallery.fotos = reportFotosData[reportId];
        currentGallery.currentIndex = index;
        showGalleryModal();
    }
    
    // ============================================
    // FUNGSI: Tampilkan Modal Gallery
    // ============================================
    function showGalleryModal() {
        const overlay = document.getElementById('galleryOverlay');
        if (!overlay) return;
        
        updateGalleryImage('fade-in');
        updateGalleryUI();
        overlay.classList.add('active');
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
    
    // ============================================
    // FUNGSI: Navigasi Antar Foto
    // ============================================
    function navigateGallery(direction) {
        if (currentGallery.isAnimating || currentGallery.fotos.length <= 1) return;
        currentGallery.isAnimating = true;
        
        const animClass = direction > 0 ? 'slide-left' : 'slide-right';
        const nextIndex = (currentGallery.currentIndex + direction + currentGallery.fotos.length) % currentGallery.fotos.length;
        
        const img = document.getElementById('galleryImage');
        img.style.opacity = '0';
        img.style.transform = direction > 0 ? 'translateX(-40px) scale(0.95)' : 'translateX(40px) scale(0.95)';
        
        setTimeout(() => {
            currentGallery.currentIndex = nextIndex;
            const fotoPath = currentGallery.fotos[currentGallery.currentIndex];
            
            // Cek apakah path sudah full URL atau relatif
            if (fotoPath.startsWith('http')) {
                img.src = fotoPath;
            } else {
                img.src = '{{ asset("storage") }}/' + fotoPath;
            }
            
            img.className = 'gallery-image ' + animClass;
            
            setTimeout(() => {
                img.style.opacity = '1';
                img.style.transform = 'translateX(0) scale(1)';
            }, 50);
            
            updateGalleryUI();
            
            setTimeout(() => {
                currentGallery.isAnimating = false;
                img.className = 'gallery-image';
            }, 400);
        }, 200);
    }
    
    // ============================================
    // FUNGSI: Update Tampilan Gallery
    // ============================================
    function updateGalleryImage(animClass) {
        const img = document.getElementById('galleryImage');
        const fotoPath = currentGallery.fotos[currentGallery.currentIndex];
        
        if (fotoPath.startsWith('http')) {
            img.src = fotoPath;
        } else {
            img.src = '{{ asset("storage") }}/' + fotoPath;
        }
        
        img.className = 'gallery-image ' + (animClass || 'fade-in');
        updateGalleryUI();
    }
    
    function updateGalleryUI() {
        const counter = document.getElementById('galleryCounter');
        const downloadBtn = document.getElementById('galleryDownload');
        const fotoPath = currentGallery.fotos[currentGallery.currentIndex];
        const fullUrl = fotoPath.startsWith('http') ? fotoPath : '{{ asset("storage") }}/' + fotoPath;
        
        if (counter) {
            counter.textContent = (currentGallery.currentIndex + 1) + ' / ' + currentGallery.fotos.length;
        }
        if (downloadBtn) {
            downloadBtn.href = fullUrl;
        }
        
        const prevBtn = document.querySelector('.gallery-nav.prev');
        const nextBtn = document.querySelector('.gallery-nav.next');
        
        if (currentGallery.fotos.length <= 1) {
            if (prevBtn) prevBtn.style.display = 'none';
            if (nextBtn) nextBtn.style.display = 'none';
        } else {
            if (prevBtn) prevBtn.style.display = 'flex';
            if (nextBtn) nextBtn.style.display = 'flex';
        }
        
        updateThumbnails();
    }
    
    function updateThumbnails() {
        const container = document.getElementById('galleryThumbnails');
        if (!container) return;
        container.innerHTML = '';
        
        currentGallery.fotos.forEach((foto, index) => {
            const fullUrl = foto.startsWith('http') ? foto : '{{ asset("storage") }}/' + foto;
            const thumb = document.createElement('div');
            thumb.className = 'gallery-thumb' + (index === currentGallery.currentIndex ? ' active' : '');
            thumb.innerHTML = '<img src="' + fullUrl + '" alt="Thumb">';
            thumb.onclick = () => {
                if (index !== currentGallery.currentIndex) {
                    navigateGallery(index - currentGallery.currentIndex);
                }
            };
            container.appendChild(thumb);
        });
    }
    
    // ============================================
    // KEYBOARD NAVIGATION
    // ============================================
    document.addEventListener('keydown', (e) => {
        const overlay = document.getElementById('galleryOverlay');
        if (!overlay || !overlay.classList.contains('active')) return;
        if (e.key === 'Escape') closeGallery();
        if (e.key === 'ArrowLeft') navigateGallery(-1);
        if (e.key === 'ArrowRight') navigateGallery(1);
    });
</script>
@endsection