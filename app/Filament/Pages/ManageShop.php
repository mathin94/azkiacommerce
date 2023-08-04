<?php

namespace App\Filament\Pages;

use Filament\Forms;
use App\Settings\ShopSettings;
use Filament\Pages\SettingsPage;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class ManageShop extends SettingsPage
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'bi-shop';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Pengaturan Toko';

    protected static ?string $title = 'Pengaturan Informasi Toko';

    protected static ?string $slug = 'manage-store';

    protected ?string $heading = 'Pengaturan Informasi Toko';

    protected ?string $subheading = 'Informasi ini akan di tampilkan di halaman kontak';

    protected static string $settings = ShopSettings::class;

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Section::make('Informasi Kontak')
                        ->schema([
                            Forms\Components\TextInput::make('phone')
                                ->label('No. Telp')
                                ->required(),
                            Forms\Components\TextInput::make('email')
                                ->label('Alamat Email')
                                ->required(),
                            Forms\Components\Textarea::make('address')
                                ->label('Alamat Toko Pusat')
                                ->required(),
                        ])
                        ->columns(2),
                    Forms\Components\Section::make('Informasi Lokasi Map')
                        ->schema([
                            Forms\Components\TextInput::make('latitude')
                                ->label('Latitude')
                                ->required(),
                            Forms\Components\TextInput::make('longitude')
                                ->label('Longitude')
                                ->required(),
                        ])
                        ->columns(2),
                ])
                ->columnSpan(['lg' => 2]),
        ];
    }
}
