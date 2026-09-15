<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = CartItem::with('product')
            ->where('user_id', Auth::id())
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
        ]);

        $product = Product::findOrFail($request->input('product_id'));

        $cartItem = CartItem::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $request->input('quantity', 1);
            $cartItem->save();
        } else {
            CartItem::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $request->input('quantity', 1),
            ]);
        }

        return redirect()->back()->with('success', "'{$product->name}' berhasil ditambahkan ke keranjang.");
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:50'],
        ]);

        $cartItem = CartItem::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        $cartItem->update(['quantity' => $request->input('quantity')]);

        return redirect()->route('cart.index')->with('success', 'Jumlah produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $cartItem = CartItem::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        $cartItem->delete();

        return redirect()->route('cart.index')->with('success', 'Produk berhasil dihapus dari keranjang.');
    }

    public function whatsappCheckout()
    {
        $user = Auth::user();
        $cartItems = CartItem::with('product')
            ->where('user_id', $user->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda masih kosong.');
        }

        $storeName = Setting::get('store_name', 'Vyora Fashion Store');
        $whatsappNumber = Setting::get('whatsapp_number', '6281234567890');
        $defaultMsg = Setting::get('whatsapp_message', 'Halo Admin Vyora, saya tertarik untuk memesan produk berikut:');

        $message = "{$defaultMsg}\n\n";
        $message .= "*Detail Pesanan:*\n";
        $message .= "Nama Pemesan: {$user->name}\n";
        $message .= "Email: {$user->email}\n";
        if ($user->phone) {
            $message .= "No. HP: {$user->phone}\n";
        }
        $message .= "-----------------------------\n";

        $totalPrice = 0;
        foreach ($cartItems as $index => $item) {
            $subtotal = $item->subtotal;
            $totalPrice += $subtotal;
            $n = $index + 1;
            $itemPrice = number_format($item->product->effective_price, 0, ',', '.');
            $subtotalFormatted = number_format($subtotal, 0, ',', '.');

            $message .= "{$n}. *{$item->product->name}*\n";
            $message .= "   {$item->quantity}x @ Rp {$itemPrice} = Rp {$subtotalFormatted}\n";
        }

        $message .= "-----------------------------\n";
        $message .= "*Total Pembayaran: Rp " . number_format($totalPrice, 0, ',', '.') . "*\n\n";
        $message .= "Mohon info proses selanjutnya ya Admin, terima kasih!";

        $whatsappUrl = "https://wa.me/{$whatsappNumber}?text=" . urlencode($message);

        return redirect()->away($whatsappUrl);
    }
}
