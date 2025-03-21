<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TherapyResource\Pages;
use App\Models\Therapy;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TherapyResource extends Resource
{
    protected static ?string $model = Therapy::class;

    protected static ?string $navigationLabel = 'Terapias';
    protected static ?string $pluralLabel = 'Terapias';
    protected static ?string $navigationGroup = 'Gestion de Recursos';
    protected static ?int $navigationSort = 9;
    protected static ?string $navigationIcon = 'heroicon-o-heart';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('therapy_type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->nullable()
                    ->maxLength(255),
                Forms\Components\TextInput::make('duration')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('therapy_type')
                    ->label('Terapia')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Descripcion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('duration')
                    ->label('Duracion')  
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->label('Editar')
                ->button(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                    ->label('Eliminar'),
                ])
                ->button('Acciones Masivas'),
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
            'index' => Pages\ListTherapies::route('/'),
            'create' => Pages\CreateTherapy::route('/create'),
            'edit' => Pages\EditTherapy::route('/{record}/edit'),
        ];
    }
}
