@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-4 bg-gray-900 rounded border border-gray-700">
    {{-- Heading --}}
    <h2 class="text-2xl font-semibold mb-6">Edit Comment</h2>

    {{-- Validation errors --}}
    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-600 text-white rounded">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li> {{-- Show each error --}}
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form to update the comment --}}
    <form 
        method="POST" 
        action="{{ route('tasks.comments.update', [$task, $comment]) }}"
    >
        @csrf {{-- CSRF token --}}
        @method('PUT') {{-- Use PUT for update --}}

        {{-- Comment text field --}}
        <div class="mb-4">
            <label for="comment" class="block text-gray-200">Your Comment</label>
            <textarea
                name="comment"
                id="comment"
                rows="4"
                class="w-full p-2 rounded border border-gray-600 bg-gray-800 text-gray-200 @error('comment') border-red-500 @enderror"
            >{{ old('comment', $comment->comment) }}</textarea>

            @error('comment')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p> {{-- Show error --}}
            @enderror
        </div>

        {{-- Buttons --}}
        <div class="flex items-center gap-4">
            <button
                type="submit"
                class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700"
            >
                Update Comment
            </button>
            <a 
                href="{{ route('tasks.show', $task) }}" 
                class="text-gray-400 hover:text-gray-200"
            >
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection