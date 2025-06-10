@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-4">

    {{-- header --}}
    <div class="flex items-center mb-6">
        <a href="{{ route('tasks.index') }}" class="text-gray-400 hover:text-gray-200 flex items-center">
            <i class="fas fa-arrow-left mr-1"></i> Back to Tasks
        </a>

        @can('update', $task)
            <a href="{{ route('tasks.edit', $task) }}"
               class="ml-auto bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 flex items-center">
                <i class="fas fa-edit mr-1"></i> Edit Task
            </a>
        @endcan
    </div>

    @if(session('success'))
        <div class="mb-6 p-3 bg-green-600 text-white rounded">{{ session('success') }}</div>
    @endif

    {{-- ---------- GRID ---------- --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        {{-- details (still 2 / 3 width on md+) --}}
        <div class="md:col-span-2 bg-gray-900 p-6 rounded border border-gray-700">
            <h1 class="text-2xl font-bold mb-6">{{ $task->title }}</h1>

            <div class="space-y-2">
                <p><span class="font-semibold">Category:</span> {{ $task->category->name ?? '-' }}</p>
                <p><span class="font-semibold">Priority:</span> {{ $task->priority->name ?? '-' }}</p>
                <p><span class="font-semibold">Status:</span> {{ $task->status->name ?? '-' }}</p>
                <p><span class="font-semibold">Due Date:</span> {{ optional($task->due_date)->format('Y-m-d') ?? '-' }}</p>
            </div>

            @if($task->description)
                <div class="mt-6 bg-gray-800 p-4 rounded border border-gray-700">
                    {!! nl2br(e($task->description)) !!}
                </div>
            @endif
        </div>

        {{-- ↓↓↓  Make comments span the full row  ↓↓↓ --}}
        <div class="md:col-span-3">
            <h2 class="text-xl font-semibold mb-4">Comments</h2>

            {{-- new-comment form --}}
            <form method="POST" action="{{ route('tasks.comments.store', $task) }}" class="mb-6">
                @csrf
                <textarea name="comment" rows="3"
                          class="w-full p-2 rounded bg-gray-800 border border-gray-600 text-gray-200 @error('comment') border-red-500 @enderror"
                          placeholder="Add your comment…">{{ old('comment') }}</textarea>
                @error('comment') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror

                <button class="mt-2 bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 flex items-center">
                    <i class="fas fa-paper-plane mr-1"></i> Submit
                </button>
            </form>

            {{-- list comments --}}
            @forelse ($task->comments as $comment)
                <div class="border border-gray-700 p-4 mb-4 rounded bg-gray-800">
                    <p>{{ $comment->comment }}</p>
                    <p class="text-sm text-gray-400 mt-1">
                        {{ $comment->user->name }} • {{ $comment->created_at->format('M d, Y g:i a') }}
                    </p>

                    <div class="mt-2 flex space-x-3">
                        @can('update', $comment)
                            <a href="{{ route('tasks.comments.edit', [$task, $comment]) }}"
                               class="text-yellow-400 text-sm flex items-center">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                        @endcan
                        @can('delete', $comment)
                            <form action="{{ route('tasks.comments.destroy', [$task, $comment]) }}"
                                  method="POST" class="inline flex items-center"
                                  onsubmit="return confirm('Delete this comment?');">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-500 text-sm flex items-center">
                                    <i class="fas fa-trash mr-1"></i> Delete
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            @empty
                <p class="text-gray-400">No comments yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
