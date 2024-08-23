<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Lead;

class AuthController extends Controller
{
    public function check(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'username' => 'required|exists:admins,username',
            'password' => 'required',
        ], [
            'username.*' => 'Invalid credential'
        ]);

        $creds = $request->only('username', 'password');

        if (Auth::guard('admin')->attempt($creds)) {
            return redirect()->route('admin.dashboard')->with('success', 'Login successfull');
            // return redirect()->intended()->with('success', 'Login successfull');
        } else {
            return redirect()->back()->with('failure', 'Invalid credential')->withInputs('request');
        }
    }

    public function dashboard()
    {
        if (Auth::guard('admin')->check()) {
            $data = (object)[];
            // $data->categories = Category::where('status', 1)->count();
            // $data->products = Product::where('status', 1)->count();
            // $data->leads = Lead::count();

            return view('admin.dashboard.index', compact('data'));
        } else {
            return redirect()->route('admin.login');
        }
    }

    public function profile()
    {
        if (Auth::guard('admin')->check()) {
            return view('admin.profile.index');
        } else {
            return redirect()->route('admin.login');
        }
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login')->with('success', 'Logout successfull');
        // return redirect()->intended()->with('success', 'Logout successfull');
    }
}
