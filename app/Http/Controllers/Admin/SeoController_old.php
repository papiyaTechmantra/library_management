<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SeoPage;
use App\Models\PageContent;

class SeoController extends Controller
{
    public function index(Request $request){
        $keyword = $request->keyword ?? '';
        $query = SeoPage::query();
        $query->when($keyword, function($query) use ($keyword) {
            $query->where('page', 'like', '%'.$keyword.'%');
        });

        $data = $query->paginate(25);

        return view('admin.seo.index', compact('data'));
    }

    public function detail(Request $request, $id)
    {
        $data = SeoPage::findOrFail($id);
        return view('admin.seo.detail', compact('data'));
    }

    public function edit(Request $request, $id){
        $data = SeoPage::findOrFail($id);
        return view('admin.seo.edit', compact('data'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'page_title' => 'nullable|string|min:1',
            'meta_title' => 'nullable|string|min:1',
            'meta_desc' => 'nullable|string|min:1',
            'meta_keyword' => 'nullable|string|min:1'
        ]);

        $seo = SeoPage::findOrFail($request->id);

        $seo->page_title = $request->page_title ?? '';
        $seo->meta_title = $request->meta_title ?? '';
        $seo->meta_desc = $request->meta_desc ?? '';
        $seo->meta_keyword = $request->meta_keyword ?? '';

        $seo->save();

        return redirect()->route('admin.seo.list.all')->with('success', 'SEO page details updated');
    }
    public function PageContentCreate(){
        return view('admin.page_content.create');
    }

    public function PageContentStore(Request $request){
        // dd($request->all());
        $request->validate([
            'page'=>'required | unique:page_content,page',
            'tilte' => 'required',
            'desc'=>'required',
            'location'=>'required'

        ]);

        $page_content = new PageContent();
        $page_content->page = $request->page;
        $page_content->tilte = $request->tilte;
        $page_content->slug = pageContentSlug($request->page, 'page_content');
        $page_content->location = $request->location;
        $page_content->desc = $request->desc;
        $page_content->custom_field = 1;
        $page_content->save();
        return redirect()->route('admin.page_content.list.all')->with('success','Page Content Created Successfully!');


    }
    public function PageContentIndex(Request $request){
        $keyword = $request->keyword ?? '';
        $query = PageContent::query();
        $query->when($keyword, function($query) use ($keyword) {
            $query->where('page', 'like', '%'.$keyword.'%');
        })->orderBy('page', 'ASC');

        $data = $query->paginate(25);

        return view('admin.page_content.index', compact('data'));
    }

    public function PageContentDetail(Request $request, $id)
    {
        $data = PageContent::findOrFail($id);
        return view('admin.page_content.detail', compact('data'));
    }

    public function PageContentEdit(Request $request, $id){
        $data = PageContent::findOrFail($id);
        return view('admin.page_content.edit', compact('data'));
    }

    public function PageContentUpdate(Request $request){
        // dd($request->all());
        $seo = PageContent::findOrFail($request->id);

        $seo->tilte = $request->tilte ?? '';
        if($seo->custom_field == 1){
            $seo->page = $request->page ?? '';
            $seo->slug = pageContentSlugUpdate($request->page, 'page_content', $seo->id);
            $seo->location = $request->location ?? '';
        }
        $seo->desc = $request->desc ?? '';
        $seo->about_founder_msg = $request->about_founder_msg ?? '';
        $seo->about_principal_msg = $request->about_principal_msg ?? '';
        $seo->about_videos  = $request->about_videos ?? '';

        if($request->curriculum_image){
            $file = $request->file('curriculum_image');
            $fileName = time() . rand(10000, 99999) . '.' . $file->getClientOriginalExtension(); // Generate unique filename
            $filePath = 'uploads/content/' . $fileName; // Construct full path
            $file->move(public_path('uploads/content/'), $fileName); // Move file to destination

            $seo->curriculum_image = $filePath;    
        }
        $seo->curriculum_desc = $request->curriculum_desc ?? '';

        if($request->beyond_image){
            $file = $request->file('beyond_image');
            $fileName = time() . rand(10000, 99999) . '.' . $file->getClientOriginalExtension(); // Generate unique filename
            $filePath = 'uploads/content/' . $fileName; // Construct full path
            $file->move(public_path('uploads/content/'), $fileName); // Move file to destination

            $seo->beyond_image = $filePath;    
        }
        $seo->beyond_desc = $request->beyond_desc ?? '';

        if($request->admission_image){
            $file = $request->file('admission_image');
            $fileName = time() . rand(10000, 99999) . '.' . $file->getClientOriginalExtension(); // Generate unique filename
            $filePath = 'uploads/content/' . $fileName; // Construct full path
            $file->move(public_path('uploads/content/'), $fileName); // Move file to destination

            $seo->admission_image = $filePath;    
        }

        $seo->admission_desc = $request->admission_desc ?? '';
        $seo->walkthrough_desc = $request->walkthrough_desc ?? '';
        $seo->walkthrough_video = $request->walkthrough_video ?? '';
        $seo->core_value_desc = $request->core_value_desc ?? '';

        if($request->core_value_image_1){
            $file = $request->file('core_value_image_1');
            $fileName = time() . rand(10000, 99999) . '.' . $file->getClientOriginalExtension(); // Generate unique filename
            $filePath = 'uploads/content/' . $fileName; // Construct full path
            $file->move(public_path('uploads/content/'), $fileName); // Move file to destination

            $seo->core_value_image_1 = $filePath;    
        }
        $seo->core_value_title_1 = $request->core_value_title_1 ?? '';
        if($request->core_value_image_2){
            $file = $request->file('core_value_image_2');
            $fileName = time() . rand(10000, 99999) . '.' . $file->getClientOriginalExtension(); // Generate unique filename
            $filePath = 'uploads/content/' . $fileName; // Construct full path
            $file->move(public_path('uploads/content/'), $fileName); // Move file to destination

            $seo->core_value_image_2 = $filePath;    
        }
        $seo->core_value_title_2 = $request->core_value_title_2 ?? '';

        if($request->core_value_image_3){
            $file = $request->file('core_value_image_3');
            $fileName = time() . rand(10000, 99999) . '.' . $file->getClientOriginalExtension(); // Generate unique filename
            $filePath = 'uploads/content/' . $fileName; // Construct full path
            $file->move(public_path('uploads/content/'), $fileName); // Move file to destination

            $seo->core_value_image_3 = $filePath;    
        }
        $seo->teacher_student_mentorship = $request->teacher_student_mentorship ?? '';
        $seo->staff_only = $request->staff_only ?? '';
        $seo->board_curriculum = $request->board_curriculum ?? '';
        $seo->a_pioneer_in_eastern_india = $request->a_pioneer_in_eastern_india ?? '';
        $seo->area_of_school = $request->area_of_school ?? '';


        $seo->save();

        return redirect()->route('admin.page_content.list.all')->with('success', 'details updated');
    }

    public function PageContentDelete($id){
        $delete = PageContent::findOrFail($id);
        
        if($delete->custom_field == 1){
            $delete->delete();
        
        }

        return redirect()->route('admin.page_content.list.all')->with('success','Deleted Successfully');

    }

}
