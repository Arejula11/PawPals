<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\CommentLike;

class CommentLikeController extends Controller
{
    public function store(string $postId, string $commentId) {
        $userId = auth()->id();
        $comment = Comment::findOrFail($commentId);

        if ($comment->user_id == $userId) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot like your own post.'
            ], 403) ;
        }

        $like = CommentLike::firstOrCreate([
            'user_id' => $userId,
            'comment_id' => $commentId,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Like added successfully!',
            'likeCount' => $comment->likes()->count(),
        ]);
    }

    public function destroy(string $post, string $comment) {
        
        $userId = auth()->id();
        $commentId = $comment;
    
        $deletedRows = CommentLike::where('user_id', $userId)
            ->where('comment_id', $commentId)
            ->delete();
    
        if ($deletedRows === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Like not found or already removed.',
            ], 404);
        }
    
        return response()->json([
            'success' => true,
            'message' => 'Like removed successfully!',
            'likeCount' => Comment::findOrFail($commentId)->likes()->count(),
        ]);
    }
}
