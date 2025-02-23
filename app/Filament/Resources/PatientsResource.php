<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PatientsResource\Pages;
use App\Filament\Resources\PatientsResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;


class PatientsResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationLabel = 'Pacientes';
    protected static ?string $pluralLabel = 'Pacientes';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

     // FILTRAR SOLO PACIENTES
     public static function getEloquentQuery(): Builder
     {
         return parent::getEloquentQuery()->where('status', 'paciente');
     }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nombre')->sortable(),
                Tables\Columns\TextColumn::make('last_name')->label('Apellido')->sortable(),
                // Tables\Columns\TextColumn::make('email')->label('Correo')->sortable(),
                Tables\Columns\TextColumn::make('phone')->label('Teléfono')->sortable(),
                Tables\Columns\TextColumn::make('disability')->label('Discapacidad')->sortable(),
                Tables\Columns\TextColumn::make('status')->label('Estado')->badge()->color('success'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('desaprobar')
                ->label('Desaprobar')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->action(fn ($record) => $record->update(['status' => 'aspirante']))
                ->requiresConfirmation()
                ->visible(fn ($record) => $record->status === 'paciente') // Solo si es paciente
                ->successNotificationTitle('El usuario ha sido desaprobado y ahora es aspirante.'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListPatients::route('/'),
            'create' => Pages\CreatePatients::route('/create'),
            'edit' => Pages\EditPatients::route('/{record}/edit'),
        ];
    }
}
