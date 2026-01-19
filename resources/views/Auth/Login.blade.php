@extends('layouts.auth')

@section('title', 'Login - SIAPABAJA')

@section('content')
@php
  // role default ppk, bisa diganti via /login?role=unit
  $role = request('role', 'ppk');
@endphp

<section class="login-figma">
  <div class="login-figma-bg">
    <div class="login-figma-card">

      <h2 class="login-figma-title">Masuk</h2>
      <p class="login-figma-desc">
        Silakan masukkan email dan kata sandi Anda untuk melanjutkan.
      </p>

      <form class="login-figma-form" action="{{ $role === 'unit' ? '/login/unit' : '/login/ppk' }}" method="POST" onsubmit="return false;">
        {{-- nanti backend: @csrf --}}
        <input type="hidden" name="role" value="{{ $role }}">

        <div class="fg">
          <label>Email</label>
          <input type="email" name="email" placeholder="esteban.schiller@gmail.com" autocomplete="email">
        </div>

        <div class="fg">
          <label>Kata Sandi</label>
          <input type="password" name="password" placeholder="••••••••" autocomplete="current-password">
        </div>

        <label class="fg-remember">
          <input type="checkbox" name="remember">
          <span>Ingat Kata Sandi</span>
        </label>

        <button class="fg-btn" type="submit">Masuk</button>

        <a class="fg-back" href="{{ url('/#beranda') }}">‹ Kembali ke Beranda</a>
      </form>

    </div>
  </div>
</section>
@endsection
