<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'SMA PGRI Pelaihari - Admin Panel')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700|inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    @stack('styles')
    
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

        /* Stat cards */
        .stat-card {
            background: white;
            border-radius: 1.5rem;
            padding: 1.5rem;
            box-shadow: 0 10px 30px -10px rgba(11, 79, 140, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.5);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200px;
            height: 200px;
            background: linear-gradient(135deg, rgba(11, 79, 140, 0.05) 0%, rgba(45, 139, 203, 0.05) 100%);
            border-radius: 50%;
            z-index: 0;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px -15px rgba(11, 79, 140, 0.3);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #0b4f8c 0%, #1e6f9f 100%);
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            box-shadow: 0 10px 20px -5px rgba(11, 79, 140, 0.3);
        }

        .stat-icon i {
            font-size: 1.8rem;
            color: white;
        }

        /* Mini stat cards */
        .mini-stat-card {
            background: white;
            border-radius: 1.2rem;
            padding: 1rem;
            box-shadow: 0 5px 15px -5px rgba(11, 79, 140, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.5);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .mini-stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px -8px rgba(11, 79, 140, 0.2);
        }

        .mini-stat-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #0b4f8c 0%, #1e6f9f 100%);
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .mini-stat-icon i {
            font-size: 1.3rem;
            color: white;
        }

        /* Progress bar */
        .progress-bar {
            width: 100%;
            height: 8px;
            background: #e2e8f0;
            border-radius: 9999px;
            overflow: hidden;
            margin-top: 0.5rem;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #0b4f8c, #1e6f9f);
            border-radius: 9999px;
            transition: width 0.3s ease;
        }

        /* Info card */
        .info-card {
            background: white;
            border-radius: 1.5rem;
            padding: 1.5rem;
            box-shadow: 0 10px 30px -10px rgba(11, 79, 140, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.5);
            height: 100%;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        /* Table styles */
        .table-container {
            background: white;
            border-radius: 1.5rem;
            padding: 1.5rem;
            box-shadow: 0 10px 30px -10px rgba(11, 79, 140, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .table-row {
            transition: all 0.2s ease;
            border-bottom: 1px solid #e2e8f0;
        }

        .table-row:hover {
            background: rgba(11, 79, 140, 0.02);
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
        }

        .form-input:focus {
            outline: none;
            border-color: #0b4f8c;
            box-shadow: 0 0 0 4px rgba(11, 79, 140, 0.1);
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #4a5568;
            font-size: 0.9rem;
        }

        .form-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 1rem;
            font-size: 0.95rem;
            background: white;
            cursor: pointer;
        }

        .form-select:focus {
            outline: none;
            border-color: #0b4f8c;
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

        /* Modal styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .modal-content {
            background: white;
            border-radius: 2rem;
            padding: 2rem;
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
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

        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }
        .delay-5 { animation-delay: 0.5s; }
        .delay-6 { animation-delay: 0.6s; }
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
                        <h2 class="font-bold text-gray-800 text-lg">Si PEMA</h2>
                        <p class="text-xs text-gray-500">By Arifin</p>
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
                   <!-- Users Management -->
                <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-user-tie w-6"></i>
                    <span class="ml-3 font-medium">Data Akun</span>
                </a>

                <a href="{{ route('admin.siswa.index') }}" class="nav-item {{ request()->routeIs('admin.siswa.*') ? 'active' : '' }}">
                    <i class="fas fa-users w-6"></i>
                    <span class="ml-3 font-medium">Data Siswa</span>
                </a>
                
                <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.pembayaran.*') ? 'active' : '' }}">
                    <i class="fas fa-credit-card w-6"></i>
                    <span class="ml-3 font-medium">Pembayaran SPP</span>
                </a>
                
                <a href="#" class="nav-item">
                    <i class="fas fa-history w-6"></i>
                    <span class="ml-3 font-medium">Riwayat Transaksi</span>
                </a>
                
                <a href="#" class="nav-item">
                    <i class="fas fa-chart-bar w-6"></i>
                    <span class="ml-3 font-medium">Laporan</span>
                </a>

                <div class="px-4 my-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Master Data</p>
                </div>

       

                <a href="#" class="nav-item">
                    <i class="fas fa-chalkboard-teacher w-6"></i>
                    <span class="ml-3 font-medium">Data Guru</span>
                </a>

               <a href="{{ route('admin.kelas.index') }}" class="nav-item {{ request()->routeIs('admin.kelas.*') ? 'active' : '' }}">
                    <i class="fas fa-school w-6"></i>
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

                <a href="#" class="nav-item">
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
                        <input type="text" placeholder="Cari siswa, pembayaran..." class="search-input" id="globalSearch">
                    </div>

                    <!-- Profile & Notifications -->
                    <div class="flex items-center space-x-4">
                        <!-- Notification -->
                        <div class="relative">
                            <button class="w-12 h-12 rounded-2xl bg-white flex items-center justify-center hover:border-[#0b4f8c] border-2 border-gray-200 transition-all" id="notificationBtn">
                                <i class="fas fa-bell text-gray-600 text-xl"></i>
                                <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 rounded-full flex items-center justify-center text-white text-xs font-bold">3</span>
                            </button>
                            
                            <!-- Notification Dropdown -->
                            <div class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-gray-100 hidden" id="notificationDropdown">
                                <div class="p-4 border-b border-gray-100">
                                    <h3 class="font-semibold text-gray-800">Notifikasi</h3>
                                </div>
                                <div class="max-h-96 overflow-y-auto">
                                    <div class="p-4 hover:bg-gray-50 border-b border-gray-100">
                                        <p class="text-sm text-gray-800"><span class="font-semibold">5 siswa</span> baru mendaftar</p>
                                        <p class="text-xs text-gray-500 mt-1">5 menit yang lalu</p>
                                    </div>
                                    <div class="p-4 hover:bg-gray-50 border-b border-gray-100">
                                        <p class="text-sm text-gray-800"><span class="font-semibold">Pembayaran SPP</span> atas nama Budi Santoso</p>
                                        <p class="text-xs text-gray-500 mt-1">1 jam yang lalu</p>
                                    </div>
                                    <div class="p-4 hover:bg-gray-50">
                                        <p class="text-sm text-gray-800"><span class="font-semibold">12 siswa</span> belum membayar SPP</p>
                                        <p class="text-xs text-gray-500 mt-1">3 jam yang lalu</p>
                                    </div>
                                </div>
                                <div class="p-4 border-t border-gray-100 text-center">
                                    <a href="#" class="text-sm text-[#0b4f8c] hover:underline">Lihat semua</a>
                                </div>
                            </div>
                        </div>

                        <!-- Profile Dropdown -->
                        <div class="relative">
                            <div class="profile-button" id="profileBtn">
                                <div class="profile-avatar">
                                    <span>{{ substr(auth()->user()->name ?? 'AD', 0, 2) }}</span>
                                </div>
                                <div class="text-left">
                                    <p class="font-semibold text-gray-800 text-sm">{{ auth()->user()->name ?? 'Admin Utama' }}</p>
                                    <p class="text-xs text-gray-500">{{ auth()->user()->email ?? 'admin@smapgri.sch.id' }}</p>
                                </div>
                                <i class="fas fa-chevron-down text-gray-400 text-sm ml-4"></i>
                            </div>
                            
                            <!-- Profile Dropdown Menu -->
                            <div class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 hidden" id="profileDropdown">
                                <a href="#" class="flex items-center space-x-3 p-4 hover:bg-gray-50 border-b border-gray-100">
                                    <i class="fas fa-user text-gray-500"></i>
                                    <span class="text-sm text-gray-700">Profil Saya</span>
                                </a>
                                <a href="#" class="flex items-center space-x-3 p-4 hover:bg-gray-50 border-b border-gray-100">
                                    <i class="fas fa-cog text-gray-500"></i>
                                    <span class="text-sm text-gray-700">Pengaturan</span>
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left flex items-center space-x-3 p-4 hover:bg-gray-50">
                                        <i class="fas fa-sign-out-alt text-gray-500"></i>
                                        <span class="text-sm text-gray-700">Keluar</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <div class="p-6 pt-0">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Global Modal -->
    <div class="modal-overlay" id="globalModal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800" id="modalTitle"></h3>
                <button class="text-gray-500 hover:text-gray-700" id="closeModal">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            <div id="modalBody"></div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Toggle Notification Dropdown
        document.getElementById('notificationBtn')?.addEventListener('click', function(e) {
            e.stopPropagation();
            const dropdown = document.getElementById('notificationDropdown');
            const profileDropdown = document.getElementById('profileDropdown');
            
            if (profileDropdown) profileDropdown.classList.add('hidden');
            dropdown.classList.toggle('hidden');
        });

        // Toggle Profile Dropdown
        document.getElementById('profileBtn')?.addEventListener('click', function(e) {
            e.stopPropagation();
            const dropdown = document.getElementById('profileDropdown');
            const notificationDropdown = document.getElementById('notificationDropdown');
            
            if (notificationDropdown) notificationDropdown.classList.add('hidden');
            dropdown.classList.toggle('hidden');
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function() {
            const notificationDropdown = document.getElementById('notificationDropdown');
            const profileDropdown = document.getElementById('profileDropdown');
            
            if (notificationDropdown) notificationDropdown.classList.add('hidden');
            if (profileDropdown) profileDropdown.classList.add('hidden');
        });

        // Global search functionality
        document.getElementById('globalSearch')?.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                const searchTerm = this.value.toLowerCase();
                // Implement search logic here
                console.log('Searching for:', searchTerm);
            }
        });

        // Modal functions
        window.showModal = function(title, content) {
            document.getElementById('modalTitle').textContent = title;
            document.getElementById('modalBody').innerHTML = content;
            document.getElementById('globalModal').style.display = 'flex';
        };

        document.getElementById('closeModal')?.addEventListener('click', function() {
            document.getElementById('globalModal').style.display = 'none';
        });

        window.addEventListener('click', function(e) {
            if (e.target === document.getElementById('globalModal')) {
                document.getElementById('globalModal').style.display = 'none';
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>