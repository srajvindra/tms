<x-worldmap::layouts.master title="World Map">
    <div class="flex h-full w-full flex-1 flex-col gap-4">
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-baseline gap-4">
                <flux:heading size="xl">World Map</flux:heading>
                <flux:text class="text-sm">Right-click two cities to measure the distance between them</flux:text>
            </div>
            <flux:text id="worldmap-coords" class="tabular-nums"></flux:text>
        </div>

        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700" style="min-height: 480px;">
            <div id="worldmap" style="position: absolute; inset: 0;"></div>
            <div id="worldmap-loading"
                 class="bg-white text-sm text-neutral-500 dark:bg-zinc-900 dark:text-neutral-400"
                 style="position: absolute; inset: 0; z-index: 10; display: flex; align-items: center; justify-content: center; transition: opacity 0.3s;">
                Loading map…
            </div>
        </div>
    </div>

    @vite('resources/js/worldmap.js')
</x-worldmap::layouts.master>
