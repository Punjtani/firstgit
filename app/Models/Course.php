<?php

namespace App\Models;

use App\Lesson;
use App\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $guarded = [''];
    public function users()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }
    public function categories($category_ids)
    {
        $cate_id = explode(',', $category_ids);
        foreach ($cate_id as $key => $value) {
            $category[] = Category::find($value)->name;
        }
        $cate_show = implode(',', $category);
        return $cate_show;
    }
    public function lessons()
    {
        return $this->hasMany(Lesson::class,'course_id','id');
    }
}
