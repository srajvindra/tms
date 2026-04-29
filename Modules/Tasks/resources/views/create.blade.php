<x-tasks::layouts.master title="Create Task">
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <flux:heading size="lg">Create New Task</flux:heading>
            <flux:button :href="route('tasks.index')" variant="ghost" wire:navigate>
                Back to Tasks
            </flux:button>
        </div>

        @livewire('tasks.tasks-create')
    </div>
</x-tasks::layouts.master>