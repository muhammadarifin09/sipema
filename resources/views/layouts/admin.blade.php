<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'SMA PGRI Pelaihari') - {{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700|inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    @stack('styles') <!-- Untuk CSS tambahan per halaman -->
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

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

        /* Pattern overlay */
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

        /* Glassmorphism card */
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        /* Sidebar styles */
        .sidebar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-right: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 10px 0 30px -15px rgba(0, 0, 0, 0.2);
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            margin: 0.25rem 0.75rem;
            border-radius: 1rem;
            color: #4a5568;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .nav-item i {
            width: 24px;
            font-size: 1.1rem;
            color: #0b4f8c;
            transition: all 0.3s ease;
        }

        .nav-item:hover {
            background: linear-gradient(135deg, #0b4f8c 0%, #1e6f9f 100%);
            color: white;
            transform: translateX(5px);
            box-shadow: 0 4px 15px rgba(11, 79, 140, 0.2);
        }

        .nav-item:hover i {
            color: white;
        }

        .nav-item.active {
            background: linear-gradient(135deg, #0b4f8c 0%, #1e6f9f 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(11, 79, 140, 0.3);
        }

        .nav-item.active i {
            color: white;
        }

        /* Search input */
        .search-input {
            padding: 0.75rem 1rem 0.75rem 2.8rem;
            border: 2px solid #e2e8f0;
            border-radius: 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: white;
            width: 100%;
        }

        .search-input:focus {
            outline: none;
            border-color: #0b4f8c;
            box-shadow: 0 0 0 4px rgba(11, 79, 140, 0.1);
        }

        /* Profile dropdown */
        .profile-button {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            background: white;
            border-radius: 2rem;
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .profile-button:hover {
            border-color: #0b4f8c;
            box-shadow: 0 4px 15px rgba(11, 79, 140, 0.1);
        }

        .profile-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #0b4f8c 0%, #1e6f9f 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
        }

        /* Button styles */
        .btn-primary {
            background: linear-gradient(135deg, #0b4f8c 0%, #1e6f9f 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(11, 79, 140, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(11, 79, 140, 0.3);
        }

        .btn-secondary {
            background: white;
            color: #0b4f8c;
            padding: 0.75rem 1.5rem;
            border-radius: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 2px solid #0b4f8c;
            cursor: pointer;
        }

        .btn-secondary:hover {
            background: #0b4f8c;
            color: white;
        }

        .btn-danger {
            background: #ef4444;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-danger:hover {
            background: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(239, 68, 68, 0.3);
        }

        /* Table styles */
        .table-container {
            background: white;
            border-radius: 1.5rem;
            padding: 1.5rem;
            box-shadow: 0 10px 30px -10px rgba(11, 79, 140, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .table-header {
            background: linear-gradient(135deg, #0b4f8c 0%, #1e6f9f 100%);
            color: white;
        }

        .table-row {
            transition: all 0.2s ease;
            border-bottom: 1px solid #e2e8f0;
        }

        .table-row:hover {
            background: rgba(11, 79, 140, 0.02);
        }

        /* Badge styles */
        .badge-success {
            background: #10b981;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-warning {
            background: #f59e0b;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-danger {
            background: #ef4444;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-info {
            background: #0b4f8c;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Form styles */
        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: white;
        }

        .form-input:focus {
            outline: none;
            border-color: #0b4f8c;
            box-shadow: 0 0 0 4px rgba(11, 79, 140, 0.1);
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #4a5568;
            font-size: 0.9rem;
        }

        .form-error {
            color: #ef4444;
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }

        /* Card styles */
        .card {
            background: white;
            border-radius: 1.5rem;
            padding: 1.5rem;
            box-shadow: 0 10px 30px -10px rgba(11, 79, 140, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        /* Animations */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-slide-in {
            animation: slideIn 0.5s ease forwards;
        }
    </style>
</head>
<body class="antialiased">
    <div class="pattern-overlay"></div>
    
    <div class="flex h-screen relative z-10">
        <!-- Sidebar -->
        <div class="sidebar w-72 flex flex-col fixed h-full">
            <!-- School Logo & Name -->
            <div class="p-6 border-b border-gray-200/50">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-[#0b4f8c] to-[#1e6f9f] rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-school text-white text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-gray-800 text-lg">SMA PGRI</h2>
                        <p class="text-xs text-gray-500">Pelaihari</p>
                    </div>
                </div>
            </div>

            <!-- Navigation Menu -->
            <div class="flex-1 overflow-y-auto py-6">
                <div class="px-4 mb-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Menu Utama</p>
                </div>
                
                <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt w-6"></i>
                    <span class="ml-3 font-medium">Dashboard</span>
                </a>
                
                <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users w-6"></i>
                    <span class="ml-3 font-medium">Data Users</span>
                </a>

                <a href="{{ route('admin.siswa.index') }}" class="nav-item {{ request()->routeIs('admin.siswa.*') ? 'active' : '' }}">
                    <i class="fas fa-user-graduate w-6"></i>
                    <span class="ml-3 font-medium">Data Siswa</span>
                </a>
                
                <a href="{{ route('admin.pembayaran.index') }}" class="nav-item {{ request()->routeIs('admin.pembayaran.*') ? 'active' : '' }}">
                    <i class="fas fa-credit-card w-6"></i>
                    <span class="ml-3 font-medium">Pembayaran SPP</span>
                </a>
                
                <a href="{{ route('admin.riwayat.index') }}" class="nav-item {{ request()->routeIs('admin.riwayat.*') ? 'active' : '' }}">
                    <i class="fas fa-history w-6"></i>
                    <span class="ml-3 font-medium">Riwayat Transaksi</span>
                </a>
                
                <a href="{{ route('admin.laporan.index') }}" class="nav-item {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar w-6"></i>
                    <span class="ml-3 font-medium">Laporan</span>
                </a>

                <div class="px-4 my-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Master Data</p>
                </div>

                <a href="{{ route('admin.admin.index') }}" class="nav-item {{ request()->routeIs('admin.admin.*') ? 'active' : '' }}">
                    <i class="fas fa-user-tie w-6"></i>
                    <span class="ml-3 font-medium">Data Admin</span>
                </a>

                <a href="{{ route('admin.guru.index') }}" class="nav-item {{ request()->routeIs('admin.guru.*') ? 'active' : '' }}">
                    <i class="fas fa-chalkboard-teacher w-6"></i>
                    <span class="ml-3 font-medium">Data Guru</span>
                </a>

                <a href="{{ route('admin.kelas.index') }}" class="nav-item {{ request()->routeIs('admin.kelas.*') ? 'active' : '' }}">
                    <i class="fas fa-book w-6"></i>
                    <span class="ml-3 font-medium">Data Kelas</span>
                </a>

                <a href="{{ route('admin.tahun-ajaran.index') }}" class="nav-item {{ request()->routeIs('admin.tahun-ajaran.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt w-6"></i>
                    <span class="ml-3 font-medium">Tahun Ajaran</span>
                </a>

                <!-- Settings -->
                <div class="px-4 my-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Pengaturan</p>
                </div>

                <a href="{{ route('admin.pengaturan.index') }}" class="nav-item {{ request()->routeIs('admin.pengaturan.*') ? 'active' : '' }}">
                    <i class="fas fa-cog w-6"></i>
                    <span class="ml-3 font-medium">Pengaturan</span>
                </a>

                <!-- Logout Button -->
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="nav-item w-full text-left">
                        <i class="fas fa-sign-out-alt w-6"></i>
                        <span class="ml-3 font-medium">Keluar</span>
                    </button>
                </form>
            </div>

            <!-- School Info -->
            <div class="p-6 border-t border-gray-200/50">
                <div class="flex items-center space-x-3 text-gray-500">
                    <i class="fas fa-map-marker-alt text-[#0b4f8c]"></i>
                    <span class="text-xs">Jl. A. Yani Km. 3, Pelaihari</span>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 ml-72">
            <!-- Top Navigation -->
            <div class="glass-card m-6 rounded-2xl p-4">
                <div class="flex items-center justify-between">
                    <!-- Search Bar -->
                    <div class="relative w-96">
                        <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" placeholder="Cari..." class="search-input" id="globalSearch">
                    </div>

                    <!-- Profile & Notifications -->
                    <div class="flex items-center space-x-4">
                        <!-- Notification -->
                        <div class="relative">
                            <button class="w-12 h-12 rounded-2xl bg-white flex items-center justify-center hover:border-[#0b4f8c] border-2 border-gray-200 transition-all">
                                <i class="fas fa-bell text-gray-600 text-xl"></i>
                                <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 rounded-full flex items-center justify-center text-white text-xs font-bold">3</span>
                            </button>
                        </div>

                        <!-- Profile Dropdown -->
                        <div class="profile-button relative" onclick="toggleProfileDropdown()">
                            <div class="profile-avatar">
                                <span>{{ strtoupper(substr(Auth::user()->name ?? 'AD', 0, 2)) }}</span>
                            </div>
                            <div class="text-left">
                                <p class="font-semibold text-gray-800 text-sm">{{ Auth::user()->name ?? 'Admin Utama' }}</p>
                                <p class="text-xs text-gray-500">{{ Auth::user()->email ?? 'admin@smapgri.sch.id' }}</p>
                            </div>
                            <i class="fas fa-chevron-down text-gray-400 text-sm ml-4"></i>
                            
                            <!-- Dropdown Menu -->
                            <div id="profileDropdown" class="hidden absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-lg py-2 border border-gray-200">
                                <a href="{{ route('admin.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i> Profile
                                </a>
                                <a href="{{ route('admin.pengaturan.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-cog mr-2"></i> Pengaturan
                                </a>
                                <hr class="my-2 border-gray-200">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="p-6 pt-0">
                <!-- Breadcrumb -->
                @hasSection('breadcrumb')
                    <div class="mb-4 text-white/80 text-sm">
                        @yield('breadcrumb')
                    </div>
                @endif

                <!-- Page Header -->
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-white drop-shadow-lg">@yield('page-title', 'Dashboard')</h1>
                        @hasSection('page-description')
                            <p class="text-white/80 mt-1">@yield('page-description')</p>
                        @endif
                    </div>
                    
                    <!-- Page Actions -->
                    @hasSection('page-actions')
                        <div class="flex space-x-3">
                            @yield('page-actions')
                        </div>
                    @endif
                </div>

                <!-- Main Content -->
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Toggle profile dropdown
        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            dropdown.classList.toggle('hidden');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const profileButton = document.querySelector('.profile-button');
            const dropdown = document.getElementById('profileDropdown');
            
            if (!profileButton.contains(event.target) && !dropdown.classList.contains('hidden')) {
                dropdown.classList.add('hidden');
            }
        });

        // Global search functionality
        document.getElementById('globalSearch')?.addEventListener('keyup', function(e) {
            // Implement search logic here
            console.log('Searching:', e.target.value);
        });
    </script>

    @stack('scripts')
</body>
</html>