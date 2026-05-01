<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEXUS - Weather System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #667eea;
            --secondary: #764ba2;
            --dark: #0f172a;
            --glass: rgba(255,255,255,0.05);
            --glass-border: rgba(255,255,255,0.1);
        }

        * { margin:0; padding:0; box-sizing:border-box; }

        body {
            font-family:'Inter',sans-serif;
            background-color:var(--dark);
            color:white;
            min-height:100vh;
            overflow-x:hidden;
            background-image:
                radial-gradient(circle at 10% 20%, rgba(102,126,234,0.1) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(118,75,162,0.1) 0%, transparent 40%);
        }

        /* --- NAV --- */
        nav {
            display:flex; justify-content:space-between; align-items:center;
            padding:20px 8%;
            background:rgba(15,23,42,0.8); backdrop-filter:blur(15px);
            border-bottom:1px solid var(--glass-border);
            position:sticky; top:0; z-index:1000;
        }
        .logo {
            font-size:26px; font-weight:900;
            background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);
            -webkit-background-clip:text; -webkit-text-fill-color:transparent;
            display:flex; align-items:center; gap:10px;
        }
        .auth-nav { display:flex; align-items:center; gap:25px; }
        .nav-link { text-decoration:none; color:rgba(255,255,255,0.7); font-weight:500; font-size:14px; transition:0.3s; }
        .nav-link:hover { color:white; }
        .btn-auth { padding:10px 24px; border-radius:12px; font-weight:600; font-size:14px; text-decoration:none; transition:all 0.3s ease; }
        .btn-login { color:white; border:1px solid var(--glass-border); }
        .btn-signup { background:linear-gradient(135deg,#667eea 0%,#764ba2 100%); color:white; box-shadow:0 4px 15px rgba(102,126,234,0.3); }
        .btn-signup:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(102,126,234,0.5); }
        .btn-logout { background:rgba(239,68,68,0.2); color:#ef4444; border:1px solid rgba(239,68,68,0.3); padding:8px 18px; border-radius:10px; cursor:pointer; font-weight:600; font-size:14px; font-family:'Inter',sans-serif; transition:0.3s; }
        .btn-logout:hover { background:#ef4444; color:white; }

        /* --- HERO --- */
        .hero { max-width:1000px; margin:0 auto; padding:80px 20px 40px; text-align:center; }
        .hero h1 { font-size:4rem; line-height:1.1; font-weight:900; margin-bottom:25px; letter-spacing:-2px; }
        .hero p { font-size:1.2rem; color:rgba(255,255,255,0.6); margin-bottom:40px; max-width:600px; margin-left:auto; margin-right:auto; }

        /* --- SEARCH --- */
        .search-container { max-width:600px; margin:0 auto; }
        .search-box { display:flex; background:var(--glass); border:1px solid var(--glass-border); padding:8px; border-radius:20px; backdrop-filter:blur(10px); transition:0.3s; }
        .search-box:focus-within { border-color:var(--primary); box-shadow:0 0 20px rgba(102,126,234,0.2); }
        .search-box input { flex:1; background:none; border:none; padding:15px 25px; color:white; font-size:1.1rem; outline:none; }
        .search-box input::placeholder { color:rgba(255,255,255,0.3); }
        .search-btn { background:var(--primary); color:white; border:none; padding:0 35px; border-radius:15px; cursor:pointer; font-weight:700; font-family:'Inter',sans-serif; transition:0.3s; }
        .search-btn:hover { background:var(--secondary); }

        /* --- RECENT SEARCHES --- */
        .recent-searches { max-width:600px; margin:16px auto 0; display:flex; align-items:center; gap:10px; flex-wrap:wrap; justify-content:center; }
        .recent-label { font-size:12px; color:rgba(255,255,255,0.35); text-transform:uppercase; letter-spacing:1px; white-space:nowrap; }
        .recent-pill { padding:6px 16px; border-radius:50px; background:rgba(255,255,255,0.07); border:1px solid rgba(255,255,255,0.12); color:rgba(255,255,255,0.7); font-size:13px; text-decoration:none; transition:0.3s; display:flex; align-items:center; gap:6px; }
        .recent-pill:hover { background:rgba(102,126,234,0.2); border-color:var(--primary); color:white; }
        .recent-pill i { font-size:10px; opacity:0.6; }

        /* --- ALERT --- */
        .alert { padding:15px 25px; border-radius:12px; margin-bottom:30px; display:inline-flex; align-items:center; gap:10px; }
        .alert-success { background:rgba(16,185,129,0.1); border:1px solid rgba(16,185,129,0.2); color:#10b981; }
        .alert-error { background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.2); color:#ef4444; }

        /* --- WEATHER RESULT --- */
        .weather-result { max-width:1000px; margin:0 auto 50px; padding:0 20px; }
        .weather-card { background:var(--glass); border:1px solid var(--glass-border); border-radius:24px; padding:40px; backdrop-filter:blur(10px); margin-bottom:20px; text-align:center; }
        .weather-temp { font-size:5rem; font-weight:900; background:linear-gradient(135deg,#667eea 0%,#764ba2 100%); -webkit-background-clip:text; -webkit-text-fill-color:transparent; line-height:1; margin-bottom:10px; }
        .weather-city { font-size:1.8rem; font-weight:700; margin-bottom:8px; }
        .weather-desc { color:rgba(255,255,255,0.5); font-size:1rem; text-transform:uppercase; letter-spacing:2px; margin-bottom:24px; }
        .weather-icon { font-size:60px; margin-bottom:20px; opacity:0.8; }

        /* Favorite Button */
        .fav-btn-wrap { display:flex; justify-content:center; margin-top:10px; }
        .btn-fav { display:inline-flex; align-items:center; gap:8px; padding:12px 28px; border-radius:50px; font-weight:700; font-size:14px; font-family:'Inter',sans-serif; cursor:pointer; border:none; transition:all 0.3s ease; }
        .btn-fav-add { background:linear-gradient(135deg,#f7971e,#ffd200); color:#000; }
        .btn-fav-add:hover { transform:translateY(-2px); box-shadow:0 8px 20px rgba(247,151,30,0.4); }
        .btn-fav-added { background:rgba(56,239,125,0.15); color:#38ef7d; border:1px solid rgba(56,239,125,0.3); cursor:default; }
        .btn-fav-login { background:var(--glass); color:rgba(255,255,255,0.5); border:1px solid var(--glass-border); text-decoration:none; }
        .btn-fav-login:hover { border-color:var(--primary); color:white; }

        /* Insight */
        .insight-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:16px; }
        .insight-card { background:var(--glass); border:1px solid var(--glass-border); border-radius:20px; padding:24px; display:flex; align-items:center; gap:16px; transition:0.3s; }
        .insight-card:hover { border-color:var(--primary); transform:translateY(-3px); }
        .insight-icon { width:48px; height:48px; border-radius:14px; background:linear-gradient(135deg,rgba(102,126,234,0.2),rgba(118,75,162,0.2)); border:1px solid rgba(102,126,234,0.3); display:flex; align-items:center; justify-content:center; font-size:20px; color:var(--primary); flex-shrink:0; }
        .insight-val { font-size:1.5rem; font-weight:800; display:block; }
        .insight-label { font-size:12px; opacity:0.5; text-transform:uppercase; letter-spacing:1px; }

        /* --- SECTION --- */
        .section-wrap { max-width:1000px; margin:0 auto 60px; padding:0 20px; }
        .section-heading { font-size:0.85rem; font-weight:700; text-transform:uppercase; letter-spacing:3px; color:rgba(255,255,255,0.4); margin-bottom:20px; display:flex; align-items:center; gap:10px; }
        .section-heading::after { content:''; flex:1; height:1px; background:var(--glass-border); }

        /* --- POPULAR CITY CARDS --- */
        .city-cards { display:grid; grid-template-columns:repeat(auto-fit,minmax(280px,1fr)); gap:20px; }
        .city-card {
            border-radius:24px; overflow:hidden;
            position:relative; height:200px;
            background-size:cover; background-position:center;
            cursor:pointer; transition:0.3s;
            text-decoration:none; display:block;
        }
        .city-card:hover { transform:translateY(-5px); box-shadow:0 20px 40px rgba(0,0,0,0.5); }
        .city-card-overlay {
            position:absolute; inset:0;
            background:linear-gradient(to top,rgba(0,0,0,0.85) 0%,rgba(0,0,0,0.15) 60%);
            padding:20px; display:flex; flex-direction:column; justify-content:flex-end;
        }

        /* FIX: teks putih dengan shadow agar terbaca di atas gambar */
        .city-card-temp {
            font-size:2.5rem;
            font-weight:900;
            line-height:1;
            color:#fff;
            text-shadow:0 2px 12px rgba(0,0,0,0.9);
        }
        .city-card-name {
            font-size:1.1rem;
            font-weight:700;
            margin-top:4px;
            color:#fff;
            text-shadow:0 2px 8px rgba(0,0,0,0.9);
        }
        .city-card-desc {
            font-size:12px;
            opacity:0.85;
            text-transform:uppercase;
            letter-spacing:1px;
            margin-top:2px;
            color:#fff;
            text-shadow:0 1px 6px rgba(0,0,0,0.9);
        }
        .city-card-badge {
            position:absolute; top:16px; right:16px;
            background:rgba(0,0,0,0.55);
            backdrop-filter:blur(8px);
            border:1px solid rgba(255,255,255,0.15);
            padding:6px 12px; border-radius:50px;
            font-size:12px; font-weight:600;
            color:#fff;
        }

        /* --- FAVORITES --- */
        .fav-list { display:flex; gap:12px; flex-wrap:wrap; }
        .fav-item { background:var(--glass); border:1px solid var(--glass-border); padding:10px 20px; border-radius:12px; text-decoration:none; color:white; font-size:14px; font-weight:500; transition:0.3s; }
        .fav-item:hover { border-color:var(--primary); background:rgba(102,126,234,0.1); }

        /* --- UNIT TOGGLE --- */
        .unit-toggle { display:flex; background:rgba(255,255,255,0.07); border:1px solid var(--glass-border); border-radius:50px; padding:4px; gap:4px; }
        .unit-btn { padding:6px 20px; border:none; border-radius:50px; background:none; color:rgba(255,255,255,0.5); font-family:'Inter',sans-serif; font-weight:600; font-size:14px; cursor:pointer; transition:0.3s; }
        .unit-btn.active { background:linear-gradient(135deg,#667eea,#764ba2); color:white; }

        /* --- FORECAST --- */
        .forecast-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; }
        .forecast-card {
            background:var(--glass); border:1px solid var(--glass-border);
            border-radius:20px; padding:24px; text-align:center;
            transition:0.3s;
        }
        .forecast-card:hover { transform:translateY(-4px); border-color:var(--primary); }
        .forecast-today { background:rgba(102,126,234,0.1); border-color:rgba(102,126,234,0.4); }
        .forecast-day { font-size:0.95rem; font-weight:700; margin-bottom:4px; }
        .forecast-date { font-size:0.75rem; opacity:0.45; margin-bottom:16px; }
        .forecast-icon { font-size:2.2rem; margin-bottom:14px; opacity:0.85; }
        .forecast-temps { display:flex; justify-content:center; align-items:baseline; gap:10px; margin-bottom:8px; }
        .forecast-max { font-size:1.6rem; font-weight:800; }
        .forecast-min { font-size:1rem; opacity:0.45; }
        .forecast-condition { font-size:12px; opacity:0.55; text-transform:uppercase; letter-spacing:1px; margin-bottom:10px; }
        .forecast-rain { font-size:12px; color:#60a5fa; display:flex; align-items:center; justify-content:center; gap:6px; }

        @media(max-width:640px) { .forecast-grid { grid-template-columns:1fr; } }

        .footer-badges { display:flex; justify-content:center; gap:40px; opacity:0.3; font-size:0.85rem; font-weight:600; text-transform:uppercase; letter-spacing:1px; margin-top:20px; padding-bottom:60px; }

        /* --- TOAST NOTIFICATION --- */
        .toast-wrap { position:fixed; top:24px; right:24px; z-index:9999; display:flex; flex-direction:column; gap:12px; pointer-events:none; }
        .toast {
            display:flex; align-items:center; gap:12px;
            padding:16px 20px; border-radius:16px;
            font-size:14px; font-weight:500;
            backdrop-filter:blur(16px);
            box-shadow:0 8px 32px rgba(0,0,0,0.4);
            animation:slideIn 0.4s ease-out forwards;
            max-width:360px; min-width:260px;
            position:relative; overflow:hidden;
            pointer-events:all;
        }
        .toast-success { background:rgba(16,185,129,0.15); border:1px solid rgba(16,185,129,0.3); color:#10b981; }
        .toast-error   { background:rgba(239,68,68,0.15);  border:1px solid rgba(239,68,68,0.3);  color:#ef4444; }
        .toast-icon { font-size:18px; flex-shrink:0; }
        .toast-msg { flex:1; line-height:1.4; }
        .toast-close { background:none; border:none; color:inherit; opacity:0.6; cursor:pointer; font-size:14px; padding:0; flex-shrink:0; }
        .toast-close:hover { opacity:1; }
        .toast-bar { position:absolute; bottom:0; left:0; height:3px; border-radius:0; animation:shrink 4s linear forwards; }
        .toast-success .toast-bar { background:#10b981; }
        .toast-error   .toast-bar { background:#ef4444; }

        @keyframes slideIn  { from{opacity:0;transform:translateX(120px)} to{opacity:1;transform:translateX(0)} }
        @keyframes slideOut { from{opacity:1;transform:translateX(0)} to{opacity:0;transform:translateX(120px)} }
        @keyframes shrink   { from{width:100%} to{width:0%} }
    </style>
</head>
<body>

    {{-- TOAST NOTIFICATIONS --}}
    <div class="toast-wrap" id="toastWrap">
        @if(session('success'))
            <div class="toast toast-success" id="toast-success">
                <span class="toast-icon"><i class="fas fa-check-circle"></i></span>
                <span class="toast-msg">{{ session('success') }}</span>
                <button class="toast-close" onclick="dismissToast('toast-success')"><i class="fas fa-times"></i></button>
                <div class="toast-bar"></div>
            </div>
        @endif
        @if(session('error'))
            <div class="toast toast-error" id="toast-error">
                <span class="toast-icon"><i class="fas fa-exclamation-circle"></i></span>
                <span class="toast-msg">{{ session('error') }}</span>
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

        {{-- RECENT SEARCHES --}}
        @auth
            @if($recentSearches->count() > 0)
                <div class="recent-searches">
                    <span class="recent-label"><i class="fas fa-history"></i> Terakhir:</span>
                    @foreach($recentSearches as $recent)
                        <a href="{{ route('weather.index', ['city' => $recent->city_name]) }}" class="recent-pill">
                            <i class="fas fa-clock"></i> {{ $recent->city_name }}
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
            $alreadyFav = false;
        @endphp
        @auth
            @php $alreadyFav = $favorites->where('city_name', $cityName)->where('user_id', Auth::id())->count() > 0; @endphp
        @endauth

        <div class="weather-result">
            <div class="weather-card">
                <div class="weather-icon">
                    @php
                        $desc = strtolower($weatherData['current']['weather_descriptions'][0] ?? '');
                        $icon = 'fa-sun';
                        if(str_contains($desc,'cloud'))    $icon='fa-cloud';
                        elseif(str_contains($desc,'rain')) $icon='fa-cloud-showers-heavy';
                        elseif(str_contains($desc,'snow')) $icon='fa-snowflake';
                        elseif(str_contains($desc,'thunder')) $icon='fa-bolt';
                        elseif(str_contains($desc,'clear')) $icon='fa-moon';
                    @endphp
                    <i class="fas {{ $icon }}"></i>
                </div>
                <div class="weather-temp" id="main-temp"
                     data-celsius="{{ $weatherData['current']['temperature'] }}">
                    {{ $weatherData['current']['temperature'] }}°C
                </div>
                <div class="weather-city">{{ $cityName }}, {{ $country }}</div>
                <div class="weather-desc">{{ strtoupper($weatherData['current']['weather_descriptions'][0] ?? '') }}</div>

                {{-- UNIT TOGGLE --}}
                <div style="display:flex;justify-content:center;margin-bottom:16px;">
                    <div class="unit-toggle">
                        <button class="unit-btn active" id="btn-celsius" onclick="setUnit('celsius')">°C</button>
                        <button class="unit-btn" id="btn-fahrenheit" onclick="setUnit('fahrenheit')">°F</button>
                    </div>
                </div>

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
                            <span class="insight-val">{{ $weatherData['current']['humidity'] }}%</span>
                            <span class="insight-label">Humidity</span>
                        </div>
                    </div>
                    <div class="insight-card">
                        <div class="insight-icon"><i class="fas fa-wind"></i></div>
                        <div>
                            <span class="insight-val">{{ $weatherData['current']['wind_speed'] }} km/h</span>
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
                            if(str_contains($fdesc,'cloud'))    $ficon='fa-cloud';
                            elseif(str_contains($fdesc,'rain')) $ficon='fa-cloud-showers-heavy';
                            elseif(str_contains($fdesc,'snow')) $ficon='fa-snowflake';
                            elseif(str_contains($fdesc,'thunder')) $ficon='fa-bolt';
                            elseif(str_contains($fdesc,'clear')) $ficon='fa-moon';
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
                            <div class="forecast-rain">
                                <i class="fas fa-umbrella"></i> {{ $day['chance_rain'] }}% rain
                            </div>
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

    {{-- POPULAR DESTINATIONS --}}
    @if(count($popularWeather) > 0)
        <div class="section-wrap">
            <div class="section-heading"><i class="fas fa-globe-asia" style="color:var(--primary)"></i> Destinasi Populer</div>
            <div class="city-cards">
                @php
                    $cityImages = [
                        'Jakarta' => 'https://images.unsplash.com/photo-1555899434-94d1368aa7af?w=600&q=80',
                        'Tokyo'   => 'https://images.unsplash.com/photo-1540959733332-eab4deabeeaf?w=600&q=80',
                        'Paris'   => 'https://images.unsplash.com/photo-1499856871958-5b9627545d1a?w=600&q=80',
                    ];
                @endphp

                @foreach($popularWeather as $pw)
                    @php
                        $img   = $cityImages[$pw['name']] ?? 'https://images.unsplash.com/photo-1477959858617-67f85cf4f1df?w=600&q=80';
                        $pdesc = strtolower($pw['desc']);
                        $picon = 'fa-sun';
                        if(str_contains($pdesc,'cloud'))    $picon='fa-cloud';
                        elseif(str_contains($pdesc,'rain')) $picon='fa-cloud-showers-heavy';
                        elseif(str_contains($pdesc,'snow')) $picon='fa-snowflake';
                        elseif(str_contains($pdesc,'clear')) $picon='fa-moon';
                    @endphp
                    <a href="{{ route('weather.index', ['city' => $pw['name']]) }}" class="city-card" style="background-image:url('{{ $img }}')">
                        <div class="city-card-overlay">
                            <div class="city-card-temp temp-val" data-celsius="{{ $pw['temp'] }}">{{ $pw['temp'] }}°C</div>
                            <div class="city-card-name">{{ $pw['name'] }}</div>
                            <div class="city-card-desc">{{ $pw['desc'] }}</div>
                        </div>
                        <div class="city-card-badge"><i class="fas {{ $picon }}"></i> {{ $pw['humidity'] }}% humidity</div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- SAVED FAVORITES --}}
    @auth
        @if($favorites->count() > 0)
            <div class="section-wrap">
                <div class="section-heading"><i class="fas fa-star" style="color:#ffd200"></i> Saved Locations</div>
                <div class="fav-list">
                    @foreach($favorites as $fav)
                        <a href="{{ route('weather.index', ['city' => $fav->city_name]) }}" class="fav-item">
                            <i class="fas fa-map-marker-alt" style="margin-right:6px;opacity:0.6;"></i> {{ $fav->city_name }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    @endauth

    <div class="footer-badges">
        <span><i class="fas fa-microchip" style="margin-right:8px;"></i> Smart API</span>
        <span><i class="fas fa-shield-alt" style="margin-right:8px;"></i> Secure Auth</span>
        <span><i class="fas fa-database" style="margin-right:8px;"></i> SQLite Ready</span>
    </div>

    <script>
        // --- UNIT CONVERTER ---
        const defaultUnit = '{{ \App\Models\Setting::get("temperature_unit", "celsius") }}';
        let currentUnit = defaultUnit;

        function toF(c) { return Math.round((c * 9/5) + 32); }

        function setUnit(unit) {
            currentUnit = unit;

            // Toggle button active state
            document.getElementById('btn-celsius').classList.toggle('active', unit === 'celsius');
            document.getElementById('btn-fahrenheit').classList.toggle('active', unit === 'fahrenheit');

            // Update main temp
            const mainTemp = document.getElementById('main-temp');
            if (mainTemp) {
                const c = parseFloat(mainTemp.dataset.celsius);
                mainTemp.textContent = unit === 'celsius' ? `${c}°C` : `${toF(c)}°F`;
            }

            // Update all .temp-val elements
            document.querySelectorAll('.temp-val').forEach(el => {
                const c = parseFloat(el.dataset.celsius);
                const isForecast   = el.classList.contains('forecast-max') || el.classList.contains('forecast-min');
                const isCityCard   = el.classList.contains('city-card-temp');

                if (isForecast) {
                    el.textContent = unit === 'celsius' ? `${c}°` : `${toF(c)}°`;
                } else if (isCityCard) {
                    el.textContent = unit === 'celsius' ? `${c}°C` : `${toF(c)}°F`;
                } else {
                    el.textContent = unit === 'celsius' ? `${c}°C` : `${toF(c)}°F`;
                }
            });
        }

        // Set unit dari setting database saat halaman load
        document.addEventListener('DOMContentLoaded', () => {
            if (defaultUnit === 'fahrenheit') setUnit('fahrenheit');
        });

        // --- TOAST ---
        function dismissToast(id) {
            const el = document.getElementById(id);
            if (!el) return;
            el.style.animation = 'slideOut 0.3s ease-in forwards';
            setTimeout(() => el.remove(), 300);
        }

        document.querySelectorAll('.toast').forEach(toast => {
            setTimeout(() => {
                toast.style.animation = 'slideOut 0.3s ease-in forwards';
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        });
    </script>
</body>
</html>

