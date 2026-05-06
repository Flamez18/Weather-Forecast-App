<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WeatherService;
use App\Services\RecommendationService;
use App\Models\Favorite;
use App\Models\RecentSearch;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class WeatherController extends Controller
{
    // Deklarasikan semua service di sini
    protected $weatherService;
    protected $recommendationService;

    // Gabungkan inject service dalam satu constructor
    public function __construct(WeatherService $weatherService, RecommendationService $recommendationService)
    {
        $this->weatherService = $weatherService;
        $this->recommendationService = $recommendationService;
    }

    public function index(Request $request)
    {
        $city        = $request->input('city');
        $weatherData = null;

        if ($city) {
            $weatherData = $this->weatherService->getWeatherWithForecast($city);

            if (Auth::check() && isset($weatherData['current'])) {
                RecentSearch::updateOrCreate(
                    ['user_id' => Auth::id(), 'city_name' => $weatherData['location']['name']],
                    ['updated_at' => now()]
                );

                $oldest = RecentSearch::where('user_id', Auth::id())
                    ->orderBy('updated_at', 'desc')
                    ->skip(5)->take(100)->pluck('id');

                if ($oldest->count()) {
                    RecentSearch::whereIn('id', $oldest)->delete();
                }
            }
        } else {
            $weatherData = $this->weatherService->getCurrentWeather('Jakarta');
        }

        // --- Logika Rekomendasi (Panggil Service) ---
        $recommendations = [];
        if (isset($weatherData['current'])) {
            // Kita ambil data dari weatherData untuk dikirim ke RecommendationService
            $recommendations = $this->recommendationService->getRecommendations(
                $weatherData['current']['temp_c'] ?? $weatherData['current']['temperature'] ?? 0,
                $weatherData['forecast']['forecastday'][0]['day']['daily_chance_of_rain'] ?? 0,
                $weatherData['current']['uv'] ?? 0,
                $weatherData['current']['condition']['text'] ?? $weatherData['current']['weather_descriptions'][0] ?? ''
            );
        }

        // Logika Local Time
        $localTime = null;
        if (isset($weatherData['location'])) {
            $timezone = $weatherData['location']['timezone_id'] ?? 'Asia/Jakarta';
            try {
                $localTime = Carbon::now($timezone);
            } catch (\Exception $e) {
                $localTime = Carbon::now();
            }
        }

        // --- LOGIKA HOURLY FORECAST DI SINI ---
        $hourlyData = collect();

        if (isset($weatherData['forecast']['forecastday'][0]['hour'])) {
            $hourlyData = collect($weatherData['forecast']['forecastday'][0]['hour'])->filter(function($value, $key) {
                return $key % 3 == 0;
            });
        } else if (isset($weatherData['current'])) {
            // Ambil data current sebagai referensi fallback
            $current = $weatherData['current'];

            // Deteksi otomatis icon dari berbagai jenis API (WeatherAPI vs Weatherstack)
            $fallbackIcon = $current['condition']['icon']
                            ?? $current['weather_icons'][0]
                            ?? '';

            $fallbackText = $current['condition']['text']
                            ?? $current['weather_descriptions'][0]
                            ?? 'Cloudy';

            $currentTemp = $current['temp_c'] ?? $current['temperature'] ?? 20;

            for ($i = 1; $i <= 8; $i++) {
                $hourlyData->push([
                    'time' => now()->addHours($i * 3)->format('Y-m-d H:i'),
                    'temp_c' => $currentTemp + rand(-2, 2),
                    'condition' => [
                        'icon' => $fallbackIcon,
                        'text' => $fallbackText
                    ]
                ]);
            }
        }

        $favorites = Auth::check()
            ? Favorite::where('user_id', Auth::id())->get()
            : collect();

        $recentSearches = Auth::check()
            ? RecentSearch::where('user_id', Auth::id())
                ->orderBy('updated_at', 'desc')
                ->take(5)->get()
            : collect();

        return view('weather.index', compact(
            'weatherData',
            'favorites',
            'recentSearches',
            'city',
            'localTime',
            'hourlyData',
            'recommendations' // Pastikan dikirim ke view
        ));
    }
}
