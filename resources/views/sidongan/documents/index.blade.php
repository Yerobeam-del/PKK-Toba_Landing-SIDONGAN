@extends('sidongan.layouts.app')
@section('title', 'Daftar Surat - SIDONGAN')

@section('content')
@php
    $currentUser = auth()->guard('sidongan')->user();
@endphp

<div>
    {{-- Header Section --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 700; color: #1e293b; margin: 0;">Daftar Surat</h1>
            <p style="font-size: 0.875rem; color: #64748b; margin-top: 0.25rem;">Kelola semua dokumen surat masuk dan keluar</p>
        </div>
        @if($currentUser && $currentUser->hasSidonganRole('sekretaris'))
        <a href="{{ route('sidongan.documents.create') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.25rem; background: linear-gradient(135deg, #3b82f6, #2563eb); color: white; text-decoration: none; border-radius: 0.5rem; font-weight: 600; box-shadow: 0 2px 8px rgba(59,130,246,0.3); transition: all 0.2s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(59,130,246,0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(59,130,246,0.3)'">
            <i class="fas fa-plus" style="font-size: 1rem;"></i>
            <span>Buat Surat Baru</span>
        </a>
        @endif
    </div>

    {{-- Stats Cards --}}
    @php
        $statsQuery = \App\Models\Document::query();
        if ($currentUser && $currentUser->hasSidonganRole('sekretaris')) {
            $statsQuery->where('created_by', $currentUser->id);
        }
        $statSelesai = (clone $statsQuery)->where('status', 'selesai')->count();
        $statBerjalan = (clone $statsQuery)->where('status', 'berjalan')->count();
        $statMenunggu = (clone $statsQuery)->whereIn('status', ['menunggu_disposisi', 'menunggu_verifikasi'])->count();
    @endphp
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
        {{-- Total Surat --}}
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
                    <p style="font-size: 1.875rem; font-weight: 800; margin: 0.25rem 0 0 0;">{{ $totalDocuments ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        {{-- Selesai --}}
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
                    <p style="font-size: 1.875rem; font-weight: 800; margin: 0.25rem 0 0 0;">{{ $statSelesai ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        {{-- Berjalan --}}
        <div style="background: linear-gradient(135deg, #f97316, #ea580c); border-radius: 0.75rem; padding: 1.25rem; color: white; position: relative; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(249, 115, 22, 0.3); transition: all 0.3s;"
            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 16px rgba(249, 115, 22, 0.4)'"
            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px -1px rgba(249, 115, 22, 0.3)'">
            <div style="position: absolute; top: -10px; right: -10px; width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
            <div style="display: flex; align-items: center; gap: 1rem; position: relative; z-index: 1;">
                <div style="width: 3rem; height: 3rem; background: rgba(255,255,255,0.25); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
                    <i class="fas fa-spinner fa-spin" style="font-size: 1.25rem;"></i>
                </div>
                <div>
                    <p style="font-size: 0.875rem; opacity: 0.95; margin: 0;">Berjalan</p>
                    <p style="font-size: 1.875rem; font-weight: 800; margin: 0.25rem 0 0 0;">{{ $statBerjalan ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        {{-- Menunggu Disposisi --}}
        <div style="background: linear-gradient(135deg, #eab308, #ca8a04); border-radius: 0.75rem; padding: 1.25rem; color: white; position: relative; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(234, 179, 8, 0.3); transition: all 0.3s;"
            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 16px rgba(234, 179, 8, 0.4)'"
            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px -1px rgba(234, 179, 8, 0.3)'">
            <div style="position: absolute; top: -10px; right: -10px; width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
            <div style="display: flex; align-items: center; gap: 1rem; position: relative; z-index: 1;">
                <div style="width: 3rem; height: 3rem; background: rgba(255,255,255,0.25); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
                    <i class="fas fa-clock" style="font-size: 1.25rem;"></i>
                </div>
                <div>
                    <p style="font-size: 0.875rem; opacity: 0.95; margin: 0;">Menunggu Disposisi</p>
                    <p style="font-size: 1.875rem; font-weight: 800; margin: 0.25rem 0 0 0;">{{ $statMenungguDisposisi ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Section --}}
    <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; padding: 1.25rem; margin-bottom: 1.5rem;">
        <form id="filterForm" method="GET" action="{{ route('sidongan.documents.index') }}">
            <div style="display: grid; grid-template-columns: 2fr 0.5fr 1fr; gap: 1rem; align-items: end;">
                {{-- Search --}}
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem;">Cari Dokumen</label>
                    <div style="position: relative;">
                        <i class="fas fa-search" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
                        <input type="text" name="search" id="searchInput" value="{{ request('search') }}" placeholder="Ketik untuk mencari berdasarkan judul atau nomor..." style="width: 100%; padding: 0.625rem 1rem 0.625rem 2.5rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.875rem; transition: all 0.2s;" onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'" onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                    </div>
                </div>

                {{-- Tampilkan Per Page --}}
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem;">Tampilkan</label>
                    <div style="position: relative;">
                        <select name="per_page" id="perPageSelect" style="width: 100%; padding: 0.625rem 2.5rem 0.625rem 1rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.875rem; background: white; cursor: pointer; appearance: none; -webkit-appearance: none; -moz-appearance: none;" onchange="document.getElementById('filterForm').submit()">
                            @foreach($allowedPerPages as $value)
                                <option value="{{ $value }}" {{ ($currentPerPage ?? 10) == $value ? 'selected' : '' }}>
                                    {{ $value }} surat
                                </option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: #94a3b8; pointer-events: none; font-size: 0.75rem;"></i>
                    </div>
                </div>

                {{-- Status --}}
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem;">Status</label>
                    <div style="position: relative;">
                        <select name="status" id="statusSelect" style="width: 100%; padding: 0.625rem 2.5rem 0.625rem 1rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.875rem; background: white; cursor: pointer; appearance: none; -webkit-appearance: none; -moz-appearance: none;" onchange="document.getElementById('filterForm').submit()">
                            <option value="">Semua Status</option>
                            <option value="menunggu_disposisi" {{ request('status') == 'menunggu_disposisi' ? 'selected' : '' }}>Menunggu Disposisi</option>
                            <option value="berjalan" {{ request('status') == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                            <option value="menunggu_verifikasi" {{ request('status') == 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="diarsipkan" {{ request('status') == 'diarsipkan' ? 'selected' : '' }}>Diarsipkan</option>
                        </select>
                        <i class="fas fa-chevron-down" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: #94a3b8; pointer-events: none; font-size: 0.75rem;"></i>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Documents Table --}}
    <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; overflow: hidden;">
        @if(isset($documents) && $documents->count() > 0)
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: linear-gradient(135deg, #0891b2, #14b8a6); color: white;">
                    <tr>
                        <th style="padding: 1rem; text-align: left; font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">NO. AGENDA</th>
                        <th style="padding: 1rem; text-align: left; font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">PERIHAL</th>
                        <th style="padding: 1rem; text-align: left; font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">NO. SURAT</th>
                        <th style="padding: 1rem; text-align: left; font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">TANGGAL</th>
                        <th style="padding: 1rem; text-align: left; font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">DISPOSISI</th>
                        <th style="padding: 1rem; text-align: left; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; min-width: 220px;">STATUS</th>
                        <th style="padding: 1rem; text-align: center; font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($documents as $doc)
                    <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                        <td style="padding: 1rem; font-weight: 600; color: #3b82f6; font-family: monospace; font-size: 0.875rem;">
                            {{ $doc->agenda_number ?? '-' }}
                        </td>
                        
                        <td style="padding: 1rem;">
                            <div style="font-weight: 600; color: #0f172a; margin-bottom: 0.25rem;">{{ Str::limit($doc->subject ?? $doc->title, 60) }}</div>
                            @if($doc->sender)
                            <div style="font-size: 0.75rem; color: #64748b;">{{ $doc->sender }}</div>
                            @endif
                        </td>
                        
                        <td style="padding: 1rem; color: #475569; font-size: 0.875rem;">
                            {{ $doc->document_number ?? '-' }}
                        </td>
                        
                        <td style="padding: 1rem; color: #475569; font-size: 0.875rem;">
                            {{ $doc->document_date ? $doc->document_date->format('d M Y') : '-' }}
                        </td>
                        
                        <td style="padding: 1rem;">
                            @php
                                $disposisiData = is_string($doc->disposisi_data) ? json_decode($doc->disposisi_data, true) : $doc->disposisi_data;
                            @endphp
                            @if($disposisiData && isset($disposisiData['target_roles']))
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
                            @foreach($disposisiData['target_roles'] as $role)
                                <span style="display: inline-block; padding: 0.25rem 0.5rem; background: #dbeafe; color: #1e40af; border-radius: 0.25rem; font-size: 0.75rem; margin: 0.125rem;">
                                    {{ $rolesMap[$role] ?? ucfirst(str_replace('_', ' ', $role)) }}
                                </span>
                            @endforeach
                            @else
                                <span style="color: #94a3b8; font-size: 0.75rem;">Belum</span>
                            @endif
                        </td>
                        
                        <td style="padding: 1rem;">
                            @php
                                $reports = $doc->activityReports ?? collect();
                                $latestReport = $reports->first();
                                $rejectedReport = $reports->where('status', 'ditolak')->first();
                                $hasReport = $reports->count() > 0;
                            @endphp

                            @if($doc->status === 'menunggu_disposisi')
                                <span style="display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.375rem 0.75rem; background: #fef3c7; color: #92400e; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">
                                    <i class="fas fa-hourglass-half" style="font-size: 0.65rem;"></i>
                                    Menunggu Disposisi
                                </span>
                            @elseif($doc->status === 'berjalan')
                                @if($rejectedReport)
                                    <div style="display: flex; flex-direction: column; gap: 0.35rem;">
                                        <span style="display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.375rem 0.75rem; background: #fee2e2; color: #991b1b; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; width: fit-content;">
                                            <i class="fas fa-times-circle" style="font-size: 0.65rem;"></i>
                                            Perlu Laporan Ulang
                                        </span>
                                        <div style="padding: 0.5rem 0.75rem; background: #fef2f2; border-left: 3px solid #ef4444; border-radius: 0.25rem; font-size: 0.75rem;">
                                            <div style="color: #991b1b; font-weight: 600; margin-bottom: 0.15rem; display: flex; align-items: center; gap: 0.35rem;">
                                                <i class="fas fa-user" style="font-size: 0.65rem;"></i>
                                                {{ $rejectedReport->creator->name ?? 'Unknown' }}
                                            </div>
                                            @if($rejectedReport->catatan_verifikasi)
                                                <div style="color: #7f1d1d; font-style: italic; font-size: 0.7rem; line-height: 1.4;">
                                                    "{{ Str::limit($rejectedReport->catatan_verifikasi, 60) }}"
                                                </div>
                                            @endif
                                            <div style="color: #94a3b8; font-size: 0.65rem; margin-top: 0.2rem;">
                                                {{ $rejectedReport->created_at->locale('id')->translatedFormat('d M Y, H.i') }}
                                            </div>
                                        </div>
                                    </div>
                                @elseif($hasReport)
                                    <div style="display: flex; flex-direction: column; gap: 0.35rem;">
                                        <span style="display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.375rem 0.75rem; background: #dbeafe; color: #1e40af; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; width: fit-content;">
                                            <i class="fas fa-clock" style="font-size: 0.65rem;"></i>
                                            Menunggu Verifikasi
                                        </span>
                                        <div style="padding: 0.5rem 0.75rem; background: #eff6ff; border-left: 3px solid #3b82f6; border-radius: 0.25rem; font-size: 0.75rem;">
                                            <div style="color: #1e40af; font-weight: 600; display: flex; align-items: center; gap: 0.35rem;">
                                                <i class="fas fa-user" style="font-size: 0.65rem;"></i>
                                                {{ $latestReport->creator->name ?? 'Unknown' }}
                                            </div>
                                            <div style="color: #94a3b8; font-size: 0.65rem; margin-top: 0.15rem;">
                                                {{ $latestReport->created_at->locale('id')->translatedFormat('d M Y, H.i') }}
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span style="display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.375rem 0.75rem; background: #f1f5f9; color: #475569; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">
                                        <i class="fas fa-file-circle-xmark" style="font-size: 0.65rem;"></i>
                                        Belum Dilaporkan
                                    </span>
                                @endif
                            @elseif($doc->status === 'selesai')
                                <div style="display: flex; flex-direction: column; gap: 0.35rem;">
                                    <span style="display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.375rem 0.75rem; background: #d1fae5; color: #065f46; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; width: fit-content;">
                                        <i class="fas fa-check-circle" style="font-size: 0.65rem;"></i>
                                        Selesai
                                    </span>
                                    @if($latestReport)
                                        <div style="padding: 0.5rem 0.75rem; background: #f0fdf4; border-left: 3px solid #22c55e; border-radius: 0.25rem; font-size: 0.75rem;">
                                            <div style="color: #065f46; font-weight: 600; display: flex; align-items: center; gap: 0.35rem;">
                                                <i class="fas fa-user" style="font-size: 0.65rem;"></i>
                                                {{ $latestReport->creator->name ?? 'Unknown' }}
                                            </div>
                                            <div style="color: #94a3b8; font-size: 0.65rem; margin-top: 0.15rem;">
                                                {{ $latestReport->created_at->locale('id')->translatedFormat('d M Y, H.i') }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @elseif($doc->status === 'diarsipkan')
                                <span style="display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.375rem 0.75rem; background: #f3e8ff; color: #7c3aed; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">
                                    <i class="fas fa-archive" style="font-size: 0.65rem;"></i>
                                    Diarsipkan
                                </span>
                            @else
                                <span style="display: inline-block; padding: 0.375rem 0.75rem; background: #f1f5f9; color: #475569; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">
                                    {{ ucfirst(str_replace('_', ' ', $doc->status)) }}
                                </span>
                            @endif
                        </td>
                        
                        <td style="padding: 1rem; white-space: nowrap;">
                            <div style="display: flex; gap: 0.5rem; justify-content: center;">
                                <a href="{{ route('sidongan.documents.show', $doc) }}" 
                                style="display: inline-flex; align-items: center; justify-content: center; width: 2rem; height: 2rem; background: #dbeafe; color: #2563eb; border-radius: 0.375rem; text-decoration: none; transition: all 0.2s;"
                                onmouseover="this.style.background='#bfdbfe'" 
                                onmouseout="this.style.background='#dbeafe'"
                                title="Lihat Detail">
                                    <i class="fas fa-eye" style="font-size: 0.875rem;"></i>
                                </a>
                                
                                @if($currentUser && $currentUser->hasSidonganRole('sekretaris') && $doc->status === 'menunggu_disposisi')
                                    <a href="{{ route('sidongan.documents.edit', $doc) }}" 
                                    style="display: inline-flex; align-items: center; justify-content: center; width: 2rem; height: 2rem; background: #fef3c7; color: #d97706; border-radius: 0.375rem; text-decoration: none; transition: all 0.2s;"
                                    onmouseover="this.style.background='#fde68a'" 
                                    onmouseout="this.style.background='#fef3c7'"
                                    title="Edit Surat">
                                        <i class="fas fa-edit" style="font-size: 0.875rem;"></i>
                                    </a>
                                    
                                    <form action="{{ route('sidongan.documents.destroy', $doc) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Yakin ingin menghapus surat ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                style="display: inline-flex; align-items: center; justify-content: center; width: 2rem; height: 2rem; background: #fee2e2; color: #ef4444; border: none; border-radius: 0.375rem; cursor: pointer; transition: all 0.2s;"
                                                onmouseover="this.style.background='#fecaca'" 
                                                onmouseout="this.style.background='#fee2e2'"
                                                title="Hapus Surat">
                                            <i class="fas fa-trash" style="font-size: 0.875rem;"></i>
                                        </button>
                                    </form>
                                @endif

                                @if($currentUser && $currentUser->hasSidonganRole('sekretaris') && $doc->status === 'selesai')
                                    <form action="{{ route('sidongan.documents.archive', $doc) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin mengarsipkan surat ini?\n\nSurat yang diarsipkan akan dipindahkan ke menu Arsip Surat.');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                style="display: inline-flex; align-items: center; justify-content: center; width: 2rem; height: 2rem; background: #ede9fe; color: #7c3aed; border: none; border-radius: 0.375rem; cursor: pointer; transition: all 0.2s;"
                                                onmouseover="this.style.background='#ddd6fe'" 
                                                onmouseout="this.style.background='#ede9fe'"
                                                title="Arsipkan Surat">
                                            <i class="fas fa-archive" style="font-size: 0.875rem;"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination Modern --}}
        @if($documents->hasPages())
        <div style="padding: 1.25rem; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div style="font-size: 0.875rem; color: #64748b;">
                Menampilkan <strong>{{ $documents->firstItem() }}</strong> - <strong>{{ $documents->lastItem() }}</strong> dari <strong>{{ $documents->total() }}</strong> surat
            </div>
            
            <div style="display: flex; gap: 0.35rem; align-items: center;">
                @if($documents->onFirstPage())
                    <span style="display: inline-flex; align-items: center; justify-content: center; width: 2.25rem; height: 2.25rem; background: #f1f5f9; color: #94a3b8; border-radius: 0.375rem; font-size: 0.875rem; cursor: not-allowed;">
                        <i class="fas fa-chevron-left"></i>
                    </span>
                @else
                    <a href="{{ $documents->previousPageUrl() }}" 
                    style="display: inline-flex; align-items: center; justify-content: center; width: 2.25rem; height: 2.25rem; background: #3b82f6; color: white; border-radius: 0.375rem; font-size: 0.875rem; text-decoration: none; transition: all 0.2s;"
                    onmouseover="this.style.background='#2563eb'" onmouseout="this.style.background='#3b82f6'">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                @endif
                
                @php
                    $currentPage = $documents->currentPage();
                    $lastPage = $documents->lastPage();
                    $startPage = max(1, $currentPage - 2);
                    $endPage = min($lastPage, $currentPage + 2);
                    
                    if ($endPage - $startPage < 4) {
                        if ($startPage == 1) $endPage = min(5, $lastPage);
                        if ($endPage == $lastPage) $startPage = max(1, $lastPage - 4);
                    }
                @endphp
                
                @if($startPage > 1)
                    <a href="{{ $documents->url(1) }}" 
                    style="display: inline-flex; align-items: center; justify-content: center; width: 2.25rem; height: 2.25rem; background: white; color: #475569; border: 1px solid #e2e8f0; border-radius: 0.375rem; font-size: 0.875rem; text-decoration: none; transition: all 0.2s;"
                    onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='white'">
                        1
                    </a>
                    @if($startPage > 2)
                        <span style="display: inline-flex; align-items: center; justify-content: center; width: 2.25rem; height: 2.25rem; color: #94a3b8; font-size: 0.875rem;">...</span>
                    @endif
                @endif
                
                @for($i = $startPage; $i <= $endPage; $i++)
                    @if($i == $currentPage)
                        <span style="display: inline-flex; align-items: center; justify-content: center; width: 2.25rem; height: 2.25rem; background: #3b82f6; color: white; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 600;">
                            {{ $i }}
                        </span>
                    @else
                        <a href="{{ $documents->url($i) }}" 
                        style="display: inline-flex; align-items: center; justify-content: center; width: 2.25rem; height: 2.25rem; background: white; color: #475569; border: 1px solid #e2e8f0; border-radius: 0.375rem; font-size: 0.875rem; text-decoration: none; transition: all 0.2s;"
                        onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='white'">
                            {{ $i }}
                        </a>
                    @endif
                @endfor
                
                @if($endPage < $lastPage)
                    @if($endPage < $lastPage - 1)
                        <span style="display: inline-flex; align-items: center; justify-content: center; width: 2.25rem; height: 2.25rem; color: #94a3b8; font-size: 0.875rem;">...</span>
                    @endif
                    <a href="{{ $documents->url($lastPage) }}" 
                    style="display: inline-flex; align-items: center; justify-content: center; width: 2.25rem; height: 2.25rem; background: white; color: #475569; border: 1px solid #e2e8f0; border-radius: 0.375rem; font-size: 0.875rem; text-decoration: none; transition: all 0.2s;"
                    onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='white'">
                        {{ $lastPage }}
                    </a>
                @endif
                
                @if($documents->hasMorePages())
                    <a href="{{ $documents->nextPageUrl() }}" 
                    style="display: inline-flex; align-items: center; justify-content: center; width: 2.25rem; height: 2.25rem; background: #3b82f6; color: white; border-radius: 0.375rem; font-size: 0.875rem; text-decoration: none; transition: all 0.2s;"
                    onmouseover="this.style.background='#2563eb'" onmouseout="this.style.background='#3b82f6'">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                @else
                    <span style="display: inline-flex; align-items: center; justify-content: center; width: 2.25rem; height: 2.25rem; background: #f1f5f9; color: #94a3b8; border-radius: 0.375rem; font-size: 0.875rem; cursor: not-allowed;">
                        <i class="fas fa-chevron-right"></i>
                    </span>
                @endif
            </div>
        </div>
        @else
        <div style="padding: 1.25rem; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; background: #f8fafc;">
            <div style="font-size: 0.875rem; color: #64748b;">
                Menampilkan <strong>{{ $documents->firstItem() ?? 0 }}</strong> - <strong>{{ $documents->lastItem() ?? 0 }}</strong> dari <strong>{{ $documents->total() }}</strong> surat
            </div>
            <div style="font-size: 0.875rem; color: #94a3b8;">
                <i class="fas fa-info-circle"></i> Semua surat ditampilkan dalam satu halaman
            </div>
        </div>
        @endif
        @else
        <div style="padding: 4rem 2rem; text-align: center;">
            <div style="width: 96px; height: 96px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                <i class="fas fa-inbox" style="color: #94a3b8; font-size: 3rem;"></i>
            </div>
            <h3 style="font-size: 1.125rem; font-weight: 700; color: #1e293b; margin: 0 0 0.5rem 0;">Belum Ada Dokumen</h3>
            <p style="font-size: 0.875rem; color: #64748b; margin: 0 0 1.5rem 0; max-width: 400px; margin-left: auto; margin-right: auto;">Belum ada dokumen surat yang ditemukan.</p>
            @if($currentUser && $currentUser->hasSidonganRole('sekretaris'))
            <a href="{{ route('sidongan.documents.create') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #3b82f6, #2563eb); color: white; text-decoration: none; border-radius: 0.5rem; font-weight: 600;">
                <i class="fas fa-plus"></i>
                <span>Buat Surat Pertama</span>
            </a>
            @endif
        </div>
        @endif
    </div>
</div>

<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
    let searchTimeout;
    const searchInput = document.getElementById('searchInput');
    const filterForm = document.getElementById('filterForm');

    document.addEventListener('DOMContentLoaded', function() {
        if (searchInput) {
            setTimeout(() => {
                searchInput.focus();
                const len = searchInput.value.length;
                searchInput.setSelectionRange(len, len);
            }, 100);
        }
    });

    searchInput?.addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            filterForm.submit();
        }, 500);
    });

    function confirmDelete(id, title) {
        if (confirm(`Apakah Anda yakin ingin menghapus surat "${title}"?\n\nPeringatan: Tindakan ini tidak dapat dibatalkan.`)) {
            document.getElementById('deleteForm').action = `/sidongan/documents/${id}`;
            document.getElementById('deleteForm').submit();
        }
    }
</script>
@endsection