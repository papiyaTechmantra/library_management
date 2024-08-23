<?php

namespace App\Interfaces;

interface CategoryInterface {
    public function listAll();
    public function listAllFaculties();

    public function findById($id);
    public function getSearchJobCategory(string $term);
    public function getSearchFaculty(string $term);
}
