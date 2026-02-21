<h2>Tambah User</h2>

<form method="POST" action="{{ route('users.store') }}">
    @csrf

    <label>Nama</label>
    <input type="text" name="name">

    <br>

    <label>Email</label>
    <input type="email" name="email">

    <br>

    <label>Password</label>
    <input type="password" name="password">

    <br>

    <label>Role</label>
    <select name="role_id">
        @foreach($roles as $role)
            <option value="{{ $role->id }}">
                {{ $role->nama_role }}
            </option>
        @endforeach
    </select>

    <br><br>

    <button type="submit">Simpan</button>
</form>