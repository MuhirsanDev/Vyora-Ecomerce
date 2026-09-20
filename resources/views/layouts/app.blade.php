<!DOCTYPE html>
<html lang="id">

<head>
  <title>@yield('title', 'VYORA')</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="VYORA - Koleksi tas & dompet wanita elegan dengan pemesanan cepat via WhatsApp.">
  
  <!-- Open Graph / WhatsApp Social Link Preview Meta Tags -->
  <meta property="og:type" content="website">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:title" content="@yield('title', 'VYORA')">
  <meta property="og:description" content="@yield('meta_description', 'VYORA - Koleksi tas & dompet wanita elegan dengan pemesanan cepat via WhatsApp.')">
  <meta property="og:site_name" content="VYORA">
  <meta property="og:image" content="@yield('og_image', asset('storage/products/34.000 - 1.jpeg'))">
  <meta property="og:image:type" content="image/jpeg">

  <!-- Twitter Meta Tags -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="@yield('title', 'VYORA')">
  <meta name="twitter:description" content="@yield('meta_description', 'VYORA - Koleksi tas & dompet wanita elegan dengan pemesanan cepat via WhatsApp.')">
  <meta name="twitter:image" content="@yield('og_image', asset('storage/products/34.000 - 1.jpeg'))">
  
  @php
    $faviconUrl = \App\Models\Setting::getFaviconUrl();
  @endphp
  <!-- Dynamic Favicon linked to Store Logo -->
  <link rel="icon" href="{{ $faviconUrl }}">
  <link rel="shortcut icon" href="{{ $faviconUrl }}">
  <link rel="apple-touch-icon" href="{{ $faviconUrl }}">
  
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- Template CSS -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/vendor.css') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
  <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500;600;700&family=Marcellus&display=swap" rel="stylesheet">

  <style>
    .badge-cart {
      position: absolute;
      top: -5px;
      right: -8px;
      font-size: 0.7rem;
      padding: 0.25em 0.5em;
    }
    .whatsapp-float-btn {
      position: fixed;
      bottom: 25px;
      right: 25px;
      background-color: #25d366;
      color: white;
      border-radius: 50px;
      padding: 12px 20px;
      font-weight: 600;
      box-shadow: 0 4px 15px rgba(0,0,0,0.2);
      z-index: 999;
      display: flex;
      align-items: center;
      gap: 10px;
      text-decoration: none;
      transition: all 0.3s ease;
    }
    .whatsapp-float-btn:hover {
      background-color: #128c7e;
      color: white;
      transform: translateY(-3px);
    }
    /* Force crisp black text for all form inputs */
    .form-control, input, select, textarea {
      color: #000000 !important;
      font-weight: 500 !important;
    }
    .form-control::placeholder, input::placeholder, textarea::placeholder {
      color: #71717a !important;
      font-weight: 400 !important;
    }
  </style>
</head>

<body class="homepage">

  <!-- Header Navigation -->
  <nav class="navbar navbar-expand-lg bg-white border-bottom py-3 sticky-top">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
        <img src="{{ \App\Models\Setting::getLogoUrl() }}" alt="Vyora Store" style="max-height: 42px; width: auto;" class="object-fit-contain">
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarContent">
        <ul class="navbar-nav mx-auto mb-2 mb-lg-0 fw-medium text-uppercase fs-6">
          <li class="nav-item me-3">
            <a class="nav-link text-dark {{ request()->routeIs('home') ? 'active fw-bold' : '' }}" href="{{ route('home') }}">Beranda</a>
          </li>
          <li class="nav-item me-3">
            <a class="nav-link text-dark" href="{{ route('home') }}#catalog">Katalog Produk</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-dark" href="{{ route('home') }}#categories">Kategori</a>
          </li>
        </ul>

        <div class="d-flex align-items-center gap-3">
          @auth
            <!-- Cart Link -->
            <a href="{{ route('cart.index') }}" class="btn btn-outline-dark position-relative rounded-pill px-3 me-2">
              <i class="fa-solid fa-cart-shopping me-1"></i> Keranjang
              @php
                $cartCount = \App\Models\CartItem::where('user_id', auth()->id())->sum('quantity');
              @endphp
              @if($cartCount > 0)
                <span class="badge rounded-pill bg-danger badge-cart">{{ $cartCount }}</span>
              @endif
            </a>

            @if(auth()->user()->isAdmin())
              <a href="{{ route('admin.dashboard') }}" class="btn btn-dark rounded-pill px-3">
                <i class="fa-solid fa-user-gear me-1"></i> Admin Panel
              </a>
            @endif

            <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary rounded-pill px-3" title="Edit Profil Saya">
              <i class="fa-solid fa-user me-1"></i> {{ auth()->user()->name }}
            </a>

            <form action="{{ route('logout') }}" method="POST" class="d-inline">
              @csrf
              <button type="submit" class="btn btn-link text-danger text-decoration-none p-0 ms-2" title="Keluar">
                <i class="fa-solid fa-right-from-bracket fs-5"></i>
              </button>
            </form>

          @else
            <a href="{{ route('login') }}" class="btn btn-outline-dark rounded-pill px-3">Masuk</a>
            <a href="{{ route('register') }}" class="btn btn-dark rounded-pill px-3">Daftar</a>
          @endauth
        </div>
      </div>
    </div>
  </nav>

  <!-- Flash Messages -->
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show container mt-3 mb-0" role="alert">
      <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show container mt-3 mb-0" role="alert">
      <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <!-- Main Content -->
  <main>
    @yield('content')
  </main>

  <!-- Floating WhatsApp Button -->
  @php
    $waNum = \App\Models\Setting::get('whatsapp_number', '6281994578184');
    $storeAddress = \App\Models\Setting::get('store_address', 'Jl. Raya Rangkasbitung No. 8, Kareo, Serang, Kabupaten Serang, Banten 42177');
  @endphp
  <a href="https://wa.me/{{ $waNum }}?text={{ urlencode('Halo Admin Vyora, saya ingin bertanya tentang produk Anda.') }}" 
     target="_blank" 
     class="whatsapp-float-btn">
    <i class="fa-brands fa-whatsapp fs-4"></i>
    <span>Chat Admin</span>
  </a>

  <!-- Footer -->
  <footer class="bg-light text-dark py-5 mt-5 border-top">
    <div class="container">
      <div class="row g-4">
        <div class="col-md-4">
          <h4 class="font-secondary text-uppercase fw-bold">VYORA</h4>
          <p class="text-muted">Toko fashion masa kini dengan koleksi terbaik, kualitas bahan nomor satu, dan transaksi langsung via WhatsApp resmi kami.</p>
        </div>
        <div class="col-md-4 ms-auto">
          <h5 class="fw-bold mb-3">Tautan Cepat</h5>
          <ul class="list-unstyled text-muted">
            <li class="mb-2"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Beranda</a></li>
            <li class="mb-2"><a href="{{ route('home') }}#catalog" class="text-decoration-none text-muted">Katalog Produk</a></li>
            <li class="mb-2"><a href="{{ route('cart.index') }}" class="text-decoration-none text-muted">Keranjang Belanja</a></li>
          </ul>
        </div>
        <div class="col-md-4">
          <h5 class="fw-bold mb-3">Hubungi Kami</h5>
          <p class="text-muted mb-1"><i class="fa-brands fa-whatsapp me-2 text-success"></i> WhatsApp: +{{ $waNum }}</p>
          <p class="text-muted"><i class="fa-solid fa-location-dot me-2"></i> {{ $storeAddress }}</p>
        </div>
      </div>
      <div class="border-top mt-4 pt-4 text-center text-muted fs-6">
        <p class="mb-0">&copy; {{ date('Y') }} Vyora. All rights reserved.</p>
      </div>
    </div>
  </footer>

  <!-- JS Scripts -->
  <script src="{{ asset('js/jquery.min.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
  <script src="{{ asset('js/plugins.js') }}"></script>
  <script src="{{ asset('js/script.min.js') }}"></script>
  @stack('scripts')
</body>

</html>
