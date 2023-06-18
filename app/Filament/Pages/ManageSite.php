<?php

namespace App\Filament\Pages;

use App\Settings\SiteSettings;
use CoringaWc\FilamentInputLoading\TextInput;
use Filament\Forms;
use Filament\Pages\SettingsPage;

class ManageSite extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $settings = SiteSettings::class;

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Section::make('Pengaturan Website')
                        ->schema([
                            Forms\Components\TextInput::make('site_name')
                                ->label('Nama Toko Online')
                                ->required(),
                            //file
                            Forms\Components\FileUpload::make('site_logo')
                                ->label('Logo Utama')
                                ->directory('images')
                                ->visibility('public')
                                ->required(),
                        ])
                        ->columns(2)
                ])
                ->columnSpan(['lg' => 2]),
        ];
    }
}
