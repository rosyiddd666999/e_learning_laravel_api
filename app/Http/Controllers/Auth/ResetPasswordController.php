<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ResetPasswordController extends Controller
{
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if token exists and is valid
        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$passwordReset) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token or email'
            ], 400);
        }

        // Check if token matches
        if (!Hash::check($request->token, $passwordReset->token)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token or email'
            ], 400);
        }

        // Check if token is expired (60 minutes)
        $tokenAge = Carbon::parse($passwordReset->created_at)->diffInMinutes(Carbon::now());
        if ($tokenAge > 60) {
            return response()->json([
                'success' => false,
                'message' => 'Token has expired'
            ], 400);
        }

        // Find user and update password
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(60)
        ]);

        // Delete token after successful reset
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Revoke all existing tokens after password reset
        $user->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password has been reset successfully'
        ], 200);
    }
}
