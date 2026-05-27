<?php

namespace App\Http\Controllers;

use App\Models\Register;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class RegisterController extends Controller
{
    public function index(){
        $users = Register::latest()->get()->map(function ($u) {
            return [
                "id" => $u->id,
                "username" => $u->username,
                "gender" => $u->gender,
                "email" => $u->email,
                "phone" => $u->phone,
                "role_name" => $u->role ? $u->role->name : null,
                "status" => $u->status,
                "profile_image_url" => $u->profile_image
                    ? url(Storage::url($u->profile_image))
                    : null,
            ];
        });

        return response()->json([
            "data" => $users
        ]);
    }

    public function store(Request $request){
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:registers,username'],
            'gender' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:registers,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'string', 'min:6', 'confirmed'], // needs password_confirmation
            'role_id' => ['required', 'exists:roles,id'],
            'status' => ['required', Rule::in(['active', 'inactive', 'suspended'])],
            'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        // Upload image if exists
        $path = null;
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profiles', 'public');
        }

        $user = Register::create([
            'username' => $validated['username'],
            'gender' => $validated['gender'], 
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role_id' => $validated['role_id'],
            'status' => $validated['status'],
            'profile_image' => $path,
        ]);
          // ✅ append full image url
        $user->profile_image_url = $path ? Storage::url($path) : null;

        return response()->json([
            'message' => 'User created',
             'data' => [
                'id' => $user->id,
                'username' => $user->username,
                'gender' => $user->gender,
                'email' => $user->email,
                'phone' => $user->phone,
                'role_id' => $user->role_id,
                'role_name' => $user->role?->name,
                'status' => $user->status,
                'profile_image_url' => $path ? url(Storage::url($path)) : null,
            ],
        ], 201);
    }

    public function update(Request $request, $id){
        $user = Register::findOrFail($id);

        $validated = $request->validate([
            'username' => ['required','string','max:255'],
            'gender'   => ['nullable','in:male,female,other'],
            'email'    => ['required','email', Rule::unique('registers','email')->ignore($user->id)],
            'phone'    => ['nullable','string','max:50'],
            'role_id'  => ['required','exists:roles,id'],
            'status'   => ['required','in:active,inactive,suspended'],

            // optional password update
            'password' => ['nullable','string','min:6','confirmed'],

            // optional image update
            'profile_image' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ]);

        $validated['gender'] = $request->filled('gender')
        ? $request->gender
        : null;

        // update normal fields
        $user->username = $validated['username'];
        $user->gender   = $validated['gender'] ?? null;
        $user->email    = $validated['email'];
        $user->phone    = $validated['phone'] ?? null;
        $user->role_id  = $validated['role_id'];
        $user->status   = $validated['status'];

        // update password if provided
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        // update image if provided
        if ($request->hasFile('profile_image')) {

            // delete old image if stored
            if (!empty($user->profile_image) && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $path = $request->file('profile_image')->store('profiles', 'public');
            $user->profile_image = $path;
        }

        $user->save();
        $user->load('role');

        return response()->json([
            'message' => 'Updated successfully',
            'data' => [
                'id' => $user->id,
                'username' => $user->username,
                'gender' => $user->gender,
                'email' => $user->email,
                'phone' => $user->phone,
                'role_name' => $user->role?->name,
                'status' => $user->status,
                'profile_image_url' => $user->profile_image
                    ? url(Storage::url($user->profile_image))
                    : null,
            ]
        ]);
    }

    public function destroy($id)
    {
        $user = Register::findOrFail($id);

        // delete image if stored (optional)
        if ($user->profile_image) {
            // example: if you stored path like "profiles/xxx.jpg" in DB
            Storage::disk('public')->delete($user->profile_image);
        }

        $user->delete();

        return response()->json([
            'message' => 'Deleted successfully'
        ], 200);
    }
}