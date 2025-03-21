<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DoctorTherapyResource\Pages;
use App\Models\DoctorTherapy;
use App\Models\Therapy;
use App\Models\User;
use Filament\Forms;
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
                ->required(),

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
}
