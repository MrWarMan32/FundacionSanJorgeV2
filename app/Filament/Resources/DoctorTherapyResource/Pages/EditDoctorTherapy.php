<?php

namespace App\Filament\Resources\DoctorTherapyResource\Pages;

use App\Filament\Resources\DoctorTherapyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDoctorTherapy extends EditRecord
{
    protected static string $resource = DoctorTherapyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->requiresConfirmation()
                ->modalHeading('Eliminar Terapeuta')
                ->modalDescription('¿Estás seguro de que quieres eliminar este terapeuta? Esta acción no se puede deshacer.')
                ->modalSubmitActionLabel('Sí, Eliminar')
                ->modalCancelActionLabel('No, cancelar')
        ];
    }
}
