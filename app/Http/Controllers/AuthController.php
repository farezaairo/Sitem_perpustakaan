<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
   public function loginWeb(Request $request)
{
    $credentials = $request->only('email', 'password');

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return back()->withErrors(['email' => 'User tidak ditemukan']);
    }

  
    if (Auth::attempt($credentials)) {

        $request->session()->regenerate();

        return match($user->peran) {
            'admin' => redirect()->route('dashboard.admin'),
            'siswa' => redirect()->route('dashboard.siswa'),
            'kepala_perpustakaan' => redirect()->route('dashboard.kepala'),
            default => abort(403, 'Role tidak dikenali'),
        };
    }

    return back()->withErrors(['password' => 'Password salah']);
}

public function logout(Request $request)
{
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
}

}
