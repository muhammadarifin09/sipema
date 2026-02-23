@extends('layouts.admin')

@section('title', 'Data User - SMA PGRI Pelaihari')

@section('content')
<!-- Header -->
<div class="mb-8 animate-slide-in">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white drop-shadow-lg">Data User</h1>
            <p class="text-white/80 mt-1">Kelola data pengguna sistem</p>
        </div>
        <button onclick="openCreateModal()" class="btn-primary">
            <i class="fas fa-plus mr-2"></i>
            Tambah User
        </button>
    </div>
</div>

<!-- Search & Filter -->
<!-- Search & Filter -->
<div class="glass-card rounded-2xl p-4 mb-6 animate-slide-in delay-1">
    <div class="flex flex-wrap items-center gap-4">
        <div class="flex-1 min-w-[200px]">
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" placeholder="Cari user..." class="search-input" id="searchInput">
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <select class="form-select w-40" id="roleFilter">
                <option value="">Semua Role</option>
                <option value="admin" style="color: #059669;">Admin</option>
                <option value="bendahara" style="color: #b45309;">Bendahara</option>
                <option value="wali" style="color: #6b21a5;"> Wali Murid</option>
            </select>
            <button onclick="resetFilter()" class="btn-secondary text-sm py-3">
                <i class="fas fa-redo-alt mr-2"></i>
                Reset
            </button>
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="table-container animate-slide-in delay-2">
    <div class="overflow-x-auto">
        <table class="w-full" id="usersTable">
            <thead>
                <tr class="bg-gradient-to-r from-[#0b4f8c] to-[#1e6f9f] text-white">
                    <th class="px-6 py-3 text-left text-sm font-semibold rounded-tl-xl">No.</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Nama</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Email</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Role</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold rounded-tr-xl">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $index => $user)
                <tr class="table-row" data-role="{{ $user->role->nama_role }}" data-name="{{ strtolower($user->name) }}" data-email="{{ strtolower($user->email) }}">
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $index + 1 }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <div class="profile-avatar w-8 h-8 text-xs" style="background: {{ 
                                $user->role->nama_role == 'admin' ? 'linear-gradient(135deg, #059669, #10b981)' : 
                                ($user->role->nama_role == 'bendahara' ? 'linear-gradient(135deg, #b45309, #d97706)' : 
                                'linear-gradient(135deg, #6b21a5, #8b5cf6)') 
                            }};">
                                <span>{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                            </div>
                            <span class="font-medium text-gray-800">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $user->email }}</td>
                    <td class="px-6 py-4">
                        @if($user->role->nama_role == 'admin')
                            <span class="badge-success">{{ ucfirst($user->role->nama_role) }}</span>
                        @elseif($user->role->nama_role == 'bendahara')
                            <span class="badge-warning">{{ ucfirst($user->role->nama_role) }}</span>
                        @else
                            <span class="badge-info">{{ ucfirst($user->role->nama_role) }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-2">
                            <button onclick="openEditModal({{ $user->id }})" class="text-green-600 hover:text-green-700 p-2 hover:bg-green-50 rounded-xl transition-all" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="confirmDelete({{ $user->id }}, '{{ $user->name }}')" class="text-red-600 hover:text-red-700 p-2 hover:bg-red-50 rounded-xl transition-all" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination Info -->
    <div class="flex items-center justify-between mt-6">
        <p class="text-sm text-gray-500">Menampilkan {{ $users->count() }} data</p>
    </div>
</div>

<!-- CREATE MODAL -->
<div class="modal-overlay" id="createModal" style="display: none;">
    <div class="modal-content max-w-2xl">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-gray-800">Tambah User Baru</h3>
            <button onclick="closeCreateModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        
        <form action="{{ route('admin.users.store') }}" method="POST" id="createForm">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="form-label">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" class="form-input" required>
                </div>
                
                <div>
                    <label class="form-label">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" class="form-input" required>
                </div>
                
                <div>
                    <label class="form-label">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" class="form-input" required min="6">
                </div>
                
                <div>
                    <label class="form-label">Role <span class="text-red-500">*</span></label>
                    <select name="role_id" class="form-select" required>
                        <option value="">Pilih Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" 
                                @if($role->nama_role == 'admin') style="color: #059669; font-weight: 600;" @endif
                                @if($role->nama_role == 'bendahara') style="color: #b45309; font-weight: 600;" @endif
                                @if($role->nama_role == 'wali') style="color: #6b21a5; font-weight: 600;" @endif
                            >
                                {{ ucfirst($role->nama_role) }}
                                @if($role->nama_role == 'admin')  @endif
                                @if($role->nama_role == 'bendahara')  @endif
                                @if($role->nama_role == 'wali')  @endif
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Admin dapat mengakses seluruh fitur</p>
                </div>
            </div>
            
            <div class="flex items-center justify-end space-x-4 mt-6 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeCreateModal()" class="btn-secondary">
                    Batal
                </button>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i>
                    Simpan User
                </button>
            </div>
        </form>
    </div>
</div>

<!-- EDIT MODAL -->
<div class="modal-overlay" id="editModal" style="display: none;">
    <div class="modal-content max-w-2xl">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-gray-800">Edit User</h3>
            <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        
        <form action="" method="POST" id="editForm">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="form-label">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="edit_name" class="form-input" required>
                </div>
                
                <div>
                    <label class="form-label">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="edit_email" class="form-input" required>
                </div>
                
                <div>
                    <label class="form-label">Password (Kosongkan jika tidak diubah)</label>
                    <input type="password" name="password" class="form-input" min="6">
                </div>
                
                <div>
                    <label class="form-label">Role <span class="text-red-500">*</span></label>
                    <select name="role_id" id="edit_role_id" class="form-select" required>
                        <option value="">Pilih Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" 
                                @if($role->nama_role == 'admin') style="color: #059669; font-weight: 600;" @endif
                                @if($role->nama_role == 'bendahara') style="color: #b45309; font-weight: 600;" @endif
                                @if($role->nama_role == 'wali') style="color: #6b21a5; font-weight: 600;" @endif
                            >
                                {{ ucfirst($role->nama_role) }}
                                @if($role->nama_role == 'admin') 👑 @endif
                                @if($role->nama_role == 'bendahara') 💰 @endif
                                @if($role->nama_role == 'wali') 👨‍👩‍👧 @endif
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Admin dapat mengakses seluruh fitur</p>
                </div>
            </div>
            
            <div class="flex items-center justify-end space-x-4 mt-6 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeEditModal()" class="btn-secondary">
                    Batal
                </button>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i>
                    Update User
                </button>
            </div>
        </form>
    </div>
</div>

<!-- DELETE CONFIRMATION MODAL -->
<div class="modal-overlay" id="deleteModal" style="display: none;">
    <div class="modal-content max-w-md">
        <div class="text-center">
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-4xl"></i>
            </div>
            
            <h3 class="text-xl font-bold text-gray-800 mb-2">Konfirmasi Hapus</h3>
            <p class="text-gray-600 mb-6" id="deleteMessage">Apakah Anda yakin ingin menghapus user ini?</p>
            
            <form action="" method="POST" id="deleteForm">
                @csrf
                @method('DELETE')
                
                <div class="flex items-center justify-center space-x-4">
                    <button type="button" onclick="closeDeleteModal()" class="btn-secondary">
                        Batal
                    </button>
                    <button type="submit" class="btn-danger">
                        <i class="fas fa-trash mr-2"></i>
                        Ya, Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Success Message -->
@if(session('success'))
<div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-xl shadow-lg flex items-center space-x-2 animate-slide-in z-50" id="successMessage">
    <i class="fas fa-check-circle"></i>
    <span>{{ session('success') }}</span>
    <button onclick="this.parentElement.remove()" class="ml-4 hover:text-white/80">
        <i class="fas fa-times"></i>
    </button>
</div>
@endif

<!-- Error Message -->
@if(session('error'))
<div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-xl shadow-lg flex items-center space-x-2 animate-slide-in z-50" id="errorMessage">
    <i class="fas fa-exclamation-circle"></i>
    <span>{{ session('error') }}</span>
    <button onclick="this.parentElement.remove()" class="ml-4 hover:text-white/80">
        <i class="fas fa-times"></i>
    </button>
</div>
@endif
@endsection

@push('scripts')
<script>
    // Data users untuk edit (simpan dalam format JSON)
    const usersData = @json($users);
    
    // ==================== CREATE MODAL ====================
    function openCreateModal() {
        document.getElementById('createModal').style.display = 'flex';
    }
    
    function closeCreateModal() {
        document.getElementById('createModal').style.display = 'none';
        document.getElementById('createForm').reset();
    }
    
    // ==================== EDIT MODAL ====================
    function openEditModal(userId) {
        const user = usersData.find(u => u.id === userId);
        
        if (user) {
            document.getElementById('edit_name').value = user.name;
            document.getElementById('edit_email').value = user.email;
            document.getElementById('edit_role_id').value = user.role_id;
            
            // Set form action
            document.getElementById('editForm').action = `/admin/users/${userId}`;
            
            document.getElementById('editModal').style.display = 'flex';
        }
    }
    
    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
        document.getElementById('editForm').reset();
    }
    
    // ==================== DELETE MODAL ====================
    function confirmDelete(userId, userName) {
        document.getElementById('deleteMessage').innerHTML = `Apakah Anda yakin ingin menghapus user <strong>${userName}</strong>?`;
        document.getElementById('deleteForm').action = `/admin/users/${userId}`;
        document.getElementById('deleteModal').style.display = 'flex';
    }
    
    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }
    
    // ==================== SEARCH & FILTER ====================
    document.getElementById('searchInput').addEventListener('keyup', function() {
        filterTable();
    });
    
    document.getElementById('roleFilter').addEventListener('change', function() {
        filterTable();
    });
    
    function filterTable() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const roleFilter = document.getElementById('roleFilter').value.toLowerCase();
        const rows = document.querySelectorAll('#usersTable tbody tr');
        
        rows.forEach(row => {
            const name = row.getAttribute('data-name');
            const email = row.getAttribute('data-email');
            const role = row.getAttribute('data-role').toLowerCase();
            
            const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm);
            const matchesRole = roleFilter === '' || role === roleFilter;
            
            row.style.display = matchesSearch && matchesRole ? '' : 'none';
        });
    }
    
    function resetFilter() {
        document.getElementById('searchInput').value = '';
        document.getElementById('roleFilter').value = '';
        
        const rows = document.querySelectorAll('#usersTable tbody tr');
        rows.forEach(row => row.style.display = '');
    }
    
    // ==================== CLOSE MODALS WHEN CLICKING OUTSIDE ====================
    window.addEventListener('click', function(e) {
        const createModal = document.getElementById('createModal');
        const editModal = document.getElementById('editModal');
        const deleteModal = document.getElementById('deleteModal');
        
        if (e.target === createModal) {
            closeCreateModal();
        }
        if (e.target === editModal) {
            closeEditModal();
        }
        if (e.target === deleteModal) {
            closeDeleteModal();
        }
    });
    
    // Auto hide success/error messages after 5 seconds
    setTimeout(function() {
        const successMsg = document.getElementById('successMessage');
        const errorMsg = document.getElementById('errorMessage');
        
        if (successMsg) successMsg.remove();
        if (errorMsg) errorMsg.remove();
    }, 5000);
</script>
@endpush