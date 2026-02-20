<div class="flex min-h-screen flex-col">
    <div class="flex-1">
        <x-layouts.app.sidebar :title="$title ?? null">
            <flux:main>
                {{ $slot }}
            </flux:main>
        </x-layouts.app.sidebar>
    </div>

    <x-layouts.app.footer />
</div>
