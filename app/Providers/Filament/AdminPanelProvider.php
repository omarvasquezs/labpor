<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
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
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->brandLogo('https://www.laboratoriosportugal.com/wp-content/uploads/2022/03/PORTUGAL-GRAPHICS-LOGO-1.png')
            ->brandLogoHeight('6rem')
            ->renderHook(
                'panels::content.start',
                fn (): string => view('filament.components.custom-alert')->render(),
            )
            ->renderHook(
                'panels::body.end',
                fn (): string => '
                <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.2.0/chartjs-plugin-datalabels.min.js" integrity="sha512-JPcRR8yFa8mmRTjbKJ6BGUPbQfPfnyYaiuGWalVp8k5gz9v9oJHjMD0feGat54wPjQUv1X56alRLUHS1k23VNg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
                <script>
                    (function() {
                        const registerPlugin = () => {
                            if (typeof Chart !== "undefined" && typeof ChartDataLabels !== "undefined") {
                                Chart.register(ChartDataLabels);
                            } else {
                                setTimeout(registerPlugin, 200);
                            }
                        };
                        registerPlugin();
                    })();
                </script>
                <style>
                    body {
                        background-image: url("' . asset('labpor_background_optimized.png') . '");
                        background-size: cover;
                        background-position: center;
                        background-repeat: no-repeat;
                        background-attachment: fixed;
                    }
                    .fi-body {
                        background-color: transparent !important;
                    }
                    .fi-sidebar {
                        background-color: white !important;
                    }
                    :is(.dark) .fi-sidebar {
                        background-color: #18181b !important;
                    }
                    .fi-main .fi-header-heading,
                    .fi-main .fi-header-subheading, 
                    .fi-main .fi-breadcrumbs-item-label {
                        color: white !important;
                    }
                </style>',
            )
            ->assets([
                \Filament\Support\Assets\Js::make('chartjs-datalabels', 'https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.2.0/chartjs-plugin-datalabels.min.js'),
                \Filament\Support\Assets\Js::make('chart-datalabels-register', asset('js/chart-datalabels.js')),
            ])
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
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
