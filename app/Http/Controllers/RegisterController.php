<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Google\Client as Google_Client;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Validator;
class RegisterController extends Controller
{
    public function create()
    {
        return view('session.register');
    }

    public function store()
    {
        $attributes = request()->validate([
            'name' => ['required', 'max:50'],
            'email' => ['required', 'email', 'max:50', Rule::unique('users', 'email')],
            'password' => ['required', 'min:5', 'max:20'],
            'agreement' => ['accepted']
        ]);
        $attributes['password'] = bcrypt($attributes['password'] );

        

        session()->flash('success', 'Your account has been created.');
        $user = User::create($attributes);
        Auth::login($user); 
        return redirect('/dashboard');
    }

    public function googleCallback(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idToken' => 'required|string', 
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
    
        $token_id = $request->input('idToken');
    
        $client = new Google_Client(['client_id' => '1070570385371-6p351s3v9d1tr5mvrqfqhbe4vnn59mhb.apps.googleusercontent.com']);
    
        try {
            $payload = $client->verifyIdToken($token_id);
    
            if (!$payload) {
                return response()->json(['error' => 'Invalid token', 'payload' => $payload], 401);
            }
    
            $userData = [
                'name' => $payload['name'],
                'email' => $payload['email'],
            ];
    
            $user = User::where('email', $userData['email'])->first();
    
            if (!$user) {
                // Create a new user
                $user = User::create($userData);
            } else {
                // Update existing user data if needed
                // ... (implement your update logic if applicable)
            }        
    
            $token = $user->createToken('myapp')->plainTextToken;
    
            // Return relevant user data and token
            return response()->json([
                'user' => $user,
                'token' => $token,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid token: ' . $e->getMessage()], 401);
        }
    }
}
