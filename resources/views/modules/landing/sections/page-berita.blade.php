<div class="page" id="page-berita" style="display: none;">
    <div class="page-header" style="background: linear-gradient(135deg, var(--primary), var(--primary-light));">
        <div class="page-header-content">
            <h1>Berita & Kegiatan</h1>
            <p>Informasi terbaru seputar kegiatan dan program PKK Kabupaten Toba</p>
            <div class="breadcrumb">
                <a onclick="navigateTo('beranda')">Beranda</a><span>/</span><span class="current">Berita</span>
            </div>
        </div>
    </div>

    <section class="news-full-section" style="padding: 4rem 2rem; min-height: 60vh; background: #f8fafc;">
        
        {{-- Loading State --}}
        <div id="news-loading" style="text-align: center; padding: 5rem 2rem;">
            <div style="font-size: 1.2rem; color: #64748b; font-weight: 500;">Memuat berita terbaru...</div>
        </div>

        {{-- News Grid --}}
        <div class="news-full-grid" id="newsFullGrid" style="display: none;"></div>

        {{-- Beautiful Empty State --}}
        <div id="news-empty-state" style="display: none; text-align: center; padding: 5rem 2rem; max-width: 650px; margin: 0 auto;">
            <div style="width: 120px; height: 120px; margin: 0 auto 2rem; background: linear-gradient(135deg, rgba(15,107,99,0.1), rgba(20,184,166,0.1)); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#0f6b63" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.8;">
                    <path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/>
                    <path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8V6Z"/>
                </svg>
            </div>
            <h3 style="font-size: 1.75rem; font-weight: 800; color: #1e293b; margin: 0 0 0.75rem 0;">Belum Ada Berita Terbaru</h3>
            <p style="color: #64748b; font-size: 1.05rem; line-height: 1.7; margin: 0 auto 2rem; max-width: 500px;">Tim kami sedang mempersiapkan informasi terkini seputar kegiatan dan program PKK Kabupaten Toba. Silakan kunjungi kembali nanti untuk update terbaru.</p>
            <a onclick="navigateTo('beranda')" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.875rem 2rem; background: linear-gradient(135deg, #0f6b63, #14b8a6); color: #fff; border-radius: 12px; font-weight: 600; text-decoration: none; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 15px rgba(15,107,99,0.3);" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(15,107,99,0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(15,107,99,0.3)'">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                Kembali ke Beranda
            </a>
        </div>
    </section>
</div>

<script>
let beritaDataLoaded = false;

// ==========================================
// LOAD BERITA DATA - DENGAN REDIRECT
// ==========================================
async function loadBeritaData() {
    if (beritaDataLoaded) return;
    
    const loadingEl = document.getElementById('news-loading');
    const gridEl = document.getElementById('newsFullGrid');
    const emptyEl = document.getElementById('news-empty-state');
    
    try {
        const response = await fetch('/api/v1/news');
        const result = await response.json();
        
        loadingEl.style.display = 'none';
        
        if (result.success && result.data && result.data.length > 0) {
            gridEl.style.display = 'grid';
            
            gridEl.innerHTML = result.data.map(news => {
                // Gunakan slug jika ada, fallback ke id
                const newsUrl = news.slug ? `/berita/${news.slug}` : `/berita/${news.id}`;
                
                return `
                <a href="${newsUrl}" style="text-decoration: none; color: inherit; display: block;">
                    <article class="news-card" 
                        style="background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 4px 15px rgba(0,0,0,0.06); transition:all 0.3s;" 
                        onmouseover="this.style.transform='translateY(-5px)';this.style.boxShadow='0 8px 25px rgba(0,0,0,0.1)'" 
                        onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 15px rgba(0,0,0,0.06)'">
                        
                        <div style="position:relative; overflow:hidden;">
                            <img src="${news.image || '/assets/landing/images/berita/default.jpg'}" 
                                 alt="${news.title}" 
                                 style="width:100%; height:200px; object-fit:cover; display:block;"
                                 onerror="this.src='/assets/landing/images/berita/default.jpg'">
                        </div>
                        
                        <div style="padding:1.5rem;">
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.75rem;">
                                <span style="background:rgba(39,103,73,0.1); color:#276749; padding:0.25rem 0.75rem; border-radius:50px; font-size:0.75rem; font-weight:600;">${news.category || 'Umum'}</span>
                                <span style="color:#94a3b8; font-size:0.8rem;">${news.date || news.created_at}</span>
                            </div>
                            
                            <h3 style="font-size:1.15rem; font-weight:700; color:#1e293b; margin:0 0 0.5rem 0; line-height:1.4;">${news.title}</h3>
                            
                            <p style="color:#64748b; font-size:0.9rem; line-height:1.6; margin:0 0 1rem 0; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">${news.excerpt || news.content}</p>
                            
                            <span style="color:#276749; font-weight:600; font-size:0.9rem; display:inline-flex; align-items:center; gap:0.5rem;">
                                Baca Selengkapnya 
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="transition:transform 0.3s;">
                                    <path d="M5 12h14M12 5l7 7-7 7"/>
                                </svg>
                            </span>
                        </div>
                    </article>
                </a>
                `;
            }).join('');
            
        } else {
            emptyEl.style.display = 'block';
        }
        
        beritaDataLoaded = true;
    } catch (error) {
        console.error('❌ Error loading berita:', error);
        loadingEl.innerHTML = '<p style="color:#ef4444; font-weight:500;">Gagal memuat berita. Silakan refresh halaman.</p>';
    }
}

// Observer untuk load data saat halaman aktif
document.addEventListener('DOMContentLoaded', () => {
    const observer = new MutationObserver(() => {
        const page = document.getElementById('page-berita');
        if (page && page.classList.contains('active') && !beritaDataLoaded) {
            setTimeout(() => loadBeritaData(), 100);
            observer.disconnect();
        }
    });
    observer.observe(document.body, { childList: true, subtree: true });
    
    // Load immediately if already active
    const page = document.getElementById('page-berita');
    if (page && page.classList.contains('active')) {
        setTimeout(() => loadBeritaData(), 100);
    }
});
</script>