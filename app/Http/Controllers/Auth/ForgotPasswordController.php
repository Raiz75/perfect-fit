<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\TemporaryPasswordMail;
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

        if (! $user) {
            return redirect()->route('admin.login')
                ->with('error', 'Email not found.');
        }

        $tempPassword = Str::random(10);
        $user->password = Hash::make($tempPassword);
        $user->save();

        Mail::to($user->email)->send(new TemporaryPasswordMail($tempPassword));

        return redirect()->route('admin.login')
            ->with('success', 'Temporary password sent to your email.');
    }
}
