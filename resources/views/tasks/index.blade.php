@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-4">
    {{-- Header: Page title + “New Task” button --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-100">Tasks</h1>
        <a href="{{ route('tasks.create') }}"
           class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 flex items-center">
            <i class="fas fa-plus mr-1"></i>
            New Task
        </a>
    </div>
    <hr>
    <br>

    <x-flash-banner />
    
    <x-filter-bar
        search-name="title"   
        search-placeholder="Search title…"  
        :category="$categories"
        :priority="$priorities"
        :status="$statuses"
        :assignees="$users"
        :show-due-date="true"
        clear-route="tasks.index"
    />

    {{-- If there are no tasks, show a placeholder message --}}
    @if($tasks->isEmpty())
        <p class="text-gray-400">No tasks found.</p>
    @else
        {{-- Tasks table --}}
        <table class="w-full bg-gray-900 text-gray-200 rounded">
            <thead>
                <tr class="bg-gray-800 text-left">
                    <th class="px-4 py-2">Title</th>
                    <th class="px-4 py-2">Category</th>
                    <th class="px-4 py-2">Priority</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Assignees</th>
                    <th class="px-4 py-2">Due Date</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tasks as $task)
                    <tr class="border-b border-gray-700 hover:bg-gray-800">
                        {{-- Task title --}}
                        <td class="px-4 py-2">{{ $task->title }}</td>

                        {{-- Related category, priority, status --}}
                        <td class="px-4 py-2">{{ $task->category->name }}</td>
                        <td class="px-4 py-2">{{ $task->priority->name }}</td>
                        <td class="px-4 py-2">{{ $task->status->name }}</td>

                        {{-- List of assigned users (or “No one” if none) --}}
                        <td class="px-4 py-2">
                            @forelse($task->assignees as $user)
                                <span class="inline-block bg-indigo-600 text-white px-2 py-1 rounded text-xs mr-1">
                                    {{ $user->name }}
                                </span>
                            @empty
                                <span class="text-gray-400 text-sm">No one</span>
                            @endforelse
                        </td>

                        {{-- Due date (formatted) --}}
                        <td class="px-4 py-2">{{ $task->due_date?->format('Y-m-d') ?? '-' }}</td>

                        {{-- Action icons: show, edit, delete --}}
                        <td class="px-4 py-2 space-x-2 flex">
                            {{-- View (show) --}}
                            <a href="{{ route('tasks.show', $task) }}" class="text-indigo-400 hover:text-indigo-300">
                                <i class="fas fa-eye"></i>
                            </a>

                            {{-- Edit --}}
                            <a href="{{ route('tasks.edit', $task) }}" class="text-yellow-400 hover:text-yellow-300">
                                <i class="fas fa-edit"></i>
                            </a>

                            {{-- Delete (with confirmation) --}}
                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline" onsubmit="return confirm('Delete this task?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-400">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Pagination links --}}
        <div class="mt-4">
            {{ $tasks->links() }}
        </div>
    @endif
</div>
@endsection
