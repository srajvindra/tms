@props(['title' => 'World Map'])

<x-layouts.app.sidebar :title="$title">
    <flux:main class="flex flex-1 flex-col">
        {{ $slot }}
    </flux:main>
</x-layouts.app.sidebar>
