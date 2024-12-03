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
        // Validate the incoming data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'bio_description' => 'nullable|string|max:1000',
            'public' => 'required|boolean',
            'type' => 'required|string|in:pet owner,admin,veterinarian,adoption organization,rescue organization',
        ]);

        // Find the user
        $user = User::findOrFail($id);

        // Update the user's attributes
        $user->firstname = $validatedData['name'];
        $user->surname = $validatedData['surname'];

        if ($request->filled('password')) {
            $user->password = bcrypt($validatedData['password']);
        }

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $fileName = $file->hashName();
            $file->storeAs('images/profile', $fileName, 'Images');

            // Update profile_picture field
            $user->profile_picture = $fileName;
        }

        $user->bio_description = $validatedData['bio_description'] ?? $user->bio_description;
        $user->is_public = $validatedData['public'];
        $user->type = $validatedData['type'];

        // Save changes
        $user->save();

        // Redirect back with a success message
        return redirect()->route('users.show', $id)->with('success', 'Profile updated successfully.');
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
}
