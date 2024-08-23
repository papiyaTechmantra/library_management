<?php

namespace App\Repositories;

use App\Interfaces\UserInterface;
use App\Models\User;

class UserRepository implements UserInterface
{
    public function listAll()
    {
        return User::orderBy('id', 'desc')->paginate(25);
    }

    public function listActive()
    {
        return User::where('status', 1)->orderBy('id', 'desc')->get();
    }

    public function listInactive()
    {
        return User::where('status', 0)->orderBy('id', 'desc')->get();
    }

    public function findById($id)
    {
        return User::findOrFail($id);
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update($id, array $data)
    {
        return User::whereId($id)->update($data);
    }

    public function delete($id)
    {
        User::destroy($id);
    }
}
