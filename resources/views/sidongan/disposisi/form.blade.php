@extends('sidongan.layouts.app')
@section('title', 'Form Disposisi - SIDONGAN')

@section('content')
{{-- CSS untuk Layout Full Width & Animasi --}}
<style>
    .full-wrapper {
        width: 100%;
        max-width: 100%;
        padding: 0 1.5rem;
        margin: 0 auto;
    }

    .header-bar {
        background: linear-gradient(135deg, #0891b2, #14b8a6);
        padding: 1.5rem 2rem;
        border-radius: 0.75rem;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .content-grid {
        display: grid;
        grid-template-columns: 1fr 1.3fr; /* Kanan sedikit lebih lebar untuk form */
        gap: 1.5rem;
        margin-top: 1.5rem;
        width: 100%;
        align-items: start;
    }

    @media (max-width: 900px) {
        .content-grid { grid-template-columns: 1fr; }
        .header-bar { flex-direction: column; align-items: flex-start; gap: 1rem; }
    }

    .card-box {
        background: white;
        border-radius: 0.75rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        overflow: hidden;
        transition: box-shadow 0.3s;
    }
    .card-box:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.08); }

    .role-option {
        display: flex; align-items: center; gap: 0.85rem; padding: 0.9rem 1rem;
        background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 0.5rem;
        cursor: pointer; transition: all 0.2s;
    }
    .role-option:hover { border-color: #cbd5e1; background: #fff; }
    .role-option input[type="checkbox"] { display: none; }
    .custom-box {
        width: 1.3rem; height: 1.3rem; border: 2px solid #94a3b8; border-radius: 0.25rem;
        display: flex; align-items: center; justify-content: center; transition: all 0.2s; flex-shrink: 0;
    }
    .custom-box svg { width: 14px; height: 14px; color: white; opacity: 0; transform: scale(0.5); transition: all 0.2s; }
    .role-option input[type="checkbox"]:checked + .custom-box { background-color: #3b82f6; border-color: #3b82f6; }
    .role-option input[type="checkbox"]:checked + .custom-box svg { opacity: 1; transform: scale(1); }
    .role-option.active { background-color: #eff6ff; border-color: #3b82f6; }
    .role-option.active .role-text { color: #1e40af; font-weight: 600; }

    .custom-select-wrapper { position: relative; width: 100%; }
    .custom-select-wrapper select {
        width: 100%; padding: 0.85rem 3rem 0.85rem 1rem; appearance: none;
        border: 2px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.9rem; background: white;
    }
    .arrow-icon { position: absolute; right: 16px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #64748b; }
</style>

<div class="full-wrapper">
    {{-- HEADER BAR (Full Width) --}}
    <div class="header-bar">
        <div>
            <h1 style="font-size: 1.35rem; font-weight: 700; margin: 0;">Form Disposisi</h1>
            <p style="font-size: 0.9rem; opacity: 0.95; margin: 0.35rem 0 0 0;">Tentukan tujuan disposisi dan instruksi untuk surat ini</p>
        </div>
        <a href="{{ route('sidongan.documents.show', $document) }}" 
           style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1.2rem; background: rgba(255,255,255,0.2); color: white; text-decoration: none; border-radius: 0.5rem; font-size: 0.9rem; font-weight: 600; transition: all 0.2s; backdrop-filter: blur(4px);" 
           onmouseover="this.style.background='rgba(255,255,255,0.3)'" 
           onmouseout="this.style.background='rgba(255,255,255,0.2)'">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Detail</span>
        </a>
    </div>

    {{-- CONTENT GRID (Full Width) --}}
    <div class="content-grid">
        
        {{-- KOLOM KIRI: Info Surat --}}
        <div class="card-box">
            <div style="padding: 1.25rem 1.75rem; border-bottom: 1px solid #f1f5f9; background: #f8fafc;">
                <h3 style="font-size: 1.15rem; font-weight: 700; color: #0f172a; margin: 0 0 0.5rem 0; line-height: 1.4;">
                    {{ $document->subject ?? $document->title }}
                </h3>
                <div style="display: flex; gap: 0.5rem; align-items: center;">
                    <span style="font-size: 0.75rem; font-family: monospace; background: #dbeafe; color: #1e40af; padding: 0.2rem 0.5rem; border-radius: 0.25rem; font-weight: 600;">
                        {{ $document->agenda_number }}
                    </span>
                    <span style="font-size: 0.8rem; color: #64748b;">{{ $document->document_number }}</span>
                </div>
            </div>

            <div style="padding: 1.75rem;">
                <div style="margin-bottom: 1.75rem;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.85rem; border-bottom: 1px dashed #e2e8f0; padding-bottom: 0.6rem;">
                        <span style="font-size: 0.9rem; color: #64748b;">Pengirim</span>
                        <span style="font-size: 0.9rem; font-weight: 600; color: #0f172a; text-align: right; max-width: 65%;">{{ $document->sender }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.85rem; border-bottom: 1px dashed #e2e8f0; padding-bottom: 0.6rem;">
                        <span style="font-size: 0.9rem; color: #64748b;">Tanggal Surat</span>
                        <span style="font-size: 0.9rem; font-weight: 600; color: #0f172a;">{{ $document->document_date ? \Carbon\Carbon::parse($document->document_date)->locale('id')->translatedFormat('d M Y') : '-' }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="font-size: 0.9rem; color: #64748b;">Dibuat oleh</span>
                        <span style="font-size: 0.9rem; font-weight: 600; color: #0f172a;">{{ $document->creator->name ?? 'Sekretaris PKK' }}</span>
                    </div>
                </div>

                <div style="background: #fffbeb; border: 1px solid #fcd34d; border-radius: 0.6rem; padding: 1.25rem;">
                    <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.6rem;">
                        <i class="fas fa-lightbulb" style="color: #d97706; font-size: 1rem;"></i>
                        <span style="font-size: 0.85rem; font-weight: 700; color: #92400e;">Saran Sekretaris</span>
                    </div>
                    <p style="font-size: 0.9rem; color: #78350f; margin: 0; line-height: 1.6; font-style: italic;">
                        "{{ $document->suggestion ?? 'Tidak ada saran yang diberikan.' }}"
                    </p>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: Form Disposisi --}}
        <form action="{{ route('sidongan.disposisi.store', $document) }}" method="POST" class="card-box" style="padding: 0;">
            @csrf
            
            {{-- TAMPILKAN ERROR JIKA ADA --}}
            @if($errors->any())
            <div style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 1rem; margin: 1.5rem 1.75rem 0 1.75rem; border-radius: 0.5rem;">
                <strong style="display: block; margin-bottom: 0.5rem;">
                    <i class="fas fa-exclamation-circle" style="margin-right: 0.5rem;"></i> Gagal Menyimpan Disposisi:
                </strong>
                <ul style="margin: 0; padding-left: 1.25rem; list-style-type: disc;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div style="padding: 1.75rem;">
                <div style="margin-bottom: 1.75rem;">
                    <label style="display: block; font-size: 0.95rem; font-weight: 700; color: #0f172a; margin-bottom: 0.85rem;">
                        <i class="fas fa-users" style="color: #3b82f6; margin-right: 0.5rem;"></i>
                        Disposisikan ke <span style="color: #ef4444;">*</span>
                    </label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0.85rem;">
                        @php
                            $roles = [
                                'sekretaris' => 'Sekretaris PKK',
                                'bendahara' => 'Bendahara PKK',
                                'pokja1' => 'Ketua POKJA 1',
                                'pokja2' => 'Ketua POKJA 2',
                                'pokja3' => 'Ketua POKJA 3',
                                'pokja4' => 'Ketua POKJA 4',
                            ];
                        @endphp
                        @foreach($roles as $value => $label)
                        <label class="role-option">
                            <input type="checkbox" name="target_roles[]" value="{{ $value }}" onchange="toggleRoleStyle(this)">
                            <div class="custom-box">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            </div>
                            <span class="role-text" style="font-size: 0.9rem; color: #475569;">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div style="height: 1px; background: #e2e8f0; margin-bottom: 1.75rem;"></div>

                <div style="margin-bottom: 1.75rem;">
                    <label style="display: block; font-size: 0.95rem; font-weight: 700; color: #0f172a; margin-bottom: 0.85rem;">
                        <i class="fas fa-tasks" style="color: #3b82f6; margin-right: 0.5rem;"></i>
                        Tindakan/Instruksi <span style="color: #ef4444;">*</span>
                    </label>
                    <div class="custom-select-wrapper">
                        <select name="action" id="action" required>
                            <option value="" disabled selected>Pilih Tindakan</option>
                            <option value="Untuk diketahui">Untuk diketahui</option>
                            <option value="Untuk dilaksanakan">Untuk dilaksanakan</option>
                            <option value="Untuk diproses lebih lanjut">Untuk diproses lebih lanjut</option>
                            <option value="Untuk diarsipkan">Untuk diarsipkan</option>
                            <option value="Untuk dikoordinasikan">Untuk dikoordinasikan</option>
                        </select>
                        <div class="arrow-icon">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                        </div>
                    </div>
                </div>

                <div style="margin-bottom: 1.25rem;">
                    <label style="display: block; font-size: 0.95rem; font-weight: 700; color: #0f172a; margin-bottom: 0.85rem;">
                        <i class="fas fa-comment-alt" style="color: #3b82f6; margin-right: 0.5rem;"></i>
                        Komentar Tambahan
                    </label>
                    <textarea name="comment" id="comment" rows="4" placeholder="Catatan tambahan..."
                              style="width: 100%; padding: 0.85rem; border: 2px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.9rem; font-family: inherit; resize: vertical;"
                              onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#e2e8f0'"></textarea>
                </div>
            </div>

            <div style="padding: 1.25rem 1.75rem; background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 0.85rem;">
                <a href="{{ route('sidongan.documents.show', $document) }}" 
                   style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.7rem 1.4rem; background: white; border: 1px solid #cbd5e1; color: #475569; text-decoration: none; border-radius: 0.5rem; font-size: 0.9rem; font-weight: 600;"
                   onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='white'">
                    Batal
                </a>
                <button type="submit" 
                        style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.7rem 1.6rem; background: #3b82f6; color: white; border: none; border-radius: 0.5rem; font-size: 0.9rem; font-weight: 600; cursor: pointer; box-shadow: 0 2px 6px rgba(59,130,246,0.25);"
                        onmouseover="this.style.background='#2563eb'" onmouseout="this.style.background='#3b82f6'">
                    <i class="fas fa-paper-plane"></i> Kirim Disposisi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleRoleStyle(checkbox) {
    const label = checkbox.closest('.role-option');
    if (checkbox.checked) label.classList.add('active');
    else label.classList.remove('active');
}
document.querySelector('form').addEventListener('submit', function(e) {
    if (document.querySelectorAll('input[name="target_roles[]"]:checked').length === 0) {
        e.preventDefault(); alert('Pilih minimal satu tujuan disposisi!');
    }
});
</script>
@endsection