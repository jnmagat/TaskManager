<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Task;
use App\Models\Status;
use Illuminate\Support\Facades\Auth;

/**
 * Class DashboardController
 *
 * Gathers key metrics for the dashboard:
 *   - Total number of tasks
 *   - Task counts grouped by status
 *   - Count of tasks assigned to the authenticated user
 *   - Count of overdue tasks (not marked “Done”)
 *   - Count of tasks due today
 *   - Count of tasks completed this week
 */
class DashboardController extends Controller
{
    /**
     * Handle the incoming request to display dashboard metrics.
     *
     * @param  \Illuminate\Http\Request  $request  The incoming HTTP request
     * @return \Illuminate\View\View             The dashboard view with aggregated data
     */
    public function __invoke(Request $request): View
    {
        // Total number of tasks in the system
        $totalTasks = Task::count();

        // Load each status along with a count of its related tasks
        $statuses = Status::withCount('tasks')->get();

        // Number of tasks assigned to the currently authenticated user
        $myTasksCount = Auth::user()->assignedTasks()->count();

        // Count of tasks whose due_date is before today and whose status is not “Done”
        $overdueCount = Task::whereDate('due_date', '<', now()->toDateString())
            ->whereHas('status', fn($q) => $q->where('name', '!=', 'Done'))
            ->count();

        // Count of tasks that are due today (regardless of status)
        $dueTodayCount = Task::whereDate('due_date', now()->toDateString())->count();

        // Count of tasks marked “Done” whose updated_at timestamp falls within the current week
        $completedThisWeek = Task::completed()
            ->whereBetween('updated_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        return view('dashboard', compact(
            'totalTasks',
            'statuses',
            'myTasksCount',
            'overdueCount',
            'dueTodayCount',
            'completedThisWeek'
        ));
    }
}
