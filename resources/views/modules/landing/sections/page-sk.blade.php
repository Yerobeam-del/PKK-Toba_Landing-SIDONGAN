<div class="page" id="page-sk" style="display: none;">
    <div class="page-header" style="background: linear-gradient(135deg, var(--primary), var(--primary-light));">
        <div class="page-header-content">
            <h1>SK & Dokumen</h1>
            <p>Surat Keputusan dan dokumen resmi PKK Kabupaten Toba</p>
            <div class="breadcrumb">
                <a onclick="navigateTo('beranda')">Beranda</a><span>/</span><span class="current">SK & Dokumen</span>
            </div>
        </div>
    </div>

    <section class="sk-section" style="padding: 4rem 2rem; background: var(--bg-light);">
        <div class="sk-container" style="max-width: 1000px; margin: 0 auto;">
            <div style="margin-bottom: 2rem;">
                <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--text-dark); margin-bottom: 1rem;">Daftar Dokumen</h2>
                <div style="display: flex; gap: 1rem; align-items: center;">
                    <div style="flex: 1; position: relative;">
                        <input type="text" id="searchInput" placeholder="Cari dokumen..." style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.5rem; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 0.95rem;">
                        <svg style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); width: 18px; height: 18px; color: var(--text-muted);" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="m21 21-4.35-4.35"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Loading State --}}
            <div id="loadingState" style="text-align: center; padding: 3rem;">
                <div style="font-size: 1.2rem; color: var(--text-muted);">Memuat dokumen...</div>
            </div>

            {{-- Documents Table --}}
            <div id="documentsTable" style="display: none; background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.06);">
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
                        <thead style="background: linear-gradient(135deg, #553c9a, #6b46c1); color: #fff;">
                            <tr>
                                <th style="padding: 1rem 1.5rem; text-align: left; font-size: 0.85rem; font-weight: 600; width: 60px;">No</th>
                                <th style="padding: 1rem 1.5rem; text-align: left; font-size: 0.85rem; font-weight: 600; width: 40%;">Nama Dokumen</th>
                                <th style="padding: 1rem 1.5rem; text-align: left; font-size: 0.85rem; font-weight: 600; width: 120px;">Tanggal</th>
                                <th style="padding: 1rem 1.5rem; text-align: left; font-size: 0.85rem; font-weight: 600; width: 100px;">Ukuran</th>
                                <th style="padding: 1rem 1.5rem; text-align: center; font-size: 0.85rem; font-weight: 600; position: sticky; right: 0; background: linear-gradient(135deg, #553c9a, #6b46c1); z-index: 10; width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="documentsBody"></tbody>
                    </table>
                </div>
            </div>

            {{-- Empty State (Consistent with Aplikasi & Berita) --}}
            <div id="emptyState" style="display: none; text-align: center; padding: 5rem 2rem; max-width: 650px; margin: 0 auto;">
                
                <!-- Icon Circle (Hijau Teal) -->
                <div style="width: 120px; height: 120px; margin: 0 auto 2rem; background: linear-gradient(135deg, rgba(15,107,99,0.1), rgba(20,184,166,0.1)); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#0f6b63" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.8;">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                        <polyline points="10 9 9 9 8 9"/>
                    </svg>
                </div>
                
                <h3 style="font-size: 1.75rem; font-weight: 800; color: #1e293b; margin: 0 0 0.75rem 0;">
                    Belum Ada Dokumen
                </h3>
                
                <p style="color: #64748b; font-size: 1.05rem; line-height: 1.7; margin: 0 auto 2rem; max-width: 500px;">
                    Dokumen SK dan surat resmi akan segera diunggah. 
                    Silakan kunjungi kembali nanti untuk update terbaru.
                </p>
                
                <!-- Tombol (Hijau Teal) -->
                <a onclick="navigateTo('beranda')" 
                   style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.875rem 2rem; background: linear-gradient(135deg, #0f6b63, #14b8a6); color: #fff; border-radius: 12px; font-weight: 600; text-decoration: none; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 15px rgba(15,107,99,0.3);"
                   onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(15,107,99,0.4)'"
                   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(15,107,99,0.3)'">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
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
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(term) ? '' : 'none';
            });
        });
    }
});
</script>