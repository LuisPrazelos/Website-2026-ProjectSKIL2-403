<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold">Boodschappenlijst</h1>
            <p class="text-sm text-gray-600 mt-1">Overzicht van benodigde ingrediënten op basis van bestellingen.</p>
        </div>

        <div class="flex items-center gap-4">
            <select wire:model.live="period" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <option value="today">Vandaag</option>
                <option value="week">Deze Week</option>
                <option value="month">Deze Maand</option>
                <option value="all">Alles</option>
            </select>
        </div>
    </div>

    <div class="bg-white dark:bg-zinc-900 rounded-lg shadow overflow-hidden">
        <div class="p-4 border-b border-gray-200 dark:border-zinc-700 flex justify-between items-center">
            <span class="font-medium text-lg">Totaal Geschatte Kosten:</span>
            <span class="text-xl font-bold text-green-600">€ {{ number_format($totalEstimatedCost, 2, ',', '.') }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                <thead class="bg-gray-50 dark:bg-zinc-800">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Ingrediënt
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Hoeveelheid Nodig
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Eenheid
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Geschatte Prijs
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-zinc-700">
                    @forelse($shoppingList as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $item['name'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ number_format($item['amount'], 2, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $item['unit'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                € {{ number_format($item['total_cost'], 2, ',', '.') }}
                                <span class="text-xs text-gray-400 ml-1">(€ {{ number_format($item['price_per_unit'], 2, ',', '.') }} / {{ $item['unit'] }})</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500 dark:text-gray-400">
                                Geen boodschappen nodig voor deze periode.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
