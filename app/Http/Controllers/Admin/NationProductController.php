<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\NationProduct;

class NationProductController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->keyword ?? '';

        $query = NationProduct::query();

        $query->when($keyword, function($query) use ($keyword) {
            $query->where('name', 'like', '%'.$keyword.'%')
            ->orWhere('title', 'like', '%'.$keyword.'%');
        });

        $data = $query->latest('id')->paginate(10);

        return view('admin.nation-product.index', compact('data'));
    }

    public function create(Request $request)
    {
        return view('admin.nation-product.create');
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|min:1',
            'production_volume' => 'required|string|min:1',
            'product_portfolio' => 'required|string|min:1',
            'application_desc' => 'nullable|string',
        ]);

        $NationProduct = new NationProduct();
        $NationProduct->name = $request->name;
        $NationProduct->title = $request->title;
        $NationProduct->long_desc = $request->description ?? '';
        $NationProduct->production_volume = $request->production_volume ?? '';
        $NationProduct->application_desc = $request->application_desc ?? '';
        $NationProduct->product_portfolio = $request->product_portfolio ?? '';
        $NationProduct->save();

        return redirect()->route('admin.nation_product.list.all')->with('success', 'New Product created');
    }

    public function detail(Request $request, $id)
    {
        $data = NationProduct::findOrFail($id);
        return view('admin.nation-product.detail', compact('data'));
    }

    public function edit(Request $request, $id)
    {
        $data = NationProduct::findOrFail($id);
        return view('admin.nation-product.edit', compact('data'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|min:1',
            'production_volume' => 'required|string|min:1',
            'product_portfolio' => 'required|string|min:1',
            'application_desc' => 'nullable|string',
        ]);

        $NationProduct = NationProduct::findOrFail($request->id);
        $NationProduct->name = $request->name;
        $NationProduct->title = $request->title;
        $NationProduct->long_desc = $request->description ?? '';
        $NationProduct->production_volume = $request->production_volume ?? '';
        $NationProduct->application_desc = $request->application_desc ?? '';
        $NationProduct->product_portfolio = $request->product_portfolio ?? '';
        $NationProduct->save();

        return redirect()->route('admin.nation_product.list.all')->with('success', 'Product updated');
    }

    public function delete(Request $request, $id)
    {
        $data = NationProduct::findOrFail($id);
        $data->delete();

        return redirect()->route('admin.nation_product.list.all')->with('success', 'Product deleted');
    }

    public function status(Request $request, $id)
    {
        $data = NationProduct::findOrFail($id);
        $data->status = ($data->status == 1) ? 0 : 1;
        $data->update();

        return response()->json([
            'status' => 200,
            'message' => 'Status updated',
        ]);
    }
}

