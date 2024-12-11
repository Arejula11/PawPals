<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('banned', User::class);
    
        $posts = Post::with('user')
            ->where('is_public', true)
            ->orderBy('creation_date', 'desc')
            ->paginate(10);
    
        $loggedInUser = auth()->user();
        $pendingRequests = [];
        $pendingRequestsCount = 0;
    
        if ($loggedInUser) {
            $pendingRequests = \App\Models\Follow::with('follower')
                ->where('user2_id', $loggedInUser->id)
                ->where('request_status', 'pending')
                ->get();
    
            $pendingRequestsCount = $pendingRequests->count();
        }
    
        if ($request->ajax()) {
            \Log::info($posts->toArray());
            $view = '';
            foreach ($posts as $post) {
                $view .= view('pages.post-list', ['post' => $post])->render();
            }
            return response($view);
        }
    
        return view('pages.home', [
            'posts' => $posts,
            'pendingRequestsCount' => $pendingRequestsCount,
            'pendingRequests' => $pendingRequests
        ]);
    }
    
    
}
