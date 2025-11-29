<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        Auth::login($user);

        // Set session untuk MikroTik aktif jika ada (dari database)
        $user->load('activeMikroTik');
        if ($user->active_mikrotik_id && $user->activeMikroTik) {
            $mikrotik = $user->activeMikroTik;
            session([
                'mikrotik_id' => $mikrotik->id,
                'mikrotik_ip' => $mikrotik->ip_address,
                'mikrotik_user' => $mikrotik->username,
                'mikrotik_password' => $mikrotik->password,
            ]);
        }

        return redirect()->route('dashboard.index');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.index');
    }
}


