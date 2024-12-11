<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Group;
use Illuminate\Support\Facades\Hash;


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
        $user = auth()->user();
        $this->authorize('admin', $user);
        return view('admin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $this->authorize('admin', $user);
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
        $loguser = auth()->user();
        $this->authorize('admin', $loguser);
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
        $loguser = auth()->user();
        $this->authorize('admin', $loguser);
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

        // Filtrar usuarios donde el campo 'type' no sea 'delete'
        $users = User::where('type', '!=', 'deleted')
                    ->orderBy('username', 'asc')
                    ->simplePaginate(25);

        return view('admin.usersManage', compact('users'));
    }

    /**
     * Manage groups.
     */
    public function groupsManage()
    {
        $user = auth()->user();
        $this->authorize('admin', $user);

        $groups = Group::orderBy('name', 'asc')->simplePaginate(10);

        return view('admin.groupsManage', compact('groups'));
    }


    /**
     * Show user details.
     */
    public function showUser(string $id)
    {
        $loguser = auth()->user();
        $this->authorize('admin', $loguser);
        $user = User::findOrFail($id);
        return view('admin.showUser', compact('user'));
    }

    /**
     * Show group details
     */
    public function showGroup(string $id)
    {
        $loguser = auth()->user();
        $this->authorize('admin', $loguser);
        $group = Group::findOrFail($id);
        return view('admin.showGroup', compact('group'));
    }


    /**
     * Ban a user
     */
    public function banUser(string $id){
        $loguser = auth()->user();
        $this->authorize('admin', $loguser);
        $user = User::findOrFail($id);
        return redirect()->route('admin.users.manage')->with('success', 'User banned successfully.');
    }

    /**
     * Show the page to change the password
     */
    public function changePassword()
    {
        $loguser = auth()->user();
        $this->authorize('admin', $loguser);
        return view('admin.changePassword');
    }

    /**
     * Change the passwrod after validating the old password
     */
    public function updatePassword(Request $request, string $id)
    {
        $loguser = auth()->user();
        $this->authorize('admin', $loguser);
        $validatedData = $request->validate([
            'old' => 'required|string|min:8',
            'new' => 'required|string|min:8',
            'repeat' => 'required|string|min:8',
        ]);
        $user = User::findOrFail($id);

        if ( $validatedData['new'] != $validatedData['repeat'] ) {
            return redirect()->route('admin.changePassword', $id)->with('error', 'Passwords did not match.');
        }


        if (Hash::check($validatedData['old'], $user->password)) {
            $user->password = bcrypt($validatedData['new']);
            $user->save();

            return redirect()->route('admin.home')->with('success', 'Password changed successfully.');
        }

        return redirect()->route('admin.changePassword', $id)->with('error', 'Old password is incorrect.');
    }

}
