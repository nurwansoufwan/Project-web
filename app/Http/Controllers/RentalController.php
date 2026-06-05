<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Customer;
use App\Models\Rental;
use App\Models\RentalDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RentalController extends Controller
{
    public function index(Request $request)
    {
        $query = Rental::with('customer');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_code', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($c) use ($search) {
                      $c->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $rentals = $query->latest()->paginate(5)->withQueryString();
        $overdueRentalsList = Rental::with('customer')
            ->where('status', 'active')
            ->where('return_date', '<', now()->toDateString())
            ->get();
        return view('rentals.index', compact('rentals', 'overdueRentalsList'));
    }

    public function create()
    {
        $customers = Customer::all();
        // Only load equipments that have stock > 0
        $equipments = Equipment::where('stock', '>', 0)->get();
        return view('rentals.create', compact('customers', 'equipments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'rental_date' => 'required|date|after_or_equal:today',
            'return_date' => 'required|date|after_or_equal:rental_date',
            'items' => 'required|array|min:1',
            'items.*.equipment_id' => 'required|exists:equipment,id',
            'items.*.quantity' => 'required|integer|min:1',
        ], [
            'customer_id.required' => 'Pelanggan wajib dipilih.',
            'customer_id.exists' => 'Pelanggan tidak valid.',
            'rental_date.required' => 'Tanggal sewa wajib diisi.',
            'rental_date.after_or_equal' => 'Tanggal sewa tidak boleh sebelum hari ini.',
            'return_date.required' => 'Tanggal pengembalian wajib diisi.',
            'return_date.after_or_equal' => 'Tanggal pengembalian harus setelah atau sama dengan tanggal sewa.',
            'items.required' => 'Pilih minimal satu alat camping untuk disewa.',
            'items.min' => 'Pilih minimal satu alat camping untuk disewa.',
            'items.*.equipment_id.required' => 'Alat camping wajib dipilih.',
            'items.*.quantity.required' => 'Jumlah barang wajib diisi.',
            'items.*.quantity.min' => 'Jumlah barang minimal 1.',
        ]);

        // Calculate rental duration in days
        $rentalDate = Carbon::parse($request->rental_date);
        $returnDate = Carbon::parse($request->return_date);
        $days = max(1, $rentalDate->diffInDays($returnDate));

        // Start Database Transaction
        DB::beginTransaction();

        try {
            // Generate Transaction Code (e.g. TR-20260605-8941)
            $transactionCode = 'TR-' . now()->format('Ymd') . '-' . rand(1000, 9999);
            
            // Check uniqueness
            while (Rental::where('transaction_code', $transactionCode)->exists()) {
                $transactionCode = 'TR-' . now()->format('Ymd') . '-' . rand(1000, 9999);
            }

            $totalPrice = 0;
            $rentalDetailsData = [];

            // Loop through selected items and validate stock
            foreach ($request->items as $item) {
                $equipment = Equipment::find($item['equipment_id']);
                
                // Double check stock
                if ($equipment->stock < $item['quantity']) {
                    return back()->withErrors([
                        'items' => "Stok untuk '{$equipment->name}' tidak mencukupi (Tersedia: {$equipment->stock}, Diminta: {$item['quantity']})."
                    ])->withInput();
                }

                $subtotal = $item['quantity'] * $equipment->rental_price_per_day * $days;
                $totalPrice += $subtotal;

                $rentalDetailsData[] = [
                    'equipment_id' => $equipment->id,
                    'quantity' => $item['quantity'],
                    'price_per_day' => $equipment->rental_price_per_day,
                    'subtotal' => $subtotal,
                    'equipment_name' => $equipment->name // helper context
                ];
            }

            // Create Rental Header
            $rental = Rental::create([
                'transaction_code' => $transactionCode,
                'customer_id' => $request->customer_id,
                'rental_date' => $request->rental_date,
                'return_date' => $request->return_date,
                'total_price' => $totalPrice,
                'status' => 'active', // Mark active (rented) straightaway
                'payment_status' => 'unpaid',
            ]);

            // Save details & decrement equipment stocks
            foreach ($rentalDetailsData as $detail) {
                RentalDetail::create([
                    'rental_id' => $rental->id,
                    'equipment_id' => $detail['equipment_id'],
                    'quantity' => $detail['quantity'],
                    'price_per_day' => $detail['price_per_day'],
                    'subtotal' => $detail['subtotal'],
                ]);

                // Decrement stock
                Equipment::where('id', $detail['equipment_id'])->decrement('stock', $detail['quantity']);
            }

            DB::commit();

            return redirect()->route('rentals.show', $rental->id)
                ->with('success', "Transaksi {$transactionCode} berhasil dibuat!");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Rental $rental)
    {
        $rental->load('customer', 'details.equipment');
        
        // Calculate rental days duration
        $days = max(1, $rental->rental_date->diffInDays($rental->return_date));
        
        // Estimate fine if late today (if status is still active)
        $estimatedFine = 0;
        $lateDays = 0;
        if ($rental->status === 'active' && now()->toDateString() > $rental->return_date->toDateString()) {
            $lateDays = now()->diffInDays($rental->return_date);
            $totalQty = $rental->details->sum('quantity');
            $estimatedFine = $lateDays * $totalQty * 15000; // Rp 15,000 / day / item
        }

        return view('rentals.show', compact('rental', 'days', 'estimatedFine', 'lateDays'));
    }

    public function markAsPaid(Rental $rental)
    {
        $rental->update(['payment_status' => 'paid']);
        return redirect()->route('rentals.show', $rental->id)->with('success', 'Pembayaran transaksi telah berhasil dikonfirmasi LUNAS.');
    }

    public function processReturn(Rental $rental)
    {
        if ($rental->status === 'completed') {
            return redirect()->route('rentals.show', $rental->id)->with('error', 'Transaksi ini sudah selesai dikembalikan.');
        }

        $rental->load('details');
        
        $actualReturnDate = now()->toDateString();
        $returnDate = $rental->return_date->toDateString();
        
        $fineAmount = 0;
        $lateDays = 0;

        if ($actualReturnDate > $returnDate) {
            $actualReturn = Carbon::parse($actualReturnDate);
            $expectedReturn = Carbon::parse($returnDate);
            $lateDays = $actualReturn->diffInDays($expectedReturn);
            
            // Calculate total quantity of items rented
            $totalQty = $rental->details->sum('quantity');
            $fineAmount = $lateDays * $totalQty * 15000; // Rp 15,000 per day per item
        }

        DB::beginTransaction();
        try {
            // Restore equipment stocks
            foreach ($rental->details as $detail) {
                Equipment::where('id', $detail->equipment_id)->increment('stock', $detail->quantity);
            }

            // Update rental status
            $rental->update([
                'status' => 'completed',
                'actual_return_date' => $actualReturnDate,
                'fine_amount' => $fineAmount,
            ]);

            DB::commit();

            $msg = 'Pengembalian alat camping berhasil diproses.';
            if ($fineAmount > 0) {
                $msg .= " Pelanggan terlambat {$lateDays} hari, dikenakan denda Rp " . number_format($fineAmount, 0, ',', '.') . ".";
            } else {
                $msg .= " Alat camping dikembalikan tepat waktu.";
            }

            return redirect()->route('rentals.show', $rental->id)->with('success', $msg);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('rentals.show', $rental->id)->with('error', 'Gagal memproses pengembalian: ' . $e->getMessage());
        }
    }
}
