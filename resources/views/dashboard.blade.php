@extends('sidongan-admin.layouts.app')
@section('title', 'Dashboard - SIDONGAN')
@section('page-title', 'Dashboard')

@section('content')
<div class="fade-in space-y-6">
    {{-- Stats Cards --}}
    <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
        <div class="stat-card" style="border-left-color: #14b8a6; background: linear-gradient(135deg, rgba(20,184,166,0.1), white);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div style="font-size: 0.875rem; color: var(--text-muted); margin-bottom: 0.25rem;">Total Surat</div>
                    <div style="font-size: 2rem; font-weight: 800; color: var(--text-dark);">{{ $totalSurat ?? 0 }}</div>
                </div>
                <div style="width: 48px; height: 48px; background: rgba(20,184,166,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-envelope" style="color: #14b8a6; font-size: 1.25rem;"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card" style="border-left-color: #3b82f6; background: linear-gradient(135deg, rgba(59,130,246,0.1), white);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div style="font-size: 0.875rem; color: var(--text-muted); margin-bottom: 0.25rem;">Sedang Berjalan</div>
                    <div style="font-size: 2rem; font-weight: 800; color: var(--text-dark);">{{ $sedangBerjalan ?? 0 }}</div>
                </div>
                <div style="width: 48px; height: 48px; background: rgba(59,130,246,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-spinner" style="color: #3b82f6; font-size: 1.25rem;"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card" style="border-left-color: #f59e0b; background: linear-gradient(135deg, rgba(245,158,11,0.1), white);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div style="font-size: 0.875rem; color: var(--text-muted); margin-bottom: 0.25rem;">Menunggu Proses</div>
                    <div style="font-size: 2rem; font-weight: 800; color: var(--text-dark);">{{ $menungguProses ?? 0 }}</div>
                </div>
                <div style="width: 48px; height: 48px; background: rgba(245,158,11,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-clock" style="color: #f59e0b; font-size: 1.25rem;"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card" style="border-left-color: #10b981; background: linear-gradient(135deg, rgba(16,185,129,0.1), white);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div style="font-size: 0.875rem; color: var(--text-muted); margin-bottom: 0.25rem;">Selesai</div>
                    <div style="font-size: 2rem; font-weight: 800; color: var(--text-dark);">{{ $selesai ?? 0 }}</div>
                </div>
                <div style="width: 48px; height: 48px; background: rgba(16,185,129,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-check-circle" style="color: #10b981; font-size: 1.25rem;"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card" style="border-left-color: #6b7280; background: linear-gradient(135deg, rgba(107,114,128,0.1), white);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div style="font-size: 0.875rem; color: var(--text-muted); margin-bottom: 0.25rem;">Diarsipkan</div>
                    <div style="font-size: 2rem; font-weight: 800; color: var(--text-dark);">{{ $diarsipkan ?? 0 }}</div>
                </div>
                <div style="width: 48px; height: 48px; background: rgba(107,114,128,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-archive" style="color: #6b7280; font-size: 1.25rem;"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
        {{-- Surat Terbaru --}}
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3 style="font-weight: 700; color: var(--text-dark); margin: 0;">Surat Terbaru</h3>
                <a href="{{ route('sidongan.admin.documents.index') }}" style="color: #14b8a6; text-decoration: none; font-weight: 500; font-size: 0.9rem;">
                    Lihat Semua →
                </a>
            </div>
            
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                @forelse($recentDocuments ?? [] as $doc)
                <div style="border: 1px solid var(--border); border-radius: 12px; padding: 1rem; transition: all 0.2s; cursor: pointer;" 
                     onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,0.1)'; this.style.transform='translateY(-2px)'"
                     onmouseout="this.style.boxShadow='none'; this.style.transform='translateY(0)'">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                        <div style="display: flex; gap: 0.5rem; align-items: center;">
                            <span style="font-family: monospace; font-size: 0.75rem; background: #f1f5f9; color: #64748b; padding: 0.25rem 0.5rem; border-radius: 6px;">
                                {{ $doc->agenda_number ?? 'AG/01/2024/00' . $loop->iteration }}
                            </span>
                            <span style="padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; 
                                {{ $doc->status === 'selesai' ? 'background: #dcfce7; color: #166534;' : 
                                   ($doc->status === 'berjalan' ? 'background: #fef3c7; color: #92400e;' : 
                                   'background: #e0f2fe; color: #075985;') }}">
                                {{ ucfirst($doc->status ?? 'draft') }}
                            </span>
                        </div>
                        <i class="fas fa-chevron-right" style="color: var(--text-muted); font-size: 0.875rem;"></i>
                    </div>
                    
                    <h4 style="font-weight: 600; color: var(--text-dark); margin: 0 0 0.5rem 0; font-size: 0.95rem;">
                        {{ Str::limit($doc->title, 60) }}
                    </h4>
                    
                    <div style="display: flex; gap: 1rem; font-size: 0.75rem; color: var(--text-muted);">
                        <span style="display: flex; align-items: center; gap: 0.25rem;">
                            <i class="fas fa-user"></i>
                            {{ $doc->creator->name ?? 'Sekretaris PKK' }}
                        </span>
                        <span style="display: flex; align-items: center; gap: 0.25rem;">
                            <i class="fas fa-calendar"></i>
                            {{ $doc->document_date?->format('d M Y') ?? now()->format('d M Y') }}
                        </span>
                    </div>
                </div>
                @empty
                <div style="text-align: center; padding: 3rem; color: var(--text-muted);">
                    <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                    <p>Belum ada surat</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Notifikasi --}}
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3 style="font-weight: 700; color: var(--text-dark); margin: 0;">Notifikasi</h3>
                <a href="#" style="color: #14b8a6; text-decoration: none; font-weight: 500; font-size: 0.9rem;">
                    Semua →
                </a>
            </div>
            
            <div style="text-align: center; padding: 2rem; color: var(--text-muted);">
                <i class="fas fa-bell-slash" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                <p>Tidak ada notifikasi baru</p>
            </div>
        </div>
    </div>

    {{-- Alur Proses Surat --}}
    <div class="card">
        <h3 style="font-weight: 700; color: var(--text-dark); margin-bottom: 1.5rem;">Alur Proses Surat di SIDONGAN</h3>
        
        <div style="display: flex; align-items: center; justify-content: space-between; overflow-x: auto; padding: 1rem 0;">
            <div style="display: flex; align-items: center; gap: 1rem; min-width: max-content;">
                {{-- Step 1: Bupati --}}
                <div style="text-align: center;">
                    <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #e0e7ff, #c7d2fe); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.75rem;">
                        <i class="fas fa-landmark" style="color: #4f46e5; font-size: 1.5rem;"></i>
                    </div>
                    <div style="font-weight: 600; font-size: 0.875rem; color: var(--text-dark);">Bupati Toba</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">Kirim Surat</div>
                </div>
                
                <i class="fas fa-arrow-right" style="color: var(--text-muted); font-size: 1.25rem;"></i>
                
                {{-- Step 2: Sekretaris --}}
                <div style="text-align: center;">
                    <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #dbeafe, #bfdbfe); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.75rem;">
                        <i class="fas fa-user-edit" style="color: #2563eb; font-size: 1.5rem;"></i>
                    </div>
                    <div style="font-weight: 600; font-size: 0.875rem; color: var(--text-dark);">Sekretaris</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">Agenda & Upload</div>
                </div>
                
                <i class="fas fa-arrow-right" style="color: var(--text-muted); font-size: 1.25rem;"></i>
                
                {{-- Step 3: Ketua PKK --}}
                <div style="text-align: center;">
                    <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #fee2e2, #fecaca); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.75rem;">
                        <i class="fas fa-user-tie" style="color: #dc2626; font-size: 1.5rem;"></i>
                    </div>
                    <div style="font-weight: 600; font-size: 0.875rem; color: var(--text-dark);">Ketua PKK</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">Disposisi</div>
                </div>
                
                <i class="fas fa-arrow-right" style="color: var(--text-muted); font-size: 1.25rem;"></i>
                
                {{-- Step 4: Pelaksana --}}
                <div style="text-align: center;">
                    <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #d1fae5, #a7f3d0); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.75rem;">
                        <i class="fas fa-users" style="color: #059669; font-size: 1.5rem;"></i>
                    </div>
                    <div style="font-weight: 600; font-size: 0.875rem; color: var(--text-dark);">Pelaksana</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">Kegiatan & Laporan</div>
                </div>
                
                <i class="fas fa-arrow-right" style="color: var(--text-muted); font-size: 1.25rem;"></i>
                
                {{-- Step 5: Ketua PKK Verifikasi --}}
                <div style="text-align: center;">
                    <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #e9d5ff, #d8b4fe); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.75rem;">
                        <i class="fas fa-check-double" style="color: #7c3aed; font-size: 1.5rem;"></i>
                    </div>
                    <div style="font-weight: 600; font-size: 0.875rem; color: var(--text-dark);">Ketua PKK</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">Verifikasi</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection