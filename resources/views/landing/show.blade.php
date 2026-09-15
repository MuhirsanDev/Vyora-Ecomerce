@extends('layouts.app')

@section('title', $product->name . ' - Vyora Store')

@section('content')

<div class="container py-5">
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Beranda</a></li>
      <li class="breadcrumb-item"><a href="{{ route('home') }}#catalog" class="text-decoration-none text-muted">Katalog</a></li>
      <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
    </ol>
  </nav>

  <div class="row g-5 align-items-center mb-5">
    <!-- Image -->
    <div class="col-md-6 text-center">
      <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="img-fluid" style="max-height: 500px; object-fit: cover; width: 100%;">
      </div>
    </div>

    <!-- Details -->
    <div class="col-md-6">
      <span class="badge bg-secondary text-uppercase mb-2">{{ $product->category->name ?? 'Umum' }}</span>
      <h1 class="font-secondary fw-bold text-dark display-5 mb-3">{{ $product->name }}</h1>

      <div class="mb-3">
        @if($product->discount_price && $product->discount_price > 0)
          <div class="d-flex align-items-center gap-3">
            <span class="fs-2 fw-bold text-danger">{{ $product->formatted_discount_price }}</span>
            <span class="fs-4 text-muted text-decoration-line-through">{{ $product->formatted_price }}</span>
            <span class="badge bg-danger fs-6">HEMAT {{ number_format((($product->price - $product->discount_price) / $product->price) * 100, 0) }}%</span>
          </div>
        @else
          <span class="fs-2 fw-bold text-dark">{{ $product->formatted_price }}</span>
        @endif
      </div>

      <div class="mb-4">
        <span class="fw-semibold text-dark me-2">Stok Tersedia:</span>
        @if($product->stock > 0)
          <span class="badge bg-success">{{ $product->stock }} Pcs</span>
        @else
          <span class="badge bg-danger">Stok Habis</span>
        @endif
      </div>

      <!-- Add to Cart Form / Direct WA -->
      <div class="d-flex flex-column gap-3">
        @auth
          <form action="{{ route('cart.add') }}" method="POST" class="d-flex gap-3 align-items-center">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <div style="width: 100px;">
              <input type="number" name="quantity" class="form-control form-control-lg text-center rounded-pill" value="1" min="1" max="{{ $product->stock }}">
            </div>
            <button type="submit" class="btn btn-dark btn-lg rounded-pill px-4 flex-grow-1" {{ $product->stock <= 0 ? 'disabled' : '' }}>
              <i class="fa-solid fa-cart-plus me-2"></i> Tambah ke Keranjang
            </button>
          </form>
        @else
          <div class="alert alert-info py-2 px-3 mb-0 fs-7">
            <i class="fa-solid fa-circle-info me-1"></i> Silakan <a href="{{ route('login') }}" class="fw-bold text-dark">Login</a> untuk memasukkan ke keranjang belanja.
          </div>
        @endauth

        <a href="{{ $directWaUrl }}" target="_blank" class="btn btn-success btn-lg rounded-pill px-4 text-white fw-bold d-flex align-items-center justify-content-center gap-2">
          <i class="fa-brands fa-whatsapp fs-4"></i>
          <span>Tanya / Order Langsung via WhatsApp</span>
        </a>
      </div>

    </div>
  </div>

  <!-- Dedicated Product Description Section -->
  <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-5 bg-white">
    <div class="border-bottom pb-3 mb-4">
      <h4 class="font-secondary fw-bold text-dark mb-0">
        <i class="fa-solid fa-align-left me-2 text-indigo-600"></i> Deskripsi Produk
      </h4>
    </div>
    <div class="text-secondary fs-6 lh-lg">
      @if($product->description)
        {!! nl2br(e($product->description)) !!}
      @else
        <p class="text-muted italic mb-0">Belum ada rincian deskripsi tambahan untuk produk ini.</p>
      @endif
    </div>
  </div>

  <!-- Related Products Section -->
  @if($relatedProducts->count() > 0)
    <div class="pt-4 border-top">
      <h3 class="font-secondary fw-bold mb-4">Produk Serupa</h3>
      <div class="row g-4">
        @foreach($relatedProducts as $rel)
          <div class="col-md-3 col-6">
            <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
              <a href="{{ route('products.show', $rel->slug) }}">
                <img src="{{ $rel->image_url }}" class="card-img-top" alt="{{ $rel->name }}" style="height: 180px; object-fit: cover;">
              </a>
              <div class="card-body">
                <h6 class="fw-bold mb-1">
                  <a href="{{ route('products.show', $rel->slug) }}" class="text-dark text-decoration-none">{{ $rel->name }}</a>
                </h6>
                <span class="fw-bold text-dark fs-6">{{ $rel->formatted_price }}</span>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  @endif

</div>

@endsection
