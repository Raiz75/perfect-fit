<?php

namespace App\Http\Controllers\Auth;

use App\Actions\CopyDefaults;
use App\Mail\VerificationCodeMail;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CheckEmailRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\SendVerificationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    public function checkEmail(CheckEmailRequest $request)
    {
        $exists = User::where('email', $request->email)->exists();

        return response()->json(['exists' => $exists]);
    }

    public function sendVerification(SendVerificationRequest $request)
    {
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        session(['verification_code' => $code, 'verification_email' => $request->email]);

        Mail::to($request->email)->send(new VerificationCodeMail($code));

        return response()->json([
            'success' => true,
            'message' => 'Verification code sent.',
        ]);
    }

    public function register(RegisterRequest $request, CopyDefaults $copyDefaults)
    {
        $admin = User::create([
            'name' => $request->email,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'church_code' => $this->generateChurchCode(),
            'church_name' => null,
        ]);

        $copyDefaults->handle($admin->id);

        return response()->json([
            'success' => true,
            'message' => 'Account created successfully.',
        ]);
    }

    public function verifyCode(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $storedCode = session('verification_code');
        $storedEmail = session('verification_email');

        if (!$storedCode || !$storedEmail) {
            return response()->json([
                'success' => false,
                'message' => 'No verification code was sent. Please request a new one.',
            ], 400);
        }

        if ($request->code !== $storedCode) {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect verification code.',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Code verified.',
        ]);
    }

    public function validateChurchCode(Request $request)
    {
        $request->validate(['church_code' => 'required|string|max:9']);

        $exists = DB::selectOne(
            'SELECT id FROM users WHERE BINARY church_code = ?',
            [$request->church_code]
        );

        return response()->json(['exists' => (bool) $exists]);
    }

    private function generateChurchCode(): string
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        do {
            $code = '';
            for ($i = 0; $i < 9; $i++) {
                $code .= $chars[random_int(0, strlen($chars) - 1)];
            }
        } while (DB::selectOne('SELECT id FROM users WHERE BINARY church_code = ?', [$code]));

        return $code;
    }
}
