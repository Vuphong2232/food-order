<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->get();
        return view('admin.categories', compact('categories'));
    }

    public function getCategories()
    {
        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json([
            'data' => $categories
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
            'slug' => 'nullable|string|max:120|unique:categories,slug',
            'is_active' => 'nullable|boolean',
        ]);

        $slug = $validated['slug'] ?? Str::slug($validated['name']);

        if (Category::where('slug', $slug)->exists()) {
            return response()->json([
                'message' => 'Slug đã tồn tại'
            ], 422);
        }

        $category = Category::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'is_active' => $validated['is_active'] ?? 1,
        ]);

        return response()->json([
            'message' => 'Thêm danh mục thành công!',
            'data' => $category
        ], 201);
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $category->id,
            'slug' => 'nullable|string|max:120|unique:categories,slug,' . $category->id,
            'is_active' => 'nullable|boolean',
        ]);

        $slug = $validated['slug'] ?? Str::slug($validated['name']);

        $category->update([
            'name' => $validated['name'],
            'slug' => $slug,
            'is_active' => $validated['is_active'] ?? $category->is_active,
        ]);

        return response()->json([
            'message' => 'Cập nhật danh mục thành công!',
            'data' => $category
        ]);
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa danh mục thành công'
        ]);
    }
}