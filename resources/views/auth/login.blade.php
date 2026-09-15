@extends('layouts.app')

@section('title', 'Masuk Akun - Vyora Store')

@section('content')

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card border-0 shadow-sm rounded-4 p-4">
        <div class="card-body">
          <h3 class="fw-bold font-secondary text-dark mb-1">Selamat Datang</h3>
          <p class="text-muted fs-6">Silakan masuk ke akun Vyora Anda</p>

          @if(session('success'))
            <div class="alert alert-success rounded-3 fs-7 mb-4">
              {{ session('success') }}
            </div>
          @endif

          @if($errors->any())
            <div class="alert alert-danger rounded-3 fs-7 mb-4">
              <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form action="{{ route('login') }}" method="POST">
            @csrf

            <div class="mb-3">
              <label class="form-label font-secondary fw-semibold">Alamat Email</label>
              <input type="email" name="email" class="form-control form-control-lg rounded-3 fs-6" value="{{ old('email') }}" required placeholder="email@contoh.com">
            </div>

            <div class="mb-4">
              <label class="form-label font-secondary fw-semibold">Password</label>
              <input type="password" name="password" class="form-control form-control-lg rounded-3 fs-6" required placeholder="••••••••">
            </div>

            <button type="submit" class="btn btn-dark btn-lg rounded-pill w-100 fw-bold mb-3">
              Masuk Sekarang
            </button>
          </form>

          <div class="text-center mt-3">
            <span class="text-muted">Belum punya akun?</span>
            <a href="{{ route('register') }}" class="text-dark fw-bold text-decoration-none ms-1">Daftar Akun Baru</a>
          </div>

          <hr class="my-4">
          <div class="text-muted fs-7">
            <strong>Akun Pengujian Demo:</strong><br>
            • <strong>Admin:</strong> admin@vyora.com / admin123<br>
            • <strong>Customer:</strong> user@vyora.com / user123
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
