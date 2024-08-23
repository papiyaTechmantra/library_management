<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Product;
use App\Models\ProductManual;

class ProductManualController extends Controller
{
    public function delete(Request $request, $id)
    {
        $data = ProductManual::findOrFail($id);
        $data->delete();

        return redirect()->back()->with('success', 'Manual deleted');

        // return response()->json([
        //     'status' => 200,
        //     'message' => 'Image deleted',
        // ]);
    }
}
