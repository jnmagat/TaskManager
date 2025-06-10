@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-4">
    {{-- Header: Page title + “Create Priority” button --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-100">Priorities</h1>
        <a href="{{ route('priorities.create') }}"
           class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 flex items-center">
            <i class="fas fa-plus mr-1"></i>
            Create Priority
        </a>
    </div>
    <hr>
    <br>

    <x-flash-banner />

    <x-filter-bar
        search-name="search"   
        search-placeholder="Search name"  
        :show-from="false"
        :show-to="false"
        clear-route="priorities.index"
    />

    {{-- If there are no priorities, show a placeholder message --}}
    @if($priorities->isEmpty())
        <p class="text-gray-400">No priorities found.</p>
    @else



        {{-- Priorities table --}}
        <table class="w-full bg-gray-900 text-gray-200 rounded">
            <thead>
                <tr class="bg-gray-800 text-left">
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Level</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($priorities as $priority)
                    <tr class="border-b border-gray-700 hover:bg-gray-800">
                        {{-- Priority name --}}
                        <td class="px-4 py-2">{{ $priority->name }}</td>

                        {{-- Priority level --}}
                        <td class="px-4 py-2">{{ $priority->level }}</td>

                        {{-- Action icons: edit, delete --}}
                        <td class="px-4 py-2 space-x-2 flex">
                            {{-- Edit --}}
                            <a href="{{ route('priorities.edit', $priority) }}" class="text-yellow-400 hover:text-yellow-300">
                                <i class="fas fa-edit"></i>
                            </a>
                            {{-- Delete (with confirmation) --}}
                            <form action="{{ route('priorities.destroy', $priority) }}" method="POST" class="inline" onsubmit="return confirm('Delete this priority?');">
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
          {{ $priorities->onEachSide(1)->links('components.pagination') }}
        </div>
    @endif
</div>
@endsection
