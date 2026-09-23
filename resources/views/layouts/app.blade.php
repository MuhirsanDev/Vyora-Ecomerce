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
    /* Product Card Hover Zoom & Elevation */
    .product-card {
      transition: transform 0.3s cubic-bezier(0.165, 0.84, 0.44, 1), box-shadow 0.3s ease !important;
    }
    .product-card:hover {
      transform: translateY(-6px) !important;
      box-shadow: 0 14px 28px rgba(0, 0, 0, 0.12) !important;
    }
    .product-card .card-img-top {
      transition: transform 0.5s ease;
    }
    .product-card:hover .card-img-top {
      transform: scale(1.05);
    }
    /* Visual Color Swatches */
    .color-swatch-dot {
      width: 16px;
      height: 16px;
      border-radius: 50%;
      display: inline-block;
      border: 1px solid rgba(0,0,0,0.2);
      vertical-align: middle;
      margin-right: 4px;
    }
    .btn-check:checked + label.color-swatch-btn {
      border-color: #000000 !important;
      background-color: #000000 !important;
      color: #ffffff !important;
      box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }
    .fs-8 {
      font-size: 0.75rem;
    }
    /* Sticky Mobile Action Bar */
    .sticky-mobile-bar {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      z-index: 1040;
      background: rgba(255, 255, 255, 0.96);
      backdrop-filter: blur(10px);
      box-shadow: 0 -4px 20px rgba(0,0,0,0.12);
    }
    @media (min-width: 768px) {
      .sticky-mobile-bar {
        display: none !important;
      }
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
          <!-- Cart Link / Drawer Trigger (Accessible to Everyone) -->
          <button type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart" class="btn btn-outline-dark position-relative rounded-pill px-3 me-1 cursor-pointer">
            <i class="fa-solid fa-cart-shopping me-1"></i> Keranjang
            @php
              $cartCount = auth()->check()
                ? \App\Models\CartItem::where('user_id', auth()->id())->sum('quantity')
                : \App\Models\CartItem::where('session_id', session()->getId())->sum('quantity');
            @endphp
            @if($cartCount > 0)
              <span class="badge rounded-pill bg-danger badge-cart">{{ $cartCount }}</span>
            @endif
          </button>

          @auth
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
              <button type="submit" class="btn btn-link text-danger text-decoration-none p-0 ms-1" title="Keluar">
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

  <!-- Flash Messages & Interactive Cart Modal -->
  @if(session('success'))
    @if(str_contains(session('success'), 'keranjang'))
      <div class="modal fade" id="addedToCartModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content rounded-4 border-0 shadow-lg p-3">
            <div class="modal-header border-0 pb-0">
              <h5 class="modal-title font-secondary fw-bold text-success d-flex align-items-center gap-2">
                <i class="fa-solid fa-circle-check fs-3"></i>
                <span>Berhasil Masuk Keranjang!</span>
              </h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3">
              <p class="text-dark fs-6 mb-0">{{ session('success') }}</p>
            </div>
            <div class="modal-footer border-0 pt-0 d-flex gap-2">
              <button type="button" class="btn btn-outline-secondary rounded-pill px-4 flex-grow-1" data-bs-dismiss="modal">Lanjut Belanja</button>
              <a href="{{ route('cart.index') }}" class="btn btn-success rounded-pill px-4 flex-grow-1 fw-bold">
                <i class="fa-solid fa-cart-shopping me-1"></i> Lihat Keranjang
              </a>
            </div>
          </div>
        </div>
      </div>
      @push('scripts')
      <script>
        document.addEventListener('DOMContentLoaded', function () {
          var cartModalEl = document.getElementById('addedToCartModal');
          if (cartModalEl) {
            var cartModal = new bootstrap.Modal(cartModalEl);
            cartModal.show();
          }
        });
      </script>
      @endpush
    @else
      <div class="alert alert-success alert-dismissible fade show container mt-3 mb-0" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif
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

  <!-- Offcanvas Slide-Over Cart Drawer -->
  @php
    $drawerCartItems = auth()->check()
      ? \App\Models\CartItem::with('product')->where('user_id', auth()->id())->get()
      : \App\Models\CartItem::with('product')->where('session_id', session()->getId())->get();
    $drawerTotalPrice = $drawerCartItems->sum(fn ($i) => $i->subtotal);
  @endphp
  <div class="offcanvas offcanvas-end rounded-start-4 border-0 shadow-lg" tabindex="-1" id="offcanvasCart" aria-labelledby="offcanvasCartLabel" style="width: 380px; max-width: 90vw;">
    <div class="offcanvas-header border-bottom py-3">
      <h5 class="offcanvas-title font-secondary fw-bold text-dark d-flex align-items-center gap-2 mb-0" id="offcanvasCartLabel">
        <i class="fa-solid fa-bag-shopping text-dark fs-4"></i>
        <span>Keranjang Belanja</span>
      </h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-3">
      @if($drawerCartItems->count() > 0)
        <div class="d-flex flex-column gap-3 mb-3">
          @foreach($drawerCartItems as $dItem)
            <div class="d-flex align-items-center gap-3 p-2 bg-light rounded-3 border">
              <img src="{{ $dItem->product->image_url }}" alt="{{ $dItem->product->name }}" class="rounded-3" style="width: 55px; height: 55px; object-fit: cover;">
              <div class="flex-grow-1 min-w-0">
                <h6 class="fw-bold text-dark mb-1 text-truncate fs-7">{{ $dItem->product->name }}</h6>
                <div class="d-flex flex-wrap gap-1 align-items-center mb-1">
                  @if($dItem->color)
                    <span class="badge bg-white text-dark border fs-8 px-1.5 py-0.5">{{ $dItem->color }}</span>
                  @endif
                  @if($dItem->size)
                    <span class="badge bg-white text-dark border fs-8 px-1.5 py-0.5">{{ $dItem->size }}</span>
                  @endif
                </div>
                <div class="d-flex justify-content-between align-items-center">
                  <span class="text-muted fs-7">{{ $dItem->quantity }}x</span>
                  <span class="fw-bold text-dark fs-7">Rp {{ number_format($dItem->subtotal, 0, ',', '.') }}</span>
                </div>
              </div>
              <form action="{{ route('cart.destroy', $dItem->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-link text-danger p-0 me-1" title="Hapus">
                  <i class="fa-solid fa-xmark fs-5"></i>
                </button>
              </form>
            </div>
          @endforeach
        </div>
      @else
        <div class="text-center py-5 text-muted">
          <i class="fa-solid fa-cart-shopping fs-1 mb-3 text-secondary opacity-50"></i>
          <p class="mb-0 font-medium">Keranjang belanja Anda masih kosong.</p>
        </div>
      @endif
    </div>
    @if($drawerCartItems->count() > 0)
      <div class="offcanvas-footer border-top p-3 bg-white">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <span class="text-muted font-medium">Total Pembayaran:</span>
          <span class="fw-bold fs-5 text-success">Rp {{ number_format($drawerTotalPrice, 0, ',', '.') }}</span>
        </div>
        <div class="d-flex flex-column gap-2">
          <a href="{{ route('cart.whatsapp') }}" target="_blank" class="btn btn-success rounded-pill fw-bold w-100 py-2.5 d-flex align-items-center justify-content-center gap-2">
            <i class="fa-brands fa-whatsapp fs-5"></i>
            <span>Order via WhatsApp</span>
          </a>
          <a href="{{ route('cart.index') }}" class="btn btn-outline-dark rounded-pill w-100 py-2 fs-7 font-semibold">
            Lihat Detail Keranjang
          </a>
        </div>
      </div>
    @endif
  </div>

  <!-- JS Scripts -->
  <script src="{{ asset('js/jquery.min.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
  <script src="{{ asset('js/plugins.js') }}"></script>
  <script src="{{ asset('js/script.min.js') }}"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      document.addEventListener('wheel', function(e) {
        if (document.activeElement && document.activeElement.type === 'number') {
          document.activeElement.blur();
        }
      }, { passive: true });
    });
  </script>
  @stack('scripts')
</body>

</html>
