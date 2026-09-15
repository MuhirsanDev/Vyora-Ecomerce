@extends('layouts.admin')

@section('title', 'Edit Pengguna - Vyora Admin')

@section('content')

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
  <div>
    <h2 class="text-2xl font-black text-slate-900 tracking-tight">Edit Akun & Update Sandi</h2>
    <p class="text-sm text-slate-500">Kelola identitas pengguna #{{ $user->id }} - {{ $user->name }}</p>
  </div>
  <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-sm transition">
    <i class="fa-solid fa-arrow-left"></i> Kembali
  </a>
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

  <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-6">
    @csrf
    @method('PUT')

    <div>
      <label class="block text-sm font-bold text-slate-900 mb-2">Nama Lengkap <span class="text-rose-500">*</span></label>
      <input type="text" name="name" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 text-sm font-medium transition" value="{{ old('name', $user->name) }}" required>
    </div>

    <div>
      <label class="block text-sm font-bold text-slate-900 mb-2">Alamat Email <span class="text-rose-500">*</span></label>
      <input type="email" name="email" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 text-sm font-medium transition" value="{{ old('email', $user->email) }}" required>
    </div>

    <div>
      <label class="block text-sm font-bold text-slate-900 mb-2">Nomor WhatsApp / HP</label>
      <input type="text" name="phone" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 text-sm font-medium transition" value="{{ old('phone', $user->phone) }}" placeholder="081234567890">
    </div>

    <div>
      <label class="block text-sm font-bold text-slate-900 mb-2">Role Hak Akses <span class="text-rose-500">*</span></label>
      <select name="role" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 text-sm font-medium bg-white">
        <option value="customer" {{ old('role', $user->role) == 'customer' ? 'selected' : '' }}>Pelanggan (Customer)</option>
        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrator</option>
      </select>
    </div>

    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex items-center gap-3">
      <input type="checkbox" name="is_blocked" value="1" id="is_blocked" class="w-4 h-4 text-rose-600 rounded border-slate-300 focus:ring-rose-500" {{ old('is_blocked', $user->is_blocked) ? 'checked' : '' }}>
      <label for="is_blocked" class="text-sm font-bold text-slate-900 cursor-pointer">
        Blokir Akun Pengguna Ini (Pengguna tidak dapat login)
      </label>
    </div>

    <hr class="border-slate-200 my-6">

    <div class="p-5 rounded-2xl bg-indigo-50/50 border border-indigo-100 space-y-4">
      <div>
        <h3 class="text-base font-extrabold text-indigo-900 mb-1"><i class="fa-solid fa-key me-1 text-indigo-600"></i> Update Kata Sandi Pengguna</h3>
        <p class="text-xs text-indigo-600/80">Demi keamanan, kata sandi lama tidak dapat dibaca oleh siapa pun. Isi kolom ini jika ingin menyetel ulang kata sandi pengguna.</p>
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-800 mb-1">Kata Sandi Baru</label>
        <input type="password" name="password" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 text-sm font-medium bg-white" placeholder="Masukkan kata sandi baru (minimal 8 karakter)">
      </div>
    </div>

    <button type="submit" class="w-full py-3 px-6 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-md shadow-blue-600/30 transition cursor-pointer">
      Simpan Perubahan Pengguna
    </button>
  </form>
</div>

@endsection
