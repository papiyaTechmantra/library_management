<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Career;
use App\Models\AdmissionForm;
use App\Models\JobCategory;
use App\Models\CarrerHigherStudies;
use App\Models\CareerExperience;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class JobsController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->keyword ?? '';
        $query = Job::query();

        $query->when($keyword, function($query) use ($keyword) {
            $query->where('title', 'like', '%'.$keyword.'%')
                ->orWhere('slug', 'like', '%'.$keyword.'%')
                ->orWhere('sub_title', 'like', '%'.$keyword.'%')
                ->orWhere('school_name', 'like', '%'.$keyword.'%')
                ->orWhere('location', 'like', '%'.$keyword.'%');
        });

        $data = $query->latest('id')->where('deleted_at', 1)->paginate(25);
        return view('admin.jobs.index', compact('data'));
    }

    public function create(Request $request){
        $category = JobCategory::orderBy('title', 'ASC')->where('status', 1)->where('deleted_at', 1)->get();
        return view('admin.jobs.create', compact('category'));
    }

    public function store(Request $request)
    {
        // Start a database transaction
        DB::beginTransaction();
        $request->validate([
            'title' => 'required|string|max:500',
            'sub_title' => 'required|string|max:500',
            'category' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'school' => 'required|string|max:500',
            'gender' => 'required',
            'experience' => 'required',
            'no_of_vacancy' => 'required',
        ]);
        try {
            // Validate the request data

            // Create a new job instance
            $job = new Job();
            $job->title = $request->title;
            $job->sub_title = $request->sub_title;
            $job->category = $request->category;
            $job->location = $request->location;
            $job->school_name = $request->school;
            $job->gender = $request->gender;
            $job->experience = $request->experience;
            $job->no_of_vacancy = $request->no_of_vacancy;
            $job->save();
            $job->slug = $job->id . '-' . Str::slug($request->title);
            $job->save();
            // Commit the transaction if everything is successful
            DB::commit();

            return redirect()->route('admin.job.list')->with('success', 'Job created');
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollback();

            // Handle the exception
            return redirect()->back()->with('failure', 'Failed to create job. Please try again.');
        }
    }

    public function edit(Request $request, $id)
    {
        $category = JobCategory::orderBy('title', 'ASC')->where('status', 1)->where('deleted_at', 1)->get();
        $data = Job::findOrFail($id);
        return view('admin.jobs.edit', compact('data','category'));
    }

    public function update(Request $request)
    {
     // Start a database transaction
        DB::beginTransaction();

        try {
            // Validate the request data
            $request->validate([
                'title' => 'required|string|max:500',
                'experience' => 'required|string|max:500',
                'sub_title' => 'required|string|max:500',
                'category' => 'required|string|max:255',
                'location' => 'required|string|max:255',
                'school' => 'required|string|max:500',
                'gender' => 'required',
                'no_of_vacancy' => 'required',
            ]);

            // Find the job by its ID
            $job = Job::findOrFail($request->id);

            // Update the job attributes
            $job->title = $request->title;
            $job->experience = $request->experience;
            $job->sub_title = $request->sub_title;
            $job->category = $request->category;
            $job->location = $request->location;
            $job->school_name = $request->school;
            $job->gender = $request->gender;
            $job->no_of_vacancy = $request->no_of_vacancy;
            $job->save();
            // Update the slug
            $job->slug = $job->id . '-' . Str::slug($request->title);
            $job->save();

            // Commit the transaction if everything is successful
            DB::commit();

            return redirect()->route('admin.job.list')->with('success', 'Job updated');
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollback();

            // Handle the exception
            return redirect()->back()->with('failure', 'Failed to update job. Please try again.');
        }
    }

    public function delete(Request $request, $id){
        DB::beginTransaction();
        try {
            $data = Job::findOrFail($id);
            $data->deleted_at = 0;
            $data->save();
            // Commit the transaction if the deletion is successful
            DB::commit();
            return redirect()->route('admin.job.list')->with('success', 'Job deleted');
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollback();
            // Log the exception if needed
            \Log::error($e);
            // Redirect back with an error message
            return redirect()->back()->with('failure', 'Failed to delete Job. Please try again.');
        }
    }
    public function status(Request $request, $id)
    {
        $data = Job::findOrFail($id);
        $data->status = ($data->status == 1) ? 0 : 1;
        $data->update();
        return response()->json([
            'status' => 200,
            'message' => 'Status updated',
        ]);
    }

    public function UserApplication(Request $request){
        $keyword = $request->keyword ?? '';
        $query = Career::query();

        $query->when($keyword, function($query) use ($keyword) {
            $query->where('name', 'like', '%'.$keyword.'%')
                
                ->orWhere('registration_id', 'like', '%'.$keyword.'%')
                ->orWhere('email', 'like', '%'.$keyword.'%')
                ->orWhere('phone', 'like', '%'.$keyword.'%')
                ->orWhere('pincode', 'like', '%'.$keyword.'%');
        });
        $data = $query->latest('id')->where('deleted_at', 1)->paginate(25);
        return view('admin.user-application.index', compact('data'));
    }

    public function UserApplicationView($id){
        $data = Career::findOrFail($id);
        $higherStudies = CarrerHigherStudies::where('career_id',$id)->get();
        $experience = CareerExperience::where('career_id',$id)->get();

        return view('admin.user-application.view', compact('data','higherStudies','experience'));
    }
    
    public function AdmissionApplication(Request $request){
        $start_date = $request->start_date ?? '';
        $end_date = $request->end_date ?? '';
        $keyword = $request->keyword ?? '';
        $query = AdmissionForm::query();

        $query->when($start_date && $end_date, function($query) use ($start_date, $end_date) {
            $query->where('created_at', '>=', $start_date)
                  ->where('created_at', '<=', $end_date);
        });


        $query->when($keyword, function($query) use ($keyword) {
            $query->where('name', 'like', '%'.$keyword.'%')
                ->orWhere('dob', 'like', '%'.$keyword.'%')
                ->orWhere('class', 'like', '%'.$keyword.'%')
                ->orWhere('mobile', 'like', '%'.$keyword.'%')
                ->orWhere('email', 'like', '%'.$keyword.'%')
                ->orWhere('utm_source', 'like', '%'.$keyword.'%')
                ->orWhere('utm_medium', 'like', '%'.$keyword.'%')
                ->orWhere('utm_campaign', 'like', '%'.$keyword.'%')
                ->orWhere('utm_term', 'like', '%'.$keyword.'%')
                ->orWhere('utm_content', 'like', '%'.$keyword.'%')
                ->orWhere('pincode', 'like', '%'.$keyword.'%');
        });
        
        $data = $query->latest('id')->paginate(25);
        return view('admin.admission-application.index', compact('data'));
    }
     public function AdmissionApplicationExport(Request $request){
        $start_date = $request->start_date ?? '';
        $end_date = $request->end_date ?? '';
        $keyword = $request->keyword ?? '';
        $query = AdmissionForm::query();

        $query->when($start_date && $end_date, function($query) use ($start_date, $end_date) {
            $query->where('created_at', '>=', $start_date)
                  ->where('created_at', '<=', $end_date);
        });


        $query->when($keyword, function($query) use ($keyword) {
            $query->where('name', 'like', '%'.$keyword.'%')
                ->orWhere('dob', 'like', '%'.$keyword.'%')
                ->orWhere('class', 'like', '%'.$keyword.'%')
                ->orWhere('mobile', 'like', '%'.$keyword.'%')
                ->orWhere('email', 'like', '%'.$keyword.'%')
                ->orWhere('utm_source', 'like', '%'.$keyword.'%')
                ->orWhere('utm_medium', 'like', '%'.$keyword.'%')
                ->orWhere('utm_campaign', 'like', '%'.$keyword.'%')
                ->orWhere('utm_term', 'like', '%'.$keyword.'%')
                ->orWhere('utm_content', 'like', '%'.$keyword.'%')
                ->orWhere('pincode', 'like', '%'.$keyword.'%');
        });
        
        $data = $query->latest('id')->get();
         if (count($data) > 0) {
            $delimiter = ",";
            $filename = "admission_application-".date('Y-m-d').".csv";
            // Create a file pointer
            $f = fopen('php://memory', 'w');

            // Set column headers
            $fields = array('Student Name','Parent Name','Email','Mobile','DOB','Class','Pin Code','Source','Medium','Campaign','Term','Content','Date');
            fputcsv($f, $fields, $delimiter);

            $count = 1;
            foreach($data as $key=> $row) {
                    $mobile = (!empty($row['country_code']) ? $row['country_code'] . ' ' : '') . $row['mobile'];
                    $lineData = array(
                        $row['name'] ? $row['name'] : '',
                        $row['parent_name'] ? $row['parent_name'] : '',
                        $row['email'] ? $row['email'] : '',
                        $mobile,
                        !empty($row['dob']) ? date('d-m-Y', strtotime($row['dob'])) : '',
                        $row['class'] ? $row['class'] : '',
                        $row['pincode'] ? $row['pincode'] : '',
                        $row['utm_source'] ? $row['utm_source'] : '',
                        $row['utm_medium'] ? $row['utm_medium'] : '',
                        $row['utm_campaign'] ? $row['utm_campaign'] : '',
                        $row['utm_term'] ? $row['utm_term'] : '',
                        $row['utm_content'] ? $row['utm_content'] : '',
                        date("d-m-Y h:i a",strtotime($row['created_at'])) ? date("d-m-Y h:i a",strtotime($row['created_at'])) : ''
                    );
                    fputcsv($f, $lineData, $delimiter);

                    $count++;
            }
                

            // Move back to beginning of file
            fseek($f, 0);

            // Set headers to download file rather than displayed
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '";');

            //output all remaining data on a file pointer
            fpassthru($f);
        }
    }

    public function AdmissionApplicationView($id){
        $data = AdmissionForm::findOrFail($id);
        return view('admin.admission-application.view', compact('data'));
    }
   
}
