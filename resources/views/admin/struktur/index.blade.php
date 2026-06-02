@extends('admin.layouts.app')
@section('title', 'Manajemen Struktur')
@section('page-title', 'Struktur Organisasi')

@section('content')
<div style="margin-bottom:2rem">
    
    {{-- Header Section --}}
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
        <div>
            <h1 style="font-size:1.5rem;font-weight:800;color:var(--text-dark);margin:0 0 0.25rem 0;letter-spacing:-0.5px">Struktur Organisasi</h1>
            <p style="color:var(--text-muted);margin:0;font-size:0.9rem">Kelola data sesuai bagan organisasi asli PKK Kabupaten Toba</p>
        </div>
        <a href="{{ route('admin.struktur.create') }}" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:0.5rem">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Tambah Anggota
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
    <div style="background:#f0fdf4;padding:1rem;margin-bottom:1.5rem;border-radius:10px;color:#166534;display:flex;align-items:center;gap:0.75rem">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    {{-- TABS: SEMUA TAB MUNCUL (Hardcoded) --}}
    <div style="display:flex;gap:0.25rem;margin-bottom:1.5rem;border-bottom:1px solid rgba(0,0,0,0.06);padding-bottom:0.5rem;overflow-x:auto">
        <button class="tab-btn active" onclick="switchTab('pengurus', this)" style="padding:0.6rem 1rem;border-radius:8px 8px 0 0;background:transparent;border:none;font-weight:600;color:var(--text-muted);cursor:pointer;transition:all 0.2s;border-bottom:2px solid var(--primary)">
            Pengurus Inti
        </button>
        <button class="tab-btn" onclick="switchTab('pokja1', this)" style="padding:0.6rem 1rem;border-radius:8px 8px 0 0;background:transparent;border:none;font-weight:600;color:var(--text-muted);cursor:pointer;transition:all 0.2s;border-bottom:2px solid transparent">
            Pokja I
        </button>
        <button class="tab-btn" onclick="switchTab('pokja2', this)" style="padding:0.6rem 1rem;border-radius:8px 8px 0 0;background:transparent;border:none;font-weight:600;color:var(--text-muted);cursor:pointer;transition:all 0.2s;border-bottom:2px solid transparent">
            Pokja II
        </button>
        <button class="tab-btn" onclick="switchTab('pokja3', this)" style="padding:0.6rem 1rem;border-radius:8px 8px 0 0;background:transparent;border:none;font-weight:600;color:var(--text-muted);cursor:pointer;transition:all 0.2s;border-bottom:2px solid transparent">
            Pokja III
        </button>
        <button class="tab-btn" onclick="switchTab('pokja4', this)" style="padding:0.6rem 1rem;border-radius:8px 8px 0 0;background:transparent;border:none;font-weight:600;color:var(--text-muted);cursor:pointer;transition:all 0.2s;border-bottom:2px solid transparent">
            Pokja IV
        </button>
    </div>

    {{-- Main Card --}}
    <div class="card" style="padding:0;overflow:hidden">
        
        {{-- Tab 1: Pengurus Inti --}}
        <div id="tab-pengurus" class="tab-content active">
            @include('admin.partials.table', [
                'data' => $pengurusInti ?? [], 
                'emptyMessage' => 'Belum ada data pengurus inti.',
                'editRoute' => 'admin.struktur.edit',
                'deleteRoute' => 'admin.struktur.destroy'
            ])
        </div>

        {{-- Tab 2: Pokja I --}}
        <div id="tab-pokja1" class="tab-content" style="display:none">
            @php $pokja1 = $pokjaList->find(1); @endphp
            @include('admin.partials.table', [
                'data' => $pokja1->members ?? [], 
                'emptyMessage' => 'Belum ada anggota di Pokja I.',
                'editRoute' => 'admin.struktur.edit',
                'deleteRoute' => 'admin.struktur.destroy'
            ])
        </div>

        {{-- Tab 3: Pokja II --}}
        <div id="tab-pokja2" class="tab-content" style="display:none">
            @php $pokja2 = $pokjaList->find(2); @endphp
            @include('admin.partials.table', [
                'data' => $pokja2->members ?? [], 
                'emptyMessage' => 'Belum ada anggota di Pokja II.',
                'editRoute' => 'admin.struktur.edit',
                'deleteRoute' => 'admin.struktur.destroy'
            ])
        </div>

        {{-- Tab 4: Pokja III --}}
        <div id="tab-pokja3" class="tab-content" style="display:none">
            @php $pokja3 = $pokjaList->find(3); @endphp
            @include('admin.partials.table', [
                'data' => $pokja3->members ?? [], 
                'emptyMessage' => 'Belum ada anggota di Pokja III.',
                'editRoute' => 'admin.struktur.edit',
                'deleteRoute' => 'admin.struktur.destroy'
            ])
        </div>

        {{-- Tab 5: Pokja IV --}}
        <div id="tab-pokja4" class="tab-content" style="display:none">
            @php $pokja4 = $pokjaList->find(4); @endphp
            @include('admin.partials.table', [
                'data' => $pokja4->members ?? [], 
                'emptyMessage' => 'Belum ada anggota di Pokja IV.',
                'editRoute' => 'admin.struktur.edit',
                'deleteRoute' => 'admin.struktur.destroy'
            ])
        </div>

    </div>
</div>

<script>
function switchTab(tabId, btn) {
    // Reset all tabs
    document.querySelectorAll('.tab-btn').forEach(b => {
        b.style.color = 'var(--text-muted)';
        b.style.borderBottom = '2px solid transparent';
    });
    // Hide all contents
    document.querySelectorAll('.tab-content').forEach(c => c.style.display = 'none');
    
    // Activate selected
    btn.style.color = 'var(--primary)';
    btn.style.borderBottom = '2px solid var(--primary)';
    document.getElementById('tab-' + tabId).style.display = 'block';
}

// Init first tab on load
document.addEventListener('DOMContentLoaded', () => {
    const firstBtn = document.querySelector('.tab-btn');
    if(firstBtn) switchTab('pengurus', firstBtn);
});
</script>
@endsection