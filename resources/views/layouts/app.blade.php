<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

  <link rel="stylesheet" href="{{ asset('css/landing.css') }}">

  @stack('head')
</head>

<body class="has-nav">

  @include('Partials.navbar')

  <main>
    @yield('content')
  </main>

  @include('Partials.footer')

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

 {{-- ✅ NAV ACTIVE + SMOOTH (LANDING) --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
  const links = Array.from(document.querySelectorAll('.nav-links a.nav-link'));
  if (!links.length) return;

  // ✅ target section yang dipakai di navbar
  const targets = Array.from(document.querySelectorAll('[id]'))
    .filter(el => ['regulasi','kontak','statistika','arsip'].includes(el.id));

  const setActiveById = (id) => {
    links.forEach(a => a.classList.remove('active'));
    const match = links.find(a => (a.getAttribute('href') || '').includes('#' + id));
    if (match) match.classList.add('active');
  };

  const getCurrent = () => {
    const offset = 140; // tinggi navbar kamu
    let current = null;

    targets.forEach(el => {
      const top = el.getBoundingClientRect().top;
      if (top - offset <= 0) current = el.id;
    });

    return current;
  };

  const onScroll = () => {
    // ✅ kalau mentok bawah, paksa kontak aktif
    const nearBottom =
      window.innerHeight + window.scrollY >= document.body.scrollHeight - 20;

    if (nearBottom) {
      setActiveById('kontak');
      return;
    }

    const current = getCurrent();
    if (current) setActiveById(current);
  };

  // klik -> langsung aktif
  links.forEach(a => {
    a.addEventListener('click', () => {
      const href = a.getAttribute('href') || '';
      const hashIndex = href.indexOf('#');
      if (hashIndex !== -1) {
        const id = href.substring(hashIndex + 1);
        if (id) setActiveById(id);
      }
    });
  });

  window.addEventListener('scroll', onScroll, { passive: true });
  window.addEventListener('hashchange', onScroll);

  onScroll();
});
</script>

  @stack('scripts')
</body>
</html>