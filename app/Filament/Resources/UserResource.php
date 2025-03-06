<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
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
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Illuminate\Support\Str;


class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationLabel = 'Usuarios';
    protected static ?string $pluralLabel = 'Usuarios';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    //NO MOSTRAR USUARIOS PACIENTES, DOCTORES  
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
        ->where('status', '!=', 'paciente')
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
        return $form->schema([

            Wizard::make()
            ->columnSpan('full')
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

                    Forms\Components\TextInput::make('ethnicity')
                    ->label('Etnia')
                    ->nullable()
                    ->maxLength(100),
   

                   Forms\Components\Select::make('status')
                   ->label('Estado')
                   ->options([
                       'aspirante' => 'Aspirante',
                       'paciente' => 'Paciente',
                   ])
                   ->default('aspirante')
                   ->required(),

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
                   ->label('Historial Médico')
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
                   Forms\Components\TextInput::make('canton')
                   ->label('Cantón')
                   ->required()
                   ->maxLength(100),

                   Forms\Components\TextInput::make('parish')
                   ->label('Parroquia')
                   ->required(),
                   
                   Forms\Components\TextInput::make('site')    
                   ->label('Sector')
                   ->required()
                   ->maxLength(100),

                   Forms\Components\TextInput::make('street_1')
                   ->label('Calle Principal')
                   ->required()
                   ->maxLength(100),

                   Forms\Components\TextInput::make('street_2')
                   ->label('Calle Secundaria')
                   ->nullable()
                   ->maxLength(100),

                   Forms\Components\TextInput::make('reference')
                   ->label('Referencia')
                   ->nullable()
                   ->maxLength(100),
                ]),

                Step::make('Creacion cuenta de Aspirante (Dejar en blanco la contraseña )')->schema([
                    
                    Forms\Components\TextInput::make('email')
                    ->email()
                    ->label('Correo Electrónico')
                    ->placeholder('nombre.apellido@gmail.com')
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
                Tables\Columns\TextColumn::make('name')
                ->label('Nombres')
                ->searchable(),

                Tables\Columns\TextColumn::make('disability_type')
                ->label('Tipo de Discapacidad')
                ->formatStateUsing(fn ($state) => is_array($state) ? implode(', ', $state) : $state),
                // ->sortable(),

                Tables\Columns\TextColumn::make('disability_level')
                ->label('Nivel'),
                // ->sortable(),

                Tables\Columns\TextColumn::make('disability_grade')
                ->label('Grado'),
                // ->sortable(),

                Tables\Columns\TextColumn::make('phone')
                ->label('Teléfono')
                ->searchable(),

                // Tables\Columns\TextColumn::make('status')
                // ->label('Estado')->badge()->color('success'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('aprobar')
                ->label('Aprobar')
                ->icon('heroicon-o-check')
                ->color('success')
                ->visible(fn($record) => $record->status === 'aspirante') // Solo se muestra si es aspirante
                ->action(fn($record) => $record->update(['status' => 'paciente']))
                ->requiresConfirmation()
                ->successNotificationTitle('El usuario ha sido aprobado como paciente'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}
