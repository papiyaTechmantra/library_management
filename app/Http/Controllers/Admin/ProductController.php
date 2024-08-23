<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductKeyFeature;
use App\Models\ProductBoxItem;
use App\Models\ProductManual;
use App\Models\ProductDatasheet;
use App\Models\ProductImage;
use App\Models\ProductService;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $activeCategories = Category::where('status', 1)->orderBy('title')->get();
        $status = $request->status ?? '';
        $category = $request->category ?? '';
        $keyword = $request->keyword ?? '';
        $query = Product::query();
        $query->when($keyword, function($query) use ($keyword) {
            $query->where('title', 'like', '%'.$keyword.'%');
        });
        $query->when($category, function($query) use ($category) {
            $query->where('category_id', $category);
        });

        $data = $query->latest('id')->paginate(25);
        // active products only
        $activeProducts = Product::where('status', 1)->orderBy('title')->get();

        return view('admin.product.index', compact('data', 'activeCategories', 'activeProducts'));
    }

    public function create(Request $request)
    {
        $activeCategories = Category::where('status', 1)->orderBy('title')->get();
        return view('admin.product.create', compact('activeCategories'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'images' => 'required',
            'images.*' => 'required|image|mimes:jpg,jpeg,png,webp,gif,svg|max:1000',
            'category_id' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|min:10|max:1000',
            
            // 'page_title' => 'nullable|string|min:1',
            // 'meta_title' => 'nullable|string|min:1',
            // 'meta_desc' => 'nullable|string|min:1',
            // 'meta_keyword' => 'nullable|string|min:1'
        ]);

        DB::beginTransaction();

        try {
            $product = new Product();
            $product->title = $request->title;
            $product->slug = slugGenerate($request->title, 'products');
            $product->category_id = $request->category_id;
            $product->description = $request->description ?? '';

            // $product->page_title = $request->page_title ?? '';
            // $product->meta_title = $request->meta_title ?? '';
            // $product->meta_desc = $request->meta_desc ?? '';
            // $product->meta_keyword = $request->meta_keyword ?? '';

            // images
            if(isset($request->images)){
                $fileUpload = fileUpload($request->images, 'products');
                $product->image = $fileUpload['file'][2];
            }
            $product->save();
            if($product){
                foreach ($request->service_title as $key => $value) {
                    $ProductService = new ProductService;
                    $ProductService->product_id = $product->id;
                    $ProductService->title = $value;
                    $ProductService->description = $request->service_desc[$key];
                    $ProductService->save();
                }
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }

        return redirect()->route('admin.product.list.all')->with('success', 'New product created');
    }

    public function detail(Request $request, $id)
    {
        $data = Product::findOrFail($id);

        return view('admin.product.detail', compact('data'));
    }

    public function edit(Request $request, $id)
    {
        $data = Product::findOrFail($id);
        $activeCategories = Category::where('status', 1)->orderBy('title')->get();
        $ProductService = ProductService::where('product_id', $id)->get();

        return view('admin.product.edit', compact('data', 'activeCategories', 'ProductService'));
    }
    public function ServiceDelete(Request $request){
        $ProductService = ProductService::findOrFail($request->id);
        $ProductService->delete();
        if($ProductService){
            return response()->json(['status'=>200]);
        }else{
            return response()->json(['status'=>400]);
        }
    }

    public function update(Request $request)
    {
        // dd($request->all());
       $request->validate([
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif,svg|max:1000',
            'category_id' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|min:10|max:1000',
            
            // 'page_title' => 'nullable|string|min:1',
            // 'meta_title' => 'nullable|string|min:1',
            // 'meta_desc' => 'nullable|string|min:1',
            // 'meta_keyword' => 'nullable|string|min:1'
        ]);

        DB::beginTransaction();

        try {
            $product = Product::findOrFail($request->id);
            $product->title = $request->title;
            $product->slug = slugGenerateUpdate($request->title, 'products', $product->id);
            $product->category_id = $request->category_id;
            $product->description = $request->description ?? '';

            // $product->page_title = $request->page_title ?? '';
            // $product->meta_title = $request->meta_title ?? '';
            // $product->meta_desc = $request->meta_desc ?? '';
            // $product->meta_keyword = $request->meta_keyword ?? '';

            // images
            if(isset($request->images)){
                $fileUpload = fileUpload($request->images, 'products');
                $product->image = $fileUpload['file'][2];
            }
            $product->save();
            if($product){
                if($request->service_id){
                    foreach ($request->service_id as $key => $value) {
                        $ProductService = ProductService::findOrFail($value);
                        $ProductService->title = $request->old_service_title[$key];
                        $ProductService->description = $request->old_service_desc[$key];
                        $ProductService->save();
                    }
                }
                if($request->service_title){
                    foreach ($request->service_title as $key => $value) {
                        $ProductService = new ProductService;
                        $ProductService->product_id = $product->id;
                        $ProductService->title = $value;
                        $ProductService->description = $request->service_desc[$key];
                        $ProductService->save();
                    }
                }
                
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }

        return redirect()->back()->with('success', 'product updated');
    }

    public function delete(Request $request, $id)
    {
        $data = Product::findOrFail($id);
        $data->delete();
        return redirect()->route('admin.product.list.all')->with('success', 'Product deleted');
    }

    public function status(Request $request, $id)
    {
        $data = Product::findOrFail($id);
        $data->status = $data->status==1?0:1;
        $data->update();

        return response()->json([
            'status' => 200,
            'message' => 'Status updated',
        ]);
    }


    // Product Category
    public function ProductIndex(Request $request)
    {
        $keyword = $request->keyword ?? '';
        $query = ProductCategory::query();

        $query->when($keyword, function($query) use ($keyword) {
            $query->where('title', 'like', '%'.$keyword.'%');
        });

        $data = $query->latest('id')->paginate(25);

        return view('admin.products-category.index', compact('data'));
    }

    public function ProductCreate(Request $request)
    {
        return view('admin.products-category.create');
    }

    public function ProductStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $ProductCategory = new ProductCategory();
        $ProductCategory->title = ucwords($request->name);
        $ProductCategory->save();
        return redirect()->route('admin.product_category.list.all')->with('success', 'Product category created');
    }

    public function ProductEdit(Request $request, $id)
    {
        $data = ProductCategory::findOrFail($id);
        return view('admin.products-category.edit', compact('data'));
    }

    public function ProductUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $ProductCategory = ProductCategory::findOrFail($request->id);
        $ProductCategory->title = $request->name;

        $ProductCategory->save();
        return redirect()->route('admin.product_category.list.all')->with('success', 'Product Category updated');
    }

    public function ProductDelete(Request $request, $id)
    {
        $data = ProductCategory::findOrFail($id);
        $data->delete();

        return redirect()->route('admin.product_category.list.all')->with('success', 'Product Category deleted');
    }
    public function ProductStatus(Request $request, $id)
    {
        $data = ProductCategory::findOrFail($id);
        $data->status = ($data->status == 1) ? 0 : 1;
        $data->update();
        return response()->json([
            'status' => 200,
            'message' => 'Status updated',
        ]);
    }
}
