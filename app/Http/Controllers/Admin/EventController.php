<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Faculty;
use App\Models\Blog;


use App\Interfaces\EventInterface;
use DB;
use Illuminate\Validation\Rule;
class EventController extends Controller
{
    public function __construct(EventInterface $EventRepository){
        $this->EventRepository = $EventRepository;
    }
   
    public function EventIndex(Request $request)
    {
        if (!empty($request->keyword)) {
            $data = $this->EventRepository->getSearchEvent($request->keyword);
        } else {
            $data = $this->EventRepository->listAllEvents();
        }
        return view('admin.event.index', compact('data'));
    }

    public function EventCreate(Request $request)
    {
        return view('admin.event.create');
    }
    public function eventStore(Request $request){
        // Validate request data
        $request->validate([
            'title' => 'required|string|max:255|unique:events,title',
            'short_desc' => 'required|string',
            'description' => 'required|string',
            'event_category' => 'required|string|max:255',
            'pic' => 'required|mimes:jpg,jpeg,png,gif,svg|max:1000'
        ], [
            'pic.max' => 'The image must not be greater than 1MB.',
        ]);

        // Begin transaction
        DB::beginTransaction();

        try {
            // Move uploaded file
            $file = $request->file('pic');
            $fileName = time() . rand(10000, 99999) . '.' . $file->getClientOriginalExtension(); // Generate unique filename
            $filePath = 'eventUploads/' . $fileName; // Construct full path
            $file->move(public_path('eventUploads'), $fileName); // Move file to destination

            // Create new event
            $data = new Event;
            $data->title = $request->title;
            $data->slug = slugGenerate($request->title, 'events'); // Assuming slugGenerate function exists
            $data->short_desc = $request->short_desc;
            $data->desc = $request->description;
            $data->event_category = $request->event_category;
            $data->image = $filePath; // Save full path
            $data->save();

            // Commit the transaction if everything is successful
            DB::commit();
            
            return redirect()->route('admin.event.list.all')->with('success', 'New Event created');
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollback();

            // You can log the exception if needed
            \Log::error($e);

            // Redirect back with an error message
            return redirect()->back()->with('failure', 'Failed to create Event. Please try again.');
        }
    }
    public function EventEdit($id)
    {
        $data = $this->EventRepository->findEventById($id);
        return view('admin.event.edit', compact('data'));
    }
    public function eventUpdate(Request $request)
    {
        DB::beginTransaction();
    
        $request->validate([
            'title' => ['required', 'string', 'max:255', Rule::unique('events', 'title')->ignore($request->id)],
            'short_desc' => ['required', 'string'],
            'description' => ['required', 'string'],
            'event_category' => ['required', 'string', 'max:255'],
            'pic' => ['nullable', 'mimes:jpg,jpeg,png,gif,svg', 'max:1000'],
        ], ['pic.max' => 'The image must not be greater than 1MB.']);
        
        try {
            $data = Event::findOrFail($request->id);
            $data->title = $request->title;
            $data->slug = slugGenerateUpdate($request->title, 'events', $request->id);
            $data->short_desc = $request->short_desc;
            $data->desc = $request->description;
            $data->event_category = $request->event_category;
    
            if ($request->hasFile('pic')) { // Check if a new image is uploaded
                $file = $request->file('pic');
                $fileName = time() . rand(10000, 99999) . '.' . $file->getClientOriginalExtension();
                $filePath = 'eventUploads/' . $fileName; // Construct full path
                $file->move(public_path('eventUploads'), $fileName);
                $data->image = $filePath;
            }
    
            $data->save();
            // Commit the transaction if everything is successful
            DB::commit();
            return redirect()->route('admin.event.list.all')->with('success', 'Event updated successfully');
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollback();
            // You can log the exception if needed
            \Log::error($e);
            // Redirect back with an error message
            return redirect()->back()->with('failure', 'Failed to update Event. Please try again.');
        }
    }
    
    public function EventStatus(Request $request, $id)
    {
        $data = $this->EventRepository->findEventById($id);
        $data->status = ($data->status == 1) ? 0 : 1;
        $data->update();
        return response()->json([
            'status' => 200,
            'message' => 'Status updated',
        ]);
    }

    public function EventDelete(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $data = Event::findOrFail($id);
            $data->deleted_at = 0;
            $data->save();
            // Commit the transaction if the deletion is successful
            DB::commit();
            return redirect()->route('admin.event.list.all')->with('success', 'Event deleted');
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollback();
            // Log the exception if needed
            \Log::error($e);
            // Redirect back with an error message
            return redirect()->back()->with('failure', 'Failed to delete Event. Please try again.');
        }
    }
    public function status(Request $request, $id)
    {
        $data = Event::findOrFail($id);
        $data->status = ($data->status == 1) ? 0 : 1;
        $data->update();

        return response()->json([
            'status' => 200,
            'message' => 'Status updated',
        ]);
    }



    // Blog Management
    public function BlogIndex(Request $request)
    {
        if (!empty($request->keyword)) {
            $data = $this->EventRepository->getSearchBlog($request->keyword);
        } else {
            $data = $this->EventRepository->listAllBlogs();
        }
        return view('admin.blog.index', compact('data'));
    }

    public function BlogCreate(Request $request)
    {
        $auther = Faculty::orderBy('name', 'ASC')->where('status',1)->where('deleted_at', 1)->get();
        return view('admin.blog.create', compact('auther'));
    }
    public function BlogStore(Request $request){
        // Validate request data
        $request->validate([
            'title' => 'required|string|max:255|unique:events,title',
            'short_desc' => 'required|string',
            'description' => 'required|string',
            'faculty' => 'required',
            'event_category' => 'required|string|max:255',
            'pic' => 'required|mimes:jpg,jpeg,png,gif,svg|max:1000'
        ], [
            'pic.max' => 'The image must not be greater than 1MB.',
        ]);

        // Begin transaction
        DB::beginTransaction();

        try {
            // Move uploaded file
            $file = $request->file('pic');
            $fileName = time() . rand(10000, 99999) . '.' . $file->getClientOriginalExtension(); // Generate unique filename
            $filePath = 'uploads/blog/' . $fileName; // Construct full path
            $file->move(public_path('uploads/blog/'), $fileName); // Move file to destination

            // Create new event
            $data = new Blog;
            $data->title = $request->title;
            $data->slug = slugGenerate($request->title, 'blogs'); // Assuming slugGenerate function exists
            $data->short_desc = $request->short_desc;
            $data->desc = $request->description;
            $data->uploaded_by = $request->faculty;
            $data->event_category = $request->event_category;
            $data->page_title = $request->page_title;
            $data->meta_title = $request->meta_title;
            $data->meta_description = $request->meta_description;
            $data->meta_keyword = $request->meta_keyword;
            $data->image = $filePath; // Save full path
            $data->save();

            // Commit the transaction if everything is successful
            DB::commit();
            
            return redirect()->route('admin.blog.list.all')->with('success', 'New blog created');
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollback();

            // You can log the exception if needed
            \Log::error($e);

            // Redirect back with an error message
            return redirect()->back()->with('failure', 'Failed to create blog. Please try again.');
        }
    }
    public function BlogEdit($id)
    {
        $data = $this->EventRepository->findBlogById($id);
        $auther = Faculty::orderBy('name', 'ASC')->where('status',1)->where('deleted_at', 1)->get();
        return view('admin.blog.edit', compact('data', 'auther'));
    }
    public function BlogUpdate(Request $request)
    {
        DB::beginTransaction();
    
        $request->validate([
            'title' => ['required', 'string', 'max:255', Rule::unique('events', 'title')->ignore($request->id)],
            'short_desc' => ['required', 'string'],
            'description' => ['required', 'string'],
            'faculty' => ['required'],
            'event_category' => ['required', 'string', 'max:255'],
            'pic' => ['nullable', 'mimes:jpg,jpeg,png,gif,svg', 'max:1000'],
        ], ['pic.max' => 'The image must not be greater than 1MB.']);
        
        try {
            $data = Blog::findOrFail($request->id);
            $data->title = $request->title;
            $data->slug = slugGenerateUpdate($request->title, 'events', $request->id);
            $data->short_desc = $request->short_desc;
            $data->desc = $request->description;
            $data->uploaded_by = $request->faculty;
            $data->event_category = $request->event_category;
            $data->page_title = $request->page_title;
            $data->meta_title = $request->meta_title;
            $data->meta_description = $request->meta_description;
            $data->meta_keyword = $request->meta_keyword;
    
            if ($request->hasFile('pic')) { // Check if a new image is uploaded
                $file = $request->file('pic');
                $fileName = time() . rand(10000, 99999) . '.' . $file->getClientOriginalExtension();
                $filePath = 'uploads/blog/' . $fileName; // Construct full path
                $file->move(public_path('uploads/blog/'), $fileName); // Move file to destination
                $data->image = $filePath;
            }
    
            $data->save();
            // Commit the transaction if everything is successful
            DB::commit();
            return redirect()->route('admin.blog.list.all')->with('success', 'Blog updated successfully');
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollback();
            // You can log the exception if needed
            \Log::error($e);
            // Redirect back with an error message
            return redirect()->back()->with('failure', 'Failed to update blog. Please try again.');
        }
    }
    
    public function BlogStatus(Request $request, $id)
    {
        $data = $this->EventRepository->findBlogById($id);
        $data->status = ($data->status == 1) ? 0 : 1;
        $data->update();
        return response()->json([
            'status' => 200,
            'message' => 'Status updated',
        ]);
    }

    public function BlogDelete(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $data = Blog::findOrFail($id);
            $data->deleted_at = 0;
            $data->save();
            // Commit the transaction if the deletion is successful
            DB::commit();
            return redirect()->route('admin.blog.list.all')->with('success', 'Blog deleted');
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollback();
            // Log the exception if needed
            \Log::error($e);
            // Redirect back with an error message
            return redirect()->back()->with('failure', 'Failed to delete blog. Please try again.');
        }
    }
}
