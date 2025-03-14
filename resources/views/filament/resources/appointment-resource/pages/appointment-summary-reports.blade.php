<x-filament::page>

    <x-filament::card>
        <form wire:submit.prevent="generateReport">
            <div class="flex flex-wrap items-end gap-4">
                
                <!-- Start Date -->
                <div class="flex-1">
                    <label for="startDate" class="block text-sm font-medium text-gray-700">Start Date</label>
                    <input type="date" id="startDate" wire:model="startDate"
                        class="block w-full mt-1 rounded-md border-gray-300 shadow-sm">
                </div>

                <!-- End Date -->
                <div class="flex-1">
                    <label for="endDate" class="block text-sm font-medium text-gray-700">End Date</label>
                    <input type="date" id="endDate" wire:model="endDate"
                        class="block w-full mt-1 rounded-md border-gray-300 shadow-sm">
                </div>

                <!-- Buttons -->
                <div class="flex gap-2">
                    <x-filament::button type="submit">Show Reports</x-filament::button>
                    <x-filament::button wire:click="printPdf">Print PDF</x-filament::button>
                </div>
            </div>
        </form>
        <br>
        @if ($appointments->isNotEmpty())
            <div class="mt-12">
                <table class="w-full table-auto border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border border-gray-300 px-4 py-2 text-left">Status</th>
                            <th class="border border-gray-300 px-4 py-2 text-right">Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-green-100">
                            <td class="border border-gray-300 px-4 py-2">Confirmed</td>
                            <td class="border border-gray-300 px-4 py-2 text-right">{{ $confirmedCount }}</td>
                        </tr>
                        <tr class="bg-yellow-100">
                            <td class="border border-gray-300 px-4 py-2">Pending</td>
                            <td class="border border-gray-300 px-4 py-2 text-right">{{ $pendingCount }}</td>
                        </tr>
                        <tr class="bg-red-100">
                            <td class="border border-gray-300 px-4 py-2">Rejected</td>
                            <td class="border border-gray-300 px-4 py-2 text-right">{{ $rejectedCount }}</td>
                        </tr>
                        <tr class="bg-blue-100">
                            <td class="border border-gray-300 px-4 py-2">Completed</td>
                            <td class="border border-gray-300 px-4 py-2 text-right">{{ $completedCount }}</td>
                        </tr>
                        <tr class="bg-100">
                            <td class="border border-gray-300 px-4 py-2">Cancelled</td>
                            <td class="border border-gray-300 px-4 py-2 text-right">{{ $cancelledCount }}</td>
                        </tr>
                        <tr class="bg-gray-100">
                            <td class="border border-gray-300 px-4 py-2">Total</td>
                            <td class="border border-gray-300 px-4 py-2 text-right">{{ $totalCount }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif
    </x-filament::card>

</x-filament::page>
