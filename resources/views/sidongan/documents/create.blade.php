@extends('sidongan.layouts.app')
@section('title', 'Buat Surat Masuk Baru - SIDONGAN')

@section('content')
@php
    // Preview nomor agenda berikutnya
    $previewAgenda = \App\Models\Document::generateAgendaNumber();
@endphp

<div style="max-width: 800px; margin: 0 auto;">
    {{-- Page Header --}}
    <div style="margin-bottom: 1.5rem;">
        <h1 style="font-size: 1.5rem; font-weight: 700; color: #1e293b; margin: 0;">Buat Surat Masuk Baru</h1>
    </div>

    {{-- Form Card --}}
    <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; overflow: hidden;">
        <form action="{{ route('sidongan.documents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Form Header --}}
            <div style="padding: 1.25rem 1.5rem; background: #f0fdf4; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; gap: 0.75rem;">
                <i class="fas fa-file-signature" style="color: #059669; font-size: 1.25rem;"></i>
                <div>
                    <h2 style="font-size: 1rem; font-weight: 700; color: #1e293b; margin: 0;">Formulir Surat Masuk</h2>
                    <p style="font-size: 0.8rem; color: #64748b; margin: 0;">Isi data surat yang diterima dari pengirim eksternal</p>
                </div>
            </div>

            <div style="padding: 1.5rem;">
                {{-- Section 1: Data Pengirim Surat --}}
                <div style="margin-bottom: 1.5rem;">
                    <h3 style="font-size: 0.9rem; font-weight: 600; color: #334155; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-user" style="color: #3b82f6; font-size: 0.875rem;"></i>
                        Data Pengirim Surat
                    </h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <label style="display: block; font-size: 0.8rem; font-weight: 500; color: #475569; margin-bottom: 0.375rem;">Pengirim Surat <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="sender" placeholder="Contoh: Bupati Toba" required value="{{ old('sender') }}"
                                style="width: 100%; padding: 0.625rem 0.875rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.875rem; transition: all 0.2s;" 
                                onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'" 
                                onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                            @error('sender') <p style="font-size: 0.75rem; color: #ef4444; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.8rem; font-weight: 500; color: #475569; margin-bottom: 0.375rem;">Tanggal Surat <span style="color: #ef4444;">*</span></label>
                            <input type="date" name="document_date" required value="{{ old('document_date') }}"
                                style="width: 100%; padding: 0.625rem 0.875rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.875rem; transition: all 0.2s;" 
                                onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'" 
                                onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                            @error('document_date') <p style="font-size: 0.75rem; color: #ef4444; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.8rem; font-weight: 500; color: #475569; margin-bottom: 0.375rem;">Nomor Surat <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="document_number" placeholder="Contoh: 123/SK/2024" required value="{{ old('document_number') }}"
                                style="width: 100%; padding: 0.625rem 0.875rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.875rem; transition: all 0.2s;" 
                                onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'" 
                                onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                            @error('document_number') <p style="font-size: 0.75rem; color: #ef4444; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.8rem; font-weight: 500; color: #475569; margin-bottom: 0.375rem;">Perihal <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="subject" placeholder="Perihal surat" required value="{{ old('subject') }}"
                                style="width: 100%; padding: 0.625rem 0.875rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.875rem; transition: all 0.2s;" 
                                onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'" 
                                onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                            @error('subject') <p style="font-size: 0.75rem; color: #ef4444; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Section 2: Data Agenda (Otomatis) --}}
                <div style="background: #eff6ff; border-radius: 0.5rem; padding: 1rem; margin-bottom: 1.5rem;">
                    <h3 style="font-size: 0.9rem; font-weight: 600; color: #334155; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-clipboard-list" style="color: #2563eb; font-size: 0.875rem;"></i>
                        Data Agenda (Otomatis)
                    </h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <label style="display: block; font-size: 0.8rem; font-weight: 500; color: #475569; margin-bottom: 0.375rem;">Nomor Agenda</label>
                            <input type="text" value="{{ $previewAgenda }}" readonly 
                                style="width: 100%; padding: 0.625rem 0.875rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.875rem; background: #f8fafc; color: #64748b; cursor: not-allowed; font-family: monospace;">
                            <small style="color: #94a3b8; display: block; margin-top: 0.25rem;">Format: AG/Bulan/Tahun/Urut</small>
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.8rem; font-weight: 500; color: #475569; margin-bottom: 0.375rem;">Tanggal Diterima</label>
                            <input type="date" name="agenda_date" value="{{ old('agenda_date', date('Y-m-d')) }}" 
                                style="width: 100%; padding: 0.625rem 0.875rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.875rem; transition: all 0.2s;" 
                                onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'" 
                                onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                        </div>
                    </div>
                </div>

                {{-- Saran Sekretaris --}}
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.8rem; font-weight: 500; color: #475569; margin-bottom: 0.375rem;">Saran Sekretaris <span style="color: #ef4444;">*</span></label>
                    <textarea name="suggestion" rows="3" placeholder="Masukkan saran atau catatan untuk Ketua PKK..." required 
                        style="width: 100%; padding: 0.625rem 0.875rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.875rem; transition: all 0.2s; resize: vertical;" 
                        onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'" 
                        onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">{{ old('suggestion') }}</textarea>
                    @error('suggestion') <p style="font-size: 0.75rem; color: #ef4444; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                </div>

                {{-- Upload Surat --}}
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.8rem; font-weight: 500; color: #475569; margin-bottom: 0.375rem;">Upload Surat (PDF/Gambar) <span style="color: #ef4444;">*</span></label>
                    <div id="dropZone" style="border: 2px dashed #e2e8f0; border-radius: 0.5rem; padding: 2rem; text-align: center; cursor: pointer; transition: all 0.2s; min-height: 200px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                        
                        {{-- Default State --}}
                        <div id="uploadPlaceholder">
                            <i class="fas fa-cloud-upload-alt" style="font-size: 3rem; color: #94a3b8; margin-bottom: 1rem;"></i>
                            <p style="font-size: 0.95rem; color: #475569; margin: 0 0 0.5rem 0; font-weight: 600;">Klik untuk memilih file atau seret file ke sini</p>
                            <p style="font-size: 0.75rem; color: #94a3b8; margin: 0;">PDF, JPG, PNG (Maks. 5MB)</p>
                        </div>

                        {{-- File Preview State --}}
                        <div id="filePreview" style="display: none; width: 100%;">
                            <div style="background: #f0fdf4; border: 2px solid #10b981; border-radius: 0.5rem; padding: 1.5rem;">
                                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                                    <div id="fileIcon" style="width: 48px; height: 48px; background: white; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        <i class="fas fa-file-pdf" style="color: #ef4444; font-size: 1.5rem;"></i>
                                    </div>
                                    <div style="flex: 1; text-align: left; overflow: hidden;">
                                        <p id="fileName" style="font-size: 0.9rem; font-weight: 600; color: #0f172a; margin: 0 0 0.25rem 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">nama_file.pdf</p>
                                        <p id="fileSize" style="font-size: 0.75rem; color: #64748b; margin: 0;">0 KB</p>
                                    </div>
                                    <div style="flex-shrink: 0;">
                                        <span style="display: inline-flex; align-items: center; padding: 0.25rem 0.75rem; background: #10b981; color: white; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">
                                            <i class="fas fa-check" style="margin-right: 0.25rem;"></i>
                                            Siap
                                        </span>
                                    </div>
                                </div>
                                <div style="background: white; border-radius: 0.25rem; padding: 0.25rem;">
                                    <div style="height: 4px; background: #10b981; border-radius: 0.25rem; width: 100%;"></div>
                                </div>
                            </div>
                            <button type="button" onclick="changeFile()" style="margin-top: 0.75rem; padding: 0.5rem 1rem; background: white; border: 1px solid #e2e8f0; color: #64748b; border-radius: 0.375rem; font-size: 0.8rem; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#f8fafc'; this.style.color='#334155'" onmouseout="this.style.background='white'; this.style.color='#64748b'">
                                <i class="fas fa-sync-alt" style="margin-right: 0.375rem;"></i>
                                Ganti File
                            </button>
                        </div>

                        <input type="file" name="file" id="fileInput" accept=".pdf,.jpg,.jpeg,.png" required style="display: none;">
                    </div>
                    @error('file') <p style="font-size: 0.75rem; color: #ef4444; margin-top: 0.5rem;">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Footer Actions --}}
            <div style="padding: 1rem 1.5rem; background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 0.75rem;">
                <a href="{{ route('sidongan.documents.index') }}" style="padding: 0.625rem 1.25rem; background: #f1f5f9; color: #475569; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 500; text-decoration: none; transition: all 0.2s; border: 1px solid #e2e8f0;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                    Batal
                </a>
                <button type="submit" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1.25rem; background: linear-gradient(135deg, #3b82f6, #2563eb); color: white; border: none; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 600; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(59,130,246,0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                    <i class="fas fa-paper-plane"></i>
                    Simpan & Kirim ke Ketua
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Drag & Drop Upload Logic - Enhanced Preview
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const uploadPlaceholder = document.getElementById('uploadPlaceholder');
    const filePreview = document.getElementById('filePreview');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const fileIcon = document.getElementById('fileIcon');

    // Click dropzone to open file picker
    dropZone.addEventListener('click', () => fileInput.click());

    // Drag over effect
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.style.borderColor = '#3b82f6';
        dropZone.style.background = '#eff6ff';
    });

    // Drag leave effect
    dropZone.addEventListener('dragleave', () => {
        dropZone.style.borderColor = '#e2e8f0';
        dropZone.style.background = 'white';
    });

    // Drop file
    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.style.borderColor = '#e2e8f0';
        dropZone.style.background = 'white';
        if(e.dataTransfer.files.length > 0) {
            fileInput.files = e.dataTransfer.files;
            showFilePreview(e.dataTransfer.files[0]);
        }
    });

    // File input change
    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            showFilePreview(e.target.files[0]);
        }
    });

    // Show file preview inside the box
    function showFilePreview(file) {
        // Hide placeholder, show preview
        if (uploadPlaceholder) uploadPlaceholder.style.display = 'none';
        if (filePreview) filePreview.style.display = 'block';
        
        // Set file name (with ellipsis if too long)
        if (fileName) fileName.textContent = file.name;
        
        // Format file size
        const sizeInKB = (file.size / 1024).toFixed(2);
        const sizeInMB = (file.size / 1024 / 1024).toFixed(2);
        if (fileSize) fileSize.textContent = sizeInKB >= 1024 ? `${sizeInMB} MB` : `${sizeInKB} KB`;
        
        // Set icon based on file type
        if (fileIcon) {
            const iconElement = fileIcon.querySelector('i');
            if (file.type === 'application/pdf') {
                iconElement.className = 'fas fa-file-pdf';
                iconElement.style.color = '#ef4444';
            } else if (file.type.startsWith('image/')) {
                iconElement.className = 'fas fa-file-image';
                iconElement.style.color = '#10b981';
            } else if (file.type.includes('word') || file.name.endsWith('.doc') || file.name.endsWith('.docx')) {
                iconElement.className = 'fas fa-file-word';
                iconElement.style.color = '#3b82f6';
            } else {
                iconElement.className = 'fas fa-file';
                iconElement.style.color = '#64748b';
            }
        }
        
        // Update dropzone styling
        dropZone.style.borderColor = '#10b981';
        dropZone.style.background = '#f0fdf4';
    }

    // Change file button handler
    function changeFile() {
        // Reset to placeholder
        if (uploadPlaceholder) uploadPlaceholder.style.display = 'block';
        if (filePreview) filePreview.style.display = 'none';
        
        // Reset file input
        fileInput.value = '';
        
        // Reset dropzone styling
        dropZone.style.borderColor = '#e2e8f0';
        dropZone.style.background = 'white';
        
        // Click to select new file
        setTimeout(() => fileInput.click(), 100);
    }
</script>
@endsection