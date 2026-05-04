@extends('layouts.admin')

@section('title', 'Log Aktivitas')

@section('content')
<!-- Header -->
<div class="mb-8 animate-slide-in">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white drop-shadow-lg">Log Aktivitas</h1>
            <p class="text-white/80 mt-1">Catatan semua aktivitas pengguna dalam sistem</p>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
@php
    $totalLogs = $logs->total();
    $todayLogs = $logs->getCollection()->filter(function($log) {
        return $log->created_at->isToday();
    })->count();
    $uniqueUsers = $logs->getCollection()->unique('user_id')->count();
    $latestAction = $logs->first()?->created_at->diffForHumans() ?? '-';
@endphp

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 animate-slide-in delay-1">
    <div class="glass-card rounded-2xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Total Aktivitas</p>
                <h3 class="text-3xl font-bold text-[#0b4f8c]">{{ $totalLogs }}</h3>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-history text-2xl text-blue-600"></i>
            </div>
        </div>
    </div>
    
    <div class="glass-card rounded-2xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Aktivitas Hari Ini</p>
                <h3 class="text-3xl font-bold text-green-600">{{ $todayLogs }}</h3>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-calendar-day text-2xl text-green-600"></i>
            </div>
        </div>
    </div>
    
    <div class="glass-card rounded-2xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Pengguna Aktif</p>
                <h3 class="text-3xl font-bold text-purple-600">{{ $uniqueUsers }}</h3>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-users text-2xl text-purple-600"></i>
            </div>
        </div>
    </div>
    
    <div class="glass-card rounded-2xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Aktivitas Terbaru</p>
                <h3 class="text-2xl font-bold text-orange-600">{{ $latestAction }}</h3>
            </div>
            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-clock text-2xl text-orange-600"></i>
            </div>
        </div>
    </div>
</div>

<!-- Search & Filter -->
<div class="glass-card rounded-2xl p-4 mb-6 animate-slide-in delay-1">
    <form method="GET" action="{{ route('admin.logs.index') }}" class="flex flex-wrap items-center gap-4">
        <div class="flex-1 min-w-[200px]">
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" placeholder="Cari aktivitas, modul, atau IP..." 
                       class="search-input" value="{{ request('search') }}">
            </div>
        </div>
        
        <div class="flex items-center space-x-2 flex-wrap gap-2">
            <select name="action" class="form-select w-36">
                <option value="">Semua Aksi</option>
                <option value="create" {{ request('action') == 'create' ? 'selected' : '' }}>Create</option>
                <option value="update" {{ request('action') == 'update' ? 'selected' : '' }}>Update</option>
                <option value="delete" {{ request('action') == 'delete' ? 'selected' : '' }}>Delete</option>
                <option value="login" {{ request('action') == 'login' ? 'selected' : '' }}>Login</option>
                <option value="logout" {{ request('action') == 'logout' ? 'selected' : '' }}>Logout</option>
            </select>
            
            <select name="module" class="form-select w-36">
                <option value="">Semua Modul</option>
                <option value="siswa" {{ request('module') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                <option value="kelas" {{ request('module') == 'kelas' ? 'selected' : '' }}>Kelas</option>
                <option value="pembayaran" {{ request('module') == 'pembayaran' ? 'selected' : '' }}>Pembayaran</option>
                <option value="user" {{ request('module') == 'user' ? 'selected' : '' }}>User</option>
            </select>
            
            <input type="date" name="from_date" class="form-input w-40" placeholder="Dari tgl" value="{{ request('from_date') }}">
            <input type="date" name="to_date" class="form-input w-40" placeholder="Sampai tgl" value="{{ request('to_date') }}">
            
            <button type="submit" class="btn-primary text-sm py-3">
                <i class="fas fa-filter mr-2"></i>
                Filter
            </button>
            
            <a href="{{ route('admin.logs.index') }}" class="btn-secondary text-sm py-3">
                <i class="fas fa-redo-alt mr-2"></i>
                Reset
            </a>
        </div>
    </form>
</div>

<!-- Log Activity Table -->
<div class="table-container animate-slide-in delay-2">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gradient-to-r from-[#0b4f8c] to-[#1e6f9f] text-white">
                    <th class="px-6 py-3 text-left text-sm font-semibold">No.</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">User</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Aksi</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Modul</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Aktivitas</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">IP Address</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Data Tambahan</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $index => $log)
                <tr class="table-row hover:bg-gray-50 transition" data-id="{{ $log->id }}">
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $logs->firstItem() + $index }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                <span class="text-blue-600 font-medium text-sm">
                                    {{ strtoupper(substr($log->user->name ?? 'U', 0, 1)) }}
                                </span>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-800">{{ $log->user->name ?? 'User tidak ditemukan' }}</p>
                                <p class="text-xs text-gray-500">{{ $log->user->email ?? '' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $badgeClass = match($log->action) {
                                'create' => 'bg-green-100 text-green-800',
                                'update' => 'bg-yellow-100 text-yellow-800',
                                'delete' => 'bg-red-100 text-red-800',
                                'login' => 'bg-blue-100 text-blue-800',
                                'logout' => 'bg-gray-100 text-gray-800',
                                default => 'bg-gray-100 text-gray-800',
                            };
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $badgeClass }}">
                            {{ ucfirst($log->action ?? '-') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $log->module ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700 max-w-xs truncate" title="{{ $log->aktivitas }}">
                        {{ Str::limit($log->aktivitas, 60) }}
                    </td>
                    <td class="px-6 py-4 text-sm font-mono text-gray-500">{{ $log->ip_address ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        @if($log->data && count($log->data) > 0)
                            <details class="cursor-pointer">
                                <summary class="text-blue-600 text-xs hover:underline">Lihat detail</summary>
                                <pre class="mt-1 text-xs bg-gray-50 p-2 rounded overflow-x-auto">{{ json_encode($log->data, JSON_PRETTY_PRINT) }}</pre>
                            </details>
                        @else
                            <span class="text-gray-400 text-xs">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-700">{{ $log->created_at->format('d/m/Y H:i:s') }}</div>
                        <div class="text-xs text-gray-400">{{ $log->created_at->diffForHumans() }}</div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-inbox text-5xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500 text-lg mb-2">Belum ada log aktivitas</p>
                            <p class="text-gray-400 text-sm">Log akan muncul saat pengguna melakukan aktivitas</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex items-center justify-between mt-6">
        <p class="text-sm text-gray-500">
            Menampilkan {{ $logs->firstItem() ?? 0 }} - {{ $logs->lastItem() ?? 0 }} 
            dari {{ $logs->total() }} data
        </p>
        <div class="flex items-center space-x-2">
            {{ $logs->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto hide any flash messages if present (optional)
    setTimeout(function() {
        const successMsg = document.getElementById('successMessage');
        const errorMsg = document.getElementById('errorMessage');
        if (successMsg) successMsg.remove();
        if (errorMsg) errorMsg.remove();
    }, 5000);
</script>
@endpush