<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Slider;
use App\Enums\SliderType;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SliderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SliderResource\RelationManagers;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;

class SliderResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Slider::class;

    protected static ?string $navigationIcon = 'carbon-carousel-horizontal';

    protected static ?string $navigationGroup = 'Pengaturan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('Detail Slider')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->maxLength(160),
                                Forms\Components\Select::make('slider_type')
                                    ->options(SliderType::asSelectArray())
                                    ->required(),
                                Forms\Components\TextInput::make('link')
                                    ->maxLength(65535),
                                Group::make()
                                    ->schema([
                                        Forms\Components\DateTimePicker::make('active_at')
                                            ->label('Tanggal Tayang')
                                            ->default('now'),
                                        Forms\Components\DateTimePicker::make('deactive_at')
                                            ->after('active_at')
                                            ->helperText('Kosongkan Untuk Aktif Selamanya')
                                            ->label('Tanggal Nonaktif'),
                                    ])
                                    ->columns(2)
                            ])
                    ]),
                Group::make()
                    ->schema([
                        Section::make('Gambar Slider')
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('media')
                                    ->collection('slider-images')
                                    ->directory('sliders')
                                    ->maxSize(2048)
                                    ->multiple(false)
                                    ->disableLabel()
                                    ->required()
                            ]),
                    ])
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('slider-image')
                    ->label('Gambar')
                    ->square()
                    ->width(200)
                    ->height(120)
                    ->collection('slider-images'),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->getStateUsing(fn (Slider $record): string => $record->isActive() ? 'Aktif' : 'Tidak Aktif')
                    ->colors([
                        'success' => 'Aktif',
                    ])
                    ->label('Status'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSliders::route('/'),
            'create' => Pages\CreateSlider::route('/create'),
            'edit' => Pages\EditSlider::route('/{record}/edit'),
        ];
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
        ];
    }
}
