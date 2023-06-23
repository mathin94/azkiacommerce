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
                    Forms\Components\Section::make('Informasi Website')
                        ->schema([
                            Forms\Components\TextInput::make('site_name')
                                ->label('Nama Toko Online (Akan di munculkan di judul website)')
                                ->required(),
                            Forms\Components\TextInput::make('site_slogan')
                                ->label('Slogan (Akan di munculkan di judul website)')
                                ->required(),
                            Forms\Components\Textarea::make('site_description')
                                ->label('Deskripsi Website (Akan di munculkan di footer)')
                                ->required(),
                            Forms\Components\FileUpload::make('site_logo')
                                ->label('Logo Utama')
                                ->directory('images')
                                ->visibility('public')
                                ->required(),
                        ])
                        ->columns(2),
                    Forms\Components\Section::make('Social Media')
                        ->schema([
                            Forms\Components\TextInput::make('facebook_link')
                                ->label('Link Facebook')
                                ->prefixIcon('bi-facebook'),
                            Forms\Components\TextInput::make('instagram_link')
                                ->prefixIcon('bi-instagram')
                                ->label('Link Instagram'),
                            Forms\Components\TextInput::make('whatsapp_link')
                                ->prefixIcon('bi-whatsapp')
                                ->label('Link Whatsapp'),
                            Forms\Components\TextInput::make('tiktok_link')
                                ->prefixIcon('bi-tiktok')
                                ->label('Link TikTok'),
                        ])
                        ->columns(2)
                ])
                ->columnSpan(['lg' => 2]),
        ];
    }
}
