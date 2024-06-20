<?php

namespace App\Http\Controllers;

use App\Models\PreviousJobs;
use App\Traits\Helper;
use App\Traits\ReturnResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PreviousJobsController extends Controller
{
    use ReturnResponse;
    use Helper;

    public function index()
    {

        $job = PreviousJobs::with('images')->get();
        if ($job) {
            return $this->returnData('job', $job);
        } else {

            return $this->returnError(404, 'Job not found');
        }
    }


    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => 'required|string',
            'disc' => 'required|string',
            'images' => 'required|array',
            'images.*' => 'image'
        ]);
        if ($validator->fails()) {
            return $this->returnError(422, $validator->errors());
        }
        $job = new PreviousJobs();
        $job->fill([
            'title' => $input['title'],
            'disc' => $input['disc'],
            'user_id' => Auth::id(),
        ]);
        $job->save();
        $this->saveImages($input['images'], $job->id, "PreviousJob $job->id");
        return $this->returnSuccessMessage('job added successfully');

    }
    public function show($id){
        $job = PreviousJobs::with('images')
            ->where('id','=',$id)
            ->get();
        if ($job) {
            return $this->returnData('job', $job);
        } else {

            return $this->returnError(404, 'Job not found');
        }
    }
    public function searchByTitle($title){
        $jobs = PreviousJobs::with('images')
            ->where('title','=',$title)
            ->get();
        if ($jobs) {
            return $this->returnData('jobs', $jobs);
        } else {

            return $this->returnError(404, 'Job not found');
        }
    }
}
