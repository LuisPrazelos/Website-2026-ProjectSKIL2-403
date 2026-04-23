<div class="flex h-full w-full flex-1 flex-col gap-6 p-6">
    <!-- Header with Background Image -->
    <div class="relative w-full overflow-hidden rounded-[3rem] bg-cover bg-center h-64 shadow-2xl"
         style="background-image: url('/pictures/Gebakjes.jpg');">
        <div class="absolute inset-0 bg-black/50 flex flex-col items-center justify-center backdrop-blur-[1px]">
            <h1 class="text-5xl font-black text-white drop-shadow-2xl uppercase italic tracking-tighter">Deel uw Ervaring</h1>
            <p class="text-white font-bold mt-2 uppercase tracking-[0.3em] text-xs">Uw feedback maakt ons beter</p>
            <div class="mt-6 bg-accent h-1.5 w-20 rounded-full"></div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-3xl mx-auto w-full">
        @if($successMessage)
            <div class="mb-8 p-10 bg-white dark:bg-zinc-900 rounded-[3rem] shadow-xl border border-zinc-100 dark:border-zinc-800 text-center animate-fade-in">
                <div class="mb-6 flex justify-center">
                    <div class="rounded-full bg-green-50 p-6 dark:bg-green-900/20">
                        <svg class="h-16 w-16 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
                <h2 class="text-3xl font-black italic tracking-tighter text-zinc-900 dark:text-white uppercase">Bedankt!</h2>
                <p class="mt-4 text-zinc-500 font-medium">Uw review is succesvol geplaatst en zal anderen helpen bij hun keuze.</p>
                <div class="mt-10">
                    <button wire:click="$set('successMessage', false)" class="inline-flex h-14 items-center justify-center rounded-2xl bg-accent px-10 text-sm font-black uppercase tracking-widest text-white shadow-xl shadow-accent/20 transition-all hover:bg-amber-700 active:scale-95">
                        Nog een review schrijven
                    </button>
                </div>
            </div>
        @else
            <div class="bg-white dark:bg-zinc-900 rounded-[3rem] p-10 lg:p-16 shadow-xl border border-zinc-100 dark:border-zinc-800 relative overflow-hidden transition-all duration-500 hover:shadow-2xl">
                <!-- Decorative element -->
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-accent/5 rounded-full blur-3xl"></div>

                <form wire:submit.prevent="submit" class="space-y-10 relative">
                    <!-- Rating Section -->
                    <div class="flex flex-col items-center">
                        <label class="block text-[10px] font-black uppercase tracking-[0.3em] text-zinc-400 mb-6">Uw Beoordeling</label>
                        <div class="flex gap-4">
                            @foreach(range(1, 5) as $i)
                                <button type="button" wire:click="$set('score', {{ $i }})" class="transition-all duration-300 transform hover:scale-125 group">
                                    <svg class="h-12 w-12 {{ $score >= $i ? 'text-accent fill-accent' : 'text-zinc-200 dark:text-zinc-800' }} transition-colors" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                </button>
                            @endforeach
                        </div>
                        @error('score') <span class="text-[10px] text-red-500 font-black uppercase tracking-widest mt-2">{{ $message }}</span> @enderror
                    </div>

                    <!-- Subject Selection -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-6 border-t border-zinc-100 dark:border-zinc-800">
                        <div class="space-y-3">
                            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400">Waarover wilt u schrijven?</label>
                            <select wire:model.live="subjectType" class="w-full rounded-2xl border-zinc-100 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-800/50 py-4 px-6 text-sm font-bold focus:ring-accent focus:border-accent transition-all dark:text-white appearance-none">
                                <option value="general">Algemene ervaring</option>
                                <option value="dessert">Een Dessert</option>
                                <option value="workshop">Een Workshop</option>
                            </select>
                            @error('subjectType') <span class="text-[10px] text-red-500 font-black uppercase tracking-widest mt-1">{{ $message }}</span> @enderror
                        </div>

                        @if($subjectType !== 'general')
                            <div class="space-y-3 animate-fade-in">
                                <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400">Selecteer het onderwerp:</label>
                                <select wire:model.defer="selectedId" class="w-full rounded-2xl border-zinc-100 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-800/50 py-4 px-6 text-sm font-bold focus:ring-accent focus:border-accent transition-all dark:text-white appearance-none">
                                    <option value="">Maak een keuze...</option>
                                    @foreach($options as $option)
                                        <option value="{{ $option->id ?? $option->reviewId ?? $option->workshopId ?? $option->id }}">{{ $option->name ?? $option->title ?? $option->id }}</option>
                                    @endforeach
                                </select>
                                @error('selectedId') <span class="text-[10px] text-red-500 font-black uppercase tracking-widest mt-1">{{ $message }}</span> @enderror
                            </div>
                        @endif
                    </div>

                    <!-- Review Content -->
                    <div class="space-y-3 pt-6">
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400">Uw Review</label>
                        <textarea wire:model.defer="content" rows="6" placeholder="Vertel ons over uw ervaring..." class="w-full rounded-[2rem] border-zinc-100 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-800/50 py-6 px-8 text-sm font-medium focus:ring-accent focus:border-accent transition-all dark:text-white placeholder:text-zinc-300 dark:placeholder:text-zinc-600 leading-relaxed"></textarea>
                        @error('content') <span class="text-[10px] text-red-500 font-black uppercase tracking-widest mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-8">
                        <button type="submit" wire:loading.attr="disabled" class="w-full bg-accent text-white py-6 rounded-[2rem] font-black text-xl uppercase italic tracking-widest hover:bg-amber-700 transition-all shadow-2xl shadow-accent/40 active:scale-[0.98] disabled:opacity-50 flex items-center justify-center gap-4 group">
                            <span wire:loading.remove>Review Plaatsen</span>
                            <span wire:loading class="animate-pulse">Versturen...</span>
                            <svg class="h-8 w-8 transition-transform group-hover:translate-x-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>
