<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'user_id'    => 'required|integer',
            'product_id' => 'required|integer',
            'quantity'   => 'required|integer|min:1',
        ]);

        // ========== CEK PRODUK DAN STOK DARI PRODUCT-SERVICE (GraphQL) ==========
        $productServiceGraphQL = env('PRODUCT_SERVICE_URL', 'http://localhost:8002') . '/graphql';

        // Query produk dari product-service
        $query = <<<GQL
        query {
            product(id: "{$validated['product_id']}") {
                id
                name
                price
                stock
            }
        }
        GQL;

        $productResponse = Http::post($productServiceGraphQL, [
            'query' => $query
        ]);

        Log::info('Product service response body:', $productResponse->json());
        Log::info('Product service response status:', ['status' => $productResponse->status()]);

        if ($productResponse->failed() || isset($productResponse['errors'])) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data produk dari product-service.',
            ], 502);
        }

        $product = $productResponse->json('data.product');

        if (!$product || $product['stock'] < $validated['quantity']) {
            return response()->json([
                'success' => false,
                'message' => 'Stok produk tidak mencukupi.',
            ], 400);
        }

        // ========== BUAT ORDER ==========
        $validated['status'] = 'pending';
        $validated['total_price'] = $product['price'] * $validated['quantity'];

        $order = Order::create($validated);

        // ========== MENGURANGI STOK PRODUK MELALUI MUTATION ==========
        $mutation = <<<GQL
        mutation {
            reduceStock(id: "{$validated['product_id']}", qty: {$validated['quantity']}) {
                id
                stock
            }
        }
        GQL;

        $mutationResponse = Http::post($productServiceGraphQL, [
            'query' => $mutation
        ]);

        if ($mutationResponse->failed() || isset($mutationResponse['errors'])) {
            $order->delete(); // rollback
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengurangi stok produk. Order dibatalkan.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data'    => $order,
            'message' => 'Order created successfully',
        ], 201);

    } catch (\Exception $e) {
        Log::error('Failed to create order: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Failed to create order',
        ], 500);
    }
}

    public function index()
    {
        $orders = Order::all();

        return response()->json([
            'success' => true,
            'data'    => $orders,
            'message' => 'List of orders',
        ]);
    }

    public function show($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $order,
            'message' => 'Order retrieved successfully',
        ]);
    }

    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
            ], 404);
        }
        $validated = $request->validate([
            'quantity' => 'sometimes|integer|min:1',
            'status'   => 'sometimes|string',
        ]);
        if (isset($validated['quantity'])) {
            $order->quantity = $validated['quantity'];
            $order->total_price = 0; // dummy karena tidak relasi
        }
        if (isset($validated['status'])) {
            $order->status = $validated['status'];
        }
        $order->save();
        return response()->json([
            'success' => true,
            'data'    => $order,
            'message' => 'Order updated successfully',
        ]);
    }

    public function destroy($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
            ], 404);
        }
        $order->delete();
        return response()->json([
            'success' => true,
            'message' => 'Order deleted successfully',
        ]);
    }
}
