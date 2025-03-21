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

</div>
