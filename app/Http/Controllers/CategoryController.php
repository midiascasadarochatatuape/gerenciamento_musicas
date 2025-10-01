<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('type')->orderBy('name')->get();
        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:category,context',
            'description' => 'nullable|string'
        ]);

        $category = Category::create($validated);
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'category' => $category
            ]);
        }
        
        return redirect()->back()->with('success', 'Categoria criada com sucesso!');
    }

    public function destroy(Category $category, Request $request)
    {
        $category->delete();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true
            ]);
        }
        
        return redirect()->back()->with('success', 'Categoria excluída com sucesso!');
    }
}