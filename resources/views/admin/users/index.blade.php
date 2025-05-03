
<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Daftar User</h1>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Tambah User Baru</a>
    </div>

    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nama</th>
                <th scope="col">Email</th>
                <th scope="col">Admin?</th>
                <th scope="col">Tgl Dibuat</th>
                <th scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <th scope="row">{{ $loop->iteration + $users->firstItem() - 1 }}</th> {{-- Penomoran Paginasi --}}
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->is_admin ? 'Ya' : 'Tidak' }}</td>
                    <td>{{ $user->created_at->format('d M Y H:i') }}</td> {{-- Format tanggal --}}
                    <td>
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning">Edit</a>
                        {{-- Tombol Hapus dengan Konfirmasi --}}
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" {{ Auth::user()->id == $user->id ? 'disabled' : '' }}>
                                Hapus
                            </button> {{-- Disable jika user saat ini --}}
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada data user.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Tampilkan Link Paginasi --}}
    <div class="d-flex justify-content-center">
        {{ $users->links() }}
    </div>
</x-app-layout>
