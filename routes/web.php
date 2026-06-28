<?php

use App\Http\Controllers\Admin\ContributionController as AdminContributionController;
use App\Http\Controllers\Admin\ExpenseController as AdminExpenseController;
use App\Http\Controllers\Admin\LetterRequestController as AdminLetterRequestController;
use App\Http\Controllers\Admin\LifeEventController as AdminLifeEventController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
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
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SuperAdminController;
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
        Route::get('/upload', [FamilyController::class, 'upload'])->name('upload');
        Route::post('/extract', [FamilyController::class, 'extract'])->name('extract');
        Route::get('/verify', [FamilyController::class, 'verify'])->name('verify');
        Route::post('/store', [FamilyController::class, 'store'])->name('store');

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
        Route::post('/', [AdminContributionController::class, 'store'])->name('store');
        Route::put('/{contribution}', [AdminContributionController::class, 'update'])->name('update');
        Route::delete('/{contribution}', [AdminContributionController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('admin/tunggakan')->name('admin.tunggakan.')->middleware('rt_role:owner,bendahara')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\TunggakanController::class, 'index'])->name('index');
    });

    Route::prefix('admin/pengeluaran')->name('admin.pengeluaran.')->middleware('rt_role:owner,bendahara')->group(function () {
        Route::get('/', [AdminExpenseController::class, 'index'])->name('index');
        Route::post('/', [AdminExpenseController::class, 'store'])->name('store');
        Route::put('/{expense}', [AdminExpenseController::class, 'update'])->name('update');
        Route::delete('/{expense}', [AdminExpenseController::class, 'destroy'])->name('destroy');
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

    Route::prefix('admin/peristiwa')->name('admin.peristiwa.')->middleware('rt_role:owner,sekretaris,humas')->group(function () {
        Route::get('/', [AdminLifeEventController::class, 'index'])->name('index');
        Route::put('/{lifeEvent}', [AdminLifeEventController::class, 'update'])->name('update');
        Route::delete('/{lifeEvent}', [AdminLifeEventController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('admin/berita')->name('admin.berita.')->middleware('rt_role:owner,sekretaris,humas')->group(function () {
        Route::get('/', [AdminNewsController::class, 'index'])->name('index');
        Route::post('/', [AdminNewsController::class, 'store'])->name('store');
        Route::put('/{news}', [AdminNewsController::class, 'update'])->name('update');
        Route::delete('/{news}', [AdminNewsController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('admin/umkm')->name('admin.umkm.')->middleware('rt_role:owner,sekretaris,humas')->group(function () {
        Route::get('/', [AdminProductController::class, 'index'])->name('index');
        Route::post('/', [AdminProductController::class, 'store'])->name('store');
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
        Route::post('/', [\App\Http\Controllers\Admin\StaffController::class, 'store'])->name('store');
        Route::put('/{staff}', [\App\Http\Controllers\Admin\StaffController::class, 'update'])->name('update');
        Route::post('/{staff}/reset-password', [\App\Http\Controllers\Admin\StaffController::class, 'resetPassword'])->name('reset_password');
        Route::delete('/{staff}', [\App\Http\Controllers\Admin\StaffController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('admin/organisasi')->name('admin.organisasi.')->middleware('rt_role:owner,sekretaris')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\RtStaffController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\Admin\RtStaffController::class, 'store'])->name('store');
        Route::put('/{staff}', [\App\Http\Controllers\Admin\RtStaffController::class, 'update'])->name('update');
        Route::delete('/{staff}', [\App\Http\Controllers\Admin\RtStaffController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('admin/panic-alerts')->name('admin.panic.')->middleware('rt_role:owner,sekretaris,keamanan')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\PanicAlertController::class, 'index'])->name('index');
        Route::post('/{panicAlert}/resolve', [\App\Http\Controllers\Admin\PanicAlertController::class, 'resolve'])->name('resolve');
    });

    Route::prefix('admin/polls')->name('admin.polls.')->middleware('rt_role:owner,sekretaris,humas')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\PollController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\Admin\PollController::class, 'store'])->name('store');
        Route::put('/{poll}', [\App\Http\Controllers\Admin\PollController::class, 'update'])->name('update');
        Route::delete('/{poll}', [\App\Http\Controllers\Admin\PollController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('admin/guestbooks')->name('admin.guestbooks.')->middleware('rt_role:owner,sekretaris,humas')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\GuestbookController::class, 'index'])->name('index');
        Route::post('/update-pin', [\App\Http\Controllers\Admin\GuestbookController::class, 'updatePin'])->name('update-pin');
        Route::post('/{guestbook}/mark-left', [\App\Http\Controllers\Admin\GuestbookController::class, 'markAsLeft'])->name('mark-left');
        Route::delete('/{guestbook}', [\App\Http\Controllers\Admin\GuestbookController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('admin/agenda')->name('admin.agendas.')->middleware('rt_role:owner,sekretaris,humas')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AdminAgendaController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\Admin\AdminAgendaController::class, 'store'])->name('store');
        Route::put('/{agenda}', [\App\Http\Controllers\Admin\AdminAgendaController::class, 'update'])->name('update');
        Route::delete('/{agenda}', [\App\Http\Controllers\Admin\AdminAgendaController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('admin/dokumen')->name('admin.documents.')->middleware('rt_role:owner,sekretaris')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AdminDocumentController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\Admin\AdminDocumentController::class, 'store'])->name('store');
        Route::put('/{document}', [\App\Http\Controllers\Admin\AdminDocumentController::class, 'update'])->name('update');
        Route::delete('/{document}', [\App\Http\Controllers\Admin\AdminDocumentController::class, 'destroy'])->name('destroy');
        Route::get('/{document}/download', [\App\Http\Controllers\Admin\AdminDocumentController::class, 'download'])->name('download');
    });

    Route::prefix('admin/cctv')->name('admin.cctvs.')->middleware('rt_role:owner,keamanan')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AdminCctvController::class, 'index'])->name('index');
        Route::post('/ezviz-settings', [\App\Http\Controllers\Admin\AdminCctvController::class, 'updateEzvizSettings'])->name('ezviz-settings');
        Route::post('/', [\App\Http\Controllers\Admin\AdminCctvController::class, 'store'])->name('store');
        Route::put('/{cctv}', [\App\Http\Controllers\Admin\AdminCctvController::class, 'update'])->name('update');
        Route::delete('/{cctv}', [\App\Http\Controllers\Admin\AdminCctvController::class, 'destroy'])->name('destroy');
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
});

// ===== PANEL SUPER ADMIN (internal PT Sekawan Putra Pratama) =====
Route::middleware(['auth', 'super_admin'])->prefix('super-admin')->name('super-admin.')->group(function () {
    Route::get('/', [SuperAdminController::class, 'index'])->name('index');
    Route::get('/tenants', [SuperAdminController::class, 'tenants'])->name('tenants.index');
    
    // Manajemen Paket Langganan
    Route::resource('plans', \App\Http\Controllers\SuperAdmin\PlanController::class)->except('show');

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

    // Laporan Keuangan
    Route::get('finance', [\App\Http\Controllers\SuperAdmin\FinanceController::class, 'index'])->name('finance.index');

    // User Management Super Admin
    Route::resource('staff', \App\Http\Controllers\SuperAdmin\SuperAdminStaffController::class)->except('show');

    // Keamanan Akun
    Route::get('security', [\App\Http\Controllers\SuperAdmin\SecurityController::class, 'index'])->name('security.index');
    Route::post('security', [\App\Http\Controllers\SuperAdmin\SecurityController::class, 'update'])->name('security.update');

    // ⚠️ Wildcard routes HARUS di bawah semua route spesifik agar tidak menangkap 'finance', 'staff', 'security' dll
    Route::get('/{tenant}', [SuperAdminController::class, 'show'])->name('show');
    Route::get('/{tenant}/edit', [SuperAdminController::class, 'edit'])->name('edit');
    Route::put('/{tenant}', [SuperAdminController::class, 'update'])->name('update');
    Route::put('/{tenant}/status', [SuperAdminController::class, 'updateStatus'])->name('status');
    Route::post('/{tenant}/subscription', [SuperAdminController::class, 'grantSubscription'])->name('grant');
    Route::post('/{tenant}/reset-password', [SuperAdminController::class, 'resetOwnerPassword'])->name('reset-password');
});

require __DIR__.'/auth.php';

// ===== PORTAL PUBLIK PER-RT (tanpa login, path: /{tenant}/...) =====
// HARUS PALING BAWAH — lihat catatan di atas file ini.
Route::middleware(['tenant.slug'])->prefix('/{tenant:slug}')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/kebijakan-privasi', [HomeController::class, 'privasi'])->name('privasi');
    Route::get('/syarat-ketentuan', [HomeController::class, 'syarat'])->name('syarat');
    Route::get('/cek-nik', [HomeController::class, 'cekNik'])->name('cek-nik');
    Route::post('/ajukan-surat', [LetterRequestController::class, 'store'])->name('ajukan-surat');
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
});
