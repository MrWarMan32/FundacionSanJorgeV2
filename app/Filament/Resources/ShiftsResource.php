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
use App\Models\Doctor;
use App\Models\Therapy;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Doctrine\DBAL\Driver\Mysqli\Initializer\Options;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Hidden;

class ShiftsResource extends Resource
{
    protected static ?string $model = Shifts::class;
    protected static ?string $navigationLabel = 'Citas';
    protected static ?string $pluralLabel = 'Citas';
    protected static ?string $navigationGroup = 'Gestion de Citas';
    protected static ?int $navigationSort = 5;
    protected static ?string $navigationIcon = 'heroicon-o-calendar';

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
                    //->afterStateUpdated(fn ($state, callable $set) => $set('doctor_id'))
                    ->searchable()
                    ->reactive()
                    ->required(),


                    // // Selección de doctor filtrado por terapia
                Select::make('doctor_id')
                    ->label('Doctor')
                    ->options(fn (callable $get) => User::where('user_type', 'doctor')
                      ->whereHas('appointments', fn ($query) => $query->where('therapy_id', $get('therapy_id')))
                      ->pluck('name', 'id'))
                    ->searchable()
                    ->reactive()
                    ->required(),

                //     // // Selección de doctor filtrado por terapia
                // Select::make('doctor_id')
                //     ->label('Doctor')
                //     ->options(function (callable $get) {
                //         $therapyId = $get('therapy_id');
                //         if (!$therapyId) {
                //             return [];
                //         }
                //         return Doctor::doctors()->whereHas('therapies', function ($query) use ($therapyId) {
                //             $query->where('therapy_id', $therapyId);
                //         })->pluck('name', 'id');
                //     })
                //     ->reactive()
                //     ->afterStateUpdated(fn ($state, callable $set) => $set('day', null))
                //     ->required(),


                // Selección de día
        Select::make('day')
        ->label('Día')
        ->options(function (callable $get) {
            $doctorId = $get('doctor_id');
            $therapyId = $get('therapy_id');
            if (!$doctorId || !$therapyId) {
                return [];
            }
            return Appointment::where('doctor_id', $doctorId)
                ->where('therapy_id', $therapyId)
                ->pluck('day', 'day')
                ->unique();
        })
        ->reactive()
        ->afterStateUpdated(fn ($state, callable $set) => $set('appointment_id', null))
        ->required(),

                
                // // Selección de terspi filtrado por terapia
                Select::make('appointment_id')
                ->label('Horario')
                ->options(function (callable $get) {
                    $doctorId = $get('doctor_id');
                    $therapyId = $get('therapy_id');
                    $day = $get('day');
                    if (!$doctorId || !$therapyId || !$day) {
                        return [];
                    }
                    return Appointment::where('doctor_id', $doctorId)
                        ->where('therapy_id', $therapyId)
                        ->where('day', $day)
                        ->where('available', 1)
                        ->get()
                        ->mapWithKeys(function ($appointment) {
                            return [$appointment->id => date('H:i', strtotime($appointment->start_time)) . ' - ' . date('H:i', strtotime($appointment->end_time))];
                        });
                })
                ->required(),

        // Hidden::make('therapy_id_for_appointment')
        //     ->default(fn (callable $get) => $get('therapy_id')),




                // //selecion horario
                // Forms\Components\Actions::make([
                //     Action::make('selectAppointment')
                //         ->label('Seleccionar Horario')
                //         ->button()
                //         // ->modalContent(fn ($record) => view('livewire.appointment-selector', ['patientId' => $record->id]))
                //         // ->modalHeading('Seleccionar Horario'),
                //         ->modalContent(function (\Filament\Forms\Components\Component $component) {
                //             $patientId = $component->getLivewire()->form->getState()['patient_id'];
                //             return view('livewire.appointment-selector', ['patientId' => $patientId]);
                //         })
                //         ->modalHeading('Seleccionar Horario'),
                // ]),

                
                // // Selección de horario/////////////////////////////////////////////
                // Select::make('appointment_id')
                // ->label('Horario')
                // ->options(Appointment::where('available', 1)
                // ->get()
                // ->mapWithKeys(function ($appointment) {
                //      return [$appointment->id => date('H:i', strtotime($appointment->start_time)) . ' - ' . date('H:i', strtotime($appointment->end_time))];
                // }))
                // ->required(),


                // // Selección de terapia
                // Select::make('therapy_id')
                //     ->label('Terapia')
                //     ->options(Therapy::all()->pluck('therapy_type', 'id'))
                //     ->searchable()
                //     ->reactive()
                //     ->required(),


                // // // Selección de doctor filtrado por terapia
                // Select::make('doctor_id')
                //     ->label('Doctor')
                //     ->options(fn (callable $get) => User::where('user_type', 'doctor')
                //       ->whereHas('appointments', fn ($query) => $query->where('therapy_id', $get('therapy_id')))
                //       ->pluck('name', 'id'))
                //     ->searchable()
                //     ->reactive()
                //     ->required(),


                // // Selección de día disponible según doctor y terapia
                // Select::make('day')
                //     ->label('Día Disponible')
                //     ->options(function (callable $get) {
                //         // Obtener doctor y terapia seleccionados
                //         $doctor_id = $get('doctor_id');
                //         $therapy_id = $get('therapy_id');
                        
                //         // Consultar los días disponibles según el doctor y terapia seleccionados
                //         return Appointment::where('doctor_id', $doctor_id)
                //             ->where('therapy_id', $therapy_id)
                //             ->where('available', 1)
                //             ->groupBy('day') // Agrupar los resultados por día
                //             ->pluck('day', 'day');
                //     })
                //     ->searchable()
                //     ->reactive()
                //     ->preload()
                //     ->required(),


                // Select::make('appointment_id')
                //     ->label('Horario Disponible')
                //     ->options(function (callable $get) {
                //         $doctorId = $get('doctor_id');
                //         $therapyId = $get('therapy_id');
                //         $day = $get('day');
                //         if (!$doctorId || !$therapyId || !$day) {
                //             return [];
                //         }
                //         return Appointment::where('doctor_id', $doctorId)
                //             ->where('therapy_id', $therapyId)
                //             ->where('day', $day)
                //             ->where('available', true)
                //             ->get()
                //             ->mapWithKeys(function ($appointment) {
                //                 return [$appointment->id => date('H:i', strtotime($appointment->start_time)) . ' - ' . date('H:i', strtotime($appointment->end_time))];
                //             });
                //     })
                //     ->required(),
        
                // Hidden::make('patient_id_for_appointment')
                //     ->default(fn (callable $get) => $get('patient_id')),


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
                Tables\Actions\EditAction::make()
                ->button()
                ->extraAttributes(['class' => 'bg-indigo-600 hover:bg-indigo-700']),

                Tables\Actions\Action::make('downloadPdf')
                ->label('Crear PDF')
                // ->icon('heroicon-o-document-check')
                // ->color('blue')
                ->button()
                ->extraAttributes(['class' => 'bg-indigo-600 hover:bg-indigo-700'])
                ->requiresConfirmation()
                ->url(fn ($record) => route('certificates.generate', $record->id))
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

    public static function create(array $data): Model
    {
       $appointment = Appointment::find($data['appointment_id']);
       $appointment->available = 0;
       $appointment->patient_id = $data['patient_id'];
       $appointment->save();

       return static::getModel()::create($data);
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
