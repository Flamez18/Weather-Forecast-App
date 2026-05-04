<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEXUS Weather — Under Maintenance</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;500;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --neon-cyan: #00f5ff;
            --neon-purple: #d400ff;
            --neon-pink: #ff0080;
            --glass: rgba(255,255,255,0.06);
            --glass-border: rgba(255,255,255,0.12);
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            background:#000; color:#fff;
            font-family:'Inter',sans-serif;
            min-height:100vh;
            display:flex; align-items:center; justify-content:center;
            padding:40px 20px;
        }
        .bg-glow {
            position:fixed; top:0; left:0; width:100%; height:100%;
            background:radial-gradient(circle at 30% 40%, #1a0033 0%, #000 60%),
                        radial-gradient(circle at 70% 70%, #001a33 0%, transparent 50%);
            z-index:-2;
        }
        #particles { position:fixed; top:0; left:0; width:100%; height:100%; z-index:-1; }

        /* Card */
        .maintenance-card {
            background:var(--glass);
            border:1px solid var(--glass-border);
            border-radius:32px;
            padding:50px 40px;
            text-align:center;
            backdrop-filter:blur(20px);
            max-width:500px;
            width:100%;
            position:relative;
            z-index:1;
        }

        .logo {
            font-size:1.1rem; font-weight:900; letter-spacing:3px;
            color:var(--neon-cyan); margin-bottom:32px;
            text-shadow:0 0 20px var(--neon-cyan);
            font-family:'JetBrains Mono',monospace;
        }

        /* Ring */
        .ring-wrap { position:relative; width:150px; height:150px; margin:0 auto 32px; }
        .ring { position:absolute; inset:0; border-radius:50%; border:2px solid transparent; }
        .ring-1 { border-top-color:var(--neon-cyan); animation:spin 3s linear infinite; }
        .ring-2 { border-right-color:var(--neon-purple); animation:spin 4s linear infinite reverse; inset:12px; }
        .ring-3 { border-bottom-color:var(--neon-pink); animation:spin 5s linear infinite; inset:24px; }
        .ring-icon {
            position:absolute; inset:0;
            display:flex; align-items:center; justify-content:center;
            font-size:3rem;
            filter:drop-shadow(0 0 20px var(--neon-cyan));
            animation:pulse 2s ease-in-out infinite;
        }
        @keyframes spin  { to { transform:rotate(360deg); } }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.7} }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.2} }

        /* Badge */
        .status-badge {
            display:inline-flex; align-items:center; gap:8px;
            background:rgba(255,0,128,0.1);
            border:1px solid rgba(255,0,128,0.3);
            color:var(--neon-pink);
            padding:6px 18px; border-radius:50px;
            font-size:0.72rem; font-weight:700;
            letter-spacing:2px; text-transform:uppercase;
            margin-bottom:24px;
            font-family:'JetBrains Mono',monospace;
        }
        .status-dot { width:6px; height:6px; border-radius:50%; background:var(--neon-pink); box-shadow:0 0 8px var(--neon-pink); animation:blink 1s ease-in-out infinite; }

        h1 { font-size:2rem; font-weight:900; line-height:1.2; margin-bottom:14px; letter-spacing:-1px; }
        h1 span { color:var(--neon-cyan); }

        .desc { font-size:0.9rem; color:rgba(255,255,255,0.5); line-height:1.8; margin-bottom:32px; }

        /* Info grid */
        .info-grid { display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:32px; }
        .info-card { background:rgba(255,255,255,0.04); border:1px solid var(--glass-border); border-radius:16px; padding:16px; }
        .info-card i { color:var(--neon-purple); font-size:16px; margin-bottom:8px; display:block; }
        .info-card-val { font-size:0.88rem; font-weight:700; }
        .info-card-label { font-size:10px; opacity:0.4; text-transform:uppercase; letter-spacing:1px; margin-top:3px; }

        /* Divider */
        .divider { height:1px; background:var(--glass-border); margin-bottom:24px; }

        /* Login Button — prominent */
        .btn-login {
            display:inline-flex; align-items:center; justify-content:center; gap:10px;
            width:100%;
            background:linear-gradient(135deg, var(--neon-cyan), var(--neon-purple));
            color:#000; font-weight:900; font-size:0.95rem;
            letter-spacing:2px; text-transform:uppercase;
            padding:16px 32px; border-radius:50px;
            text-decoration:none; transition:0.3s;
            font-family:'Inter',sans-serif;
            margin-bottom:12px;
        }
        .btn-login:hover { opacity:0.85; transform:translateY(-2px); box-shadow:0 8px 25px rgba(0,245,255,0.3); }

        .login-hint { font-size:11px; color:rgba(255,255,255,0.25); font-family:'JetBrains Mono',monospace; }
    </style>
</head>
<body>
    <div class="bg-glow"></div>
    <canvas id="particles"></canvas>

    <div class="maintenance-card">
        <div class="logo">NEXUS.WTHR</div>

        <div class="ring-wrap">
            <div class="ring ring-1"></div>
            <div class="ring ring-2"></div>
            <div class="ring ring-3"></div>
            <div class="ring-icon"><i class="fas fa-wrench"></i></div>
        </div>

        <div class="status-badge">
            <div class="status-dot"></div>
            System Maintenance
        </div>

        <h1>Sedang dalam <span>Pemeliharaan</span></h1>

        <p class="desc">
            Kami sedang melakukan peningkatan sistem untuk memberikan
            pengalaman yang lebih baik. Silakan kembali dalam beberapa saat.
        </p>

        <div class="info-grid">
            <div class="info-card">
                <i class="fas fa-shield-alt"></i>
                <div class="info-card-val">Data Aman</div>
                <div class="info-card-label">Your data is safe</div>
            </div>
            <div class="info-card">
                <i class="fas fa-clock"></i>
                <div class="info-card-val">Segera Kembali</div>
                <div class="info-card-label">Back soon</div>
            </div>
        </div>

        <div class="divider"></div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-login">
                <i class="fas fa-arrow-right-to-bracket"></i> Masuk sebagai Admin
            </button>
        </form>
        <p class="login-hint">Hanya admin yang dapat mengakses saat maintenance</p>
    </div>

    <script>
        const canvas = document.getElementById('particles');
        const ctx    = canvas.getContext('2d');
        canvas.width  = window.innerWidth;
        canvas.height = window.innerHeight;
        const pts = Array.from({length:80}, () => ({
            x: Math.random() * canvas.width,
            y: Math.random() * canvas.height,
            size: Math.random() * 2,
            speed: Math.random() * 0.4 + 0.1,
        }));
        (function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            pts.forEach(p => {
                p.y -= p.speed;
                if (p.y < 0) p.y = canvas.height;
                ctx.fillStyle = 'rgba(0,245,255,0.25)';
                ctx.beginPath();
                ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
                ctx.fill();
            });
            requestAnimationFrame(animate);
        })();
        window.addEventListener('resize', () => {
            canvas.width  = window.innerWidth;
            canvas.height = window.innerHeight;
        });
    </script>
</body>
</html>
