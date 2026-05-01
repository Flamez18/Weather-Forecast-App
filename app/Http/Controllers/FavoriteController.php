<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function store(Request $request)
{
    // 1. Validasi input
    $request->validate([
        'city_name' => 'required|string',
        'country'   => 'required|string',
    ]);

    // 2. Cek apakah user sudah memfavoritkan kota ini [Nomor 2]
    $isAlreadyFavorite = Favorite::where('user_id', Auth::id())
                                ->where('city_name', $request->city_name)
                                ->exists();

    if ($isAlreadyFavorite) {
        return back()->with('error', 'Kota ' . $request->city_name . ' sudah ada di daftar favoritmu!');
    }

    // 3. Simpan jika belum ada
    Favorite::create([
        'user_id'   => Auth::id(),
        'city_name' => $request->city_name,
        'country'   => $request->country,
        'latitude'  => $request->latitude,
        'longitude' => $request->longitude,
    ]);

    return back()->with('success', 'Lokasi berhasil ditambahkan ke favorit!');
    }

    public function destroy($id)
    {
        // Pastikan hanya pemilik yang bisa menghapus
        $favorite = Favorite::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $favorite->delete();

        return back()->with('success', 'Lokasi berhasil dihapus.');
    }
}
