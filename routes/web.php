<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Landing\NewsController;
use App\Http\Controllers\Sidongan\AdminDocumentController;
use App\Http\Controllers\Sidongan\CategoryController;
use App\Http\Controllers\Sidongan\TagController;

// ================= API ROUTES (Untuk Dynamic Content) =================

// Health check endpoint
Route::get('/api/v1/health', function () {
    return response()->json(['status' => 'ok']);
});

// ================= API: NEWS =================
Route::get('/api/v1/news', function () {
    try {
        $news = \App\Models\News::published()
            ->orderBy('published_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'slug' => $item->slug,
                    'title' => $item->title,
                    'category' => $item->category,
                    'excerpt' => $item->excerpt,
                    'content' => $item->content,
                    'image_path' => $item->image_path,
                    'image' => $item->image_path ? asset('storage/' . $item->image_path) : null,
                    'published_at' => $item->published_at,
                    'created_at' => $item->created_at,
                    'date' => $item->published_at?->format('d M Y') ?? $item->created_at->format('d M Y'),
                ];
            });
        
        return response()->json(['success' => true, 'data' => $news]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
});

Route::get('/api/v1/news/{slug}', function ($slug) {
    try {
        $news = \App\Models\News::published()->where('slug', $slug)->firstOrFail();
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $news->id, 'slug' => $news->slug, 'title' => $news->title,
                'category' => $news->category, 'excerpt' => $news->excerpt,
                'content' => $news->content, 'image_path' => $news->image_path,
                'image' => $news->image_path ? asset('storage/' . $news->image_path) : null,
                'published_at' => $news->published_at,
                'date' => $news->published_at?->format('d M Y') ?? $news->created_at->format('d M Y'),
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Berita tidak ditemukan'], 404);
    }
});

// ================= API: STRUKTUR (BARU) =================
Route::get('/api/v1/struktur', function () {
    try {
        // Pengurus Inti (yang tidak punya pokja_id)
        $pengurusInti = \App\Models\StrukturMember::whereNull('pokja_id')
            ->active()->orderBy('sort_order')->get()
            ->map(fn($m) => [
                'id' => $m->id,
                'name' => $m->name,
                'position' => $m->position,
                'photo' => $m->photo_path ? asset('storage/' . $m->photo_path) : null,
                'description' => $m->description,
            ]);
        
        // Pokja beserta anggotanya
        $pokja = \App\Models\Pokja::active()->with(['members' => fn($q) => $q->active()])
            ->orderBy('sort_order')->get()
            ->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'slug' => $p->slug,
                'description' => $p->description,
                'members' => $p->members->map(fn($m) => [
                    'id' => $m->id,
                    'name' => $m->name,
                    'position' => $m->position,
                    'photo' => $m->photo_path ? asset('storage/' . $m->photo_path) : null,
                    'description' => $m->description,
                ]),
            ]);
        
        return response()->json([
            'success' => true,
            'data' => ['pengurus_inti' => $pengurusInti, 'pokja' => $pokja]
        ]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
});

// ================= API: APPLICATIONS =================
Route::get('/api/v1/applications', function () {
    try {
        // Aplikasi Aktif
        $activeApps = \App\Models\Application::aplikasi()
            ->active()
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->get(['id', 'name', 'short_name', 'description', 'url', 'icon', 'category', 'status', 'features', 'sort_order', 'is_active']);
        
        // Aplikasi Dalam Pengembangan
        $developmentApps = \App\Models\Application::aplikasi()
            ->where('status', 'development')
            ->orderBy('sort_order')
            ->get(['id', 'name', 'short_name', 'description', 'url', 'icon', 'category', 'status', 'features', 'sort_order', 'is_active']);
        
        // Aplikasi Maintenance
        $maintenanceApps = \App\Models\Application::aplikasi()
            ->where('status', 'maintenance')
            ->orderBy('sort_order')
            ->get(['id', 'name', 'short_name', 'description', 'url', 'icon', 'category', 'status', 'features', 'sort_order', 'is_active']);
        
        return response()->json([
            'success' => true,
            'data' => [
                'active' => $activeApps,
                'development' => $developmentApps,
                'maintenance' => $maintenanceApps,
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
});

// ================= API: KECAMATAN (From wilayah.id) =================
Route::get('/api/v1/kecamatans', function (App\Services\WilayahIndonesiaService $service) {
    try {
        // Auto-sync jika database masih kosong
        if (\App\Models\Kecamatan::count() === 0) {
            $service->syncKecamatansToba();
        }
        
        $kecamatans = \App\Models\Kecamatan::select('id', 'name', 'kode_wilayah')
            ->orderBy('name')
            ->get()
            ->map(fn($k) => [
                'id' => $k->id,
                'name' => $k->name,
                'code' => $k->kode_wilayah  // Konsisten dengan format API
            ]);
        
        return response()->json([
            'success' => true,
            'data' => $kecamatans
        ]);
    } catch (\Exception $e) {
        \Log::error('API Kecamatan Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Gagal memuat data kecamatan'
        ], 500);
    }
});

// ================= API: DESA =================
Route::get('/api/v1/desas', function () {
    try {
        $kecamatans = \App\Models\Kecamatan::with(['activeDesas' => function($q) {
            $q->select('id', 'kecamatan_id', 'name', 'kode_wilayah', 'description', 'image', 'population', 'households', 'sort_order', 'is_active')
              ->orderBy('sort_order');
        }])
        ->orderBy('name')
        ->get()
        ->map(function($kec) {
            return [
                'id' => $kec->id,
                'name' => $kec->name,
                'desas' => $kec->activeDesas->map(function($desa) {
                    return [
                        'id' => $desa->id,
                        'name' => $desa->name,
                        'kode_wilayah' => $desa->kode_wilayah,
                        'description' => $desa->description,
                        'image' => $desa->image ? asset('storage/' . $desa->image) : null,
                        'population' => $desa->population,
                        'households' => $desa->households,
                        'sort_order' => $desa->sort_order,
                        'is_active' => $desa->is_active,
                    ];
                })
            ];
        });
        
        return response()->json(['success' => true, 'data' => $kecamatans]);
    } catch (\Exception $e) {
        \Log::error('API Desa Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Gagal memuat data desa: ' . $e->getMessage()
        ], 500);
    }
});

// ================= API: PROXY WILAYAH.ID =================
Route::get('/api/v1/wilayah/proxy/desa/{kecamatanCode}', function ($kecamatanCode) {
    try {
        // Fetch dari API wilayah.id via server (bukan browser)
        $response = Http::timeout(30)->get("https://wilayah.id/api/villages/{$kecamatanCode}.json");
        
        if (!$response->successful()) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data dari API wilayah.id'
            ], $response->status());
        }
        
        return response()->json([
            'success' => true,
            'data' => $response->json()['data'] ?? []
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Proxy Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Server error: ' . $e->getMessage()
        ], 500);
    }
});

// ================= API: SK & DOKUMEN =================
Route::get('/api/v1/dokumens', function () {
    try {
        \Log::info('API /api/v1/dokumens accessed');
        
        $dokumens = \App\Models\Dokumen::published()
            ->orderBy('document_date', 'desc')
            ->orderBy('sort_order')
            ->get()
            ->map(function($doc) {
                return [
                    'id' => $doc->id,
                    'name' => $doc->name,
                    'file_name' => $doc->file_name,
                    'file_size' => $doc->file_size,
                    'file_url' => $doc->file_url,
                    'file_type' => $doc->file_type,
                    'document_date' => $doc->document_date?->format('Y-m-d'),
                    'formatted_date' => $doc->document_date?->format('d M Y'),
                    'status' => $doc->status, // Penting untuk filter client-side
                ];
            });
        
        \Log::info('API returning ' . count($dokumens) . ' documents');
        
        return response()->json(['success' => true, 'data' => $dokumens]);
    } catch (\Exception $e) {
        \Log::error('API Dokumen Error: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
});

// API Template untuk Landing Page
Route::get('/api/v1/templates', function () {
    try {
        \Log::info('API /api/v1/templates accessed');
        
        $templates = \App\Models\Template::published()
            ->orderBy('upload_date', 'desc')
            ->orderBy('sort_order')
            ->get()
            ->map(function($t) {
                return [
                    'id' => $t->id,
                    'name' => $t->name,
                    'file_name' => $t->file_name,
                    'file_size' => $t->file_size,
                    'file_url' => $t->file_url,
                    'file_type' => $t->file_type,
                    'upload_date' => $t->upload_date?->format('Y-m-d'),
                    'formatted_date' => $t->upload_date?->format('d M Y'),
                    'status' => $t->status,
                    'description' => $t->description ?? null,
                ];
            });
        
        \Log::info('API returning ' . count($templates) . ' templates');
        
        return response()->json(['success' => true, 'data' => $templates]);
    } catch (\Exception $e) {
        \Log::error('API Templates Error: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());
        
        return response()->json([
            'success' => false, 
            'message' => $e->getMessage()
        ], 500);
    }
});

// API untuk landing page
Route::get('/api/v1/tentang', function () {
    $tentang = \App\Models\TentangKami::getFirst();
    return response()->json([
        'success' => true,
        'data' => $tentang
    ]);
});

// API untuk landing page
Route::get('/api/v1/hero-slider', function () {
    $sliders = \App\Models\HeroSlider::active()->get()->map(fn($s) => [
        'id' => $s->id,
        'image_url' => $s->image_url,
        'display_duration' => $s->display_duration ?? 5
    ]);
    
    $settings = file_exists(storage_path('app/hero_slider_settings.json')) 
        ? json_decode(file_get_contents(storage_path('app/hero_slider_settings.json')), true)
        : ['auto_play' => true, 'transition_duration' => 500, 'show_arrows' => false, 'show_dots' => true];
    
    return response()->json(['success' => true, 'data' => $sliders, 'settings' => $settings]);
});



// ================= API: SIDONGAN =================
Route::get('/api/v1/sidongan/documents', function () {
    try {
        \Log::info('API /api/v1/sidongan/documents accessed');
        
        $documents = \App\Models\Document::published()
            ->with(['category', 'tags'])
            ->orderBy('document_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12)
            ->through(function($doc) {
                return [
                    'id' => $doc->id,
                    'title' => $doc->title,
                    'slug' => $doc->slug,
                    'description' => $doc->description,
                    'document_number' => $doc->document_number,
                    'document_date' => $doc->document_date?->format('Y-m-d'),
                    'formatted_date' => $doc->document_date?->format('d M Y'),
                    'category' => $doc->category ? [
                        'id' => $doc->category->id,
                        'name' => $doc->category->name,
                        'color' => $doc->category->color,
                    ] : null,
                    'tags' => $doc->tags->pluck('name'),
                    'file_name' => $doc->file_name,
                    'file_type' => $doc->file_type,
                    'file_size' => $doc->file_size,
                    'formatted_size' => $doc->formatted_size,
                    'file_url' => $doc->file_url,
                    'status' => $doc->status,
                    'is_public' => $doc->is_public,
                    'created_at' => $doc->created_at->format('Y-m-d H:i:s'),
                ];
            });
        
        \Log::info('API returning ' . count($documents->items()) . ' documents');
        
        return response()->json([
            'success' => true, 
            'data' => $documents,
            'meta' => [
                'total' => $documents->total(),
                'per_page' => $documents->perPage(),
                'current_page' => $documents->currentPage(),
                'last_page' => $documents->lastPage(),
            ]
        ]);
    } catch (\Exception $e) {
        \Log::error('API SIDONGAN Documents Error: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());
        return response()->json([
            'success' => false, 
            'message' => $e->getMessage()
        ], 500);
    }
});

Route::get('/api/v1/sidongan/documents/{slug}', function ($slug) {
    try {
        $document = \App\Models\Document::published()
            ->with(['category', 'tags', 'creator'])
            ->where('slug', $slug)
            ->firstOrFail();
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $document->id,
                'title' => $document->title,
                'slug' => $document->slug,
                'description' => $document->description,
                'document_number' => $document->document_number,
                'document_date' => $document->document_date?->format('Y-m-d'),
                'formatted_date' => $document->document_date?->format('d M Y'),
                'category' => $document->category ? [
                    'id' => $document->category->id,
                    'name' => $document->category->name,
                    'color' => $document->category->color,
                    'description' => $document->category->description,
                ] : null,
                'tags' => $document->tags->map(fn($t) => ['id' => $t->id, 'name' => $t->name, 'slug' => $t->slug]),
                'file_name' => $document->file_name,
                'file_type' => $document->file_type,
                'file_size' => $document->file_size,
                'formatted_size' => $document->formatted_size,
                'file_url' => $document->file_url,
                'metadata' => $document->metadata,
                'creator' => $document->creator ? [
                    'id' => $document->creator->id,
                    'name' => $document->creator->name,
                ] : null,
                'created_at' => $document->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $document->updated_at?->format('Y-m-d H:i:s'),
            ]
        ]);
    } catch (\Exception $e) {
        \Log::error('API SIDONGAN Document Detail Error: ' . $e->getMessage());
        return response()->json([
            'success' => false, 
            'message' => 'Dokumen tidak ditemukan'
        ], 404);
    }
});

Route::get('/api/v1/sidongan/categories', function () {
    try {
        $categories = \App\Models\DocumentCategory::where('is_active', true)
            ->withCount('documents')
            ->orderBy('name')
            ->get()
            ->map(fn($c) => [
                'id' => $c->id,
                'name' => $c->name,
                'slug' => $c->slug,
                'description' => $c->description,
                'color' => $c->color,
                'documents_count' => $c->documents_count,
            ]);
        
        return response()->json(['success' => true, 'data' => $categories]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
});

Route::get('/api/v1/sidongan/tags', function () {
    try {
        $tags = \App\Models\DocumentTag::orderBy('name')->get()
            ->map(fn($t) => ['id' => $t->id, 'name' => $t->name, 'slug' => $t->slug]);
        
        return response()->json(['success' => true, 'data' => $tags]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
});

// ================= END API ROUTES =================


// ================= LANDING PAGE =================
Route::get('/', function () {
    return view('modules.landing.home');
})->name('landing.home');

Route::get('/berita', function () {
    return redirect('/#berita');  // ← Redirect ke SPA dengan hash
})->name('landing.berita');

// Route untuk detail berita (frontend view)
Route::get('/berita/{slug}', [NewsController::class, 'show'])->name('news.show');

// ================= ADMIN PANEL =================
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // Profile Routes
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/password', [App\Http\Controllers\ProfileController::class, 'password'])->name('profile.password');
    Route::put('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Dashboard (semua user login bisa akses)
    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Hero Sliders (Beranda)
    Route::prefix('hero-sliders')->name('hero-sliders.')->middleware('permission:manage-hero-slider')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\HeroSliderController::class, 'index'])->name('index');
        Route::post('/', [App\Http\Controllers\Admin\HeroSliderController::class, 'store'])->name('store');
        Route::put('/{heroSlider}', [App\Http\Controllers\Admin\HeroSliderController::class, 'update'])->name('update');
        Route::delete('/{heroSlider}', [App\Http\Controllers\Admin\HeroSliderController::class, 'destroy'])->name('destroy');
        Route::post('/reorder', [App\Http\Controllers\Admin\HeroSliderController::class, 'updateOrder'])->name('reorder');
        Route::post('/settings', [App\Http\Controllers\Admin\HeroSliderController::class, 'updateSettings'])->name('settings');
    });

    // STRUKTUR
    Route::resource('struktur', App\Http\Controllers\Admin\StrukturController::class)
        ->middleware('permission:manage-struktur');

    // Aplikasi
    Route::resource('aplikasi', App\Http\Controllers\Admin\ApplicationController::class)
        ->middleware('permission:manage-aplikasi');

    // Berita
    Route::resource('berita', App\Http\Controllers\Admin\BeritaController::class)
        ->middleware('permission:manage-berita');

    // Desa
    Route::prefix('desa')->name('desa.')->middleware('permission:manage-desa')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\DesaController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\DesaController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\DesaController::class, 'store'])->name('store');
        Route::get('/{desa}/edit', [App\Http\Controllers\Admin\DesaController::class, 'edit'])->name('edit');
        Route::put('/{desa}', [App\Http\Controllers\Admin\DesaController::class, 'update'])->name('update');
        Route::delete('/{desa}', [App\Http\Controllers\Admin\DesaController::class, 'destroy'])->name('destroy');
        Route::get('/max-sort-order', [App\Http\Controllers\Admin\DesaController::class, 'getMaxSortOrder'])->name('max-sort-order');
        Route::post('/kecamatan', [App\Http\Controllers\Admin\DesaController::class, 'storeKecamatan'])->name('kecamatan.store');
        Route::put('/kecamatan/{kecamatan}', [App\Http\Controllers\Admin\DesaController::class, 'updateKecamatan'])->name('kecamatan.update');
        Route::delete('/kecamatan/{kecamatan}', [App\Http\Controllers\Admin\DesaController::class, 'destroyKecamatan'])->name('kecamatan.destroy');
    });

    // SK & Dokumen
    Route::resource('sk', App\Http\Controllers\Admin\DokumenController::class)
        ->parameters(['sk' => 'dokumen'])
        ->middleware('permission:manage-dokumen');

    // Template
    Route::resource('template', App\Http\Controllers\Admin\TemplateController::class)
        ->middleware('permission:manage-template');

    // Tentang Kami
    Route::get('/tentang', [App\Http\Controllers\Admin\TentangKamiController::class, 'index'])
        ->name('tentang.index')
        ->middleware('permission:manage-tentang');
    Route::post('/tentang/update', [App\Http\Controllers\Admin\TentangKamiController::class, 'update'])
        ->name('tentang.update')
        ->middleware('permission:manage-tentang');

    // Account Management (Manajemen Akun) - HANYA untuk yang punya permission manage-users
    Route::prefix('user-management')->name('user-management.')->middleware('permission:manage-users')->group(function () {
        // Route statis HARUS di atas wildcard
        Route::get('/', [App\Http\Controllers\Admin\UserManagementController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\UserManagementController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\UserManagementController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [App\Http\Controllers\Admin\UserManagementController::class, 'edit'])->name('edit');
        Route::put('/{user}', [App\Http\Controllers\Admin\UserManagementController::class, 'update'])->name('update');
        Route::delete('/{user}', [App\Http\Controllers\Admin\UserManagementController::class, 'destroy'])->name('destroy');
        Route::post('/{user}/toggle-status', [App\Http\Controllers\Admin\UserManagementController::class, 'toggleStatus'])->name('user-management.toggle-status');
        
        // Route view-only (wildcard di paling bawah)
        Route::get('/{user}', [App\Http\Controllers\Admin\UserManagementController::class, 'show'])->name('show');
    });
});

// ================= SIDONGAN AUTH =================
Route::get('/sidongan-login', function () {
    return view('sidongan-auth.login');
})->name('sidongan.login');

Route::post('/sidongan-login', [App\Http\Controllers\Sidongan\AuthController::class, 'login'])->name('sidongan.login.post');
Route::post('/sidongan-logout', [App\Http\Controllers\Sidongan\AuthController::class, 'logout'])->name('sidongan.logout');

// ================= SIDONGAN ADMIN - SEMUA ROUTE PRIVATE =================
// Semua route SIDONGAN sekarang menggunakan prefix 'sidongan' dan middleware 'sidongan.auth'
Route::middleware(['sidongan.auth'])->prefix('sidongan')->name('sidongan.')->group(function () {
    
    // Dashboard (akses via /sidongan)
    Route::get('/', [App\Http\Controllers\Sidongan\AdminDocumentController::class, 'dashboard'])->name('dashboard');
    
    // Documents
    Route::get('/documents', [App\Http\Controllers\Sidongan\AdminDocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/create', [App\Http\Controllers\Sidongan\AdminDocumentController::class, 'create'])->name('documents.create');
    Route::post('/documents', [App\Http\Controllers\Sidongan\AdminDocumentController::class, 'store'])->name('documents.store');
    Route::get('/documents/{document}', [App\Http\Controllers\Sidongan\AdminDocumentController::class, 'show'])->name('documents.show');
    Route::get('/documents/{document}/edit', [App\Http\Controllers\Sidongan\AdminDocumentController::class, 'edit'])->name('documents.edit');
    Route::put('/documents/{document}', [App\Http\Controllers\Sidongan\AdminDocumentController::class, 'update'])->name('documents.update');
    Route::delete('/documents/{document}', [App\Http\Controllers\Sidongan\AdminDocumentController::class, 'destroy'])->name('documents.destroy');
    Route::get('/documents/{document}/download', [App\Http\Controllers\Sidongan\AdminDocumentController::class, 'download'])->name('documents.download');
    
    // Disposisi
    Route::get('/disposisi', [App\Http\Controllers\Sidongan\AdminDocumentController::class, 'disposisi'])->name('disposisi');
    Route::get('/disposisi/{document}', [App\Http\Controllers\Sidongan\AdminDocumentController::class, 'showDisposisiForm'])->name('disposisi.form');
    Route::post('/disposisi/{document}', [App\Http\Controllers\Sidongan\AdminDocumentController::class, 'storeDisposisi'])->name('disposisi.store');
    
    // Verifikasi
    Route::get('/verifikasi', [App\Http\Controllers\Sidongan\AdminDocumentController::class, 'verifikasi'])->name('verifikasi');
    Route::post('/verifikasi/{document}', [App\Http\Controllers\Sidongan\AdminDocumentController::class, 'storeVerifikasi'])->name('verifikasi.store');
    
    // Arsip
    Route::get('/arsip', [App\Http\Controllers\Sidongan\AdminDocumentController::class, 'arsip'])->name('arsip');
    
    // Routes untuk Lapor Kegiatan (Sekretaris)
    Route::get('/lapor-kegiatan', [App\Http\Controllers\Sidongan\ActivityReportController::class, 'index'])->name('lapor_kegiatan.index');
    Route::get('/lapor-kegiatan/create/{document_id?}', [App\Http\Controllers\Sidongan\ActivityReportController::class, 'create'])->name('lapor_kegiatan.create');
    Route::post('/lapor-kegiatan', [App\Http\Controllers\Sidongan\ActivityReportController::class, 'store'])->name('lapor_kegiatan.store');
    Route::get('/lapor-kegiatan/{id}', [App\Http\Controllers\Sidongan\ActivityReportController::class, 'show'])->name('lapor_kegiatan.show');
    Route::get('/lapor-kegiatan/{id}/edit', [App\Http\Controllers\Sidongan\ActivityReportController::class, 'edit'])->name('lapor_kegiatan.edit');
    Route::put('/lapor-kegiatan/{id}', [App\Http\Controllers\Sidongan\ActivityReportController::class, 'update'])->name('lapor_kegiatan.update');
    Route::delete('/lapor-kegiatan/{id}', [App\Http\Controllers\Sidongan\ActivityReportController::class, 'destroy'])->name('lapor_kegiatan.destroy');

    // Routes untuk Verifikasi Laporan Kegiatan (Ketua PKK)
    Route::get('/verifikasi', [App\Http\Controllers\Sidongan\VerificationController::class, 'index'])
        ->name('verifikasi');

    Route::get('/verifikasi/{id}/form', [App\Http\Controllers\Sidongan\VerificationController::class, 'form'])
        ->name('verifikasi.form');

    Route::post('/verifikasi/{id}', [App\Http\Controllers\Sidongan\VerificationController::class, 'store'])
        ->name('verifikasi.store');

    // Halaman notifikasi list
    Route::get('/notifications', [App\Http\Controllers\Sidongan\AdminDocumentController::class, 'notifications'])
        ->name('notifications');

    Route::post('/notifications/{id}/read', [App\Http\Controllers\Sidongan\AdminDocumentController::class, 'markNotificationAsRead'])
        ->name('notifications.read');

    // Route untuk mark all notifications as read
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\Sidongan\AdminDocumentController::class, 'markAllNotificationsAsRead'])
        ->name('notifications.mark-all-read');
});


// ================= AUTH ROUTES (Wajib di paling bawah) =================
require __DIR__.'/auth.php';