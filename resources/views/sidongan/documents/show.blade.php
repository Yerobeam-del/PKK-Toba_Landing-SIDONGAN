@extends('sidongan.layouts.app')
@section('title', 'Detail Surat - SIDONGAN')

@section('content')
@php
    $currentUser = auth()->guard('sidongan')->user();
    $disposisiData = is_string($document->disposisi_data ?? '') ? json_decode($document->disposisi_data, true) : $document->disposisi_data;
@endphp

<div>
    {{-- Header --}}
    <div style="background: linear-gradient(135deg, #0891b2, #14b8a6); padding: 1.5rem; border-radius: 0.75rem; margin-bottom: 1.5rem; color: white;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h1 style="font-size: 1.25rem; font-weight: 700; margin: 0;">Detail Surat</h1>
            
            {{-- Action Buttons --}}
            <div style="display: flex; gap: 0.75rem;">
                <a href="{{ route('sidongan.documents.index') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1rem; background: rgba(255,255,255,0.2); color: white; text-decoration: none; border-radius: 0.5rem; font-weight: 600; transition: all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali ke Daftar</span>
                </a>
                
                {{-- Tombol Edit (Hanya untuk Sekretaris & Status Menunggu Disposisi) --}}
                @if($currentUser && $currentUser->hasSidonganRole('sekretaris') && $document->status === 'menunggu_disposisi')
                <a href="{{ route('sidongan.documents.edit', $document) }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1rem; background: white; color: #0891b2; text-decoration: none; border-radius: 0.5rem; font-weight: 600; transition: all 0.2s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.2)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                    <i class="fas fa-edit"></i>
                    <span>Edit Surat</span>
                </a>
                @endif
                
                {{-- ✅ TOMBOL DISPOSISI (Hanya untuk Ketua & Status Menunggu) --}}
                @if($currentUser && $currentUser->hasSidonganRole('ketua') && $document->status === 'menunggu_disposisi')
                <a href="{{ route('sidongan.disposisi.form', $document) }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1rem; background: #f97316; color: white; text-decoration: none; border-radius: 0.5rem; font-weight: 600; transition: all 0.2s; box-shadow: 0 2px 8px rgba(249,115,22,0.3);" onmouseover="this.style.background='#ea580c'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(249,115,22,0.4)'" onmouseout="this.style.background='#f97316'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(249,115,22,0.3)'">
                    <i class="fas fa-paper-plane"></i>
                    <span>Disposisi</span>
                </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Status & Agenda Number --}}
    <div style="background: white; border-radius: 0.75rem; padding: 1.25rem; margin-bottom: 1.5rem; border: 1px solid #e2e8f0;">
        <div style="display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap; margin-bottom: 1rem;">
            <span style="display: inline-block; padding: 0.375rem 0.75rem; background: #dbeafe; color: #1e40af; border-radius: 0.375rem; font-size: 0.75rem; font-weight: 600; font-family: monospace;">
                {{ $document->agenda_number ?? 'AG/01/2024/001' }}
            </span>
            @php
                $statusConfig = [
                    'menunggu_disposisi' => ['bg' => '#fef3c7', 'text' => '#92400e', 'label' => 'Menunggu Disposisi Ketua'],
                    'berjalan' => ['bg' => '#dbeafe', 'text' => '#1e40af', 'label' => 'Sedang Berjalan'],
                    'menunggu_verifikasi' => ['bg' => '#ede9fe', 'text' => '#6b21a8', 'label' => 'Menunggu Verifikasi'],
                    'selesai' => ['bg' => '#d1fae5', 'text' => '#065f46', 'label' => 'Selesai'],
                    'diarsipkan' => ['bg' => '#f3e8ff', 'text' => '#7c3aed', 'label' => 'Diarsipkan'],
                ];
                $status = $statusConfig[$document->status] ?? ['bg' => '#f1f5f9', 'text' => '#475569', 'label' => $document->status];
            @endphp
            <span style="display: inline-block; padding: 0.375rem 0.75rem; background: {{ $status['bg'] }}; color: {{ $status['text'] }}; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">
                {{ $status['label'] }}
            </span>
        </div>
        <h2 style="font-size: 1.25rem; font-weight: 700; color: #0f172a; margin: 0;">
            {{ $document->subject ?? $document->title }}
        </h2>
    </div>

    {{-- Main Content - 2 Columns --}}
    <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; padding: 1.5rem; margin-bottom: 1.5rem;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            {{-- Data Surat --}}
            <div>
                <h3 style="font-size: 0.875rem; font-weight: 700; color: #0891b2; margin: 0 0 1rem 0; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-envelope" style="color: #14b8a6;"></i>
                    Data Surat
                </h3>
                <div style="display: grid; gap: 1rem;">
                    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 0.75rem; padding: 0.75rem 0; border-bottom: 1px solid #f1f5f9;">
                        <span style="font-size: 0.875rem; color: #64748b;">Pengirim</span>
                        <span style="font-size: 0.875rem; color: #0f172a; font-weight: 500;">{{ $document->sender ?? '-' }}</span>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 0.75rem; padding: 0.75rem 0; border-bottom: 1px solid #f1f5f9;">
                        <span style="font-size: 0.875rem; color: #64748b;">Nomor Surat</span>
                        <span style="font-size: 0.875rem; color: #0f172a; font-weight: 500;">{{ $document->document_number ?? '-' }}</span>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 0.75rem; padding: 0.75rem 0; border-bottom: 1px solid #f1f5f9;">
                        <span style="font-size: 0.875rem; color: #64748b;">Tanggal Surat</span>
                        <span style="font-size: 0.875rem; color: #0f172a; font-weight: 500;">{{ $document->document_date ? \Carbon\Carbon::parse($document->document_date)->locale('id')->translatedFormat('d F Y') : '-' }}</span>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 0.75rem; padding: 0.75rem 0;">
                        <span style="font-size: 0.875rem; color: #64748b;">Perihal</span>
                        <span style="font-size: 0.875rem; color: #0f172a; font-weight: 500;">{{ $document->subject ?? $document->title }}</span>
                    </div>
                </div>
            </div>

            {{-- Data Agenda --}}
            <div>
                <h3 style="font-size: 0.875rem; font-weight: 700; color: #0891b2; margin: 0 0 1rem 0; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-clipboard-list" style="color: #14b8a6;"></i>
                    Data Agenda
                </h3>
                <div style="display: grid; gap: 1rem;">
                    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 0.75rem; padding: 0.75rem 0; border-bottom: 1px solid #f1f5f9;">
                        <span style="font-size: 0.875rem; color: #64748b;">Nomor Agenda</span>
                        <span style="font-size: 0.875rem; color: #3b82f6; font-weight: 600; font-family: monospace;">{{ $document->agenda_number ?? '-' }}</span>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 0.75rem; padding: 0.75rem 0; border-bottom: 1px solid #f1f5f9;">
                        <span style="font-size: 0.875rem; color: #64748b;">Tanggal Agenda</span>
                        <span style="font-size: 0.875rem; color: #0f172a; font-weight: 500;">{{ $document->created_at ? \Carbon\Carbon::parse($document->created_at)->locale('id')->translatedFormat('d F Y') : '-' }}</span>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 0.75rem; padding: 0.75rem 0; border-bottom: 1px solid #f1f5f9;">
                        <span style="font-size: 0.875rem; color: #64748b;">Dibuat oleh</span>
                        <span style="font-size: 0.875rem; color: #0f172a; font-weight: 500;">{{ $document->creator->name ?? 'Sekretaris PKK' }}</span>
                    </div>
                    <div style="display: grid; gap: 0.5rem; padding: 0.75rem 0;">
                        <span style="font-size: 0.875rem; color: #64748b;">Saran Sekretaris:</span>
                        <div style="background: #eff6ff; border-radius: 0.5rem; padding: 0.75rem; font-size: 0.875rem; color: #1e40af;">
                            {{ $document->suggestion ?? $document->description ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Lampiran Surat (UPDATED: Dengan Cek File) --}}
    @if($document->file_path)
    <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; padding: 1.5rem; margin-bottom: 1.5rem;">
        <h3 style="font-size: 0.875rem; font-weight: 700; color: #0891b2; margin: 0 0 1rem 0; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-paperclip" style="color: #14b8a6;"></i>
            Lampiran Surat
        </h3>
        <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" style="text-decoration: none; display: block;">
            <div style="background: #f8fafc; border-radius: 0.5rem; padding: 1rem; display: flex; align-items: center; gap: 1rem; transition: all 0.2s; border: 1px solid transparent;" onmouseover="this.style.background='#f1f5f9'; this.style.borderColor='#e2e8f0'" onmouseout="this.style.background='#f8fafc'; this.style.borderColor='transparent'">
                <div style="width: 3rem; height: 3rem; background: #fee2e2; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="fas fa-file-pdf" style="color: #ef4444; font-size: 1.25rem;"></i>
                </div>
                <div style="flex: 1; min-width: 0;">
                    <p style="font-size: 0.875rem; font-weight: 600; color: #0f172a; margin: 0 0 0.125rem 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ $document->file_name }}
                    </p>
                    <p style="font-size: 0.75rem; color: #64748b; margin: 0;">
                        {{ $document->file_size ? round($document->file_size / 1024, 2) . ' KB' : 'File surat' }}
                    </p>
                </div>
                <div style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1rem; background: #dbeafe; color: #2563eb; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 600;">
                    <i class="fas fa-external-link-alt"></i>
                    <span>Buka</span>
                </div>
            </div>
        </a>
    </div>
    @else
    <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; padding: 1.5rem; margin-bottom: 1.5rem; text-align: center;">
        <i class="fas fa-paperclip" style="color: #cbd5e1; font-size: 2rem; margin-bottom: 0.5rem;"></i>
        <p style="color: #64748b; margin: 0; font-size: 0.875rem;">Tidak ada lampiran file untuk surat ini.</p>
    </div>
    @endif

    {{-- Alur Kegiatan - Timeline Style --}}
    <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; padding: 1.5rem;">
        <h3 style="font-size: 0.875rem; font-weight: 700; color: #0891b2; margin: 0 0 1.5rem 0; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-stream" style="color: #14b8a6;"></i>
            Alur Kegiatan
        </h3>
        
        <div style="display: flex; flex-direction: column; gap: 0;">
            
            {{-- Timeline Item 1: Sekretaris Upload --}}
            <div style="display: flex; gap: 1.25rem; position: relative;">
                {{-- Vertical Line Container --}}
                <div style="display: flex; flex-direction: column; align-items: center; flex-shrink: 0;">
                    <div style="width: 2.5rem; height: 2.5rem; background: #3b82f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; position: relative; z-index: 2;">
                        <i class="fas fa-user" style="color: white; font-size: 0.875rem;"></i>
                    </div>
                    <div style="width: 2px; flex: 1; background: linear-gradient(to bottom, #3b82f6, #e2e8f0); min-height: 2rem; margin: 0.25rem 0;"></div>
                </div>
                
                {{-- Content --}}
                <div style="flex: 1; padding-bottom: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 0.25rem;">
                        <h4 style="font-size: 0.875rem; font-weight: 600; color: #0f172a; margin: 0;">Sekretaris PKK</h4>
                        <span style="font-size: 0.75rem; color: #94a3b8;">{{ $document->created_at->locale('id')->translatedFormat('d M Y, H.i') }}</span>
                    </div>
                    <p style="font-size: 0.875rem; color: #64748b; margin: 0;">
                        Membuat agenda dan mengupload surat dari {{ $document->sender ?? 'Pengirim' }}
                    </p>
                </div>
            </div>

            {{-- Timeline Item 2: Disposisi --}}
            @if($document->disposisi_data)
            <div style="display: flex; gap: 1.25rem; position: relative;">
                <div style="display: flex; flex-direction: column; align-items: center; flex-shrink: 0;">
                    <div style="width: 2.5rem; height: 2.5rem; background: #f97316; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; position: relative; z-index: 2;">
                        <i class="fas fa-share-alt" style="color: white; font-size: 0.875rem;"></i>
                    </div>
                    <div style="width: 2px; flex: 1; background: linear-gradient(to bottom, #f97316, #e2e8f0); min-height: 2rem; margin: 0.25rem 0;"></div>
                </div>
                
                <div style="flex: 1; padding-bottom: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 0.25rem;">
                        <h4 style="font-size: 0.875rem; font-weight: 600; color: #0f172a; margin: 0;">Ketua PKK</h4>
                        <span style="font-size: 0.75rem; color: #94a3b8;">
                            @php
                                $dispo = is_string($document->disposisi_data) ? json_decode($document->disposisi_data, true) : $document->disposisi_data;
                            @endphp
                            {{ isset($dispo['disposed_at']) ? \Carbon\Carbon::parse($dispo['disposed_at'])->locale('id')->translatedFormat('d M Y, H:i') : '-' }}
                        </span>
                    </div>
                    <p style="font-size: 0.875rem; color: #64748b; margin: 0;">
                        Melakukan disposisi kepada:
                        @if(isset($dispo['target_roles']))
                            @foreach($dispo['target_roles'] as $role)
                                <span style="display: inline-block; background: #dbeafe; color: #1e40af; padding: 0.125rem 0.5rem; border-radius: 0.25rem; font-size: 0.75rem; margin: 0.125rem;">{{ ucfirst(str_replace('_', ' ', $role)) }}</span>
                            @endforeach
                        @endif
                    </p>
                    @if(isset($dispo['comment']))
                    <p style="font-size: 0.875rem; color: #475569; margin: 0.5rem 0 0 0; font-style: italic;">
                        "{{ $dispo['comment'] }}"
                    </p>
                    @endif
                </div>
            </div>
            @endif

            {{-- Timeline Item 3: Laporan Kegiatan (Terakhir - tanpa garis bawah) --}}
            @forelse($activityReports ?? [] as $report)
            <div style="display: flex; gap: 1.25rem; position: relative;">
                <div style="display: flex; flex-direction: column; align-items: center; flex-shrink: 0;">
                    <div style="width: 2.5rem; height: 2.5rem; background: #22c55e; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; position: relative; z-index: 2;">
                        <i class="fas fa-clipboard-list" style="color: white; font-size: 0.875rem;"></i>
                    </div>
                    {{-- Tidak ada garis di bawah item terakhir --}}
                </div>
                
                <div style="flex: 1;">
                    <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 0.25rem;">
                        <h4 style="font-size: 0.875rem; font-weight: 600; color: #0f172a; margin: 0;">
                            {{ $report->creator->name ?? 'Sekretaris PKK' }}
                        </h4>
                        <span style="font-size: 0.75rem; color: #94a3b8;">
                            {{ $report->created_at->locale('id')->translatedFormat('d M Y, H:i') }}
                        </span>
                    </div>
                    <p style="font-size: 0.875rem; color: #64748b; margin: 0;">
                        Membuat laporan kegiatan: <strong style="color: #0f172a;">{{ $report->kegiatan_nama }}</strong>
                    </p>
                    @if($report->lokasi)
                    <p style="font-size: 0.8rem; color: #64748b; margin: 0.25rem 0 0 0;">
                        <i class="fas fa-map-marker-alt" style="margin-right: 0.25rem;"></i>
                        {{ $report->lokasi }}
                    </p>
                    @endif
                </div>
            </div>
            @empty
            {{-- Jika tidak ada laporan, tutup garis dari item sebelumnya --}}
            @if($document->disposisi_data)
            <div style="display: flex; gap: 1.25rem;">
                <div style="width: 2.5rem; display: flex; flex-direction: column; align-items: center; flex-shrink: 0;">
                    <div style="width: 2px; height: 2rem; background: linear-gradient(to bottom, #f97316, transparent);"></div>
                </div>
                <div style="flex: 1;"></div>
            </div>
            @endif
            @endforelse
        </div>
    </div>
</div>
@endsection