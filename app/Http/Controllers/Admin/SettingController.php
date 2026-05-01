<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SettingController extends Controller
{
    public function update(Request $request)
    {
        Setting::set('maintenance_mode',   $request->has('maintenance_mode') ? '1' : '0');
        Setting::set('user_registration',  $request->has('user_registration') ? '1' : '0');
        Setting::set('email_verification', $request->has('email_verification') ? '1' : '0');
        Setting::set('temperature_unit',   $request->input('temperature_unit', 'celsius'));
        Setting::set('cache_duration',     $request->input('cache_duration', '30'));
        Setting::set('site_name',          $request->input('site_name', 'NEXUS Weather'));
        Setting::set('admin_email',        $request->input('admin_email', ''));
        Setting::set('footer_copyright',   $request->input('footer_copyright', ''));

        return redirect()->route('admin.dashboard')
            ->with('settings_saved', 'Pengaturan berhasil disimpan!');
    }

    public function checkApi()
    {
        try {
            $response = Http::timeout(5)
                ->withoutVerifying()
                ->get('https://api.weatherapi.com/v1/current.json', [
                    'key' => config('services.weatherapi.key'),
                    'q'   => 'Jakarta',
                    'aqi' => 'no',
                ]);

            $data = $response->json();

            if (isset($data['current'])) {
                return response()->json([
                    'status'  => 'connected',
                    'message' => 'WeatherAPI terhubung dengan baik!',
                ]);
            }

            return response()->json([
                'status'  => 'error',
                'message' => $data['error']['message'] ?? 'API key tidak valid.',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Tidak dapat terhubung ke API.',
            ]);
        }
    }
}
