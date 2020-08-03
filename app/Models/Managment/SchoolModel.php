<?php


namespace App\Models\Managment;


use Illuminate\Database\Eloquent\Model;

class SchoolModel extends Model
{
  public $table = "company_models";
  protected $primaryKey = 'id';
  public $timestamps = true;
}
