<?php

namespace App\Providers;

use App\Filament\Pages\Pos;
use Filament\Facades\Filament;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Support\ServiceProvider;

class FilamentPosServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Filament::registerPages([
            Pos::class,
        ]);

        $this->app->booted(function () {
            if (auth()->check() && auth()->user()->hasRole('cashier') && !auth()->user()->hasRole('admin')) {
                Filament::registerRenderHook(
                    'panels::sidebar.start',
                    fn (): string => view('filament.pos-navigation', [
                        'url' => '/loon/pos-terminal',
                        'active' => request()->routeIs('filament.loon.pages.pos-terminal'),
                    ])->render()
                );
            }
        });
    }
}