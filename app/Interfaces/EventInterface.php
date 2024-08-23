<?php

namespace App\Interfaces;

interface EventInterface {
    public function getSearchEvent(string $term);
    public function listAllEvents();
    public function findEventById($id);
}
