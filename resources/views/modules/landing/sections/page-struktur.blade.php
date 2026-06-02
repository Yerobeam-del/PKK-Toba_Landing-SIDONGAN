<div class="page" id="page-struktur" style="display: none;">
    <div class="page-header" style="background: linear-gradient(135deg, var(--primary), var(--primary-light));">
        <div class="page-header-content">
            <h1>Struktur Organisasi</h1>
            <p>Tim Penggerak PKK Kabupaten Toba</p>
            <div class="breadcrumb">
                <a onclick="navigateTo('beranda')">Beranda</a><span>/</span><span class="current">Struktur Organisasi</span>
            </div>
        </div>
    </div>
    
    <section class="struktur-section">
        <div class="struktur-wrapper">
            <div class="struktur-scroll">
                <div class="struktur-tree">
                    <!-- ROW 1: Ketua Pembina, Ketua TP PKK, Staff Ahli -->
                    <div class="top3-tree" id="top3Tree">
                        <div class="org-card-wrapper" id="wrapper-ketua-pembina">
                            <div class="top-v-line"></div>
                            <div class="org-card highlight">
                                <div class="org-avatar">
                                    <img id="img-ketua-pembina" src="" alt="Ketua Pembina" style="width:100%;height:100%;object-fit:cover;" onerror="this.style.display='none'">
                                    <div class="avatar-placeholder" id="placeholder-ketua-pembina" style="display:none; width:100%;height:100%; background:linear-gradient(135deg,#cbd5e1,#94a3b8); display:flex;align-items:center;justify-content:center;color:#fff;">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                            <circle cx="12" cy="7" r="4"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="org-position">Ketua Pembina</div>
                                <div class="org-name" id="name-ketua-pembina">Data belum diisi</div>
                            </div>
                        </div>
                        
                        <div class="org-card-wrapper" id="wrapper-ketua-pkk">
                            <div class="top-v-line"></div>
                            <div class="org-card highlight ketua-center-card">
                                <div class="org-avatar org-avatar-lg">
                                    <img id="img-ketua-pkk" src="" alt="Ketua PKK" style="width:100%;height:100%;object-fit:cover;" onerror="this.style.display='none'">
                                    <div class="avatar-placeholder" id="placeholder-ketua-pkk" style="display:none; width:100%;height:100%; background:linear-gradient(135deg,#cbd5e1,#94a3b8); display:flex;align-items:center;justify-content:center;color:#fff;">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                            <circle cx="12" cy="7" r="4"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="org-position" style="font-size:0.7rem;">Ketua TP PKK</div>
                                <div class="org-name" id="name-ketua-pkk" style="font-size:0.78rem;">Data belum diisi</div>
                            </div>
                        </div>
                        
                        <div class="org-card-wrapper" id="wrapper-staf-ahli">
                            <div class="top-v-line"></div>
                            <div class="org-card highlight staff-combined-card">
                                <div class="org-position">Staf Ahli</div>
                                <div class="staff-duo-grid">
                                    <div class="staff-duo-item" id="staf-ahli-1">
                                        <div class="staff-duo-avatar">
                                            <img id="img-staf-1" src="" alt="Staf Ahli 1" onerror="this.style.display='none'">
                                            <div class="avatar-placeholder" id="placeholder-staf-1" style="display:none; width:100%;height:100%; background:linear-gradient(135deg,#cbd5e1,#94a3b8); display:flex;align-items:center;justify-content:center;color:#fff;">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                                    <circle cx="12" cy="7" r="4"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="staff-duo-role">Staf Ahli 1</div>
                                        <div class="staff-duo-name" id="name-staf-1">Data belum diisi</div>
                                    </div>
                                    <div class="staff-duo-item" id="staf-ahli-2">
                                        <div class="staff-duo-avatar">
                                            <img id="img-staf-2" src="" alt="Staf Ahli 2" onerror="this.style.display='none'">
                                            <div class="avatar-placeholder" id="placeholder-staf-2" style="display:none; width:100%;height:100%; background:linear-gradient(135deg,#cbd5e1,#94a3b8); display:flex;align-items:center;justify-content:center;color:#fff;">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                                    <circle cx="12" cy="7" r="4"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="staff-duo-role">Staf Ahli 2</div>
                                        <div class="staff-duo-name" id="name-staf-2">Data belum diisi</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- ROW 2: Sekretaris & Bendahara -->
                    <div class="struktur-row" id="row-sekben">
                        <div class="org-card-wrapper">
                            <div class="org-card chair">
                                <div class="org-avatar">
                                    <img id="img-sekretaris" src="" alt="Sekretaris" style="width:100%;height:100%;object-fit:cover;" onerror="this.style.display='none'">
                                    <div class="avatar-placeholder" id="placeholder-sekretaris" style="display:none; width:100%;height:100%; background:linear-gradient(135deg,#cbd5e1,#94a3b8); display:flex;align-items:center;justify-content:center;color:#fff;">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                            <circle cx="12" cy="7" r="4"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="org-position">Sekretaris</div>
                                <div class="org-name" id="name-sekretaris">Data belum diisi</div>
                            </div>
                        </div>
                        <div class="org-card-wrapper">
                            <div class="org-card chair">
                                <div class="org-avatar">
                                    <img id="img-bendahara" src="" alt="Bendahara" style="width:100%;height:100%;object-fit:cover;" onerror="this.style.display='none'">
                                    <div class="avatar-placeholder" id="placeholder-bendahara" style="display:none; width:100%;height:100%; background:linear-gradient(135deg,#cbd5e1,#94a3b8); display:flex;align-items:center;justify-content:center;color:#fff;">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                            <circle cx="12" cy="7" r="4"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="org-position">Bendahara</div>
                                <div class="org-name" id="name-bendahara">Data belum diisi</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="struktur-row-gap" aria-hidden="true"></div>
                    
                    <!-- ROW 3: Ketua Pokja -->
                    <div class="struktur-row" id="row-pokja">
                        <div class="org-card-wrapper">
                            <div class="org-card" style="background:linear-gradient(135deg,#234e52,#285e61);color:#fff;border:none;">
                                <div class="org-avatar">
                                    <img id="img-pokja-1" src="" alt="Ketua I" style="width:100%;height:100%;object-fit:cover;" onerror="this.style.display='none'">
                                    <div class="avatar-placeholder" id="placeholder-pokja-1" style="display:none; width:100%;height:100%; background:linear-gradient(135deg,#4a5568,#2d3748); display:flex;align-items:center;justify-content:center;color:#fff;">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                            <circle cx="12" cy="7" r="4"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="org-position" style="color:#f6e05e;">Ketua I</div>
                                <div class="org-name" id="name-pokja-1" style="color:#fff;">Data belum diisi</div>
                            </div>
                        </div>
                        <div class="org-card-wrapper">
                            <div class="org-card" style="background:linear-gradient(135deg,#234e52,#285e61);color:#fff;border:none;">
                                <div class="org-avatar">
                                    <img id="img-pokja-2" src="" alt="Ketua II" style="width:100%;height:100%;object-fit:cover;" onerror="this.style.display='none'">
                                    <div class="avatar-placeholder" id="placeholder-pokja-2" style="display:none; width:100%;height:100%; background:linear-gradient(135deg,#4a5568,#2d3748); display:flex;align-items:center;justify-content:center;color:#fff;">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                            <circle cx="12" cy="7" r="4"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="org-position" style="color:#f6e05e;">Ketua II</div>
                                <div class="org-name" id="name-pokja-2" style="color:#fff;">Data belum diisi</div>
                            </div>
                        </div>
                        <div class="org-card-wrapper">
                            <div class="org-card" style="background:linear-gradient(135deg,#234e52,#285e61);color:#fff;border:none;">
                                <div class="org-avatar">
                                    <img id="img-pokja-3" src="" alt="Ketua III" style="width:100%;height:100%;object-fit:cover;" onerror="this.style.display='none'">
                                    <div class="avatar-placeholder" id="placeholder-pokja-3" style="display:none; width:100%;height:100%; background:linear-gradient(135deg,#4a5568,#2d3748); display:flex;align-items:center;justify-content:center;color:#fff;">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                            <circle cx="12" cy="7" r="4"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="org-position" style="color:#f6e05e;">Ketua III</div>
                                <div class="org-name" id="name-pokja-3" style="color:#fff;">Data belum diisi</div>
                            </div>
                        </div>
                        <div class="org-card-wrapper">
                            <div class="org-card" style="background:linear-gradient(135deg,#234e52,#285e61);color:#fff;border:none;">
                                <div class="org-avatar">
                                    <img id="img-pokja-4" src="" alt="Ketua IV" style="width:100%;height:100%;object-fit:cover;" onerror="this.style.display='none'">
                                    <div class="avatar-placeholder" id="placeholder-pokja-4" style="display:none; width:100%;height:100%; background:linear-gradient(135deg,#4a5568,#2d3748); display:flex;align-items:center;justify-content:center;color:#fff;">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                            <circle cx="12" cy="7" r="4"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="org-position" style="color:#f6e05e;">Ketua IV</div>
                                <div class="org-name" id="name-pokja-4" style="color:#fff;">Data belum diisi</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- POKJA SECTIONS (Static Structure, Dynamic Content) -->
            <div style="max-width:1300px;margin:0 auto;">
                @php
                    $pokjaConfig = [
                        ['id'=>'pokja1', 'num'=>'I', 'title'=>'Pokja I — Penghayatan & Pengamalan Pancasila', 'sub'=>'Kegiatan penghayatan dan pengamalan Pancasila dalam kehidupan keluarga', 'color'=>'p1'],
                        ['id'=>'pokja2', 'num'=>'II', 'title'=>'Pokja II — Gotong Royong', 'sub'=>'Kegiatan gotong royong dan kegotongroyongan masyarakat', 'color'=>'p2'],
                        ['id'=>'pokja3', 'num'=>'III', 'title'=>'Pokja III — Pangan, Sandang, Perumahan & Tatalaksana Rumah Tangga', 'sub'=>'Program pangan, sandang, perumahan dan tatalaksana rumah tangga', 'color'=>'p3'],
                        ['id'=>'pokja4', 'num'=>'IV', 'title'=>'Pokja IV — Kehidupan Berkoperasi, Ketrampilan & Pendidikan', 'sub'=>'Program koperasi, ketrampilan dan pendidikan keluarga', 'color'=>'p4']
                    ];
                @endphp

                @foreach($pokjaConfig as $pokja)
                <div class="pokja-section {{ $pokja['color'] }}">
                    <div class="pokja-header" onclick="togglePokja('{{ $pokja['id'] }}')">
                        <div class="pokja-number {{ $pokja['color'] }}">{{ $pokja['num'] }}</div>
                        <div>
                            <div class="pokja-title">{{ $pokja['title'] }}</div>
                            <div class="pokja-subtitle">{{ $pokja['sub'] }}</div>
                        </div>
                        <div class="toggle-icon" id="icon-{{ $pokja['id'] }}">
                            <svg viewBox="0 0 24 24"><path d="M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6z"/></svg>
                        </div>
                    </div>
                    <div class="pokja-content" id="{{ $pokja['id'] }}">
                        <div class="pokja-members" id="members-{{ $pokja['id'] }}">
                            <div class="member-card"><div class="org-position">Ketua</div><div class="org-name">Data belum diisi</div></div>
                            <div class="member-card"><div class="org-position">Wakil Ketua</div><div class="org-name">Data belum diisi</div></div>
                            <div class="member-card"><div class="org-position">Sekretaris</div><div class="org-name">Data belum diisi</div></div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
</div>

<script>
console.log('Struktur script loaded (Static Layout Mode)');
let strukturDataLoaded = false;

function forceRedrawConnectors() {
    window.dispatchEvent(new Event('resize'));
    if (typeof drawTreeConnectors === 'function') {
        setTimeout(() => drawTreeConnectors(), 100);
    }
}

async function loadStrukturData() {
    if (strukturDataLoaded) return;
    console.log('Fetching struktur data...');
    
    try {
        const response = await fetch('/api/v1/struktur');
        if (!response.ok) throw new Error('HTTP ' + response.status);
        
        const result = await response.json();
        if (!result.success) throw new Error(result.message);
        
        const { pengurus_inti, pokja } = result.data;
        populateStrukturDOM(pengurus_inti || [], pokja || []);
        
        setTimeout(() => {
            forceRedrawConnectors();
        }, 150);
        
        strukturDataLoaded = true;
        console.log('Struktur populated successfully');
    } catch (error) {
        console.error('Failed to load struktur:', error);
    }
}

function populateStrukturDOM(pengurus, pokjaList) {
    const setCard = (nameId, imgId, placeholderId, data) => {
        const nameEl = document.getElementById(nameId);
        const imgEl = document.getElementById(imgId);
        const phEl = document.getElementById(placeholderId);
        
        if (data && data.name) {
            nameEl.textContent = data.name;
            if (data.photo) {
                imgEl.src = data.photo;
                imgEl.style.display = 'block';
                phEl.style.display = 'none';
            } else {
                imgEl.style.display = 'none';
                phEl.style.display = 'flex';
            }
        }
    };

    const findPos = (arr, pos) => arr.find(p => p.position === pos);

    // Pengurus Inti (Row 1 & 2)
    setCard('name-ketua-pembina', 'img-ketua-pembina', 'placeholder-ketua-pembina', findPos(pengurus, 'Ketua Pembina'));
    setCard('name-ketua-pkk', 'img-ketua-pkk', 'placeholder-ketua-pkk', findPos(pengurus, 'Ketua TP PKK'));
    setCard('name-sekretaris', 'img-sekretaris', 'placeholder-sekretaris', findPos(pengurus, 'Sekretaris'));
    setCard('name-bendahara', 'img-bendahara', 'placeholder-bendahara', findPos(pengurus, 'Bendahara'));
    
    // Staf Ahli
    const staf = pengurus.filter(p => p.position === 'Staf Ahli');
    setCard('name-staf-1', 'img-staf-1', 'placeholder-staf-1', staf[0] || null);
    setCard('name-staf-2', 'img-staf-2', 'placeholder-staf-2', staf[1] || null);

    // Ketua I, II, III, IV
    setCard('name-pokja-1', 'img-pokja-1', 'placeholder-pokja-1', findPos(pengurus, 'Ketua I'));
    setCard('name-pokja-2', 'img-pokja-2', 'placeholder-pokja-2', findPos(pengurus, 'Ketua II'));
    setCard('name-pokja-3', 'img-pokja-3', 'placeholder-pokja-3', findPos(pengurus, 'Ketua III'));
    setCard('name-pokja-4', 'img-pokja-4', 'placeholder-pokja-4', findPos(pengurus, 'Ketua IV'));

    // Anggota Pokja Sections
    const pokjaIds = ['pokja1', 'pokja2', 'pokja3', 'pokja4'];
    const positionHierarchy = ['Ketua', 'Wakil Ketua', 'Sekretaris', 'Sekretaris Pokja', 'Anggota'];
    
    pokjaIds.forEach((id, idx) => {
        const container = document.getElementById('members-' + id);
        if (!container) return;
        
        const members = pokjaList[idx]?.members || [];

        members.sort((a, b) => {
            const orderA = positionHierarchy.indexOf(a.position);
            const orderB = positionHierarchy.indexOf(b.position);
            return (orderA === -1 ? 99 : orderA) - (orderB === -1 ? 99 : orderB);
        });
        
        if (members.length === 0) return;
        
        container.innerHTML = members.map(m => {
            const pos = m.position.toLowerCase();
            const isStruktural = pos.includes('ketua') || pos.includes('wakil') || pos.includes('sekretaris');
            const roleClass = isStruktural ? (pos.includes('ketua') ? 'pos-ketua' : (pos.includes('wakil') ? 'pos-wakil' : 'pos-sekretaris')) : 'pos-anggota';
            
            const initials = m.name ? m.name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase() : '';
            
            let avatarContent = '';
            if (m.photo) {
                avatarContent = '<img src="' + m.photo + '" style="width:100%;height:100%;object-fit:cover;" onerror="this.style.display=\'none\'; this.nextElementSibling.style.display=\'flex\';">';
                avatarContent += '<div class="avatar-placeholder" style="display:none; width:100%;height:100%; background:linear-gradient(135deg,#cbd5e1,#94a3b8); display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;">' + initials + '</div>';
            } else {
                avatarContent = '<div class="avatar-placeholder" style="width:100%;height:100%; background:linear-gradient(135deg,#cbd5e1,#94a3b8); display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;">' + (initials || '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>') + '</div>';
            }
            
            return '<div class="member-card ' + roleClass + '">' +
                '<div class="org-avatar">' + avatarContent + '</div>' +
                '<div class="org-position">' + m.position + '</div>' +
                '<div class="org-name">' + m.name + '</div>' +
            '</div>';
        }).join('');
    });
}

function togglePokja(pokjaId) {
    const content = document.getElementById(pokjaId);
    const icon = document.getElementById('icon-' + pokjaId);
    if (content && icon) {
        const isHidden = content.style.display === 'none' || !content.style.display;
        content.style.display = isHidden ? 'block' : 'none';
        icon.style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0deg)';
    }
}

// Auto-load when struktur tab becomes active
document.addEventListener('DOMContentLoaded', () => {
    const observer = new MutationObserver(() => {
        const page = document.getElementById('page-struktur');
        if (page && page.classList.contains('active') && !strukturDataLoaded) {
            setTimeout(() => loadStrukturData(), 100);
            observer.disconnect();
        }
    });
    observer.observe(document.body, { childList: true, subtree: true });
});
</script>