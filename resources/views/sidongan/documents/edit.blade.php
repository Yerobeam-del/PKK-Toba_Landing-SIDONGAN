@extends('sidongan.layouts.app')
@section('title', 'Edit Dokumen - SIDONGAN')

@section('content')
@php
    $currentUser = auth()->guard('sidongan')->user();
@endphp

<div>
    {{-- Header --}}
    <div style="background: linear-gradient(135deg, #0891b2, #14b8a6); padding: 1.5rem; border-radius: 0.75rem; margin-bottom: 1.5rem; color: white;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="font-size: 1.25rem; font-weight: 700; margin: 0 0 0.25rem 0;">Edit Dokumen</h1>
                <p style="font-size: 0.875rem; opacity: 0.9; margin: 0;">Update informasi dokumen</p>
            </div>
            <a href="{{ route('sidongan.documents.index') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1rem; background: rgba(255,255,255,0.2); color: white; text-decoration: none; border-radius: 0.5rem; font-weight: 600; transition: all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <form action="{{ route('sidongan.documents.update', $document) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="delete_file" id="deleteFileInput" value="0">

        <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; padding: 1.5rem; margin-bottom: 1.5rem;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                {{-- Kolom Kiri: Data Surat --}}
                <div style="display: grid; gap: 1.25rem;">
                    <h3 style="font-size: 0.875rem; font-weight: 700; color: #0891b2; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-envelope" style="color: #14b8a6;"></i> Data Surat
                    </h3>
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #475569; margin-bottom: 0.5rem;">Pengirim <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="sender" value="{{ old('sender', $document->sender) }}" required style="width: 100%; padding: 0.625rem 0.875rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.875rem; transition: all 0.2s;" onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'" onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                        @error('sender') <p style="font-size: 0.75rem; color: #ef4444; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #475569; margin-bottom: 0.5rem;">Nomor Surat <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="document_number" value="{{ old('document_number', $document->document_number) }}" required style="width: 100%; padding: 0.625rem 0.875rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.875rem; transition: all 0.2s;" onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'" onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                        @error('document_number') <p style="font-size: 0.75rem; color: #ef4444; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #475569; margin-bottom: 0.5rem;">Tanggal Surat <span style="color: #ef4444;">*</span></label>
                        <input type="date" name="document_date" value="{{ old('document_date', $document->document_date?->format('Y-m-d')) }}" required style="width: 100%; padding: 0.625rem 0.875rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.875rem; transition: all 0.2s;" onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'" onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                        @error('document_date') <p style="font-size: 0.75rem; color: #ef4444; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #475569; margin-bottom: 0.5rem;">Perihal <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="subject" value="{{ old('subject', $document->subject) }}" required style="width: 100%; padding: 0.625rem 0.875rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.875rem; transition: all 0.2s;" onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'" onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                        @error('subject') <p style="font-size: 0.75rem; color: #ef4444; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Kolom Kanan: Data Agenda --}}
                <div style="display: grid; gap: 1.25rem;">
                    <h3 style="font-size: 0.875rem; font-weight: 700; color: #0891b2; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-clipboard-list" style="color: #14b8a6;"></i> Data Agenda
                    </h3>
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #475569; margin-bottom: 0.5rem;">Nomor Agenda</label>
                        <input type="text" value="{{ $document->agenda_number ?? 'Belum ada' }}" readonly style="width: 100%; padding: 0.625rem 0.875rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.875rem; background: #f8fafc; color: #64748b; cursor: not-allowed; font-family: monospace;">
                        <p style="font-size: 0.75rem; color: #94a3b8; margin-top: 0.25rem;">Nomor agenda tidak dapat diubah</p>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #475569; margin-bottom: 0.5rem;">Saran Sekretaris</label>
                        <textarea name="suggestion" rows="4" style="width: 100%; padding: 0.625rem 0.875rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.875rem; resize: vertical; transition: all 0.2s;" onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'" onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">{{ old('suggestion', $document->suggestion) }}</textarea>
                        @error('suggestion') <p style="font-size: 0.75rem; color: #ef4444; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- ✅ LAMPIRAN FILE (Diperbarui: Menampilkan file saat ini + upload) --}}
            <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 2px solid #f1f5f9;">
                <h3 style="font-size: 0.875rem; font-weight: 700; color: #0891b2; margin: 0 0 1rem 0; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-paperclip" style="color: #14b8a6;"></i> Lampiran File
                </h3>

                {{-- Tampilkan File Saat Ini (Jika Ada) --}}
                @if($document->file_path)
                <div id="currentFileCard" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 1rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 1rem; transition: all 0.2s;">
                    <div style="width: 3rem; height: 3rem; background: #fee2e2; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fas fa-file-pdf" style="color: #ef4444; font-size: 1.25rem;"></i>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <p style="font-size: 0.875rem; font-weight: 600; color: #0f172a; margin: 0 0 0.125rem 0;">{{ $document->file_name }}</p>
                        <p style="font-size: 0.75rem; color: #64748b; margin: 0;">{{ $document->file_size ? round($document->file_size / 1024, 2) . ' KB' : 'File saat ini' }}</p>
                    </div>
                    <div style="display: flex; gap: 0.5rem;">
                        <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" style="display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.4rem 0.8rem; background: #dbeafe; color: #2563eb; text-decoration: none; border-radius: 0.375rem; font-size: 0.75rem; font-weight: 600;" onmouseover="this.style.background='#bfdbfe'" onmouseout="this.style.background='#dbeafe'">
                            <i class="fas fa-external-link-alt"></i> Buka
                        </a>
                        <button type="button" onclick="confirmDeleteFile(event)" style="display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.4rem 0.8rem; background: #fee2e2; color: #ef4444; border: none; border-radius: 0.375rem; font-size: 0.75rem; font-weight: 600; cursor: pointer;" onmouseover="this.style.background='#fecaca'" onmouseout="this.style.background='#fee2e2'">
                            <i class="fas fa-trash-alt"></i> Hapus
                        </button>
                    </div>
                </div>
                @endif

                {{-- Upload Area untuk Ganti File --}}
                <div style="position: relative;">
                    <label style="display: block; font-size: 0.8rem; font-weight: 500; color: #475569; margin-bottom: 0.375rem;">
                        @if($document->file_path) Ganti File (Opsional) @else Upload Surat (PDF/Gambar) @endif
                    </label>
                    <div id="dropZone" style="border: 2px dashed #e2e8f0; border-radius: 0.5rem; padding: 2rem; text-align: center; cursor: pointer; transition: all 0.2s; min-height: 150px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                        <div id="uploadPlaceholder">
                            <i class="fas fa-cloud-upload-alt" style="font-size: 2rem; color: #94a3b8; margin-bottom: 0.5rem;"></i>
                            <p style="font-size: 0.875rem; color: #64748b; margin: 0 0 0.25rem 0; font-weight: 500;">Klik untuk memilih file atau seret file ke sini</p>
                            <p style="font-size: 0.75rem; color: #94a3b8; margin: 0;">PDF, JPG, PNG, DOC, DOCX (Maks. 5MB)</p>
                        </div>
                        <div id="filePreview" style="display: none; width: 100%;">
                            <div style="background: #f0fdf4; border: 2px solid #10b981; border-radius: 0.5rem; padding: 1rem;">
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div id="fileIcon" style="width: 36px; height: 36px; background: white; border-radius: 0.375rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        <i class="fas fa-file-pdf" style="color: #ef4444; font-size: 1.25rem;"></i>
                                    </div>
                                    <div style="flex: 1; text-align: left; overflow: hidden;">
                                        <p id="fileName" style="font-size: 0.8rem; font-weight: 600; color: #0f172a; margin: 0 0 0.125rem 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">nama_file.pdf</p>
                                        <p id="fileSize" style="font-size: 0.7rem; color: #64748b; margin: 0;">0 KB</p>
                                    </div>
                                    <span style="display: inline-flex; align-items: center; padding: 0.2rem 0.5rem; background: #10b981; color: white; border-radius: 9999px; font-size: 0.7rem; font-weight: 600;">
                                        <i class="fas fa-check" style="margin-right: 0.2rem; font-size: 0.6rem;"></i> Siap
                                    </span>
                                </div>
                            </div>
                            <button type="button" onclick="changeFile()" style="margin-top: 0.5rem; padding: 0.4rem 0.8rem; background: white; border: 1px solid #e2e8f0; color: #64748b; border-radius: 0.375rem; font-size: 0.75rem; cursor: pointer;">
                                <i class="fas fa-sync-alt" style="margin-right: 0.25rem;"></i> Ganti File
                            </button>
                        </div>
                        <input type="file" name="file" id="fileInput" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" style="display: none;">
                    </div>
                </div>
                @error('file') <p style="font-size: 0.75rem; color: #ef4444; margin-top: 0.5rem;">{{ $message }}</p> @enderror
            </div>

            {{-- Action Buttons --}}
            <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 2px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 0.75rem;">
                <a href="{{ route('sidongan.documents.index') }}" style="padding: 0.75rem 1.5rem; background: #f1f5f9; color: #475569; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">Batal</a>
                <button type="submit" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #3b82f6, #2563eb); color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(59,130,246,0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    // Drag & Drop & Preview Logic
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const uploadPlaceholder = document.getElementById('uploadPlaceholder');
    const filePreview = document.getElementById('filePreview');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const fileIcon = document.getElementById('fileIcon');

    dropZone.addEventListener('click', () => fileInput.click());
    dropZone.addEventListener('dragover', (e) => { e.preventDefault(); dropZone.style.borderColor = '#3b82f6'; dropZone.style.background = '#eff6ff'; });
    dropZone.addEventListener('dragleave', () => { dropZone.style.borderColor = '#e2e8f0'; dropZone.style.background = 'white'; });
    dropZone.addEventListener('drop', (e) => {
        e.preventDefault(); dropZone.style.borderColor = '#e2e8f0'; dropZone.style.background = 'white';
        if(e.dataTransfer.files.length > 0) { fileInput.files = e.dataTransfer.files; showFilePreview(e.dataTransfer.files[0]); }
    });
    fileInput.addEventListener('change', (e) => { if (e.target.files.length > 0) showFilePreview(e.target.files[0]); });

    function showFilePreview(file) {
        uploadPlaceholder.style.display = 'none'; filePreview.style.display = 'block';
        fileName.textContent = file.name;
        const sizeInKB = (file.size / 1024).toFixed(2);
        fileSize.textContent = sizeInKB >= 1024 ? `${(file.size / 1024 / 1024).toFixed(2)} MB` : `${sizeInKB} KB`;
        const iconElement = fileIcon.querySelector('i');
        if (file.type === 'application/pdf') { iconElement.className = 'fas fa-file-pdf'; iconElement.style.color = '#ef4444'; }
        else if (file.type.startsWith('image/')) { iconElement.className = 'fas fa-file-image'; iconElement.style.color = '#10b981'; }
        else if (file.type.includes('word') || file.name.endsWith('.doc') || file.name.endsWith('.docx')) { iconElement.className = 'fas fa-file-word'; iconElement.style.color = '#3b82f6'; }
        else { iconElement.className = 'fas fa-file'; iconElement.style.color = '#64748b'; }
        dropZone.style.borderColor = '#10b981'; dropZone.style.background = '#f0fdf4';
    }

    function changeFile() {
        uploadPlaceholder.style.display = 'block'; filePreview.style.display = 'none'; fileInput.value = '';
        dropZone.style.borderColor = '#e2e8f0'; dropZone.style.background = 'white';
        setTimeout(() => fileInput.click(), 100);
    }

    // Hapus File Logic
    function confirmDeleteFile(event) {
        event.stopPropagation();
        if (confirm('Apakah Anda yakin ingin menghapus lampiran file ini?')) {
            document.getElementById('deleteFileInput').value = '1';
            event.target.closest('form').submit();
        }
    }
</script>
@endsection