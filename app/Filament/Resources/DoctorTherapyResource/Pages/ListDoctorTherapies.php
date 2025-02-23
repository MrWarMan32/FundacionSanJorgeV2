<?php

namespace App\Filament\Resources\DoctorTherapyResource\Pages;

use App\Filament\Resources\DoctorTherapyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDoctorTherapies extends ListRecords
{
    protected static string $resource = DoctorTherapyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
