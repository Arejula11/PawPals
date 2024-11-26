<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $postImages = FileController::getAllPostUserImages(1);
        return view('pages.home', ['postImages' => $postImages]);
    }

}
