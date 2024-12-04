<?php

namespace App\Http\Controllers;

use Mail;
use Illuminate\Http\Request;
use App\Mail\MailModel;
use TransportException;
use Exception;

class PasswordController extends Controller
{
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
    
        if (empty($missingVariables)) {

            $mailData = [
                'email' => $request->email,
            ];

            try {
                # TODO: catch case of mail not in database
                Mail::to($request->email)->send(new MailModel($mailData));
                $status = 'Success!';
                $message = 'An email has been sent to ' . $request->email;
            } catch (TransportException $e) {
                $status = 'Error!';
                $message = 'SMTP connection error occurred during the email sending process to ' . $request->email;
            } catch (Exception $e) {
                $status = 'Error!';
                $message = 'An unhandled exception occurred during the email sending process to ' . $request->email;
            }

        } else {
            $status = 'Error!';
            $message = 'The SMTP server cannot be reached due to missing environment variables:';
        }

        $request->session()->flash('status', $status);
        $request->session()->flash('message', $message);
        $request->session()->flash('details', $missingVariables);
        return redirect()->route('login');
    }
}