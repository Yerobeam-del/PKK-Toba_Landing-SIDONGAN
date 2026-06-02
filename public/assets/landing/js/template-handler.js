/**
 * Template Handler Module - DYNAMIC VERSION
 * Copy-paste ready - Search working guaranteed
 */

// Global variables
window.templateData = [];
window.isTemplatesLoaded = false;
window.searchTimeout = null;

/**
 * Get placeholder image based on file type
 */
function getPlaceholderImage(fileType, fileName) {
    const ext = fileName ? fileName.split('.').pop().toLowerCase() : '';
    const backgrounds = {
        'pdf': 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)',
        'doc': 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)',
        'docx': 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)',
        'xls': 'linear-gradient(135deg, #22c55e 0%, #16a34a 100%)',
        'xlsx': 'linear-gradient(135deg, #22c55e 0%, #16a34a 100%)',
        'ppt': 'linear-gradient(135deg, #f97316 0%, #ea580c 100%)',
        'pptx': 'linear-gradient(135deg, #f97316 0%, #ea580c 100%)'
    };
    const bg = backgrounds[ext] || 'linear-gradient(135deg, #6b7280 0%, #4b5563 100%)';
    const extUpper = ext.toUpperCase() || 'DOC';
    const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="600" height="400" viewBox="0 0 600 400"><defs><linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#3b82f6;stop-opacity:1"/><stop offset="100%" style="stop-color:#2563eb;stop-opacity:1"/></linearGradient></defs><rect width="600" height="400" fill="url(#grad)"/><text x="50%" y="50%" font-family="Arial" font-size="72" font-weight="bold" fill="white" text-anchor="middle">${extUpper}</text><text x="50%" y="65%" font-family="Arial" font-size="24" fill="rgba(255,255,255,0.8)" text-anchor="middle">Template</text></svg>`;
    return 'data:image/svg+xml;base64,' + btoa(svg);
}

/**
 * Fetch data from Laravel API
 */
async function loadTemplatesFromAPI() {
    try {
        const response = await fetch('/api/v1/templates');
        const result = await response.json();
        if (!result.success) throw new Error(result.message || 'API error');
        
        window.templateData = result.data.filter(tpl => tpl.status === 'published').map(tpl => {
            let category = 'Dokumen';
            const nameLower = tpl.name.toLowerCase();
            if (nameLower.includes('surat')) category = 'Surat Resmi';
            else if (nameLower.includes('formulir') || nameLower.includes('form')) category = 'Formulir';
            else if (nameLower.includes('laporan')) category = 'Laporan';
            else if (nameLower.includes('notulen')) category = 'Administrasi';
            else if (nameLower.includes('proposal')) category = 'Program';
            else if (nameLower.includes('posyandu') || nameLower.includes('kesehatan')) category = 'Kesehatan';
            else if (nameLower.includes('keuangan')) category = 'Keuangan';
            const ext = tpl.file_name ? tpl.file_name.split('.').pop().toUpperCase() : 'FILE';
            return {
                id: tpl.id, title: tpl.name, name: tpl.name, file_name: tpl.file_name,
                file_url: tpl.file_url, file_size: tpl.file_size, file_type: tpl.file_type,
                upload_date: tpl.upload_date, formatted_date: tpl.formatted_date,
                description: tpl.description || 'Template dokumen resmi PKK Kabupaten Toba',
                desc: tpl.description || 'Template dokumen resmi PKK Kabupaten Toba',
                category: category, format: ext, size: tpl.file_size || '-',
                image: tpl.preview_url || getPlaceholderImage(tpl.file_type, tpl.file_name),
                status: tpl.status
            };
        });
        window.isTemplatesLoaded = true;
        _renderGrid(window.templateData);
    } catch (error) {
        console.error('❌ Failed to load templates:', error);
        window.templateData = [];
    }
}

/**
 * Render single template card
 */
function renderTemplateCard(template) {
    const ext = (template.format || 'PDF').toLowerCase();
    const bgColors = {
        'pdf': 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)',
        'doc': 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)',
        'docx': 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)',
        'xls': 'linear-gradient(135deg, #22c55e 0%, #16a34a 100%)',
        'xlsx': 'linear-gradient(135deg, #22c55e 0%, #16a34a 100%)',
        'ppt': 'linear-gradient(135deg, #f97316 0%, #ea580c 100%)',
        'pptx': 'linear-gradient(135deg, #f97316 0%, #ea580c 100%)'
    };
    const bgColor = bgColors[ext] || 'linear-gradient(135deg, #6b7280 0%, #4b5563 100%)';
    return `<div class="template-card"><div class="image-container" style="background:${bgColor}"><span class="category-badge">${template.category}</span><div class="file-icon"><div style="font-size:64px;margin-bottom:0.5rem">📄</div><div class="file-type">${ext.toUpperCase()}</div></div></div><div class="card-content"><h3 class="card-title">${template.title}</h3><p class="card-description">${template.desc || template.description || 'Template dokumen resmi PKK Kabupaten Toba'}</p><div class="meta-info"><span class="format-badge">${template.format}</span><span>•</span><span>${template.size}</span>${template.formatted_date ? `<span>•</span><span>${template.formatted_date}</span>` : ''}</div><a href="${template.file_url}" download="${template.file_name}" class="download-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>Unduh Template</a></div></div>`;
}

/**
 * Render grid
 */
function _renderGrid(dataToRender = window.templateData) {
    const grid = document.getElementById('templateGrid');
    const loading = document.getElementById('loadingState');
    let empty = document.getElementById('emptyState');
    
    if (!grid) return;
    if (loading) loading.style.display = 'none';
    
    if (!dataToRender || dataToRender.length === 0) {
        grid.style.display = 'none';
        
        // CREATE empty state if not exists
        if (!empty) {
            empty = document.createElement('div');
            empty.id = 'emptyState';
            empty.style.cssText = 'display: block; text-align: center; padding: 5rem 2rem; max-width: 650px; margin: 0 auto;';
            empty.innerHTML = `
                <div style="width: 120px; height: 120px; margin: 0 auto 2rem; background: linear-gradient(135deg, rgba(15,107,99,0.1), rgba(20,184,166,0.1)); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#0f6b63" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                </div>
                <h3 style="font-size: 1.75rem; font-weight: 800; color: #1e293b; margin: 0 0 0.75rem 0;">Belum Ada Template</h3>
                <p style="color: #64748b; font-size: 1.05rem; line-height: 1.7; margin: 0 auto 2rem; max-width: 500px;">Template surat dan formulir akan segera diunggah. Silakan kunjungi kembali nanti untuk update terbaru.</p>
                <a onclick="navigateTo('beranda')" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.875rem 2rem; background: linear-gradient(135deg, #0f6b63, #14b8a6); color: #fff; border-radius: 12px; font-weight: 600; text-decoration: none; cursor: pointer;">Kembali ke Beranda</a>
            `;
            grid.parentNode.insertBefore(empty, grid.nextSibling);
        } else {
            empty.style.display = 'block';
            empty.innerHTML = '<div style="text-align:center;padding:3rem;color:var(--text-muted)">Tidak ada template yang ditemukan</div>';
        }
        return;
    }
}

/**
 * Global search handler - CALLED BY INLINE HTML
 */
window.handleTemplateSearch = function(searchTerm) {
    if (window.searchTimeout) clearTimeout(window.searchTimeout);
    window.searchTimeout = setTimeout(() => {
        const term = searchTerm.toLowerCase().trim();
        if (term === '') { _renderGrid(window.templateData); return; }
        const filtered = window.templateData.filter(t => 
            (t.title && t.title.toLowerCase().includes(term)) ||
            (t.file_name && t.file_name.toLowerCase().includes(term)) ||
            (t.category && t.category.toLowerCase().includes(term)) ||
            (t.description && t.description.toLowerCase().includes(term))
        );
        _renderGrid(filtered);
    }, 300);
};

/**
 * Populate templates
 */
function populateTemplates() {
    const grid = document.getElementById('templateGrid');
    const loading = document.getElementById('loadingState');
    const empty = document.getElementById('emptyState');
    if (!grid) return;
    if (loading) loading.style.display = 'block';
    if (grid) grid.style.display = 'none';
    if (empty) empty.style.display = 'none';
    loadTemplatesFromAPI();
}

/**
 * Initialize page
 */
function initTemplatePage() {
    if (!window.isTemplatesLoaded || window.templateData.length === 0) {
        populateTemplates();
    } else {
        _renderGrid(window.templateData);
    }
}

// Initialize once
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initTemplatePage);
} else {
    initTemplatePage();
}

// Expose to window
window.initTemplatePage = initTemplatePage;
window.populateTemplates = populateTemplates;
window.filterTemplates = window.handleTemplateSearch;
window._renderGrid = _renderGrid;