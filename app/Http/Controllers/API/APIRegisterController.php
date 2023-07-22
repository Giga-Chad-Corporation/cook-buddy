<?php

namespace App\Http\Controllers\API;

use App\Mail\VerificationEmail;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Provider;
use App\Models\ProviderType;
use App\Models\Document;
use App\Models\DocumentType;
use Mail;

class APIRegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'regex:/^[0-9]{10}$/'], // exactly 10 digits
            'is_provider' => ['boolean'],
            'provider_type' => ['required_if:is_provider,1', 'exists:provider_types,id'],
            'document' => ['required_if:is_provider,1', 'file', 'mimes:pdf,doc,docx,png,jpg,jpeg'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            'username' => $request->input('username'),
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'address' => $request->input('address'),
            'phone' => $request->input('phone'),
            'email_verified_at' => null // the email is not yet verified
        ]);
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify', now()->addMinutes(60), ['id' => $user->id, 'hash' => sha1($user->email)]
        );
        // Send the verification email
        Mail::to($user->email)->send(new VerificationEmail($verificationUrl));

        if ($request->input('is_provider')) {
            $providerType = ProviderType::find($request->input('provider_type'));

            if (!$providerType) {
                return response()->json([
                    'error' => 'Type de prestataire invalide',
                ], 422);
            }

            $extension = $request->file('document')->getClientOriginalExtension();
            $documentType = DocumentType::where('type_name', $extension)->first();

            if (!$documentType) {
                return response()->json([
                    'error' => "Type de document incorrect: $extension",
                ], 422);
            }

            $provider = Provider::create([
                'user_id' => $user->id,
                'provider_type_id' => $providerType->id,
            ]);

            $document = new Document();
            $document->document_type_id = $documentType->id;
            $document->provider_id = $provider->id;
            $document->url = $request->file('document')->store('documents', 'public');
            $document->is_valid = false;
            $document->save();
        }

        // Associate the free plan subscription to the user
        $freePlan = Plan::where('name', 'Free')->first();

        if ($freePlan) {
            $subscription = new Subscription();
            $subscription->user_id = $user->id;
            $subscription->plan_id = $freePlan->id;
            $subscription->start_date = now();
            $subscription->save();
        }

        return response()->json([
            'message' => 'Inscrit avec succes ! Veuillez vérifier votre e-mail !',
            'user' => $user
        ], 201);
    }
    public function verify($id, $hash, Request $request)
    {
        $expires = $request->get('expires');

        if ($expires <= now()->timestamp) {
            throw new AuthorizationException('Verification link expired.');
        }

        $user = User::find($id);
        if (! $user || ! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            throw new AuthorizationException;
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json('Email already verified.', 422);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return response()->json('Email verified successfully.');
    }
    public function showVerificationNotice()
    {
        // Check if user is logged in
        if (!auth()->check()) {
            return response()->json('User not logged in.', 401);
        }

        // Check if user has verified email
        if (auth()->user()->hasVerifiedEmail()) {
            return response()->json('Email already verified.', 200);
        }

        // If user is not verified, send a response or redirect them to a front-end page
        return response()->json('Email not verified.', 403);
    }
}
