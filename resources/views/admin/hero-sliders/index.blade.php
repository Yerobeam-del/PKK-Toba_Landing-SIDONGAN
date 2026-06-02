@extends('admin.layouts.app')
@section('title', 'Manajemen Hero Slider')
@section('page-title', 'Kelola Slider Beranda')

@section('content')
<div style="margin-bottom:2rem">
    @if(session('success'))
    <div style="background:#f0fdf4;padding:1rem;margin-bottom:1.5rem;border-radius:10px;color:#166534;display:flex;align-items:center;gap:0.75rem">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

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

            <div style="display:grid;grid-template-columns:1fr 1fr auto;gap:1rem;align-items:end">
                <div>
                    <label style="font-weight:600;display:block;margin-bottom:0.5rem;font-size:0.9rem">Durasi Tampil (detik)</label>
                    <input type="number" name="display_duration" class="form-control" value="5" min="3" max="30">
                </div>
                <div>
                    <label style="font-weight:600;display:block;margin-bottom:0.5rem;font-size:0.9rem">Urutan</label>
                    <input type="number" name="sort_order" class="form-control" value="{{ $sliders->count() + 1 }}" min="0">
                </div>
                <div style="padding-bottom:0.25rem">
                    <div style="display:flex;align-items:center;gap:0.5rem;background:#f8fafc;padding:0.75rem 1rem;border-radius:10px;width:fit-content">
                        <input type="checkbox" name="is_active" value="1" id="isActive" checked style="width:18px;height:18px;cursor:pointer;margin:0">
                        <label for="isActive" style="font-weight:600;color:var(--text-dark);font-size:0.9rem;cursor:pointer;margin:0">Aktif</label>
                    </div>
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
        <div style="padding:0 0 1.5rem 0;display:flex;justify-content:space-between;align-items:center">
            <div style="display:flex;align-items:center;gap:0.75rem">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#14b8a6" stroke-width="2" style="flex-shrink:0"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                <h3 style="font-size:1.1rem;font-weight:700;color:#14b8a6;margin:0">Daftar Slide</h3>
            </div>
            <small style="color:var(--text-muted)">Drag & drop untuk mengurutkan</small>
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
                    <form action="{{ route('admin.hero-sliders.destroy', $slider) }}" method="POST" onsubmit="return confirm('Hapus slide ini?')" style="display:inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-del" title="Hapus"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg></button>
                    </form>
                </div>
            </div>
            @empty
            <div style="text-align:center;padding:3rem 1rem;color:var(--text-muted)">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin:0 auto 1rem;opacity:0.3"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                <p style="margin:0;font-size:0.95rem">Belum ada slide. Tambahkan slide pertama di atas.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div id="editModal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;padding:1rem">
    <div style="background:#fff;border-radius:16px;max-width:500px;width:100%;max-height:90vh;overflow-y:auto;box-shadow:0 20px 50px rgba(0,0,0,0.12)">
        <div style="padding:1.25rem 1.5rem;display:flex;justify-content:space-between;align-items:center">
            <h3 style="margin:0;font-size:1.2rem;font-weight:700">Edit Slide</h3>
            <button onclick="closeEditModal()" style="background:none;font-size:1.5rem;cursor:pointer;color:var(--text-muted)">&times;</button>
        </div>
        <form id="editForm" method="POST" enctype="multipart/form-data" style="padding:1.5rem;display:grid;gap:1rem">
            @csrf @method('PUT')
            <input type="hidden" id="editId" name="id">
            <div>
                <label style="font-weight:600;display:block;margin-bottom:0.5rem;font-size:0.9rem">Gambar (kosongkan jika tidak diubah)</label>
                <input type="file" name="image" class="form-control" accept="image/*">
                <img id="editImagePreview" src="" style="max-width:100%;max-height:200px;margin-top:0.75rem;border-radius:8px;display:none">
            </div>
            <div>
                <label style="font-weight:600;display:block;margin-bottom:0.5rem;font-size:0.9rem">Durasi Tampil (detik)</label>
                <input type="number" name="display_duration" id="editDuration" class="form-control" min="3" max="30">
            </div>
            <div>
                <label style="font-weight:600;display:block;margin-bottom:0.5rem;font-size:0.9rem">Urutan</label>
                <input type="number" name="sort_order" id="editSortOrder" class="form-control" min="0">
            </div>
            <div>
                <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer">
                    <input type="checkbox" name="is_active" id="editActive" value="1" style="width:18px;height:18px;cursor:pointer">
                    <span style="font-weight:500">Aktif</span>
                </label>
            </div>
            <div style="display:flex;gap:0.75rem;justify-content:flex-end;margin-top:1rem">
                <button type="button" onclick="closeEditModal()" class="btn" style="background:#f8fafc;color:var(--text-dark)">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function editSlider(id) {
    const item = document.querySelector(`.slider-item[data-id="${id}"]`);
    if (!item) return;
    document.getElementById('editId').value = id;
    const infoText = item.querySelector('div[style*="font-size:0.8rem"]').textContent;
    document.getElementById('editDuration').value = infoText.match(/(\d+)s/)?.[1] || '5';
    document.getElementById('editSortOrder').value = infoText.match(/Urutan:\s*(\d+)/)?.[1] || '0';
    document.getElementById('editActive').checked = infoText.includes('Aktif') && !infoText.includes('Nonaktif');
    const preview = document.getElementById('editImagePreview');
    preview.src = item.querySelector('img').src;
    preview.style.display = 'block';
    document.getElementById('editModal').style.display = 'flex';
}
function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
    document.getElementById('editImagePreview').style.display = 'none';
}
let draggedItem = null;
document.getElementById('slidersList').addEventListener('dragstart', e => { if(e.target.classList.contains('slider-item')) { draggedItem = e.target; setTimeout(() => e.target.style.opacity = '0.5', 0); }});
document.getElementById('slidersList').addEventListener('dragend', e => { if(draggedItem) { draggedItem.style.opacity = '1'; updateOrder(); draggedItem = null; }});
document.getElementById('slidersList').addEventListener('dragover', e => {
    e.preventDefault();
    const afterElement = [...document.getElementById('slidersList').querySelectorAll('.slider-item:not(.dragging)')].reduce((closest, child) => {
        const box = child.getBoundingClientRect(); const offset = e.clientY - box.top - box.height/2;
        return offset < 0 && offset > closest.offset ? { offset, element: child } : closest;
    }, { offset: Number.NEGATIVE_INFINITY }).element;
    afterElement == null ? document.getElementById('slidersList').appendChild(draggedItem) : document.getElementById('slidersList').insertBefore(draggedItem, afterElement);
});
async function updateOrder() {
    const order = [...document.querySelectorAll('.slider-item')].map(el => el.dataset.id);
    await fetch('{{ route('admin.hero-sliders.reorder') }}', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ order }) });
}
</script>
@endsection