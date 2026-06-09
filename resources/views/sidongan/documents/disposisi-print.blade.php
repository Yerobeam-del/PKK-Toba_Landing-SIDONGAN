@extends('sidongan.layouts.app')
@section('title', 'Cetak Lembar Disposisi - SIDONGAN')

@section('content')
<style>
    /* ============================================
       Layout Preview Lembar Disposisi
       ============================================ */
    .disposisi-preview-wrapper {
        max-width: 210mm;
        margin: 0 auto;
        background: white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        border-radius: 0.5rem;
        overflow: hidden;
    }
    
    .disposisi-page {
        padding: 15mm 20mm 20mm 20mm;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12pt;
        line-height: 1.5;
        color: #000;
    }
    
    /* LAMPIRAN 4.8 */
    .disposisi-lampiran {
        text-align: right;
        font-weight: bold;
        font-size: 11pt;
        margin-bottom: 25px;
    }
    
    /* Judul */
    .disposisi-title {
        text-align: center;
        font-weight: bold;
        font-size: 14pt;
        margin-bottom: 3px;
        letter-spacing: 0.5px;
    }
    
    .disposisi-subtitle {
        text-align: center;
        font-weight: bold;
        font-size: 12pt;
        margin-bottom: 15px;
    }
    
    /* Garis horizontal tebal */
    .disposisi-line-thick {
        border: none;
        border-top: 2px solid #000;
        margin: 0 0 8px 0;
    }
    
    /* Garis horizontal tipis (pendek) - untuk di atas SURAT DARI */
    .disposisi-line-short {
        border: none;
        border-top: 1px solid #000;
        width: 80px;
        margin: 0 0 6px 0;
    }
    
    /* Row NO. AGENDA & TANGGAL */
    .disposisi-row-agenda {
        display: flex;
        align-items: baseline;
        padding: 6px 0;
    }
    
    .disposisi-row-agenda .label {
        font-weight: bold;
        font-size: 11pt;
        white-space: nowrap;
    }
    
    .disposisi-row-agenda .value {
        flex: 1;
        border-bottom: 1px solid #000;
        margin: 0 8px;
        padding-bottom: 2px;
        font-size: 11pt;
    }
    
    /* Row Info Surat */
    .disposisi-row-info {
        display: flex;
        align-items: baseline;
        padding: 4px 0;
    }
    
    .disposisi-row-info .label {
        font-weight: bold;
        font-size: 11pt;
        width: 130px;
        flex-shrink: 0;
    }
    
    .disposisi-row-info .colon {
        width: 15px;
        flex-shrink: 0;
    }
    
    .disposisi-row-info .value {
        flex: 1;
        border-bottom: 1px solid #000;
        padding-bottom: 2px;
        font-size: 11pt;
    }
    
    /* Tabel SARAN SEKRETARIS & DISPOSISI */
    .disposisi-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        border: none;
    }
    
    .disposisi-table th {
        border: none;
        border-top: 1px solid #000;
        padding: 8px 10px;
        text-align: center;
        font-weight: bold;
        font-size: 11pt;
        text-decoration: underline;
        background: white;
    }
    
    .disposisi-table th:first-child {
        border-right: 1px solid #000;
    }
    
    .disposisi-table td {
        border: none;
        padding: 12px 15px;
        vertical-align: top;
        font-size: 11pt;
        line-height: 1.7;
        height: 380px;
    }
    
    .disposisi-table td:first-child {
        border-right: 1px solid #000;
    }
    
    /* Tanda Tangan */
    .disposisi-signature {
        margin-top: 30px;
        text-align: right;
        padding-right: 10px;
        font-size: 11pt;
    }
    
    .disposisi-signature-date {
        margin-bottom: 5px;
    }
    
    .disposisi-signature-space {
        height: 80px; /* Ruang untuk tanda tangan */
    }
    
    .disposisi-signature-name {
        font-weight: bold;
        text-decoration: underline;
    }
    
    /* Tombol Aksi */
    .action-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 0.75rem;
    }
    
    .action-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1rem;
        font-weight: 700;
        color: #1e293b;
    }
    
    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }
    
    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-weight: 600;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        border: none;
    }
    
    .btn-print {
        background: #3b82f6;
        color: white;
    }
    .btn-print:hover {
        background: #2563eb;
        transform: translateY(-1px);
    }
    
    .btn-back {
        background: #64748b;
        color: white;
    }
    .btn-back:hover {
        background: #475569;
        transform: translateY(-1px);
    }
    
    /* ============================================
    PRINT STYLES - A4 Only
    ============================================ */
    @media print {
        /* ✅ PAKSA A4 */
        @page {
            size: A4 portrait !important;
            margin: 0 !important;
        }
        
        * {
            print-color-adjust: exact !important;
            -webkit-print-color-adjust: exact !important;
        }
        
        /* Sembunyikan SEMUA elemen di body */
        body * {
            visibility: hidden !important;
        }
        
        /* Tampilkan HANYA wrapper disposisi dan isinya */
        .disposisi-preview-wrapper,
        .disposisi-preview-wrapper * {
            visibility: visible !important;
        }
        
        /* Position wrapper di pojok kiri atas saat print */
        .disposisi-preview-wrapper {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 210mm !important;
            height: 297mm !important;
            max-width: 100% !important;
            box-shadow: none !important;
            border-radius: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            background: white !important;
        }
        
        .disposisi-page {
            padding: 15mm 20mm !important;
            width: 100% !important;
            height: 100% !important;
        }
        
        /* Sembunyikan action bar */
        .action-bar,
        .no-print {
            display: none !important;
        }
    }
</style>

<div style="max-width: 900px; margin: 0 auto;">
    {{-- Action Bar --}}
    <div class="action-bar no-print">
        <div class="action-title">
            <i class="fas fa-print" style="color: #0891b2;"></i>
            <span>Preview Lembar Disposisi</span>
        </div>
        <div class="action-buttons">
            <button onclick="window.print()" class="btn-action btn-print">
                <i class="fas fa-print"></i>
                <span>Cetak</span>
            </button>
            <a href="{{ route('sidongan.documents.show', $document) }}" class="btn-action btn-back">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    {{-- Preview Lembar Disposisi --}}
    <div class="disposisi-preview-wrapper">
        <div class="disposisi-page">
            {{-- LAMPIRAN 4.8 --}}
            <div class="disposisi-lampiran">LAMPIRAN 4.8</div>
            
            {{-- Judul --}}
            <div class="disposisi-title">LEMBAR DISPOSISI</div>
            <div class="disposisi-subtitle">KETUA UMUM TIM PENGGERAK PKK</div>
            
            {{-- Garis tebal atas --}}
            <hr class="disposisi-line-thick">
            
            {{-- NO. AGENDA & TANGGAL --}}
            <div class="disposisi-row-agenda">
                <span class="label">NO. AGENDA :</span>
                <span class="value">{{ $document->agenda_number }}</span>
                <span class="label" style="width: 90px; margin-left: 30px;">TANGGAL:</span>
                <span class="value">{{ $document->created_at->format('d/m/Y') }}</span>
            </div>
            
            {{-- Garis tebal bawah --}}
            <hr class="disposisi-line-thick">
            
            {{-- Garis tipis pendek di atas SURAT DARI (dekat) --}}
            <hr class="disposisi-line-short">
            
            {{-- SURAT DARI --}}
            <div class="disposisi-row-info">
                <span class="label">SURAT DARI</span>
                <span class="colon">:</span>
                <span class="value">{{ $document->sender }}</span>
            </div>
            
            {{-- TANGGAL --}}
            <div class="disposisi-row-info">
                <span class="label">TANGGAL</span>
                <span class="colon">:</span>
                <span class="value">{{ $document->document_date->format('d/m/Y') }}</span>
            </div>
            
            {{-- NOMOR SURAT --}}
            <div class="disposisi-row-info">
                <span class="label">NOMOR SURAT</span>
                <span class="colon">:</span>
                <span class="value">{{ $document->document_number }}</span>
            </div>
            
            {{-- PERIHAL --}}
            <div class="disposisi-row-info">
                <span class="label">PERIHAL</span>
                <span class="colon">:</span>
                <span class="value">{{ $document->subject }}</span>
            </div>
            
            {{-- Tabel SARAN SEKRETARIS & DISPOSISI --}}
            <table class="disposisi-table">
                <thead>
                    <tr>
                        <th style="width: 50%;">SARAN SEKRETARIS</th>
                        <th style="width: 50%;">DISPOSISI</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        {{-- Kolom SARAN SEKRETARIS --}}
                        <td>
                            {{ $document->suggestion ?? '-' }}
                        </td>
                        
                        {{-- Kolom DISPOSISI --}}
                        <td>
                            @php
                                $disposisiData = is_string($document->disposisi_data ?? '') ? json_decode($document->disposisi_data, true) : $document->disposisi_data;
                            @endphp
                            
                            @if(is_array($disposisiData) && isset($disposisiData['action']))
                                <div style="margin-bottom: 12px;">
                                    <strong>Didisposisikan kepada:</strong><br>
                                    @php
                                        $rolesMap = [
                                            'sekretaris' => 'Sekretaris PKK',
                                            'bendahara' => 'Bendahara PKK',
                                            'staf_ahli_1' => 'Staf Ahli I',
                                            'staf_ahli_2' => 'Staf Ahli II',
                                            'pengurus_1' => 'Ketua Pengurus I',
                                            'pengurus_2' => 'Ketua Pengurus II',
                                            'pengurus_3' => 'Ketua Pengurus III',
                                            'pengurus_4' => 'Ketua Pengurus IV',
                                        ];
                                    @endphp
                                    @foreach($disposisiData['target_roles'] as $role)
                                        - {{ $rolesMap[$role] ?? ucfirst(str_replace('_', ' ', $role)) }}<br>
                                    @endforeach
                                </div>
                                
                                <div style="margin-bottom: 12px;">
                                    <strong>Tindakan:</strong><br>
                                    {{ $disposisiData['action'] }}
                                </div>
                                
                                @if(!empty($disposisiData['comment']))
                                    <div style="margin-bottom: 12px;">
                                        <strong>Komentar:</strong><br>
                                        {{ $disposisiData['comment'] }}
                                    </div>
                                @endif
                                
                                {{-- Tanda Tangan dengan Ruang Kosong --}}
                                <div class="disposisi-signature">
                                    <div class="disposisi-signature-date">
                                        {{ isset($disposisiData['disposed_at']) ? \Carbon\Carbon::parse($disposisiData['disposed_at'])->format('d/m/Y') : $document->updated_at->format('d/m/Y') }}
                                    </div>
                                    <div class="disposisi-signature-space"></div>
                                    <div class="disposisi-signature-name">
                                        @php
                                            $disposedBy = null;
                                            if (isset($disposisiData['disposed_by'])) {
                                                $disposedBy = \App\Models\User::find($disposisiData['disposed_by']);
                                            }
                                        @endphp
                                        {{ $disposedBy->name ?? 'Ketua PKK' }}
                                    </div>
                                </div>
                            @else
                                <em style="color: #64748b;">Belum ada disposisi</em>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection