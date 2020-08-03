<?php


namespace App\Models\Managment;


use Illuminate\Database\Eloquent\Model;

class CountryModel extends Model
{
  public $table = "country_models";
  protected $primaryKey = 'id';
  public $timestamps = true;
}
