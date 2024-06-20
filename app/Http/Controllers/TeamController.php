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
            'uId'=>'required|string'
        ]);
        if ($validator->fails()) {
            return $this->returnError(422, $validator->errors());
        }
        $user = new User();
        $user->fill([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => $request['password'],
            'phone' => $request['phone'],
            'uId'=>$request['uId']
        ]);
        $user->save();
        $user->roles()->attach([2]);
        return $this->returnSuccessMessage('Team added successfully');
    }

    public function getTeamDate($id){
       $teamDates = Appointment::query()->where('team_id','=',$id)
       ->select(['start_time','end_time'])->get();
        return $this->returnData('teamDates :', $teamDates);
    }
}
