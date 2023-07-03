<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Provider;
use App\Models\ProviderType;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'document' => ['required_if:is_provider,1'],
        ], [
            'document.required_if' => 'The document field is required if you want to register as a provider.',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            'username' => $input['username'],
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        // Check if the user is registering as a provider
        if (isset($input['is_provider']) && $input['is_provider'] == 1) {
            $validator = Validator::make($input, [
                'provider_type' => ['required', 'exists:provider_types,id'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 422);
            }

            $extension = $input['document']->getClientOriginalExtension();
            $documentType = DocumentType::where('type_name', $extension)->first();

            if (!$documentType) {
                throw ValidationException::withMessages([
                    'document' => "Unsupported document type: $extension",
                ]);
            }

            $providerType = ProviderType::find($input['provider_type']);

            if (!$providerType) {
                throw ValidationException::withMessages([
                    'provider_type' => "Invalid provider type selected.",
                ]);
            }

            $provider = Provider::create([
                'user_id' => $user->id,
                'provider_type_id' => $providerType->id,
            ]);

            Document::create([
                'document_type_id' => $documentType->id,
                'provider_id' => $provider->id,
                'url' => $input['document']->store('documents', 'public'),
                'is_valid' => 0,
            ]);
        }

        return response()->json([
            'message' => 'User registered successfully.',
            'user' => $user
        ], 201);
    }
}
