<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Group;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\Log;
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

    /**
     * Show all posts
     */
    public function postsManage()
    {
        $user = auth()->user();
        $this->authorize('admin', $user);
        $posts = Post::orderBy('creation_date', 'desc')->simplePaginate(10);
        return view('admin.postsManage', compact('posts'));
    }

    /**
     * Show a post
     */
    public function showPost() {

        $user = auth()->user();
        $this->authorize('admin', $user);
        return view('admin.showPost');
        
    }

    /**
     * Show the form for editing the specified post.
     */
    public function editPost(string $id)
    {
        $user = auth()->user();
        $this->authorize('admin', $user);
        $post = Post::findOrFail($id);
        return view('admin.editPost', compact('post'));
    }

    /**
     * Update the specified post in storage.
     */
    public function updatePost(Request $request, string $id)
    {
        $user = auth()->user();
        $this->authorize('admin', $user);
        $validated = $request->validate([
            'description' => 'required|string|max:255',

        ]);

        $post = Post::findOrFail($id);
        $post->update($validated);

        return redirect()->route('admin.posts.manage')->with('success', 'Post updated successfully.');
    }

    /**
     * Remove the specified post from storage.
     */
    public function destroyPost(string $id)
    {
        $user = auth()->user();
        $this->authorize('admin', $user);
        $post = Post::findOrFail($id);
        $post->delete();

        return redirect()->route('admin.posts.manage')->with('success', 'Post deleted successfully.');
    }

    /**
     * Show the specified comment.
     */
    public function showComment(string $id)
    {
        $user = auth()->user();
        $this->authorize('admin', $user);
        $comment = Comment::findOrFail($id);
        return view('admin.showComment', compact('comment'));
    }

    /**
     * Update the specified comment in storage.
     */
    public function updateComment(Request $request, string $id)
    {
        $user = auth()->user();
        $this->authorize('admin', $user);
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $comment = Comment::findOrFail($id);
        $comment->update($validated);

        return redirect()->route('admin.home', $id)->with('success', 'Comment updated successfully.');
    }

    /**
     * Remove the specified comment from storage.
     */
    public function destroyComment(string $id)
    {
        $user = auth()->user();
        $this->authorize('admin', $user);
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return redirect()->route('admin.posts.manage')->with('success', 'Comment deleted successfully.');
    }


}
