<?php

namespace App\Http\Controllers\Authentication;

use App\Mail\ForgetPasswordMail;
use App\Models\Authorization\ResetPasswordVerificationTokenModel;
use App\Models\Clients\Clients;
use App\Models\Guards\GuardRoles;
use App\Models\Ticketation\Users\User;
use App\Models\Users\UserLastLoginModel;
use App\Models\Users\UserModel;
use App\Models\Users\UsersLoggedInHistoryModel;
use Carbon\Carbon;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use App\Models\Users\WrongLoginUserAttemptsModel;

class AuthenticationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function login(Request $request)
    {

        $userModel = new UserModel();

        $rememberMe = 0;
        if ($request->has('remember_me')) {
            $rememberMe = true;
        } else {
            $rememberMe = false;
        }
        if ($request->designation == 1) {
            if (Auth::guard('user')->attempt(['email' => $request->email, 'password' => $request->password], $rememberMe)) {

               $loggedInUser = $userModel->getUserById(Auth::guard('user')->id());
                if($loggedInUser->is_active == 1)
                {
                    $userModelLastLogin = new UserModel();
                    $sessions = $request->session();
                    $ip = $request->server('REMOTE_ADDR');
                    $role = $userModelLastLogin->where('id','=',$loggedInUser->id)->pluck('role_id');
                    $guard_roles = new GuardRoles();
                    $role_name = $guard_roles->where('id','=',$role[0])->pluck('name');
//                    $userLastLogin = new UserLastLoginModel();
//                    $userLastLogin->saveRecords($loggedInUser->id, $role->role_id,$ip,$sessions->getId());
                    $userModelLastLogin->changeLastLoggedIn($loggedInUser->id , $sessions->getId(), $ip , $role_name[0]);



                    $userLoggedInnHistoryModel = new UsersLoggedInHistoryModel();
                    $userLoggedInnHistoryModel->addNewModel($loggedInUser->id, \Illuminate\Support\Facades\Session::getId());


                    return Redirect::to(url('map/'));
                }
                else
                {
                    self::logout();
                    return redirect()->back()->with('message','Wrong email or password!');
                }


            } else {

                $user = $userModel->getUserByEmail($request->email);

                if ($user) {
                    $wrongAttempt = new WrongLoginUserAttemptsModel();
                    $wrongAttempt = $wrongAttempt->saveModel($request, $user->id);
                }
                return redirect()->back()->with('message','Wrong email or password!');
            }
        }
        if ($request->designation == 2) {
            if (Auth::guard('client')->attempt(['email' => $request->email, 'password' => $request->password])) {
                $loggedInUser = $userModel->getUserById(Auth::guard('user')->id());
//                $userobj = new UserModel();
//                $userobj->changeLastLoggedIn($loggedInUser->id);
                return Redirect::to(url('clientPortal/home'));
            } else {
                return Redirect::to('/')->with('message','Entered Email or Password is Wrong');;
            }
        }
    }

    public function logout()
    {




        if (Auth::guard('user')->check()) {

            $userLoggedInnHistoryModel = new UsersLoggedInHistoryModel();
            $response = $userLoggedInnHistoryModel->makeUserLoggedOut(\Illuminate\Support\Facades\Session::getId());

            Auth::guard('user')->logout();
            return Redirect::to('/');
        }
        if (Auth::guard('client')->check()) {
            Auth::guard('client')->logout();
            return Redirect::to('/');
        }
    }

    public function forgetPassword()
    {
        return view('authentication.forgetPassword');
    }

    public function generateNewPasswordMail(Request $request)
    {


        if ($request->designation == 1) {
            $user = new UserModel();
            $user = $user->verifyUserByEmail($request->email);
            if ($user == true) {
//                dd('true');

                $user = new UserModel();
                $user = $user->getUserByEmail($request->email);



                $verificationToken = md5(microtime());
                config(['mail.host' => 'smtp.gmail.com']);
                config(['mail.port' => '465']);
                Mail::to($request->email)->send(new ForgetPasswordMail($verificationToken));

                $saveVerificationToken = new ResetPasswordVerificationTokenModel();
                $saveVerificationToken = $saveVerificationToken->saveModel(1, $user->id, $verificationToken);

                dd('verification email has been sent to your email address, click the link to reset your password');


            } else {
                dd('No Email Record Matched Against Provided Email');
            }

        }
        if ($request->designation == 2) {

            $user = new Clients();
            $user = $user->verifyClientByEmail($request->email);
            if ($user == true) {
                dd('client true');
            } else {
                dd('No Email Record Matched Against Provided Email');
            }

        }
    }

    public function verifyResetPasswordLink($verificationToken)
    {
        $isValidVerificationToken = new ResetPasswordVerificationTokenModel();
        $isValidVerificationToken = $isValidVerificationToken->getByVerificationToken($verificationToken);

        if ($isValidVerificationToken) {

            $resetPasswordVerificationTokenModel = new ResetPasswordVerificationTokenModel();
            $resetPasswordVerificationTokenModel = $resetPasswordVerificationTokenModel->getByVerificationToken($verificationToken);
            $minPasswordLength = Config::get('globalvariables.$minPasswordLength');
            $maxPasswordLength = Config::get('globalvariables.$maxPasswordLength');
            $data = array('resetPasswordVerificationTokenModel' => $resetPasswordVerificationTokenModel,
                'minPasswordLength' => $minPasswordLength,
                'maxPasswordLength' => $maxPasswordLength);
            return view('authentication.resetPassword')->with($data);
        } else {
            dd('invalid token');
        }
    }

    public function saveNewPassword(Request $request)
    {
        if($request->user_client_type)
        {
            $user = new UserModel();
            $user = $user->updatePassword($request->user_client_id, $request->newPassword);
        }
        if($request->user_client_id)
        {
            $client = new Clients();
            $client = $client->updatePassword($request->user_client_id, $request->newPassword);
        }
    }

}
