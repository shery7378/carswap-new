<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\Plan;
use App\Models\Subscription;
use App\Mail\WelcomeMail;
use App\Mail\VerifyEmailOtp;
use Illuminate\Support\Facades\Cache;

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
                    'regex:/[!@#$%^&*(),.?":{}|<>]/',
                ],
                'has_whatsapp' => 'nullable|boolean',
                'has_viber' => 'nullable|boolean',
            ], [
                'password.min' => 'A jelszónak legalább 6 karakter hosszúnak kell lennie. Nagybetűvel kell kezdődnie, és tartalmaznia kell speciális karaktert.',
                'password.regex' => 'A jelszónak legalább 6 karakter hosszúnak kell lennie. Nagybetűvel kell kezdődnie, és tartalmaznia kell speciális karaktert.',
            ]);

            // Generate 6-digit OTP
            $otp = rand(100000, 999999);

            // Store validated data and OTP in cache for 15 minutes
            Cache::put('registration_data_' . $validated['email'], $validated, 900);
            Cache::put('registration_otp_' . $validated['email'], $otp, 900);

            // Send OTP email
            try {
                Mail::to($validated['email'])->send(new VerifyEmailOtp($otp));
            } catch (\Exception $mailError) {
                Log::error('OTP email failed for ' . $validated['email'] . ': ' . $mailError->getMessage());
                // For local debugging, we might want to see the OTP in logs
                if (env('APP_DEBUG')) {
                    Log::info('OTP for ' . $validated['email'] . ': ' . $otp);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Verification code sent to your email address.',
            ], 200);

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

    public function verifyOtp(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'otp' => 'required|numeric',
            ]);

            $cachedOtp = Cache::get('registration_otp_' . $validated['email']);
            $userData = Cache::get('registration_data_' . $validated['email']);

            if (!$cachedOtp || $cachedOtp != $validated['otp']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired verification code.',
                ], 422);
            }

            if (!$userData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Registration data not found. Please register again.',
                ], 422);
            }

            // Create user
            $user = User::create([
                'first_name' => $userData['first_name'],
                'last_name' => $userData['last_name'],
                'phone' => $userData['phone'],
                'email' => $userData['email'],
                'password' => Hash::make($userData['password']),
                'has_whatsapp' => $userData['has_whatsapp'] ?? false,
                'has_viber' => $userData['has_viber'] ?? false,
            ]);

            // Assign FREE package to user
            $freePlan = Plan::where('slug', 'free')->first() ?? Plan::find(8);
            if ($freePlan) {
                Subscription::create([
                    'user_id' => $user->id,
                    'plan_id' => $freePlan->id,
                    'amount' => 0,
                    'status' => 'active',
                    'starts_at' => now(),
                    'next_billing_at' => now()->addYears(10),
                    'ends_at' => now()->addYears(10),
                    'duration' => 'Lifetime (Free)'
                ]);
            }

            // Assign basic user role
            $user->assignRole('user');

            // Send welcome email
            try {
                Mail::to($user->email)->send(new WelcomeMail($user));
            } catch (\Exception $mailError) {
                Log::error('Welcome email failed for user ' . $user->email . ': ' . $mailError->getMessage());
            }

            // Clear cache
            Cache::forget('registration_otp_' . $validated['email']);
            Cache::forget('registration_data_' . $validated['email']);

            $token = $user->createToken('auth_token')->plainTextToken;

            $user->load('activeSubscription.plan');
            $responseUserData = $user->toArray();
            if ($user->profile_picture) {
                $responseUserData['profile_picture_url'] = asset($user->profile_picture);
            }

            return response()->json([
                'success' => true,
                'message' => 'Enail verified and account created successfully',
                'token' => $token,
                'token_type' => 'Bearer',
                'data' => $responseUserData,
                'redirect_to' => '/profile'
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Verification error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error'
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

        $user->load('activeSubscription.plan');
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
        $user->load('activeSubscription.plan');
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
            'new_password' => [
                'sometimes',
                'confirmed',
                'min:6',
                'regex:/^[A-Z]/',
                'regex:/[!@#$%^&*(),.?":{}|<>]/',
            ],
            'current_password' => 'required_with:new_password|current_password',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'has_whatsapp' => 'nullable|boolean',
            'has_viber' => 'nullable|boolean',
            'is_email_visible' => 'nullable|boolean',
        ], [
            'new_password.min' => 'A jelszónak legalább 6 karakter hosszúnak kell lennie. Nagybetűvel kell kezdődnie, és tartalmaznia kell speciális karaktert.',
            'new_password.regex' => 'A jelszónak legalább 6 karakter hosszúnak kell lennie. Nagybetűvel kell kezdődnie, és tartalmaznia kell speciális karaktert.',
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
        $user->load('activeSubscription.plan');

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
