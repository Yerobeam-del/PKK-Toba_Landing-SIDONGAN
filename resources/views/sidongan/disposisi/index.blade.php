@extends('sidongan.layouts.app')
@section('title', 'Disposisi Surat - SIDONGAN')

@section('content')
<div>
    {{-- Header --}}
    <div style="margin-bottom: 1.5rem;">
        <h1 style="font-size: 1.5rem; font-weight: 700; color: #1e293b; margin: 0;">Disposisi Surat</h1>
        <p style="font-size: 0.875rem; color: #64748b; margin-top: 0.25rem;">Kelola disposisi surat untuk diteruskan ke pelaksana</p>
    </div>

    {{-- Stats Cards --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
        <div style="background: linear-gradient(135deg, #f97316, #ea580c); border-radius: 0.75rem; padding: 1.25rem; color: white;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 3rem; height: 3rem; background: rgba(255,255,255,0.2); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-list-check" style="font-size: 1.25rem;"></i>
                </div>
                <div>
                    <p style="font-size: 0.875rem; opacity: 0.9; margin: 0;">Menunggu Disposisi</p>
                    <p style="font-size: 1.875rem; font-weight: 800; margin: 0.25rem 0 0 0;">{{ $documents->total() ?? 0 }}</p>
                </div>
            </div>
        </div>
        {{-- Tambahkan stats lain jika diperlukan --}}
    </div>

    {{-- List Surat dalam bentuk Kartu (Sesuai Contoh) --}}
    <div style="display: grid; gap: 1rem;">
        @forelse($documents as $doc)
        <div style="background: white; border: 1px solid #e2e8f0; border-radius: 0.75rem; padding: 1.25rem; box-shadow: 0 1px 2px rgba(0,0,0,0.05); transition: all 0.2s;" 
             onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,0.1)'; this.style.borderColor='#cbd5e1'"
             onmouseout="this.style.boxShadow='0 1px 2px rgba(0,0,0,0.05)'; this.style.borderColor='#e2e8f0'">
            
            {{-- Baris Atas: Nomor Agenda & Status --}}
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <span style="font-size: 0.75rem; font-family: monospace; background: #e0f2fe; color: #0369a1; padding: 0.2rem 0.5rem; border-radius: 0.25rem;">
                        {{ $doc->agenda_number ?? '-' }}
                    </span>
                    <span style="font-size: 0.75rem; background: #fef3c7; color: #92400e; padding: 0.2rem 0.6rem; border-radius: 9999px; font-weight: 600;">
                        Menunggu Disposisi Ketua
                    </span>
                </div>
            </div>

            {{-- Judul Surat --}}
            <h3 style="font-size: 1.1rem; font-weight: 700; color: #0f172a; margin: 0 0 1rem 0; line-height: 1.4;">
                {{ $doc->subject ?? $doc->title }}
            </h3>

            {{-- Grid Informasi Surat --}}
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
                {{-- Pengirim --}}
                <div style="background: #f8fafc; padding: 0.75rem; border-radius: 0.5rem;">
                    <span style="display: block; font-size: 0.7rem; color: #64748b; margin-bottom: 0.25rem;">Pengirim</span>
                    <span style="font-size: 0.85rem; font-weight: 500; color: #334155;">{{ $doc->sender }}</span>
                </div>
                
                {{-- No. Surat --}}
                <div style="background: #f8fafc; padding: 0.75rem; border-radius: 0.5rem;">
                    <span style="display: block; font-size: 0.7rem; color: #64748b; margin-bottom: 0.25rem;">No. Surat</span>
                    <span style="font-size: 0.85rem; font-weight: 500; color: #334155; font-family: monospace;">{{ $doc->document_number }}</span>
                </div>

                {{-- Tanggal Surat --}}
                <div style="background: #f8fafc; padding: 0.75rem; border-radius: 0.5rem;">
                    <span style="display: block; font-size: 0.7rem; color: #64748b; margin-bottom: 0.25rem;">Tanggal Surat</span>
                    <span style="font-size: 0.85rem; font-weight: 500; color: #334155;">{{ $doc->document_date ? \Carbon\Carbon::parse($doc->document_date)->locale('id')->translatedFormat('d M Y') : '-' }}</span>
                </div>

                {{-- Saran Sekretaris --}}
                <div style="background: #f8fafc; padding: 0.75rem; border-radius: 0.5rem;">
                    <span style="display: block; font-size: 0.7rem; color: #64748b; margin-bottom: 0.25rem;">Saran Sekretaris</span>
                    <span style="font-size: 0.85rem; font-weight: 500; color: #334155;">{{ Str::limit($doc->suggestion, 50) }}</span>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div style="border-top: 1px solid #f1f5f9; padding-top: 1rem; display: flex; justify-content: flex-end;">
                <a href="{{ route('sidongan.documents.show', $doc) }}" style="display: inline-flex; align-items: center; gap: 0.5rem; color: #2563eb; text-decoration: none; font-size: 0.9rem; font-weight: 600;" onmouseover="this.style.color='#1d4ed8'" onmouseout="this.style.color='#2563eb'">
                    <i class="fas fa-eye"></i>
                    Lihat Detail & Disposisi →
                </a>
            </div>
        </div>
        @empty
        <div style="background: white; border-radius: 0.75rem; border: 1px solid #e2e8f0; padding: 4rem; text-align: center;">
            <i class="fas fa-check-double" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 1rem;"></i>
            <h3 style="font-size: 1.125rem; font-weight: 700; color: #1e293b; margin: 0 0 0.5rem 0;">Semua Surat Sudah Didisposisi</h3>
            <p style="font-size: 0.875rem; color: #64748b;">Tidak ada surat yang menunggu tindakan saat ini.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection