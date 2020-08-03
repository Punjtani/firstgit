<?php


namespace App\Models\Managment;


use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
  public $table = "company_models";
  protected $primaryKey = 'id';
  public $timestamps = true;

    public function getAllDegrees()
    {
        return $clientList = self::all();
    }
}
