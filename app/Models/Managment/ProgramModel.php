<?php


namespace App\Models\Managment;


use Illuminate\Database\Eloquent\Model;

class ProgramModel extends Model
{
  public $table = "program_models";
  protected $primaryKey = 'id';
  public $timestamps = true;
}
