<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{


    protected $fillable = ['image', 'previous_job_id'];
    protected $searchableFields = ['*'];

    public $timestamps = false;

    public function previous_jobs()
    {
        return $this->belongsTo(PreviousJobs::class,'previous_job_id');
    }
}
