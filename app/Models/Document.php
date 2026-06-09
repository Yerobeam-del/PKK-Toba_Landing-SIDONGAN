<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sidongan_documents';

    // ✅ UPDATED: Tambahkan semua field yang digunakan di form
    protected $fillable = [
        'title',
        'slug',
        'description',
        'sender',              // ✅ Pengirim surat
        'document_number',     // ✅ Nomor surat dari pengirim
        'agenda_number',       // ✅ Nomor agenda internal (AG/MM/YYYY/NNN)
        'document_date',       // ✅ Tanggal surat
        'subject',             // ✅ Perihal surat
        'suggestion',          // ✅ Saran sekretaris
        'status',
        'category_id',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'is_public',
        'metadata',
        'disposisi_data',      // ✅ Data disposisi (JSON)
        'verifikasi_data',     // ✅ Data verifikasi (JSON)
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'document_date' => 'date',
        'is_public' => 'boolean',
        'metadata' => 'array',
        'disposisi_data' => 'array',
        'verifikasi_data' => 'array',
        'file_size' => 'integer',
    ];

    // Auto-generate slug saat title diubah
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value . '-' . time());
    }

    // Format ukuran file (KB, MB, GB)
    public function getFormattedSizeAttribute()
    {
        $bytes = $this->file_size;
        if ($bytes >= 1073741824) return number_format($bytes / 1073741824, 2) . ' GB';
        if ($bytes >= 1048576) return number_format($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024) return number_format($bytes / 1024, 2) . ' KB';
        return $bytes . ' B';
    }

    // URL file untuk akses publik
    public function getFileUrlAttribute()
    {
        return Storage::disk('public')->url($this->file_path);
    }

    // Relasi
    public function category()
    {
        return $this->belongsTo(DocumentCategory::class, 'category_id');
    }

    public function tags()
    {
        return $this->belongsToMany(DocumentTag::class, 'sidongan_document_tag', 'document_id', 'tag_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function activityReports()
    {
        return $this->hasMany(ActivityReport::class, 'document_id');
    }

    // Scope: Hanya dokumen published & public
    public function scopePublished($query)
    {
        return $query->where('status', 'published')->where('is_public', true);
    }

    // Scope: Search
    public function scopeSearch($query, $keyword)
    {
        return $query->where(function($q) use ($keyword) {
            $q->where('title', 'LIKE', "%{$keyword}%")
            ->orWhere('description', 'LIKE', "%{$keyword}%")
            ->orWhere('document_number', 'LIKE', "%{$keyword}%")
            ->orWhere('agenda_number', 'LIKE', "%{$keyword}%")
            ->orWhere('sender', 'LIKE', "%{$keyword}%")
            ->orWhere('subject', 'LIKE', "%{$keyword}%");
        });
    }

    // AUTO-GENERATE: Slug & Agenda Number saat dokumen dibuat
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($document) {
            // Generate slug jika kosong
            if (empty($document->slug)) {
                $document->slug = Str::slug($document->title . '-' . time());
            }
            
            // Generate agenda number jika kosong
            if (empty($document->agenda_number)) {
                $document->agenda_number = self::generateAgendaNumber();
            }
        });
    }

    /**
     * Generate nomor agenda otomatis
     * Format BARU: NNN/SM/PKK-T/BULAN(ROMAWI)/TAHUN
     * Contoh: 001/SM/PKK-T/VI/2026
     * Reset nomor urut setiap ganti bulan
     */
    public static function generateAgendaNumber()
    {
        $month = now()->month; // 1-12
        $year = now()->year;   // 2026
        
        // Cari dokumen terakhir di bulan & tahun ini untuk dapat nomor urut terakhir
        $lastDocument = self::withTrashed()
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->whereNotNull('agenda_number')
            ->orderBy('id', 'desc')
            ->first();
        
        // Hitung nomor urut berikutnya
        $nextSequence = 1;
        
        if ($lastDocument && $lastDocument->agenda_number) {
            // Parse format baru: 001/SM/PKK-T/VI/2026
            // Ambil bagian pertama (nomor urut)
            $parts = explode('/', $lastDocument->agenda_number);
            if (!empty($parts[0])) {
                $nextSequence = intval($parts[0]) + 1;
            }
        }
        
        // Format nomor urut dengan 3 digit
        $sequence = str_pad($nextSequence, 3, '0', STR_PAD_LEFT);
        
        // Konversi bulan ke Romawi
        $bulanRomawi = self::toRomanMonth($month);
        
        // Format final: 001/SM/PKK-T/VI/2026
        $agendaNumber = "{$sequence}/SM/PKK-T/{$bulanRomawi}/{$year}";
        
        // Pastikan nomor agenda unik (loop sampai dapat yang belum dipakai)
        while (self::withTrashed()->where('agenda_number', $agendaNumber)->exists()) {
            $sequence = str_pad(intval($sequence) + 1, 3, '0', STR_PAD_LEFT);
            $agendaNumber = "{$sequence}/SM/PKK-T/{$bulanRomawi}/{$year}";
        }
        
        return $agendaNumber;
    }

    /**
     * Helper: Ubah angka bulan (1-12) ke Romawi
     */
    private static function toRomanMonth($month)
    {
        $roman = [
            1 => 'I',   2 => 'II',  3 => 'III', 4 => 'IV',
            5 => 'V',   6 => 'VI',  7 => 'VII', 8 => 'VIII',
            9 => 'IX',  10 => 'X',  11 => 'XI',  12 => 'XII'
        ];
        return $roman[$month] ?? 'I';
    }
}