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

    // Assuming User is your model for users
    $users = User::all(); // Fetch all users from the database

    foreach ($users as $user) {
        // Fetch the posts for each user
        $userPostImages = FileController::getAllPostUserImages($user->id);
        $allPostImages = array_merge($allPostImages, $userPostImages);
        //allpostImages contains only 20 images
        if (count($allPostImages) >= 10) {
            break;
        }
        
    }

    return view('pages.home', ['postImages' => $allPostImages]);

    }

}
