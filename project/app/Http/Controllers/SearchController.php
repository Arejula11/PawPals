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
        $request->validate([
            'query' => 'required|string|min:2',
        ]);

        $query = $request->input('query');
        
        // Query users based on username
        $users = User::whereRaw('LOWER(username) LIKE ?', ['%' . strtolower($query) . '%'])
                    ->limit(5)
                    ->get()
                    ->map(function ($user) {
                        return [
                            'id' => $user->id,
                            'first_name' => $user->firstname,
                            'username' => $user->username,
                            'profile_image' => $user->profile_picture ? asset('images/profile/' . $user->profile_picture) : asset('images/profile/default.png'), // Fallback image
                            'profile_url' => route('users.show', $user->id),
                        ];
                    });

        return response()->json($users);
    }

}
