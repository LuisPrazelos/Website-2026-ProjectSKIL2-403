<x-layouts.app title="Scan to Pay">
    <div class="container mx-auto px-4 py-8 text-center">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Scan to Pay</h1>
        <p class="mt-2 text-lg text-gray-600 dark:text-gray-400">Please scan the QR code below with your payment app to complete the purchase.</p>
        <div class="mt-8 flex justify-center">
            <img src="{{ asset('pictures/QrCode.png') }}" alt="QR Code for Payment" class="w-64 h-64">
        </div>
        <div class="mt-8">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-black hover:bg-gray-800 dark:bg-white dark:text-black dark:hover:bg-gray-200">
                Back to Dashboard
            </a>
        </div>
    </div>
</x-layouts.app>
