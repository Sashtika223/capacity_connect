<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = CourseCategory::withCount('courses')->orderBy('name')->get();

        return view('dashboards.admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:course_categories',
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        CourseCategory::create($validated);

        return back()->with('success', 'Category created successfully.');
    }

    public function update(Request $request, CourseCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:course_categories,name,'.$category->id,
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $category->update($validated);

        return back()->with('success', 'Category updated successfully.');
    }

    public function destroy(CourseCategory $category)
    {
        if ($category->courses()->exists()) {
            return back()->with('error', 'Cannot delete category that has courses assigned.');
        }

        $category->delete();

        return back()->with('success', 'Category deleted successfully.');
    }
}
