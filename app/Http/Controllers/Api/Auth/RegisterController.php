<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Mail\WelcomeMail;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        try {

            // check existing email
            $existingUser = User::where('email', $request->email)->first();

            if ($existingUser) {
                return response()->json([
                    'success' => true,
                    'message' => 'User already registered',
                ], 200);
            }

            $validated = $request->validate([
                'first_name' => 'required|string|max:191',
                'last_name' => 'required|string|max:191',
                'phone' => 'required|string|max:191',
                'email' => 'required|string|email|max:191|unique:users,email',
                'password' => [
                    'required',
                    'confirmed',
                    'min:6',
                    'regex:/^[A-Z]/',
                    'regex:/[@$!%*#?&]/'
                ],
                'has_whatsapp' => 'nullable|boolean',
                'has_viber' => 'nullable|boolean',
            ], [
                'password.regex' => 'Password must start with a capital letter and include a special character.',
            ]);

            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'has_whatsapp' => $validated['has_whatsapp'] ?? false,
                'has_viber' => $validated['has_viber'] ?? false,
            ]);

            // Send welcome email (async, don't block registration)
            if ($user) {
                try {
                    Mail::to($user->email)->send(new WelcomeMail($user));
                } catch (\Exception $mailError) {
                    // Log the error but don't fail the registration
                    \Log::error('Welcome email failed for user ' . $user->email . ': ' . $mailError->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'User registered successfully'
            ], 201);
        } catch (ValidationException $e) {

            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Registration error: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => env('APP_DEBUG') ? $e->getMessage() : 'Server error'
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        
        $userData = $user->toArray();
        if ($user->profile_picture) {
            $userData['profile_picture_url'] = asset($user->profile_picture);
        }

        return response()->json([
            'success' => true,
            'token' => $token,
            'token_type' => 'Bearer',
            'data' => $userData
        ]);
    }

    public function profile(Request $request)
    {
        $user = $request->user();
        $userData = $user->toArray();
        if ($user->profile_picture) {
            $userData['profile_picture_url'] = asset($user->profile_picture);
        }
        
        return response()->json([
            'success' => true,
            'data' => $userData
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    public function updateProfile(Request $request)
{
    try {
        $user = $request->user();

        $validated = $request->validate([
            'first_name' => 'sometimes|required|string|max:191',
            'last_name' => 'sometimes|required|string|max:191',
            'phone' => 'sometimes|required|string|max:191|unique:users,phone,' . $user->id,
            'email' => 'sometimes|required|email|max:191|unique:users,email,' . $user->id,
            'new_password' => ['sometimes', 'confirmed', 'min:6'],
            'current_password' => 'required_with:new_password|current_password',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'has_whatsapp' => 'nullable|boolean',
            'has_viber' => 'nullable|boolean',
            'is_email_visible' => 'nullable|boolean',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Upload Profile Picture DIRECTLY to public/profiles and save immediately
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            if ($file->isValid()) {
                $destinationPath = public_path('profiles');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($destinationPath, $filename);

                // Directly save to user model and DB
                $user->profile_picture = 'profiles/' . $filename;
                $user->save();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Password Update
        |--------------------------------------------------------------------------
        */
        if (!empty($validated['new_password'])) {
            $validated['password'] = Hash::make($validated['new_password']);
        }

        unset($validated['new_password']);
        unset($validated['new_password_confirmation']);
        unset($validated['current_password']);

        /*
        |--------------------------------------------------------------------------
        | Boolean Fields
        |--------------------------------------------------------------------------
        */
        $validated['has_whatsapp'] = isset($validated['has_whatsapp']) ? (bool)$validated['has_whatsapp'] : $user->has_whatsapp;
        $validated['has_viber'] = isset($validated['has_viber']) ? (bool)$validated['has_viber'] : $user->has_viber;
        $validated['is_email_visible'] = isset($validated['is_email_visible']) ? (bool)$validated['is_email_visible'] : $user->is_email_visible;

        /*
        |--------------------------------------------------------------------------
        | Update User Other Fields
        |--------------------------------------------------------------------------
        */
        $user->update($validated);
        $user->refresh();

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */
        $userData = $user->toArray();
        if ($user->profile_picture) {
            $userData['profile_picture_url'] = asset($user->profile_picture);
        }

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'user' => $userData
        ], 200);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error updating profile: ' . $e->getMessage()
        ], 500);
    }
}
}
