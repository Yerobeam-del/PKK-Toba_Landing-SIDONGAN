<section class="apps-home-section" id="aplikasiSection" style="padding: 4rem 2rem; background: #f8fafc;">
    <style>
        /* Grid Layout */
        .apps-home-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem; max-width: 1200px; margin: 0 auto;
        }
        /* Card Styles */
        .app-card-home {
            background: #fff; border-radius: 20px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease; text-decoration: none; color: inherit; display: flex; flex-direction: column; height: 100%;
        }
        .app-card-home:hover { transform: translateY(-8px); box-shadow: 0 12px 40px rgba(0,0,0,0.12); }
        .app-card-header {
            padding: 2rem; background: linear-gradient(135deg, rgba(20,184,166,0.1), rgba(13,148,136,0.05));
            display: flex; justify-content: center;
        }
        .app-icon-wrapper {
            width: 80px; height: 80px; border-radius: 16px; overflow: hidden; background: #f1f5f9;
            border: 1px solid #cbd5e1; box-shadow: 0 4px 12px rgba(0,0,0,0.1); display: flex; align-items: center; justify-content: center;
        }
        .app-icon-wrapper img { width: 100%; height: 100%; object-fit: contain; padding: 10px; }
        .app-card-body { padding: 1.5rem 2rem; flex: 1; }
        .app-name { font-size: 1.5rem; font-weight: 800; color: #0f766e; margin: 0 0 0.25rem 0; }
        .app-fullname { font-size: 0.95rem; color: #64748b; margin: 0 0 1rem 0; font-weight: 500; }
        .app-description { color: #64748b; line-height: 1.7; margin: 0 0 1.5rem 0; font-size: 0.95rem; }
        .app-features { list-style: none; padding: 0; margin: 0; }
        .app-features li { display: flex; align-items: flex-start; gap: 0.75rem; color: #334155; font-size: 0.9rem; margin-bottom: 0.75rem; }
        .app-features li svg { width: 20px; height: 20px; color: #10b981; flex-shrink: 0; margin-top: 2px; }
        .app-card-footer {
            padding: 1.5rem 2rem; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between;
            align-items: center; background: #fafafa;
        }
        .app-btn {
            display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1.25rem; background: #0d9488;
            color: #fff; border-radius: 10px; font-weight: 600; font-size: 0.9rem; transition: all 0.3s;
        }
        .app-btn:hover { background: #0f766e; transform: translateX(3px); }
        .app-status { display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; color: #10b981; font-weight: 600; }
        .app-status-dot { width: 8px; height: 8px; background: #10b981; border-radius: 50%; animation: pulse 2s infinite; }
        
        .apps-loading { text-align: center; padding: 3rem; color: #64748b; font-size: 1.1rem; grid-column: 1 / -1; }
        .btn-see-all {
            display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 2rem; background: #0d9488;
            color: #fff; border-radius: 10px; font-weight: 600; text-decoration: none; cursor: pointer; transition: all 0.3s;
        }
        .btn-see-all:hover { background: #0f766e; transform: translateY(-2px); }

        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }

        @media (max-width: 768px) {
            .apps-home-section .section-title { font-size: 2rem; }
            .apps-home-grid { grid-template-columns: 1fr; }
            .app-card-body { padding: 1.25rem 1.5rem; }
        }
    </style>

    {{-- ✅ SECTION HEADER - DIPERBAIKI AGAR 100% RATA TENGAH --}}
    <div style="text-align: center; margin-bottom: 3rem; max-width: 800px; margin-left: auto; margin-right: auto;">
        <h2 style="font-size: 2.5rem; font-weight: 800; color: #0f766e; margin: 0 0 0.5rem 0; line-height: 1.2; text-align: center;">
            Sistem & Aplikasi Digital
        </h2>
        <p style="color: #64748b; font-size: 1.05rem; line-height: 1.8; margin: 0; text-align: center;">
            Akses layanan digital PKK Kabupaten Toba melalui aplikasi-aplikasi yang telah kami sediakan.
        </p>
    </div>

    {{-- Loading State --}}
    <div id="apps-home-loading" style="text-align: center; padding: 3rem; color: #64748b; font-size: 1.1rem; grid-column: 1 / -1;">
        Memuat data aplikasi...
    </div>

    {{-- Content Grid --}}
    <div class="apps-home-grid" id="apps-home-grid" style="display: none;"></div>

</section>

<script>
let appsHomeLoaded = false;

function buildHomeImageUrl(iconPath) {
    if (!iconPath) return null;
    return '/storage/' + iconPath.replace(/^(storage\/|public\/|app\/public\/)/i, '');
}

async function loadAppsHomeData() {
    if (appsHomeLoaded) return;
    
    const loadingEl = document.getElementById('apps-home-loading');
    const gridEl = document.getElementById('apps-home-grid');
    
    try {
        const response = await fetch('/api/v1/applications');
        const result = await response.json();
        
        if (!result.success) throw new Error(result.message);
        
        const activeApps = result.data.active || [];
        
        loadingEl.style.display = 'none';
        gridEl.style.display = 'grid';
        
        gridEl.innerHTML = activeApps.map((app, index) => {
            const colors = ['sieda', 'SIDONGAN', 'app3', 'app4'];
            const cardClass = colors[index % colors.length] || 'sieda';
            const iconUrl = buildHomeImageUrl(app.icon);
            const features = Array.isArray(app.features) ? app.features.slice(0, 5) : [];
            const featuresHtml = features.map(f => `<li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>${f}</li>`).join('');
            
            return `
            <a href="${app.url && app.url !== '#' ? app.url : '#'}" target="_blank" class="app-card-home ${cardClass}" ${!app.url || app.url === '#' ? 'style="pointer-events:none;opacity:0.7"' : ''}>
                <div class="app-card-header">
                    <div class="app-icon-wrapper">
                        ${iconUrl ? `<img src="${iconUrl}" alt="${app.short_name}" onerror="this.style.display='none'">` : '<div style="width:40px;height:40px;background:#e2e8f0;border-radius:8px;"></div>'}
                    </div>
                </div>
                <div class="app-card-body">
                    <h3 class="app-name">${app.short_name || app.name}</h3>
                    <p class="app-fullname">${app.name}</p>
                    <p class="app-description">${app.description || 'Sistem informasi digital terpadu PKK Kabupaten Toba.'}</p>
                    <ul class="app-features">${featuresHtml}</ul>
                </div>
                <div class="app-card-footer">
                    <span class="app-btn">${app.url && app.url !== '#' ? `Akses ${app.short_name || 'Aplikasi'}` : 'Akses Terbatas'}<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span>
                    <div class="app-status"><div class="app-status-dot"></div>Aktif</div>
                </div>
            </a>`;
        }).join('');

        // Handle Empty State
        if (activeApps.length === 0) {
            gridEl.innerHTML = `
            <div style="grid-column: 1 / -1; text-align: center; padding: 4rem 2rem; background: linear-gradient(135deg, rgba(39,103,73,0.05), rgba(56,161,105,0.05)); border-radius: 20px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                <div style="width: 80px; height: 80px; margin: 0 auto 1.5rem; background: linear-gradient(135deg, rgba(39,103,73,0.1), rgba(56,161,105,0.1)); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#276749" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
                    </svg>
                </div>
                <h3 style="font-size: 1.5rem; font-weight: 800; color: #1e293b; margin: 0 0 0.5rem 0; text-align: center;">Belum Ada Aplikasi</h3>
                <p style="color: #64748b; font-size: 0.95rem; margin: 0 0 1.5rem 0; text-align: center; max-width: 400px;">Tim kami sedang mempersiapkan aplikasi terbaru untuk Anda.</p>
            </div>`;
        }
        
        appsHomeLoaded = true;
        
    } catch (error) {
        console.error('Error:', error);
        loadingEl.innerHTML = '<p style="color:red">Gagal memuat aplikasi.</p>';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const section = document.getElementById('aplikasiSection');
    if (section && 'IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !appsHomeLoaded) {
                    loadAppsHomeData();
                    observer.disconnect();
                }
            });
        }, { threshold: 0.1 });
        observer.observe(section);
    } else {
        loadAppsHomeData();
    }
});
</script>