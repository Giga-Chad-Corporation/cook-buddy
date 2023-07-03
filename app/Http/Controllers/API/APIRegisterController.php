<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Provider;
use App\Models\ProviderType;
use App\Models\Document;
use App\Models\DocumentType;

class APIRegisterController extends Controller
{
    public function register(Request $request)
    {
        var_dump("register");
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'is_provider' => ['boolean'],
            'provider_type' => ['required_if:is_provider,1', 'exists:provider_types,id'],
            'document' => ['required_if:is_provider,1', 'file', 'mimes:pdf,doc,docx'],
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
        ]);

        if ($request->input('is_provider')) {
            $providerType = ProviderType::find($request->input('provider_type'));

            if (!$providerType) {
                return response()->json([
                    'error' => 'Invalid provider type selected.',
                ], 422);
            }

            $extension = $request->file('document')->getClientOriginalExtension();
            $documentType = DocumentType::where('type_name', $extension)->first();

            if (!$documentType) {
                return response()->json([
                    'error' => "Unsupported document type: $extension",
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

        return response()->json([
            'message' => 'User registered successfully.',
            'user' => $user
        ], 201);
    }
}

