<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CaseStudy;

class CaseStudyController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->keyword ?? '';

        $query = CaseStudy::query();

        $query->when($keyword, function($query) use ($keyword) {
            $query->where('title', 'like', '%'.$keyword.'%');
        });

        $data = $query->latest('id')->paginate(25);

        return view('admin.case-study.index', compact('data'));
    }

    public function create(Request $request)
    {
        return view('admin.case-study.create');
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
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

        $case_study = new CaseStudy();
        $case_study->title = $request->title;
        $case_study->slug = slugGenerate($request->title, 'case_studies');
        $case_study->short_desc = $request->short_desc ?? '';
        $case_study->long_desc = $request->long_desc;

        $case_study->page_title = $request->page_title ?? '';
        $case_study->meta_title = $request->meta_title ?? '';
        $case_study->meta_desc = $request->meta_desc ?? '';
        $case_study->meta_keyword = $request->meta_keyword ?? '';

        // image upload
        if (isset($request->image)) {
            $fileUpload1 = fileUpload($request->image, 'case-study');

            $case_study->image_small = $fileUpload1['file'][0];
            $case_study->image_medium = $fileUpload1['file'][1];
            $case_study->image_large = $fileUpload1['file'][2];
        }

        $case_study->save();

        return redirect()->route('admin.case-study.list.all')->with('success', 'New case study created');
    }

    public function detail(Request $request, $id)
    {
        $data = CaseStudy::findOrFail($id);
        return view('admin.case-study.detail', compact('data'));
    }

    public function edit(Request $request, $id)
    {
        $data = CaseStudy::findOrFail($id);
        return view('admin.case-study.edit', compact('data'));
    }

    public function update(Request $request)
    {
        // dd($request->all());

        $request->validate([
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

        $case_study = CaseStudy::findOrFail($request->id);
        if ($case_study->title != $request->title) {
            $case_study->slug = slugGenerate($request->title, 'case_studies');
        }
        $case_study->title = $request->title;
        $case_study->short_desc = $request->short_desc ?? '';
        $case_study->long_desc = $request->long_desc;

        $case_study->page_title = $request->page_title ?? '';
        $case_study->meta_title = $request->meta_title ?? '';
        $case_study->meta_desc = $request->meta_desc ?? '';
        $case_study->meta_keyword = $request->meta_keyword ?? '';

        // image upload
        if (isset($request->image)) {
            $fileUpload1 = fileUpload($request->image, 'case-study');

            $case_study->image_small = $fileUpload1['file'][0];
            $case_study->image_medium = $fileUpload1['file'][1];
            $case_study->image_large = $fileUpload1['file'][2];
        }

        $case_study->save();

        return redirect()->route('admin.case-study.list.all')->with('success', 'Case study updated');
    }

    public function delete(Request $request, $id)
    {
        $data = CaseStudy::findOrFail($id);
        $data->delete();

        return redirect()->route('admin.case-study.list.all')->with('success', 'Case study deleted');
    }

    public function status(Request $request, $id)
    {
        $data = CaseStudy::findOrFail($id);
        $data->status = ($data->status == 1) ? 0 : 1;
        $data->update();

        return response()->json([
            'status' => 200,
            'message' => 'Status updated',
        ]);
    }
}
