<div class="max-w-4xl mx-auto p-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Task</h1>
        
        @if (session()->has('message'))
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('message') }}
            </div>
        @endif
        
        <form wire:submit="update" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- What -->
                <div class="md:col-span-2">
                    <label for="what" class="block text-sm font-medium text-gray-700 mb-2">What (Task Description)</label>
                    <textarea wire:model="what" id="what" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Describe the task..."></textarea>
                    @error('what') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <!-- Source -->
                <div>
                    <label for="source" class="block text-sm font-medium text-gray-700 mb-2">Source</label>
                    <input type="text" wire:model="source" id="source" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Task source...">
                    @error('source') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <!-- Action -->
                <div>
                    <label for="action" class="block text-sm font-medium text-gray-700 mb-2">Action</label>
                    <input type="text" wire:model="action" id="action" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Required action...">
                    @error('action') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <!-- Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                    <input type="text" wire:model="type" id="type" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Task type...">
                    @error('type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <!-- Category -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <input type="text" wire:model="category" id="category" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Primary category...">
                    @error('category') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <!-- Category II -->
                <div>
                    <label for="category_ii" class="block text-sm font-medium text-gray-700 mb-2">Category II (Optional)</label>
                    <input type="text" wire:model="category_ii" id="category_ii" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Secondary category...">
                    @error('category_ii') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <!-- Priority -->
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                    <select wire:model="priority" id="priority" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                    @error('priority') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select wire:model="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="on_hold">On Hold</option>
                    </select>
                    @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <!-- Comments -->
                <div class="md:col-span-2">
                    <label for="comments" class="block text-sm font-medium text-gray-700 mb-2">Comments (Optional)</label>
                    <textarea wire:model="comments" id="comments" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Additional comments..."></textarea>
                    @error('comments') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <!-- Recurring Options -->
                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <input type="checkbox" wire:model.live="is_recurring" id="is_recurring" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_recurring" class="ml-2 block text-sm text-gray-900">Is Recurring Task</label>
                    </div>
                    @error('is_recurring') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                @if($is_recurring)
                    <div>
                        <label for="recurring_type" class="block text-sm font-medium text-gray-700 mb-2">Recurring Type</label>
                        <input type="text" wire:model="recurring_type" id="recurring_type" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="e.g., daily, weekly, monthly...">
                        @error('recurring_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                @endif
            </div>
            
            <div class="flex gap-4 pt-6">
                <button type="submit" wire:loading.attr="disabled" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50">
                    <span wire:loading.remove>Update Task</span>
                    <span wire:loading>Updating...</span>
                </button>
                
                <a href="{{ route('tasks.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>