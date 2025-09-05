<?php

namespace Modules\Tasks\Livewire;

use Livewire\Component;
use Modules\Tasks\Models\Task;

class TasksEdit extends Component
{
    public Task $task;
    public $what = '';
    public $source = '';
    public $action = '';
    public $type = '';
    public $category = '';
    public $category_ii = '';
    public $priority = 'medium';
    public $comments = '';
    public $status = 'pending';
    public $is_recurring = false;
    public $recurring_type = '';

    public function mount(Task $task): void
    {
        $this->task = $task;
        $this->what = $task->what;
        $this->source = $task->source;
        $this->action = $task->action;
        $this->type = $task->type;
        $this->category = $task->category;
        $this->category_ii = $task->category_ii;
        $this->priority = $task->priority;
        $this->comments = $task->comments;
        $this->status = $task->status;
        $this->is_recurring = $task->is_recurring;
        $this->recurring_type = $task->recurring_type;
    }

    protected function rules(): array
    {
        return [
            'what' => 'required|string|max:65535',
            'source' => 'required|string|max:255',
            'action' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'category_ii' => 'nullable|string|max:255',
            'priority' => 'required|in:low,medium,high,urgent',
            'comments' => 'nullable|string|max:65535',
            'status' => 'required|in:pending,in_progress,completed,cancelled,on_hold',
            'is_recurring' => 'boolean',
            'recurring_type' => 'nullable|string|max:255',
        ];
    }

    protected function messages(): array
    {
        return [
            'what.required' => 'The task description is required.',
            'what.max' => 'The task description may not be greater than 65535 characters.',
            'source.required' => 'The task source is required.',
            'source.max' => 'The source may not be greater than 255 characters.',
            'action.required' => 'The action is required.',
            'action.max' => 'The action may not be greater than 255 characters.',
            'type.required' => 'The task type is required.',
            'type.max' => 'The type may not be greater than 255 characters.',
            'category.required' => 'The category is required.',
            'category.max' => 'The category may not be greater than 255 characters.',
            'category_ii.max' => 'The secondary category may not be greater than 255 characters.',
            'priority.required' => 'The priority is required.',
            'priority.in' => 'The selected priority is invalid. Must be one of: low, medium, high, urgent.',
            'comments.max' => 'The comments may not be greater than 65535 characters.',
            'status.required' => 'The status is required.',
            'status.in' => 'The selected status is invalid. Must be one of: pending, in_progress, completed, cancelled, on_hold.',
            'is_recurring.boolean' => 'The recurring field must be true or false.',
            'recurring_type.max' => 'The recurring type may not be greater than 255 characters.',
        ];
    }

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function update(): void
    {
        $this->validate();
        
        // Additional validation for recurring tasks
        if ($this->is_recurring && empty($this->recurring_type)) {
            $this->addError('recurring_type', 'The recurring type is required when task is set as recurring.');
            return;
        }
        
        $this->task->update([
            'what' => $this->what,
            'source' => $this->source,
            'action' => $this->action,
            'type' => $this->type,
            'category' => $this->category,
            'category_ii' => $this->category_ii,
            'priority' => $this->priority,
            'comments' => $this->comments,
            'status' => $this->status,
            'is_recurring' => $this->is_recurring,
            'recurring_type' => $this->recurring_type,
        ]);
        
        session()->flash('message', 'Task updated successfully!');
        
        $this->redirect(route('tasks.index'));
    }

    public function render()
    {
        return view('tasks::livewire.tasks-edit');
    }
}