<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allPostImages = [];
        $pendingRequestsCount = 0;

    // Assuming User is your model for users
    $users = User::all(); // Fetch all users from the database

    foreach ($users as $user) {
        // Fetch the posts for each user
        $userPostImages = FileController::getAllPostUserImages($user->id);
        $allPostImages = array_merge($allPostImages, $userPostImages);
    }

    // Fetch pending requests for the logged-in user
    $loggedInUser = auth()->user();
    if ($loggedInUser) {
        $pendingRequestsCount = \App\Models\Follow::where('user2_id', $loggedInUser->id)
            ->where('request_status', 'pending')
            ->count();
    }
    if ($loggedInUser) {
        $pendingRequests = \App\Models\Follow::with('follower')
                                            ->where('user2_id', $loggedInUser->id)
                                            ->where('request_status', 'pending')
                                            ->get();
    }

    return view('pages.home', ['postImages' => $allPostImages,'pendingRequestsCount' => $pendingRequestsCount,'pendingRequests'=>$pendingRequests]);

    }

}
