<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Mail\ContactMailModel;
use Mail;
use TransportException;
use Exception;

class StaticController extends Controller
{
    /**
     * Display the about us page.
     */
    public function showAbout()
    {
        return view('static.about');
    }

    /**
     * Display the contact page.
     */
    public function showContact()
    {
        return view('static.contact');
    }

    /**
     * Display the about us page.
     */
    public function showFAQ()
    {
        return view('static.faq');
    }

    /**
     * Send a mail from the contact form.
     */
    public function sendContact(Request $request) 
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'topic' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        $missingVariables = [];
        $requiredEnvVariables = [
            'MAIL_MAILER',
            'MAIL_HOST',
            'MAIL_PORT',
            'MAIL_USERNAME',
            'MAIL_PASSWORD',
            'MAIL_ENCRYPTION',
            'MAIL_TO_ADDRESS_CONTACT',
            'MAIL_TO_NAME_CONTACT',
        ];
    
        foreach ($requiredEnvVariables as $envVar) {
            if (empty(env($envVar))) {
                $missingVariables[] = $envVar;
            }
        }
    
        if (empty($missingVariables)) {

            $mailData = [
                'email' => $request->email,
                'topic' => $request->topic,
                'message' => $request->message,
            ];

            try {
                Mail::to(env('MAIL_TO_ADDRESS_CONTACT'))->send(new ContactMailModel($mailData));
                $status = 'Success!';
                $message = 'An email has been sent to ' . env('MAIL_TO_ADDRESS_CONTACT');
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
            return redirect()->route('static.contact')
            ->withSuccess('Your contact form was successfully sent! We will contact you soon.');
        } else {
            return redirect()->route('static.contact')
            ->withError('This message could not be sent. Please check that you have entered a valid email.');
        }
    }

}
