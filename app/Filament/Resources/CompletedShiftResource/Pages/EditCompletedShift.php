<?php

namespace App\Filament\Resources\CompletedShiftResource\Pages;

use App\Filament\Resources\CompletedShiftResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCompletedShift extends EditRecord
{
    protected static string $resource = CompletedShiftResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
