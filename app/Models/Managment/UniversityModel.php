<?php


namespace App\Models\Managment;


use Illuminate\Database\Eloquent\Model;

class UniversityModel extends Model
{
  public $table = "university_models";
  protected $primaryKey = 'id';
  public $timestamps = true;
}
