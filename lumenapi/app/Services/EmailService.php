<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Exceptions\EmailNotSendException;

class EmailService
{

    protected $commonModel;

    public function __construct()
    {
        
    }

    /**
     * To send the forgot password link email
     */
    public function sendForgotPasswordEmail($senderEmail, $senderName, $url_link)
    {
        try {
            $html = 'We received a request to reset the password associated with this e-mail address.<br/> 
                 If you made this request, please follow the instructions below.<br/><br/>

                Click on the link below to reset your password using our secure server:
                <a href="' . $url_link . '">Reset Link</a> .<br/>If you did not request to have your password reset you can safely 
                    ignore this email. Rest assured your customer account is safe.';
            
            Mail::send([], ['html'], function($message) use ($senderName, $senderEmail, $html) {
                $message->from(config('mail.forgotpassword_from_email'), config('mail.forgotpassword_from_name'));
                $message->subject('Reset Password Assistance');
                $message->to($senderEmail, $senderName);
                $message->setBody($html, 'text/html');
            });
        } catch (\Exception $e) {
             throw new EmailNotSendException(config('mts-config.forgot_passowrd.email_fail'),1070);
        }

        return true;
    }

}
