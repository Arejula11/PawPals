<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\CommentLike;

class CommentLikeController extends Controller
{
    public function store(string $post, string $comment) {
        
        $like = new CommentLike();
        $like->user_id = auth()->id();
        $like->comment_id = $comment;
        
        $like->save();
        return redirect()->route('posts.show', ['id' => $post])->with('success', 'Like added successfully!');
    }

    public function destroy(string $post, string $comment) {
        
        $deleted = CommentLike::where('user_id', auth()->id())
                    ->where('comment_id', $comment)
                    ->delete();
        
        if (!$deleted) {
            return redirect()->route('posts.show', ['id' => $post])
            ->with('error', 'Like not found or already removed.');
        }

        return redirect()->route('posts.show', ['id' => $post])
                ->with('success', 'Like removed successfully.');
    }
}
