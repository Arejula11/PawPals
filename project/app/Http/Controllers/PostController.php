<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Log;


class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = auth()->id();
            
        $posts = Post::with('user')
            ->where('user_id', $userId)
            ->orderBy('creation_date', 'desc')
            ->paginate(10); 
            
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
        $user = auth()->user();

        if (!$post->is_public && (!$user || (!$user->follows->contains($post->user_id) && $user->id !== $post->user_id))) {
            abort(403, 'You do not have permission to view this post.');
        }
    
        return view('pages.post.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $post = Post::findOrFail($id);
        $user = auth()->user();

        if($user->id !== $post->user_id || !$user) {
            abort(403, 'You do not have permission to edit this post.');
        }
        return view('pages.post.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $post = Post::findOrFail($id);
    
        $validated = $request->validate([
            'description' => 'nullable|string|max:500',
            'post_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'is_public' => 'nullable|boolean',
        ]);
    
        $post->description = $validated['description'];
        $post->is_public = $validated['is_public'];
    
        if ($request->hasFile('post_picture')) {
            if ($post->post_picture && \Storage::disk('Images')->exists($post->post_picture)) {
                \Storage::disk('Images')->delete($post->post_picture);
            }
    
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
        $post = Post::findOrFail($id);
        $user = auth()->user();
        
        if (!$user || $user->id !== $post->user_id) {
            abort(403, 'You do not have permission to delete this post.');
        }
    
        if ($post->post_picture && \Storage::disk('Images')->exists($post->post_picture)) {
            \Storage::disk('Images')->delete($post->post_picture);
        }
    
        $post->delete();
    
        return redirect()->route('posts.index')->with('success', 'Post deleted successfully!');        
    }
}