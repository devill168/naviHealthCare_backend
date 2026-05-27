<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Register;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = Register::where('username', $data['username'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid username or password',
            ], 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Login success',
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->role,
                'status' => $user->status,
                'profile_image_url' => $user->profile_image
                    ? asset('storage/' . $user->profile_image)
                    : null,
            ],
            'token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function enableBiometric(Request $request)
    {
        $user = $request->user();

        $token = Str::random(80);

        $user->update([
            'biometric_enabled' => true,
            'biometric_token' => hash('sha256', $token),
        ]);

        return response()->json([
            'biometric_token' => $token,
        ]);
    }


}
