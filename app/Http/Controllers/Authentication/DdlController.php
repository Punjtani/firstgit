<?php

namespace App\Http\Controllers\Authentication;

//use App\Models\Clients\Clients;
use App\User;
use App\Models\Managment\DegreeModel;
//use App\Models\Users\UserModel;
use App\Models\Managment\UserModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DdlController extends Controller
{
    //
  public function degrees(Request $request)
  {

    $degrees = new DegreeModel();
    $degrees = $degrees->getAllDegrees();


//    dd($degrees);

    return $degrees;
  }
  public function users(Request $request)
  {
//        $users = User::where('id','>',0)->get();
////        return $users;


      $users = DB::table('users')
          ->join('user_group_models', 'users.role_id', '=', 'user_group_models.id')
          ->get();

      return $users;

  }
  public function countinents(Request $request)
  {

    $degrees = new DegreeModel();
    $degrees = $degrees->getAllDegrees();




    return json_encode($degrees);
  }public function countries(Request $request)
  {

    $degrees = new DegreeModel();
    $degrees = $degrees->getAllDegrees();




    return json_encode($degrees);
  }public function userGroups(Request $request)
  {

    $degrees = new DegreeModel();
    $degrees = $degrees->getAllDegrees();




    return json_encode($degrees);
  }public function permissions(Request $request)
  {

    $degrees = new DegreeModel();
    $degrees = $degrees->getAllDegrees();




    return json_encode($degrees);
  }public function roles(Request $request)
  {

    $degrees = new DegreeModel();
    $degrees = $degrees->getAllDegrees();




    return json_encode($degrees);
  }
  public function programs(Request $request)
  {

    $degrees = new DegreeModel();
    $degrees = $degrees->getAllDegrees();




    return json_encode($degrees);
  }
  public function universities(Request $request)
  {

    $degrees = new DegreeModel();
    $degrees = $degrees->getAllDegrees();




    return json_encode($degrees);
  }
  public function schools(Request $request)
  {

    $degrees = new DegreeModel();
    $degrees = $degrees->getAllDegrees();




    return json_encode($degrees);
  }
  public function companies(Request $request)
  {

    $degrees = new DegreeModel();
    $degrees = $degrees->getAllDegrees();




    return json_encode($degrees);
  }
  public function students(Request $request)
  {

    $degrees = new DegreeModel();
    $degrees = $degrees->getAllDegrees();




    return json_encode($degrees);
  }

}
