@extends('sidongan.layouts.app')
@section('title', 'Arsip Surat - SIDONGAN')

@section('content')
@php
    $currentUser = auth()->guard('sidongan')->user();
@endphp

<div>
    {{-- Header Section --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 700; color: #1e293b; margin: 0;">Arsip Surat</h1>
            <p style="font-size: 0.875rem; color: #64748b; margin-top: 0.25rem;">Lihat dan unduh dokumen yang telah selesai diproses</p>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
        {{-- Total Arsip --}}
        <div style="background: linear-gradient(135deg, #6366f1, #4f46e5); border-radius: 0.75rem; padding: 1.25rem; color: white;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 3rem; height: 3rem; background: rgba(255,255,255,0.2); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-archive" style="font-size: 1.25rem;"></i>
                </div>
                <div>
                    <p style="font-size: 0.875rem; opacity: 0.9; margin: 0;">Total Arsip</p>
                    <p style="font-size: 1.875rem; font-weight: 800; margin: 0.25rem 0 0 0;">{{ $documents->total() ?? 0 }}</p>
                </div>
            </div>
        </div>
        {{-- Arsip Bulan Ini --}}
        <div style="background: linear-gradient(135deg, #22c55e, #16a34a); border-radius: 0.75rem; padding: 1.25rem; color: white;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 3rem; height: 3rem; background: rgba(255,255,255,0.2); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-calendar-alt" style="font-size: 1.25rem;"></i>
                </div>
                <div>
                    <p style="font-size: 0.875rem; opacity: 0.9; margin: 0;">Arsip Bulan Ini</p>
                    <p style="font-size: 1.875rem; font-weight: 800; margin: 0.25rem 0 0 0;">0</p>
                </div>
            </div>
        </div>
        {{-- Arsip Tahun Ini --}}
        <div style="background: linear-gradient(135deg, #f97316, #ea580c); border-radius: 0.75rem; padding: 1.25rem; color: white;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 3rem; height: 3rem; background: rgba(255,255,255,0.2); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-calendar-check" style="font-size: 1.25rem;"></i>
                </div>
                <div>
                    <p style="font-size: 0.875rem; opacity: 0.9; margin: 0;">Arsip Tahun Ini</p>
                    <p style="font-size: 1.875rem; font-weight: 800; margin: 0.25rem 0 0 0;">0</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Section --}}
    <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; padding: 1.25rem; margin-bottom: 1.5rem;">
        <form id="filterForm" method="GET" action="{{ route('sidongan.arsip') }}">
            <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 1rem;">
                {{-- Search --}}
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem;">Cari Arsip</label>
                    <div style="position: relative;">
                        <i class="fas fa-search" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
                        <input type="text" name="search" id="searchInput" value="{{ request('search') }}" placeholder="Cari berdasarkan judul atau nomor..." style="width: 100%; padding: 0.625rem 1rem 0.625rem 2.5rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.875rem; transition: all 0.2s;" onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'" onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                    </div>
                </div>

                {{-- Kategori --}}
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem;">Kategori</label>
                    <div style="position: relative;">
                        <select name="category" style="width: 100%; padding: 0.625rem 2.5rem 0.625rem 1rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.875rem; background: white; cursor: pointer; appearance: none; -webkit-appearance: none; -moz-appearance: none;" onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#e2e8f0'" onchange="document.getElementById('filterForm').submit()">
                            <option value="">Semua Kategori</option>
                            @foreach($categories ?? [] as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: #94a3b8; pointer-events: none; font-size: 0.75rem;"></i>
                    </div>
                </div>

                {{-- Tahun --}}
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem;">Tahun</label>
                    <div style="position: relative;">
                        <select name="year" style="width: 100%; padding: 0.625rem 2.5rem 0.625rem 1rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.875rem; background: white; cursor: pointer; appearance: none; -webkit-appearance: none; -moz-appearance: none;" onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#e2e8f0'" onchange="document.getElementById('filterForm').submit()">
                            <option value="">Semua Tahun</option>
                            @for($y = date('Y'); $y >= 2020; $y--)
                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                        <i class="fas fa-chevron-down" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: #94a3b8; pointer-events: none; font-size: 0.75rem;"></i>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Archive Table --}}
    <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; overflow: hidden;">
        @if(isset($documents) && $documents->count() > 0)
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                    <tr>
                        <th style="padding: 1rem 1.25rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">No. Agenda</th>
                        <th style="padding: 1rem 1.25rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Judul Dokumen</th>
                        <th style="padding: 1rem 1.25rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Kategori</th>
                        <th style="padding: 1rem 1.25rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Tanggal Arsip</th>
                        <th style="padding: 1rem 1.25rem; text-align: center; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($documents as $doc)
                    <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                        <td style="padding: 1rem 1.25rem; font-size: 0.875rem; font-family: monospace; color: #64748b;">{{ $doc->document_number ?? 'AG/01/2024/' . str_pad($loop->iteration, 3, '0', STR_PAD_LEFT) }}</td>
                        <td style="padding: 1rem 1.25rem;">
                            <div style="font-weight: 600; color: #0f172a; margin-bottom: 0.25rem;">{{ Str::limit($doc->title, 50) }}</div>
                            @if($doc->description)
                            <div style="font-size: 0.75rem; color: #94a3b8;">{{ Str::limit($doc->description, 60) }}</div>
                            @endif
                        </td>
                        <td style="padding: 1rem 1.25rem;">
                            @if($doc->category)
                            <span style="display: inline-block; padding: 0.25rem 0.75rem; background: #dbeafe; color: #2563eb; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">{{ $doc->category->name }}</span>
                            @else
                            <span style="color: #94a3b8; font-size: 0.875rem;">-</span>
                            @endif
                        </td>
                        <td style="padding: 1rem 1.25rem; font-size: 0.875rem; color: #64748b;">{{ $doc->document_date?->format('d M Y') ?? $doc->created_at->format('d M Y') }}</td>
                        <td style="padding: 1rem 1.25rem; text-align: center;">
                            <div style="display: flex; gap: 0.5rem; justify-content: center;">
                                <a href="{{ route('sidongan.documents.show', $doc->id) }}" style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; background: #dbeafe; color: #2563eb; border-radius: 0.375rem; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='#2563eb'; this.style.color='white'" onmouseout="this.style.background='#dbeafe'; this.style.color='#2563eb'" title="Lihat Detail">
                                    <i class="fas fa-eye" style="font-size: 0.875rem;"></i>
                                </a>
                                <a href="{{ route('sidongan.documents.download', $doc->id) }}" style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; background: #d1fae5; color: #059669; border-radius: 0.375rem; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='#059669'; this.style.color='white'" onmouseout="this.style.background='#d1fae5'; this.style.color='#059669'" title="Download">
                                    <i class="fas fa-download" style="font-size: 0.875rem;"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($documents->hasPages())
        <div style="padding: 1rem 1.25rem; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
            <p style="font-size: 0.875rem; color: #64748b; margin: 0;">Menampilkan {{ $documents->firstItem() }} - {{ $documents->lastItem() }} dari {{ $documents->total() }} arsip</p>
            <div style="display: flex; gap: 0.25rem;">
                @if($documents->onFirstPage())
                <button disabled style="padding: 0.5rem 1rem; border: 1px solid #e2e8f0; background: #f8fafc; color: #cbd5e1; border-radius: 0.375rem; cursor: not-allowed; font-size: 0.875rem;">Previous</button>
                @else
                <a href="{{ $documents->previousPageUrl() }}" style="padding: 0.5rem 1rem; border: 1px solid #e2e8f0; background: white; color: #2563eb; border-radius: 0.375rem; text-decoration: none; font-size: 0.875rem; transition: all 0.2s;" onmouseover="this.style.background='#f8fafc'">Previous</a>
                @endif

                @foreach($documents->getUrlRange(1, $documents->lastPage()) as $page => $url)
                @if($page == $documents->currentPage())
                <button style="padding: 0.5rem 1rem; border: 1px solid #3b82f6; background: #3b82f6; color: white; border-radius: 0.375rem; cursor: default; font-size: 0.875rem; font-weight: 600;">{{ $page }}</button>
                @else
                <a href="{{ $url }}" style="padding: 0.5rem 1rem; border: 1px solid #e2e8f0; background: white; color: #334155; border-radius: 0.375rem; text-decoration: none; font-size: 0.875rem; transition: all 0.2s;" onmouseover="this.style.background='#f8fafc'">{{ $page }}</a>
                @endif
                @endforeach

                @if($documents->hasMorePages())
                <a href="{{ $documents->nextPageUrl() }}" style="padding: 0.5rem 1rem; border: 1px solid #e2e8f0; background: white; color: #2563eb; border-radius: 0.375rem; text-decoration: none; font-size: 0.875rem; transition: all 0.2s;" onmouseover="this.style.background='#f8fafc'">Next</a>
                @else
                <button disabled style="padding: 0.5rem 1rem; border: 1px solid #e2e8f0; background: #f8fafc; color: #cbd5e1; border-radius: 0.375rem; cursor: not-allowed; font-size: 0.875rem;">Next</button>
                @endif
            </div>
        </div>
        @endif
        @else
        {{-- Empty State --}}
        <div style="padding: 4rem 2rem; text-align: center;">
            <div style="width: 96px; height: 96px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                <i class="fas fa-archive" style="color: #94a3b8; font-size: 3rem;"></i>
            </div>
            <h3 style="font-size: 1.125rem; font-weight: 700; color: #1e293b; margin: 0 0 0.5rem 0;">Belum Ada Dokumen Diarsipkan</h3>
            <p style="font-size: 0.875rem; color: #64748b; margin: 0 0 1.5rem 0; max-width: 400px; margin-left: auto; margin-right: auto;">Dokumen yang sudah selesai diproses akan otomatis masuk ke dalam arsip.</p>
        </div>
        @endif
    </div>
</div>

<script>
    // Auto-submit search on Enter key
    document.getElementById('searchInput')?.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('filterForm').submit();
        }
    });
</script>
@endsection