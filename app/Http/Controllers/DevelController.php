<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Validator;

class DevelController extends Controller
{   
    public function email(Request $request)
    {
        try 
        {
            Mail::send('mail.user.registered', ['name' => "aa", 'content' => "bb"], function ($message) use ($request)
            {
                $message->from(getenv('MAIL_FROM_ADDRESS'), getenv('MAIL_FROM_NAME'));
                $message->to('saya@echo.web.id');
                $message->subject("Hello from Echo");
            });
        } catch(Exception $e)
        {
            return response()->json(['message' => 'Request failed']); 
        }
        
        return response()->json(['message' => 'Request completed']); 
    }
}