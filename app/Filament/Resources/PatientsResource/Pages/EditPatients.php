<?php

namespace App\Filament\Resources\PatientsResource\Pages;

use App\Filament\Resources\PatientsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPatients extends EditRecord
{
    protected static string $resource = PatientsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->requiresConfirmation()
                ->modalHeading('Eliminar Paciente')
                ->modalDescription('¿Estás seguro de que quieres eliminar este paciente? Esta acción no se puede deshacer.')
                ->modalSubmitActionLabel('Sí, Eliminar')
                ->modalCancelActionLabel('No, cancelar')
        ];
    }
}
