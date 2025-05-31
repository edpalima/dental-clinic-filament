<x-filament::page>

    <x-filament::card>
        <form wire:submit.prevent="generateReport">
            <div class="flex flex-wrap items-end gap-4">
                <!-- Start Date -->
                <div class="flex-1">
                    <label for="startDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start
                        Date</label>
                    <input type="date" id="startDate" wire:model="startDate"
                        class="block w-full mt-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white shadow-sm">
                </div>

                <!-- End Date -->
                <div class="flex-1">
                    <label for="endDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">End
                        Date</label>
                    <input type="date" id="endDate" wire:model="endDate"
                        class="block w-full mt-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white shadow-sm">
                </div>

                <!-- Status Filter -->
                <div class="flex-1">
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                    <select id="status" wire:model="status"
                        class="block w-full mt-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white shadow-sm">
                        <option value="ALL">All</option>
                        <option value="PENDING">Pending</option>
                        <option value="CONFIRMED">Confirmed</option>
                        <option value="CANCELLED">Cancelled</option>
                        <option value="REJECTED">Rejected</option>
                        <option value="COMPLETED">Completed</option>
                    </select>
                </div>

                <!-- Time Filter -->
                <div class="flex-1">
                    <label for="timeId" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Time</label>
                    <select id="timeId" wire:model="timeId"
                        class="block w-full mt-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white shadow-sm">
                        <option value="">All</option>
                        @foreach (\App\Models\Time::all() as $time)
                        <option value="{{ $time->id }}">{{ $time->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Buttons -->
                <div class="flex gap-2">
                    <x-filament::button type="submit" icon="heroicon-o-magnifying-glass">
                        Generate Reports
                    </x-filament::button>
                    <x-filament::button wire:click="printPdf" icon="heroicon-o-arrow-down-tray">
                        Download Reports
                    </x-filament::button>
                </div>
            </div>
        </form>
        <br>
        <div class="mt-12">
            <table class="w-full table-auto border-collapse border border-gray-300 dark:border-gray-600">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700">
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left dark:text-white">ID
                        </th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left dark:text-white">
                            Patient</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left dark:text-white">
                            Doctor</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left dark:text-white">
                            Status</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-right dark:text-white">
                            Date</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-right dark:text-white">
                            Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($appointments as $appointment)
                    <tr>
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 dark:text-white">
                            {{ $appointment->id }}
                        </td>
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 dark:text-white">
                            {{ $appointment->patient ? $appointment->patient->fullname : 'N/A' }}
                        </td>
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 dark:text-white">
                            {{ $appointment->doctor ? $appointment->doctor->fullname : 'N/A' }}
                        </td>
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 dark:text-white">
                            {{ $appointment->status }}
                        </td>
                        <td
                            class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-right dark:text-white">
                            {{ $appointment->date }}
                        </td>
                        <td
                            class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-right dark:text-white">
                            {{ $appointment->time->name }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-filament::card>

</x-filament::page>