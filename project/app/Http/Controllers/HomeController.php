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
    public function index()
    {
        
        $this->authorize('banned', User::class);

        $posts = Post::with('user')
        ->where('is_public', true)
        ->orderBy('creation_date', 'desc')
        ->limit(10)
        ->get();
        $pendingRequestsCount = 0;
        $pendingRequests = [];
    
        $users = User::all();

        foreach ($users as $user) {

            // Fetch pending requests for the logged-in user
            $loggedInUser = auth()->user();
            if ($loggedInUser) {
                $pendingRequests = \App\Models\Follow::with('follower')
                    ->where('user2_id', $loggedInUser->id)
                    ->where('request_status', 'pending')
                    ->get();

                $pendingRequestsCount = $pendingRequests->count();
            }

            return view('pages.home', [
                'posts' => $posts,
                'pendingRequestsCount' => $pendingRequestsCount,
                'pendingRequests'=>$pendingRequests]);
        }

    }
    
}
