<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompletedShiftResource\Pages;
use App\Models\Shifts;
use Filament\Forms;
use Filament\Tables\Actions\Action;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CompletedShiftResource extends Resource
{
    protected static ?string $model = Shifts::class;

    protected static ?string $navigationLabel = 'Citas Completadas';
    protected static ?string $pluralLabel = 'Citas Completadas';

    protected static ?string $navigationGroup = 'Gestion de Citas';
    protected static ?int $navigationSort = 5;
    
    protected static ?string $navigationIcon = 'heroicon-o-folder';

    //solo citas completas
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('status', 'Completada');
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
                Tables\Columns\TextColumn::make('patient.name')
                    ->label('Paciente')
                    ->searchable(),

                Tables\Columns\TextColumn::make('doctor.name')
                    ->label('Doctor')
                    ->searchable(),

                Tables\Columns\TextColumn::make('therapy.therapy_type')
                    ->label('Terapia')
                    ->searchable(),

                Tables\Columns\TextColumn::make('date')
                    ->label('Fecha')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('downloadPdf')
                ->label('Crear PDF')
                ->button()
                ->extraAttributes(['class' => 'bg-indigo-600 hover:bg-indigo-700'])
                ->requiresConfirmation()
                ->url(fn ($record) => route('certificates.generate', $record->id))
                ->openUrlInNewTab(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
            'index' => Pages\ListCompletedShifts::route('/'),
            'create' => Pages\CreateCompletedShift::route('/create'),
            'edit' => Pages\EditCompletedShift::route('/{record}/edit'),
        ];
    }
}
