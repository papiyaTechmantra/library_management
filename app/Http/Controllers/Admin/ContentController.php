<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\ContentAbout;
use App\Models\ContentHome;
use App\Models\ContentEpc;
use App\Models\ContentContact;
use App\Models\Setting;
use App\Models\Lead;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ContentController extends Controller
{
    public function home(Request $request)
    {
        $data = ContentHome::first();
        return view('admin.content.home', compact('data'));
    }
    public function epc(Request $request)
    {
        $data = ContentEpc::first();
        return view('admin.content.epc', compact('data'));
    }
    public function leads(Request $request)
    {
        $data = Lead::latest()->get();
        return view('admin.content.leads', compact('data'));
    }
    public function careers(Request $request)
    {
        $data = DB::table('careers')->get();
        return view('admin.content.careers', compact('data'));
    }
    public function about(Request $request)
    {
        $data = ContentAbout::first();
        return view('admin.content.about', compact('data'));
    }

    public function contact(Request $request)
    {
        $data = ContentContact::first();
        return view('admin.content.contact', compact('data'));
    }

    public function settings(Request $request){
        $data = Setting::get();
        return view('admin.content.settings', compact('data'));
    }

    
    public function homeUpdate(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'id' => 'required|integer|min:1',
            'title1' => 'required|string|max:255',
            'title1_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif,svg|max:1000',
            'title1_author' => 'required|string|max:255',
            'title1_video' => 'required|string|max:255',
            'title1_author_designation' => 'required|string|max:255',
            'title1_desc' => 'required|string|max:1000',

            'why_choose_us_title' => 'required|string|max:255',
            'why_choose_us_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif,svg|max:1000',

            'why_choose_us_section1_title' => 'required|string|max:255',
            'why_choose_us_section1_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif,svg|max:1000',
            'why_choose_us_section1_desc' => 'required|string|max:1000',

            'why_choose_us_section2_title' => 'required|string|max:255',
            'why_choose_us_section2_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif,svg|max:1000',
            'why_choose_us_section2_desc' => 'required|string|max:1000',

            'why_choose_us_section3_title' => 'required|string|max:255',
            'why_choose_us_section3_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif,svg|max:1000',
            'why_choose_us_section3_desc' => 'required|string|max:1000',

            'why_choose_us_section4_title' => 'required|string|max:255',
            'why_choose_us_section4_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif,svg|max:1000',
            'why_choose_us_section4_desc' => 'required|string|max:1000',

            'project_completed' => 'required|string|max:255',
            'happy_customer' => 'required|string|max:255',
            'solar_panels' => 'required|string|max:255',
            'distributors' => 'required|string|max:255',
        ], [
            'title1_image.max' => 'The image must not be greater than 1MB.',
            'why_choose_us_image.max' => 'The image must not be greater than 1MB.',
            'why_choose_us_section1_image.max' => 'The image must not be greater than 1MB.',
            'why_choose_us_section2_image.max' => 'The image must not be greater than 1MB.',
            'why_choose_us_section3_image.max' => 'The image must not be greater than 1MB.',
            'why_choose_us_section4_image.max' => 'The image must not be greater than 1MB.',
        ]);

        $data = ContentHome::first();
        $data->title1 = $request->title1;
        $data->title1_desc = $request->title1_desc;
        $data->title1_author = $request->title1_author;
        $data->title1_video = $request->title1_video;
        $data->title1_author_designation = $request->title1_author_designation;

        $data->why_choose_us_title = $request->why_choose_us_title;
        $data->why_choose_us_section1_title = $request->why_choose_us_section1_title;
        $data->why_choose_us_section1_desc = $request->why_choose_us_section1_desc;

        $data->why_choose_us_section2_title = $request->why_choose_us_section2_title;
        $data->why_choose_us_section2_desc = $request->why_choose_us_section2_desc;

        $data->why_choose_us_section3_title = $request->why_choose_us_section3_title;
        $data->why_choose_us_section3_desc = $request->why_choose_us_section3_desc;

        $data->why_choose_us_section4_title = $request->why_choose_us_section4_title;
        $data->why_choose_us_section4_desc = $request->why_choose_us_section4_desc;

        $data->project_completed = $request->project_completed;
        $data->happy_customer = $request->happy_customer;
        $data->solar_panels = $request->solar_panels;
        $data->distributors = $request->distributors;

        // image upload
        if (isset($request->title1_image)) {
            $fileUpload1 = fileUpload($request->title1_image, 'home-page');
            $data->title1_image = $fileUpload1['file'][2];
        }
        // image upload
        if (isset($request->why_choose_us_image)) {
            $fileUpload2 = fileUpload($request->why_choose_us_image, 'home-page');
            $data->why_choose_us_image = $fileUpload2['file'][2];
        }
        // image upload
        if (isset($request->why_choose_us_section1_image)) {
            $fileUpload3 = fileUpload($request->why_choose_us_section1_image, 'home-page');
            $data->why_choose_us_section1_image = $fileUpload3['file'][2];
        }
        // image upload
        if (isset($request->why_choose_us_section2_image)) {
            $fileUpload4 = fileUpload($request->why_choose_us_section2_image, 'home-page');
            $data->why_choose_us_section2_image = $fileUpload4['file'][2];
        }
        // image upload
        if (isset($request->why_choose_us_section3_image)) {
            $fileUpload5 = fileUpload($request->why_choose_us_section3_image, 'home-page');
            $data->why_choose_us_section3_image = $fileUpload5['file'][2];
        }
        // image upload
        if (isset($request->why_choose_us_section4_image)) {
            $fileUpload6 = fileUpload($request->why_choose_us_section4_image, 'home-page');
            $data->why_choose_us_section4_image = $fileUpload6['file'][2];
        }
        
        $data->save();

        return redirect()->back()->with('success', 'Content updated');
    }
    public function epcUpdate(Request $request){
        // dd($request->all());
        $request->validate([
            'id' => 'required|integer|min:1',
            'point1_title' => 'required|string|max:255',
            'section1_title' => 'required|string|max:255',
            'section1_desc' => 'required|string',
            'section1_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif,svg|max:3000',
            
            'point2_title' => 'required|string|max:255',
            'section2_title' => 'required|string|max:255',
            'section2_desc' => 'required|string',
            'section2_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif,svg|max:3000',
            
            'section3_title' => 'required|string|max:255',
            'section3_desc' => 'required|string',
            'section3_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif,svg|max:3000',

            'section4_title' => 'required|string|max:255',
            'section4_desc' => 'required|string',
            'section4_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif,svg|max:3000',

            'section5_title' => 'required|string|max:255',
            'section5_desc' => 'required|string',
            'section5_image1' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif,svg|max:3000',
            'section5_image2' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif,svg|max:3000',

        ], [
            'section1_image.max' => 'The image must not be greater than 3MB.',
            'section2_image.max' => 'The image must not be greater than 3MB.',
            'section3_image.max' => 'The image must not be greater than 3MB.',
            'section4_image.max' => 'The image must not be greater than 3MB.',
            'section5_image1.max' => 'The image must not be greater than 3MB.',
            'section5_image2.max' => 'The image must not be greater than 3MB.',
        ]);

        $data = ContentEpc::first();
        $data->point1_title = $request->point1_title;
        $data->section1_title = $request->section1_title;
        $data->section1_desc = $request->section1_desc;
        $data->point2_title = $request->point2_title;
        $data->section2_title = $request->section2_title;
        $data->section2_desc = $request->section2_desc;
        $data->section3_title = $request->section3_title;
        $data->section3_desc = $request->section3_desc;
        $data->section4_title = $request->section4_title;
        $data->section4_desc = $request->section4_desc;
        $data->section5_title = $request->section5_title;
        $data->section5_desc = $request->section5_desc;

        // image upload
        if (isset($request->section1_image)) {
            $fileUpload1 = fileUpload($request->section1_image, 'banner');
            $data->section1_image = $fileUpload1['file'][2];
        }
        // image upload
        if (isset($request->section2_image)) {
            $fileUpload2 = fileUpload($request->section2_image, 'home-page');
            $data->section2_image = $fileUpload2['file'][2];
        }
        // image upload
        if (isset($request->section3_image)) {
            $fileUpload3 = fileUpload($request->section3_image, 'home-page');
            $data->section3_image = $fileUpload3['file'][2];
        }
        // image upload
        if (isset($request->section4_image)) {
            $fileUpload4 = fileUpload($request->section4_image, 'home-page');
            $data->section4_image = $fileUpload4['file'][2];
        }
        // image upload
        if (isset($request->section5_image1)) {
            $fileUpload5 = fileUpload($request->section5_image1, 'home-page');
            $data->section5_image1 = $fileUpload5['file'][2];
        }
        // image upload
        if (isset($request->section5_image2)) {
            $fileUpload6 = fileUpload($request->section5_image2, 'home-page');
            $data->section5_image2 = $fileUpload6['file'][2];
        }
        
        $data->save();

        return redirect()->back()->with('success', 'Content updated');
    }
    public function aboutUpdate(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'id' => 'required|integer|min:1',
            'page_title' => 'required|string|max:255',
            'page_banner' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif,svg|max:1000',

            'section1_title' => 'required|string|max:255',
            'section1_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif,svg|max:1000',
            'section1_desc' => 'required|string|max:1000',
            'section1_video_link' => 'required|string|max:255',

            'section2_title' => 'required|string|max:255',
            'section2_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif,svg|max:1000',
            'section2_desc' => 'required|string|max:1000',

            'section3_title' => 'required|string|max:255',
            'section3_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif,svg|max:1000',
            'section3_desc' => 'required|string|max:1000',

            'section4_title' => 'required|string|max:255',
            'section4_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif,svg|max:1000',
            'section4_desc' => 'required|string|max:1000',

            'section5_title' => 'required|string|max:255',
            'section5_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif,svg|max:1000',
            'section5_desc' => 'required|string|max:1000',

        ], [
            'page_banner.max' => 'The image must not be greater than 1MB.',
            'section1_image.max' => 'The image must not be greater than 1MB.',
            'section2_image.max' => 'The image must not be greater than 1MB.',
            'section3_image.max' => 'The image must not be greater than 1MB.',
            'section4_image.max' => 'The image must not be greater than 1MB.',
            'section5_image.max' => 'The image must not be greater than 1MB.',
        ]);

        $data = ContentAbout::first();
        $data->page_title = $request->page_title;

        $data->section1_title = $request->section1_title;
        $data->section1_desc = $request->section1_desc;
        $data->section1_video_link = $request->section1_video_link;

        $data->section2_title = $request->section2_title;
        $data->section2_desc = $request->section2_desc;

        $data->section3_title = $request->section3_title;
        $data->section3_desc = $request->section3_desc;

        $data->section4_title = $request->section4_title;
        $data->section4_desc = $request->section4_desc;

        $data->section5_title = $request->section5_title;
        $data->section5_desc = $request->section5_desc;

        // image upload
        if (isset($request->page_banner)) {
            $fileUpload1 = fileUpload($request->page_banner, 'about-page');
            $data->page_banner = $fileUpload1['file'][2];
        }
        // image upload
        if (isset($request->section1_image)) {
            $fileUpload2 = fileUpload($request->section1_image, 'about-page');
            $data->section1_image = $fileUpload2['file'][2];
        }
        // image upload
        if (isset($request->section2_image)) {
            $fileUpload3 = fileUpload($request->section2_image, 'about-page');
            $data->section2_image = $fileUpload3['file'][2];
        }
        // image upload
        if (isset($request->section3_image)) {
            $fileUpload4 = fileUpload($request->section3_image, 'about-page');
            $data->section3_image = $fileUpload4['file'][2];
        }
        // image upload
        if (isset($request->section4_image)) {
            $fileUpload5 = fileUpload($request->section4_image, 'about-page');
            $data->section4_image = $fileUpload5['file'][2];
        }
        // image upload
        if (isset($request->section5_image)) {
            $fileUpload6 = fileUpload($request->section5_image, 'about-page');
            $data->section5_image = $fileUpload6['file'][2];
        }

        $data->save();

        return redirect()->back()->with('success', 'Content updated');
    }

    public function contactUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|min:1',
            'page_title' => 'required|string|max:255',
            'page_banner' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif,svg|max:1000',
            'form_title' => 'required|string|max:255',
            'form_submit_btn_text' => 'required|string|max:255',
        ], [
            'page_banner.max' => 'The image must not be greater than 1MB.',
        ]);

        $data = ContentContact::first();
        $data->page_title = $request->page_title;

        $data->registerd_office_title = $request->registerd_office_title;
        $data->registerd_office_address = $request->registerd_office_address;
        $data->registerd_office_tell = $request->registerd_office_tell;
        $data->registerd_office_fax = $request->registerd_office_fax;
        $data->registerd_office_email = $request->registerd_office_email;
        $data->registerd_office_map = $request->registerd_office_map;

        $data->corporate_office_title = $request->corporate_office_title;
        $data->corporate_office_address = $request->corporate_office_address;
        $data->corporate_office_tell = $request->corporate_office_tell;
        $data->corporate_office_fax = $request->corporate_office_fax;
        $data->corporate_office_email = $request->corporate_office_email;
        $data->corporate_office_map = $request->corporate_office_map;

        $data->plant_title = $request->plant_title;
        $data->plant_address = $request->plant_address;
        $data->plant_tell = $request->plant_tell;
        $data->plant_fax = $request->plant_fax;
        $data->plant_email = $request->plant_email;
        $data->plant_map = $request->plant_map;

        $data->plant_title1 = $request->plant_title1;
        $data->plant_address1 = $request->plant_address1;
        $data->plant_tell1 = $request->plant_tell1;
        $data->plant_fax1 = $request->plant_fax1;
        $data->plant_email1 = $request->plant_email1;
        $data->plant_map1 = $request->plant_map1;

        $data->form_title = $request->form_title;
        $data->form_submit_btn_text = $request->form_submit_btn_text;
        $data->form_desc = $request->form_desc;

        // image upload
        if (isset($request->page_banner)) {
            $fileUpload1 = fileUpload($request->page_banner, 'contact-page');
            $data->page_banner = $fileUpload1['file'][2];
        }

        $data->save();

        return redirect()->back()->with('success', 'Content updated');
    }

    public function settingsUpdate(Request $request)
    {
        $request->validate([
            'official_phone1' => 'required|integer|digits:10',
            'official_phone2' => 'nullable|integer|digits:10',
            'official_email' => 'required|email|min:5|max:255',
            'website_link' => 'required|min:5|max:255',
            'full_company_name' => 'required|string|min:1|max:255',
            'pretty_company_name' => 'required|string|min:1|max:255',
            'company_short_desc' => 'required|string|min:5|max:1000',
            'company_full_address' => 'required|string|min:5|max:1000',
            'google_map_address_link' => 'required|string|min:5',
        ]);

        Setting::where('title', 'official_phone1')->update([
            'content' => $request->official_phone1
        ]);
        Setting::where('title', 'website_link')->update([
            'content' => $request->website_link
        ]);
        Setting::where('title', 'official_phone2')->update([
            'content' => $request->official_phone2
        ]);
        Setting::where('title', 'official_email')->update([
            'content' => $request->official_email
        ]);
        Setting::where('title', 'full_company_name')->update([
            'content' => $request->full_company_name
        ]);
        Setting::where('title', 'pretty_company_name')->update([
            'content' => $request->pretty_company_name
        ]);
        Setting::where('title', 'company_short_desc')->update([
            'content' => $request->company_short_desc
        ]);
        Setting::where('title', 'company_full_address')->update([
            'content' => $request->company_full_address
        ]);
        Setting::where('title', 'google_map_address_link')->update([
            'content' => $request->google_map_address_link
        ]);

        return redirect()->back()->with('success', 'Content updated');
    }

}
