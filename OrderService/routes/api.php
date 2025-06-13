<?php

use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('orders', [OrderController::class, 'store']);  // Consumer: Membuat order (ambil data user dan produk)
Route::get('orders', [OrderController::class, 'index']);   // Provider: Menampilkan semua order
Route::get('orders/{id}', [OrderController::class, 'show']); // Provide : Menampilkan Order by ID
Route::put('orders/{id}', [OrderController::class, 'update']); // Update order
Route::delete('orders/{id}', [OrderController::class, 'destroy']); // Delete order
