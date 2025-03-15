<?php

namespace App\Filament\Resources\ShiftsResource\Pages;

use App\Filament\Resources\ShiftsResource;
use Filament\Actions;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Artisan;

class ListShifts extends ListRecords
{
    protected static string $resource = ShiftsResource::class;

    protected function getHeaderActions(): array
    {
        return [

            CreateAction::make('generateRecurringShifts')
                ->label('Generar Citas Recurrentes')
                ->color('primary')
                ->action(function () {
                    // Ejecutar el comando de Artisan para generar las citas recurrentes
                    Artisan::call('generate:recurring-shifts');
                    
                    // Mostrar una notificación de éxito
                    $this->notify('success', 'Las citas recurrentes se generaron correctamente.');
                }),

            CreateAction::make()
            ->label('Agendar Cita')
        ];
    }
   
}
