<x-news::layouts.master title="Add Article">
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <flux:heading size="lg">Add New Article</flux:heading>
            <flux:button :href="route('news.index')" variant="ghost" wire:navigate>
                Back to News
            </flux:button>
        </div>

        @livewire('news.news-create')
    </div>
</x-news::layouts.master>
