@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto p-6 bg-gray-900 rounded shadow-lg text-gray-200">

    {{-- Back to Task List --}}
    <div class="mb-4">
        <a href="{{ route('tasks.index') }}" class="text-gray-400 hover:text-gray-200 flex items-center">
            <i class="fas fa-arrow-left mr-1"></i> Back to Tasks
        </a>
    </div>

    <h1 class="text-2xl font-bold mb-6 text-indigo-400">Edit Task</h1>

    {{-- Show errors --}}
    @if ($errors->any())
        <div class="mb-6 bg-red-800 border border-red-700 rounded p-4 text-red-400">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tasks.update', $task) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Title --}}
        <label class="block mb-2 text-gray-300">Title</label>
        <input type="text"
               name="title"
               value="{{ old('title', $task->title) }}"
               class="w-full bg-gray-800 border border-gray-700 px-3 py-2 mb-4 rounded text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
               required>

        {{-- Description --}}
        <label class="block mb-2 text-gray-300">Description</label>
        <textarea name="description"
                  class="w-full bg-gray-800 border border-gray-700 px-3 py-2 mb-4 rounded text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                  rows="4">{{ old('description', $task->description) }}</textarea>

        {{-- Category --}}
        <label class="block mb-2 text-gray-300">Category</label>
        <select name="category_id"
                class="w-full bg-gray-800 border border-gray-700 px-3 py-2 mb-4 rounded text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                required>
            <option value="" class="text-gray-400">Select Category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                        {{ old('category_id', $task->category_id) == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        {{-- Priority --}}
        <label class="block mb-2 text-gray-300">Priority</label>
        <select name="priority_id"
                class="w-full bg-gray-800 border border-gray-700 px-3 py-2 mb-4 rounded text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                required>
            <option value="" class="text-gray-400">Select Priority</option>
            @foreach ($priorities as $priority)
                <option value="{{ $priority->id }}"
                        {{ old('priority_id', $task->priority_id) == $priority->id ? 'selected' : '' }}>
                    {{ $priority->name }}
                </option>
            @endforeach
        </select>

        {{-- Status --}}
        <label class="block mb-2 text-gray-300">Status</label>
        <select name="status_id"
                class="w-full bg-gray-800 border border-gray-700 px-3 py-2 mb-4 rounded text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                required>
            <option value="" class="text-gray-400">Select Status</option>
            @foreach ($statuses as $status)
                <option value="{{ $status->id }}"
                        {{ old('status_id', $task->status_id) == $status->id ? 'selected' : '' }}>
                    {{ $status->name }}
                </option>
            @endforeach
        </select>

        {{-- Due Date --}}
        <label for="due_date" class="block mb-2 text-gray-300">Due Date</label>
        <input type="date"
               name="due_date"
               id="due_date"
               value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}"
               class="w-full bg-gray-800 border border-gray-700 px-3 py-2 mb-4 rounded text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500">

        {{-- Assignees (Multi-select) --}}
        <label class="block mb-2 text-gray-300">Assign Users</label>
        <select name="assignees[]"
                id="assignees"
                multiple
                size="5"
                class="w-full bg-gray-800 border border-gray-700 px-3 py-2 mb-6 rounded text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('assignees') border-red-500 @enderror">
            @foreach ($users as $user)
                <option value="{{ $user->id }}"
                        {{ in_array($user->id, old('assignees', $assignedIds)) ? 'selected' : '' }}>
                    {{ $user->name }}
                </option>
            @endforeach
        </select>
        @error('assignees')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror

        {{-- Submit --}}
        <button type="submit"
                class="w-full bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">
            Update
        </button>
    </form>
</div>
@endsection
