<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PatientsResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\Action;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use App\Models\Address;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PatientsResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationLabel = 'Pacientes';
    protected static ?string $pluralLabel = 'Pacientes';
    protected static ?string $navigationGroup = 'Gestion de Usuarios';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationIcon = 'heroicon-o-user';

    // FILTRAR SOLO PACIENTES
    public static function getEloquentQuery(): Builder
    {
       
       return parent::getEloquentQuery()
       ->whereNotIn('status', ['aspirante'])
       ->whereNotIn('user_type', ['admin'])
       ->whereNotIn('user_type', ['doctor']);
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make()
            ->columnSpan('full')
            ->columns(2)
            ->steps([
                Step::make('Información del Aspirante')->schema([
                    Forms\Components\TextInput::make('name')
                    ->label('Nombres')
                    ->required()
                    ->maxLength(100),

                    Forms\Components\TextInput::make('last_name')
                    ->label('Apellidos')
                    ->required(),

                    Forms\Components\TextInput::make('id_card')
                    ->label('Cédula')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(20),

                    Forms\Components\Select::make('gender')
                    ->label('Género')
                    ->options([
                        'Masculino' => 'Masculino',
                        'Femenino' => 'Femenino',
                        'Otro' => 'Otro',
                    ])
                    ->required(),

                    Forms\Components\DatePicker::make('birth_date')
                    ->label('Fecha de Nacimiento')
                    ->nullable(),

                    Forms\Components\TextInput::make('age')
                    ->label('Edad')
                    ->nullable()
                    ->numeric(),

                    Forms\Components\Select::make('ethnicity')
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
   

                   Forms\Components\Select::make('status')
                   ->label('Estado')
                   ->options([
                       'aspirante' => 'Aspirante',
                       'paciente' => 'Paciente',
                   ])
                   ->default('paciente')
                   ->required()
                   ->hidden(),

                   Forms\Components\Select::make('disability_type')
                   ->label('Tipo de Discapacidad')
                   ->options([
                    'Fisica' => 'Física',
                    'Intelectual' => 'Intelectual',
                    'Sensorial' => 'Sensorial',
                    'Psicosocial' => 'Psicosocial',
                    'Visceral' => 'Visceral',
                    'Otra' => 'Otra',
                    ])
                    ->multiple()
                    ->native(false)
                    ->required(),

                   Forms\Components\Select::make('disability_level')
                   ->label('Nivel de Discapacidad')
                   ->options([
                        'En Proceso' => 'En Proceso',
                        'Leve' => 'Leve',
                        'Moderado' => 'Moderado',
                        'Grave' => 'Grave',
                        'Muy Grave' => 'Muy Grave',
                    ])
                    ->required(),

                    Forms\Components\TextInput::make('disability_grade')
                   ->label('Grado de Discapacidad')
                   ->nullable()
                   ->numeric(),

                   Forms\Components\Toggle::make('id_card_status')
                   ->label('Posee Carnet de Discapacidad')
                   ->default(false),

                   Forms\Components\TextArea::make('diagnosis')
                   ->label('Diagnóstico')
                   ->nullable(),

                   Forms\Components\TextArea::make('medical_history')
                   ->label('Causa de Discapacidad')
                   ->nullable(),
                ]),

                Step::make('Informacion Representante')->schema([
                    Forms\Components\TextInput::make('representative_name')
                    ->label('Nombre')
                    ->nullable()
                    ->maxLength(100),

                    Forms\Components\TextInput::make('representative_last_name')
                    ->label('Apellido')
                    ->nullable()
                    ->maxLength(100),

                    Forms\Components\TextInput::make('representative_id_card')
                    ->label('Cédula')
                    ->nullable()
                    ->maxLength(20),

                    Forms\Components\TextInput::make('phone')
                    ->label('Teléfono')
                    ->nullable()
                    ->maxLength(20),

                 ]),

                Step::make('Dirección del Aspirante')->schema([

                    Select::make('id_address')
                    ->label('Direccion')
                    ->options(Address::all()->mapWithKeys(function ($address) {
                        $parroquia = $address->parroquia ? $address->parroquia->parroquia : 'Parroquia no definida';
                        $street_1 = $address->street_1 ?? 'Calle principal no definida';
                        return [$address->id => $parroquia . ' - ' . $street_1];
                    }))
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        Select::make('id_provincia')
                        ->label('Provincia')
                        ->options(DB::table('provincia')->pluck('provincia', 'id'))
                        ->reactive()
                        ->afterStateUpdated(fn ($set) => $set('id_canton', null)),


                        Select::make('id_canton')
                        ->label('Cantón')
                        ->options(function (callable $get) {
                            if (!$get('id_provincia')) {
                                return [];
                            }
                            return DB::table('canton')->where('id_provincia', $get('id_provincia'))->pluck('canton', 'id');
                        })
                        ->reactive()
                        ->afterStateUpdated(fn ($set) => $set('id_parroquia', null)),

                        Select::make('id_parroquia')
                        ->label('Parroquia')
                        ->options(function (callable $get) {
                            if (!$get('id_canton')) {
                                return [];
                            }
                            return DB::table('parroquia')->where('id_canton', $get('id_canton'))->pluck('parroquia', 'id');
                        })
                        ->reactive(),

                        TextInput::make('site')
                        ->label('Lugar')
                        ->required()
                        ->maxLength(100),

                        TextInput::make('street_1')
                        ->label('Calle Principal')
                        ->required()
                        ->maxLength(100),

                        TextInput::make('street_2')
                        ->label('Calle Secundaria')
                        ->nullable()
                        ->maxLength(100),

                        Textarea::make('reference')
                        ->label('Referencia')
                        ->nullable()
                        ->maxLength(255),
                    ])
                    ->createOptionUsing(function (array $data): int {
                        $address = Address::create([
                            'id_provincia' => $data['id_provincia'],
                            'id_canton' => $data['id_canton'],
                            'id_parroquia' => $data['id_parroquia'],
                            'street_1' => $data['street_1'],
                            'street_2' => $data['street_2'],
                            'reference' => $data['reference'],
                        ]);
            
                        return $address->id;
                    })
                    ->required(),
                ]),

                Step::make('Creacion cuenta de Aspirante (Dejar en blanco la contraseña )')->schema([
                    Forms\Components\TextInput::make('email')
                    ->email()
                    ->label('Correo Electrónico')
                    ->placeholder('nombre.apellido1234@gmail.com')
                    ->helperText('Se recomienda utilizar este formato en caso de no poseer correo')
                    ->required()
                    ->maxLength(100)
                    ->unique(ignoreRecord: true),

                    Forms\Components\TextInput::make('password')
                    ->password()
                    ->label('Contraseña')
                    ->nullable()
                    ->dehydrated(fn ($state) => filled($state)) // Guarda solo si se cambia
                    ->maxLength(255),

                    Forms\Components\TextInput::make('email_verified_at')
                    ->label('Correo Verificado')
                    ->default(now())
                    ->hidden(),

                    Forms\Components\TextInput::make('remember_token')
                    ->label('Token de Recuerdo')
                    ->default(fn () => Str::random(60))
                    ->hidden(),

                    Forms\Components\TextInput::make('created_at')
                    ->label('Creado en')
                    ->default(now())
                    ->readonly()
                    ->hidden(),

                    Forms\Components\TextInput::make('updated_at')
                    ->label('Actualizado en')
                    ->default(now())
                    ->readonly()
                    ->hidden(),

 
                 ])
            ]),
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
                        
                        ExcelExport::make('Exportar datos relevantes')->fromTable()
                        ->askForFilename()
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
