<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class FinancialController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('transaction_code', 'like', "%{$search}%")
                  ->orWhere('product_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('from_date')) {
            $query->whereDate('transaction_date', '>=', $request->input('from_date'));
        }

        if ($request->filled('to_date')) {
            $query->whereDate('transaction_date', '<=', $request->input('to_date'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Summary Calculations
        $totalRevenue = (clone $query)->where('status', 'Lunas')->sum('total_amount');
        $totalUnpaid = (clone $query)->where('status', 'Belum Bayar')->sum('total_amount');
        $countUnpaid = (clone $query)->where('status', 'Belum Bayar')->count();
        $totalItemsSold = (clone $query)->where('status', 'Lunas')->sum('quantity');
        $totalTransactions = (clone $query)->count();

        $transactions = $query->latest('transaction_date')->latest('id')->paginate(50)->withQueryString();

        return view('admin.financial.index', compact(
            'transactions',
            'totalRevenue',
            'totalUnpaid',
            'countUnpaid',
            'totalItemsSold',
            'totalTransactions'
        ));
    }

    public function create()
    {
        $products = Product::where('is_active', true)->get();
        return view('admin.financial.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:20'],
            'product_name' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:1'],
            'price_per_unit' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['required', 'string', 'max:100'],
            'status' => ['required', 'string', 'in:Lunas,Belum Bayar,Pending,Batal'],
            'transaction_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $validated['transaction_code'] = 'TRX-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 5));
        $validated['total_amount'] = $validated['quantity'] * $validated['price_per_unit'];

        Transaction::create($validated);

        return redirect()->route('admin.financial.index')->with('success', 'Catatan pembukuan transaksi berhasil ditambahkan!');
    }

    public function edit(Transaction $financial)
    {
        return view('admin.financial.edit', ['transaction' => $financial]);
    }

    public function update(Request $request, Transaction $financial)
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:20'],
            'product_name' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:1'],
            'price_per_unit' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['required', 'string', 'max:100'],
            'status' => ['required', 'string', 'in:Lunas,Belum Bayar,Pending,Batal'],
            'transaction_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $validated['total_amount'] = $validated['quantity'] * $validated['price_per_unit'];

        $financial->update($validated);

        return redirect()->route('admin.financial.index')->with('success', 'Catatan transaksi berhasil diperbarui!');
    }

    public function updateStatus(Request $request, Transaction $financial)
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:Lunas,Belum Bayar,Pending,Batal'],
        ]);

        $financial->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', "Status transaksi {$financial->transaction_code} berhasil diubah menjadi {$financial->status}.");
    }

    public function destroy(Transaction $financial)
    {
        $financial->delete();
        return redirect()->route('admin.financial.index')->with('success', 'Catatan transaksi berhasil dihapus.');
    }

    public function downloadReport(Request $request)
    {
        $query = Transaction::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('transaction_code', 'like', "%{$search}%")
                  ->orWhere('product_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('from_date')) {
            $query->whereDate('transaction_date', '>=', $request->input('from_date'));
        }
        if ($request->filled('to_date')) {
            $query->whereDate('transaction_date', '<=', $request->input('to_date'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $records = $query->latest('transaction_date')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Keuangan');

        // Header Styling
        $sheet->setCellValue('A1', 'LAPORAN PEMBUKUAN KEUANGAN & PENJUALAN VYORA');
        $sheet->mergeCells('A1:J1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A2', 'Tanggal Ekspor: ' . date('d F Y H:i:s'));
        $sheet->mergeCells('A2:J2');
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(10);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Column Headers
        $headers = [
            'A4' => 'Kode Transaksi',
            'B4' => 'Tanggal',
            'C4' => 'Nama Pelanggan',
            'D4' => 'Nomor WhatsApp',
            'E4' => 'Nama Produk',
            'F4' => 'Jumlah (Qty)',
            'G4' => 'Harga Satuan (Rp)',
            'H4' => 'Total Bayar (Rp)',
            'I4' => 'Metode Pembayaran',
            'J4' => 'Status Pembayaran'
        ];

        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
        }

        $headerRange = 'A4:J4';
        $sheet->getStyle($headerRange)->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFF'));
        $sheet->getStyle($headerRange)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('2563EB'); // Blue header
        $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Populate Data
        $rowNumber = 5;
        foreach ($records as $row) {
            $sheet->setCellValue("A{$rowNumber}", $row->transaction_code);
            $sheet->setCellValue("B{$rowNumber}", $row->transaction_date->format('Y-m-d'));
            $sheet->setCellValue("C{$rowNumber}", $row->customer_name);
            $sheet->setCellValue("D{$rowNumber}", $row->customer_phone ?? '-');
            $sheet->setCellValue("E{$rowNumber}", $row->product_name);
            $sheet->setCellValue("F{$rowNumber}", $row->quantity);
            $sheet->setCellValue("G{$rowNumber}", $row->price_per_unit);
            $sheet->setCellValue("H{$rowNumber}", $row->total_amount);
            $sheet->setCellValue("I{$rowNumber}", $row->payment_method);
            $sheet->setCellValue("J{$rowNumber}", $row->status);

            // Format numbers
            $sheet->getStyle("G{$rowNumber}:H{$rowNumber}")->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle("A{$rowNumber}:B{$rowNumber}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("F{$rowNumber}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("J{$rowNumber}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $rowNumber++;
        }

        // Summary Row
        $summaryRow = $rowNumber + 1;
        $sheet->setCellValue("A{$summaryRow}", 'TOTAL LUNAS:');
        $sheet->mergeCells("A{$summaryRow}:G{$summaryRow}");
        $sheet->setCellValue("H{$summaryRow}", "=SUMIF(J5:J" . ($rowNumber - 1) . ', "Lunas", H5:H' . ($rowNumber - 1) . ')');
        $sheet->getStyle("A{$summaryRow}:H{$summaryRow}")->getFont()->setBold(true);
        $sheet->getStyle("H{$summaryRow}")->getNumberFormat()->setFormatCode('#,##0');

        // Auto-fit Columns
        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'Laporan_Pembukuan_Keuangan_Vyora_' . date('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
