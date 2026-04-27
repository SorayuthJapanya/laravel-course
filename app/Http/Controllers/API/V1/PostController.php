<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = request()->user();
        $posts = $user->posts()->with('author')->paginate();
        return response()->json([
            'message' => 'success',
            'data' => PostResource::collection($posts)
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        // Get body
        $data = $request->validated();

        // Get user
        $user = $request->user();
        $data['author_id'] = $user->id;

        // Create post
        $post = Post::create($data);

        return response()->json([
            'message' => 'Created posts sucessfully',
            'data' => $post
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        abort_if(Auth::id() != $post->id, 403, 'Access Forbidden');
        return response()->json([
            'message' => 'success',
            'data' => (new PostResource($post))->resolve()
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StorePostRequest $request, Post $post)
    {
        abort_if(Auth::id() != $post->id, 403, 'Access Forbidden');

        $data = $request->validated();
        $post->update($data);

        return response()->json([
            'message' => 'Updated posts successfully.',
            'data' => (new PostResource($post))->resolve()
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        abort_if(Auth::id() != $post->id, 403, 'Access Forbidden');
        
        $post->delete();
        return response()->json([
            'message' => 'Deleted posts successfully.',
        ], 200);
    }
}
