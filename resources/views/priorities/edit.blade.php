@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto p-6 bg-gray-900 rounded shadow-lg text-gray-200">
    
    {{-- Link back to the priority list --}}
    <div class="mb-4">
        <a href="{{ route('priorities.index') }}" class="text-gray-400 hover:text-gray-200 flex items-center">
            <i class="fas fa-arrow-left mr-1"></i>
            Back to List
        </a>
    </div>

    {{-- Page heading --}}
    <h1 class="text-2xl font-bold mb-6 text-indigo-400">Edit Priority</h1>

    {{-- Validation error messages --}}
    @if ($errors->any())
        <div class="mb-6 bg-red-800 border border-red-700 rounded p-4 text-red-400">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li> {{-- Show each validation error --}}
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form to update existing priority --}}
    <form action="{{ route('priorities.update', $priority) }}" method="POST">
        @csrf {{-- CSRF token --}}
        @method('PUT') {{-- Spoof PUT method --}}

        {{-- Priority name input --}}
        <label class="block mb-2 text-gray-300">Name</label>
        <input
            type="text"
            name="name"
            value="{{ old('name', $priority->name) }}"
            class="w-full bg-gray-800 border border-gray-700 px-3 py-2 mb-4 rounded text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
            required
            maxlength="50"
        >

        {{-- Priority level input --}}
        <label class="block mb-2 text-gray-300">Level</label>
        <input
            type="number"
            name="level"
            value="{{ old('level', $priority->level) }}"
            class="w-full bg-gray-800 border border-gray-700 px-3 py-2 mb-6 rounded text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
            required
            min="1"
        >

        {{-- Submit button --}}
        <button
            type="submit"
            class="w-full bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition"
        >
            Update
        </button>
        
    </form>
</div>
@endsection