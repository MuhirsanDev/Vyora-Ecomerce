@extends('layouts.admin')

@section('title', 'Manajemen Pengguna - Vyora Admin')

@section('content')

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
  <div>
    <h2 class="text-2xl font-black text-slate-900 tracking-tight">Manajemen Pengguna & Akun</h2>
    <p class="text-sm text-slate-500">Kelola identitas pendaftar, atur kata sandi baru, blokir akun, atau hapus user</p>
  </div>
</div>

<!-- Search & Filter Bar -->
<div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs mb-6">
  <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
    <div class="flex-1">
      <input type="text" name="search" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 text-sm font-medium" placeholder="Cari nama, email, atau nomor HP pengguna..." value="{{ request('search') }}">
    </div>
    <div class="w-full sm:w-48">
      <select name="role" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 text-sm font-medium bg-white">
        <option value="">-- Semua Role --</option>
        <option value="customer" {{ request('role') == 'customer' ? 'selected' : '' }}>Pelanggan (Customer)</option>
        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
      </select>
    </div>
    <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm transition cursor-pointer">
      Cari
    </button>
    @if(request('search') || request('role'))
      <a href="{{ route('admin.users.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-sm transition text-center">
        Reset
      </a>
    @endif
  </form>
</div>

<!-- Users Table -->
<div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-left border-collapse text-sm">
      <thead>
        <tr class="bg-slate-50 text-slate-400 text-xs uppercase tracking-wider font-bold border-b border-slate-100">
          <th class="py-3 px-6">Identitas Pengguna</th>
          <th class="py-3 px-4">Kontak WhatsApp</th>
          <th class="py-3 px-4">Role Hak Akses</th>
          <th class="py-3 px-4">Status Akun</th>
          <th class="py-3 px-6 text-right">Aksi Management</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100">
        @forelse($users as $user)
          <tr class="hover:bg-slate-50/80 transition">
            <td class="py-4 px-6">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-700 font-extrabold flex items-center justify-center border border-slate-200">
                  {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                  <div class="font-bold text-slate-900 text-sm">{{ $user->name }}</div>
                  <div class="text-xs text-slate-400">{{ $user->email }}</div>
                </div>
              </div>
            </td>
            <td class="py-4 px-4 font-semibold text-slate-700">
              @if($user->phone)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->phone) }}" target="_blank" class="text-emerald-600 hover:underline">
                  <i class="fa-brands fa-whatsapp me-1"></i> {{ $user->phone }}
                </a>
              @else
                <span class="text-slate-400">-</span>
              @endif
            </td>
            <td class="py-4 px-4">
              @if($user->isAdmin())
                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                  Administrator
                </span>
              @else
                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                  Pelanggan
                </span>
              @endif
            </td>
            <td class="py-4 px-4">
              @if($user->is_blocked)
                <span class="inline-block px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                  <i class="fa-solid fa-ban me-1"></i> Diblokir
                </span>
              @else
                <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                  <i class="fa-solid fa-circle-check me-1"></i> Aktif
                </span>
              @endif
            </td>
            <td class="py-4 px-6 text-right space-x-1">
              <!-- Edit Details & Password -->
              <a href="{{ route('admin.users.edit', $user->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-600 hover:text-white font-bold text-xs transition" title="Update Identitas & Password">
                <i class="fa-solid fa-key"></i> Edit / Sandi
              </a>

              <!-- Toggle Block Button -->
              @if($user->id !== auth()->id())
                <form action="{{ route('admin.users.toggle-block', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ $user->is_blocked ? 'Aktifkan kembali akun ini?' : 'Blokir akun ini agar tidak bisa login?' }}')">
                  @csrf
                  @method('PATCH')
                  <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg {{ $user->is_blocked ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white' : 'bg-amber-50 text-amber-700 hover:bg-amber-600 hover:text-white' }} font-bold text-xs transition cursor-pointer">
                    <i class="fa-solid {{ $user->is_blocked ? 'fa-lock-open' : 'fa-ban' }}"></i>
                    {{ $user->is_blocked ? 'Unblock' : 'Blokir' }}
                  </button>
                </form>

                <!-- Delete Button -->
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus akun ini secara permanen?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 text-rose-600 hover:bg-rose-600 hover:text-white transition cursor-pointer" title="Hapus Akun">
                    <i class="fa-solid fa-trash-can text-xs"></i>
                  </button>
                </form>
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="text-center py-10 text-slate-400 font-medium">Pengguna tidak ditemukan.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($users->hasPages())
    <div class="p-4 border-t border-slate-100 bg-white">
      {{ $users->links() }}
    </div>
  @endif
</div>

@endsection
