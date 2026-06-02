@extends('sidongan.layouts.app')
@section('title', 'Daftar Surat - SIDONGAN')

@section('content')
@php
    $currentUser = auth()->guard('sidongan')->user();
@endphp

<div>
    {{-- Header Section --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 700; color: #1e293b; margin: 0;">Daftar Surat</h1>
            <p style="font-size: 0.875rem; color: #64748b; margin-top: 0.25rem;">Kelola semua dokumen surat masuk dan keluar</p>
        </div>
        @if($currentUser && $currentUser->hasSidonganRole('sekretaris'))
        <a href="{{ route('sidongan.documents.create') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.25rem; background: linear-gradient(135deg, #3b82f6, #2563eb); color: white; text-decoration: none; border-radius: 0.5rem; font-weight: 600; box-shadow: 0 2px 8px rgba(59,130,246,0.3); transition: all 0.2s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(59,130,246,0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(59,130,246,0.3)'">
            <i class="fas fa-plus" style="font-size: 1rem;"></i>
            <span>Buat Surat Baru</span>
        </a>
        @endif
    </div>

    {{-- Stats Cards --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
        <div style="background: linear-gradient(135deg, #3b82f6, #2563eb); border-radius: 0.75rem; padding: 1.25rem; color: white;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 3rem; height: 3rem; background: rgba(255,255,255,0.2); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-envelope" style="font-size: 1.25rem;"></i>
                </div>
                <div>
                    <p style="font-size: 0.875rem; opacity: 0.9; margin: 0;">Total Surat</p>
                    <p style="font-size: 1.875rem; font-weight: 800; margin: 0.25rem 0 0 0;">{{ $totalDocuments ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div style="background: linear-gradient(135deg, #22c55e, #16a34a); border-radius: 0.75rem; padding: 1.25rem; color: white;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 3rem; height: 3rem; background: rgba(255,255,255,0.2); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-check-circle" style="font-size: 1.25rem;"></i>
                </div>
                <div>
                    <p style="font-size: 0.875rem; opacity: 0.9; margin: 0;">Selesai</p>
                    <p style="font-size: 1.875rem; font-weight: 800; margin: 0.25rem 0 0 0;">0</p>
                </div>
            </div>
        </div>
        <div style="background: linear-gradient(135deg, #f97316, #ea580c); border-radius: 0.75rem; padding: 1.25rem; color: white;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 3rem; height: 3rem; background: rgba(255,255,255,0.2); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-spinner" style="font-size: 1.25rem;"></i>
                </div>
                <div>
                    <p style="font-size: 0.875rem; opacity: 0.9; margin: 0;">Proses</p>
                    <p style="font-size: 1.875rem; font-weight: 800; margin: 0.25rem 0 0 0;">0</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Section (UPDATED: Tanpa Kategori, Auto Search) --}}
    <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; padding: 1.25rem; margin-bottom: 1.5rem;">
        <form id="filterForm" method="GET" action="{{ route('sidongan.documents.index') }}">
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem;">
                {{-- Search --}}
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem;">Cari Dokumen</label>
                    <div style="position: relative;">
                        <i class="fas fa-search" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
                        <input type="text" name="search" id="searchInput" value="{{ request('search') }}" placeholder="Ketik untuk mencari berdasarkan judul atau nomor..." style="width: 100%; padding: 0.625rem 1rem 0.625rem 2.5rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.875rem; transition: all 0.2s;" onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'" onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                    </div>
                </div>

                {{-- Status --}}
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem;">Status</label>
                    <div style="position: relative;">
                        <select name="status" id="statusSelect" style="width: 100%; padding: 0.625rem 2.5rem 0.625rem 1rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.875rem; background: white; cursor: pointer; appearance: none; -webkit-appearance: none; -moz-appearance: none;" onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#e2e8f0'" onchange="document.getElementById('filterForm').submit()">
                            <option value="">Semua Status</option>
                            <option value="menunggu_disposisi" {{ request('status') == 'menunggu_disposisi' ? 'selected' : '' }}>Menunggu Disposisi</option>
                            <option value="berjalan" {{ request('status') == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                            <option value="menunggu_verifikasi" {{ request('status') == 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="diarsipkan" {{ request('status') == 'diarsipkan' ? 'selected' : '' }}>Diarsipkan</option>
                        </select>
                        <i class="fas fa-chevron-down" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: #94a3b8; pointer-events: none; font-size: 0.75rem;"></i>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Documents Table --}}
    <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; overflow: hidden;">
        @if(isset($documents) && $documents->count() > 0)
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: linear-gradient(135deg, #0891b2, #14b8a6); color: white;">
                    <tr>
                        <th style="padding: 1rem; text-align: left; font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">NO. AGENDA</th>
                        <th style="padding: 1rem; text-align: left; font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">PERIHAL</th>
                        <th style="padding: 1rem; text-align: left; font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">NO. SURAT</th>
                        <th style="padding: 1rem; text-align: left; font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">TANGGAL SURAT</th>
                        <th style="padding: 1rem; text-align: left; font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">DISPOSISI</th>
                        <th style="padding: 1rem; text-align: left; font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">STATUS</th>
                        <th style="padding: 1rem; text-align: center; font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($documents as $doc)
                    <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                        {{-- NO. AGENDA --}}
                        <td style="padding: 1rem; font-weight: 600; color: #3b82f6; font-family: monospace; font-size: 0.875rem;">
                            {{ $doc->agenda_number ?? '-' }}
                        </td>
                        
                        {{-- PERIHAL --}}
                        <td style="padding: 1rem;">
                            <div style="font-weight: 600; color: #0f172a; margin-bottom: 0.25rem;">{{ Str::limit($doc->subject ?? $doc->title, 60) }}</div>
                            @if($doc->sender)
                            <div style="font-size: 0.75rem; color: #64748b;">{{ $doc->sender }}</div>
                            @endif
                        </td>
                        
                        {{-- NO. SURAT --}}
                        <td style="padding: 1rem; color: #475569; font-size: 0.875rem;">
                            {{ $doc->document_number ?? '-' }}
                        </td>
                        
                        {{-- TANGGAL SURAT --}}
                        <td style="padding: 1rem; color: #475569; font-size: 0.875rem;">
                            {{ $doc->document_date ? $doc->document_date->format('d M Y') : '-' }}
                        </td>
                        
                        {{-- DISPOSISI --}}
                        <td style="padding: 1rem;">
                            @php
                                $disposisiData = is_string($doc->disposisi_data) ? json_decode($doc->disposisi_data, true) : $doc->disposisi_data;
                            @endphp
                            @if($disposisiData && isset($disposisiData['target_roles']))
                                @foreach($disposisiData['target_roles'] as $role)
                                    <span style="display: inline-block; padding: 0.25rem 0.5rem; background: #dbeafe; color: #1e40af; border-radius: 0.25rem; font-size: 0.75rem; margin: 0.125rem;">
                                        {{ ucfirst(str_replace('_', ' ', $role)) }}
                                    </span>
                                @endforeach
                            @else
                                <span style="color: #94a3b8; font-size: 0.75rem;">Belum</span>
                            @endif
                        </td>
                        
                        {{-- STATUS --}}
                        <td style="padding: 1rem;">
                            @php
                                $statusColors = [
                                    'menunggu_disposisi' => ['bg' => '#fef3c7', 'text' => '#92400e', 'label' => 'Menunggu Disposisi Ketua'],
                                    'berjalan' => ['bg' => '#dbeafe', 'text' => '#1e40af', 'label' => 'Sedang Berjalan'],
                                    'menunggu_verifikasi' => ['bg' => '#ede9fe', 'text' => '#6b21a8', 'label' => 'Menunggu Verifikasi'],
                                    'selesai' => ['bg' => '#d1fae5', 'text' => '#065f46', 'label' => 'Selesai'],
                                    'diarsipkan' => ['bg' => '#f3e8ff', 'text' => '#7c3aed', 'label' => 'Diarsipkan'],
                                    'ditolak' => ['bg' => '#fee2e2', 'text' => '#991b1b', 'label' => 'Ditolak'],
                                ];
                                $status = $statusColors[$doc->status] ?? ['bg' => '#f1f5f9', 'text' => '#475569', 'label' => $doc->status];
                            @endphp
                            <span style="display: inline-block; padding: 0.375rem 0.75rem; background: {{ $status['bg'] }}; color: {{ $status['text'] }}; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">
                                {{ $status['label'] }}
                            </span>
                        </td>
                        
                        {{-- KOLOM AKSI --}}
                        <td style="padding: 1rem; white-space: nowrap;">
                            <div style="display: flex; gap: 0.5rem; justify-content: center;">
                                
                                {{-- 1. Tombol View (Selalu Muncul untuk Semua Role) --}}
                                <a href="{{ route('sidongan.documents.show', $doc) }}" 
                                style="display: inline-flex; align-items: center; justify-content: center; width: 2rem; height: 2rem; background: #dbeafe; color: #2563eb; border-radius: 0.375rem; text-decoration: none; transition: all 0.2s;"
                                onmouseover="this.style.background='#bfdbfe'" 
                                onmouseout="this.style.background='#dbeafe'"
                                title="Lihat Detail">
                                    <i class="fas fa-eye" style="font-size: 0.875rem;"></i>
                                </a>
                                
                                {{-- 2. Tombol Edit & Delete (HANYA untuk Sekretaris DAN Status Menunggu Disposisi) --}}
                                @if($currentUser && $currentUser->hasSidonganRole('sekretaris') && $doc->status === 'menunggu_disposisi')
                                    
                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('sidongan.documents.edit', $doc) }}" 
                                    style="display: inline-flex; align-items: center; justify-content: center; width: 2rem; height: 2rem; background: #fef3c7; color: #d97706; border-radius: 0.375rem; text-decoration: none; transition: all 0.2s;"
                                    onmouseover="this.style.background='#fde68a'" 
                                    onmouseout="this.style.background='#fef3c7'"
                                    title="Edit Surat">
                                        <i class="fas fa-edit" style="font-size: 0.875rem;"></i>
                                    </a>
                                    
                                    {{-- Tombol Delete --}}
                                    <form action="{{ route('sidongan.documents.destroy', $doc) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Yakin ingin menghapus surat ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                style="display: inline-flex; align-items: center; justify-content: center; width: 2rem; height: 2rem; background: #fee2e2; color: #ef4444; border: none; border-radius: 0.375rem; cursor: pointer; transition: all 0.2s;"
                                                onmouseover="this.style.background='#fecaca'" 
                                                onmouseout="this.style.background='#fee2e2'"
                                                title="Hapus Surat">
                                            <i class="fas fa-trash" style="font-size: 0.875rem;"></i>
                                        </button>
                                    </form>
                                    
                                @endif
                                {{-- Jika kondisi di atas tidak terpenuhi (bukan Sekretaris atau sudah didisposisi), TIDAK ADA tombol lain yang muncul. --}}
                                
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($documents->hasPages())
        <div style="padding: 1rem; border-top: 1px solid #e2e8f0;">
            {{ $documents->links() }}
        </div>
        @endif
        @else
        {{-- Empty State --}}
        <div style="padding: 4rem 2rem; text-align: center;">
            <div style="width: 96px; height: 96px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                <i class="fas fa-inbox" style="color: #94a3b8; font-size: 3rem;"></i>
            </div>
            <h3 style="font-size: 1.125rem; font-weight: 700; color: #1e293b; margin: 0 0 0.5rem 0;">Belum Ada Dokumen</h3>
            <p style="font-size: 0.875rem; color: #64748b; margin: 0 0 1.5rem 0; max-width: 400px; margin-left: auto; margin-right: auto;">Belum ada dokumen surat yang ditemukan.</p>
            @if($currentUser && $currentUser->hasSidonganRole('sekretaris'))
            <a href="{{ route('sidongan.documents.create') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #3b82f6, #2563eb); color: white; text-decoration: none; border-radius: 0.5rem; font-weight: 600;">
                <i class="fas fa-plus"></i>
                <span>Buat Surat Pertama</span>
            </a>
            @endif
        </div>
        @endif
    </div>
</div>

{{-- Delete Confirmation Form --}}
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
    let searchTimeout;
    const searchInput = document.getElementById('searchInput');
    const filterForm = document.getElementById('filterForm');

    // Auto-focus saat halaman selesai dimuat
    document.addEventListener('DOMContentLoaded', function() {
        if (searchInput) {
            setTimeout(() => {
                searchInput.focus();
                // Pindahkan kursor ke akhir teks agar user tidak perlu delete jika ingin menambah kata
                const len = searchInput.value.length;
                searchInput.setSelectionRange(len, len);
            }, 100);
        }
    });

    // Auto-search dengan debounce
    searchInput?.addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            filterForm.submit();
        }, 500);
    });

    // Delete confirmation
    function confirmDelete(id, title) {
        if (confirm(`Apakah Anda yakin ingin menghapus surat "${title}"?\n\nPeringatan: Tindakan ini tidak dapat dibatalkan.`)) {
            document.getElementById('deleteForm').action = `/sidongan/documents/${id}`;
            document.getElementById('deleteForm').submit();
        }
    }
</script>
@endsection