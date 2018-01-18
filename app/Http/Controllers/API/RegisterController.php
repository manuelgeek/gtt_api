<?php

namespace App\Http\Controllers\API;

use App\User;
use Validator;

use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
    		'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string',
        ]);

    	if ($validator->fails()) {
            return  response()->json([
            	'success' => false,
            	'message'=>$validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        if ($user) {
        	Auth::attempt(['email' => $request->email, 'password' => $request->password]);

        	Auth::user()->generateToken();

		   	return response()->json([
							            'success' => true,
							            'data' => Auth::user()->toArray(),
							        ]);
        }

        return  response()->json([
            	'success' => false,
            	'message'=> 'Error ocured, try again',500]);

    }
}


// {
//     "success": false,
//     "message": {
//         "name": [
//             "The name field is required."
//         ],
//         "email": [
//             "The email field is required."
//         ],
//         "password": [
//             "The password field is required."
//         ]
//     }
// }

// {
// 	"name" : "Geek Manu",
// 	"email" : "mail@mail.com",
// 	"password" : "1234"
// }

// {
//     "success": true,
//     "data": {
//         "id": 3,
//         "name": "Geek Manu",
//         "email": "mail@mail.com",
//         "created_at": "2018-01-18 19:41:14",
//         "updated_at": "2018-01-18 19:41:14",
//         "api_token": "us2nqSAxypJczTRFpBIzIoBGeigqQqeNZGZcbdKhShwvfo1Yh9FnlbjlT3BI"
//     }
// }