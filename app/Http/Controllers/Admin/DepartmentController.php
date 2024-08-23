<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Department;
use App\Models\Facility;
use App\Models\SubFacility;
use App\Models\StudentClass;
use App\Models\ScheduleContent;
use App\Models\DailySchedule;
use App\Models\TeachingProcess;
use App\Models\Schedule;
use App\Models\WhyChooseUs;

use App\Interfaces\DepartmentInterface;
use DB;
use Illuminate\Validation\Rule;
class DepartmentController extends Controller
{
    public function __construct(DepartmentInterface $DepartmentRepository)
    {
        $this->DepartmentRepository = $DepartmentRepository;
    }
    public function status(Request $request, $id)
    {
        $data = Department::findOrFail($id);
        $data->status = ($data->status == 1) ? 0 : 1;
        $data->update();

        return response()->json([
            'status' => 200,
            'message' => 'Status updated',
        ]);
    }

    //Personal Management -> Department
    public function DepartmentIndex(Request $request)
    {
        if (!empty($request->keyword)) {
            $data = $this->DepartmentRepository->getSearchDepartment($request->keyword);
        } else {
            $data = $this->DepartmentRepository->listAllDepartments();
        }
        return view('admin.department.index', compact('data'));
    }

    public function DepartmentCreate(Request $request)
    {
        return view('admin.department.create');
    }
    public function DepartmentStore(Request $request)
    {
        DB::beginTransaction();
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
        ]);
        
        try {
            $data = new Department;
            $data->name = $request->name;
            $data->save();
            // Commit the transaction if everything is successful
            DB::commit();
            return redirect()->route('admin.department.list.all')->with('success', 'New Department created');
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollback();
            // You can log the exception if needed
            \Log::error($e);
            // Redirect back with an error message
            return redirect()->back()->with('failure', 'Failed to create Department. Please try again.');
        }
    }
    public function DepartmentEdit($id)
    {
        $data = $this->DepartmentRepository->findDepartmentById($id);
        return view('admin.department.edit', compact('data'));
    }
    public function DepartmentUpdate(Request $request)
    {
        // dd($request->all());
        // Start a database transaction
        DB::beginTransaction();

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('departments', 'name')->ignore($request->id),
            ],
        ]);

        try {
            $data = Department::findOrFail($request->id);
            $data->name = $request->name;                  
            $data->save();
            // Commit the transaction if everything is successful
            DB::commit();
            return redirect()->route('admin.department.list.all')->with('success', 'Department updated successfully');
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollback();
            // You can log the exception if needed
            \Log::error($e);
            // Redirect back with an error message
            return redirect()->back()->with('failure', 'Failed to update Department. Please try again.');
        }
    }
    public function DepartmentStatus(Request $request, $id)
    {
        $data = $this->DepartmentRepository->findDepartmentById($id);
        $data->status = ($data->status == 1) ? 0 : 1;
        $data->update();
        return response()->json([
            'status' => 200,
            'message' => 'Status updated',
        ]);
    }

    public function DepartmentDelete(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $data = Department::findOrFail($id);
            $data->deleted_at = 0;
            $data->save();
            // Commit the transaction if the deletion is successful
            DB::commit();
            return redirect()->route('admin.department.list.all')->with('success', 'Department deleted');
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollback();
            // Log the exception if needed
            \Log::error($e);
            // Redirect back with an error message
            return redirect()->back()->with('failure', 'Failed to delete Department. Please try again.');
        }
    }

    //Personal Management -> Teaching Process List
    public function TeachingIndex(Request $request){
        $data = TeachingProcess::latest()->get();
        return view('admin.teaching.index', compact('data'));
    }

    public function TeachingCreate(Request $request){
        return view('admin.teaching.create');
    }
    public function TeachingStore(Request $request)
    {
        DB::beginTransaction();
        $request->validate([
            'title' => 'required|string|max:255|unique:teaching_process,title',
            'description' => 'required|string',
            'image' => 'required|mimes:jpg,jpeg,png,gif,svg,webp|max:1000',
        ]);
        
        try {
            $data = new TeachingProcess;
            $data->title = $request->title;
            $data->desc = $request->description;
            // Faculty image
            if ($request->hasFile('image')) {
                $file1 = $request->file('image');
                $fileImageName = time() . rand(10000, 99999) . '.' . $file1->getClientOriginalExtension();
                $file1->move(public_path('uploads/teaching'), $fileImageName);
                // Store the full path of the uploaded file
                $data->image =  'uploads/teaching/' . $fileImageName;
            }
            $data->save();
            // Commit the transaction if everything is successful
            DB::commit();
            return redirect()->route('admin.teaching.list.all')->with('success', 'New process created');
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollback();
            // You can log the exception if needed
            \Log::error($e);
            // Redirect back with an error message
            return redirect()->back()->with('failure', 'Failed to create process. Please try again.');
        }
    }
    public function TeachingEdit($id)
    {
        $data = TeachingProcess::findOrFail($id);
        return view('admin.teaching.edit', compact('data'));
    }
    public function TeachingUpdate(Request $request)
    {
        // Start a database transaction
        DB::beginTransaction();

        $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('facilities', 'title')->ignore($request->id),
            ],
            'description'=>[
                'required',
                'string',
            ],
            'image'=>[
                'mimes:jpg,jpeg,png,gif,svg,webp',
                'max:1000'
            ],
        ]);

        try {
            $data = TeachingProcess::findOrFail($request->id);
            $data->title = $request->title;
            $data->desc = $request->description;
            // Image update
            if ($request->hasFile('image')) {
                $file1 = $request->file('image');
                $fileImageName = time() . rand(10000, 99999) . '.' . $file1->getClientOriginalExtension();
                $file1->move(public_path('uploads/teaching'), $fileImageName);

                // Store the full path of the uploaded image
                $data->image = 'uploads/teaching/' . $fileImageName;
            }             
            $data->save();
            // Commit the transaction if everything is successful
            DB::commit();
            return redirect()->route('admin.teaching.list.all')->with('success', 'Process updated successfully');
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollback();
            // You can log the exception if needed
            \Log::error($e);
            // Redirect back with an error message
            return redirect()->back()->with('failure', 'Failed to update process. Please try again.');
        }
    }
    public function TeachingStatus(Request $request, $id)
    {
        $data = TeachingProcess::findOrFail($request->id);
        $data->status = ($data->status == 1) ? 0 : 1;
        $data->update();
        return response()->json([
            'status' => 200,
            'message' => 'Status updated',
        ]);
    }

    public function TeachingDelete(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $data = TeachingProcess::findOrFail($id);
            $data->delete();
            // Commit the transaction if the deletion is successful
            DB::commit();
            return redirect()->route('admin.teaching.list.all')->with('success', 'Process deleted');
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollback();
            // Log the exception if needed
            \Log::error($e);
            // Redirect back with an error message
            return redirect()->back()->with('failure', 'Failed to delete process. Please try again.');
        }
    }
    
    //Personal Management -> Why  choolse us
    public function ChooseUsIndex(Request $request){
        $data = WhyChooseUs::latest()->get();
        return view('admin.choose_us.index', compact('data'));
    }

    public function ChooseUsCreate(Request $request){
        return view('admin.choose_us.create');
    }
    public function ChooseUsStore(Request $request)
    {
        DB::beginTransaction();
        $request->validate([
            'title' => 'required|string|max:255|unique:why_chhose_us,title',
            'description' => 'required|string',
            'image' => 'required|mimes:jpg,jpeg,png,gif,svg,webp|max:1000',
        ]);
        
        try {
            $data = new WhyChooseUs;
            $data->title = $request->title;
            $data->desc = $request->description;
            // Faculty image
            if ($request->hasFile('image')) {
                $file1 = $request->file('image');
                $fileImageName = time() . rand(10000, 99999) . '.' . $file1->getClientOriginalExtension();
                $file1->move(public_path('uploads/teaching'), $fileImageName);
                // Store the full path of the uploaded file
                $data->image =  'uploads/teaching/' . $fileImageName;
            }
            $data->save();
            // Commit the transaction if everything is successful
            DB::commit();
            return redirect()->route('admin.choose_us.list.all')->with('success', 'New data created');
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollback();
            // You can log the exception if needed
            \Log::error($e);
            // Redirect back with an error message
            return redirect()->back()->with('failure', 'Failed to create data. Please try again.');
        }
    }
    public function ChooseUsEdit($id)
    {
        $data = WhyChooseUs::findOrFail($id);
        return view('admin.choose_us.edit', compact('data'));
    }
    public function ChooseUsUpdate(Request $request)
    {
        // Start a database transaction
        DB::beginTransaction();

        $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('why_chhose_us', 'title')->ignore($request->id),
            ],
            'description'=>[
                'required',
                'string',
            ],
            'image'=>[
                'mimes:jpg,jpeg,png,gif,svg,webp',
                'max:1000'
            ],
        ]);

        try {
            $data = WhyChooseUs::findOrFail($request->id);
            $data->title = $request->title;
            $data->desc = $request->description;
            // Image update
            if ($request->hasFile('image')) {
                $file1 = $request->file('image');
                $fileImageName = time() . rand(10000, 99999) . '.' . $file1->getClientOriginalExtension();
                $file1->move(public_path('uploads/teaching'), $fileImageName);

                // Store the full path of the uploaded image
                $data->image = 'uploads/teaching/' . $fileImageName;
            }             
            $data->save();
            // Commit the transaction if everything is successful
            DB::commit();
            return redirect()->route('admin.choose_us.list.all')->with('success', 'Data updated successfully');
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollback();
            // You can log the exception if needed
            \Log::error($e);
            // Redirect back with an error message
            return redirect()->back()->with('failure', 'Failed to update data. Please try again.');
        }
    }
    public function ChooseUsStatus(Request $request, $id)
    {
        $data = WhyChooseUs::findOrFail($request->id);
        $data->status = ($data->status == 1) ? 0 : 1;
        $data->update();
        return response()->json([
            'status' => 200,
            'message' => 'Status updated',
        ]);
    }

    public function ChooseUsDelete(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $data = WhyChooseUs::findOrFail($id);
            $data->delete();
            // Commit the transaction if the deletion is successful
            DB::commit();
            return redirect()->route('admin.choose_us.list.all')->with('success', 'data deleted');
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollback();
            // Log the exception if needed
            \Log::error($e);
            // Redirect back with an error message
            return redirect()->back()->with('failure', 'Failed to delete data. Please try again.');
        }
    }
    

    //Personal Management -> Facilities List
    public function FacilityIndex(Request $request)
    {
        if (!empty($request->keyword)) {
            $data = $this->DepartmentRepository->getSearchFacility($request->keyword);
        } else {
            $data = $this->DepartmentRepository->listAllFacilities();
        }
        return view('admin.facility.index', compact('data'));
    }

    public function FacilityCreate(Request $request)
    {
        return view('admin.facility.create');
    }
    public function FacilityStore(Request $request)
    {
        DB::beginTransaction();
        $request->validate([
            'title' => 'required|string|max:255|unique:facilities,title',
            'description' => 'required|string',
            'logo' => 'required|mimes:jpg,jpeg,png,gif,svg,webp|max:1000',
            'image' => 'required|mimes:jpg,jpeg,png,gif,svg,webp|max:1000',
        ]);
        
        try {
            $data = new Facility;
            $data->title = $request->title;
            $data->slug = slugGenerate($request->title, 'facilities');
            $data->desc = $request->description;
            // Faculty logo
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $fileLogoName = time() . rand(10000, 99999) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/facility'), $fileLogoName);
                // Store the full path of the uploaded file
                $data->logo = 'uploads/facility/' . $fileLogoName;
            }

            // Faculty image
            if ($request->hasFile('image')) {
                $file1 = $request->file('image');
                $fileImageName = time() . rand(10000, 99999) . '.' . $file1->getClientOriginalExtension();
                $file1->move(public_path('uploads/facility'), $fileImageName);
                // Store the full path of the uploaded file
                $data->image =  'uploads/facility/' . $fileImageName;
            }
            // $data->page_title = $request->page_title;
            // $data->meta_title = $request->meta_title;
            // $data->meta_desc = $request->meta_description;
            // $data->meta_keyword = $request->meta_keyword;
            $data->save();
            // Commit the transaction if everything is successful
            DB::commit();
            return redirect()->route('admin.facility.list.all')->with('success', 'New Facility created');
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollback();
            // You can log the exception if needed
            \Log::error($e);
            // Redirect back with an error message
            return redirect()->back()->with('failure', 'Failed to create Facility. Please try again.');
        }
    }
    public function FacilityEdit($id)
    {
        $data = $this->DepartmentRepository->findFacilityById($id);
        return view('admin.facility.edit', compact('data'));
    }
    public function FacilityUpdate(Request $request)
    {
        // dd($request->all());
        // Start a database transaction
        DB::beginTransaction();

        $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('facilities', 'title')->ignore($request->id),
            ],
            'description'=>[
                'required',
                'string',
            ],
            'logo'=>[
                'mimes:jpg,jpeg,png,gif,svg,webp',
                'max:1000'
                
            ],
            'image'=>[
                'mimes:jpg,jpeg,png,gif,svg,webp',
                'max:1000'
            ],
        ]);

        try {
            $data = Facility::findOrFail($request->id);
            $data->title = $request->title;
            $data->slug = slugGenerateUpdate($request->title, 'facilities', $request->id);
            $data->desc = $request->description;
            //logo update
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $fileLogoName = time() . rand(10000, 99999) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/facility'), $fileLogoName);
            
                // Store the full path of the uploaded file
                $data->logo =  'uploads/facility/' . $fileLogoName;
            } else {
                $data->logo = $request->old_facility_logo;
            }
            // Image update
            if ($request->hasFile('image')) {
                $file1 = $request->file('image');
                $fileImageName = time() . rand(10000, 99999) . '.' . $file1->getClientOriginalExtension();
                $file1->move(public_path('uploads/facility'), $fileImageName);

                // Store the full path of the uploaded image
                $data->image = 'uploads/facility/' . $fileImageName;
            } else {
                $data->image = $request->old_facility_image;
            }

            // $data->page_title = $request->page_title;
            // $data->meta_title = $request->meta_title;
            // $data->meta_desc = $request->meta_description;
            // $data->meta_keyword = $request->meta_keyword;             
            $data->save();
            // Commit the transaction if everything is successful
            DB::commit();
            return redirect()->route('admin.facility.list.all')->with('success', 'Facility updated successfully');
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollback();
            // You can log the exception if needed
            \Log::error($e);
            // Redirect back with an error message
            return redirect()->back()->with('failure', 'Failed to update Facility. Please try again.');
        }
    }
    public function FacilityStatus(Request $request, $id)
    {
        $data = $this->DepartmentRepository->findFacilityById($id);
        $data->status = ($data->status == 1) ? 0 : 1;
        $data->update();
        return response()->json([
            'status' => 200,
            'message' => 'Status updated',
        ]);
    }

    public function FacilityDelete(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $data = Facility::findOrFail($id);
            $data->deleted_at = 0;
            $data->save();
            // Commit the transaction if the deletion is successful
            DB::commit();
            return redirect()->route('admin.facility.list.all')->with('success', 'Facility deleted');
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollback();
            // Log the exception if needed
            \Log::error($e);
            // Redirect back with an error message
            return redirect()->back()->with('failure', 'Failed to delete Facility. Please try again.');
        }
    }
    public function FacilityView(Request $request, $id){
        if (!empty($request->keyword)) {
            $data = $this->DepartmentRepository->getSearchSubfacility($request->keyword);
        } else {
            $data = $this->DepartmentRepository->listAllSubfacilities($id);
        }
        return view('admin.facility.view', compact('data','id'));
    }
    
    //subfacilty
    public function SubfacilityStore(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);
        
        try {
            $data = new SubFacility();
            $data->title = $request->title;
            $data->desc = $request->description;
            $data->facility_id = $request->facilityId;
            $data->save();
            // Commit the transaction if everything is successful
            DB::commit();
            return redirect()->back()->with('success', 'New SubFacility created');
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollback();
            // You can log the exception if needed
            \Log::error($e);
            // Redirect back with an error message
            return redirect()->back()->with('failure', 'Failed to create SubFacility. Please try again.');
        }
    }
    public function SubfacilityStatus(Request $request, $id)
    {
        $data = $this->DepartmentRepository->findSubfacilityById($id);
        $data->status = ($data->status == 1) ? 0 : 1;
        $data->update();
        return response()->json([
            'status' => 200,
            'message' => 'Status updated',
        ]);
    }

public function SubfacilityDelete(Request $request, $id)
{   
    DB::beginTransaction();
    try {
        $data = SubFacility::findOrFail($id);
        $data->deleted_at = 0;
        $data->save();
        // Commit the transaction if the deletion is successful
        DB::commit();
        return redirect()->back()->with('success', 'Subfacility deleted');
    } catch (\Exception $e) {
        // Rollback the transaction if an exception occurs
        DB::rollback();
        // Log the exception if needed
        \Log::error($e);
        // Redirect back with an error message
        return redirect()->back()->with('failure', 'Failed to delete SubFacility. Please try again.');
    }
}

public function SubfacilityEdit($id)
    {
        $data = $this->DepartmentRepository->findSubfacilityById($id);
        // dd($data);
        return response()->json($data);
        // return view('admin.facility.view', compact('data'));
    }

    public function SubfacilityUpdate(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = SubFacility::findOrFail($request->id);
            $data->title = $request->SubFacility_title;
            $data->desc = $request->SubFacility_description;             
            $data->facility_id = $request->facility_id;             
            $data->save();
            // Commit the transaction if everything is successful
            DB::commit();
            return redirect()->back()->with('success', 'SubFacility updated successfully');
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollback();
            // You can log the exception if needed
            \Log::error($e);
            // Redirect back with an error message
            return redirect()->back()->with('failure', 'Failed to update SubFacility. Please try again.');
        }
    }

    //class
    public function ClassIndex(Request $request)
    {
        if (!empty($request->keyword)) {
            $data = $this->DepartmentRepository->getSearchClass($request->keyword);
        } else {
            $data = $this->DepartmentRepository->listAllClasses();
        }
        return view('admin.class.index', compact('data'));
    }
    public function ClassCreate(Request $request)
    {
        return view('admin.class.create');
    }
    public function ClassStore(Request $request)
    {
        DB::beginTransaction();
        $request->validate([
            'name' => 'required|string|max:255|unique:student_classes,name',
            // 'page_title' => 'required|string|max:255',
            // 'meta_title' => 'required|string|max:255',
            // 'meta_description' => 'required|string',
            // 'meta_keyword' => 'required|string|max:255',            
        ]);       
        try {
            $data = new StudentClass();
            $data->name = $request->name;
            // $data->page_title = $request->page_title;
            // $data->meta_title = $request->meta_title;
            // $data->meta_desc = $request->meta_description;
            // $data->meta_keyword = $request->meta_keyword;
            $data->save();
            // Commit the transaction if everything is successful
            DB::commit();
            return redirect()->route('admin.class.list.all')->with('success', 'New Class created');
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollback();
            // You can log the exception if needed
            \Log::error($e);
            // Redirect back with an error message
            return redirect()->back()->with('failure', 'Failed to create Class. Please try again.');
        }
    }

    public function ClassStatus(Request $request, $id)
    {
        $data = $this->DepartmentRepository->findClassById($id);
        $data->status = ($data->status == 1) ? 0 : 1;
        $data->update();
        return response()->json([
            'status' => 200,
            'message' => 'Status updated',
        ]);
    }

    public function ClassDelete(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $data = StudentClass::findOrFail($id);
            $data->deleted_at = 0;
            $data->save();
            // Commit the transaction if the deletion is successful
            DB::commit();
            return redirect()->route('admin.class.list.all')->with('success', 'Class deleted');
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollback();
            // Log the exception if needed
            \Log::error($e);
            // Redirect back with an error message
            return redirect()->back()->with('failure', 'Failed to delete Class. Please try again.');
        }
    }
    public function ClassEdit($id)
    {
        $data = $this->DepartmentRepository->findClassById($id);
        return view('admin.class.edit', compact('data'));
    }
    
    public function ClassUpdate(Request $request)
    {
        // dd($request->all());
        // Start a database transaction
        DB::beginTransaction();

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('student_classes', 'name')->ignore($request->id),
            ],
        ]);

        try {
            $data = StudentClass::findOrFail($request->id);
            $data->name = $request->name;
            // $data->page_title = $request->page_title;
            // $data->meta_title = $request->meta_title;
            // $data->meta_desc = $request->meta_description;
            // $data->meta_keyword = $request->meta_keyword;             
            $data->save();
            // Commit the transaction if everything is successful
            DB::commit();
            return redirect()->route('admin.class.list.all')->with('success', 'Class updated successfully');
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollback();
            // You can log the exception if needed
            \Log::error($e);
            // Redirect back with an error message
            return redirect()->back()->with('failure', 'Failed to update Class. Please try again.');
        }
    }
    //Schedule Content
    public function ScheduleContentIndex(Request $request){
        if (!empty($request->keyword)) {
            $data = $this->DepartmentRepository->getSearchScheduleContent($request->keyword);
        } else {
            $data = $this->DepartmentRepository->listAllScheduleContents();
        }
        return view('admin.schedulecontent.index', compact('data'));
    }

    public function ScheduleContentEdit($id)
    {
        $data = $this->DepartmentRepository->findScheduleContentById($id);
        return view('admin.schedulecontent.edit', compact('data'));
    }
    public function ScheduleContentUpdate(Request $request)
    {
        // dd($request->all());
        // Start a database transaction
        DB::beginTransaction();

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('schedule_contents', 'name')->ignore($request->id),
            ],
            'description' => [
                'required',
                'string',
            ],
            'page_title'=>[
                'required',
                'string',
                'max:255',
            ],
            'meta_title'=>[
                'required',
                'string',
                'max:255',
            ],
            'meta_description'=>[
                'required',
                'string',
            ],
            'meta_keyword'=>[
                'required',
                'string',
                'max:255',
            ],
        ]);

        try {
            $data = ScheduleContent::findOrFail($request->id);
            $data->name = $request->name;
            $data->desc = $request->description;
            $data->page_title = $request->page_title;
            $data->meta_title = $request->meta_title;
            $data->meta_desc = $request->meta_description;
            $data->meta_keyword = $request->meta_keyword;             
            $data->save();
            // Commit the transaction if everything is successful
            DB::commit();
            return redirect()->route('admin.schedulecontent.list.all')->with('success', 'ScheduleContent updated successfully');
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollback();
            // You can log the exception if needed
            \Log::error($e);
            // Redirect back with an error message
            return redirect()->back()->with('failure', 'Failed to update ScheduleContent. Please try again.');
        }
    }
    
    public function ScheduleContentView(Request $request){
        // $data = ScheduleContent::findOrFail($id);
        $Class = $this->DepartmentRepository->listAllClassesForScheduleContent();
        $daily_schedule_data =$this->DepartmentRepository->listAllDailySchedules();
        return view('admin.schedulecontent.view', compact('Class','daily_schedule_data'));
    }

    public function DailyScheduleDelete($id){
        // return $id;
        $data = DailySchedule::findOrFail($id);
        $data->delete();
        return redirect()->back()->with('success', 'Class Schedule deleted successfully');
    }
    public function DailyScheduleStatus($id){
        $data = DailySchedule::findOrFail($id);
        $data->status = ($data->status == 1) ? 0 : 1;
        $data->update();
        return response()->json([
            'status' => 200,
            'message' => 'Status updated',
        ]);
    }
    public function DailyScheduleUpdate(Request $request){
        $update = DailySchedule::findOrFail($request->id);
        $update->class_id =$request->class;
        $update->day =$request->day;
        $update->save();
        return redirect()->back()->with('success', 'Data updated successfully');
    }
    public function DailyScheduleList($id){
        $DailySchedule = DailySchedule::findOrFail($id);
        $data = Schedule::where('daily_schedule_id', $id)->get();
        return view('admin.schedulecontent.schedule', compact('data','DailySchedule'));
    }
    public function ScheduleStore(Request $request){
        $data = new Schedule;
        $data->time = strtoupper($request->time);
        $data->desc = $request->desc;
        $data->daily_schedule_id =$request->daily_schedule_id;
        $data->save();
        return redirect()->back()->with('success', 'Schedule successfully added!');
    }
    public function ScheduleUpdate(Request $request){
        $data = Schedule::findOrFail($request->id);
        $data->time = strtoupper($request->time);
        $data->desc = $request->desc;
        $data->daily_schedule_id =$request->daily_schedule_id;
        $data->save();
        return redirect()->back()->with('success', 'Schedule successfully updated!');
    }

    public function ScheduleDelete(Request $request, $id){
        $delete = Schedule::findOrFail($id);
        $delete->delete();
        return redirect()->back()->with('success', 'Schedule successfully deleted!');
    }

     public function DailyScheduleStored(Request $request){
        //  dd($request->all());
         DB::beginTransaction();
         $request->validate([
             'class' => 'required',
             'day' => 'required|string|max:255',
             
         ]);
         try {
             $data = new DailySchedule();
             $data->day = $request->day;
             $data->class_id = $request->class;
             $data->save();
             // Commit the transaction if everything is successful
             DB::commit();
             return redirect()->route('admin.schedulecontent.view', $request->id)->with('success', 'Class Schedule created');
         } catch (\Exception $e) {
             // Rollback the transaction if an exception occurs
             DB::rollback();
             // You can log the exception if needed
             \Log::error($e);
             // Redirect back with an error message
             return redirect()->back()->with('failure', 'Failed to create Class Schedule. Please try again.');
         }
     }

    
}
