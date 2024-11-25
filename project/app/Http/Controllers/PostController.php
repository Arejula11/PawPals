<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

use App\Models\Post;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        // Validate the request
    $request->validate([
        'description' => 'required|string|max:500',
        'post_picture' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
    ]);

    // Save the post to the database
    $post = new Post();
    $post->description = $request->description;
    $post->creation_date = now();
    $post->user_id = auth()->id(); // Assuming user is logged in

    // Handle file upload
    if ($request->hasFile('post_picture')) {
        $file = $request->file('post_picture');
        $path = $file->store('uploads/posts', 'public');
        $post->post_picture_id = $path; // Adjust this to match your database logic
    }

    $post->save();

    // Redirect with success message
    return redirect()->route('home')->with('success', 'Post created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::find($id);

        // Check if the current user is authorized to delete this item.
        $this->authorize('delete', $post);

        // Delete the item and return it as JSON.
        $post->delete();
        return response()->json($post);
    }
}