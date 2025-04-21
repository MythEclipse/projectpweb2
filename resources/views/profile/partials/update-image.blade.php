<form action="{{ route('profile.updateImage') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div>
        <label for="image">Upload New Profile Image</label>
        <input type="file" name="image" id="image" required>
    </div>
    <button type="submit">Update Image</button>
</form>
