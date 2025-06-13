<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReviewController;

// Route untuk menampilkan semua review
Route::get('/reviews', [ReviewController::class, 'index']);

// Route untuk menambahkan review (POST dari Postman)
Route::post('/reviews', [ReviewController::class, 'store']);
