<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'city_name' => 'required|string',
            'country'   => 'required|string',
        ]);

        // Anti-duplikasi
        $exists = Favorite::where('user_id', Auth::id())
                          ->where('city_name', $request->city_name)
                          ->exists();

        if ($exists) {
            return back()->with('success', $request->city_name . ' sudah ada di daftar favorit kamu!');
        }

        Favorite::create([
            'user_id'   => Auth::id(),
            'city_name' => $request->city_name,
            'country'   => $request->country,
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return back()->with('success', $request->city_name . ' berhasil ditambahkan ke favorit!');
    }

    public function destroy($id)
    {
        // Admin bisa hapus favorit siapapun
        // User biasa hanya bisa hapus miliknya sendiri
        if (Auth::user()->isAdmin()) {
            $favorite = Favorite::findOrFail($id);
        } else {
            $favorite = Favorite::where('id', $id)
                                ->where('user_id', Auth::id())
                                ->firstOrFail();
        }

        $cityName = $favorite->city_name;
        $favorite->delete();

        return back()->with('success', $cityName . ' berhasil dihapus dari favorit.');
    }
}
