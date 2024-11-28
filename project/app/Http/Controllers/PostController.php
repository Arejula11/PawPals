<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Picture;
use Illuminate\Support\Facades\Log;


class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get the authenticated user's ID
        $userId = auth()->id();
            
        // Fetch posts belonging to the authenticated user
        $posts = Post::with('user')
            ->where('user_id', $userId)
            ->orderBy('creation_date', 'desc')
            ->paginate(10); // Paginate with 10 posts per page
            
        // Return the index view with user's posts
        return view('pages.post.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.post.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    $validated = $request->validate([
        'description' => 'required|string|max:500',
        'post_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'is_public' => 'required|boolean',
    ]);

    $post = new Post($validated);
    $post->description = $request->description;
    $post->creation_date = now();
    $post->is_public = $request->is_public;
    $post->user_id = auth()->id();

    if ($request->hasFile('post_picture')) {
        $file = $request->file('post_picture');
        $fileName = $file->hashName();
        $file->storeAs('post', $fileName, 'Images');

        $post->post_picture = "$fileName";
    }

    $post->save();

    return redirect()->route('home')->with('success', 'Post created successfully!');
    }

    
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::with('user')->findOrFail($id);

        return view('pages.post.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //**** VALIDATION REQUIRED
        $post = Post::findOrFail($id);
        // Return the edit view with the post
        return view('pages.post.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $post = Post::findOrFail($id);
    
        // Validate the request
        $validated = $request->validate([
            'description' => 'nullable|string|max:500',
            'post_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'is_public' => 'nullable|boolean',
        ]);
    
        // Update description
        $post->description = $validated['description'];
        $post->is_public = $validated['is_public'];
    
        // Update post picture if provided
        if ($request->hasFile('post_picture')) {
            // Delete the old picture
            if ($post->post_picture && \Storage::disk('Images')->exists($post->post_picture)) {
                \Storage::disk('Images')->delete($post->post_picture);
            }
    
            // Save the new picture
            $file = $request->file('post_picture');
            $fileName = $file->hashName();
            $file->storeAs('post', $fileName, 'Images');
            $post->post_picture = "$fileName";
        }
    
        $post->save();
    
        return redirect()->route('posts.index')->with('success', 'Post updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //**** VALIDATION REQUIRED
        $post = Post::findOrFail($id);
    
        // Delete the associated image if it exists
        if ($post->post_picture && \Storage::disk('Images')->exists($post->post_picture)) {
            \Storage::disk('Images')->delete($post->post_picture);
        }
    
        $post->delete();
    
        return redirect()->route('posts.index')->with('success', 'Post deleted successfully!');        
    }
}