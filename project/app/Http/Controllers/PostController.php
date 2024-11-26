<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Picture;

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
    $validated = $request->validate([
        'description' => 'required|string|max:500',
        'post_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $post = new Post($validated);
    $post->description = $request->description;
    $post->creation_date = now();
    $post->user_id = auth()->id();

    if ($request->hasFile('post_picture')) {
        $file = $request->file('post_picture');
        $fileName = $file->hashName();
        $file->storeAs('post', $fileName, 'Images');

        $post->post_picture = "post/$fileName";
    }

    $post->save();

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
        //
    }
}