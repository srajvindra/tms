<?php

namespace App\Livewire\Tasks;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Tasks\Models\Task;

class TasksList extends Component
{
    use WithPagination;

    public $search = '';
    public $status_filter = '';
    public $priority_filter = '';
    public $category_filter = '';
    public $per_page = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'status_filter' => ['except' => ''],
        'priority_filter' => ['except' => ''],
        'category_filter' => ['except' => ''],
        'per_page' => ['except' => 10],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingPriorityFilter(): void
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function deleteTask($id): void
    {
        $task = Task::findOrFail($id);
        $task->delete();
        
        session()->flash('message', 'Task deleted successfully!');
        $this->resetPage();
    }

    public function getTasks()
    {
        $query = Task::query();
        
        if ($this->search) {
            $query->where(function($q) {
                $q->where('what', 'like', '%' . $this->search . '%')
                  ->orWhere('source', 'like', '%' . $this->search . '%')
                  ->orWhere('action', 'like', '%' . $this->search . '%')
                  ->orWhere('type', 'like', '%' . $this->search . '%')
                  ->orWhere('category', 'like', '%' . $this->search . '%')
                  ->orWhere('comments', 'like', '%' . $this->search . '%');
            });
        }
        
        if ($this->status_filter) {
            $query->where('status', $this->status_filter);
        }
        
        if ($this->priority_filter) {
            $query->where('priority', $this->priority_filter);
        }
        
        if ($this->category_filter) {
            $query->where('category', 'like', '%' . $this->category_filter . '%');
        }
        
        return $query->orderBy('created_at', 'desc')->paginate($this->per_page);
    }

    public function render()
    {
        return view('livewire.tasks.tasks-list', [
            'tasks' => $this->getTasks(),
        ]);
    }
}
