<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AddressResource\Pages;
use App\Models\Address;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;


class AddressResource extends Resource
{
    protected static ?string $model = Address::class;

    protected static ?string $navigationLabel = 'Direcciones';
    protected static ?string $pluralLabel = 'Direcciones';
    protected static ?string $navigationGroup = 'Gestion de Recursos';
    protected static ?int $navigationSort = 8;
    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('user.name')
                ->label('Usuario')
                ->searchable(),

                Tables\Columns\TextColumn::make('canton.canton')
                ->label('Canton')
                ->searchable(),

                Tables\Columns\TextColumn::make('parroquia.parroquia')
                ->label('Parroquia')
                ->searchable(),
                Tables\Columns\TextColumn::make('site')
                ->label('Lugar')
                ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->button()
                ->label('Editar'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                    ->label('Eliminar')
                ])
            ->label('Acciones Masivas'),
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
            'index' => Pages\ListAddresses::route('/'),
            'create' => Pages\CreateAddress::route('/create'),
            'edit' => Pages\EditAddress::route('/{record}/edit'),
        ];
    }
}
