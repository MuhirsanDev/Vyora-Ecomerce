@extends('layouts.app')

@section('title', 'Keranjang Belanja - Vyora Store')

@section('content')

<div class="container py-5">
  <h2 class="font-secondary fw-bold mb-4">Keranjang Belanja Anda</h2>

  @if($cartItems->count() > 0)
    <div class="row g-4">
      <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th class="ps-4">Produk</th>
                  <th>Harga</th>
                  <th style="width: 130px;">Jumlah</th>
                  <th>Subtotal</th>
                  <th class="text-end pe-4">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($cartItems as $item)
                  <tr>
                    <td class="ps-4 py-3">
                      <div class="d-flex align-items-center gap-3">
                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="rounded-3" style="width: 60px; height: 60px; object-fit: cover;">
                        <div>
                          <a href="{{ route('products.show', $item->product->slug) }}" class="fw-bold text-dark text-decoration-none">
                            {{ $item->product->name }}
                          </a>
                          <br>
                          <small class="text-muted me-2">{{ $item->product->category->name ?? '' }}</small>
                          @if($item->color)
                            <span class="badge bg-light text-dark border me-1">
                              <i class="fa-solid fa-palette me-1 text-primary"></i> {{ $item->color }}
                            </span>
                          @endif
                          @if($item->size)
                            <span class="badge bg-light text-dark border">
                              <i class="fa-solid fa-ruler-horizontal me-1 text-indigo-600"></i> {{ $item->size }}
                            </span>
                          @endif
                        </div>
                      </div>
                    </td>
                    <td>
                      <span class="fw-semibold">Rp {{ number_format($item->product->effective_price, 0, ',', '.') }}</span>
                    </td>
                    <td>
                      <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex align-items-center gap-1">
                        @csrf
                        @method('PATCH')
                        <input type="number" name="quantity" class="form-control form-control-sm text-center" value="{{ $item->quantity }}" min="1" max="50" onchange="this.form.submit()">
                      </form>
                    </td>
                    <td>
                      <span class="fw-bold text-dark">{{ $item->formatted_subtotal }}</span>
                    </td>
                    <td class="text-end pe-4">
                      <form action="{{ route('cart.destroy', $item->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-circle" title="Hapus">
                          <i class="fa-solid fa-trash-can"></i>
                        </button>
                      </form>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4">
          <h5 class="fw-bold mb-3">Ringkasan Belanja</h5>
          <hr>
          <div class="d-flex justify-content-between mb-2">
            <span class="text-muted">Total Items</span>
            <span class="fw-semibold">{{ $cartItems->sum('quantity') }} Pcs</span>
          </div>
          <div class="d-flex justify-content-between mb-4">
            <span class="fw-bold fs-5">Total Pembayaran</span>
            <span class="fw-bold fs-5 text-success">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
          </div>

          <div class="alert alert-light border fs-7 text-muted mb-4">
            <i class="fa-brands fa-whatsapp text-success me-1"></i> Pesanan Anda akan diformat secara otomatis dan dikirimkan langsung ke WhatsApp Admin Vyora untuk diproses.
          </div>

          <form action="{{ route('cart.whatsapp') }}" method="GET" target="_blank">
            @guest
              <div class="mb-3 text-start">
                <label class="form-label text-dark fw-semibold fs-7 mb-1">Nama Pemesan <span class="text-muted fw-normal">(Opsional)</span></label>
                <input type="text" name="guest_name" class="form-control form-control-sm rounded-3 px-3" placeholder="Contoh: Siska / Pengunjung">
              </div>
              <div class="mb-3 text-start">
                <label class="form-label text-dark fw-semibold fs-7 mb-1">No. WhatsApp <span class="text-muted fw-normal">(Opsional)</span></label>
                <input type="text" name="guest_phone" class="form-control form-control-sm rounded-3 px-3" placeholder="Contoh: 08123456789">
              </div>
            @endguest

            <button type="submit" class="btn btn-success btn-lg rounded-pill w-100 fw-bold d-flex align-items-center justify-content-center gap-2">
              <i class="fa-brands fa-whatsapp fs-4"></i>
              <span>Pesan via WhatsApp</span>
            </button>
          </form>

          <a href="{{ route('home') }}#catalog" class="btn btn-outline-dark rounded-pill w-100 mt-2">
            Lanjut Berbelanja
          </a>
        </div>
      </div>
    </div>
  @else
    <div class="text-center py-5 bg-white rounded-4 shadow-sm my-4">
      <i class="fa-solid fa-cart-shopping fs-1 text-muted mb-3"></i>
      <h4 class="fw-bold text-dark">Keranjang Belanja Anda Kosong</h4>
      <p class="text-muted mb-4">Yuk, temukan pakaian dan aksoris favorit Anda di katalog kami!</p>
      <a href="{{ route('home') }}#catalog" class="btn btn-dark btn-lg rounded-pill px-5">Lihat Katalog</a>
    </div>
  @endif
</div>

@endsection
