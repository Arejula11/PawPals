<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $loguser = auth()->user();
        if ($loguser) {
            $this->authorize('banned', $loguser);
        }
        $users = User::all();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $loguser = auth()->user();
        $this->authorize('banned', $loguser);
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $loguser = auth()->user();
        $this->authorize('banned', $loguser);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        $user = new User($validated);
        $user->password = bcrypt($validated['password']);
        $user->save();

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $loguser = auth()->user();
        if ($loguser) {
            $this->authorize('banned', $loguser);
        }
        $user = User::findOrFail($id);

        if (!$user->is_public && !auth()->check()) {
            abort(403, 'This profile is private.');
        }

        $loggedInUser = auth()->user();    
        $isOwnProfile = $loggedInUser && $loggedInUser->id === $user->id;

        $followStatus = null;

        if ($loggedInUser && !$isOwnProfile) {
            $follow = \App\Models\Follow::where([
                ['user1_id', '=', $loggedInUser->id],
                ['user2_id', '=', $user->id],
            ])->first();

            if ($follow) {
                $followStatus = $follow->request_status;
            }
        }
        $postImages = FileController::getAllPostUserImages($user->id);
        return view('users.show', compact('user', 'isOwnProfile', 'postImages', 'followStatus'));
    }

    public function follow(Request $request)
    {
        $loguser = auth()->user();
        $this->authorize('banned', $loguser);
        $userToFollow = User::findOrFail($request->user2_id);
        $loggedInUser = auth()->user();

        if ($userToFollow->is_public) {
            \App\Models\Follow::create([
                'user1_id' => $loggedInUser->id,
                'user2_id' => $userToFollow->id,
                'request_status' => 'accepted',
            ]);
        } else {
            \App\Models\Follow::create([
                'user1_id' => $loggedInUser->id,
                'user2_id' => $userToFollow->id,
                'request_status' => 'pending',
            ]);
        }

        return redirect()->route('users.show', $userToFollow->id);
    }

    public function checkRequests()
    {
        $loguser = auth()->user();
        $this->authorize('banned', $loguser);
        $user = auth()->user();
        $pendingRequests = \App\Models\Follow::with('follower')
                                            ->where('user2_id', $user->id)
                                            ->where('request_status', 'pending')
                                            ->get();

        $pendingRequestsCount = $pendingRequests->count();
        return view('requests.show', compact('pendingRequests','pendingRequestsCount','user'));
    }

    public function accept($user1_id, $user2_id)
    {
        $loguser = auth()->user();
        $this->authorize('banned', $loguser);
        $request = \App\Models\Follow::where('user1_id', $user1_id)
                                    ->where('user2_id', $user2_id)
                                    ->delete();

        if ($request) {
            \App\Models\Follow::create([
                'user1_id' => $user1_id,
                'user2_id' => $user2_id,
                'request_status' => 'accepted',
            ]);

            $notification = new \App\Models\Notification([
                'user_id' => $user1_id,
                'description' => 'Your follow request has been accepted!',
                'date' => date('Y-m-d H:i:s'),
            ]);

            $notification->save();
            //get the id from the notification just created
            $notificationID = \App\Models\Notification::where('user_id', $user1_id)
                                                    ->where('description', 'Your follow request has been accepted!')
                                                    ->where('date', date('Y-m-d H:i:s'))
                                                    ->first()->id;

            $userNotifiaction = new \App\Models\UserNotification([
                'notification_id' => $notificationID,
                'trigger_user_id' => $user2_id,
                'user_notification_type' => 'follow_response',
            ]);
            $userNotifiaction->save();
            return redirect()->route('home')->with('success', 'Request accepted.');
        }

        return redirect()->route('home')->with('error', 'Request not found.');
    }

    public function reject($user1_id, $user2_id)
    {
        $loguser = auth()->user();
        $this->authorize('banned', $loguser);
        $deleted = \App\Models\Follow::where('user1_id', $user1_id)
                                    ->where('user2_id', $user2_id)
                                    ->delete();
    
        if ($deleted) {
            return redirect()->route('home')->with('success', 'Request rejected.');
        }
    
        return redirect()->route('home')->with('error', 'Request not found.');
    }

    public function unfollow(Request $request)
    {
        $loguser = auth()->user();
        $this->authorize('banned', $loguser);

        \App\Models\Follow::where([
            ['user1_id', '=', $request->user1_id],
            ['user2_id', '=', $request->user2_id],
        ])->delete();

        return response()->json(['message' => 'Unfollowed successfully'], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $loguser = auth()->user();
        $this->authorize('banned', $loguser);
        $user = User::findOrFail($id);

        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $loguser = auth()->user();
        $this->authorize('banned', $loguser);
        // Validate the incoming data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'bio_description' => 'nullable|string|max:1000',
            'type' => 'required|string|in:pet owner,admin,veterinarian,adoption organization,rescue organization',
        ]);

        // Find the user
        $user = User::findOrFail($id);

        // Update the user's attributes
        $user->firstname = $validatedData['name'];
        $user->surname = $validatedData['surname'];

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $fileName = $file->hashName();
            $file->storeAs('profile', $fileName, 'Images');

            // Update profile_picture field
            $user->profile_picture = $fileName;
        }

        $user->bio_description = $validatedData['bio_description'] ?? $user->bio_description;
        $user->type = $validatedData['type'];

        // Save changes
        $user->save();

        // Redirect back with a success message, if its an admin redirect to the admin's home else redirect to the user's profile
        if (auth()->user()->admin) {
            return redirect()->route('admin.home')->with('success', 'Profile updated successfully.');
        }else{
            return redirect()->route('users.show', $id)->with('success', 'Profile updated successfully.');
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $loguser = auth()->user();
        $this->authorize('banned', $loguser);
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    /**
     * Show the settings page.
     */
    public function settings()
    {
        $loguser = auth()->user();
        $this->authorize('banned', $loguser);
        $user = auth()->user();
        return view('settings.show', compact('user'));
    }

    /**
     * Change the password.
     */
    public function updatePassword(Request $request, string $id)
    {
        $loguser = auth()->user();
        $this->authorize('banned', $loguser);
        $validatedData = $request->validate([
            'old' => 'required|string|min:8',
            'new' => 'required|string|min:8',
            'repeat' => 'required|string|min:8',
        ]);

        if ( $validatedData['new'] != $validatedData['repeat'] ) {
            return redirect()->route('settings.show', $id)->with('error', 'Passwords did not match.');
        }

        $user = User::findOrFail($id);

        if (Hash::check($validatedData['old'], $user->password)) {
            $user->password = bcrypt($validatedData['new']);
            $user->save();

            return redirect()->route('home')->with('success', 'Password changed successfully.');
        }

        return redirect()->route('settings.show', $id)->with('error', 'Old password is incorrect.');
    }

    /**
     * Delete a user making it a anonymous user
     */
    public function deleteUser(string $id)
    {
        $loguser = auth()->user();
        $this->authorize('banned', $loguser);
        $loguser = auth()->user();
        $this->authorize('banned', $loguser);
        $user = User::findOrFail($id);
        $user->username = null;
        $user->firstname = null;
        $user->surname = null;
        $user->password = null;
        $user->email = null;
        $user->bio_description = null;
        $user->profile_picture = 'default.jpg';
        $user->is_public = false;
        $user->type = 'deleted';
        $user->save();
        
        if (auth()->user()->admin) {
            return redirect()->route('admin.home')->with('success', 'Profile updated successfully.');
        }else{
            return redirect()->route('/logout', $id)->with('success', 'Profile updated successfully.');
        }
    }

    /**
     * Change the privacity of the user
     */
    public function privacity(Request $request, string $id)   
    {
        $loguser = auth()->user();
        $this->authorize('banned', $loguser);
        $user = User::findOrFail($id);

        $validatedData = $request->validate([
            'public' => 'required|boolean',
        ]);
        $user->is_public = $validatedData['public'];
        $user->save();

        return redirect()->route('settings.show', $id)->with('success', 'Privacity changed successfully.');
    }


}
