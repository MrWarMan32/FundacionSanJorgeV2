<?php

namespace App\Filament\Widgets;

use Filament\Forms\Components\Select as ComponentsSelect;
use Filament\Forms\Components\TextInput as ComponentsTextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Forms\TextInput;
use Filament\Forms\Select;
use Illuminate\Foundation\Auth\User;

class Contador extends BaseWidget
{

  use InteractsWithForms;

  
    protected function getStats(): array
    {
      return [
          Stat::make('Nuevos Usuarios (Hoy)', User::whereDate('created_at', now())->count())
              ->description('Usuarios registrados hoy')
              ->descriptionIcon('heroicon-m-user-plus')
              ->chart([1, 5, 2, 8, 3, 10, 4])
              ->color('info'),

          Stat::make('Aspirantes', User::query()
            ->where('status', 'aspirante')
            ->where('user_type', '!=', 'doctor')
            ->where('user_type', '!=', 'admin') 
            ->count()
          ),

          Stat::make('Pacientes', User::query()
            ->where('status', 'paciente')
            ->where('user_type', '!=', 'doctor')
            ->where('user_type', '!=', 'admin')
            ->count()
          ),

          Stat::make('Terapeutas', User::query()
            ->where('user_type', '=', 'doctor')
            ->where('user_type', '!=', 'admin') 
            ->count()
          ),

          Stat::make('Pacientes Masculinos', User::query()
            ->where('status', 'paciente')
            ->where('gender', 'Masculino')
            ->where('user_type', '!=', 'doctor')
            ->where('user_type', '!=', 'admin')
            ->count()
          ),

          Stat::make('Pacientes Femeninos', User::query()
            ->where('status', 'paciente')
            ->where('gender', 'Femenino')
            ->where('user_type', '!=', 'doctor')
            ->where('user_type', '!=', 'admin')
            ->count()
        )

      ];
    }

    public function updateAgeChart()
    {
        $this->refresh(); // Para refrescar el widget después de actualizar el estado
    }
}
