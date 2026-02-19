<x-layouts.app :title="__('Overschotten')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Overschotten</h1>

            <div class="">
                <input
                    wire:model="search"
                    type="search"
                    name="search"
                    placeholder="Zoek op dessertnaam..."
                    class="rounded-md border px-3 py-2"
                />
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left">Datum</th>
                        <th class="px-4 py-2 text-left">Dessert</th>
                        <th class="px-4 py-2 text-left">Aantal</th>
                        <th class="px-4 py-2 text-left">Acties</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($surpluses as $surplus)
                        <tr>
                            <td class="px-4 py-2">{{ isset($surplus->date) ? (\Illuminate\Support\Str::length($surplus->date) ? $surplus->date : '') : '' }}</td>
                            <td class="px-4 py-2">{{ $surplus->dessert->name ?? '—' }}</td>
                            <td class="px-4 py-2">{{ $surplus->quantity ?? $surplus->amount ?? '—' }}</td>
                            <td class="px-4 py-2">{{-- actions placeholder --}}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-2" colspan="4">Geen overschotten gevonden.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $surpluses->links() }}
        </div>
    </div>
</x-layouts.app>


