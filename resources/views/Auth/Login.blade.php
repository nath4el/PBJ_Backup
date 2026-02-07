@extends('layouts.auth')

@section('title', 'Login - SIAPABAJA')

@section('content')
@php
  // role default ppk, bisa diganti via /login?role=unit
  $role = request('role', 'ppk');
  $showError = request()->boolean('error'); // tampilkan banner kalau ada ?error=1
@endphp

<section class="login-figma">
  <div class="login-figma-bg">

    {{-- Banner error (frontend only) --}}
    @if($showError)
      <div class="login-error">
        Username atau Password Salah!
      </div>
    @endif

    <div class="login-figma-card">
      <h2 class="login-figma-title">Masuk</h2>

      <p class="login-figma-desc">
        Silakan masukkan email dan kata sandi Anda untuk melanjutkan.
      </p>

      {{-- FRONTEND ONLY: jangan POST ke server --}}
      <form class="login-figma-form" id="loginForm" action="javascript:void(0)" method="GET">
        <input type="hidden" name="role" value="{{ $role }}">

        <div class="fg">
          <label>Email</label>
          <input
            type="email"
            id="email"
            name="email"
            placeholder="esteban.schiller@gmail.com"
            autocomplete="email"
            required
          >
        </div>

        <div class="fg">
          <label>Kata Sandi</label>
          <input
            type="password"
            id="password"
            name="password"
            placeholder="••••••••"
            autocomplete="current-password"
            required
          >
        </div>

        <label class="fg-remember">
          <input type="checkbox" name="remember">
          <span>Ingat Kata Sandi</span>
        </label>

        <button class="fg-btn" type="submit">Masuk</button>

        <a class="fg-back" href="{{ url('/') }}">
          ‹ Kembali
        </a>
      </form>
    </div>
  </div>
</section>

{{-- Simulasi login FRONTEND --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('loginForm');
  const emailEl = document.getElementById('email');
  const passEl  = document.getElementById('password');

  if(!form) return;

  const dummy = {
  ppk:  { email: 'ppk@unsoed.ac.id',  pass: '123456', redirect: '/home' },
  unit: { email: 'unit@unsoed.ac.id', pass: '123456', redirect: '/home' }
};

  form.addEventListener('submit', (e) => {
    e.preventDefault();

    const role = new URLSearchParams(window.location.search).get('role') || 'ppk';
    const inputEmail = (emailEl.value || '').trim().toLowerCase();
    const inputPass  = (passEl.value || '').trim();

    const akun = dummy[role] || dummy.ppk;

    // kalau cocok -> redirect dashboard
    if (inputEmail === akun.email && inputPass === akun.pass) {
      window.location.href = akun.redirect;
      return;
    }

    // kalau gagal -> reload halaman login dengan ?error=1
    const url = new URL(window.location.href);
    url.searchParams.set('role', role);
    url.searchParams.set('error', '1');
    window.location.href = url.toString();
  });
});
</script>
@endsection
