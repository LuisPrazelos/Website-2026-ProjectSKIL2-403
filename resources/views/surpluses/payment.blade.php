<x-layouts.app title="Betaling">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-6">
        <!-- Header with Background Image -->
        <div class="relative w-full overflow-hidden rounded-xl bg-cover bg-center h-64"
             style="background-image: url('/pictures/Gebakjes.jpg');">
            <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                <h1 class="text-4xl font-bold text-white drop-shadow-lg">Betaling</h1>
            </div>
        </div>

        <!-- Payment Content -->
        <div class="relative flex flex-1 flex-col rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-800 overflow-hidden">
            <div class="flex flex-col items-center justify-center min-h-[300px]">
                <div class="bg-white rounded-xl shadow-md p-8 mt-8 flex flex-col items-center">
                    <h1 class="text-3xl font-bold text-green-600 mb-4">Betaling gelukt!</h1>
                    <p class="text-gray-700 text-lg mb-6">Bedankt voor je bestelling. Je betaling is succesvol verwerkt.</p>
                    <form action="{{ route('home') }}" method="GET" class="flex justify-center mt-4">
                        <button type="submit" class="bg-gray-400 text-white px-8 py-3 rounded-lg text-lg font-bold hover:bg-gray-500 transition">
                            Terug naar homepagina
                        </button>
                    </form>
                    <form action="{{ route('dashboard') }}" method="GET" class="flex justify-center mt-4">
                        <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg text-lg font-bold hover:bg-blue-700 transition">
                            Terug naar home
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('components.layouts.app.footer')
</x-layouts.app>
