<x-layouts.app title="Surplus Shop">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-6">
        <!-- Header with Background Image -->
        <div class="relative w-full overflow-hidden rounded-xl bg-cover bg-center h-64"
             style="background-image: url('/pictures/Gebakjes.jpg');">
            <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                <h1 class="text-4xl font-bold text-white drop-shadow-lg">Overschotten</h1>
            </div>
            <div class="absolute top-4 right-4">
                <a href="{{ route('cart.index') }}" class="bg-white p-2 rounded-full shadow-lg flex items-center justify-center hover:bg-gray-100 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    @if(session('cart') && count(session('cart')) > 0)
                        <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">{{ count(session('cart')) }}</span>
                    @endif
                </a>
            </div>
        </div>

        <!-- Main Content (Original Style) -->
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            @foreach($surpluses as $surplus)
                <x-surplus-list :surplus="$surplus"/>
            @endforeach
        </div>
    </div>
</x-layouts.app>
