<form action="{{ route('profile.updateImage') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="space-y-2">
        <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
            Upload New Profile Image
        </label>
        <x-input-file id="image" name="image" label="Profile Image" required />
        <x-input-error :messages="$errors->get('image')" class="mt-2" />
    </div>
    @push('scripts')
        @if (session('status') === 'profile-image-updated')
            <script>
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Foto profil berhasil diperbarui.',
                    icon: 'success',
                    confirmButtonColor: '#ec4899',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = "{{ route('profile.edit') }}"; // full page reload
                });
            </script>
        @endif
    @endpush


    <div class="text-right mt-3">
        <button type="submit"
            class="inline-flex items-center px-5 py-2 bg-pink-600 hover:bg-pink-700 text-white text-sm font-medium rounded-xl shadow transition-colors focus:outline-none focus:ring-2 focus:ring-pink-400">
            {{ __('Save') }}
        </button>
    </div>
</form>
