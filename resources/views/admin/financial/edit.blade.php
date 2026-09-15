@extends('layouts.admin')

@section('title', 'Edit Catatan Transaksi - Vyora Admin')

@section('content')

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
  <div>
    <h2 class="text-2xl font-black text-slate-900 tracking-tight">Edit Catatan Transaksi</h2>
    <p class="text-sm text-slate-500">Ubah detail transaksi {{ $transaction->transaction_code }}</p>
  </div>
  <a href="{{ route('admin.financial.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-sm transition">
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

  <form action="{{ route('admin.financial.update', $transaction->id) }}" method="POST" class="space-y-6">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-bold text-slate-900 mb-2">Nama Pelanggan <span class="text-rose-500">*</span></label>
        <input type="text" name="customer_name" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 text-sm font-medium" value="{{ old('customer_name', $transaction->customer_name) }}" required>
      </div>
      <div>
        <label class="block text-sm font-bold text-slate-900 mb-2">Nomor WhatsApp / HP</label>
        <input type="text" name="customer_phone" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 text-sm font-medium" value="{{ old('customer_phone', $transaction->customer_phone) }}">
      </div>
    </div>

    <div>
      <label class="block text-sm font-bold text-slate-900 mb-2">Nama Produk Terjual <span class="text-rose-500">*</span></label>
      <input type="text" name="product_name" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 text-sm font-medium" value="{{ old('product_name', $transaction->product_name) }}" required>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-bold text-slate-900 mb-2">Jumlah Unit (Qty) <span class="text-rose-500">*</span></label>
        <input type="number" name="quantity" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 text-sm font-medium" value="{{ old('quantity', $transaction->quantity) }}" required min="1">
      </div>
      <div>
        <label class="block text-sm font-bold text-slate-900 mb-2">Harga Satuan (Rp) <span class="text-rose-500">*</span></label>
        <input type="number" name="price_per_unit" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 text-sm font-medium" value="{{ old('price_per_unit', (int)$transaction->price_per_unit) }}" required min="0">
      </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
      <div>
        <label class="block text-sm font-bold text-slate-900 mb-2">Metode Pembayaran</label>
        <select name="payment_method" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 text-sm font-medium bg-white">
          <option value="WhatsApp / Transfer" {{ old('payment_method', $transaction->payment_method) == 'WhatsApp / Transfer' ? 'selected' : '' }}>WhatsApp / Transfer</option>
          <option value="Cash / Tunai" {{ old('payment_method', $transaction->payment_method) == 'Cash / Tunai' ? 'selected' : '' }}>Cash / Tunai</option>
          <option value="BCA Transfer" {{ old('payment_method', $transaction->payment_method) == 'BCA Transfer' ? 'selected' : '' }}>BCA Transfer</option>
          <option value="Mandiri Transfer" {{ old('payment_method', $transaction->payment_method) == 'Mandiri Transfer' ? 'selected' : '' }}>Mandiri Transfer</option>
          <option value="QRIS" {{ old('payment_method', $transaction->payment_method) == 'QRIS' ? 'selected' : '' }}>QRIS</option>
        </select>
      </div>

      <div>
        <label class="block text-sm font-bold text-slate-900 mb-2">Status Pembayaran <span class="text-rose-500">*</span></label>
        <select name="status" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 text-sm font-medium bg-white">
          <option value="Lunas" {{ old('status', $transaction->status) == 'Lunas' ? 'selected' : '' }}>Lunas</option>
          <option value="Belum Bayar" {{ old('status', $transaction->status) == 'Belum Bayar' ? 'selected' : '' }}>Belum Bayar</option>
          <option value="Pending" {{ old('status', $transaction->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
          <option value="Batal" {{ old('status', $transaction->status) == 'Batal' ? 'selected' : '' }}>Batal</option>
        </select>
      </div>

      <div>
        <label class="block text-sm font-bold text-slate-900 mb-2">Tanggal Transaksi <span class="text-rose-500">*</span></label>
        <input type="date" name="transaction_date" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 text-sm font-medium" value="{{ old('transaction_date', $transaction->transaction_date->format('Y-m-d')) }}" required>
      </div>
    </div>

    <div>
      <label class="block text-sm font-bold text-slate-900 mb-2">Catatan Tambahan</label>
      <textarea name="notes" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 text-sm font-medium" rows="3">{{ old('notes', $transaction->notes) }}</textarea>
    </div>

    <button type="submit" class="w-full py-3 px-6 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-md shadow-blue-600/30 transition cursor-pointer">
      Update Catatan Transaksi
    </button>
  </form>
</div>

@endsection
