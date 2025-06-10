<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    @vite('resources/css/app.css') {{-- Load Tailwind CSS --}}
</head>
<body class="bg-gray-900 flex items-center justify-center min-h-screen">

    {{-- Centered container --}}
    <div class="w-full max-w-md p-8 bg-gray-800 shadow-lg rounded-lg text-gray-200">
        <h1 class="text-2xl font-bold text-center mb-6 text-indigo-400">Login</h1>

        {{-- Show first error if validation or login fails --}}
        @if($errors->any())
            <div class="mb-4 text-sm bg-red-800 text-red-200 border border-red-700 p-3 rounded">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ url('login') }}" class="space-y-4">
            @csrf {{-- CSRF protection --}}

            {{-- Email field --}}
            <div>
                <label class="block text-gray-300 mb-1">Email</label>
                <input
                    type="email"
                    name="email"
                    required
                    autofocus
                    class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
            </div>

            {{-- Password field with eye toggle --}}
            <div>
                <label for="password" class="block text-gray-300 mb-1">Password</label>
                <div class="relative">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    >
                    <button
                        type="button"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-200 focus:outline-none"
                        onclick="toggleVisibility('password', this)"
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
                    Login
                </button>
            </div>
        </form>

        {{-- Link to registration --}}
        <div class="mt-6 text-center">
            <p class="text-gray-400">Don't have an account?</p>
            <a href="{{ url('register') }}"
               class="inline-block mt-2 px-6 py-2 border border-indigo-500 text-indigo-500 rounded hover:bg-indigo-500 hover:text-white transition"
            >
                Register
            </a>
        </div>
    </div>

    @vite('resources/js/togglePassword.js')
</body>
</html>
