@extends('admin.layouts.app')
@section('title', 'Manajemen Akun')
@section('page-title', 'Manajemen Akun')

@section('content')

{{-- Header Section --}}
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
    <div>
        <h1 style="font-size:1.5rem;font-weight:800;color:var(--text-dark);margin:0 0 0.25rem 0;letter-spacing:-0.5px">Manajemen Akun</h1>
        <p style="color:var(--text-muted);margin:0;font-size:0.9rem">Kelola akun pengguna dan hak akses aplikasi sistem PKK</p>
    </div>
    <a href="{{ route('admin.user-management.create') }}" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:0.5rem">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Akun
    </a>
</div>

{{-- Success Message --}}
@if(session('success'))
<div style="background:#f0fdf4;padding:1rem;margin-bottom:1.5rem;border-radius:10px;color:#166534;display:flex;align-items:center;gap:0.75rem">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
    <span>{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div style="background:#fef2f2;padding:1rem;margin-bottom:1.5rem;border-radius:10px;color:#dc2626;display:flex;align-items:center;gap:0.75rem">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
    <span>{{ session('error') }}</span>
</div>
@endif

{{-- Stats Cards --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:1rem;margin-bottom:2rem">
    {{-- Total Pengguna --}}
    <div class="stat-card" style="background:linear-gradient(135deg,#3182ce,#2b6cb0);color:#fff">
        <div style="display:flex;align-items:flex-start;gap:1rem">
            <div style="width:48px;height:48px;background:rgba(255,255,255,0.2);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div style="flex:1">
                <p style="font-size:0.85rem;opacity:0.9;margin:0 0 0.25rem 0">Total Pengguna</p>
                <p style="font-size:1.85rem;font-weight:800;margin:0;line-height:1.1">{{ $users->total() }}</p>
            </div>
        </div>
    </div>

    {{-- Pengguna Aktif --}}
    <div class="stat-card" style="background:linear-gradient(135deg,#38a169,#2f855a);color:#fff">
        <div style="display:flex;align-items:flex-start;gap:1rem">
            <div style="width:48px;height:48px;background:rgba(255,255,255,0.2);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <div style="flex:1">
                <p style="font-size:0.85rem;opacity:0.9;margin:0 0 0.25rem 0">Pengguna Aktif</p>
                <p style="font-size:1.85rem;font-weight:800;margin:0;line-height:1.1">{{ $users->filter(fn($u) => $u->email_verified_at)->count() }}</p>
            </div>
        </div>
    </div>

    {{-- Pengguna Nonaktif --}}
    <div class="stat-card" style="background:linear-gradient(135deg,#e53e3e,#c53030);color:#fff">
        <div style="display:flex;align-items:flex-start;gap:1rem">
            <div style="width:48px;height:48px;background:rgba(255,255,255,0.2);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            </div>
            <div style="flex:1">
                <p style="font-size:0.85rem;opacity:0.9;margin:0 0 0.25rem 0">Pengguna Nonaktif</p>
                <p style="font-size:1.85rem;font-weight:800;margin:0;line-height:1.1">{{ $users->filter(fn($u) => !$u->email_verified_at)->count() }}</p>
            </div>
        </div>
    </div>

    {{-- Dengan Akses Aplikasi --}}
    <div class="stat-card" style="background:linear-gradient(135deg,#805ad5,#6b46c1);color:#fff">
        <div style="display:flex;align-items:flex-start;gap:1rem">
            <div style="width:48px;height:48px;background:rgba(255,255,255,0.2);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
            </div>
            <div style="flex:1">
                <p style="font-size:0.85rem;opacity:0.9;margin:0 0 0.25rem 0">Punya Akses Aplikasi</p>
                <p style="font-size:1.85rem;font-weight:800;margin:0;line-height:1.1">{{ $users->filter(fn($u) => $u->applications->count() > 0)->count() }}</p>
            </div>
        </div>
    </div>
</div>

{{-- Modern Tabs --}}
<div style="display:flex;gap:0.25rem;margin-bottom:1.5rem;border-bottom:1px solid rgba(0,0,0,0.06);padding-bottom:0.5rem;overflow-x:auto">
    <button class="tab-btn active" onclick="switchTab('all', this)" style="padding:0.6rem 1rem;border-radius:8px 8px 0 0;background:transparent;border:none;font-weight:600;color:var(--text-muted);cursor:pointer;transition:all 0.2s;border-bottom:2px solid var(--primary)">
        Semua Pengguna
    </button>
    <button class="tab-btn" onclick="switchTab('active', this)" style="padding:0.6rem 1rem;border-radius:8px 8px 0 0;background:transparent;border:none;font-weight:600;color:var(--text-muted);cursor:pointer;transition:all 0.2s;border-bottom:2px solid transparent">
        Aktif <span style="background:rgba(56,161,105,0.15);color:#2f855a;padding:2px 8px;border-radius:12px;font-size:0.75rem;margin-left:4px">{{ $users->filter(fn($u) => $u->email_verified_at)->count() }}</span>
    </button>
    <button class="tab-btn" onclick="switchTab('inactive', this)" style="padding:0.6rem 1rem;border-radius:8px 8px 0 0;background:transparent;border:none;font-weight:600;color:var(--text-muted);cursor:pointer;transition:all 0.2s;border-bottom:2px solid transparent">
        Nonaktif <span style="background:rgba(229,62,62,0.15);color:#c53030;padding:2px 8px;border-radius:12px;font-size:0.75rem;margin-left:4px">{{ $users->filter(fn($u) => !$u->email_verified_at)->count() }}</span>
    </button>
    <button class="tab-btn" onclick="switchTab('with-access', this)" style="padding:0.6rem 1rem;border-radius:8px 8px 0 0;background:transparent;border:none;font-weight:600;color:var(--text-muted);cursor:pointer;transition:all 0.2s;border-bottom:2px solid transparent">
        Punya Akses <span style="background:rgba(128,90,213,0.15);color:#6b46c1;padding:2px 8px;border-radius:12px;font-size:0.75rem;margin-left:4px">{{ $users->filter(fn($u) => $u->applications->count() > 0)->count() }}</span>
    </button>
</div>

{{-- Main Card --}}
<div class="card" style="padding:0;overflow:hidden">
    
    {{-- All Users --}}
    <div id="tab-all" class="tab-content active">
        <div class="table-container" style="padding:1rem">
            @if($users->count() > 0)
            <table style="width:100%;border-collapse:collapse">
                <thead>
                    <tr style="text-align:left;border-bottom:1px solid rgba(0,0,0,0.06)">
                        <th style="padding:1rem;color:var(--text-muted);font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px">Pengguna</th>
                        <th style="padding:1rem;color:var(--text-muted);font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px">Email</th>
                        <th style="padding:1rem;color:var(--text-muted);font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px">Aplikasi</th>
                        <th style="padding:1rem;color:var(--text-muted);font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px">Status</th>
                        <th style="padding:1rem;color:var(--text-muted);font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px">Dibuat</th>
                        <th style="padding:1rem;color:var(--text-muted);font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px;text-align:right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr style="border-bottom:1px solid rgba(0,0,0,0.04)">
                        <td style="padding:1rem">
                            <div style="display:flex;align-items:center;gap:0.75rem">
                                <div style="width:40px;height:40px;border-radius:50%;overflow:hidden;background:linear-gradient(135deg,var(--primary),#0d9488);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;flex-shrink:0">
                                    @if($user->avatar)
                                        <img src="{{ asset('storage/' . $user->avatar) }}" style="width:100%;height:100%;object-fit:cover">
                                    @else
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    @endif
                                </div>
                                <div>
                                    <div style="font-weight:600;color:var(--text-dark)">{{ $user->name }}</div>
                                    <div style="font-size:0.85rem;color:var(--text-muted)">{{ Str::limit($user->email, 20) }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding:1rem;color:var(--text-muted);font-size:0.9rem">{{ $user->email }}</td>
                        <td style="padding:1rem">
                            @if($user->applications->count() > 0)
                                <span style="background:rgba(128,90,213,0.1);color:#6b46c1;padding:4px 10px;border-radius:20px;font-size:0.75rem;font-weight:600">
                                    {{ $user->applications->count() }} aplikasi
                                </span>
                            @else
                                <span style="color:var(--text-muted);font-size:0.85rem">-</span>
                            @endif
                        </td>
                        <td style="padding:1rem">
                            @if($user->email_verified_at)
                                <span style="display:inline-flex;align-items:center;gap:6px;padding:4px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;background:rgba(34,197,94,0.1);color:#166534">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="20 6 9 17 4 12"/>
                                    </svg>
                                    Aktif
                                </span>
                            @else
                                <span style="display:inline-flex;align-items:center;gap:6px;padding:4px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;background:rgba(239,68,68,0.1);color:#dc2626">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"/>
                                        <line x1="12" y1="8" x2="12" y2="12"/>
                                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                                    </svg>
                                    Nonaktif
                                </span>
                            @endif
                        </td>
                        <td style="padding:1rem;color:var(--text-muted);font-size:0.9rem">{{ $user->created_at->format('d M Y') }}</td>
                        <td style="padding:1rem;text-align:right">
                            <div class="actions" style="justify-content:flex-end;gap:0.5rem">
                                {{-- Tombol View --}}
                                <a href="{{ route('admin.user-management.show', $user) }}" class="btn-view" title="Lihat" style="width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;background:#f8fafc;color:#64748b;border-radius:6px;transition:all 0.2s">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </a>
                                
                                {{-- Tombol Edit & Delete HANYA untuk Super Admin --}}
                                @if(auth()->user()->sidongan_role === 'super_admin')
                                    
                                    {{-- TOMBOL TOGGLE STATUS --}}
                                    <button type="button" onclick="toggleStatus({{ $user->id }})" 
                                            title="{{ $user->email_verified_at ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}" 
                                            style="width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;background:{{ $user->email_verified_at ? '#fef3c7' : '#f0fdf4' }};color:{{ $user->email_verified_at ? '#d97706' : '#16a34a' }};border-radius:6px;border:none;cursor:pointer;transition:all 0.2s"
                                            onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 4px 12px rgba(0,0,0,0.1)'"
                                            onmouseout="this.style.transform='';this.style.boxShadow='none'">
                                        @if($user->email_verified_at)
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
                                        @else
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                                        @endif
                                    </button>
                                    
                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('admin.user-management.edit', $user) }}" class="btn-edit" title="Edit" style="width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;background:#eff6ff;color:#2563eb;border-radius:6px;transition:all 0.2s">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    </a>
                                    
                                    {{-- Tombol Delete --}}
                                    @if($user->id !== auth()->id() && $user->sidongan_role !== 'super_admin')
                                    <form action="{{ route('admin.user-management.destroy', $user) }}" method="POST" style="display:inline" onsubmit="return confirm('Yakin ingin menghapus akun {{ $user->name }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-del" title="Hapus" style="width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;background:#fef2f2;color:#ef4444;border-radius:6px;transition:all 0.2s;border:none;cursor:pointer">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        </button>
                                    </form>
                                    @endif
                                    
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div style="text-align:center;padding:3rem 1rem;color:var(--text-muted)">
                <div style="width:64px;height:64px;background:#f8fafc;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <h3 style="font-size:1rem;font-weight:700;color:var(--text-dark);margin:0 0 0.5rem">Belum Ada Pengguna</h3>
                <p style="font-size:0.9rem;margin:0">Silakan tambah akun pengguna pertama Anda.</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Active Users --}}
    <div id="tab-active" class="tab-content" style="display:none">
        <div class="table-container" style="padding:1rem">
            @php $activeUsers = $users->filter(fn($u) => $u->email_verified_at); @endphp
            @if($activeUsers->count() > 0)
            <table style="width:100%;border-collapse:collapse">
                <thead>
                    <tr style="text-align:left;border-bottom:1px solid rgba(0,0,0,0.06)">
                        <th style="padding:1rem;color:var(--text-muted);font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px">Pengguna</th>
                        <th style="padding:1rem;color:var(--text-muted);font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px">Email</th>
                        <th style="padding:1rem;color:var(--text-muted);font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px">Aplikasi</th>
                        <th style="padding:1rem;color:var(--text-muted);font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px;text-align:right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activeUsers as $user)
                    <tr style="border-bottom:1px solid rgba(0,0,0,0.04)">
                        <td style="padding:1rem">
                            <div style="display:flex;align-items:center;gap:0.75rem">
                                <div style="width:40px;height:40px;border-radius:50%;overflow:hidden;background:linear-gradient(135deg,#38a169,#2f855a);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;flex-shrink:0">
                                    @if($user->avatar)
                                        <img src="{{ asset('storage/' . $user->avatar) }}" style="width:100%;height:100%;object-fit:cover">
                                    @else
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    @endif
                                </div>
                                <div>
                                    <div style="font-weight:600;color:var(--text-dark)">{{ $user->name }}</div>
                                    <div style="font-size:0.85rem;color:var(--text-muted)">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding:1rem;color:var(--text-muted)">{{ $user->email }}</td>
                        <td style="padding:1rem">
                            @if($user->applications->count() > 0)
                                <span style="background:rgba(128,90,213,0.1);color:#6b46c1;padding:4px 10px;border-radius:20px;font-size:0.75rem;font-weight:600">{{ $user->applications->count() }} aplikasi</span>
                            @else
                                <span style="color:var(--text-muted)">-</span>
                            @endif
                        </td>
                        <td style="padding:1rem;text-align:right">
                            <div class="actions" style="justify-content:flex-end;gap:0.5rem">
                                @if(auth()->user()->sidongan_role === 'super_admin')
                                <button type="button" onclick="toggleStatus({{ $user->id }})" title="Nonaktifkan Akun" 
                                        style="width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;background:#fef3c7;color:#d97706;border-radius:6px;border:none;cursor:pointer;transition:all 0.2s">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
                                </button>
                                @endif
                                <a href="{{ route('admin.user-management.edit', $user) }}" class="btn-edit" title="Edit" style="width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;background:#eff6ff;color:#2563eb;border-radius:6px;transition:all 0.2s">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </a>
                                @if($user->id !== auth()->id() && $user->sidongan_role !== 'super_admin')
                                <form action="{{ route('admin.user-management.destroy', $user) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus akun {{ $user->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-del" title="Hapus" style="width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;background:#fef2f2;color:#ef4444;border-radius:6px;border:none;cursor:pointer;transition:all 0.2s">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div style="text-align:center;padding:3rem 1rem;color:var(--text-muted)">
                <p style="margin:0;font-size:0.95rem">Tidak ada pengguna aktif</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Inactive Users --}}
    <div id="tab-inactive" class="tab-content" style="display:none">
        <div class="table-container" style="padding:1rem">
            @php $inactiveUsers = $users->filter(fn($u) => !$u->email_verified_at); @endphp
            @if($inactiveUsers->count() > 0)
            <table style="width:100%;border-collapse:collapse">
                <thead>
                    <tr style="text-align:left;border-bottom:1px solid rgba(0,0,0,0.06)">
                        <th style="padding:1rem;color:var(--text-muted);font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px">Pengguna</th>
                        <th style="padding:1rem;color:var(--text-muted);font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px">Email</th>
                        <th style="padding:1rem;color:var(--text-muted);font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px">Status</th>
                        <th style="padding:1rem;color:var(--text-muted);font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px;text-align:right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inactiveUsers as $user)
                    <tr style="border-bottom:1px solid rgba(0,0,0,0.04)">
                        <td style="padding:1rem">
                            <div style="display:flex;align-items:center;gap:0.75rem">
                                <div style="width:40px;height:40px;border-radius:50%;overflow:hidden;background:linear-gradient(135deg,#e53e3e,#c53030);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;flex-shrink:0">
                                    @if($user->avatar)
                                        <img src="{{ asset('storage/' . $user->avatar) }}" style="width:100%;height:100%;object-fit:cover">
                                    @else
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    @endif
                                </div>
                                <div>
                                    <div style="font-weight:600;color:var(--text-dark)">{{ $user->name }}</div>
                                    <div style="font-size:0.85rem;color:var(--text-muted)">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding:1rem;color:var(--text-muted)">{{ $user->email }}</td>
                        <td style="padding:1rem">
                            <span style="display:inline-flex;align-items:center;gap:6px;padding:4px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;background:rgba(229,62,62,0.1);color:#c53030">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                Nonaktif
                            </span>
                        </td>
                        <td style="padding:1rem;text-align:right">
                            <div class="actions" style="justify-content:flex-end;gap:0.5rem">
                                @if(auth()->user()->sidongan_role === 'super_admin')
                                <button type="button" onclick="toggleStatus({{ $user->id }})" title="Aktifkan Akun" 
                                        style="width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;background:#f0fdf4;color:#16a34a;border-radius:6px;border:none;cursor:pointer;transition:all 0.2s">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                                </button>
                                @endif
                                <a href="{{ route('admin.user-management.edit', $user) }}" class="btn-edit" title="Edit" style="width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;background:#eff6ff;color:#2563eb;border-radius:6px;transition:all 0.2s">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </a>
                                @if($user->id !== auth()->id() && $user->sidongan_role !== 'super_admin')
                                <form action="{{ route('admin.user-management.destroy', $user) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus akun {{ $user->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-del" title="Hapus" style="width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;background:#fef2f2;color:#ef4444;border-radius:6px;border:none;cursor:pointer;transition:all 0.2s">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div style="text-align:center;padding:3rem 1rem;color:var(--text-muted)">
                <p style="margin:0;font-size:0.95rem">Tidak ada pengguna nonaktif</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Users with Access --}}
    <div id="tab-with-access" class="tab-content" style="display:none">
        <div class="table-container" style="padding:1rem">
            @php $usersWithAccess = $users->filter(fn($u) => $u->applications->count() > 0); @endphp
            @if($usersWithAccess->count() > 0)
            <table style="width:100%;border-collapse:collapse">
                <thead>
                    <tr style="text-align:left;border-bottom:1px solid rgba(0,0,0,0.06)">
                        <th style="padding:1rem;color:var(--text-muted);font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px">Pengguna</th>
                        <th style="padding:1rem;color:var(--text-muted);font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px">Email</th>
                        <th style="padding:1rem;color:var(--text-muted);font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px">Aplikasi Diakses</th>
                        <th style="padding:1rem;color:var(--text-muted);font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px;text-align:right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usersWithAccess as $user)
                    <tr style="border-bottom:1px solid rgba(0,0,0,0.04)">
                        <td style="padding:1rem">
                            <div style="display:flex;align-items:center;gap:0.75rem">
                                <div style="width:40px;height:40px;border-radius:50%;overflow:hidden;background:linear-gradient(135deg,#805ad5,#6b46c1);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;flex-shrink:0">
                                    @if($user->avatar)
                                        <img src="{{ asset('storage/' . $user->avatar) }}" style="width:100%;height:100%;object-fit:cover">
                                    @else
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    @endif
                                </div>
                                <div>
                                    <div style="font-weight:600;color:var(--text-dark)">{{ $user->name }}</div>
                                    <div style="font-size:0.85rem;color:var(--text-muted)">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding:1rem;color:var(--text-muted)">{{ $user->email }}</td>
                        <td style="padding:1rem">
                            <div style="display:flex;flex-wrap:wrap;gap:0.25rem">
                                @foreach($user->applications->take(3) as $app)
                                <span style="background:rgba(59,130,246,0.1);color:#2563eb;padding:3px 8px;border-radius:20px;font-size:0.7rem;font-weight:600">
                                    {{ $app->short_name ?? Str::limit($app->name, 10) }}
                                </span>
                                @endforeach
                                @if($user->applications->count() > 3)
                                <span style="background:#f1f5f9;color:#64748b;padding:3px 8px;border-radius:20px;font-size:0.7rem;font-weight:600">+{{ $user->applications->count() - 3 }}</span>
                                @endif
                            </div>
                        </td>
                        <td style="padding:1rem;text-align:right">
                            <div class="actions" style="justify-content:flex-end;gap:0.5rem">
                                @if(auth()->user()->sidongan_role === 'super_admin')
                                <button type="button" onclick="toggleStatus({{ $user->id }})" 
                                        title="{{ $user->email_verified_at ? 'Nonaktifkan' : 'Aktifkan' }}" 
                                        style="width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;background:{{ $user->email_verified_at ? '#fef3c7' : '#f0fdf4' }};color:{{ $user->email_verified_at ? '#d97706' : '#16a34a' }};border-radius:6px;border:none;cursor:pointer;transition:all 0.2s">
                                    @if($user->email_verified_at)
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
                                    @else
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                                    @endif
                                </button>
                                @endif
                                <a href="{{ route('admin.user-management.edit', $user) }}" class="btn-edit" title="Edit" style="width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;background:#eff6ff;color:#2563eb;border-radius:6px;transition:all 0.2s">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </a>
                                @if($user->id !== auth()->id() && $user->sidongan_role !== 'super_admin')
                                <form action="{{ route('admin.user-management.destroy', $user) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus akun {{ $user->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-del" title="Hapus" style="width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;background:#fef2f2;color:#ef4444;border-radius:6px;border:none;cursor:pointer;transition:all 0.2s">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div style="text-align:center;padding:3rem 1rem;color:var(--text-muted)">
                <p style="margin:0;font-size:0.95rem">Belum ada pengguna dengan akses aplikasi</p>
            </div>
            @endif
        </div>
    </div>

</div>

<script>
// ==========================================
// TOAST NOTIFICATION SYSTEM (INLINE)
// ==========================================
const Toast = {
    container: null,
    
    init() {
        if (this.container) return;
        this.container = document.createElement('div');
        this.container.id = 'toast-container';
        this.container.style.cssText = 'position:fixed;top:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:10px;pointer-events:none;';
        document.body.appendChild(this.container);
        
        if (!document.getElementById('toast-styles')) {
            const style = document.createElement('style');
            style.id = 'toast-styles';
            style.textContent = `
                @keyframes toastSlideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
                @keyframes toastSlideOut { from { transform: translateX(0); opacity: 1; } to { transform: translateX(100%); opacity: 0; } }
                @keyframes modalFadeIn { from { opacity: 0; } to { opacity: 1; } }
                @keyframes modalSlideIn { from { transform: scale(0.9); opacity: 0; } to { transform: scale(1); opacity: 1; } }
                .toast-item { pointer-events: auto; cursor: pointer; transition: all 0.3s ease; }
                .toast-item:hover { transform: translateX(-5px); box-shadow: 0 6px 16px rgba(0,0,0,0.2) !important; }
                .modal-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 10000; display: flex; align-items: center; justify-content: center; animation: modalFadeIn 0.2s ease; }
                .modal-content { background: white; border-radius: 12px; padding: 1.5rem; max-width: 400px; width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,0.3); animation: modalSlideIn 0.3s ease; }
                .modal-title { font-size: 1.1rem; font-weight: 700; color: #1e293b; margin-bottom: 0.75rem; }
                .modal-message { color: #64748b; margin-bottom: 1.5rem; line-height: 1.6; }
                .modal-actions { display: flex; gap: 0.75rem; justify-content: flex-end; }
                .btn-modal { padding: 0.6rem 1.25rem; border-radius: 8px; font-weight: 600; font-size: 0.9rem; cursor: pointer; transition: all 0.2s; border: none; }
                .btn-modal:hover { transform: translateY(-2px); }
                .btn-cancel { background: #f1f5f9; color: #475569; }
                .btn-cancel:hover { background: #e2e8f0; }
                .btn-confirm { background: #dc2626; color: white; }
                .btn-confirm:hover { background: #b91c1c; }
            `;
            document.head.appendChild(style);
        }
    },
    
    show(message, type = 'info', duration = 4000) {
        this.init();
        const configs = {
            success: { bg: '#f0fdf4', border: '#22c55e', text: '#166534', icon: '<polyline points="20 6 9 17 4 12"/>' },
            error: { bg: '#fef2f2', border: '#ef4444', text: '#dc2626', icon: '<circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>' },
            warning: { bg: '#fffbeb', border: '#f59e0b', text: '#92400e', icon: '<path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>' },
            info: { bg: '#eff6ff', border: '#3b82f6', text: '#1e40af', icon: '<circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>' }
        };
        
        const config = configs[type] || configs.info;
        const toast = document.createElement('div');
        toast.className = 'toast-item';
        toast.style.cssText = `background:${config.bg};border-left:4px solid ${config.border};color:${config.text};padding:1rem 1.25rem;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,0.15);display:flex;align-items:center;gap:0.75rem;min-width:300px;max-width:500px;animation:toastSlideIn 0.3s ease;`;
        
        toast.innerHTML = `
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="${config.border}" stroke-width="2" style="flex-shrink:0">${config.icon}</svg>
            <span style="font-weight:500;font-size:0.9rem;flex:1;">${message}</span>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="${config.text}" stroke-width="2" style="opacity:0.5;cursor:pointer;"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        `;
        
        toast.querySelector('svg:last-child').addEventListener('click', () => this.remove(toast));
        this.container.appendChild(toast);
        
        if (duration > 0) {
            setTimeout(() => this.remove(toast), duration);
        }
        
        return toast;
    },
    
    remove(toast) {
        toast.style.animation = 'toastSlideOut 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    },
    
    confirm(message, options = {}) {
        return new Promise((resolve) => {
            this.init();
            const title = options.title || 'Konfirmasi';
            const confirmText = options.confirmText || 'Ya';
            const cancelText = options.cancelText || 'Batal';
            const type = options.type || 'warning';
            
            const colors = {
                warning: { button: '#dc2626' },
                danger: { button: '#dc2626' },
                info: { button: '#3b82f6' },
                success: { button: '#22c55e' }
            };
            
            const color = colors[type] || colors.warning;
            
            const overlay = document.createElement('div');
            overlay.className = 'modal-overlay';
            overlay.innerHTML = `
                <div class="modal-content">
                    <div class="modal-title">${title}</div>
                    <div class="modal-message">${message}</div>
                    <div class="modal-actions">
                        <button class="btn-modal btn-cancel">${cancelText}</button>
                        <button class="btn-modal btn-confirm" style="background:${color.button}">${confirmText}</button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(overlay);
            
            const handleResponse = (confirmed) => {
                overlay.style.animation = 'modalFadeIn 0.2s ease reverse';
                setTimeout(() => overlay.remove(), 200);
                resolve(confirmed);
            };
            
            overlay.querySelector('.btn-cancel').addEventListener('click', () => handleResponse(false));
            overlay.querySelector('.btn-confirm').addEventListener('click', () => handleResponse(true));
            overlay.addEventListener('click', (e) => {
                if (e.target === overlay) handleResponse(false);
            });
        });
    },
    
    success(message, duration = 4000) { return this.show(message, 'success', duration); },
    error(message, duration = 5000) { return this.show(message, 'error', duration); },
    warning(message, duration = 4000) { return this.show(message, 'warning', duration); },
    info(message, duration = 4000) { return this.show(message, 'info', duration); }
};

// ==========================================
// TOGGLE STATUS FUNCTION
// ==========================================
async function toggleStatus(userId) {
    const confirmed = await Toast.confirm(
        'Apakah Anda yakin ingin mengubah status akun ini?',
        {
            title: 'Konfirmasi Perubahan Status',
            confirmText: 'Ya, Ubah',
            cancelText: 'Batal',
            type: 'warning'
        }
    );
    
    if (!confirmed) return;
    
    try {
        const response = await fetch(`/admin/user-management/${userId}/toggle-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            Toast.success(data.message);
            setTimeout(() => location.reload(), 1000);
        } else {
            Toast.error(data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        Toast.error('Terjadi kesalahan saat mengubah status akun');
    }
}

// ==========================================
// TAB SWITCHING
// ==========================================
function switchTab(tabId, btn) {
    document.querySelectorAll('.tab-btn').forEach(b => {
        b.style.color = 'var(--text-muted)';
        b.style.borderBottom = '2px solid transparent';
    });
    document.querySelectorAll('.tab-content').forEach(c => c.style.display = 'none');
    
    btn.style.color = 'var(--primary)';
    btn.style.borderBottom = '2px solid var(--primary)';
    document.getElementById('tab-' + tabId).style.display = 'block';
}

document.addEventListener('DOMContentLoaded', () => {
    const firstBtn = document.querySelector('.tab-btn');
    if(firstBtn) switchTab('all', firstBtn);
});
</script>

@endsection