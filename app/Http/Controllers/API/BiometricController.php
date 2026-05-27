<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Register;
use App\Models\Biometric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BiometricController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:registers,email',
            'password' => 'required|string',
            'device_id' => 'required|string',
        ]);

        $user = Register::where('email', $request->email)->first();

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid password'
            ], 401);
        }

        $biometricToken = Str::random(80);

        Biometric::updateOrCreate(
            [
                'register_id' => $user->id,
                'device_id' => $request->device_id,
            ],
            [
                'biometric_type' => 'face_id',
                'biometric_token' => hash('sha256', $biometricToken),
                'is_enabled' => true,
            ]
        );

        return response()->json([
            'message' => 'Biometric enabled successfully',
            'biometric_token' => $biometricToken,
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:registers,email',
            'device_id' => 'required|string',
            'biometric_token' => 'required|string',
        ]);

        $user = Register::where('email', $request->email)->first();

        $biometric = Biometric::where('register_id', $user->id)
            ->where('device_id', $request->device_id)
            ->where('is_enabled', true)
            ->first();

        if (!$biometric) {
            return response()->json([
                'message' => 'Biometric not enabled'
            ], 403);
        }

        if (!hash_equals($biometric->biometric_token, hash('sha256', $request->biometric_token))) {
            return response()->json([
                'message' => 'Invalid biometric token'
            ], 401);
        }

        $token = $user->createToken('mobile-biometric-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    public function disable(Request $request)
    {
        $request->validate([
            'device_id' => 'required|string',
        ]);

        Biometric::where('register_id', $request->user()->id)
            ->where('device_id', $request->device_id)
            ->update(['is_enabled' => false]);

        return response()->json([
            'message' => 'Biometric disabled successfully'
        ]);
    }
}