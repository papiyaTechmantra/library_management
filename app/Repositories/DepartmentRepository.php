<?php

namespace App\Repositories;

use App\Interfaces\DepartmentInterface;
use App\Models\Collection;
use App\Models\JobCategory;
use App\Models\Faculty;
use App\Models\Event;
use App\Models\Department;
use App\Models\Facility;
use App\Models\SubFacility;
use App\Models\StudentClass;
use App\Models\ScheduleContent;
use App\Models\DailySchedule;
use App\Models\order;
use App\Models\Unit;
use App\Models\Subject;

class DepartmentRepository implements DepartmentInterface
{
    //Department
    public function getSearchDepartment(string $term)
    {
        return Department::where([['name', 'LIKE', '%' . $term . '%']])->where('deleted_at', 1)->get();
    }
    public function listAllDepartments()
    {
        return Department::latest()->where('deleted_at', 1)->get();
    }
    public function findDepartmentById($id)
    {
        return Department::findOrFail($id);
    }

    //Facilities List
    public function getSearchFacility(string $term)
    {
        return Facility::where([['title', 'LIKE', '%' . $term . '%']])->where('deleted_at', 1)->get();
    }
    public function listAllFacilities()
    {
        return Facility::latest()->where('deleted_at', 1)->get();
    }
    public function findFacilityById($id)
    {
        return Facility::findOrFail($id);
    }
    
    //subFacility
    public function getSearchSubfacility(string $term)
    {
        return SubFacility::where([['title', 'LIKE', '%' . $term . '%']])->where('deleted_at', 1)->get();
    }
    public function listAllSubfacilities($id)
    {        
        return SubFacility::latest()->where('facility_id', $id)->where('deleted_at',1)->get();
    }
    public function findSubfacilityById($id)
    {
        return SubFacility::findOrFail($id);
    }
    //class
    public function getSearchClass(string $term)
    {
        return StudentClass::where([['name', 'LIKE', '%' . $term . '%']])->where('deleted_at', 1)->get();
    }
    public function listAllClasses()
    {        
        return StudentClass::latest()->where('deleted_at',1)->get();
    }
    public function findClassById($id)
    {
        return StudentClass::findOrFail($id);
    }
    //class
    public function getSearchScheduleContent(string $term)
    {
        return ScheduleContent::where([['name', 'LIKE', '%' . $term . '%']])->where('deleted_at', 1)->get();
    }
    public function listAllScheduleContents()
    {        
        return ScheduleContent::latest()->get();
    }
    public function findScheduleContentById($id)
    {
        return ScheduleContent::findOrFail($id);
    }
    public function listAllClassesForScheduleContent()     // function create for facting class name in schedule content
    {        
        return StudentClass::orderBy('id', 'ASC')->get();
    }

    public function listAllDailySchedules(){        
        return DailySchedule::with('ClassData')->orderBy('id', "ASC")->where('deleted_at',1)->get();
    }
}
