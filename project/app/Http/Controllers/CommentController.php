<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $id)
    {

        $post = Post::findOrFail($id);
        // Validate the input
        $validated = $request->validate([
            'content' => 'required|string|max:255',
            'previous_comment_id' => 'nullable|exists:comment,id',
        ]);
        // Create a new comment
        $comment = new Comment($validated);
        $comment->content = $request->content;
        $comment->date = now();
        $comment->post_id = $id; 
        $comment->user_id = auth()->id(); 
        $comment->previous_comment_id = $request->previous_comment_id;

        $comment->save();
    
        return redirect()->route('posts.show', compact('id'))->with('success', 'Comment added successfully!');
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
    public function edit(string $postId, string $commentId)
    {
        $loguser = auth()->user();
        $this->authorize('banned', $loguser);
        
        $post = Post::findOrFail($postId);
        $comment = Comment::findOrFail($commentId);
        $user = auth()->user();
        
        if($user->id !== $comment->user_id || !$user) {
            abort(403, 'You do not have permission to edit this comment.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $postId, string $commentId)
    {
        $loguser = auth()->user();
        $this->authorize('banned', $loguser);

        $comment = Comment::findOrFail($commentId);
    
        $request->validate([
            'content' => 'required|string|max:255',
        ]);
    
        $comment->content = $request->input('content');
        $comment->save();

        if ($comment->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
    
        return response()->json(['success' => true, 'content' => $comment->content]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $postId, string $commentId)
    {
        $loguser = auth()->user();
        $this->authorize('banned', $loguser);
    
        $comment = Comment::findOrFail($commentId);
    
        // Check if the authenticated user owns the comment
        if ($comment->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        $comment->delete();
    
        return response()->json(['success' => true]);
    }
}
