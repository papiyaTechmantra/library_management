<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SocialMedia;

class SocialMediaController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->keyword ?? '';

        $query = SocialMedia::query();

        $query->when($keyword, function($query) use ($keyword) {
            $query->where('title', 'like', '%'.$keyword.'%');
        });

        $data = $query->latest('id')->paginate(25);

        return view('admin.social.index', compact('data'));
    }

    public function create(Request $request)
    {
        return view('admin.social.create');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp,gif,svg|max:1000',
            'social_link' => 'required|string',
        ], [
            'image.max' => 'The image must not be greater than 1MB.',
        ]);

        $social = new SocialMedia();
        $social->title = ucwords($request->title);
        $social->link = $request->social_link;

        // image upload
        if (isset($request->image)) {
            $fileUpload = fileUpload($request->image, 'social');
            $social->image = $fileUpload['file'][0];
        }

        $social->save();
        return redirect()->route('admin.social_media.list.all')->with('success', 'New Social media created');
    }


    public function edit(Request $request, $id)
    {
        $data = SocialMedia::findOrFail($id);
        return view('admin.social.edit', compact('data'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif,svg|max:1000',
            'social_link' => 'required|string',
        ], [
            'image.max' => 'The image must not be greater than 1MB.',
        ]);

        $social = SocialMedia::findOrFail($request->id);
        $social->title = $request->title;
        $social->link = $request->social_link;

        // image upload
        if (isset($request->image)) {
            $fileUpload = fileUpload($request->image, 'social');

            $social->image = $fileUpload['file'][0];
        }

        $social->save();

        return redirect()->route('admin.social_media.list.all')->with('success', 'Social media updated');
    }

    public function delete(Request $request, $id)
    {
        $data = SocialMedia::findOrFail($id);
        $data->delete();

        return redirect()->route('admin.social_media.list.all')->with('success', 'Social media deleted');
    }
}
