<x-app-layout>
    <h1>Edit User: {{ $user->name }}</h1>

    <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf {{-- Token CSRF --}}
        @method('PUT') {{-- Method Spoofing untuk Update --}}

        <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
             @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
             @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

         <hr>
         <p class="text-muted">Kosongkan password jika tidak ingin mengubahnya.</p>

        <div class="mb-3">
            <label for="password" class="form-label">Password Baru</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
             @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
        </div>

        <hr>

        <div class="mb-3 form-check">
             <input type="hidden" name="is_admin" value="0">
            <input type="checkbox" class="form-check-input @error('is_admin') is-invalid @enderror" id="is_admin" name="is_admin" value="1" {{ old('is_admin', $user->is_admin) ? 'checked' : '' }} {{ Auth::user()->id == $user->id ? 'disabled' : '' }}> {{-- Disable jika user saat ini --}}
            <label class="form-check-label" for="is_admin">Jadikan Admin?</label>
             @error('is_admin')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
             @if(Auth::user()->id == $user->id)
             <small class="form-text text-muted d-block">Anda tidak dapat mengubah status admin diri sendiri.</small>
             @endif
        </div>


        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">Update User</button>
    </form>
</x-app-layout>
