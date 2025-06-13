<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

    class Order extends Model
    {
        use HasFactory;

        // Definisikan atribut yang dapat diisi
        protected $fillable = [
            'user_id',
            'product_id',
            'quantity',
            'total_price',
            'status', // add status to fillable
        ];

        // Relasi dengan User
        public function user()
        {
            return $this->belongsTo(User::class);
        }

        // Relasi dengan Product
        public function product()
        {
            return $this->belongsTo(Product::class);
        }

        // Metode untuk menghitung total harga
        public function calculateTotalPrice()
        {
            $product = $this->product()->first();
            if ($product) {
                $this->total_price = $product->price * $this->quantity;
                $this->save();
            }
        }

    }
