<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

    public function updatePassword(ChangePasswordRequest $request)
    {
        $user = Auth::user();
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully.',
        ]);
    }
}
