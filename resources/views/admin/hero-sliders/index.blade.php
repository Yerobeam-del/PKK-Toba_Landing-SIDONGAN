@extends('admin.layouts.app')
@section('title', 'Manajemen Hero Slider')
@section('page-title', 'Kelola Slider Beranda')

@section('content')
<div style="margin-bottom:2rem">

    {{-- Tambah Slide Baru --}}
    <div class="card" style="margin-bottom:2rem">
        <div style="padding:0 0 1rem 0;display:flex;align-items:center;gap:0.75rem">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#8b5cf6" stroke-width="2" style="flex-shrink:0"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            <h3 style="font-size:1.1rem;font-weight:700;color:#8b5cf6;margin:0">Tambah Slide Baru</h3>
        </div>
        <p style="color:var(--text-muted);margin:0 0 1.5rem 0;font-size:0.9rem;line-height:1.5">Upload gambar background untuk slider beranda. Teks konten tetap menggunakan desain yang sudah ada.</p>
        
        <form action="{{ route('admin.hero-sliders.store') }}" method="POST" enctype="multipart/form-data" style="display:grid;gap:1.5rem">
            @csrf
            
            <div style="grid-column:1/-1">
                <label style="font-weight:600;display:block;margin-bottom:0.5rem;font-size:0.9rem">Gambar Background <span style="color:var(--danger)">*</span></label>
                <input type="file" name="image" class="form-control" accept="image/*" required>
                <div style="display:flex;gap:1rem;margin-top:0.5rem;font-size:0.8rem;color:var(--text-muted);flex-wrap:wrap">
                    <span>Format: JPG, PNG, WebP</span><span>Maksimal: 5MB</span><span>Rekomendasi: 1920x1080px (16:9)</span>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr auto;gap:1rem;align-items:end">
                <div>
                    <label style="font-weight:600;display:block;margin-bottom:0.5rem;font-size:0.9rem">Durasi Tampil (detik)</label>
                    <input type="number" name="display_duration" class="form-control" value="5" min="3" max="30">
                </div>
                <div style="padding-bottom:0.25rem">
                    <label style="display:flex;align-items:center;gap:0.75rem;cursor:pointer;padding:0.75rem 1rem;background:#f8fafc;border-radius:10px;transition:all 0.2s;width:fit-content" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">
                        <input type="checkbox" name="is_active" id="isActive" value="1" checked style="display:none">
                        <div id="isActiveBox" style="width:22px;height:22px;border:2px solid #cbd5e1;border-radius:6px;background:#fff;transition:all 0.25s cubic-bezier(0.4, 0, 0.2, 1);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <svg id="isActiveCheck" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="opacity:1;transform:scale(1);transition:all 0.25s cubic-bezier(0.4, 0, 0.2, 1)">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                        </div>
                        <span style="font-weight:600;color:#334155;font-size:0.9rem;user-select:none">Aktif</span>
                    </label>
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;grid-column:1/-1">
                <button type="submit" class="btn btn-primary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Tambah Slide
                </button>
            </div>
        </form>
    </div>

    {{-- Daftar Slide --}}
    <div class="card">
        <div style="padding:0 0 1.5rem 0;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:0.75rem">
            <div style="display:flex;align-items:center;gap:0.75rem">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#14b8a6" stroke-width="2" style="flex-shrink:0"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                <h3 style="font-size:1.1rem;font-weight:700;color:#14b8a6;margin:0">Daftar Slide</h3>
            </div>
            <div style="display:flex;align-items:center;gap:1rem;font-size:0.85rem;flex-wrap:wrap">
                <small style="color:var(--text-muted);display:flex;align-items:center;gap:0.25rem">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="8" y1="6" x2="21" y2="6"/>
                        <line x1="8" y1="12" x2="21" y2="12"/>
                        <line x1="8" y1="18" x2="21" y2="18"/>
                    </svg>
                    Drag & drop untuk mengurutkan
                </small>
                <small style="color:#14b8a6;font-weight:500">
                    • Slide baru otomatis di urutan terakhir
                </small>
            </div>
        </div>
        
        <div id="slidersList">
            @forelse($sliders as $slider)
            <div class="slider-item" data-id="{{ $slider->id }}" style="display:flex;gap:1rem;padding:1rem;margin-bottom:1rem;background:#fff;border-radius:12px;cursor:grab;transition:all 0.2s" onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,0.06)'" onmouseout="this.style.boxShadow='none'" draggable="true">
                <div style="display:flex;align-items:center;color:var(--text-muted);padding:0 0.25rem">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/></svg>
                </div>
                <img src="{{ $slider->image_url }}" alt="Slide {{ $slider->id }}" style="width:100px;height:70px;object-fit:cover;border-radius:8px">
                <div style="flex:1;min-width:0">
                    <div style="font-weight:600;margin-bottom:0.25rem">Slide #{{ $slider->id }}</div>
                    <div style="font-size:0.85rem;color:var(--text-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $slider->image_path }}</div>
                    <div style="display:flex;align-items:center;gap:1rem;margin-top:0.5rem;font-size:0.8rem;color:var(--text-muted)">
                        <span>{{ $slider->display_duration }}s</span>
                        <span>Urutan: {{ $slider->sort_order }}</span>
                        @if($slider->is_active)<span style="color:#22c55e;font-weight:500">● Aktif</span>@else<span style="color:#ef4444;font-weight:500">● Nonaktif</span>@endif
                    </div>
                </div>
                <div style="display:flex;gap:0.5rem;align-items:center">
                    <a href="{{ $slider->image_url }}" target="_blank" class="btn-edit" title="Preview"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></a>
                    <button onclick="editSlider({{ $slider->id }})" class="btn-edit" title="Edit"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
                    <button onclick="confirmDeleteWithToast({{ $slider->id }}, 'Slide #{{ $slider->id }}')" class="btn-del" title="Hapus">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"/>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                            <line x1="10" y1="11" x2="10" y2="17"/>
                            <line x1="14" y1="11" x2="14" y2="17"/>
                        </svg>
                    </button>

                    {{-- Hidden form untuk delete --}}
                    <form id="delete-form-{{ $slider->id }}" action="{{ route('admin.hero-sliders.destroy', $slider) }}" method="POST" style="display:none">
                        @csrf 
                        @method('DELETE')
                    </form>
                </div>
            </div>
            @empty
            <div style="text-align:center;padding:3rem 1rem;color:var(--text-muted)">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin:0 auto 1rem;opacity:0.3">
                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                    <line x1="3" y1="9" x2="21" y2="9"/>
                    <line x1="9" y1="21" x2="9" y2="9"/>
                </svg>
                <p style="margin:0;font-size:0.95rem">Belum ada slide. Tambahkan slide pertama di atas.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div id="editModal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(15,23,42,0.6);backdrop-filter:blur(4px);z-index:1000;align-items:center;justify-content:center;padding:1rem">
    <div style="background:#fff;border-radius:16px;max-width:500px;width:100%;max-height:90vh;overflow-y:auto;box-shadow:0 25px 60px rgba(0,0,0,0.2);animation:modalSlideUp 0.3s ease">
        <div style="padding:1.5rem 1.5rem 1rem;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid #f1f5f9">
            <h3 style="margin:0;font-size:1.25rem;font-weight:700;color:#1e293b">Edit Slide</h3>
            <button onclick="closeEditModal()" style="background:none;border:none;cursor:pointer;color:#94a3b8;padding:0.5rem;border-radius:8px;transition:all 0.2s;display:flex;align-items:center;justify-content:center" onmouseover="this.style.background='#f1f5f9';this.style.color='#ef4444'" onmouseout="this.style.background='none';this.style.color='#94a3b8'">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <form id="editForm" method="POST" enctype="multipart/form-data" style="padding:1.5rem;display:grid;gap:1.25rem">
            @csrf
            @method('PUT')
            <input type="hidden" id="editId" name="id">
            
            <div>
                <label style="font-weight:600;display:block;margin-bottom:0.5rem;font-size:0.9rem;color:#334155">Gambar (kosongkan jika tidak diubah)</label>
                <input type="file" name="image" class="form-control" accept="image/*">
                <img id="editImagePreview" src="" style="max-width:100%;max-height:200px;margin-top:0.75rem;border-radius:10px;display:none;object-fit:cover;box-shadow:0 4px 12px rgba(0,0,0,0.1)">
            </div>
            
            <div>
                <label style="font-weight:600;display:block;margin-bottom:0.5rem;font-size:0.9rem;color:#334155">Durasi Tampil (detik)</label>
                <input type="number" name="display_duration" id="editDuration" class="form-control" min="3" max="30">
            </div>
            
            <div>
                <label style="display:flex;align-items:center;gap:0.75rem;cursor:pointer;padding:0.75rem 1rem;background:#f8fafc;border-radius:10px;transition:all 0.2s" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">
                    <input type="checkbox" name="is_active" id="editActive" value="1" style="display:none">
                    <div id="editActiveBox" style="width:22px;height:22px;border:2px solid #cbd5e1;border-radius:6px;background:#fff;transition:all 0.25s cubic-bezier(0.4, 0, 0.2, 1);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <svg id="editActiveCheck" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="opacity:0;transform:scale(0.5);transition:all 0.25s cubic-bezier(0.4, 0, 0.2, 1)">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                    <span style="font-weight:600;color:#334155;font-size:0.9rem;user-select:none">Aktif</span>
                </label>
            </div>
            
            <div style="display:flex;gap:0.75rem;justify-content:flex-end;margin-top:0.5rem;padding-top:1rem;border-top:1px solid #f1f5f9">
                <button type="button" onclick="closeEditModal()" style="padding:0.75rem 1.5rem;background:#f1f5f9;color:#475569;border:none;border-radius:10px;font-weight:600;font-size:0.9rem;cursor:pointer;transition:all 0.2s" onmouseover="this.style.background='#e2e8f0';this.style.transform='translateY(-2px)'" onmouseout="this.style.background='#f1f5f9';this.style.transform='translateY(0)'">Batal</button>
                <button type="submit" style="padding:0.75rem 1.5rem;background:linear-gradient(135deg,#14b8a6,#0d9488);color:#fff;border:none;border-radius:10px;font-weight:600;font-size:0.9rem;cursor:pointer;transition:all 0.2s;display:flex;align-items:center;gap:0.5rem;box-shadow:0 4px 12px rgba(20,184,166,0.3)" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 20px rgba(20,184,166,0.4)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 12px rgba(20,184,166,0.3)'">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/>
                        <polyline points="7 3 7 8 15 8"/>
                    </svg>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<style>
@keyframes modalSlideUp {
    from { opacity: 0; transform: translateY(20px) scale(0.95); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}
</style>

<script>
// ==========================================
// DELETE CONFIRMATION
// ==========================================
async function confirmDeleteWithToast(id, name) {
    try {
        if (typeof Toast !== 'undefined' && typeof Toast.confirm === 'function') {
            const confirmed = await Toast.confirm(
                `Slide <strong>"${name}"</strong> akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan.`,
                {
                    title: 'Hapus Slide?',
                    confirmText: 'Ya, Hapus',
                    cancelText: 'Batal',
                    type: 'danger'
                }
            );
            if (confirmed) submitDelete(id);
        } else {
            if (confirm(`Hapus slide "${name}"?`)) submitDelete(id);
        }
    } catch (error) {
        console.error('Error:', error);
        if (confirm(`Hapus slide "${name}"?`)) submitDelete(id);
    }
}

function submitDelete(id) {
    const form = document.getElementById('delete-form-' + id);
    if (form) form.submit();
}

// ==========================================
// EDIT SLIDER
// ==========================================
function editSlider(id) {
    const item = document.querySelector(`.slider-item[data-id="${id}"]`);
    if (!item) return;
    
    // Set form action dengan ID yang benar
    const form = document.getElementById('editForm');
    form.action = `/admin/hero-sliders/${id}`;
    
    document.getElementById('editId').value = id;
    
    const infoText = item.querySelector('div[style*="font-size:0.8rem"]').textContent;
    document.getElementById('editDuration').value = infoText.match(/(\d+)s/)?.[1] || '5';
    
    const isActive = infoText.includes('Aktif') && !infoText.includes('Nonaktif');
    document.getElementById('editActive').checked = isActive;
    updateCheckboxStyle('editActiveBox', 'editActiveCheck', isActive);
    
    const preview = document.getElementById('editImagePreview');
    preview.src = item.querySelector('img').src;
    preview.style.display = 'block';
    
    document.getElementById('editModal').style.display = 'flex';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
    document.getElementById('editImagePreview').style.display = 'none';
}

// ==========================================
// CHECKBOX ANIMATION
// ==========================================
function updateCheckboxStyle(boxId, checkId, isChecked) {
    const box = document.getElementById(boxId);
    const check = document.getElementById(checkId);
    if (!box || !check) return;
    
    if (isChecked) {
        box.style.background = 'linear-gradient(135deg, #14b8a6, #0d9488)';
        box.style.borderColor = '#14b8a6';
        box.style.boxShadow = '0 2px 8px rgba(20,184,166,0.3)';
        check.style.opacity = '1';
        check.style.transform = 'scale(1)';
    } else {
        box.style.background = '#fff';
        box.style.borderColor = '#cbd5e1';
        box.style.boxShadow = 'none';
        check.style.opacity = '0';
        check.style.transform = 'scale(0.5)';
    }
}

// ==========================================
// INITIALIZATION
// ==========================================
document.addEventListener('DOMContentLoaded', function() {
    // Checkbox form Tambah Slide
    const isActiveCheckbox = document.getElementById('isActive');
    if (isActiveCheckbox) {
        updateCheckboxStyle('isActiveBox', 'isActiveCheck', isActiveCheckbox.checked);
        isActiveCheckbox.addEventListener('change', function() {
            updateCheckboxStyle('isActiveBox', 'isActiveCheck', this.checked);
        });
    }
    
    // Checkbox modal Edit Slide
    const editActiveCheckbox = document.getElementById('editActive');
    if (editActiveCheckbox) {
        editActiveCheckbox.addEventListener('change', function() {
            updateCheckboxStyle('editActiveBox', 'editActiveCheck', this.checked);
        });
    }
    
    // Close modal on ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeEditModal();
    });
});

// ==========================================
// DRAG AND DROP
// ==========================================
let draggedItem = null;
const slidersList = document.getElementById('slidersList');

if (slidersList) {
    slidersList.addEventListener('dragstart', e => { 
        if(e.target.classList.contains('slider-item')) { 
            draggedItem = e.target; 
            setTimeout(() => e.target.style.opacity = '0.5', 0); 
        } 
    });

    slidersList.addEventListener('dragend', e => { 
        if(draggedItem) { 
            draggedItem.style.opacity = '1'; 
            updateOrder(); 
            draggedItem = null; 
        } 
    });

    slidersList.addEventListener('dragover', e => {
        e.preventDefault();
        const afterElement = [...slidersList.querySelectorAll('.slider-item:not(.dragging)')].reduce((closest, child) => {
            const box = child.getBoundingClientRect(); 
            const offset = e.clientY - box.top - box.height/2;
            return offset < 0 && offset > closest.offset ? { offset, element: child } : closest;
        }, { offset: Number.NEGATIVE_INFINITY }).element;
        afterElement == null ? slidersList.appendChild(draggedItem) : slidersList.insertBefore(draggedItem, afterElement);
    });
}

async function updateOrder() {
    const order = [...document.querySelectorAll('.slider-item')].map(el => el.dataset.id);
    await fetch('{{ route('admin.hero-sliders.reorder') }}', { 
        method: 'POST', 
        headers: { 
            'Content-Type': 'application/json', 
            'X-CSRF-TOKEN': '{{ csrf_token() }}' 
        }, 
        body: JSON.stringify({ order }) 
    });
}
</script>
@endsection