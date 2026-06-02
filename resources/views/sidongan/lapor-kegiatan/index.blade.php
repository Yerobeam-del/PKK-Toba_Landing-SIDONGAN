@extends('sidongan.layouts.app')
@section('title', 'Lapor Kegiatan - SIDONGAN')

@section('content')
<style>
    .laporan-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid transparent;
    }
    .laporan-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.1);
        border-color: #3b82f6;
    }
    .btn-laporan {
        transition: all 0.2s;
    }
    .btn-laporan:hover {
        transform: scale(1.05);
    }
    .stats-card {
        transition: all 0.3s;
    }
    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.15);
    }
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-slide-in {
        animation: slideIn 0.4s ease-out;
    }
</style>

<div style="max-width: 1200px; margin: 0 auto;">
    {{-- Header --}}
    <div style="margin-bottom: 1.5rem;" class="animate-slide-in">
        <h1 style="font-size: 1.5rem; font-weight: 700; color: #1e293b; margin: 0;">Lapor Kegiatan</h1>
        <p style="font-size: 0.875rem; color: #64748b; margin-top: 0.25rem;">Laporkan kegiatan yang telah dilaksanakan</p>
    </div>

    {{-- Stats Cards --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
        {{-- ✅ PERLU DILAPORKAN (Pekerjaan Aktif) - WARNA BIRU --}}
        <div class="stats-card" style="background: linear-gradient(135deg, #06b6d4, #0891b2); border-radius: 0.75rem; padding: 1.25rem; color: white; position: relative; overflow: hidden;">
            <div style="position: absolute; top: -10px; right: -10px; width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
            <div style="display: flex; align-items: center; gap: 1rem; position: relative; z-index: 1;">
                <div style="width: 3rem; height: 3rem; background: rgba(255,255,255,0.25); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
                    <i class="fas fa-tasks" style="font-size: 1.25rem;"></i>
                </div>
                <div>
                    <p style="font-size: 0.875rem; opacity: 0.95; margin: 0;">Perlu Dilaporkan</p>
                    <p style="font-size: 1.875rem; font-weight: 800; margin: 0.25rem 0 0 0;">{{ $perluDilaporkan ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        {{-- Menunggu Verifikasi - WARNA ORANGE --}}
        <div class="stats-card" style="background: linear-gradient(135deg, #f97316, #ea580c); border-radius: 0.75rem; padding: 1.25rem; color: white; position: relative; overflow: hidden;">
            <div style="position: absolute; top: -10px; right: -10px; width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
            <div style="display: flex; align-items: center; gap: 1rem; position: relative; z-index: 1;">
                <div style="width: 3rem; height: 3rem; background: rgba(255,255,255,0.25); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
                    <i class="fas fa-clock" style="font-size: 1.25rem;"></i>
                </div>
                <div>
                    <p style="font-size: 0.875rem; opacity: 0.95; margin: 0;">Menunggu Verifikasi</p>
                    <p style="font-size: 1.875rem; font-weight: 800; margin: 0.25rem 0 0 0;">{{ $menungguVerifikasi ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        {{-- Disetujui - WARNA HIJAU --}}
        <div class="stats-card" style="background: linear-gradient(135deg, #22c55e, #16a34a); border-radius: 0.75rem; padding: 1.25rem; color: white; position: relative; overflow: hidden;">
            <div style="position: absolute; top: -10px; right: -10px; width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
            <div style="display: flex; align-items: center; gap: 1rem; position: relative; z-index: 1;">
                <div style="width: 3rem; height: 3rem; background: rgba(255,255,255,0.25); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
                    <i class="fas fa-check-circle" style="font-size: 1.25rem;"></i>
                </div>
                <div>
                    <p style="font-size: 0.875rem; opacity: 0.95; margin: 0;">Disetujui</p>
                    <p style="font-size: 1.875rem; font-weight: 800; margin: 0.25rem 0 0 0;">{{ $disetujui ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        {{-- Ditolak - WARNA MERAH --}}
        <div class="stats-card" style="background: linear-gradient(135deg, #ef4444, #dc2626); border-radius: 0.75rem; padding: 1.25rem; color: white; position: relative; overflow: hidden;">
            <div style="position: absolute; top: -10px; right: -10px; width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
            <div style="display: flex; align-items: center; gap: 1rem; position: relative; z-index: 1;">
                <div style="width: 3rem; height: 3rem; background: rgba(255,255,255,0.25); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
                    <i class="fas fa-times-circle" style="font-size: 1.25rem;"></i>
                </div>
                <div>
                    <p style="font-size: 0.875rem; opacity: 0.95; margin: 0;">Ditolak</p>
                    <p style="font-size: 1.875rem; font-weight: 800; margin: 0.25rem 0 0 0;">{{ $ditolak ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Daftar Surat yang Perlu Dilaporkan --}}
    <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e2e8f0;">
        <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #e2e8f0;">
            <h3 style="font-size: 1rem; font-weight: 700; color: #1e293b; margin: 0;">Surat yang Perlu Dilaporkan</h3>
            <p style="font-size: 0.8rem; color: #64748b; margin: 0.25rem 0 0 0;">Buat laporan kegiatan untuk surat-surat berikut yang telah didisposisi kepada Anda</p>
        </div>
        
        <div style="padding: 1.5rem;">
            @forelse($documents ?? [] as $index => $doc)
                @php
                    // Cek apakah sudah ada laporan untuk surat ini
                    $existingReport = \App\Models\ActivityReport::where('document_id', $doc->id)
                        ->where('created_by', $user->id)
                        ->first();
                    
                    // CONFIG WARNA BERDASARKAN STATUS
                    $statusConfig = [
                        // Default: Belum ada laporan (Perlu Dilaporkan) -> BIRU
                        null => ['bg' => '#eff6ff', 'border' => '#bfdbfe', 'text' => '#1e40af', 'btn' => '#3b82f6', 'label' => 'Perlu Dilaporkan'],
                        'draft' => ['bg' => '#eff6ff', 'border' => '#bfdbfe', 'text' => '#1e40af', 'btn' => '#3b82f6', 'label' => 'Draft'],
                        // Menunggu Verifikasi -> ORANGE
                        'menunggu_verifikasi' => ['bg' => '#fff7ed', 'border' => '#fed7aa', 'text' => '#9a3412', 'btn' => '#f97316', 'label' => 'Menunggu Verifikasi'],
                        // Disetujui -> HIJAU
                        'disetujui' => ['bg' => '#f0fdf4', 'border' => '#bbf7d0', 'text' => '#166534', 'btn' => '#22c55e', 'label' => 'Disetujui'],
                        // Ditolak -> MERAH
                        'ditolak' => ['bg' => '#fef2f2', 'border' => '#fecaca', 'text' => '#991b1b', 'btn' => '#ef4444', 'label' => 'Ditolak'],
                    ];

                    // Ambil config: jika report null, pakai default, kalau tidak, ambil sesuai status
                    $theme = $statusConfig[$existingReport->status ?? null] ?? $statusConfig[null];
                @endphp
                
                {{-- Card Surat dengan Warna Berdasarkan Status --}}
                <div class="laporan-card animate-slide-in" 
                    style="background: {{ $theme['bg'] }}; 
                            border: 2px solid {{ $theme['border'] }}; 
                            border-radius: 0.75rem; padding: 1.25rem; margin-bottom: 1rem; 
                            animation-delay: {{ $loop->index * 0.1 }}s;">

                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                        <div style="flex: 1;">
                            {{-- Header: Agenda Number & Status Badge --}}
                            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem; flex-wrap: wrap;">
                                <span style="font-size: 0.75rem; font-family: monospace; background: white; color: {{ $theme['text'] }}; padding: 0.25rem 0.6rem; border-radius: 0.375rem; font-weight: 700; border: 1px solid {{ $theme['border'] }};">
                                    {{ $doc->agenda_number }}
                                </span>
                                <span style="font-size: 0.75rem; padding: 0.25rem 0.75rem; border-radius: 9999px; font-weight: 600; background: {{ $theme['btn'] }}; color: white;">
                                    {{ $theme['label'] }}
                                </span>
                            </div>
                            
                            {{-- Judul Surat --}}
                            <h4 style="font-size: 1.05rem; font-weight: 700; color: #0f172a; margin: 0 0 0.75rem 0; line-height: 1.4;">
                                {{ $doc->subject ?? $doc->title }}
                            </h4>
                            
                            {{-- Meta Info --}}
                            <div style="display: flex; gap: 1.5rem; font-size: 0.85rem; color: #64748b; flex-wrap: wrap;">
                                <span style="display: flex; align-items: center; gap: 0.5rem;">
                                    <div style="width: 1.5rem; height: 1.5rem; background: {{ $theme['bg'] }}; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-user" style="color: {{ $theme['text'] }}; font-size: 0.7rem;"></i>
                                    </div>
                                    {{ $doc->sender }}
                                </span>
                                <span style="display: flex; align-items: center; gap: 0.5rem;">
                                    <div style="width: 1.5rem; height: 1.5rem; background: {{ $theme['bg'] }}; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-calendar" style="color: {{ $theme['text'] }}; font-size: 0.7rem;"></i>
                                    </div>
                                    {{ $doc->created_at->locale('id')->translatedFormat('d M Y') }}
                                </span>
                            </div>
                        </div>
                        
                        {{-- Tombol Aksi --}}
                        <div style="display: flex; gap: 0.5rem;">
                            @if($existingReport)
                                <a href="{{ route('sidongan.lapor_kegiatan.show', $existingReport->id) }}" 
                                class="btn-laporan"
                                style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1rem; background: {{ $theme['btn'] }}; color: white; text-decoration: none; border-radius: 0.5rem; font-size: 0.85rem; font-weight: 600; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                    <i class="fas fa-eye"></i>
                                    <span>Lihat</span>
                                </a>
                            @else
                                <a href="{{ route('sidongan.lapor_kegiatan.create', ['document_id' => $doc->id]) }}" 
                                class="btn-laporan"
                                style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1rem; background: {{ $theme['btn'] }}; color: white; text-decoration: none; border-radius: 0.5rem; font-size: 0.85rem; font-weight: 600; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                    <i class="fas fa-plus"></i>
                                    <span>Buat Laporan</span>
                                </a>
                            @endif
                        </div>
                    </div>
                    
                    {{-- Box Instruksi Disposisi --}}
                    @if($doc->disposisi_data)
                        @php
                            $dispo = is_string($doc->disposisi_data) ? json_decode($doc->disposisi_data, true) : $doc->disposisi_data;
                        @endphp
                        @if(isset($dispo['action']))
                        <div style="background: white; border-left: 4px solid {{ $theme['btn'] }}; border-radius: 0.5rem; padding: 1rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                            <div style="display: flex; align-items: start; gap: 0.75rem;">
                                <div style="width: 2rem; height: 2rem; background: {{ $theme['bg'] }}; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <i class="fas fa-info-circle" style="color: {{ $theme['btn'] }}; font-size: 0.85rem;"></i>
                                </div>
                                <div style="flex: 1;">
                                    <p style="font-size: 0.85rem; color: #1e293b; margin: 0; font-weight: 700;">
                                        Instruksi: <span style="color: {{ $theme['btn'] }};">{{ $dispo['action'] }}</span>
                                    </p>
                                    @if(isset($dispo['comment']) && $dispo['comment'])
                                    <p style="font-size: 0.8rem; color: #64748b; margin: 0.5rem 0 0 0; font-style: italic; line-height: 1.5;">
                                        "{{ $dispo['comment'] }}"
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                    @endif
                </div>
            @empty
                {{-- Empty State --}}
                <div style="text-align: center; padding: 4rem 2rem;">
                    <div style="width: 120px; height: 120px; background: linear-gradient(135deg, #f1f5f9, #e2e8f0); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; animation: pulse 2s infinite;">
                        <i class="fas fa-inbox" style="color: #94a3b8; font-size: 3rem;"></i>
                    </div>
                    <h4 style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin: 0 0 0.5rem 0;">Tidak Ada Surat yang Perlu Dilaporkan</h4>
                    <p style="font-size: 0.875rem; color: #64748b; margin: 0;">
                        Surat yang didisposisi kepada Anda akan muncul di sini.
                    </p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}
</style>
@endsection