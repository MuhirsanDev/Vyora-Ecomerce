@extends('layouts.admin')

@section('title', 'Profil Saya - Vyora Admin')

@section('content')

<div class="mb-8">
  <h2 class="text-2xl font-black text-slate-900 tracking-tight">Pengaturan Profil Admin</h2>
  <p class="text-sm text-slate-500">Ubah identitas diri, email, nomor WhatsApp, dan kata sandi Anda</p>
</div>

<div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-8 max-w-2xl">
  @if($errors->any())
    <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-medium">
      <ul class="list-disc ps-5 space-y-1">
        @foreach($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
    @csrf

    <div>
      <label class="block text-sm font-bold text-slate-900 mb-2">Nama Lengkap <span class="text-rose-500">*</span></label>
      <input type="text" name="name" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 text-sm font-medium transition" value="{{ old('name', $user->name) }}" required>
    </div>

    <div>
      <label class="block text-sm font-bold text-slate-900 mb-2">Alamat Email <span class="text-rose-500">*</span></label>
      <input type="email" name="email" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 text-sm font-medium transition" value="{{ old('email', $user->email) }}" required>
    </div>

    <div>
      <label class="block text-sm font-bold text-slate-900 mb-2">Nomor WhatsApp Admin</label>
      <input type="text" name="phone" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 text-sm font-medium transition" value="{{ old('phone', $user->phone) }}" placeholder="081234567890">
    </div>

    <hr class="border-slate-200 my-6">

    <div>
      <h3 class="text-base font-extrabold text-slate-900 mb-1">Ubah Kata Sandi (Opsional)</h3>
      <p class="text-xs text-slate-400 mb-4">Kosongkan kolom sandi jika Anda tidak berniat mengganti password saat ini.</p>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Kata Sandi Baru</label>
          <input type="password" name="password" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 text-sm font-medium transition" placeholder="Minimal 8 karakter">
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Konfirmasi Sandi Baru</label>
          <input type="password" name="password_confirmation" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 text-sm font-medium transition" placeholder="Ulangi kata sandi">
        </div>
      </div>
    </div>

    <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-md shadow-blue-600/30 transition cursor-pointer">
      <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan Profil
    </button>
  </form>
</div>

@endsection
