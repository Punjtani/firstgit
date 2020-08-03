<?php

namespace App\Http\Controllers\Guards;

use App\Models\Clients\ClientGuardsAssociation;
use App\Models\Clients\Clients;
use App\Models\Guards\GuardStatusesModel;
use App\Models\Guards\GuardVerificationStatusesModel;
use App\Models\Guards\GuardVerificationTypesModel;
use App\Models\Guards\RegionalOfficeModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SalaryController extends Controller
{
    public function export(){
        $guards = new ClientGuardsAssociation();
        $guards = $guards->getAll();
        foreach ($guards as $guard) {
            dd($guard);

        }


        $regionalOffices = RegionalOfficeModel::all();
        $guardStatus = GuardStatusesModel::all();
        $allVerifications = GuardVerificationTypesModel::all();
        $allVerificationStatus = GuardVerificationStatusesModel::all();

        $allClients = new Clients();
        $allClients = $allClients->getAllClientsWithoutPaginate();

        $reportFormats = \config('globalvariables.$reportFormats');

        return view('Salary.export', compact('regionalOffices', 'reportFormats',
            'guardStatus', 'allVerifications', 'allVerificationStatus' , 'allClients'));

    }
    public function hiredGuards(Request $request)
    {
        $guardsObject = new Guards();
        $hiredGuards = $guardsObject->getHiredGuards($request->all());

        if ($hiredGuards->isNotEmpty()) {
            $reportFormat = $request->get('report_format') ? $request->get('report_format') : 1;

            $bloodGroupModal = new BloodGroupsModel();
            $usersModal = new UserModel();

            foreach ($hiredGuards as $guard) {
                $guard->name = ucfirst($guard->name);
                $guard->current_status_name = ucfirst($guard->getCurrentStatus->value);
                $guard->regional_office_name = ucfirst($guard->getRegionalOfficeModel->office_head);
                $guard->age = $guard->age . ' Years';
                $guard->blood_group_name = $bloodGroupModal->getModelById($guard->blood_group_id) ? ucfirst($bloodGroupModal->getModelById($guard->blood_group_id)->name) : 'Nill';
                $guard->education_name = $guard->education ? ucfirst($guard->getEducationModel->name) : 'None';
                $guard->enrollment_date = date('jS M, Y', strtotime($guard->created_at));
                $guard->termination_date = $guard->termination_date ? date('jS M, Y', strtotime($guard->termination_date)) : 'N/A';
                $guard->resign_date = $guard->resign_date ? date('jS M, Y', strtotime($guard->resign_date)) : 'N/A';
                $guard->enrolled_by = $usersModal->getUserById($guard->enrolled_by) ? ucfirst($usersModal->getUserById($guard->enrolled_by)->name) : 'Nill';
            }

            if ($reportFormat == 1) {
//                $inputs = new \stdClass();
//
//                $regionalOffice = $guardStatus = 'All';
//
//                $rg_office  = RegionalOfficeModel::find($request->get('regional_office'));
//                $guard_st = GuardStatusesModel::find($request->get('guards_status'));
//
//                if ($rg_office){
//                    $regionalOffice = $rg_office->office_head;
//                }
//
//                if ($guard_st){
//                    $guardStatus = $guard_st->value;
//                }
//
//                $inputs->start_date = date('jS M, Y',strtotime($request->get('hired_from')));
//                $inputs->end_date = date('jS M, Y',strtotime($request->get('hired_till')));
//                $inputs->regional_office = $regionalOffice;
//                $inputs->guard_status = $guardStatus;

                $domPdf = new Dompdf();
//                $domPdf->loadHtml(view('reports.pdf.hiredGuards', compact('hiredGuards','inputs')));
                $domPdf->loadHtml(view('reports.pdf.hiredGuards', compact('hiredGuards')));
//                $domPdf->loadHtml('Hello World!!');
                // (Optional) Setup the paper size and orientation
                $domPdf->setPaper('A4', 'portrait');
                // Render the HTML as PDF
                $domPdf->render();
                // Output the generated PDF to Browser
                $domPdf->stream('HiredGuards'.date('Y-m-d'));
            } elseif ($reportFormat == 2) {
//                Excel
                try {
                    $directory = storage_path('Reports');
                    $fileName = 'hiredGuards' . date('Y-m-d') . ".csv";

                    if (!is_dir($directory)) {
                        mkdir($directory);
                    }

                    $fileName = $directory . "/" . $fileName;
                    $file = new \SplFileObject($fileName, 'w');

                    $fileHeader = [];
                    $fileHeader[] = 'Parwest Id';
                    $fileHeader[] = 'Name';
                    $fileHeader[] = 'Status';
                    $fileHeader[] = 'CNIC';
                    $fileHeader[] = 'Regional Office';
                    $fileHeader[] = 'Supervisor';
                    $fileHeader[] = 'Age';
                    $fileHeader[] = 'Blood Group';
                    $fileHeader[] = 'Education';
                    $fileHeader[] = 'Enrollment Date';
                    $fileHeader[] = 'Enrolled By';
                    $fileHeader[] = 'Termination Date';
                    $fileHeader[] = 'Resign Date';

                    $file->fputcsv($fileHeader, ',');

                    foreach ($hiredGuards as $guard) {
                        $guardDetailArray = [];
                        $guardDetailArray[] = $guard->parwest_id;
                        $guardDetailArray[] = $guard->name;
                        $guardDetailArray[] = $guard->current_status_name;
                        $guardDetailArray[] = $guard->cnic_no;
                        $guardDetailArray[] = $guard->regional_office_name;
                        $guardDetailArray[] = $guard->introducer;
                        $guardDetailArray[] = $guard->age;
                        $guardDetailArray[] = $guard->blood_group_name;
                        $guardDetailArray[] = $guard->education_name;
                        $guardDetailArray[] = $guard->enrollment_date;
                        $guardDetailArray[] = $guard->enrolled_by;
                        $guardDetailArray[] = $guard->termination_date;
                        $guardDetailArray[] = $guard->resign_date;

                        $file->fputcsv($guardDetailArray, ',');
                    }
                    return response()->download($fileName);
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', 'Error in making Excel File! Please Retry!!');
//                    return redirect()->back()->with('error', $e->getMessage() . " Line " . $e->getLine() . "File" . $e->getFile());
                }
            }
        } else {
            return redirect()->back()->withInput()->with('warning', 'No Guards found matching the specified criteria!');
        }
    }

    public function verifiedGuards(Request $request)
    {
        $guardsObject = new GuardVerificationModel();
        $guards = $guardsObject->getGuardsByVerifications($request->all());

        if ($guards->isNotEmpty()) {
            $reportFormat = $request->get('report_format_veri') ? $request->get('report_format_veri') : 1;

            $bloodGroupModal = new BloodGroupsModel();
            $usersModal = new UserModel();

            $allGuardsStatus = GuardStatusesModel::all();
            $allRegionalOffice = RegionalOfficeModel::all();
            $allEducations = GuardEducationsTypesModel::all();

            foreach ($guards as $guard) {
                $guard->education_name = 'None';

                foreach ($allGuardsStatus as $status) {
                    if ($status->id == $guard->current_status_id)
                        $guard->current_status_name = ucfirst($status->value);
                }

                foreach ($allRegionalOffice as $office) {
                    if ($office->id == $guard->regional_office_id)
                        $guard->regional_office_name = ucfirst($office->office_head);
                }

                foreach ($allEducations as $education) {
                    if ($education->id == $guard->education)
                        $guard->education_name = ucfirst($education->name);
                }

                $guard->name = ucfirst($guard->name);
                $guard->age = $guard->age . ' Years';
                $guard->blood_group_name = $bloodGroupModal->getModelById($guard->blood_group_id) ? ucfirst($bloodGroupModal->getModelById($guard->blood_group_id)->name) : 'Nill';

                $guard->enrollment_date = date('jS M, Y', strtotime($guard->created_at));
                $guard->termination_date = $guard->termination_date ? date('jS M, Y', strtotime($guard->termination_date)) : 'N/A';
                $guard->enrolled_by = $usersModal->getUserById($guard->enrolled_by) ? ucfirst($usersModal->getUserById($guard->enrolled_by)->name) : 'Nill';

                $guard->verification_name = $guard->verification_name ? ucfirst($guard->verification_name) : 'Nill';
                $guard->verification_status_name = $guard->verification_status_name ? ucfirst($guard->verification_status_name) : 'Nill';
                $guard->verification_comment = $guard->comment ? ucfirst($guard->comment) : 'Nill';
                $guard->verification_file = $guard->verification_file ? 'Yes' : 'No';
                $guard->verification_by = $guard->added_by ? ucfirst($usersModal->getUserById($guard->added_by)->name) : 'Nill';
                $guard->verification_date = $guard->verification_date ? date('jS M, Y', strtotime($guard->verification_date)) : 'N/A';
            }

            if ($reportFormat == 1) {
//                PDF
                $domPdf = new Dompdf();
                $domPdf->loadHtml(view('reports.pdf.verifiedGuards', compact('guards')));

                // (Optional) Setup the paper size and orientation
                $domPdf->setPaper('A4', 'portrait');
//                // Render the HTML as PDF
                $domPdf->render();
//                // Output the generated PDF to Browser
                $domPdf->stream('VerifiedGuards'.date('Y-m-d'));
            } elseif ($reportFormat == 2) {
//                Excel
                try {
                    $directory = storage_path('Reports');
                    $fileName = 'verifiedGuards' . date('Y-m-d') . ".csv";

                    if (!is_dir($directory)) {
                        mkdir($directory);
                    }

                    $fileName = $directory . "/" . $fileName;
                    $file = new \SplFileObject($fileName, 'w');

                    $fileHeader = [];
                    $fileHeader[] = 'Parwest Id';
                    $fileHeader[] = 'Name';
                    $fileHeader[] = 'Status';
                    $fileHeader[] = 'CNIC';
                    $fileHeader[] = 'Regional Office';
                    $fileHeader[] = 'Age';
                    $fileHeader[] = 'Blood Group';
                    $fileHeader[] = 'Education';
                    $fileHeader[] = 'Enrollment Date';
                    $fileHeader[] = 'Enrolled By';
                    $fileHeader[] = 'Termination Date';

                    $fileHeader[] = 'Verification Type';
                    $fileHeader[] = 'Verification Status';
                    $fileHeader[] = 'Verified By';
                    $fileHeader[] = 'Comments';
                    $fileHeader[] = 'Is File Attached?';
                    $fileHeader[] = 'Verification Date';

                    $file->fputcsv($fileHeader, ',');

                    foreach ($guards as $guard) {
                        $guardDetailArray = [];
                        $guardDetailArray[] = strtoupper($guard->parwest_id);
                        $guardDetailArray[] = $guard->name;
                        $guardDetailArray[] = $guard->current_status_name;
                        $guardDetailArray[] = $guard->cnic_no;
                        $guardDetailArray[] = $guard->regional_office_name;
                        $guardDetailArray[] = $guard->age;
                        $guardDetailArray[] = $guard->blood_group_name;
                        $guardDetailArray[] = $guard->education_name;
                        $guardDetailArray[] = $guard->enrollment_date;
                        $guardDetailArray[] = $guard->enrolled_by;
                        $guardDetailArray[] = $guard->termination_date;

                        $guardDetailArray[] = $guard->verification_name;
                        $guardDetailArray[] = $guard->verification_status_name;
                        $guardDetailArray[] = $guard->verification_by;
                        $guardDetailArray[] = $guard->verification_comment;
                        $guardDetailArray[] = $guard->verification_file;
                        $guardDetailArray[] = $guard->verification_date;

                        $file->fputcsv($guardDetailArray, ',');
                    }
                    return response()->download($fileName);
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', 'Error in making Excel File! Please Retry!!');
                }
            }
        }else{
            return redirect()->back()->withInput()->with('warning','No Guards found matching the specified criteria!');
        }
    }
    public function deployedGuards(Request $request)
    {
//        dd($request);

        $regionalOffices = RegionalOfficeModel::all();
        $guardStatus = GuardStatusesModel::all();
        $allVerifications = GuardVerificationTypesModel::all();
        $allVerificationStatus = GuardVerificationStatusesModel::all();

        $allClients = new Clients();
        $allClients = $allClients->getAllClientsWithoutPaginate();
        $branchModel =  new ClientBranchesModel();

        $deployedGuards = $this->getDeployedGuards($request);
        $deployedGuardsEncoded = base64_encode(serialize($deployedGuards));

        $reportFormats = \config('globalvariables.$reportFormats');

        return view('reports.guards.guardsReports', compact('regionalOffices', 'reportFormats',
            'guardStatus', 'allVerifications', 'allVerificationStatus' , 'allClients' , 'deployedGuards' ,
            'deployedGuardsEncoded', 'branchModel'));
    }

    public function getDeployedGuards($request)
    {
        $guardAttendance = array();
        $where = "";

//        appending search parameters into string
        if ($request->client != 0) {
            $where.= " AND client_guard_association.client_id = $request->client ";
        }
        else{
            $where.= " AND client_guard_association.client_id = 0 ";
        }

        if ($request->branch != 0) {
            $where.= " AND client_guard_association.branch_id = $request->branch ";
        }


        if ($request->startDate != NULL && $request->endDate != NULL) {
            $where.= " AND(( client_guard_association.end_date >= '$request->startDate' OR client_guard_association.end_date IS NULL) ";
            $where.= " AND( client_guard_association.created_at <= '$request->endDate')) ";
        }

        elseif ($request->startDate != NULL) {
            $where.= " AND( client_guard_association.end_date >= '$request->startDate' OR client_guard_association.end_date IS NULL) ";
        }

        elseif ($request->endDate != NULL) {
            $where.= " AND( client_guard_association.created_at <= '$request->endDate') ";
        }
        $where.=" Order By client_guard_association.created_at ASC ";

        $guardsAssignedToClient = DB::select("SELECT
                guards.id,
                guards.parwest_id,
                guards.name,
                guards.cnic_no,
                guards.contact_no,
                guards.father_name,
                guards.date_of_birth,
                guards.ex,
                guards.current_status_id,
                guards.created_at,
                client_guard_association.branch_id as branch_id,
                client_guard_association.client_id as client_id,
                client_guard_association.is_currently_deployed,
                client_guard_association.shift_day_night as shift,
                client_guard_association.is_overtime,
                client_guard_association.isExtra,
                client_guard_association.deployed_as,
                client_guard_association.created_at as assign_date,
                client_guard_association.end_date
                FROM `client_guard_association`
                JOIN `guards`
                ON guards.id = client_guard_association.guard_id". $where);

//        $a = "SELECT
//                guards.id,
//                guards.parwest_id,
//                guards.name,
//                guards.cnic_no,
//                guards.contact_no,
//                guards.father_name,
//                guards.date_of_birth,
//                guards.ex,
//                guards.current_status_id,
//                guards.created_at,
//                client_guard_association.branch_id as branch_id,
//                client_guard_association.client_id as client_id,
//                client_guard_association.is_currently_deployed,
//                client_guard_association.shift_day_night as shift,
//                client_guard_association.is_overtime,
//                client_guard_association.isExtra,
//                client_guard_association.deployed_as,
//                client_guard_association.created_at as assign_date,
//                client_guard_association.end_date
//                FROM `client_guard_association`
//                JOIN `guards`
//                ON guards.id = client_guard_association.guard_id
//                WHERE client_guard_association.client_id = $request->client". $where;
//        print_r($a);

        $counter = 0;
        foreach ($guardsAssignedToClient as $key=>$searchResult){
            if($searchResult->assign_date != $searchResult->end_date ){

                $guardAttendance[$counter] = $searchResult;
                $counter++;
            }
        }

        return $guardAttendance;

    }

    public function deployedGuardsExcelExport(Request $request){

        $searchResults = (object)(unserialize(base64_decode($request->deployedGuardsEncoded)));
//        $searchResults = $searchResults->toArray()['data'];

        $searchResults = json_decode(json_encode($searchResults), true);
//        dd($searchResults);

        $fileName = md5(microtime());
//        dd($fileName);

        $guardDesignationModel = new GuardDesignationModel();
        $clientBranchesModel = new ClientBranchesModel();
        $clientsModel = new Clients();
        $exServicesModel = new GuardExServices();
        $GuardStatusesModel = new GuardStatusesModel();

        foreach ($searchResults as $key => $value) {

            $searchResults[$key]['id'] = $key+1;
            $searchResults[$key]['deployed_as'] = $guardDesignationModel->getModelById($value['deployed_as'])->name;
            $searchResults[$key]['branch_id'] = $clientBranchesModel->getModelById($value['branch_id'])->name;
            $searchResults[$key]['client_id'] = $clientsModel->getModelById($value['client_id'])->name;
            $searchResults[$key]['current_status_id'] = $GuardStatusesModel->getModelById($value['current_status_id'])->value;
            $searchResults[$key]['ex'] = $exServicesModel->getModelById($value['ex'])->name;

            if($value['shift'] == 0){
                $searchResults[$key]['shift'] = 'Night';
            }
            else{
                $searchResults[$key]['shift'] = 'Day';
            }

            if($value['is_currently_deployed'] == 1){

                $searchResults[$key]['is_currently_deployed'] = 'Curreenlty Deployed';
            }
            else{
                $searchResults[$key]['is_currently_deployed'] = 'Finished';
            }

            if($value['isExtra'] == 1){

                $searchResults[$key]['isExtra'] = 'Extra Guard';
            }
            else{
                $searchResults[$key]['isExtra'] = 'No';
            }

            if($value['is_overtime'] == 1){

                $searchResults[$key]['is_overtime'] = 'Over Time';
            }
            else{
                $searchResults[$key]['is_overtime'] = 'Regular';
            }

            if($value['end_date'] == ""){

                $searchResults[$key]['end_date'] = '-------';
            }

        }


//        dd($searchResults);
        Excel::create($fileName, function ($excel) use ($searchResults) {


            $excel->sheet('Sheet1', function ($sheet) use ($searchResults) {
                $sheet->row(1, array(
                    '#', 'Parwest Id', 'Name', 'Father\'s Name' ,  'CNIC #', 'Contact #', 'DOB' , 'EX' ,
                    'Enrollment Date' , 'Current Status' , 'Client Name', 'Branch Name',
                    'Status', 'shift', 'Regular / Over Time', 'Extra Guard', 'Deployed as',
                    'Deployment Date', 'Revoke Date',

                ));

                $count = 2;
                foreach ($searchResults as $key => $array) {
                    $sheet->row($count, array(

                        $array['id'], $array['parwest_id'], $array['name'], $array['father_name'], $array['cnic_no'],
                        $array['contact_no'], $array['date_of_birth'], $array['ex'], $array['created_at'],
                        $array['current_status_id'], $array['client_id'], $array['branch_id'],
                        $array['is_currently_deployed'], $array['shift'], $array['is_overtime'],
                        $array['isExtra'], $array['deployed_as'], $array['assign_date'], $array['end_date']

                    ));
                    $count += 1;
                }
            });

            $excel->setTitle('Deplpoyed Guard History');
            $excel->setDescription('Deployed Guard History of a client');


        })->store('xlsx', Config::get('globalvariables.$pathToSaveTemporaryExcelFiles'));

        $fileNameToDownload = $fileName . '.xlsx';

        $data = array('fileNameToDownload' => $fileNameToDownload);

        return ['responseCode' => 1, 'responseStatus' => 'successful', 'message' => 'your file is ready, and will download in few seconds', 'data' => $data];


    }

    public function deployedGuardsExcelExportDownload($filename){

        // Check if file exists in app/storage/file folder
        $file_path = Config::get('globalvariables.$pathToSaveTemporaryExcelFiles') . '/' . $filename;
        if (file_exists($file_path)) {
            // Send Download
            return Response::download($file_path, $filename, [
                'Content-Length: ' . filesize($file_path)
            ])->deleteFileAfterSend(true);
        } else {
            // Error
            exit('Requested file does not exist on our server!');
        }
    }


//    public function guardsHiredLastWeek()
//    {
//        $guardsModel = new Guards();
//        $searchResults = $guardsModel->guardsHiredLastWeek();
//        $fileName = md5(microtime());
//
//
//        Excel::create($fileName, function ($excel) use ($searchResults) {
//
//            $excel->setTitle('Guards Hired In Last Week');
//            $excel->setDescription('Following guards hired in last week');
//
//            $excel->sheet('Sheet1', function ($sheet) use ($searchResults) {
//
//
//                // Sheet manipulation
//                $sheet->setOrientation('landscape');
//
//
//                $sheet->fromArray($searchResults, null, 'A1', true, true);
//
//
//            });
//
//        })->store('xlsx', Config::get('globalvariables.$pathToSaveTemporaryExcelFiles'));
//
//        $fileNameToDownload = $fileName . '.xlsx';
//
//        // Check if file exists in app/storage/file folder
//        $file_path = Config::get('globalvariables.$pathToSaveTemporaryExcelFiles') . '/' . $fileNameToDownload;
//        if (file_exists($file_path)) {
//            // Send Download
//            return Response::download($file_path, 'guards_hired_last_week.xlsx', [
//                'Content-Length: ' . filesize($file_path)
//            ])->deleteFileAfterSend(true);
//        } else {
//            // Error
//            exit('Requested file does not exist on our server!');
//        }
//    }
//    public function guardsHiredLastMonth()
//    {
//        $guardsModel = new Guards();
//        $searchResults = $guardsModel->guardsHiredLastMonth();
//        $fileName = md5(microtime());
//
//
//        Excel::create($fileName, function ($excel) use ($searchResults) {
//
//            $excel->setTitle('Guards Hired In Last Month');
//            $excel->setDescription('Following guards hired in last month');
//
//            $excel->sheet('Sheet1', function ($sheet) use ($searchResults) {
//
//
//                // Sheet manipulation
//                $sheet->setOrientation('landscape');
//
//
//                $sheet->fromArray($searchResults, null, 'A1', true, true);
//
//
//            });
//
//        })->store('xlsx', Config::get('globalvariables.$pathToSaveTemporaryExcelFiles'));
//
//        $fileNameToDownload = $fileName . '.xlsx';
//
//        // Check if file exists in app/storage/file folder
//        $file_path = Config::get('globalvariables.$pathToSaveTemporaryExcelFiles') . '/' . $fileNameToDownload;
//        if (file_exists($file_path)) {
//            // Send Download
//            return Response::download($file_path, 'guards_hired_last_month.xlsx', [
//                'Content-Length: ' . filesize($file_path)
//            ])->deleteFileAfterSend(true);
//        } else {
//            // Error
//            exit('Requested file does not exist on our server!');
//        }
//    }
//    public function terminatedGuards()
//    {
//        $guardsModel = new Guards();
//        $searchResults = $guardsModel->terminatedGuards();
//        $fileName = md5(microtime());
//
//
//        Excel::create($fileName, function ($excel) use ($searchResults) {
//
//            $excel->setTitle('Terminated Guards');
//            $excel->setDescription('Following is the list of terminated guards');
//
//            $excel->sheet('Sheet1', function ($sheet) use ($searchResults) {
//
//
//                // Sheet manipulation
//                $sheet->setOrientation('landscape');
//
//
//                $sheet->fromArray($searchResults, null, 'A1', true, true);
//
//
//            });
//
//        })->store('xlsx', Config::get('globalvariables.$pathToSaveTemporaryExcelFiles'));
//
//        $fileNameToDownload = $fileName . '.xlsx';
//
//        // Check if file exists in app/storage/file folder
//        $file_path = Config::get('globalvariables.$pathToSaveTemporaryExcelFiles') . '/' . $fileNameToDownload;
//        if (file_exists($file_path)) {
//            // Send Download
//            return Response::download($file_path, 'terminated_guards.xlsx', [
//                'Content-Length: ' . filesize($file_path)
//            ])->deleteFileAfterSend(true);
//        } else {
//            // Error
//            exit('Requested file does not exist on our server!');
//        }
//    }
//
//    public function branchesOpenedLastWeek()
//    {
//        $clientBranchesModel = new ClientBranchesModel();
//        $searchResults = $clientBranchesModel->branchesOpenedLastWeek();
//        $fileName = md5(microtime());
//
//
//
//        Excel::create($fileName, function ($excel) use ($searchResults) {
//
//            $excel->setTitle('Branches Opened In Last Week');
//            $excel->setDescription('Following branches opened in last week');
//
//            $excel->sheet('Sheet1', function ($sheet) use ($searchResults) {
//
//
//                // Sheet manipulation
//                $sheet->setOrientation('landscape');
//
//
//                $sheet->fromArray($searchResults, null, 'A1', true, true);
//
//
//            });
//
//        })->store('xlsx', Config::get('globalvariables.$pathToSaveTemporaryExcelFiles'));
//
//        $fileNameToDownload = $fileName . '.xlsx';
//
//        // Check if file exists in app/storage/file folder
//        $file_path = Config::get('globalvariables.$pathToSaveTemporaryExcelFiles') . '/' . $fileNameToDownload;
//        if (file_exists($file_path)) {
//            // Send Download
//            return Response::download($file_path, 'branches_opened_last_week.xlsx', [
//                'Content-Length: ' . filesize($file_path)
//            ])->deleteFileAfterSend(true);
//        } else {
//            // Error
//            exit('Requested file does not exist on our server!');
//        }
//    }
//    public function branchesOpenedLastMonth()
//    {
//        $clientBranchesModel = new ClientBranchesModel();
//        $searchResults = $clientBranchesModel->branchesOpenedLastMonth();
//        $fileName = md5(microtime());
//
//
//
//        Excel::create($fileName, function ($excel) use ($searchResults) {
//
//            $excel->setTitle('Branches Opened In Last Month');
//            $excel->setDescription('Following branches opened in last month');
//
//            $excel->sheet('Sheet1', function ($sheet) use ($searchResults) {
//
//
//                // Sheet manipulation
//                $sheet->setOrientation('landscape');
//
//
//                $sheet->fromArray($searchResults, null, 'A1', true, true);
//
//
//            });
//
//        })->store('xlsx', Config::get('globalvariables.$pathToSaveTemporaryExcelFiles'));
//
//        $fileNameToDownload = $fileName . '.xlsx';
//
//        // Check if file exists in app/storage/file folder
//        $file_path = Config::get('globalvariables.$pathToSaveTemporaryExcelFiles') . '/' . $fileNameToDownload;
//        if (file_exists($file_path)) {
//            // Send Download
//            return Response::download($file_path, 'branches_opened_last_month.xlsx', [
//                'Content-Length: ' . filesize($file_path)
//            ])->deleteFileAfterSend(true);
//        } else {
//            // Error
//            exit('Requested file does not exist on our server!');
//        }
//    }
//    public function clientEnrolledLastWeek()
//    {
//        $clientModel = new Clients();
//        $searchResults = $clientModel->clientsEnrolledLastWeek();
//        $fileName = md5(microtime());
//
//
//
//        Excel::create($fileName, function ($excel) use ($searchResults) {
//
//            $excel->setTitle('Clients Enrolled In Last Week');
//            $excel->setDescription('Following clients enrolled in last week');
//
//            $excel->sheet('Sheet1', function ($sheet) use ($searchResults) {
//
//
//                // Sheet manipulation
//                $sheet->setOrientation('landscape');
//
//
//                $sheet->fromArray($searchResults, null, 'A1', true, true);
//
//
//            });
//
//        })->store('xlsx', Config::get('globalvariables.$pathToSaveTemporaryExcelFiles'));
//
//        $fileNameToDownload = $fileName . '.xlsx';
//
//        // Check if file exists in app/storage/file folder
//        $file_path = Config::get('globalvariables.$pathToSaveTemporaryExcelFiles') . '/' . $fileNameToDownload;
//        if (file_exists($file_path)) {
//            // Send Download
//            return Response::download($file_path, 'clients_enrolled_last_week.xlsx', [
//                'Content-Length: ' . filesize($file_path)
//            ])->deleteFileAfterSend(true);
//        } else {
//            // Error
//            exit('Requested file does not exist on our server!');
//        }
//    }
//    public function clientEnrolledLastMonth()
//    {
//        $clientModel = new Clients();
//        $searchResults = $clientModel->clientsEnrolledLastMonth();
//        $fileName = md5(microtime());
//
//
//
//        Excel::create($fileName, function ($excel) use ($searchResults) {
//
//            $excel->setTitle('Clients Enrolled In Last Month');
//            $excel->setDescription('Following clients enrolled in last month');
//
//            $excel->sheet('Sheet1', function ($sheet) use ($searchResults) {
//
//
//                // Sheet manipulation
//                $sheet->setOrientation('landscape');
//
//
//                $sheet->fromArray($searchResults, null, 'A1', true, true);
//
//
//            });
//
//        })->store('xlsx', Config::get('globalvariables.$pathToSaveTemporaryExcelFiles'));
//
//        $fileNameToDownload = $fileName . '.xlsx';
//
//        // Check if file exists in app/storage/file folder
//        $file_path = Config::get('globalvariables.$pathToSaveTemporaryExcelFiles') . '/' . $fileNameToDownload;
//        if (file_exists($file_path)) {
//            // Send Download
//            return Response::download($file_path, 'clients_enrolled_last_month.xlsx', [
//                'Content-Length: ' . filesize($file_path)
//            ])->deleteFileAfterSend(true);
//        } else {
//            // Error
//            exit('Requested file does not exist on our server!');
//        }
//    }

}
