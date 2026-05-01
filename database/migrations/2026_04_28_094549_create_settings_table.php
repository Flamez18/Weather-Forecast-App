<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Seed default values
        $defaults = [
            ['key' => 'maintenance_mode',   'value' => '0'],
            ['key' => 'user_registration',  'value' => '1'],
            ['key' => 'email_verification', 'value' => '0'],
            ['key' => 'temperature_unit',   'value' => 'celsius'],
            ['key' => 'cache_duration',     'value' => '30'],
            ['key' => 'api_provider',       'value' => 'Weatherstack'],
            ['key' => 'site_name',          'value' => 'NEXUS Weather'],
            ['key' => 'admin_email',        'value' => 'admin@nexus.com'],
            ['key' => 'footer_copyright',   'value' => '© 2026 NEXUS Weather. All rights reserved.'],
        ];

        foreach ($defaults as $setting) {
            DB::table('settings')->insert(array_merge($setting, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
