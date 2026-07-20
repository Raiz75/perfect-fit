<?php

namespace App\Http\Controllers\Auth;

use App\Mail\TemporaryPasswordMail;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('admin.forgot-password');
    }

    public function sendTempPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email not found.',
            ], 404);
        }

        $tempPassword = Str::random(10);
        $user->password = Hash::make($tempPassword);
        $user->save();

        Mail::to($user->email)->send(new TemporaryPasswordMail($tempPassword));

        return response()->json([
            'success' => true,
            'message' => 'Temporary password sent to your email.',
        ]);
    }
}