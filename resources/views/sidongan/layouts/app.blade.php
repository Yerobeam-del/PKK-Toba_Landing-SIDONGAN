<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIDONGAN - PKK Kabupaten Toba')</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 80px;
            --header-height: 64px;
            --primary: #14b8a6;
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --sidebar-active: rgba(20,184,166,0.15);
            --text-light: #f8fafc;
            --text-muted: #94a3b8;
            --border: #334155;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f1f5f9;
            color: #334155;
            overflow-x: hidden;
        }

        .admin-layout {
            display: flex;
            min-height: 100vh;
            position: relative;
        }

        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            color: var(--text-light);
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            transition: var(--transition);
            border-right: 1px solid var(--border);
        }

        .admin-layout.collapsed .sidebar {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar-header {
            height: var(--header-height);
            display: flex;
            align-items: center;
            padding: 0 1.25rem;
            gap: 0.75rem;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
            overflow: hidden;
        }

        .sidebar-logo {
            width: 44px; 
            height: 44px;
            background: linear-gradient(135deg, #3b82f6, #10b981);
            border-radius: 8px;
            display: flex; 
            align-items: center; 
            justify-content: center;
            flex-shrink: 0;
        }

        .sidebar-logo i {
            color: white;
            font-size: 1.25rem;
        }

        .sidebar-title {
            transition: var(--transition);
            opacity: 1;
            width: auto;
        }

        .admin-layout.collapsed .sidebar-title {
            opacity: 0;
            width: 0;
        }

        .sidebar-title h1 { font-size: 1rem; font-weight: 700; line-height: 1.2; }
        .sidebar-title small { font-size: 0.7rem; color: var(--text-muted); font-weight: 500; }

        .sidebar-nav {
            flex: 1;
            padding: 1rem 0.75rem;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .nav-section-title {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            padding: 0.5rem 0.5rem;
            margin-top: 1rem;
            white-space: nowrap;
            transition: var(--transition);
        }

        .admin-layout.collapsed .nav-section-title {
            opacity: 0;
            height: 0;
            padding: 0;
            margin: 0;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 0.75rem;
            margin-bottom: 0.25rem;
            border-radius: 8px;
            color: var(--text-light);
            text-decoration: none;
            transition: all 0.2s;
            white-space: nowrap;
            overflow: hidden;
        }

        .nav-item:hover {
            background: var(--sidebar-hover);
        }

        .nav-item.active {
            background: var(--sidebar-active);
            border-left: 4px solid var(--primary);
            padding-left: calc(0.75rem - 4px);
        }

        .nav-icon-box {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .nav-icon-box svg {
            width: 20px;
            height: 20px;
        }

        .nav-text {
            transition: var(--transition);
            opacity: 1;
            width: auto;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .admin-layout.collapsed .nav-text {
            opacity: 0;
            width: 0;
        }

        .main-wrapper {
            flex: 1;
            margin-left: var(--sidebar-width);
            transition: var(--transition);
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .admin-layout.collapsed .main-wrapper {
            margin-left: var(--sidebar-collapsed-width);
        }

        .top-header {
            height: var(--header-height);
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            position: sticky;
            top: 0;
            z-index: 900;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        .toggle-btn {
            width: 36px; height: 36px;
            display: flex; align-items: center; justify-content: center;
            background: transparent;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            cursor: pointer;
            color: #64748b;
            transition: var(--transition);
        }

        .toggle-btn:hover { background: #f1f5f9; color: var(--primary); }
        .toggle-btn svg { width: 20px; height: 20px; }

        .header-right { display: flex; align-items: center; gap: 1rem; }

        .content-area {
            padding: 2rem;
            flex: 1;
            width: 100%;
            overflow-x: hidden;
        }

        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); }
            .admin-layout.mobile-open .sidebar { transform: translateX(0); }
            .main-wrapper { margin-left: 0 !important; }
            .sidebar-overlay {
                display: none; position: fixed; inset: 0;
                background: rgba(0,0,0,0.5); z-index: 999;
                transition: opacity 0.3s;
            }
            .admin-layout.mobile-open .sidebar-overlay { display: block; opacity: 1; }
            .admin-layout.collapsed .sidebar { width: var(--sidebar-width); }
            .admin-layout.collapsed .nav-item .nav-text { opacity: 1; width: auto; position: static; box-shadow: none; }
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 480px) {
            .user-text { display: none !important; }
        }

        .user-profile-btn:hover .user-text span:first-child {
            color: var(--primary);
        }

        .user-menu {
            display: none;
            position: absolute;
            right: 0;
            top: calc(100% + 0.5rem);
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            min-width: 220px;
            z-index: 1000;
            animation: slideIn 0.2s ease;
        }

        @keyframes slideIn {
            from { 
                opacity: 0; 
                transform: translateY(-8px); 
            }
            to { 
                opacity: 1; 
                transform: translateY(0); 
            }
        }

        /* Hide submenu items when sidebar is collapsed */
        .admin-layout.collapsed .surat-submenu {
            display: none !important;
        }

        .admin-layout.collapsed .nav-item-wrapper {
            margin-bottom: 0.25rem;
        }

        /* Ensure arrow icon is visible */
        #suratArrow {
            display: block;
        }

        .admin-layout.collapsed #suratArrow {
            display: none;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    @php
        $currentUser = auth()->guard('sidongan')->user();
        
        if (!$currentUser && !request()->routeIs('sidongan.login*')) {
            if (!request()->ajax() && !request()->wantsJson()) {
                echo '<script>window.location.href="' . route('sidongan.login') . '";</script>';
                exit;
            }
        }
    @endphp

    <div class="admin-layout" id="adminLayout">
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <i class="fas fa-file-signature"></i>
                </div>
                <div class="sidebar-title">
                    <h1>SIDONGAN</h1>
                    <small>PKK Kabupaten Toba</small>
                </div>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-section-title">Menu Utama</div>
                
                <a href="{{ route('sidongan.dashboard') }}" class="nav-item {{ request()->routeIs('sidongan.dashboard') ? 'active' : '' }}">
                    <div class="nav-icon-box">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    </div>
                    <span class="nav-text">Dashboard</span>
                </a>
                
                {{-- MENU KHUSUS SEKRETARIS (Dengan Dropdown) --}}
                @if($currentUser && $currentUser->hasSidonganRole('sekretaris'))
                <div style="margin-bottom: 0.25rem;">
                    <a href="javascript:void(0)" onclick="toggleSuratMenu()" class="nav-item" style="justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div class="nav-icon-box">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            </div>
                            <span class="nav-text">Surat</span>
                        </div>
                        <svg id="suratArrow" class="nav-text" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="transition: transform 0.2s;">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </a>
                    <div id="suratSubmenu" class="surat-submenu" style="display: none; padding-left: 1.25rem; margin-top: 0.25rem;">
                        <a href="{{ route('sidongan.documents.index') }}" class="nav-item {{ request()->routeIs('sidongan.documents.index') ? 'active' : '' }}" style="font-size: 0.85rem; padding: 0.5rem 0.75rem; margin-bottom: 0.125rem;">
                            <span class="nav-text">Daftar Surat</span>
                        </a>
                        <a href="{{ route('sidongan.documents.create') }}" class="nav-item {{ request()->routeIs('sidongan.documents.create') ? 'active' : '' }}" style="font-size: 0.85rem; padding: 0.5rem 0.75rem; margin-bottom: 0.125rem;">
                            <span class="nav-text">Buat Surat Baru</span>
                        </a>
                    </div>
                </div>

                {{-- MENU LAPORAN KEGIATAN (KHUSUS SEKRETARIS) --}}
                <a href="{{ route('sidongan.lapor_kegiatan.index') }}" class="nav-item {{ request()->routeIs('sidongan.lapor_kegiatan*') ? 'active' : '' }}">
                    <div class="nav-icon-box">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <line x1="16" y1="13" x2="8" y2="13"/>
                            <line x1="16" y1="17" x2="8" y2="17"/>
                            <polyline points="9 19 9 19"/>
                            <line x1="15" y1="19" x2="15" y2="19"/>
                        </svg>
                    </div>
                    <span class="nav-text">Lapor Kegiatan</span>
                </a>
                @endif

                {{-- MENU KHUSUS KETUA PKK --}}
                @if($currentUser && $currentUser->hasSidonganRole('ketua'))
                    <a href="{{ route('sidongan.documents.index') }}" class="nav-item {{ request()->routeIs('sidongan.documents.*') ? 'active' : '' }}">
                        <div class="nav-icon-box">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        </div>
                        <span class="nav-text">Surat</span>
                    </a>
                    <a href="{{ route('sidongan.disposisi') }}" class="nav-item {{ request()->routeIs('sidongan.disposisi*') ? 'active' : '' }}">
                        <div class="nav-icon-box">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                        </div>
                        <span class="nav-text">Disposisi Surat</span>
                    </a>
                    <a href="{{ route('sidongan.verifikasi') }}" class="nav-item {{ request()->routeIs('sidongan.verifikasi*') ? 'active' : '' }}">
                        <div class="nav-icon-box">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <span class="nav-text">Verifikasi Laporan</span>
                    </a>
                @endif

                {{-- MENU KHUSUS BENDAHARA & KETUA POKJA (SAMA PERSIS) --}}
                @if($currentUser && ($currentUser->hasSidonganRole('bendahara') || $currentUser->isSidonganPokja()))
                    {{-- Daftar Surat --}}
                    <a href="{{ route('sidongan.documents.index') }}" class="nav-item {{ request()->routeIs('sidongan.documents.*') ? 'active' : '' }}">
                        <div class="nav-icon-box">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                        </div>
                        <span class="nav-text">Daftar Surat</span>
                    </a>
                    
                    {{-- Lapor Kegiatan --}}
                    <a href="{{ route('sidongan.lapor-kegiatan.index') }}" class="nav-item {{ request()->routeIs('sidongan.lapor-kegiatan*') ? 'active' : '' }}">
                        <div class="nav-icon-box">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                                <line x1="16" y1="13" x2="8" y2="13"/>
                                <line x1="16" y1="17" x2="8" y2="17"/>
                            </svg>
                        </div>
                        <span class="nav-text">Lapor Kegiatan</span>
                    </a>
                @endif

                {{-- Menu Umum (Arsip & Notifikasi) --}}
                <a href="{{ route('sidongan.arsip') }}" class="nav-item {{ request()->routeIs('sidongan.arsip') ? 'active' : '' }}">
                    <div class="nav-icon-box">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="21 8 21 21 3 21 3 8"/><rect x="1" y="3" width="22" height="5"/><line x1="10" y1="12" x2="14" y2="12"/></svg>
                    </div>
                    <span class="nav-text">Arsip Surat</span>
                </a>
                
                <a href="{{ route('sidongan.notifications') }}" class="nav-item {{ request()->routeIs('sidongan.notifications') ? 'active' : '' }}">
                    <div class="nav-icon-box">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                    </div>
                    <span class="nav-text">Notifikasi</span>
                </a>
            </nav>

            {{-- Tombol "Keluar" dihapus dari sidebar. Gunakan dropdown di header kanan atas. --}}
        </aside>

        <div class="main-wrapper">
            <header class="top-header">
                <button class="toggle-btn" id="toggleBtn" title="Toggle Sidebar">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="9" y1="3" x2="9" y2="21"></line>
                    </svg>
                </button>
                <div class="header-right" style="position:relative">
                    
                    {{-- NOTIFIKASI POPUP --}}
                    @php
                        // Query langsung notifikasi
                        $user = auth()->guard('sidongan')->user();
                        if ($user) {
                            $sidonganNotifications = \App\Models\Notification::where('user_id', $user->id)
                                ->latest()
                                ->take(5)
                                ->get();
                            $sidonganUnreadCount = \App\Models\Notification::where('user_id', $user->id)
                                ->whereNull('read_at')
                                ->count();
                        } else {
                            $sidonganNotifications = collect();
                            $sidonganUnreadCount = 0;
                        }
                    @endphp

                    <div style="position: relative;">
                        <button onclick="toggleNotificationPopup()" class="toggle-btn" style="position: relative; margin-right: 0.5rem;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                                <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                            </svg>
                            @if($sidonganUnreadCount > 0)
                            <span style="position: absolute; top: 2px; right: 2px; width: 16px; height: 16px; background: #ef4444; border-radius: 50%; border: 2px solid white; display: flex; align-items: center; justify-content: center;">
                                <span style="color: white; font-size: 10px; font-weight: 700; line-height: 1;">{{ $sidonganUnreadCount > 9 ? '9+' : $sidonganUnreadCount }}</span>
                            </span>
                            @endif
                        </button>
                        
                        {{-- Popup Notifikasi --}}
                        <div id="notificationPopup" style="display: none; position: absolute; right: 0; top: calc(100% + 0.5rem); width: 400px; background: white; border-radius: 0.75rem; box-shadow: 0 10px 40px rgba(0,0,0,0.15); border: 1px solid #e2e8f0; z-index: 1000; animation: slideIn 0.2s ease;">
                            
                            {{-- Header Popup --}}
                            <div style="padding: 1rem 1.25rem; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                                <h3 style="font-size: 1rem; font-weight: 700; color: #1e293b; margin: 0;">Notifikasi</h3>
                                @if($sidonganUnreadCount > 0)
                                <button onclick="markAllAsRead()" style="font-size: 0.75rem; color: #2563eb; background: none; border: none; cursor: pointer; font-weight: 500;">Tandai semua dibaca</button>
                                @endif
                            </div>
                            
                            {{-- List Notifikasi --}}
                            <div style="max-height: 350px; overflow-y: auto;">
                                @forelse($sidonganNotifications as $notif)
                                <div style="padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; background: {{ $notif->read_at ? '#ffffff' : '#eff6ff' }}; cursor: pointer; transition: background 0.2s;" 
                                    onmouseover="this.style.background='{{ $notif->read_at ? '#f8fafc' : '#dbeafe' }}'" 
                                    onmouseout="this.style.background='{{ $notif->read_at ? '#ffffff' : '#eff6ff' }}'"
                                    @if(!$notif->read_at && $notif->related_id) onclick="window.location.href='{{ route('sidongan.documents.show', $notif->related_id) }}'" @endif>
                                    <div style="display: flex; gap: 0.75rem; align-items: start;">
                                        <div style="width: 2rem; height: 2rem; background: {{ $notif->read_at ? '#f1f5f9' : '#dbeafe' }}; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                            <i class="fas fa-bell" style="color: {{ $notif->read_at ? '#94a3b8' : '#3b82f6' }}; font-size: 0.85rem;"></i>
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
                                </div>
                                @empty
                                <div style="padding: 3rem 1.25rem; text-align: center;">
                                    <div style="width: 64px; height: 64px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                                        <i class="fas fa-bell-slash" style="color: #94a3b8; font-size: 1.75rem;"></i>
                                    </div>
                                    <p style="font-size: 0.95rem; color: #64748b; margin: 0 0 0.5rem 0; font-weight: 500;">Tidak ada notifikasi</p>
                                    <p style="font-size: 0.8rem; color: #94a3b8; margin: 0;">Notifikasi akan muncul di sini</p>
                                </div>
                                @endforelse
                            </div>
                            
                            {{-- Footer Popup --}}
                            <div style="padding: 0.75rem 1.25rem; border-top: 1px solid #e2e8f0; text-align: center;">
                                <a href="{{ route('sidongan.notifications') }}" style="font-size: 0.875rem; color: #2563eb; text-decoration: none; font-weight: 500;">Lihat Semua Notifikasi →</a>
                            </div>
                        </div>
                    </div>
                    
                    {{-- User Profile Button --}}
                    @if($currentUser)
                    <button onclick="toggleUserMenu()" class="user-profile-btn" style="display:flex;align-items:center;gap:0.75rem;background:none;border:none;cursor:pointer;padding:0.5rem 0.75rem;border-radius:8px;transition:background 0.2s" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
                        <div class="user-text" style="text-align:right;display:flex;flex-direction:column;align-items:flex-end">
                            <span style="font-weight:600;font-size:0.9rem;color:#334155;line-height:1.2">{{ $currentUser->name }}</span>
                            <span style="font-size:0.7rem;color:#94a3b8">{{ $currentUser->sidongan_role_name }}</span>
                        </div>
                        <div style="width:36px;height:36px;border-radius:50%;overflow:hidden;background:linear-gradient(135deg,{{ $currentUser->sidongan_role === 'ketua' ? '#dc2626' : ($currentUser->sidongan_role === 'sekretaris' ? '#2563eb' : '#4f46e5') }},#14b8a6);display:flex;align-items:center;justify-content:center;flex-shrink:0;border:2px solid #fff;box-shadow:0 2px 4px rgba(0,0,0,0.1)">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                        </div>
                        <svg id="userMenuArrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" style="transition:transform 0.2s">
                            <polyline points="6 9 12 15 18 9"/>
                        </svg>
                    </button>

                    {{-- User Dropdown Menu --}}
                    <div id="userMenu" class="user-menu">
                        <div style="padding:0.75rem 1rem;border-bottom:1px solid #f1f5f9">
                            <div style="font-weight:600;font-size:0.9rem;color:#334155">{{ $currentUser->name }}</div>
                            <div style="font-size:0.75rem;color:#94a3b8">{{ $currentUser->sidongan_role_name }}</div>
                        </div>
                        <form method="POST" action="{{ route('sidongan.logout') }}" style="padding:0.5rem 0">
                            @csrf
                            <button type="submit" style="width:100%;display:flex;align-items:center;gap:0.75rem;padding:0.65rem 1rem;background:none;border:none;cursor:pointer;color:#ef4444;transition:background 0.2s;text-align:left;font-size:0.9rem" onmouseover="this.style.background='#fef2f2'" onmouseout="this.style.background='transparent'">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                    <polyline points="16 17 21 12 16 7"/>
                                    <line x1="21" y1="12" x2="9" y2="12"/>
                                </svg>
                                <span>Keluar</span>
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </header>

            <main class="content-area">
                @if(session('success'))
                <div style="background:#f0fdf4;border-left:4px solid #16a34a;padding:1rem;margin-bottom:1.5rem;border-radius:8px;">
                    <div style="display:flex;align-items:center;gap:0.75rem;color:#166534;">
                        <i class="fas fa-check-circle"></i>
                        <p style="margin:0;">{{ session('success') }}</p>
                    </div>
                </div>
                @endif
                
                @if(session('error'))
                <div style="background:#fef2f2;border-left:4px solid #dc2626;padding:1rem;margin-bottom:1.5rem;border-radius:8px;">
                    <div style="display:flex;align-items:center;gap:0.75rem;color:#dc2626;">
                        <i class="fas fa-exclamation-circle"></i>
                        <p style="margin:0;">{{ session('error') }}</p>
                    </div>
                </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const layout = document.getElementById('adminLayout');
            const toggleBtn = document.getElementById('toggleBtn');
            const overlay = document.getElementById('sidebarOverlay');
            const navItems = document.querySelectorAll('.nav-item');

            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            const isMobile = window.innerWidth <= 1024;

            if (!isMobile && isCollapsed) {
                layout.classList.add('collapsed');
            }

            toggleBtn.addEventListener('click', () => {
                if (window.innerWidth <= 1024) {
                    layout.classList.toggle('mobile-open');
                } else {
                    layout.classList.toggle('collapsed');
                    localStorage.setItem('sidebarCollapsed', layout.classList.contains('collapsed'));
                }
            });

            navItems.forEach(item => {
                item.addEventListener('click', () => {
                    if (window.innerWidth <= 1024) {
                        layout.classList.remove('mobile-open');
                    }
                });
            });

            overlay.addEventListener('click', () => {
                layout.classList.remove('mobile-open');
            });

            window.addEventListener('resize', () => {
                if (window.innerWidth > 1024) {
                    layout.classList.remove('mobile-open');
                    if (localStorage.getItem('sidebarCollapsed') === 'true') {
                        layout.classList.add('collapsed');
                    } else {
                        layout.classList.remove('collapsed');
                    }
                } else {
                    layout.classList.remove('collapsed');
                }
            });
        });

        function toggleUserMenu() {
            const menu = document.getElementById('userMenu');
            const arrow = document.getElementById('userMenuArrow');
            
            if (menu.style.display === 'block') {
                menu.style.display = 'none';
                arrow.style.transform = 'rotate(0deg)';
            } else {
                menu.style.display = 'block';
                arrow.style.transform = 'rotate(180deg)';
            }
        }

        document.addEventListener('click', function(e) {
            const menu = document.getElementById('userMenu');
            const btn = e.target.closest('.user-profile-btn');
            
            if (!btn && menu.style.display === 'block') {
                menu.style.display = 'none';
                document.getElementById('userMenuArrow').style.transform = 'rotate(0deg)';
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const menu = document.getElementById('userMenu');
                menu.style.display = 'none';
                document.getElementById('userMenuArrow').style.transform = 'rotate(0deg)';
            }
        });

        // Toggle Notification Popup
        function toggleNotificationPopup() {
            const popup = document.getElementById('notificationPopup');
            const userMenu = document.getElementById('userMenu');
            
            // Close user menu if open
            if (userMenu && userMenu.style.display === 'block') {
                userMenu.style.display = 'none';
                const arrow = document.getElementById('userMenuArrow');
                if (arrow) arrow.style.transform = 'rotate(0deg)';
            }
            
            // Toggle notification popup
            if (popup.style.display === 'block') {
                popup.style.display = 'none';
            } else {
                popup.style.display = 'block';
            }
        }

        // Mark All as Read
        function markAllAsRead() {
            fetch('/sidongan/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Reload halaman untuk update UI
                    location.reload();
                } else {
                    alert('Gagal menandai notifikasi sebagai dibaca');
                }
            })
            .catch(err => {
                console.error('Error:', err);
                alert('Terjadi kesalahan');
            });
        }

        // Close popups when clicking outside
        document.addEventListener('click', function(e) {
            const notificationBtn = e.target.closest('button[onclick="toggleNotificationPopup()"]');
            const notificationPopup = document.getElementById('notificationPopup');
            const userMenu = document.getElementById('userMenu');
            const userBtn = e.target.closest('.user-profile-btn');
            
            // Close notification popup
            if (!notificationBtn && notificationPopup && notificationPopup.style.display === 'block') {
                if (!notificationPopup.contains(e.target)) {
                    notificationPopup.style.display = 'none';
                }
            }
            
            // Close user menu
            if (!userBtn && userMenu && userMenu.style.display === 'block') {
                if (!userMenu.contains(e.target)) {
                    userMenu.style.display = 'none';
                    const arrow = document.getElementById('userMenuArrow');
                    if (arrow) arrow.style.transform = 'rotate(0deg)';
                }
            }
        });

        // Close popups on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const notificationPopup = document.getElementById('notificationPopup');
                const userMenu = document.getElementById('userMenu');
                
                if (notificationPopup) notificationPopup.style.display = 'none';
                if (userMenu) userMenu.style.display = 'none';
                
                const arrow = document.getElementById('userMenuArrow');
                if (arrow) arrow.style.transform = 'rotate(0deg)';
            }
        });

        function toggleSuratMenu() {
            const submenu = document.getElementById('suratSubmenu');
            const arrow = document.getElementById('suratArrow');
            const layout = document.getElementById('adminLayout');
            
            if (!submenu || !arrow) return;
            
            // Check if sidebar is collapsed
            const isCollapsed = layout.classList.contains('collapsed');
            if (isCollapsed) {
                // Expand sidebar first
                layout.classList.remove('collapsed');
                localStorage.setItem('sidebarCollapsed', 'false');
            }
            
            // Toggle submenu
            if (submenu.style.display === 'none' || submenu.style.display === '') {
                submenu.style.display = 'block';
                arrow.style.transform = 'rotate(180deg)';
                localStorage.setItem('suratMenuOpen', 'true');
            } else {
                submenu.style.display = 'none';
                arrow.style.transform = 'rotate(0deg)';
                localStorage.setItem('suratMenuOpen', 'false');
            }
        }

        // Restore state on load
        document.addEventListener('DOMContentLoaded', () => {
            const isOpen = localStorage.getItem('suratMenuOpen') === 'true';
            const submenu = document.getElementById('suratSubmenu');
            const arrow = document.getElementById('suratArrow');
            const layout = document.getElementById('adminLayout');
            const isCollapsed = layout.classList.contains('collapsed');
            
            if (isOpen && submenu && arrow && !isCollapsed) {
                submenu.style.display = 'block';
                arrow.style.transform = 'rotate(180deg)';
            }
        });
    </script>

    @stack('scripts')
</body>
</html>