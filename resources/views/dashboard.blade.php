@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-4 space-y-6">
    {{-- Page Title --}}
    <h1 class="text-3xl font-bold text-gray-100">Dashboard</h1>

    {{-- Statistic Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6">

        {{-- Total Tasks --}}
        <div class="bg-gray-800 p-6 rounded border border-gray-700 flex flex-col items-center">
            <h2 class="text-xl font-semibold text-gray-200 mb-2">Total Tasks</h2>
            <span class="text-4xl font-bold text-indigo-400">{{ $totalTasks }}</span>
        </div>

        {{-- My Assigned Tasks --}}
        <div class="bg-gray-800 p-6 rounded border border-gray-700 flex flex-col items-center">
            <h2 class="text-xl font-semibold text-gray-200 mb-2">My Tasks</h2>
            <span class="text-4xl font-bold text-indigo-400">{{ $myTasksCount }}</span>
        </div>

        {{-- Overdue Tasks --}}
        <div class="bg-gray-800 p-6 rounded border border-gray-700 flex flex-col items-center">
            <h2 class="text-xl font-semibold text-gray-200 mb-2">Overdue</h2>
            <span class="text-4xl font-bold text-red-500">{{ $overdueCount }}</span>
        </div>

        {{-- Due Today --}}
        <div class="bg-gray-800 p-6 rounded border border-gray-700 flex flex-col items-center">
            <h2 class="text-xl font-semibold text-gray-200 mb-2">Due Today</h2>
            <span class="text-4xl font-bold text-yellow-400">{{ $dueTodayCount }}</span>
        </div>

        {{-- Completed This Week --}}
        <div class="bg-gray-800 p-6 rounded border border-gray-700 flex flex-col items-center">
            <h2 class="text-xl font-semibold text-gray-200 mb-2">Completed This Week</h2>
            <span class="text-4xl font-bold text-green-400">{{ $completedThisWeek }}</span>
        </div>
    </div>

    {{-- Tasks by Status --}}
    <div class="mt-8 bg-gray-800 p-6 rounded border border-gray-700">
        <h2 class="text-2xl font-semibold text-gray-100 mb-4">Tasks by Status</h2>
        <div class="grid grid-cols-1 md:grid-cols-{{ $statuses->count() }} gap-4">
            @foreach($statuses as $status)
                <div class="bg-gray-700 p-4 rounded flex items-center justify-between">
                    <span class="text-gray-200 font-medium">{{ $status->name }}</span>
                    <span class="text-2xl font-bold text-indigo-300">{{ $status->tasks_count }}</span>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
