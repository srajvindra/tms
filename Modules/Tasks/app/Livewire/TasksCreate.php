<?php

namespace Modules\Tasks\app\Livewire;

use Livewire\Component;
use Modules\Tasks\Models\Task;

class TasksCreate extends Component
{
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

    public function create(): void
    {
        $this->validate();
        
        // Additional validation for recurring tasks
        if ($this->is_recurring && empty($this->recurring_type)) {
            $this->addError('recurring_type', 'The recurring type is required when task is set as recurring.');
            return;
        }
        
        Task::create([
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
        
        session()->flash('message', 'Task created successfully!');
        
        // Reset form
        $this->reset();
        $this->priority = 'medium';
        $this->status = 'pending';
        $this->is_recurring = false;
    }

    public function resetForm(): void
    {
        $this->reset();
        $this->priority = 'medium';
        $this->status = 'pending';
        $this->is_recurring = false;
    }

    public function render()
    {
        return view('tasks::livewire.tasks-create');
    }
}