<x-guest-layout>

<style>

.login-card{
    max-width:420px;
    margin:auto;
}

.login-btn{
    width:100%;
    padding:12px;
    border:none;
    border-radius:8px;
    background:#2f6df6;
    color:white;
    font-weight:600;
    transition:0.3s;
}

.login-btn:hover{
    background:#1e52c9;
}

.login-btn:disabled{
    background:#7aa2ff;
    cursor:not-allowed;
}

.alert-login{
    background:#ff4d4f;
    color:white;
    padding:10px;
    border-radius:6px;
    margin-bottom:15px;
    text-align:center;
}

.form-control{
    border-radius:8px;
}

.spinner{
    border:3px solid rgba(255,255,255,0.4);
    border-top:3px solid white;
    border-radius:50%;
    width:16px;
    height:16px;
    display:inline-block;
    animation:spin 0.8s linear infinite;
}

@keyframes spin{
    0%{ transform:rotate(0deg); }
    100%{ transform:rotate(360deg); }
}

</style>

<div class="login-card">

<h4 class="text-center mb-4">Login Account</h4>

@if ($errors->any())
    <div class="alert-login">
        Email atau password salah
    </div>
@endif

<form id="loginForm" method="POST" action="{{ route('login') }}">
@csrf

<div class="mb-3">
<label>Email</label>
<input type="email" name="email"
class="form-control"
placeholder="Masukkan email"
required autofocus>
</div>

<div class="mb-3">
<label>Password</label>
<input type="password" name="password"
class="form-control"
placeholder="Masukkan password"
required>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">

<div>
<input type="checkbox" name="remember">
<small>Remember me</small>
</div>

@if (Route::has('password.request'))
<a href="{{ route('password.request') }}">
Lupa password?
</a>
@endif

</div>

<button id="loginButton" type="submit" class="login-btn">

<span id="loginText">Login</span>

<span id="loginLoading" style="display:none;">
<span class="spinner"></span>
</span>

</button>

</form>

<div class="text-center mt-3">

<small>
Belum punya akun?
<a href="{{ route('register') }}">Register</a>
</small>

</div>

</div>


<script>

document.getElementById("loginForm").addEventListener("submit", function(){

document.getElementById("loginText").style.display="none";

document.getElementById("loginLoading").style.display="inline-block";

document.getElementById("loginButton").disabled=true;

});

</script>

</x-guest-layout>