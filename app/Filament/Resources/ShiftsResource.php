<?php

namespace App\Filament\Resources;

use Closure;
use Filament\Forms\Get;
use App\Filament\Resources\ShiftsResource\Pages;
use App\Models\Shifts;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Therapy;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Filament\Actions\Action;



class ShiftsResource extends Resource
{
    protected static ?string $model = Shifts::class;
    protected static ?string $navigationLabel = 'Citas';
    protected static ?string $pluralLabel = 'Citas';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Selección de paciente
                Select::make('patient_id')
                    ->label('Paciente')
                    ->options(User::where('status', 'paciente')->pluck('name', 'id'))
                    ->required()
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


                // Selección de día disponible según doctor y terapia
                Select::make('day')
                    ->label('Día Disponible')
                    ->options(function (callable $get) {
                        // Obtener doctor y terapia seleccionados
                        $doctor_id = $get('doctor_id');
                        $therapy_id = $get('therapy_id');
                        
                        // Consultar los días disponibles según el doctor y terapia seleccionados
                        return Appointment::where('doctor_id', $doctor_id)
                            ->where('therapy_id', $therapy_id)
                            ->where('available', 1)
                            ->groupBy('day') // Agrupar los resultados por día
                            ->pluck('day', 'day');
                    })
                    ->searchable()
                    ->reactive()
                    ->preload()
                    ->required(),


                Select::make('appointment_id')
                    ->label('Horario Disponible')
                    ->options(fn (callable $get) => Appointment::where('doctor_id', $get('doctor_id'))
                       ->where('therapy_id', $get('therapy_id'))
                       ->where('day', $get('day'))
                       ->where('available', 1)
                       ->orderBy('start_time')
                       ->get()
                       ->mapWithKeys(fn ($appointment) => [
                           $appointment->id => $appointment->start_time . ' - ' . $appointment->end_time
                       ]))
                    ->reactive()
                    ->searchable()
                    ->preload()
                    ->required(),


                Textarea::make('notes')
                    ->label('Notas')
                    ->nullable(),


                Toggle::make('is_recurring')
                    ->label('Es recurrente')
                    ->default(false),

                Toggle::make('is_emergency')
                    ->label('¿Este cambio es por emergencia?')
                    ->helperText('Si activas esto, solo se cambiará esta cita. Si no, se modificarán todas las futuras citas recurrentes.')
                    ->default(false),
            ]);
    }

   

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('patient.name')
                    ->label('Paciente')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('doctor.name')
                    ->label('Doctor')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('therapy.therapy_type')
                    ->label('Terapia')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('start_time')
                    ->label('Inicio de la cita')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('end_time')
                    ->label('Fin de la cita')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

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

    // // Hook para actualizar la tabla `appointments` al guardar la cita
    // protected static function afterCreate(Model $record): void
    // {
    //     $appointment = Appointment::find($record->appointment_id);
    //        if ($appointment) {
    //        $appointment->available = 0;
    //        $appointment->patient_id = $record->patient_id;
    //        $appointment->save();
    //        Log::info("Cita asignada a paciente ID: " . $record->patient_id);
    //    }
    // }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShifts::route('/'),
            'create' => Pages\CreateShifts::route('/create'),
            'edit' => Pages\EditShifts::route('/{record}/edit'),
        ];
    }


    // public static function create(array $data): Model
    // {
    //     $appointment = Appointment::find($data['appointment_id']);

    //     $shift = static::getModel()::create([
    //         'patient_id' => $data['patient_id'],
    //         'doctor_id' => $data['doctor_id'],
    //         'therapy_id' => $data['therapy_id'],
    //         'start_time' => $appointment->start_time,
    //         'end_time' => $appointment->end_time,
    //         'is_recurring' => $data['is_recurring'],
    //         'notes' => $data['notes'],
    //         'appointment_id' => $data['appointment_id'],
    //         'is_modified' => $data['is_emergency'],
    //     ]);

    //     $appointment->update([
    //         'available' => 0,
    //         'patient_id' => $data['patient_id'],
    //     ]);

    //     return $shift;
    // }


}
