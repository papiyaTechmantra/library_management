<?php

namespace App\Repositories;

use App\Interfaces\OrderInterface;
use App\Models\Order;

class OrderRepository implements OrderInterface
{
    public function listAll()
    {
        return Order::orderBy('id', 'desc')->paginate(25);
    }

    public function findById($id)
    {
        return Order::findOrFail($id);
    }

    public function create(array $data)
    {
        return Order::create($data);
    }

    public function update($id, array $data)
    {
        return Order::whereId($id)->update($data);
    }

    public function delete($id)
    {
        Order::destroy($id);
    }
}
