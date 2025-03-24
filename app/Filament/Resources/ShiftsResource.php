<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShiftsResource\Pages;
use App\Models\Shifts;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Therapy;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class ShiftsResource extends Resource
{
    protected static ?string $model = Shifts::class;

    protected static ?string $navigationLabel = 'Citas Pendientes';
    protected static ?string $pluralLabel = 'Citas Pendientes';
    protected static ?string $modelLabel = 'Cita';

    protected static ?string $navigationGroup = 'Gestion de Citas';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    //solo citas pendientes
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('status', 'Pendiente');
    }

    //contador en el menu de navegacion
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::query()
        ->where('status', 'Pendiente')
        ->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Selección de paciente
                Select::make('patient_id')
                    ->label('Paciente')
                    ->options(User::where('status', 'paciente')->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                // Selección de terapia
                Select::make('therapy_id')
                    ->label('Terapia')
                    ->options(Therapy::all()->pluck('therapy_type', 'id'))
                    ->searchable()
                    ->reactive()
                    ->required(),

                // Selección de doctor filtrado por terapia
                Select::make('doctor_id')
                    ->label('Doctor')
                    ->options(fn (callable $get) => User::where('user_type', 'doctor')
                      ->whereHas('appointments', fn ($query) => $query->where('therapy_id', $get('therapy_id')))
                      ->pluck('name', 'id'))
                    ->searchable()
                    ->reactive()
                    ->required(),

                //seleccion de fecha para el certificado
                DatePicker::make('date')
                ->label('Fecha de la cita')
                ->default(now())
                ->required(),

                // Selección de día
                Select::make('day')
                ->label('Día Disponible')
                ->options(fn (callable $get) => Appointment::where('doctor_id', $get('doctor_id'))
                    ->where('therapy_id', $get('therapy_id'))
                    ->where('available', 1)
                    ->distinct()
                    ->pluck('day', 'day'))
                ->reactive()
                ->preload()
                ->required()
                ->afterStateHydrated(fn ($set, $record) => $set('day', $record?->appointment?->day)),
            

                // Seleccionar Horario Disponible
                Select::make('appointment_id')
                ->label('Horario Disponible')
                ->options(fn (callable $get) => Appointment::where('doctor_id', $get('doctor_id'))
                    ->where('therapy_id', $get('therapy_id'))
                    ->where('day', $get('day'))
                    ->where('available', 1)
                    ->orderBy('start_time')
                    ->get()
                ->mapWithKeys(fn ($appointment) => [
                $appointment->id => $appointment->start_time . ' - ' . $appointment->end_time ////////////////////
                ]))
                ->searchable()
                ->preload()
                ->required()
                ->reactive(),

                Textarea::make('notes')
                    ->label('Notas')
                    ->nullable(),


                Toggle::make('is_recurring')
                    ->label('Es recurrente')
                    ->default(false),

                Toggle::make('is_emergency')
                    ->label('¿Este cambio es por emergencia?')
                    ->visible(fn ($get) => $get('id') !== null)
                    ->helperText('Si activas esto, solo se cambiará esta cita. Si no, se modificarán todas las futuras citas recurrentes.')
                    ->default(false),
                ]);
           
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('patient.name')
                    ->label('Paciente')
                    ->searchable(),

                TextColumn::make('doctor.name')
                    ->label('Doctor')
                    ->searchable(),

                TextColumn::make('therapy.therapy_type')
                    ->label('Terapia')
                    ->searchable(),

                TextColumn::make('appointment.day')
                    ->label('Día'),

                TextColumn::make('date')
                    ->label('Fecha')
                    ->badge()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make()
                ->button()
                ->label('Editar')
                ->extraAttributes(['class' => 'bg-indigo-600 hover:bg-indigo-700']),

                Action::make('completeShift')
                ->label('Completar')
                ->button()
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->action(function (Shifts $record) { // Asegúrate de usar el modelo correcto
                    $record->update(['status' => 'Completada']);
                        Notification::make()
                        ->success()
                        ->title('Cita completada')
                        ->body("La cita de {$record->patient->name} ha sido marcada como completada.")
                        ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                    ->label('Eliminar'),

                    Tables\Actions\BulkAction::make('completeShifts')
                        ->label('Completar Citas')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($records) {  
                            $total = $records->count();
                            foreach ($records as $record) {
                             $record->update(['status' => 'Completada']);
                            }
                        Notification::make()
                            ->success()
                            ->title('Citas completadas')
                            ->body("$total citas han sido marcadas como completadas.")
                            ->send();
                        }),
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
            'index' => Pages\ListShifts::route('/'),
            'create' => Pages\CreateShifts::route('/create'),
            'edit' => Pages\EditShifts::route('/{record}/edit'),
        ];
    }



}
