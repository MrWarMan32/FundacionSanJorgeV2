<?php

namespace App\Filament\Resources\ShiftsResource\Pages;

use App\Filament\Resources\ShiftsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Shifts;

class ListShifts extends ListRecords
{
    protected static string $resource = ShiftsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Agendar Cita'),
        ];
    }

   
}
