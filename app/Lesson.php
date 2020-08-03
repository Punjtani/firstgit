<?php

namespace App;

use App\Models\Course;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $guarded = [''];

    public function course()
    {
        return $this->belongsTo(Course::class,'course_id');
    }
}
