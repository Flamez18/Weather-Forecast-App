<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEXUS Weather - Login</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;500;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root { --neon-cyan:#00f5ff; --neon-purple:#d400ff; --neon-pink:#ff0080; --glass:rgba(255,255,255,0.08); --glass-border:rgba(255,255,255,0.15); }
        * { margin:0; padding:0; box-sizing:border-box; cursor:none; }
        body { background:#000; color:#fff; font-family:'Inter',sans-serif; min-height:100vh; display:flex; align-items:center; justify-content:center; overflow-x:hidden; }
        #cursor { position:fixed; width:10px; height:10px; background:var(--neon-cyan); border-radius:50%; pointer-events:none; z-index:9999; box-shadow:0 0 15px var(--neon-cyan); }
        #cursor-follower { position:fixed; width:35px; height:35px; border:1px solid var(--neon-purple); border-radius:50%; pointer-events:none; z-index:9998; transition:transform 0.15s ease-out; }
        .bg-glow { position:fixed; top:0; left:0; width:100%; height:100%; background:radial-gradient(circle at 50% 50%,#1a0033 0%,#000 100%); z-index:-2; }
        #particles { position:fixed; top:0; left:0; width:100%; height:100%; z-index:-1; }

        .auth-wrapper { width:100%; max-width:460px; padding:20px; }
        .logo { text-align:center; font-weight:900; font-size:1.8rem; letter-spacing:2px; color:var(--neon-cyan); margin-bottom:8px; text-shadow:0 0 20px var(--neon-cyan); }
        .logo-sub { text-align:center; font-size:0.75rem; letter-spacing:5px; color:rgba(255,255,255,0.4); text-transform:uppercase; margin-bottom:40px; font-family:'JetBrains Mono',monospace; }

        .tab-toggle { display:flex; background:rgba(255,255,255,0.05); border:1px solid var(--glass-border); border-radius:50px; padding:4px; margin-bottom:30px; }
        .tab-btn { flex:1; padding:12px; border:none; border-radius:50px; background:none; color:rgba(255,255,255,0.5); font-family:'Inter',sans-serif; font-size:0.9rem; font-weight:500; letter-spacing:1px; cursor:none; transition:0.3s; text-decoration:none; text-align:center; display:block; }
        .tab-btn.active { background:var(--neon-cyan); color:#000; font-weight:700; }

        .auth-card { background:var(--glass); border:1px solid var(--glass-border); border-radius:30px; padding:40px; backdrop-filter:blur(20px); }

        .form-group { margin-bottom:20px; }
        .form-group label { display:block; font-size:0.75rem; letter-spacing:2px; text-transform:uppercase; color:rgba(255,255,255,0.5); margin-bottom:8px; font-family:'JetBrains Mono',monospace; }
        .input-wrap { position:relative; }
        .input-wrap i { position:absolute; left:18px; top:50%; transform:translateY(-50%); color:var(--neon-purple); font-size:14px; pointer-events:none; }
        .form-group input { width:100%; background:rgba(0,0,0,0.5); border:1px solid var(--glass-border); padding:15px 20px 15px 48px; border-radius:15px; color:#fff; outline:none; font-family:'Inter',sans-serif; font-size:0.95rem; transition:0.3s; }
        .form-group input:focus { border-color:var(--neon-cyan); box-shadow:0 0 15px rgba(0,245,255,0.15); }
        .form-group input::placeholder { color:rgba(255,255,255,0.25); }
        .field-error { color:var(--neon-pink); font-size:0.8rem; margin-top:6px; display:flex; align-items:center; gap:5px; }

        .remember-row { display:flex; align-items:center; gap:10px; margin-bottom:20px; }
        .remember-row input[type="checkbox"] { accent-color:var(--neon-cyan); width:16px; height:16px; }
        .remember-row label { font-size:0.85rem; color:rgba(255,255,255,0.5); cursor:none; }

        .forgot-link { display:block; text-align:right; font-size:0.8rem; color:var(--neon-purple); text-decoration:none; margin-top:-10px; margin-bottom:20px; transition:0.2s; }
        .forgot-link:hover { color:var(--neon-cyan); }

        .btn-submit { width:100%; padding:16px; border:none; border-radius:50px; background:linear-gradient(135deg,var(--neon-cyan),var(--neon-purple)); color:#000; font-weight:900; font-size:0.95rem; letter-spacing:2px; text-transform:uppercase; cursor:none; transition:0.3s; font-family:'Inter',sans-serif; }
        .btn-submit:hover { opacity:0.85; transform:translateY(-2px); box-shadow:0 0 30px rgba(0,245,255,0.3); }

        .alert-success { background:rgba(0,245,255,0.08); border:1px solid rgba(0,245,255,0.3); color:var(--neon-cyan); padding:14px 18px; border-radius:12px; margin-bottom:20px; font-size:0.88rem; display:flex; align-items:center; gap:10px; }
    </style>
</head>
<body>
    <div id="cursor"></div>
    <div id="cursor-follower"></div>
    <div class="bg-glow"></div>
    <canvas id="particles"></canvas>

    <div class="auth-wrapper">
        <div class="logo">NEXUS.WTHR</div>
        <p class="logo-sub">Future Forecast System</p>

        <div class="tab-toggle">
            <span class="tab-btn active">Login</span>
            <a href="{{ route('register') }}" class="tab-btn">Sign Up</a>
        </div>

        <div class="auth-card">
            @if (session('status'))
                <div class="alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Username --}}
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-wrap">
                        <i class="fas fa-user"></i>
                        <input type="text" id="username" name="username"
                               placeholder="username kamu"
                               value="{{ old('username') }}"
                               required autofocus autocomplete="username">
                    </div>
                    @error('username')
                        <p class="field-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrap">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password"
                               placeholder="••••••••"
                               required autocomplete="current-password">
                    </div>
                    @error('password')
                        <p class="field-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</p>
                    @enderror
                </div>

                <a href="{{ route('password.request') }}" class="forgot-link">Lupa password?</a>

                <div class="remember-row">
                    <input type="checkbox" name="remember" id="remember_me">
                    <label for="remember_me">Ingat saya</label>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="fas fa-arrow-right-to-bracket"></i> Masuk
                </button>
            </form>
        </div>
    </div>

    <script>
        const cursor = document.getElementById('cursor');
        const follower = document.getElementById('cursor-follower');
        document.addEventListener('mousemove', e => {
            cursor.style.left = e.clientX + 'px';
            cursor.style.top = e.clientY + 'px';
            setTimeout(() => { follower.style.left=(e.clientX-12)+'px'; follower.style.top=(e.clientY-12)+'px'; }, 50);
        });
        const canvas = document.getElementById('particles'), ctx = canvas.getContext('2d');
        canvas.width = window.innerWidth; canvas.height = window.innerHeight;
        let pts = Array.from({length:80}, () => ({ x:Math.random()*canvas.width, y:Math.random()*canvas.height, size:Math.random()*2, speed:Math.random()*0.5 }));
        (function animate() {
            ctx.clearRect(0,0,canvas.width,canvas.height);
            pts.forEach(p => { p.y-=p.speed; if(p.y<0) p.y=canvas.height; ctx.fillStyle='rgba(0,245,255,0.3)'; ctx.beginPath(); ctx.arc(p.x,p.y,p.size,0,Math.PI*2); ctx.fill(); });
            requestAnimationFrame(animate);
        })();
    </script>
</body>
</html>
