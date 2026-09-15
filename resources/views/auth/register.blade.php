@extends('layouts.app')

@section('title', 'Daftar Akun Baru - Vyora Store')

@section('content')

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card border-0 shadow-sm rounded-4 p-4">
        <div class="text-center mb-4">
          <h3 class="font-secondary fw-bold text-dark">Buat Akun Baru</h3>
          <p class="text-muted fs-6">Daftar sekarang untuk dapat menyimpan produk ke keranjang</p>
        </div>

        @if($errors->any())
          <div class="alert alert-danger py-2 fs-7 mb-3">
            <ul class="mb-0 ps-3">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form action="{{ route('register') }}" method="POST">
          @csrf
          <div class="mb-3">
            <label class="form-label fw-semibold text-dark">Nama Lengkap</label>
            <input type="text" name="name" class="form-control rounded-pill px-3" value="{{ old('name') }}" required placeholder="Nama Anda">
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold text-dark">Alamat Email</label>
            <input type="email" name="email" class="form-control rounded-pill px-3" value="{{ old('email') }}" required placeholder="nama@email.com">
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold text-dark">Nomor WhatsApp (Opsional)</label>
            <input type="text" name="phone" class="form-control rounded-pill px-3" value="{{ old('phone') }}" placeholder="081234567890">
          </div>

          <div class="row g-3 mb-4">
            <div class="col-md-6">
              <label class="form-label fw-semibold text-dark">Kata Sandi</label>
              <input type="password" name="password" class="form-control rounded-pill px-3" required placeholder="Minimal 8 karakter">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold text-dark">Konfirmasi Sandi</label>
              <input type="password" name="password_confirmation" class="form-control rounded-pill px-3" required placeholder="Ulangi kata sandi">
            </div>
          </div>

          <button type="submit" class="btn btn-dark btn-lg rounded-pill w-100 mb-3 fw-bold">Daftar Sekarang</button>

          <div class="text-center text-muted fs-7">
            Sudah punya akun? <a href="{{ route('login') }}" class="fw-bold text-dark text-decoration-none">Masuk di sini</a>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>

@endsection
