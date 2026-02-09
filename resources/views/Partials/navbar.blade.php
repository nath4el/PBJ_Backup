<header class="nav">
  <div class="container nav-inner">

    <a href="{{ route('landing') }}" class="brand">
      <img class="brand-logo" src="{{ asset('image/Logo_Unsoed.png') }}" alt="Logo Universitas Jenderal Soedirman">
      <span class="brand-name">SIAPABAJA</span>
    </a>

    <nav class="nav-links">
      <a href="{{ route('landing') }}#regulasi" class="nav-link">Regulasi</a>

      <a href="{{ route('ArsipPBJ') }}"
         class="nav-link {{ request()->routeIs('ArsipPBJ') ? 'active' : '' }}">
        Arsip PBJ
      </a>

      <a href="{{ route('landing') }}#kontak" class="nav-link">Kontak</a>

      <a class="btn btn-white" href="{{ route('login') }}">Masuk</a>
    </nav>

  </div>
</header>