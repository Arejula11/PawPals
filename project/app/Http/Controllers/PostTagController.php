<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostTag;

class PostTagController extends Controller
{
    public function store(string $post_id, string $user_id) {
        $data = [
            'post_id' => $post_id,
            'user_id' => $user_id,
        ];

        $existingTag = PostTag::where('post_id', $post_id)
                              ->where('user_id', $user_id)
                              ->first();

        if (!$existingTag) {
            $tag = new PostTag();
            $tag->post_id = $post_id;
            $tag->user_id = $user_id;
            $tag->save();

            return redirect()
                ->route('posts.create', ['id' => $post_id])
                ->with('success', 'Tag added successfully!');
        }

        return redirect()
            ->route('posts.create', ['id' => $post_id])
            ->with('error', 'User is already tagged.');
    }

    public function destroy(string $post_id, string $user_id) {
        $tag = PostTag::where('post_id', $post_id)
                      ->where('user_id', $user_id)
                      ->firstOrFail();

        $tag->delete();

        return redirect()
            ->route('posts.create', ['id' => $post_id])
            ->with('success', 'Tag removed successfully.');
    }
}
