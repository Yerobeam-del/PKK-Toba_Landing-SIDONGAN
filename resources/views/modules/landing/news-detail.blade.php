@extends('modules.landing.layouts.app')

@section('title', $news->title . ' - Berita')

@push('styles')
<style>
    /* News Detail - Match Your Design System */
    
    .news-detail-wrapper {
        background: var(--bg-light);
        min-height: 100vh;
    }
    
    /* Page Header - Match your existing style */
    .news-page-header {
        padding: 8rem 2rem 4rem;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .news-page-header::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    
    .news-page-header-content {
        position: relative;
        z-index: 1;
    }
    
    .news-page-header h1 {
        font-size: 2.5rem;
        font-weight: 800;
        color: #fff;
        margin-bottom: 0.5rem;
    }
    
    .news-page-header p {
        color: rgba(255,255,255,0.7);
        font-size: 1.05rem;
    }
    
    .news-breadcrumb {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 1.5rem;
    }
    
    .news-breadcrumb a {
        color: rgba(255,255,255,0.6);
        text-decoration: none;
        font-size: 0.85rem;
        cursor: pointer;
        transition: color 0.3s ease;
    }
    
    .news-breadcrumb a:hover {
        color: var(--gold);
    }
    
    .news-breadcrumb span {
        color: rgba(255,255,255,0.4);
    }
    
    .news-breadcrumb .current {
        color: var(--gold);
        font-weight: 600;
    }
    
    /* Article Content */
    .news-detail-container {
        max-width: 900px;
        margin: -3rem auto 4rem;
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        border: 1px solid rgba(0,0,0,0.04);
        overflow: hidden;
        position: relative;
        z-index: 10;
    }
    
    .news-detail-article {
        padding: 2.5rem;
    }
    
    .news-detail-title {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--text-dark);
        line-height: 1.3;
        margin-bottom: 1rem;
        text-align: center;
    }
    
    .news-detail-meta {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    
    .news-detail-category {
        display: inline-flex;
        align-items: center;
        background: rgba(19, 140, 127, 0.1);
        color: var(--news-color);
        font-size: 0.72rem;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 20px;
    }
    
    .news-detail-date {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.75rem;
        color: var(--text-muted);
        font-weight: 500;
    }
    
    .news-detail-date svg {
        width: 14px;
        height: 14px;
    }
    
    /* Featured Image */
    .news-detail-image {
        width: 100%;
        height: 400px;
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 2rem;
        background: var(--bg-light);
    }
    
    .news-detail-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.3s ease;
    }
    
    .news-detail-image img:hover {
        transform: scale(1.02);
    }
    
    /* Excerpt */
    .news-detail-excerpt {
        font-size: 1.1rem;
        color: var(--text-secondary, #4a5568);
        line-height: 1.8;
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: rgba(19, 140, 127, 0.05);
        border-left: 4px solid var(--news-color);
        border-radius: 0 12px 12px 0;
    }
    
    /* Content */
    .news-detail-content {
        line-height: 1.8;
        color: var(--text-dark);
        font-size: 1rem;
    }
    
    .news-detail-content p {
        margin-bottom: 1.25rem;
    }
    
    .news-detail-content img {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
        margin: 1rem 0;
        display: block;
    }
    
    .news-detail-content h2,
    .news-detail-content h3,
    .news-detail-content h4 {
        margin: 1.5rem 0 0.75rem;
        font-weight: 700;
        color: var(--text-dark);
    }
    
    .news-detail-content h2 { font-size: 1.4rem; }
    .news-detail-content h3 { font-size: 1.2rem; }
    .news-detail-content h4 { font-size: 1.05rem; }
    
    .news-detail-content ul,
    .news-detail-content ol {
        margin: 0.75rem 0 1.25rem 1.5rem;
    }
    
    .news-detail-content li {
        margin-bottom: 0.375rem;
        color: var(--text-dark);
    }
    
    .news-detail-content a {
        color: var(--news-color);
        text-decoration: none;
        border-bottom: 1px dashed var(--news-color);
        transition: all 0.2s;
    }
    
    .news-detail-content a:hover {
        color: var(--primary);
        border-bottom-style: solid;
    }
    
    /* Share Section */
    .news-detail-share {
        margin: 2.5rem 0 0;
        padding-top: 1.5rem;
        border-top: 1px solid rgba(0,0,0,0.05);
        text-align: center;
    }
    
    .news-detail-share-title {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-muted);
        margin-bottom: 1rem;
    }
    
    .news-share-buttons {
        display: flex;
        justify-content: center;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
    
    .news-share-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 20px;
        font-size: 0.8rem;
        font-weight: 600;
        border-radius: 12px;
        text-decoration: none;
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.1);
        background: #fff;
        color: var(--text-dark);
    }
    
    .news-share-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    }
    
    .news-share-btn.facebook { color: #1877f2; border-color: #1877f2; }
    .news-share-btn.twitter { color: #1da1f2; border-color: #1da1f2; }
    .news-share-btn.whatsapp { color: #25d366; border-color: #25d366; }
    
    .news-share-btn svg {
        width: 16px;
        height: 16px;
    }
    
    /* Related News */
    .news-detail-related {
        margin-top: 3rem;
        padding: 2.5rem;
        background: var(--bg-light);
        border-top: 1px solid rgba(0,0,0,0.05);
    }
    
    .news-detail-related-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 1.5rem;
        text-align: center;
    }
    
    .news-related-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        max-width: 1100px;
        margin: 0 auto;
    }
    
    .news-related-card {
        background: #fff;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        border: 1px solid rgba(0,0,0,0.04);
        transition: all 0.4s ease;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
    }
    
    .news-related-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.12);
    }
    
    .news-related-image {
        width: 100%;
        height: 160px;
        object-fit: cover;
        display: block;
        background: var(--bg-light);
    }
    
    .news-related-image-placeholder {
        width: 100%;
        height: 160px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, rgba(19,140,127,0.1), rgba(19,140,127,0.05));
        color: var(--news-color);
    }
    
    .news-related-image-placeholder svg {
        width: 48px;
        height: 48px;
        opacity: 0.5;
    }
    
    .news-related-body {
        padding: 1.25rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    .news-related-category {
        display: inline-block;
        background: rgba(19, 140, 127, 0.1);
        color: var(--news-color);
        font-size: 0.72rem;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
        margin-bottom: 0.75rem;
        width: fit-content;
    }
    
    .news-related-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--text-dark);
        line-height: 1.4;
        margin-bottom: 0.5rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .news-related-excerpt {
        font-size: 0.82rem;
        color: var(--text-muted);
        line-height: 1.6;
        margin-bottom: 0.75rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex: 1;
    }
    
    .news-related-date {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 0.7rem;
        color: var(--text-muted);
        margin-bottom: 0.75rem;
    }
    
    .news-related-date svg {
        width: 12px;
        height: 12px;
    }
    
    .news-related-link {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        color: var(--news-color);
        font-size: 0.8rem;
        font-weight: 600;
        text-decoration: none;
        transition: gap 0.3s ease;
        margin-top: auto;
    }
    
    .news-related-link:hover {
        gap: 8px;
    }
    
    .news-related-link svg {
        width: 14px;
        height: 14px;
        transition: transform 0.3s ease;
    }
    
    .news-related-link:hover svg {
        transform: translateX(4px);
    }

    /* ==========================================
       ALIGNMENT SUPPORT (CKEditor)
       ========================================== */
    
    /* Support inline style dari CKEditor */
    .news-detail-content [style*="text-align: center"],
    .news-detail-content .text-center {
        text-align: center !important;
    }
    
    .news-detail-content [style*="text-align: right"],
    .news-detail-content .text-right {
        text-align: right !important;
    }
    
    .news-detail-content [style*="text-align: left"],
    .news-detail-content .text-left {
        text-align: left !important;
    }
    
    .news-detail-content [style*="text-align: justify"],
    .news-detail-content .text-justify {
        text-align: justify !important;
    }
    
    /* Fix list markers agar tidak terpotong */
    .news-detail-content ul,
    .news-detail-content ol {
        padding-left: 2rem !important;
        margin: 1rem 0 !important;
        list-style-position: outside !important;
    }
    
    .news-detail-content ul li,
    .news-detail-content ol li {
        margin: 0.5rem 0 !important;
        line-height: 1.8 !important;
        padding-left: 0.25rem !important;
    }
    
    /* Nested lists */
    .news-detail-content ul ul,
    .news-detail-content ol ol,
    .news-detail-content ul ol,
    .news-detail-content ol ul {
        padding-left: 2rem !important;
        margin: 0.5rem 0 !important;
    }
    
    /* Blockquote styling */
    .news-detail-content blockquote {
        padding: 1rem 1.5rem !important;
        margin: 1.5rem 0 !important;
        border-left: 4px solid var(--news-color) !important;
        background: rgba(19, 140, 127, 0.05) !important;
        font-style: italic !important;
        color: var(--text-secondary) !important;
    }
    
    /* Responsive */
    @media (max-width: 1024px) {
        .news-related-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .news-detail-image {
            height: 300px;
        }
    }
    
    @media (max-width: 768px) {
        .news-page-header {
            padding: 7rem 1.5rem 3rem;
        }
        
        .news-page-header h1 {
            font-size: 1.8rem;
        }
        
        .news-page-header p {
            font-size: 0.95rem;
        }
        
        .news-detail-wrapper {
            padding: 0;
        }
        
        .news-detail-article,
        .news-detail-related {
            padding: 1.5rem;
        }
        
        .news-detail-title {
            font-size: 1.4rem;
        }
        
        .news-detail-image {
            height: 240px;
        }
        
        .news-detail-excerpt {
            font-size: 1rem;
            padding: 1rem;
        }
        
        .news-related-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="news-detail-wrapper">
    <!-- Page Header - Like other pages -->
    <header class="news-page-header">
        <div class="news-page-header-content">
            <h1>Berita</h1>
            <p>Informasi terkini dari PKK Kabupaten Toba</p>
            <nav class="news-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('landing.home') }}">Beranda</a>
                <span>/</span>
                <a href="{{ url('/berita') }}">Berita</a>
                <span>/</span>
                <span class="current">{{ Str::limit($news->title, 40) }}</span>
            </nav>
        </div>
    </header>

    <!-- Article Container - NO SECOND BREADCRUMB -->
    <div class="news-detail-container">
        <div class="news-detail-article">
            <!-- Header -->
            <header>
                <h1 class="news-detail-title">{{ $news->title }}</h1>
                
                <div class="news-detail-meta">
                    @if($news->category)
                        <span class="news-detail-category">{{ $news->category }}</span>
                    @endif
                    <span class="news-detail-date">
                        <!-- Calendar Icon SVG -->
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        {{ $news->published_at?->format('d F Y') ?? $news->created_at->format('d F Y') }}
                    </span>
                </div>
            </header>

            <!-- Featured Image -->
            @if($news->image_path)
                <figure class="news-detail-image">
                    <img src="{{ asset('storage/' . $news->image_path) }}" 
                         alt="{{ $news->title }}"
                         onerror="this.src='{{ asset('assets/landing/images/berita/default.jpg') }}'">
                </figure>
            @endif

            <!-- Excerpt -->
            @if($news->excerpt)
                <p class="news-detail-excerpt">{{ $news->excerpt }}</p>
            @endif

            <!-- Content -->
            <div class="news-detail-content">
                {!! $news->content !!}
            </div>

            <!-- Share Buttons -->
            <div class="news-detail-share">
                <p class="news-detail-share-title">Bagikan Artikel Ini:</p>
                <div class="news-share-buttons">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                       target="_blank" 
                       class="news-share-btn facebook"
                       rel="noopener noreferrer">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                        Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($news->title) }}&url={{ urlencode(request()->url()) }}" 
                       target="_blank" 
                       class="news-share-btn twitter"
                       rel="noopener noreferrer">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/></svg>
                        Twitter
                    </a>
                    <a href="https://wa.me/?text={{ urlencode($news->title . ' ' . request()->url()) }}" 
                       target="_blank" 
                       class="news-share-btn whatsapp"
                       rel="noopener noreferrer">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/></svg>
                        WhatsApp
                    </a>
                </div>
            </div>
        </div>

        <!-- Related News -->
        @if(isset($relatedNews) && $relatedNews->count() > 0)
        <div class="news-detail-related">
            <h2 class="news-detail-related-title">Berita Terkait</h2>
            <div class="news-related-grid">
                @foreach($relatedNews as $item)
                    <a href="{{ route('news.show', $item->slug) }}" class="news-related-card" onclick="event.preventDefault(); window.location.href=this.href">
                        @if($item->image_path)
                            <img src="{{ asset('storage/' . $item->image_path) }}" 
                                 alt="{{ $item->title }}"
                                 class="news-related-image"
                                 onerror="this.src='{{ asset('assets/landing/images/berita/default.jpg') }}'">
                        @else
                            <div class="news-related-image-placeholder">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                                    <circle cx="8.5" cy="8.5" r="1.5"/>
                                    <path d="M21 15l-5-5L5 21"/>
                                </svg>
                            </div>
                        @endif
                        <div class="news-related-body">
                            @if($item->category)
                                <span class="news-related-category">{{ $item->category }}</span>
                            @endif
                            <h3 class="news-related-title">{{ Str::limit($item->title, 60) }}</h3>
                            @if($item->excerpt)
                                <p class="news-related-excerpt">{{ Str::limit($item->excerpt, 80) }}</p>
                            @endif
                            <span class="news-related-date">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:12px;height:12px">
                                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                                    <line x1="16" y1="2" x2="16" y2="6"/>
                                    <line x1="8" y1="2" x2="8" y2="6"/>
                                    <line x1="3" y1="10" x2="21" y2="10"/>
                                </svg>
                                {{ $item->published_at?->format('d M Y') ?? $item->created_at->format('d M Y') }}
                            </span>
                            <span class="news-related-link">
                                Baca Selengkapnya
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M5 12h14M12 5l7 7-7 7"/>
                                </svg>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Lazy load images in content
    document.addEventListener('DOMContentLoaded', function() {
        const contentImages = document.querySelectorAll('.news-detail-content img');
        contentImages.forEach(img => {
            img.setAttribute('loading', 'lazy');
        });
    });
    
    // Navigate to page using the existing navigation system
    function navigateToPage(pageName) {
        if (typeof window.showPage === 'function') {
            window.showPage(pageName);
        } else if (typeof showPage === 'function') {
            showPage(pageName);
        }
        // If SPA navigation not available, will follow normal link
    }
</script>
@endpush
@endsection