<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faculty;
use App\Models\Category;
use App\Models\ExtraCurricular;
use App\Models\Testimonial;
use App\Models\StudentClass;
use App\Models\Gallery;
use Illuminate\Support\Facades\Session;


use App\Interfaces\CategoryInterface;
use DB;
use Illuminate\Validation\Rule;

class FacultyController extends Controller
{
    public function __construct(CategoryInterface $CategoryRepository)
    {
        $this->CategoryRepository = $CategoryRepository;
    }
    public function index(Request $request)
    {
        $keyword = $request->keyword ?? '';
        $parent_id = $request->parent_id ?? '';

        $query = Category::query();

        $query->when($keyword, function ($query) use ($keyword) {
            $query->where('title', 'like', '%' . $keyword . '%');
        });
        $query->when($parent_id, function ($query) use ($parent_id) {
            $query->where('parent_id', $parent_id);
        });

        $data = $query->latest('id')->paginate(25);
        $parentCategories = Category::where('parent_id', 0)->orderBy('title')->get();

        return view('admin.category.index', compact('data', 'parentCategories'));
    }

    public function create(Request $request)
    {
        $activeCategories = Category::where('status', 1)->orderBy('title')->get();

        return view('admin.category.create', compact('activeCategories'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'title' => 'required|string|max:255',
            'level' => 'required|string|in:parent,child',
            'short_desc' => 'nullable|string|min:1|max:100',
            'long_desc' => 'nullable|string|min:1',
            'icon_path' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif,svg|max:1000',
            'banner_image_path' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif,svg|max:1000',
            'page_title' => 'nullable|string|min:1',
            'meta_title' => 'nullable|string|min:1',
            'meta_desc' => 'nullable|string|min:1',
            'meta_keyword' => 'nullable|string|min:1'
        ], [
            'icon_path.max' => 'The icon must not be greater than 1MB.',
            'banner_image_path.max' => 'The icon must not be greater than 1MB.'
        ]);

        $parent_id = 0;
        if ($request->level == "child") {
            $parent_id = $request->parent_id ?? 0;
        }

        $category = new Category();
        $category->parent_id = $parent_id;
        $category->title = $request->title;
        $category->slug = slugGenerate($request->title, 'categories');
        $category->short_desc = $request->short_desc ?? '';
        $category->long_desc = $request->long_desc ?? '';

        $category->page_title = $request->page_title ?? '';
        $category->meta_title = $request->meta_title ?? '';
        $category->meta_desc = $request->meta_desc ?? '';
        $category->meta_keyword = $request->meta_keyword ?? '';

        // image upload
        if (isset($request->icon_path)) {
            $fileUpload1 = fileUpload($request->icon_path, 'categories');

            $category->icon_path_small = $fileUpload1['file'][0];
            $category->icon_path_medium = $fileUpload1['file'][1];
            $category->icon_path_large = $fileUpload1['file'][2];
        }

        // image upload
        if (isset($request->banner_image_path)) {
            $fileUpload2 = fileUpload($request->banner_image_path, 'categories');

            $category->banner_image_path_small = $fileUpload2['file'][0];
            $category->banner_image_path_medium = $fileUpload2['file'][1];
            $category->banner_image_path_large = $fileUpload2['file'][2];
        }

        $category->save();

        return redirect()->route('admin.category.list.all')->with('success', 'New category created');
    }

    public function detail(Request $request, $id)
    {
        $data = Category::findOrFail($id);

        return view('admin.category.detail', compact('data'));
    }

    public function edit(Request $request, $id)
    {
        $data = Category::findOrFail($id);
        $allCategories = Category::orderBy('title')->get();

        return view('admin.category.edit', compact('data', 'allCategories'));
    }

    public function update(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'id' => 'required|integer',
            'title' => 'required|string|max:255',
            'level' => 'required|string|in:parent,child',
            'short_desc' => 'nullable|string|min:1|max:100',
            'long_desc' => 'nullable|string|min:1',
            'icon_path' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif,svg|max:1000',
            'banner_image_path' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif,svg|max:1000',
            'page_title' => 'nullable|string|min:1',
            'meta_title' => 'nullable|string|min:1',
            'meta_desc' => 'nullable|string|min:1',
            'meta_keyword' => 'nullable|string|min:1'
        ], [
            'icon_path.max' => 'The icon must not be greater than 1MB.',
            'banner_image_path.max' => 'The icon must not be greater than 1MB.'
        ]);

        $parent_id = 0;
        if ($request->level == "child") {
            $parent_id = $request->parent_id ?? 0;
        }

        $category = Category::findOrFail($request->id);
        $category->parent_id = $parent_id;

        if ($category->title != $request->title) {
            $category->slug = slugGenerate($request->title, 'categories');
        }

        $category->title = $request->title;
        $category->short_desc = $request->short_desc;
        $category->long_desc = $request->long_desc;

        $category->page_title = $request->page_title ?? '';
        $category->meta_title = $request->meta_title ?? '';
        $category->meta_desc = $request->meta_desc ?? '';
        $category->meta_keyword = $request->meta_keyword ?? '';

        // image upload
        if (isset($request->icon_path)) {
            $fileUpload1 = fileUpload($request->icon_path, 'categories');

            $category->icon_path_small = $fileUpload1['file'][0];
            $category->icon_path_medium = $fileUpload1['file'][1];
            $category->icon_path_large = $fileUpload1['file'][2];
        }

        // image upload
        if (isset($request->banner_image_path)) {
            $fileUpload2 = fileUpload($request->banner_image_path, 'categories');

            $category->banner_image_path_small = $fileUpload2['file'][0];
            $category->banner_image_path_medium = $fileUpload2['file'][1];
            $category->banner_image_path_large = $fileUpload2['file'][2];
        }

        $category->save();

        return redirect()->route('admin.category.list.all')->with('success', 'Category updated');
    }

    public function delete(Request $request, $id)
    {
        $data = Category::findOrFail($id);
        $data->delete();

        return redirect()->route('admin.category.list.all')->with('success', 'Category deleted');
    }

    public function status(Request $request, $id)
    {
        $data = Faculty::findOrFail($id);
        $data->status = ($data->status == 1) ? 0 : 1;
        $data->update();

        return response()->json([
            'status' => 200,
            'message' => 'Status updated',
        ]);
    }
    // Faculty
    public function FacultyIndex(Request $request)
    {
        if (!empty($request->keyword)) {
            $data = $this->CategoryRepository->getSearchFaculty($request->keyword);
        } else {
            $data = $this->CategoryRepository->listAllFaculties();
        }
        return view('admin.faculty.index', compact('data'));
    }

    public function FacultyCreate(Request $request)
    {
        $StudentClass = StudentClass::where('status', 1)->where('deleted_at', 1)->get();
        return view('admin.faculty.create', compact('StudentClass'));
    }
    public function FacultyStore(Request $request)
    {
        DB::beginTransaction();
        $request->validate([
            'name' => 'required|string|max:255|unique:faculties,name',
            'designation' => 'required|string|max:255',
            'description' => 'required|string',
            'class_name' => 'required|string',
            'pic' => 'required|mimes:jpg,jpeg,png,gif,svg|max:1000'

        ], [
            'pic.max' => 'The image must not be greater than 1MB.',
        ]);
        $file = $request->file('pic');
        $fileName = time() . rand(10000, 99999) . '.' . $file->getClientOriginalExtension(); // Generate unique filename
        $filePath = 'uploads/faculty/' . $fileName; // Construct full path
        $file->move(public_path('uploads/faculty/'), $fileName); // Move file to destination
        try {
            $data = new Faculty;
            $data->name = $request->name;
            $data->class_name = $request->class_name;
            $data->designation = $request->designation;
            $data->desc = $request->description;
            $data->facebook_link = $request->facebook_link;
            $data->twitter_link = $request->twitter_link;
            $data->instagram_link = $request->instagram_link;
            $data->image = $filePath;
            $data->save();
            // Commit the transaction if everything is successful
            DB::commit();
            return redirect()->route('admin.faculty.list.all')->with('success', 'New faculty created');
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollback();
            // You can log the exception if needed
            \Log::error($e);
            // Redirect back with an error message
            return redirect()->back()->with('failure', 'Failed to create faculty. Please try again.');
        }
    }
    public function FacultyEdit($id)
    {
        $StudentClass = StudentClass::where('status', 1)->where('deleted_at', 1)->get();
        $data = $this->CategoryRepository->findFacultyById($id);
        return view('admin.faculty.edit', compact('data', 'StudentClass'));
    }
    public function FacultyUpdate(Request $request)
    {
        // dd($request->all());
        // Start a database transaction
        DB::beginTransaction();

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('collections', 'title')->ignore($request->id),
            ],
            'designation' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => [
                'required',
                'string',
            ],
            'class_name' => [
                'required',
                'string',
            ],
            
            'pic' => [
                'mimes:jpg,jpeg,png,gif,svg',
                'max:1000',
            ],

        ],[
            'pic.max' => 'The image must not be greater than 1MB.'
        ]);

        try {
            $data = Faculty::findOrFail($request->id);
            $data->name = $request->name;
            $data->class_name = $request->class_name;
            $data->designation = $request->designation;
            $data->desc = $request->description;
            $data->facebook_link = $request->facebook_link;
            $data->twitter_link = $request->twitter_link;
            $data->instagram_link = $request->instagram_link;
            if($request->pic){
                $file = $request->file('pic');
               
                $fileName = time() . rand(10000, 99999) . '.' . $file->getClientOriginalExtension(); // Generate unique filename
                $filePath = 'uploads/faculty/' . $fileName; // Construct full path
                $file->move(public_path('uploads/faculty/'), $fileName); // Move file to destination

            $data->image = $filePath;    
            }else{
            $data->image = $request->old_faculty_img;    
            }
            $data->save();
            // Commit the transaction if everything is successful
            DB::commit();
            return redirect()->route('admin.faculty.list.all')->with('success', 'Faculty updated successfully');
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollback();
            // You can log the exception if needed
            \Log::error($e);
            // Redirect back with an error message
            return redirect()->back()->with('failure', 'Failed to update faculty. Please try again.');
        }
    }
    public function FacultyStatus(Request $request, $id)
    {
        $data = $this->CategoryRepository->findFacultyById($id);
        $data->status = ($data->status == 1) ? 0 : 1;
        $data->update();
        return response()->json([
            'status' => 200,
            'message' => 'Status updated',
        ]);
    }

    public function FacultyDelete(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $data = Faculty::findOrFail($id);
            $data->deleted_at = 0;
            $data->save();
            // Commit the transaction if the deletion is successful
            DB::commit();
            return redirect()->route('admin.faculty.list.all')->with('success', 'Faculty deleted');
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollback();
            // Log the exception if needed
            \Log::error($e);
            // Redirect back with an error message
            return redirect()->back()->with('failure', 'Failed to delete faculty. Please try again.');
        }
    }
    // Testimonials
    public function TestimonialIndex(Request $request)
    {
        $data = Testimonial::latest()->get();
        return view('admin.testimonial.index', compact('data'));
    }

    public function TestimonialCreate(Request $request)
    {
        return view('admin.testimonial.create');
    }
    public function TestimonialStore(Request $request)
    {
        DB::beginTransaction();
        $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|mimes:jpg,jpeg,png,gif,svg|max:1000'

        ], [
            'image.max' => 'The image must not be greater than 1MB.',
        ]);
        $file = $request->file('image');
        $fileName = time() . rand(10000, 99999) . '.' . $file->getClientOriginalExtension(); // Generate unique filename
        $filePath = 'uploads/faculty/' . $fileName; // Construct full path
        $file->move(public_path('uploads/faculty/'), $fileName); // Move file to destination
        try {
            $data = new Testimonial;
            $data->name = $request->name;
            $data->designation = $request->designation;
            $data->desc = $request->description;
            $data->image = $filePath;
            $data->save();
            // Commit the transaction if everything is successful
            DB::commit();
            return redirect()->route('admin.testimonial.list.all')->with('success', 'New testimonial created');
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollback();
            // You can log the exception if needed
            \Log::error($e);
            // Redirect back with an error message
            return redirect()->back()->with('failure', 'Failed to create testimonial. Please try again.');
        }
    }
    public function TestimonialEdit($id)
    {
       $data = Testimonial::findOrFail($id);
        return view('admin.testimonial.edit', compact('data'));
    }
    public function TestimonialUpdate(Request $request)
    {
        DB::beginTransaction();
        $request->validate([
            'name' => [
                'required',
                'string'
            ],
            'designation' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => [
                'required',
                'string',
            ],
            
            'image' => [
                'mimes:jpg,jpeg,png,gif,svg',
                'max:1000',
            ],

        ],[
            'image.max' => 'The image must not be greater than 1MB.'
        ]);

        try {
            $data = Testimonial::findOrFail($request->id);
            $data->name = $request->name;
            $data->designation = $request->designation;
            $data->desc = $request->description;
            if($request->image){
                $file = $request->file('image');
                $fileName = time() . rand(10000, 99999) . '.' . $file->getClientOriginalExtension(); // Generate unique filename
                $filePath = 'uploads/faculty/' . $fileName; // Construct full path
                $file->move(public_path('uploads/faculty/'), $fileName); // Move file to destination

                $data->image = $filePath;    
            }
            $data->save();
            // Commit the transaction if everything is successful
            DB::commit();
            return redirect()->route('admin.testimonial.list.all')->with('success', 'Testimonial updated successfully');
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollback();
            // You can log the exception if needed
            \Log::error($e);
            // Redirect back with an error message
            return redirect()->back()->with('failure', 'Failed to update testimonial. Please try again.');
        }
    }
    public function TestimonialStatus(Request $request, $id)
    {
        $data = Testimonial::findOrFail($request->id);
        $data->status = ($data->status == 1) ? 0 : 1;
        $data->update();
        return response()->json([
            'status' => 200,
            'message' => 'Status updated',
        ]);
    }

    public function TestimonialDelete(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $data = Testimonial::findOrFail($id);
            $data->delete();
            // Commit the transaction if the deletion is successful
            DB::commit();
            return redirect()->route('admin.testimonial.list.all')->with('success', 'Testimonial deleted');
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollback();
            // Log the exception if needed
            \Log::error($e);
            // Redirect back with an error message
            return redirect()->back()->with('failure', 'Failed to delete testimonial. Please try again.');
        }
    }
    // Extra Curricular
    public function CurricularIndex(Request $request)
    {
        $data = ExtraCurricular::latest()->get();
        return view('admin.curricular.index', compact('data'));
    }

    public function CurricularCreate(Request $request)
    {
        return view('admin.curricular.create');
    }
    public function CurricularStore(Request $request){
        DB::beginTransaction();
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|mimes:jpg,jpeg,png,gif,svg|max:1000'

        ], [
            'image.max' => 'The image must not be greater than 1MB.',
        ]);
        $file = $request->file('image');
        $fileName = time() . rand(10000, 99999) . '.' . $file->getClientOriginalExtension(); // Generate unique filename
        $filePath = 'uploads/faculty/' . $fileName; // Construct full path
        $file->move(public_path('uploads/faculty/'), $fileName); // Move file to destination
        try {
            $data = new ExtraCurricular;
            $data->title = $request->title;
            $data->desc = $request->description;
            $data->image = $filePath;
            $data->save();
            // Commit the transaction if everything is successful
            DB::commit();
            return redirect()->route('admin.curricular.list.all')->with('success', 'New data created');
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollback();
            // You can log the exception if needed
            \Log::error($e);
            // Redirect back with an error message
            return redirect()->back()->with('failure', 'Failed to create data. Please try again.');
        }
    }
    public function CurricularEdit($id)
    {
        $data = ExtraCurricular::findOrFail($id);
        return view('admin.curricular.edit', compact('data'));
    }
    public function CurricularUpdate(Request $request)
    {
        DB::beginTransaction();

        $request->validate([
            'title' => [
                'required',
                'string',
                'max:255'
            ],
            'description' => [
                'required',
                'string',
            ],
            
            'image' => [
                'mimes:jpg,jpeg,png,gif,svg',
                'max:1000',
            ],

        ],[
            'image.max' => 'The image must not be greater than 1MB.'
        ]);

        try {
            $data = ExtraCurricular::findOrFail($request->id);
            $data->title = $request->title;
            $data->desc = $request->description;
            if($request->image){
                $file = $request->file('image');
               
                $fileName = time() . rand(10000, 99999) . '.' . $file->getClientOriginalExtension(); // Generate unique filename
                $filePath = 'uploads/faculty/' . $fileName; // Construct full path
                $file->move(public_path('uploads/faculty/'), $fileName); // Move file to destination

            $data->image = $filePath;    
            }
            $data->save();
            // Commit the transaction if everything is successful
            DB::commit();
            return redirect()->route('admin.curricular.list.all')->with('success', 'Extra curricular updated successfully');
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollback();
            // You can log the exception if needed
            \Log::error($e);
            // Redirect back with an error message
            return redirect()->back()->with('failure', 'Failed to update Extra curricular. Please try again.');
        }
    }

    public function CurricularDelete(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $data = ExtraCurricular::findOrFail($id);
            $data->delete();
            // Commit the transaction if the deletion is successful
            DB::commit();
            return redirect()->route('admin.curricular.list.all')->with('success', 'Data deleted');
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollback();
            // Log the exception if needed
            \Log::error($e);
            // Redirect back with an error message
            return redirect()->back()->with('failure', 'Failed to delete data. Please try again.');
        }
    }
    // Gallery
    public function GalleryIndex(Request $request){
        $data = Gallery::latest()->get();
        return view('admin.gallery.index', compact('data'));
    }
    
    public function GalleryCreate(Request $request){
        return view('admin.gallery.create');
    }
    public function GalleryStore(Request $request){
        if ($request->type == "image" && !$request->hasFile('file')) {
            Session::flash('key', 'Please choose an image');
            return redirect()->route('admin.gallery.create');
        }
        if ($request->type == "video" && !$request->video_path) {
            Session::flash('key', 'Please enter valid video URL path');
            return redirect()->route('admin.gallery.create');
        }
        
        $data = new Gallery;
        if($request->type=="image"){
            $file = $request->file('file');
            $fileName = time() . rand(10000, 99999) . '.' . $file->getClientOriginalExtension(); // Generate unique filename
            $filePath = 'uploads/faculty/' . $fileName; // Construct full path
            $file->move(public_path('uploads/faculty/'), $fileName); // Move file to destination
            $data->image = $filePath;
        }else{
            $data->video = $request->video_path;
        }
        $data->save();
        return redirect()->route('admin.gallery.list.all')->with('success', 'New data created');
    }
    public function GalleryEdit($id){
        $data = Gallery::findOrFail($id);
        return view('admin.gallery.edit', compact('data'));
    }
    public function GalleryUpdate(Request $request){
        if ($request->type == "image" && !$request->hasFile('file')) {
            Session::flash('key', 'Please choose an image');
            return redirect()->back();
        }
        if ($request->type == "video" && !$request->video_path) {
            Session::flash('key', 'Please enter valid video URL path');
            return redirect()->back();
        }
        $data = Gallery::findOrFail($request->id);
        if($request->type=="image"){
            $file = $request->file('file');
            $fileName = time() . rand(10000, 99999) . '.' . $file->getClientOriginalExtension(); // Generate unique filename
            $filePath = 'uploads/faculty/' . $fileName; // Construct full path
            $file->move(public_path('uploads/faculty/'), $fileName); // Move file to destination
            $data->image = $filePath;
            $data->video = null;
        }else{
            $data->video = $request->video_path;
            $data->image = null;
        }
        $data->save();
        return redirect()->route('admin.gallery.list.all')->with('success', 'New data created');
    }

    public function GalleryDelete(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $data = Gallery::findOrFail($id);
            $data->delete();
            // Commit the transaction if the deletion is successful
            DB::commit();
            return redirect()->route('admin.gallery.list.all')->with('success', 'Data deleted');
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollback();
            // Log the exception if needed
            \Log::error($e);
            // Redirect back with an error message
            return redirect()->back()->with('failure', 'Failed to delete data. Please try again.');
        }
    }
}
