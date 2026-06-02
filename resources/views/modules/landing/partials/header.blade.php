<nav class="navbar" id="navbar">
    <style>
        /* --- CSS NAVBAR UTAMA --- */
        .navbar {
            position: fixed; top: 0; left: 0; right: 0;
            z-index: 1000;
            /* PERBAIKAN 1: Transparan secara default */
            background: transparent; 
            transition: all 0.3s ease;
        }

        /* Efek saat scroll ke bawah (Menjadi Solid) */
        .navbar.scrolled {
            background: rgba(20, 83, 76, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .navbar-inner {
            max-width: 1400px; margin: 0 auto;
            padding: 1rem 2rem;
            display: flex; align-items: center; justify-content: space-between;
            position: relative;
        }

        /* Logo */
        .navbar-brand { display: flex; align-items: center; gap: 12px; cursor: pointer; z-index: 1002; }
        .navbar-logo { width: 40px; height: 40px; object-fit: contain; }
        .navbar-title { display: flex; flex-direction: column; line-height: 1.2; }
        .navbar-title span:first-child { font-weight: 800; font-size: 1.05rem; color: #fff; letter-spacing: 0.5px; }
        .navbar-title span:last-child { font-size: 0.7rem; color: rgba(255,255,255,0.7); font-weight: 500; }

        /* Desktop Links */
        .navbar-links { display: flex; align-items: center; gap: 8px; list-style: none; margin: 0; padding: 0; }
        .nav-link {
            color: rgba(255,255,255,0.9); text-decoration: none; font-size: 0.875rem; font-weight: 600;
            padding: 6px 10px; border-radius: 6px; transition: all 0.2s ease; cursor: pointer;
        }
        .nav-link:hover { color: #fff; background: rgba(255,255,255,0.15); }
        
        /* Warna Aktif (Kuning) */
        .nav-link.active-link { color: #fbbf24; background: rgba(251, 191, 36, 0.15); font-weight: 700; }

        /* Hamburger Button */
        .hamburger { display: none; flex-direction: column; gap: 5px; cursor: pointer; padding: 5px; z-index: 1002; background: none; border: none; }
        .hamburger span { width: 24px; height: 2px; background: #fff; border-radius: 2px; transition: all 0.3s ease; }
        
        /* Animasi X pada Hamburger */
        .hamburger.active span:nth-child(1) {
            transform: translateY(7px) rotate(45deg);
        }
        .hamburger.active span:nth-child(2) {
            opacity: 0;
            transform: scaleX(0);
        }
        .hamburger.active span:nth-child(3) {
            transform: translateY(-7px) rotate(-45deg);
        }

        /* --- MOBILE MENU DROPDOWN --- */
        .mobile-menu {
            position: absolute; top: 100%; left: 0; right: 0;
            background: rgba(20, 83, 76, 0.98);
            backdrop-filter: blur(15px);
            padding: 1rem 2rem 2rem 2rem;
            border-radius: 0 0 16px 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            
            /* Animasi Muncul */
            opacity: 0; visibility: hidden; transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 999;
            
            /* Layout Vertikal */
            display: flex; flex-direction: column; gap: 0.5rem; 
        }

        .mobile-menu.open { opacity: 1; visibility: visible; transform: translateY(0); }

        /* Link di dalam Mobile Menu */
        .mobile-menu .nav-link {
            display: block; width: 100%; padding: 12px 15px;
            border-radius: 8px; border-bottom: 1px solid rgba(255,255,255,0.05);
            font-size: 1rem; text-align: left;
        }
        .mobile-menu .nav-link:hover { background: rgba(255,255,255,0.05); }

        /* Responsive Logic */
        @media (max-width: 1100px) {
            .navbar-links { display: none; }
            .hamburger { display: flex; }
            .navbar-title span:last-child { display: none; }
        }
        @media (min-width: 1101px) {
            .mobile-menu { display: none !important; }
        }
    </style>

    <div class="navbar-inner">
        <div class="navbar-brand" onclick="navigateTo('beranda'); updateActiveNav('beranda')">
            <img src="{{ asset('assets/landing/images/PKK-Logo.png') }}" alt="Logo" class="navbar-logo">
            <div class="navbar-title">
                <span>PKK KAB. TOBA</span>
                <span>Kabupaten Toba, Sumatera Utara</span>
            </div>
        </div>
        
        {{-- Menu Desktop --}}
        <ul class="navbar-links" id="navLinks">
            {{-- SPA Links --}}
            <li>
                <a href="{{ route('landing.home') }}" 
                onclick="return handleNavClick(event, 'beranda')" 
                class="nav-link active-link" 
                data-page="beranda">Beranda</a>
            </li>
            <li>
                <a href="{{ route('landing.home') }}#struktur" 
                onclick="return handleNavClick(event, 'struktur')" 
                class="nav-link" 
                data-page="struktur">Struktur</a>
            </li>
            <li>
                <a href="{{ route('landing.home') }}#aplikasi" 
                onclick="return handleNavClick(event, 'aplikasi')" 
                class="nav-link" 
                data-page="aplikasi">Aplikasi</a>
            </li>
            
            {{-- Route Link (Berita adalah halaman terpisah) --}}
            <li>
                <a href="{{ url('/berita') }}" class="nav-link" data-page="berita">Berita</a>
            </li>
            
            {{-- SPA Links --}}
            <li>
                <a href="{{ route('landing.home') }}#desa" 
                onclick="return handleNavClick(event, 'desa')" 
                class="nav-link" 
                data-page="desa">Desa</a>
            </li>
            <li>
                <a href="{{ route('landing.home') }}#sk" 
                onclick="return handleNavClick(event, 'sk')" 
                class="nav-link" 
                data-page="sk">SK & Dokumen</a>
            </li>
            <li>
                <a href="{{ route('landing.home') }}#template" 
                onclick="return handleNavClick(event, 'template')" 
                class="nav-link" 
                data-page="template">Template</a>
            </li>
            <li>
                <a href="{{ route('landing.home') }}#tentang" 
                onclick="return handleNavClick(event, 'tentang')" 
                class="nav-link" 
                data-page="tentang">Tentang</a>
            </li>
        </ul>

        {{-- Tombol Hamburger --}}
        <button class="hamburger" id="hamburgerBtn" onclick="toggleMobileMenu()">
            <span></span><span></span><span></span>
        </button>
    </div>

    {{-- Menu Mobile --}}
    <div class="mobile-menu" id="mobileMenu">
        <a href="{{ route('landing.home') }}" 
        onclick="return handleNavClick(event, 'beranda')" 
        class="nav-link" data-page="beranda">Beranda</a>
        <a href="{{ route('landing.home') }}#struktur" 
        onclick="return handleNavClick(event, 'struktur')" 
        class="nav-link" data-page="struktur">Struktur</a>
        <a href="{{ route('landing.home') }}#aplikasi" 
        onclick="return handleNavClick(event, 'aplikasi')" 
        class="nav-link" data-page="aplikasi">Aplikasi</a>
        
        {{-- Berita - Route terpisah --}}
        <a href="{{ url('/berita') }}" class="nav-link" data-page="berita">Berita</a>
        
        <a href="{{ route('landing.home') }}#desa" 
        onclick="return handleNavClick(event, 'desa')" 
        class="nav-link" data-page="desa">Desa</a>
        <a href="{{ route('landing.home') }}#sk" 
        onclick="return handleNavClick(event, 'sk')" 
        class="nav-link" data-page="sk">SK & Dokumen</a>
        <a href="{{ route('landing.home') }}#template" 
        onclick="return handleNavClick(event, 'template')" 
        class="nav-link" data-page="template">Template</a>
        <a href="{{ route('landing.home') }}#tentang" 
        onclick="return handleNavClick(event, 'tentang')" 
        class="nav-link" data-page="tentang">Tentang</a>
    </div>
</nav>

<script>
    // FUNGSI BARU: Handle navigation untuk desktop & mobile
    function handleNavClick(event, pageId) {
        console.log('handleNavClick:', pageId);
        console.log('Current href:', event.currentTarget.href);
        
        // Cek apakah ini halaman SPA
        const isSPA = document.getElementById('page-beranda') !== null;
        console.log('Is SPA?', isSPA);
        
        if (isSPA && typeof navigateTo === 'function') {
            // Di SPA → prevent default & pakai SPA navigation
            event.preventDefault();
            navigateTo(pageId);
            updateActiveNav(pageId);
            toggleMobileMenu();
            return false;
        } else {
            // Di halaman Blade terpisah → biarkan href bekerja
            // URL sudah ada hash (#template, #struktur, dll)
            console.log('Not SPA, navigating to:', event.currentTarget.href);
            toggleMobileMenu();
            return true;
        }
    }

    // 1. Fungsi Utama saat klik menu di HP (untuk mobile menu)
    function handleMobileClick(pageId) {
        console.log('📱 handleMobileClick:', pageId);
        
        if (typeof navigateTo === 'function') {
            try {
                navigateTo(pageId);
                updateActiveNav(pageId);
                toggleMobileMenu();
                return false;
            } catch(e) {
                console.log('SPA navigation failed:', e);
            }
        }
        toggleMobileMenu();
        return true;
    }

    // 2. Fungsi Toggle Hamburger
    function toggleMobileMenu() {
        const btn = document.getElementById('hamburgerBtn');
        const menu = document.getElementById('mobileMenu');
        if (btn) btn.classList.toggle('active');
        if (menu) menu.classList.toggle('open');
    }

    // 3. Fungsi PENTING: Update Highlight Navbar (Kuning)
    function updateActiveNav(pageId) {
        document.querySelectorAll('.nav-link').forEach(link => {
            link.classList.remove('active-link');
        });
        document.querySelectorAll(`.nav-link[data-page="${pageId}"]`).forEach(link => {
            link.classList.add('active-link');
        });
    }

    // 4. Scroll Effect (Transparan -> Solid)
    window.addEventListener('scroll', () => {
        const nav = document.getElementById('navbar');
        if (nav) {
            if (window.scrollY > 50) nav.classList.add('scrolled');
            else nav.classList.remove('scrolled');
        }
    });

    // 5. FIX: Aktifkan menu SAAT RELOAD
    document.addEventListener('DOMContentLoaded', () => {
        const hash = window.location.hash.replace('#', '');
        const currentPage = hash || 'beranda';
        updateActiveNav(currentPage);
        
        if (window.scrollY > 50) {
            const nav = document.getElementById('navbar');
            if (nav) nav.classList.add('scrolled');
        }
        
        // Debug: Log navbar status
        console.log('🎯 Navbar initialized. Current page:', currentPage);
    });
</script>