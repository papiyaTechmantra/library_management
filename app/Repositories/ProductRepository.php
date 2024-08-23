<?php

namespace App\Repositories;

use App\Interfaces\ProductInterface;
use App\Models\Product;

class ProductRepository implements ProductInterface
{
    public function listAll()
    {
        return Product::orderBy('id', 'desc')->paginate(25);
    }

    public function listActive()
    {
        return Product::where('status', 1)->orderBy('id', 'desc')->get();
    }

    public function listInactive()
    {
        return Product::where('status', 0)->orderBy('id', 'desc')->get();
    }

    public function findById($id)
    {
        return Product::findOrFail($id);
    }

}
