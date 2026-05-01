<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WeatherService;
use App\Models\Favorite;
use App\Models\RecentSearch;
use Illuminate\Support\Facades\Auth;

class WeatherController extends Controller
{
    protected $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function index(Request $request)
    {
        $city        = $request->input('city');
        $weatherData = null;

        if ($city) {
            // Pakai forecast endpoint (sudah include current + 3 hari)
            $weatherData = $this->weatherService->getWeatherWithForecast($city);

            // Simpan recent search
            if (Auth::check() && isset($weatherData['current'])) {
                RecentSearch::updateOrCreate(
                    ['user_id' => Auth::id(), 'city_name' => $weatherData['location']['name']],
                    ['updated_at' => now()]
                );

                // Hanya simpan 5 terbaru
                $oldest = RecentSearch::where('user_id', Auth::id())
                    ->orderBy('updated_at', 'desc')
                    ->skip(5)->take(100)->pluck('id');

                if ($oldest->count()) {
                    RecentSearch::whereIn('id', $oldest)->delete();
                }
            }
        } else {
            // Default: Jakarta tanpa forecast
            $weatherData = $this->weatherService->getCurrentWeather('Jakarta');
        }

        // Popular cities — pakai current saja (hemat API)
        $popularCities  = ['Jakarta', 'Tokyo', 'Paris'];
        $popularWeather = [];
        foreach ($popularCities as $popularCity) {
            $data = $this->weatherService->getCurrentWeather($popularCity);
            if (isset($data['current'])) {
                $popularWeather[] = [
                    'name'       => $data['location']['name'],
                    'country'    => $data['location']['country'],
                    'temp'       => $data['current']['temperature'],
                    'desc'       => $data['current']['weather_descriptions'][0] ?? '',
                    'humidity'   => $data['current']['humidity'],
                    'wind_speed' => $data['current']['wind_speed'],
                    'feelslike'  => $data['current']['feelslike'],
                ];
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
            'popularWeather',
            'recentSearches',
            'city'
        ));
    }
}
