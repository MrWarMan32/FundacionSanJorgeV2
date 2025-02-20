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


class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationLabel = 'Usuarios';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),

                // Forms\Components\DateTimePicker::make('email_verified_at'),

                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required()
                    ->maxLength(255),
                
                    Forms\Components\TextInput::make('last_name')
                    ->nullable()
                    ->maxLength(100),
    
                    Forms\Components\TextInput::make('id_card')
                    ->nullable()
                    ->unique()
                    ->maxLength(20),
    
                    Forms\Components\Select::make('gender')
                    ->options([
                        'Masculino' => 'Masculino',
                        'Femenino' => 'Femenino',
                        'Otro' => 'Otro',
                    ])
                    ->required(),
    
                    Forms\Components\DatePicker::make('birth_date')
                    ->nullable(),
    
                    Forms\Components\TextInput::make('age')
                    ->nullable()
                    ->numeric(),
    
                    Forms\Components\TextInput::make('ethnicity')
                    ->nullable()
                    ->maxLength(100),
    
                    Forms\Components\TextInput::make('phone')
                    ->nullable()
                    ->maxLength(20),
    
                    Forms\Components\Select::make('user_type')
                    ->options([
                        'admin' => 'admin',
                        'doctor' => 'doctor',
                        'paciente' => 'paciente',
                        'usuario' => 'usuario',
                    ])
                    ->default('usuario')
                    ->required(),
    
                    Forms\Components\Select::make('status')
                    ->options([
                        'aspirante' => 'aspirante',
                        'paciente' => 'paciente',
                    ])
                    ->default('aspirante')
                    ->required(),
    
                    Forms\Components\TextArea::make('disability')
                    ->nullable(),
    
                    Forms\Components\Toggle::make('id_card_status')
                    ->default(false)
                    ->label('Posee Carnet de Discapacidad'),
    
                    Forms\Components\TextInput::make('disability_grade')
                    ->nullable()
                    ->numeric(),
    
                    Forms\Components\TextArea::make('diagnosis')
                    ->nullable(),
    
                    Forms\Components\TextArea::make('medical_history')
                    ->nullable(),
    
                // Relación con las tablas address y therapy
                Forms\Components\Select::make('address_id')
                    ->relationship('address', 'address') // Cambia 'street' por el campo relevante de la tabla 'addresses'
                    ->nullable(),
    
                Forms\Components\Select::make('therapy_id')
                    ->relationship('therapy', 'therapy_type') // Cambia 'name' por el campo relevante de la tabla 'therapies'
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('email_verified_at')
                //     ->dateTime()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('created_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('updated_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
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
                DeleteAction::make(),
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
}
