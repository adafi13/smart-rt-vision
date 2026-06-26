<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Cek trial/subscription yang akan & sudah berakhir. Di shared hosting cPanel,
// daftarkan 1 cron job: `php artisan schedule:run` setiap menit — tidak perlu queue worker.
Schedule::command('app:check-subscription-expiry')->daily();
