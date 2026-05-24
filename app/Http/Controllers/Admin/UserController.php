<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Toggle role user antara 'user' dan 'admin'
     */
    public function toggleRole(User $user)
    {
        // Tidak bisa ubah role diri sendiri
        if ($user->id === Auth::id()) {
            return back()->with('settings_saved', 'Tidak bisa mengubah role akun sendiri.');
        }

        $user->role = $user->role === 'admin' ? 'user' : 'admin';
        $user->save();

        $label = $user->role === 'admin' ? 'Admin' : 'User';
        return back()->with('settings_saved', "{$user->name} berhasil diubah menjadi {$label}.");
    }

    /**
     * Hapus user
     */
    public function destroy(User $user)
    {
        // Tidak bisa hapus diri sendiri
        if ($user->id === Auth::id()) {
            return back()->with('settings_saved', 'Tidak bisa menghapus akun sendiri.');
        }

        $name = $user->name;
        $user->delete();

        return back()->with('settings_saved', "User {$name} berhasil dihapus.");
    }
}
