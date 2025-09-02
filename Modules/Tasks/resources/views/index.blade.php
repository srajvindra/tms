<x-tasks::layouts.master title="Tasks List">
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <flux:heading size="lg">All Tasks</flux:heading>
            <div class="flex gap-3">
                <flux:button variant="ghost" wire:click="$dispatch('import-modal-open')">
                    Import CSV
                </flux:button>
                <flux:button :href="route('tasks.create')" wire:navigate>
                    Create New Task
                </flux:button>
            </div>
        </div>

        @livewire('tasks.tasks-list')
    </div>
</x-tasks::layouts.master>