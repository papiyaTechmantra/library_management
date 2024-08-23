<?php

namespace App\Interfaces;

interface OrderInterface {
    public function listAll();

    public function findById($id);

    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
}
