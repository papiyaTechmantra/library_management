<?php

namespace App\Interfaces;

interface DepartmentInterface {
    public function getSearchDepartment(string $term);
    public function listAllDepartments();
    public function findDepartmentById($id);


    public function getSearchFacility(string $term);
    public function listAllFacilities();
    public function findFacilityById($id);


    public function getSearchSubfacility(string $term);
    public function listAllSubfacilities($id);
    public function findSubfacilityById($id);

    public function getSearchClass(string $term);
    public function listAllClasses();
    public function findClassById($id);
    
    public function getSearchScheduleContent(string $term);
    public function listAllScheduleContents();
    public function findScheduleContentById($id);
    public function listAllClassesForScheduleContent();
    public function listAllDailySchedules();
}
