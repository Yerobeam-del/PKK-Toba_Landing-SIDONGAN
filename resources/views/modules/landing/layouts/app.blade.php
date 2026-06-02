<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PKK Kabupaten Toba - Portal Aplikasi')</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/landing/css/style.css') }}">
    
    @stack('styles')
</head>
<body>
    {{-- Header / Navbar --}}
    @include('modules.landing.partials.header')
    
    {{-- Floating Button --}}
    @include('modules.landing.partials.floating-btn')
    
    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>
    
    {{-- Footer --}}
    @include('modules.landing.partials.footer')
    
    {{-- News Modal --}}
    @include('modules.landing.partials.news-modal')
    
    {{-- Global Config & Init --}}
    <script>
        window.Laravel = { csrfToken: '{{ csrf_token() }}' };
        // Script inisialisasi SPA sudah dipindah ke navigation.js
    </script>
    
    {{-- JavaScript Modular --}}
    <script src="{{ asset('assets/landing/js/main.js') }}"></script>
    <script src="{{ asset('assets/landing/js/navigation.js') }}"></script>
    <script src="{{ asset('assets/landing/js/hero-slider.js') }}"></script>
    <script src="{{ asset('assets/landing/js/news-handler.js') }}"></script>
    <script src="{{ asset('assets/landing/js/desa-handler.js') }}"></script>
    <script src="{{ asset('assets/landing/js/sk-handler.js') }}"></script>
    <script src="{{ asset('assets/landing/js/template-handler.js') }}"></script>
    <script src="{{ asset('assets/landing/js/struktur-handler.js') }}"></script>
    <script src="{{ asset('assets/landing/js/animations.js') }}"></script>
    
    {{-- Dynamic News Content (Inline Script) --}}
    <script>
        // Only run if functions not already defined in external JS files
        if (typeof populateNewsHome !== 'function') {
            async function fetchNewsFromAPI(limit = 10) {
                try {
                    const response = await fetch('/api/v1/news?limit=' + limit);
                    const result = await response.json();
                    return result.success ? result.data : [];
                } catch (error) {
                    console.error('Gagal memuat berita:', error);
                    return window.landingNewsData || [];
                }
            }
            
            function renderNewsCard(news, index) {
                const imgUrl = news.image_path 
                    ? `/storage/${news.image_path}` 
                    : '/assets/landing/images/berita/default.jpg';
                
                const date = news.published_at 
                    ? new Date(news.published_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })
                    : new Date(news.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
                
                return `<div class="news-card" onclick="openNewsModalBySlug('${news.slug}')">
                    <img src="${imgUrl}" alt="${news.title}" class="news-card-image" onerror="this.src='/assets/landing/images/berita/default.jpg'">
                    <div class="news-card-body">
                        <div class="news-card-date">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            ${date}
                        </div>
                        <span class="news-card-category">${news.category}</span>
                        <h3 class="news-card-title">${news.title}</h3>
                        <p class="news-card-excerpt">${news.excerpt}</p>
                        <span class="news-card-link">Baca Selengkapnya<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span>
                    </div>
                </div>`;
            }
            
            async function populateNewsHome() {
                const grid = document.getElementById('newsHomeGrid');
                if (!grid) return;
                
                try {
                    const news = await fetchNewsFromAPI(3);
                    if (news.length === 0) {
                        grid.innerHTML = '<div class="text-center py-8 col-span-3"><p class="text-muted">Belum ada berita.</p></div>';
                        return;
                    }
                    grid.innerHTML = news.map((n, i) => renderNewsCard(n, i)).join('');
                } catch (error) {
                    console.error('Error:', error);
                }
            }
            
            async function openNewsModalBySlug(slug) {
                try {
                    const response = await fetch('/api/v1/news/' + slug);
                    const result = await response.json();
                    if (result.success) {
                        const news = result.data;
                        document.getElementById('newsModalImage').src = news.image_path ? `/storage/${news.image_path}` : '/assets/landing/images/berita/default.jpg';
                        document.getElementById('newsModalDate').innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> ${new Date(news.published_at || news.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' })}`;
                        document.getElementById('newsModalCategory').textContent = news.category;
                        document.getElementById('newsModalTitle').textContent = news.title;
                        document.getElementById('newsModalContent').textContent = news.content;
                        document.getElementById('newsModal').classList.add('active');
                        document.body.style.overflow = 'hidden';
                    }
                } catch (error) {
                    console.error('Error:', error);
                }
            }
            
            function closeNewsModal() {
                document.getElementById('newsModal')?.classList.remove('active');
                document.body.style.overflow = '';
            }
            
            // Auto-navigate based on URL hash when page loads
            document.addEventListener('DOMContentLoaded', function() {
                const hash = window.location.hash.replace('#', '');
                console.log('Page loaded with hash:', hash);
                
                if (hash && typeof navigateTo === 'function') {
                    // Tunggu sebentar agar DOM ready
                    setTimeout(function() {
                        navigateTo(hash);
                        updateActiveNav(hash);
                    }, 100);
                }
            });
        }
    </script>
    
    @stack('scripts')
</body>
</html>