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
        $pendingRequests = [];
    
        $users = User::all();

        foreach ($users as $user) {
            $userPostImages = FileController::getAllPostUserImages($user->id);
            $allPostImages = array_merge($allPostImages, $userPostImages);
            
            if (count($allPostImages) >= 10) {
                break;
            }

            // Fetch pending requests for the logged-in user
            $loggedInUser = auth()->user();
            if ($loggedInUser) {
                $pendingRequests = \App\Models\Follow::with('follower')
                    ->where('user2_id', $loggedInUser->id)
                    ->where('request_status', 'pending')
                    ->get();

                $pendingRequestsCount = $pendingRequests->count();
            }

            return view('pages.home', ['postImages' => $allPostImages,'pendingRequestsCount' => $pendingRequestsCount,'pendingRequests'=>$pendingRequests]);
        }

    }
}
