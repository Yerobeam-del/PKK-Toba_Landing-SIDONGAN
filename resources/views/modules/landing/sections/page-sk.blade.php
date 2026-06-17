<div class="page" id="page-sk">
    <div class="page-header">
        <div class="page-header-content">
            <h1>SK & Dokumen</h1>
            <p>Surat Keputusan dan dokumen resmi PKK Kabupaten Toba</p>
            <div class="breadcrumb">
                <a onclick="navigateTo('beranda')">Beranda</a>
                <span>/</span>
                <span class="current">SK & Dokumen</span>
            </div>
        </div>
    </div>

    <section class="sk-section">
        <div class="sk-container">
            {{-- Header Section --}}
            <div class="sk-header">
                <h2 class="sk-section-title">Daftar Dokumen</h2>
                <div class="sk-search-box">
                    <svg class="sk-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="m21 21-4.35-4.35"/>
                    </svg>
                    <input type="text" id="searchInput" class="sk-search-input" placeholder="Cari dokumen...">
                </div>
            </div>

            {{-- Loading State --}}
            <div id="loadingState" class="sk-loading">
                <div class="sk-loading-spinner"></div>
                <div class="sk-loading-text">Memuat dokumen...</div>
            </div>

            {{-- Documents Table - Desktop --}}
            <div id="documentsTable" class="sk-table-container">
                <table class="sk-table">
                    <thead>
                        <tr>
                            <th class="sk-col-no">No</th>
                            <th class="sk-col-name">
                                <svg class="sk-header-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14 2 14 8 20 8"/>
                                    <line x1="16" y1="13" x2="8" y2="13"/>
                                    <line x1="16" y1="17" x2="8" y2="17"/>
                                </svg>
                                Nama Dokumen
                            </th>
                            <th class="sk-col-date">
                                <svg class="sk-header-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                    <line x1="16" y1="2" x2="16" y2="6"/>
                                    <line x1="8" y1="2" x2="8" y2="6"/>
                                    <line x1="3" y1="10" x2="21" y2="10"/>
                                </svg>
                                Tanggal
                            </th>
                            <th class="sk-col-size">
                                <svg class="sk-header-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                    <polyline points="17 8 12 3 7 8"/>
                                    <line x1="12" y1="3" x2="12" y2="15"/>
                                </svg>
                                Ukuran
                            </th>
                            <th class="sk-col-action">
                                <svg class="sk-header-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="3"/>
                                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                                </svg>
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody id="documentsBody">
                        {{-- Documents will be loaded here --}}
                    </tbody>
                </table>
            </div>

            {{-- Mobile Cards View --}}
            <div id="mobileDocumentsList" class="sk-mobile-list">
                {{-- Mobile cards will be loaded here --}}
            </div>

            {{-- Empty State --}}
            <div id="emptyState" class="sk-empty-state">
                <div class="sk-empty-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                        <polyline points="10 9 9 9 8 9"/>
                    </svg>
                </div>
                <h3 class="sk-empty-title">Belum Ada Dokumen</h3>
                <p class="sk-empty-text">
                    Dokumen SK dan surat resmi akan segera diunggah. 
                    Silakan kunjungi kembali nanti untuk update terbaru.
                </p>
                <a onclick="navigateTo('beranda')" class="sk-back-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </section>
</div>

<script>
// Search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase().trim();
            const rows = document.querySelectorAll('#documentsBody tr');
            const mobileCards = document.querySelectorAll('.sk-mobile-card');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(term) ? '' : 'none';
            });
            
            mobileCards.forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = text.includes(term) ? '' : 'none';
            });
        });
    }
});

// Render documents function
function renderDocuments(documents) {
    const tableBody = document.getElementById('documentsBody');
    const mobileList = document.getElementById('mobileDocumentsList');
    const tableContainer = document.getElementById('documentsTable');
    const emptyState = document.getElementById('emptyState');
    const loadingState = document.getElementById('loadingState');
    
    loadingState.style.display = 'none';
    
    if (!documents || documents.length === 0) {
        tableContainer.style.display = 'none';
        mobileList.style.display = 'none';
        emptyState.style.display = 'block';
        return;
    }
    
    emptyState.style.display = 'none';
    tableContainer.style.display = 'block';
    mobileList.style.display = 'block';
    
    // Clear existing content
    tableBody.innerHTML = '';
    mobileList.innerHTML = '';
    
    documents.forEach((doc, index) => {
        // Desktop Table Row
        const row = document.createElement('tr');
        row.className = 'sk-table-row';
        row.innerHTML = `
            <td class="sk-cell sk-cell-no">${index + 1}</td>
            <td class="sk-cell sk-cell-name">
                <div class="sk-doc-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                </div>
                <div class="sk-doc-info">
                    <div class="sk-doc-name">${doc.name}</div>
                    <div class="sk-doc-file">${doc.file_name || ''}</div>
                </div>
            </td>
            <td class="sk-cell sk-cell-date">
                <svg class="sk-cell-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                ${doc.date}
            </td>
            <td class="sk-cell sk-cell-size">
                <svg class="sk-cell-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="17 8 12 3 7 8"/>
                    <line x1="12" y1="3" x2="12" y2="15"/>
                </svg>
                ${doc.size}
            </td>
            <td class="sk-cell sk-cell-action">
                <div class="sk-action-buttons">
                    <button class="sk-action-btn sk-view-btn" onclick="viewDocument('${doc.id}')" title="Lihat">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                    <button class="sk-action-btn sk-download-btn" onclick="downloadDocument('${doc.id}')" title="Unduh">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="7 10 12 15 17 10"/>
                            <line x1="12" y1="15" x2="12" y2="3"/>
                        </svg>
                    </button>
                </div>
            </td>
        `;
        tableBody.appendChild(row);
        
        // Mobile Card
        const mobileCard = document.createElement('div');
        mobileCard.className = 'sk-mobile-card';
        mobileCard.innerHTML = `
            <div class="sk-mobile-card-header">
                <div class="sk-mobile-doc-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                </div>
                <div class="sk-mobile-doc-info">
                    <div class="sk-mobile-doc-name">${doc.name}</div>
                    <div class="sk-mobile-doc-meta">
                        <span class="sk-mobile-doc-date">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/>
                            </svg>
                            ${doc.date}
                        </span>
                        <span class="sk-mobile-doc-size">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="17 8 12 3 7 8"/>
                            </svg>
                            ${doc.size}
                        </span>
                    </div>
                </div>
            </div>
            <div class="sk-mobile-card-actions">
                <button class="sk-mobile-action-btn sk-view-btn" onclick="viewDocument('${doc.id}')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                    <span>Lihat</span>
                </button>
                <button class="sk-mobile-action-btn sk-download-btn" onclick="downloadDocument('${doc.id}')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="7 10 12 15 17 10"/>
                        <line x1="12" y1="15" x2="12" y2="3"/>
                    </svg>
                    <span>Unduh</span>
                </button>
            </div>
        `;
        mobileList.appendChild(mobileCard);
    });
}
</script>