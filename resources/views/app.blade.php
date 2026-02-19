<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">    
    <meta name="theme-color" content="#0d6efd">
    <title>{{ config('app.name') }}</title>

    <link rel="manifest" href="/pwa/manifest.json">
    <link rel="apple-touch-icon" href="/pwa/icons/icon-192.png">

    <style>
        /* layar penuh */
        #preloader{
            position:fixed;
            inset:0;
            background:linear-gradient(180deg,#0b1a33,#0f172a);
            display:flex;
            align-items:center;
            justify-content:center;
            z-index:99999;
            overflow:hidden;
        }

        /* box tengah */
        .boot-box{
            display:flex;
            flex-direction:column;
            align-items:center;
            justify-content:center;
            text-align:center;
            color:white;
            font-family:system-ui, -apple-system, sans-serif;
        }

        /* logo */
        .boot-logo{
            width:120px;
            height:120px;
            object-fit:contain;
            margin-bottom:18px;
            animation:float 1.4s ease-in-out infinite alternate;
        }

        /* efek nafas (mobile feel) */
        @keyframes float{
            from{transform:scale(.92);opacity:.7}
            to{transform:scale(1.05);opacity:1}
        }

        /* teks */
        .boot-text{
            font-size:15px;
            opacity:.85;
            margin-top:6px;
            letter-spacing:.4px;
        }

        /* spinner modern */
        .spinner{
            width:30px;
            height:30px;
            border-radius:50%;
            border:3px solid rgba(255,255,255,.15);
            border-top:3px solid #60a5fa;
            margin-top:18px;
            animation:spin .8s linear infinite;
        }

        @keyframes spin{
            to{transform:rotate(360deg)}
        }
        </style>
</head>
<body>
    <div id="preloader">
        <div class="boot-box">
            <img src="/pwa/icons/android-launchericon-192-192.png" class="boot-logo">
            <div class="boot-text">Sedang Memuat...</div>
            <div class="spinner"></div>
        </div>
    </div>
    <div id="app"></div>
    @vite('resources/js/app.js')
</body>
<script src="https://accounts.google.com/gsi/client" async defer></script>
</html>
