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
    {{-- HEADER (Style sama seperti Detail Surat) --}}
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

    {{-- LAYOUT 2 KOLOM (Responsive) --}}
    <div class="responsive-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; align-items: start;">
        
        {{-- KOLOM KIRI: Detail Surat --}}
        @if($document)
        <div class="detail-surat-card" style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; overflow: hidden; position: sticky; top: 1rem;">
            {{-- Header Detail Surat --}}
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

            {{-- Content Detail Surat --}}
            <div style="padding: 1.5rem;">
                {{-- Data Surat --}}
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

                {{-- Data Agenda --}}
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

                {{-- Saran Sekretaris --}}
                <div style="margin-bottom: 1.5rem;">
                    <span style="display: block; font-size: 0.75rem; color: #64748b; margin-bottom: 0.5rem;">Saran Sekretaris:</span>
                    <div style="background: #eff6ff; border-radius: 0.5rem; padding: 0.85rem; font-size: 0.85rem; color: #1e40af; border: 1px solid #bfdbfe;">
                        {{ $document->suggestion ?? '-' }}
                    </div>
                </div>

                {{-- Lampiran Surat --}}
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

                {{-- Disposisi Ketua --}}
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
                
                {{-- Hidden field untuk document_id --}}
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

                    {{-- Tanggal Kegiatan --}}
                    <div style="margin-bottom: 1.25rem;">
                        <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem;">
                            Tanggal Kegiatan <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="date" name="kegiatan_tanggal" required value="{{ old('kegiatan_tanggal') }}"
                               style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.875rem; transition: all 0.2s; box-sizing: border-box;" 
                               onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'" 
                               onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                        @error('kegiatan_tanggal') <p style="font-size: 0.75rem; color: #ef4444; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                    </div>

                    {{-- Lokasi --}}
                    <div style="margin-bottom: 1.25rem;">
                        <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem;">
                            Lokasi Kegiatan
                        </label>
                        <input type="text" name="lokasi" placeholder="Contoh: Aula PKK Kabupaten Toba" value="{{ old('lokasi') }}"
                               style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.875rem; transition: all 0.2s; box-sizing: border-box;" 
                               onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'" 
                               onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
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

                    {{-- Buttons: Reset (Kiri) & Batal/Kirim (Kanan) --}}
                    <div class="btn-group" style="display: flex; justify-content: space-between; align-items: center; padding-top: 1.25rem; border-top: 1px solid #e2e8f0;">
                        
                        {{-- Kiri: Reset --}}
                        <button type="reset" 
                                style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.25rem; background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-weight: 600; cursor: pointer; transition: all 0.2s;" 
                                onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                            <i class="fas fa-sync-alt"></i>
                            <span>Reset</span>
                        </button>

                        {{-- Kanan: Batal & Kirim Laporan --}}
                        <div style="display: flex; gap: 0.75rem;">
                            <a href="{{ route('sidongan.lapor_kegiatan.index') }}" 
                               style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.25rem; background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-weight: 600; text-decoration: none; transition: all 0.2s;" 
                               onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="btn-submit"
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
    // Drag & drop untuk foto
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const fileList = document.getElementById('fileList');
    
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
    
    function updateFileList() {
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
</script>
@endsection