<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Product;
use App\Models\ProductImage;

class ProductImageController extends Controller
{
    public function delete(Request $request, $id)
    {
        $data = ProductImage::findOrFail($id);
        $data->delete();

        return redirect()->back()->with('success', 'Image deleted');

        // return response()->json([
        //     'status' => 200,
        //     'message' => 'Image deleted',
        // ]);
    }
}
