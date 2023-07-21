<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Color;
use Illuminate\Support\Str;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Validation\Rules\Unique;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\ColorResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ColorResource\RelationManagers;

class ColorResource extends Resource
{
    protected static ?string $model = Color::class;

    protected static ?string $navigationIcon = 'carbon-color-palette';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $modelLabel = 'Warna';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Nama Warna')
                    ->unique(callback: function (Unique $query, callable $get) {
                        return $query->whereNull('deleted_at');
                    }, ignoreRecord: true)
                    ->reactive()
                    ->lazy()
                    ->afterStateUpdated(function (string $context, $state, callable $set) {
                        if ($context === 'create') {
                            $set('code', Str::slug($state));
                        }
                    })
                    ->maxLength(30),
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->label('Kode Warna')
                    ->disabled()
                    ->maxLength(30),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (Tables\Actions\DeleteAction $action, Color $record) {
                        if ($record->productVariants()->exists()) {
                            Notification::make()
                                ->warning()
                                ->title("Tidak dapat menghapus warna $record->name!")
                                ->body('Warna yang sudah di gunakan oleh produk tidak dapat dihapus.')
                                ->persistent()
                                ->duration(5000)
                                ->send();

                            $action->cancel();
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->before(function (Tables\Actions\DeleteBulkAction $action, Collection $records) {
                        $records->each(function (Color $record) use ($action) {
                            if ($record->productVariants()->exists()) {
                                Notification::make()
                                    ->warning()
                                    ->title("Tidak dapat menghapus warna $record->name!")
                                    ->body('Warna yang sudah di gunakan oleh produk tidak dapat dihapus. delete masal di batalkan')
                                    ->persistent()
                                    ->duration(5000)
                                    ->send();

                                $action->cancel();
                            }
                        });
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageColors::route('/'),
        ];
    }
}
