<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Banner;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->status ?? '';
        $category = $request->category ?? '';
        $keyword = $request->keyword ?? '';

        $query = Banner::query();

        $query->when($keyword, function($query) use ($keyword) {
            $query->where('title1', 'like', '%'.$keyword.'%')
            ->orWhere('title2', 'like', '%'.$keyword.'%');
        });
        $query->when($status, function($query) use ($status) {
            $query->where('status', $status);
        });

        $data = $query->latest('id')->paginate(25);

        return view('admin.banner.index', compact('data'));
    }

    public function create(Request $request)
    {
        return view('admin.banner.create');
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'title1' => 'required|string|max:25',
            'title2' => 'required|string|max:25',
            'description' => 'nullable|string|min:1|max:70',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp,gif,svg|max:1000',
            'btn1_text' => 'nullable|string|max:25',
            'btn1_link' => 'nullable|string',
            'btn2_text' => 'nullable|string|max:25',
            'btn2_link' => 'nullable|string',
        ], [
            'image.max' => 'The image must not be greater than 1MB.',
        ]);

        $banner = new Banner();
        $banner->title1 = $request->title1;
        $banner->title2 = $request->title2;
        $banner->description = $request->description ?? '';
        $banner->btn1_text = $request->btn1_text ?? '';
        $banner->btn1_link = $request->btn1_link ?? '';
        $banner->btn2_text = $request->btn2_text ?? '';
        $banner->btn2_link = $request->btn2_link ?? '';

        // image upload
        if (isset($request->image)) {
            $fileUpload = fileUpload($request->image, 'banner');

            $banner->image_small = $fileUpload['file'][0];
            $banner->image_medium = $fileUpload['file'][1];
            $banner->image_large = $fileUpload['file'][2];
        }

        $banner->save();

        return redirect()->route('admin.content.banner.list.all')->with('success', 'New banner created');
    }

    public function detail(Request $request, $id)
    {
        $data = Banner::findOrFail($id);
        return view('admin.banner.detail', compact('data'));
    }

    public function edit(Request $request, $id)
    {
        $data = Banner::findOrFail($id);
        return view('admin.banner.edit', compact('data'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'title1' => 'required|string|max:25',
            'title2' => 'required|string|max:25',
            'description' => 'nullable|string|min:1|max:70',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif,svg|max:1000',
            'btn1_text' => 'nullable|string|max:25',
            'btn1_link' => 'nullable|string',
            'btn2_text' => 'nullable|string|max:25',
            'btn2_link' => 'nullable|string',
        ], [
            'image.max' => 'The image must not be greater than 1MB.',
        ]);

        $banner = Banner::findOrFail($request->id);
        $banner->title1 = $request->title1;
        $banner->title2 = $request->title2;
        $banner->description = $request->description ?? '';
        $banner->btn1_text = $request->btn1_text ?? '';
        $banner->btn1_link = $request->btn1_link ?? '';
        $banner->btn2_text = $request->btn2_text ?? '';
        $banner->btn2_link = $request->btn2_link ?? '';

        // image upload
        if (isset($request->image)) {
            $fileUpload = fileUpload($request->image, 'banner');

            $banner->image_small = $fileUpload['file'][0];
            $banner->image_medium = $fileUpload['file'][1];
            $banner->image_large = $fileUpload['file'][2];
        }

        $banner->save();

        return redirect()->route('admin.content.banner.list.all')->with('success', 'Banner updated');
    }

    public function delete(Request $request, $id)
    {
        $data = Banner::findOrFail($id);
        $data->delete();

        return redirect()->route('admin.content.banner.list.all')->with('success', 'Banner deleted');
    }

    public function status(Request $request, $id)
    {
        $data = Banner::findOrFail($id);
        $data->status = ($data->status == 1) ? 0 : 1;
        $data->update();

        return response()->json([
            'status' => 200,
            'message' => 'Status updated',
        ]);
    }
}
