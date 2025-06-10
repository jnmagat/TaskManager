@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto p-6 bg-gray-900 rounded shadow-lg text-gray-200">

    <h1 class="text-2xl font-bold mb-6 text-indigo-400">Create Task</h1>
    
    {{-- Show error if present --}}
    @if ($errors->any())
        <div class="mb-6 bg-red-800 border border-red-700 rounded p-4 text-red-400">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tasks.store') }}" method="POST">
        @csrf {{-- CSRF protection --}}

        {{-- Title --}}
        <label class="block mb-2 text-gray-300">Title</label>
        <input type="text"
               name="title"
               value="{{ old('title') }}"
               class="w-full bg-gray-800 border border-gray-700 px-3 py-2 mb-4 rounded text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
               required>

        {{-- Description --}}
        <label class="block mb-2 text-gray-300">Description</label>
        <textarea name="description"
                  class="w-full bg-gray-800 border border-gray-700 px-3 py-2 mb-4 rounded text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                  rows="4">{{ old('description') }}</textarea>

        {{-- Category --}}
        <label class="block mb-2 text-gray-300">Category</label>
        <select name="category_id"
                class="w-full bg-gray-800 border border-gray-700 px-3 py-2 mb-4 rounded text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                required>
            <option value="" class="text-gray-400">Select Category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                        {{ old('priority_id') == $priority->id ? 'selected' : '' }}>
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
                        {{ old('status_id') == $status->id ? 'selected' : '' }}>
                    {{ $status->name }}
                </option>
            @endforeach
        </select>

        {{-- Due Date --}}
        <label for="due_date" class="block mb-2 text-gray-300">Due Date</label>
        <input type="date"
               name="due_date"
               id="due_date"
               value="{{ old('due_date') }}"
               class="w-full bg-gray-800 border border-gray-700 px-3 py-2 mb-4 rounded text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500">

        {{-- Assignees (Multi‐Select) --}}
        <label class="block mb-2 text-gray-300">Assign Users</label>
        <select name="assignees[]"
                id="assignees"
                multiple
                size="5"
                class="w-full bg-gray-800 border border-gray-700 px-3 py-2 mb-6 rounded text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('assignees') border-red-500 @enderror">
            @foreach ($users as $user)
                <option value="{{ $user->id }}"
                        {{ in_array($user->id, old('assignees', [])) ? 'selected' : '' }}>
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
            Create
        </button>
    </form>
</div>
@endsection
