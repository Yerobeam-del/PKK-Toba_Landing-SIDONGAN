/**
 * News Handler - Dynamic Content from API
 * Fetches news data from Laravel API endpoint
 */

// Base API URL
const API_BASE = '/api/v1';

/**
 * Fetch news from API
 * @param {Object} params - Query parameters
 * @returns {Promise<Array>}
 */
async function fetchNews(params = {}) {
    try {
        const queryParams = new URLSearchParams({
            limit: params.limit || 10,
            category: params.category || '',
            ...params
        });
        
        const response = await fetch(`${API_BASE}/news?${queryParams}`);
        const result = await response.json();
        
        if (result.success) {
            return result.data;
        }
        throw new Error('Failed to fetch news');
    } catch (error) {
        console.error('Error fetching news:', error);
        // Fallback to window.landingNewsData if API fails
        return window.landingNewsData || [];
    }
}

/**
 * Render news card HTML
 * @param {Object} news - News object
 * @param {Number} index - Index for modal reference
 * @returns {String}
 */
function renderNewsCard(news, index) {
    const imageUrl = news.image_path 
        ? `/storage/${news.image_path}` 
        : '/assets/landing/images/berita/default.jpg';
    
    const publishedDate = news.published_at 
        ? new Date(news.published_at).toLocaleDateString('id-ID', { 
            day: '2-digit', month: 'short', year: 'numeric' 
        })
        : new Date(news.created_at).toLocaleDateString('id-ID', { 
            day: '2-digit', month: 'short', year: 'numeric' 
        });
    
    return `
    <div class="news-card" onclick="openNewsModalBySlug('${news.slug}')">
        <img src="${imageUrl}" 
             alt="${news.title}" 
             class="news-card-image"
             onerror="this.src='/assets/landing/images/berita/default.jpg'">
        <div class="news-card-body">
            <div class="news-card-date">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                ${publishedDate}
            </div>
            <span class="news-card-category">${news.category}</span>
            <h3 class="news-card-title">${escapeHtml(news.title)}</h3>
            <p class="news-card-excerpt">${escapeHtml(news.excerpt)}</p>
            <span class="news-card-link">Baca Selengkapnya
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </span>
        </div>
    </div>`;
}

/**
 * Escape HTML to prevent XSS
 * @param {String} text 
 * @returns {String}
 */
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

/**
 * Populate news grid (home page - 3 items)
 */
async function populateNewsHome() {
    const grid = document.getElementById('newsHomeGrid');
    if (!grid) return;
    
    // Show loading state
    grid.innerHTML = '<div class="col-span-3 text-center py-4"><div class="animate-pulse">Memuat berita...</div></div>';
    
    try {
        const news = await fetchNews({ limit: 3 });
        
        if (news.length === 0) {
            grid.innerHTML = '<div class="col-span-3 text-center py-8 text-muted"><p>Belum ada berita terbaru.</p></div>';
            return;
        }
        
        grid.innerHTML = news.map((n, i) => renderNewsCard(n, i)).join('');
    } catch (error) {
        console.error('Error populating news:', error);
        grid.innerHTML = '<div class="col-span-3 text-center py-8 text-muted"><p>Gagal memuat berita.</p></div>';
    }
}

/**
 * Populate news grid (full page - all items)
 */
async function populateNewsFull() {
    const grid = document.getElementById('newsFullGrid');
    if (!grid) return;
    
    grid.innerHTML = '<div class="col-span-3 text-center py-4"><div class="animate-pulse">Memuat berita...</div></div>';
    
    try {
        const news = await fetchNews({ limit: 20 });
        
        if (news.length === 0) {
            grid.innerHTML = '<div class="col-span-3 text-center py-8 text-muted"><p>Belum ada berita terbaru.</p></div>';
            return;
        }
        
        grid.innerHTML = news.map((n, i) => renderNewsCard(n, i)).join('');
    } catch (error) {
        console.error('Error populating full news:', error);
        grid.innerHTML = '<div class="col-span-3 text-center py-8 text-muted"><p>Gagal memuat berita.</p></div>';
    }
}

/**
 * Open news modal by fetching single news by slug
 * @param {String} slug 
 */
async function openNewsModalBySlug(slug) {
    try {
        const response = await fetch(`${API_BASE}/news/${slug}`);
        const result = await response.json();
        
        if (result.success) {
            const news = result.data;
            const imageUrl = news.image_path 
                ? `/storage/${news.image_path}` 
                : '/assets/landing/images/berita/default.jpg';
            
            document.getElementById('newsModalImage').src = imageUrl;
            document.getElementById('newsModalDate').innerHTML = `
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                </svg> 
                ${new Date(news.published_at || news.created_at).toLocaleDateString('id-ID', { 
                    day: '2-digit', month: 'long', year: 'numeric' 
                })}
            `;
            document.getElementById('newsModalCategory').textContent = news.category;
            document.getElementById('newsModalTitle').textContent = news.title;
            document.getElementById('newsModalContent').textContent = news.content;
            document.getElementById('newsModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    } catch (error) {
        console.error('Error opening news modal:', error);
        alert('Gagal memuat detail berita.');
    }
}

/**
 * Close news modal
 */
function closeNewsModal() {
    document.getElementById('newsModal')?.classList.remove('active');
    document.body.style.overflow = '';
}

// Event listeners
document.addEventListener('DOMContentLoaded', () => {
    populateNewsHome();
    
    // Modal close handlers
    document.getElementById('newsModal')?.addEventListener('click', function(e) { 
        if (e.target === this) closeNewsModal(); 
    });
    document.addEventListener('keydown', (e) => { 
        if (e.key === 'Escape') closeNewsModal(); 
    });
});

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { 
        fetchNews, 
        renderNewsCard, 
        populateNewsHome, 
        populateNewsFull, 
        openNewsModalBySlug, 
        closeNewsModal 
    };
}