<?php

namespace App\GraphQL\Resolvers;
use App\Models\Product;

class ProductResolver
{
    public function reduceStock($_, array $args) {
        $product = Product::findorFail($args['id']);

        if ($product->stock < $args['qty']) {
            throw new \Exception('Stok tidak mencukupi');
        }
        // Kurangi stok produk
        $product->stock -= $args['qty'];
        $product->save();

        return $product;
    }
}
