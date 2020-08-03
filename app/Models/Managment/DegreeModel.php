<?php

namespace App\Models\Managment;

use Illuminate\Database\Eloquent\Model;

class DegreeModel extends Model
{
    //

  public $table = "degree_models";
  protected $primaryKey = 'id';
  public $timestamps = true;


  public function getAllDegrees()
  {
    return $clientList = self::all();
  }
}
