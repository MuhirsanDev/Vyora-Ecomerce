@extends('layouts.app')

@section('title', $product->name . ' - VYORA')
@section('meta_description', $product->formatted_price . ' - ' . \Illuminate\Support\Str::limit(strip_tags($product->description), 120))
@section('og_image', $product->image_url)

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
    <!-- Image & Photo Gallery -->
    <div class="col-md-6 text-center">
      <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-3">
        <img id="mainProductImage" src="{{ $product->image_url }}" alt="{{ $product->name }}" class="img-fluid" style="max-height: 500px; object-fit: cover; width: 100%; transition: opacity 0.2s ease;">
      </div>

      @php
        $allImages = array_merge([$product->image_url], $product->additional_images_urls);
      @endphp

      @if(count($allImages) > 1)
        <div class="d-flex justify-content-center gap-2 overflow-auto py-1">
          @foreach($allImages as $idx => $imgUrl)
            <button type="button" class="btn p-0 border rounded-3 overflow-hidden thumbnail-btn {{ $idx === 0 ? 'border-dark border-2' : 'opacity-75' }}" onclick="changeMainImage('{{ $imgUrl }}', this)">
              <img src="{{ $imgUrl }}" style="width: 70px; height: 70px; object-fit: cover;">
            </button>
          @endforeach
        </div>
      @endif
    </div>

    <!-- Details -->
    <div class="col-md-6">
      <span class="badge bg-secondary text-uppercase mb-2">{{ $product->category->name ?? 'Umum' }}</span>
      <h1 class="font-secondary fw-bold text-dark display-5 mb-3">{{ $product->name }}</h1>

      <div class="mb-3">
        @if($product->has_active_promo)
          <div class="d-flex align-items-center gap-3">
            <span class="fs-2 fw-bold text-danger">{{ $product->formatted_discount_price }}</span>
            <span class="fs-4 text-muted text-decoration-line-through">{{ $product->formatted_price }}</span>
            <span class="badge bg-danger fs-6">HEMAT {{ number_format((($product->price - $product->discount_price) / $product->price) * 100, 0) }}%</span>
          </div>
          @if($product->promo_ends_at)
            <div class="text-danger small mt-2 fw-semibold">
              <i class="fa-regular fa-clock me-1"></i> Promo Spesial Berakhir Pada: {{ $product->promo_ends_at->format('d M Y, H:i') }} WIB
            </div>
          @endif
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

      @if(count($product->colors_list) > 0)
        @php
          $colorMap = [
            'Hitam'     => ['color' => '#000000', 'ring' => 'rgba(0, 0, 0, 0.25)'],
            'Coffee'    => ['color' => '#4a2c11', 'ring' => 'rgba(74, 44, 17, 0.25)'],
            'Cokelat'   => ['color' => '#795548', 'ring' => 'rgba(121, 85, 72, 0.25)'],
            'Cream'     => ['color' => '#e6d7b8', 'ring' => 'rgba(230, 215, 184, 0.4)'],
            'Hijau'     => ['color' => '#2e7d32', 'ring' => 'rgba(46, 125, 50, 0.25)'],
            'Merah'     => ['color' => '#d32f2f', 'ring' => 'rgba(211, 47, 47, 0.25)'],
            'Navy'      => ['color' => '#0d47a1', 'ring' => 'rgba(13, 71, 161, 0.25)'],
            'Putih'     => ['color' => '#ffffff', 'ring' => 'rgba(156, 163, 175, 0.4)'],
            'Pink'      => ['color' => '#e91e63', 'ring' => 'rgba(233, 30, 99, 0.25)'],
            'Lilac'     => ['color' => '#c8a2c8', 'ring' => 'rgba(200, 162, 200, 0.4)'],
            'Abu-abu'   => ['color' => '#808080', 'ring' => 'rgba(128, 128, 128, 0.25)'],
            'Maroon'    => ['color' => '#800000', 'ring' => 'rgba(128, 0, 0, 0.25)'],
            'Rose Gold' => ['color' => '#b76e79', 'ring' => 'rgba(183, 110, 121, 0.25)'],
            'Sage'      => ['color' => '#9caf88', 'ring' => 'rgba(156, 175, 136, 0.4)'],
            'Mocca'     => ['color' => '#9e7b66', 'ring' => 'rgba(158, 123, 102, 0.25)'],
            'Moka'      => ['color' => '#9e7b66', 'ring' => 'rgba(158, 123, 102, 0.25)'],
            'Beige'     => ['color' => '#e6d7b8', 'ring' => 'rgba(230, 215, 184, 0.4)'],
            'Biru'      => ['color' => '#1d4ed8', 'ring' => 'rgba(29, 78, 216, 0.25)'],
            'Kuning'    => ['color' => '#eab308', 'ring' => 'rgba(234, 179, 8, 0.3)'],
          ];
        @endphp
        <style>
          .color-swatch-pill {
            background-color: #ffffff !important;
            color: #374151 !important;
            border: 1.5px solid #d1d5db !important;
            transition: all 0.2s ease-in-out;
            cursor: pointer;
          }
          .color-swatch-pill:hover {
            border-color: var(--swatch-color, #4b5563) !important;
            transform: translateY(-1px);
          }
          .color-swatch-dot {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
            background-color: var(--swatch-color, #71717a);
            border: 1.5px solid rgba(0,0,0,0.15);
            box-shadow: inset 0 1px 2px rgba(255,255,255,0.3);
            transition: transform 0.2s ease;
          }
          .color-radio:checked + .color-swatch-pill {
            background-color: #ffffff !important;
            color: #111827 !important;
            border: 2.5px solid var(--swatch-color, #111827) !important;
            box-shadow: 0 0 0 3px var(--swatch-ring, rgba(0,0,0,0.1)), 0 2px 6px rgba(0,0,0,0.08) !important;
            font-weight: 700 !important;
          }
          .color-radio:checked + .color-swatch-pill .color-swatch-dot {
            transform: scale(1.2);
            border-color: rgba(0,0,0,0.3) !important;
          }
        </style>
        <div class="mb-3 p-3 bg-light rounded-4 border">
          <label class="fw-semibold text-dark mb-2 d-block">
            <i class="fa-solid fa-palette me-1 text-primary"></i> Pilihan Warna Tersedia:
          </label>
          <div class="d-flex flex-wrap gap-2">
            @foreach($product->colors_list as $index => $colorOption)
              @php
                $c = null;
                $normalizedKey = strtolower(trim($colorOption));
                foreach ($colorMap as $k => $v) {
                  if (strtolower($k) === $normalizedKey) {
                    $c = $v;
                    break;
                  }
                }
                if (!$c) {
                  $c = ['color' => '#71717a', 'ring' => 'rgba(113, 113, 122, 0.25)'];
                }
              @endphp
              <input type="radio" class="btn-check color-radio" name="color" id="color_{{ $index }}" value="{{ $colorOption }}" {{ $index === 0 ? 'checked' : '' }} form="addToCartForm" onchange="updateWaUrl()">
              <label class="btn rounded-pill px-3 py-1.5 fs-7 fw-semibold color-swatch-pill d-inline-flex align-items-center" 
                     for="color_{{ $index }}"
                     style="--swatch-color: {{ $c['color'] }}; --swatch-ring: {{ $c['ring'] }};">
                <span class="color-swatch-dot"></span>
                <span>{{ $colorOption }}</span>
              </label>
            @endforeach
          </div>
        </div>
      @endif

      @if(count($product->sizes_list) > 0)
        <div class="mb-4 p-3 bg-light rounded-4 border">
          <label class="fw-semibold text-dark mb-2 d-block">
            <i class="fa-solid fa-ruler-horizontal me-1 text-primary"></i> Pilihan Ukuran (Size) Tersedia:
          </label>
          <div class="d-flex flex-wrap gap-2">
            @foreach($product->sizes_list as $index => $sizeOption)
              <input type="radio" class="btn-check size-radio" name="size" id="size_{{ $index }}" value="{{ $sizeOption }}" {{ $index === 0 ? 'checked' : '' }} form="addToCartForm" onchange="updateWaUrl()">
              <label class="btn btn-outline-dark rounded-pill px-3 py-1.5 fs-7 fw-semibold" for="size_{{ $index }}">
                {{ $sizeOption }}
              </label>
            @endforeach
          </div>
        </div>
      @endif

      <!-- Add to Cart Form / Direct WA -->
      <div class="d-flex flex-column gap-3">
        <a id="directWaBtn" href="{{ $directWaUrl }}" target="_blank" class="btn btn-success btn-lg rounded-pill px-4 text-white fw-bold d-flex align-items-center justify-content-center gap-2">
          <i class="fa-brands fa-whatsapp fs-4"></i>
          <span>Tanya Stok Varian / Order via WA</span>
        </a>

        <form action="{{ route('cart.add') }}" method="POST" id="addToCartForm" class="d-flex gap-3 align-items-center">
          @csrf
          <input type="hidden" name="product_id" value="{{ $product->id }}">
          <div style="width: 100px;">
            <input type="number" name="quantity" class="form-control form-control-lg text-center rounded-pill" value="1" min="1" max="{{ $product->stock }}">
          </div>
          <button type="submit" class="btn btn-dark btn-lg rounded-pill px-4 flex-grow-1" {{ $product->stock <= 0 ? 'disabled' : '' }}>
            <i class="fa-solid fa-cart-plus me-2"></i> Tambah ke Keranjang
          </button>
        </form>

        <!-- Share Section -->
        <div class="pt-3 border-top mt-2 d-flex align-items-center gap-3">
          <span class="fs-7 fw-semibold text-muted"><i class="fa-solid fa-share-nodes me-1"></i> Bagikan:</span>
          <a href="https://wa.me/?text={{ urlencode('Lihat produk ' . $product->name . ' di Vyora Store: ' . route('products.show', $product->slug)) }}" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-3 fs-7">
            <i class="fa-brands fa-whatsapp me-1"></i> WhatsApp
          </a>
          <button type="button" onclick="copyProductLink()" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fs-7">
            <i class="fa-solid fa-link me-1"></i> Salin Link
          </button>
        </div>
      </div>

    </div>
  </div>

  <!-- Dedicated Product Description Section -->
  <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-5 bg-white">
    <div class="border-bottom pb-3 mb-4">
      <h4 class="font-secondary fw-bold text-dark mb-0">
        <i class="fa-solid fa-align-left me-2 text-primary"></i> Deskripsi Produk
      </h4>
    </div>
    <div class="text-secondary fs-6 lh-lg">
      @if($product->description)
        {!! nl2br(e($product->description)) !!}
      @else
        <p class="text-muted italic mb-0">Belum ada rincian deskripsi tambahan untuk produk ini.</p>
      @endif
    </div>

    <!-- Full Dimension Photo Showcase (Resolusi & Dimensi Asli Tanpa Terpotong) -->
    @if(count($allImages) > 0)
      <div class="border-top pt-4 mt-5">
        <div class="mb-4">
          <h5 class="fw-bold text-dark mb-0">
            <i class="fa-solid fa-images me-2 text-primary"></i> Detail & Foto Lengkap Produk
          </h5>
        </div>
        <div class="d-flex flex-column gap-4">
          @foreach($allImages as $idx => $img)
            <div class="w-100 text-center rounded-4 overflow-hidden bg-light p-2 border">
              <img src="{{ $img }}" alt="{{ $product->name }} Detail {{ $idx + 1 }}" class="img-fluid rounded-3" style="max-width: 100%; height: auto; object-fit: contain;">
            </div>
          @endforeach
        </div>
      </div>
    @endif
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

  <!-- Sticky Mobile Bottom Bar -->
  <div class="sticky-mobile-bar d-flex align-items-center justify-content-between gap-2 p-3">
    <div>
      <span class="text-muted fs-8 d-block lh-1 mb-1">Harga:</span>
      <span class="fw-bold text-dark fs-6">{{ $product->formatted_price }}</span>
    </div>
    <div class="d-flex gap-2">
      <button type="button" onclick="document.getElementById('addToCartForm').submit()" class="btn btn-dark btn-sm rounded-pill px-3 py-2 fw-bold d-flex align-items-center gap-1" {{ $product->stock <= 0 ? 'disabled' : '' }}>
        <i class="fa-solid fa-cart-plus"></i>
        <span>+Keranjang</span>
      </button>
      <a id="mobileWaBtn" href="{{ $directWaUrl }}" target="_blank" class="btn btn-success btn-sm rounded-pill px-3 py-2 fw-bold d-flex align-items-center gap-1">
        <i class="fa-brands fa-whatsapp fs-5"></i>
        <span>WA</span>
      </a>
    </div>
  </div>

</div>

@push('scripts')
<script>
  function changeMainImage(url, btn) {
    const mainImg = document.getElementById('mainProductImage');
    if (mainImg) {
      mainImg.style.opacity = 0;
      setTimeout(() => {
        mainImg.src = url;
        mainImg.style.opacity = 1;
      }, 150);
    }
    document.querySelectorAll('.thumbnail-btn').forEach(b => {
      b.classList.remove('border-dark', 'border-2');
      b.classList.add('opacity-75');
    });
    btn.classList.add('border-dark', 'border-2');
    btn.classList.remove('opacity-75');
  }

  function updateWaUrl() {
    const waBtn = document.getElementById('directWaBtn');
    if (!waBtn) return;

    const baseWaNum = "{{ \App\Models\Setting::get('whatsapp_number', '6281234567890') }}";
    const storeName = "{{ \App\Models\Setting::get('store_name', 'VYORA') }}";
    const productName = "{{ $product->name }}";
    const productPrice = "{{ $product->formatted_price }}";
    const productUrl = "{{ route('products.show', $product->slug) }}";

    let colorVal = '';
    const selectedColor = document.querySelector('.color-radio:checked');
    if (selectedColor) colorVal = selectedColor.value;

    let sizeVal = '';
    const selectedSize = document.querySelector('.size-radio:checked');
    if (selectedSize) sizeVal = selectedSize.value;

    let msg = `Halo ${storeName}, saya mau tanya / pesan produk ini:\n\n*${productName}*\n`;
    if (colorVal) msg += `Warna: ${colorVal}\n`;
    if (sizeVal) msg += `Ukuran: ${sizeVal}\n`;
    msg += `Harga: ${productPrice}\nLink: ${productUrl}`;

    const finalUrl = `https://wa.me/${baseWaNum}?text=${encodeURIComponent(msg)}`;
    waBtn.href = finalUrl;

    const mobileWaBtn = document.getElementById('mobileWaBtn');
    if (mobileWaBtn) {
      mobileWaBtn.href = finalUrl;
    }
  }

  function copyProductLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
      alert('Tautan produk berhasil disalin ke clipboard!');
    }).catch(err => {
      console.error('Gagal menyalin link: ', err);
    });
  }
</script>
@endpush

@endsection
