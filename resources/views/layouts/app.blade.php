<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <title>@yield('title', 'SIAPABAJA')</title>

  
  <!-- Bootstrap Icons (WAJIB di dalam <head>) -->
  <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

  <!-- Nunito Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">

  <!-- CSS Landing -->
  <link rel="stylesheet" href="{{ asset('css/landing.css') }}">

  @stack('head')
</head>
<body>

  @include('partials.navbar')

  <main>
    @yield('content')
  </main>

  @include('partials.footer')

  @stack('scripts')
</body>
</html>
