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

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Pengaturan Website';

    protected static ?string $slug = 'manage-website';

    protected static ?string $title = 'Pengaturan Website';

    protected ?string $heading = 'Pengaturan Website';

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
                        ])
                        ->columns(2),
                    Forms\Components\Section::make('Pengaturan Logo Website')
                        ->schema([
                            Forms\Components\FileUpload::make('site_logo')
                                ->label('Logo Utama')
                                ->directory('images')
                                ->visibility('public')
                                ->panelAspectRatio('2:1')
                                ->imagePreviewHeight(80)
                                ->image()
                                ->required(),
                            Forms\Components\FileUpload::make('site_logo_mobile')
                                ->label('Logo Mobile')
                                ->directory('images')
                                ->imagePreviewHeight(80)
                                ->panelAspectRatio('2:1')
                                ->visibility('public')
                                ->image()
                                ->required(),
                            Forms\Components\FileUpload::make('site_favicon')
                                ->label('Icon Website')
                                ->directory('images')
                                ->panelAspectRatio('1:1')
                                ->visibility('public')
                                ->image()
                                ->extraAttributes([
                                    'class' => 'bg-white'
                                ])
                                ->required(),
                        ])
                        ->columns(3),
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
                        ->columns(2),
                    Forms\Components\Section::make('Custom Scripts')
                        ->schema([
                            Forms\Components\Textarea::make('widget_home_page')
                                ->label('Widget Home Page'),
                            Forms\Components\Textarea::make('custom_scripts')
                                ->label('Custom Scripts (Analytic / Pixel / Dll)'),
                        ])
                        ->columns(1),
                ])
                ->columnSpan(['lg' => 2]),
        ];
    }
}
