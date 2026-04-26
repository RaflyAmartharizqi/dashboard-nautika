<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|min:6|same:password',
            'role' => 'nullable|in:user,admin',
            'username' => 'required|unique:users',
            'institution' => 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'user',
            'username' => $request->username,
            'institution' => $request->institution
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'code' => 200,
            'message' => 'Register berhasil',
            'token' => $token,
            'user' => $user
        ]);
    }

    public function login(Request $request)
    {
        if (!auth()->attempt($request->only('email', 'password'))) {
            return response()->json([
                'code' => 401,
                'message' => 'Email atau password salah'
            ], 401);
        }

        $user = User::where('email', $request->email)->first();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'code' => 200,
            'message' => 'Login berhasil',
            'token' => $token,
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'code' => 200,
            'message' => 'Logout berhasil'
        ]);
    }

    public function sendOtpForgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['code' => 404, 'message' => 'Email tidak ditemukan'], 404);
        }

        $otp = rand(100000, 999999);

        $user->update([
            'otp' => $otp,
            'otp_expired_at' => Carbon::now()->addMinutes(3)
        ]);
        $appName = config('app.name');
        Mail::send([], [], function ($message) use ($user, $otp, $appName) {
        $message->to($user->email)
            ->subject('Kode OTP Reset Password')
            ->html("
                <h1 style='color:#2d89ef; text-align:center;'>$appName</h1>
                <div style='font-family: Arial, sans-serif; padding:20px;'>
                    <h2 style='color:#333;'>Reset Password</h2>
                    <p>Halo,</p>
                    <p>Gunakan kode OTP berikut untuk mereset password kamu:</p>
                    
                    <div style='font-size:30px; font-weight:bold; letter-spacing:5px; margin:20px 0; color:#2d89ef;'>
                        $otp
                    </div>

                    <p>Kode ini berlaku selama <b>3 menit</b>.</p>

                    <p>Jika kamu tidak meminta reset password, abaikan email ini.</p>

                    <br>
                    <p>Terima kasih,<br><b>$appName</b></p>
                </div>
            ");
    });

        return response()->json([
            'code' => 200,
            'message' => 'Kode OTP dikirim ke email'
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required',
            'password' => 'required|min:6|confirmed'
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user || $user->otp != $request->otp) {
            return response()->json(['code' => 400, 'message' => 'OTP salah'], 400);
        }

        if (now()->gt($user->otp_expired_at)) {
            return response()->json(['code' => 400, 'message' => 'OTP sudah kadaluarsa'], 400);
        }

        $user->update([
            'password' => Hash::make($request->password),
            'otp' => null,
            'otp_expired_at' => null
        ]);

        return response()->json([
            'code' => 200,
            'message' => 'Password berhasil direset'
        ]);
    }
}