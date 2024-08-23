<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SeoPage;
use App\Models\PageContent;
use App\Models\Wace;
use App\Models\WaceNew;
use App\Models\WaceNewTab;

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
        $waceData = Wace::where('page_content_id',$id)->first();
        // dd($waceData);
        $data = PageContent::findOrFail($id);    
        // dd($data);
        $waceNewData = WaceNew::where('page_content_id',$data->id)->get();
        // dd($waceNewData);
        return view('admin.page_content.edit', compact('data','waceData','waceNewData'));
    }

    public function waceTabStore(Request $request){
        // dd($request->all());
        $request->validate([
            'title'=>'required|string|unique:wace_new,title',
            'wace_tab_desc'=>'required|string',
            'image'=>'required|mimes:jpg,jpeg,png,gif'
        ]);
         $wace_new = new WaceNew();
         $wace_new->title = $request->title;
         $wace_new->wace_tab_desc = $request->wace_tab_desc;
         $wace_new->page_content_id = $request->page_content_id;
         if($request->image){
            $file = $request->file('image');
            $fileName = time().rand(10000,99999).'.'.$file->getClientOriginalExtension();
            $imgPath = public_path("uploads/wace");
            $file->move($imgPath,$fileName);
            $wace_new->image = "uploads/wace/".$fileName;
         }
          $save = $wace_new->save();
          if($save){
             return redirect()->route('admin.page_content.edit',$request->page_content_id)->with('success','New Wace Tab Added Successfully');
          }
          else{
            return redirect()->back()->with('failure','Unable to create Wace new tab');
          }
    }
   
    public function waceTabUpdate(Request $request){
        $wace_new = WaceNew::findOrFail($request->wace_tab_id);
        // dd($wace_new);
        $wace_new->title = $request->title;
        $wace_new->wace_tab_desc = $request->wace_tab_desc;
        $wace_new->page_content_id = $request->page_content_id;
        if($request->image){
            $file = $request->file('image');
            $fileName = time().rand(10000,99999).'.'.$file->getClientOriginalExtension();
            $imgPath = public_path("uploads/wace");
            $file->move($imgPath,$fileName);
            $wace_new->image = "uploads/wace/".$fileName;
         }
        $wace_new_Update =  $wace_new->save();
         if($wace_new_Update){
            return redirect()->back()->with('success','Wace Tab Updated Successfully');
         }
        
    }

    public function waceTabUpdate1(Request $request){
    //     // return "hi";
    //   dd($request->all());
        $wace_new = WaceNew::findOrFail($request->wace_tab_id);
        // dd($waceNew);
        $wace_new->title = $request->title;
        $wace_new->wace_tab_desc = $request->wace_tab_desc;
        $wace_new->page_content_id = $request->page_content_id;
        // dd($request->image);
        if($request->hasFile('image')){
            $file = $request->file('image');
            $fileName = time().rand(10000,99999).'.'.$file->getClientOriginalExtension();
            $imgPath = public_path("uploads/wace");
            $file->move($imgPath,$fileName);
            $wace_new->image = "uploads/wace/".$fileName;
         }
         $wace_new->save();
         return response()->json(['message'=>'Record updated successfully']);
    }

    public function waceTabDelete(Request $request,$id){
        $wace_new = WaceNew::findOrFail($id);
       $delete =   $wace_new->delete();
       if($delete){
           return redirect()->back()->with('success','Wace Tab Updated Successfully');
        }
      
    }

    public function waceNewTabStore(Request $request){
        // dd($request->all());
        $wace_new_tab = new WaceNewTab();
        $wace_new_tab->title = $request->title;
        $wace_new_tab->tab_desc = $request->tab_desc;
        $wace_new_tab->wace_new_id = $request->wace_new_id;
       $wace_new_tab_Store = $wace_new_tab->save();
        if($wace_new_tab_Store){
            return redirect()->route('admin.page_content.edit',$request->page_content_id)->with('success','Wace New Tab Added successfully');
        }
        else{
            return redirect()->back()->with('failure','Unable to create the Wace new tab');
        }
    }

    public function waceNewTabUpdate(Request $request){
        // dd($request->all());
        $wace_new_tab = WaceNewTab::findOrFail($request->wace_new_tab_id);
        $wace_new_tab->title = $request->title ;
        $wace_new_tab->tab_desc = $request->tab_desc;
        $wace_new_tab_Update = $wace_new_tab->save();
        if($wace_new_tab_Update){
            return redirect()->back()->with('success','Wace New Tab Updated Successfully');
        }
    }
  

    public function waceNewTabDelete($id){
        // dd('hi');
        $wace_new_tab = WaceNewTab::findOrFail($id);
        // dd($wace_new_tab);
        $delete =  $wace_new_tab->delete();
        if($delete){
            return redirect()->back()->with('success','Wace New Tab Deleted Successfully');
        } 
    }

    public function PageContentUpdate(Request $request){
        // dd($request->all());
         $seo = PageContent::findOrFail($request->id);
        //  dd($seo);
        if($seo->page == 'Home'){
            $seo->tilte = $request->tilte ?? '';
            $seo->desc = $request->desc ?? '';
            $seo->btn_text = $request->btn_text ?? '';
            $seo->btn_link = $request->btn_link ?? '';
            if($request->background_image){
                $file = $request->file('background_image');
                $fileName = time().rand(10000,99999).'.'.$file->getClientOriginalExtension();
                $imgPath = public_path('uploads/banner');
                $file->move($imgPath,$fileName);
                $seo->background_image = "uploads/banner/".$fileName;
            }
        }
        // 
        if($request->wace_id){
                $wace = Wace::findOrFail($request->wace_id);
                $wace->title = $request->wace_title ?? '';
                if($request->wace_image){
                    $file = $request->file('wace_image');
                    $fileName = time().rand(10000,99999).'.'.$file->getClientOriginalExtension();
                    $imgPath = public_path('uploads/wace');
                    $file->move($imgPath,$fileName);
                    $wace->image = "uploads/wace/".$fileName;
                }
               $wace->description =   $request->description ?? '';
               $wace->sub_title_1 =   $request->sub_title_1 ?? '';
               $wace->sub_title_desc_1 =   $request->sub_title_desc_1 ?? '';
               
               if($request->sub_title_img_1){
                 $file = $request->file('sub_title_img_1');
                 $fileName = time().rand(10000,99999).'.'.$file->getClientOriginalExtension();
                 $imgPath = public_path("uploads/wace");
                 $file->move($imgPath,$fileName);
                 $wace->sub_title_img_1 = "uploads/wace/".$fileName;
               }

               $wace->sub_title_2 = $request->sub_title_2 ?? '';
               $wace->sub_title_desc_2 = $request->sub_title_desc_2 ?? '';

               if($request->sub_title_img_2){
                  $file = $request->file('sub_title_img_2');
                  $fileName = time().rand(10000,99999).'.'.$file->getClientOriginalExtension();
                  $imgPath = public_path("uploads/wace");
                  $file->move($imgPath,$fileName);
                  $wace->sub_title_img_2 = "uploads/wace/".$fileName;
               }

               $wace->sub_title_3 = $request->sub_title_3 ?? '';
               $wace->sub_title_desc_3 = $request->sub_title_desc_3 ?? '';
               
               if($request->sub_title_img_3){
                  $file = $request->file('sub_title_img_3');
                  $fileName = time().rand(10000,99999).'.'.$file->getClientOriginalExtension();
                  $imgPath = public_path("uploads/wace");
                  $file->move($imgPath,$fileName);
                  $wace->sub_title_img_3 = "uploads/wace/".$fileName;
               }
               $wace->save();

        }
           
        
       
        // 
         $seo->title1 = $request->title1 ?? '';
         if($request->img1){
            $file = $request->file('img1');
            $fileName = time().rand(10000,99999).'.'.$file->getClientOriginalExtension();
            $imgPath = public_path("uploads/academics");
            $file->move($imgPath,$fileName);

            $seo->img1 = "uploads/academics/". $fileName ;
        }
        $seo->desc1 = $request->desc1 ?? '';
        $seo->btn_text_1 = $request->btn_text_1 ?? '';
        $seo->btn_link_1 = $request->btn_link_1 ?? '';
         $seo->title2 = $request->title2 ?? '';
         if($request->img2){
            $file = $request->file('img2');
            $fileName = time().rand(10000,99999).'.'.$file->getClientOriginalExtension();
            $imgPath = public_path("uploads/academics");
            $file->move($imgPath,$fileName);

            $seo->img2 = "uploads/academics/". $fileName ;
        }
        $seo->desc2 = $request->desc2 ?? '';
        $seo->btn_text_2 = $request->btn_text_2 ?? '';
        $seo->btn_link_2 = $request->btn_link_2 ?? '';

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
