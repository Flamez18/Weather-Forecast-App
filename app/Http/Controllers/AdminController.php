<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Favorite;
use App\Models\Setting;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $totalUsers     = User::count();
        $totalFavorites = Favorite::count();
        $allFavorites   = Favorite::latest()->get();
        $allUsers       = User::latest()->get();

        // Ambil semua settings sebagai array key => value
        $settings = Setting::pluck('value', 'key')->toArray();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalFavorites',
            'allFavorites',
            'allUsers',
            'settings'
        ));
    }
}
