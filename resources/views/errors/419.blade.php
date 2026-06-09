<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 - Page Expired | PKK Kabupaten Toba</title>
    <style>
        /* Reset & Base */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            /* Background tema UNGU lembut */
            background: linear-gradient(180deg, #f5f3ff 0%, #ede9fe 50%, #ffffff 100%);
            height: 100vh;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        /* ANIMASI AWAN */
        .cloud {
            position: absolute;
            background: #fff;
            border-radius: 100px;
            opacity: 0.9;
            box-shadow: 0 10px 30px rgba(139, 92, 246, 0.1);
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

        /* KARTU KONTEN UTAMA */
        .container {
            position: relative;
            z-index: 10;
            text-align: center;
            background: rgba(255, 255, 255, 0.92);
            padding: 2.5rem 3.5rem;
            border-radius: 24px;
            box-shadow: 0 25px 70px rgba(139, 92, 246, 0.15);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            max-width: 720px; 
            width: 94%;
            animation: floatCard 6s ease-in-out infinite;
        }
        @keyframes floatCard {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        /* IKON JAM UNGU */
        .clock-icon {
            width: 72px;
            height: 72px;
            margin: 0 auto 1.25rem;
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulseGlow 2.5s infinite;
        }
        @keyframes pulseGlow {
            0% { box-shadow: 0 0 0 0 rgba(139, 92, 246, 0.4); }
            70% { box-shadow: 0 0 0 18px rgba(139, 92, 246, 0); }
            100% { box-shadow: 0 0 0 0 rgba(139, 92, 246, 0); }
        }
        .clock-icon svg { width: 36px; height: 36px; color: #ffffff; }

        /* TYPOGRAPHY */
        .error-code {
            font-size: 3.2rem;
            font-weight: 800;
            margin-bottom: 0.25rem;
            letter-spacing: -2px;
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
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
            color: #6d28d9; /* Ungu gelap */
            display: block;
            margin-bottom: 0.6rem;
            font-size: 1.15rem;
        }

        /* TOMBOL UNGU */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            color: #ffffff;
            padding: 0.85rem 2rem;
            border-radius: 14px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
        }
        .btn:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 8px 25px rgba(139, 92, 246, 0.4); 
            background: linear-gradient(135deg, #7c3aed, #6d28d9);
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
    
    <!-- Konten Utama -->
    <div class="container">
        <div class="clock-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <polyline points="12 6 12 12 16 14"/>
            </svg>
        </div>
        
        <div class="error-code">419</div>
        
        <div class="message">
            <span class="dynamic-msg">Halaman Login Telah Kedaluwarsa</span>
            Sesi login SIDONGAN Anda telah berakhir karena halaman terlalu lama tidak digunakan.<br>
            Silahkan kembali ke halaman login untuk masuk kembali.
        </div>
        
        <a href="{{ route('sidongan.login') }}" class="btn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                <polyline points="10 17 15 12 10 7"/>
                <line x1="15" y1="12" x2="3" y2="12"/>
            </svg>
            Kembali ke Login SIDONGAN
        </a>
    </div>
</body>
</html>