<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SMA PGRI Pelaihari') }} - Login</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .bg-school-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M40 10 L70 25 L70 55 L40 70 L10 55 L10 25 Z' fill='none' stroke='rgba(255,255,255,0.1)' stroke-width='1.5'/%3E%3C/svg%3E");
            background-size: 60px 60px;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 md:p-0 bg-gradient-to-br from-blue-50 to-indigo-100">
    
    {{-- Main Container --}}
    <div class="w-full max-w-6xl bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row">
        
        {{-- Left Panel: Ilustrasi Sekolah --}}
        <div class="md:w-1/2 bg-gradient-to-br from-blue-600 to-blue-400 p-8 md:p-12 flex flex-col justify-between text-white relative overflow-hidden bg-school-pattern">
            {{-- Decorative Elements --}}
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-20 -mt-20"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full -ml-10 -mb-10"></div>
            
            <div class="relative z-10">
                <div class="flex items-center space-x-3 mb-8">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                        <i class="fas fa-school text-2xl"></i>
                    </div>
                    <span class="text-2xl font-bold tracking-tight">SIPEMA</span>
                </div>
                
                <h2 class="text-3xl md:text-4xl font-bold mb-4 leading-tight">Selamat Datang</h2>
                <p class="text-white/80 text-lg mb-8">Sistem Pembayaran SPP dan Manajemen Siswa.</p>
                
          
            </div>
            
            <div class="relative z-10 mt-8 text-sm text-white/60">
                <i class="far fa-copyright mr-1"></i> {{ date('Y') }} Arifin. All rights reserved.
            </div>
        </div>
        
        {{-- Right Panel: Form Login --}}
        <div class="md:w-1/2 p-8 md:p-12 flex flex-col justify-center bg-white">
            <div class="max-w-sm mx-auto w-full">
                <div class="text-center md:text-left mb-8">
                    <h1 class="text-3xl font-bold text-gray-800">Login</h1>
                    <p class="text-gray-500 mt-1">Silakan masuk untuk melanjutkan</p>
                </div>

                {{-- Session Status --}}
                @if (session('status'))
                    <div class="mb-4 p-3 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-lg text-sm flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    {{-- Email / Username --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-1">Email / Username</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="fas fa-user"></i>
                            </span>
                            <input 
                                id="email" 
                                type="email" 
                                name="email" 
                                value="{{ old('email') }}" 
                                required 
                                autofocus 
                                autocomplete="username"
                                placeholder="nama@email.com"
                                class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent bg-gray-50/50"
                            >
                        </div>
                        @error('email')
                            <p class="mt-1 text-xs text-red-500 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-1">Password</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input 
                                id="password" 
                                type="password"
                                name="password" 
                                required 
                                autocomplete="current-password"
                                placeholder="••••••••"
                                class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent bg-gray-50/50"
                            >
                        </div>
                        @error('password')
                            <p class="mt-1 text-xs text-red-500 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Remember & Forgot --}}
                    <div class="flex items-center justify-between mt-4">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input 
                                id="remember_me" 
                                type="checkbox" 
                                name="remember"
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                            >
                            <span class="text-sm text-gray-600">Ingat saya</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-800 hover:underline">
                                Lupa Password?
                            </a>
                        @endif
                    </div>

                    {{-- Tombol Login --}}
                    <button type="submit" class="mt-6 w-full bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white font-semibold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center space-x-2">
                        <span>Masuk</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>

                    {{-- Opsional: Link Sign Up (jika ada) --}}
                    {{-- 
                    <p class="mt-6 text-center text-gray-500 text-sm">
                        Belum punya akun? 
                        <a href="{{ route('register') }}" class="text-blue-600 font-medium hover:underline">Daftar</a>
                    </p>
                    --}}
                </form>
            </div>
        </div>
    </div>
    
    {{-- Footer Mobile (opsional) --}}
    <div class="md:hidden fixed bottom-0 left-0 right-0 p-4 text-center text-gray-400 text-xs bg-white/80 backdrop-blur-sm">
        <i class="fas fa-phone-alt mr-1"></i> (0512) 12345 • By Arifin
    </div>
</body>
</html>