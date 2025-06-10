@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto p-6 bg-gray-900 rounded shadow-lg text-gray-200">

    {{-- Back to List --}}
    <div class="mb-4">
        <a href="{{ route('users.index') }}" class="text-gray-400 hover:text-gray-200 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to List
        </a>
    </div>

    <h1 class="text-2xl font-bold mb-6 text-indigo-400">User Details</h1>

    <div class="space-y-4">
        <p><span class="text-gray-400">ID:</span> {{ $user->id }}</p>
        <p><span class="text-gray-400">Name:</span> {{ $user->name }}</p>
        <p><span class="text-gray-400">Email:</span> {{ $user->email }}</p>
        <p><span class="text-gray-400">Created At:</span> {{ $user->created_at->format('F j, Y, g:i a') }}</p>
        <p><span class="text-gray-400">Updated At:</span> {{ $user->updated_at->format('F j, Y, g:i a') }}</p>
    </div>
</div>
@endsection
