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
        $posts = Post::with('user')->orderBy('creation_date', 'desc')->get();
    
        return view('pages.home', ['posts' => $posts]);
    }
}
