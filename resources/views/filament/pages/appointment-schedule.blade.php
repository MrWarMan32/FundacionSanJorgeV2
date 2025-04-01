<x-filament-panels::page>
    <div class="overflow-x-auto">
        <table class="table-auto w-full">
            <thead>
                <tr>
                    <th></th>
                    @foreach ($daysOfWeek as $day)
                        <th class="px-4 py-2 text-left">{{ $day }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($timeSlots as $timeSlot)
                    <tr>
                        <th class="px-4 py-2 text-left">{{ $timeSlot }}</th>
                        @foreach ($daysOfWeek as $day)
                            <td class="px-4 py-2 border">
                                {!! $scheduleData[$day][$timeSlot] ?? '' !!}
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-filament-panels::page>


