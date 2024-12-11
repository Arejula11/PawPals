<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostLike;


class PostLikeController extends Controller
{
    public function store(string $id) {
        
        $like = new PostLike();
        $like->user_id = auth()->id();
        $like->post_id = $id;
        
        $like->save();

        return response()->json([
            'success' => true,
            'message' => 'Like added successfully!',
            'likeCount' => Post::findOrFail($id)->likes()->count(),
        ]);
    }

    public function destroy(string $id) {
        
        $userId = auth()->id();
        $postId = $id;
    
        $deletedRows = PostLike::where('user_id', $userId)
            ->where('post_id', $postId)
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
            'likeCount' => Post::findOrFail($postId)->likes()->count(),
        ]);
    }
}
