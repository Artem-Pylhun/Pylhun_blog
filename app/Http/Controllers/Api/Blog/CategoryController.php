<?php

namespace App\Http\Controllers\Api\Blog;

use App\Http\Controllers\Blog\BaseController;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = BlogCategory::with('parentCategory')->paginate(5);

        return $posts;
    }
    public function getForComboBox()
    {
        $posts = BlogCategory::with('parentCategory')->get();
        return $posts;
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    /* 'title',
        'slug',
        'parent_id',
        'description',*/
    public function store(Request $request)
    {
        $newCategory = BlogCategory::with('parentCategory')->create([
            'title' => $request->title,
            'slug' => $request->slug,
            'parent_id' => $request->parent_id,
            'description' =>$request->description
        ]);
        if($newCategory){
            return response()->json([
                'status' => 200,
                'message' => "Category Created Successfully"
            ],200);
        }else{
            return response()->json([
                'status' => 500,
                'message' => "Something went wrong"
            ],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = BlogCategory::with('parentCategory')->find($id);

        return $category;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = BlogCategory::find($id);
        if (!$category) {
            return response()->json([
                'status' => 404,
                'message' => 'Category not found'
            ], 404);
        }
        $category->update($request->all());

        return response()->json([
            'status' => 200,
            'message' => 'Category updated successfully',
            'data' =>$category
        ],  200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category =BlogCategory::find($id);
        if (!$category) {
            return response()->json([
                'status' => 404,
                'message' => 'Category not found'
            ], 404);
        }
        try {
            $category->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Category deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            // Handle delete exception
            return response()->json([
                'status' => 500,
                'message' => 'Failed to delete category'
            ], 500);
        }
    }
}
