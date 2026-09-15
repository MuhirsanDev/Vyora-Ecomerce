<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $storeName = Setting::get('store_name', 'Vyora Fashion Store');
        $storeEmail = Setting::get('store_email', 'info@vyorastore.com');
        $storeAddress = Setting::get('store_address', 'Jakarta, Indonesia');
        $whatsappNumber = Setting::get('whatsapp_number', '6281234567890');
        $whatsappMessage = Setting::get('whatsapp_message', 'Halo Admin Vyora, saya tertarik untuk memesan produk berikut:');
        $storeLogo = Setting::get('store_logo');

        return view('admin.settings.index', compact('storeName', 'storeEmail', 'storeAddress', 'whatsappNumber', 'whatsappMessage', 'storeLogo'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'store_name' => ['required', 'string', 'max:255'],
            'store_email' => ['required', 'string', 'email', 'max:255'],
            'store_address' => ['required', 'string', 'max:500'],
            'whatsapp_number' => ['required', 'string', 'max:20'],
            'whatsapp_message' => ['required', 'string', 'max:500'],
            'store_logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'],
        ]);

        // Clean phone number
        $cleanPhone = preg_replace('/[^0-9]/', '', $validated['whatsapp_number']);
        if (str_starts_with($cleanPhone, '0')) {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        }

        Setting::set('store_name', $validated['store_name']);
        Setting::set('store_email', $validated['store_email']);
        Setting::set('store_address', $validated['store_address']);
        Setting::set('whatsapp_number', $cleanPhone);
        Setting::set('whatsapp_message', $validated['whatsapp_message']);

        // Handle Logo Upload
        if ($request->hasFile('store_logo')) {
            $oldLogo = Setting::get('store_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }

            $path = $request->file('store_logo')->store('settings', 'public');
            Setting::set('store_logo', $path);
        }

        return redirect()->route('admin.settings.index')->with('success', 'Pengaturan toko, kontak & logo Vyora berhasil disimpan!');
    }
}
