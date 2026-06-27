@extends('sidongan.layouts.app')
@section('title', 'Dashboard - SIDONGAN')

@section('content')
@php
    $currentUser = auth()->guard('sidongan')->user();
@endphp

<div>
    {{-- Header --}}
    <div style="margin-bottom: 1.5rem;">
        <h1 style="font-size: 1.5rem; font-weight: 700; color: #1e293b; margin: 0;">Selamat Datang di SIDONGAN</h1>
        <p style="font-size: 0.875rem; color: #64748b; margin-top: 0.25rem;">Ini adalah dashboard admin untuk mengelola dokumen organisasi, agenda, dan naskah PKK Kabupaten Toba.</p>
    </div>

    {{-- Stats Cards - Mengikuti Desain Daftar Surat --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
        {{-- Total Surat - BIRU --}}
        <div style="background: linear-gradient(135deg, #3b82f6, #2563eb); border-radius: 0.75rem; padding: 1.25rem; color: white; position: relative; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3); transition: all 0.3s;"
            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 16px rgba(59, 130, 246, 0.4)'"
            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px -1px rgba(59, 130, 246, 0.3)'">
            <div style="position: absolute; top: -10px; right: -10px; width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
            <div style="display: flex; align-items: center; gap: 1rem; position: relative; z-index: 1;">
                <div style="width: 3rem; height: 3rem; background: rgba(255,255,255,0.25); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
                    <i class="fas fa-envelope" style="font-size: 1.25rem;"></i>
                </div>
                <div>
                    <p style="font-size: 0.875rem; opacity: 0.95; margin: 0;">Total Surat</p>
                    <p style="font-size: 1.875rem; font-weight: 800; margin: 0.25rem 0 0 0;">{{ $totalSurat ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        {{-- Sedang Berjalan - ORANGE --}}
        <div style="background: linear-gradient(135deg, #f97316, #ea580c); border-radius: 0.75rem; padding: 1.25rem; color: white; position: relative; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(249, 115, 22, 0.3); transition: all 0.3s;"
            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 16px rgba(249, 115, 22, 0.4)'"
            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px -1px rgba(249, 115, 22, 0.3)'">
            <div style="position: absolute; top: -10px; right: -10px; width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
            <div style="display: flex; align-items: center; gap: 1rem; position: relative; z-index: 1;">
                <div style="width: 3rem; height: 3rem; background: rgba(255,255,255,0.25); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
                    <i class="fas fa-spinner fa-spin" style="font-size: 1.25rem;"></i>
                </div>
                <div>
                    <p style="font-size: 0.875rem; opacity: 0.95; margin: 0;">Sedang Berjalan</p>
                    <p style="font-size: 1.875rem; font-weight: 800; margin: 0.25rem 0 0 0;">{{ $sedangBerjalan ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        {{-- Menunggu Proses - KUNING --}}
        <div style="background: linear-gradient(135deg, #eab308, #ca8a04); border-radius: 0.75rem; padding: 1.25rem; color: white; position: relative; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(234, 179, 8, 0.3); transition: all 0.3s;"
            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 16px rgba(234, 179, 8, 0.4)'"
            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px -1px rgba(234, 179, 8, 0.3)'">
            <div style="position: absolute; top: -10px; right: -10px; width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
            <div style="display: flex; align-items: center; gap: 1rem; position: relative; z-index: 1;">
                <div style="width: 3rem; height: 3rem; background: rgba(255,255,255,0.25); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
                    <i class="fas fa-clock" style="font-size: 1.25rem;"></i>
                </div>
                <div>
                    <p style="font-size: 0.875rem; opacity: 0.95; margin: 0;">Menunggu Proses</p>
                    <p style="font-size: 1.875rem; font-weight: 800; margin: 0.25rem 0 0 0;">{{ $menungguProses ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        {{-- Selesai - HIJAU --}}
        <div style="background: linear-gradient(135deg, #22c55e, #16a34a); border-radius: 0.75rem; padding: 1.25rem; color: white; position: relative; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(34, 197, 94, 0.3); transition: all 0.3s;"
            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 16px rgba(34, 197, 94, 0.4)'"
            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px -1px rgba(34, 197, 94, 0.3)'">
            <div style="position: absolute; top: -10px; right: -10px; width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
            <div style="display: flex; align-items: center; gap: 1rem; position: relative; z-index: 1;">
                <div style="width: 3rem; height: 3rem; background: rgba(255,255,255,0.25); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
                    <i class="fas fa-check-circle" style="font-size: 1.25rem;"></i>
                </div>
                <div>
                    <p style="font-size: 0.875rem; opacity: 0.95; margin: 0;">Selesai</p>
                    <p style="font-size: 1.875rem; font-weight: 800; margin: 0.25rem 0 0 0;">{{ $selesai ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        {{-- Diarsipkan - UNGU --}}
        <div style="background: linear-gradient(135deg, #8b5cf6, #7c3aed); border-radius: 0.75rem; padding: 1.25rem; color: white; position: relative; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(139, 92, 246, 0.3); transition: all 0.3s;"
            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 16px rgba(139, 92, 246, 0.4)'"
            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px -1px rgba(139, 92, 246, 0.3)'">
            <div style="position: absolute; top: -10px; right: -10px; width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
            <div style="display: flex; align-items: center; gap: 1rem; position: relative; z-index: 1;">
                <div style="width: 3rem; height: 3rem; background: rgba(255,255,255,0.25); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
                    <i class="fas fa-archive" style="font-size: 1.25rem;"></i>
                </div>
                <div>
                    <p style="font-size: 0.875rem; opacity: 0.95; margin: 0;">Diarsipkan</p>
                    <p style="font-size: 1.875rem; font-weight: 800; margin: 0.25rem 0 0 0;">{{ $diarsipkan ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- AKSI CEPAT (BARU) --}}
    <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; padding: 1.5rem; margin-bottom: 1.5rem;">
        <h3 style="font-size: 1.125rem; font-weight: 700; color: #1e293b; margin: 0 0 1rem 0;">Aksi Cepat</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            
            @if($currentUser && $currentUser->hasSidonganRole('sekretaris'))
            {{-- Sekretaris: Buat Surat Baru --}}
            <a href="{{ route('sidongan.documents.create') }}" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; border-radius: 0.5rem; border: 1px solid #e2e8f0; background: white; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='#f8fafc'; this.style.borderColor='#cbd5e1'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='white'; this.style.borderColor='#e2e8f0'; this.style.transform='translateY(0)'">
                <div style="width: 2.5rem; height: 2.5rem; background: #dbeafe; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-plus" style="color: #2563eb;"></i>
                </div>
                <div>
                    <div style="font-weight: 600; color: #0f172a; font-size: 0.95rem;">Buat Surat Baru</div>
                    <div style="font-size: 0.75rem; color: #64748b;">Input surat masuk</div>
                </div>
            </a>
            @endif

            @if($currentUser && $currentUser->hasSidonganRole('ketua'))
            {{-- Ketua: Disposisi Surat --}}
            <a href="{{ route('sidongan.disposisi') }}" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; border-radius: 0.5rem; border: 1px solid #e2e8f0; background: white; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='#f8fafc'; this.style.borderColor='#cbd5e1'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='white'; this.style.borderColor='#e2e8f0'; this.style.transform='translateY(0)'">
                <div style="width: 2.5rem; height: 2.5rem; background: #ffedd5; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-tasks" style="color: #ea580c;"></i>
                </div>
                <div>
                    <div style="font-weight: 600; color: #0f172a; font-size: 0.95rem;">Disposisi Surat</div>
                    <div style="font-size: 0.75rem; color: #64748b;">Tindak lanjuti surat</div>
                </div>
            </a>

            {{-- Ketua: Verifikasi Laporan --}}
            <a href="{{ route('sidongan.verifikasi') }}" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; border-radius: 0.5rem; border: 1px solid #e2e8f0; background: white; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='#f8fafc'; this.style.borderColor='#cbd5e1'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='white'; this.style.borderColor='#e2e8f0'; this.style.transform='translateY(0)'">
                <div style="width: 2.5rem; height: 2.5rem; background: #d1fae5; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-check-double" style="color: #059669;"></i>
                </div>
                <div>
                    <div style="font-weight: 600; color: #0f172a; font-size: 0.95rem;">Verifikasi Laporan</div>
                    <div style="font-size: 0.75rem; color: #64748b;">Setujui laporan</div>
                </div>
            </a>
            @endif

            @if($currentUser && ($currentUser->hasSidonganRole('bendahara') || $currentUser->isSidonganPokja()))
                {{-- Bendahara & Ketua Pokja: Lapor Kegiatan --}}
                <a href="{{ route('sidongan.lapor_kegiatan.create') }}" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; border-radius: 0.5rem; border: 1px solid #e2e8f0; background: white; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='#f8fafc'; this.style.borderColor='#cbd5e1'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='white'; this.style.borderColor='#e2e8f0'; this.style.transform='translateY(0)'">
                    <div style="width: 2.5rem; height: 2.5rem; background: #dcfce7; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-clipboard-list" style="color: #16a34a;"></i>
                    </div>
                    <div>
                        <div style="font-weight: 600; color: #0f172a; font-size: 0.95rem;">Lapor Kegiatan</div>
                        <div style="font-size: 0.75rem; color: #64748b;">Laporkan aktivitas</div>
                    </div>
                </a>
            @endif

            @if($currentUser && ($currentUser->hasSidonganRole('bendahara') || $currentUser->hasSidonganRole('ketua_pokja')))
                {{-- Bendahara & Ketua Pokja: Lapor Kegiatan --}}
                <a href="{{ route('sidongan.lapor_kegiatan.create') }}" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; border-radius: 0.5rem; border: 1px solid #e2e8f0; background: white; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='#f8fafc'; this.style.borderColor='#cbd5e1'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='white'; this.style.borderColor='#e2e8f0'; this.style.transform='translateY(0)'">
                    <div style="width: 2.5rem; height: 2.5rem; background: #dcfce7; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-clipboard-list" style="color: #16a34a;"></i>
                    </div>
                    <div>
                        <div style="font-weight: 600; color: #0f172a; font-size: 0.95rem;">Lapor Kegiatan</div>
                        <div style="font-size: 0.75rem; color: #64748b;">Laporkan aktivitas</div>
                    </div>
                </a>
            @endif

            {{-- Semua Role: Lihat Surat --}}
            <a href="{{ route('sidongan.documents.index') }}" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; border-radius: 0.5rem; border: 1px solid #e2e8f0; background: white; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='#f8fafc'; this.style.borderColor='#cbd5e1'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='white'; this.style.borderColor='#e2e8f0'; this.style.transform='translateY(0)'">
                <div style="width: 2.5rem; height: 2.5rem; background: #f3e8ff; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-list" style="color: #9333ea;"></i>
                </div>
                <div>
                    <div style="font-weight: 600; color: #0f172a; font-size: 0.95rem;">Daftar Surat</div>
                    <div style="font-size: 0.75rem; color: #64748b;">Lihat semua surat</div>
                </div>
            </a>

            {{-- Semua Role: Arsip --}}
            <a href="{{ route('sidongan.arsip') }}" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; border-radius: 0.5rem; border: 1px solid #e2e8f0; background: white; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='#f8fafc'; this.style.borderColor='#cbd5e1'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='white'; this.style.borderColor='#e2e8f0'; this.style.transform='translateY(0)'">
                <div style="width: 2.5rem; height: 2.5rem; background: #fef3c7; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-archive" style="color: #d97706;"></i>
                </div>
                <div>
                    <div style="font-weight: 600; color: #0f172a; font-size: 0.95rem;">Arsip Surat</div>
                    <div style="font-size: 0.75rem; color: #64748b;">Dokumen tersimpan</div>
                </div>
            </a>

        </div>
    </div>

    {{-- PERUBAHAN 1: Surat Terbaru & Notifikasi - Grid 2:1 agar Surat Terbaru lebih lebar --}}
        <style>
        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        @media (max-width: 1024px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
    <div class="dashboard-grid">
        {{-- Surat Terbaru --}}
        <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; overflow: hidden;">
            <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 1rem; font-weight: 700; color: #0f172a; margin: 0;">Surat Terbaru</h3>
                <a href="{{ route('sidongan.documents.index') }}" style="font-size: 0.875rem; color: #3b82f6; text-decoration: none; font-weight: 600;">
                    Lihat Semua →
                </a>
            </div>
            
            <div style="padding: 0;">
                @forelse($recentDocuments as $doc)
                @php
                    // Ambil data laporan untuk status dinamis
                    $reports = $doc->activityReports ?? collect();
                    $latestReport = $reports->first();
                    $rejectedReport = $reports->where('status', 'ditolak')->first();
                    $hasReport = $reports->count() > 0;
                    
                    // Tentukan status label dan warna
                    if ($doc->status === 'menunggu_disposisi') {
                        $statusLabel = 'Menunggu Disposisi';
                        $statusColor = '#fef3c7';
                        $statusTextColor = '#92400e';
                        $statusIcon = 'fa-hourglass-half';
                    } elseif ($doc->status === 'berjalan') {
                        if ($rejectedReport) {
                            $statusLabel = 'Perlu Laporan Ulang';
                            $statusColor = '#fee2e2';
                            $statusTextColor = '#991b1b';
                            $statusIcon = 'fa-times-circle';
                        } elseif ($hasReport) {
                            $statusLabel = 'Menunggu Verifikasi';
                            $statusColor = '#dbeafe';
                            $statusTextColor = '#1e40af';
                            $statusIcon = 'fa-clock';
                        } else {
                            $statusLabel = 'Belum Dilaporkan';
                            $statusColor = '#f1f5f9';
                            $statusTextColor = '#475569';
                            $statusIcon = 'fa-file-circle-xmark';
                        }
                    } elseif ($doc->status === 'selesai') {
                        $statusLabel = 'Selesai';
                        $statusColor = '#d1fae5';
                        $statusTextColor = '#065f46';
                        $statusIcon = 'fa-check-circle';
                    } elseif ($doc->status === 'diarsipkan') {
                        $statusLabel = 'Diarsipkan';
                        $statusColor = '#f3e8ff';
                        $statusTextColor = '#7c3aed';
                        $statusIcon = 'fa-archive';
                    } else {
                        $statusLabel = ucfirst(str_replace('_', ' ', $doc->status));
                        $statusColor = '#f1f5f9';
                        $statusTextColor = '#475569';
                        $statusIcon = 'fa-circle';
                    }
                @endphp
                
                <a href="{{ route('sidongan.documents.show', $doc) }}" 
                style="display: block; padding: 1rem 1.5rem; border-bottom: 1px solid #f1f5f9; text-decoration: none; transition: background 0.2s;"
                onmouseover="this.style.background='#f8fafc'" 
                onmouseout="this.style.background='white'">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem;">
                        <div style="flex: 1; min-width: 0;">
                            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem; flex-wrap: wrap;">
                                <span style="font-family: monospace; font-size: 0.75rem; font-weight: 600; color: #3b82f6; background: #eff6ff; padding: 0.25rem 0.5rem; border-radius: 0.25rem;">
                                    {{ $doc->agenda_number ?? '-' }}
                                </span>
                                <span style="display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.25rem 0.65rem; background: {{ $statusColor }}; color: {{ $statusTextColor }}; border-radius: 9999px; font-size: 0.7rem; font-weight: 600;">
                                    <i class="fas {{ $statusIcon }}" style="font-size: 0.6rem;"></i>
                                    {{ $statusLabel }}
                                </span>
                            </div>
                            <h4 style="font-size: 0.9rem; font-weight: 600; color: #0f172a; margin: 0 0 0.35rem 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ Str::limit($doc->subject ?? $doc->title, 70) }}
                            </h4>
                            <div style="display: flex; align-items: center; gap: 1rem; font-size: 0.75rem; color: #64748b;">
                                <span style="display: inline-flex; align-items: center; gap: 0.35rem;">
                                    <i class="fas fa-user" style="font-size: 0.65rem;"></i>
                                    {{ $doc->creator->name ?? 'Sekretaris PKK' }}
                                </span>
                                <span style="display: inline-flex; align-items: center; gap: 0.35rem;">
                                    <i class="fas fa-calendar" style="font-size: 0.65rem;"></i>
                                    {{ $doc->document_date ? $doc->document_date->locale('id')->translatedFormat('d M Y') : '-' }}
                                </span>
                            </div>
                            
                            {{-- Info tambahan untuk status khusus --}}
                            @if($rejectedReport)
                                <div style="margin-top: 0.5rem; padding: 0.5rem 0.75rem; background: #fef2f2; border-left: 3px solid #ef4444; border-radius: 0.25rem; font-size: 0.7rem;">
                                    <div style="color: #991b1b; font-weight: 600; margin-bottom: 0.15rem;">
                                        <i class="fas fa-user" style="font-size: 0.6rem;"></i>
                                        {{ $rejectedReport->creator->name ?? 'Unknown' }}
                                    </div>
                                    @if($rejectedReport->catatan_verifikasi)
                                        <div style="color: #7f1d1d; font-style: italic;">
                                            "{{ Str::limit($rejectedReport->catatan_verifikasi, 50) }}"
                                        </div>
                                    @endif
                                </div>
                            @elseif($hasReport && $doc->status === 'berjalan')
                                <div style="margin-top: 0.5rem; padding: 0.5rem 0.75rem; background: #eff6ff; border-left: 3px solid #3b82f6; border-radius: 0.25rem; font-size: 0.7rem; color: #1e40af;">
                                    <i class="fas fa-user" style="font-size: 0.6rem;"></i>
                                    {{ $latestReport->creator->name ?? 'Unknown' }}
                                </div>
                            @endif
                        </div>
                        <div style="flex-shrink: 0; color: #cbd5e1;">
                            <i class="fas fa-chevron-right" style="font-size: 0.875rem;"></i>
                        </div>
                    </div>
                </a>
                @empty
                <div style="padding: 3rem 2rem; text-align: center;">
                    <div style="width: 64px; height: 64px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                        <i class="fas fa-inbox" style="color: #94a3b8; font-size: 1.5rem;"></i>
                    </div>
                    <p style="font-size: 0.875rem; color: #64748b; margin: 0;">Belum ada surat</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Notifikasi Section --}}
        <div style="background: white; border-radius: 0.75rem; padding: 1.25rem; border: 1px solid #e2e8f0;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3 style="font-size: 1rem; font-weight: 700; color: #1e293b; margin: 0;">Notifikasi</h3>
                <a href="{{ route('sidongan.notifications') }}" style="font-size: 0.85rem; color: #3b82f6; text-decoration: none; font-weight: 500;">
                    Semua →
                </a>
            </div>
            
            <div style="max-height: 400px; overflow-y: auto;">
                @php
                    // Query yang SAMA PERSIS dengan controller notifications()
                    // HANYA ambil yang BELUM DIBACA (read_at = null)
                    $user = auth()->guard('sidongan')->user();
                    $dashboardNotifications = \App\Models\Notification::where('user_id', $user->id)
                        ->whereNull('read_at')  // ← PENTING: Hanya yang belum dibaca
                        ->latest()
                        ->take(5)
                        ->get();
                @endphp
                
                @forelse($dashboardNotifications as $notif)
                <div style="padding: 1rem; border-bottom: 1px solid #f1f5f9; background: #eff6ff; border-radius: 0.5rem; margin-bottom: 0.75rem; cursor: pointer;" 
                    onclick="markNotificationReadAndRedirect({{ $notif->id }}, '{{ route('sidongan.documents.show', $notif->related_id) }}')">
                    <div style="display: flex; gap: 0.75rem; align-items: start;">
                        <div style="width: 2.5rem; height: 2.5rem; background: #dbeafe; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fas fa-bell" style="color: #3b82f6; font-size: 0.85rem;"></i>
                        </div>
                        <div style="flex: 1; min-width: 0;">
                            <p style="font-size: 0.85rem; color: #0f172a; margin: 0 0 0.25rem 0; line-height: 1.4; font-weight: 500;">
                                {{ Str::limit($notif->message, 80) }}
                            </p>
                            <span style="font-size: 0.75rem; color: #94a3b8;">
                                {{ $notif->created_at->locale('id')->translatedFormat('d M Y, H.i') }}
                            </span>
                        </div>
                    </div>
                </div>
                @empty
                {{-- Empty State --}}
                <div style="text-align: center; padding: 2rem 1rem;">
                    <div style="width: 4rem; height: 4rem; background: #f0fdf4; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.75rem;">
                        <svg style="width: 2rem; height: 2rem; stroke: #22c55e;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p style="font-size: 0.9rem; color: #1e293b; margin: 0; font-weight: 600;">Semua Notifikasi Sudah Dibaca</p>
                    <p style="font-size: 0.8rem; color: #64748b; margin: 0.25rem 0 0 0;">Tidak ada notifikasi baru</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Alur Proses Surat di SIDONGAN --}}
    <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; padding: 1.5rem;">
        <h3 style="font-size: 1rem; font-weight: 700; color: #1e293b; margin: 0 0 1.5rem 0;">Alur Proses Surat di SIDONGAN</h3>
        
        <div style="display: flex; align-items: center; justify-content: space-between; overflow-x: auto; padding-bottom: 0.5rem; gap: 0.5rem;">
            
            {{-- Step 1: Bupati Toba --}}
            <div style="text-align: center; min-width: 100px;">
                <div style="width: 48px; height: 48px; background: #e0e7ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.5rem;">
                    <i class="fas fa-landmark" style="color: #4f46e5; font-size: 1.25rem;"></i>
                </div>
                <p style="font-size: 0.75rem; font-weight: 600; color: #334155; margin: 0;">Bupati Toba</p>
                <p style="font-size: 0.7rem; color: #94a3b8; margin: 0;">Kirim Surat</p>
            </div>
            
            <i class="fas fa-arrow-right" style="color: #cbd5e1; font-size: 0.875rem;"></i>
            
            {{-- Step 2: Sekretaris --}}
            <div style="text-align: center; min-width: 100px;">
                <div style="width: 48px; height: 48px; background: #dbeafe; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.5rem;">
                    <i class="fas fa-user-edit" style="color: #2563eb; font-size: 1.25rem;"></i>
                </div>
                <p style="font-size: 0.75rem; font-weight: 600; color: #334155; margin: 0;">Sekretaris</p>
                <p style="font-size: 0.7rem; color: #94a3b8; margin: 0;">Agenda & Upload</p>
            </div>
            
            <i class="fas fa-arrow-right" style="color: #cbd5e1; font-size: 0.875rem;"></i>
            
            {{-- Step 3: Ketua PKK --}}
            <div style="text-align: center; min-width: 100px;">
                <div style="width: 48px; height: 48px; background: #fee2e2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.5rem;">
                    <i class="fas fa-user-tie" style="color: #dc2626; font-size: 1.25rem;"></i>
                </div>
                <p style="font-size: 0.75rem; font-weight: 600; color: #334155; margin: 0;">Ketua PKK</p>
                <p style="font-size: 0.7rem; color: #94a3b8; margin: 0;">Disposisi</p>
            </div>
            
            <i class="fas fa-arrow-right" style="color: #cbd5e1; font-size: 0.875rem;"></i>
            
            {{-- Step 4: Pelaksana --}}
            <div style="text-align: center; min-width: 100px;">
                <div style="width: 48px; height: 48px; background: #d1fae5; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.5rem;">
                    <i class="fas fa-users" style="color: #059669; font-size: 1.25rem;"></i>
                </div>
                <p style="font-size: 0.75rem; font-weight: 600; color: #334155; margin: 0;">Pelaksana</p>
                <p style="font-size: 0.7rem; color: #94a3b8; margin: 0;">Kegiatan & Laporan</p>
            </div>
            
            <i class="fas fa-arrow-right" style="color: #cbd5e1; font-size: 0.875rem;"></i>
            
            {{-- Step 5: Ketua PKK Verifikasi --}}
            <div style="text-align: center; min-width: 100px;">
                <div style="width: 48px; height: 48px; background: #e9d5ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.5rem;">
                    <i class="fas fa-check-double" style="color: #7c3aed; font-size: 1.25rem;"></i>
                </div>
                <p style="font-size: 0.75rem; font-weight: 600; color: #334155; margin: 0;">Ketua PKK</p>
                <p style="font-size: 0.7rem; color: #94a3b8; margin: 0;">Verifikasi</p>
            </div>
            
        </div>
    </div>
</div>

{{-- Script untuk mark as read + redirect --}}
<script>
function markNotificationReadAndRead(notificationId, redirectUrl) {
    // Mark as read dulu
    fetch(`/sidongan/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // Redirect ke halaman tujuan
            window.location.href = redirectUrl;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Tetap redirect meski error
        window.location.href = redirectUrl;
    });
}
</script>
@endsection