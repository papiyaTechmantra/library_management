<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Permission;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    public function index(Request $request){
        $keyword = $request->keyword ?? '';
        $query = Admin::query();
        $query->when($keyword, function($query) use ($keyword) {
            $query->where('name', 'like', '%'.$keyword.'%');
        });

        $data = $query->latest('id')->where('type', 2)->paginate(25);

        return view('admin.master.index', compact('data'));
    }

    public function create(Request $request)
    {
        return view('admin.master.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'mobile' => 'required|string|max:20|unique:admins,mobile_no',
            'username' => 'required|string|max:255|unique:admins,username',
            'password' => 'required|string|min:6|confirmed',
        ];

        // Perform validation
        $request->validate($rules);
        try {
            $data = new Admin();
            $data->name = $request->name;
            $data->email = $request->email;
            $data->mobile_no = $request->mobile;
            $data->username  = $request->username;
            $data->type  = 2;
            $data->password = Hash::make($request->password);
    
            $data->save();
            
            return redirect()->route('admin.user_management.list.all')->with('success', 'New admin created');
        } catch (\Exception $e) {
            // Log the exception
            \Log::error('Error while creating new admin: ' . $e->getMessage());
    
            // Cache the validation errors
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                Cache::put('validation_errors', $e->errors(), 60); // Cache the validation errors for 60 seconds
            }
    
            // Redirect back to the form with an error message
            return redirect()->back()->withInput()->with('error', 'An error occurred. Please try again.');
        }
    }


    public function edit(Request $request)
    {
        $data = Admin::findOrFail($request->id);
        return view('admin.master.edit', compact('data'));
    }

    public function update(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('admins')->ignore($request->id),
            ],
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('admins')->ignore($request->id),
            ],
            'password' => 'nullable|string|min:6|confirmed',
        ];

        // Perform validation
        $request->validate($rules);

        // Update user information
        $user = Admin::findOrFail($request->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile_no = $request->mobile;
        $user->username = $request->username;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return redirect()->route('admin.user_management.list.all')->with('success', 'Admin updated successfully.');
    }

    public function delete(Request $request, $id)
    {
        $data = Admin::findOrFail($id);
        $data->delete();

        return redirect()->route('admin.user_management.list.all')->with('success', 'Admin deleted');
    }
    public function Permissions($id){
        $data = Admin::findOrFail($id);
        $permissions = Permission::where('admin_id', $id)->get()->pluck('value')->toArray();
        return view('admin.master.permissions', compact('data', 'permissions'));
    }
    public function PermissionsUpdate(Request $request){
        $Permission = Permission::where('value', $request->value)->where('parent', $request->parent)->where('admin_id', $request->admin_id)->first();
        if($Permission){
            $Permission->delete();
        }else{
            $Permission = new Permission;
            $Permission->value = $request->value;
            $Permission->parent = $request->parent;
            $Permission->admin_id = $request->admin_id;
            $Permission->save();
        }
        return response()->json(['status'=>200]);
    }
}
