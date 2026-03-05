<x-guest-layout>

        <h3 class="text-center text-white mb-4">
            Reset Password
        </h3>

        <p class="text-center text-gray-300 mb-4">
            Masukkan email kamu dan kami akan mengirimkan link untuk reset password.
        </p>

        <x-auth-session-status class="mb-4 text-success" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-3">
                <label class="text-white">Email</label>

                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       class="form-control premium-input"
                       placeholder="Masukkan email kamu"
                       required autofocus>

                <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger"/>
            </div>

            <button type="submit" class="btn premium-btn w-100 mt-3">
                Kirim Link Reset Password
            </button>

        </form>

        <p class="text-center text-gray-300 mt-4">
            <a href="{{ route('login') }}" class="text-blue-400">
                Kembali ke Login
            </a>
        </p>

    </div>

</div>


<style>

body{
background: linear-gradient(135deg,#0f172a,#1e293b);
min-height:100vh;
display:flex;
align-items:center;
justify-content:center;
}

.login-box{
width:420px;
background:#1e293b;
padding:40px;
border-radius:12px;
box-shadow:0 20px 40px rgba(0,0,0,0.4);
}

.premium-input{
background:#374151;
border:none;
color:white;
padding:12px;
border-radius:8px;
}

.premium-input::placeholder{
color:#9ca3af;
}

.premium-input:focus{
background:#374151;
color:white;
box-shadow:none;
}

.premium-btn{
background:linear-gradient(90deg,#3b82f6,#2563eb);
border:none;
padding:12px;
border-radius:8px;
font-weight:600;
color:white;
}

.premium-btn:hover{
background:linear-gradient(90deg,#2563eb,#1d4ed8);
}

</style>

</x-guest-layout>