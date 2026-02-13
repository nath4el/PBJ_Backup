{{-- resources/views/Viewer/FileViewer.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Preview Dokumen</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <style>
    html, body{
      margin:0;
      padding:0;
      height:100%;
      width:100%;
      background:#111827;
      overflow:hidden;
      font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
    }

    /* ✅ FULLSCREEN VIEWER (tanpa topbar custom) */
    .viewer{
      position:fixed;
      inset:0;
      background:#111827;
    }

    iframe{
      position:absolute;
      inset:0;
      width:100%;
      height:100%;
      border:none;
      background:#111827;
      display:block;
    }

    .imgwrap{
      position:absolute;
      inset:0;
      overflow:auto;
      background:#111827;
      display:flex;
      align-items:center;
      justify-content:center;
    }
    .imgwrap img{
      max-width:100%;
      max-height:100%;
      height:auto;
      width:auto;
      display:block;
    }

    .msg{
      position:absolute;
      inset:0;
      display:flex;
      align-items:center;
      justify-content:center;
      padding:18px;
      box-sizing:border-box;
      text-align:center;
      color:#fff;
      background:#111827;
    }
    .msgcard{
      max-width:520px;
      width:100%;
      background:#1f2937;
      border:1px solid rgba(255,255,255,.12);
      border-radius:16px;
      padding:18px;
      box-sizing:border-box;
    }
    .msgcard h3{
      margin:0 0 8px 0;
      font-size:16px;
    }
    .msgcard p{
      margin:0;
      font-size:14px;
      opacity:.85;
      line-height:1.5;
    }
  </style>
</head>

<body class="{{ request('mode') === 'public' ? 'is-public' : '' }}">

@php
  $raw = request()->query('file', $file ?? '');
  $path = is_string($raw) ? urldecode($raw) : '';

  // keamanan minimal: hanya izinkan akses yang mulai dari /storage/
  if(!$path || !\Illuminate\Support\Str::startsWith($path, '/storage/')){
    $path = '';
  }

  $filename = $path ? basename(parse_url($path, PHP_URL_PATH) ?: $path) : 'Dokumen';
  $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

  $isPublic = request('mode') === 'public';
  $isPdf = $ext === 'pdf';
  $isImg = in_array($ext, ['png','jpg','jpeg','webp','gif'], true);

  // PDF: public -> hide toolbar
  $src = $path;
  if($isPdf && $path){
    $src = $isPublic
      ? ($path.'#toolbar=0&navpanes=0&scrollbar=0')
      : $path;
  }
@endphp

<div class="viewer">
  @if(!$path)
    <div class="msg">
      <div class="msgcard">
        <h3>File tidak valid</h3>
        <p>Link file tidak ditemukan atau format link tidak sesuai.</p>
      </div>
    </div>

  @elseif($isPdf)
    <iframe
      src="{{ $src }}"
      referrerpolicy="no-referrer"
      loading="lazy"
    ></iframe>

  @elseif($isImg)
    <div class="imgwrap">
      <img src="{{ $path }}" alt="{{ $filename }}">
    </div>

  @else
    <div class="msg">
      <div class="msgcard">
        <h3>Dokumen tidak bisa dipreview</h3>
        <p>
          File <b>{{ $filename }}</b> tidak mendukung preview langsung di browser.
          <br>Gunakan format <b>PDF</b> atau <b>gambar (JPG/PNG)</b>.
        </p>
      </div>
    </div>
  @endif
</div>

@if($isPublic)
<script>
  // (opsional) UX: block right click di public
  document.addEventListener('contextmenu', function(e){ e.preventDefault(); }, { passive:false });
</script>
@endif

</body>
</html>
