<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\QueryException;
use App\Models\Status;

/**
 * Class StatusController
 *
 * Handles CRUD operations for the Status model:
 *   - Listing statuses
 *   - Showing forms to create/edit statuses
 *   - Storing, updating, and deleting statuses
 */
class StatusController extends Controller
{
    /**
     * Display a paginated list of all statuses.
     *
     * @param  void
     * @return \Illuminate\View\View
     */
    public function index(Request $request): View
    {
        $term = trim((string) $request->input('search', ''));

        $statuses = Status::query()
            ->when($term !== '', fn ($q) =>
                $q->where('name', 'like', "%{$term}%")
            )
            ->orderBy('name')
            ->paginate(10)
            ->appends($request->only('search'));

        return view('statuses.index', compact('statuses'));
    }
    /**
     * Show the form for creating a new status.
     *
     * @param  void
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        return view('statuses.create');
    }

    /**
     * Persist a newly created status in storage.
     *
     * @param  \Illuminate\Http\Request  $request  Incoming HTTP request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:statuses,name',
        ]);

        try {
            Status::create($request->only('name'));

            return redirect()
                ->route('statuses.index')
                ->with('success', 'Status created successfully.');
        } catch (QueryException $e) {
            \Log::error('Status store DB error: ' . $e->getMessage());

            return back()
                ->withErrors(['name' => 'Failed to create status.'])
                ->onlyInput('name');
        }
    }

    /**
     * Show the form for editing the specified status.
     *
     * @param  \App\Models\Status  $status  The status instance (route-model binding)
     * @return \Illuminate\View\View
     */
    public function edit(Status $status): View
    {
        return view('statuses.edit', compact('status'));
    }

    /**
     * Update the specified status in storage.
     *
     * @param  \Illuminate\Http\Request  $request  Incoming HTTP request
     * @param  \App\Models\Status        $status   The status instance (route-model binding)
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Status $status): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:50',
        ]);

        try {
            $status->update($request->only('name'));

            return redirect()
                ->route('statuses.index')
                ->with('success', 'Status updated successfully.');
        } catch (QueryException $e) {
            \Log::error('Status update DB error: ' . $e->getMessage());

            return back()
                ->withErrors(['name' => 'Failed to update status.'])
                ->onlyInput('name');
        }
    }

    /**
     * Remove the specified status from storage.
     *
     * @param  \App\Models\Status  $status  The status instance (route-model binding)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Status $status): RedirectResponse
    {
        try {
            $status->delete();

            return redirect()
                ->route('statuses.index')
                ->with('success', 'Status deleted successfully.');
        } catch (\Throwable $e) {
            \Log::error('Status delete error: ' . $e->getMessage());

            return redirect()
                ->route('statuses.index')
                ->withErrors(['delete' => 'Could not delete status because it is assigned to one or more tasks.']);
        }
    }
}
