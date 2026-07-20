<?php

namespace App\Http\Controllers\Auth;

use App\Mail\VerificationCodeMail;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function checkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $exists = User::where('email', $request->email)->exists();

        return response()->json(['exists' => $exists]);
    }

    public function sendVerification(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        session(['verification_code' => $code, 'verification_email' => $request->email]);

        Mail::to($request->email)->send(new VerificationCodeMail($code));

        return response()->json([
            'success' => true,
            'message' => 'Verification code sent.',
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $admin = User::create([
            'name' => $request->email,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'church_code' => $this->generateChurchCode(),
            'church_name' => null,
        ]);

        $this->copyDefaults($admin->id);

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

    private function copyDefaults(int $newUserId): void
    {
        $tables = [
            'demographic_restrictions' => ['user_id', 'ministry_id', 'gender', 'age_min', 'age_max', 'marital_status', 'baptized', 'time_in_faith'],
            'skill_restrictions' => ['user_id', 'ministry_id', 'music', 'technology', 'writing', 'technical', 'speaking', 'accounting', 'mentoring', 'bible_knowledge'],
            'skill_questions' => ['user_id', 'skill_id', 'question_number', 'question_en', 'question_tl'],
            'interest_and_passion_questions' => ['user_id', 'ministry_category_id', 'question_number', 'question_en', 'question_tl'],
            'behavioral_questions' => ['user_id', 'ministry_id', 'question_number', 'question_en', 'question_tl'],
        ];

        foreach ($tables as $table => $columns) {
            $rows = DB::table($table)->where('user_id', 1)->get();
            foreach ($rows as $row) {
                $insert = [];
                foreach ($columns as $col) {
                    $insert[$col] = $col === 'user_id' ? $newUserId : $row->$col;
                }
                DB::table($table)->insert($insert);
            }
        }
    }
}