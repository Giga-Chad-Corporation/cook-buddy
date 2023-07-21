<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;

class MailController extends Controller
{
    public function sendEmail(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'api_token' => 'required',
        ]);

        $user = User::where('email', $validatedData['email'])->first();

        if (!$user || $user->api_token !== $validatedData['api_token']) {
            return response()->json(['error' => 'Invalid email or API key.'], 401);
        }

        // If the api_key matches, send the email
        Mail::to($validatedData['email'])->send(new WelcomeEmail);

        return response()->json(['message' => 'Email sent successfully']);
    }
}
