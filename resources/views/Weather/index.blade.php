<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEXUS - Weather System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

    {{-- TOAST --}}
    <div class="toast-wrap" id="toastWrap">
        @if(session('success'))
            <div class="toast toast-success" id="toast-success">
                <span style="font-size:18px;flex-shrink:0;"><i class="fas fa-check-circle"></i></span>
                <span style="flex:1;line-height:1.4;">{{ session('success') }}</span>
                <button class="toast-close" onclick="dismissToast('toast-success')"><i class="fas fa-times"></i></button>
                <div class="toast-bar"></div>
            </div>
        @endif
        @if(session('error'))
            <div class="toast toast-error" id="toast-error">
                <span style="font-size:18px;flex-shrink:0;"><i class="fas fa-exclamation-circle"></i></span>
                <span style="flex:1;line-height:1.4;">{{ session('error') }}</span>
                <button class="toast-close" onclick="dismissToast('toast-error')"><i class="fas fa-times"></i></button>
                <div class="toast-bar"></div>
            </div>
        @endif
    </div>

    {{-- NAVBAR --}}
    <nav>
        <div class="logo"><i class="fas fa-bolt"></i> NEXUS</div>
        <div class="auth-nav">
            @auth
                <span style="font-size:14px;opacity:0.6;">Welcome, <strong>{{ Auth::user()->name }}</strong></span>
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="fas fa-tachometer-alt"></i> Admin Panel</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn-auth btn-login">Log in</a>
                <a href="{{ route('register') }}" class="btn-auth btn-signup">Sign Up</a>
            @endauth
        </div>
    </nav>

    {{-- HERO --}}
    <main class="hero">
        <h1>Predict the <span style="color:var(--primary)">Future</span> <br> of Your Weather.</h1>
        <p>Dapatkan informasi cuaca akurat di seluruh dunia. Simpan lokasi favoritmu dan kelola datanya melalui dashboard personal.</p>

        <div class="search-container">
            <form action="{{ route('weather.index') }}" method="GET" class="search-box">
                <input type="text" name="city" placeholder="Masukkan nama kota (Contoh: Jakarta)..." value="{{ request('city') }}">
                <button type="submit" class="search-btn"><i class="fas fa-search"></i> Cari Cuaca</button>
            </form>
        </div>

        @auth
            @if($recentSearches->count() > 0)
                <div class="recent-searches">
                    <span class="recent-label"><i class="fas fa-history"></i> Terakhir:</span>
                    @foreach($recentSearches as $recent)
                        <a href="{{ route('weather.index', ['city' => $recent->city_name]) }}" class="recent-pill">
                            <i class="fas fa-clock" style="font-size:10px;opacity:0.6;"></i> {{ $recent->city_name }}
                        </a>
                    @endforeach
                </div>
            @endif
        @endauth
    </main>

    {{-- WEATHER RESULT --}}
    @if(isset($weatherData['current']) && request('city'))
        @php
            $cityName   = $weatherData['location']['name'];
            $country    = $weatherData['location']['country'];
            $lat        = $weatherData['location']['lat'] ?? null;
            $lon        = $weatherData['location']['lon'] ?? null;
            $localtime  = $weatherData['location']['localtime'] ?? null;
            $uvIndex    = $weatherData['current']['uv_index'] ?? 0;
            $humidity   = $weatherData['current']['humidity'];
            $windSpeed  = $weatherData['current']['wind_speed'];
            $chanceRain = $weatherData['forecast'][0]['chance_rain'] ?? 0;
            $alreadyFav = false;

            $desc = strtolower($weatherData['current']['weather_descriptions'][0] ?? '');
            $cardClass = 'default';
            $icon = 'fa-sun';
            if(str_contains($desc,'thunder'))     { $cardClass='stormy'; $icon='fa-bolt'; }
            elseif(str_contains($desc,'snow'))    { $cardClass='snowy';  $icon='fa-snowflake'; }
            elseif(str_contains($desc,'rain'))    { $cardClass='rainy';  $icon='fa-cloud-showers-heavy'; }
            elseif(str_contains($desc,'drizzle')) { $cardClass='rainy';  $icon='fa-cloud-rain'; }
            elseif(str_contains($desc,'cloud') || str_contains($desc,'overcast')) { $cardClass='cloudy'; $icon='fa-cloud'; }
            elseif(str_contains($desc,'clear') || str_contains($desc,'sunny'))    { $cardClass='sunny';  $icon='fa-sun'; }

            $uvLabel = 'Low'; $uvColor = '#38ef7d';
            if($uvIndex >= 11)     { $uvLabel='Extreme';   $uvColor='#9c27b0'; }
            elseif($uvIndex >= 8)  { $uvLabel='Very High'; $uvColor='#ff416c'; }
            elseif($uvIndex >= 6)  { $uvLabel='High';      $uvColor='#ff9800'; }
            elseif($uvIndex >= 3)  { $uvLabel='Moderate';  $uvColor='#ffd200'; }
            $uvPos = min(100, ($uvIndex / 11) * 100);

            $shareText = "🌤 Cuaca {$cityName} sekarang {$weatherData['current']['temperature']}°C, {$weatherData['current']['weather_descriptions'][0]}. Feels like {$weatherData['current']['feelslike']}°C, Humidity {$humidity}%. via NEXUS Weather";
        @endphp
        @auth
            @php $alreadyFav = $favorites->where('city_name', $cityName)->where('user_id', Auth::id())->count() > 0; @endphp
        @endauth

        <div class="weather-result">
            {{-- AREA PENEMPATAN JAM --}}

            {{-- MAIN WEATHER CARD --}}
            <div class="weather-card {{ $cardClass }}">
                <div class="weather-icon"><i class="fas {{ $icon }}"></i></div>
                <div class="weather-temp" id="main-temp" data-celsius="{{ $weatherData['current']['temperature'] }}">
                    {{ $weatherData['current']['temperature'] }}°C
                </div>
                <div class="weather-city">{{ $cityName }}, {{ $country }}</div>

                {{-- AREA PENEMPATAN JAM --}}
                <div class="weather-localtime" style="font-family: 'JetBrains Mono', monospace; font-size: 0.9rem; color: rgba(255,255,255,0.7); margin-top: 5px; margin-bottom: 10px;">
                    <i class="fas fa-clock" style="color: #6366f1; margin-right: 5px;"></i>
                        @if($localTime)
                            {{ $localTime->format('H:i') }}
                            <span style="font-size: 0.75rem; opacity: 0.8;">• {{ $localTime->translatedFormat('l, d M Y') }}</span>
                        @else
                            <span style="font-style: italic;">Jam tidak tersedia</span>
                        @endif
                </div>

                <div class="weather-desc">{{ strtoupper($weatherData['current']['weather_descriptions'][0] ?? '') }}</div>
                {{-- UV INDEX --}}
                <div class="uv-bar-wrap" style="max-width:300px;margin:0 auto 20px;">
                    <div class="uv-label">UV Index</div>
                    <div class="uv-bar">
                        <div class="uv-indicator" style="left:{{ $uvPos }}%;"></div>
                    </div>
                    <div class="uv-text" style="color:{{ $uvColor }}">{{ $uvIndex }} — {{ $uvLabel }}</div>
                </div>

                {{-- UNIT TOGGLE + SHARE --}}
                <div style="display:flex;justify-content:center;align-items:center;gap:12px;margin-bottom:16px;flex-wrap:wrap;">
                    <div class="unit-toggle">
                        <button class="unit-btn active" id="btn-celsius" onclick="setUnit('celsius')">°C</button>
                        <button class="unit-btn" id="btn-fahrenheit" onclick="setUnit('fahrenheit')">°F</button>
                    </div>
                    <button class="btn-share" onclick="shareWeather()">
                        <i class="fas fa-share-alt"></i> Share
                    </button>
                </div>

                {{-- FAVORITE --}}
                <div class="fav-btn-wrap">
                    @auth
                        @if($alreadyFav)
                            <span class="btn-fav btn-fav-added"><i class="fas fa-star"></i> Tersimpan di Favorit</span>
                        @else
                            <form method="POST" action="{{ route('favorites.store') }}">
                                @csrf
                                <input type="hidden" name="city_name" value="{{ $cityName }}">
                                <input type="hidden" name="country"   value="{{ $country }}">
                                <input type="hidden" name="latitude"  value="{{ $lat }}">
                                <input type="hidden" name="longitude" value="{{ $lon }}">
                                <button type="submit" class="btn-fav btn-fav-add"><i class="fas fa-star"></i> Simpan ke Favorit</button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn-fav btn-fav-login"><i class="fas fa-lock"></i> Login untuk Simpan Favorit</a>
                    @endauth
                </div>
            </div>

            {{-- WEATHER INSIGHT --}}
            <div style="margin-bottom:20px;">
                <div class="insight-grid">
                    <div class="insight-card">
                        <div class="insight-icon"><i class="fas fa-tint"></i></div>
                        <div>
                            <span class="insight-val">{{ $humidity }}%</span>
                            <span class="insight-label">Humidity</span>
                        </div>
                    </div>
                    <div class="insight-card">
                        <div class="insight-icon"><i class="fas fa-wind"></i></div>
                        <div>
                            <span class="insight-val">{{ $windSpeed }} km/h</span>
                            <span class="insight-label">Wind Speed</span>
                        </div>
                    </div>
                    <div class="insight-card">
                        <div class="insight-icon"><i class="fas fa-thermometer-half"></i></div>
                        <div>
                            <span class="insight-val temp-val" data-celsius="{{ $weatherData['current']['feelslike'] }}">{{ $weatherData['current']['feelslike'] }}°C</span>
                            <span class="insight-label">Feels Like</span>
                        </div>
                    </div>
                    <div class="insight-card">
                        <div class="insight-icon"><i class="fas fa-eye"></i></div>
                        <div>
                            <span class="insight-val">{{ $weatherData['current']['visibility'] }} km</span>
                            <span class="insight-label">Visibility</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ACTIVITY RECOMMENDATIONS --}}
            @if(isset($recommendations) && count($recommendations) > 0)
                <div class="section-wrap" style="padding:0; margin:0 0 20px;">
                    <div class="section-heading" style="margin-bottom:16px;">
                        <i class="fas fa-lightbulb" style="color:#ffd200"></i> Rekomendasi Aktivitas
                    </div>
                    <div class="activity-grid">
                        @foreach($recommendations as $act)
                            <div class="activity-card {{ $act['type'] }}" style="border-left: 4px solid {{ $act['color'] }};">
                                <div class="activity-icon" style="color: {{ $act['color'] }};"><i class="fas {{ $act['icon'] }}"></i></div>
                                <div>
                                    <div class="activity-title">{{ $act['title'] }}</div>
                                    <div class="activity-desc">{{ $act['desc'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- HOURLY FORECAST --}}
            @if(isset($weatherData['hourly']) && count($weatherData['hourly']) > 0)
            <div class="section-wrap" style="padding:0;margin:0 0 20px;">
                <div class="section-heading" style="margin-bottom:16px;">
                    <i class="fas fa-clock" style="color:var(--primary)"></i> Prakiraan Per Jam
                </div>
                <div class="hourly-scroll">
                    <div id="hourlyScroll" class="hourly-container" style="display: flex; overflow-x: auto; gap: 15px; padding-bottom: 15px; scrollbar-width: none; -ms-overflow-style: none; cursor: grab;">

                    @foreach($weatherData['hourly'] as $index => $hour)
                        @php
                            $hdesc = strtolower($hour['condition']);
                            $hicon = 'fa-sun';
                            if(str_contains($hdesc,'cloud'))      $hicon='fa-cloud';
                            elseif(str_contains($hdesc,'rain'))   $hicon='fa-cloud-showers-heavy';
                            elseif(str_contains($hdesc,'snow'))   $hicon='fa-snowflake';
                            elseif(str_contains($hdesc,'thunder'))$hicon='fa-bolt';
                        @endphp
                        <div class="hourly-card {{ $index === 0 ? 'now' : '' }}">
                            <div class="hourly-time">{{ $index === 0 ? 'Sekarang' : $hour['time'] }}</div>
                            <div class="hourly-icon"><i class="fas {{ $hicon }}"></i></div>
                            <span class="hourly-temp temp-val" data-celsius="{{ $hour['temp'] }}">{{ $hour['temp'] }}°C</span>
                            <div class="hourly-rain"><i class="fas fa-umbrella"></i> {{ $hour['rain'] }}%</div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- HOURLY FORECAST --}}
            <div class="forecast-section mt-5 mb-5">
                <h3 style="color: #fff; font-size: 1.1rem; margin-bottom: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-clock" style="color: #6366f1;"></i> Prakiraan Per 3 Jam
                </h3>

                <!-- Tambahkan ID hourlyScroll di sini -->
                <div id="hourlyScroll" class="hourly-container" style="
                    overflow-x: auto;
                    gap: 15px;
                    padding-bottom: 20px;
                    cursor: grab;
                    -webkit-overflow-scrolling: touch;
                    scrollbar-color: #6366f1 rgba(255,255,255,0.1); /* Untuk Firefox */
                    scrollbar-width: auto; /* Aktifkan scrollbar */">
                    @foreach($hourlyData as $hour)
                        <div class="hourly-card" style="min-width: 110px; background: rgba(255,255,255,0.05); backdrop-filter: blur(10px); padding: 15px; border-radius: 16px; text-align: center; border: 1px solid rgba(255,255,255,0.1); flex-shrink: 0;">
                            <div style="font-size: 0.85rem; opacity: 0.7; font-family: 'JetBrains Mono', monospace;">
                                {{ \Carbon\Carbon::parse($hour['time'])->format('H:i') }}
                            </div>
                            <img src="{{ $hour['condition']['icon'] }}" style="width: 35px; margin: 10px auto; filter: drop-shadow(0 0 5px rgba(255,255,255,0.3));">
                            <div style="font-size: 1.1rem; font-weight: 700;">{{ round($hour['temp_c']) }}°</div>
                            <div style="font-size: 0.65rem; opacity: 0.6; margin-top: 4px; text-transform: uppercase; letter-spacing: 1px;">
                                {{ $hour['condition']['text'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            {{-- 3-DAY FORECAST --}}
            @if(isset($weatherData['forecast']) && count($weatherData['forecast']) > 0)
            <div style="margin-bottom:20px;">
                <div class="section-heading" style="margin-bottom:16px;">
                    <i class="fas fa-calendar-alt" style="color:var(--primary)"></i> 3-Day Forecast
                </div>
                <div class="forecast-grid">
                    @foreach($weatherData['forecast'] as $index => $day)
                        @php
                            $dayName = $index === 0 ? 'Hari Ini' : ($index === 1 ? 'Besok' : \Carbon\Carbon::parse($day['date'])->translatedFormat('l'));
                            $fdesc   = strtolower($day['condition']);
                            $ficon   = 'fa-sun';
                            if(str_contains($fdesc,'cloud'))      $ficon='fa-cloud';
                            elseif(str_contains($fdesc,'rain'))   $ficon='fa-cloud-showers-heavy';
                            elseif(str_contains($fdesc,'snow'))   $ficon='fa-snowflake';
                            elseif(str_contains($fdesc,'thunder'))$ficon='fa-bolt';
                        @endphp
                        <div class="forecast-card {{ $index === 0 ? 'forecast-today' : '' }}">
                            <div class="forecast-day">{{ $dayName }}</div>
                            <div class="forecast-date">{{ \Carbon\Carbon::parse($day['date'])->format('d M') }}</div>
                            <div class="forecast-icon"><i class="fas {{ $ficon }}"></i></div>
                            <div class="forecast-temps">
                                <span class="forecast-max temp-val" data-celsius="{{ $day['max_temp'] }}">{{ $day['max_temp'] }}°</span>
                                <span class="forecast-min temp-val" data-celsius="{{ $day['min_temp'] }}">{{ $day['min_temp'] }}°</span>
                            </div>
                            <div class="forecast-condition">{{ $day['condition'] }}</div>
                            <div class="forecast-rain"><i class="fas fa-umbrella"></i> {{ $day['chance_rain'] }}% rain</div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

    @elseif(request('city'))
        <div style="text-align:center;padding:20px;">
            <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> Kota tidak ditemukan atau API limit tercapai.</div>
        </div>
    @endif

    {{-- SAVED FAVORITES --}}
    @auth
        @if($favorites->count() > 0)
            <div class="section-wrap">
                <div class="section-heading"><i class="fas fa-star" style="color:#ffd200"></i> Saved Locations</div>
                <div class="fav-list">
                    @foreach($favorites as $fav)
                        <div style="display:flex;align-items:center;gap:8px;">
                            <a href="{{ route('weather.index', ['city' => $fav->city_name]) }}" class="fav-item">
                                <i class="fas fa-map-marker-alt" style="margin-right:6px;opacity:0.6;"></i> {{ $fav->city_name }}
                            </a>
                            <form action="{{ route('favorites.destroy', $fav->id) }}" method="POST" onsubmit="return confirm('Hapus dari favorit?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.2);color:#ef4444;padding:8px 12px;border-radius:10px;cursor:pointer;transition:0.3s;">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endauth

    <div class="footer-badges">
        <span><i class="fas fa-microchip" style="margin-right:8px;"></i> Smart API</span>
        <span><i class="fas fa-shield-alt" style="margin-right:8px;"></i> Secure Auth</span>
        <span><i class="fas fa-database" style="margin-right:8px;"></i> MySQL Ready</span>
    </div>

    {{-- Share text hidden --}}
    <span id="share-text" style="display:none;">{{ $shareText ?? '' }}</span>

    {{-- Inject Blade variable ke JS sebelum load script.js --}}
    <script>
        const defaultUnit = '{{ \App\Models\Setting::get("temperature_unit", "celsius") }}';
    </script>
    <script src="{{ asset('js/script.js') }}"></script>

    <script>
    const scrollContainer = document.getElementById('hourlyScroll');

    if (scrollContainer) {
        scrollContainer.addEventListener('wheel', (evt) => {
            evt.preventDefault();
            // Menambah kecepatan scroll horizontal berdasarkan wheel mouse
            scrollContainer.scrollLeft += evt.deltaY * 2;
        });

        // Opsional: Efek "Grab to Scroll" agar bisa ditarik pakai mouse (seperti mobile)
        let isDown = false;
        let startX;
        let scrollLeft;

        scrollContainer.addEventListener('mousedown', (e) => {
            isDown = true;
            scrollContainer.style.cursor = 'grabbing';
            startX = e.pageX - scrollContainer.offsetLeft;
            scrollLeft = scrollContainer.scrollLeft;
        });
        scrollContainer.addEventListener('mouseleave', () => {
            isDown = false;
            scrollContainer.style.cursor = 'grab';
        });
        scrollContainer.addEventListener('mouseup', () => {
            isDown = false;
            scrollContainer.style.cursor = 'grab';
        });
        scrollContainer.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - scrollContainer.offsetLeft;
            const walk = (x - startX) * 2; // Kecepatan geser
            scrollContainer.scrollLeft = scrollLeft - walk;
        });
    }
    </script>
</body>
</html>
