<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEXUS Weather - Admin Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --dark: #1a1a2e; --darker: #16213e;
            --glass: rgba(255,255,255,0.08); --glass-border: rgba(255,255,255,0.12);
            --neon-glow: 0 0 30px rgba(102,126,234,0.5);
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Inter',sans-serif; background:var(--dark); color:white; overflow-x:hidden; }

        /* SIDEBAR */
        .sidebar { position:fixed; left:0; top:0; width:280px; height:100vh; background:linear-gradient(180deg,rgba(26,26,46,0.98) 0%,rgba(22,33,62,0.98) 100%); backdrop-filter:blur(20px); border-right:1px solid var(--glass-border); z-index:1000; transition:all 0.3s ease; overflow-y:auto; }
        .sidebar.collapsed { width:80px; }
        .sidebar.collapsed .nav-text, .sidebar.collapsed .logo-sub { display:none; }
        .logo-section { padding:32px 30px; border-bottom:1px solid var(--glass-border); text-align:center; }
        .logo { font-size:26px; font-weight:900; background:var(--primary); -webkit-background-clip:text; -webkit-text-fill-color:transparent; }
        .logo-sub { font-size:11px; opacity:0.4; margin-top:4px; }
        .nav-menu { padding:20px 0; list-style:none; }
        .nav-item { padding:15px 30px; cursor:pointer; transition:all 0.3s ease; display:flex; align-items:center; gap:16px; color:rgba(255,255,255,0.6); text-decoration:none; font-size:14px; font-weight:500; border-right:4px solid transparent; border:none; background:none; width:100%; font-family:'Inter',sans-serif; }
        .nav-item:hover { background:var(--glass); color:white; }
        .nav-item.active { background:var(--glass); color:white; border-right:4px solid #667eea; box-shadow:var(--neon-glow); }
        .nav-item i { width:20px; text-align:center; font-size:16px; }
        .nav-divider { height:1px; background:var(--glass-border); margin:10px 30px; }

        /* MAIN */
        .main-content { margin-left:280px; min-height:100vh; transition:all 0.3s ease; }
        .main-content.expanded { margin-left:80px; }
        .header { background:var(--glass); backdrop-filter:blur(25px); border-bottom:1px solid var(--glass-border); padding:18px 30px; display:flex; justify-content:space-between; align-items:center; position:sticky; top:0; z-index:900; }
        .header-left { display:flex; align-items:center; gap:15px; }
        .toggle-btn { background:none; border:none; color:white; font-size:18px; cursor:pointer; padding:8px; border-radius:8px; transition:0.3s; }
        .toggle-btn:hover { background:var(--glass); }
        .user-profile { display:flex; align-items:center; gap:12px; padding:8px 18px; background:var(--glass); border-radius:24px; border:1px solid var(--glass-border); }
        .avatar { width:32px; height:32px; border-radius:50%; background:linear-gradient(135deg,#667eea,#764ba2); display:flex; align-items:center; justify-content:center; font-weight:700; font-size:13px; }

        /* SECTIONS */
        .section { display:none; padding:30px 40px; }
        .section.active { display:block; }

        /* STATS */
        .stats-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(280px,1fr)); gap:24px; margin-bottom:30px; }
        .stat-card { background:var(--glass); border:1px solid var(--glass-border); border-radius:20px; padding:28px; transition:all 0.3s ease; }
        .stat-card:hover { transform:translateY(-5px); box-shadow:0 15px 35px rgba(0,0,0,0.3); }
        .stat-icon { width:48px; height:48px; border-radius:14px; background:linear-gradient(135deg,#667eea,#764ba2); display:flex; align-items:center; justify-content:center; font-size:20px; margin-bottom:16px; }
        .stat-number { font-size:42px; font-weight:800; font-family:'JetBrains Mono',monospace; background:linear-gradient(135deg,#667eea,#764ba2); -webkit-background-clip:text; -webkit-text-fill-color:transparent; line-height:1; margin-bottom:6px; }
        .stat-label { font-size:13px; opacity:0.6; }

        /* TABLE */
        .table-card { background:var(--glass); border:1px solid var(--glass-border); border-radius:20px; overflow:hidden; }
        .table-header { padding:20px 28px; background:rgba(102,126,234,0.08); display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid var(--glass-border); }
        .table-header h3 { font-size:15px; font-weight:700; }
        table { width:100%; border-collapse:collapse; }
        th, td { padding:16px 28px; text-align:left; border-bottom:1px solid rgba(255,255,255,0.05); }
        th { font-size:11px; text-transform:uppercase; letter-spacing:1px; opacity:0.5; font-weight:600; }
        tr:last-child td { border-bottom:none; }
        tr:hover td { background:rgba(255,255,255,0.03); }
        .status-badge { padding:5px 12px; border-radius:20px; font-size:11px; font-weight:700; }
        .status-active { background:rgba(56,239,125,0.15); color:#38ef7d; }
        .btn { border:none; border-radius:10px; padding:8px 16px; cursor:pointer; color:white; font-weight:600; font-size:13px; font-family:'Inter',sans-serif; transition:0.3s; }
        .btn-primary { background:linear-gradient(135deg,#667eea,#764ba2); }
        .btn-primary:hover { opacity:0.85; }
        .btn-danger { background:linear-gradient(135deg,#ff416c,#ff4b2b); }
        .btn-danger:hover { opacity:0.85; }
        .btn-sm { padding:5px 12px; font-size:12px; }
        .btn-success { background:linear-gradient(135deg,#11998e,#38ef7d); color:#000; }
        .empty-state { text-align:center; padding:60px 20px; opacity:0.4; }
        .empty-state i { font-size:48px; margin-bottom:15px; display:block; }

        /* SETTINGS */
        .settings-section { display:flex; flex-direction:column; gap:24px; }
        .settings-card { background:var(--glass); border:1px solid var(--glass-border); border-radius:20px; padding:28px; }
        .settings-card-title { font-size:15px; font-weight:700; margin-bottom:20px; padding-bottom:15px; border-bottom:1px solid var(--glass-border); display:flex; align-items:center; gap:10px; }
        .settings-card-title i { color:#667eea; }

        .setting-row { display:flex; justify-content:space-between; align-items:center; padding:14px 0; border-bottom:1px solid rgba(255,255,255,0.05); }
        .setting-row:last-child { border-bottom:none; padding-bottom:0; }
        .setting-label { font-size:14px; font-weight:500; }
        .setting-desc { font-size:12px; opacity:0.45; margin-top:3px; }

        /* Toggle Switch */
        .toggle-switch { position:relative; width:44px; height:24px; flex-shrink:0; }
        .toggle-switch input { opacity:0; width:0; height:0; }
        .slider { position:absolute; cursor:pointer; top:0; left:0; right:0; bottom:0; background:rgba(255,255,255,0.2); border-radius:24px; transition:0.3s; }
        .slider:before { position:absolute; content:""; height:18px; width:18px; left:3px; bottom:3px; background:white; border-radius:50%; transition:0.3s; }
        input:checked + .slider { background:linear-gradient(135deg,#667eea,#764ba2); }
        input:checked + .slider:before { transform:translateX(20px); }

        /* Form Inputs in Settings */
        .setting-input { background:rgba(0,0,0,0.3); border:1px solid var(--glass-border); color:white; padding:9px 14px; border-radius:10px; font-family:'Inter',sans-serif; font-size:13px; outline:none; transition:0.3s; }
        .setting-input:focus { border-color:#667eea; box-shadow:0 0 10px rgba(102,126,234,0.2); }
        .setting-input option { background:#1a1a2e; }
        .setting-input-wide { width:100%; margin-top:8px; }

        /* API Status */
        .api-status { display:flex; align-items:center; gap:10px; }
        .api-dot { width:8px; height:8px; border-radius:50%; background:#38ef7d; box-shadow:0 0 8px #38ef7d; }
        .api-dot.error { background:#ff416c; box-shadow:0 0 8px #ff416c; }
        .api-dot.checking { background:#ffd200; box-shadow:0 0 8px #ffd200; animation:pulse 1s infinite; }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.3} }

        /* Alert */
        .alert-saved { background:rgba(56,239,125,0.1); border:1px solid rgba(56,239,125,0.3); color:#38ef7d; padding:14px 20px; border-radius:12px; margin-bottom:24px; display:flex; align-items:center; gap:10px; font-size:14px; }

        /* Save Button Row */
        .save-row { display:flex; justify-content:flex-end; margin-top:10px; }

        @keyframes fadeInUp { from{opacity:0;transform:translateY(15px)} to{opacity:1;transform:translateY(0)} }
        .animate-in { animation:fadeInUp 0.4s ease-out forwards; }
    </style>
</head>
<body>

    {{-- SIDEBAR --}}
    <nav class="sidebar" id="sidebar">
        <div class="logo-section">
            <div class="logo"><i class="fas fa-bolt"></i> NEXUS</div>
            <div class="logo-sub nav-text">Weather Admin v2.0</div>
        </div>
        <ul class="nav-menu">
            <li><button class="nav-item active" onclick="switchSection('overview', this)"><i class="fas fa-chart-line"></i><span class="nav-text">Overview</span></button></li>
            <li><button class="nav-item" onclick="switchSection('weather', this)"><i class="fas fa-cloud-sun"></i><span class="nav-text">Weather Data</span></button></li>
            <li><button class="nav-item" onclick="switchSection('users', this)"><i class="fas fa-users"></i><span class="nav-text">Users</span></button></li>
            <div class="nav-divider"></div>
            <li><a href="{{ url('/weather') }}" class="nav-item"><i class="fas fa-external-link-alt"></i><span class="nav-text">Main App</span></a></li>
            <li><button class="nav-item" onclick="switchSection('settings', this)"><i class="fas fa-cog"></i><span class="nav-text">Settings</span></button></li>
            <div class="nav-divider"></div>
            <li style="padding:10px 20px;">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" style="width:100%;background:rgba(239,68,68,0.15);color:#ef4444;border:1px solid rgba(239,68,68,0.3);padding:10px;border-radius:10px;cursor:pointer;font-family:'Inter',sans-serif;font-weight:600;font-size:13px;transition:0.3s;display:flex;align-items:center;justify-content:center;gap:8px;">
                        <i class="fas fa-sign-out-alt"></i><span class="nav-text">Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </nav>

    {{-- MAIN CONTENT --}}
    <div class="main-content" id="mainContent">
        <header class="header">
            <div class="header-left">
                <button class="toggle-btn" id="toggleSidebar"><i class="fas fa-bars"></i></button>
                <span style="font-size:1.1rem;font-weight:700;" id="pageTitle">Dashboard Overview</span>
            </div>
            <div class="user-profile">
                <div class="avatar">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}</div>
                <span style="font-size:14px;">{{ Auth::user()->name ?? 'Admin' }}</span>
            </div>
        </header>

        {{-- OVERVIEW --}}
        <div class="section active animate-in" id="section-overview">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                    <div class="stat-number">{{ number_format($totalUsers) }}</div>
                    <div class="stat-label">Total Users</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="stat-number">{{ $totalFavorites }}</div>
                    <div class="stat-label">Total Saved Locations</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-server"></i></div>
                    <div class="stat-number">1.2M</div>
                    <div class="stat-label">API Requests (Monthly)</div>
                </div>
            </div>
            <div class="table-card animate-in">
                <div class="table-header">
                    <h3><i class="fas fa-star" style="margin-right:8px;color:#667eea;"></i> Recent Saved Locations</h3>
                    <button class="btn btn-primary btn-sm" onclick="location.reload()"><i class="fas fa-sync"></i> Refresh</button>
                </div>
                <table>
                    <thead><tr><th>ID</th><th>City</th><th>Added At</th><th>Status</th><th>Action</th></tr></thead>
                    <tbody>
                        @forelse($allFavorites as $fav)
                        <tr>
                            <td style="font-family:'JetBrains Mono';opacity:0.6;">#{{ $fav->id }}</td>
                            <td style="font-weight:600;">{{ $fav->city_name }}</td>
                            <td style="opacity:0.6;font-size:13px;">{{ $fav->created_at->diffForHumans() }}</td>
                            <td><span class="status-badge status-active">ACTIVE</span></td>
                            <td>
                                <form method="POST" action="{{ route('favorites.destroy', $fav->id) }}" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5"><div class="empty-state"><i class="fas fa-inbox"></i> Belum ada data</div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- WEATHER DATA --}}
        <div class="section" id="section-weather">
            <div class="table-card animate-in">
                <div class="table-header">
                    <h3><i class="fas fa-cloud-sun" style="margin-right:8px;color:#667eea;"></i> All Saved Locations</h3>
                    <span style="font-size:13px;opacity:0.5;">{{ $totalFavorites }} total entries</span>
                </div>
                <table>
                    <thead><tr><th>ID</th><th>City Name</th><th>User ID</th><th>Created At</th><th>Action</th></tr></thead>
                    <tbody>
                        @forelse($allFavorites as $fav)
                        <tr>
                            <td style="font-family:'JetBrains Mono';opacity:0.6;">#{{ $fav->id }}</td>
                            <td style="font-weight:600;">{{ $fav->city_name }}</td>
                            <td style="opacity:0.6;">{{ $fav->user_id ?? '-' }}</td>
                            <td style="opacity:0.6;font-size:13px;">{{ $fav->created_at->format('d M Y, H:i') }}</td>
                            <td>
                                <form method="POST" action="{{ route('favorites.destroy', $fav->id) }}" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5"><div class="empty-state"><i class="fas fa-cloud"></i> Belum ada data</div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- USERS --}}
        <div class="section" id="section-users">
            <div class="table-card animate-in">
                <div class="table-header">
                    <h3><i class="fas fa-users" style="margin-right:8px;color:#667eea;"></i> All Users</h3>
                    <span style="font-size:13px;opacity:0.5;">{{ $totalUsers }} total users</span>
                </div>
                <table>
                    <thead><tr><th>ID</th><th>Name</th><th>Username</th><th>Email</th><th>Role</th><th>Joined</th></tr></thead>
                    <tbody>
                        @forelse($allUsers as $user)
                        <tr>
                            <td style="font-family:'JetBrains Mono';opacity:0.6;">#{{ $user->id }}</td>
                            <td style="font-weight:600;">{{ $user->name }}</td>
                            <td style="font-family:'JetBrains Mono';font-size:13px;opacity:0.7;">{{ $user->username ?? '-' }}</td>
                            <td style="opacity:0.7;font-size:13px;">{{ $user->email }}</td>
                            <td>
                                <span class="status-badge" style="{{ $user->role === 'admin' ? 'background:rgba(102,126,234,0.2);color:#667eea;' : 'background:rgba(56,239,125,0.15);color:#38ef7d;' }}">
                                    {{ strtoupper($user->role ?? 'user') }}
                                </span>
                            </td>
                            <td style="opacity:0.6;font-size:13px;">{{ $user->created_at->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="6"><div class="empty-state"><i class="fas fa-user-slash"></i> Belum ada user</div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- SETTINGS --}}
        <div class="section" id="section-settings">

            @if(session('settings_saved'))
                <div class="alert-saved animate-in">
                    <i class="fas fa-check-circle"></i> {{ session('settings_saved') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.settings.update') }}">
                @csrf
                <div class="settings-section">

                    {{-- 1. GENERAL SETTINGS --}}
                    <div class="settings-card animate-in">
                        <div class="settings-card-title">
                            <i class="fas fa-sliders-h"></i> General Settings
                        </div>

                        <div class="setting-row">
                            <div>
                                <div class="setting-label">Maintenance Mode</div>
                                <div class="setting-desc">Jika aktif, user biasa tidak bisa akses website — muncul halaman "Under Maintenance"</div>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" name="maintenance_mode" {{ $settings['maintenance_mode'] === '1' ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </div>

                        <div class="setting-row">
                            <div>
                                <div class="setting-label">User Registration</div>
                                <div class="setting-desc">Izinkan user baru untuk mendaftar akun</div>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" name="user_registration" {{ $settings['user_registration'] === '1' ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </div>

                        <div class="setting-row">
                            <div>
                                <div class="setting-label">Email Verification</div>
                                <div class="setting-desc">Wajibkan verifikasi email saat pendaftaran</div>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" name="email_verification" {{ $settings['email_verification'] === '1' ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </div>

                        <div class="setting-row">
                            <div>
                                <div class="setting-label">Default Temperature Unit</div>
                                <div class="setting-desc">Satuan suhu yang ditampilkan secara global di aplikasi</div>
                            </div>
                            <select name="temperature_unit" class="setting-input">
                                <option value="celsius" {{ $settings['temperature_unit'] === 'celsius' ? 'selected' : '' }}>Celsius (°C)</option>
                                <option value="fahrenheit" {{ $settings['temperature_unit'] === 'fahrenheit' ? 'selected' : '' }}>Fahrenheit (°F)</option>
                            </select>
                        </div>
                    </div>

                    {{-- 2. API CONFIGURATION --}}
                    <div class="settings-card animate-in">
                        <div class="settings-card-title">
                            <i class="fas fa-key"></i> API Configuration
                        </div>

                        <div class="setting-row">
                            <div>
                                <div class="setting-label">Weather API Key Status</div>
                                <div class="setting-desc">Status koneksi ke weather API provider</div>
                            </div>
                            <div class="api-status">
                                <div class="api-dot" id="apiDot"></div>
                                <span id="apiStatusText" style="font-size:13px;opacity:0.8;">Connected</span>
                                <button type="button" class="btn btn-primary btn-sm" onclick="checkApiConnection()" style="margin-left:8px;">
                                    <i class="fas fa-plug"></i> Check
                                </button>
                            </div>
                        </div>

                        <div class="setting-row">
                            <div>
                                <div class="setting-label">API Provider</div>
                                <div class="setting-desc">Layanan API cuaca yang digunakan</div>
                            </div>
                            <span style="font-family:'JetBrains Mono';font-size:13px;opacity:0.7;">{{ $settings['api_provider'] }}</span>
                        </div>

                        <div class="setting-row">
                            <div style="flex:1;">
                                <div class="setting-label">Cache Duration</div>
                                <div class="setting-desc">Berapa menit data cuaca disimpan sebelum mengambil data baru dari API (hemat kuota)</div>
                                <div style="display:flex;align-items:center;gap:10px;margin-top:10px;">
                                    <input type="number" name="cache_duration" value="{{ $settings['cache_duration'] }}" min="1" max="1440" class="setting-input" style="width:100px;">
                                    <span style="font-size:13px;opacity:0.5;">menit</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 3. SITE CUSTOMIZATION --}}
                    <div class="settings-card animate-in">
                        <div class="settings-card-title">
                            <i class="fas fa-paint-brush"></i> Site Customization
                        </div>

                        <div class="setting-row" style="flex-direction:column;align-items:flex-start;">
                            <div>
                                <div class="setting-label">Site Name</div>
                                <div class="setting-desc">Nama aplikasi yang ditampilkan di navbar dan title</div>
                            </div>
                            <input type="text" name="site_name" value="{{ $settings['site_name'] }}" class="setting-input setting-input-wide" placeholder="NEXUS Weather">
                        </div>

                        <div class="setting-row" style="flex-direction:column;align-items:flex-start;">
                            <div>
                                <div class="setting-label">Admin Email</div>
                                <div class="setting-desc">Email kontak untuk sistem dan notifikasi</div>
                            </div>
                            <input type="email" name="admin_email" value="{{ $settings['admin_email'] }}" class="setting-input setting-input-wide" placeholder="admin@example.com">
                        </div>

                        <div class="setting-row" style="flex-direction:column;align-items:flex-start;">
                            <div>
                                <div class="setting-label">Footer Copyright</div>
                                <div class="setting-desc">Teks yang ditampilkan di bagian bawah website</div>
                            </div>
                            <input type="text" name="footer_copyright" value="{{ $settings['footer_copyright'] }}" class="setting-input setting-input-wide" placeholder="© 2026 NEXUS Weather">
                        </div>
                    </div>

                    {{-- SAVE BUTTON --}}
                    <div class="save-row">
                        <button type="submit" class="btn btn-primary" style="padding:14px 40px;font-size:15px;">
                            <i class="fas fa-save"></i> Simpan Semua Pengaturan
                        </button>
                    </div>

                </div>
            </form>
        </div>

    </div>

    <script>
        // Sidebar Toggle
        document.getElementById('toggleSidebar').addEventListener('click', () => {
            document.getElementById('sidebar').classList.toggle('collapsed');
            document.getElementById('mainContent').classList.toggle('expanded');
        });

        // Section Switching
        const pageTitles = {
            overview: 'Dashboard Overview',
            weather:  'Weather Data',
            users:    'User Management',
            settings: 'Settings'
        };

        function switchSection(name, el) {
            document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
            document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
            document.getElementById('section-' + name).classList.add('active');
            if (el) el.classList.add('active');
            document.getElementById('pageTitle').textContent = pageTitles[name] || name;
        }

        // Auto switch to settings if just saved
        @if(session('settings_saved'))
            switchSection('settings', document.querySelector('[onclick="switchSection(\'settings\', this)"]'));
        @endif

        // Check API Connection
        function checkApiConnection() {
            const dot  = document.getElementById('apiDot');
            const text = document.getElementById('apiStatusText');
            dot.className  = 'api-dot checking';
            text.textContent = 'Checking...';

            fetch('{{ route('admin.api.check') }}')
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'connected') {
                        dot.className  = 'api-dot';
                        text.textContent = 'Connected';
                    } else {
                        dot.className  = 'api-dot error';
                        text.textContent = data.message;
                    }
                })
                .catch(() => {
                    dot.className  = 'api-dot error';
                    text.textContent = 'Connection failed';
                });
        }
    </script>
</body>
</html>
