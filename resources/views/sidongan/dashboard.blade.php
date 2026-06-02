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

    {{-- Stats Cards - Grid Layout --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
        {{-- Total Surat --}}
        <div style="background: linear-gradient(135deg, #3b82f6, #2563eb); border-radius: 0.75rem; padding: 1.25rem; color: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 3rem; height: 3rem; background: rgba(255,255,255,0.2); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
                    <i class="fas fa-envelope" style="font-size: 1.25rem;"></i>
                </div>
                <div>
                    <p style="font-size: 0.875rem; font-weight: 500; color: rgba(255,255,255,0.9); text-transform: uppercase; letter-spacing: 0.05em; margin: 0;">Total Surat</p>
                    <p style="font-size: 1.875rem; font-weight: 800; margin: 0.25rem 0 0 0;">{{ $totalSurat ?? 0 }}</p>
                    <p style="font-size: 0.75rem; color: rgba(255,255,255,0.8); margin: 0.25rem 0 0 0;">Dokumen & Agenda</p>
                </div>
            </div>
        </div>

        {{-- Sedang Berjalan --}}
        <div style="background: linear-gradient(135deg, #f97316, #ea580c); border-radius: 0.75rem; padding: 1.25rem; color: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 3rem; height: 3rem; background: rgba(255,255,255,0.2); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
                    <i class="fas fa-spinner" style="font-size: 1.25rem;"></i>
                </div>
                <div>
                    <p style="font-size: 0.875rem; font-weight: 500; color: rgba(255,255,255,0.9); text-transform: uppercase; letter-spacing: 0.05em; margin: 0;">Sedang Berjalan</p>
                    <p style="font-size: 1.875rem; font-weight: 800; margin: 0.25rem 0 0 0;">{{ $sedangBerjalan ?? 0 }}</p>
                    <p style="font-size: 0.75rem; color: rgba(255,255,255,0.8); margin: 0.25rem 0 0 0;">Proses Aktif</p>
                </div>
            </div>
        </div>

        {{-- Menunggu Proses --}}
        <div style="background: linear-gradient(135deg, #eab308, #ca8a04); border-radius: 0.75rem; padding: 1.25rem; color: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 3rem; height: 3rem; background: rgba(255,255,255,0.2); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
                    <i class="fas fa-clock" style="font-size: 1.25rem;"></i>
                </div>
                <div>
                    <p style="font-size: 0.875rem; font-weight: 500; color: rgba(255,255,255,0.9); text-transform: uppercase; letter-spacing: 0.05em; margin: 0;">Menunggu Proses</p>
                    <p style="font-size: 1.875rem; font-weight: 800; margin: 0.25rem 0 0 0;">{{ $menungguProses ?? 0 }}</p>
                    <p style="font-size: 0.75rem; color: rgba(255,255,255,0.8); margin: 0.25rem 0 0 0;">Perlu Tindakan</p>
                </div>
            </div>
        </div>

        {{-- Selesai --}}
        <div style="background: linear-gradient(135deg, #22c55e, #16a34a); border-radius: 0.75rem; padding: 1.25rem; color: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 3rem; height: 3rem; background: rgba(255,255,255,0.2); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
                    <i class="fas fa-check-circle" style="font-size: 1.25rem;"></i>
                </div>
                <div>
                    <p style="font-size: 0.875rem; font-weight: 500; color: rgba(255,255,255,0.9); text-transform: uppercase; letter-spacing: 0.05em; margin: 0;">Selesai</p>
                    <p style="font-size: 1.875rem; font-weight: 800; margin: 0.25rem 0 0 0;">{{ $selesai ?? 0 }}</p>
                    <p style="font-size: 0.75rem; color: rgba(255,255,255,0.8); margin: 0.25rem 0 0 0;">Terverifikasi</p>
                </div>
            </div>
        </div>

        {{-- Diarsipkan --}}
        <div style="background: linear-gradient(135deg, #a855f7, #9333ea); border-radius: 0.75rem; padding: 1.25rem; color: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 3rem; height: 3rem; background: rgba(255,255,255,0.2); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
                    <i class="fas fa-archive" style="font-size: 1.25rem;"></i>
                </div>
                <div>
                    <p style="font-size: 0.875rem; font-weight: 500; color: rgba(255,255,255,0.9); text-transform: uppercase; letter-spacing: 0.05em; margin: 0;">Diarsipkan</p>
                    <p style="font-size: 1.875rem; font-weight: 800; margin: 0.25rem 0 0 0;">{{ $diarsipkan ?? 0 }}</p>
                    <p style="font-size: 0.75rem; color: rgba(255,255,255,0.8); margin: 0.25rem 0 0 0;">Dokumen Lama</p>
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
                <a href="{{ route('sidongan.lapor-kegiatan.create') }}" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; border-radius: 0.5rem; border: 1px solid #e2e8f0; background: white; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='#f8fafc'; this.style.borderColor='#cbd5e1'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='white'; this.style.borderColor='#e2e8f0'; this.style.transform='translateY(0)'">
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
                <a href="{{ route('sidongan.lapor-kegiatan.create') }}" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; border-radius: 0.5rem; border: 1px solid #e2e8f0; background: white; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='#f8fafc'; this.style.borderColor='#cbd5e1'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='white'; this.style.borderColor='#e2e8f0'; this.style.transform='translateY(0)'">
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
        {{-- Surat Terbaru (Lebih Lebar) --}}
        <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e2e8f0;">
            <div style="padding: 1.25rem; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between;">
                <h3 style="font-size: 1rem; font-weight: 700; color: #1e293b; margin: 0;">Surat Terbaru</h3>
                <a href="{{ route('sidongan.documents.index') }}" style="font-size: 0.875rem; color: #2563eb; text-decoration: none; font-weight: 500;">Lihat Semua →</a>
            </div>
            
            <div style="display: flex; flex-direction: column;">
                @forelse($recentDocuments ?? [] as $doc)
                <a href="{{ route('sidongan.documents.show', $doc) }}" style="text-decoration: none; color: inherit; padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" 
                onmouseover="this.style.background='#f8fafc'" 
                onmouseout="this.style.background='white'">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                        <div style="display: flex; gap: 0.5rem; align-items: center;">
                            <span style="font-family: monospace; font-size: 0.75rem; background: #f1f5f9; color: #64748b; padding: 0.25rem 0.5rem; border-radius: 6px;">
                                {{ $doc->agenda_number ?? '-' }}
                            </span>
                            <span style="padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; 
                                {{ $doc->status === 'menunggu_disposisi' ? 'background: #fef3c7; color: #92400e;' : 
                                ($doc->status === 'berjalan' ? 'background: #dbeafe; color: #1e40af;' : 
                                ($doc->status === 'selesai' ? 'background: #dcfce7; color: #166534;' : 
                                ($doc->status === 'diarsipkan' ? 'background: #f3e8ff; color: #7c3aed;' : 
                                'background: #f1f5f9; color: #64748b;'))) }}">
                                {{ $doc->status === 'menunggu_disposisi' ? 'Menunggu Disposisi Ketua' : 
                                    ($doc->status === 'berjalan' ? 'Sedang Berjalan' : 
                                    ($doc->status === 'selesai' ? 'Selesai' : 
                                    ($doc->status === 'diarsipkan' ? 'Diarsipkan' : 
                                    ucfirst(str_replace('_', ' ', $doc->status))))) }}
                            </span>
                        </div>
                        <i class="fas fa-chevron-right" style="color: #cbd5e1; font-size: 0.875rem;"></i>
                    </div>
                    
                    <h4 style="font-weight: 600; color: #0f172a; margin: 0 0 0.5rem 0; font-size: 0.95rem;">
                        {{ Str::limit($doc->subject ?? $doc->title, 100) }}
                    </h4>
                    
                    <div style="display: flex; gap: 1rem; font-size: 0.75rem; color: #64748b;">
                        <span style="display: flex; align-items: center; gap: 0.25rem;">
                            <i class="fas fa-user"></i>
                            {{ $doc->creator->name ?? 'Sekretaris PKK' }}
                        </span>
                        <span style="display: flex; align-items: center; gap: 0.25rem;">
                            <i class="fas fa-calendar"></i>
                            {{ $doc->document_date ? \Carbon\Carbon::parse($doc->document_date)->locale('id')->translatedFormat('d M Y') : '-' }}
                        </span>
                    </div>
                </a>
                @empty
                <div style="padding: 3rem; text-align: center; color: #64748b;">
                    <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 0.75rem; color: #cbd5e1;"></i>
                    <p style="margin: 0;">Belum ada surat</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Notifikasi Section --}}
        <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; padding: 1.25rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <div style="display: flex; align-items: baseline; gap: 0.5rem;">
                    <h3 style="font-weight: 700; color: #0f172a; margin: 0; font-size: 1rem;">Notifikasi</h3>
                    @if(isset($unreadCount) && $unreadCount > 0)
                    <span style="background: #ef4444; color: white; font-size: 0.7rem; padding: 0.1rem 0.4rem; border-radius: 9999px; font-weight: 600; line-height: 1;">
                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                    </span>
                    @endif
                </div>
                <a href="{{ route('sidongan.notifications') }}" style="color: #3b82f6; text-decoration: none; font-size: 0.875rem; font-weight: 500;">
                    Semua →
                </a>
            </div>
            
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                @forelse($notifications ?? [] as $notif)
                <div style="padding: 0.75rem; border-radius: 0.5rem; background: {{ $notif->read_at ? '#f8fafc' : '#eff6ff' }}; border: 1px solid {{ $notif->read_at ? '#e2e8f0' : '#bfdbfe' }}; display: flex; gap: 0.75rem; align-items: start;">
                    <div style="width: 2rem; height: 2rem; background: {{ $notif->read_at ? '#cbd5e1' : '#3b82f6' }}; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fas fa-bell" style="font-size: 0.75rem; color: white;"></i>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <p style="font-size: 0.85rem; font-weight: 500; color: #0f172a; margin: 0 0 0.25rem 0; line-height: 1.4;">
                            {{ $notif->message }}
                        </p>
                        <span style="font-size: 0.7rem; color: #94a3b8;">
                            {{ \Carbon\Carbon::parse($notif->created_at)->locale('id')->translatedFormat('d M Y, H.i') }}
                        </span>
                    </div>
                    @if(!$notif->read_at)
                    <div style="width: 0.5rem; height: 0.5rem; background: #3b82f6; border-radius: 50%; flex-shrink: 0; margin-top: 0.4rem;"></div>
                    @endif
                </div>
                @empty
                <div style="text-align: center; padding: 2rem 1rem; color: #94a3b8;">
                    <i class="fas fa-bell-slash" style="font-size: 2rem; margin-bottom: 0.5rem; opacity: 0.3;"></i>
                    <p style="font-size: 0.875rem; margin: 0;">Tidak ada notifikasi baru</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ✅ PERUBAHAN 2: Tambahkan Alur Proses Surat di SIDONGAN --}}
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
@endsection