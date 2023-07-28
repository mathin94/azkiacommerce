<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FAQResource\Pages;
use App\Filament\Resources\FAQResource\RelationManagers;
use App\Models\FAQ;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Validation\Rules\Unique;

class FAQResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = FAQ::class;

    protected static ?string $modelLabel = 'FAQ';

    protected static ?string $slug = 'faqs';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?string $navigationIcon = 'bi-question-circle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('index')
                    ->label('Urutan')
                    ->numeric()
                    ->unique(callback: function (Unique $rule) {
                        return $rule->where('deleted_at', null);
                    }, ignoreRecord: true)
                    ->required(),
                Forms\Components\TextInput::make('question')
                    ->label('Pertanyaan')
                    ->required(),
                Forms\Components\Textarea::make('answer')
                    ->label('Jawaban')
                    ->required()
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('Urutan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('question')
                    ->label('Pertanyaan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('answer')
                    ->label('Jawaban')
                    ->getStateUsing(fn (FAQ $record) => \Str::of($record->answer)->limit(50))
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageFAQS::route('/'),
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
