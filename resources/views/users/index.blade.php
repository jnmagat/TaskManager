{{-- resources/views/users/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-4">
    {{-- Header: Page title + “New User” button --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-100">Users</h1>
        <a href="{{ route('users.create') }}"
           class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 flex items-center">
            <i class="fas fa-plus mr-1"></i>
            New User
        </a>
    </div>
    <hr>
    <br>

    <x-flash-banner />

    <x-filter-bar 
        search-placeholder="Search name or Email"  
        clear-route="users.index" 
        />

    {{-- If there are no users, show a placeholder message --}}
    @if($users->isEmpty())
        <p class="text-gray-400">No users found.</p>
    @else
    
        {{-- Users table --}}
        <table class="w-full bg-gray-900 text-gray-200 rounded">
            <thead>
                <tr class="bg-gray-800 text-left">
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr class="border-b border-gray-700 hover:bg-gray-800">

                        {{-- User name --}}
                        <td class="px-4 py-2">{{ $user->name }}</td>

                        {{-- User email --}}
                        <td class="px-4 py-2">{{ $user->email }}</td>

                        {{-- Action icons: show, edit, delete --}}
                        <td class="px-4 py-2 space-x-2 flex">
                            {{-- View (show) --}}
                            <a href="{{ route('users.show', $user) }}" class="text-indigo-400 hover:text-indigo-300">
                                <i class="fas fa-eye"></i>
                            </a>

                            {{-- Edit --}}
                            <a href="{{ route('users.edit', $user) }}" class="text-yellow-400 hover:text-yellow-300">
                                <i class="fas fa-edit"></i>
                            </a>

                            {{-- Delete (with confirmation) --}}
                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Delete this user?');">
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
          {{ $users->onEachSide(1)->links('components.pagination') }}
        </div>
    @endif
</div>
@endsection
