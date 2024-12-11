<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaticController extends Controller
{
    /**
     * Display the about us page.
     */
    public function showAbout()
    {
        return view('static.about');
    }
}
