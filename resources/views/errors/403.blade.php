<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak</title>
    <style>
        /* Reset & Base */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: linear-gradient(180deg, #e0f2fe 0%, #f0fdfa 50%, #ffffff 100%);
            height: 100vh;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        /* ☁️ ANIMASI AWAN */
        .cloud {
            position: absolute;
            background: #fff;
            border-radius: 100px;
            opacity: 0.9;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            z-index: 1;
        }
        .cloud::before, .cloud::after {
            content: '';
            position: absolute;
            background: #fff;
            border-radius: 50%;
        }
        .c1 { width: 120px; height: 40px; top: 15%; left: -150px; animation: drift 28s linear infinite; }
        .c1::before { width: 50px; height: 50px; top: -25px; left: 20px; }
        .c1::after { width: 70px; height: 70px; top: -35px; left: 45px; }

        .c2 { width: 160px; height: 50px; top: 45%; left: -200px; animation: drift 38s linear infinite; animation-delay: -8s; }
        .c2::before { width: 60px; height: 60px; top: -30px; left: 30px; }
        .c2::after { width: 90px; height: 90px; top: -45px; left: 60px; }

        .c3 { width: 100px; height: 35px; top: 70%; left: -130px; animation: drift 22s linear infinite; animation-delay: -15s; }
        .c3::before { width: 40px; height: 40px; top: -20px; left: 15px; }
        .c3::after { width: 55px; height: 55px; top: -28px; left: 35px; }

        .c4 { width: 140px; height: 45px; top: 25%; left: -180px; animation: drift 32s linear infinite; animation-delay: -20s; }
        .c4::before { width: 55px; height: 55px; top: -28px; left: 25px; }
        .c4::after { width: 80px; height: 80px; top: -40px; left: 50px; }

        @keyframes drift {
            from { transform: translateX(-250px); }
            to { transform: translateX(110vw); }
        }

        /* 📦 KARTU KONTEN UTAMA (DILEBARKAN) */
        .container {
            position: relative;
            z-index: 10;
            text-align: center;
            background: rgba(255, 255, 255, 0.95);
            padding: 2.5rem 3.5rem;
            border-radius: 24px;
            box-shadow: 0 25px 70px rgba(15, 107, 99, 0.12);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            
            /* ✅ PERUBAHAN UTAMA: Lebar diperbesar */
            max-width: 720px; 
            width: 94%;
            
            animation: floatCard 6s ease-in-out infinite;
        }
        @keyframes floatCard {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        /*  IKON GEMBUK */
        .lock-icon {
            width: 72px;
            height: 72px;
            margin: 0 auto 1.25rem;
            background: linear-gradient(135deg, #0f6b63, #14b8a6);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulseGlow 2.5s infinite;
        }
        @keyframes pulseGlow {
            0% { box-shadow: 0 0 0 0 rgba(15, 107, 99, 0.4); }
            70% { box-shadow: 0 0 0 18px rgba(15, 107, 99, 0); }
            100% { box-shadow: 0 0 0 0 rgba(15, 107, 99, 0); }
        }
        .lock-icon svg { width: 36px; height: 36px; color: #ffffff; }

        /* TYPOGRAPHY */
        .error-code {
            font-size: 3.2rem;
            font-weight: 800;
            color: #0f6b63;
            margin-bottom: 0.25rem;
            letter-spacing: -2px;
            background: linear-gradient(135deg, #0f6b63, #14b8a6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .message {
            color: #475569;
            font-size: 1.1rem;
            line-height: 1.7;
            margin-bottom: 1.75rem;
        }
        .dynamic-msg {
            font-weight: 700;
            color: #0f6b63;
            display: block;
            margin-bottom: 0.6rem;
            font-size: 1.15rem;
        }

        /* TOMBOL */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            background: linear-gradient(135deg, #0f6b63, #14b8a6);
            color: #ffffff;
            padding: 0.85rem 2rem;
            border-radius: 14px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 15px rgba(15, 107, 99, 0.25);
        }
        .btn:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 8px 25px rgba(15, 107, 99, 0.35); 
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .container { 
                max-width: 95%; 
                padding: 2rem 1.5rem; 
            }
            .error-code { font-size: 2.5rem; }
            .message { font-size: 1rem; }
            .dynamic-msg { font-size: 1.05rem; }
        }
    </style>
</head>
<body>
    <!-- Awan-awan Bergerak -->
    <div class="cloud c1"></div>
    <div class="cloud c2"></div>
    <div class="cloud c3"></div>
    <div class="cloud c4"></div>
    
    <!--  Konten Utama -->
    <div class="container">
        <div class="lock-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
        </div>
        
        <div class="error-code">403</div>
        
        <div class="message">
            <span class="dynamic-msg">{{ $exception->getMessage() ?? 'Akses Ditolak!' }}</span>
            Anda tidak memiliki izin untuk mengakses halaman ini.<br>
            Silahkan kembali ke dashboard atau hubungi administrator.
        </div>
        
        <a href="{{ route('admin.dashboard') }}" class="btn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
            Kembali ke Dashboard
        </a>
    </div>
</body>
</html>