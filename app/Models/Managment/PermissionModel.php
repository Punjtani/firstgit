<?php


namespace App\Models\Managment;


use Illuminate\Database\Eloquent\Model;

class PermissionModel extends Model
{
  public $table = "company_models";
  protected $primaryKey = 'id';
  public $timestamps = true;
}
