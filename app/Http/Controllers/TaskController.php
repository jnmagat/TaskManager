<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\QueryException;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Models\Category;
use App\Models\Priority;
use App\Models\Status;
use App\Models\User;

/**
 * Class TaskController
 *
 * Handles CRUD operations for Task model:
 *   - Listing tasks
 *   - Showing forms to create/edit tasks
 *   - Storing, updating, and deleting tasks
 *   - Displaying a single task
 */
class TaskController extends Controller
{
    /**
     * Display a paginated list of tasks, eager-loading related models.
     *
     * @param  void
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $tasks = Task::with(['category','priority','status','assignees'])
            // ▼ NEW: search by title only
            ->when($request->filled('title'), fn ($q) =>
                $q->where('title', 'like', '%' . $request->title . '%')
            )

            // ▼ keep the other dropdown filters as before
            ->when($request->filled('category'), fn ($q) =>
                $q->where('category_id', $request->category)
            )
            ->when($request->filled('priority'), fn ($q) =>
                $q->where('priority_id', $request->priority)
            )
            ->when($request->filled('status'), fn ($q) =>
                $q->where('status_id', $request->status)
            )
            ->when($request->filled('assignees'), fn ($q) =>
                $q->whereHas('assignees', fn ($q2) =>
                    $q2->where('users.id', $request->assignees)
                )
            )
            ->when($request->filled('due_date'), fn ($q) =>
                $q->whereDate('due_date', $request->due_date)
            )
            ->paginate(10)
            ->appends($request->except('page'));   // keep ?title=… on page links

        // dropdown options…
        $categories = Category::orderBy('name')->get();
        $priorities = Priority::orderBy('level')->get();
        $statuses   = Status::orderBy('name')->get();
        $users      = User::orderBy('name')->get();

        return view('tasks.index', compact(
            'tasks','categories','priorities','statuses','users'
        ));
    }

    /**
     * Show the form for creating a new task.
     *
     * @param  void
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        $priorities = Priority::orderBy('name')->get();
        $statuses   = Status::orderBy('name')->get();
        $users      = User::orderBy('name')->get();

        return view('tasks.create', compact('categories', 'priorities', 'statuses', 'users'));
    }

    /**
     * Persist a newly created task in storage.
     *
     * @param  \App\Http\Requests\StoreTaskRequest  $request  Validated request data
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        try {
            $task = Task::create([
                'title'        => $validated['title'],
                'description'  => $validated['description'] ?? null,
                'category_id'  => $validated['category_id'],
                'priority_id'  => $validated['priority_id'],
                'status_id'    => $validated['status_id'],
                'due_date'     => $validated['due_date'] ?? null,
            ]);

            $task->assignees()->sync($validated['assignees'] ?? []);

            return redirect()
                ->route('tasks.index')
                ->with('success', 'Task created successfully.');
        } catch (QueryException $e) {
            \Log::error('Task store DB error: ' . $e->getMessage());

            return back()
                ->withErrors(['title' => 'Failed to create task.'])
                ->onlyInput('title');
        }
    }

    /**
     * Show the form for editing an existing task.
     *
     * @param  \App\Models\Task  $task  The task to edit (route-model binding)
     * @return \Illuminate\View\View
     */
    public function edit(Task $task): View
    {
        $categories  = Category::orderBy('name')->get();
        $priorities  = Priority::orderBy('name')->get();
        $statuses    = Status::orderBy('name')->get();
        $users       = User::orderBy('name')->get();
        $assignedIds = $task->assignees->pluck('id')->toArray();

        return view('tasks.edit', compact(
            'task',
            'categories',
            'priorities',
            'statuses',
            'users',
            'assignedIds'
        ));
    }

    /**
     * Update the specified task in storage.
     *
     * @param  \App\Http\Requests\UpdateTaskRequest  $request  Validated request data
     * @param  \App\Models\Task                      $task     The task to update (route-model binding)
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        $validated = $request->validated();

        try {
            $task->update([
                'title'        => $validated['title'],
                'description'  => $validated['description'] ?? null,
                'category_id'  => $validated['category_id'],
                'priority_id'  => $validated['priority_id'],
                'status_id'    => $validated['status_id'],
                'due_date'     => $validated['due_date'] ?? null,
            ]);

            $task->assignees()->sync($validated['assignees'] ?? []);

            return redirect()
                ->route('tasks.index')
                ->with('success', 'Task updated successfully.');
        } catch (QueryException $e) {
            \Log::error('Task update DB error: ' . $e->getMessage());

            return back()
                ->withErrors(['title' => 'Failed to update task.'])
                ->onlyInput('title');
        }
    }

    /**
     * Remove the specified task from storage.
     *
     * @param  \App\Models\Task  $task  The task to delete (route-model binding)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Task $task): RedirectResponse
    {
        try {
            $task->delete();

            return redirect()
                ->route('tasks.index')
                ->with('success', 'Task deleted successfully.');
        } catch (\Throwable $e) {
            \Log::error('Task delete error: ' . $e->getMessage());

            return redirect()
                ->route('tasks.index')
                ->withErrors(['delete' => 'Could not delete task.']);
        }
    }

    /**
     * Display the specified task along with related models and comments.
     *
     * @param  \App\Models\Task  $task  The task to display (route-model binding)
     * @return \Illuminate\View\View
     */
    public function show(Task $task): View
    {
        $task->load(['category', 'priority', 'status', 'assignees', 'comments.user']);
        return view('tasks.show', compact('task'));
    }
}
