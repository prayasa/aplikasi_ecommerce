<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // <-- 1. Impor DB facade
use Illuminate\Support\Str;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Eager load relasi customer untuk efisiensi query
        $orders = Order::with('customer')->orderBy('order_date', 'desc')->get();
        return response()->json($orders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,customer_id',
            'payment_method' => 'required|string|in:Tunai,Transfer Bank',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,product_id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // ======================================================================
        // PERBAIKAN UTAMA: Gunakan Database Transaction
        // ======================================================================
        try {
            $order = DB::transaction(function () use ($validated) {
                $totalAmount = 0;

                // 2. Validasi stok dan hitung total harga SEBELUM membuat pesanan
                foreach ($validated['items'] as $item) {
                    $product = Product::find($item['product_id']);

                    // Jika produk tidak ditemukan atau stok tidak cukup, lempar error
                    if (!$product || $product->stock < $item['quantity']) {
                        throw new \Exception('Stok untuk produk ' . ($product->name ?? 'yang dipilih') . ' tidak mencukupi.');
                    }
                    $totalAmount += $product->price * $item['quantity'];
                }

                // 3. Buat pesanan baru
                $order = Order::create([
                    'order_id' => (string) Str::uuid(),
                    'customer_id' => $validated['customer_id'],
                    'order_date' => now(),
                    'total_amount' => $totalAmount,
                    'payment_method' => $validated['payment_method'],
                    'status' => 'pending', // Status awal
                ]);

                // 4. Buat item pesanan dan kurangi stok produk
                foreach ($validated['items'] as $item) {
                    $product = Product::find($item['product_id']); // Ambil produk lagi di dalam transaksi

                    OrderItem::create([
                        'id' => (string) Str::uuid(), // Sesuaikan dengan primary key Anda
                        'order_id' => $order->order_id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $product->price,
                    ]);

                    // LOGIKA PENGURANGAN STOK
                    $product->decrement('stock', $item['quantity']);
                }

                return $order;
            });

            return response()->json($order->load('items'), 201);

        } catch (\Exception $e) {
            // Jika terjadi error di dalam transaksi, kirim respons error
            return response()->json(['message' => $e->getMessage()], 422); // 422 Unprocessable Entity
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        // Eager load relasi untuk detail
        return response()->json($order->load(['customer', 'items.product']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:pending,completed,cancelled',
        ]);

        $order->update($validated);
        return response()->json($order);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        // Logika untuk mengembalikan stok jika pesanan dihapus bisa ditambahkan di sini
        // Untuk saat ini, kita hanya hapus pesanan dan itemnya
        DB::transaction(function () use ($order) {
            // Optional: Kembalikan stok
            foreach ($order->items as $item) {
                Product::find($item->product_id)->increment('stock', $item->quantity);
            }
            $order->items()->delete();
            $order->delete();
        });

        return response()->json(null, 204);
    }
}
