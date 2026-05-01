<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DirectPasswordResetController extends Controller
{
    /**
     * Tampilkan form reset password langsung
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Proses reset password
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => ['required', 'string'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ], [
            'username.required'  => 'Username wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'password.required'  => 'Password baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min'       => 'Password minimal 8 karakter.',
        ]);

        // Cari user berdasarkan username DAN email sekaligus
        $user = User::where('username', $request->username)
                    ->where('email', $request->email)
                    ->first();

        if (!$user) {
            return back()->withErrors([
                'username' => 'Username dan email tidak cocok dengan akun manapun.',
            ])->withInput();
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')
            ->with('status', 'Password berhasil direset! Silakan login dengan password baru.');
    }
}
