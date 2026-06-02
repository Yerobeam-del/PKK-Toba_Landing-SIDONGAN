<div class="page" id="page-template" style="display: none;">
    <div class="page-header" style="background: linear-gradient(135deg, var(--primary), var(--primary-light));">
        <div class="page-header-content">
            <h1>Template PKK</h1>
            <p>Template surat dan formulir yang dapat dicetak untuk keperluan PKK</p>
            <div class="breadcrumb">
                <a onclick="navigateTo('beranda')">Beranda</a><span>/</span><span class="current">Template</span>
            </div>
        </div>
    </div>

    <section class="sk-section" style="padding: 4rem 2rem; background: var(--bg-light);">
        <div class="sk-container" style="max-width: 1000px; margin: 0 auto;">
            <div style="margin-bottom: 2rem;">
                <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--text-dark); margin-bottom: 1rem;">Daftar Template</h2>
                <div style="display: flex; gap: 1rem; align-items: center;">
                    <div style="flex: 1; position: relative;">
                        <input type="text" id="searchInput" placeholder="Cari template..." style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.5rem; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 0.95rem;">
                        <svg style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); width: 18px; height: 18px; color: var(--text-muted);" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="m21 21-4.35-4.35"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Loading State --}}
            <div id="loadingState" style="text-align: center; padding: 3rem;">
                <div style="font-size: 1.2rem; color: var(--text-muted);">Memuat template...</div>
            </div>

            {{-- Templates Table --}}
            <div id="templatesTable" style="display: none; background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.06);">
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
                        <thead style="background: linear-gradient(135deg, var(--template-color), #0f6b63); color: #fff;">
                            <tr>
                                <th style="padding: 1rem 1.5rem; text-align: left; font-size: 0.85rem; font-weight: 600; width: 60px;">No</th>
                                <th style="padding: 1rem 1.5rem; text-align: left; font-size: 0.85rem; font-weight: 600; width: 40%;">Nama Template</th>
                                <th style="padding: 1rem 1.5rem; text-align: left; font-size: 0.85rem; font-weight: 600; width: 100px;">Format</th>
                                <th style="padding: 1rem 1.5rem; text-align: left; font-size: 0.85rem; font-weight: 600; width: 120px;">Tanggal</th>
                                <th style="padding: 1rem 1.5rem; text-align: left; font-size: 0.85rem; font-weight: 600; width: 100px;">Ukuran</th>
                                <th style="padding: 1rem 1.5rem; text-align: center; font-size: 0.85rem; font-weight: 600; position: sticky; right: 0; background: linear-gradient(135deg, var(--template-color), #0f6b63); z-index: 10; width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="templatesBody"></tbody>
                    </table>
                </div>
            </div>

            {{-- Empty State - TAMBAHKAN INI! --}}
            <div id="emptyState" style="display: none; text-align: center; padding: 5rem 2rem; max-width: 650px; margin: 0 auto;">
                <div style="width: 120px; height: 120px; margin: 0 auto 2rem; background: linear-gradient(135deg, rgba(15,107,99,0.1), rgba(20,184,166,0.1)); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#0f6b63" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.8;">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                        <polyline points="10 9 9 9 8 9"/>
                    </svg>
                </div>
                <h3 style="font-size: 1.75rem; font-weight: 800; color: #1e293b; margin: 0 0 0.75rem 0;">Belum Ada Template</h3>
                <p style="color: #64748b; font-size: 1.05rem; line-height: 1.7; margin: 0 auto 2rem; max-width: 500px;">Template surat dan formulir akan segera diunggah. Silakan kunjungi kembali nanti untuk update terbaru.</p>
                <a onclick="navigateTo('beranda')" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.875rem 2rem; background: linear-gradient(135deg, #0f6b63, #14b8a6); color: #fff; border-radius: 12px; font-weight: 600; text-decoration: none; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 15px rgba(15,107,99,0.3);" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(15,107,99,0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(15,107,99,0.3)'">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </section>
</div>

<script>
window.allTemplates = [];

async function loadTemplates() {
    console.log('[TEMPLATE] loadTemplates() STARTED');
    
    const loadingEl = document.getElementById('loadingState');
    const tableEl = document.getElementById('templatesTable');
    const tbodyEl = document.getElementById('templatesBody');
    
    if (!loadingEl) {
        console.error('[TEMPLATE] ERROR: loadingState element NOT FOUND!');
        return;
    }
    
    console.log('[TEMPLATE] Elements found, hiding loading...');
    
    // FORCE hide loading
    loadingEl.style.display = 'none';
    loadingEl.innerHTML = '';
    
    // Also hide table
    if (tableEl) {
        tableEl.style.display = 'none';
    }
    
    // Remove any existing empty state
    const existingEmpty = document.getElementById('emptyState');
    if (existingEmpty) {
        console.log('[TEMPLATE] Removing existing empty state');
        existingEmpty.remove();
    }
    
    try {
        console.log('[TEMPLATE] Fetching API...');
        const response = await fetch('/api/v1/templates');
        const result = await response.json();
        
        console.log('[TEMPLATE] API Result:', result);
        
        if (!result.success) throw new Error(result.message);
        
        const templates = (result.data || []).filter(t => t.status === 'published');
        console.log('[TEMPLATE] Found', templates.length, 'templates');
        
        if (templates.length === 0) {
            console.log('[TEMPLATE] Creating empty state...');
            
            // CREATE empty state
            const emptyDiv = document.createElement('div');
            emptyDiv.id = 'emptyState';
            emptyDiv.style.cssText = 'text-align: center; padding: 5rem 2rem; max-width: 650px; margin: 0 auto; animation: fadeIn 0.5s ease;';
            emptyDiv.innerHTML = `
                <div style="width: 120px; height: 120px; margin: 0 auto 2rem; background: linear-gradient(135deg, rgba(15,107,99,0.1), rgba(20,184,166,0.1)); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#0f6b63" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.8;">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                        <polyline points="10 9 9 9 8 9"/>
                    </svg>
                </div>
                <h3 style="font-size: 1.75rem; font-weight: 800; color: #1e293b; margin: 0 0 0.75rem 0;">Belum Ada Template</h3>
                <p style="color: #64748b; font-size: 1.05rem; line-height: 1.7; margin: 0 auto 2rem; max-width: 500px;">Template surat dan formulir akan segera diunggah. Silakan kunjungi kembali nanti untuk update terbaru.</p>
                <a onclick="navigateTo('beranda')" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.875rem 2rem; background: linear-gradient(135deg, #0f6b63, #14b8a6); color: #fff; border-radius: 12px; font-weight: 600; text-decoration: none; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 15px rgba(15,107,99,0.3);" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(15,107,99,0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(15,107,99,0.3)'">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                    Kembali ke Beranda
                </a>
            `;
            
            // INSERT after search container
            const searchContainer = document.querySelector('.sk-container');
            if (searchContainer) {
                searchContainer.appendChild(emptyDiv);
                console.log('[TEMPLATE] Empty state inserted and displayed!');
            } else {
                console.error('[TEMPLATE] Could not find container to insert empty state');
            }
            
            return;
        }
        
        // HAS data - show table
        console.log('[TEMPLATE] Rendering table...');
        if (tableEl && tbodyEl) {
            tableEl.style.display = 'block';
            window.allTemplates = templates;
            
            tbodyEl.innerHTML = templates.map((tpl, i) => {
                const ext = tpl.file_name ? tpl.file_name.split('.').pop().toUpperCase() : 'FILE';
                const date = tpl.formatted_date || new Date(tpl.upload_date).toLocaleDateString('id-ID', { day:'numeric', month:'short', year:'numeric' });
                const size = tpl.file_size || '-';
                
                return '<tr style="border-bottom:1px solid #e2e8f0" onmouseover="this.style.background=\'#f8fafc\'" onmouseout="this.style.background=\'\'">' +
                    '<td style="padding:1rem 1.5rem;color:#64748b">' + (i + 1) + '</td>' +
                    '<td style="padding:1rem 1.5rem;font-weight:600;color:#1e293b">' + tpl.name + '</td>' +
                    '<td style="padding:1rem 1.5rem"><span style="background:#f1f5f9;padding:0.25rem 0.75rem;border-radius:6px;font-size:0.75rem;font-weight:600;color:#475569">' + ext + '</span></td>' +
                    '<td style="padding:1rem 1.5rem;color:#64748b">' + date + '</td>' +
                    '<td style="padding:1rem 1.5rem;color:#64748b">' + size + '</td>' +
                    '<td style="padding:1rem 1.5rem;text-align:center"><a href="' + tpl.file_url + '" download="' + tpl.file_name + '" style="display:inline-flex;align-items:center;gap:0.35rem;padding:0.5rem 1rem;background:linear-gradient(135deg,var(--template-color),#0f6b63);color:#fff;border-radius:8px;font-size:0.8rem;font-weight:600;text-decoration:none" onmouseover="this.style.transform=\'translateY(-2px)\';this.style.boxShadow=\'0 4px 12px rgba(20,184,166,0.3)\'" onmouseout="this.style.transform=\'\';this.style.boxShadow=\'\'"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>Unduh</a></td>' +
                '</tr>';
            }).join('');
            
            console.log('[TEMPLATE] Table rendered successfully');
        }
    } catch (error) {
        console.error('[TEMPLATE] Error:', error);
        if (loadingEl) {
            loadingEl.style.display = 'block';
            loadingEl.innerHTML = '<div style="color:#ef4444;padding:2rem"><p style="font-weight:600">Gagal memuat template</p><button onclick="loadTemplates()" style="margin-top:1rem;padding:0.5rem 1.5rem;background:var(--primary);color:#fff;border:none;border-radius:8px;cursor:pointer">Coba Lagi</button></div>';
        }
    }
}

window.handleTemplateSearch = function(term) {
    document.querySelectorAll('#templatesBody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(term.toLowerCase()) ? '' : 'none';
    });
};

document.addEventListener('DOMContentLoaded', function() {
    console.log('[TEMPLATE] DOM ready');
    
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            window.handleTemplateSearch(e.target.value);
        });
    }
    
    const page = document.getElementById('page-template');
    if (page && page.classList.contains('active')) {
        console.log('[TEMPLATE] Page already active');
        setTimeout(loadTemplates, 100);
    }
    
    if (page) {
        const observer = new MutationObserver(() => {
            if (page.classList.contains('active')) {
                console.log('[TEMPLATE] Page activated');
                loadTemplates();
                observer.disconnect();
            }
        });
        observer.observe(page, { attributes: true, attributeFilter: ['class'] });
    }
});
</script>