<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use EightyNine\ExcelImport\ExcelImportAction;
use Filament\Notifications\Notification;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Nuevo Aspirante')
            ->successNotification(
                Notification::make()
                    ->success()
                    ->title('Aspirante creado')
                    ->body('El nuevo aspirante ha sido registrado exitosamente.')
                    ->persistent()
                    ->send()
            ),
        ];
    }
}
