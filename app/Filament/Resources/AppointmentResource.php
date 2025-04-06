<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentResource\Pages;
use App\Filament\Resources\AppointmentResource\RelationManagers;
use App\Models\Appointment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Therapy;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Tables\Columns\TextColumn;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $pluralLabel = 'Horarios';
    protected static ?string $modelLabel = 'Horarrio';
    protected static ?string $navigationLabel = 'Horarios';
    
    protected static ?string $navigationGroup = 'Gestion de Recursos';
    protected static ?int $navigationSort = 7;


    public static function form(Form $form): Form
    {
        return $form
        
            ->schema([
                Select::make('therapy_id')
                   ->label('Terapia')
                   ->options(Therapy::pluck('therapy_type', 'id'))
                   ->searchable()
                   ->required(),

                Select::make('doctor_id')
                   ->label('Doctor')
                   ->options(fn (callable $get) => User::where('user_type', 'doctor')
                     ->whereHas('appointments', fn ($query) => $query->where('therapy_id', $get('therapy_id')))
                     ->pluck('name', 'id'))
                   ->searchable()
                   ->reactive()
                   ->required(),

                Select::make('day')
                    ->label('Dia')
                   ->options([
                       'Lunes' => 'Lunes',
                       'Martes' => 'Martes',
                       'Miercoles' => 'Miércoles',
                       'Jueves' => 'Jueves',
                       'Viernes' => 'Viernes',
                       'Pivote' => 'Pivote (Para intercambio de citas)',
                   ])
                   ->required(),

                TimePicker::make('start_time')
                ->label('Hors de inicio')
                   ->required()
                   ->seconds(false), // Opcional, para ocultar los segundos
               
               TimePicker::make('end_time')
                   ->label('Hora de fin')
                   ->required()
                   ->seconds(false),

                Forms\Components\Toggle::make('available')
                    ->default(true)
                    ->hidden(),
            ]);
    }

    

    public static function table(Table $table): Table
    {
        return $table
        ->query(Appointment::query()) // Consulta base

            ->columns([
                Tables\Columns\TextColumn::make('doctor.name')
                    ->label('Doctor')
                    ->formatStateUsing(fn ($record) => $record->doctor->name . ' ' . $record->doctor->last_name)
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('therapy.therapy_type')
                    ->label('Terapia')
                    ->searchable(),

                Tables\Columns\TextColumn::make('day')
                    ->label('Dia')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('start_time')
                    ->label('Hora de inicio')
                    ->searchable(),

                Tables\Columns\TextColumn::make('end_time')
                    ->label('Hora de fin')
                    ->searchable(),

                Tables\Columns\IconColumn::make('available')
                    ->label('Disponible')
                    ->searchable()
                    ->boolean(),
                
                Tables\Columns\IconColumn::make('patiend_id')
                    ->label('Paciente')
                    ->searchable(),
            ])
            ->filters([
            // Filtro para la terapia
            Tables\Filters\SelectFilter::make('therapy_id')
            ->relationship('therapy', 'therapy_type')
            ->label('Filtrar por terapia')
            ->options([
                1 => 'Terapia Física',
                2 => 'Terapia de Lenguaje',  
                3 => 'Hipoterapia',
           ]),
            // Filtro para el día de la semana
            Tables\Filters\SelectFilter::make('day')
            ->label('Filtrar por Día')
            ->options([
                'Lunes' => 'Lunes',
                'Martes' => 'Martes',
                'Miercoles' => 'Miercoles',
                'Jueves' => 'Jueves',
                'Viernes' => 'Viernes',
                ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->button(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make()
                ->label('Eliminar'),

                ExportBulkAction::make()->exports([
                    ExcelExport::make('Exportar datos')->fromTable()
                        ->askForFilename()
                    ])
                    ->color('info')
                    ->label('Exportar Datos'),
                ])
                ->label('Acciones Masivas')
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
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),

        ];
    }
}
