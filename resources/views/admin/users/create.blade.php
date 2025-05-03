<x-app-layout>
    <h1>Tambah User Baru</h1>

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf {{-- Token CSRF --}}

        <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
             @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
             @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
        </div>

        <div class="mb-3 form-check">
            <input type="hidden" name="is_admin" value="0"> {{-- Nilai default jika checkbox tidak dicentang --}}
            <input type="checkbox" class="form-check-input @error('is_admin') is-invalid @enderror" id="is_admin" name="is_admin" value="1" {{ old('is_admin') ? 'checked' : '' }}>
            <label class="form-check-label" for="is_admin">Jadikan Admin?</label>
             @error('is_admin')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">Simpan User</button>
    </form>
</x-app-layout>
