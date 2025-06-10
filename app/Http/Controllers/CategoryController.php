<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Models\Category;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Class CategoryController
 *
 * Handles CRUD operations for Category model:
 *   - Listing categories
 *   - Showing forms to create/edit
 *   - Storing, updating, and deleting categories
 */
class CategoryController extends Controller
{
    /**
     * Display a paginated list of categories, ordered by name.
     *
     * @param  void
     * @return \Illuminate\View\View
     */
    public function index(Request $request): View
    {
        $term = trim((string) $request->input('search', ''));

        $categories = Category::query()
            ->when($term !== '', fn ($q) =>
                $q->where('name', 'like', "%{$term}%")
            )
            ->orderBy('name')
            ->paginate(10)
            ->appends($request->only('search'));

        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     *
     * @param  void
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        return view('categories.create');
    }

    /**
     * Persist a newly created category in storage.
     *
     * @param  \Illuminate\Http\Request  $request  Incoming HTTP request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
        ]);

        try {
            Category::create($request->only('name'));

            return redirect()
                ->route('categories.index')
                ->with('success', 'Category created successfully.');
        } catch (QueryException $e) {
            \Log::error('Category store DB error: ' . $e->getMessage());

            return back()
                ->withErrors(['name' => 'Failed to create category. Try a different name.'])
                ->onlyInput('name');
        }
    }

    /**
     * Show the form for editing the specified category.
     *
     * @param  \App\Models\Category  $category  The category instance (route-model binding)
     * @return \Illuminate\View\View
     */
    public function edit(Category $category): View
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified category in storage.
     *
     * @param  \Illuminate\Http\Request  $request   Incoming HTTP request
     * @param  \App\Models\Category      $category  The category instance (route-model binding)
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $category->id,
        ]);

        try {
            $category->update($request->only('name'));

            return redirect()
                ->route('categories.index')
                ->with('success', 'Category updated successfully.');
        } catch (QueryException $e) {
            \Log::error('Category update DB error: ' . $e->getMessage());

            return back()
                ->withErrors(['name' => 'Failed to update category. Try a different name.'])
                ->onlyInput('name');
        }
    }

    /**
     * Remove the specified category from storage.
     * Refuses deletion if any tasks are assigned to this category.
     *
     * @param  \App\Models\Category  $category  The category instance (route-model binding)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Category $category): RedirectResponse
    {
        // 1) If any tasks link to this category, refuse deletion:
        if ($category->tasks()->exists()) {
            return redirect()
                ->route('categories.index')
                ->withErrors([
                    'delete' => 'Cannot delete this category because it is assigned to one or more tasks.'
                ]);
        }

        // 2) Otherwise attempt to delete
        try {
            $category->delete();

            return redirect()
                ->route('categories.index')
                ->with('success', 'Category deleted successfully.');
        } catch (\Throwable $e) {
            \Log::error('Category delete error: ' . $e->getMessage());

            return redirect()
                ->route('categories.index')
                ->withErrors(['delete' => 'Could not delete category.']);
        }
    }
}
