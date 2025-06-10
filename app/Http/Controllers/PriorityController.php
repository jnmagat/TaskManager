<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\QueryException;
use App\Models\Priority;

/**
 * Class PriorityController
 *
 * Handles CRUD operations for Priority model:
 *   - Listing priorities
 *   - Showing forms to create/edit priorities
 *   - Storing, updating, and deleting priorities
 */
class PriorityController extends Controller
{
    /**
     * Display a paginated list of priorities, ordered by level.
     *
     * @param  void
     * @return \Illuminate\View\View
     */
    public function index(Request $request): View
    {
        $term  = trim((string) $request->input('search', ''));
        $level = $request->input('level');

        $priorities = Priority::query()
            ->when($term !== '',  fn ($q) =>
                $q->where('name', 'like', "%{$term}%")
            )
            ->when($level !== null && $level !== '', fn ($q) =>
                $q->where('level', $level)
            )
            ->orderBy('level')
            ->paginate(10)
            ->appends($request->only(['search', 'level']));

        $levels = Priority::orderBy('level')->pluck('level')->unique();

        return view('priorities.index', compact('priorities', 'levels'));
    }


    /**
     * Show the form for creating a new priority.
     *
     * @param  void
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        return view('priorities.create');
    }

    /**
     * Persist a newly created priority in storage.
     *
     * @param  \Illuminate\Http\Request  $request  Incoming HTTP request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'  => 'required|string|max:50|unique:priorities,name', 
            'level' => 'required|integer|min:1',
        ]);

        try {
            Priority::create($request->only('name', 'level'));

            return redirect()
                ->route('priorities.index')
                ->with('success', 'Priority created successfully.');
        } catch (QueryException $e) {
            \Log::error('Priority store DB error: ' . $e->getMessage());

            return back()
                ->withErrors(['name' => 'Failed to create priority.'])
                ->onlyInput('name');
        }
    }

    /**
     * Show the form for editing the specified priority.
     *
     * @param  \App\Models\Priority  $priority  The priority instance (route-model binding)
     * @return \Illuminate\View\View
     */
    public function edit(Priority $priority): View
    {
        return view('priorities.edit', compact('priority'));
    }

    /**
     * Update the specified priority in storage.
     *
     * @param  \Illuminate\Http\Request  $request   Incoming HTTP request
     * @param  \App\Models\Priority      $priority  The priority instance (route-model binding)
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Priority $priority): RedirectResponse
    {
        $request->validate([
            'name'  => 'required|string|max:50',
            'level' => 'required|integer|min:1',
        ]);

        try {
            $priority->update($request->only('name', 'level'));

            return redirect()
                ->route('priorities.index')
                ->with('success', 'Priority updated successfully.');
        } catch (QueryException $e) {
            \Log::error('Priority update DB error: ' . $e->getMessage());

            return back()
                ->withErrors(['name' => 'Failed to update priority.'])
                ->onlyInput('name');
        }
    }

    /**
     * Remove the specified priority from storage.
     *
     * @param  \App\Models\Priority  $priority  The priority instance (route-model binding)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Priority $priority): RedirectResponse
    {
        try {
            $priority->delete();

            return redirect()
                ->route('priorities.index')
                ->with('success', 'Priority deleted successfully.');
        } catch (\Throwable $e) {
            \Log::error('Priority delete error: ' . $e->getMessage());

            return redirect()
                ->route('priorities.index')
                ->withErrors(['delete' => 'Could not delete priority because it is assigned to one or more tasks.']);
        }
    }
}
