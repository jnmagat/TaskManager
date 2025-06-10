<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Register</title>
    @vite('resources/css/app.css') {{-- Load Tailwind CSS --}}
</head>
<body class="bg-gray-900 flex items-center justify-center min-h-screen">

    {{-- Centered container --}}
    <div class="w-full max-w-md p-8 bg-gray-800 shadow-lg rounded-lg text-gray-200">
        <h1 class="text-2xl font-bold text-center mb-6 text-indigo-400">Register</h1>

        {{-- List validation errors --}}
        @if($errors->any())
            <div class="mb-4 text-sm bg-red-800 text-red-200 border border-red-700 p-3 rounded overflow-hidden animate-fade-out-collapse">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ url('register') }}" class="space-y-6">
            @csrf {{-- CSRF protection --}}

            {{-- Name field --}}
            <div>
                <label for="name" class="block text-gray-300 mb-1">Name</label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    required
                    value="{{ old('name') }}"
                    class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                />
            </div>

            {{-- Email field --}}
            <div>
                <label for="email" class="block text-gray-300 mb-1">Email</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    required
                    value="{{ old('email') }}"
                    class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                />
            </div>

            {{-- Password field with tooltip --}}
            <div>
                <label for="password" class="block text-gray-300 mb-1 flex items-center justify-between">
                    Password
                    <div class="relative group">
                        <i class="fas fa-info-circle text-gray-500"></i>
                        {{-- Tooltip on hover --}}
                        <div class="absolute bottom-full mb-1 w-56 bg-gray-800 text-white text-xs rounded-lg p-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            8 Characters minimum<br>
                            1 uppercase letter<br>
                            1 number
                        </div>
                    </div>
                </label>
                <div class="relative">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    />
                    {{-- Toggle visibility --}}
                    <button
                        type="button"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-200 focus:outline-none"
                        onclick="toggleVisibility('password', this)"
                    >
                        {{-- Eye-off icon shown initially --}}
                        <i class="fas fa-eye-slash eye-icon"></i>
                        {{-- Eye icon hidden by default --}}
                        <i class="fas fa-eye eye-off-icon hidden"></i>
                    </button>
                </div>
            </div>

            {{-- Confirm password field --}}
            <div>
                <label for="password_confirmation" class="block text-gray-300 mb-1">Confirm Password</label>
                <div class="relative">
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        required
                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    />
                    {{-- Toggle visibility --}}
                    <button
                        type="button"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-200 focus:outline-none"
                        onclick="toggleVisibility('password_confirmation', this)"
                    >
                        <i class="fas fa-eye-slash eye-icon"></i>
                        <i class="fas fa-eye eye-off-icon hidden"></i>
                    </button>
                </div>
            </div>

            {{-- Submit button --}}
            <div>
                <button
                    type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded transition"
                >
                    Register
                </button>
            </div>
        </form>

        {{-- Link back to login --}}
        <div class="mt-6 text-center">
            <a href="{{ url('login') }}"
               class="text-indigo-400 hover:underline"
            >
                &larr; Back to Login
            </a>
        </div>
    </div>

    @vite('resources/js/togglePassword.js')
</body>
</html>
