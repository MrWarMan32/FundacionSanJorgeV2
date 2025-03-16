<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->label('Eliminar Aspirante')
            ->requiresConfirmation()
                ->modalHeading('Eliminar Aspirante')
                ->modalDescription('¿Estás seguro de que quieres eliminar este aspirante? Esta acción no se puede deshacer.')
                ->modalSubmitActionLabel('Sí, Eliminar')
                ->modalCancelActionLabel('No, cancelar')
        ];
    }
}
