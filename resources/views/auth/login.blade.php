<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SMA PGRI Pelaihari') }} - Login</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700|inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0b4f8c 0%, #1e6f9f 50%, #2d8bcb 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }
        
        /* Decorative background elements */
        body::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 600px;
            height: 600px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            z-index: 0;
        }
        
        body::after {
            content: '';
            position: absolute;
            bottom: -20%;
            left: -5%;
            width: 500px;
            height: 500px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 50%;
            z-index: 0;
        }
        
        /* School pattern overlay */
        .pattern-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 5 L55 20 L55 40 L30 55 L5 40 L5 20 Z' fill='none' stroke='rgba(255,255,255,0.02)' stroke-width='1'/%3E%3C/svg%3E");
            opacity: 0.5;
            z-index: 0;
        }
        
        /* Glassmorphism effect */
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(255, 255, 255, 0.1);
        }
        
        /* Input field styles */
        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }
        
        .input-group i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #0b4f8c;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            z-index: 10;
        }
        
        .input-group .input-field {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 2.8rem;
            border: 2px solid #e2e8f0;
            border-radius: 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: white;
            color: #1e293b;
            font-weight: 400;
        }
        
        .input-group .input-field:focus {
            outline: none;
            border-color: #0b4f8c;
            box-shadow: 0 0 0 4px rgba(11, 79, 140, 0.1);
        }
        
        .input-group .input-field:focus + i {
            color: #0b4f8c;
        }
        
        /* Custom checkbox */
        .checkbox-custom {
            accent-color: #0b4f8c;
            width: 1.1rem;
            height: 1.1rem;
            border-radius: 0.25rem;
            border: 2px solid #cbd5e1;
            transition: all 0.2s ease;
        }
        
        .checkbox-custom:checked {
            background-color: #0b4f8c;
            border-color: #0b4f8c;
        }
        
        /* Login button */
        .login-btn {
            background: linear-gradient(135deg, #0b4f8c 0%, #1e6f9f 100%);
            color: white;
            font-weight: 600;
            padding: 0.875rem 2rem;
            border-radius: 1rem;
            width: 100%;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(11, 79, 140, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .login-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }
        
        .login-btn:hover::before {
            left: 100%;
        }
        
        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(11, 79, 140, 0.4);
        }
        
        .login-btn:active {
            transform: translateY(0);
        }
        
        /* Links */
        .forgot-link {
            color: #64748b;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        
        .forgot-link:hover {
            color: #0b4f8c;
            text-decoration: underline;
        }
        
        /* School logo area */
        .school-logo {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, #0b4f8c, #1e6f9f);
            border-radius: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 10px 25px -5px rgba(11, 79, 140, 0.3);
            border: 3px solid rgba(255, 255, 255, 0.2);
        }
        
        .school-logo i {
            font-size: 3rem;
            color: white;
        }
        
        /* Floating animation */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        
        .float-animation {
            animation: float 4s ease-in-out infinite;
        }
    </style>
</head>
<body class="antialiased">
    <div class="pattern-overlay"></div>
    
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative z-10">
        <!-- School Header -->
        <div class="text-center mb-6">
            <div class="school-logo float-animation">
                <i class="fas fa-school"></i>
            </div>
            <h1 class="text-3xl font-bold text-white mb-1 drop-shadow-lg">SMA PGRI Pelaihari</h1>
            <p class="text-white/90 text-sm font-light tracking-wider">Sistem Pembayaran SPP Digital</p>
        </div>

        <!-- Login Card -->
        <div class="w-full sm:max-w-md px-8 py-8 glass-card rounded-3xl shadow-2xl">
            <!-- Header Card -->
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-800">Selamat Datang Kembali</h2>
                <p class="text-gray-500 text-sm mt-1">Silakan login ke akun Anda</p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-lg text-sm">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus 
                        autocomplete="username"
                        placeholder="Email"
                        class="input-field"
                    >
                    @error('email')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input 
                        id="password" 
                        type="password"
                        name="password" 
                        required 
                        autocomplete="current-password"
                        placeholder="Password"
                        class="input-field"
                    >
                    @error('password')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between mt-6">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input 
                            id="remember_me" 
                            type="checkbox" 
                            name="remember"
                            class="checkbox-custom"
                        >
                        <span class="text-sm text-gray-600 font-medium">Ingat saya</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link flex items-center">
                            <i class="fas fa-key mr-1 text-xs"></i>
                            Lupa Password?
                        </a>
                    @endif
                </div>

                <!-- Login Button -->
                <div class="mt-8">
                    <button type="submit" class="login-btn flex items-center justify-center space-x-2">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Masuk</span>
                    </button>
                </div>

                <!-- Additional Info -->
                <div class="mt-6 text-center">
                    <p class="text-xs text-gray-400">
                        <i class="far fa-copyright mr-1"></i>
                        {{ date('Y') }} SMA PGRI Pelaihari. All rights reserved.
                    </p>
                </div>
            </form>
        </div>

        <!-- Footer Links -->
        <div class="mt-6 text-center text-white/80 text-sm">
            <p class="flex items-center justify-center space-x-4">
                <span><i class="fas fa-phone-alt mr-1"></i> (0512) 12345</span>
                <span>•</span>
                <span><i class="fas fa-map-marker-alt mr-1"></i> Jl. A. Yani Km. 3, Pelaihari</span>
            </p>
        </div>
    </div>
</body>
</html>