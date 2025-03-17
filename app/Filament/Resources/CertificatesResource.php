<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CertificatesResource\Pages;
use App\Filament\Resources\CertificatesResource\RelationManagers;
use App\Models\Certificates;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CertificatesResource extends Resource
{
    // protected static ?string $model = Certificates::class;
    protected static ?string $model = User::class;
    protected static ?string $navigationLabel = 'Certificados de Asistencia';
    protected static ?string $pluralLabel = 'Certificados';
    protected static ?string $navigationGroup = 'Gestion de Recursos';
    protected static ?int $navigationSort = 6;
    protected static ?string $navigationIcon = 'heroicon-o-document-check';

     //NO MOSTRAR USUARIOS PACIENTES, DOCTORES  
     public static function getEloquentQuery(): Builder
     {
         return parent::getEloquentQuery()
         ->where('status', '!=', 'aspirante')
         ->where('user_type', '!=', 'doctor')
         ->where(function (Builder $query) {
             $query->where('user_type', '!=', 'admin')
                   ->orWhere('status', '!=', 'paciente');
         })
         ->where(function (Builder $query) {
             $query->where('user_type', '!=', 'admin')
                   ->orWhere('status', '!=', 'aspirante');
         });
     }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nombres'),
                Tables\Columns\TextColumn::make('last_name')->label('Apellidos'),
                Tables\Columns\TextColumn::make('phone')->label('Contacto de Representante'),
                Tables\Columns\TextColumn::make('disability_type')->label('Discapacidad'),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('downloadPdf')
                ->label('Crear PDF')
                ->button()
                ->extraAttributes(['class' => 'bg-indigo-600 hover:bg-indigo-700'])
                ->requiresConfirmation()
                ->url(fn ($record) => route('certificates.generate2', $record->id))
                ->openUrlInNewTab(),
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
            'index' => Pages\ListCertificates::route('/'),
            'create' => Pages\CreateCertificates::route('/create'),
            'edit' => Pages\EditCertificates::route('/{record}/edit'),
        ];
    }
}
