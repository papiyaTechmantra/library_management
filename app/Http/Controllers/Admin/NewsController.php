<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NewsCategory;
use App\Models\News;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->keyword ?? '';
        $query = News::query();

        $query->when($keyword, function($query) use ($keyword) {
            $query->where('title', 'like', '%'.$keyword.'%');
        });

        $data = $query->latest('id')->paginate(25);

        return view('admin.news.index', compact('data'));
    }

    public function create(Request $request)
    {
        $category = NewsCategory::orderBy('id', 'ASC')->get();
        return view('admin.news.create', compact('category'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|integer|min:1',
            'desc' => 'required',
            'image.*' => 'required|image|mimes:jpg,jpeg,png,webp,gif,svg|max:2000',
        ], [
            'image.max' => 'The image must not be greater than 1MB.',
        ]);
        $News = new News();
        $News->title = $request->title;
        $News->slug = slugGenerate($request->title, 'news');
        $News->news_category_id = $request->category;
        $News->desc = $request->desc;

         // image upload
         if (isset($request->image)) {
            $fileUpload = fileUpload($request->image, 'news');
            $News->image = $fileUpload['file'][2];
        }
        $News->save();
        return redirect()->route('admin.news.list.all')->with('success', 'News created');
    }

    public function edit(Request $request, $id)
    {
        $category = NewsCategory::orderBy('id', 'ASC')->get();
        $data = News::findOrFail($id);
        return view('admin.news.edit', compact('data', 'category'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|integer|min:1',
            'desc' => 'required',
            'image.*' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif,svg|max:2000',
        ], [
            'image.max' => 'The image must not be greater than 1MB.',
        ]);

        $News = News::findOrFail($request->id);
        $News->title = $request->title;
        $News->slug = slugGenerateUpdate($request->title, 'news', $News->id);
        $News->news_category_id = $request->category;
        $News->desc = $request->desc;


         // image upload
         if (isset($request->image)) {
            $fileUpload = fileUpload($request->image, 'news');
            $News->image = $fileUpload['file'][2];
        }
        $News->save();
        return redirect()->route('admin.news.list.all')->with('success', 'News updated');
    }

    public function delete(Request $request, $id)
    {
        $data = News::findOrFail($id);
        $data->delete();
        return redirect()->route('admin.news.list.all')->with('success', 'News deleted');
    }
    public function status(Request $request, $id)
    {
        $data = News::findOrFail($id);
        $data->status = ($data->status == 1) ? 0 : 1;
        $data->update();
        return response()->json([
            'status' => 200,
            'message' => 'Status updated',
        ]);
    }
    // News Category
    public function CategoryIndex(Request $request)
    {
        $keyword = $request->keyword ?? '';
        $query = NewsCategory::query();

        $query->when($keyword, function($query) use ($keyword) {
            $query->where('name', 'like', '%'.$keyword.'%');
        });

        $data = $query->latest('id')->paginate(25);

        return view('admin.news-category.index', compact('data'));
    }

    public function CategoryCreate(Request $request)
    {
        return view('admin.news-category.create');
    }

    public function CategoryStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $NewsCategory = new NewsCategory();
        $NewsCategory->name = ucwords($request->name);
        $NewsCategory->save();
        return redirect()->route('admin.news_category.list.all')->with('success', 'News category created');
    }

    public function CategoryEdit(Request $request, $id)
    {
        $data = NewsCategory::findOrFail($id);
        return view('admin.news-category.edit', compact('data'));
    }

    public function CategoryUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $NewsCategory = NewsCategory::findOrFail($request->id);
        $NewsCategory->name = $request->name;

        $NewsCategory->save();
        return redirect()->route('admin.news_category.list.all')->with('success', 'News Category updated');
    }

    public function CategoryDelete(Request $request, $id)
    {
        $data = NewsCategory::findOrFail($id);
        $data->delete();

        return redirect()->route('admin.news_category.list.all')->with('success', 'News Category deleted');
    }
    public function CategoryStatus(Request $request, $id)
    {
        
        $data = NewsCategory::findOrFail($id);
        $data->status = ($data->status == 1) ? 0 : 1;
        $data->update();
        return response()->json([
            'status' => 200,
            'message' => 'Status updated',
        ]);
    }
}
