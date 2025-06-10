@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto p-4 bg-white rounded shadow">
    {{-- Heading --}}
    <h1 class="text-xl font-bold mb-4">Add Comment to Task: {{ $task->title }}</h1>

    {{-- Validation errors --}}
    @if ($errors->any())
        <div class="mb-4 text-red-600">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li> {{-- Show each error --}}
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form to store new comment --}}
    <form action="{{ route('comments.store', $task) }}" method="POST">
        @csrf {{-- CSRF token --}}
        <label class="block mb-2">Comment</label>
        <textarea
            name="comment"
            rows="4"
            class="w-full border px-3 py-2 mb-4 rounded"
            required
        >{{ old('comment') }}</textarea>

        {{-- Submit button --}}
        <button
            type="submit"
            class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700"
        >
            Add Comment
        </button>
    </form>
</div>
@endsection