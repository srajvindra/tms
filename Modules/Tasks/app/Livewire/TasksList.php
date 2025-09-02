<?php

namespace Modules\Tasks\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Modules\Tasks\Models\Task;
use Illuminate\Support\Facades\Validator;

class TasksList extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $status_filter = '';
    public $priority_filter = '';
    public $category_filter = '';
    public $per_page = 10;
    
    public $csvFile;
    public $showImportModal = false;

    protected $listeners = ['import-modal-open' => 'openImportModal'];

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

    public function openImportModal(): void
    {   

        $this->showImportModal = true;
    }

    public function closeImportModal(): void
    {
        $this->showImportModal = false;
        $this->csvFile = null;
    }

    public function importCsv(): void
    {
        $this->validate([
            'csvFile' => 'required|file|mimes:csv,txt|max:2048',
        ]);
        
        dump($this->csvFile);

        $path = $this->csvFile->getRealPath();
        $csv = array_map('str_getcsv', file($path));
        
        // Remove header row if exists
        $header = array_shift($csv);
        
        $imported = 0;
        $skipped = 0;
        
        foreach ($csv as $row) {
            if (count($row) < 3) {
                $skipped++;
                continue;
            }
            
            try {
                Task::create([
                    'what' => $row[0] ?? '',
                    'source' => $row[1] ?? '',
                    'action' => $row[2] ?? '',
                    'type' => $row[3] ?? 'task',
                    'category' => $row[4] ?? 'general',
                    'category_ii' => $row[5] ?? null,
                    'priority' => in_array(strtolower($row[6] ?? ''), ['low', 'medium', 'high', 'urgent']) 
                        ? strtolower($row[6]) 
                        : 'medium',
                    'comments' => $row[7] ?? '',
                    'status' => in_array(strtolower($row[8] ?? ''), ['pending', 'in_progress', 'completed', 'cancelled', 'on_hold']) 
                        ? strtolower($row[8]) 
                        : 'pending',
                    'is_recurring' => filter_var($row[9] ?? false, FILTER_VALIDATE_BOOLEAN),
                    'recurring_type' => $row[10] ?? null,
                ]);
                $imported++;
            } catch (\Exception $e) {
                $skipped++;
            }
        }
        
        session()->flash('message', "Successfully imported {$imported} tasks. {$skipped} rows were skipped.");
        
        $this->closeImportModal();
        $this->resetPage();
    }

    public function render()
    {
        return view('tasks::livewire.tasks-list', [
            'tasks' => $this->getTasks(),
        ]);
    }
}