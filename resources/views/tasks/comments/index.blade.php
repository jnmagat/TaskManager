@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-4">
    {{-- Heading --}}
    <h1 class="text-2xl font-bold mb-4">Comments for Task: {{ $task->title }}</h1>

    {{-- Link to add a new comment --}}
    <a 
        href="{{ route('comments.create', $task) }}" 
        class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 mb-4 inline-block"
    >
        Add Comment
    </a>

    {{-- Success message --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    {{-- List of comments --}}
    @forelse ($comments as $comment)
        <div class="border p-4 mb-4 rounded shadow-sm">
            {{-- Comment text --}}
            <p>{{ $comment->comment }}</p>
            {{-- Metadata --}}
            <p class="text-sm text-gray-600 mt-2">
                By {{ $comment->user->name }} on {{ $comment->created_at->format('F j, Y, g:i a') }}
            </p>
            {{-- Actions --}}
            <div class="mt-2 space-x-2">
                <a 
                    href="{{ route('comments.edit', [$task, $comment]) }}" 
                    class="text-yellow-600 hover:underline"
                >
                    Edit
                </a>
                <form 
                    action="{{ route('comments.destroy', [$task, $comment]) }}" 
                    method="POST" 
                    class="inline" 
                    onsubmit="return confirm('Delete this comment?');"
                >
                    @csrf {{-- CSRF token --}}
                    @method('DELETE') {{-- Use DELETE --}}
                    <button type="submit" class="text-red-600 hover:underline">Delete</button>
                </form>
            </div>
        </div>
    @empty
        {{-- No comments found --}}
        <p>No comments found.</p>
    @endforelse

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $comments->links() }}
    </div>
</div>
@endsection