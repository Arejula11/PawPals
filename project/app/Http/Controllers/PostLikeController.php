<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostLike;


class PostLikeController extends Controller
{
    public function store(string $id) {
        $userId = auth()->id();
        $post = Post::findOrFail($id);
    
        if ($post->user_id == $userId) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot like your own post.'
            ], 403) ;
        }
        
        $like = new PostLike();
        $like->user_id = auth()->id();
        $like->post_id = $id;
        
        $like->save();

        return response()->json([
            'success' => true,
            'message' => 'Like added successfully!',
            'likeCount' => $post->likes()->count(),
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
