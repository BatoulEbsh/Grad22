<?php

namespace App\Http\Controllers;

use App\Models\Record;
use App\Models\User;
use App\Traits\Helper;
use App\Traits\ReturnResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use Helper;
    use ReturnResponse;

    public function login()
    {
        $credentials = request(['email', 'password']);
        if (!$token = JWTAuth::attempt($credentials)) {
            return $this->returnError(401, 'Unauthorized');
        }
        $user = auth()->user();
        $user['token'] = $token;
        return $this->returnData('token', $token, 'User login successfully');

    }

    public function register(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'phone' => 'required|numeric',
            'password' => 'required|min:4',
            'c_password' => 'required|same:password',
            'uId' => 'required|string'
        ]);

        if ($validator->fails()) {
            return $this->returnError(401, $validator->errors());
        }

        $input['password'] = Hash::make($input['password']);

        $user = new User();
        $user->name = $input['name'];
        $user->email = $input['email'];
        $user->phone = $input['phone'];
        $user->password = $input['password'];
        $user->uId = $input['uId'];

        $user->save();

        $user->roles()->syncWithoutDetaching([3]);

        $token = JWTAuth::fromUser($user);
        $user['token'] = $token;
        return $this->returnData('token', $token, 'User registered successfully');
    }


    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

}
