<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->ip = $_SERVER['REMOTE_ADDR'];;
    }

    public function checkMobile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_number' => 'required|integer|digits:10'
        ], [
			// 'mobile_number.exists' => 'We could not find your mobile number',
			'mobile_number.*' => 'Enter valid mobile number'
		]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()->first()
            ]);
        } else {

            $userExists = User::where('mobile_no', $request->mobile_number)->first();

            // user found
            if (!empty($userExists)) {
                return response()->json([
                    'status' => 200,
                    'type' => 'login',
                    'message' => 'Enter Password'
                ]);
            }
            // user NOT found
            else {
                return response()->json([
                    'status' => 200,
                    'type' => 'register',
                    'message' => 'Create new account'
                ]);
            }

        }
    }

    /*
    public function create(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'referral_id' => 'required|string|exists:users,mobile_no',
            'name' => 'required|string|min:2|max:255',
            'email' => 'nullable|email|min:2|max:255',
            'mobile_number' => 'required|integer|digits:10|unique:users,mobile_no',
            'password' => 'required|string|max:255',
            'agree_to_terms' => 'required|in:agree',
        ], [
            'referral_id.required' => 'Referral id is mandetory. Please contact us',
            'referral_id.exists' => 'Please enter valid Referral id',
            'agree_to_terms.*' => 'Please agree to terms & conditions'
        ]);

        $referred_by = User::select('id')->where('mobile_no', $request->referral_id)->first();

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile_no = $request->mobile_number;
        $user->password = Hash::make($request->password);
        $user->referred_by = $referred_by->id;
        $save = $user->save();

        if ($save) {
            return redirect()->route('front.user.login')->with('success', 'Registration successfull');
        } else {
            return redirect()->back()->with('failure', 'Failed to craete User');
        }
    }

    public function check(Request $request)
    {
        // dd($request->all());

        $validator = Validator::make($request->all(),[
            'mobile_no' => 'required|integer|digits:10|exists:users,mobile_no',
            'password' => 'required'
        ], [
			'mobile_no.exists' => 'We could not find your mobile number',
			'mobile_no.*' => 'Enter valid mobile number'
		]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()->first()
            ]);
        }

        $creds = $request->only('mobile_no', 'password');

        if (Auth::guard('web')->attempt($creds)) {
            // return redirect()->intended()->with('success', 'Login successfull');
            // return redirect()->route('front.user.profile')->with('success', 'Login successfull');
            // return redirect()->route('front.home')->with('success', 'Login successfull');
            return response()->json([
                'status' => 200,
                'message' => 'Login successfull',
                'data' => Auth::guard('web')->user(),
            ]);
        } else {
            // return redirect()->back()->with('failure', 'Invalid credential');
            return response()->json([
                'status' => 422,
                'message' => 'Invalid credential',
            ]);
        }
    }

    public function logout()
    {
        Auth::guard('web')->logout();
        return redirect()->intended()->with('success', 'Logout successfull');
    }
    */
}
