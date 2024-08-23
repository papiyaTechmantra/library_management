<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lead;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->keyword ?? '';

        $query = Lead::query();

        $query->when($keyword, function($query) use ($keyword) {
            $query->where('full_name', 'like', '%'.$keyword.'%')
            ->orWhere('full_name', 'like', '%'.$keyword.'%')
            ->orWhere('mobile_no', 'like', '%'.$keyword.'%')
            ->orWhere('message', 'like', '%'.$keyword.'%');
        });

        $data = $query->latest('id')->paginate(25);

        return view('admin.lead.index', compact('data'));
    }

    public function status(Request $request, $id)
    {
        $data = Lead::findOrFail($id);
        $data->status = ($data->status == 1) ? 0 : 1;
        $data->update();

        return response()->json([
            'status' => 200,
            'message' => 'Status updated',
        ]);
    }
}
