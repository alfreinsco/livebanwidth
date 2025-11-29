<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule traffic data collection setiap 30 detik
// Note: Untuk menggunakan schedule, pastikan kredensial router sudah di-set di .env
// atau jalankan dengan parameter: php artisan traffic:collect --ip=xxx --user=xxx --password=xxx
// Schedule::command('traffic:collect --ip=' . env('ROUTER_IP') . ' --user=' . env('ROUTER_USER') . ' --password=' . env('ROUTER_PASSWORD'))
//     ->everyThirtySeconds()
//     ->withoutOverlapping()
//     ->runInBackground();
