<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 11/9/2018
 * Time: 3:03 AM
 */

use App\Aquisition;
use App\User;
use App\Mail\UserSingup;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;


function send_sms($phone,$text){

    $ch = curl_init();

    // set url
    curl_setopt($ch, CURLOPT_URL, "http://bsms.ufone.com/bsms_app5/sendapi-0.3.jsp?id=03328775486&lang=English&message=".urlencode($text)."&shortcode=CSL&mobilenum=".$phone."&password=Ptml@123456&messagetype=transactional");

    //return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // $output contains the output string
    $output = curl_exec($ch);

    // close curl resource to free up system resources
    curl_close($ch);

    $xmlnew=simplexml_load_string($output )  or die("Error: Cannot create object") ;

    if($xmlnew->response == 0){
       return true;
    } else {
      return false;
    }

}

function send_email(){


}

function get_all_users(){
    $users = [];
    $users = Aquisition::all()->toArray();
    return $users[0]['id'];

}


function get_company_logo()
{

    $logo = public_path('assets/img/nevcorelogo.png');

    return $logo;
}

function send_mail(){


    $receiver_email = 'nomangul934@gmail.com';
    $user_name = 'nomangul';
    $password = '12345';
    Mail::send(new UserSingup($user_name, $password));

}

function upload_file(){

}

function get_company_location(){
    return 'Our office is located at Main Akbar Chowk 1st Floor,852-D (Next to Summit Bank) ';
}

function script($path)
{
    if (is_array($path)) {
        foreach ($path as $p) {
            echo '<script src="' . te_asset($p) . '"></script>';
        }
    } else {
        echo '<script src="' . te_asset($path) . '"></script>';
    }
}

function te_asset($filePath)
{

    $filePath = ltrim($filePath, '/');

    return te_schema_less_asset_url() . $filePath;

}

function te_schema_less_asset_url($slash = true)
{
    $asset_url = url('assets');

    $asset_url = te_make_url_scheme_less(rtrim($asset_url, '/'));
    if ($slash) {
        $asset_url = $asset_url . '/';
    }

    return $asset_url;
}

function te_make_url_scheme_less($url)
{

    $protocol = strtolower(substr($url, 0, 6));
    if ($protocol == 'https:') {
        $url = substr($url, 6);
    }
    $protocol = strtolower(substr($url, 0, 5));
    if ($protocol == 'http:') {
        $url = substr($url, 5);
    }

    return $url;

}

function style($path)
{
    if (is_array($path)) {
        foreach ($path as $p) {
            echo '<link rel="stylesheet" href="' . te_asset($p) . '"  rel="stylesheet" />';
        }
    } else {
        echo '<link rel="stylesheet" href="' . te_asset($path) . '" rel="stylesheet" />';
    }
}

function cal_sum_user_factor($user_id){

    $users = DB::select("SELECT SUM(factor_obtained_marks) as factor_marks FROM evaluations WHERE user_id=$user_id");

    foreach($users as $user){

         return $user->factor_marks;
    }


}

function cal_sum_active_factor(){

    $marks = DB::select("SELECT SUM(marks) as marks FROM factors WHERE status=active");

    foreach($marks as $total){
        if($total->$total <=100 ){
            return true;

        }else{
            return false;
        }
    }

}
function factor_validation()
{

    $marks = DB::table("factors")->where('status','active')->sum('marks');

    if($marks<=100){
        return response()->json(true);
    }else{
        return response()->json(false);
    }
}

function convert_number($number){


    $pfx = country_codes_array('PK');

    //The default country code if the recipient's is unknown:
    $default_country_code  = '92';

    //Loop through the numbers and make them international format:
      //Remove any parentheses and the numbers they contain:
    $number = preg_replace("/\([0-9]+?\)/", "", $number);

        //Strip spaces and non-numeric characters:
    $number = preg_replace("/[^0-9]/", "", $number);

        //Strip out leading zeros:
    $number = ltrim($number, '0');

        //Look up the country dialling code for this number:


        //Check if the number doesn't already start with the correct dialling code:
        if ( !preg_match('/^'.$pfx.'/', $number)  ) {
            $number = $pfx.$number;
        }

        //return the converted number:
        return $number;




}


function convert_number_array(){
    $numbers = array(
        '07123 456 781' => 'US',
        '07123456782' => 'UK',
        '07123456783' => '',
        '447123456784' => 'UK',
        '+44(0)7123456785' => 'UK',
        '17123456786' => 'US'
    );

        //An array of country codes:
        //Get a full list at: https://plugins.svn.wordpress.org/mediaburst-ecommerce-sms-notifications/tags/1.2.1/country-calling-codes.php
        $numbers = array(
            'UK' => '44',
            'US' => '1',
            'PK' => '92'
        );

    //The default country code if the recipient's is unknown:
    $default_country_code  = '44';

    //Loop through the numbers and make them international format:
    foreach ( $numbers as $n => $c )
    {
        //Remove any parentheses and the numbers they contain:
        $n = preg_replace("/\([0-9]+?\)/", "", $n);

        //Strip spaces and non-numeric characters:
        $n = preg_replace("/[^0-9]/", "", $n);

        //Strip out leading zeros:
        $n = ltrim($n, '0');

        //Look up the country dialling code for this number:
        if ( array_key_exists($c, $country_codes)  ) {
            $pfx = $country_codes[$c];
        } else {
            $pfx = $default_country_code;
        }

        //Check if the number doesn't already start with the correct dialling code:
        if ( !preg_match('/^'.$pfx.'/', $n)  ) {
            $n = $pfx.$n;
        }

        //return the converted number:
        echo $n."\r\n";

        //Outputs: 17123456781 447123456782 447123456783 447123456784 447123456785 17123456786
    }
}


function country_codes_array($country_code){

    $country_codes = array(
        'AC' => '247',
        'AD' => '376',
        'AE' => '971',
        'AF' => '93',
        'AG' => '1268',
        'AI' => '1264',
        'AL' => '355',
        'AM' => '374',
        'AO' => '244',
        'AQ' => '672',
        'AR' => '54',
        'AS' => '1684',
        'AT' => '43',
        'AU' => '61',
        'AW' => '297',
        'AX' => '358',
        'AZ' => '994',
        'BA' => '387',
        'BB' => '1246',
        'BD' => '880',
        'BE' => '32',
        'BF' => '226',
        'BG' => '359',
        'BH' => '973',
        'BI' => '257',
        'BJ' => '229',
        'BL' => '590',
        'BM' => '1441',
        'BN' => '673',
        'BO' => '591',
        'BQ' => '599',
        'BR' => '55',
        'BS' => '1242',
        'BT' => '975',
        'BW' => '267',
        'BY' => '375',
        'BZ' => '501',
        'CA' => '1',
        'CC' => '61',
        'CD' => '243',
        'CF' => '236',
        'CG' => '242',
        'CH' => '41',
        'CI' => '225',
        'CK' => '682',
        'CL' => '56',
        'CM' => '237',
        'CN' => '86',
        'CO' => '57',
        'CR' => '506',
        'CU' => '53',
        'CV' => '238',
        'CW' => '599',
        'CX' => '61',
        'CY' => '357',
        'CZ' => '420',
        'DE' => '49',
        'DJ' => '253',
        'DK' => '45',
        'DM' => '1767',
        'DO' => '1809',
        'DO' => '1829',
        'DO' => '1849',
        'DZ' => '213',
        'EC' => '593',
        'EE' => '372',
        'EG' => '20',
        'EH' => '212',
        'ER' => '291',
        'ES' => '34',
        'ET' => '251',
        'EU' => '388',
        'FI' => '358',
        'FJ' => '679',
        'FK' => '500',
        'FM' => '691',
        'FO' => '298',
        'FR' => '33',
        'GA' => '241',
        'GB' => '44',
        'GD' => '1473',
        'GE' => '995',
        'GF' => '594',
        'GG' => '44',
        'GH' => '233',
        'GI' => '350',
        'GL' => '299',
        'GM' => '220',
        'GN' => '224',
        'GP' => '590',
        'GQ' => '240',
        'GR' => '30',
        'GT' => '502',
        'GU' => '1671',
        'GW' => '245',
        'GY' => '592',
        'HK' => '852',
        'HN' => '504',
        'HR' => '385',
        'HT' => '509',
        'HU' => '36',
        'ID' => '62',
        'IE' => '353',
        'IL' => '972',
        'IM' => '44',
        'IN' => '91',
        'IO' => '246',
        'IQ' => '964',
        'IR' => '98',
        'IS' => '354',
        'IT' => '39',
        'JE' => '44',
        'JM' => '1876',
        'JO' => '962',
        'JP' => '81',
        'KE' => '254',
        'KG' => '996',
        'KH' => '855',
        'KI' => '686',
        'KM' => '269',
        'KN' => '1869',
        'KP' => '850',
        'KR' => '82',
        'KW' => '965',
        'KY' => '1345',
        'KZ' => '7',
        'LA' => '856',
        'LB' => '961',
        'LC' => '1758',
        'LI' => '423',
        'LK' => '94',
        'LR' => '231',
        'LS' => '266',
        'LT' => '370',
        'LU' => '352',
        'LV' => '371',
        'LY' => '218',
        'MA' => '212',
        'MC' => '377',
        'MD' => '373',
        'ME' => '382',
        'MF' => '590',
        'MG' => '261',
        'MH' => '692',
        'MK' => '389',
        'ML' => '223',
        'MM' => '95',
        'MN' => '976',
        'MO' => '853',
        'MP' => '1670',
        'MQ' => '596',
        'MR' => '222',
        'MS' => '1664',
        'MT' => '356',
        'MU' => '230',
        'MV' => '960',
        'MW' => '265',
        'MX' => '52',
        'MY' => '60',
        'MZ' => '258',
        'NA' => '264',
        'NC' => '687',
        'NE' => '227',
        'NF' => '672',
        'NG' => '234',
        'NI' => '505',
        'NL' => '31',
        'NO' => '47',
        'NP' => '977',
        'NR' => '674',
        'NU' => '683',
        'NZ' => '64',
        'OM' => '968',
        'PA' => '507',
        'PE' => '51',
        'PF' => '689',
        'PG' => '675',
        'PH' => '63',
        'PK' => '92',
        'PL' => '48',
        'PM' => '508',
        'PR' => '1787',
        'PR' => '1939',
        'PS' => '970',
        'PT' => '351',
        'PW' => '680',
        'PY' => '595',
        'QA' => '974',
        'QN' => '374',
        'QS' => '252',
        'QY' => '90',
        'RE' => '262',
        'RO' => '40',
        'RS' => '381',
        'RU' => '7',
        'RW' => '250',
        'SA' => '966',
        'SB' => '677',
        'SC' => '248',
        'SD' => '249',
        'SE' => '46',
        'SG' => '65',
        'SH' => '290',
        'SI' => '386',
        'SJ' => '47',
        'SK' => '421',
        'SL' => '232',
        'SM' => '378',
        'SN' => '221',
        'SO' => '252',
        'SR' => '597',
        'SS' => '211',
        'ST' => '239',
        'SV' => '503',
        'SX' => '1721',
        'SY' => '963',
        'SZ' => '268',
        'TA' => '290',
        'TC' => '1649',
        'TD' => '235',
        'TG' => '228',
        'TH' => '66',
        'TJ' => '992',
        'TK' => '690',
        'TL' => '670',
        'TM' => '993',
        'TN' => '216',
        'TO' => '676',
        'TR' => '90',
        'TT' => '1868',
        'TV' => '688',
        'TW' => '886',
        'TZ' => '255',
        'UA' => '380',
        'UG' => '256',
        'UK' => '44',
        'US' => '1',
        'UY' => '598',
        'UZ' => '998',
        'VA' => '379',
        'VA' => '39',
        'VC' => '1784',
        'VE' => '58',
        'VG' => '1284',
        'VI' => '1340',
        'VN' => '84',
        'VU' => '678',
        'WF' => '681',
        'WS' => '685',
        'XC' => '991',
        'XD' => '888',
        'XG' => '881',
        'XL' => '883',
        'XN' => '857',
        'XN' => '858',
        'XN' => '870',
        'XP' => '878',
        'XR' => '979',
        'XS' => '808',
        'XT' => '800',
        'XV' => '882',
        'YE' => '967',
        'YT' => '262',
        'ZA' => '27',
        'ZM' => '260',
        'ZW' => '263',
    );
    if (array_key_exists($country_code,$country_codes))
    {
        return $country_codes[$country_code];

    }
    else
    {
        return null;

    }
}

function send_interview_sms($phone){

//    $txt = 'Hy: '.$_POST['name'].'\r\n'.'Thanks For Applying for CSL'.'\r\n'.'your Trial Date & TIme: '.$_POST['date'].', '.$_POST['time'].'\r\n'.'Trial Location: '.$_POST['location'].'\r\n'.'Support Message: '.$_POST['msg'];
    $text = 'welcome to nevcore ';
    $response = send_sms($phone,$text);
    if($response == true){
        return true;
    }else{
        return false;
    }

}

function send_offer_letter_sms($phone){

    $txt = 'Hy: '.$_POST['name'].'\r\n'.'Thanks For Applying for CSL'.'\r\n'.'your Trial Date & TIme: '.$_POST['date'].', '.$_POST['time'].'\r\n'.'Trial Location: '.$_POST['location'].'\r\n'.'Support Message: '.$_POST['msg'];
    $text = 'welcome to nevcore ';
    $response = send_sms($phone,$text);
    if($response == true){
        return true;
    }else{
        return false;
    }

}

function get_job_types()
{
    $jobs_types = [
        'hr' => "HR Manager",
        'office_boy' => "Office Boy",
        'developer' => "Backand Developer",
        'designer' => "Front End Designer",
        'sqa' => "Software Quality Assurance",
        'accountant' => "Accountant",

    ];
    return $jobs_types;
}

function get_branches()
{
    $branches= \App\Branch::all();
    return $branches;
}
function get_departments()
{
    $departments= \App\Department::all();
    return $departments;
}
function get_companies()
{
    $companies= \App\Company::all();
    return $companies;
}
function get_job_title()
{
    $jobs_types= \App\JobTitle::all();
    return $jobs_types;
}

function get_job_roles()
{
    $jobs_types = [
        'hr' => "HR Department",
        'development' => "Development",
        'admin' => "Administrator",
        'accounts' => "Accounts Department",

    ];
    return $jobs_types;
}
//generate a username from Full name
function generate_username($string_name="Mike Tyson", $rand_no = 200){
    $username_parts = array_filter(explode(" ", strtolower($string_name))); //explode and lowercase name
    $username_parts = array_slice($username_parts, 0, 2); //return only first two arry part

    $part1 = (!empty($username_parts[0]))?substr($username_parts[0], 0,3):""; //cut first name to 8 letters
    $part2 = (!empty($username_parts[1]))?substr($username_parts[1], 0,2):""; //cut second name to 5 letters
    $part3 = ($rand_no)?rand(0, $rand_no):"";

    $username = $part1. str_shuffle($part2). $part3; //str_shuffle to randomly shuffle all characters
    return $username;
}

//usage

//Generate a unique username using Database
function generate_unique_username($string_name="Mike Tyson", $rand_no = 200){

    while(true){
        $username_parts = array_filter(explode(" ", strtolower($string_name))); //explode and lowercase name
        $username_parts = array_slice($username_parts, 0, 2); //return only first two arry part

        $part1 = (!empty($username_parts[0]))?substr($username_parts[0], 0,8):""; //cut first name to 8 letters
        $part2 = (!empty($username_parts[1]))?substr($username_parts[1], 0,5):""; //cut second name to 5 letters
        $part3 = ($rand_no)?rand(0, $rand_no):"";

        $username = $part1. str_shuffle($part2). $part3; //str_shuffle to randomly shuffle all characters

//        $username_exist_in_db = username_exist_in_database($username); //check username in database
//        if(!$username_exist_in_db){
            return $username;
//        }
    }
}


function username_exist_in_database($username){


}

//usage
//echo generate_unique_username("Mike Tyson", 10);

function get_user_name($id){
    $name = DB::table("aquisitions")->where('id',$id)->value('name');
    return $name;

}
function get_employees(){
    $employees = DB::table("employees")->select('id','name','avatar_pic_url')->get();
    return $employees;

}

function get_clients(){
    $clients = DB::table("clients")->select('id','name','profile_pic')->get();
    return $clients;

}

function get_company(){
    $company = DB::table("companies")->select('id','company_name')->get();
    return $company;

}
function get_roles(){
    $roles = DB::table("roles")->select('id','name')->get();
    return $roles;

}

function get_working_days()
{
    $current_date = \Carbon\Carbon::now()->startOfMonth()->toDateString();;
    $first_day_of_current_month = \Carbon\Carbon::parse($current_date);

    $from = $first_day_of_current_month;
    $to = \Carbon\Carbon::now();
    $next_day_of_end_date=$to->copy()->addDay();

    $total_days = $to->diffInDays($from);
    $total_days++;
    $total_office_holidays = 0;

//    $total_presents = App\Attendance::where('user_id', '=', $id)
//        ->whereBetween('dated', [$from, $to])
//        ->count();
//
    $month_interval = new DateInterval('P1D');
    $month_periods = new DatePeriod($from, $month_interval, $next_day_of_end_date);
    $weak_ends=[];
    foreach ($month_periods as $month_period) {
        if($month_period->isWeekend()) {
            array_push($weak_ends, $month_period);
        }
    }
    $weekend_days=count($weak_ends);

    $start_month_holidays = App\LeaveType::where([
        ['start_leaves', '<', $from],
        ['end_leaves', '>=', $from]
    ])->get();

    foreach ($start_month_holidays as $start_month_holiday) {

        $end = Carbon\Carbon::parse($start_month_holiday->end_leaves);
        $next_day_of_holidays=$end->copy()->addDay();
        $holidays = $end->diffInDays($from);
        $holidays ++;
        $interval = new DateInterval('P1D');
        $periods = new DatePeriod($from, $interval, $next_day_of_holidays);
        $days=[];
        foreach ($periods as $period) {
            if($period->isWeekend()) {
                array_push($days, $period);
            }
        }
        $weak_days_in_holidays=count($days);
        $total_office_holidays += $holidays - $weak_days_in_holidays;
    }

    $with_in_month_office_holidays = App\LeaveType::where([
        ['start_leaves', '>=', $from],
        ['end_leaves', '<=', $to]
    ])->get();

    foreach ($with_in_month_office_holidays as $office_holiday) {

        $start = Carbon\Carbon::parse($office_holiday->start_leaves);
        $end = Carbon\Carbon::parse($office_holiday->end_leaves);
        $holidays = $end->diffInDays($start);
        $holidays++;

        $next_day_of_holidays=$end->copy()->addDay();

        $interval = new DateInterval('P1D');
        $periods = new DatePeriod($start, $interval, $next_day_of_holidays);
        $days=[];
        foreach ($periods as $period) {
            if($period->isWeekend()) {
                array_push($days, $period);
            }
        }
        $weak_days_in_holidays=count($days);

        $total_office_holidays += $holidays - $weak_days_in_holidays;
    }

    $end_month_holidays = App\LeaveType::where([
        ['start_leaves', '>=', $from],
        ['start_leaves', '<', $to],
        ['end_leaves', '>', $to]
    ])->get();
    foreach ($end_month_holidays as $end_month_holiday) {

        $start = Carbon\Carbon::parse($end_month_holiday->start_leaves);

        $holidays = $to->diffInDays($start);
        $holidays++;

        $interval = new DateInterval('P1D');
        $periods = new DatePeriod($start, $interval, $next_day_of_end_date);
        $days=[];
        foreach ($periods as $period) {
            if($period->isWeekend()) {
                array_push($days, $period);
            }
        }
        $weak_days_in_holidays=count($days);

        $total_office_holidays += $holidays - $weak_days_in_holidays;
    }

    $working_days = $total_days - $weekend_days - $total_office_holidays;

    return $working_days;

}
function get_current_month_absents($id)
{
    $current_date = \Carbon\Carbon::now()->startOfMonth()->toDateString();;
    $first_day_of_current_month = \Carbon\Carbon::parse($current_date);

    $from = $first_day_of_current_month;
    $to = \Carbon\Carbon::now();
    $next_day_of_end_date=$to->copy()->addDay();

    $total_days = $to->diffInDays($from);
    $total_days++;
    $total_office_holidays = 0;

    $total_presents = App\Attendance::where('user_id', '=', $id)
        ->whereBetween('dated', [$from, $to])
        ->count();

    $month_interval = new DateInterval('P1D');
    $month_periods = new DatePeriod($from, $month_interval, $next_day_of_end_date);
    $weak_ends=[];
    foreach ($month_periods as $month_period) {
        if($month_period->isWeekend()) {
            array_push($weak_ends, $month_period);
        }
    }
    $weekend_days=count($weak_ends);

    $start_month_holidays = App\LeaveType::where([
        ['start_leaves', '<', $from],
        ['end_leaves', '>=', $from]
    ])->get();

    foreach ($start_month_holidays as $start_month_holiday) {

        $end = Carbon\Carbon::parse($start_month_holiday->end_leaves);
        $next_day_of_holidays=$end->copy()->addDay();
        $holidays = $end->diffInDays($from);
        $holidays ++;
        $interval = new DateInterval('P1D');
        $periods = new DatePeriod($from, $interval, $next_day_of_holidays);
        $days=[];
        foreach ($periods as $period) {
            if($period->isWeekend()) {
                array_push($days, $period);
            }
        }
        $weak_days_in_holidays=count($days);
        $total_office_holidays += $holidays - $weak_days_in_holidays;
    }

    $with_in_month_office_holidays = App\LeaveType::where([
        ['start_leaves', '>=', $from],
        ['end_leaves', '<=', $to]
    ])->get();

    foreach ($with_in_month_office_holidays as $office_holiday) {

        $start = Carbon\Carbon::parse($office_holiday->start_leaves);
        $end = Carbon\Carbon::parse($office_holiday->end_leaves);
        $holidays = $end->diffInDays($start);
        $holidays++;

        $next_day_of_holidays=$end->copy()->addDay();

        $interval = new DateInterval('P1D');
        $periods = new DatePeriod($start, $interval, $next_day_of_holidays);
        $days=[];
        foreach ($periods as $period) {
            if($period->isWeekend()) {
                array_push($days, $period);
            }
        }
        $weak_days_in_holidays=count($days);

        $total_office_holidays += $holidays - $weak_days_in_holidays;
    }

    $end_month_holidays = App\LeaveType::where([
        ['start_leaves', '>=', $from],
        ['start_leaves', '<', $to],
        ['end_leaves', '>', $to]
    ])->get();
    foreach ($end_month_holidays as $end_month_holiday) {

        $start = Carbon\Carbon::parse($end_month_holiday->start_leaves);

        $holidays = $to->diffInDays($start);
        $holidays++;

        $interval = new DateInterval('P1D');
        $periods = new DatePeriod($start, $interval, $next_day_of_end_date);
        $days=[];
        foreach ($periods as $period) {
            if($period->isWeekend()) {
                array_push($days, $period);
            }
        }
        $weak_days_in_holidays=count($days);

        $total_office_holidays += $holidays - $weak_days_in_holidays;
    }

    $absents = $total_days - $total_presents - $weekend_days - $total_office_holidays;

    return $absents;

}

function get_absents_deduction($id,$month,$salary)
{
    $date = explode('/', $month);
    $month = $date[0];
    $year = $date[1];
    $day = '01';
    $user_complete_date = $year . '-' . $month . '-' . $day;
    $first_day_of_user_date = \Carbon\Carbon::parse($user_complete_date);

    $current_date = \Carbon\Carbon::now()->startOfMonth()->toDateString();;
    $first_day_of_current_month = \Carbon\Carbon::parse($current_date);

    if ($first_day_of_user_date == $first_day_of_current_month) {
        $from = $first_day_of_current_month;
        $to = \Carbon\Carbon::now();
        $next_day_of_end_date=$to->copy()->addDay();

    }else{
        $from = $first_day_of_user_date;
        $user_request = \Carbon\Carbon::parse($user_complete_date);
        $end_date = $user_request->endOfMonth()->toDateString();
        $to = \Carbon\Carbon::parse($end_date);
        $next_day_of_end_date=$to->copy()->addDay();
    }

    $total_days = $to->diffInDays($from);
    $total_days++;
    $total_office_holidays = 0;

    $total_presents = App\Attendance::where('user_id', '=', $id)
        ->whereBetween('dated', [$from, $to])
        ->count();

    $month_interval = new DateInterval('P1D');
    $month_periods = new DatePeriod($from, $month_interval, $next_day_of_end_date);
    $weak_ends=[];
    foreach ($month_periods as $month_period) {
        if($month_period->isWeekend()) {
            array_push($weak_ends, $month_period);
        }
    }
    $weekend_days=count($weak_ends);

    $start_month_holidays = App\LeaveType::where([
        ['start_leaves', '<', $from],
        ['end_leaves', '>=', $from]
    ])->get();

    foreach ($start_month_holidays as $start_month_holiday) {

        $end = Carbon\Carbon::parse($start_month_holiday->end_leaves);
        $next_day_of_holidays=$end->copy()->addDay();
        $holidays = $end->diffInDays($from);
        $holidays ++;
        $interval = new DateInterval('P1D');
        $periods = new DatePeriod($from, $interval, $next_day_of_holidays);
        $days=[];
        foreach ($periods as $period) {
            if($period->isWeekend()) {
                array_push($days, $period);
            }
        }
        $weak_days_in_holidays=count($days);
        $total_office_holidays += $holidays - $weak_days_in_holidays;
    }

    $with_in_month_office_holidays = App\LeaveType::where([
        ['start_leaves', '>=', $from],
        ['end_leaves', '<=', $to]
    ])->get();

    foreach ($with_in_month_office_holidays as $office_holiday) {

        $start = Carbon\Carbon::parse($office_holiday->start_leaves);
        $end = Carbon\Carbon::parse($office_holiday->end_leaves);
        $holidays = $end->diffInDays($start);
        $holidays++;

        $next_day_of_holidays=$end->copy()->addDay();

        $interval = new DateInterval('P1D');
        $periods = new DatePeriod($start, $interval, $next_day_of_holidays);
        $days=[];
        foreach ($periods as $period) {
            if($period->isWeekend()) {
                array_push($days, $period);
            }
        }
        $weak_days_in_holidays=count($days);

        $total_office_holidays += $holidays - $weak_days_in_holidays;
    }

    $end_month_holidays = App\LeaveType::where([
        ['start_leaves', '>=', $from],
        ['start_leaves', '<', $to],
        ['end_leaves', '>', $to]
    ])->get();
    foreach ($end_month_holidays as $end_month_holiday) {

        $start = Carbon\Carbon::parse($end_month_holiday->start_leaves);

        $holidays = $to->diffInDays($start);
        $holidays++;

        $interval = new DateInterval('P1D');
        $periods = new DatePeriod($start, $interval, $next_day_of_end_date);
        $days=[];
        foreach ($periods as $period) {
            if($period->isWeekend()) {
                array_push($days, $period);
            }
        }
        $weak_days_in_holidays=count($days);

        $total_office_holidays += $holidays - $weak_days_in_holidays;
    }

    $absents = $total_days - $total_presents - $weekend_days - $total_office_holidays;

    $per_day_salary = get_per_day_salary($salary);
    $total_cost= get_total_leaves_deduction($per_day_salary , $absents);

    return $total_cost;

}
function get_late_deduction($id,$month,$salary){

    $date = explode('/', $month);
    $month = $date[0];
    $year = $date[1];
    $day = '01';
    $user_complete_date = $year . '-' . $month . '-' . $day;
    $first_day_of_user_date = \Carbon\Carbon::parse($user_complete_date);

    $current_date = \Carbon\Carbon::now()->startOfMonth()->toDateString();;
    $first_day_of_current_month = \Carbon\Carbon::parse($current_date);

    if ($first_day_of_user_date == $first_day_of_current_month) {
        $from = $first_day_of_current_month;
        $to = \Carbon\Carbon::now();
//        $next_day_of_end_date=$to->copy()->addDay();

    }else{
        $from = $first_day_of_user_date;
        $user_request = \Carbon\Carbon::parse($user_complete_date);
        $end_date = $user_request->endOfMonth()->toDateString();
        $to = \Carbon\Carbon::parse($end_date);
//        $next_day_of_end_date=$to->copy()->addDay();
    }

    $total_presents = App\Attendance::where('user_id', '=', $id)
        ->whereBetween('dated', [$from, $to])
        ->get();
    $office_timing=\App\OfficeTiming::first();
    $total_late_days = 0;
    $late_days = 0;
    $total_half_days = 0;
    foreach ($total_presents as $present) {
        $employee_entry_time = Carbon\Carbon::parse($present->time_from);
        $office_entry_time = Carbon\Carbon::parse($office_timing->late_time);
        $half_day_time = Carbon\Carbon::parse($office_timing->half_day);

        if ($employee_entry_time > $office_entry_time) {
            if($employee_entry_time > $half_day_time){
                $total_half_days++;
            }
            $late_days++;
        }
    }
    $total_late_days = $late_days - $total_half_days;

    $per_day_salary = get_per_day_salary($salary);
    $late_cost=get_late_cost();
    $per_day_late_deduction = get_late_per_day_salary_deduction($per_day_salary , $late_cost);
    $total_late_deduction = floor($per_day_late_deduction * $total_late_days);

    $half_day_cost = get_half_day_cost();
    $half_day_salary_deduction = get_half_day_salary_deduction($per_day_salary , $half_day_cost);
    $total_half_day_deduction= floor($half_day_salary_deduction * $total_half_days);

    $total_deduction = $total_late_deduction + $total_half_day_deduction;

    return $total_deduction;
}

function get_salary($id){
    $salary=DB::table('employees')
         ->select('salary')
        ->where('id',$id)
        ->first();
    return $salary->salary;
}
function get_per_day_salary($salary){
    $per_day_salary = $salary/30;
    return $per_day_salary;
}
function get_leave_cost(){
    $leave=App\LeaveCost::first();
    return $leave->cost;
}
function get_leave_per_day_salary_deduction($leave_cost , $salary){
    $leave_deduction_cost= ($leave_cost/100) * $salary;
    return $leave_deduction_cost;
}
function get_total_leaves_deduction($per_day_salary , $absents){
    $total_cost= floor($per_day_salary * $absents);
    return $total_cost;
}

function get_half_day_cost(){
    $late=App\LateCost::first();
    return $late->half_day_cost;
}
function get_half_day_salary_deduction($per_day_salary , $half_day_cost){
    $half_day_cost= ($half_day_cost/100) * $per_day_salary;
    return $half_day_cost;
}

function get_late_cost(){
    $late=App\LateCost::first();
    return $late->late_cost;
}

function get_late_per_day_salary_deduction($per_day_salary , $late_cost){
      $per_day_deduction = ($late_cost/100) * $per_day_salary;
    return $per_day_deduction;
}
function get_advance($id){
    $head_account=App\HeadAccount::where([
        ['user_id','=',$id],
        ['is_employee','=','yes'],
        ])->first();
    $total_advance_expense = 0;
    if(isset($head_account)) {

        $accountancies = App\Accountancy::where('receivable_id', $head_account->id)->get();
        foreach ($accountancies as $accountancy) {
            $total_advance_expense += $accountancy->amount;
        }
    }
    return $total_advance_expense;
}
function get_provident_fund(){
    return 2000;
}

function get_fbr_tax($salary){
    $tax_amount=0;
    if($salary>35000){
        $tax=\App\Tax::first();
        $tax_amount=($tax->cost/100) * $salary;
        return $tax_amount;
    }else{
        return $tax_amount;
    }
}
function get_head_accounts_title(){
        $head_accounts=App\HeadAccount::select('id','title')->get();
//    $head_accounts=App\HeadAccount::all();
    return $head_accounts;
}
function get_random_id(){

    $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
    $pin =  $characters[rand(0, strlen($characters) - 1)]
            . mt_rand(1000000, 9999999)
            . $characters[rand(0, strlen($characters) - 1)];
    $string = str_shuffle($pin);
    if (App\HeadAccount::where('account_id', '=', $string)->exists()) {

       get_random_id();
    }else{
        return $string;
    }

}
function get_company_head_account(){

  $head_account = App\HeadAccount::where('account_id','JB1136382')->first();
  return $head_account;
}
function get_provident_account(){

  $provident_account = App\HeadAccount::where('account_id','6004N8L68')->first();
  return $provident_account;
}
function get_leave_deduction_account(){

  $leave_deduction = App\HeadAccount::where('account_id','64EM97226')->first();
  return $leave_deduction;
}
function get_late_deduction_account(){

  $late_deduction = App\HeadAccount::where('account_id','231967B7R')->first();
  return $late_deduction;
}
function get_gs_tax_account(){

  $gs_tax = App\HeadAccount::where('account_id','04442G55E')->first();
  return $gs_tax;
}
function get_employee_head_account($id){

    $head_account=App\HeadAccount::where([
        ['user_id','=', $id],
        ['is_employee','=','yes'],
    ])->first();
    return $head_account;
}
function delete_employee_accountancy($id){

    $accountancies = App\Accountancy::where('receivable_id', $id)->get();
    foreach ($accountancies as $accountancy) {
        $record = App\Accountancy::find($accountancy->id);
        $record->delete();
    }
}
function get_interviewed_applicants(){

//    $interviews = App\Aquisition::where('call_status' ,'sent')->get();
    $interviews =  DB::table('aquisitions')
        ->join('job_titles','job_titles.id','=','aquisitions.job_title')
        ->select('aquisitions.*','job_titles.job_title as title_of_job')
        ->where('call_status' ,'sent')
        ->get();
    return $interviews;

}
function get_interviewed_and_evaluated_applicants(){

//    $interviews = App\Aquisition::where([
//        ['call_status' ,'sent'],
//        ['evaluation_status' ,null],
//        ])->get();
    $interviews =  DB::table('aquisitions')
        ->join('job_titles','job_titles.id','=','aquisitions.job_title')
        ->select('aquisitions.*','job_titles.job_title as title_of_job')
        ->where([
            ['call_status' ,'sent'],
            ['evaluation_status' ,null],
        ])
        ->get();
    return $interviews;

}
