<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Size;
use Filament\Tables;
use Illuminate\Support\Str;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\SizeResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SizeResource\RelationManagers;
use Illuminate\Validation\Rules\Unique;

class SizeResource extends Resource
{
    protected static ?string $model = Size::class;

    protected static ?string $navigationIcon = 'css-size';

    protected static ?string $navigationGroup = 'Master Data';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->reactive()
                    ->unique(callback: function (Unique $query, callable $get) {
                        return $query->whereNull('deleted_at');
                    }, ignoreRecord: true)
                    ->afterStateUpdated(function (string $context, $state, callable $set) {
                        if ($context === 'create') {
                            $set('code', Str::slug($state));
                        }
                    })
                    ->maxLength(30),
                Forms\Components\TextInput::make('code')
                    ->label('Kode')
                    ->required()
                    ->disabled()
                    ->maxLength(30),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code'),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (Tables\Actions\DeleteAction $action, Size $record) {
                        if ($record->productVariants()->exists()) {
                            Notification::make()
                                ->warning()
                                ->title("Tidak dapat menghapus size $record->name!")
                                ->body('Size yang sudah di gunakan oleh produk tidak dapat dihapus.')
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
                        $records->each(function (Size $record) use ($action) {
                            if ($record->productVariants()->exists()) {
                                Notification::make()
                                    ->warning()
                                    ->title("Tidak dapat menghapus size $record->name!")
                                    ->body('Size yang sudah di gunakan oleh produk tidak dapat dihapus. delete masal di batalkan')
                                    ->persistent()
                                    ->duration(5000)
                                    ->send();

                                $action->cancel();
                            }
                        });
                    }),
            ])
            ->defaultSort('name');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSizes::route('/'),
        ];
    }
}
