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
        $loguser = auth()->user();
        $this->authorize('banned', $loguser);
        return view('pages.search');
    }

    /**
     * Search users based on the query.
     */
    public function searchUsers(Request $request)
    {
        $loguser = auth()->user();
        $this->authorize('banned', $loguser);
        $request->validate([
            'query' => 'required|string|min:2',
        ]);

        $query = $request->input('query');
        $query = strtolower($query);
        
        // Query users based on username
        $users = User::whereRaw('LOWER(username) LIKE ?', ['%' . $query . '%'])
                    ->orWhereRaw('LOWER(firstname) LIKE ?', ['%' . $query . '%'])
                    ->orWhereRaw('LOWER(surname) LIKE ?', ['%' . $query . '%'])
                    ->orWhereRaw('LOWER(CAST(type AS TEXT)) LIKE ?', ['%' . strtolower($query) . '%'])
                    ->limit(5)
                    ->get()
                    ->map(function ($user) {
                        return [
                            'id' => $user->id,
                            'first_name' => $user->firstname,
                            'username' => $user->username,
                            'profile_image' => $user->profile_picture ? asset('profile/' . $user->profile_picture) : asset('profile/default.png'), // Fallback image
                            'profile_url' => route('users.show', $user->id),
                        ];
                    });

        return response()->json($users);
    }

}
