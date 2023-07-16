<?php

namespace App\Filament\Pages;

use App\Settings\ComingSoon;
use Filament\Forms;
use Filament\Pages\SettingsPage;

class ManageComingSoon extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Halaman ComingSoon';

    protected static ?string $title = 'Pengaturan Halaman ComingSoon';

    protected static ?string $slug = 'manage-coming-soon';

    protected ?string $heading = 'Pengaturan Halaman ComingSoon';

    protected static string $settings = ComingSoon::class;

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Card::make()
                ->schema([
                    Forms\Components\TextInput::make('link_title')
                        ->label('Judul di Menu')
                        ->required(),
                    Forms\Components\TextInput::make('link_slug')
                        ->label('Slug Link')
                        ->required(),
                    Forms\Components\TextInput::make('body_title')
                        ->label('Judul di Konten')
                        ->required(),
                    Forms\Components\DateTimePicker::make('launching_date')
                        ->label('Tanggal Launching')
                        ->required(),
                    Forms\Components\TextInput::make('body_content')
                        ->label('Kalimat di Body')
                        ->required(),
                    Forms\Components\FileUpload::make('background_image')
                        ->label('Gambar Background')
                        ->directory('images')
                        ->visibility('public')
                        ->image()
                        ->required(),
                    Forms\Components\Toggle::make('active')
                        ->label('Aktifkan Halaman ComingSoon')
                ])
                ->columnSpan(['lg' => 2]),
        ];
    }
}
