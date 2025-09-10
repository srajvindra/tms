<x-tasks::layouts.master title="View Task">
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <flux:heading size="lg">Task Details</flux:heading>
            <div class="flex gap-2">
                <flux:button :href="route('tasks.edit', $task)" variant="primary" wire:navigate>
                    Edit Task
                </flux:button>
                <flux:button :href="route('tasks.index')" variant="ghost" wire:navigate>
                    Back to Tasks
                </flux:button>
            </div>
        </div>

        <div class="max-w-4xl mx-auto">
            <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-md p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Task Description -->
                    <div class="md:col-span-2">
                        <flux:heading size="sm" class="mb-2">Task Description</flux:heading>
                        <div class="p-3 bg-gray-50 dark:bg-zinc-700 rounded border min-h-[80px]">
                            <p class="text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $task->what ?? 'No description provided' }}</p>
                        </div>
                    </div>
                    
                    <!-- Source -->
                    <div>
                        <flux:heading size="sm" class="mb-2">Source</flux:heading>
                        <div class="p-3 bg-gray-50 dark:bg-zinc-700 rounded border">
                            <p class="text-gray-900 dark:text-gray-100">{{ $task->source ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <!-- Action -->
                    <div>
                        <flux:heading size="sm" class="mb-2">Action</flux:heading>
                        <div class="p-3 bg-gray-50 dark:bg-zinc-700 rounded border">
                            <p class="text-gray-900 dark:text-gray-100">{{ $task->action ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <!-- Type -->
                    <div>
                        <flux:heading size="sm" class="mb-2">Type</flux:heading>
                        <div class="p-3 bg-gray-50 dark:bg-zinc-700 rounded border">
                            <p class="text-gray-900 dark:text-gray-100">{{ $task->type ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <!-- Category -->
                    <div>
                        <flux:heading size="sm" class="mb-2">Category</flux:heading>
                        <div class="p-3 bg-gray-50 dark:bg-zinc-700 rounded border">
                            <p class="text-gray-900 dark:text-gray-100">{{ $task->category ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <!-- Category II -->
                    @if($task->category_ii)
                    <div>
                        <flux:heading size="sm" class="mb-2">Secondary Category</flux:heading>
                        <div class="p-3 bg-gray-50 dark:bg-zinc-700 rounded border">
                            <p class="text-gray-900 dark:text-gray-100">{{ $task->category_ii }}</p>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Priority -->
                    <div>
                        <flux:heading size="sm" class="mb-2">Priority</flux:heading>
                        <div class="p-3 bg-gray-50 dark:bg-zinc-700 rounded border">
                            <flux:badge 
                                :variant="match($task->priority) {
                                    'urgent' => 'solid',
                                    'high' => 'solid', 
                                    'medium' => 'outline',
                                    'low' => 'subtle',
                                    default => 'outline'
                                }"
                                :class="match($task->priority) {
                                    'urgent' => 'bg-red-600 text-white',
                                    'high' => 'bg-orange-600 text-white',
                                    'medium' => 'border-yellow-500 text-yellow-700 dark:text-yellow-400',
                                    'low' => 'text-gray-600 dark:text-gray-400',
                                    default => 'text-gray-600 dark:text-gray-400'
                                }"
                            >
                                {{ ucfirst($task->priority ?? 'medium') }}
                            </flux:badge>
                        </div>
                    </div>
                    
                    <!-- Status -->
                    <div>
                        <flux:heading size="sm" class="mb-2">Status</flux:heading>
                        <div class="p-3 bg-gray-50 dark:bg-zinc-700 rounded border">
                            <flux:badge 
                                :variant="match($task->status) {
                                    'completed' => 'solid',
                                    'in_progress' => 'solid',
                                    'pending' => 'outline',
                                    'cancelled' => 'subtle',
                                    'on_hold' => 'subtle',
                                    default => 'outline'
                                }"
                                :class="match($task->status) {
                                    'completed' => 'bg-green-600 text-white',
                                    'in_progress' => 'bg-blue-600 text-white',
                                    'pending' => 'border-yellow-500 text-yellow-700 dark:text-yellow-400',
                                    'cancelled' => 'text-red-600 dark:text-red-400',
                                    'on_hold' => 'text-gray-600 dark:text-gray-400',
                                    default => 'text-gray-600 dark:text-gray-400'
                                }"
                            >
                                {{ ucwords(str_replace('_', ' ', $task->status ?? 'pending')) }}
                            </flux:badge>
                        </div>
                    </div>
                    
                    <!-- Comments -->
                    @if($task->comments)
                    <div class="md:col-span-2">
                        <flux:heading size="sm" class="mb-2">Comments</flux:heading>
                        <div class="p-3 bg-gray-50 dark:bg-zinc-700 rounded border min-h-[80px]">
                            <p class="text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $task->comments }}</p>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Recurring Task Info -->
                    @if($task->is_recurring)
                    <div class="md:col-span-2">
                        <flux:heading size="sm" class="mb-2">Recurring Task</flux:heading>
                        <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded border border-blue-200 dark:border-blue-800">
                            <div class="flex items-center gap-2">
                                <flux:icon name="arrow-path" class="h-5 w-5 text-blue-600 dark:text-blue-400" />
                                <p class="text-blue-900 dark:text-blue-100">
                                    This is a recurring task
                                    @if($task->recurring_type)
                                        ({{ $task->recurring_type }})
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Timestamps -->
                    <div>
                        <flux:heading size="sm" class="mb-2">Created</flux:heading>
                        <div class="p-3 bg-gray-50 dark:bg-zinc-700 rounded border">
                            <p class="text-gray-900 dark:text-gray-100">{{ $task->created_at?->format('M j, Y \a\t g:i A') ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <div>
                        <flux:heading size="sm" class="mb-2">Last Updated</flux:heading>
                        <div class="p-3 bg-gray-50 dark:bg-zinc-700 rounded border">
                            <p class="text-gray-900 dark:text-gray-100">{{ $task->updated_at?->format('M j, Y \a\t g:i A') ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-tasks::layouts.master>