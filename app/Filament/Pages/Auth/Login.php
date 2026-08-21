<?php

namespace App\Filament\Pages\Auth;

use App\Models\SiteSetting;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Support\Facades\Schema;

class Login extends BaseLogin
{
    public function getSubheading(): string
    {
        $collegeName = 'ວິທະຍາໄລຄູສົງ ອົງຕື້';

        try {
            if (Schema::hasTable('site_settings')) {
                $collegeName = SiteSetting::group('general')['college_name_lo'] ?? $collegeName;
            }
        } catch (\Throwable $e) {
            // keep default
        }

        return "{$collegeName} — ລະບົບຈັດການເວັບໄຊ";
    }
}
