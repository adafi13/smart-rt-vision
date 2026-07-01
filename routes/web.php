<?php

use App\Http\Controllers\Admin\ContributionController as AdminContributionController;
use App\Http\Controllers\Admin\ExpenseController as AdminExpenseController;
use App\Http\Controllers\Admin\LetterRequestController as AdminLetterRequestController;
use App\Http\Controllers\Admin\LifeEventController as AdminLifeEventController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\VacantHomeController as AdminVacantHomeController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\ContributionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LetterRequestController;
use App\Http\Controllers\LifeEventController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PushSubscriptionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\Public\BlogController;
use App\Http\Controllers\SuperAdmin\ArticleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PENTING: urutan route di file ini disengaja.
|--------------------------------------------------------------------------
| Route publik per-tenant didaftarkan paling akhir karena bentuknya
| "/{tenant:slug}" — satu segmen path generik yang akan "menelan" rute
| spesifik (mis. /dashboard, /login, /kk) jika didaftarkan lebih dulu.
| Selalu tambahkan rute baru yang TIDAK butuh slug tenant SEBELUM grup
| tenant di bagian paling bawah file ini.
*/

// Webhook server-to-server dari Xendit — tanpa sesi/CSRF (lihat bootstrap/app.php).
Route::post('/webhook/xendit', [BillingController::class, 'webhook'])->name('webhook.xendit');

// ===== HALAMAN JUALAN SAAS (marketing, publik) =====
Route::get('/', [MarketingController::class, 'index'])->name('marketing.home');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/syarat-dan-ketentuan', function () {
    return view('terms');
})->name('terms');

Route::get('/kebijakan-privasi', function () {
    return view('privacy');
})->name('privacy');

// Alias untuk Google OAuth Console (English URLs)
Route::get('/privacy-policy', fn() => redirect('/kebijakan-privasi', 301));
Route::get('/terms', fn() => redirect('/syarat-dan-ketentuan', 301));

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/suspended', function () {
        if (auth()->user()->tenant?->status !== 'suspended') {
            return redirect()->route('dashboard');
        }
        return view('errors.suspended');
    })->name('suspended');

    Route::get('/expired', function () {
        $tenant = auth()->user()->tenant;
        if (!$tenant || $tenant->isActive()) {
            return redirect()->route('dashboard');
        }
        $plans = \App\Models\Plan::where('is_active', true)->orderBy('sort_order')->get();
        return view('errors.expired', compact('plans'));
    })->name('expired');
});

// ===== AREA PENGURUS (login, otomatis ter-scope ke tenant pengurus tsb) =====
Route::middleware(['auth', 'verified', 'tenant.auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('kk')->name('kk.')->middleware('rt_role:owner,sekretaris')->group(function () {
        Route::get('/', [FamilyController::class, 'index'])->name('index');
        Route::get('/create', [FamilyController::class, 'create'])->name('create');
        Route::get('/upload', [FamilyController::class, 'upload'])->name('upload');
        Route::post('/extract', [FamilyController::class, 'extract'])->name('extract');
        Route::get('/verify', [FamilyController::class, 'verify'])->name('verify');
        Route::post('/store', [FamilyController::class, 'store'])->name('store');
        
        Route::post('/{family}/approve', [FamilyController::class, 'approve'])->name('approve');
        Route::post('/{family}/reject', [FamilyController::class, 'reject'])->name('reject');

        // KK Updates (Pembaruan KK)
        Route::prefix('updates')->name('updates.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\KkUpdateController::class, 'index'])->name('index');
            Route::get('/{update}', [\App\Http\Controllers\Admin\KkUpdateController::class, 'show'])->name('show');
            Route::post('/{update}/approve', [\App\Http\Controllers\Admin\KkUpdateController::class, 'approve'])->name('approve');
            Route::post('/{update}/reject', [\App\Http\Controllers\Admin\KkUpdateController::class, 'reject'])->name('reject');
        });

        // Catatan: harus didaftarkan SEBELUM /{family} agar tidak "ditelan" sebagai parameter family.
        Route::get('/import', [FamilyController::class, 'importForm'])->name('import.form');
        Route::post('/import', [FamilyController::class, 'import'])->name('import.store');
        Route::get('/import/template', [FamilyController::class, 'downloadTemplate'])->name('import.template');

        Route::get('/{family}', [FamilyController::class, 'show'])->name('show');
        Route::get('/{family}/edit', [FamilyController::class, 'edit'])->name('edit');
        Route::put('/{family}', [FamilyController::class, 'update'])->name('update');
        Route::delete('/{family}', [FamilyController::class, 'destroy'])->name('destroy');

        Route::post('/{family}/anggota', [MemberController::class, 'store'])->name('anggota.store');
        Route::put('/{family}/anggota/{member}', [MemberController::class, 'updateMember'])->name('anggota.update');
        Route::delete('/{family}/anggota/{member}', [MemberController::class, 'destroyMember'])->name('anggota.destroy');
    });

    Route::prefix('warga')->name('warga.')->middleware('rt_role:owner,sekretaris')->group(function () {
        Route::get('/', [MemberController::class, 'index'])->name('index');
    });

    Route::prefix('warga-kos')->name('warga-kos.')->middleware('rt_role:owner,sekretaris')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\TemporaryResidentController::class, 'index'])->name('index');
        Route::put('/{resident}', [\App\Http\Controllers\Admin\TemporaryResidentController::class, 'update'])->name('update');
        Route::delete('/{resident}', [\App\Http\Controllers\Admin\TemporaryResidentController::class, 'destroy'])->name('destroy');
    });

    Route::get('/export/excel', [ExportController::class, 'excel'])->name('export.excel');
    Route::get('/export/pdf', [ExportController::class, 'pdf'])->name('export.pdf');

    // Cetak surat (PDF) untuk warga
    Route::get('/cetak-surat/{id}', [PdfController::class, 'cetakSurat'])->name('cetak_surat')->middleware('rt_role:owner,sekretaris');
    Route::get('/cetak-permohonan/{id}', [PdfController::class, 'cetakSuratPermohonan'])->name('cetak_permohonan')->middleware('rt_role:owner,sekretaris');

    // ===== BILLING / SUBSCRIPTION =====
    Route::prefix('billing')->name('billing.')->middleware('rt_role:owner,bendahara')->group(function () {
        Route::get('/', [BillingController::class, 'index'])->name('index');
        Route::get('/success/{subscription}', [BillingController::class, 'success'])->name('success');
        Route::post('/checkout/{plan}', [BillingController::class, 'checkout'])->name('checkout');
        Route::post('/cancel/{subscription}', [BillingController::class, 'cancel'])->name('cancel');
    });

    // ===== MODUL ADMIN RT =====
    Route::prefix('admin/iuran')->name('admin.iuran.')->middleware('rt_role:owner,bendahara')->group(function () {
        Route::get('/', [AdminContributionController::class, 'index'])->name('index');
        Route::get('/create', [AdminContributionController::class, 'create'])->name('create');
        Route::post('/', [AdminContributionController::class, 'store'])->name('store');
        Route::get('/{contribution}/edit', [AdminContributionController::class, 'edit'])->name('edit');
        Route::put('/{contribution}', [AdminContributionController::class, 'update'])->name('update');
        Route::delete('/{contribution}', [AdminContributionController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('admin/tunggakan')->name('admin.tunggakan.')->middleware('rt_role:owner,bendahara')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\TunggakanController::class, 'index'])->name('index');
        Route::post('/setting', [\App\Http\Controllers\Admin\TunggakanController::class, 'updateSetting'])->name('update-setting');
    });

    Route::prefix('admin/pengeluaran')->name('admin.pengeluaran.')->middleware('rt_role:owner,bendahara')->group(function () {
        Route::get('/', [AdminExpenseController::class, 'index'])->name('index');
        Route::get('/create', [AdminExpenseController::class, 'create'])->name('create');
        Route::post('/', [AdminExpenseController::class, 'store'])->name('store');
        Route::get('/{expense}/edit', [AdminExpenseController::class, 'edit'])->name('edit');
        Route::put('/{expense}', [AdminExpenseController::class, 'update'])->name('update');
        Route::delete('/{expense}', [AdminExpenseController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('admin/laporan-kas-rw')->name('admin.rw-finance.')->middleware('rt_role:owner,bendahara')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\RwFinanceReportController::class, 'index'])->name('index');
    });

    Route::prefix('admin/pengajuan-surat')->name('admin.pengajuan.')->middleware('rt_role:owner,sekretaris')->group(function () {
        Route::get('/', [AdminLetterRequestController::class, 'index'])->name('index');
        Route::post('/signature', [AdminLetterRequestController::class, 'updateSignature'])->name('signature');
        Route::get('/{letterRequest}/pdf', [AdminLetterRequestController::class, 'downloadPdf'])->name('pdf');
        Route::put('/{letterRequest}', [AdminLetterRequestController::class, 'update'])->name('update');
        Route::delete('/{letterRequest}', [AdminLetterRequestController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('admin/laporan-warga')->name('admin.laporan.')->middleware('rt_role:owner,sekretaris,keamanan,pembangunan')->group(function () {
        Route::get('/', [AdminReportController::class, 'index'])->name('index');
        Route::put('/{report}', [AdminReportController::class, 'update'])->name('update');
        Route::delete('/{report}', [AdminReportController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('admin/riwayat-scan')->name('admin.riwayat-scan.')->middleware('rt_role:owner,sekretaris')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\KkScanLogController::class, 'index'])->name('index');
    });

    Route::prefix('admin/peristiwa')->name('admin.peristiwa.')->middleware('rt_role:owner,sekretaris,humas')->group(function () {
        Route::get('/', [AdminLifeEventController::class, 'index'])->name('index');
        Route::put('/{lifeEvent}', [AdminLifeEventController::class, 'update'])->name('update');
        Route::delete('/{lifeEvent}', [AdminLifeEventController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('admin/berita')->name('admin.berita.')->middleware('rt_role:owner,sekretaris,humas')->group(function () {
        Route::get('/', [AdminNewsController::class, 'index'])->name('index');
        Route::get('/create', [AdminNewsController::class, 'create'])->name('create');
        Route::post('/', [AdminNewsController::class, 'store'])->name('store');
        Route::get('/{news}/edit', [AdminNewsController::class, 'edit'])->name('edit');
        Route::put('/{news}', [AdminNewsController::class, 'update'])->name('update');
        Route::delete('/{news}', [AdminNewsController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('admin/umkm')->name('admin.umkm.')->middleware('rt_role:owner,sekretaris,humas')->group(function () {
        Route::get('/', [AdminProductController::class, 'index'])->name('index');
        Route::get('/create', [AdminProductController::class, 'create'])->name('create');
        Route::post('/', [AdminProductController::class, 'store'])->name('store');
        Route::get('/{product}/edit', [AdminProductController::class, 'edit'])->name('edit');
        Route::put('/{product}', [AdminProductController::class, 'update'])->name('update');
        Route::delete('/{product}', [AdminProductController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('admin/inventaris')->name('admin.inventaris.')->middleware('rt_role:owner,sekretaris,bendahara,pembangunan')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\InventoryController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\Admin\InventoryController::class, 'store'])->name('store');
        Route::put('/{inventory}', [\App\Http\Controllers\Admin\InventoryController::class, 'update'])->name('update');
        Route::delete('/{inventory}', [\App\Http\Controllers\Admin\InventoryController::class, 'destroy'])->name('destroy');
        
        Route::post('/borrowings/{borrowing}/approve', [\App\Http\Controllers\Admin\InventoryController::class, 'approve'])->name('borrowings.approve');
        Route::post('/borrowings/{borrowing}/reject', [\App\Http\Controllers\Admin\InventoryController::class, 'reject'])->name('borrowings.reject');
        Route::post('/borrowings/{borrowing}/return', [\App\Http\Controllers\Admin\InventoryController::class, 'returnItem'])->name('borrowings.return');
    });

    Route::prefix('admin/ronda')->name('admin.ronda.')->middleware('rt_role:owner,sekretaris,keamanan')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\RondaController::class, 'index'])->name('index');
        Route::post('/schedule', [\App\Http\Controllers\Admin\RondaController::class, 'storeSchedule'])->name('schedule.store');
        Route::delete('/schedule/{schedule}', [\App\Http\Controllers\Admin\RondaController::class, 'destroySchedule'])->name('schedule.destroy');
    });

    Route::prefix('admin/staff')->name('admin.staff.')->middleware('rt_role:owner')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\StaffController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\StaffController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\StaffController::class, 'store'])->name('store');
        Route::get('/{staff}/edit', [\App\Http\Controllers\Admin\StaffController::class, 'edit'])->name('edit');
        Route::put('/{staff}', [\App\Http\Controllers\Admin\StaffController::class, 'update'])->name('update');
        Route::post('/{staff}/reset-password', [\App\Http\Controllers\Admin\StaffController::class, 'resetPassword'])->name('reset_password');
        Route::delete('/{staff}', [\App\Http\Controllers\Admin\StaffController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('admin/organisasi')->name('admin.organisasi.')->middleware('rt_role:owner,sekretaris')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\RtStaffController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\RtStaffController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\RtStaffController::class, 'store'])->name('store');
        Route::get('/{staff}/edit', [\App\Http\Controllers\Admin\RtStaffController::class, 'edit'])->name('edit');
        Route::put('/{staff}', [\App\Http\Controllers\Admin\RtStaffController::class, 'update'])->name('update');
        Route::delete('/{staff}', [\App\Http\Controllers\Admin\RtStaffController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('admin/panic-alerts')->name('admin.panic.')->middleware('rt_role:owner,sekretaris,keamanan')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\PanicAlertController::class, 'index'])->name('index');
        Route::post('/{panicAlert}/resolve', [\App\Http\Controllers\Admin\PanicAlertController::class, 'resolve'])->name('resolve');
    });

    Route::prefix('admin/polls')->name('admin.polls.')->middleware('rt_role:owner,sekretaris,humas')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\PollController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\PollController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\PollController::class, 'store'])->name('store');
        Route::get('/{poll}/edit', [\App\Http\Controllers\Admin\PollController::class, 'edit'])->name('edit');
        Route::put('/{poll}', [\App\Http\Controllers\Admin\PollController::class, 'update'])->name('update');
        Route::delete('/{poll}', [\App\Http\Controllers\Admin\PollController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('admin/guestbooks')->name('admin.guestbooks.')->middleware('rt_role:owner,sekretaris,humas')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\GuestbookController::class, 'index'])->name('index');
        Route::post('/update-pin', [\App\Http\Controllers\Admin\GuestbookController::class, 'updatePin'])->name('update-pin');
        Route::post('/{guestbook}/mark-left', [\App\Http\Controllers\Admin\GuestbookController::class, 'markAsLeft'])->name('mark-left');
        Route::delete('/{guestbook}', [\App\Http\Controllers\Admin\GuestbookController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('admin/vacant-homes')->name('admin.vacant-homes.')->middleware('rt_role:owner,sekretaris,keamanan')->group(function () {
        Route::get('/', [AdminVacantHomeController::class, 'index'])->name('index');
        Route::get('/{vacantHome}', [AdminVacantHomeController::class, 'show'])->name('show');
        Route::post('/{vacantHome}/log', [AdminVacantHomeController::class, 'storeLog'])->name('log.store');
        Route::put('/{vacantHome}/status', [AdminVacantHomeController::class, 'updateStatus'])->name('status.update');
        Route::delete('/{vacantHome}', [AdminVacantHomeController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('admin/agenda')->name('admin.agendas.')->middleware('rt_role:owner,sekretaris,humas')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AdminAgendaController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\AdminAgendaController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\AdminAgendaController::class, 'store'])->name('store');
        Route::get('/{agenda}/edit', [\App\Http\Controllers\Admin\AdminAgendaController::class, 'edit'])->name('edit');
        Route::put('/{agenda}', [\App\Http\Controllers\Admin\AdminAgendaController::class, 'update'])->name('update');
        Route::delete('/{agenda}', [\App\Http\Controllers\Admin\AdminAgendaController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('admin/dokumen')->name('admin.documents.')->middleware('rt_role:owner,sekretaris')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AdminDocumentController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\AdminDocumentController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\AdminDocumentController::class, 'store'])->name('store');
        Route::get('documents/{document}/download', [\App\Http\Controllers\Admin\AdminDocumentController::class, 'download'])->name('admin.documents.download');
        Route::get('documents/{document}/view', [\App\Http\Controllers\Admin\AdminDocumentController::class, 'view'])->name('admin.documents.view');
        
        Route::delete('/{document}', [\App\Http\Controllers\Admin\AdminDocumentController::class, 'destroy'])->name('destroy');
        Route::get('/{document}/download', [\App\Http\Controllers\Admin\AdminDocumentController::class, 'download'])->name('download');
    });

    // Settings RT (Pengaturan Profil RT)
    Route::prefix('admin/settings')->name('admin.settings.')->middleware('rt_role:owner')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\TenantSettingsController::class, 'index'])->name('index');
        Route::post('/generate-token', [\App\Http\Controllers\Admin\TenantSettingsController::class, 'generateToken'])->name('generate-token');
    });

    Route::prefix('admin/cctv')->name('admin.cctvs.')->middleware('rt_role:owner,keamanan')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AdminCctvController::class, 'index'])->name('index');
        Route::post('/ezviz-settings', [\App\Http\Controllers\Admin\AdminCctvController::class, 'updateEzvizSettings'])->name('ezviz-settings');
        Route::post('/', [\App\Http\Controllers\Admin\AdminCctvController::class, 'store'])->name('store');
        Route::put('/{cctv}', [\App\Http\Controllers\Admin\AdminCctvController::class, 'update'])->name('update');
        Route::delete('/{cctv}', [\App\Http\Controllers\Admin\AdminCctvController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('admin/tickets')->name('admin.tickets.')->middleware('rt_role:owner')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\TicketController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\TicketController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\TicketController::class, 'store'])->name('store');
        Route::get('/{ticket}', [\App\Http\Controllers\Admin\TicketController::class, 'show'])->name('show');
        Route::post('/{ticket}/reply', [\App\Http\Controllers\Admin\TicketController::class, 'reply'])->name('reply');
        Route::post('/{ticket}/close', [\App\Http\Controllers\Admin\TicketController::class, 'close'])->name('close');
    });

    Route::prefix('admin/logs')->name('admin.logs.')->middleware('rt_role:owner')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('index');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dismiss announcement (AJAX) — diakses oleh mitra RT
    Route::post('/announcements/dismiss', [\App\Http\Controllers\AnnouncementDismissController::class, 'dismiss'])->name('announcements.dismiss');

    // Leave impersonation — harus di luar grup super_admin karena saat impersonating,
    // auth user adalah Admin RT (bukan Super Admin), sehingga middleware super_admin akan 403.
    Route::post('/super-admin/leave-impersonation', [\App\Http\Controllers\SuperAdmin\ImpersonationController::class, 'leave'])->name('super-admin.leave-impersonation');
    
    // Web Push Subscriptions
    Route::post('/push-subscriptions', [PushSubscriptionController::class, 'update'])->name('push-subscriptions.update');
});

// ===== PANEL SUPER ADMIN (internal PT Sekawan Putra Pratama) =====
Route::middleware(['auth', 'super_admin'])->prefix('super-admin')->name('super-admin.')->group(function () {
    Route::get('/', [SuperAdminController::class, 'index'])->name('index');
    Route::get('/tenants', [SuperAdminController::class, 'tenants'])->name('tenants.index');
    Route::get('/search', [\App\Http\Controllers\SuperAdmin\SearchController::class, 'search'])->name('search');
    
    // Manajemen Paket Langganan
    Route::resource('plans', \App\Http\Controllers\SuperAdmin\PlanController::class)->except('show');
    
    // Manajemen Kupon / Diskon
    Route::resource('coupons', \App\Http\Controllers\SuperAdmin\CouponController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('coupons/{coupon}/toggle', [\App\Http\Controllers\SuperAdmin\CouponController::class, 'toggleActive'])->name('coupons.toggle');

    // Helpdesk / Sistem Tiket (Super Admin)
    Route::resource('tickets', \App\Http\Controllers\SuperAdmin\TicketController::class)->only(['index', 'show']);
    Route::post('tickets/{ticket}/reply', [\App\Http\Controllers\SuperAdmin\TicketController::class, 'reply'])->name('tickets.reply');
    Route::patch('tickets/{ticket}/status', [\App\Http\Controllers\SuperAdmin\TicketController::class, 'updateStatus'])->name('tickets.status');
    Route::patch('tickets/{ticket}/assign', [\App\Http\Controllers\SuperAdmin\TicketController::class, 'assign'])->name('tickets.assign');

    // Impersonation (start only — leave is outside this group)
    Route::post('impersonate/{tenant}', [\App\Http\Controllers\SuperAdmin\ImpersonationController::class, 'impersonate'])->name('impersonate');

    // Transaksi / Billing SaaS
    Route::get('transactions', [\App\Http\Controllers\SuperAdmin\TransactionController::class, 'index'])->name('transactions.index');

    // Audit Logs
    Route::get('audit-logs', [\App\Http\Controllers\SuperAdmin\AuditLogController::class, 'index'])->name('audit-logs.index');

    // Pengaturan Global
    Route::get('settings', [\App\Http\Controllers\SuperAdmin\SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [\App\Http\Controllers\SuperAdmin\SettingController::class, 'update'])->name('settings.update');
    Route::post('settings/maintenance', [\App\Http\Controllers\SuperAdmin\SettingController::class, 'toggleMaintenance'])->name('settings.maintenance');

    // Pengumuman Global (terpisah)
    Route::resource('announcements', \App\Http\Controllers\SuperAdmin\AnnouncementController::class)->except('show');
    Route::post('announcements/{announcement}/toggle', [\App\Http\Controllers\SuperAdmin\AnnouncementController::class, 'toggleActive'])->name('announcements.toggle');

    // Manajemen Blog / Artikel SEO
    Route::resource('articles', ArticleController::class)->except('show');

    // Laporan Keuangan
    Route::get('finance', [\App\Http\Controllers\SuperAdmin\FinanceController::class, 'index'])->name('finance.index');

    // User Management Super Admin
    Route::resource('staff', \App\Http\Controllers\SuperAdmin\SuperAdminStaffController::class)->except('show');

    // Keamanan Akun
    Route::get('security', [\App\Http\Controllers\SuperAdmin\SecurityController::class, 'index'])->name('security.index');
    Route::post('security', [\App\Http\Controllers\SuperAdmin\SecurityController::class, 'update'])->name('security.update');

    // Manajemen RW
    Route::resource('rws', \App\Http\Controllers\SuperAdmin\RwController::class)->only(['index', 'show']);
    Route::post('impersonate-rw/{rw}', [\App\Http\Controllers\SuperAdmin\ImpersonationController::class, 'impersonateRw'])->name('impersonate.rw');

    // ⚠️ Wildcard routes HARUS di bawah semua route spesifik agar tidak menangkap 'finance', 'staff', 'security', 'rws' dll
    Route::get('/{tenant}', [SuperAdminController::class, 'show'])->name('show');
    Route::get('/{tenant}/edit', [SuperAdminController::class, 'edit'])->name('edit');
    Route::put('/{tenant}', [SuperAdminController::class, 'update'])->name('update');
    Route::put('/{tenant}/status', [SuperAdminController::class, 'updateStatus'])->name('status');
    Route::post('/{tenant}/subscription', [SuperAdminController::class, 'grantSubscription'])->name('grant');
    Route::post('/{tenant}/reset-password', [SuperAdminController::class, 'resetOwnerPassword'])->name('reset-password');
});

// ===== SUPER RW =====
Route::middleware(['auth', 'rw_admin'])->prefix('rw')->name('rw.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Rw\DashboardController::class, 'index'])->name('dashboard');

    // Manajemen RT
    Route::resource('tenants', \App\Http\Controllers\Rw\TenantController::class)->only(['index', 'create', 'store', 'show']);

    // Klaim RT Lama
    Route::get('adopt', [\App\Http\Controllers\Rw\TenantController::class, 'adoptSearch'])->name('tenants.adopt');
    Route::post('adopt/{tenant}', [\App\Http\Controllers\Rw\TenantController::class, 'adopt'])->name('tenants.adopt.store');

    // Pengaturan RW
    Route::get('/settings', [\App\Http\Controllers\Rw\SettingsController::class, 'index'])->name('settings');
    Route::put('/settings', [\App\Http\Controllers\Rw\SettingsController::class, 'update'])->name('settings.update');

    // Tiket Bantuan (Support Tickets)
    Route::resource('tickets', \App\Http\Controllers\Rw\TicketController::class)->only(['index', 'create', 'store', 'show']);
    Route::post('tickets/{ticket}/reply', [\App\Http\Controllers\Rw\TicketController::class, 'reply'])->name('tickets.reply');
    Route::post('tickets/{ticket}/close', [\App\Http\Controllers\Rw\TicketController::class, 'close'])->name('tickets.close');

    // Pengumuman/Broadcast
    Route::resource('broadcasts', \App\Http\Controllers\Rw\BroadcastController::class)->except('show');

    // Direktori Warga Global (RW)
    Route::get('/warga', [\App\Http\Controllers\Rw\MemberController::class, 'index'])->name('members.index');
    Route::get('/warga/export-excel', [\App\Http\Controllers\Rw\MemberController::class, 'exportExcel'])->name('members.export-excel');

    // Pengajuan Surat (RW)
    Route::prefix('pengajuan-surat')->name('letter-requests.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Rw\LetterRequestController::class, 'index'])->name('index');
        Route::put('/{letterRequest}', [\App\Http\Controllers\Rw\LetterRequestController::class, 'update'])->name('update');
        Route::get('/{letterRequest}/pdf', [\App\Http\Controllers\Rw\LetterRequestController::class, 'downloadPdf'])->name('pdf');
    });

    // Keuangan (Kas Gabungan RW)
    Route::prefix('keuangan')->name('finance.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Rw\FinanceController::class, 'index'])->name('index');
        // Iuran RT
        Route::post('/contributions', [\App\Http\Controllers\Rw\FinanceController::class, 'storeContribution'])->name('contributions.store');
        Route::delete('/contributions/{contribution}', [\App\Http\Controllers\Rw\FinanceController::class, 'destroyContribution'])->name('contributions.destroy');
        // Pengeluaran RW
        Route::post('/expenses', [\App\Http\Controllers\Rw\FinanceController::class, 'storeExpense'])->name('expenses.store');
        Route::delete('/expenses/{expense}', [\App\Http\Controllers\Rw\FinanceController::class, 'destroyExpense'])->name('expenses.destroy');
    });

    // Agenda & Berita RW
    Route::resource('agendas', \App\Http\Controllers\Rw\AgendaController::class)->except('show');
    
    Route::prefix('berita')->name('berita.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Rw\NewsController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Rw\NewsController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Rw\NewsController::class, 'store'])->name('store');
        Route::get('/{news}/edit', [\App\Http\Controllers\Rw\NewsController::class, 'edit'])->name('edit');
        Route::put('/{news}', [\App\Http\Controllers\Rw\NewsController::class, 'update'])->name('update');
        Route::delete('/{news}', [\App\Http\Controllers\Rw\NewsController::class, 'destroy'])->name('destroy');
    });

    // Inventaris RW
    Route::prefix('inventaris')->name('inventaris.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Rw\InventoryController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\Rw\InventoryController::class, 'store'])->name('store');
        Route::put('/{inventory}', [\App\Http\Controllers\Rw\InventoryController::class, 'update'])->name('update');
        Route::delete('/{inventory}', [\App\Http\Controllers\Rw\InventoryController::class, 'destroy'])->name('destroy');
        
        Route::post('/borrowings/{borrowing}/approve', [\App\Http\Controllers\Rw\InventoryController::class, 'approve'])->name('borrowings.approve');
        Route::post('/borrowings/{borrowing}/reject', [\App\Http\Controllers\Rw\InventoryController::class, 'reject'])->name('borrowings.reject');
        Route::post('/borrowings/{borrowing}/return', [\App\Http\Controllers\Rw\InventoryController::class, 'returnItem'])->name('borrowings.return');
    });
});
require __DIR__.'/auth.php';

Route::middleware('guest')->group(function () {
    Route::get('register-rw', [\App\Http\Controllers\Auth\RwRegisteredUserController::class, 'create'])
                ->name('register.rw');
    Route::post('register-rw', [\App\Http\Controllers\Auth\RwRegisteredUserController::class, 'store']);
});

// ===== PORTAL PUBLIK PER-RT (tanpa login, path: /{tenant}/...) =====
// HARUS PALING BAWAH — lihat catatan di atas file ini.
Route::middleware(['tenant.slug'])->prefix('/{tenant:slug}')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/kebijakan-privasi', [HomeController::class, 'privasi'])->name('privasi');
    Route::get('/syarat-ketentuan', [HomeController::class, 'syarat'])->name('syarat');
    Route::get('/cek-nik', [HomeController::class, 'cekNik'])->name('cek-nik');
    
    // Pendaftaran KK Mandiri (Warga)
    Route::get('/kk-baru', [HomeController::class, 'wargaUploadKk'])->name('warga.kk.upload');
    Route::post('/kk-baru/extract', [HomeController::class, 'wargaExtractKk'])->name('warga.kk.extract');
    Route::get('/kk-baru/verify', [HomeController::class, 'wargaVerifyKk'])->name('warga.kk.verify');
    Route::post('/kk-baru/store', [HomeController::class, 'wargaStoreKk'])->name('warga.kk.store');

    Route::post('/ajukan-surat', [LetterRequestController::class, 'store'])->name('ajukan-surat');
    Route::get('/cek-surat', [LetterRequestController::class, 'cekSurat'])->name('cek-surat');
    Route::get('/unduh-surat/{id}', [LetterRequestController::class, 'downloadPdfPublic'])->name('unduh-surat');
    Route::get('/cek-iuran', [ContributionController::class, 'cekIuran'])->name('cek-iuran');
    Route::post('/kirim-laporan', [ReportController::class, 'store'])->name('kirim-laporan');
    Route::get('/cek-laporan', [ReportController::class, 'cekLaporan'])->name('cek-laporan');
    Route::post('/balas-laporan', [ReportController::class, 'balasLaporan'])->name('balas-laporan');
    Route::post('/lapor-peristiwa', [LifeEventController::class, 'store'])->name('lapor-peristiwa');
    Route::post('/pinjam-inventaris', [HomeController::class, 'pinjamInventaris'])->name('pinjam-inventaris');
    Route::post('/absen-ronda', [HomeController::class, 'absenRonda'])->name('absen-ronda');
    Route::post('/trigger-panic', [HomeController::class, 'triggerPanic'])->name('trigger-panic');
    Route::post('/submit-vote', [HomeController::class, 'submitVote'])->name('submit-vote');
    Route::post('/submit-guestbook', [HomeController::class, 'submitGuestbook'])->name('submit-guestbook');
    Route::post('/lapor-kos', [HomeController::class, 'storeKos'])->name('lapor.kos');
    Route::post('/titip-rumah', [HomeController::class, 'storeVacantHome'])->name('titip-rumah');
    Route::get('/titip-rumah/track', [HomeController::class, 'trackVacantHome'])->name('titip-rumah.track');
});
