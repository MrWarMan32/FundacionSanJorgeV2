<?php

namespace App\Filament\Resources\TherapyResource\Pages;

use App\Filament\Resources\TherapyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTherapy extends EditRecord
{
    protected static string $resource = TherapyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->label('Eliminar Terapia')
            ->requiresConfirmation()
                ->modalHeading('Eliminar Terapia')
                ->modalDescription('¿Estás seguro de que quieres eliminar esta terapia? Esta acción no se puede deshacer.')
                ->modalSubmitActionLabel('Sí, Eliminar')
                ->modalCancelActionLabel('No, cancelar')
        ];
    }
}
