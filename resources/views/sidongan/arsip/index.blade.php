@extends('sidongan.layouts.app')
@section('title', 'Arsip Surat - SIDONGAN')

@section('content')
<style>
    .arsip-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid transparent;
    }
    .arsip-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.1);
        border-color: #8b5cf6;
    }
    .btn-arsip {
        transition: all 0.2s;
    }
    .btn-arsip:hover {
        transform: scale(1.05);
    }
    .stats-card {
        transition: all 0.3s;
    }
    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.15);
    }
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-slide-in {
        animation: slideIn 0.4s ease-out;
    }
</style>

<div style="max-width: 1200px; margin: 0 auto;">
    {{-- Header --}}
    <div style="margin-bottom: 1.5rem;" class="animate-slide-in">
        <h1 style="font-size: 1.5rem; font-weight: 700; color: #1e293b; margin: 0;">Arsip Surat</h1>
        <p style="font-size: 0.875rem; color: #64748b; margin-top: 0.25rem;">Lihat dan unduh dokumen yang telah selesai diproses</p>
    </div>

    {{-- Stats Cards --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
        {{-- Total Arsip - WARNA UNGU --}}
        <div class="stats-card" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed); border-radius: 0.75rem; padding: 1.25rem; color: white; position: relative; overflow: hidden;">
            <div style="position: absolute; top: -10px; right: -10px; width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
            <div style="display: flex; align-items: center; gap: 1rem; position: relative; z-index: 1;">
                <div style="width: 3rem; height: 3rem; background: rgba(255,255,255,0.25); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
                    <i class="fas fa-archive" style="font-size: 1.25rem;"></i>
                </div>
                <div>
                    <p style="font-size: 0.875rem; opacity: 0.95; margin: 0;">Total Arsip</p>
                    <p style="font-size: 1.875rem; font-weight: 800; margin: 0.25rem 0 0 0;">{{ $totalArsip ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        {{-- Arsip Bulan Ini - WARNA HIJAU --}}
        <div class="stats-card" style="background: linear-gradient(135deg, #22c55e, #16a34a); border-radius: 0.75rem; padding: 1.25rem; color: white; position: relative; overflow: hidden;">
            <div style="position: absolute; top: -10px; right: -10px; width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
            <div style="display: flex; align-items: center; gap: 1rem; position: relative; z-index: 1;">
                <div style="width: 3rem; height: 3rem; background: rgba(255,255,255,0.25); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
                    <i class="fas fa-calendar-check" style="font-size: 1.25rem;"></i>
                </div>
                <div>
                    <p style="font-size: 0.875rem; opacity: 0.95; margin: 0;">Arsip Bulan Ini</p>
                    <p style="font-size: 1.875rem; font-weight: 800; margin: 0.25rem 0 0 0;">{{ $arsipBulanIni ?? 0 }}</p>
                    <p style="font-size: 0.75rem; opacity: 0.9; margin: 0.25rem 0 0 0;">
                        {{ now()->locale('id')->translatedFormat('F Y') }}
                    </p>
                </div>
            </div>
        </div>
        
        {{-- Arsip Tahun Ini - WARNA ORANGE --}}
        <div class="stats-card" style="background: linear-gradient(135deg, #f97316, #ea580c); border-radius: 0.75rem; padding: 1.25rem; color: white; position: relative; overflow: hidden;">
            <div style="position: absolute; top: -10px; right: -10px; width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
            <div style="display: flex; align-items: center; gap: 1rem; position: relative; z-index: 1;">
                <div style="width: 3rem; height: 3rem; background: rgba(255,255,255,0.25); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
                    <i class="fas fa-calendar-alt" style="font-size: 1.25rem;"></i>
                </div>
                <div>
                    <p style="font-size: 0.875rem; opacity: 0.95; margin: 0;">Arsip Tahun Ini</p>
                    <p style="font-size: 1.875rem; font-weight: 800; margin: 0.25rem 0 0 0;">{{ $arsipTahunIni ?? 0 }}</p>
                    <p style="font-size: 0.75rem; opacity: 0.9; margin: 0.25rem 0 0 0;">
                        {{ now()->format('Y') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Section --}}
    <div style="background: white; border-radius: 0.75rem; padding: 1.25rem; margin-bottom: 1.5rem; border: 1px solid #e2e8f0;">
        <form method="GET" action="{{ route('sidongan.arsip') }}" style="display: grid; grid-template-columns: 2fr 1fr 1fr auto; gap: 1rem; align-items: end;">
            <div>
                <label style="display: block; font-size: 0.8rem; font-weight: 600; color: #64748b; margin-bottom: 0.5rem;">Cari Arsip</label>
                <div style="position: relative;">
                    <i class="fas fa-search" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem;"></i>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari berdasarkan judul atau nomor..." 
                           style="width: 100%; padding: 0.6rem 1rem 0.6rem 2.5rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.875rem;">
                </div>
            </div>
            
            <div>
                <label style="display: block; font-size: 0.8rem; font-weight: 600; color: #64748b; margin-bottom: 0.5rem;">Kategori</label>
                <select name="category" style="width: 100%; padding: 0.6rem 2.5rem 0.6rem 1rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.875rem; background: white; appearance: none; -webkit-appearance: none; -moz-appearance: none; background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%2364748b\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-linejoin=\'round\'%3e%3cpolyline points=\'6 9 12 15 18 9\'%3e%3c/polyline%3e%3c/svg%3e'); background-repeat: no-repeat; background-position: right 0.75rem center; background-size: 1rem;">
                    <option value="">Semua Kategori</option>
                    @foreach($categories ?? [] as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label style="display: block; font-size: 0.8rem; font-weight: 600; color: #64748b; margin-bottom: 0.5rem;">Tahun</label>
                <select name="year" style="width: 100%; padding: 0.6rem 2.5rem 0.6rem 1rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.875rem; background: white; appearance: none; -webkit-appearance: none; -moz-appearance: none; background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%2364748b\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-linejoin=\'round\'%3e%3cpolyline points=\'6 9 12 15 18 9\'%3e%3c/polyline%3e%3c/svg%3e'); background-repeat: no-repeat; background-position: right 0.75rem center; background-size: 1rem;">
                    <option value="">Semua Tahun</option>
                    @for($year = date('Y'); $year >= date('Y')-5; $year--)
                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endfor
                </select>
            </div>
            
            <div>
                <button type="submit" 
                        style="padding: 0.6rem 1.5rem; background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer; transition: all 0.2s; height: calc(100% - 0px);"
                        onmouseover="this.style.transform='scale(1.05)'" 
                        onmouseout="this.style.transform='scale(1)'">
                    <i class="fas fa-filter" style="margin-right: 0.5rem;"></i>
                    Filter
                </button>
            </div>
        </form>
    </div>

    {{-- Daftar Arsip --}}
    <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e2e8f0;">
        <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #e2e8f0;">
            <h3 style="font-size: 1rem; font-weight: 700; color: #1e293b; margin: 0;">Dokumen Terarsip</h3>
            <p style="font-size: 0.8rem; color: #64748b; margin: 0.25rem 0 0 0;">Daftar dokumen yang telah selesai diproses dan diarsipkan</p>
        </div>
        
        <div style="padding: 1.5rem;">
            @forelse($documents ?? [] as $index => $doc)
                {{-- Card Arsip --}}
                <div class="arsip-card animate-slide-in" 
                    style="background: #faf5ff; 
                            border: 2px solid #e9d5ff; 
                            border-radius: 0.75rem; 
                            padding: 1.25rem; 
                            margin-bottom: 1rem; 
                            animation-delay: {{ $loop->index * 0.1 }}s;">

                    <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 1rem;">
                        <div style="flex: 1; min-width: 0;">
                            {{-- Header: Agenda Number & Status Badge --}}
                            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem; flex-wrap: wrap;">
                                <span style="font-size: 0.75rem; font-family: monospace; background: white; color: #7c3aed; padding: 0.25rem 0.6rem; border-radius: 0.375rem; font-weight: 700; border: 1px solid #e9d5ff;">
                                    {{ $doc->agenda_number }}
                                </span>
                                <span style="font-size: 0.75rem; padding: 0.25rem 0.75rem; border-radius: 9999px; font-weight: 600; background: #22c55e; color: white;">
                                    {{ $doc->status === 'selesai' ? 'Selesai' : 'Diarsipkan' }}
                                </span>
                            </div>
                            
                            {{-- Judul Dokumen --}}
                            <h4 style="font-size: 1.05rem; font-weight: 700; color: #0f172a; margin: 0 0 0.75rem 0; line-height: 1.4;">
                                {{ $doc->subject ?? $doc->title }}
                            </h4>
                            
                            {{-- Meta Info --}}
                            <div style="display: flex; gap: 1.5rem; font-size: 0.85rem; color: #64748b; flex-wrap: wrap;">
                                <span style="display: flex; align-items: center; gap: 0.5rem;">
                                    <div style="width: 1.5rem; height: 1.5rem; background: #faf5ff; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-user" style="color: #7c3aed; font-size: 0.7rem;"></i>
                                    </div>
                                    {{ $doc->sender }}
                                </span>
                                <span style="display: flex; align-items: center; gap: 0.5rem;">
                                    <div style="width: 1.5rem; height: 1.5rem; background: #faf5ff; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-calendar" style="color: #7c3aed; font-size: 0.7rem;"></i>
                                    </div>
                                    {{ $doc->document_date->locale('id')->translatedFormat('d M Y') }}
                                </span>
                                @if($doc->category)
                                <span style="display: flex; align-items: center; gap: 0.5rem;">
                                    <div style="width: 1.5rem; height: 1.5rem; background: #faf5ff; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-folder" style="color: #7c3aed; font-size: 0.7rem;"></i>
                                    </div>
                                    {{ $doc->category->name }}
                                </span>
                                @endif
                            </div>
                        </div>
                        
                        {{-- Tombol Aksi --}}
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('sidongan.documents.show', $doc->id) }}" 
                            class="btn-arsip"
                            style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1rem; background: #3b82f6; color: white; text-decoration: none; border-radius: 0.5rem; font-size: 0.85rem; font-weight: 600; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                <i class="fas fa-eye"></i>
                                <span>Lihat</span>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                {{-- Empty State --}}
                <div style="text-align: center; padding: 4rem 2rem;">
                    <div style="width: 120px; height: 120px; background: linear-gradient(135deg, #f1f5f9, #e2e8f0); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; animation: pulse 2s infinite;">
                        <i class="fas fa-archive" style="color: #94a3b8; font-size: 3rem;"></i>
                    </div>
                    <h4 style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin: 0 0 0.5rem 0;">Tidak Ada Arsip</h4>
                    <p style="font-size: 0.875rem; color: #64748b; margin: 0;">
                        Dokumen yang telah selesai diproses akan muncul di sini.
                    </p>
                </div>
            @endforelse
        </div>
        
        {{-- Pagination --}}
        @if($documents && $documents->hasPages())
        <div style="padding: 1rem 1.5rem; border-top: 1px solid #e2e8f0;">
            {{ $documents->links() }}
        </div>
        @endif
    </div>
</div>

<style>
@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}
</style>
@endsection