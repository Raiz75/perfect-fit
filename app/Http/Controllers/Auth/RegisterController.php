<?php

namespace App\Http\Controllers\Auth;

use App\Actions\CopyDefaults;
use App\Mail\VerificationCodeMail;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SendVerificationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        if (auth()->check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.register');
    }

    public function sendVerification(SendVerificationRequest $request)
    {
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        session([
            'verification_code' => $code,
            'verification_email' => $request->email,
            'verification_password' => $request->password,
        ]);

        Mail::to($request->email)->send(new VerificationCodeMail($code));

        return redirect()->route('admin.register', ['verify' => $request->email])
            ->with('success', 'Verification code sent to your email.');
    }

    public function verifyRegistration(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $storedCode = session('verification_code');
        $email = session('verification_email');
        $password = session('verification_password');

        if (!$storedCode || !$email || !$password) {
            return redirect()->route('admin.register')
                ->withErrors(['verify' => 'Session expired. Please sign up again.']);
        }

        if ($request->code !== $storedCode) {
            return redirect()->route('admin.register', ['verify' => $email])
                ->withErrors(['verify' => 'Incorrect verification code.']);
        }

        if (User::where('email', $email)->exists()) {
            session()->forget(['verification_code', 'verification_email', 'verification_password']);
            return redirect()->route('admin.register')
                ->withErrors(['email' => 'This email is already registered.']);
        }

        $admin = User::create([
            'name' => $email,
            'email' => $email,
            'password' => Hash::make($password),
            'church_code' => $this->generateChurchCode(),
            'church_name' => null,
        ]);

        session()->forget(['verification_code', 'verification_email', 'verification_password']);

        app(CopyDefaults::class)->handle($admin->id);

        return redirect()->route('admin.login')
            ->with('success', 'Email verified and account created! Please sign in.');
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
