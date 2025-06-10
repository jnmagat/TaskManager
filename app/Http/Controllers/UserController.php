<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\QueryException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Rules\StrongPassword;

/**
 * Class UserController
 *
 * Handles CRUD operations for the User model:
 *   - Listing users
 *   - Showing forms to create/edit users
 *   - Storing, updating, and deleting users
 *   - Displaying a single user
 */
class UserController extends Controller
{
    /**
     * Display a paginated list of users.
     *
     * @param  void
     * @return \Illuminate\View\View
     */
    public function index(Request $request): View
    {
        $users = User::query()
            // Search by name OR email
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where(function ($sub) use ($request) {
                    $sub->where('name',  'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%');
                });
            })
            // Registered FROM (date >=)
            ->when($request->filled('registered_from'), function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->registered_from);
            })
            // Registered TO (date <=)
            ->when($request->filled('registered_to'), function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->registered_to);
            })
            ->orderByDesc('created_at')          // newest first
            ->paginate(10)
            ->appends($request->only([
                'search',
                'registered_from',
                'registered_to',
            ]));

        return view('users.index', compact('users'));
    }

    /**
     * Show the form to create a new user.
     *
     * @param  void
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        return view('users.create');
    }

    /**
     * Persist a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request  Incoming HTTP request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
                new StrongPassword,
            ],
        ]);

        try {
            User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return redirect()
                ->route('users.index')
                ->with('success', 'User created successfully.');
        } catch (QueryException $e) {
            \Log::error('User store DB error: ' . $e->getMessage());
            return back()
                ->withErrors(['email' => 'Failed to create user. Email may already exist.'])
                ->onlyInput('email');
        }
    }

    /**
     * Display a specific user’s details.
     *
     * @param  \App\Models\User  $user  The user instance (route-model binding)
     * @return \Illuminate\View\View
     */
    public function show(User $user): View
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form to edit an existing user.
     *
     * @param  \App\Models\User  $user  The user instance (route-model binding)
     * @return \Illuminate\View\View
     */
    public function edit(User $user): View
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request  Incoming HTTP request
     * @param  \App\Models\User          $user     The user to update (route-model binding)
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        try {
            $user->name  = $request->name;
            $user->email = $request->email;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            return redirect()
                ->route('users.index')
                ->with('success', 'User updated successfully.');
        } catch (QueryException $e) {
            \Log::error('User update DB error: ' . $e->getMessage());
            return back()
                ->withErrors(['email' => 'Failed to update user. Email may already exist.'])
                ->onlyInput('email');
        }
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  \App\Models\User  $user  The user to delete (route-model binding)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user): RedirectResponse
    {
        try {
            $user->delete();
            return redirect()
                ->route('users.index')
                ->with('success', 'User deleted successfully.');
        } catch (\Throwable $e) {
            \Log::error('User delete error: ' . $e->getMessage());
            return redirect()
                ->route('users.index')
                ->withErrors(['delete' => 'Could not delete user.']);
        }
    }
}
