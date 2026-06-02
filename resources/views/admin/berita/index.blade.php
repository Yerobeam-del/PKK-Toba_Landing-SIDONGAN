@extends('admin.layouts.app')

@section('title', 'Manajemen Berita')
@section('page-title', 'Daftar Berita')

@section('content')

{{-- Header Section --}}
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
    <div>
        <h1 style="font-size:1.5rem;font-weight:800;color:var(--text-dark);margin:0 0 0.25rem 0;letter-spacing:-0.5px">Daftar Berita</h1>
        <p style="color:var(--text-muted);margin:0;font-size:0.9rem">Kelola semua berita dan kegiatan PKK Kabupaten Toba</p>
    </div>
    <a href="{{ route('admin.berita.create') }}" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:0.5rem">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Berita
    </a>
</div>

{{-- Success Message --}}
@if(session('success'))
<div style="background:#f0fdf4;padding:1rem;margin-bottom:1.5rem;border-radius:10px;color:#166534;display:flex;align-items:center;gap:0.75rem">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
    <span>{{ session('success') }}</span>
</div>
@endif

{{-- Stats Cards --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:1rem;margin-bottom:2rem">
    {{-- Total Berita --}}
    <div class="stat-card" style="background:linear-gradient(135deg,#3182ce,#2b6cb0);color:#fff">
        <div style="display:flex;align-items:flex-start;gap:1rem">
            <div style="width:48px;height:48px;background:rgba(255,255,255,0.2);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8V6Z"/></svg>
            </div>
            <div style="flex:1">
                <p style="font-size:0.85rem;opacity:0.9;margin:0 0 0.25rem 0">Total Berita</p>
                <p style="font-size:1.85rem;font-weight:800;margin:0;line-height:1.1">{{ $berita->total() }}</p>
            </div>
        </div>
    </div>

    {{-- Dipublikasi --}}
    <div class="stat-card" style="background:linear-gradient(135deg,#38a169,#2f855a);color:#fff">
        <div style="display:flex;align-items:flex-start;gap:1rem">
            <div style="width:48px;height:48px;background:rgba(255,255,255,0.2);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <div style="flex:1">
                <p style="font-size:0.85rem;opacity:0.9;margin:0 0 0.25rem 0">Dipublikasi</p>
                <p style="font-size:1.85rem;font-weight:800;margin:0;line-height:1.1">{{ $berita->where('is_published', 1)->count() }}</p>
            </div>
        </div>
    </div>

    {{-- Draft --}}
    <div class="stat-card" style="background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff">
        <div style="display:flex;align-items:flex-start;gap:1rem">
            <div style="width:48px;height:48px;background:rgba(255,255,255,0.2);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
            </div>
            <div style="flex:1">
                <p style="font-size:0.85rem;opacity:0.9;margin:0 0 0.25rem 0">Draft</p>
                <p style="font-size:1.85rem;font-weight:800;margin:0;line-height:1.1">{{ $berita->where('is_published', 0)->count() }}</p>
            </div>
        </div>
    </div>
</div>

{{-- Main Card --}}
<div class="card" style="padding:0;overflow:hidden">
    
    {{-- Search Toolbar --}}
    <div style="padding:1.25rem 1.5rem;border-bottom:1px solid rgba(0,0,0,0.06);display:flex;justify-content:space-between;align-items:center">
        <div class="search-box" style="position:relative;max-width:400px;width:100%">
            <svg style="position:absolute;left:1rem;top:50%;transform:translateY(-50%);width:18px;height:18px;color:var(--text-muted)" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
            <input type="text" id="searchInput" placeholder="Cari judul atau ringkasan..." style="width:100%;padding:0.65rem 1rem 0.65rem 3rem;border:1px solid rgba(0,0,0,0.06);border-radius:8px;font-family:inherit;font-size:0.9rem;background:#fff;transition:border-color 0.2s">
        </div>
    </div>

    {{-- Table Container --}}
    <div class="table-container" style="padding:1rem">
        <table style="width:100%;border-collapse:collapse">
            <thead>
                <tr style="text-align:left;border-bottom:1px solid rgba(0,0,0,0.06)">
                    <th style="padding:1rem;color:var(--text-muted);font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px">Judul Berita</th>
                    <th style="padding:1rem;color:var(--text-muted);font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px">Kategori</th>
                    <th style="padding:1rem;color:var(--text-muted);font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px">Tanggal</th>
                    <th style="padding:1rem;color:var(--text-muted);font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px">Status</th>
                    <th style="padding:1rem;color:var(--text-muted);font-weight:600;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px;text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody id="newsTableBody">
                @forelse($berita as $item)
                <tr class="news-row" data-title="{{ strtolower($item->title) }}" data-excerpt="{{ strtolower($item->excerpt) }}" style="border-bottom:1px solid rgba(0,0,0,0.04);transition:background 0.2s">
                    <td style="padding:1rem">
                        <div style="display:flex;gap:1rem;align-items:flex-start">
                            @if($item->image_path)
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}" style="width:80px;height:60px;object-fit:cover;border-radius:8px;background:#f8fafc" onerror="this.src='https://via.placeholder.com/80x60?text=No+Image'">
                            @else
                            <div style="width:80px;height:60px;border-radius:8px;background:#f8fafc;display:flex;align-items:center;justify-content:center;color:#94a3b8">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                            </div>
                            @endif
                            <div style="flex:1;min-width:0">
                                <div style="font-weight:600;color:var(--text-dark);margin-bottom:0.25rem">{{ Str::limit($item->title, 50) }}</div>
                                <p style="color:var(--text-muted);font-size:0.85rem;margin:0;line-height:1.4">{{ Str::limit($item->excerpt, 80) }}</p>
                            </div>
                        </div>
                    </td>
                    <td style="padding:1rem">
                        <span style="background:rgba(59,130,246,0.1);color:#2563eb;padding:4px 10px;border-radius:20px;font-size:0.75rem;font-weight:600">{{ $item->category }}</span>
                    </td>
                    <td style="padding:1rem;color:var(--text-muted);font-size:0.9rem">
                        {{ $item->published_at?->format('d M Y') ?? '-' }}
                    </td>
                    <td style="padding:1rem">
                        @if($item->is_published)
                        <span style="display:inline-flex;align-items:center;gap:6px;padding:4px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;background:rgba(34,197,94,0.1);color:#166534">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                            Publik
                        </span>
                        @else
                        <span style="display:inline-flex;align-items:center;gap:6px;padding:4px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;background:rgba(234,179,8,0.1);color:#92400e">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            Draft
                        </span>
                        @endif
                    </td>
                    <td style="padding:1rem;text-align:center">
                        <div class="actions" style="justify-content:center;gap:0.5rem">
                            <a href="{{ route('admin.berita.edit', $item) }}" class="btn-edit" title="Edit" style="width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;background:#eff6ff;color:#2563eb;border-radius:6px;transition:all 0.2s">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </a>
                            <form action="{{ route('admin.berita.destroy', $item) }}" method="POST" style="display:inline" onsubmit="return confirm('Yakin ingin menghapus berita ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-del" title="Hapus" style="width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;background:#fef2f2;color:#ef4444;border-radius:6px;transition:all 0.2s;border:none;cursor:pointer">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div style="text-align:center;padding:3rem 1rem;color:var(--text-muted)">
                            <div style="width:64px;height:64px;background:#f8fafc;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8V6Z"/></svg>
                            </div>
                            <h3 style="font-size:1rem;font-weight:700;color:var(--text-dark);margin:0 0 0.5rem">Belum ada berita</h3>
                            <p style="font-size:0.9rem;margin:0 0 1rem">Mulai tambahkan berita pertama Anda</p>
                            <a href="{{ route('admin.berita.create') }}" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:0.5rem">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                Tambah Berita Pertama
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($berita->hasPages())
    <div style="padding:1rem 1.5rem;border-top:1px solid rgba(0,0,0,0.06)">
        {{ $berita->links() }}
    </div>
    @endif
</div>

<script>
// Search functionality
document.getElementById('searchInput')?.addEventListener('input', function(e) {
    const term = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('.news-row');
    rows.forEach(row => {
        const title = row.dataset.title || '';
        const excerpt = row.dataset.excerpt || '';
        row.style.display = (title.includes(term) || excerpt.includes(term)) ? '' : 'none';
    });
});
</script>
@endsection