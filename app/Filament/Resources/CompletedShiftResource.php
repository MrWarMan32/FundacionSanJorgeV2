<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompletedShiftResource\Pages;
use App\Models\Shifts;
use App\Models\Therapy;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\Action;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Columns\Column;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class CompletedShiftResource extends Resource
{
    protected static ?string $model = Shifts::class;

    protected static ?string $navigationLabel = 'Citas Completadas';
    protected static ?string $pluralLabel = 'Citas Completadas';
    protected static ?string $modelLabel = 'Cita';

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
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('patient.name')
                    ->label('Paciente')
                    ->disableClick()
                    ->searchable(),

                Tables\Columns\TextColumn::make('doctor.name')
                    ->label('Doctor')
                    ->disableClick()
                    ->searchable(),

                Tables\Columns\TextColumn::make('therapy.therapy_type')
                    ->label('Terapia')
                    ->disableClick()
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('appointment')
                    ->label('Fecha y Hora')
                    ->disableClick()
                    ->formatStateUsing(fn ($record) =>
                        ucfirst($record->appointment->day) . ' - ' .
                        Carbon::parse($record->date)->format('d/m/Y') . ' - ' . 
                        Carbon::parse($record->appointment->start_time)->format('h:i') . ' - ' . 
                        Carbon::parse($record->appointment->end_time)->format('h:i A')
                    )
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('appointment.day')
                    ->label('Dia')
                    ->hidden()
                    ->searchable(),

                Tables\Columns\TextColumn::make('appointment.start_time')
                    ->label('Hora de Inicio')
                    ->hidden()
                    ->searchable(),

                Tables\Columns\TextColumn::make('appointment.end_time')
                    ->label('Hora de Fin')
                    ->hidden()
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('date')
                    ->label('Fecha')
                    ->hidden()
                    ->searchable(),
            ])

            ->filters([
                Tables\Filters\SelectFilter::make('therapy_id')
                    ->label('Filtrar por Terapia')
                    ->options(Therapy::all()->pluck('therapy_type', 'id'))
                    ->searchable(),

                Tables\Filters\Filter::make('date')
                    ->label('Filtrar por Fecha')
                    ->form([
                        DatePicker::make('date')->label('Selecciona una fecha')
                    ])
                    ->query(fn (Builder $query, array $data) => 
                        $query->when($data['date'], fn ($q) => $q->whereDate('date', $data['date']))
                    )
            ])
            ->actions([
                Action::make('downloadPdf')
                ->label('Crear PDF')
                ->button()
                ->extraAttributes(['class' => 'bg-indigo-600 hover:bg-indigo-700'])
                ->requiresConfirmation()
                ->url(fn ($record) => route('certificates.generate', $record->id))
                // ->openUrlInNewTab(),
            ])
            ->bulkActions([
                ExportBulkAction::make()->exports([
                    ExcelExport::make('Exportar datos')->fromTable()
                    ->askForFilename()
                    ->except([
                        'appointment',
                     ])
                ])
                ->label('Exportar Datos'),
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
