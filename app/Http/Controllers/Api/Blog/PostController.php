<?php

namespace App\Http\Controllers\Api\Blog;

use App\Http\Controllers\Blog\BaseController;
use App\Models\BlogPost;
use Carbon\Traits\Date;
use Illuminate\Http\Request;

class PostController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $posts = BlogPost::with(['user', 'category'])->paginate(5);

        return $posts;
    }
    public function get()
    {
        $posts = BlogPost::with(['user', 'category'])->get();
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
/*'title',
'slug',
'category_id',
'excerpt',
'content_raw',
'is_published',
'published_at',*/
    public function store(Request $request)
    {
       /* $data = $request->input(); //отримаємо масив даних, які надійшли з форми


        $item = (new BlogPost())->create($data);*/
       $newPost = BlogPost::with(['user', 'category'])->create([
           'title' => $request->title,
           'slug' => $request->slug,
           'category_id' => $request->category_id,
           'excerpt' =>$request->excerpt,
           'content_raw' => $request->content_raw,
           'is_published' => $request->is_published
       ]);
       if($newPost){
           return response()->json([
               'status' => 200,
               'message' => "Post Created Successfully"
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
        $post = BlogPost::with(['user', 'category'])->find($id);

        return $post;
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
        $post = BlogPost::find($id);

        if (!$post) {
            return response()->json([
                'status' => 404,
                'message' => 'Post not found'
            ], 404);
        }
        if($request->is_published === true && $post->is_published ===false)
        {
            $post->published_at = now();
        }
        // Update only the fields that were sent in the request
        $post->update($request->all());

        return response()->json([
            'status' => 200,
            'message' => 'Post updated successfully',
            'data' => $post
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = BlogPost::find($id);

        if (!$post) {
            return response()->json([
                'status' => 404,
                'message' => 'Post not found'
            ], 404);
        }

        try {
            $post->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Post deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            // Handle delete exception
            return response()->json([
                'status' => 500,
                'message' => 'Failed to delete post'
            ], 500);
        }
    }
}
