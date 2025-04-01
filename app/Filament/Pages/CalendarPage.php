<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class CalendarPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Calendario de Citas';

    protected static string $view = 'filament.pages.calendar-page';
    
}
