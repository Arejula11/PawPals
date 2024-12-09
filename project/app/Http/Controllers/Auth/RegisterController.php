<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Facades\Log;

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
        //'is_public' => 'nullable|boolean',
        'type' => 'required|in:pet owner,veterinarian,adoption organization,rescue organization',
        'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $isPublic = $request->has('is_public') ? true : false;
        $fileName = null;

        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $fileName = $file->hashName();
    
            $storedFilePath = $file->storeAs('profile', $fileName, 'Images');
            if ($storedFilePath) {
                Log::debug('File stored successfully', ['path' => $storedFilePath]);
            } else {
                Log::debug('File storage failed');
            }
        } else {
            $fileName = 'default.png';
        }

        $user = User::create([
            'username' => $request->username,
            'firstname' => $request->firstname,
            'surname' => $request->surname,
            'password' => Hash::make($request->password),
            'email' => $request->email,
            'bio_description' => $request->bio_description,
            'is_public' => $isPublic,
            'type' => $request->type,
            'profile_picture' => $fileName,
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('home')->withSuccess('You have successfully registered & logged in!');
        } else {
            return redirect()->back()->withErrors(['login' => 'Login failed after registration']);
        }
    }

    /**
     * Register a new admin user.
     */
    public function registerAdmin(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users|max:255',
            'firstname' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'email' => 'required|email|unique:users|max:255',
        ]);
        $user = User::create([
            'username' => $request->username,
            'firstname' => $request->firstname,
            'surname' => $request->surname,
            'password' => Hash::make($request->password),
            'email' => $request->email,
            'bio_description' => "Platform admin. Here to keep things safe and friendly!",
            'is_public' => true,
            'type' => 'admin',
            'profile_picture' => 'default.png',
            'admin' => true,
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('admin.home')->withSuccess('Admin registered & logged in successfully!');
        } else {
            return redirect()->back()->withErrors(['login' => 'Login failed after registration']);
        }
    }
}
