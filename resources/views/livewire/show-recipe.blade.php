<div>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-transparent">
            <!-- Top Navigation -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-2">
                    <h1 class="text-2xl font-semibold">{{ $recipe->name }}</h1>
                    <span class="text-gray-500 text-sm">({{ __('Recept bekijken') }})</span>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('owner.recipes.index') }}" class="inline-flex items-center px-4 py-2 border rounded-md text-sm bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200">{{ __('Terug naar overzicht') }}</a>
                    <a href="{{ route('owner.recipes.index', ['edit_id' => $recipe->id]) }}" class="inline-flex items-center px-4 py-2 border rounded-md text-sm bg-black text-white">{{ __('Recept bewerken') }}</a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basisinformatie -->
                <div class="bg-white dark:bg-zinc-900 rounded-md shadow-sm p-4">
                    <h2 class="text-xl font-semibold mb-4">{{ __('Basisinformatie') }}</h2>
                    <div class="space-y-2">
                        <p><strong>{{ __('Categorie') }}:</strong> {{ $recipe->category->name ?? '-' }}</p>
                        <p><strong>{{ __('Bereidingstijd') }}:</strong> {{ $recipe->preparation_time ?? '-' }} {{ __('minuten') }}</p>
                        <p><strong>{{ __('Verkoopprijs') }} (€):</strong> €{{ number_format($recipe->selling_price ?? 0, 2) }}</p>
                        <p><strong>{{ __('Portie grootte') }}:</strong> {{ $recipe->portion_size ?? '-' }} {{ $recipe->portionUnit->name ?? '' }}</p>
                        <p><strong>{{ __('Laatste update') }}:</strong> {{ $recipe->updated_at ? $recipe->updated_at->format('d-m-Y H:i') : '-' }}</p>
                        <p><strong>{{ __('Allergenen') }}:</strong>
                            @if($allAllergies)
                                {{ implode(', ', $allAllergies) }}
                            @else
                                {{ __('Geen') }}
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Ingrediënten -->
                <div class="bg-white dark:bg-zinc-900 rounded-md shadow-sm p-4">
                    <h2 class="text-xl font-semibold mb-4">{{ __('Ingrediënten') }}</h2>
                    @if($recipe->ingredients->isNotEmpty())
                        <table class="min-w-full text-left">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2">{{ __('Ingrediëntnaam') }}</th>
                                    <th class="px-4 py-2">{{ __('Hoeveelheid') }}</th>
                                    <th class="px-4 py-2">{{ __('Allergenen') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recipe->ingredients as $ingredient)
                                    <tr class="border-t">
                                        <td class="px-4 py-2">{{ $ingredient->name }}</td>
                                        <td class="px-4 py-2">{{ $ingredient->pivot->quantity }} {{ $units[$ingredient->pivot->measurement_unit_id] ?? '-' }}</td>
                                        <td class="px-4 py-2">
                                            @if($ingredient->ingredientAllergies->isNotEmpty())
                                                {{ $ingredient->ingredientAllergies->map(fn($ia) => $ia->allergy->name)->implode(', ') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>{{ __('Geen ingrediënten toegevoegd.') }}</p>
                    @endif
                </div>
            </div>

            <!-- Bereidingswijze -->
            <div class="bg-white dark:bg-zinc-900 rounded-md shadow-sm p-4 mt-6">
                <h2 class="text-xl font-semibold mb-4">{{ __('Bereidingswijze') }}</h2>
                <div class="prose dark:prose-invert">
                    {!! nl2br(e($recipe->instructions ?? __('Geen bereidingswijze opgegeven.'))) !!}
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <!-- Receptfoto's -->
                <div class="bg-white dark:bg-zinc-900 rounded-md shadow-sm p-4">
                    <h2 class="text-xl font-semibold mb-4">{{ __('Receptfoto\'s') }}</h2>
                    @if($recipe->photo)
                        <img src="{{ Storage::url($recipe->photo) }}" alt="{{ $recipe->name }}" class="max-w-full h-auto rounded-md">
                    @else
                        <p>{{ __('Geen foto beschikbaar.') }}</p>
                    @endif
                </div>

                <!-- Notities bij recept -->
                <div class="bg-white dark:bg-zinc-900 rounded-md shadow-sm p-4">
                    <h2 class="text-xl font-semibold mb-4">{{ __('Notities bij recept') }}</h2>
                    <div class="prose dark:prose-invert">
                        {!! nl2br(e($recipe->description ?? __('Geen notities beschikbaar.'))) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
