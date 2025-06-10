<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use App\Models\User;
use App\Rules\StrongPassword;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Class AuthController
 *
 * Handles user authentication:
 *   - Displaying login and registration forms
 *   - Processing login and registration
 *   - Logging out
 */
class AuthController extends Controller
{
    /**
     * Show the login form.
     *
     * @param  void
     * @return \Illuminate\View\View
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Authenticate and log in the user.
     *
     * @param  \Illuminate\Http\Request  $request  Incoming HTTP request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        try {
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                return redirect()->intended('dashboard');
            }

            // Invalid credentials
            return back()
                ->withErrors(['email' => 'Invalid credentials or account does not exist.'])
                ->onlyInput('email');
        } catch (\Throwable $e) {
            \Log::error('Login error: ' . $e->getMessage());

            return back()
                ->withErrors(['email' => 'An unexpected error occurred. Please try again.'])
                ->onlyInput('email');
        }
    }

    /**
     * Log out the currently authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request  Incoming HTTP request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        try {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/login');
        } catch (\Throwable $e) {
            \Log::error('Logout error: ' . $e->getMessage());

            return redirect('/login')
                ->withErrors(['logout' => 'Could not log out. Please try again.']);
        }
    }

    /**
     * Show the registration form.
     *
     * @param  void
     * @return \Illuminate\View\View
     */
    public function showRegisterForm(): View
    {
        return view('auth.register');
    }

    /**
     * Process registration and create a new user.
     *
     * @param  \Illuminate\Http\Request  $request  Incoming HTTP request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed', new StrongPassword],
        ]);

        try {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);

            Auth::login($user);
            return redirect()->intended('dashboard');
        } catch (QueryException $e) {
            \Log::error('Registration DB error: ' . $e->getMessage());

            return back()
                ->withErrors(['email' => 'Registration failed. Email may already be taken.'])
                ->onlyInput('email');
        } catch (\Throwable $e) {
            \Log::error('Registration error: ' . $e->getMessage());

            return back()
                ->withErrors(['email' => 'An unexpected error occurred. Please try again.'])
                ->onlyInput('email');
        }
    }
}
