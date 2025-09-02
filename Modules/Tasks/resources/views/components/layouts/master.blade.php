@props(['title' => 'Tasks'])

<x-layouts.app.sidebar :title="$title">
    <flux:main>
        <div class="space-y-6">
            <div>
                <flux:heading size="xl">{{ $title }}</flux:heading>
            </div>

            {{ $slot }}
        </div>
    </flux:main>
</x-layouts.app.sidebar>
