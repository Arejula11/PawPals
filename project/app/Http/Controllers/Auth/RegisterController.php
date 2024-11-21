<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use App\Models\User;

class RegisterController extends Controller
{
    /**
     * Display a login form.
     */
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users|max:255',
            'firstname' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'email' => 'required|email|unique:users|max:255',
            'bio_description' => 'nullable|string',
            'is_public' => 'nullable|boolean',
            'type' => 'required|in:pet owner,admin,veterinarian,adoption organization,rescue organization',
            'profile_picture' => 'required|integer' //|exists:picture,id'
        ]);

        $user = User::create([
            'username' => $request->username,
            'firstname' => $request->firstname,
            'surname' => $request->surname,
            'password' => Hash::make($request->password),
            'email' => $request->email,
            'bio_description' => $request->bio_description,
            'is_public' => $request->is_public ?? true,
            'admin' => false,
            'type' => $request->type,
            'profile_picture' => $request->profile_picture,
        ]);
    
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('home')->withSuccess('You have successfully registered & logged in!');
        } else {
            return redirect()->back()->withErrors(['login' => 'Login failed after registration']);
        }
    }
}
