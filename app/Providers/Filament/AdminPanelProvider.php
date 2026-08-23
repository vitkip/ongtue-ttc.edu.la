<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\Login;
use App\Models\SiteSetting;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $general = [];

        try {
            if (Schema::hasTable('site_settings')) {
                $general = SiteSetting::group('general');
            }
        } catch (\Throwable $e) {
            $general = [];
        }

        $brandLogo = ! empty($general['logo']) ? Storage::disk('public')->url($general['logo']) : null;

        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->brandName($general['college_name_lo'] ?? 'ວິທະຍາໄລຄູສົງ ອົງຕື້')
            ->brandLogoHeight('2.25rem')
            ->when($brandLogo, fn (Panel $panel) => $panel->brandLogo($brandLogo))
            ->favicon($brandLogo ?? asset('favicon.ico'))
            ->login(Login::class)
            ->renderHook(
                'panels::head.end',
                fn () => new HtmlString(<<<'HTML'
                    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
                    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@400;500;600;700&family=Noto+Serif+Lao:wght@600;700&display=swap" rel="stylesheet">
                    <style>
                        :root {
                            --font-family: Inter, 'Noto Sans Lao', sans-serif;
                        }
                        .fi-sidebar-group-label,
                        h1, h2, .fi-header-heading {
                            font-family: 'Noto Serif Lao', Inter, sans-serif;
                        }
                    </style>
                    HTML)
            )
            ->colors([
                'primary' => Color::hex('#800000'),
            ])
            ->navigationGroups([
                'ເນື້ອຫາເວັບໄຊ',
                'ອົງກອນ',
                'ຄຳຮ້ອງ ແລະ ຕິດຕໍ່',
                'ລະບົບ',
            ])
            ->collapsibleNavigationGroups(false)
            ->userMenuItems([
                'view-site' => MenuItem::make()
                    ->label('ເບິ່ງໜ້າເວັບໄຊ')
                    ->icon('heroicon-o-globe-alt')
                    ->url(fn () => url('/'))
                    ->openUrlInNewTab(),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
            ])
            // This list is the panel's full middleware stack (Filament\Panel starts with none),
            // matching what `filament:install` scaffolds. Re-check it against Filament's install
            // stub after major Filament upgrades in case new required middleware is added upstream.
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
