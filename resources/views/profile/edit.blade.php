@extends('layouts.app')

@section('title', 'Profil Saya - Vyora Store')

@section('content')

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-7">
      <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
        
        <div class="d-flex align-items-center gap-3 mb-4 border-bottom pb-3">
          <div class="rounded-circle bg-dark text-white fw-bold d-flex align-items-center justify-content-center fs-3" style="width: 56px; height: 56px;">
            {{ strtoupper(substr($user->name, 0, 1)) }}
          </div>
          <div>
            <h3 class="font-secondary fw-bold text-dark mb-0">{{ $user->name }}</h3>
            <span class="badge bg-light text-muted border">{{ ucfirst($user->role) }} Account</span>
          </div>
        </div>

        @if(session('success'))
          <div class="alert alert-success alert-dismissible fade show fs-7 mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        @endif

        @if($errors->any())
          <div class="alert alert-danger py-2 fs-7 mb-4">
            <ul class="mb-0 ps-3">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST">
          @csrf

          <div class="mb-3">
            <label class="form-label fw-semibold text-dark">Nama Lengkap</label>
            <input type="text" name="name" class="form-control rounded-pill px-3" value="{{ old('name', $user->name) }}" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold text-dark">Alamat Email</label>
            <input type="email" name="email" class="form-control rounded-pill px-3" value="{{ old('email', $user->email) }}" required>
          </div>

          <div class="mb-4">
            <label class="form-label fw-semibold text-dark">Nomor WhatsApp</label>
            <input type="text" name="phone" class="form-control rounded-pill px-3" value="{{ old('phone', $user->phone) }}" placeholder="081234567890">
            <small class="text-muted fs-7">Nomor ini akan digunakan saat pemesanan otomatis via WhatsApp.</small>
          </div>

          <hr class="my-4">

          <h5 class="fw-bold text-dark mb-3">Ubah Kata Sandi (Opsional)</h5>
          <p class="text-muted fs-7 mb-3">Biarkan kosong jika Anda tidak ingin menguubah kata sandi.</p>

          <div class="row g-3 mb-4">
            <div class="col-md-6">
              <label class="form-label fw-semibold text-dark">Kata Sandi Baru</label>
              <input type="password" name="password" class="form-control rounded-pill px-3" placeholder="Minimal 8 karakter">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold text-dark">Konfirmasi Sandi Baru</label>
              <input type="password" name="password_confirmation" class="form-control rounded-pill px-3" placeholder="Ulangi kata sandi baru">
            </div>
          </div>

          <button type="submit" class="btn btn-dark btn-lg rounded-pill w-100 fw-bold">Simpan Perubahan Profil</button>
        </form>

      </div>
    </div>
  </div>
</div>

@endsection
