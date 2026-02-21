<h2>Data User</h2>

<a href="{{ route('users.create') }}">Tambah User</a>

<table border="1">
    <tr>
        <th>Nama</th>
        <th>Email</th>
        <th>Role</th>
    </tr>

    @foreach($users as $user)
    <tr>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
        <td>{{ $user->role->nama_role }}</td>
    </tr>
    @endforeach
</table>