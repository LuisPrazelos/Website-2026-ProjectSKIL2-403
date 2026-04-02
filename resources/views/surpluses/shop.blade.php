<x-layouts.app title="Overschotten">
    <div class="flex h-full w-full flex-1 flex-col gap-8 p-6">
        <!-- Header -->
        <div class="relative w-full overflow-hidden rounded-3xl bg-cover bg-center h-80 shadow-2xl"
             style="background-image: url('/pictures/Gebakjes.jpg');">
            <div class="absolute inset-0 bg-black/50 flex flex-col items-center justify-center backdrop-blur-[1px]">
                <h1 class="text-6xl font-black text-white drop-shadow-2xl uppercase italic tracking-tighter">Onze Overschotten</h1>
                <p class="text-white font-bold mt-4 uppercase tracking-[0.3em] text-sm text-center">Vers van vandaag • Extra voordelig • Wees er snel bij!</p>
                <div class="mt-8 bg-accent h-1.5 w-24 rounded-full"></div>
            </div>
            <div class="absolute top-6 right-6">
                <a href="{{ route('shopping-cart') }}" class="bg-white/90 backdrop-blur p-3 rounded-full shadow-lg flex items-center justify-center hover:bg-white transition-all hover:scale-110 active:scale-95 group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-zinc-800 group-hover:text-accent transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    @if(session('cart') && count(session('cart')) > 0)
                        <span class="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">{{ count(session('cart')) }}</span>
                    @endif
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto w-full">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @forelse($surpluses as $surplus)
                    @livewire('surplus-card', ['surplus' => $surplus], key('surplus-'.$surplus->id))
                @empty
                    <div class="col-span-full py-20 text-center">
                        <div class="bg-zinc-50 dark:bg-zinc-900 rounded-3xl p-16 border border-dashed border-zinc-200 dark:border-zinc-700">
                             <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-20 w-20 text-zinc-300 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <h3 class="text-2xl font-black text-zinc-400 dark:text-zinc-600 uppercase italic tracking-tight">{{ __('Geen overschotten beschikbaar.') }}</h3>
                            <p class="mt-2 text-zinc-400 font-medium">Kom later nog eens terug voor onze dagelijkse aanbiedingen!</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.app>
