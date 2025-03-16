<?php

namespace App\Filament\Resources\AddressResource\Pages;

use App\Filament\Resources\AddressResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAddress extends EditRecord
{
    protected static string $resource = AddressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->requiresConfirmation()
                ->modalHeading('Eliminar Direccion')
                ->modalDescription('¿Estás seguro de que quieres eliminar esta direccion? Esta acción no se puede deshacer.')
                ->modalSubmitActionLabel('Sí, Eliminar')
                ->modalCancelActionLabel('No, cancelar')
        ];
    }
}
