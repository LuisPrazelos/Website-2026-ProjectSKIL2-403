<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:sidebar sticky stashable
        class="flex h-screen flex-col border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
            <x-app-logo />
        </a>

        <flux:navlist variant="outline">
            <flux:navlist.group :heading="__('Platform')" class="grid">
                <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                    wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
                <flux:navlist.item icon="calendar" :href="route('event.request')"
                    :current="request()->routeIs('event.request')" wire:navigate>{{ __('Evenementen') }}
                </flux:navlist.item>
                <flux:navlist.item icon="cake" :href="route('deserts.index')"
                    :current="request()->routeIs('deserts.index')" wire:navigate>{{ __('Desserts') }}
                </flux:navlist.item>
                <flux:navlist.item icon="plus" :href="route('userSurplusShop.index')"
                    :current="request()->routeIs('userSurplusShop.index')" wire:navigate>{{ __('Surpluses') }}
                </flux:navlist.item>
                <flux:navlist.item icon="shopping-cart" href="#" wire:navigate>{{ __('Orders') }}</flux:navlist.item>
                <flux:navlist.item icon="users" href="#" wire:navigate>{{ __('Workshops') }}</flux:navlist.item>
                <flux:navlist.item icon="clipboard-document-list" href="#" wire:navigate>{{ __('Shopping List') }}
                </flux:navlist.item>
                <flux:navlist.item icon="star" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                    wire:navigate>{{ __('Reviews') }}</flux:navlist.item>
            </flux:navlist.group>

            @if(auth()->check() && auth()->user()->isAdmin())
                <flux:navlist.group :heading="__('Beheer')" class="grid mt-4">
                    <flux:navlist.item icon="cake" :href="route('owner.deserts.index')"
                        :current="request()->routeIs('owner.deserts.index')" wire:navigate>{{ __('Desserts Beheren') }}
                    </flux:navlist.item>
                    <flux:navlist.item icon="book-open" :href="route('owner.recipes.index')"
                        :current="request()->routeIs('owner.recipes.index')" wire:navigate>{{ __('Recepten Beheren') }}
                    </flux:navlist.item>
                    <flux:navlist.item icon="beaker" :href="route('owner.ingredients.index')"
                        :current="request()->routeIs('owner.ingredients.index')" wire:navigate>
                        {{ __('Ingrediënten Beheren') }}</flux:navlist.item>
                    <flux:navlist.item icon="shopping-cart" :href="route('owner.orders.index')"
                        :current="request()->routeIs('owner.orders.index')" wire:navigate>{{ __('Bestellingen Beheren') }}
                    </flux:navlist.item>
                    <flux:navlist.item icon="plus" :href="route('owner.surpluses.index')"
                        :current="request()->routeIs('owner.surpluses.index')" wire:navigate>
                        {{ __('Overschotten Beheren') }}</flux:navlist.item>
                    <flux:navlist.item icon="chart-bar" :href="route('price-evolution')"
                        :current="request()->routeIs('price-evolution')" wire:navigate>{{ __('Prijsevolutie') }}
                    </flux:navlist.item>
                    <flux:navlist.item icon="calendar" :href="route('owner.respond-order-requests')"
                        :current="request()->routeIs('owner.respond-order-requests*')" wire:navigate>
                        {{ __('Evenementen Beheren') }}</flux:navlist.item>
                    <flux:navlist.item icon="star" :href="route('owner.reviews.index')"
                        :current="request()->routeIs('owner.reviews.index')" wire:navigate>{{ __('Reviews Beheren') }}
                    </flux:navlist.item>
                    <flux:navlist.item icon="clipboard-document-list" :href="route('shopping-list')"
                        :current="request()->routeIs('shopping-list')" wire:navigate>{{ __('Boodschappenlijst') }}
                    </flux:navlist.item>
                    <flux:navlist.item icon="users" :href="route('owner.workshops.index')"
                        :current="request()->routeIs('owner.workshops.index')" wire:navigate>{{ __('Workshops Beheren') }}
                    </flux:navlist.item>
                    <flux:navlist.item icon="paint-brush" :href="route('owner.themes.index')"
                        :current="request()->routeIs('owner.themes.index')" wire:navigate>{{ __('Thema\'s Beheren') }}
                    </flux:navlist.item>
                </flux:navlist.group>
            @endif
        </flux:navlist>

        <div class="mt-auto hidden lg:block">
            <div class="p-2">
                <livewire:shopping-cart />
            </div>
            <flux:dropdown position="bottom" align="start">
                <flux:profile :name="auth()->user()->name" :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down" />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>
                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>
                    <flux:menu.separator />
                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>
                    <flux:menu.separator />
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </div>
    </flux:sidebar>

    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
        <flux:spacer />
        <livewire:shopping-cart :handle-adds="true" />
        <flux:dropdown position="top" align="end">
            <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />
            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>
                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    {{ $slot }}

    @fluxScripts
</body>

</html>