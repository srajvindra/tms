<x-news::layouts.master title="News">
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <flux:heading size="lg">All Articles</flux:heading>
            <flux:button :href="route('news.create')" wire:navigate>
                Add Article
            </flux:button>
        </div>

        @livewire('news.news-list')
    </div>
</x-news::layouts.master>
