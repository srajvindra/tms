<?php

namespace Modules\Tasks\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Tasks\Models\Task;
use Modules\Tasks\Http\Requests\TaskRequest;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Task::query();
        
        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('what', 'like', '%' . $search . '%')
                  ->orWhere('source', 'like', '%' . $search . '%')
                  ->orWhere('action', 'like', '%' . $search . '%')
                  ->orWhere('type', 'like', '%' . $search . '%')
                  ->orWhere('category', 'like', '%' . $search . '%')
                  ->orWhere('comments', 'like', '%' . $search . '%');
            });
        }
        
        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }
        
        // Apply priority filter
        if ($request->filled('priority')) {
            $query->where('priority', $request->get('priority'));
        }
        
        // Apply category filter
        if ($request->filled('category')) {
            $query->where('category', 'like', '%' . $request->get('category') . '%');
        }
        
        $perPage = $request->get('per_page', 10);
        $tasks = $query->orderBy('created_at', 'desc')->paginate($perPage);
        
        if ($request->ajax()) {
            return response()->json([
                'html' => view('tasks::partials.tasks-table', compact('tasks'))->render(),
                'pagination' => $tasks->links()->render()
            ]);
        }
        
        return view('tasks::index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tasks::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        $task = Task::create($request->validated());
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Task created successfully!',
                'task' => $task
            ]);
        }
        
        return redirect()->route('tasks.index')
                        ->with('success', 'Task created successfully!');
    }

    /**
     * Show the specified resource.
     */
    public function show(Task $task)
    {
        return view('tasks::show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        return view('tasks::edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, Task $task)
    {
        $task->update($request->validated());
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Task updated successfully!',
                'task' => $task
            ]);
        }
        
        return redirect()->route('tasks.index')
                        ->with('success', 'Task updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Task deleted successfully!'
            ]);
        }
        
        return redirect()->route('tasks.index')
                        ->with('success', 'Task deleted successfully!');
    }
}
