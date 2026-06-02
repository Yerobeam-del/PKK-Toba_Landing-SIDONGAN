@extends('sidongan.layouts.app')
@section('title', 'Verifikasi Laporan - SIDONGAN')

@section('content')
<style>
    /* Styles untuk kartu dan tombol */
    .verif-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .verif-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.1);
    }
    .btn-verif {
        transition: all 0.2s;
    }
    .btn-verif:hover {
        transform: scale(1.05);
    }
    .stats-card {
        transition: all 0.3s;
    }
    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.15);
    }
</style>

<div style="max-width: 1200px; margin: 0 auto;">
    {{-- Header --}}
    <div style="margin-bottom: 1.5rem;">
        <h1 style="font-size: 1.5rem; font-weight: 700; color: #1e293b; margin: 0;">Verifikasi Laporan</h1>
        <p style="font-size: 0.875rem; color: #64748b; margin-top: 0.25rem;">Tinjau dan verifikasi laporan kegiatan dari Sekretaris</p>
    </div>

    {{-- Stats Cards --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
        {{-- Menunggu Verifikasi --}}
        <div class="stats-card" style="background: linear-gradient(135deg, #f97316, #ea580c); border-radius: 0.75rem; padding: 1.25rem; color: white; position: relative; overflow: hidden;">
            <div style="position: absolute; top: -10px; right: -10px; width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
            <div style="display: flex; align-items: center; gap: 1rem; position: relative; z-index: 1;">
                <div style="width: 3rem; height: 3rem; background: rgba(255,255,255,0.25); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
                    <i class="fas fa-clock" style="font-size: 1.25rem;"></i>
                </div>
                <div>
                    <p style="font-size: 0.875rem; opacity: 0.95; margin: 0;">Menunggu Verifikasi</p>
                    <p style="font-size: 1.875rem; font-weight: 800; margin: 0.25rem 0 0 0;">{{ $documents->where('status', 'menunggu_verifikasi')->count() }}</p>
                </div>
            </div>
        </div>
        
        {{-- Disetujui --}}
        <div class="stats-card" style="background: linear-gradient(135deg, #22c55e, #16a34a); border-radius: 0.75rem; padding: 1.25rem; color: white; position: relative; overflow: hidden;">
            <div style="position: absolute; top: -10px; right: -10px; width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
            <div style="display: flex; align-items: center; gap: 1rem; position: relative; z-index: 1;">
                <div style="width: 3rem; height: 3rem; background: rgba(255,255,255,0.25); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
                    <i class="fas fa-check-circle" style="font-size: 1.25rem;"></i>
                </div>
                <div>
                    <p style="font-size: 0.875rem; opacity: 0.95; margin: 0;">Disetujui</p>
                    <p style="font-size: 1.875rem; font-weight: 800; margin: 0.25rem 0 0 0;">{{ $documents->where('status', 'disetujui')->count() }}</p>
                </div>
            </div>
        </div>
        
        {{-- Ditolak --}}
        <div class="stats-card" style="background: linear-gradient(135deg, #ef4444, #dc2626); border-radius: 0.75rem; padding: 1.25rem; color: white; position: relative; overflow: hidden;">
            <div style="position: absolute; top: -10px; right: -10px; width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
            <div style="display: flex; align-items: center; gap: 1rem; position: relative; z-index: 1;">
                <div style="width: 3rem; height: 3rem; background: rgba(255,255,255,0.25); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
                    <i class="fas fa-times-circle" style="font-size: 1.25rem;"></i>
                </div>
                <div>
                    <p style="font-size: 0.875rem; opacity: 0.95; margin: 0;">Ditolak / Revisi</p>
                    <p style="font-size: 1.875rem; font-weight: 800; margin: 0.25rem 0 0 0;">{{ $documents->where('status', 'ditolak')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Section --}}
    <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; margin-bottom: 1.5rem;">
        <form action="{{ route('sidongan.verifikasi') }}" method="GET">
            <div style="padding: 1.25rem 1.5rem;">
                <div style="display: grid; grid-template-columns: 2fr 1fr auto; gap: 1rem; align-items: end;">
                    {{-- Search Input --}}
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 0.5rem;">Cari Laporan</label>
                        <div style="position: relative;">
                            <i class="fas fa-search" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #94a3b8; pointer-events: none;"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan judul kegiatan..." 
                                style="width: 100%; padding: 0.625rem 1rem 0.625rem 2.5rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.875rem; box-sizing: border-box;">
                        </div>
                    </div>
                    
                    {{-- Status Dropdown --}}
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 0.5rem;">Status Verifikasi</label>
                        <div style="position: relative; display: inline-block; width: 100%;">
                            <select name="status" style="width: 100%; padding: 0.625rem 2.5rem 0.625rem 1rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.875rem; background: white; box-sizing: border-box; appearance: none; -webkit-appearance: none; -moz-appearance: none; cursor: pointer; outline: none; transition: border-color 0.2s;"
                                    onfocus="this.style.borderColor='#3b82f6'" 
                                    onblur="this.style.borderColor='#e2e8f0'">
                                <option value="">Semua Status</option>
                                <option value="menunggu_verifikasi" {{ request('status') == 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                                <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                            {{-- Custom Arrow - Posisi Fixed --}}
                            <div style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); pointer-events: none; display: flex; align-items: center; color: #64748b;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Filter Button --}}
                    <div>
                        <button type="submit" style="display: inline-flex; align-items: center; justify-content: center; width: 100%; padding: 0.625rem 1.5rem; background: #3b82f6; color: white; border: none; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 600; cursor: pointer; box-sizing: border-box; box-shadow: 0 2px 4px rgba(59,130,246,0.2);"
                                onmouseover="this.style.background='#2563eb'; this.style.transform='translateY(-1px)'"
                                onmouseout="this.style.background='#3b82f6'; this.style.transform='translateY(0)'">
                            <i class="fas fa-filter" style="margin-right: 0.5rem;"></i>
                            Filter
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- List Container --}}
    <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; overflow: hidden;">
        <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #e2e8f0;">
            <h3 style="font-size: 1rem; font-weight: 700; color: #1e293b; margin: 0;">Daftar Laporan Kegiatan</h3>
            <p style="font-size: 0.8rem; color: #64748b; margin: 0.25rem 0 0 0;">Tinjau dan verifikasi laporan kegiatan yang telah dikirim</p>
        </div>
        
        <div style="padding: 1.5rem;">
            @forelse($documents as $report)
                @php
                    // Config warna status
                    $statusConfig = [
                        'menunggu_verifikasi' => ['bg' => '#fff7ed', 'border' => '#fed7aa', 'text' => '#9a3412', 'btn' => '#f97316', 'label' => 'Menunggu Verifikasi'],
                        'disetujui' => ['bg' => '#f0fdf4', 'border' => '#bbf7d0', 'text' => '#166534', 'btn' => '#22c55e', 'label' => 'Disetujui'],
                        'ditolak' => ['bg' => '#fef2f2', 'border' => '#fecaca', 'text' => '#991b1b', 'btn' => '#ef4444', 'label' => 'Ditolak'],
                    ];
                    $theme = $statusConfig[$report->status] ?? $statusConfig['menunggu_verifikasi'];
                @endphp
                
                <div class="verif-card" 
                     style="background: {{ $theme['bg'] }}; 
                            border: 2px solid {{ $theme['border'] }}; 
                            border-radius: 0.75rem; padding: 1.25rem; margin-bottom: 1rem;">
                    
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                        <div style="flex: 1;">
                            {{-- Header: Agenda & Status --}}
                            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem; flex-wrap: wrap;">
                                @if($report->document)
                                <span style="font-size: 0.75rem; font-family: monospace; background: white; color: {{ $theme['text'] }}; padding: 0.25rem 0.6rem; border-radius: 0.375rem; font-weight: 700; border: 1px solid {{ $theme['border'] }};">
                                    {{ $report->document->agenda_number }}
                                </span>
                                @endif
                                <span style="font-size: 0.75rem; padding: 0.25rem 0.75rem; border-radius: 9999px; font-weight: 600; background: {{ $theme['btn'] }}; color: white;">
                                    {{ $theme['label'] }}
                                </span>
                            </div>
                            
                            {{-- Judul Laporan --}}
                            <h4 style="font-size: 1.05rem; font-weight: 700; color: #0f172a; margin: 0 0 0.75rem 0; line-height: 1.4;">
                                {{ $report->kegiatan_nama }}
                            </h4>
                            
                            {{-- Meta Info --}}
                            <div style="display: flex; gap: 1.5rem; font-size: 0.85rem; color: #64748b; flex-wrap: wrap;">
                                <span style="display: flex; align-items: center; gap: 0.5rem;">
                                    <div style="width: 1.5rem; height: 1.5rem; background: {{ $theme['bg'] }}; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-user" style="color: {{ $theme['text'] }}; font-size: 0.7rem;"></i>
                                    </div>
                                    {{ $report->creator->name ?? 'Sekretaris PKK' }}
                                </span>
                                <span style="display: flex; align-items: center; gap: 0.5rem;">
                                    <div style="width: 1.5rem; height: 1.5rem; background: {{ $theme['bg'] }}; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-calendar" style="color: {{ $theme['text'] }}; font-size: 0.7rem;"></i>
                                    </div>
                                    {{ $report->kegiatan_tanggal->locale('id')->translatedFormat('d M Y') }}
                                </span>
                                @if($report->lokasi)
                                <span style="display: flex; align-items: center; gap: 0.5rem;">
                                    <div style="width: 1.5rem; height: 1.5rem; background: {{ $theme['bg'] }}; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-map-marker-alt" style="color: {{ $theme['text'] }}; font-size: 0.7rem;"></i>
                                    </div>
                                    {{ Str::limit($report->lokasi, 30) }}
                                </span>
                                @endif
                            </div>
                        </div>
                        
                        {{-- Tombol Aksi --}}
                        <div style="display: flex; gap: 0.5rem; flex-shrink: 0;">
                            @if($report->status === 'menunggu_verifikasi')
                            <a href="{{ route('sidongan.verifikasi.form', $report->id) }}" 
                               class="btn-verif"
                               style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1rem; background: #7c3aed; color: white; text-decoration: none; border-radius: 0.5rem; font-size: 0.85rem; font-weight: 600; box-shadow: 0 2px 4px rgba(124,58,237,0.2);">
                                <i class="fas fa-clipboard-check"></i>
                                <span>Verifikasi</span>
                            </a>
                            @endif
                            <a href="{{ route('sidongan.lapor_kegiatan.show', $report->id) }}" 
                               class="btn-verif"
                               style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1rem; background: white; color: {{ $theme['text'] }}; text-decoration: none; border-radius: 0.5rem; font-size: 0.85rem; font-weight: 600; border: 1px solid {{ $theme['border'] }};">
                                <i class="fas fa-eye"></i>
                                <span>Detail</span>
                            </a>
                        </div>
                    </div>
                    
                    {{-- Box Preview Deskripsi --}}
                    @if($report->deskripsi)
                    <div style="background: white; border-left: 4px solid {{ $theme['btn'] }}; border-radius: 0.5rem; padding: 1rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: start; gap: 0.75rem;">
                            <div style="width: 2rem; height: 2rem; background: {{ $theme['bg'] }}; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="fas fa-align-left" style="color: {{ $theme['btn'] }}; font-size: 0.85rem;"></i>
                            </div>
                            <div style="flex: 1;">
                                <p style="font-size: 0.85rem; color: #1e293b; margin: 0; font-weight: 700;">Deskripsi Kegiatan</p>
                                <p style="font-size: 0.8rem; color: #64748b; margin: 0.35rem 0 0 0; line-height: 1.5;">
                                    {{ Str::limit($report->deskripsi, 120) }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    {{-- Catatan Verifikasi --}}
                    @if($report->catatan_verifikasi)
                    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px dashed {{ $theme['border'] }};">
                        <p style="font-size: 0.75rem; color: #64748b; margin: 0 0 0.35rem 0; font-weight: 600;">Catatan Verifikasi:</p>
                        <p style="font-size: 0.85rem; color: #475569; margin: 0; font-style: italic;">{{ $report->catatan_verifikasi }}</p>
                    </div>
                    @endif
                </div>
            @empty
                <div style="text-align: center; padding: 4rem 2rem;">
                    <div style="width: 120px; height: 120px; background: linear-gradient(135deg, #f1f5f9, #e2e8f0); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                        <i class="fas fa-inbox" style="color: #94a3b8; font-size: 3rem;"></i>
                    </div>
                    <h4 style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin: 0 0 0.5rem 0;">Tidak Ada Laporan</h4>
                    <p style="font-size: 0.875rem; color: #64748b; margin: 0;">Belum ada laporan kegiatan yang sesuai dengan filter.</p>
                </div>
            @endforelse
        </div>
        
        @if($documents->hasPages())
        <div style="padding: 1rem 1.5rem; border-top: 1px solid #e2e8f0;">
            {{ $documents->links() }}
        </div>
        @endif
    </div>
</div>
@endsection