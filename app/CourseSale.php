<?php

namespace App;

use App\Models\Course;
use Illuminate\Database\Eloquent\Model;

class CourseSale extends Model
{
    protected $guarded = [''];

    public function course()
    {
        return $this->belongsTo(Course::class,'course_id');
    }
    public function seller()
    {
        return $this->belongsTo(User::class,'sell_by');
    }
    public function buyer()
    {
        return $this->belongsTo(User::class,'purchased_by');
    }
}
