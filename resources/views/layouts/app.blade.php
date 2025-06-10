<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'My App')</title>
    {{-- Load compiled Tailwind CSS via Vite --}}
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-900 min-h-screen flex text-gray-300">

    {{-- Sidebar --}}
    <aside class="w-64 bg-gray-800 shadow h-screen sticky top-0 flex flex-col p-6">
        {{-- Logo / Home link --}}
        <a href="{{ url('/') }}" class="font-bold text-2xl text-indigo-400 mb-8 hover:text-indigo-300">
            TaskManager
        </a>

        {{-- Navigation links --}}
        <nav class="flex flex-col space-y-4 flex-grow">
            {{-- Link to Tasks index --}}
            <a href="{{ route('tasks.index') }}" class="hover:text-indigo-400 transition">
                Tasks
            </a>
            {{-- Link to Categories index --}}
            <a href="{{ route('categories.index') }}" class="hover:text-indigo-400 transition">
                Categories
            </a>
            {{-- Link to Priorities index --}}
            <a href="{{ route('priorities.index') }}" class="hover:text-indigo-400 transition">
                Priorities
            </a>
            {{-- Link to Statuses index --}}
            <a href="{{ route('statuses.index') }}" class="hover:text-indigo-400 transition">
                Status
            </a>
            {{-- Link to Users index --}}
            <a href="{{ route('users.index') }}" class="hover:text-indigo-400 transition">
                Users
            </a>
        </nav>

        {{-- Logout form --}}
        <form method="POST" action="{{ route('logout') }}" class="mt-auto">
            @csrf {{-- CSRF protection --}}
            <button 
                type="submit" 
                class="w-full text-left text-gray-300 hover:text-indigo-400 transition focus:outline-none"
            >
                Logout
            </button>
        </form>
    </aside>

    {{-- Main content area; child views will be injected here --}}
    <main class="flex-1 p-6 overflow-auto bg-gray-900">
        @yield('content')
    </main>

</body>
</html>