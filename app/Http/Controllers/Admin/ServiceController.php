<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->keyword ?? '';
        $query = Service::query();
        $query->when($keyword, function($query) use ($keyword) {
            $query->where('title', 'like', '%'.$keyword.'%');
        });

        $data = $query->latest('id')->paginate(25);

        return view('admin.service.index', compact('data'));
    }

    public function create(Request $request)
    {

        return view('admin.service.create');
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'category_id' => 'required|integer|min:1',
            'subcategory_id' => 'nullable|integer|min:1',
            'title' => 'required|string|max:255',
            'short_desc' => 'nullable|string|min:1|max:100',
            'long_desc' => 'required|string|min:1',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp,gif,svg|max:1000',
            'page_title' => 'nullable|string|min:1',
            'meta_title' => 'nullable|string|min:1',
            'meta_desc' => 'nullable|string|min:1',
            'meta_keyword' => 'nullable|string|min:1'
        ], [
            'image.max' => 'The icon must not be greater than 1MB.',
        ]);

        $service = new Service();
        $service->category_id = $request->category_id;
        $service->subcategory_id = $request->subcategory_id ?? null;
        $service->title = $request->title;
        $service->slug = slugGenerate($request->title, 'services');
        $service->short_desc = $request->short_desc ?? '';
        $service->long_desc = $request->long_desc;

        $service->page_title = $request->page_title ?? '';
        $service->meta_title = $request->meta_title ?? '';
        $service->meta_desc = $request->meta_desc ?? '';
        $service->meta_keyword = $request->meta_keyword ?? '';

        // image upload
        if (isset($request->image)) {
            $fileUpload1 = fileUpload($request->image, 'service');

            $service->image_small = $fileUpload1['file'][0];
            $service->image_medium = $fileUpload1['file'][1];
            $service->image_large = $fileUpload1['file'][2];
        }

        $service->save();

        return redirect()->route('admin.service.list.all')->with('success', 'New Service created');
    }

    public function detail(Request $request, $id)
    {
        $data = Service::findOrFail($id);
        return view('admin.service.detail', compact('data'));
    }

    public function edit(Request $request, $id)
    {
        $data = Service::findOrFail($id);
        $categories = ServiceCategory::where('status', 1)->orderBy('title')->get();
        $subcategories = ServiceSubCategory::where('status', 1)->orderBy('title')->get();

        return view('admin.service.edit', compact('data', 'categories', 'subcategories'));
    }

    public function update(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'category_id' => 'required|integer|min:1',
            'subcategory_id' => 'nullable|integer|min:1',
            'id' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'short_desc' => 'nullable|string|min:1|max:100',
            'long_desc' => 'required|string|min:1',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif,svg|max:1000',
            'page_title' => 'nullable|string|min:1',
            'meta_title' => 'nullable|string|min:1',
            'meta_desc' => 'nullable|string|min:1',
            'meta_keyword' => 'nullable|string|min:1'
        ], [
            'image.max' => 'The icon must not be greater than 1MB.',
        ]);

        $service = Service::findOrFail($request->id);
        if ($service->title != $request->title) {
            $service->slug = slugGenerate($request->title, 'services');
        }
        $service->title = $request->title;
        $service->category_id = $request->category_id;
        $service->subcategory_id = $request->subcategory_id ?? null;
        $service->short_desc = $request->short_desc ?? '';
        $service->long_desc = $request->long_desc;

        $service->page_title = $request->page_title ?? '';
        $service->meta_title = $request->meta_title ?? '';
        $service->meta_desc = $request->meta_desc ?? '';
        $service->meta_keyword = $request->meta_keyword ?? '';

        // image upload
        if (isset($request->image)) {
            $fileUpload1 = fileUpload($request->image, 'service');

            $service->image_small = $fileUpload1['file'][0];
            $service->image_medium = $fileUpload1['file'][1];
            $service->image_large = $fileUpload1['file'][2];
        }

        $service->save();

        return redirect()->route('admin.service.list.all')->with('success', 'Service updated');
    }

    public function delete(Request $request, $id)
    {
        $data = Service::findOrFail($id);
        $data->delete();

        return redirect()->route('admin.service.list.all')->with('success', 'Service deleted');
    }

    public function status(Request $request, $id)
    {
        $data = Service::findOrFail($id);
        $data->status = ($data->status == 1) ? 0 : 1;
        $data->update();

        return response()->json([
            'status' => 200,
            'message' => 'Status updated',
        ]);
    }
}
