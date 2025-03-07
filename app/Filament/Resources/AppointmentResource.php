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

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'Horarios';
    protected static ?string $navigationGroup = 'Gestion Recursos';
    protected static ?int $navigationSort = 7;
    protected static ?string $pluralLabel = 'Horarios';


    public static function form(Form $form): Form
    {
        return $form
        
            ->schema([
                Forms\Components\TextInput::make('doctor_id')
                    ->label('Doctor')
                    ->options(User::where('user_type', 'doctor')->pluck(DB::raw("CONCAT(name, ' ', last_name)"), 'id'))
                    ->searchable()
                    ->required(),
                    // ->numeric(),
                Forms\Components\TextInput::make('therapy_id')
                   ->label('Terapia')
                   ->options(Therapy::pluck('therapy_type', 'id'))
                   ->searchable()
                   ->required(),
                Forms\Components\TextInput::make('day')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('start_time')
                    ->required(),
                Forms\Components\TextInput::make('end_time')
                    ->required(),
                Forms\Components\Toggle::make('available')
                    ->required(),
            ]);
    }

    

    public static function table(Table $table): Table
    {
        return $table
        ->query(Appointment::query()) // Consulta base

            ->columns([
                Tables\Columns\TextColumn::make('doctor.name')
                    ->label('Doctor')
                    ->sortable()
                    ->formatStateUsing(fn ($record) => $record->doctor->name . ' ' . $record->doctor->last_name)
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('therapy.therapy_type')
                    ->label('Terapia')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('day')
                    ->label('Dia')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->label('Hora de inicio')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('end_time')
                    ->label('Hora de fin')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\IconColumn::make('available')
                    ->label('Disponible')
                    ->sortable()
                    ->searchable()
                    ->boolean(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }
}
