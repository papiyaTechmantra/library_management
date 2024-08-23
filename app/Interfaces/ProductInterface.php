<?php

namespace App\Interfaces;

interface ProductInterface {
    public function listAll();
    public function listActive();
    public function listInactive();

    public function findById($id);
}
