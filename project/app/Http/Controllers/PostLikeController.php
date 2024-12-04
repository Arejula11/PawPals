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
        return redirect()->route('posts.show', compact('id'))->with('success', 'Like added successfully!');
    }

    public function destroy(string $id) {
        
        $like = PostLike::where('user_id', auth()->id())
                    ->where('post_id', $id)
                    ->firstOrFail();
        $like->delete();

        return redirect()->route('posts.show', compact('id'))->with('success', 'Like removed successfully.');
    }
}
