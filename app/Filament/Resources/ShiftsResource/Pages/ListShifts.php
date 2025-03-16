<?php

namespace App\Filament\Resources\ShiftsResource\Pages;

use App\Filament\Resources\ShiftsResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Artisan;

class ListShifts extends ListRecords
{
    protected static string $resource = ShiftsResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Action::make('generateRecurringShifts')
                ->label('Generar Citas Recurrentes')
                ->color('primary')
                ->requiresConfirmation()
                ->modalHeading('Confirmar generación de citas')
                ->modalDescription('¿Estás seguro de que quieres generar las nuevas citas? Esta acción no se puede deshacer.')
                ->modalSubmitActionLabel('Sí, generar')
                ->modalCancelActionLabel('No, cancelar')
                ->action(function () {
                    // Ejecutar el comando de Artisan para generar las citas recurrentes
                    Artisan::call('generate:recurring-shifts');
                    
                    Notification::make()
                        ->title('Citas Generadas')
                        ->body('Las nuevas citas se generaron correctamente.')  
                        ->success()
                        ->persistent()
                        ->send();
                }),

            CreateAction::make()
            ->label('Agendar Cita')
        ];
    }
   
}
