@extends('layouts.auth')

@section('title', 'Login - SIAPABAJA')

@section('content')
@php
    // role default ppk, bisa diganti via /login?role=unit
    $role = request('role', 'ppk');
    // Cek apakah ada error dari session (dari redirect back)
    $hasError = session()->has('errors') || session('status') || request()->boolean('error');
@endphp

<section class="login-figma">
  <div class="login-figma-bg">

    {{-- Banner error (dari Laravel atau session) --}}
    @if($hasError)
      <div class="login-error">
        @if($errors->has('email'))
          {{ $errors->first('email') }}
        @elseif(session('status'))
          {{ session('status') }}
        @else
          Email atau Kata Sandi salah!
        @endif
      </div>
    @endif

    <div class="login-figma-card">
      <h2 class="login-figma-title">Masuk</h2>

      <p class="login-figma-desc">
        Silakan masukkan email dan kata sandi Anda untuk melanjutkan.
      </p>

      {{-- Form login REAL (POST ke server) --}}
      <form class="login-figma-form" id="loginForm" action="{{ url('/login') }}" method="POST">
        @csrf

        <input type="hidden" name="role" value="{{ $role }}">

        <div class="fg">
          <label>Email</label>
          <input
            type="email"
            id="email"
            name="email"
            placeholder="esteban.schiller@gmail.com"
            autocomplete="email"
            value="{{ old('email') }}"
            required
            autofocus
          >
          @error('email')
            <span class="error-text">{{ $message }}</span>
          @enderror
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
          @error('password')
            <span class="error-text">{{ $message }}</span>
          @enderror
        </div>

        <label class="fg-remember">
          <input type="checkbox" name="remember" id="remember">
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
@endsection