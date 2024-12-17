<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Post;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $loggedInUser = auth()->user();
        if ($loggedInUser) {
            $this->authorize('banned', User::class);
        }

        $pendingRequests = [];
        $pendingRequestsCount = 0;

        $filter = $request->query('filter', 'following'); 
        if (!$loggedInUser) {
            $filter = 'public';
        }
        
        if ($filter === 'public') {
            $posts = Post::with('user')
                ->where('is_public', true) 
                ->orderBy('creation_date', 'desc')
                ->paginate(10);
        } elseif ($filter === 'following' && $loggedInUser) {
            $followedUserIds = $loggedInUser->follows()->pluck('id'); 
            $posts = Post::with('user')
                ->whereIn('user_id', $followedUserIds)
                ->orderBy('creation_date', 'desc')
                ->paginate(10);
        } else {
            $posts = collect(); 
        }

        if ($loggedInUser) {
            $pendingRequests = \App\Models\Follow::with('follower')
                ->where('user2_id', $loggedInUser->id)
                ->where('request_status', 'pending')
                ->get();
    
            $pendingRequestsCount = $pendingRequests->count();
        }
    
        if ($request->ajax()) {
            $view = '';
            foreach ($posts as $post) {
                $view .= view('pages.post-list', ['post' => $post])->render();
            }
            return response($view);
        }
        
        return view('pages.home', [
            'posts' => $posts,
            'pendingRequestsCount' => $pendingRequestsCount,
            'pendingRequests' => $pendingRequests,
            'currentFilter' => $filter
        ]);
    }

}
