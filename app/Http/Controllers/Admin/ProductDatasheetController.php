<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Product;
use App\Models\ProductDatasheet;

class ProductDatasheetController extends Controller
{
    public function delete(Request $request, $id)
    {
        $data = ProductDatasheet::findOrFail($id);
        $data->delete();

        return redirect()->back()->with('success', 'Datasheet deleted');

        // return response()->json([
        //     'status' => 200,
        //     'message' => 'Image deleted',
        // ]);
    }
}
