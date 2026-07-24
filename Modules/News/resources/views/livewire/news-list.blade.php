<div class="max-w-7xl mx-auto p-6">
    <div class="bg-white rounded-lg shadow-md">

        @if (session()->has('message'))
            <div class="mx-6 mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('message') }}
            </div>
        @endif

        <!-- Filters -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" wire:model.live.debounce.300ms="search"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                           placeholder="Search articles...">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select wire:model.live="status_filter"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">All Status</option>
                        <option value="unread">Unread</option>
                        <option value="reading">Reading</option>
                        <option value="read">Read</option>
                        <option value="saved">Saved</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <input type="text" wire:model.live.debounce.300ms="category_filter"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                           placeholder="Filter by category...">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Source</label>
                    <input type="text" wire:model.live.debounce.300ms="source_filter"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                           placeholder="Filter by source...">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Per Page</label>
                    <select wire:model.live="per_page"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Articles Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Source</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Published</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($articles as $article)
                        <tr wire:key="article-{{ $article->id }}" class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 max-w-xs">
                                    {{ Str::limit($article->title, 80) }}
                                </div>
                                @if($article->author)
                                    <div class="text-xs text-gray-500">{{ $article->author }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $article->source }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $article->category }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($article->status === 'read') bg-green-100 text-green-800
                                    @elseif($article->status === 'reading') bg-blue-100 text-blue-800
                                    @elseif($article->status === 'saved') bg-purple-100 text-purple-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ ucfirst($article->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $article->published_at?->format('M d, Y') ?? '—' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ $article->url }}" target="_blank" rel="noopener noreferrer"
                                   class="text-gray-500 hover:text-gray-800">Open</a>
                                <a href="{{ route('news.show', $article) }}" class="text-blue-600 hover:text-blue-900" wire:navigate>View</a>
                                <a href="{{ route('news.edit', $article) }}" class="text-green-600 hover:text-green-900" wire:navigate>Edit</a>
                                <button wire:click="deleteArticle({{ $article->id }})"
                                        onclick="return confirm('Are you sure you want to delete this article?')"
                                        type="button" class="text-red-600 hover:text-red-900">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                    </svg>
                                    <p class="text-lg font-medium">No articles found</p>
                                    <p class="text-sm">Get started by adding your first article.</p>
                                    <a href="{{ route('news.create') }}" wire:navigate
                                       class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                        Add Article
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($articles->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $articles->links() }}
            </div>
        @endif
    </div>
</div>
