<?php

namespace App\Filament\Resources\Shop\ProductResource\RelationManagers;

use App\Models\Backoffice\Product as PosProduct;
use App\Services\Product\SyncVariantService;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VariantsRelationManager extends RelationManager
{
    protected static string $relationship = 'variants';

    protected static ?string $recordTitleAttribute = 'name';

    protected static string $view = 'livewire.product.variant-relation';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('barcode')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('color.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('size.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('price')
                    ->alignRight()
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\Action::make('sync-variants')
                    ->label('Sinkron Varian')
                    ->icon('fas-sync-alt')
                    ->button()
                    ->tooltip('Sinkron Data Produk dari data backoffice')
                    ->requiresConfirmation()
                    ->action(function (RelationManager $livewire) {
                        $product = $livewire->ownerRecord;

                        $service = new SyncVariantService($product);

                        $service->perform();
                    })
                // Tables\Actions\Action::make('import')
                //     ->label('Import Produk')
                //     ->icon('fas-download')
                //     ->button()
                //     ->tooltip('Import Data Produk dari data backoffice')
                //     ->action(function () {
                //     })
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
