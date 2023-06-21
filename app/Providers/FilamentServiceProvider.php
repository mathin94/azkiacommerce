<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Illuminate\Support\ServiceProvider;
use Filament\Navigation\NavigationGroup;

class FilamentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Filament::serving(function () {
            Filament::registerViteTheme('resources/css/filament.css');

            Filament::registerNavigationGroups([
                NavigationGroup::make()
                    ->label('Shop')
                    ->icon('heroicon-s-shopping-cart'),

                NavigationGroup::make()
                    ->label('Diskon & Voucher')
                    ->icon('carbon-money'),

                NavigationGroup::make()
                    ->label('Blog')
                    ->icon('heroicon-s-pencil'),

                NavigationGroup::make()
                    ->label('Pengaturan Website')
                    ->icon('heroicon-s-cog')
                    ->collapsed(),

                NavigationGroup::make()
                    ->label('System')
                    ->icon('heroicon-o-server')
            ]);
        });
    }
}
