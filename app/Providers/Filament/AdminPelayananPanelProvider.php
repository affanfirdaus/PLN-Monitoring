<?php

namespace App\Providers\Filament;

use App\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Assets\Css;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPelayananPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin-pelayanan')
            ->path('internal/admin-pelayanan')
            ->authGuard('web')
            ->colors([
                'primary' => Color::hex('#0B5ED7'),
            ])
            ->brandName('Sistem Monitoring PLN')
            ->brandLogo(asset('images/pln-logo2.png'))
            ->brandLogoHeight('2.5rem')
            ->maxContentWidth('full')
            ->assets([
                Css::make('admin-pelayanan-custom', asset('css/filament/filament/admin-pelayanan.css')),
            ])
            ->renderHook(PanelsRenderHook::BODY_START, fn () => view('filament.admin-pelayanan.partials.topbar'))
            ->renderHook(PanelsRenderHook::CONTENT_START, fn () => view('filament.admin-pelayanan.partials.admin-pelayanan-title'))
            ->discoverResources(in: app_path('Filament/AdminPelayanan/Resources'), for: 'App\\Filament\\AdminPelayanan\\Resources')
            ->discoverPages(in: app_path('Filament/AdminPelayanan/Pages'), for: 'App\\Filament\\AdminPelayanan\\Pages')
            ->pages([
                \App\Filament\AdminPelayanan\Pages\AdminLayananDashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/AdminPelayanan/Widgets'), for: 'App\\Filament\\AdminPelayanan\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
            ])
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
