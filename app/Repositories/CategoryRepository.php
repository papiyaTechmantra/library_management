<?php

namespace App\Repositories;

use App\Interfaces\CategoryInterface;
use App\Models\Collection;
use App\Models\JobCategory;
use App\Models\Faculty;
use App\Models\order;
use App\Models\Unit;
use App\Models\Subject;

class CategoryRepository implements CategoryInterface
{
    // Collection
    public function listAll()
    {
        return Collection::latest()->where('deleted_at', 1)->get();
    }
    public function findById($id)
    {
        return Collection::findOrFail($id);
    }
    public function findByCatId($id)
    {
        return JobCategory::findOrFail($id);
    }

    public function getSearchCollection(string $term)
    {
        return Collection::where([['title', 'LIKE', '%' . $term . '%']])->where('deleted_at', 1)
        ->get();
    }


    //Faculty
    public function getSearchFaculty(string $term)
    {
        return Faculty::where([['name', 'LIKE', '%' . $term . '%']])->where('deleted_at', 1)
        ->get();
    }
    public function listAllFaculties()
    {
        return Faculty::latest()->where('deleted_at', 1)->get();
    }
    public function findFacultyById($id)
    {
        return Faculty::findOrFail($id);
    }


    // Units
    public function listAllUnits()
    {
        return Unit::latest()->where('deleted_at', 1)->get();
    }
    public function findUnitById($id)
    {
        return Unit::findOrFail($id);
    }

    public function getSearchUnit(string $term)
    {
        return Unit::where([['title', 'LIKE', '%' . $term . '%']])->where('deleted_at', 1)
        ->get();
    }


    // Subjects
    public function listAllSubjects()
    {
        return Subject::latest()->where('deleted_at', 1)->get();
    }
    

    public function findSubjectById($id)
    {
        return Subject::findOrFail($id);
    }

    public function getSearchSubject(string $term)
    {
        return Subject::where([['title', 'LIKE', '%' . $term . '%']])->where('deleted_at', 1)
        ->get();
    }

    // Job Category
    public function getSearchJobCategory(string $term){
        return JobCategory::where([['title', 'LIKE', '%' . $term . '%']])->where('deleted_at', 1)
        ->get();
    }
    public function listAllJobCategory(){
        return JobCategory::latest()->where('deleted_at', 1)->get();
    }
}
