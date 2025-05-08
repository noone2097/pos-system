<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Pos;
use App\Filament\Resources\CategoryResource;
use App\Filament\Resources\CustomerResource;
use App\Filament\Resources\ProductResource;
use App\Filament\Resources\SaleResource;
use App\Filament\Widgets\InventoryStats;
use App\Filament\Widgets\MonthlySalesChart;
use App\Filament\Widgets\SalesChart;
use App\Filament\Widgets\StatsOverview;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class LoonPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('loon')
            ->path('loon')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->brandLogo(fn () => view('filament.components.logo'))
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
                Pos::class,
            ])
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Inventory Management')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Sales Management')
                    ->collapsed(),
            ])
            ->sidebarCollapsibleOnDesktop()
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
            ])
            ->resources([
                CategoryResource::class,
                ProductResource::class,
                CustomerResource::class,
                SaleResource::class,
            ])
            ->maxContentWidth('full')
            ->widgets([
                StatsOverview::class,
                InventoryStats::class,
                SalesChart::class,
                MonthlySalesChart::class,
            ]);
    }
}
