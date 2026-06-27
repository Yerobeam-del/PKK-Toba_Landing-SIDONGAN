@extends('sidongan.layouts.app')
@section('title', 'Detail Surat - SIDONGAN')

@section('content')
@php
    $currentUser = auth()->guard('sidongan')->user();
    $disposisiData = is_string($document->disposisi_data ?? '') ? json_decode($document->disposisi_data, true) : $document->disposisi_data;
    $dispo = $disposisiData;
    
    $statusConfig = [
        'menunggu_disposisi' => ['class' => 'ds-status-menunggu_disposisi', 'label' => 'Menunggu Disposisi Ketua'],
        'berjalan' => ['class' => 'ds-status-berjalan', 'label' => 'Sedang Berjalan'],
        'menunggu_verifikasi' => ['class' => 'ds-status-menunggu_verifikasi', 'label' => 'Menunggu Verifikasi'],
        'selesai' => ['class' => 'ds-status-selesai', 'label' => 'Selesai'],
        'diarsipkan' => ['class' => 'ds-status-diarsipkan', 'label' => 'Diarsipkan'],
    ];
    $status = $statusConfig[$document->status] ?? ['class' => '', 'label' => $document->status];
    
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
    
    $userReceivedDisposisi = false;
    if (is_array($disposisiData) && isset($disposisiData['target_roles']) && $document->status === 'berjalan') {
        $userReceivedDisposisi = in_array($currentUser->sidongan_role, $disposisiData['target_roles']);
    }
@endphp

<link rel="stylesheet" href="{{ asset('assets/sidongan/css/detail-surat.css') }}">

<div class="ds-container">
    {{-- Header --}}
    <div class="ds-header">
        <div class="ds-header-top">
            <div>
                <h1>Detail Surat</h1>
                <p>Informasi lengkap surat masuk</p>
            </div>
            
            <div class="ds-header-actions">
                <button onclick="window.history.back()" class="ds-btn ds-btn-back" type="button">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali</span>
                </button>
                
                @if($currentUser && $currentUser->hasSidonganRole('sekretaris') && $document->status === 'menunggu_disposisi')
                <a href="{{ route('sidongan.documents.edit', $document) }}" class="ds-btn ds-btn-edit">
                    <i class="fas fa-edit"></i>
                    <span>Edit Surat</span>
                </a>
                @endif
                
                @if($currentUser && $currentUser->hasSidonganRole('ketua') && $document->status === 'menunggu_disposisi')
                <a href="{{ route('sidongan.disposisi.form', $document) }}" class="ds-btn ds-btn-disposisi">
                    <i class="fas fa-paper-plane"></i>
                    <span>Disposisi</span>
                </a>
                @endif

                @if(is_array($disposisiData) && isset($disposisiData['action']) && ($currentUser->hasSidonganRole('sekretaris') || $currentUser->hasSidonganRole('ketua')))
                <a href="{{ route('sidongan.documents.disposisi-print', $document) }}" class="ds-btn ds-btn-print">
                    <i class="fas fa-print"></i>
                    <span>Cetak Disposisi</span>
                </a>
                @endif

                @if($userReceivedDisposisi)
                <a href="{{ route('sidongan.lapor_kegiatan.create', ['document_id' => $document->id]) }}" class="ds-btn ds-btn-lapor">
                    <i class="fas fa-clipboard-list"></i>
                    <span>Lapor Kegiatan</span>
                </a>
                @endif

                @if($currentUser && $currentUser->hasSidonganRole('sekretaris') && $document->status === 'selesai')
                <form action="{{ route('sidongan.documents.archive', $document) }}" method="POST" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin mengarsipkan surat ini?\n\nSurat yang sudah diarsipkan akan dipindahkan ke arsip dan tidak akan muncul di daftar surat aktif.')">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="ds-btn ds-btn-archive">
                        <i class="fas fa-archive"></i>
                        <span>Arsipkan</span>
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>

    {{-- Status & Subject --}}
    <div class="ds-status-section">
        <div class="ds-status-badges">
            <span class="ds-badge-agenda">{{ $document->agenda_number }}</span>
            <span class="ds-badge-status {{ $status['class'] }}">{{ $status['label'] }}</span>
        </div>
        <h2 class="ds-subject-title">{{ $document->subject ?? $document->title }}</h2>
    </div>

    {{-- Data Surat & Agenda --}}
    <div class="ds-card">
        <div class="ds-card-header ds-card-header-blue">
            <h3>
                <i class="fas fa-file-alt"></i>
                Informasi Surat
            </h3>
        </div>
        <div class="ds-card-body">
            <div class="ds-info-grid">
                {{-- Data Surat --}}
                <div>
                    <h4 style="font-size: 1.05rem; font-weight: 700; color: #0891b2; margin: 0 0 1rem 0; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-envelope" style="color: #14b8a6;"></i>
                        Data Surat
                    </h4>
                    <div class="ds-info-row">
                        <span class="ds-info-label">Pengirim</span>
                        <span class="ds-info-value">{{ $document->sender ?? '-' }}</span>
                    </div>
                    <div class="ds-info-row">
                        <span class="ds-info-label">Nomor Surat</span>
                        <span class="ds-info-value">{{ $document->document_number ?? '-' }}</span>
                    </div>
                    <div class="ds-info-row">
                        <span class="ds-info-label">Tanggal Surat</span>
                        <span class="ds-info-value">
                            {{ $document->document_date ? \Carbon\Carbon::parse($document->document_date)->locale('id')->translatedFormat('d F Y') : '-' }}
                        </span>
                    </div>
                    <div class="ds-info-row">
                        <span class="ds-info-label">Perihal</span>
                        <span class="ds-info-value">{{ $document->subject ?? $document->title }}</span>
                    </div>
                </div>

                {{-- Data Agenda --}}
                <div>
                    <h4 style="font-size: 1.05rem; font-weight: 700; color: #0891b2; margin: 0 0 1rem 0; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-clipboard-list" style="color: #14b8a6;"></i>
                        Data Agenda
                    </h4>
                    <div class="ds-info-row">
                        <span class="ds-info-label">Nomor Agenda</span>
                        <span class="ds-info-value ds-info-value-mono">{{ $document->agenda_number ?? '-' }}</span>
                    </div>
                    <div class="ds-info-row">
                        <span class="ds-info-label">Tanggal Agenda</span>
                        <span class="ds-info-value">
                            {{ $document->created_at ? \Carbon\Carbon::parse($document->created_at)->locale('id')->translatedFormat('d F Y') : '-' }}
                        </span>
                    </div>
                    <div class="ds-info-row">
                        <span class="ds-info-label">Dibuat oleh</span>
                        <span class="ds-info-value">{{ $document->creator->name ?? 'Sekretaris PKK' }}</span>
                    </div>
                    <div style="padding-top: 0.75rem;">
                        <span class="ds-info-label">Saran Sekretaris:</span>
                        <div class="ds-saran-box">
                            {{ $document->suggestion ?? $document->description ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Lampiran Surat --}}
    <div class="ds-card">
        <div class="ds-card-header ds-card-header-green">
            <h3>
                <i class="fas fa-paperclip"></i>
                Lampiran Surat
            </h3>
        </div>
        <div class="ds-card-body">
            @if($document->file_path)
            <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" rel="noopener noreferrer" class="ds-lampiran-box">
                <div class="ds-lampiran-icon">
                    <i class="fas fa-file-pdf"></i>
                </div>
                <div class="ds-lampiran-info">
                    <p class="ds-lampiran-name">{{ $document->file_name }}</p>
                    <p class="ds-lampiran-size">
                        {{ $document->file_size ? round($document->file_size / 1024, 2) . ' KB' : 'File surat' }}
                    </p>
                </div>
                <div class="ds-lampiran-btn">
                    <i class="fas fa-external-link-alt"></i>
                    <span>Buka</span>
                </div>
            </a>
            @else
            <div style="text-align: center; padding: 2rem;">
                <i class="fas fa-paperclip" style="color: #cbd5e1; font-size: 2.5rem; margin-bottom: 0.75rem;"></i>
                <p style="color: #64748b; margin: 0; font-size: 1rem;">Tidak ada lampiran file untuk surat ini.</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Disposisi Ketua --}}
    @if(is_array($dispo) && isset($dispo['action']))
    <div class="ds-card">
        <div class="ds-card-header ds-card-header-orange">
            <h3>
                <i class="fas fa-share-square" style="color: #f97316;"></i>
                Disposisi Ketua
            </h3>
        </div>
        <div class="ds-card-body">
            <div class="ds-disposisi-grid">
                <div class="ds-disposisi-row">
                    <div>
                        <span class="ds-disposisi-label">Didisposisikan kepada:</span>
                        <div class="ds-disposisi-targets">
                            @foreach($dispo['target_roles'] as $role)
                            <span class="ds-disposisi-badge">
                                <i class="fas fa-user-group"></i>
                                {{ $rolesMap[$role] ?? ucfirst(str_replace('_', ' ', $role)) }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <span class="ds-disposisi-label">Tindakan:</span>
                        <span class="ds-disposisi-action">{{ $dispo['action'] }}</span>
                    </div>
                </div>
                
                @if(isset($dispo['comment']) && $dispo['comment'])
                <div class="ds-disposisi-comment">
                    <span class="ds-disposisi-comment-label">Komentar Disposisi:</span>
                    <p class="ds-disposisi-comment-text">"{{ $dispo['comment'] }}"</p>
                </div>
                @endif
                
                <div class="ds-disposisi-footer">
                    @php
                        $disposedBy = null;
                        if (isset($dispo['disposed_by'])) {
                            $disposedBy = \App\Models\User::find($dispo['disposed_by']);
                        }
                    @endphp
                    Didisposisikan oleh <strong>{{ $disposedBy->name ?? 'Ketua PKK' }}</strong> pada 
                    {{ isset($dispo['disposed_at']) ? \Carbon\Carbon::parse($dispo['disposed_at'])->locale('id')->translatedFormat('d M Y, H.i') : $document->updated_at->locale('id')->translatedFormat('d M Y, H.i') }}
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Laporan Kegiatan --}}
    @if(isset($activityReports) && $activityReports->count() > 0)
    <div class="ds-card">
        <div class="ds-card-header ds-card-header-blue">
            <h3>
                <i class="fas fa-clipboard-list"></i>
                Laporan Kegiatan
            </h3>
        </div>
        <div class="ds-card-body">
            @foreach($activityReports as $report)
                @php
                    $theme = [
                        'draft' => 'ds-laporan-card-draft',
                        'menunggu_verifikasi' => 'ds-laporan-card-menunggu_verifikasi',
                        'disetujui' => 'ds-laporan-card-disetujui',
                        'ditolak' => 'ds-laporan-card-ditolak',
                    ];
                    $labelMap = [
                        'draft' => 'Draft',
                        'menunggu_verifikasi' => 'Menunggu Verifikasi',
                        'disetujui' => 'Disetujui',
                        'ditolak' => 'Ditolak',
                    ];
                    $cardClass = $theme[$report->status] ?? 'ds-laporan-card-menunggu_verifikasi';
                    $statusLabel = $labelMap[$report->status] ?? 'Menunggu Verifikasi';
                @endphp
                
                <div class="ds-laporan-card {{ $cardClass }}">
                    <div class="ds-laporan-header">
                        <div class="ds-laporan-creator">
                            <div class="ds-laporan-avatar">
                                {{ substr($report->creator->name ?? 'U', 0, 1) }}
                            </div>
                            <div>
                                <p class="ds-laporan-name" style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                                    {{ $report->creator->name ?? 'Sekretaris PKK' }}
                                    @if($report->creator && $report->creator->sidongan_role)
                                        @php
                                            $roleLabels = [
                                                'ketua' => 'Ketua PKK',
                                                'sekretaris' => 'Sekretaris PKK',
                                                'bendahara' => 'Bendahara PKK',
                                                'staf_ahli_1' => 'Staf Ahli I',
                                                'staf_ahli_2' => 'Staf Ahli II',
                                                'pengurus_1' => 'Ketua Pengurus I',
                                                'pengurus_2' => 'Ketua Pengurus II',
                                                'pengurus_3' => 'Ketua Pengurus III',
                                                'pengurus_4' => 'Ketua Pengurus IV',
                                            ];
                                            $roleLabel = $roleLabels[$report->creator->sidongan_role] ?? ucfirst(str_replace('_', ' ', $report->creator->sidongan_role));
                                        @endphp
                                        <span style="display: inline-block; padding: 0.2rem 0.6rem; background: #dbeafe; color: #1e40af; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">
                                            {{ $roleLabel }}
                                        </span>
                                    @endif
                                </p>
                                <p class="ds-laporan-date">{{ $report->created_at->locale('id')->translatedFormat('d M Y, H.i') }}</p>
                            </div>
                        </div>
                        <span class="ds-laporan-status-badge">{{ $statusLabel }}</span>
                    </div>
                    
                    <div class="ds-laporan-info-grid">
                        <div>
                            <span class="ds-laporan-info-label">Tanggal Kegiatan:</span>
                            <p class="ds-laporan-info-value">
                                @if($report->kegiatan_tanggal)
                                    {{ \Carbon\Carbon::parse($report->kegiatan_tanggal)->locale('id')->translatedFormat('d M Y') }}
                                @else
                                    <span style="color: #94a3b8; font-style: italic;">-</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <span class="ds-laporan-info-label">Waktu Kegiatan:</span>
                            <p class="ds-laporan-info-value">
                                @if($report->start_time && $report->end_time)
                                    {{ \Carbon\Carbon::parse($report->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($report->end_time)->format('H:i') }}
                                @else
                                    <span style="color: #94a3b8; font-style: italic;">Waktu tidak tersedia</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    @php
                        $lokasiParts = [];
                        if (!empty($report->kelurahan)) $lokasiParts[] = $report->kelurahan;
                        if (!empty($report->kecamatan)) $lokasiParts[] = $report->kecamatan;
                        if (!empty($report->kabupaten)) $lokasiParts[] = $report->kabupaten;
                        if (!empty($report->provinsi)) $lokasiParts[] = $report->provinsi;
                        $lokasiLengkap = implode(', ', $lokasiParts);
                    @endphp
                    
                    @if($lokasiLengkap || !empty($report->alamat_lengkap))
                    <div class="ds-laporan-lokasi-box">
                        @if($lokasiLengkap)
                            <p class="ds-laporan-lokasi-hierarki">
                                <i class="fas fa-map-marker-alt" style="color: #ef4444; margin-right: 0.5rem;"></i>
                                {{ $lokasiLengkap }}
                            </p>
                        @endif
                        @if(!empty($report->alamat_lengkap))
                            <p class="ds-laporan-lokasi-alamat">
                                <i class="fas fa-location-arrow"></i>
                                {{ $report->alamat_lengkap }}
                            </p>
                        @endif
                    </div>
                    @endif
                    
                    <div class="ds-laporan-deskripsi">
                        {{ $report->deskripsi }}
                    </div>
                    
                    @php
                        $fotos = is_string($report->fotos ?? '') ? json_decode($report->fotos, true) : $report->fotos;
                        $fotosArray = is_array($fotos) ? $fotos : [];
                    @endphp
                    @if(count($fotosArray) > 0)
                    <div style="margin-bottom: 1.25rem;">
                        <span class="ds-laporan-info-label" style="display: block; margin-bottom: 0.75rem;">
                            <i class="fas fa-camera" style="margin-right: 0.35rem;"></i>
                            Dokumentasi ({{ count($fotosArray) }} foto):
                        </span>
                        <div class="ds-laporan-foto-grid">
                            @foreach($fotosArray as $index => $foto)
                            <div onclick="openReportGallery({{ $report->id }}, {{ $index }})" class="ds-laporan-foto-item">
                                <img src="{{ asset('storage/' . $foto) }}" alt="Dokumentasi">
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    <a href="{{ route('sidongan.lapor_kegiatan.show', $report->id) }}" class="ds-laporan-detail-btn">
                        <i class="fas fa-eye"></i>
                        <span>Lihat Detail</span>
                    </a>
                    <div style="clear: both;"></div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Alur Kegiatan - Timeline --}}
    <div class="ds-card">
        <div class="ds-card-header ds-card-header-blue">
            <h3>
                <i class="fas fa-stream"></i>
                Alur Kegiatan
            </h3>
        </div>
        <div class="ds-card-body">
            <div class="ds-timeline">
                @php
                    $reports = $activityReports ?? collect();
                    $hasDisposisi = !empty($document->disposisi_data);
                    
                    $totalItems = 1;
                    if ($hasDisposisi) $totalItems++;
                    foreach ($reports as $r) {
                        $totalItems++;
                        if (in_array($r->status ?? '', ['disetujui', 'ditolak'])) {
                            $totalItems++;
                        }
                    }
                    $currentItem = 0;
                @endphp
                
                {{-- Timeline Item 1: Sekretaris Upload --}}
                @php $currentItem++; @endphp
                <div class="ds-timeline-item">
                    <div class="ds-timeline-icon-col">
                        <div class="ds-timeline-icon ds-timeline-icon-blue">
                            @if($document->creator && $document->creator->avatar && file_exists(public_path('storage/' . $document->creator->avatar)))
                                <img src="{{ asset('storage/' . $document->creator->avatar) }}" alt="{{ $document->creator->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                            @else
                                <i class="fas fa-user"></i>
                            @endif
                        </div>
                        @if($currentItem < $totalItems)
                            <div class="ds-timeline-line" style="background: #e2e8f0;"></div>
                        @endif
                    </div>
                    <div class="ds-timeline-content">
                        <div class="ds-timeline-header">
                            <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                                <h4 class="ds-timeline-title" style="margin: 0;">{{ $document->creator->name ?? 'Sekretaris PKK' }}</h4>
                                @if($document->creator && $document->creator->sidongan_role)
                                    @php
                                        $roleLabels = [
                                            'ketua' => 'Ketua PKK',
                                            'sekretaris' => 'Sekretaris PKK',
                                            'bendahara' => 'Bendahara PKK',
                                            'staf_ahli_1' => 'Staf Ahli I',
                                            'staf_ahli_2' => 'Staf Ahli II',
                                            'pengurus_1' => 'Ketua Pengurus I',
                                            'pengurus_2' => 'Ketua Pengurus II',
                                            'pengurus_3' => 'Ketua Pengurus III',
                                            'pengurus_4' => 'Ketua Pengurus IV',
                                        ];
                                        $roleLabel = $roleLabels[$document->creator->sidongan_role] ?? ucfirst(str_replace('_', ' ', $document->creator->sidongan_role));
                                    @endphp
                                    <span class="ds-timeline-role-badge">{{ $roleLabel }}</span>
                                @endif
                            </div>
                            <span class="ds-timeline-date">{{ $document->created_at->locale('id')->translatedFormat('d M Y, H.i') }}</span>
                        </div>
                        <p class="ds-timeline-desc">
                            Membuat agenda dan mengupload surat dari {{ $document->sender ?? 'Pengirim' }}
                        </p>
                    </div>
                </div>

                {{-- Timeline Item 2: Disposisi --}}
                @if($hasDisposisi)
                    @php $currentItem++; @endphp
                    <div class="ds-timeline-item">
                        <div class="ds-timeline-icon-col">
                            <div class="ds-timeline-icon ds-timeline-icon-orange">
                                @php
                                    $disposedByUser = null;
                                    if (isset($dispo['disposed_by'])) {
                                        $disposedByUser = \App\Models\User::find($dispo['disposed_by']);
                                    }
                                @endphp
                                @if($disposedByUser && $disposedByUser->avatar && file_exists(public_path('storage/' . $disposedByUser->avatar)))
                                    <img src="{{ asset('storage/' . $disposedByUser->avatar) }}" alt="{{ $disposedByUser->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                @else
                                    <i class="fas fa-share-alt"></i>
                                @endif
                            </div>
                            @if($currentItem < $totalItems)
                                <div class="ds-timeline-line" style="background: #e2e8f0;"></div>
                            @endif
                        </div>
                        <div class="ds-timeline-content">
                            <div class="ds-timeline-header">
                                <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                                    <h4 class="ds-timeline-title" style="margin: 0;">{{ $disposedByUser->name ?? 'Ketua PKK' }}</h4>
                                    @if($disposedByUser && $disposedByUser->sidongan_role)
                                        @php
                                            $roleLabels = [
                                                'ketua' => 'Ketua PKK',
                                                'sekretaris' => 'Sekretaris PKK',
                                                'bendahara' => 'Bendahara PKK',
                                                'staf_ahli_1' => 'Staf Ahli I',
                                                'staf_ahli_2' => 'Staf Ahli II',
                                                'pengurus_1' => 'Ketua Pengurus I',
                                                'pengurus_2' => 'Ketua Pengurus II',
                                                'pengurus_3' => 'Ketua Pengurus III',
                                                'pengurus_4' => 'Ketua Pengurus IV',
                                            ];
                                            $roleLabel = $roleLabels[$disposedByUser->sidongan_role] ?? ucfirst(str_replace('_', ' ', $disposedByUser->sidongan_role));
                                        @endphp
                                        <span class="ds-timeline-role-badge">{{ $roleLabel }}</span>
                                    @endif
                                </div>
                                <span class="ds-timeline-date">
                                    {{ isset($dispo['disposed_at']) ? \Carbon\Carbon::parse($dispo['disposed_at'])->locale('id')->translatedFormat('d M Y, H:i') : '-' }}
                                </span>
                            </div>
                            <p class="ds-timeline-desc">
                                Melakukan disposisi kepada:
                                @if(isset($dispo['target_roles']))
                                    @foreach($dispo['target_roles'] as $role)
                                        <span class="ds-timeline-role-badge">
                                            {{ $rolesMap[$role] ?? ucfirst(str_replace('_', ' ', $role)) }}
                                        </span>
                                    @endforeach
                                @endif
                            </p>
                            @if(isset($dispo['comment']) && $dispo['comment'])
                            <p class="ds-timeline-quote">"{{ $dispo['comment'] }}"</p>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Timeline 3: Laporan Kegiatan + Verifikasi --}}
                @forelse($reports as $report)
                    @php
                        $currentItem++;
                        $isVerified = in_array($report->status ?? '', ['disetujui', 'ditolak']);
                        $verifStatus = $report->status ?? null;
                        $verifColor = $verifStatus === 'disetujui' ? '#10b981' : '#ef4444';
                        $verifIcon = $verifStatus === 'disetujui' ? 'check' : 'times';
                        $verifComment = $report->catatan_verifikasi ?? null;
                        $verifAt = $report->verified_at ?? $report->updated_at;
                        
                        $timelineLokasiParts = [];
                        if ($report->kelurahan) $timelineLokasiParts[] = $report->kelurahan;
                        if ($report->kecamatan) $timelineLokasiParts[] = $report->kecamatan;
                        if ($report->kabupaten) $timelineLokasiParts[] = $report->kabupaten;
                        if ($report->provinsi) $timelineLokasiParts[] = $report->provinsi;
                        $timelineLokasi = implode(', ', $timelineLokasiParts);
                    @endphp

                        {{-- Laporan --}}
                        <div class="ds-timeline-item">
                            <div class="ds-timeline-icon-col">
                                <div class="ds-timeline-icon ds-timeline-icon-green">
                                    @if($report->creator && $report->creator->avatar && file_exists(public_path('storage/' . $report->creator->avatar)))
                                        <img src="{{ asset('storage/' . $report->creator->avatar) }}" alt="{{ $report->creator->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                    @else
                                        <i class="fas fa-clipboard-list"></i>
                                    @endif
                                </div>
                                @if($currentItem < $totalItems)
                                    <div class="ds-timeline-line" style="background: #e2e8f0;"></div>
                                @endif
                            </div>
                            <div class="ds-timeline-content">
                                <div class="ds-timeline-header">
                                    <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                                        <h4 class="ds-timeline-title" style="margin: 0;">
                                            {{ $report->creator->name ?? 'Sekretaris PKK' }}
                                        </h4>
                                        @if($report->creator && $report->creator->sidongan_role)
                                            @php
                                                $roleLabels = [
                                                    'ketua' => 'Ketua PKK',
                                                    'sekretaris' => 'Sekretaris PKK',
                                                    'bendahara' => 'Bendahara PKK',
                                                    'staf_ahli_1' => 'Staf Ahli I',
                                                    'staf_ahli_2' => 'Staf Ahli II',
                                                    'pengurus_1' => 'Ketua Pengurus I',
                                                    'pengurus_2' => 'Ketua Pengurus II',
                                                    'pengurus_3' => 'Ketua Pengurus III',
                                                    'pengurus_4' => 'Ketua Pengurus IV',
                                                ];
                                                $roleLabel = $roleLabels[$report->creator->sidongan_role] ?? ucfirst(str_replace('_', ' ', $report->creator->sidongan_role));
                                            @endphp
                                            <span class="ds-timeline-role-badge">{{ $roleLabel }}</span>
                                        @endif
                                    </div>
                                    <span class="ds-timeline-date">
                                        {{ $report->created_at->locale('id')->translatedFormat('d M Y, H:i') }}
                                    </span>
                                </div>
                                <p class="ds-timeline-desc">
                                    Membuat laporan kegiatan: <strong>{{ $report->kegiatan_nama }}</strong>
                                </p>
                                @if($timelineLokasi)
                                <p class="ds-timeline-meta">
                                    <i class="fas fa-map-marker-alt"></i>
                                    {{ $timelineLokasi }}
                                </p>
                                @endif
                                @if($report->alamat_lengkap)
                                <p class="ds-timeline-meta">
                                    <i class="fas fa-location-arrow"></i>
                                    {{ Str::limit($report->alamat_lengkap, 80) }}
                                </p>
                                @endif
                            </div>
                        </div>

                    {{-- Verifikasi --}}
                    @if($isVerified)
                        @php $currentItem++; @endphp
                        <div class="ds-timeline-item">
                            <div class="ds-timeline-icon-col">
                                <div class="ds-timeline-icon" style="background: {{ $verifColor }}; box-shadow: 0 0 0 4px {{ $verifColor }}30;">
                                    <i class="fas fa-{{ $verifIcon }}"></i>
                                </div>
                                @if($currentItem < $totalItems)
                                    <div style="width: 3px; height: 2.5rem; background: #e2e8f0;"></div>
                                @endif
                            </div>
                            <div class="ds-timeline-content">
                                <div class="ds-timeline-header">
                                    <h4 class="ds-timeline-title">Ketua PKK</h4>
                                    <span class="ds-timeline-date">
                                        {{ \Carbon\Carbon::parse($verifAt)->locale('id')->translatedFormat('d M Y, H:i') }}
                                    </span>
                                </div>
                                
                                @if($verifStatus === 'disetujui')
                                    <p class="ds-timeline-desc">
                                        <span class="ds-timeline-verif-badge ds-timeline-verif-badge-success">
                                            <i class="fas fa-check-circle"></i>
                                            Menyetujui
                                        </span>
                                        Laporan kegiatan: <strong>{{ $report->kegiatan_nama }}</strong>
                                    </p>
                                @elseif($verifStatus === 'ditolak')
                                    <p class="ds-timeline-desc">
                                        <span class="ds-timeline-verif-badge ds-timeline-verif-badge-danger">
                                            <i class="fas fa-times-circle"></i>
                                            Menolak
                                        </span>
                                        Laporan kegiatan: <strong>{{ $report->kegiatan_nama }}</strong>
                                    </p>
                                @endif
                                
                                @if($verifComment)
                                <div class="ds-timeline-note {{ $verifStatus === 'disetujui' ? 'ds-timeline-note-success' : 'ds-timeline-note-danger' }}">
                                    "{{ $verifComment }}"
                                </div>
                                @endif
                            </div>
                        </div>
                    @endif
                @empty
                    @if($hasDisposisi && $currentItem < $totalItems)
                    <div class="ds-timeline-item">
                        <div class="ds-timeline-icon-col">
                            <div style="width: 3px; height: 2.5rem; background: linear-gradient(to bottom, #f97316, transparent);"></div>
                        </div>
                        <div class="ds-timeline-content"></div>
                    </div>
                    @endif
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- MODAL GALLERY --}}
<div id="galleryOverlay" class="dl-gallery-overlay" onclick="closeGallery(event)">
    <button class="dl-gallery-close" onclick="closeGallery()">
        <i class="fas fa-times"></i>
    </button>
    
    <div class="dl-gallery-container" onclick="event.stopPropagation()">
        <div class="dl-gallery-image-wrapper">
            <img id="galleryImage" class="dl-gallery-image" src="" alt="Dokumentasi">
        </div>
        
        <button class="dl-gallery-nav prev" onclick="navigateGallery(-1)">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="dl-gallery-nav next" onclick="navigateGallery(1)">
            <i class="fas fa-chevron-right"></i>
        </button>
        
        <div class="dl-gallery-bottom-bar">
            <span id="galleryCounter" class="dl-gallery-counter">1 / 1</span>
            <a id="galleryDownload" class="dl-gallery-download-btn" href="" download>
                <i class="fas fa-download"></i>
                <span>Unduh Foto</span>
            </a>
        </div>
        
        <div id="galleryThumbnails" class="dl-gallery-thumbnails"></div>
    </div>
</div>

{{-- Reuse CSS dari detail-laporan untuk gallery --}}
<link rel="stylesheet" href="{{ asset('assets/sidongan/css/detail-laporan.css') }}">

<script>
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
    
    let currentGallery = {
        fotos: [],
        currentIndex: 0,
        isAnimating: false
    };
    
    function openGallery(index) {
        if (documentFoto.length === 0) return;
        currentGallery.fotos = documentFoto;
        currentGallery.currentIndex = index;
        showGalleryModal();
    }
    
    function openReportGallery(reportId, index) {
        if (!reportFotosData[reportId] || reportFotosData[reportId].length === 0) return;
        currentGallery.fotos = reportFotosData[reportId];
        currentGallery.currentIndex = index;
        showGalleryModal();
    }
    
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
            
            if (fotoPath.startsWith('http')) {
                img.src = fotoPath;
            } else {
                img.src = '{{ asset("storage") }}/' + fotoPath;
            }
            
            img.className = 'dl-gallery-image ' + animClass;
            
            setTimeout(() => {
                img.style.opacity = '1';
                img.style.transform = 'translateX(0) scale(1)';
            }, 50);
            
            updateGalleryUI();
            
            setTimeout(() => {
                currentGallery.isAnimating = false;
                img.className = 'dl-gallery-image';
            }, 400);
        }, 200);
    }
    
    function updateGalleryImage(animClass) {
        const img = document.getElementById('galleryImage');
        const fotoPath = currentGallery.fotos[currentGallery.currentIndex];
        
        if (fotoPath.startsWith('http')) {
            img.src = fotoPath;
        } else {
            img.src = '{{ asset("storage") }}/' + fotoPath;
        }
        
        img.className = 'dl-gallery-image ' + (animClass || 'fade-in');
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
        
        const prevBtn = document.querySelector('.dl-gallery-nav.prev');
        const nextBtn = document.querySelector('.dl-gallery-nav.next');
        
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
            thumb.className = 'dl-gallery-thumb' + (index === currentGallery.currentIndex ? ' active' : '');
            thumb.innerHTML = '<img src="' + fullUrl + '" alt="Thumb">';
            thumb.onclick = () => {
                if (index !== currentGallery.currentIndex) {
                    navigateGallery(index - currentGallery.currentIndex);
                }
            };
            container.appendChild(thumb);
        });
    }
    
    document.addEventListener('keydown', (e) => {
        const overlay = document.getElementById('galleryOverlay');
        if (!overlay || !overlay.classList.contains('active')) return;
        if (e.key === 'Escape') closeGallery();
        if (e.key === 'ArrowLeft') navigateGallery(-1);
        if (e.key === 'ArrowRight') navigateGallery(1);
    });
</script>
@endsection