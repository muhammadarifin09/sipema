<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SMA PGRI Pelaihari - Dashboard Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700|inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
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

        /* Mini stat cards untuk pengganti grafik */
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
        <!-- Sidebar (sama seperti sebelumnya) -->
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
                
                <div class="nav-item active">
                    <i class="fas fa-tachometer-alt w-6"></i>
                    <span class="ml-3 font-medium">Dashboard</span>
                </div>
                
                <div class="nav-item">
                    <i class="fas fa-users w-6"></i>
                    <span class="ml-3 font-medium">Data Siswa</span>
                </div>
                
                <div class="nav-item">
                    <i class="fas fa-credit-card w-6"></i>
                    <span class="ml-3 font-medium">Pembayaran SPP</span>
                </div>
                
                <div class="nav-item">
                    <i class="fas fa-history w-6"></i>
                    <span class="ml-3 font-medium">Riwayat Transaksi</span>
                </div>
                
                <div class="nav-item">
                    <i class="fas fa-chart-bar w-6"></i>
                    <span class="ml-3 font-medium">Laporan</span>
                </div>

                <div class="px-4 my-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Master Data</p>
                </div>

                <div class="nav-item">
                    <i class="fas fa-user-tie w-6"></i>
                    <span class="ml-3 font-medium">Data Admin</span>
                </div>

                <div class="nav-item">
                    <i class="fas fa-chalkboard-teacher w-6"></i>
                    <span class="ml-3 font-medium">Data Guru</span>
                </div>

                <div class="nav-item">
                    <i class="fas fa-book w-6"></i>
                    <span class="ml-3 font-medium">Data Kelas</span>
                </div>

                <div class="nav-item">
                    <i class="fas fa-calendar-alt w-6"></i>
                    <span class="ml-3 font-medium">Tahun Ajaran</span>
                </div>

                <!-- Settings -->
                <div class="px-4 my-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Pengaturan</p>
                </div>

                <div class="nav-item">
                    <i class="fas fa-cog w-6"></i>
                    <span class="ml-3 font-medium">Pengaturan</span>
                </div>

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
                        <input type="text" placeholder="Cari siswa, pembayaran..." class="search-input">
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
                        <div class="profile-button">
                            <div class="profile-avatar">
                                <span>AD</span>
                            </div>
                            <div class="text-left">
                                <p class="font-semibold text-gray-800 text-sm">Admin Utama</p>
                                <p class="text-xs text-gray-500">admin@smapgri.sch.id</p>
                            </div>
                            <i class="fas fa-chevron-down text-gray-400 text-sm ml-4"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dashboard Content -->
            <div class="p-6 pt-0">
                <!-- Welcome Header -->
                <div class="mb-8 animate-slide-in">
                    <h1 class="text-3xl font-bold text-white drop-shadow-lg">Dashboard Admin</h1>
                    <p class="text-white/80 mt-1">Selamat datang kembali! Berikut ringkasan data SPP hari ini.</p>
                </div>

                <!-- Stat Cards Utama -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Total Siswa -->
                    <div class="stat-card animate-slide-in delay-1">
                        <div class="stat-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <p class="text-gray-500 text-sm font-medium">Total Siswa</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-1">456</h3>
                        <p class="text-green-600 text-sm mt-2">
                            <i class="fas fa-arrow-up mr-1"></i>
                            +12 dari bulan lalu
                        </p>
                    </div>

                    <!-- SPP Bulan Ini -->
                    <div class="stat-card animate-slide-in delay-2">
                        <div class="stat-icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <p class="text-gray-500 text-sm font-medium">SPP Bulan Ini</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-1">Rp 68.400.000</h3>
                        <p class="text-green-600 text-sm mt-2">
                            <i class="fas fa-arrow-up mr-1"></i>
                            +8.5% dari bulan lalu
                        </p>
                    </div>

                    <!-- Siswa Lunas -->
                    <div class="stat-card animate-slide-in delay-3">
                        <div class="stat-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <p class="text-gray-500 text-sm font-medium">Siswa Lunas</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-1">342</h3>
                        <div class="progress-bar mt-3">
                            <div class="progress-fill" style="width: 75%"></div>
                        </div>
                        <p class="text-gray-500 text-xs mt-2">
                            <span class="text-green-600 font-medium">75%</span> dari total siswa
                        </p>
                    </div>

                    <!-- Tunggakan -->
                    <div class="stat-card animate-slide-in delay-4">
                        <div class="stat-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <p class="text-gray-500 text-sm font-medium">Total Tunggakan</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-1">Rp 12.540.000</h3>
                        <p class="text-red-600 text-sm mt-2">
                            <i class="fas fa-arrow-down mr-1"></i>
                            114 siswa menunggak
                        </p>
                    </div>
                </div>

                <!-- Statistik Tambahan (Pengganti Grafik) -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Per Kelas -->
                    <div class="info-card animate-slide-in delay-2">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-semibold text-gray-800">Statistik Per Kelas</h3>
                            <i class="fas fa-chart-pie text-[#0b4f8c] text-xl"></i>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-600">Kelas X</span>
                                    <span class="font-semibold text-gray-800">156 Siswa</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 34%"></div>
                                </div>
                            </div>
                            
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-600">Kelas XI</span>
                                    <span class="font-semibold text-gray-800">148 Siswa</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 32%"></div>
                                </div>
                            </div>
                            
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-600">Kelas XII</span>
                                    <span class="font-semibold text-gray-800">152 Siswa</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 33%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-2 mt-4 pt-4 border-t border-gray-100">
                            <div class="text-center">
                                <p class="text-xs text-gray-500">Lunas X</p>
                                <p class="font-bold text-gray-800">128</p>
                                <span class="badge-success text-[10px] px-2">82%</span>
                            </div>
                            <div class="text-center">
                                <p class="text-xs text-gray-500">Lunas XI</p>
                                <p class="font-bold text-gray-800">112</p>
                                <span class="badge-warning text-[10px] px-2">76%</span>
                            </div>
                            <div class="text-center">
                                <p class="text-xs text-gray-500">Lunas XII</p>
                                <p class="font-bold text-gray-800">102</p>
                                <span class="badge-danger text-[10px] px-2">67%</span>
                            </div>
                        </div>
                    </div>

                    <!-- 5 Pembayaran Tertinggi -->
                    <div class="info-card animate-slide-in delay-3">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-semibold text-gray-800">Top Pembayaran</h3>
                            <i class="fas fa-trophy text-[#0b4f8c] text-xl"></i>
                        </div>

                        <div class="space-y-3">
                            <div class="mini-stat-card">
                                <div class="mini-stat-icon">
                                    <i class="fas fa-crown"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-800 text-sm">XII IPA 1</p>
                                    <p class="text-xs text-gray-500">Total: Rp 23.450.000</p>
                                </div>
                                <span class="badge-info">#1</span>
                            </div>

                            <div class="mini-stat-card">
                                <div class="mini-stat-icon" style="background: linear-gradient(135deg, #4a5568, #718096);">
                                    <i class="fas fa-medal"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-800 text-sm">XI IPA 2</p>
                                    <p class="text-xs text-gray-500">Total: Rp 21.800.000</p>
                                </div>
                                <span class="badge-info">#2</span>
                            </div>

                            <div class="mini-stat-card">
                                <div class="mini-stat-icon" style="background: linear-gradient(135deg, #b45309, #d97706);">
                                    <i class="fas fa-medal"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-800 text-sm">X IPA 3</p>
                                    <p class="text-xs text-gray-500">Total: Rp 19.650.000</p>
                                </div>
                                <span class="badge-info">#3</span>
                            </div>
                        </div>
                    </div>

                    <!-- Info Penting -->
                    <div class="info-card animate-slide-in delay-4">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-semibold text-gray-800">Info Penting</h3>
                            <i class="fas fa-bullhorn text-[#0b4f8c] text-xl"></i>
                        </div>

                        <div class="space-y-3">
                            <div class="flex items-start space-x-3 p-3 bg-blue-50 rounded-xl">
                                <i class="fas fa-calendar-alt text-[#0b4f8c] mt-1"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">Jatuh Tempo SPP</p>
                                    <p class="text-xs text-gray-600">Batas pembayaran SPP bulan ini: 10 April 2024</p>
                                    <p class="text-xs text-red-600 mt-1">Sisa 5 hari lagi</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-3 p-3 bg-green-50 rounded-xl">
                                <i class="fas fa-check-circle text-green-600 mt-1"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">Target Penerimaan</p>
                                    <p class="text-xs text-gray-600">Realisasi: Rp 68.4jt dari Rp 91.2jt</p>
                                    <div class="progress-bar mt-2">
                                        <div class="progress-fill" style="width: 75%"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-start space-x-3 p-3 bg-yellow-50 rounded-xl">
                                <i class="fas fa-exclamation-triangle text-yellow-600 mt-1"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">Perhatian!</p>
                                    <p class="text-xs text-gray-600">114 siswa belum membayar SPP bulan ini</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 grid grid-cols-2 gap-2">
                            <div class="text-center p-2 bg-gray-50 rounded-xl">
                                <p class="text-xs text-gray-500">Hari Efektif</p>
                                <p class="text-lg font-bold text-gray-800">22</p>
                                <p class="text-[10px] text-gray-400">dari 30 hari</p>
                            </div>
                            <div class="text-center p-2 bg-gray-50 rounded-xl">
                                <p class="text-xs text-gray-500">Transaksi Hari Ini</p>
                                <p class="text-lg font-bold text-gray-800">47</p>
                                <p class="text-[10px] text-green-600">+12 dari kemarin</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Payments Table -->
                <div class="table-container animate-slide-in delay-5">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-semibold text-gray-800">Pembayaran Terbaru</h3>
                        <button class="btn-primary text-sm py-2">
                            <i class="fas fa-plus mr-2"></i>
                            Tambah Pembayaran
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gradient-to-r from-[#0b4f8c] to-[#1e6f9f] text-white">
                                    <th class="px-6 py-3 text-left text-sm font-semibold rounded-tl-xl">No.</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold">NIS</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold">Nama Siswa</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold">Kelas</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold">Jumlah</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold">Bulan</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold rounded-tr-xl">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="table-row">
                                    <td class="px-6 py-4 text-sm text-gray-700">1</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">2024001</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-800">Budi Santoso</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">XII IPA 1</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">Rp 150.000</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">Maret 2024</td>
                                    <td class="px-6 py-4"><span class="badge-success">Lunas</span></td>
                                    <td class="px-6 py-4">
                                        <button class="text-[#0b4f8c] hover:text-[#1e6f9f] mr-3">
                                            <i class="fas fa-print"></i>
                                        </button>
                                        <button class="text-green-600 hover:text-green-700">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="table-row">
                                    <td class="px-6 py-4 text-sm text-gray-700">2</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">2024045</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-800">Siti Aisyah</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">XI IPS 2</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">Rp 150.000</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">Maret 2024</td>
                                    <td class="px-6 py-4"><span class="badge-warning">Menunggu</span></td>
                                    <td class="px-6 py-4">
                                        <button class="text-[#0b4f8c] hover:text-[#1e6f9f] mr-3">
                                            <i class="fas fa-print"></i>
                                        </button>
                                        <button class="text-green-600 hover:text-green-700">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="table-row">
                                    <td class="px-6 py-4 text-sm text-gray-700">3</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">2024089</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-800">Ahmad Hidayat</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">X IPA 3</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">Rp 150.000</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">Februari 2024</td>
                                    <td class="px-6 py-4"><span class="badge-danger">Tunggakan</span></td>
                                    <td class="px-6 py-4">
                                        <button class="text-[#0b4f8c] hover:text-[#1e6f9f] mr-3">
                                            <i class="fas fa-print"></i>
                                        </button>
                                        <button class="text-green-600 hover:text-green-700">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="table-row">
                                    <td class="px-6 py-4 text-sm text-gray-700">4</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">2024012</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-800">Dewi Lestari</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">XII IPS 1</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">Rp 150.000</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">Maret 2024</td>
                                    <td class="px-6 py-4"><span class="badge-success">Lunas</span></td>
                                    <td class="px-6 py-4">
                                        <button class="text-[#0b4f8c] hover:text-[#1e6f9f] mr-3">
                                            <i class="fas fa-print"></i>
                                        </button>
                                        <button class="text-green-600 hover:text-green-700">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="flex items-center justify-between mt-6">
                        <p class="text-sm text-gray-500">Menampilkan 1-4 dari 25 data</p>
                        <div class="flex space-x-2">
                            <button class="w-10 h-10 rounded-xl border-2 border-gray-200 hover:border-[#0b4f8c] transition-all">
                                <i class="fas fa-chevron-left text-gray-400"></i>
                            </button>
                            <button class="w-10 h-10 rounded-xl bg-gradient-to-r from-[#0b4f8c] to-[#1e6f9f] text-white">1</button>
                            <button class="w-10 h-10 rounded-xl border-2 border-gray-200 hover:border-[#0b4f8c] transition-all">2</button>
                            <button class="w-10 h-10 rounded-xl border-2 border-gray-200 hover:border-[#0b4f8c] transition-all">3</button>
                            <button class="w-10 h-10 rounded-xl border-2 border-gray-200 hover:border-[#0b4f8c] transition-all">
                                <i class="fas fa-chevron-right text-gray-400"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>