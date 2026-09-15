@extends('layouts.admin')

@section('title', 'Pembukuan & Laporan Keuangan - Vyora Admin')

@section('content')

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
  <div>
    <h2 class="text-2xl font-black text-slate-900 tracking-tight">Pembukuan & Laporan Keuangan</h2>
    <p class="text-sm text-slate-500">Catat transaksi penjualan, riwayat status pembayaran, dan unduh laporan Excel (.xlsx)</p>
  </div>
  <div class="flex flex-wrap items-center gap-3">
    <a href="{{ route('admin.financial.download', request()->query()) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-md shadow-emerald-600/30 transition">
      <i class="fa-solid fa-file-excel text-base"></i> Unduh Laporan (.xlsx Excel)
    </a>
    <a href="{{ route('admin.financial.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-md shadow-blue-600/30 transition">
      <i class="fa-solid fa-plus text-base"></i> Catat Transaksi Baru
    </a>
  </div>
</div>

@if(session('success'))
  <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-bold flex items-center gap-3 shadow-xs">
    <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
    <span>{{ session('success') }}</span>
  </div>
@endif

<!-- Financial Summary Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
  <!-- Total Revenue -->
  <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
    <div class="flex items-center justify-between mb-3">
      <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Pendapatan (Lunas)</span>
      <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-lg">
        <i class="fa-solid fa-money-bill-wave"></i>
      </div>
    </div>
    <div class="text-2xl font-black text-emerald-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
    <span class="text-xs text-slate-400 font-medium mt-1 block">Hasil penjualan produk lunas</span>
  </div>

  <!-- Total Unpaid / Piutang -->
  <div class="bg-white p-6 rounded-2xl border border-rose-100 shadow-xs">
    <div class="flex items-center justify-between mb-3">
      <span class="text-xs font-bold uppercase tracking-wider text-rose-500">Belum Bayar (Piutang)</span>
      <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center text-lg">
        <i class="fa-solid fa-triangle-exclamation"></i>
      </div>
    </div>
    <div class="text-2xl font-black text-rose-600">Rp {{ number_format($totalUnpaid, 0, ',', '.') }}</div>
    <span class="text-xs text-rose-400 font-bold mt-1 block"><i class="fa-solid fa-user-clock me-1"></i> {{ $countUnpaid }} Orang Belum Bayar</span>
  </div>

  <!-- Total Items Sold -->
  <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
    <div class="flex items-center justify-between mb-3">
      <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Produk Terjual</span>
      <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-lg">
        <i class="fa-solid fa-boxes-packing"></i>
      </div>
    </div>
    <div class="text-2xl font-black text-slate-900">{{ number_format($totalItemsSold) }} Pcs</div>
    <span class="text-xs text-slate-400 font-medium mt-1 block">Total unit barang terjual</span>
  </div>

  <!-- Total Transactions -->
  <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
    <div class="flex items-center justify-between mb-3">
      <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Transaksi</span>
      <div class="w-10 h-10 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center text-lg">
        <i class="fa-solid fa-receipt"></i>
      </div>
    </div>
    <div class="text-2xl font-black text-slate-900">{{ number_format($totalTransactions) }} Transaksi</div>
    <span class="text-xs text-slate-400 font-medium mt-1 block">Total catatan pembukuan</span>
  </div>
</div>

<!-- Filter Bar -->
<div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs mb-6">
  <form action="{{ route('admin.financial.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
    <div>
      <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
          <i class="fa-solid fa-magnifying-glass text-xs"></i>
        </div>
        <input type="text" name="search" class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 text-sm font-medium" placeholder="Cari nama pelanggan, WA, produk..." value="{{ request('search') }}">
      </div>
    </div>
    <div>
      <input type="date" name="from_date" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 text-sm font-medium" value="{{ request('from_date') }}" title="Dari Tanggal">
    </div>
    <div>
      <input type="date" name="to_date" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 text-sm font-medium" value="{{ request('to_date') }}" title="Sampai Tanggal">
    </div>
    <div class="flex gap-2">
      <select name="status" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 text-sm font-medium bg-white">
        <option value="">-- Status Pembayaran --</option>
        <option value="Lunas" {{ request('status') == 'Lunas' ? 'selected' : '' }}>Lunas</option>
        <option value="Belum Bayar" {{ request('status') == 'Belum Bayar' ? 'selected' : '' }}>Belum Bayar</option>
        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
        <option value="Batal" {{ request('status') == 'Batal' ? 'selected' : '' }}>Batal</option>
      </select>
      <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm transition cursor-pointer">
        Filter
      </button>
      @if(request('search') || request('from_date') || request('to_date') || request('status'))
        <a href="{{ route('admin.financial.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-sm transition text-center">
          Reset
        </a>
      @endif
    </div>
  </form>
</div>

<!-- Transactions Table (50 Items Per Page) -->
<div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-left border-collapse text-sm">
      <thead>
        <tr class="bg-slate-50 text-slate-400 text-xs uppercase tracking-wider font-bold border-b border-slate-100">
          <th class="py-3 px-6">Kode & Tanggal</th>
          <th class="py-3 px-4">Nama Pelanggan</th>
          <th class="py-3 px-4">Produk Terjual</th>
          <th class="py-3 px-4">Qty</th>
          <th class="py-3 px-4">Harga Satuan</th>
          <th class="py-3 px-4">Total Pembayaran</th>
          <th class="py-3 px-4">Status Pembayaran</th>
          <th class="py-3 px-6 text-right">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100">
        @forelse($transactions as $trx)
          <tr class="hover:bg-slate-50/80 transition {{ $trx->status === 'Belum Bayar' ? 'bg-rose-50/30' : '' }}">
            <td class="py-4 px-6">
              <div class="font-bold text-slate-900 text-xs font-mono text-indigo-600">{{ $trx->transaction_code }}</div>
              <div class="text-xs text-slate-400">{{ $trx->transaction_date->format('d M Y') }}</div>
            </td>
            <td class="py-4 px-4 font-bold text-slate-800">
              <div class="text-slate-900 font-bold">{{ $trx->customer_name }}</div>
              @if($trx->customer_phone)
                <div class="text-xs text-slate-500 font-normal mt-0.5">
                  <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $trx->customer_phone) }}" target="_blank" class="hover:underline text-emerald-600 font-semibold">
                    <i class="fa-brands fa-whatsapp me-1"></i> {{ $trx->customer_phone }}
                  </a>
                </div>
              @endif
            </td>
            <td class="py-4 px-4 font-semibold text-slate-900">{{ $trx->product_name }}</td>
            <td class="py-4 px-4 font-bold text-slate-900">{{ $trx->quantity }} Pcs</td>
            <td class="py-4 px-4 text-slate-700">{{ $trx->formatted_price_per_unit }}</td>
            <td class="py-4 px-4 font-extrabold {{ $trx->status === 'Belum Bayar' ? 'text-rose-600' : 'text-emerald-600' }}">
              {{ $trx->formatted_total_amount }}
            </td>
            <td class="py-4 px-4">
              <!-- Quick Status Change Form -->
              <form action="{{ route('admin.financial.update-status', $trx->id) }}" method="POST" class="inline-block">
                @csrf
                @method('PATCH')
                <select name="status" onchange="this.form.submit()" class="text-xs font-bold rounded-lg px-2.5 py-1.5 border cursor-pointer focus:ring-2 focus:ring-blue-500 transition
                  @if($trx->status === 'Lunas') bg-emerald-50 text-emerald-700 border-emerald-200
                  @elseif($trx->status === 'Belum Bayar') bg-rose-50 text-rose-700 border-rose-300 font-black animate-pulse
                  @elseif($trx->status === 'Pending') bg-amber-50 text-amber-700 border-amber-200
                  @else bg-slate-100 text-slate-600 border-slate-200 @endif">
                  <option value="Lunas" {{ $trx->status === 'Lunas' ? 'selected' : '' }}>✔ Lunas</option>
                  <option value="Belum Bayar" {{ $trx->status === 'Belum Bayar' ? 'selected' : '' }}>⚠️ Belum Bayar</option>
                  <option value="Pending" {{ $trx->status === 'Pending' ? 'selected' : '' }}>⏳ Pending</option>
                  <option value="Batal" {{ $trx->status === 'Batal' ? 'selected' : '' }}>✖ Batal</option>
                </select>
              </form>
            </td>
            <td class="py-4 px-6 text-right space-x-1">
              <a href="{{ route('admin.financial.edit', $trx->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 text-blue-600 hover:bg-blue-600 hover:text-white transition" title="Edit Transaksi">
                <i class="fa-solid fa-pen-to-square text-xs"></i>
              </a>
              <form action="{{ route('admin.financial.destroy', $trx->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus catatan transaksi ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 text-rose-600 hover:bg-rose-600 hover:text-white transition cursor-pointer" title="Hapus">
                  <i class="fa-solid fa-trash-can text-xs"></i>
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="text-center py-10 text-slate-400 font-medium">Belum ada catatan transaksi pembukuan.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($transactions->hasPages())
    <div class="p-4 border-t border-slate-100 bg-white">
      {{ $transactions->links() }}
    </div>
  @endif
</div>

@endsection
