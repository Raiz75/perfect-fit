<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('admin.settings', compact('user'));
    }

    public function updateChurchName(Request $request)
    {
        $request->validate([
            'church_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'church_name')->ignore(Auth::id()),
            ],
        ]);

        $user = Auth::user();
        $user->church_name = $request->church_name;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Church name updated successfully.',
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/[A-Z]/', 'regex:/\d/', 'regex:/[^A-Za-z0-9]/'],
        ], [
            'current_password.current_password' => 'Wrong current password.',
        ]);

        $user = Auth::user();
        $user->password = $request->new_password;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully.',
        ]);
    }
}
