/**
 * Navigation & SPA Router
 */

function navigateTo(pageId) {
    console.log('🔄 navigateTo:', pageId);
    
    // 1. Hide ALL pages
    document.querySelectorAll('.page').forEach(p => {
        p.classList.remove('active');
        p.style.display = 'none';
    });
    
    // 2. Show target page
    const targetPage = document.getElementById('page-' + pageId);
    if (targetPage) {
        targetPage.classList.add('active');
        targetPage.style.display = 'block';
        
        // Force browser to calculate layout
        void targetPage.offsetHeight;
        console.log('✅ Page shown:', pageId);
    } else {
        console.error('❌ Page not found:', pageId);
        return;
    }

    // 3. Update active nav link
    document.querySelectorAll('.nav-link').forEach(link => {
        link.classList.remove('active-link');
        if (link.getAttribute('data-page') === pageId) {
            link.classList.add('active-link');
        }
    });
    
    // 4. Close mobile menu
    const navLinks = document.getElementById('navLinks');
    const hamburger = document.getElementById('hamburger');
    if (navLinks) navLinks.classList.remove('active');
    if (hamburger) hamburger.classList.remove('active');
    
    // 5. Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
    
    // 6. Load dynamic content per page - Wait for DOM paint
    const loadPageData = () => {
        if (pageId === 'struktur' && typeof loadStrukturData === 'function') {
            console.log('🏗️ Loading struktur...');
            loadStrukturData();
        }
        if (pageId === 'aplikasi' && typeof loadAplikasiData === 'function') {
            console.log('📱 Loading aplikasi...');
            loadAplikasiData();
        }
        if (pageId === 'berita' && typeof populateNewsFull === 'function') {
            console.log('📰 Loading berita...');
            populateNewsFull();
        }
        if (pageId === 'desa' && typeof populateDesa === 'function') {
            console.log('🏘️ Loading desa...');
            populateDesa();
        }
        if (pageId === 'sk' && typeof loadSKDocuments === 'function') {
            console.log('📄 Loading SK...');
            loadSKDocuments();
        }
        if (pageId === 'template' && typeof populateTemplates === 'function') {
            console.log('📋 Loading template...');
            populateTemplates();
        }
        if (pageId === 'tentang' && typeof loadTentangKami === 'function') {
            console.log('ℹ️ Loading tentang kami...');
            loadTentangKami();
        }
    };

    // Ambil base URL (hapus hash jika ada)
    const baseUrl = window.location.href.split('#')[0];
    
    if (pageId === 'beranda') {
        // Untuk Beranda: URL bersih tanpa hash
        history.replaceState(null, null, baseUrl);
    } else {
        // Untuk section lain: tambahkan hash
        history.replaceState(null, null, baseUrl + '#' + pageId);
    }
    
    console.log('🔗 URL updated to:', window.location.href);

    // Wait for browser paint before loading data
    requestAnimationFrame(() => {
        requestAnimationFrame(loadPageData);
    });
    
    // 7. Close floating menu
    closeFloatingMenu();
}

function toggleMenu() {
    const navLinks = document.getElementById('navLinks');
    const hamburger = document.getElementById('hamburger');
    if (navLinks) navLinks.classList.toggle('active');
    if (hamburger) hamburger.classList.toggle('active');
}

/**
 * Close floating menu - ✅ DEFINED HERE
 */
function closeFloatingMenu() {
    const menu = document.getElementById('floatingMenu');
    const trigger = document.getElementById('floatingTrigger');
    if (menu) menu.classList.remove('open');
    if (trigger) trigger.classList.remove('open');
}

/**
 * Toggle floating menu
 */
function toggleFloatingMenu() {
    const menu = document.getElementById('floatingMenu');
    const trigger = document.getElementById('floatingTrigger');
    if (menu) menu.classList.toggle('open');
    if (trigger) trigger.classList.toggle('open');
}

// Click outside floating menu to close
document.addEventListener('click', function(e) {
    const floatingBtn = document.getElementById('floatingAppBtn');
    if (floatingBtn && !floatingBtn.contains(e.target)) {
        closeFloatingMenu();
    }
});

// Load SK Documents
function loadSKDocuments() {
    console.log('📄 loadSKDocuments() called');
    
    const loadingEl = document.getElementById('loadingState');
    const tableEl = document.getElementById('documentsTable');
    const emptyEl = document.getElementById('emptyState');
    const tbodyEl = document.getElementById('documentsBody');
    
    if (!tbodyEl) {
        console.error('❌ documentsBody not found!');
        return;
    }
    
    if (loadingEl) loadingEl.style.display = 'block';
    if (tableEl) tableEl.style.display = 'none';
    if (emptyEl) emptyEl.style.display = 'none';
    
    fetch('/api/v1/dokumens')
        .then(res => {
            console.log('📡 Response status:', res.status);
            if (!res.ok) throw new Error('HTTP ' + res.status);
            return res.json();
        })
        .then(result => {
            console.log('📦 API Result:', result);
            
            if (!result.success) throw new Error(result.message);
            
            const docs = (result.data || []).filter(d => d.status === 'published');
            console.log('✅ Found', docs.length, 'published documents');
            
            if (loadingEl) loadingEl.style.display = 'none';
            
            if (docs.length === 0) {
                if (tableEl) tableEl.style.display = 'none';
                if (emptyEl) emptyEl.style.display = 'block';
                return;
            }
            
            tbodyEl.innerHTML = docs.map((doc, i) => {
                return `<tr style="border-bottom:1px solid rgba(0,0,0,0.05);transition:background 0.3s" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                    <td style="padding:1rem 1.5rem;color:var(--text-muted);min-width:60px;width:60px">${i+1}</td>
                    <td style="padding:1rem 1.5rem;max-width:400px">
                        <div style="display:flex;align-items:center;gap:0.75rem">
                            <div style="width:36px;height:36px;border-radius:8px;background:rgba(85,60,154,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;color:var(--sk-color)">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14 2 14 8 20 8"/>
                                </svg>
                            </div>
                            <div style="min-width:0;flex:1;overflow:hidden">
                                <div style="font-weight:600;color:var(--text-dark);white-space:nowrap;overflow:hidden;text-overflow:ellipsis" title="${doc.name}">${doc.name}</div>
                                <div style="font-size:0.75rem;color:var(--text-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis" title="${doc.file_name}">${doc.file_name}</div>
                            </div>
                        </div>
                    </td>
                    <td style="padding:1rem 1.5rem;color:var(--text-muted);font-size:0.9rem;white-space:nowrap;min-width:120px">${doc.formatted_date || '-'}</td>
                    <td style="padding:1rem 1.5rem;white-space:nowrap;min-width:100px"><span style="background:#f1f5f9;padding:0.25rem 0.5rem;border-radius:4px;font-size:0.8rem;color:var(--text-muted)">${doc.file_size || '-'}</span></td>
                    <td style="padding:1rem 1.5rem;text-align:center;position:sticky;right:0;background:#fff;z-index:10;box-shadow:-2px 0 8px rgba(0,0,0,0.1);min-width:100px">
                        <div style="display:flex;gap:0.5rem;justify-content:center">
                            <a href="${doc.file_url}" target="_blank" style="width:32px;height:32px;border-radius:8px;background:rgba(20,184,166,0.1);color:var(--primary);display:flex;align-items:center;justify-content:center;transition:all 0.3s" onmouseover="this.style.background='var(--primary)';this.style.color='#fff'" onmouseout="this.style.background='rgba(20,184,166,0.1)';this.style.color='var(--primary)'" title="Preview">👁️</a>
                            <a href="${doc.file_url}" download="${doc.file_name}" style="width:32px;height:32px;border-radius:8px;background:rgba(85,60,154,0.1);color:var(--sk-color);display:flex;align-items:center;justify-content:center;transition:all 0.3s;text-decoration:none" onmouseover="this.style.background='var(--sk-color)';this.style.color='#fff'" onmouseout="this.style.background='rgba(85,60,154,0.1)';this.style.color='var(--sk-color)'" title="Download">⬇️</a>
                        </div>
                    </td>
                </tr>`;
            }).join('');
            
            if (tableEl) tableEl.style.display = 'block';
            console.log(' Table rendered successfully');
        })
        .catch(err => {
            console.error('❌ Error:', err);
            if (loadingEl) {
                loadingEl.innerHTML = `<div style="color:var(--danger);font-size:1.1rem"> Gagal memuat dokumen<br><small style="color:var(--text-muted)">${err.message}</small><button onclick="loadSKDocuments()" style="margin-top:1rem;padding:0.5rem 1.5rem;background:var(--primary);color:#fff;border:none;border-radius:8px;cursor:pointer">🔄 Coba Lagi</button></div>`;
            }
        });
}

// Di assets/landing/js/navigation.js
window.showPage = function(pageName) {
    // Hide all pages
    document.querySelectorAll('.page').forEach(page => {
        page.classList.remove('active');
    });
    
    // Show target page
    const targetPage = document.getElementById(pageName + 'Page');
    if (targetPage) {
        targetPage.classList.add('active');
        window.scrollTo(0, 0);
    }
    
    // Update active nav link
    document.querySelectorAll('.navbar-links a').forEach(link => {
        link.classList.remove('active-link');
    });
    
    const activeLink = document.querySelector(`.navbar-links a[data-page="${pageName}"]`);
    if (activeLink) {
        activeLink.classList.add('active-link');
    }
};

// ==========================================
// SPA INITIALIZATION & HASH ROUTING
// ==========================================
document.addEventListener('DOMContentLoaded', () => {
    console.log('📄 DOMContentLoaded | SPA Router Starting...');
    
    // 1. Baca hash dari URL (contoh: #template -> 'template')
    let targetPage = window.location.hash.replace('#', '');
    console.log('🔍 Hash detected:', targetPage || '(none)');
    
    // 2. Validasi apakah element halaman target benar-benar ada
    const isValidPage = targetPage && document.getElementById('page-' + targetPage);
    
    // 3. Fallback ke 'beranda' jika hash kosong atau element tidak ditemukan
    if (!isValidPage) {
        targetPage = 'beranda';
        console.log('️ Invalid/Empty hash. Defaulting to: beranda');
    }
    
    // 4. Tunggu sebentar agar semua fungsi (navigateTo, updateActiveNav) siap
    setTimeout(() => {
        if (typeof navigateTo === 'function') {
            console.log(' Triggering SPA navigation to:', targetPage);
            navigateTo(targetPage);
            
            if (typeof updateActiveNav === 'function') {
                updateActiveNav(targetPage);
            }
        } else {
            console.error('❌ navigateTo function not found! Check script load order.');
        }
    }, 50); // Delay 50ms untuk memastikan DOM & functions ready
    
    // 5. Load dynamic content per page (sesuai kode asli Anda)
    const loadPageData = () => {
        if (targetPage === 'struktur' && typeof loadStrukturData === 'function') loadStrukturData();
        if (targetPage === 'aplikasi' && typeof loadAplikasiData === 'function') loadAplikasiData();
        if (targetPage === 'berita' && typeof populateNewsFull === 'function') populateNewsFull();
        if (targetPage === 'desa' && typeof populateDesa === 'function') populateDesa();
        if (targetPage === 'sk' && typeof loadSKDocuments === 'function') loadSKDocuments();
        if (targetPage === 'template' && typeof populateTemplates === 'function') populateTemplates();
        if (targetPage === 'tentang' && typeof loadTentangKami === 'function') loadTentangKami();
    };

    requestAnimationFrame(() => requestAnimationFrame(loadPageData));
});