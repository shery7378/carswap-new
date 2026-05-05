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
use App\Mail\VerifyEmailLink;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Models\NewsletterSubscriber;

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
                'phone' => 'required|string|max:191|unique:users,phone',
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
                'is_trader' => 'nullable|boolean',
            ], [
                'password.min' => 'A jelszónak legalább 6 karakter hosszúnak kell lennie. Nagybetűvel kell kezdődnie, és tartalmaznia kell speciális karaktert.',
                'password.regex' => 'A jelszónak legalább 6 karakter hosszúnak kell lennie. Nagybetűvel kell kezdődnie, és tartalmaznia kell speciális karaktert.',
                'phone.unique' => 'Ez a telefonszám már regisztrálva van valaki máshoz!',
            ]);

            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'has_whatsapp' => $validated['has_whatsapp'] ?? false,
                'has_viber' => $validated['has_viber'] ?? false,
                'status' => 'inactive',
                'is_trader' => $validated['is_trader'] ?? false,
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

            // Automatically subscribe to newsletter
            try {
                NewsletterSubscriber::updateOrCreate(
                    ['email' => $user->email],
                    ['name' => $user->first_name . ' ' . $user->last_name]
                );
            } catch (\Exception $e) {
                Log::error('Newsletter subscription failed during registration for ' . $user->email . ': ' . $e->getMessage());
            }

            // Generate verification token
            $token = Str::random(64);
            Cache::put('email_verification_' . $token, $user->id, 86400); // 24 hours

            // Send verification email
            try {
                Mail::to($user->email)->send(new VerifyEmailLink($user, $token));
            } catch (\Exception $mailError) {
                Log::error('Verification email failed for ' . $user->email . ': ' . $mailError->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Registration successful. Please check your email to verify your account.',
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

    public function verifyEmail(Request $request)
    {
        $token = $request->query('token');
        if (!$token) {
            return response()->json(['success' => false, 'message' => 'Invalid verification link.'], 400);
        }

        $userId = Cache::get('email_verification_' . $token);
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'Verification link expired or invalid.'], 400);
        }

        $user = User::find($userId);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.'], 404);
        }

        if ($user->status === 'active') {
            return response()->json(['success' => true, 'message' => 'Email already verified.'], 200);
        }

        $user->update([
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        Cache::forget('email_verification_' . $token);

        // Redirect to frontend or return success
        $frontendUrl = env('FRONTEND_URL', 'http://localhost:3000') . '/login?verified=true';
        return redirect()->away($frontendUrl);
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

        if ($user->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Please verify your email address before logging in.'
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $user->load('activeSubscription.plan');
        $userData = $user->toArray();
        if ($user->profile_picture) {
            $userData['profile_picture_url'] = $user->getAvatarUrl();
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
            $userData['profile_picture_url'] = $user->getAvatarUrl();
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
                    // Delete old picture if exists
                    if ($user->getRawOriginal('profile_picture')) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($user->getRawOriginal('profile_picture'));
                    }

                    // Use Laravel Storage for consistency (stored in storage/app/public/profiles)
                    $path = $file->store('profiles', 'public');

                    // Directly save to user model and DB
                    $user->profile_picture = $path;
                    $user->save();
                }
            }

            // Remove profile_picture from validated to prevent overwriting with UploadedFile object in update()
            unset($validated['profile_picture']);

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
            $validated['has_whatsapp'] = isset($validated['has_whatsapp']) ? (bool) $validated['has_whatsapp'] : $user->has_whatsapp;
            $validated['has_viber'] = isset($validated['has_viber']) ? (bool) $validated['has_viber'] : $user->has_viber;
            $validated['is_email_visible'] = isset($validated['is_email_visible']) ? (bool) $validated['is_email_visible'] : $user->is_email_visible;

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
                $userData['profile_picture_url'] = $user->getAvatarUrl();
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
