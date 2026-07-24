<div class="max-w-4xl mx-auto p-6">
    <div class="bg-white rounded-lg shadow-md p-6">

        @if (session()->has('message'))
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit="update" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Title -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                    <input type="text" wire:model="title"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Article title...">
                    @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- URL -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">URL</label>
                    <input type="url" wire:model="url"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="https://...">
                    @error('url') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Source -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Source</label>
                    <input type="text" wire:model="source"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="e.g. The Hindu, NDTV...">
                    @error('source') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Author -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Author (Optional)</label>
                    <input type="text" wire:model="author"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Author name...">
                    @error('author') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <input type="text" wire:model="category"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="e.g. Technology, Politics, Sports...">
                    @error('category') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Published At -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Published Date (Optional)</label>
                    <input type="date" wire:model="published_at"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('published_at') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select wire:model="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="unread">Unread</option>
                        <option value="reading">Reading</option>
                        <option value="read">Read</option>
                        <option value="saved">Saved</option>
                    </select>
                    @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description (Optional)</label>
                    <textarea wire:model="description" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Brief description of the article..."></textarea>
                    @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Personal Notes (Optional)</label>
                    <textarea wire:model="notes" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Your notes about this article..."></textarea>
                    @error('notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

            </div>

            <div class="flex gap-4 pt-6">
                <button type="submit" wire:loading.attr="disabled"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50">
                    <span wire:loading.remove>Update Article</span>
                    <span wire:loading>Updating...</span>
                </button>

                <a href="{{ route('news.index') }}" wire:navigate
                   class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
