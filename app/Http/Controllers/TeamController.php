<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use App\Traits\ReturnResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{
    use ReturnResponse;

    public function index()
    {
        $team = User::query()->select(['*'])
            ->join('role_user as u', 'users.id', '=', 'u.user_id')
            ->where('role_id', 2)->get();
        return $this->returnData('team', $team);

    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email',
            'password' => 'required|string',
            'phone' => 'required|numeric',
            'uId' => 'required|string'
        ]);

        if ($validator->fails()) {
            return $this->returnError(422, $validator->errors());
        }

        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->phone = $request->input('phone');
        $user->uId = $request->input('uId');

        $user->save();

        $user->roles()->attach([2]);

        return $this->returnSuccessMessage('Team added successfully');
    }

}
