<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Partner;

class PartnerController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->keyword ?? '';
        $query = Partner::query();

        $query->when($keyword, function($query) use ($keyword) {
            $query->where('name', 'like', '%'.$keyword.'%');
        });

        $data = $query->latest('id')->paginate(25);

        return view('admin.Partner.index', compact('data'));
    }

    public function create(Request $request)
    {
        return view('admin.Partner.create');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp,gif,svg|max:1000',
        ], [
            'image.max' => 'The image must not be greater than 1MB.',
        ]);

        $Partner = new Partner();
        $Partner->name = ucwords($request->name);

        // image upload
        if (isset($request->image)) {
            $fileUpload = fileUpload($request->image, 'partner');
            $Partner->image = $fileUpload['file'][0];
        }

        $Partner->save();
        return redirect()->route('admin.partner.list.all')->with('success', 'New client created');
    }


    public function edit(Request $request, $id)
    {
        $data = Partner::findOrFail($id);
        return view('admin.Partner.edit', compact('data'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif,svg|max:1000',
        ], [
            'image.max' => 'The image must not be greater than 1MB.',
        ]);

        $Partner = Partner::findOrFail($request->id);
        $Partner->name = $request->name;

        // image upload
        if (isset($request->image)) {
            $fileUpload = fileUpload($request->image, 'partner');
            $Partner->image = $fileUpload['file'][0];
        }
        $Partner->save();
        return redirect()->route('admin.partner.list.all')->with('success', 'client updated');
    }

    public function delete(Request $request, $id)
    {
        $data = Partner::findOrFail($id);
        $data->delete();
        return redirect()->route('admin.partner.list.all')->with('success', 'client deleted');
    }
}
