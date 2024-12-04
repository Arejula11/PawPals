<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        $user = new User($validated);
        $user->password = bcrypt($validated['password']);
        $user->save();

        return redirect()->route('admin.index')->with('success', 'Admin created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);

        $this->authorize('update', $user);

        return view('admin.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    /**
     * Show the admin dashboard.
     */
    public function home()
    {
        $user = auth()->user();
        $this->authorize('admin', $user);
        $users = User::all();
        return view('admin.home', compact('users'));
    }

    /**
     * Manage users.
     */
    public function usersManage()
    {
        $user = auth()->user();
        $this->authorize('admin', $user);
        $users = User::orderBy('username', 'asc')->simplePaginate(25);
        return view('admin.usersManage', compact('users'));
    }

    /**
     * Show user details.
     */
    public function showUser(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.showUser', compact('user'));
    }


    /**
     * Ban a user
     */
    public function banUser(string $id){
        $user = User::findOrFail($id);
        

        return redirect()->route('admin.users.manage')->with('success', 'User banned successfully.');
    }
}
