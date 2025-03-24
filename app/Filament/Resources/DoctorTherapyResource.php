<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DoctorTherapyResource\Pages;
use App\Models\DoctorTherapy;
use App\Models\Therapy;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class DoctorTherapyResource extends Resource  
{
    protected static ?string $model = DoctorTherapy::class;
    protected static ?string $navigationLabel = 'Terapeutas';
    protected static ?string $pluralLabel = 'Terapeutas';
    protected static ?string $modelLabel = 'Terapeuta';

    protected static ?string $navigationGroup = 'Gestion de Usuarios';

    protected static ?int $navigationSort = 4;
    protected static ?string $navigationIcon = 'heroicon-o-identification';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('doctor_id')
                ->label('Doctor')
                ->options(User::where('user_type', 'doctor')->pluck(DB::raw("CONCAT(name, ' ', last_name)"), 'id'))
                ->searchable()
                ->required()
                ->createOptionForm([
                    TextInput::make('name')
                        ->label('Nombre')
                        ->required(),
            
                    TextInput::make('last_name')
                        ->label('Apellido')
                        ->required(),
            
                    TextInput::make('id_card')
                        ->label('Cédula')
                        ->required(),
                    
                    TextInput::make('phone')
                        ->label('Teléfono')
                        ->nullable()
                        ->regex('/^[0-9]+$/')
                        ->minLength(10)
                        ->maxLength(10),

                    Select::make('gender')
                        ->label('Género')
                        ->options([
                            'Masculino' => 'Masculino',
                            'Femenino' => 'Femenino',
                            'Otro' => 'Otro',
                        ])
                        ->required(),
                    
                    DatePicker::make('birth_date')
                        ->label('Fecha de Nacimiento')
                        ->nullable()
                        ->afterStateUpdated(function ($state, callable $set) {
                            if ($state) {
                                $birthDate = Carbon::parse($state);
                                $age = floor($birthDate->diffInYears(Carbon::now())); // Redondear hacia abajo
    
                                $set('age', $age);
                            } else {
                                $set('age', null);
                            }
                        }),
                    
                    TextInput::make('age')
                        ->label('Edad')
                        ->nullable()
                        ->numeric(),
            
                    Select::make('ethnicity')
                        ->label('Etnia')
                        ->options([
                            'mestizo' => 'Mestizo/a',
                            'indigena' => 'Indígena',
                            'afroecuatoriano' => 'Afroecuatoriano/a',
                            'blanco' => 'Blanco/a',
                            'montubio' => 'Montubio/a',
                            'otro' => 'Otro',
                        ])
                        ->nullable(),
                    
                    Hidden::make('user_type')
                        ->default('doctor'),
                ])
                ->createOptionUsing(function (array $data) {
                    $doctor = User::create([
                        'name' => $data['name'],
                        'last_name' => $data['last_name'],
                        'id_card' => $data['id_card'],
                        'phone' => $data['phone'],
                        'gender' => $data['gender'],
                        'birth_date' => $data['birth_date'],
                        'age' => $data['age'],
                        'ethnicity' => $data['ethnicity'],
                        'user_type' => 'doctor',
                    ]);
            
                    return $doctor->id;
                }),

            Forms\Components\Select::make('therapy_id')
                ->label('Terapia')
                ->options(Therapy::pluck('therapy_type', 'id'))
                ->searchable()
                ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('doctor.name')
                ->label('Nombres')
                ->formatStateUsing(fn ($record) => $record->doctor->name . ' ' . $record->doctor->last_name)
                ->searchable(),

                Tables\Columns\TextColumn::make('doctor.phone')
                ->label('Telefono')
                ->searchable(),

                Tables\Columns\TextColumn::make('therapy.therapy_type')
                ->label('Terapia que imparte')
                ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->button(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                    ->label('Eliminar '),
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
            'index' => Pages\ListDoctorTherapies::route('/'),
            'create' => Pages\CreateDoctorTherapy::route('/create'),
            'edit' => Pages\EditDoctorTherapy::route('/{record}/edit'),
        ];
    }

    public static function getRedirectUrl(): ?string
    {
        return static::getUrl('index'); // Redirige a la lista después de crear
    }

}
