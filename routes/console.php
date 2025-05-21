<?php

use App\Models\MembershipUser;
use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::call(function () {
    MembershipUser::where('status', 'aktif')
        ->where('tanggal_berakhir', '<', Carbon::now())
        ->update(['status' => 'non-aktif']);
})->everyMinute();
