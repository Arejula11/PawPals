<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Models\User;

use App\Mail\PasswordMailModel;
use Mail;
use TransportException;
use Exception;

class PasswordController extends Controller
{
    public function index() {
        
        return view('auth.password');
        
    }

    function send(Request $request) {

        $missingVariables = [];
        $requiredEnvVariables = [
            'MAIL_MAILER',
            'MAIL_HOST',
            'MAIL_PORT',
            'MAIL_USERNAME',
            'MAIL_PASSWORD',
            'MAIL_ENCRYPTION',
            'MAIL_FROM_ADDRESS',
            'MAIL_FROM_NAME',
        ];
    
        foreach ($requiredEnvVariables as $envVar) {
            if (empty(env($envVar))) {
                $missingVariables[] = $envVar;
            }
        }

        $user = User::where('email', $request->email)->first();
        #Log::debug('Email:', ['email' => $request->email]);
        #Log::debug('Username: ', ['username' => $user->firstname]);

        if (!$user) {
            Log::warning('Email not found in the database: ' . $request->email);
            return redirect()->route('password')
                ->withError('This email is not associated with any account. Please try another.');
        }
    
        if (empty($missingVariables)&&$user) {

            $mailData = [
                'email' => $request->email,
                'name' => $user->firstname, // Include the user's name in the mail data
                'new_password' => $this->generateNewPassword($user), // Generate a new password
            ];

            try {
                Mail::to($request->email)->send(new PasswordMailModel($mailData));
                $status = 'Success!';
                $message = 'An email has been sent to ' . $request->email;
                #Log::info('Email successfully sent!');
            } catch (TransportException $e) {
                $status = 'Error!';
                $message = 'SMTP connection error occurred during the email sending process to ' . $request->email;
                #Log::info('Transportexception!');
            } 

        } else {
            $status = 'Error!';
            $message = 'The SMTP server cannot be reached due to missing environment variables:';
        }

        $request->session()->flash('status', $status);
        $request->session()->flash('message', $message);
        $request->session()->flash('details', $missingVariables);

        if ($status='Success') {
            return redirect()->route('login')
            ->withSuccess('A new password has been sent to your email!');
        } else {
            return redirect()->route('password')
            ->withError('');
        }
    }

    private function generateNewPassword(User $user): string
    {
        $newPassword = Str::random(16); // Generate an 8-character random password
        $user->password = Hash::make($newPassword); // Hash the password
        $user->save(); // Save the updated user record
        Log::info('New password generated and saved for user ID: ' . $user->id);

        return $newPassword;
    }
}