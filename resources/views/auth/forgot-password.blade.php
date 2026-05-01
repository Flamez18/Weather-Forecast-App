<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEXUS Weather - Reset Password</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;500;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root { --neon-cyan:#00f5ff; --neon-purple:#d400ff; --neon-pink:#ff0080; --glass:rgba(255,255,255,0.08); --glass-border:rgba(255,255,255,0.15); }
        * { margin:0; padding:0; box-sizing:border-box; cursor:none; }
        body { background:#000; color:#fff; font-family:'Inter',sans-serif; min-height:100vh; display:flex; align-items:center; justify-content:center; overflow-x:hidden; padding:40px 20px; }
        #cursor { position:fixed; width:10px; height:10px; background:var(--neon-cyan); border-radius:50%; pointer-events:none; z-index:9999; box-shadow:0 0 15px var(--neon-cyan); }
        #cursor-follower { position:fixed; width:35px; height:35px; border:1px solid var(--neon-purple); border-radius:50%; pointer-events:none; z-index:9998; transition:transform 0.15s ease-out; }
        .bg-glow { position:fixed; top:0; left:0; width:100%; height:100%; background:radial-gradient(circle at 50% 50%,#1a0033 0%,#000 100%); z-index:-2; }
        #particles { position:fixed; top:0; left:0; width:100%; height:100%; z-index:-1; }

        .auth-wrapper { width:100%; max-width:460px; }
        .logo { text-align:center; font-weight:900; font-size:1.8rem; letter-spacing:2px; color:var(--neon-cyan); margin-bottom:8px; text-shadow:0 0 20px var(--neon-cyan); }
        .logo-sub { text-align:center; font-size:0.75rem; letter-spacing:5px; color:rgba(255,255,255,0.4); text-transform:uppercase; margin-bottom:40px; font-family:'JetBrains Mono',monospace; }

        .auth-card { background:var(--glass); border:1px solid var(--glass-border); border-radius:30px; padding:40px; backdrop-filter:blur(20px); }

        .card-title { font-size:1rem; font-weight:700; color:var(--neon-cyan); letter-spacing:2px; text-transform:uppercase; font-family:'JetBrains Mono',monospace; margin-bottom:8px; }
        .card-desc { font-size:0.85rem; color:rgba(255,255,255,0.4); line-height:1.7; margin-bottom:28px; }

        .form-group { margin-bottom:20px; }
        .form-group label { display:block; font-size:0.75rem; letter-spacing:2px; text-transform:uppercase; color:rgba(255,255,255,0.5); margin-bottom:8px; font-family:'JetBrains Mono',monospace; }
        .input-wrap { position:relative; }
        .input-wrap i { position:absolute; left:18px; top:50%; transform:translateY(-50%); color:var(--neon-purple); font-size:14px; pointer-events:none; }
        .form-group input { width:100%; background:rgba(0,0,0,0.5); border:1px solid var(--glass-border); padding:15px 20px 15px 48px; border-radius:15px; color:#fff; outline:none; font-family:'Inter',sans-serif; font-size:0.95rem; transition:0.3s; }
        .form-group input:focus { border-color:var(--neon-cyan); box-shadow:0 0 15px rgba(0,245,255,0.15); }
        .form-group input::placeholder { color:rgba(255,255,255,0.25); }
        .field-error { color:var(--neon-pink); font-size:0.8rem; margin-top:6px; display:flex; align-items:center; gap:5px; }

        .divider { display:flex; align-items:center; gap:12px; margin:8px 0 20px; }
        .divider::before, .divider::after { content:''; flex:1; height:1px; background:var(--glass-border); }
        .divider span { font-size:0.7rem; color:rgba(255,255,255,0.3); font-family:'JetBrains Mono',monospace; white-space:nowrap; }

        .btn-submit { width:100%; padding:16px; border:none; border-radius:50px; background:linear-gradient(135deg,var(--neon-cyan),var(--neon-purple)); color:#000; font-weight:900; font-size:0.95rem; letter-spacing:2px; text-transform:uppercase; cursor:none; transition:0.3s; font-family:'Inter',sans-serif; }
        .btn-submit:hover { opacity:0.85; transform:translateY(-2px); box-shadow:0 0 30px rgba(0,245,255,0.3); }

        .back-link { display:flex; align-items:center; justify-content:center; gap:8px; margin-top:20px; font-size:0.85rem; color:rgba(255,255,255,0.4); text-decoration:none; transition:0.2s; }
        .back-link:hover { color:var(--neon-cyan); }

        .alert-error { background:rgba(255,0,128,0.08); border:1px solid rgba(255,0,128,0.3); color:var(--neon-pink); padding:14px 18px; border-radius:12px; margin-bottom:20px; font-size:0.88rem; display:flex; align-items:center; gap:10px; }
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

        <div class="auth-card">
            <p class="card-title"><i class="fas fa-key"></i> Reset Password</p>
            <p class="card-desc">Masukkan username, email, dan password baru kamu. Pastikan username dan email sesuai dengan akun yang terdaftar.</p>

            @if ($errors->any())
                <div class="alert-error">
                    <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.direct.store') }}">
                @csrf

                {{-- Username --}}
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-wrap">
                        <i class="fas fa-user"></i>
                        <input type="text" id="username" name="username"
                               placeholder="Username kamu"
                               value="{{ old('username') }}"
                               required autofocus>
                    </div>
                    @error('username')
                        <p class="field-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-wrap">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email"
                               placeholder="Email terdaftar"
                               value="{{ old('email') }}"
                               required>
                    </div>
                    @error('email')
                        <p class="field-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</p>
                    @enderror
                </div>

                <div class="divider"><span>Password Baru</span></div>

                {{-- New Password --}}
                <div class="form-group">
                    <label for="password">Password Baru</label>
                    <div class="input-wrap">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password"
                               placeholder="Min. 8 karakter"
                               required>
                    </div>
                    @error('password')
                        <p class="field-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <div class="input-wrap">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               placeholder="Ulangi password baru"
                               required>
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="fas fa-check"></i> Reset Password
                </button>
            </form>

            <a href="{{ route('login') }}" class="back-link">
                <i class="fas fa-arrow-left"></i> Kembali ke Login
            </a>
        </div>
    </div>

    <script>
        const cursor = document.getElementById('cursor');
        const follower = document.getElementById('cursor-follower');
        document.addEventListener('mousemove', e => {
            cursor.style.left = e.clientX + 'px'; cursor.style.top = e.clientY + 'px';
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
