<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Certificate;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->keyword ?? '';
        $query = Certificate::query();

        $query->when($keyword, function($query) use ($keyword) {
            $query->where('title', 'like', '%'.$keyword.'%');
        });

        $data = $query->latest('id')->paginate(25);

        return view('admin.certificate.index', compact('data'));
    }

    public function create(Request $request)
    {
        return view('admin.certificate.create');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp,gif,svg|max:1000',
        ], [
            'image.max' => 'The image must not be greater than 1MB.',
        ]);

        $Partner = new Certificate();
        $Partner->title = ucwords($request->title);

        // image upload
        if (isset($request->image)) {
            $fileUpload = fileUpload($request->image, 'certificate');
            $Partner->image = $fileUpload['file'][0];
        }

        $Partner->save();
        return redirect()->route('admin.certificate.list.all')->with('success', 'New certificate created');
    }


    public function edit(Request $request, $id)
    {
        $data = Certificate::findOrFail($id);
        return view('admin.certificate.edit', compact('data'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif,svg|max:1000',
        ], [
            'image.max' => 'The image must not be greater than 1MB.',
        ]);

        $Partner = Certificate::findOrFail($request->id);
        $Partner->title = $request->title;

        // image upload
        if (isset($request->image)) {
            $fileUpload = fileUpload($request->image, 'partner');
            $Partner->image = $fileUpload['file'][0];
        }
        $Partner->save();
        return redirect()->route('admin.certificate.list.all')->with('success', 'Certificate updated');
    }

    public function delete(Request $request, $id)
    {
        $data = Certificate::findOrFail($id);
        $data->delete();

        return redirect()->route('admin.certificate.list.all')->with('success', 'Certificate deleted');
    }
}
