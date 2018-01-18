<?php

namespace App\Http\Controllers\API;

use App\User;
use Validator;

use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string',
            'remember' => 'required',
        ]);

    	if ($validator->fails()) {
            return  response()->json([
            	'success' => false,
            	'message'=>$validator->errors()], 422);
        }


        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
        	Auth::user()->generateToken();

		   	return response()->json([
							            'success' => true,
							            'data' => Auth::user()->toArray(),
							        ]);
        }

        return response()->json([
        	'success' => false,
        	'message' => 'Wrong Reg No or Password'],200);

    }

    public function logout(Request $request)
    {
    	//pass request in header

	    $user = Auth::guard('api')->user();

	    if ($user) {
	        $user->api_token = null;
	        $user->save();

	        return response()->json([
		    	'success' => true,
		    	'message' => 'User logged out.'], 200);
	    }

	    return response()->json([
	    	'success' => false,
	    	'message' => 'User Not loged in'], 200);
	}
}


//LOGIN

// {
//     "success": false,
//     "message": {
//         "remember": [
//             "The remember field is required."
//         ]
//     }
// }

// {
//     "email":"emashmagak@gmail.com",
//     "password":"manuelgeek",
//     "remember" : true
// }



// {
//     "success": true,
//     "data": {
//         "id": 1,
//         "name": "Geek Manu",
//         "email": "emashmagak@gmail.com",
//         "created_at": "2018-01-18 18:40:38",
//         "updated_at": "2018-01-18 19:09:34",
//         "api_token": "xlWZudhvKeck3HCrpkixTps8xTgr66IPWoIVLXihWINnK1adsjXeBtyMB6x9"
//     }
// }


//LOGOUT
//    header = {"Authentication": "Bearer ycSgiktLsofqZ5ED13KPiPGDfWpomhxnJ5Rg8Nrsai778Ym50Zn20kB31jxT", "Accept" : "application/json"} //Bearer <api_token>
	// {
	//     $validator = Validator::make($request->all(), [
 //            'api_token' => 'required',
 //        ]);

//error
// {
//     "success": false,
//     "message": "User not Unauthenticated."
// }

 //    	if ($validator->fails()) {
 //            return  response()->json([
 //            	'success' => false,
 //            	'message'=>$validator->errors()], 422);
 //        }
    	