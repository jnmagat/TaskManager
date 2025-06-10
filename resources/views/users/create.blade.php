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

    <h1 class="text-2xl font-bold mb-6 text-indigo-400">Create User</h1>

    @if ($errors->any())
    <div class="mb-6 bg-red-800 border border-red-700 rounded p-4 text-red-400">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('users.store') }}" method="POST">
        @csrf

        <label class="block mb-2 text-gray-300">Name</label>
        <input
            type="text"
            name="name"
            value="{{ old('name') }}"
            class="w-full bg-gray-800 border border-gray-700 px-3 py-2 mb-4 rounded text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
            required
        >

        <label class="block mb-2 text-gray-300">Email</label>
        <input
            type="email"
            name="email"
            value="{{ old('email') }}"
            class="w-full bg-gray-800 border border-gray-700 px-3 py-2 mb-4 rounded text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
            required
        >

        {{-- Password with eye-toggle --}}
        <label class="block mb-2 text-gray-300">Password</label>
        <div class="relative mb-4">
            <input
                id="password"
                type="password"
                name="password"
                class="w-full bg-gray-800 border border-gray-700 px-3 py-2 rounded text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                required
            >
            <button
                        type="button"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-600 focus:outline-none"
                        onclick="toggleVisibility('password', this)"
                    >
                        <i class="fas fa-eye-slash eye-icon"></i>
                        <i class="fas fa-eye eye-off-icon hidden"></i>
                    </button>
        </div>

        {{-- Confirm Password with eye-toggle --}}
        <label class="block mb-2 text-gray-300">Confirm Password</label>
        <div class="relative mb-6">
            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                class="w-full bg-gray-800 border border-gray-700 px-3 py-2 rounded text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                required
            >
            <button
                        type="button"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-600 focus:outline-none"
                        onclick="toggleVisibility('password_confirmation', this)"
                    >
                        <i class="fas fa-eye-slash eye-icon"></i>
                        <i class="fas fa-eye eye-off-icon hidden"></i>
                    </button>
        </div>

        <button
            type="submit"
            class="w-full bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition"
        >
            Create
        </button>
    </form>
</div>

@vite('resources/js/togglePassword.js') 
@endsection
