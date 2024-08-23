<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withTrashed()->paginate(10); // Including soft deleted records
        return view('admin.category.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        Category::create($request->all());
        return redirect()->route('admin.category.index')->with('success', 'Category created successfully.');
    }
    public function detail(Request $request, $id)
    {
        $data = Category::findOrFail($id);
        return view('admin.category.detail', compact('data'));
    }

    public function show(Request $request, $id)
    {
        $data = Category::findOrFail($id);
        return view('admin.category.show', compact('data'));
    }

    public function edit(Request $request, $id)
    {
        $data = Category::findOrFail($id);
        return view('admin.category.edit', compact('data'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        $category->update($request->all());
        return redirect()->route('admin.category.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.category.index')->with('success', 'Category deleted successfully.');
    }

    public function restore($id)
    {
        Category::withTrashed()->find($id)->restore();
        return redirect()->route('admin.category.index')->with('success', 'Category restored successfully.');
    }
    public function updateStatus(Request $request, $id)
{
    $category = Category::findOrFail($id);
    $category->status = $request->status;
    $category->save();

    return response()->json([
        'success' => true,
        'message' => 'Category status updated successfully.'
    ]);
}
}
