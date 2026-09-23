<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    private function getCartQuery()
    {
        if (Auth::check()) {
            return CartItem::where('user_id', Auth::id());
        }
        return CartItem::where('session_id', session()->getId());
    }

    public function index()
    {
        $cartItems = $this->getCartQuery()
            ->with('product')
            ->get();

        $totalPrice = $cartItems->sum(fn ($item) => $item->subtotal);
        $whatsappNumber = Setting::get('whatsapp_number', '6281234567890');

        return view('cart.index', compact('cartItems', 'totalPrice', 'whatsappNumber'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:50'],
            'color' => ['nullable', 'string', 'max:100'],
            'size' => ['nullable', 'string', 'max:50'],
        ]);

        $product = Product::findOrFail($request->input('product_id'));
        $color = $request->input('color');
        $size = $request->input('size');

        $query = $this->getCartQuery()->where('product_id', $product->id);
        if ($color) {
            $query->where('color', $color);
        } else {
            $query->whereNull('color');
        }

        if ($size) {
            $query->where('size', $size);
        } else {
            $query->whereNull('size');
        }

        $cartItem = $query->first();

        if ($cartItem) {
            $cartItem->quantity += (int) $request->input('quantity', 1);
            $cartItem->save();
        } else {
            CartItem::create([
                'user_id' => Auth::check() ? Auth::id() : null,
                'session_id' => Auth::check() ? null : session()->getId(),
                'product_id' => $product->id,
                'quantity' => (int) $request->input('quantity', 1),
                'color' => $color,
                'size' => $size,
            ]);
        }

        $details = [];
        if ($color) $details[] = "Warna: {$color}";
        if ($size) $details[] = "Ukuran: {$size}";
        $variantInfo = !empty($details) ? " (" . implode(', ', $details) . ")" : "";

        return redirect()->back()->with('success', "'{$product->name}'{$variantInfo} berhasil ditambahkan ke keranjang.");
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:50'],
        ]);

        $cartItem = $this->getCartQuery()->where('id', $id)->firstOrFail();
        $cartItem->update(['quantity' => $request->input('quantity')]);

        return redirect()->route('cart.index')->with('success', 'Jumlah produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $cartItem = $this->getCartQuery()->where('id', $id)->firstOrFail();
        $cartItem->delete();

        return redirect()->route('cart.index')->with('success', 'Produk berhasil dihapus dari keranjang.');
    }

    public function whatsappCheckout(Request $request)
    {
        $cartItems = $this->getCartQuery()
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda masih kosong.');
        }

        $storeName = Setting::get('store_name', 'VYORA');
        $whatsappNumber = Setting::get('whatsapp_number', '6281234567890');
        $defaultMsg = Setting::get('whatsapp_message', 'Halo Admin Vyora, saya tertarik untuk memesan produk berikut:');

        $message = "{$defaultMsg}\n\n";
        $message .= "*Detail Pesanan:*\n";

        if (Auth::check()) {
            $user = Auth::user();
            $message .= "Nama Pemesan: {$user->name}\n";
            $message .= "Email: {$user->email}\n";
            if ($user->phone) {
                $message .= "No. HP: {$user->phone}\n";
            }
        } else {
            $guestName = $request->input('guest_name') ?: 'Pengunjung Web';
            $guestPhone = $request->input('guest_phone');
            $message .= "Nama Pemesan: {$guestName}\n";
            if ($guestPhone) {
                $message .= "No. HP: {$guestPhone}\n";
            }
        }

        $message .= "-----------------------------\n";

        $totalPrice = 0;
        foreach ($cartItems as $index => $item) {
            $subtotal = $item->subtotal;
            $totalPrice += $subtotal;
            $n = $index + 1;
            $itemPrice = number_format($item->product->effective_price, 0, ',', '.');
            $subtotalFormatted = number_format($subtotal, 0, ',', '.');

            $variantDetails = [];
            if ($item->color) {
                $variantDetails[] = "Warna: {$item->color}";
            }
            if ($item->size) {
                $variantDetails[] = "Ukuran: {$item->size}";
            }
            $variantText = !empty($variantDetails) ? " (" . implode(', ', $variantDetails) . ")" : "";

            $message .= "{$n}. *{$item->product->name}*{$variantText}\n";
            $message .= "   {$item->quantity}x @ Rp {$itemPrice} = Rp {$subtotalFormatted}\n";
        }

        $message .= "-----------------------------\n";
        $message .= "*Total Pembayaran: Rp " . number_format($totalPrice, 0, ',', '.') . "*\n\n";
        $message .= "Mohon info proses selanjutnya ya Admin, terima kasih!";

        $whatsappUrl = "https://wa.me/{$whatsappNumber}?text=" . urlencode($message);

        return redirect()->away($whatsappUrl);
    }
}

