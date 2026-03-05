<x-guest-layout>


<h4 class="text-center mb-4">Create Account</h4>

<form method="POST" action="{{ route('register') }}">
    @csrf

    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="name" class="form-control"
            placeholder="Masukkan nama lengkap" required>
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control"
            placeholder="Masukkan email" required>
    </div>

    <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control"
            placeholder="Masukkan password" required>
    </div>

    <div class="mb-3">
        <label>Konfirmasi Password</label>
        <input type="password" name="password_confirmation" class="form-control"
            placeholder="Ulangi password" required>
    </div>

    <button type="submit" class="btn btn-primary w-100">
        Register
    </button>
</form>

<div class="text-center mt-3">
    <small>
        Sudah punya akun?
        <a href="{{ route('login') }}">Login</a>
    </small>
</div>

</x-guest-layout>