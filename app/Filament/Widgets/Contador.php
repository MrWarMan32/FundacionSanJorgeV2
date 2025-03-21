<?php

namespace App\Filament\Widgets;

use App\Models\Therapy;
use Filament\Forms\Components\Select as ComponentsSelect;
use Filament\Forms\Components\TextInput as ComponentsTextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
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
        ),

        
          // Agregar estadística de pacientes por terapia
          Stat::make('Pacientes por Terapia Fisica', function () {
            $therapyId = 1;
                // Contar los pacientes con citas asignadas a esta terapia
                $count = User::query()
                ->join('shifts', 'users.id', '=', 'shifts.patient_id')
                ->where('shifts.therapy_id', $therapyId)
                ->where('users.status', 'paciente')
                ->distinct()
                ->count('users.id');  // Contamos por pacientes distintos
              return $count;
        }),

        // Agregar estadística de pacientes por terapia
        Stat::make('Pacientes por Terapia de Lenguaje', function () {
          $therapyId = 2;
              // Contar los pacientes con citas asignadas a esta terapia
              $count = User::query()
              ->join('shifts', 'users.id', '=', 'shifts.patient_id')
              ->where('shifts.therapy_id', $therapyId)
              ->where('users.status', 'paciente')
              ->distinct()
              ->count('users.id');  // Contamos por pacientes distintos
            return $count;
      }),

      // Agregar estadística de pacientes por terapia
      Stat::make('Pacientes por Terapia Ocupacional', function () {
        $therapyId = 3;
            // Contar los pacientes con citas asignadas a esta terapia
            $count = User::query()
            ->join('shifts', 'users.id', '=', 'shifts.patient_id')
            ->where('shifts.therapy_id', $therapyId)
            ->where('users.status', 'paciente')
            ->distinct()
            ->count('users.id');  // Contamos por pacientes distintos
          return $count;
    }),



      ];
    }

    public function updateAgeChart()
    {
        $this->refresh(); // Para refrescar el widget después de actualizar el estado
    }

}
