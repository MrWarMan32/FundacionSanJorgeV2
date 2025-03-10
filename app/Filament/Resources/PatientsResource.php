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
use Filament\Tables\Actions\ExportBulkAction as ActionsExportBulkAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Illuminate\Support\Collection;

class PatientsResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationLabel = 'Pacientes';
    protected static ?string $pluralLabel = 'Pacientes';
    protected static ?string $navigationGroup = 'Gestion de Usuarios';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationIcon = 'heroicon-o-user';


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
        
        return parent::getEloquentQuery()
        ->whereNotIn('status', ['aspirante'])
        ->whereNotIn('user_type', ['admin'])
        ->whereNotIn('user_type', ['doctor']);
     }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nombres'),
                Tables\Columns\TextColumn::make('last_name')->label('Apellidos'),
                Tables\Columns\TextColumn::make('phone')->label('Contacto de Representante'),
                Tables\Columns\TextColumn::make('disability_type')->label('Discapacidad'),
                // Tables\Columns\TextColumn::make('status')->label('Estado')->badge()->color('success'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->label('Editar'),

                Action::make('desaprobar')
                ->label('Desaprobar')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->action(fn ($record) => $record->update(['status' => 'aspirante']))
                ->requiresConfirmation()
                ->visible(fn ($record) => $record->status === 'paciente')
                ->successNotificationTitle('El usuario ha sido desaprobado y ahora es aspirante.'),
            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                    ->label('Eliminar'),

                    ExportBulkAction::make()->exports([
                        ExcelExport::make('Exportrar datos completos')->fromForm()
                           ->askForFilename()
                           ->except([
                              'created_at', 'updated_at',
                           ])
                           ->withColumns([
                            Column::make('name')->heading('Nombres'),
                            Column::make('last_name')->heading('Apellidos'),
                            Column::make('id_card')->heading('Cedula'),
                            Column::make('gender')->heading('Genero'),
                            Column::make('birth_date')->heading('Fecha de Nacimiento'),
                            Column::make('age')->heading('Edad'),
                            Column::make('ethnicity')->heading('Etnia'),
                            Column::make('id_card_status')->heading('Carnet de Discapacidad'),
                            Column::make('disability_type')->heading('Tipo de Discapacidad'),
                            Column::make('disability_grade')->heading('Grado de Discapacidad'),
                            Column::make('disability_level')->heading('Nivel de Discapacidad'),
                            Column::make('diagnosis')->heading('Diagnostico'),
                            Column::make('medical_history')->heading('Causa de Discapacidad'),
                            Column::make('therapy_id')->heading('Terapia que Recibe'),
                            Column::make('representative_name')->heading('Nombres de Representante'),
                            Column::make('representative_last_name')->heading('Apellidos de Representante'),
                            Column::make('representative_id_card')->heading('Cedula de Representante'),
                            Column::make('phone')->heading('Celular de Representante'),
                            Column::make('id_address')->heading('Direccion'),
                        ]),
                        
                    ExcelExport::make('Exportar datos relevantes')->fromTable(),
                    ])
                    ->label('Exportar Datos'),
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
            'index' => Pages\ListPatients::route('/'),
            'create' => Pages\CreatePatients::route('/create'),
            'edit' => Pages\EditPatients::route('/{record}/edit'),
        ];
    }
}
