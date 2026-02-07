<header class="nav nav-home">
  <div class="container nav-inner">

    <a href="{{ route('home') }}" class="brand">
      <img class="brand-logo" src="{{ asset('image/Logo_Unsoed.png') }}" alt="Logo Unsoed">
      <span class="brand-name">SIAPABAJA</span>
    </a>

    <nav class="nav-links">
      <a href="{{ route('home') }}"
         class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
        Dasbor
      </a>

      <a href="{{ route('home') }}#regulasi" class="nav-link">Regulasi</a>

      <a href="{{ route('home.pbj') }}"
         class="nav-link {{ request()->routeIs('home.pbj') ? 'active' : '' }}">
        Arsip PBJ
      </a>

      <a href="{{ route('home') }}#kontak" class="nav-link">Kontak</a>

      <div class="nav-user" id="homeUserMenu">
        <button type="button" class="nav-user-btn" id="homeUserBtn" aria-label="User menu">
          <i class="bi bi-person-circle"></i>
        </button>

        <div class="nav-user-menu" id="homeUserDropdown">
          <form action="{{ url('/logout') }}" method="POST">
            @csrf
            <button type="submit" class="nav-logout">
              <i class="bi bi-box-arrow-right"></i> Keluar
            </button>
          </form>
        </div>
      </div>
    </nav>

  </div>
</header>
