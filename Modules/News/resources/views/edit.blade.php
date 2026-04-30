<x-news::layouts.master title="Edit Article">
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <flux:heading size="lg">Edit Article</flux:heading>
            <flux:button :href="route('news.index')" variant="ghost" wire:navigate>
                Back to News
            </flux:button>
        </div>

        @livewire('news.news-edit', ['article' => $news])
    </div>
</x-news::layouts.master>
