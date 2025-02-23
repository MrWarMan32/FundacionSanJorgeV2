<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DoctorTherapyResource\Pages;
use App\Filament\Resources\DoctorTherapyResource\RelationManagers;
use App\Models\DoctorTherapy;
use App\Models\Therapy;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;

class DoctorTherapyResource extends Resource  
{
    protected static ?string $model = DoctorTherapy::class;
    protected static ?string $navigationLabel = 'Terapeutas';
    protected static ?string $pluralLabel = 'Terapeutas';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('doctor_id')
                ->label('Doctor')
                ->options(User::where('user_type', 'doctor')->pluck('name', 'id'))
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
                ->sortable()
                ->formatStateUsing(fn ($record) => $record->doctor->name . ' ' . $record->doctor->last_name)
                ->searchable(),

                Tables\Columns\TextColumn::make('doctor.phone')
                ->label('Telefono')
                ->sortable()
                ->searchable(),

                Tables\Columns\TextColumn::make('therapy.therapy_type')
                ->label('Terapia que imparte')
                ->sortable()
                ->searchable(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDoctorTherapies::route('/'),
            'create' => Pages\CreateDoctorTherapy::route('/create'),
            'edit' => Pages\EditDoctorTherapy::route('/{record}/edit'),
        ];
    }
}
