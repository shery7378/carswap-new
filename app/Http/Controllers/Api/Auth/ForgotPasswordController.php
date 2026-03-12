<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ForgotPasswordController extends Controller
{
    /**
     * Send password reset email
     */
    public function sendResetEmail(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email|exists:users,email',
            ], [
                'email.exists' => 'This email address does not exist in our system.',
            ]);

            // Generate a unique token
            $token = Str::random(60);

            // Delete existing reset tokens for this email
            DB::table('password_resets')->where('email', $validated['email'])->delete();

            // Store the reset token
            DB::table('password_resets')->insert([
                'email' => $validated['email'],
                'token' => Hash::make($token),
                'created_at' => now(),
            ]);

            // Send email with reset link
            $user = User::where('email', $validated['email'])->first();
            
            try {
                Mail::to($validated['email'])->send(new ResetPasswordMail($user, $token));
            } catch (\Exception $mailError) {
                Log::error('Mail sending error: ' . $mailError->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Email configuration error: ' . $mailError->getMessage(),
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Password reset link has been sent to your email address.',
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Forgot password error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Reset password with token
     */
    public function resetPassword(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email|exists:users,email',
                'token' => 'required|string',
                'password' => [
                    'required',
                    'confirmed',
                    'min:6',
                    'regex:/^[A-Z]/',
                    'regex:/[@$!%*#?&]/'
                ],
            ], [
                'password.regex' => 'Password must start with a capital letter and include a special character.',
            ]);

            // Check if token exists and is valid
            $resetRecord = DB::table('password_resets')
                ->where('email', $validated['email'])
                ->first();

            if (!$resetRecord) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired reset token.',
                ], 400);
            }

            // Verify token
            if (!Hash::check($validated['token'], $resetRecord->token)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired reset token.',
                ], 400);
            }

            // Check if token is not older than 1 hour
            if ($resetRecord->created_at < now()->subHour()) {
                DB::table('password_resets')->where('email', $validated['email'])->delete();
                return response()->json([
                    'success' => false,
                    'message' => 'Password reset token has expired.',
                ], 400);
            }

            // Update user password
            $user = User::where('email', $validated['email'])->first();
            $user->update([
                'password' => Hash::make($validated['password']),
            ]);

            // Delete the reset token
            DB::table('password_resets')->where('email', $validated['email'])->delete();

            return response()->json([
                'success' => true,
                'message' => 'Password has been reset successfully.',
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while resetting the password.',
            ], 500);
        }
    }

    /**
     * Verify reset token
     */
    public function verifyToken(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email|exists:users,email',
                'token' => 'required|string',
            ]);

            $resetRecord = DB::table('password_resets')
                ->where('email', $validated['email'])
                ->first();

            if (!$resetRecord) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired reset token.',
                ], 400);
            }

            if (!Hash::check($validated['token'], $resetRecord->token)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired reset token.',
                ], 400);
            }

            if ($resetRecord->created_at < now()->subHour()) {
                DB::table('password_resets')->where('email', $validated['email'])->delete();
                return response()->json([
                    'success' => false,
                    'message' => 'Password reset token has expired.',
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'Token is valid.',
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while verifying the token.',
            ], 500);
        }
    }
}
