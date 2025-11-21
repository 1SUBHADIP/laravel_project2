<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Helpers\AdminActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('books')->orderBy('name')->paginate(10);
        return view('categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
        ]);
        $category = Category::create($data);

        // Log admin activity
        AdminActivityLogger::log(
            'create',
            'Category Added',
            "New category \"{$category->name}\" was created",
            route('categories.edit', $category)
        );

        return redirect()->route('categories.index')->with('status', 'Category added');
    }

    public function edit(Category $category): View
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,' . $category->id],
        ]);
        $category->update($data);

        // Log admin activity
        AdminActivityLogger::log(
            'update',
            'Category Updated',
            "Category \"{$category->name}\" was updated",
            route('categories.edit', $category)
        );

        return redirect()->route('categories.index')->with('status', 'Category updated');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $name = $category->name;

        $category->delete();

        // Log admin activity
        AdminActivityLogger::log(
            'delete',
            'Category Deleted',
            "Category \"{$name}\" was removed",
            route('categories.index')
        );

        return redirect()->route('categories.index')->with('status', 'Category deleted');
    }
}
