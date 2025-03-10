<div>

    <x-filament::modal id="appointment-selector-modal" width="md">
        <x-slot name="heading">Seleccionar Horario</x-slot>

        <div class="space-y-4">
            <select wire:model="therapyId">
                <option value="">Seleccionar Terapia</option>
                @foreach ($therapies as $therapy) {{-- Verifica que el nombre de la variable sea $therapies --}}
                    <option value="{{ $therapy->id }}">{{ $therapy->name }}</option>
                @endforeach
            </select>

            <select wire:model="doctorId">
                <option value="">Seleccionar Doctor</option>
                @foreach ($doctors as $doctor)
                    <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                @endforeach
            </select>

            {{-- ... resto del formulario ... --}}
        </div>
    </x-filament::modal>



    {{-- <x-filament::modal id="appointment-selector-modal" width="md">
        <x-slot name="heading">Seleccionar Horario</x-slot>

        <div class="space-y-4">
            <select wire:model="therapyId">
                <option value="">Seleccionar Terapia</option>
                @foreach ($therapies as $therapy)
                    <option value="{{ $therapy->id }}">{{ $therapy->name }}</option>
                @endforeach
            </select>

            <select wire:model="doctorId">
                <option value="">Seleccionar Doctor</option>
                @foreach ($doctors as $doctor)
                    <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                @endforeach
            </select>

            {{-- ... resto del formulario ... --}}
        {{-- </div>
    </x-filament::modal> --}}


    
    {{-- <x-filament::modal id="appointment-selector-modal" width="md">
        <x-slot name="heading">Seleccionar Horario</x-slot>

        <div class="space-y-4">
            <x-filament::select wire:model="therapyId" label="Terapia">
                <option value="">Seleccionar Terapia</option>
                @foreach ($therapies as $therapy)
                    <option value="{{ $therapy->id }}">{{ $therapy->name }}</option>
                @endforeach
            </x-filament::select>

            <x-filament::select wire:model="doctorId" label="Doctor">
                <option value="">Seleccionar Doctor</option>
                @foreach ($doctors as $doctor)
                    <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                @endforeach
            </x-filament::select>

            <x-filament::select wire:model="day" label="Día">
                <option value="">Seleccionar Día</option>
                @foreach ($days as $day)
                    <option value="{{ $day }}">{{ $day }}</option>
                @endforeach
            </x-filament::select>

            <x-filament::select wire:model="appointmentId" label="Horario">
                <option value="">Seleccionar Horario</option>
                @foreach ($appointments as $appointment)
                    <option value="{{ $appointment->id }}">{{ date('H:i', strtotime($appointment->start_time)) }} - {{ date('H:i', strtotime($appointment->end_time)) }}</option>
                @endforeach
            </x-filament::select>

            <x-filament::button wire:click="selectAppointment" wire:loading.attr="disabled">Seleccionar</x-filament::button>
        </div>
    </x-filament::modal> --}}
</div>
