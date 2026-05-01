<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\Setting;

class WeatherService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey  = config('services.weatherapi.key');
        $this->baseUrl = 'https://api.weatherapi.com/v1/';
    }

    /**
     * Ambil cuaca real-time saja
     */
    public function getCurrentWeather(string $city): array
    {
        $cacheKey      = 'weather_current_' . strtolower(trim($city));
        $cacheDuration = (int) Setting::get('cache_duration', 30);

        return Cache::remember($cacheKey, now()->addMinutes($cacheDuration), function () use ($city) {
            $response = Http::timeout(10)->withoutVerifying()->get($this->baseUrl . 'current.json', [
                'key' => $this->apiKey,
                'q'   => $city,
                'aqi' => 'no',
            ]);

            if (!$response->successful()) return [];

            $data = $response->json();

            return $this->formatCurrent($data);
        });
    }

    /**
     * Ambil cuaca + forecast 3 hari sekaligus (1 API call)
     */
    public function getWeatherWithForecast(string $city): array
    {
        $cacheKey      = 'weather_forecast_' . strtolower(trim($city));
        $cacheDuration = (int) Setting::get('cache_duration', 30);

        return Cache::remember($cacheKey, now()->addMinutes($cacheDuration), function () use ($city) {
            $response = Http::timeout(10)->withoutVerifying()->get($this->baseUrl . 'forecast.json', [
                'key'  => $this->apiKey,
                'q'    => $city,
                'days' => 3,
                'aqi'  => 'no',
            ]);

            if (!$response->successful()) return [];

            $data = $response->json();

            $result = $this->formatCurrent($data);

            // Tambahkan data forecast
            $result['forecast'] = [];
            foreach ($data['forecast']['forecastday'] ?? [] as $day) {
                $result['forecast'][] = [
                    'date'          => $day['date'],
                    'max_temp'      => $day['day']['maxtemp_c'],
                    'min_temp'      => $day['day']['mintemp_c'],
                    'avg_temp'      => $day['day']['avgtemp_c'],
                    'chance_rain'   => $day['day']['daily_chance_of_rain'],
                    'condition'     => $day['day']['condition']['text'],
                    'humidity'      => $day['day']['avghumidity'],
                    'wind_speed'    => $day['day']['maxwind_kph'],
                ];
            }

            return $result;
        });
    }

    /**
     * Format response WeatherAPI → format standar aplikasi
     */
    private function formatCurrent(array $data): array
    {
        return [
            'location' => [
                'name'    => $data['location']['name']    ?? '',
                'country' => $data['location']['country'] ?? '',
                'lat'     => $data['location']['lat']     ?? null,
                'lon'     => $data['location']['lon']     ?? null,
            ],
            'current' => [
                'temperature'          => $data['current']['temp_c']              ?? 0,
                'feelslike'            => $data['current']['feelslike_c']         ?? 0,
                'humidity'             => $data['current']['humidity']             ?? 0,
                'wind_speed'           => $data['current']['wind_kph']            ?? 0,
                'visibility'           => $data['current']['vis_km']              ?? 0,
                'weather_descriptions' => [$data['current']['condition']['text']  ?? 'Unknown'],
                'weather_icons'        => [$data['current']['condition']['icon']  ?? ''],
                'uv_index'             => $data['current']['uv']                  ?? 0,
                'pressure'             => $data['current']['pressure_mb']         ?? 0,
            ],
        ];
    }

    public function forceRefresh(string $city): array
    {
        Cache::forget('weather_current_'  . strtolower(trim($city)));
        Cache::forget('weather_forecast_' . strtolower(trim($city)));
        return $this->getWeatherWithForecast($city);
    }

    public static function clearAll(): void
    {
        Cache::flush();
    }
}
