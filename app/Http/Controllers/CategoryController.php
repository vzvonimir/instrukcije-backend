<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return response()->json([
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        Category::create($request->all());
        return response()->json(['message' => 'Category added successfully.'], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request)
    {
        $category = Category::find($request->id);
        $category->update($request->all());
        return response()->json(['message' => 'Category successfully updated.', 'category' => $category], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        if(!$category){
            return response()->json(['message' => 'Category not found.'], 404);
        }
        $category->delete();
        return response()->json(['message' => 'Category successfully deleted.'], 200);
    }
}
