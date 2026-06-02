@extends('admin.layouts.app')
@section('title', 'Manajemen Aplikasi')
@section('page-title', 'Aplikasi & Sistem')

@section('content')

{{-- Header Section --}}
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
    <div>
        <h1 style="font-size:1.5rem;font-weight:800;color:var(--text-dark);margin:0 0 0.25rem 0;letter-spacing:-0.5px">Aplikasi & Sistem</h1>
        <p style="color:var(--text-muted);margin:0;font-size:0.9rem">Kelola aplikasi dan sistem informasi PKK Kabupaten Toba</p>
    </div>
    <a href="{{ route('admin.aplikasi.create') }}" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:0.5rem">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Aplikasi
    </a>
</div>

{{-- Success Message --}}
@if(session('success'))
<div style="background:#f0fdf4;padding:1rem;margin-bottom:1.5rem;border-radius:10px;color:#166534;display:flex;align-items:center;gap:0.75rem">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
    <span>{{ session('success') }}</span>
</div>
@endif

{{-- Stats Cards - Redesigned --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:1rem;margin-bottom:2rem">
    {{-- Total Aplikasi --}}
    <div class="stat-card" style="background:linear-gradient(135deg,#3182ce,#2b6cb0);color:#fff">
        <div style="display:flex;align-items:flex-start;gap:1rem">
            <div style="width:48px;height:48px;background:rgba(255,255,255,0.2);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
            </div>
            <div style="flex:1">
                <p style="font-size:0.85rem;opacity:0.9;margin:0 0 0.25rem 0">Total Aplikasi</p>
                <p style="font-size:1.85rem;font-weight:800;margin:0;line-height:1.1">{{ $applications->count() }}</p>
            </div>
        </div>
    </div>

    {{-- Aplikasi Aktif --}}
    <div class="stat-card" style="background:linear-gradient(135deg,#38a169,#2f855a);color:#fff">
        <div style="display:flex;align-items:flex-start;gap:1rem">
            <div style="width:48px;height:48px;background:rgba(255,255,255,0.2);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <div style="flex:1">
                <p style="font-size:0.85rem;opacity:0.9;margin:0 0 0.25rem 0">Aplikasi Aktif</p>
                <p style="font-size:1.85rem;font-weight:800;margin:0;line-height:1.1">{{ $applications->where('status','active')->where('is_active',true)->count() }}</p>
            </div>
        </div>
    </div>

    {{-- Maintenance --}}
    <div class="stat-card" style="background:linear-gradient(135deg,#dd6b20,#c05621);color:#fff">
        <div style="display:flex;align-items:flex-start;gap:1rem">
            <div style="width:48px;height:48px;background:rgba(255,255,255,0.2);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
            </div>
            <div style="flex:1">
                <p style="font-size:0.85rem;opacity:0.9;margin:0 0 0.25rem 0">Maintenance</p>
                <p style="font-size:1.85rem;font-weight:800;margin:0;line-height:1.1">{{ $applications->where('status','maintenance')->count() }}</p>
            </div>
        </div>
    </div>

    {{-- Dalam Pengembangan --}}
    <div class="stat-card" style="background:linear-gradient(135deg,#805ad5,#6b46c1);color:#fff">
        <div style="display:flex;align-items:flex-start;gap:1rem">
            <div style="width:48px;height:48px;background:rgba(255,255,255,0.2);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 22h20"/><path d="M12 2v20"/><path d="M12 22V2"/><path d="M2 12h20"/></svg>
            </div>
            <div style="flex:1">
                <p style="font-size:0.85rem;opacity:0.9;margin:0 0 0.25rem 0">Dalam Pengembangan</p>
                <p style="font-size:1.85rem;font-weight:800;margin:0;line-height:1.1">{{ $applications->where('status','development')->count() }}</p>
            </div>
        </div>
    </div>
</div>

{{-- Modern Tabs --}}
<div style="display:flex;gap:0.25rem;margin-bottom:1.5rem;border-bottom:1px solid rgba(0,0,0,0.06);padding-bottom:0.5rem;overflow-x:auto">
    <button class="tab-btn active" onclick="switchTab('all', this)" style="padding:0.6rem 1rem;border-radius:8px 8px 0 0;background:transparent;border:none;font-weight:600;color:var(--text-muted);cursor:pointer;transition:all 0.2s;border-bottom:2px solid var(--primary)">
        Semua Aplikasi
    </button>
    <button class="tab-btn" onclick="switchTab('active', this)" style="padding:0.6rem 1rem;border-radius:8px 8px 0 0;background:transparent;border:none;font-weight:600;color:var(--text-muted);cursor:pointer;transition:all 0.2s;border-bottom:2px solid transparent">
        Aktif <span style="background:rgba(56,161,105,0.15);color:#2f855a;padding:2px 8px;border-radius:12px;font-size:0.75rem;margin-left:4px">{{ $applications->where('status','active')->where('is_active',true)->count() }}</span>
    </button>
    <button class="tab-btn" onclick="switchTab('maintenance', this)" style="padding:0.6rem 1rem;border-radius:8px 8px 0 0;background:transparent;border:none;font-weight:600;color:var(--text-muted);cursor:pointer;transition:all 0.2s;border-bottom:2px solid transparent">
        Maintenance <span style="background:rgba(221,107,32,0.15);color:#c05621;padding:2px 8px;border-radius:12px;font-size:0.75rem;margin-left:4px">{{ $applications->where('status','maintenance')->count() }}</span>
    </button>
    <button class="tab-btn" onclick="switchTab('development', this)" style="padding:0.6rem 1rem;border-radius:8px 8px 0 0;background:transparent;border:none;font-weight:600;color:var(--text-muted);cursor:pointer;transition:all 0.2s;border-bottom:2px solid transparent">
        Pengembangan <span style="background:rgba(128,90,213,0.15);color:#6b46c1;padding:2px 8px;border-radius:12px;font-size:0.75rem;margin-left:4px">{{ $applications->where('status','development')->count() }}</span>
    </button>
</div>

{{-- Main Card --}}
<div class="card" style="padding:0;overflow:hidden">
    
    {{-- All Applications --}}
    <div id="tab-all" class="tab-content active">
        <div class="table-container" style="padding:1rem">
            @if($applications->count() > 0)
            <table style="width:100%;border-collapse:collapse">
                <thead>
                    <tr style="text-align:left;border-bottom:1px solid rgba(0,0,0,0.06)">
                        <th style="padding:1rem;color:var(--text-muted);font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px">Logo</th>
                        <th style="padding:1rem;color:var(--text-muted);font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px">Nama Aplikasi</th>
                        <th style="padding:1rem;color:var(--text-muted);font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px">Kategori</th>
                        <th style="padding:1rem;color:var(--text-muted);font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px">Status</th>
                        <th style="padding:1rem;color:var(--text-muted);font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px">URL</th>
                        <th style="padding:1rem;color:var(--text-muted);font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px">Urutan</th>
                        <th style="padding:1rem;color:var(--text-muted);font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px;text-align:right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applications as $app)
                    <tr style="border-bottom:1px solid rgba(0,0,0,0.04)">
                        <td style="padding:1rem">
                            @if($app->icon)
                            <img src="{{ asset('storage/'.$app->icon) }}" style="width:40px;height:40px;border-radius:8px;object-fit:cover;background:#f8fafc">
                            @else
                            <div style="width:40px;height:40px;border-radius:8px;background:linear-gradient(135deg,var(--primary),var(--primary-dark));display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:0.8rem">
                                {{ strtoupper(substr($app->short_name, 0, 2)) }}
                            </div>
                            @endif
                        </td>
                        <td style="padding:1rem">
                            <div style="font-weight:600;color:var(--text-dark)">{{ $app->name }}</div>
                            <small style="color:var(--text-muted);font-size:0.85rem">{{ $app->short_name }}</small>
                        </td>
                        <td style="padding:1rem">
                            <span style="background:{{ $app->category == 'aplikasi' ? 'rgba(20,184,166,0.1)' : 'rgba(59,130,246,0.1)' }};color:{{ $app->category == 'aplikasi' ? 'var(--primary)' : '#2563eb' }};padding:4px 10px;border-radius:20px;font-size:0.75rem;font-weight:600">
                                {{ ucfirst($app->category) }}
                            </span>
                        </td>
                        <td style="padding:1rem">
                            {{-- Status Badge - Tanpa PHP Array Kompleks --}}
                            @if($app->status == 'active')
                                <span style="display:inline-flex;align-items:center;gap:6px;padding:4px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;background:rgba(34,197,94,0.1);color:#166534">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                    Aktif
                                </span>
                            @elseif($app->status == 'maintenance')
                                <span style="display:inline-flex;align-items:center;gap:6px;padding:4px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;background:rgba(234,179,8,0.1);color:#92400e">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                                    Maintenance
                                </span>
                            @elseif($app->status == 'development')
                                <span style="display:inline-flex;align-items:center;gap:6px;padding:4px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;background:rgba(139,92,246,0.1);color:#6b21a8">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 22h20"/><path d="M12 2v20"/><path d="M12 22V2"/><path d="M2 12h20"/></svg>
                                    Pengembangan
                                </span>
                            @else
                                <span style="display:inline-flex;align-items:center;gap:6px;padding:4px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;background:rgba(148,163,184,0.1);color:#64748b">
                                    Tidak Diketahui
                                </span>
                            @endif
                        </td>
                        <td style="padding:1rem">
                            @if($app->url && $app->url !== '#')
                            <a href="{{ $app->url }}" target="_blank" style="color:var(--primary);text-decoration:none;font-size:0.85rem;border-bottom:1px dotted var(--primary)">
                                {{ Str::limit($app->url, 25) }}
                            </a>
                            @else
                            <span style="color:var(--text-muted);font-size:0.85rem">-</span>
                            @endif
                        </td>
                        <td style="padding:1rem;color:var(--text-muted);font-size:0.9rem">{{ $app->sort_order }}</td>
                        <td style="padding:1rem;text-align:right">
                            <div class="actions" style="justify-content:flex-end">
                                <a href="{{ route('admin.aplikasi.edit', $app) }}" class="btn-edit" title="Edit">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </a>
                                <form action="{{ route('admin.aplikasi.destroy', $app) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus aplikasi ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-del" title="Hapus">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div style="text-align:center;padding:3rem 1rem;color:var(--text-muted)">
                <div style="width:64px;height:64px;background:#f8fafc;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                </div>
                <h3 style="font-size:1rem;font-weight:700;color:var(--text-dark);margin:0 0 0.5rem">Belum Ada Aplikasi</h3>
                <p style="font-size:0.9rem;margin:0">Silakan tambah aplikasi pertama Anda.</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Active Apps --}}
    <div id="tab-active" class="tab-content" style="display:none">
        <div class="table-container" style="padding:1rem">
            @php $activeApps = $applications->where('status','active')->where('is_active',true); @endphp
            @if($activeApps->count() > 0)
            <table style="width:100%;border-collapse:collapse">
                <thead>
                    <tr style="text-align:left;border-bottom:1px solid rgba(0,0,0,0.06)">
                        <th style="padding:1rem;color:var(--text-muted);font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px">Logo</th>
                        <th style="padding:1rem;color:var(--text-muted);font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px">Nama</th>
                        <th style="padding:1rem;color:var(--text-muted);font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px">URL</th>
                        <th style="padding:1rem;color:var(--text-muted);font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px;text-align:right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activeApps as $app)
                    <tr style="border-bottom:1px solid rgba(0,0,0,0.04)">
                        <td style="padding:1rem">
                            @if($app->icon)
                            <img src="{{ asset('storage/'.$app->icon) }}" style="width:40px;height:40px;border-radius:8px;object-fit:cover;background:#f8fafc">
                            @else
                            <div style="width:40px;height:40px;border-radius:8px;background:linear-gradient(135deg,#38a169,#2f855a);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700">
                                {{ strtoupper(substr($app->short_name, 0, 2)) }}
                            </div>
                            @endif
                        </td>
                        <td style="padding:1rem">
                            <div style="font-weight:600;color:var(--text-dark)">{{ $app->name }}</div>
                            <small style="color:var(--text-muted);font-size:0.85rem">{{ $app->short_name }}</small>
                        </td>
                        <td style="padding:1rem">
                            @if($app->url)
                            <a href="{{ $app->url }}" target="_blank" style="color:var(--primary);text-decoration:none;font-size:0.85rem;border-bottom:1px dotted var(--primary)">{{ Str::limit($app->url, 30) }}</a>
                            @else
                            <span style="color:var(--text-muted);font-size:0.85rem">-</span>
                            @endif
                        </td>
                        <td style="padding:1rem;text-align:right">
                            <div class="actions" style="justify-content:flex-end">
                                <a href="{{ route('admin.aplikasi.edit', $app) }}" class="btn-edit">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </a>
                                <form action="{{ route('admin.aplikasi.destroy', $app) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-del">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div style="text-align:center;padding:3rem 1rem;color:var(--text-muted)">
                <p style="margin:0;font-size:0.95rem">Belum ada aplikasi aktif</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Maintenance Apps --}}
    <div id="tab-maintenance" class="tab-content" style="display:none">
        <div class="table-container" style="padding:1rem">
            @php $maintenanceApps = $applications->where('status','maintenance'); @endphp
            @if($maintenanceApps->count() > 0)
            <table style="width:100%;border-collapse:collapse">
                <thead>
                    <tr style="text-align:left;border-bottom:1px solid rgba(0,0,0,0.06)">
                        <th style="padding:1rem;color:var(--text-muted);font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px">Logo</th>
                        <th style="padding:1rem;color:var(--text-muted);font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px">Nama</th>
                        <th style="padding:1rem;color:var(--text-muted);font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px">Status</th>
                        <th style="padding:1rem;color:var(--text-muted);font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px;text-align:right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($maintenanceApps as $app)
                    <tr style="border-bottom:1px solid rgba(0,0,0,0.04)">
                        <td style="padding:1rem">
                            @if($app->icon)
                            <img src="{{ asset('storage/'.$app->icon) }}" style="width:40px;height:40px;border-radius:8px;object-fit:cover;background:#f8fafc">
                            @else
                            <div style="width:40px;height:40px;border-radius:8px;background:linear-gradient(135deg,#dd6b20,#c05621);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700">
                                {{ strtoupper(substr($app->short_name, 0, 2)) }}
                            </div>
                            @endif
                        </td>
                        <td style="padding:1rem">
                            <div style="font-weight:600;color:var(--text-dark)">{{ $app->name }}</div>
                            <small style="color:var(--text-muted);font-size:0.85rem">{{ $app->short_name }}</small>
                        </td>
                        <td style="padding:1rem">
                            <span style="display:inline-flex;align-items:center;gap:6px;padding:4px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;background:rgba(234,179,8,0.1);color:#92400e">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                                Dalam Maintenance
                            </span>
                        </td>
                        <td style="padding:1rem;text-align:right">
                            <div class="actions" style="justify-content:flex-end">
                                <a href="{{ route('admin.aplikasi.edit', $app) }}" class="btn-edit">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </a>
                                <form action="{{ route('admin.aplikasi.destroy', $app) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-del">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div style="text-align:center;padding:3rem 1rem;color:var(--text-muted)">
                <p style="margin:0;font-size:0.95rem">Tidak ada aplikasi dalam maintenance</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Development Apps --}}
    <div id="tab-development" class="tab-content" style="display:none">
        <div class="table-container" style="padding:1rem">
            @php $devApps = $applications->where('status','development'); @endphp
            @if($devApps->count() > 0)
            <table style="width:100%;border-collapse:collapse">
                <thead>
                    <tr style="text-align:left;border-bottom:1px solid rgba(0,0,0,0.06)">
                        <th style="padding:1rem;color:var(--text-muted);font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px">Logo</th>
                        <th style="padding:1rem;color:var(--text-muted);font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px">Nama</th>
                        <th style="padding:1rem;color:var(--text-muted);font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px">Status</th>
                        <th style="padding:1rem;color:var(--text-muted);font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px;text-align:right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($devApps as $app)
                    <tr style="border-bottom:1px solid rgba(0,0,0,0.04)">
                        <td style="padding:1rem">
                            @if($app->icon)
                            <img src="{{ asset('storage/'.$app->icon) }}" style="width:40px;height:40px;border-radius:8px;object-fit:cover;background:#f8fafc">
                            @else
                            <div style="width:40px;height:40px;border-radius:8px;background:linear-gradient(135deg,#805ad5,#6b46c1);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700">
                                {{ strtoupper(substr($app->short_name, 0, 2)) }}
                            </div>
                            @endif
                        </td>
                        <td style="padding:1rem">
                            <div style="font-weight:600;color:var(--text-dark)">{{ $app->name }}</div>
                            <small style="color:var(--text-muted);font-size:0.85rem">{{ $app->short_name }}</small>
                        </td>
                        <td style="padding:1rem">
                            <span style="display:inline-flex;align-items:center;gap:6px;padding:4px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;background:rgba(139,92,246,0.1);color:#6b21a8">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 22h20"/><path d="M12 2v20"/><path d="M12 22V2"/><path d="M2 12h20"/></svg>
                                Dalam Pengembangan
                            </span>
                        </td>
                        <td style="padding:1rem;text-align:right">
                            <div class="actions" style="justify-content:flex-end">
                                <a href="{{ route('admin.aplikasi.edit', $app) }}" class="btn-edit">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </a>
                                <form action="{{ route('admin.aplikasi.destroy', $app) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-del">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div style="text-align:center;padding:3rem 1rem;color:var(--text-muted)">
                <p style="margin:0;font-size:0.95rem">Belum ada aplikasi dalam pengembangan</p>
            </div>
            @endif
        </div>
    </div>

</div>

<script>
function switchTab(tabId, btn) {
    // Reset all tabs
    document.querySelectorAll('.tab-btn').forEach(b => {
        b.style.color = 'var(--text-muted)';
        b.style.borderBottom = '2px solid transparent';
    });
    // Hide all contents
    document.querySelectorAll('.tab-content').forEach(c => c.style.display = 'none');
    
    // Activate selected
    btn.style.color = 'var(--primary)';
    btn.style.borderBottom = '2px solid var(--primary)';
    document.getElementById('tab-' + tabId).style.display = 'block';
}

// Init first tab
document.addEventListener('DOMContentLoaded', () => {
    const firstBtn = document.querySelector('.tab-btn');
    if(firstBtn) switchTab('all', firstBtn);
});
</script>

@endsection