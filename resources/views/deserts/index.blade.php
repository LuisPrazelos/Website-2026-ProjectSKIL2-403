<x-layouts.app title="Onze Selectie">
    <div class="flex h-full w-full flex-1 flex-col bg-zinc-50/50 dark:bg-zinc-950/50">
        <!-- Modern Hero Section -->
        <div class="relative h-[450px] w-full overflow-hidden">
            <img src="/pictures/Gebakjes.jpg" alt="Hero" class="absolute inset-0 h-full w-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-transparent backdrop-blur-[2px]"></div>

            <div class="relative flex h-full flex-col items-center justify-center px-6 text-center">
                <span class="mb-4 text-[10px] font-black uppercase tracking-[0.5em] text-accent/90 animate-fade-in">Ambachtelijk & Vers</span>
                <h1 class="max-w-4xl text-5xl md:text-7xl font-black italic tracking-tighter text-white uppercase drop-shadow-2xl">
                    Onze <span class="text-accent underline decoration-white/20 underline-offset-8">Dessert</span> Selectie
                </h1>
                <p class="mt-6 max-w-2xl text-lg font-medium text-white/80 leading-relaxed">
                    Ontdek onze huisgemaakte creaties, met passie bereid voor het ultieme genot.
                </p>
                <div class="mt-10 flex gap-4">
                    <div class="h-1.5 w-12 rounded-full bg-accent"></div>
                    <div class="h-1.5 w-1.5 rounded-full bg-white/30"></div>
                    <div class="h-1.5 w-1.5 rounded-full bg-white/30"></div>
                </div>
            </div>

            <!-- Floating Cart Bubble -->
            <div class="absolute top-8 right-8 z-30">
                <a href="{{ route('shopping-cart') }}"
                   class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white/90 shadow-2xl backdrop-blur-xl transition-all hover:scale-110 active:scale-95 group dark:bg-zinc-900/90 border border-white/20">
                    <svg class="h-7 w-7 text-zinc-900 dark:text-white transition-colors group-hover:text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 118 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    @if(session('cart') && count(session('cart')) > 0)
                        <span class="absolute -top-1 -right-1 flex h-6 w-6 items-center justify-center rounded-lg bg-accent text-[10px] font-black text-white ring-4 ring-white dark:ring-zinc-900">
                            {{ count(session('cart')) }}
                        </span>
                    @endif
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="mx-auto w-full max-w-[1400px] px-6 py-20">
            <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div>
                    <h2 class="text-3xl font-black italic tracking-tighter text-zinc-900 dark:text-white uppercase">Beschikbaar Genot</h2>
                    <div class="mt-2 h-1 w-20 bg-accent rounded-full"></div>
                </div>
                <p class="text-sm font-bold text-zinc-400 uppercase tracking-widest">
                    {{ $deserts->count() }} Items Totaal
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-10">
                @forelse($deserts as $desert)
                    @livewire('dessert-card', ['desert' => $desert], key('desert-'.$desert->id))
                @empty
                    <div class="col-span-full py-32 text-center">
                        <div class="mx-auto max-w-xl rounded-[3rem] bg-white p-20 shadow-sm border border-zinc-100 dark:bg-zinc-900 dark:border-zinc-800">
                            <div class="mb-8 flex justify-center">
                                <div class="rounded-full bg-zinc-50 p-8 dark:bg-zinc-800">
                                    <svg class="h-16 w-16 text-zinc-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-2xl font-black italic tracking-tight text-zinc-400 dark:text-zinc-600 uppercase">{{ __('Even geduld...') }}</h3>
                            <p class="mt-4 font-medium text-zinc-400">Onze patissiers zijn momenteel druk bezig. Kom binnenkort terug!</p>
                            <div class="mt-10">
                                <a href="{{ route('home') }}" class="inline-flex h-14 items-center justify-center rounded-2xl bg-zinc-900 px-8 text-sm font-black uppercase tracking-widest text-white transition-all hover:bg-accent dark:bg-white dark:text-zinc-900">
                                    Terug naar Start
                                </a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Footer Decoration -->
        <div class="py-20 flex flex-col items-center justify-center opacity-20 select-none pointer-events-none">
             <div class="h-px w-32 bg-gradient-to-r from-transparent via-zinc-400 to-transparent mb-8"></div>
             <span class="text-4xl font-black italic tracking-[0.5em] text-zinc-300 dark:text-zinc-800 uppercase">Eten op Tafel</span>
        </div>
    </div>
</x-layouts.app>
