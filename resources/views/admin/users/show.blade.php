<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Detail User: {{ $user->name }}</h1>
        <div>
            {{-- Tombol kembali ke daftar --}}
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
            </a>
            {{-- Tombol Edit --}}
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit User
            </a>
             {{-- Tombol Hapus hanya jika BUKAN user yang sedang login --}}
            @if (Auth::user()->id != $user->id)
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('PERINGATAN: Menghapus user ini bersifat permanen. Apakah Anda yakin?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt"></i> Hapus User
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            Informasi Detail User
        </div>
        <div class="card-body">
            <dl class="row"> {{-- Definition List untuk tampilan key-value --}}
                <dt class="col-sm-3">ID User</dt>
                <dd class="col-sm-9">{{ $user->id }}</dd>

                <dt class="col-sm-3">Nama Lengkap</dt>
                <dd class="col-sm-9">{{ $user->name }}</dd>

                <dt class="col-sm-3">Alamat Email</dt>
                <dd class="col-sm-9">{{ $user->email }}</dd>

                <dt class="col-sm-3">Status Verifikasi Email</dt>
                <dd class="col-sm-9">
                    @if ($user->email_verified_at)
                        <span class="badge bg-success">Terverifikasi</span>
                        (pada {{ $user->email_verified_at->format('d M Y H:i') }})
                    @else
                        <span class="badge bg-warning text-dark">Belum Terverifikasi</span>
                    @endif
                </dd>

                <dt class="col-sm-3">Status Admin</dt>
                <dd class="col-sm-9">
                    @if ($user->is_admin)
                        <span class="badge bg-primary">Admin</span>
                    @else
                        <span class="badge bg-secondary">User Biasa</span>
                    @endif
                </dd>

                <dt class="col-sm-3">Tanggal Akun Dibuat</dt>
                <dd class="col-sm-9">{{ $user->created_at->format('d M Y H:i:s') }} ({{ $user->created_at->diffForHumans() }})</dd>

                <dt class="col-sm-3">Tanggal Terakhir Diperbarui</dt>
                <dd class="col-sm-9">{{ $user->updated_at->format('d M Y H:i:s') }} ({{ $user->updated_at->diffForHumans() }})</dd>

                {{-- Anda bisa menambahkan detail lain jika diperlukan --}}
                {{-- Contoh: Menampilkan data dari relasi (jika ada) --}}
                {{--
                <dt class="col-sm-3">Jumlah Postingan</dt>
                <dd class="col-sm-9">{{ $user->posts->count() }}</dd>
                --}}

            </dl>
        </div>
         <div class="card-footer text-muted">
             Informasi diambil pada: {{ now()->format('d M Y H:i:s') }}
         </div>
    </div>

    {{-- Jika Anda ingin menambahkan bagian lain, misal riwayat aktivitas user --}}
    {{-- <div class="card mt-4"> ... </div> --}}
</x-app-layout>
