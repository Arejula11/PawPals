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
            'query' => 'nullable|string|min:2',
            'type'  => 'required|string',
        ]);

        $query = strtolower($request->input('query', ''));
        $type = strtolower($request->input('type', 'pet owner'));
        
        $users = User::query()
            ->when($query, function ($queryBuilder) use ($query) {
                $queryBuilder->where(function ($subQuery) use ($query) {
                    $subQuery->whereRaw('LOWER(username) LIKE ?', ['%' . $query . '%'])
                            ->orWhereRaw('LOWER(firstname) LIKE ?', ['%' . $query . '%'])
                            ->orWhereRaw('LOWER(surname) LIKE ?', ['%' . $query . '%']);
                });
            })
            ->whereRaw('LOWER(CAST(type AS TEXT)) = ?', [$type])
            ->limit(5)
            ->get()
            ->map(function ($user) {
                return [
                    'id'            => $user->id,
                    'first_name'    => $user->firstname,
                    'username'      => $user->username,
                    'profile_image' => $user->profile_picture 
                                        ? asset('profile/' . $user->profile_picture) 
                                        : asset('profile/default.png'),
                    'profile_url'   => route('users.show', $user->id),
                ];
            });
        return response()->json($users);
    }

}
