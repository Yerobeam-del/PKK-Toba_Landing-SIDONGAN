@extends('sidongan.layouts.app')
@section('title', 'Buat Laporan Kegiatan - SIDONGAN')

@section('content')
<style>
    /* Responsive Design untuk Mobile */
    @media (max-width: 768px) {
        .responsive-grid {
            grid-template-columns: 1fr !important;
            gap: 1rem !important;
        }
        
        .detail-surat-card {
            position: static !important;
            margin-bottom: 1rem;
        }
        
        .form-header h1 {
            font-size: 1.125rem !important;
        }
        
        .form-header p {
            font-size: 0.75rem !important;
        }
        
        .section-title {
            font-size: 0.8rem !important;
        }
        
        .info-row {
            flex-direction: column !important;
            gap: 0.25rem !important;
        }
        
        .info-label, .info-value {
            font-size: 0.75rem !important;
        }
        
        .btn-submit {
            width: 100% !important;
            justify-content: center !important;
        }
        
        .btn-group {
            flex-direction: column-reverse !important;
            gap: 0.5rem !important;
        }
        
        .btn-group > * {
            width: 100% !important;
        }
    }
</style>

<div style="max-width: 1400px; margin: 0 auto;">
    {{-- HEADER --}}
    <div style="background: linear-gradient(135deg, #0891b2, #14b8a6); padding: 1.25rem 1.5rem; border-radius: 0.75rem; margin-bottom: 1.5rem; color: white;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem;">
            <div>
                <h1 class="form-header" style="font-size: 1.25rem; font-weight: 700; margin: 0;">Buat Laporan Kegiatan</h1>
                <p style="font-size: 0.85rem; opacity: 0.9; margin: 0.25rem 0 0 0;">Isi data kegiatan yang telah dilaksanakan</p>
            </div>
            
            <a href="{{ route('sidongan.lapor_kegiatan.index') }}" 
               style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1rem; background: rgba(255,255,255,0.2); color: white; text-decoration: none; border-radius: 0.5rem; font-weight: 600; transition: all 0.2s; white-space: nowrap;" 
               onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Daftar</span>
            </a>
        </div>
    </div>

    {{-- LAYOUT 2 KOLOM --}}
    <div class="responsive-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; align-items: start;">
        
        {{-- KOLOM KIRI: Detail Surat --}}
        @if($document)
        <div class="detail-surat-card" style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; overflow: hidden; position: sticky; top: 1rem;">
            <div style="background: linear-gradient(135deg, #f0f9ff, #e0f2fe); padding: 1.25rem 1.5rem; border-bottom: 1px solid #bae6fd;">
                <div style="display: flex; gap: 0.75rem; align-items: center; margin-bottom: 0.75rem; flex-wrap: wrap;">
                    <span style="font-size: 0.75rem; font-family: monospace; background: #0ea5e9; color: white; padding: 0.25rem 0.6rem; border-radius: 0.375rem; font-weight: 700;">
                        {{ $document->agenda_number }}
                    </span>
                    <span style="font-size: 0.75rem; padding: 0.25rem 0.75rem; border-radius: 9999px; font-weight: 600; background: #dbeafe; color: #1e40af;">
                        {{ $document->status === 'berjalan' ? 'Sedang Berjalan' : ucfirst(str_replace('_', ' ', $document->status)) }}
                    </span>
                </div>
                <h2 style="font-size: 1.125rem; font-weight: 700; color: #0c4a6e; margin: 0; line-height: 1.4;">
                    {{ $document->subject ?? $document->title }}
                </h2>
            </div>

            <div style="padding: 1.5rem;">
                <div style="margin-bottom: 1.5rem;">
                    <h3 class="section-title" style="font-size: 0.875rem; font-weight: 700; color: #0891b2; margin: 0 0 1rem 0; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-envelope" style="color: #14b8a6;"></i>
                        Data Surat
                    </h3>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <div class="info-row" style="display: flex; justify-content: space-between; padding-bottom: 0.75rem; border-bottom: 1px solid #f1f5f9;">
                            <span class="info-label" style="font-size: 0.85rem; color: #64748b;">Pengirim</span>
                            <span class="info-value" style="font-size: 0.85rem; font-weight: 500; color: #0f172a; text-align: right; max-width: 60%;">{{ $document->sender }}</span>
                        </div>
                        <div class="info-row" style="display: flex; justify-content: space-between; padding-bottom: 0.75rem; border-bottom: 1px solid #f1f5f9;">
                            <span class="info-label" style="font-size: 0.85rem; color: #64748b;">Nomor Surat</span>
                            <span class="info-value" style="font-size: 0.85rem; font-weight: 500; color: #0f172a;">{{ $document->document_number }}</span>
                        </div>
                        <div class="info-row" style="display: flex; justify-content: space-between; padding-bottom: 0.75rem; border-bottom: 1px solid #f1f5f9;">
                            <span class="info-label" style="font-size: 0.85rem; color: #64748b;">Tanggal Surat</span>
                            <span class="info-value" style="font-size: 0.85rem; font-weight: 500; color: #0f172a;">{{ $document->document_date ? \Carbon\Carbon::parse($document->document_date)->locale('id')->translatedFormat('d M Y') : '-' }}</span>
                        </div>
                        <div class="info-row" style="display: flex; justify-content: space-between;">
                            <span class="info-label" style="font-size: 0.85rem; color: #64748b;">Perihal</span>
                            <span class="info-value" style="font-size: 0.85rem; font-weight: 500; color: #0f172a; text-align: right; max-width: 60%;">{{ $document->subject }}</span>
                        </div>
                    </div>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <h3 class="section-title" style="font-size: 0.875rem; font-weight: 700; color: #0891b2; margin: 0 0 1rem 0; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-clipboard-list" style="color: #14b8a6;"></i>
                        Data Agenda
                    </h3>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <div class="info-row" style="display: flex; justify-content: space-between; padding-bottom: 0.75rem; border-bottom: 1px solid #f1f5f9;">
                            <span class="info-label" style="font-size: 0.85rem; color: #64748b;">Nomor Agenda</span>
                            <span class="info-value" style="font-size: 0.85rem; font-weight: 600; color: #3b82f6; font-family: monospace;">{{ $document->agenda_number }}</span>
                        </div>
                        <div class="info-row" style="display: flex; justify-content: space-between; padding-bottom: 0.75rem; border-bottom: 1px solid #f1f5f9;">
                            <span class="info-label" style="font-size: 0.85rem; color: #64748b;">Tanggal Agenda</span>
                            <span class="info-value" style="font-size: 0.85rem; font-weight: 500; color: #0f172a;">{{ $document->created_at->locale('id')->translatedFormat('d M Y') }}</span>
                        </div>
                        <div class="info-row" style="display: flex; justify-content: space-between;">
                            <span class="info-label" style="font-size: 0.85rem; color: #64748b;">Dibuat oleh</span>
                            <span class="info-value" style="font-size: 0.85rem; font-weight: 500; color: #0f172a;">{{ $document->creator->name ?? 'Sekretaris PKK' }}</span>
                        </div>
                    </div>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <span style="display: block; font-size: 0.75rem; color: #64748b; margin-bottom: 0.5rem;">Saran Sekretaris:</span>
                    <div style="background: #eff6ff; border-radius: 0.5rem; padding: 0.85rem; font-size: 0.85rem; color: #1e40af; border: 1px solid #bfdbfe;">
                        {{ $document->suggestion ?? '-' }}
                    </div>
                </div>

                @if($document->file_path)
                <div style="margin-bottom: 1.5rem;">
                    <h3 class="section-title" style="font-size: 0.875rem; font-weight: 700; color: #0891b2; margin: 0 0 0.75rem 0; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-paperclip" style="color: #14b8a6;"></i>
                        Lampiran Surat
                    </h3>
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 1rem;">
                        <div style="display: flex; align-items: center; gap: 1rem;">
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
                            <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" 
                               style="display: inline-flex; align-items: center; justify-content: center; width: 2.5rem; height: 2.5rem; background: #dbeafe; color: #2563eb; border-radius: 0.375rem; text-decoration: none; transition: all 0.2s; flex-shrink: 0;"
                               onmouseover="this.style.background='#bfdbfe'; this.style.transform='translateY(-2px)'" 
                               onmouseout="this.style.background='#dbeafe'; this.style.transform='translateY(0)'"
                               title="Lihat Dokumen">
                                <i class="fas fa-eye" style="font-size: 0.875rem;"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                @if($document->disposisi_data)
                    @php
                        $dispo = is_string($document->disposisi_data) ? json_decode($document->disposisi_data, true) : $document->disposisi_data;
                    @endphp
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 1rem;">
                        <h3 class="section-title" style="font-size: 0.875rem; font-weight: 700; color: #0891b2; margin: 0 0 0.75rem 0; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-share-alt" style="color: #14b8a6;"></i>
                            Disposisi Ketua
                        </h3>
                        
                        <div style="margin-bottom: 0.75rem;">
                            <span style="display: block; font-size: 0.75rem; color: #64748b; margin-bottom: 0.35rem;">Didisposisikan ke:</span>
                            <div style="display: flex; flex-wrap: wrap; gap: 0.35rem;">
                                @if(isset($dispo['target_roles']))
                                    @foreach($dispo['target_roles'] as $role)
                                    <span style="display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.25rem 0.6rem; background: #dbeafe; color: #1e40af; border-radius: 0.375rem; font-size: 0.7rem; font-weight: 600;">
                                        <i class="fas fa-users" style="font-size: 0.6rem;"></i>
                                        {{ ucfirst(str_replace('_', ' ', $role)) }}
                                    </span>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        
                        @if(isset($dispo['action']))
                        <div style="margin-bottom: 0.75rem;">
                            <span style="display: block; font-size: 0.75rem; color: #64748b; margin-bottom: 0.35rem;">Tindakan:</span>
                            <span style="display: inline-block; padding: 0.25rem 0.6rem; background: #f3e8ff; color: #7c3aed; border-radius: 0.375rem; font-size: 0.7rem; font-weight: 600;">
                                {{ $dispo['action'] }}
                            </span>
                        </div>
                        @endif
                        
                        @if(isset($dispo['comment']) && $dispo['comment'])
                        <div>
                            <span style="display: block; font-size: 0.75rem; color: #64748b; margin-bottom: 0.35rem;">Komentar:</span>
                            <div style="background: white; border: 1px solid #e2e8f0; border-radius: 0.375rem; padding: 0.6rem; font-size: 0.8rem; color: #475569; font-style: italic;">
                                {{ $dispo['comment'] }}
                            </div>
                        </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
        @endif

        {{-- KOLOM KANAN: Form Laporan Kegiatan --}}
        <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; overflow: hidden;">
            <div style="padding: 1.25rem 1.5rem; background: linear-gradient(135deg, #dcfce7, #bbf7d0); border-bottom: 1px solid #86efac;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 2rem; height: 2rem; background: #16a34a; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-plus" style="color: white; font-size: 0.875rem;"></i>
                    </div>
                    <div>
                        <h2 class="form-header" style="font-size: 1rem; font-weight: 700; color: #1e293b; margin: 0;">Formulir Laporan Kegiatan</h2>
                        <p style="font-size: 0.75rem; color: #166534; margin: 0;">Lengkapi semua informasi kegiatan</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('sidongan.lapor_kegiatan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                @if($document)
                <input type="hidden" name="document_id" value="{{ $document->id }}">
                @endif

                <div style="padding: 1.5rem;">
                    {{-- Nama Kegiatan --}}
                    <div style="margin-bottom: 1.25rem;">
                        <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem;">
                            Nama Kegiatan <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" name="kegiatan_nama" placeholder="Contoh: Rapat Koordinasi Bulanan" required 
                            value="{{ old('kegiatan_nama', $document->subject ?? '') }}"
                            style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.875rem; transition: all 0.2s; box-sizing: border-box;" 
                            onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'" 
                            onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                        @error('kegiatan_nama') <p style="font-size: 0.75rem; color: #ef4444; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                    </div>

                    {{-- Tanggal dan Jam Kegiatan --}}
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
                        <div>
                            <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem;">
                                Tanggal Kegiatan <span style="color: #ef4444;">*</span>
                            </label>
                            <input type="date" name="kegiatan_tanggal" required value="{{ old('kegiatan_tanggal') }}"
                                style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.875rem; transition: all 0.2s; box-sizing: border-box;" 
                                onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'" 
                                onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                            @error('kegiatan_tanggal') <p style="font-size: 0.75rem; color: #ef4444; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem;">
                                Jam Mulai <span style="color: #ef4444;">*</span>
                            </label>
                            <input type="time" name="start_time" required value="{{ old('start_time') }}"
                                style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.875rem; transition: all 0.2s; box-sizing: border-box;" 
                                onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'" 
                                onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                            @error('start_time') <p style="font-size: 0.75rem; color: #ef4444; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div style="margin-bottom: 1.25rem;">
                        <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem;">
                            Jam Selesai <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="time" name="end_time" required value="{{ old('end_time') }}"
                            style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.875rem; transition: all 0.2s; box-sizing: border-box;" 
                            onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'" 
                            onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                        @error('end_time') <p style="font-size: 0.75rem; color: #ef4444; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                    </div>

                    {{-- Lokasi Kegiatan - PERBAIKAN: HAPUS HIDDEN FIELDS --}}
                    <div style="margin-bottom: 1.5rem; padding: 1.25rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.5rem;">
                        <h3 style="font-size: 0.875rem; font-weight: 700; color: #0891b2; margin: 0 0 1rem 0; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-map-marker-alt" style="color: #14b8a6;"></i>
                            Lokasi Kegiatan
                        </h3>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                            <div>
                                <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem;">
                                    Provinsi <span style="color: #ef4444;">*</span>
                                </label>
                                <select name="provinsi" id="provinsiSelect" required
                                        style="width: 100%; padding: 0.75rem 2.5rem 0.75rem 1rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.875rem; background: white; box-sizing: border-box; cursor: pointer; appearance: none; -webkit-appearance: none; background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E&quot;); background-repeat: no-repeat; background-position: right 0.75rem center; background-size: 16px;"
                                        onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'" 
                                        onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                                    <option value="">Memuat data provinsi...</option>
                                </select>
                                @error('provinsi') <p style="font-size: 0.75rem; color: #ef4444; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem;">
                                    Kab/Kota <span style="color: #ef4444;">*</span>
                                </label>
                                <select name="kabupaten" id="kabupatenSelect" required
                                        style="width: 100%; padding: 0.75rem 2.5rem 0.75rem 1rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.875rem; background: white; box-sizing: border-box; cursor: pointer; appearance: none; -webkit-appearance: none; background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E&quot;); background-repeat: no-repeat; background-position: right 0.75rem center; background-size: 16px;"
                                        onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'" 
                                        onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                                    <option value="">Pilih provinsi terlebih dahulu</option>
                                </select>
                                @error('kabupaten') <p style="font-size: 0.75rem; color: #ef4444; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                            <div>
                                <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem;">
                                    Kecamatan <span style="color: #ef4444;">*</span>
                                </label>
                                <select name="kecamatan" id="kecamatanSelect" required
                                        style="width: 100%; padding: 0.75rem 2.5rem 0.75rem 1rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.875rem; background: white; box-sizing: border-box; cursor: pointer; appearance: none; -webkit-appearance: none; background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E&quot;); background-repeat: no-repeat; background-position: right 0.75rem center; background-size: 16px;"
                                        onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'" 
                                        onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                                    <option value="">Pilih kabupaten/kota terlebih dahulu</option>
                                </select>
                                @error('kecamatan') <p style="font-size: 0.75rem; color: #ef4444; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem;">
                                    Kelurahan/Desa <span style="color: #ef4444;">*</span>
                                </label>
                                <select name="kelurahan" id="kelurahanSelect" required
                                        style="width: 100%; padding: 0.75rem 2.5rem 0.75rem 1rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.875rem; background: white; box-sizing: border-box; cursor: pointer; appearance: none; -webkit-appearance: none; background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E&quot;); background-repeat: no-repeat; background-position: right 0.75rem center; background-size: 16px;"
                                        onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'" 
                                        onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                                    <option value="">Pilih kecamatan terlebih dahulu</option>
                                </select>
                                @error('kelurahan') <p style="font-size: 0.75rem; color: #ef4444; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem;">
                                Alamat Lengkap <span style="color: #ef4444;">*</span>
                            </label>
                            <textarea name="alamat_lengkap" rows="3" required
                                    placeholder="Masukkan alamat lengkap kegiatan"
                                    style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.875rem; resize: vertical; box-sizing: border-box;"
                                    onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'" 
                                    onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">{{ old('alamat_lengkap') }}</textarea>
                            @error('alamat_lengkap') <p style="font-size: 0.75rem; color: #ef4444; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Deskripsi --}}
                    <div style="margin-bottom: 1.25rem;">
                        <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem;">
                            Deskripsi Kegiatan <span style="color: #ef4444;">*</span>
                        </label>
                        <textarea name="deskripsi" rows="4" placeholder="Jelaskan detail kegiatan yang dilaksanakan, peserta, hasil yang dicapai, dll..." required
                                style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.875rem; transition: all 0.2s; resize: vertical; box-sizing: border-box;" 
                                onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'" 
                                onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi') <p style="font-size: 0.75rem; color: #ef4444; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                    </div>

                    {{-- Dokumentasi Foto --}}
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem;">
                            Dokumentasi Kegiatan (Foto)
                        </label>
                        <div id="dropZone" style="border: 2px dashed #e2e8f0; border-radius: 0.5rem; padding: 1.5rem; text-align: center; cursor: pointer; transition: all 0.2s;">
                            <i class="fas fa-camera" style="font-size: 1.75rem; color: #94a3b8; margin-bottom: 0.5rem;"></i>
                            <p style="font-size: 0.8rem; color: #64748b; margin: 0;">Klik atau seret foto ke sini</p>
                            <p style="font-size: 0.7rem; color: #94a3b8; margin-top: 0.35rem;">JPG, PNG, HEIC (Maks. 5MB)</p>
                            <input type="file" name="fotos[]" id="fileInput" accept="image/*, .heic" multiple style="display: none;">
                        </div>
                        <div id="fileList" style="margin-top: 0.75rem;"></div>
                        @error('fotos.*') <p style="font-size: 0.75rem; color: #ef4444; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                    </div>

                    {{-- Buttons --}}
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 1.25rem; border-top: 1px solid #e2e8f0;">
                        <button type="reset" 
                                style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.25rem; background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-weight: 600; cursor: pointer; transition: all 0.2s;" 
                                onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                            <i class="fas fa-sync-alt"></i>
                            <span>Reset</span>
                        </button>

                        <div style="display: flex; gap: 0.75rem;">
                            <a href="{{ route('sidongan.lapor_kegiatan.index') }}" 
                            style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.25rem; background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-weight: 600; text-decoration: none; transition: all 0.2s;" 
                            onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                                Batal
                            </a>
                            <button type="submit" 
                                    style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #22c55e, #16a34a); color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer; transition: all 0.2s; box-shadow: 0 2px 4px rgba(34,197,94,0.2);" 
                                    onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(34,197,94,0.3)'" 
                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(34,197,94,0.2)'">
                                <i class="fas fa-paper-plane"></i>
                                <span>Kirim Laporan</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Drag & Drop untuk Foto
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const fileList = document.getElementById('fileList');
    
    if (dropZone && fileInput) {
        dropZone.addEventListener('click', () => fileInput.click());
        
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.style.borderColor = '#3b82f6';
            dropZone.style.background = '#eff6ff';
        });
        
        dropZone.addEventListener('dragleave', () => {
            dropZone.style.borderColor = '#e2e8f0';
            dropZone.style.background = 'white';
        });
        
        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.style.borderColor = '#e2e8f0';
            dropZone.style.background = 'white';
            if(e.dataTransfer.files.length > 0) {
                fileInput.files = e.dataTransfer.files;
                updateFileList();
            }
        });
        
        fileInput.addEventListener('change', updateFileList);
    }
    
    function updateFileList() {
        if (!fileList) return;
        fileList.innerHTML = '';
        const files = fileInput.files;
        
        if (files.length > 0) {
            const listDiv = document.createElement('div');
            listDiv.style.cssText = 'background: #f0fdf4; border: 1px solid #10b981; border-radius: 0.5rem; padding: 0.75rem;';
            
            const title = document.createElement('p');
            title.style.cssText = 'font-size: 0.8rem; font-weight: 600; color: #059669; margin: 0 0 0.35rem 0;';
            title.textContent = `${files.length} file:`;
            listDiv.appendChild(title);
            
            Array.from(files).forEach((file, index) => {
                const fileItem = document.createElement('div');
                fileItem.style.cssText = 'font-size: 0.7rem; color: #047857; padding: 0.15rem 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;';
                fileItem.textContent = `${index + 1}. ${file.name}`;
                listDiv.appendChild(fileItem);
            });
            
            fileList.appendChild(listDiv);
        }
    }

    // Dynamic Location Dropdowns
    const provinsiSelect = document.getElementById('provinsiSelect');
    const kabupatenSelect = document.getElementById('kabupatenSelect');
    const kecamatanSelect = document.getElementById('kecamatanSelect');
    const kelurahanSelect = document.getElementById('kelurahanSelect');
    
    // Load Provinces
    async function loadProvinces() {
        if (!provinsiSelect) return;
        
        try {
            provinsiSelect.innerHTML = '<option value="">Memuat data provinsi...</option>';
            
            const response = await fetch('/api/v1/wilayah/proxy/provinces');
            const result = await response.json();
            
            if (result.success && result.data) {
                provinsiSelect.innerHTML = '<option value="">Pilih Provinsi</option>';
                
                result.data.forEach(province => {
                    const option = document.createElement('option');
                    option.value = province.name; // Gunakan NAME
                    option.textContent = province.name;
                    provinsiSelect.appendChild(option);
                });
            } else {
                provinsiSelect.innerHTML = '<option value="">Gagal memuat data provinsi</option>';
            }
        } catch (error) {
            console.error('Error loading provinces:', error);
            provinsiSelect.innerHTML = '<option value="">Error memuat data provinsi</option>';
        }
    }
    
    // Load Regencies
    async function loadRegencies() {
        if (!kabupatenSelect || !provinsiSelect.value) {
            if (kabupatenSelect) kabupatenSelect.innerHTML = '<option value="">Pilih provinsi terlebih dahulu</option>';
            return;
        }
        
        try {
            kabupatenSelect.innerHTML = '<option value="">Memuat data kabupaten/kota...</option>';
            
            // Cari province code
            const provResponse = await fetch('/api/v1/wilayah/proxy/provinces');
            const provResult = await provResponse.json();
            const province = provResult.data.find(p => p.name === provinsiSelect.value);
            
            if (!province) {
                kabupatenSelect.innerHTML = '<option value="">Provinsi tidak ditemukan</option>';
                return;
            }
            
            const response = await fetch(`/api/v1/wilayah/proxy/regencies/${province.code}`);
            const result = await response.json();
            
            if (result.success && result.data) {
                kabupatenSelect.innerHTML = '<option value="">Pilih Kab/Kota</option>';
                
                result.data.forEach(regency => {
                    const option = document.createElement('option');
                    option.value = regency.name; // Gunakan NAME
                    option.textContent = regency.name;
                    kabupatenSelect.appendChild(option);
                });
            } else {
                kabupatenSelect.innerHTML = '<option value="">Gagal memuat data kabupaten/kota</option>';
            }
        } catch (error) {
            console.error('Error loading regencies:', error);
            kabupatenSelect.innerHTML = '<option value="">Error memuat data kabupaten/kota</option>';
        }
    }
    
    // Load Districts
    async function loadDistricts() {
        if (!kecamatanSelect || !kabupatenSelect.value) {
            if (kecamatanSelect) kecamatanSelect.innerHTML = '<option value="">Pilih kabupaten/kota terlebih dahulu</option>';
            return;
        }
        
        try {
            kecamatanSelect.innerHTML = '<option value="">Memuat data kecamatan...</option>';
            
            // Cari regency code
            const provResponse = await fetch('/api/v1/wilayah/proxy/provinces');
            const provResult = await provResponse.json();
            const province = provResult.data.find(p => p.name === provinsiSelect.value);
            
            const regResponse = await fetch(`/api/v1/wilayah/proxy/regencies/${province.code}`);
            const regResult = await regResponse.json();
            const regency = regResult.data.find(r => r.name === kabupatenSelect.value);
            
            if (!regency) {
                kecamatanSelect.innerHTML = '<option value="">Kabupaten tidak ditemukan</option>';
                return;
            }
            
            const response = await fetch(`/api/v1/wilayah/proxy/districts/${regency.code}`);
            const result = await response.json();
            
            if (result.success && result.data) {
                kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                
                result.data.forEach(district => {
                    const option = document.createElement('option');
                    option.value = district.name; // Gunakan NAME
                    option.textContent = district.name;
                    kecamatanSelect.appendChild(option);
                });
            } else {
                kecamatanSelect.innerHTML = '<option value="">Gagal memuat data kecamatan</option>';
            }
        } catch (error) {
            console.error('Error loading districts:', error);
            kecamatanSelect.innerHTML = '<option value="">Error memuat data kecamatan</option>';
        }
    }
    
    // Load Villages
    async function loadVillages() {
        if (!kelurahanSelect || !kecamatanSelect.value) {
            if (kelurahanSelect) kelurahanSelect.innerHTML = '<option value="">Pilih kecamatan terlebih dahulu</option>';
            return;
        }
        
        try {
            kelurahanSelect.innerHTML = '<option value="">Memuat data desa...</option>';
            
            // Cari district code
            const provResponse = await fetch('/api/v1/wilayah/proxy/provinces');
            const provResult = await provResponse.json();
            const province = provResult.data.find(p => p.name === provinsiSelect.value);
            
            const regResponse = await fetch(`/api/v1/wilayah/proxy/regencies/${province.code}`);
            const regResult = await regResponse.json();
            const regency = regResult.data.find(r => r.name === kabupatenSelect.value);
            
            const distResponse = await fetch(`/api/v1/wilayah/proxy/districts/${regency.code}`);
            const distResult = await distResponse.json();
            const district = distResult.data.find(d => d.name === kecamatanSelect.value);
            
            if (!district) {
                kelurahanSelect.innerHTML = '<option value="">Kecamatan tidak ditemukan</option>';
                return;
            }
            
            const response = await fetch(`/api/v1/wilayah/proxy/villages/${district.code}`);
            const result = await response.json();
            
            if (result.success && result.data) {
                kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan/Desa</option>';
                
                result.data.forEach(village => {
                    const option = document.createElement('option');
                    option.value = village.name; // Gunakan NAME
                    option.textContent = village.name;
                    kelurahanSelect.appendChild(option);
                });
            } else {
                kelurahanSelect.innerHTML = '<option value="">Gagal memuat data desa</option>';
            }
        } catch (error) {
            console.error('Error loading villages:', error);
            kelurahanSelect.innerHTML = '<option value="">Error memuat data desa</option>';
        }
    }
    
    // Event Listeners
    if (provinsiSelect) {
        provinsiSelect.addEventListener('change', loadRegencies);
    }
    
    if (kabupatenSelect) {
        kabupatenSelect.addEventListener('change', loadDistricts);
    }
    
    if (kecamatanSelect) {
        kecamatanSelect.addEventListener('change', loadVillages);
    }
    
    // Init on page load
    document.addEventListener('DOMContentLoaded', loadProvinces);
</script>
@endsection