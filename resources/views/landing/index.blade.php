@extends('layouts.app')

@section('title', 'Vyora Fashion Store - Landing Page')

@section('content')

<!-- Hero Section / Slider -->
<section id="billboard" class="bg-light py-5">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <span class="text-uppercase text-muted letter-spacing-2 fw-semibold">Koleksi Terbaru 2026</span>
        <h1 class="display-3 font-secondary fw-bold my-3 text-dark">Fashion Elegan, Harga Bersahabat</h1>
        <p class="lead text-secondary mb-4">Temukan berbagai koleksi pakaian & aksesoris terbaik untuk gaya harian dan acara spesial Anda. Pilihan mudah dan pemesanan instan via WhatsApp.</p>
        <div class="d-flex gap-3">
          <a href="#catalog" class="btn btn-dark btn-lg rounded-pill px-4">Lihat Katalog</a>
          <a href="#categories" class="btn btn-outline-dark btn-lg rounded-pill px-4">Kategori</a>
        </div>
      </div>
      <div class="col-lg-6 text-center">
        <img src="{{ asset('images/banner-image-1.jpg') }}" alt="Vyora Fashion Hero" class="img-fluid rounded-4 shadow-sm" style="max-height: 450px; object-fit: cover;">
      </div>
    </div>
  </div>
</section>

<!-- Categories Section -->
<section id="categories" class="py-5">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h2 class="font-secondary fw-bold mb-1">Kategori Produk</h2>
        <p class="text-muted mb-0">Pilih kategori fashion sesuai kebutuhan Anda</p>
      </div>
      <a href="{{ route('home') }}#catalog" class="btn btn-outline-dark btn-sm rounded-pill">Semua Produk</a>
    </div>

    <div class="row g-4">
      @forelse($categories as $category)
        <div class="col-md-3 col-6">
          <a href="{{ route('home', ['category' => $category->slug]) }}#catalog" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden text-center category-card">
              @if($category->image)
                <img src="{{ $category->image_url }}" class="card-img-top" alt="{{ $category->name }}" style="height: 180px; object-fit: cover;">
              @else
                <div class="bg-light py-5 text-muted">
                  <i class="fa-solid fa-layer-group fs-1"></i>
                </div>
              @endif
              <div class="card-body py-3">
                <h6 class="fw-bold text-dark mb-1">{{ $category->name }}</h6>
                <span class="badge bg-light text-dark border">{{ $category->products_count }} Produk</span>
              </div>
            </div>
          </a>
        </div>
      @empty
        <div class="col-12 text-center text-muted py-4">
          Belum ada kategori yang ditambahkan.
        </div>
      @endforelse
    </div>
  </div>
</section>

<!-- Catalog Section -->
<section id="catalog" class="py-5 bg-light">
  <div class="container">
    
    <!-- Title & Search Bar -->
    <div class="row align-items-center mb-4 g-3">
      <div class="col-md-6">
        <h2 class="font-secondary fw-bold mb-1">Katalog Produk</h2>
        <p class="text-muted mb-0">Temukan produk impian Anda sekarang</p>
      </div>
      <div class="col-md-6">
        <form action="{{ route('home') }}#catalog" method="GET" class="d-flex gap-2">
          @if(request('category'))
            <input type="hidden" name="category" value="{{ request('category') }}">
          @endif
          <input type="text" name="search" class="form-control rounded-pill px-3" placeholder="Cari nama produk..." value="{{ request('search') }}">
          <button type="submit" class="btn btn-dark rounded-pill px-4">Cari</button>
          @if(request('search') || request('category'))
            <a href="{{ route('home') }}#catalog" class="btn btn-outline-secondary rounded-pill px-3" title="Reset Filter">Reset</a>
          @endif
        </form>
      </div>
    </div>

    <!-- Active Filter Badge -->
    @if(request('category'))
      <div class="mb-4 p-3 bg-white rounded-3 shadow-sm d-flex align-items-center justify-content-between">
        <div>
          <span class="text-muted me-2">Menampilkan Kategori:</span>
          <span class="badge bg-dark fs-6 px-3 py-2 rounded-pill">
            {{ $activeCategory ? $activeCategory->name : request('category') }}
          </span>
          <span class="text-muted ms-2 fs-7">({{ $products->total() }} Produk ditemukan)</span>
        </div>
        <a href="{{ route('home', ['search' => request('search')]) }}#catalog" class="btn btn-outline-danger btn-sm rounded-pill">
          <i class="fa-solid fa-xmark me-1"></i> Hapus Filter Kategori
        </a>
      </div>
    @endif

    <!-- Product Grid -->
    <div class="row g-4">
      @forelse($products as $product)
        <div class="col-lg-3 col-md-4 col-6">
          <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden position-relative product-card">
            
            @if($product->discount_price && $product->discount_price > 0)
              <span class="badge bg-danger position-absolute top-0 start-0 m-3 fs-7 px-2 py-1">PROMO</span>
            @endif

            <a href="{{ route('products.show', $product->slug) }}">
              <img src="{{ $product->image_url }}" class="card-img-top" alt="{{ $product->name }}" style="height: 240px; object-fit: cover;">
            </a>

            <div class="card-body d-flex flex-column">
              <span class="text-muted fs-7 text-uppercase fw-semibold mb-1">{{ $product->category->name ?? 'Uncategorized' }}</span>
              <h6 class="fw-bold text-dark card-title mb-2">
                <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none text-dark hover-title">
                  {{ $product->name }}
                </a>
              </h6>

              <div class="mt-auto pt-2">
                @if($product->discount_price && $product->discount_price > 0)
                  <div class="d-flex align-items-center gap-2">
                    <span class="fw-bold text-danger fs-5">{{ $product->formatted_discount_price }}</span>
                    <span class="text-muted text-decoration-line-through fs-7">{{ $product->formatted_price }}</span>
                  </div>
                @else
                  <span class="fw-bold text-dark fs-5">{{ $product->formatted_price }}</span>
                @endif
              </div>

              <div class="d-flex gap-2 mt-3">
                <a href="{{ route('products.show', $product->slug) }}" class="btn btn-outline-dark btn-sm rounded-pill flex-grow-1">Detail</a>
                @auth
                  <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn btn-dark btn-sm rounded-circle px-2" title="Tambah ke Keranjang">
                      <i class="fa-solid fa-cart-plus"></i>
                    </button>
                  </form>
                @else
                  <a href="{{ route('login') }}" class="btn btn-dark btn-sm rounded-circle px-2" title="Login untuk Beli">
                    <i class="fa-solid fa-cart-plus"></i>
                  </a>
                @endauth
              </div>

            </div>
          </div>
        </div>
      @empty
        <div class="col-12 text-center py-5">
          <div class="text-muted fs-4 mb-2"><i class="fa-solid fa-box-open fs-1"></i></div>
          <h5 class="fw-bold text-muted">Produk Tidak Ditemukan</h5>
          <p class="text-muted">Coba kata kunci lain atau pilih kategori yang berbeda.</p>
        </div>
      @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-5">
      {{ $products->links() }}
    </div>

  </div>
</section>

@endsection
