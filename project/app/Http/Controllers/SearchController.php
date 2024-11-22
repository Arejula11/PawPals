<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Ensure to import the User model

class SearchController extends Controller
{
    /**
     * Display the search page.
     */
    public function index()
    {
        return view('pages.search');
    }

    /**
     * Search users based on the query.
     */
    public function searchUsers(Request $request)
    {
        // Validate the input (optional but recommended)
        $request->validate([
            'query' => 'required|string|min:2',
        ]);

        $query = $request->input('query');
        
        // Query users based on the username, ignoring case
        $users = User::whereRaw('LOWER(username) LIKE ?', ['%' . strtolower($query) . '%'])->limit(5)->get();

        // Return results as JSON
        return response()->json($users);
    }
}
