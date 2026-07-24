<x-news::layouts.master title="Article">
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <flux:heading size="lg">{{ $news->title }}</flux:heading>
            <div class="flex gap-3">
                <flux:button :href="route('news.edit', $news)" variant="ghost" wire:navigate>
                    Edit
                </flux:button>
                <flux:button :href="route('news.index')" variant="ghost" wire:navigate>
                    Back to News
                </flux:button>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 space-y-4">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                    @if($news->status === 'read') bg-green-100 text-green-800
                    @elseif($news->status === 'reading') bg-blue-100 text-blue-800
                    @elseif($news->status === 'saved') bg-purple-100 text-purple-800
                    @else bg-yellow-100 text-yellow-800 @endif">
                    {{ ucfirst($news->status) }}
                </span>
                <span class="text-sm text-gray-500">{{ $news->category }}</span>
                @if($news->source)
                    <span class="text-sm text-gray-500">— {{ $news->source }}</span>
                @endif
            </div>

            @if($news->description)
                <p class="text-gray-700">{{ $news->description }}</p>
            @endif

            <div class="flex items-center gap-4 text-sm text-gray-500">
                @if($news->author)
                    <span>By {{ $news->author }}</span>
                @endif
                @if($news->published_at)
                    <span>{{ $news->published_at->format('M d, Y') }}</span>
                @endif
            </div>

            <a href="{{ $news->url }}" target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium">
                Open Article
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
            </a>

            @if($news->notes)
                <div class="border-t pt-4">
                    <flux:heading size="sm" class="mb-2">Notes</flux:heading>
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $news->notes }}</p>
                </div>
            @endif
        </div>
    </div>
</x-news::layouts.master>
