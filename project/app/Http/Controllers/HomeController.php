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

    $users = User::all();

    foreach ($users as $user) {

        $userPostImages = FileController::getAllPostUserImages($user->id);
        $allPostImages = array_merge($allPostImages, $userPostImages);
    }

    return view('pages.home', ['postImages' => $allPostImages]);

    }

}
