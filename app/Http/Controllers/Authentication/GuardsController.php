<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Clients\ClientsController;
use App\Models\Clients\ClientBranchesModel;
use App\Models\Clients\ClientBranchesExtraGuardDemands;
use App\Models\Clients\ClientBranchWiseGuardSalary;
use App\Models\Clients\ClientContractsRates;
use App\Models\Clients\ClientGuardsAssociation;
use App\Models\Clients\ClientProvincesModel;
use App\Models\Clients\Clients;
use App\Models\Clients\ClientType;
use App\Models\Dashboard\DashboardOptionsByUserRoleModel;
use App\Models\Dashboard\DashboardOptionsByUsersModel;
use App\Models\Dashboard\DashboardSubOptionsModel;
use App\Models\Documents\DocumentType;
use App\Models\Guards;
use App\Models\Guards\GuardDesignationModel;
use App\Models\Guards\GuardExServices;
use App\Models\Guards\GuardLoansModel;
use App\Models\Guards\GuardStatusesModel;
use App\Models\Guards\GuardVerificationModel;
use App\Models\Guards\PayrollDefaultsModel;
use App\Models\Guards\PayrollSalaryMonth;
use App\Models\Guards\PayrollSpecialDuty;
use App\Models\Helpers;
use App\Models\Inventory\InventoryAssignHistoryModel;
use App\Models\Inventory\InventoryCategoryModel;
use App\Models\Inventory\InventoryDemandsFromRegionalOfficesModel;
use App\Models\Inventory\InventoryIssuedToGuardsModel;
use App\Models\Inventory\InventoryProductsModel;
use App\Models\Inventory\InventoryProductsNamesModel;
use App\Models\Inventory\InventoryProductsStatusModel;
use App\Models\Mix\CitiesModel;
use App\Models\Salary\Salary;
use App\Models\Settings\SettingsModel;
use App\Models\Ticketation\Statuses\Status;
use App\Models\Ticketation\Tickets\Ticket;
use App\Models\Ticketation\Tickets\TicketAssignHistoryModel;
use App\Models\Ticketation\Users\User;
use App\Models\Users\ClientUserAssociationModel;
use App\Models\Users\CustomPermissionsOnSubModules;
use App\Models\Users\CustomRolePermissions;
use App\Models\Users\CustomUserPermissions;
use App\Models\Users\MailQueuesByUsersModel;
use App\Models\Users\ManagerSupervisorAssociation;
use App\Models\Users\UserModel;
use App\Models\Users\UserPersonalInformationModel;
use App\Repositories\CentralModel;
use App\Models\Mix;
use App\Models\Guards\GuardPledgableDocumentsTypeModel;
use App\Repositories\DashboardRepository;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
use Dompdf\Dompdf;
use Dompdf\Exception;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use App\classes\FileManager;
use Chumper\Zipper\Facades\Zipper;
use Maatwebsite\Excel\Facades\Excel;
use phpDocumentor\Reflection\Types\Null_;
use Spatie\Permission\Guard;
use ZipArchive;
use Illuminate\Support\Facades\File;
use Illuminate\Filesystem\Filesystem;
use Barryvdh\DomPDF\Facade as PDF;
use App\Models\CustomizedDashboardHelpers;



class GuardsController extends Controller
{
//   public function addGuard(){
//      $model = new  CentralModel( Guards::class , Input::all() , null  );
//      $model->saveModel();
//   }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $guard = new Guards\Guards();
        $guard = $guard->guardList();

        $allDesignation = Guards\GuardDesignationModel::all();
        $allStatus = Guards\GuardStatusesModel::all();

        $exServicesModel = new Guards\GuardExServices();


        $data = array(
            'guard' => $guard,
            'exServicesModel' => $exServicesModel,
            'designations' => $allDesignation,
            'status' => $allStatus
        );
        return view('guards.list')->with($data);
    }


    public function stats()
    {
        $DashboardRepositoryObject=new DashboardRepository();
        $resultStats=$DashboardRepositoryObject->getGuardsStats(0);
        $helpersModel = new Helpers();
        $customizedDashboardHelpersModel = new CustomizedDashboardHelpers();
        $helpers = $helpersModel->getAllRolePermissionFlags();
        $customizedDashboardHelpers = $customizedDashboardHelpersModel->getAllDashboardOptionOfUser();
        if($customizedDashboardHelpers->guardBasicStats
            || $customizedDashboardHelpers->guardWeeklyAttendance ||
            $customizedDashboardHelpers->guardCategories)
        {
            return view("Stats.guardstats")->with('resultStats',$resultStats);
        }
        else
        {
            return view('users.permissionDeniedPage');
        }




    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $enrollForm = new Guards\GuardsPrerequisite();
        $prerequisite = $enrollForm->activePrerequisite();

        $allDesignations = new Guards\GuardDesignationModel();
        $allDesignations = $allDesignations->getAll();


        $allReligions = new Mix\ReligionModel();
        $allReligions = $allReligions->getAll();

        $allSects = new Mix\SectModel();
        $allSects = $allSects->getAll();

        $allEducations = new Guards\GuardEducationsTypesModel();
        $allEducations = $allEducations->getAll();

        $allRegionalOffices = new Guards\RegionalOfficeModel();
        $allRegionalOffices = $allRegionalOffices->getAll();

        $allExServices = new Guards\GuardExServices();
        $allExServices = $allExServices->getAllByDescendingId();

        $bloodGroupModel = new Mix\BloodGroupsModel();
        $allBloodGroups = $bloodGroupModel->getAll();


        $data = array('prerequisite' => $prerequisite,
            'allDesignations' => $allDesignations,
            'allReligions' => $allReligions,
            'allSects' => $allSects,
            'allEducations' => $allEducations,
            'allRegionalOffices' => $allRegionalOffices,
            'allExServices' => $allExServices,
            'allBloodGroups' => $allBloodGroups);


        return view('guards.create')->with($data);

    }


    public function formA($id)
    {

        $guard = new Guards\Guards();
        $guardSalaryCategoryModel = new Guards\GuardSalaryCategoriesModel();
        $allGuardSalariesCategories = $guardSalaryCategoryModel->getAllModelByAscendingMaxRange();


        $guard = $guard->guardDetail($id);

        $data = array('guard' => $guard, 'allGuardSalariesCategories' => $allGuardSalariesCategories);


        $paper_size = array(0, 0, 1250, 1500);

        $pdf = PDF::loadView('pdf.form_A', $data)->setPaper($paper_size, 'portrait');

        return $pdf->stream();


//        $paper_size = array(0,0,1000,1435);
//        $pdf = PDF::loadView('pdf.formA',array('guard' => $guard))->setPaper($paper_size,'portrait');
//        return $pdf->stream();
    }

    public function downloadFormA($guardId)
    {


        $guard = new Guards\Guards();
        $guardSalaryCategoryModel = new Guards\GuardSalaryCategoriesModel();

        $guard = $guard->guardDetail($guardId);
        $allGuardSalariesCategories = $guardSalaryCategoryModel->getAllModelByAscendingMaxRange();


        $data = array('guard' => $guard, 'allGuardSalariesCategories' => $allGuardSalariesCategories);

        $paper_size = array(0, 0, 1250, 1500);
        $pdf = PDF::loadView('pdf.form_A', $data)->setPaper($paper_size, 'portrait');


        return $pdf->download($guardId . 'form_A.pdf');

//        return $pdf->download($guardId.'form_A.pdf');

    }

    public function downloadFormAInZipDownload($guardId)
    {


        $directory = public_path() . '/tmp_dynamic_pdf_forms';


        if (!is_dir($directory)) {
            File::makeDirectory($directory);
        }
        $guard = new Guards\Guards();
        $guard = $guard->guardDetail($guardId);
        $paper_size = array(0, 0, 1250, 1500);


        $guardSalaryCategoryModel = new Guards\GuardSalaryCategoriesModel();
        $allGuardSalariesCategories = $guardSalaryCategoryModel->getAllModelByAscendingMaxRange();


        $data = array('guard' => $guard, 'allGuardSalariesCategories' => $allGuardSalariesCategories);

        $pdf = PDF::loadView('pdf.form_A', $data)->setPaper($paper_size, 'portrait');
        $output = $pdf->output();
        $fileToSave = File::put($directory . '/' . $guardId . '_form_A.pdf', $output);

//         $tmpVar = public_path('img/mask.png');


//        copy($tmpVar, $directory . '/' . $guardId . '_form_A.pdf');
//
//        echo '<pre>';
//        echo $tmpVar;
//        echo '</pre>';

//        return 'asd';

//        echo '<pre>';
//        echo $fileToSave;
//        echo '</pre>';
//
//        return 'asd';


    }


    public function formB($id)
    {
        $guard = new Guards\Guards();
        $guard = $guard->guardDetail($id);

        $allExServices = new Guards\GuardExServices();
        $allExServices = $allExServices->getAllByDescendingId();


        $guardDesignationModel = new Guards\GuardDesignationModel();

        $data = array(
            'guard' => $guard,
            'guardDesignationModel' => $guardDesignationModel,
            'allExServices' => $allExServices,
        );

        $paper_size = array(0, 0, 1250, 1500);

        $pdf = PDF::loadView('pdf.form_B', $data)->setPaper($paper_size, 'portrait');
        return $pdf->stream();

//        $paper_size = array(0, 0, 1000, 1435);
//        $pdf = PDF::loadView('pdf.formB', array('guard' => $guard))->setPaper($paper_size, 'portrait');
//        return $pdf->stream();
    }

    public function downloadFormB($guardId)
    {


        $guard = new Guards\Guards();
        $guard = $guard->guardDetail($guardId);


//        $paper_size = array(0, 0, 1000, 1435);
//        $pdf = PDF::loadView('pdf.formB', array('guard' => $guard))->setPaper($paper_size, 'portrait');


        $allExServices = new Guards\GuardExServices();
        $allExServices = $allExServices->getAllByDescendingId();


        $guardDesignationModel = new Guards\GuardDesignationModel();

        $data = array(
            'guard' => $guard,
            'guardDesignationModel' => $guardDesignationModel,
            'allExServices' => $allExServices,
        );
        $paper_size = array(0, 0, 1250, 1500);
        $pdf = PDF::loadView('pdf.form_B', $data)->setPaper($paper_size, 'portrait');


        return $pdf->download($guardId . 'formB.pdf');


    }

    public function downloadFormBInZipDownload($guardId)
    {

        $directory = public_path() . '/tmp_dynamic_pdf_forms';
        if (!is_dir($directory)) {
            File::makeDirectory($directory);
        }
        $guard = new Guards\Guards();
        $guard = $guard->guardDetail($guardId);


        $allExServices = new Guards\GuardExServices();
        $allExServices = $allExServices->getAllByDescendingId();


        $guardDesignationModel = new Guards\GuardDesignationModel();

        $data = array(
            'guard' => $guard,
            'guardDesignationModel' => $guardDesignationModel,
            'allExServices' => $allExServices,
        );
        $paper_size = array(0, 0, 1250, 1500);
        $pdf = PDF::loadView('pdf.form_B', $data)->setPaper($paper_size, 'portrait');

        $output = $pdf->output();
        $fileToSave = file_put_contents($directory . '/' . $guardId . '_form_B.pdf', $output);


    }

    public function viewEmploymentCard($id)
    {
        $guard = new Guards\Guards();
        $guard = $guard->guardDetail($id);

        $designationModel = new Guards\GuardDesignationModel();
        $designationModel = $designationModel->getModelById($guard->designation);

        $guardDesignation = $designationModel->name;
        $bloodGroupModel = new Mix\BloodGroupsModel();
        $guardBloodGroup = 'Not Defined';
        if ($guard->blood_group_id) {
            $guardBloodGroup = $bloodGroupModel->getModelById($guard->blood_group_id)->name;
        }


        $paper_size = array(0, 0, 1000, 1500);
        $pdf = PDF::loadView('pdf.employmentForm', array('guard' => $guard, 'guardDesignation' => $guardDesignation, 'guardBloodGroup' => $guardBloodGroup))->setPaper($paper_size, 'portrait');
        return $pdf->stream();
    }

    public function downloadEmploymentCard($id)
    {


        $guard = new Guards\Guards();
        $guard = $guard->guardDetail($id);

        $designationModel = new Guards\GuardDesignationModel();
        $designationModel = $designationModel->getModelById($guard->designation);

        $guardDesignation = $designationModel->name;

        $bloodGroupModel = new Mix\BloodGroupsModel();
        $guardBloodGroup = 'Not Defined';
        if ($guard->blood_group_id) {
            $guardBloodGroup = $bloodGroupModel->getModelById($guard->blood_group_id)->name;
        }


        $paper_size = array(0, 0, 1000, 1500);
        $pdf = PDF::loadView('pdf.employmentForm', array('guard' => $guard, 'guardDesignation' => $guardDesignation, 'guardBloodGroup' => $guardBloodGroup))->setPaper($paper_size, 'portrait');

        return $pdf->download($id . 'employmentForm.pdf');


    }


    public function downloadEmploymentCardInZipDownload($guardId)
    {

        $directory = public_path() . '/tmp_dynamic_pdf_forms';
        if (!is_dir($directory)) {
            File::makeDirectory($directory);
        }

        $guard = new Guards\Guards();
        $guard = $guard->guardDetail($guardId);

        $designationModel = new Guards\GuardDesignationModel();
        $designationModel = $designationModel->getModelById($guard->designation);

        $guardDesignation = $designationModel->name;

        $bloodGroupModel = new Mix\BloodGroupsModel();
        $guardBloodGroup = 'Not Defined';
        if ($guard->blood_group_id) {
            $guardBloodGroup = $bloodGroupModel->getModelById($guard->blood_group_id)->name;
        }


        $paper_size = array(0, 0, 1000, 1500);
        $pdf = PDF::loadView('pdf.employmentForm', array('guard' => $guard, 'guardDesignation' => $guardDesignation, 'guardBloodGroup' => $guardBloodGroup))->setPaper($paper_size, 'portrait');


        $output = $pdf->output();
        $fileToSave = file_put_contents($directory . '/' . $guardId . '_employment_card.pdf', $output);


    }

    /**
     * Search guard.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */


    public function searchGuard(Request $request)
    {


        $allReligions = new Mix\ReligionModel();
        $allReligions = $allReligions->getAll();

        $allEducations = new Guards\GuardEducationsTypesModel();
        $allEducations = $allEducations->getAll();

        $allDesignations = new Guards\GuardDesignationModel();
        $allDesignations = $allDesignations->getAll();

        $all_statuses = new Guards\GuardStatusesModel();
        $all_statuses = $all_statuses->getAll();

        $allClients = new Clients();
        $allClients = $allClients->getAllClientsWithoutPaginate();

        $usereModel = new UserModel();
        $allSupervisors = $usereModel->getAllUsersByRoleId(3);

        $verificationTypeModel = new Guards\GuardVerificationTypesModel();
        $verificationStatusModel = new Guards\GuardVerificationStatusesModel();

        $allGuardVerificationTypes = $verificationTypeModel->getAll();
        $allGuardVerificationStatus = $verificationStatusModel->getAll();

        $guard = new Guards\Guards();
        $guard = $guard->guardListWithoutPaginate();

        $allDesignation = Guards\GuardDesignationModel::all();
        $allStatus = Guards\GuardStatusesModel::all();

        $exServicesModel = new Guards\GuardExServices();


        // $data = array(
        //     // 'allReligions' => $allReligions,
        //     // 'allEducations' => $allEducations,
        //     // 'allDesignations' => $allDesignations,
        //     // 'all_statuses' => $all_statuses,
        //     // 'allClients' => $allClients,
        //     // 'allSupervisors' => $allSupervisors,
        //     'allGuardVerificationTypes' => $allGuardVerificationTypes,
        //     'allGuardVerificationStatus' => $allGuardVerificationStatus,
        //     // 'guard' => $guard
        //     // 'exServicesModel' => $exServicesModel,
        //     // 'designations' => $allDesignation,
        //     // 'statuses' => $allStatus,

        //     // 'oldRequestString' => '',
        //     // 'withoutPagination' => '',

        //     // 'searchParameters' => [
        //     //     'isByDefault' => 1,
        //     //     'parwestId' => -1,
        //     //     'name' => -1,
        //     //     'cnic_no' => -1,
        //     //     'education' => -1,
        //     //     'relegion' => -1,
        //     //     'status' => -1,
        //     //     'client' => -1,
        //     //     'supervisor' => -1,
        //     //     'verification_type' => -1,
        //     //     'verification_status' => -1,
        //     //     'over_staying' => -1,
        //     //     'on_night_duty' => -1,
        //     //     'archieved_recored' => -1,
        //     // ],

        // );

        // JSON.stringify(obj);
        return json_encode($guard);
    }
    public function getSalaryDetails(Request $request)
    {


        $allReligions = new Mix\ReligionModel();
        $allReligions = $allReligions->getAll();

        $allEducations = new Guards\GuardEducationsTypesModel();
        $allEducations = $allEducations->getAll();

        $allDesignations = new Guards\GuardDesignationModel();
        $allDesignations = $allDesignations->getAll();

        $all_statuses = new Guards\GuardStatusesModel();
        $all_statuses = $all_statuses->getAll();

        $allClients = new Clients();
        $allClients = $allClients->getAllClientsWithoutPaginate();

        $usereModel = new UserModel();
        $allSupervisors = $usereModel->getAllUsersByRoleId(3);

        $verificationTypeModel = new Guards\GuardVerificationTypesModel();
        $verificationStatusModel = new Guards\GuardVerificationStatusesModel();

        $allGuardVerificationTypes = $verificationTypeModel->getAll();
        $allGuardVerificationStatus = $verificationStatusModel->getAll();

        $guard = new Guards\Guards();
        $guard = $guard->guardListWithoutPaginate();

        $allDesignation = Guards\GuardDesignationModel::all();
        $allStatus = Guards\GuardStatusesModel::all();

        $exServicesModel = new Guards\GuardExServices();


        $data = array(
            'allReligions' => $allReligions,
            'allEducations' => $allEducations,
            'allDesignations' => $allDesignations,
            'all_statuses' => $all_statuses,
            'allClients' => $allClients,
            'allSupervisors' => $allSupervisors,
            'allGuardVerificationTypes' => $allGuardVerificationTypes,
            'allGuardVerificationStatus' => $allGuardVerificationStatus,

            'guard' => $guard,
            'exServicesModel' => $exServicesModel,
            'designations' => $allDesignation,
            'statuses' => $allStatus,

            'oldRequestString' => '',
            'withoutPagination' => '',

            'searchParameters' => [
                'isByDefault' => 1,
                'parwestId' => -1,
                'name' => -1,
                'cnic_no' => -1,
                'education' => -1,
                'relegion' => -1,
                'status' => -1,
                'client' => -1,
                'supervisor' => -1,
                'verification_type' => -1,
                'verification_status' => -1,
                'over_staying' => -1,
                'on_night_duty' => -1,
                'archieved_recored' => -1,
            ],

        );

        return view('guards.salary')->with($data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */


    public function store(Request $request)
    {


        $this->validate($request, [
            'parwestID'=>'required',
            'name' => 'required',
            'father_name' => 'required',
            'mother_name' => 'required',
            'date_of_birth' => 'required',
//            'age' => 'required',
            'cnic_no' => 'required',

            'cnic_no_issue_date' => 'required',
            'cnic_no_expiry_date' => 'required',
            'next_of_ken' => 'required',
            'contact_no' => 'required',
            'sect' => 'required',
            'cast' => 'required',

            'current_address' => 'required',
            'current_address_contact_num' => 'required',
            'permanent_address' => 'required',
            'permanent_address_contact_no' => 'required',
            'police_station' => 'required',

            'introducer_name' => 'required',
//            'introducer_cnic_no' => 'required',
//            'introducer_address' => 'required',
//            'introducer_contact_no' => 'required',
            'height' => 'required',

            'weight' => 'required',
            'eye_color' => 'required',
            'disablity' => 'required',
            'hair_color' => 'required',
            'mark_of_identification' => 'required',
        ]);

//        for ($a = 0; $a < count($request->relative_detail['relative_name']); $a++) // Check if the relative details entered have CNIC's
//        {
//            if (trim($request->relative_detail['relative_name'][$a]) != '' && trim($request->relative_detail['relative_cnic_no'][$a]) == '')
//            {
//                $request->validate([
//                    $request->relative_detail['relative_cnic_no'][$a] =>'required'
//                ]);
//            }
//        }


        $blackListGuardModel = new Guards\BlackListedGuards();
        $guardCnic = $request->cnic_no;

        if ($blackListGuardModel->isCnicBlackListed($guardCnic)) {
            return redirect()->back()->with('guardAddFail', 'Cannot Add Guard Because Provided Cnic Number Is Black Listed');

        }

        $guard = new Guards\Guards();
        $guardId = $guard->saveBasicDetails($request);

        $guardFamilyDetailModel = new Guards\GuardFamilyModel();
        $saveGuardFamilyDetail = $guardFamilyDetailModel->saveGuardFamily($request, $guardId);

        $guardRelativeModel = new Guards\GuardNearestRelativeModel();
        $saveGuardRelativeDetail = $guardRelativeModel->saveRelative($request, $guardId);


//        $guardEmploymentHistory = new Guards\GuardEmploymentHistoryModel();
//        $guardEmploymentHistory->saveEmploymentHistory($request,$guardId);
//
//        $guardFamily = new Guards\GuardFamilyModel();
//        $guardFamily->saveGuardFamily($request,$guardId);
//
//        $guardRelative = new Guards\GuardNearestRelativeModel();
//        $guardRelative->saveRelative($request,$guardId);
//
//        $guardJudicialCase = new Guards\GuardJudicialCaseModel();
//        $guardJudicialCase->saveJudicialCase($request,$guardId);
//
//

//        if guard is added successfully
        if ($guardId) {
            $enterLeaves = new Guards\GuardRemainingLeavesModel();
            $enterLeaves = $enterLeaves->newGuardEntryLeaves($guardId);
            $allGuards = $guard->guardList();


            $guardSerialNumber = $guard->guardDetail($guardId)->parwest_id;

            $carbonObject = new Carbon();
            $currentDate = $carbonObject::now();
            $guardsDateOfBirthCarbon = $carbonObject::parse($request->date_of_birth);

            $guardsCurrentAge = $currentDate->diffInYears($guardsDateOfBirthCarbon);

            $minGuardAgeLimit = Config::get('globalvariables.$guardMinimumAge');
            $maxGuardAgeLimit = Config::get('globalvariables.$guardMaximumAge');

            if ($guardsCurrentAge < $minGuardAgeLimit || $guardsCurrentAge > $maxGuardAgeLimit) {
                return redirect('guard' . '?page=' . $allGuards->firstItem())
                    ->with('guardAddMessage', 'guard added successfully with serial number ' . $guardSerialNumber)
                    ->with('guardAgeNotification', 'Guard\'s age is not between ' . $minGuardAgeLimit . ' and ' . $maxGuardAgeLimit . ' years');

            } else {
                return redirect('guard' . '?page=' . $allGuards->firstItem())->with('guardAddMessage', 'guard added successfully with serial number ' . $guardSerialNumber);

            }


        } else {
            //if already a guard with same cnic and is not terminated
            return redirect('guard')->with('guardAddFail', 'cannot add guard because there is already a guard with same cnic number and is not terminated');
        }


    }
    public function test()
    {
        $date = '2019-07-18';
        $month_no =  getMonthByDate($date);
        $loan = new Guards\GuardLoansModel();
        $loan = $loan->getModelByMonth($month_no,'L-007');
        $clientGuardAcciciationModel = new ClientGuardsAssociation();
        $guardDeployments = $clientGuardAcciciationModel->deployedGuardByIdForAttendance('L-7184' , '2019-05-01', '2019-05-30');
        dd($guardDeployments);
    }
    public function printInventory($guard_id)
    {
        $guardModel = new \App\Models\Guards\Guards();
        $inventoryProductModel = new InventoryProductsModel();
        $inventoryProductNamesModelModel = new InventoryProductsNamesModel();
        $userModel = new UserModel();
        $guardName = $guardModel->guardDetail($guard_id) ? $guardModel->guardDetail($guard_id)->name : null;
        $guardParwestId = $guardModel->guardDetail($guard_id) ? $guardModel->guardDetail($guard_id)->parwest_id : null;
        $isGuardUnderSupervisor = $guardModel->getCurrentSupervisor($guard_id) ? $guardModel->getCurrentSupervisor($guard_id) : null;

        if ($isGuardUnderSupervisor) {
            $guardCurrentSupervisor = $isGuardUnderSupervisor->name;
            $managerSupervisorAssociationModel = new ManagerSupervisorAssociation();
            $guardCurrentManagerId = $managerSupervisorAssociationModel->managerOfSupervisor($isGuardUnderSupervisor->id);
        } else {
            $guardCurrentSupervisor = 'none';
            $guardCurrentManagerId = '';
        }
            $inventoryAssigned = new InventoryAssignHistoryModel();
            $inventoryAssigned = $inventoryAssigned->getLastAssignedToGuard($guard_id);
            $paper_size = array(0, 0, 1250, 1500);


            $dataArray = array(
                'guardName' => $guardName,
                'guardParwestId' => $guardParwestId,
                'guardCurrentSupervisor' => $guardCurrentSupervisor,
                'guardCurrentManagerId' => $guardCurrentManagerId,
                'inventoryAssigned' => $inventoryAssigned,
                'inventoryProductModel' => $inventoryProductModel,
                'inventoryProductNamesModelModel' => $inventoryProductNamesModelModel,
                'userModel' => $userModel,
            );
//            return view('pdf.guards_inventory')->with($dataArray);
            $pdf = PDF::loadView('pdf.guards_inventory', $dataArray)->setPaper($paper_size, 'portrait');
//            dd($pdf);
        return $pdf->download('inventory-'. $guardParwestId .'.pdf');

    }
    public function guardShow($guard_id)
    {

//        Profile Completion Code by Azam on 06/06/2018

        $guardPersonalInfoCount = Guards\Guards::where('id', $guard_id)->count();
        if ($guardPersonalInfoCount >= 1) {
            $guardPersonalInfoCount = 100;
        } else {
            $guardPersonalInfoCount = ($guardPersonalInfoCount / 1) * 100;
        }
        $guardNearestRelativesCount = Guards\GuardNearestRelativeModel::where('guard_id', $guard_id)->count();
        if ($guardNearestRelativesCount >= 3) {
            $guardNearestRelativesCount = 100;
        } else {
            $guardNearestRelativesCount = ($guardNearestRelativesCount / 3) * 100;
        }

        $guardFamilyCount = Guards\GuardFamilyModel::where('guard_id', $guard_id)->count();
//        print_r($guardFamilyCount);
        if ($guardFamilyCount >= 3) {
            $guardFamilyCount = 100;
        } else {
            $guardFamilyCount = ($guardFamilyCount / 3) * 100;
        }

        $guardEmploymentHistory = Guards\GuardEmploymentHistoryModel::where('guard_id', $guard_id)->count();
        if ($guardEmploymentHistory >= 1) {
            $guardEmploymentHistory = 100;
        } else {
            $guardEmploymentHistory = ($guardEmploymentHistory / 1) * 100;
        }

        $guardsAttachments = Guards\GuardsDocument::where('guard_id', $guard_id)->count();
        $guardsAttachments = $guardsAttachments + 3;
        if ($guardsAttachments >= 10) {
            $guardsAttachments = 100;
        } else {
            $guardsAttachments = ($guardsAttachments / 10) * 100;
        }


//        End of Profile Completion Code by Azam on 06/06/2018


        $guard = new Guards\Guards();
        $guard = $guard->guardDetail($guard_id);

        $ageLimit = new SettingsModel();
        $ageLimit = $ageLimit->pluck('guard_max_age');


        if( $guard->age > $ageLimit[0])
        {
            $guard->over_age = 1;
        }
        else
        {
            $guard->over_age = 0;
        }

        $attendanceModel = new Guards\GuardAttendance();
        $guardAttendanceFull = $attendanceModel->getAttendanceByGuardId($guard_id);

        $profileCompleteness = (($guardPersonalInfoCount + $guardFamilyCount + $guardNearestRelativesCount + $guardEmploymentHistory + $guardsAttachments) / 5);

//        $allActiveYears = $attendanceModel->getAllActiveYears($guard_id);

        $guardAttendanceYearModel = new Guards\GuardAttendanceYearsModel();
        $allActiveYears = $guardAttendanceYearModel->getAll();


        $documentTypes = new DocumentType();
        $documentTypes = $documentTypes->getAll();

        $allAssignedInventory = new InventoryAssignHistoryModel();
        $allAssignedInventory = $allAssignedInventory->getAllByGuardId($guard_id);


        $allPaidSalaries = new Guards\GuardsPaidSalaryModel();
        $allPaidSalaries = $allPaidSalaries->getAllByGuard($guard_id);

        $mhdate = new SettingsModel();
        $mhdate = $mhdate->first();
        $mhdate = $mhdate->mental_health_recheck_months;

        $current_date = Carbon::now();
        $last_mental_check_date = Carbon::parse($guard->mental_health_check);
        $days_since_last_mental_check = $last_mental_check_date->diffInMonths($current_date);


        $guardCreatedDate = Carbon::parse($guard->created_at);
        $guardTotalDays = $guardCreatedDate->diffInDays($current_date);


        $deploymentHistory = new ClientGuardsAssociation();
        $deploymentHistory = $deploymentHistory->getAllByGuardId($guard_id);

        $exServicesModel = new Guards\GuardExServices();

        $inventoryCategories = new InventoryCategoryModel();
        $inventoryCategories = $inventoryCategories->getAll();

        $allSupervisors = new UserModel();
        $allSupervisors = $allSupervisors->getAllSupervisors();

        $allClients = new Clients();
        $allClients = $allClients->getAllClientsWithoutPaginate();


        $allRefresherCourses = new Guards\RefresherCoursesModel();
        $allRefresherCourses = $allRefresherCourses->getByGuardId($guard_id);


        $specialBranchCheckHistory = new Guards\GuardSpecialBranchCheckHistoryModel();
        $specialBranchCheckHistory = $specialBranchCheckHistory->getAllByGuardId($guard_id);


        $customRolePermissionObject = new CustomRolePermissions();

        $guardStatusModel = new Guards\GuardStatusesModel();
        $allStatusesOfGuards = $guardStatusModel->getAll();

        $guardAllVerificationsType = new Guards\GuardVerificationTypesModel();
        $allGuardVerificationTypes = $guardAllVerificationsType->getAll();


        $guardAllVerificationsStatuses = new Guards\GuardVerificationStatusesModel();
        $allGuardVerificationStatuses = $guardAllVerificationsStatuses->getAll();

        $guardVerificationsModel = new Guards\GuardVerificationModel();
        $guardVerifications = $guardVerificationsModel->getAllByGuardId($guard_id);
//        dd($guardVerifications);


        $guardPledgedDocumentModel = new Guards\GuardPledgedDocument();
        $guardPledgedDocumentTypesModel = new GuardPledgableDocumentsTypeModel();

        $guardStatusByColModel = new Guards\GuardStatusByColModel();
        $allGuardStatusByCol = $guardStatusByColModel->getAll();


        $guardAsSystemUserModel = new Guards\GuardAsSystemUser();
        $guardCurrentSystemRole = $guardAsSystemUserModel->getActiveModelByGuardId($guard_id);
        $userModel = new UserModel();
        $guardRoles = new Guards\GuardRoles();
        $guardAsSystemUser = null;
        $guardAsSystemRole = null;


        if ($guardCurrentSystemRole) {
            $guardAsSystemUser = $userModel->getUserById($guardCurrentSystemRole->user_id);
            $guardAsSystemRole = $guardRoles->getModelById($guardAsSystemUser->role_id)->name;
        }

        $isgGuardAsSystemRoleDeactivated = false;
        $deactivatedRoleName = null;
        if ($guardAsSystemUserModel->getDeActiveModelByGuardId($guard_id)) {


            $deactivated = $guardAsSystemUserModel->getDeActiveModelByGuardId($guard_id);
            $deactivated = $userModel->getUserById($deactivated->user_id);
            $deactivatedRoleName = $guardRoles->getModelById($deactivated->role_id)->name;


            $isgGuardAsSystemRoleDeactivated = true;


        }


        $guardNonPledgedDocuments = $guardPledgedDocumentTypesModel->getNonPledgedDocumentOfGuard($guard_id);


        $allPlegedDocumentByGuard = $guardPledgedDocumentModel->getAllByGuardId($guard_id);

        $inventory_statuses = new InventoryProductsStatusModel();
        $inventory_statuses = $inventory_statuses->getAll();

        $data = array(
            'guardsAttachments' => $guardsAttachments,
            'profileCompleteness' => $profileCompleteness,
            'guardEmploymentHistory' => $guardEmploymentHistory,
            'guardFamilyCount' => $guardFamilyCount,
            'guardNearestRelativesCount' => $guardNearestRelativesCount,
            'guardPersonalInfoCount' => $guardPersonalInfoCount,
            'guard' => $guard,
            'guardAttendanceFull' => $guardAttendanceFull,
            'allActiveYears' => $allActiveYears,
            'attendanceModel' => $attendanceModel,
            'documentTypes' => $documentTypes,
            'allAssignedInventory' => $allAssignedInventory,
            'allPaidSalaries' => $allPaidSalaries,
            'months_since_last_mental_check' => $days_since_last_mental_check,
            'mentalhealthlimitinmonths' => $mhdate,
            'guardTotalDays' => $guardTotalDays,
            'deploymentHistory' => $deploymentHistory,
            'exServicesModel' => $exServicesModel,
            'inventoryCategories' => $inventoryCategories,
            'allSupervisors' => $allSupervisors,
            'allClients' => $allClients,
            'allRefresherCourses' => $allRefresherCourses,
            'customRolePermissionObject' => $customRolePermissionObject,
            'specialBranchCheckHistory' => $specialBranchCheckHistory,
            'allStatusesOfGuards' => $allStatusesOfGuards,
            'allGuardVerificationTypes' => $allGuardVerificationTypes,
            'allGuardVerificationStatuses' => $allGuardVerificationStatuses,
            'guardVerifications' => $guardVerifications,
            'allPlegedDocumentByGuard' => $allPlegedDocumentByGuard,
            'guardNonPledgedDocuments' => $guardNonPledgedDocuments,
            'guardAsSystemRole' => $guardAsSystemRole,
            'isgGuardAsSystemRoleDeactivated' => $isgGuardAsSystemRoleDeactivated,
            'deactivatedRoleName' => $deactivatedRoleName,
            'allGuardStatusByCol' => $allGuardStatusByCol,
            'inventory_statuses' =>$inventory_statuses

        );


        return view('guards.detail')->with($data);
    }


    public function updateForm($id)
    {


        $enrollForm = new Guards\GuardsPrerequisite();
        $prerequisite = $enrollForm->activePrerequisite();

        $allDesignations = new Guards\GuardDesignationModel();
        $allDesignations = $allDesignations->getAll();


        $allReligions = new Mix\ReligionModel();
        $allReligions = $allReligions->getAll();

        $allSects = new Mix\SectModel();
        $allSects = $allSects->getAll();

        $allEducations = new Guards\GuardEducationsTypesModel();
        $allEducations = $allEducations->getAll();

        $allRegionalOffices = new Guards\RegionalOfficeModel();
        $allRegionalOffices = $allRegionalOffices->getAll();

        $allExServices = new Guards\GuardExServices();
        $allExServices = $allExServices->getAllByDescendingId();

        $bloodGroupModel = new Mix\BloodGroupsModel();
        $allBloodGroups = $bloodGroupModel->getAll();

        $guard = new Guards\Guards();
        $guard = $guard->guardDetail($id);


        $data = array('prerequisite' => $prerequisite,
            'allDesignations' => $allDesignations,
            'allReligions' => $allReligions,
            'allSects' => $allSects,
            'allEducations' => $allEducations,
            'allRegionalOffices' => $allRegionalOffices,
            'allExServices' => $allExServices,
            'guard' => $guard,
            'allBloodGroups' => $allBloodGroups);


        return view('guards.update')->with($data);
    }


    public function associationStore(Request $request)
    {
        $model = new CentralModel(new Guards\GuardAssociation(), $request);
        return $model->save();
    }

    public function associationShow($id)
    {
        $model = new CentralModel(new Guards\GuardAssociation());
        return $model->get($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */

    public function editPreguardClearanceResultrequisiteForm($id)
    {
        $guard = new Guards\GuardsPrerequisite();
        $prerequisite = $guard->getEditablePrerquisite($id);
        return view('guards.editprerequisite')->with('prerequisite', $prerequisite);
    }

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
    public function update(Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'father_name' => 'required',
            'mother_name' => 'required',
            'date_of_birth' => 'required',
//            'age' => 'required',
            'cnic_no' => 'required',

            'cnic_no_issue_date' => 'required',
            'cnic_no_expiry_date' => 'required',
            'next_of_ken' => 'required',
            'contact_no' => 'required',
            'sect' => 'required',
            'cast' => 'required',

            'current_address' => 'required',
            'current_address_contact_num' => 'required',
            'permanent_address' => 'required',
            'permanent_address_contact_no' => 'required',
            'police_station' => 'required',
//
//            'introducer_name' => 'required',
//            'introducer_cnic_no' => 'required',
//            'introducer_address' => 'required',
//            'introducer_contact_no' => 'required',
            'height' => 'required',

            'weight' => 'required',
            'eye_color' => 'required',
            'disablity' => 'required',
            'hair_color' => 'required',
            'mark_of_identification' => 'required',
        ]);

        $guard = new Guards\Guards();
        $guardId = $guard->updateBasicDetails($request);


        $carbonObject = new Carbon();
        $currentDate = $carbonObject::now();
        $guardsDateOfBirthCarbon = $carbonObject::parse($request->date_of_birth);

        $guardsCurrentAge = $currentDate->diffInYears($guardsDateOfBirthCarbon);

        $minGuardAgeLimit = Config::get('globalvariables.$guardMinimumAge');
        $maxGuardAgeLimit = Config::get('globalvariables.$guardMaximumAge');

        if ($guardsCurrentAge < $minGuardAgeLimit || $guardsCurrentAge > $maxGuardAgeLimit) {
            return redirect('guard/show/' . $request->guard_id_on_update_form)
                ->with('message', 'Guard\'s Profile Updated Successfully')
                ->with('guardAgeNotification', 'Guard\'s age is not between ' . $minGuardAgeLimit . ' and ' . $maxGuardAgeLimit . ' years');
        } else {
            return redirect('guard/show/' . $request->guard_id_on_update_form)->with('message', 'Guard\'s Profile Updated Successfully');

        }


//        $guardEmploymentHistory = new Guards\GuardEmploymentHistoryModel();
//        $guardEmploymentHistory->updateEmploymentHistory($request);
//
//        $guardFamily = new Guards\GuardFamilyModel();
//        $guardFamily->updateGuardFamily($request);
//
//        $guardRelative = new Guards\GuardNearestRelativeModel();
//        $guardRelative->updateRelative($request);
//
//        $guardJudicialCase = new Guards\GuardJudicialCaseModel();
//        $guardJudicialCase->updateJudicialCase($request);


    }

    public function storeEmploymentHistory(Request $request)
    {
        $guardEmploymentHistory = new Guards\GuardEmploymentHistoryModel();
        $response = $guardEmploymentHistory->saveSingleEmploymentHistory($request);
        return $response;
    }

    public function storeRelative(Request $request)
    {


        $guardRelative = new Guards\GuardNearestRelativeModel();
        $response = $guardRelative->saveSingleRelative($request);
        return $response;
    }


    public function relativeDetailById(Request $request)
    {

        $relativeId = $request->relative_id;
        $guardRelative = new Guards\GuardNearestRelativeModel();
        $response = $guardRelative->getModelById($relativeId);
        return ['responseCode' => 1, 'responseStatus' => 'Successful', 'message' => 'successfully returned relative of guard', 'data' => $response];

    }

    public function storeFamily(Request $request)
    {
        $guardFamily = new Guards\GuardFamilyModel();
        $response = $guardFamily->saveSingleFamily($request);
        return $response;

    }

    public function storeJudicialCase(Request $request)
    {
        $guardJudicialCase = new Guards\GuardJudicialCaseModel();
        $response = $guardJudicialCase->saveSingleJudicialCase($request);
        return $response;
    }

    public function prerequisiteForm()
    {
        return view('guards.addnewprerequisite');
    }

    public function prerequisite()
    {


        $guard = new Guards\GuardsPrerequisite();
        $prerequisite = $guard->prerequisite();
        return view('guards.prerequisite')->with('prerequisite', $prerequisite);

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

    public function storeNewPrerequisite(Request $request)
    {


        $prerequisites = new Guards\GuardsPrerequisite();
        $response = $prerequisites->addPrerequisite($request);
        return redirect('guard/mergedOptions');
    }

    public function storeNewPrerequisiteByAjax(Request $request)
    {

        $prerequisites = new Guards\GuardsPrerequisite();
        $response = $prerequisites->addPrerequisiteUsingAjax($request);
        return $response;


    }

    public function updatePrerequisite(Request $request)
    {
        $prerequisites = new Guards\GuardsPrerequisite();
        $response = $prerequisites->updatePrerequisite($request);
        return $response;
    }

    public function deletePrerequisite(Request $request)
    {
        $prerequisites = new Guards\GuardsPrerequisite();
        $response = $prerequisites->deletePrerequisite($request);
        return $response;
    }

    public function searchCustom(Request $request)
    {


        // before new search logic

        $guard = new Guards\Guards();
        $withPagination = $guard->searchGuard($request, 0);

//        $withoutPagination = $guard->searchGuard($request, 0);

        $exServicesModel = new Guards\GuardExServices();

        $data = array(
            'guard' => $withPagination,
            'exServicesModel' => $exServicesModel,
            'oldRequestString' => base64_encode(serialize($request->all()))
//            'withoutPagination' => base64_encode(serialize($withoutPagination))
        );


        // after new search login


        $allReligions = new Mix\ReligionModel();
        $allReligions = $allReligions->getAll();

        $allEducations = new Guards\GuardEducationsTypesModel();
        $allEducations = $allEducations->getAll();

        $allDesignations = new Guards\GuardDesignationModel();
        $allDesignations = $allDesignations->getAll();

        $all_statuses = new Guards\GuardStatusesModel();
        $all_statuses = $all_statuses->getAll();

        $allClients = new Clients();
        $allClients = $allClients->getAllClientsWithoutPaginate();

        $usereModel = new UserModel();
        $allSupervisors = $usereModel->getAllUsersByRoleId(3);

        $verificationTypeModel = new Guards\GuardVerificationTypesModel();
        $verificationStatusModel = new Guards\GuardVerificationStatusesModel();

        $allGuardVerificationTypes = $verificationTypeModel->getAll();
        $allGuardVerificationStatus = $verificationStatusModel->getAll();

        $guard = new Guards\Guards();
        $guard = $guard->guardList();

        $allDesignation = Guards\GuardDesignationModel::all();
        $allStatus = Guards\GuardStatusesModel::all();

        $exServicesModel = new Guards\GuardExServices();





        $data = array(
            'allReligions' => $allReligions,
            'allEducations' => $allEducations,
            'allDesignations' => $allDesignations,
            'all_statuses' => $all_statuses,
            'allClients' => $allClients,
            'allSupervisors' => $allSupervisors,
            'allGuardVerificationTypes' => $allGuardVerificationTypes,
            'allGuardVerificationStatus' => $allGuardVerificationStatus,

            'guard' => $withPagination,
            'exServicesModel' => $exServicesModel,
            'designations' => $allDesignation,
            'statuses' => $allStatus,
            'oldRequestString' => base64_encode(serialize($request->all())),
//            'withoutPagination' => base64_encode(serialize($withoutPagination)),




            'searchParameters' => [
                'isByDefault' => 0,
                'parwestId' => $request->parwest_id,
                'name' => $request->name,
                'cnic_no' => $request->cnic,
                'education' => $request->education_id,
                'relegion' => $request->religion_id,
                'status' => $request->current_status_id,
                'client' => $request->client_id,
                'supervisor' => $request->supervisor_id,
                'verification_type' => $request->verification_type_id,
                'verification_status' => $request->verification_status_id,
                'over_staying' => $request->has('isOverstaying') ? 1: -1,
                'on_night_duty' => $request->has('isOnNightDuty') ? 1: -1,
                'archieved_recored' => $request->has('isArchived') ? 1: -1,
            ],


        );



        return view('guards.search')->with($data);


//        return view('guards.searchresult')->with($data);

    }

    public function document($id)
    {
        $guard = new Guards\Guards();
        $guard = $guard->guardDetail($id);
        return view('guards.documents')->with('guard', $guard);
    }

    public function addDocument($id)
    {
        $guard = new Guards\Guards();
        $guard = $guard->guardDetail($id);


        $document = new DocumentType();
        $documentTypes = $document->getAll();


        $data = array(
            'guard' => $guard,
            'documentTypes' => $documentTypes,

        );


        return view('guards.adddocument')->with($data);
    }

    public function uploadDocument(Request $request)
    {


        $guard_id = $request->input('guard_id') ? $request->input('guard_id') : null;
        $document_id = $request->input('document_id');

        $guardModel = new Guards\Guards();
        $guardModel = $guardModel->guardDetail($guard_id);
        $guardFolderName = $guardModel->parwest_id;

        $ds = DIRECTORY_SEPARATOR;  //1
        $storeFolder = 'guard_documents';

        $pathToSaveFile = public_path() . $ds . $storeFolder . $ds . $guardFolderName;
        if (!is_dir($pathToSaveFile)) {
            //Directory does not exist, so lets create it.
//            echo '<pre>';
//            echo $pathToSaveFile;
//            echo '</pre>';
            mkdir($pathToSaveFile);

        }


        if (!empty($_FILES)) {

            $guard_id = $request->input('guard_id') ? $request->input('guard_id') : null;
            $document_id = $request->input('document_id');


            $ds = DIRECTORY_SEPARATOR;  //1
            $storeFolder = 'guard_documents';


            if (!empty($_FILES)) {

                $randomString = md5(microtime());
                $fileName = $randomString . preg_replace('/\s+/', '', $_FILES['file']['name']);
                $tempFile = $_FILES['file']['tmp_name'];          //3


                $targetFile = $pathToSaveFile . $ds . $fileName;  //5

                move_uploaded_file($tempFile, $targetFile); //6


                $guardDocument = new Guards\GuardsDocument();
                $savedDocumentId = $guardDocument->saveDocument($guard_id, $guardFolderName, $document_id, $fileName);

                $documentType = new DocumentType();
                $documentType = $documentType->getDocumentById($document_id);


                $helpers = new Helpers();
                $helpers = $helpers->getAllRolePermissionFlags();


                $canViewDocument = $helpers->viewGuardDocument;
                $canDeleteDocumentDocument = $helpers->deleteGuardDocument;
                $canDownloadDocument = $helpers->downloadGuardDocument;

                return ['responseStatus' => 'Document Added Successfully ',
                    'savedDocumentId' => $savedDocumentId,
                    'documentTypeName' => $documentType->name,
                    'documentPathWithParentFolder' => $storeFolder . '/' . $guardFolderName . '/' . $fileName,
                    'documentName' => $guardFolderName . '/' . $fileName,
                    'canViewDocument' => $canViewDocument,
                    'canDeleteDocumentDocument' => $canDeleteDocumentDocument,
                    'canDownloadDocument' => $canDownloadDocument,
                ];
            } else {
                return ['responseStatus' => 'Failed'];
            }
        }


    }

    public function uploadMultipleDocumentsOfGuard(Request $request)
    {


        $guardId = $request->guard_id;
        $document_id_array = $request->document_id;
        $files_array = $request->file;


        $guardModel = new Guards\Guards();
        $guardModel = $guardModel->guardDetail($guardId);
        $guardFolderName = $guardModel->parwest_id;

        if (!empty($_FILES)) {
            $ds = DIRECTORY_SEPARATOR;  //1
            $storeFolder = 'guard_documents';

            $pathToSaveFile = public_path() . $ds . $storeFolder;
            if (!is_dir($pathToSaveFile)) {

                mkdir($pathToSaveFile);

            }

            $pathToSaveFile = public_path() . $ds . $storeFolder . $ds . $guardFolderName;

            if (!is_dir($pathToSaveFile)) {

                mkdir($pathToSaveFile);

            }

            $guardDocument = new Guards\GuardsDocument();


            foreach ($document_id_array as $key => $val) {

                $randomString = md5(microtime());
                $fileName = $randomString . preg_replace('/\s+/', '', $_FILES['file_' . $val]['name']);
                $tempFile = $_FILES['file_' . $val]['tmp_name'];          //3


                $targetFile = $pathToSaveFile . $ds . $fileName;  //5

                move_uploaded_file($tempFile, $targetFile); //6

                $savedDocumentId = $guardDocument->saveDocument($guardId, $guardFolderName, $val, $fileName);
            }
        } else {
            return 'no';


        }


        return 'asd';

    }


    public function deleteDocument(Request $request)
    {


        $document_id = $request->input('document_id') ? $request->input('document_id') : null;
        $guard_id = $request->input('guard_id') ? $request->input('guard_id') : null;
        $document = new Guards\GuardsDocument();


        $guardModel = new Guards\Guards();
        $guardModel = $guardModel->guardDetail($guard_id);
        $guardFolderName = $guardModel->parwest_id;


        $documentModel = $document->getDocumentById($document_id);
        unlink(public_path('guard_documents/' . $documentModel->path));

        $response = $document->deleteDocument($document_id);


        $deletedDocumentType = new DocumentType();
        $deletedDocumentType = $deletedDocumentType->getDocumentById($response);


        return ['responseStatus' => 'Document Deleted Successfully',
            'documentTypeId' => $deletedDocumentType->id,
            'documentTypeName' => $deletedDocumentType->name];


        return $response;
    }

    public function guardAttendanceByYear($year, $guardId)
    {

        $allAttendanceByYear = new Guards\GuardAttendance();
        $allAttendanceByYear = $allAttendanceByYear->getTotalAttendanceByYearOfGuard($year, $guardId);


        $available_attendance_date_status = [];


        //going to place only marked attendances either P,A,L or any other but marked of guard with key => date and value => status_id in
        foreach ($allAttendanceByYear as $key => $date_status) {

            $available_attendance_date_status[$date_status->date] = $date_status->status_id;

        }


        $current_date = new Carbon('first day of January' . $year);
        $end_date = new Carbon('last day of December' . $year);
        $allDatesWithAttendanceStatus = array();


        while ($current_date <= $end_date) {
            if (array_key_exists($current_date->format('Y-m-d'), $available_attendance_date_status)) {


                $allDatesWithAttendanceStatus = array_add($allDatesWithAttendanceStatus, $current_date->format('Y-m-d'), $available_attendance_date_status[$current_date->format('Y-m-d')]);
            } else {
                // 0 means no attendance marked against this date
                $allDatesWithAttendanceStatus = array_add($allDatesWithAttendanceStatus, $current_date->format('Y-m-d'), 0);

            }
            $current_date = $current_date->addDay();
        }


        $data = array('allDatesWithAttendanceStatus' => $allDatesWithAttendanceStatus, 'year' => $year);


        return view('guards.attendancebyyear')->with($data);
    }

    public function downloadDocument($folderName, $filename)
    {

        // Check if file exists in app/storage/file folder
        $file_path = public_path() . '/guard_documents/' . $folderName . '/' . $filename;
        if (file_exists($file_path)) {
            // Send Download
            return Response::download($file_path, $filename, [
                'Content-Length: ' . filesize($file_path)
            ]);
        } else {
            // Error
            exit('Requested file does not exist on our server!');
        }


    }

    public function downloadRefresherCourse($folderName, $subFolderName, $fileName)
    {
        // Check if file exists in app/storage/file folder
        $file_path = public_path() . '/guard_documents/' . $folderName . '/' . $subFolderName . '/' . $fileName;
        if (file_exists($file_path)) {
            // Send Download
            return Response::download($file_path, $fileName, [
                'Content-Length: ' . filesize($file_path)
            ]);
        } else {
            // Error
            exit('Requested file does not exist on our server!');
        }
    }


    public function downloadPledgedDocument($folderName, $subFolderName, $fileName)
    {
        // Check if file exists in app/storage/file folder
        $file_path = public_path() . '/guard_documents/' . $folderName . '/' . $subFolderName . '/' . $fileName;
        if (file_exists($file_path)) {
            // Send Download
            return Response::download($file_path, $fileName, [
                'Content-Length: ' . filesize($file_path)
            ]);
        } else {
            // Error
            exit('Requested file does not exist on our server!');
        }
    }

    public function downloadCreatedZipFile($guardId)
    {


        $file_path = public_path() . '/uploads/' . $guardId . '_guard_documents.zip';

        if (file_exists($file_path)) {
            // Send Download

            return Response::download($file_path, $guardId . '_guard_documents.zip', [
                'Content-Length: ' . filesize($file_path)
            ]);
        } else {
            // Error
            exit('Requested file does not exist on our server!');
        }
    }

    public function generateSalaryForm()
    {


        $guardsWithSalary = DB::table('guards')
            ->select('guards.*', 'guards_basic_salary.basic_salary')
            ->join('guards_basic_salary', 'guards.id', '=', 'guards_basic_salary.guard_id')
            ->get();
        $data = array('guardsWithSalary' => $guardsWithSalary);
        return view('guards.generateSalaryForm')->with($data);
    }

    public function generateSalary(Request $request)
    {
//        dd($request->all());

        $basicSalaryModel = new Guards\GuardsBasicSalaryModel();
        $basicSalaryModel = $basicSalaryModel->getByGuardId($request->guard_id);


        $isSalarayExist = new Guards\GuardsPaidSalaryModel();
        $isSalarayExist = $isSalarayExist->checkIfSalaryExist($request->guard_id, $request->month, $request->year);

        if ($isSalarayExist) {
            dd('cannot add salary, already exist');
        } else {
            $getGuardAttendance = new Guards\GuardAttendance();
            $getGuardAttendance = $getGuardAttendance->getGuardAttendanceByMonthYear($request->guard_id, $request->month, $request->year);

            $payableSalary = $getGuardAttendance * $basicSalaryModel->basic_salary;

            $savePaidSalary = new Guards\GuardsPaidSalaryModel();
            $savePaidSalary = $savePaidSalary->saveModel($request->guard_id, $request->month, $request->year, $payableSalary);

            dd($payableSalary);
            dd('added successfully');
        }
    }

    public function changePrerequisiteStatus($prerequisiteId)
    {
        $togglePrerequisiteStatus = new Guards\GuardsPrerequisite();
        $changedStatus = $togglePrerequisiteStatus->togglePrerequisiteStatusById($prerequisiteId);


        $prerequisite = $togglePrerequisiteStatus->prerequisite();
        return redirect(url('guard/mergedOptions'));
//        return redirect(url('guard/prerequisite'));

    }

    public function uploadProfilePicture(Request $request)
    {
//        $fileManager = new FileManager($request);
//        $fileManager->file_name = md5(microtime()) . $fileManager->file_extension;
//        $fileManager->save_to = 'guards_profile_picture';
//        $file = $fileManager->save();


        $fileName = md5(microtime());

        $image_parts = explode(";base64,", $request->file);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $file = public_path() . '/guards_profile_picture/' . $fileName . '.' . $image_type;

        //check if a folder exists or not. if not than create one
        if (!is_dir(public_path() . '/guards_profile_picture/')) {
            mkdir(public_path() . '/guards_profile_picture/', 0777, true);
        }

        file_put_contents($file, $image_base64);


        $fileFullNameWithExtension = $fileName . '.' . $image_type;


        $uploadGuardProfilePicture = new Guards\Guards();
        $uploadGuardProfilePicture = $uploadGuardProfilePicture->updateProfilePicturePath($request->guard_id, $fileFullNameWithExtension);


        return ['responseCode' => 1, 'responseStatus' => 'successful', 'message' => 'Profile Image Uploaded Successfully', 'filename' => $fileFullNameWithExtension];
    }

    public function statuses(Request $request)
    {

        $guardColorStatusModel = new Guards\GuardStatusColors();

        $allGuardStatusesColors = $guardColorStatusModel->getAll();
        $all_statuses = new Guards\GuardStatusesModel();
        $all_statuses = $all_statuses->getAllWithPaginate();

        $data = array('all_statuses' => $all_statuses, 'allGuardStatusesColors' => $allGuardStatusesColors);


        return view('guards/guardStatuses')->with($data);
    }

    public function addNewStatusOfGuard()
    {

        $guardColorStatusModel = new Guards\GuardStatusColors();

        $allGuardStatusesColors = $guardColorStatusModel->getAll();

        $data = array('allGuardStatusesColors' => $allGuardStatusesColors);

        return view('guards/addNewStatusOfGuard')->with($data);
    }

    public function storeNewStatusOfGuard(Request $request)
    {


        $saveStatus = new Guards\GuardStatusesModel();
        $response = $saveStatus->saveModel($request);

        if ($response) {
            return redirect('guard/mergedOptions')->with('status', 1)->with('message', 'successfully added new status');

        } else {
            return redirect('guard/mergedOptions')->with('status', 0)->with('message', 'failed to add new status, there is already a status with same name or value is empty');

        }


    }
    public function storeNewLoanOfGuard(Request $request)
    {
//dd($request);
        $name = Carbon::now();

        $file = $request->file('image_upload');
        if($file){
            $destinationPath = public_path('images');
//        dd($destinationPath);
//        $file->move($destinationPath, $name.'logo.png');

//        $destinationPath = 'uploads';
            // GET THE FILE EXTENSION
            $extension = $file->getClientOriginalExtension();
            // RENAME THE UPLOAD WITH RANDOM NUMBER
            $fileName = rand(11111, 99999) . '.' . $extension;
            // MOVE THE UPLOADED FILES TO THE DESTINATION DIRECTORY
            $upload_success = $file->move($destinationPath, $fileName);
//        $file = move_uploaded_file($_FILES['document']['name'],$destinationPath);
//        dd($upload_success);

        }

        $saveStatus = new Guards\GuardLoansModel();
        $response = $saveStatus->saveModel($request);
//        return $request->parwest_id;
        return $response;

//        if ($response) {
//            return redirect()->back()->with('success_central', 'Loan Added Successfully!');
////            return redirect('guard/accountLoan')->with('status', 1)->with('message', 'successfully added new status');
//
//        } else {
//            return redirect('guard/accountLoan')->with('status', 0)->with('message', 'failed to add new status, there is already a status with same name or value is empty');
//
//        }


    }
    public function ajaxstoreNewLoanOfGuard(Request $request)
    {
//dd($request);
        $name = Carbon::now();

        $file = $request->file('image_upload');
        if($file){
            $destinationPath = public_path('images');
//        dd($destinationPath);
//        $file->move($destinationPath, $name.'logo.png');

//        $destinationPath = 'uploads';
            // GET THE FILE EXTENSION
            $extension = $file->getClientOriginalExtension();
            // RENAME THE UPLOAD WITH RANDOM NUMBER
            $fileName = rand(11111, 99999) . '.' . $extension;
            // MOVE THE UPLOADED FILES TO THE DESTINATION DIRECTORY
            $upload_success = $file->move($destinationPath, $fileName);
//        $file = move_uploaded_file($_FILES['document']['name'],$destinationPath);
//        dd($upload_success);

        }

        $saveStatus = new Guards\GuardLoansModel();
        $response = $saveStatus->saveModel($request);

        if ($response) {
            return redirect()->back()->with('success_central', 'Loan Added Successfully!');
//            return redirect('guard/accountLoan')->with('status', 1)->with('message', 'successfully added new status');

        } else {
            return redirect('guard/accountLoan')->with('status', 0)->with('message', 'failed to add new status, there is already a status with same name or value is empty');

        }


    }
    public function updateLoanOfGuard(Request $request)
    {
//dd($request);
        $name = Carbon::now();

        $file = $request->file('image_upload');
        if($file){
            $destinationPath = public_path('images');
//        dd($destinationPath);
//        $file->move($destinationPath, $name.'logo.png');

//        $destinationPath = 'uploads';
            // GET THE FILE EXTENSION
            $extension = $file->getClientOriginalExtension();
            // RENAME THE UPLOAD WITH RANDOM NUMBER
            $fileName = rand(11111, 99999) . '.' . $extension;
            // MOVE THE UPLOADED FILES TO THE DESTINATION DIRECTORY
            $upload_success = $file->move($destinationPath, $fileName);
//        $file = move_uploaded_file($_FILES['document']['name'],$destinationPath);
//        dd($upload_success);

        }

        $saveStatus = new Guards\GuardLoansModel();
        $response = $saveStatus->updateModel($request);

        if ($response) {
            return redirect('guard/accountLoan')->with('status', 1)->with('message', 'successfully added new status');

        } else {
            return redirect('guard/accountLoan')->with('status', 0)->with('message', 'failed to add new status, there is already a status with same name or value is empty');

        }


    }

    public function editStatus($statusId)
    {
        $statusModel = new Guards\GuardStatusesModel();
        $statusModel = $statusModel->getModelById($statusId);
        $data = array('status' => $statusModel);
        return view('guards/editStatus')->with($data);
    }

    public function updateStatusValue(Request $request)
    {


        $updateModel = new Guards\GuardStatusesModel();
        $response = $updateModel->updateModel($request->id, $request->status);

        return $response;


    }

    public function deleteStatus(Request $request)
    {
        $deleteModel = new Guards\GuardStatusesModel();
        $response = $deleteModel->deleteModelById($request->id);
        return $response;
    }

    public function changeCurrentStatusForm($guardId)
    {

        $guardModel = new Guards\Guards();
        $guardModel = $guardModel->guardDetail($guardId);

        $guardCnic = $guardModel->cnic_no;

        $all_statuses = new Guards\GuardStatusesModel();
        $all_statuses = $all_statuses->getAll();
        $data = array(
            'all_statuses' => $all_statuses,
            'guardId' => $guardId,
            'guardCnic' => $guardCnic);
        return view('guards/editGuardCurrentStatus')->with($data);
    }

    public function updateStatus(Request $request)
    {


        $guardModel = new Guards\Guards();

        $isAllInventoryReturned = $guardModel->checkIfGuardIsFreeToTerminate($request->guard_id);


        if ($request->guard_current_status == 3) //going to terminate guard
        {
            if ($isAllInventoryReturned) {
                $response = $guardModel->updatedCurrentStatusByIdAndCnic($request->guard_id, $request->guard_cnic, $request->guard_current_status);
                if ($response == 0) {
                    dd('Failed to update guard status, There is already a guard with same cnic and is not terminated');
                } else {
                    $updateTerminationDate = $guardModel->updateGuardTerminationDate($request->guard_id);
                    return redirect('guard/show/' . $request->guard_id)->with('statusUpdate', 1)->with('statusUpdateMessage', 'guard terminated successfully');
                }


            } else {
                return redirect('guard/show/' . $request->guard_id)->with('statusUpdate', 0)->with('statusUpdateMessage', 'failed to terminate guard, because all inventory is not returned by guard or guard is currently deployed on some location');
            }
        } else {
            $response = $guardModel->updatedCurrentStatusByIdAndCnic($request->guard_id, $request->guard_cnic, $request->guard_current_status);
            if ($response == 0) {
                dd('Failed to update guard status, There is already a guard with same cnic and is not terminated');
            } else {
                return redirect('guard/show/' . $request->guard_id)->with('statusUpdate', 1)->with('statusUpdateMessage', 'guard\'s status updated successfully');
            }
        }


    }

    public function regionalOffices()
    {
        $all_offices = new Guards\RegionalOfficeModel();
        $all_offices = $all_offices->getAllByPaginate();
        $data = array('all_offices' => $all_offices);
        return view('guards.regionalOfficesList')->with($data);
    }

    public function addRegionalOfficeForm()
    {
        return view('guards.addNewRegionalOffice');
    }

    public function storeRegionalOffice(Request $request)
    {


//        dd($request->all());
        $regionalOfficeModel = new Guards\RegionalOfficeModel();
        $response = $regionalOfficeModel->store($request);

        $allRegionalOfficesByPaginate = $regionalOfficeModel->getAllByPaginate();
        if ($response) {

            $regionalOfficeContactNumberModel = new Guards\RegionalOfficesContactNumbersModel();
            $regionalOfficeExtensionNumberModel = new Guards\RegionalOfficesExtensionNumbersModel();


            $response1 = $regionalOfficeContactNumberModel->saveModel($request->all(), $response->id);
            $response2 = $regionalOfficeExtensionNumberModel->saveModel($request->all(), $response->id);


            return redirect('guard/regionalOffices?page=' . $allRegionalOfficesByPaginate->lastPage())->with('status', 1)->with('message', 'regional office added successfully');
        } else {
            return redirect('guard/regionalOffices?page=' . $allRegionalOfficesByPaginate->lastPage())->with('status', 0)->with('message', 'failed to add regional office, there exists a same regional office or short name already');

        }

    }

    public function addNewContactOfRegionalOffice(Request $request)
    {

        $regionalOfficeContactNumberModel = new Guards\RegionalOfficesContactNumbersModel();
        $response = $regionalOfficeContactNumberModel->saveModel($request->all(), $request->regional_office_id);
        return ['responseCode' => 1, 'responseStatus' => 'Successful', 'message' => 'Successfully Added New Contact Information'];
    }

    public function addNewExtensionOfRegionalOffice(Request $request)
    {

        $regionalOfficeExtensionNumberModel = new Guards\RegionalOfficesExtensionNumbersModel();
        $response = $regionalOfficeExtensionNumberModel->saveModel($request->all(), $request->regional_office_id);
        return ['responseCode' => 1, 'responseStatus' => 'Successful', 'message' => 'Successfully Added New Extension Number Information'];
    }

    public function showRegionalOffice($id)
    {
        $regionalOffice = new Guards\RegionalOfficeModel();
        $regionalOfficeContactNumberModel = new Guards\RegionalOfficesContactNumbersModel();
        $showRegionalOfficeExtensionNumberModel = new Guards\RegionalOfficesExtensionNumbersModel();
        $regionalOfficeInventoryDemandModel = new InventoryDemandsFromRegionalOfficesModel();
        $inventoryObject = new InventoryProductsModel();

        $regionalOffice = $regionalOffice->getModelById($id);
        $allContactNumber = $regionalOfficeContactNumberModel->getByRegionalOfficeId($id);
        $allExtensionNumber = $showRegionalOfficeExtensionNumberModel->getByRegionalOfficeId($id);
        $allDemandedInventory = $regionalOfficeInventoryDemandModel->getAllByRegionalOfficeId($id);
        $allInventory = $inventoryObject->getRegionalOfficeInventory($id);

        $data = array(
            'regionalOffice' => $regionalOffice,
            'allContactNumber' => $allContactNumber,
            'allExtensionNumber' => $allExtensionNumber,
            'allDemandedInventory' => $allDemandedInventory,
            'allInventory' => $allInventory,
        );

        return view('guards.regionalOfficeDetail')->with($data);
    }

    public function updatedRegionalOfficeForm($id)
    {
        $regionalOffice = new Guards\RegionalOfficeModel();
        $regionalOffice = $regionalOffice->getModelById($id);

        $data = array('regionalOffice' => $regionalOffice);

        return view('guards.updateRegionalOfficeForm')->with($data);
    }

    public function updateRegionalOffice(Request $request)
    {


        $updateRegionalOffice = new Guards\RegionalOfficeModel();
        $response = $updateRegionalOffice->updateModel($request);

        if ($response) {
            return redirect('guard/showRegionalOffice/' . $request->regional_office_id)->with('status', 1)->with('message', 'updated successfully');

        } else {
            return redirect('guard/showRegionalOffice/' . $request->regional_office_id)->with('status', 1)->with('message', 'updated successfully');
        }


    }

    public function deleteRegionalOffice($id)
    {
        $deleteModel = new Guards\RegionalOfficeModel();
        $response = $deleteModel->deleteModelById($id);

        return redirect('guard/regionalOffices');
    }

    public function updateMentalHealthCheckDate(Request $request)
    {
        $guardModel = new Guards\Guards();
        $response = $guardModel->updateMentalHealthCheckDateById($request);
        return $response;
    }

    public function updateAFieldOfGuard(Request $request)
    {

        $updateGuardModel = new Guards\Guards();
        $updateGuardModel = $updateGuardModel->updateAFieldOfGuard($request);

    }

    public function createZipOfGuardFilesForDownload(Request $request)
    {

        $guardId = $request->guard_id;
        $allFilePaths = [];
        $allFileNames = [];


        $public_dir = public_path() . '/uploads';
        $zipFileName = $guardId . '_guard_documents.zip';
        $zip = new ZipArchive;

        if (!is_dir($public_dir)) {
            File::makeDirectory($public_dir);
        } else {
//            File::cleanDirectory($public_dir);
        }


        // removing old files from zip, if zip already exists
        if ((File::exists($public_dir . '/' . $zipFileName))) {

            File::delete($public_dir . '/' . $zipFileName);

        }


        foreach ($request->fileNames as $key => $file) {

            if ($file == 'form_A') {
                $file_path = public_path() . '/tmp_dynamic_pdf_forms/' . $guardId . '_form_A.pdf';
                if (file_exists($file_path)) {
                    if ($zip->open($public_dir . '/' . $zipFileName, $zip::CREATE) === TRUE) {
                        $zip->addFile($file_path, 'form_A.pdf');
                    }
                }


            } else
                if ($file == 'form_B') {
                    $file_path = public_path() . '/tmp_dynamic_pdf_forms/' . $guardId . '_form_B.pdf';
                    if (file_exists($file_path)) {
                        if ($zip->open($public_dir . '/' . $zipFileName, $zip::CREATE) === TRUE) {
                            $zip->addFile($file_path, 'form_B.pdf');
                        }
                    }
                } else
                    if ($file == 'employee_card') {
                        $file_path = public_path() . '/tmp_dynamic_pdf_forms/' . $guardId . '_employment_card.pdf';
                        if (file_exists($file_path)) {
                            if ($zip->open($public_dir . '/' . $zipFileName, $zip::CREATE) === TRUE) {
                                $zip->addFile($file_path, 'employment_card.pdf');
                            }
                        }
                    } else {
                        $file_path = public_path() . '/guard_documents/' . $file;
                        if (file_exists($file_path)) {

                            $allFilePaths[] = $file_path; // fileFullPath
                            $allFileNames[] = $file; // filename
                        }
                    }
        }


        if ($zip->open($public_dir . '/' . $zipFileName, $zip::CREATE) === TRUE) {


            $documentModel = new Guards\GuardsDocument();

            foreach ($allFilePaths as $key => $path) {

                $extension = substr($allFileNames[$key], strrpos($allFileNames[$key], '.') + 1);

                $document = $documentModel->getDocumentByPath($allFileNames[$key]);

                $documentType = new DocumentType();
                $documentType = $documentType->getDocumentById($document->document_id);


                $zip->addFile($path, $documentType->name . '.' . $extension);
            }

            $zip->close();
            return ['responseCode' => 1, 'responseStatus' => 'successful', 'message' => 'successfully created zip file'];
        } else {
            return ['responseCode' => 0, 'responseStatus' => 'fail', 'message' => 'failed to created zip file'];
        }


//
//        $headers = array(
//            'Content-Type' => 'application/octet-stream',
//        );
//        $filetopath = $public_dir . '/' . $zipFileName;
//
//
//        if (file_exists($filetopath)) {
//            return response()->download($filetopath, $zipFileName, $headers);
//        }


//
//         Check if file exists in app/storage/file folder
//        $file_path = public_path() . '/guard_documents/' . $filename;
//        if (file_exists($file_path)) {
//            // Send Download
//            return Response::download($file_path, $filename, [
//                'Content-Length: ' . filesize($file_path)
//            ]);
//        } else {
//            // Error
//            exit('Requested file does not exist on our server!');
//        }


    }


    public function mergeGuardPdfFilesIntoSinglePdfToPrint(Request $request)
    {


        $pdf_merger = new \PDFMerger();


        $guardId = $request->guard_id;
        $allFilePaths = [];
        $allFileNames = [];


        $public_dir = public_path() . '/uploads';


        if (!is_dir($public_dir)) {
            File::makeDirectory($public_dir);
        } else {
//            File::cleanDirectory($public_dir);
        }


        foreach ($request->fileNames as $key => $file) {

            if ($file == 'form_A') {
                $file_path = public_path() . '/tmp_dynamic_pdf_forms/' . $guardId . '_form_A.pdf';
                if (file_exists($file_path)) {
                    $pdf_merger->addPDF($file_path, 'all');

                }


            } else
                if ($file == 'form_B') {
                    $file_path = public_path() . '/tmp_dynamic_pdf_forms/' . $guardId . '_form_B.pdf';
                    if (file_exists($file_path)) {
                        $pdf_merger->addPDF($file_path, 'all');

                    }
                } else
                    if ($file == 'employee_card') {
                        $file_path = public_path() . '/tmp_dynamic_pdf_forms/' . $guardId . '_employment_card.pdf';
                        if (file_exists($file_path)) {
                            $pdf_merger->addPDF($file_path, 'all');

                        }
                    } else {
                        $file_path = public_path() . '/guard_documents/' . $file;
                        if (file_exists($file_path)) {

                            $pdf_merger->addPDF($file_path, 'all');

                        }
                    }
        }


        $pdf_merger->merge('download', "mergedpdf.pdf");

    }

    public function updateSupervisorOfGuard(Request $request)
    {

//        $guardModel = new Guards\Guards();
//        $guardModel = $guardModel->updateSupervisorOfGuard($request->guard_id, $request->supervisor_id);


        $guardUnderSupervisorModel = new Guards\GuardsUnderSupervisorHistory();
        $revokeGuardFromOldSupervisor = $guardUnderSupervisorModel->revokeGuardFromSupervisor($request->guard_id);

        $assignGuardToNewSupervisor = $guardUnderSupervisorModel->saveModel($request->supervisor_id, $request->guard_id);


        $userModel = new UserModel();
        $userModel = $userModel->getUserById($request->supervisor_id);

        $data = array('supervisorName' => $userModel->name);


        return [
            'responseCode' => 1,
            'responseStatus' => 'successful',
            'message' => 'supervisor of guard updated successfully',
            'data' => $data,
        ];
    }

    public function deleteEmploymentHistoryById($guardId, $employmentHistoryId)
    {
        $model = new Guards\GuardEmploymentHistoryModel();

        $deleteModelById = $model->deleteModelById($employmentHistoryId);

        return Redirect::to(url('guard/show/' . $guardId));


    }

    public function deleteNearestRelativeById($guardId, $nearestRelativeId)
    {
        $modelRelative = new Guards\GuardNearestRelativeModel();

        $deleteModelById = $modelRelative->deleteRelativeModalById($nearestRelativeId);

        return Redirect::to(url('guard/show/' . $guardId));
    }

    public function deleteFamilyDetailById($guardId, $familyMemberId)
    {
        $familyDetailModel = new Guards\GuardFamilyModel();
        $deleteFamilyDetailById = $familyDetailModel->deleteFamilyDetailById($familyMemberId);

        return Redirect::to(url('guard/show/' . $guardId));

    }

    public function deleteJudicialCaseById($guardId, $JudicialCaseById)
    {
        $judicialCaseModel = new Guards\GuardJudicialCaseModel();

        $deleteJudicialCaseById = $judicialCaseModel->deleteJudicialCaseBId($JudicialCaseById);

        return Redirect::to(url('guard/show/' . $guardId));

    }


    public function addNewRefresherCourse(Request $request)
    {
        $guardId = $request->input('guard_id') ? $request->input('guard_id') : null;
        $courseName = $request->input('course_name') ? $request->input('course_name') : null;
        $courseLevel = $request->input('course_level') ? $request->input('course_level') : null;
        $courseInstructor = $request->input('course_instructor') ? $request->input('course_instructor') : null;
        $courseOfferedBy = $request->input('course_offered_by') ? $request->input('course_offered_by') : null;
        $courseIssueDate = $request->input('course_issue_date') ? $request->input('course_issue_date') : null;
        $courseDescription = $request->input('course_description') ? $request->input('course_description') : null;


        $guardModel = new Guards\Guards();
        $guardParwestId = $guardModel->guardDetail($guardId)->parwest_id;


        $ds = DIRECTORY_SEPARATOR;  //1

        $parentFolder = 'guard_documents' . $ds . $guardParwestId;
        $storeFolder = $parentFolder . $ds . 'refresher_courses';


//        $pathToSaveFile = public_path() . $ds . $storeFolder;
        $pathToSaveFile = public_path() . $ds . $storeFolder;

//        echo '<pre>';
//        echo $pathToSaveFile;
//        echo '</pre>';
        if (!is_dir($parentFolder)) {
            //Directory does not exist, so lets create it.

            mkdir($parentFolder);

        }

        if (!is_dir($pathToSaveFile)) {
            //Directory does not exist, so lets create it.

            mkdir($pathToSaveFile);

        }

        if (!empty($_FILES)) {

            $randomString = md5(microtime());
            $fileName = $randomString . preg_replace('/\s+/', '', $_FILES['file']['name']);
            $tempFile = $_FILES['file']['tmp_name'];          //3


            $targetFile = $pathToSaveFile . $ds . $fileName;  //5

            move_uploaded_file($tempFile, $targetFile); //6

            $fileNameToSaveInDataBase = $guardParwestId . '/refresher_courses/' . $fileName;


            $refresherCourseModel = new Guards\RefresherCoursesModel();
            $refresherCourseModel = $refresherCourseModel->saveModel($guardId, $courseName, $courseLevel, $courseInstructor, $courseOfferedBy, $courseIssueDate, $courseDescription, $fileNameToSaveInDataBase);


        } else {

        }

        return 'course added successfully';
    }

    public function accountBulkExportUnpaid(){
        return view('guards.accountBulkExportUnpaid');
    }
    public function makeExcelSheetOfEmployee(Request $request)
    {
        if($request->unpaid_month){
            $input = $request->all();
            $sal_month =  substr($input['unpaid_month'], 5);
            $sal_year =  substr($input['unpaid_month'], 0, -3);
        }else{
            $sal_month = null;
        }

        if($request->status){
            $status = $request->status;
        }else{
            $status = null;
        }
//            if($request->status){
        $dateObj   = DateTime::createFromFormat('!m', $sal_month);
        $monthName = $dateObj->format('F'); // March
        $guardModel = new Guards\GuardUnpaidSalariesModel();
        $searchResults = $guardModel->getAllBulk($sal_month,$status);

//                return [
//                    'responseCode' => 1,
//                    'responseStatus' => 'successful',
//                    'message' => 'your file is ready, and will download in few seconds',
//                    'data' => $searchResults
//                ];


                foreach ($searchResults as $guard){
                    $guard_model = new Guards\Guards();
                    $guard_model = $guard_model->getByParwestId($guard->parwest_id);
                    if($guard_model){
                        $guard->guard_name = $guard_model['name'];
                    }else{
                        $guard->guard_name =' Ashfaq ahmad';
                    }
                    if($guard->updated_by){
                        $user_model = new UserModel();
                        $user_model = $user_model->getUserById($guard->updated_by);
                        if($user_model){
                            $guard->updated_user = $user_model['name'];
                        }else{
                            $guard->updated_user = 'manager';
                        }
                    }else{
                        $guard->updated_user = '';
                    }

                }
//        $data = array('fileNameToDownload' => $searchResults);
//
//        return ['responseCode' => 1, 'responseStatus' => 'successful', 'message' => 'your file is ready, and will download in few seconds', 'data' => $data];

//        $first_day_this_month = date('Y-m-01'); // hard-coded '01' for first day
//        $last_day_this_month  = date('Y-m-t');
//        foreach ($searchResults as $employe){
////            $joining_date = date("y-m-d",$employee['updated_at']);
//            $joining_date = date('Y-m-d', strtotime($employe->updated_at));
////            dd($first_day_this_month);
//            if($joining_date > $first_day_this_month ){
//                $days = $this->getDaysBetweenDates($joining_date,$last_day_this_month) +1;
//            }else{
//
//                $days = $this->getDaysBetweenDates($first_day_this_month,$last_day_this_month)+1;
//            }
////            dd($days);
////            dd($days);
//            $amount = 3500/31*$days;
//            $employe->amount = (int)$amount;
//
//        }
//        return $searchResults[0]->kt_id;


                $fileName = 'Salary Status Report';


                Excel::create($fileName, function ($excel) use ($searchResults,$monthName,$sal_year, $guardModel) {


                    $excel->setTitle('Guard List Result');
                    $excel->setDescription('Following is result of Guard List');


                    $excel->sheet('Sheet1', function ($sheet) use ($searchResults,$monthName,$sal_year, $guardModel) {


                        $sheet->cells('A'.'5'.':F'.'5', function ($cells) {
                            $cells->setBackground('#E8E8E8');
                            $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                            $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);


                        });




                        $sheet->cells('A5:F5', function ($cells) {
                            $cells->setFont(array(
                                'bold' => true
                            ));

                            $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                            $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

                        });



                        $sheet->mergeCells('A1:F1');
                        $sheet->mergeCells('A2:F2');
                        $sheet->mergeCells('A3:F3');


                        $sheet->row(1, array(
                            'PARWEST PACIFIC SECURITY ( PVT ) LTD.'

                        ));
                        $sheet->row(2, array(
                            '176-CAVALARY GROUND, LAHORE CANTT.'

                        ));
                        $sheet->row(3, array(
                            'Salary Status Report for the month of    '.$monthName.",  ".$sal_year

                        ));
//                        $sheet->row(4, array(
//                            'Region :     '.$reg."  "
//
//                        ));

                        $sheet->row(5,
                            array(
                            'Parwest Id', 'Name', 'Status', 'Dated',
                            'Remarks', 'updated_by'

                             )
                        );

                        $count = 6;
                        foreach ($searchResults as $key => $array) {




                            $sheet->row($count, array(
                                ucfirst($array->parwest_id),
                                ucfirst($array->guard_name),
                                ucfirst($array->status),
                                ucfirst($array->unpaid_date),
                                ucfirst($array->remarks),
                                ucfirst($array->updated_user),



                            ));
                            $count += 1;
                        }
                    });


//            $excel->sheet('Sheet1', function ($sheet) use ($searchResults) {
//                // Sheet manipulation
//                $sheet->setOrientation('landscape');
//                $sheet->fromArray($searchResults, null, 'A1', true, true);
//            });

                })->store('xlsx', Config::get('globalvariables.$pathToSaveTemporaryExcelFiles'));

                $fileNameToDownload = $fileName . '.xlsx';

                $data = array('fileNameToDownload' => $fileNameToDownload);

                return ['responseCode' => 1, 'responseStatus' => 'successful', 'message' => 'your file is ready, and will download in few seconds', 'data' => $data];


//        }

    }
    public function makeExcelSheetOfGuardSearchResult(Request $request)
    {

        $guardModel = new Guards\Guards();
        $searchResults = (object)(unserialize(base64_decode($request->search_result_without_pagination)));
//        $searchResults = $searchResults->toArray()['data'];
//        dd($searchResults);


        $searchResults = json_decode(json_encode($searchResults), true);


        $fileName = md5(microtime());


        Excel::create($fileName, function ($excel) use ($searchResults, $guardModel) {

//            $excel->setTitle('Guard SearchResult');
//            $excel->setDescription('Following is result of Guard Search');
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

            $excel->setTitle('Guard List Result');
            $excel->setDescription('Following is result of Guard List');


            $excel->sheet('Sheet1', function ($sheet) use ($searchResults, $guardModel) {
                $religionModel = new Mix\ReligionModel();
                $bloodGroupModel = new Mix\BloodGroupsModel();
                $educationModel = new Guards\GuardEducationsTypesModel();
                $guardStatusModel = new Guards\GuardStatusesModel();
                $exServiceModel = new Guards\GuardExServices();
                $regionalOfficeModel = new Guards\RegionalOfficeModel();
                $userModel = new UserModel();
                $carbonObject = new Carbon();
                $guardDesignationModel = new Guards\GuardDesignationModel();
                $employmentHistoryModel = new Guards\GuardEmploymentHistoryModel();
                $nearestRelativeModel = new Guards\GuardNearestRelativeModel();
                $familyModel = new Guards\GuardFamilyModel();
                $judicialCasesModel = new Guards\GuardJudicialCaseModel();
                $guardSupervisorRelationshipModel = new Guards\GuardsUnderSupervisorHistory();
                $managerSupervisorAssociation = new ManagerSupervisorAssociation();


                $sheet->row(1, array(
                    'Parwest Id', 'Name', 'Father Name', 'Mother Name',
                    'Next Of Ken', 'Cnic No', 'Cnic Issue Date', 'Cnic Expiry Date',
                    'Passport No', 'Passport Expiry Date', 'Date Of Birth',
                    'Age', 'Contact_ No', 'Religion',
                    'Cast', 'Sect', 'Blood Group Id',
                    'Education', 'Education Passing Year', 'Education Istitution',
                    'Current Address', 'Current Address Contact No', 'Permanent Address',
                    'Permanent Address Contact No', 'Police Station', 'Salary',
                    'Introducer', 'Introducer Cnic No', 'Introducer Address',
                    'Introducer Contact No', 'Height', 'Weight',
                    'Eye Color', 'Hair Color', 'Mark Of Identification',
                    'Disablity', 'Designation', 'Current Status Id',
                    'Ex Service', 'Other', 'Army No',
                    'Army Rank', 'Army Group', 'Army Service Period',
                    'Army Service Years', 'Army Service Months', 'Army Joining Date',
                    'Army Leaving Date', 'Remarks', 'Regional Office Id',
                    'Termination Date', 'Enrolled By',

                    'Supervisor', 'Manger', 'Parwest Joining Date','Parwest Joining Time',

                    'Employment History Company 1', 'Service Duration', 'Enrollment Date', 'Discharge Date',
                    'Employment History Company 2', 'Service Duration', 'Enrollment Date', 'Discharge Date',
                    'Employment History Company 3', 'Service Duration', 'Enrollment Date', 'Discharge Date',


                    'Supervisor', 'Manger',

                    'Nearest Relative Name 1', 'Father Name', 'Relation', 'Contact #', 'CNIC #', 'Profession', 'Address',
                    'Nearest Relative Name 2', 'Father Name', 'Relation', 'Contact #', 'CNIC #', 'Profession', 'Address',
                    'Nearest Relative Name 3', 'Father Name', 'Relation', 'Contact #', 'CNIC #', 'Profession', 'Address',

                    'Family Member Name 1', 'Relation', 'Age', 'Profession ', 'Address',
                    'Family Member Name 2', 'Relation', 'Age', 'Profession ', 'Address',
                    'Family Member Name 3', 'Relation', 'Age', 'Profession ', 'Address',

                    'Judicial Case 1', 'Date', 'Police Station', 'Investigation Result ', 'Court Result',
                    'Judicial Case 2', 'Date', 'Police Station', 'Investigation Result ', 'Court Result',
                    'Judicial Case 3', 'Date', 'Police Station', 'Investigation Result ', 'Court Result',


                ));
                $count = 2;
                foreach ($searchResults as $key => $array) {


                    $employmentCompanyName1 = '';
                    $employmentCompanyDuration1 = '';
                    $employmentCompanyStartDate1 = '';
                    $employmentCompanyEndDate1 = '';

                    $employmentCompanyName2 = '';
                    $employmentCompanyDuration2 = '';
                    $employmentCompanyStartDate2 = '';
                    $employmentCompanyEndDate2 = '';

                    $employmentCompanyName3 = '';
                    $employmentCompanyDuration3 = '';
                    $employmentCompanyStartDate3 = '';
                    $employmentCompanyEndDate3 = '';

                    $guardEmploymentHistory = $employmentHistoryModel->getByGuardId($array['id']);
                    foreach ($guardEmploymentHistory as $key => $val) {
                        if ($key == 0) {
                            $employmentCompanyName1 = $val->name_of_company;
                            $employmentCompanyDuration1 = $val->duration;
                            if ($val->duration_type == 1) {
                                $employmentCompanyDuration1 = $employmentCompanyDuration1 . ' Days';
                            } elseif ($val->duration_type == 2) {
                                $employmentCompanyDuration1 = $employmentCompanyDuration1 . ' Months';
                            } elseif ($val->duration_type == 3) {
                                $employmentCompanyDuration1 = $employmentCompanyDuration1 . ' Years';
                            }
                            $employmentCompanyStartDate1 = $val->start_date;
                            $employmentCompanyEndDate1 = $val->end_date;
                        }
                        if ($key == 1) {
                            $employmentCompanyName2 = $val->name_of_company;
                            $employmentCompanyDuration2 = $val->duration;
                            if ($val->duration_type == 1) {
                                $employmentCompanyDuration2 = $employmentCompanyDuration2 . ' Days';
                            } elseif ($val->duration_type == 2) {
                                $employmentCompanyDuration2 = $employmentCompanyDuration2 . ' Months';
                            } elseif ($val->duration_type == 3) {
                                $employmentCompanyDuration2 = $employmentCompanyDuration2 . ' Years';
                            }
                            $employmentCompanyStartDate2 = $val->start_date;
                            $employmentCompanyEndDate2 = $val->end_date;
                        }
                        if ($key == 2) {
                            $employmentCompanyName3 = $val->name_of_company;
                            $employmentCompanyDuration3 = $val->duration;
                            if ($val->duration_type == 1) {
                                $employmentCompanyDuration3 = $employmentCompanyDuration3 . ' Days';
                            } elseif ($val->duration_type == 2) {
                                $employmentCompanyDuration3 = $employmentCompanyDuration3 . ' Months';
                            } elseif ($val->duration_type == 3) {
                                $employmentCompanyDuration3 = $employmentCompanyDuration3 . ' Years';
                            }
                            $employmentCompanyStartDate3 = $val->start_date;
                            $employmentCompanyEndDate3 = $val->end_date;
                        }

                    }


                    $nearestRelativeName1 = '';
                    $nearestRelativeFatherName1 = '';
                    $nearestRelativeRelation1 = '';
                    $nearestRelativeContactNo1 = '';
                    $nearestRelativeCnicNo1 = '';
                    $nearestRelativeProfession1 = '';
                    $nearestRelativeAddress1 = '';


                    $nearestRelativeName2 = '';
                    $nearestRelativeFatherName2 = '';
                    $nearestRelativeRelation2 = '';
                    $nearestRelativeContactNo2 = '';
                    $nearestRelativeCnicNo2 = '';
                    $nearestRelativeProfession2 = '';
                    $nearestRelativeAddress2 = '';

                    $nearestRelativeName3 = '';
                    $nearestRelativeFatherName3 = '';
                    $nearestRelativeRelation3 = '';
                    $nearestRelativeContactNo3 = '';
                    $nearestRelativeCnicNo3 = '';
                    $nearestRelativeProfession3 = '';
                    $nearestRelativeAddress3 = '';

                    $guardRelativeHistory = $nearestRelativeModel->getByGuardId($array['id']);
                    foreach ($guardRelativeHistory as $key => $val) {
                        if ($key == 0) {
                            $nearestRelativeName1 = $val->relative_name;
                            $nearestRelativeFatherName1 = $val->relative_father_name;
                            $nearestRelativeRelation1 = $val->relative_relation;
                            $nearestRelativeContactNo1 = $val->relative_cnic_no;
                            $nearestRelativeCnicNo1 = $val->relative_address;
                            $nearestRelativeProfession1 = $val->relative_profession;
                            $nearestRelativeAddress1 = $val->relative_contact_no;
                        }
                        if ($key == 1) {
                            $nearestRelativeName2 = $val->relative_name;
                            $nearestRelativeFatherName2 = $val->relative_father_name;
                            $nearestRelativeRelation2 = $val->relative_relation;
                            $nearestRelativeContactNo2 = $val->relative_cnic_no;
                            $nearestRelativeCnicNo2 = $val->relative_address;
                            $nearestRelativeProfession2 = $val->relative_profession;
                            $nearestRelativeAddress2 = $val->relative_contact_no;
                        }
                        if ($key == 2) {
                            $nearestRelativeName3 = $val->relative_name;
                            $nearestRelativeFatherName3 = $val->relative_father_name;
                            $nearestRelativeRelation3 = $val->relative_relation;
                            $nearestRelativeContactNo3 = $val->relative_cnic_no;
                            $nearestRelativeCnicNo3 = $val->relative_address;
                            $nearestRelativeProfession3 = $val->relative_profession;
                            $nearestRelativeAddress3 = $val->relative_contact_no;
                        }

                    }


                    $familyMemberName1 = '';
                    $familyMemberRelation1 = '';
                    $familyMemberAge1 = '';
                    $familyMemberProfession1 = '';
                    $familyMemberAddress1 = '';

                    $familyMemberName2 = '';
                    $familyMemberRelation2 = '';
                    $familyMemberAge2 = '';
                    $familyMemberProfession2 = '';
                    $familyMemberAddress2 = '';

                    $familyMemberName3 = '';
                    $familyMemberRelation3 = '';
                    $familyMemberAge3 = '';
                    $familyMemberProfession3 = '';
                    $familyMemberAddress3 = '';


                    $guardFamily = $familyModel->getByGuardId($array['id']);
                    foreach ($guardFamily as $key => $val) {
                        if ($key == 0) {
                            $familyMemberName1 = $val->family_member_name;
                            $familyMemberRelation1 = $val->family_member_relation;
                            $familyMemberAge1 = $val->family_member_age;
                            $familyMemberProfession1 = $val->family_member_profession;
                            $familyMemberAddress1 = $val->family_member_address;
                        }
                        if ($key == 1) {
                            $familyMemberName2 = $val->family_member_name;
                            $familyMemberRelation2 = $val->family_member_relation;
                            $familyMemberAge2 = $val->family_member_age;
                            $familyMemberProfession2 = $val->family_member_profession;
                            $familyMemberAddress2 = $val->family_member_address;
                        }
                        if ($key == 2) {
                            $familyMemberName3 = $val->family_member_name;
                            $familyMemberRelation3 = $val->family_member_relation;
                            $familyMemberAge3 = $val->family_member_age;
                            $familyMemberProfession3 = $val->family_member_profession;
                            $familyMemberAddress3 = $val->family_member_address;
                        }

                    }


                    $judicialCaseNumber1 = '';
                    $judicialCaseDate1 = '';
                    $judicialCasePoliceStation1 = '';
                    $judicialCaseInvestigationResult1 = '';
                    $judicialCaseCourtResult1 = '';

                    $judicialCaseNumber2 = '';
                    $judicialCaseDate2 = '';
                    $judicialCasePoliceStation2 = '';
                    $judicialCaseInvestigationResult2 = '';
                    $judicialCaseCourtResult2 = '';

                    $judicialCaseNumber3 = '';
                    $judicialCaseDate3 = '';
                    $judicialCasePoliceStation3 = '';
                    $judicialCaseInvestigationResult3 = '';
                    $judicialCaseCourtResult3 = '';


                    $judicialCases = $judicialCasesModel->getByGuardId($array['id']);
                    foreach ($judicialCases as $key => $val) {
                        if ($key == 0) {
                            $judicialCaseNumber1 = $val->judicial_case_no;
                            $judicialCaseDate1 = $val->judicial_case_date;
                            $judicialCasePoliceStation1 = $val->judicial_case_police_station;
                            $judicialCaseInvestigationResult1 = $val->judicial_case_investigatoin_result;
                            $judicialCaseCourtResult1 = $val->judicial_case_court_result;
                        }
                        if ($key == 1) {
                            $judicialCaseNumber2 = $val->judicial_case_no;
                            $judicialCaseDate2 = $val->judicial_case_date;
                            $judicialCasePoliceStation2 = $val->judicial_case_police_station;
                            $judicialCaseInvestigationResult2 = $val->judicial_case_investigatoin_result;
                            $judicialCaseCourtResult2 = $val->judicial_case_court_result;
                        }
                        if ($key == 2) {
                            $judicialCaseNumber3 = $val->judicial_case_no;
                            $judicialCaseDate3 = $val->judicial_case_date;
                            $judicialCasePoliceStation3 = $val->judicial_case_police_station;
                            $judicialCaseInvestigationResult3 = $val->judicial_case_investigatoin_result;
                            $judicialCaseCourtResult3 = $val->judicial_case_court_result;
                        }

                    }


//                    echo "<pre>";
//                    print_r(count($guardEmploymentHistory));
//                    echo "</pre>";
//                    die("dead");


                    $religion = $array['religion'];
                    $bloodGroup = $array['blood_group_id'];
                    $education = $array['education'];
                    $currentStatus = $array['current_status_id'];
                    $exService = $array['ex'];
                    $regionalOffice = $array['regional_office_id'];
                    $enrolledBy = $array['enrolled_by'];


                    $supervisor = '';
                    $manager = '';
                    $managerOfSupervisor = '';


                    $guardSupervisor = $guardModel->getCurrentSupervisor($array['id']);

                    if ($guardSupervisor) {
                        $supervisor = $guardSupervisor->name;

                        if ($supervisor) {

                            $managerOfSupervisor = $managerSupervisorAssociation->managerOfSupervisor($guardSupervisor->id);

                            if ($managerOfSupervisor) {
                                $managerId = $managerOfSupervisor->manager_id;
                                $manager = $userModel->getUserById($managerId)->name;
                            }
                        }
                    }


//                    echo "<pre>";
//                    print_r($bloodGroupModel);
//                    echo "</pre>";
//                    die("dead") ;

                    if ($religion > 0) {
                        $religion = $religionModel->getModelById($religion)->name;
                    }
                    if ($bloodGroup > 0) {
                        $bloodGroup = $bloodGroupModel->getModelById($bloodGroup)->name;
                    }
                    if ($education > 0) {
                        $education = $educationModel->getModelById($education)->name;
                    } else {
                        $education = '';
                    }
                    if ($currentStatus > 0) {
                        $currentStatus = $guardStatusModel->getModelById($currentStatus)->value;
                    }
                    if ($exService > 0) {
                        $exService = $exServiceModel->getModelById($exService)->name;
                    }
                    if ($regionalOffice > 0) {
                        $regionalOffice = $regionalOfficeModel->getModelById($regionalOffice)->office_head;
                    }
                    if ($enrolledBy > 0) {
                        $enrolledBy = $userModel->getUserById($enrolledBy)->name;
                    }
                    if ($array['cnic_issue_date']) {
                        $array['cnic_issue_date'] = $carbonObject->parse($array['cnic_issue_date'])->format('d  M  Y');

                    }
                    if ($array['cnic_expiry_date']) {
                        $array['cnic_expiry_date'] = $carbonObject->parse($array['cnic_expiry_date'])->format('d  M  Y');

                    }
                    if ($array['date_of_birth']) {
                        $array['date_of_birth'] = $carbonObject->parse($array['date_of_birth'])->format('d  M  Y');
                    }
                    if ($array['passport_expiry_date']) {


                        if ($array['passport_expiry_date'] == '0000-00-00') {
                            $array['passport_expiry_date'] = '';
                        } else {
                            $array['passport_expiry_date'] = $carbonObject->parse($array['passport_expiry_date'])->format('d  M  Y');
                        }


                    }
                    if ($array['army_joining_date']) {
                        $array['army_joining_date'] = $carbonObject->parse($array['army_joining_date'])->format('d  M  Y');
                    }
                    if ($array['army_leaving_date']) {
                        $array['army_leaving_date'] = $carbonObject->parse($array['army_leaving_date'])->format('d  M  Y');
                    }
                    if ($array['termination_date']) {
                        $array['termination_date'] = $carbonObject->parse($array['termination_date'])->format('d  M  Y');
                    }

                    $array['designation'] = $guardDesignationModel->getModelById($array['designation'])->name;
//                    echo "<pre>";
//                    print_r($enrolledBy);
//                    echo "</pre>";
//                    die("dead") ;

                    $sheet->row($count, array(
                        ucfirst($array['parwest_id']), ucfirst($array['name']), ucfirst($array['father_name']), ucfirst($array['mother_name']),
                        ucfirst($array['next_of_ken']), ucfirst($array['cnic_no']), ucfirst($array['cnic_issue_date']), ucfirst($array['cnic_expiry_date']),
                        ucfirst($array['passport_no']), ucfirst($array['passport_expiry_date']), ucfirst($array['date_of_birth']),
                        ucfirst($array['age']), ucfirst($array['contact_no']), ucfirst($religion),
                        ucfirst($array['cast']), ucfirst($array['sect']), ucfirst($bloodGroup),
                        ucfirst($education), ucfirst($array['education_passing_year']), ucfirst($array['education_istitution']),
                        ucfirst($array['current_address']), ucfirst($array['current_address_contact_no']), ucfirst($array['permanent_address']),
                        ucfirst($array['permanent_address_contact_no']), ucfirst($array['police_station']), ucfirst($array['salary']),
                        ucfirst($array['introducer']), ucfirst($array['introducer_cnic_no']), ucfirst($array['introducer_address']),
                        ucfirst($array['introducer_contact_no']), ucfirst($array['height']), ucfirst($array['weight']),
                        ucfirst($array['eye_color']), ucfirst($array['hair_color']), ucfirst($array['mark_of_identification']),
                        ucfirst($array['disablity']), ucfirst($array['designation']), ucfirst($currentStatus),
                        ucfirst($exService), ucfirst($array['other']), ucfirst($array['army_no']),
                        ucfirst($array['army_rank']), ucfirst($array['army_group']), ucfirst($array['army_service_period']),
                        ucfirst($array['army_service_years']), ucfirst($array['army_service_months']), ucfirst($array['army_joining_date']),
                        ucfirst($array['army_leaving_date']), ucfirst($array['remarks']), ucfirst($regionalOffice),
                        ucfirst($array['termination_date']), ucfirst($enrolledBy),

                        ucfirst($supervisor), ucfirst($manager),
                        $carbonObject->parse($array['created_at'])->format('d  M  Y'),
                        $carbonObject->parse($array['created_at'])->format('H:i'),


                        ucfirst($employmentCompanyName1), ucfirst($employmentCompanyDuration1), ucfirst($employmentCompanyStartDate1), ucfirst($employmentCompanyEndDate1),
                        ucfirst($employmentCompanyName2), ucfirst($employmentCompanyDuration2), ucfirst($employmentCompanyStartDate2), ucfirst($employmentCompanyEndDate2),
                        ucfirst($employmentCompanyName3), ucfirst($employmentCompanyDuration3), ucfirst($employmentCompanyStartDate3), ucfirst($employmentCompanyEndDate3),


                        ucfirst($nearestRelativeName1), ucfirst($nearestRelativeFatherName1), ucfirst($nearestRelativeRelation1), ucfirst($nearestRelativeContactNo1),
                        ucfirst($nearestRelativeCnicNo1), ucfirst($nearestRelativeProfession1), ucfirst($nearestRelativeAddress1),
                        ucfirst($nearestRelativeName2), ucfirst($nearestRelativeFatherName2), ucfirst($nearestRelativeRelation2), ucfirst($nearestRelativeContactNo2),
                        ucfirst($nearestRelativeCnicNo2), ucfirst($nearestRelativeProfession2), ucfirst($nearestRelativeAddress2),
                        ucfirst($nearestRelativeName3), ucfirst($nearestRelativeFatherName3), ucfirst($nearestRelativeRelation3), ucfirst($nearestRelativeContactNo3),
                        ucfirst($nearestRelativeCnicNo3), ucfirst($nearestRelativeProfession3), ucfirst($nearestRelativeAddress3),


                        ucfirst($familyMemberName1), ucfirst($familyMemberRelation1), ucfirst($familyMemberAge1),
                        ucfirst($familyMemberProfession1), ucfirst($familyMemberAddress1),
                        ucfirst($familyMemberName2), ucfirst($familyMemberRelation2), ucfirst($familyMemberAge2),
                        ucfirst($familyMemberProfession2), ucfirst($familyMemberAddress2),
                        ucfirst($familyMemberName3), ucfirst($familyMemberRelation3), ucfirst($familyMemberAge3),
                        ucfirst($familyMemberProfession3), ucfirst($familyMemberAddress3),


                        ucfirst($judicialCaseNumber1), ucfirst($judicialCaseDate1), ucfirst($judicialCasePoliceStation1),
                        ucfirst($judicialCaseInvestigationResult1), ucfirst($judicialCaseCourtResult1),
                        ucfirst($judicialCaseNumber2), ucfirst($judicialCaseDate2), ucfirst($judicialCasePoliceStation2),
                        ucfirst($judicialCaseInvestigationResult2), ucfirst($judicialCaseCourtResult2),
                        ucfirst($judicialCaseNumber3), ucfirst($judicialCaseDate3), ucfirst($judicialCasePoliceStation3),
                        ucfirst($judicialCaseInvestigationResult3), ucfirst($judicialCaseCourtResult3),


                    ));
                    $count += 1;
                }
            });

        })->store('xlsx', Config::get('globalvariables.$pathToSaveTemporaryExcelFiles'));

        $fileNameToDownload = $fileName . '.xlsx';

        $data = array('fileNameToDownload' => $fileNameToDownload);

        return ['responseCode' => 1, 'responseStatus' => 'successful', 'message' => 'your file is ready, and will download in few seconds', 'data' => $data];

    }
    public function guardSearchExport(Request $request)
    {

        $guardModel = new Guards\Guards();
//        $searchResults = (object)(unserialize(base64_decode($request->search_result_without_pagination)));
//        $searchResults = $searchResults->toArray()['data'];
//        dd($searchResults);

        $guard = new Guards\Guards();
        $withPagination = $guard->searchGuardExp($request, 0);
//        return $withPagination;




//        $searchResults = json_decode(json_encode($withPagination), true);
        $searchResults = $withPagination;


        $fileName = md5(microtime());


        Excel::create($fileName, function ($excel) use ($searchResults, $guardModel) {

//            $excel->setTitle('Guard SearchResult');
//            $excel->setDescription('Following is result of Guard Search');
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

            $excel->setTitle('Guard List Result');
            $excel->setDescription('Following is result of Guard List');


            $excel->sheet('Sheet1', function ($sheet) use ($searchResults, $guardModel) {
                $religionModel = new Mix\ReligionModel();
                $bloodGroupModel = new Mix\BloodGroupsModel();
                $educationModel = new Guards\GuardEducationsTypesModel();
                $guardStatusModel = new Guards\GuardStatusesModel();
                $exServiceModel = new Guards\GuardExServices();
                $regionalOfficeModel = new Guards\RegionalOfficeModel();
                $userModel = new UserModel();
                $carbonObject = new Carbon();
                $guardDesignationModel = new Guards\GuardDesignationModel();
                $employmentHistoryModel = new Guards\GuardEmploymentHistoryModel();
                $nearestRelativeModel = new Guards\GuardNearestRelativeModel();
                $familyModel = new Guards\GuardFamilyModel();
                $judicialCasesModel = new Guards\GuardJudicialCaseModel();
                $guardSupervisorRelationshipModel = new Guards\GuardsUnderSupervisorHistory();
                $managerSupervisorAssociation = new ManagerSupervisorAssociation();


                $sheet->row(1, array(
                    'Parwest Id', 'Name', 'Father Name', 'Mother Name',
                    'Next Of Ken', 'Cnic No', 'Cnic Issue Date', 'Cnic Expiry Date',
                    'Passport No', 'Passport Expiry Date', 'Date Of Birth',
                    'Age', 'Contact_ No', 'Religion',
                    'Cast', 'Sect', 'Blood Group Id',
                    'Education', 'Education Passing Year', 'Education Istitution',
                    'Current Address', 'Current Address Contact No', 'Permanent Address',
                    'Permanent Address Contact No', 'Police Station', 'Salary',
                    'Introducer', 'Introducer Cnic No', 'Introducer Address',
                    'Introducer Contact No', 'Height', 'Weight',
                    'Eye Color', 'Hair Color', 'Mark Of Identification',
                    'Disablity', 'Designation', 'Current Status Id',
                    'Ex Service', 'Other', 'Army No',
                    'Army Rank', 'Army Group', 'Army Service Period',
                    'Army Service Years', 'Army Service Months', 'Army Joining Date',
                    'Army Leaving Date', 'Remarks', 'Regional Office Id',
                    'Termination Date', 'Enrolled By',

                    'Supervisor', 'Manger', 'Parwest Joining Date','Parwest Joining Time',

                    'Employment History Company 1', 'Service Duration', 'Enrollment Date', 'Discharge Date',
                    'Employment History Company 2', 'Service Duration', 'Enrollment Date', 'Discharge Date',
                    'Employment History Company 3', 'Service Duration', 'Enrollment Date', 'Discharge Date',


                    'Supervisor', 'Manger',

                    'Nearest Relative Name 1', 'Father Name', 'Relation', 'Contact #', 'CNIC #', 'Profession', 'Address',
                    'Nearest Relative Name 2', 'Father Name', 'Relation', 'Contact #', 'CNIC #', 'Profession', 'Address',
                    'Nearest Relative Name 3', 'Father Name', 'Relation', 'Contact #', 'CNIC #', 'Profession', 'Address',

                    'Family Member Name 1', 'Relation', 'Age', 'Profession ', 'Address',
                    'Family Member Name 2', 'Relation', 'Age', 'Profession ', 'Address',
                    'Family Member Name 3', 'Relation', 'Age', 'Profession ', 'Address',

                    'Judicial Case 1', 'Date', 'Police Station', 'Investigation Result ', 'Court Result',
                    'Judicial Case 2', 'Date', 'Police Station', 'Investigation Result ', 'Court Result',
                    'Judicial Case 3', 'Date', 'Police Station', 'Investigation Result ', 'Court Result',


                ));
                $count = 2;
                foreach ($searchResults as $key => $array) {


                    $employmentCompanyName1 = '';
                    $employmentCompanyDuration1 = '';
                    $employmentCompanyStartDate1 = '';
                    $employmentCompanyEndDate1 = '';

                    $employmentCompanyName2 = '';
                    $employmentCompanyDuration2 = '';
                    $employmentCompanyStartDate2 = '';
                    $employmentCompanyEndDate2 = '';

                    $employmentCompanyName3 = '';
                    $employmentCompanyDuration3 = '';
                    $employmentCompanyStartDate3 = '';
                    $employmentCompanyEndDate3 = '';

                    $guardEmploymentHistory = $employmentHistoryModel->getByGuardId($array['id']);
                    foreach ($guardEmploymentHistory as $key => $val) {
                        if ($key == 0) {
                            $employmentCompanyName1 = $val->name_of_company;
                            $employmentCompanyDuration1 = $val->duration;
                            if ($val->duration_type == 1) {
                                $employmentCompanyDuration1 = $employmentCompanyDuration1 . ' Days';
                            } elseif ($val->duration_type == 2) {
                                $employmentCompanyDuration1 = $employmentCompanyDuration1 . ' Months';
                            } elseif ($val->duration_type == 3) {
                                $employmentCompanyDuration1 = $employmentCompanyDuration1 . ' Years';
                            }
                            $employmentCompanyStartDate1 = $val->start_date;
                            $employmentCompanyEndDate1 = $val->end_date;
                        }
                        if ($key == 1) {
                            $employmentCompanyName2 = $val->name_of_company;
                            $employmentCompanyDuration2 = $val->duration;
                            if ($val->duration_type == 1) {
                                $employmentCompanyDuration2 = $employmentCompanyDuration2 . ' Days';
                            } elseif ($val->duration_type == 2) {
                                $employmentCompanyDuration2 = $employmentCompanyDuration2 . ' Months';
                            } elseif ($val->duration_type == 3) {
                                $employmentCompanyDuration2 = $employmentCompanyDuration2 . ' Years';
                            }
                            $employmentCompanyStartDate2 = $val->start_date;
                            $employmentCompanyEndDate2 = $val->end_date;
                        }
                        if ($key == 2) {
                            $employmentCompanyName3 = $val->name_of_company;
                            $employmentCompanyDuration3 = $val->duration;
                            if ($val->duration_type == 1) {
                                $employmentCompanyDuration3 = $employmentCompanyDuration3 . ' Days';
                            } elseif ($val->duration_type == 2) {
                                $employmentCompanyDuration3 = $employmentCompanyDuration3 . ' Months';
                            } elseif ($val->duration_type == 3) {
                                $employmentCompanyDuration3 = $employmentCompanyDuration3 . ' Years';
                            }
                            $employmentCompanyStartDate3 = $val->start_date;
                            $employmentCompanyEndDate3 = $val->end_date;
                        }

                    }


                    $nearestRelativeName1 = '';
                    $nearestRelativeFatherName1 = '';
                    $nearestRelativeRelation1 = '';
                    $nearestRelativeContactNo1 = '';
                    $nearestRelativeCnicNo1 = '';
                    $nearestRelativeProfession1 = '';
                    $nearestRelativeAddress1 = '';


                    $nearestRelativeName2 = '';
                    $nearestRelativeFatherName2 = '';
                    $nearestRelativeRelation2 = '';
                    $nearestRelativeContactNo2 = '';
                    $nearestRelativeCnicNo2 = '';
                    $nearestRelativeProfession2 = '';
                    $nearestRelativeAddress2 = '';

                    $nearestRelativeName3 = '';
                    $nearestRelativeFatherName3 = '';
                    $nearestRelativeRelation3 = '';
                    $nearestRelativeContactNo3 = '';
                    $nearestRelativeCnicNo3 = '';
                    $nearestRelativeProfession3 = '';
                    $nearestRelativeAddress3 = '';

                    $guardRelativeHistory = $nearestRelativeModel->getByGuardId($array['id']);
                    foreach ($guardRelativeHistory as $key => $val) {
                        if ($key == 0) {
                            $nearestRelativeName1 = $val->relative_name;
                            $nearestRelativeFatherName1 = $val->relative_father_name;
                            $nearestRelativeRelation1 = $val->relative_relation;
                            $nearestRelativeContactNo1 = $val->relative_cnic_no;
                            $nearestRelativeCnicNo1 = $val->relative_address;
                            $nearestRelativeProfession1 = $val->relative_profession;
                            $nearestRelativeAddress1 = $val->relative_contact_no;
                        }
                        if ($key == 1) {
                            $nearestRelativeName2 = $val->relative_name;
                            $nearestRelativeFatherName2 = $val->relative_father_name;
                            $nearestRelativeRelation2 = $val->relative_relation;
                            $nearestRelativeContactNo2 = $val->relative_cnic_no;
                            $nearestRelativeCnicNo2 = $val->relative_address;
                            $nearestRelativeProfession2 = $val->relative_profession;
                            $nearestRelativeAddress2 = $val->relative_contact_no;
                        }
                        if ($key == 2) {
                            $nearestRelativeName3 = $val->relative_name;
                            $nearestRelativeFatherName3 = $val->relative_father_name;
                            $nearestRelativeRelation3 = $val->relative_relation;
                            $nearestRelativeContactNo3 = $val->relative_cnic_no;
                            $nearestRelativeCnicNo3 = $val->relative_address;
                            $nearestRelativeProfession3 = $val->relative_profession;
                            $nearestRelativeAddress3 = $val->relative_contact_no;
                        }

                    }


                    $familyMemberName1 = '';
                    $familyMemberRelation1 = '';
                    $familyMemberAge1 = '';
                    $familyMemberProfession1 = '';
                    $familyMemberAddress1 = '';

                    $familyMemberName2 = '';
                    $familyMemberRelation2 = '';
                    $familyMemberAge2 = '';
                    $familyMemberProfession2 = '';
                    $familyMemberAddress2 = '';

                    $familyMemberName3 = '';
                    $familyMemberRelation3 = '';
                    $familyMemberAge3 = '';
                    $familyMemberProfession3 = '';
                    $familyMemberAddress3 = '';


                    $guardFamily = $familyModel->getByGuardId($array['id']);
                    foreach ($guardFamily as $key => $val) {
                        if ($key == 0) {
                            $familyMemberName1 = $val->family_member_name;
                            $familyMemberRelation1 = $val->family_member_relation;
                            $familyMemberAge1 = $val->family_member_age;
                            $familyMemberProfession1 = $val->family_member_profession;
                            $familyMemberAddress1 = $val->family_member_address;
                        }
                        if ($key == 1) {
                            $familyMemberName2 = $val->family_member_name;
                            $familyMemberRelation2 = $val->family_member_relation;
                            $familyMemberAge2 = $val->family_member_age;
                            $familyMemberProfession2 = $val->family_member_profession;
                            $familyMemberAddress2 = $val->family_member_address;
                        }
                        if ($key == 2) {
                            $familyMemberName3 = $val->family_member_name;
                            $familyMemberRelation3 = $val->family_member_relation;
                            $familyMemberAge3 = $val->family_member_age;
                            $familyMemberProfession3 = $val->family_member_profession;
                            $familyMemberAddress3 = $val->family_member_address;
                        }

                    }


                    $judicialCaseNumber1 = '';
                    $judicialCaseDate1 = '';
                    $judicialCasePoliceStation1 = '';
                    $judicialCaseInvestigationResult1 = '';
                    $judicialCaseCourtResult1 = '';

                    $judicialCaseNumber2 = '';
                    $judicialCaseDate2 = '';
                    $judicialCasePoliceStation2 = '';
                    $judicialCaseInvestigationResult2 = '';
                    $judicialCaseCourtResult2 = '';

                    $judicialCaseNumber3 = '';
                    $judicialCaseDate3 = '';
                    $judicialCasePoliceStation3 = '';
                    $judicialCaseInvestigationResult3 = '';
                    $judicialCaseCourtResult3 = '';


                    $judicialCases = $judicialCasesModel->getByGuardId($array['id']);
                    foreach ($judicialCases as $key => $val) {
                        if ($key == 0) {
                            $judicialCaseNumber1 = $val->judicial_case_no;
                            $judicialCaseDate1 = $val->judicial_case_date;
                            $judicialCasePoliceStation1 = $val->judicial_case_police_station;
                            $judicialCaseInvestigationResult1 = $val->judicial_case_investigatoin_result;
                            $judicialCaseCourtResult1 = $val->judicial_case_court_result;
                        }
                        if ($key == 1) {
                            $judicialCaseNumber2 = $val->judicial_case_no;
                            $judicialCaseDate2 = $val->judicial_case_date;
                            $judicialCasePoliceStation2 = $val->judicial_case_police_station;
                            $judicialCaseInvestigationResult2 = $val->judicial_case_investigatoin_result;
                            $judicialCaseCourtResult2 = $val->judicial_case_court_result;
                        }
                        if ($key == 2) {
                            $judicialCaseNumber3 = $val->judicial_case_no;
                            $judicialCaseDate3 = $val->judicial_case_date;
                            $judicialCasePoliceStation3 = $val->judicial_case_police_station;
                            $judicialCaseInvestigationResult3 = $val->judicial_case_investigatoin_result;
                            $judicialCaseCourtResult3 = $val->judicial_case_court_result;
                        }

                    }


//                    echo "<pre>";
//                    print_r(count($guardEmploymentHistory));
//                    echo "</pre>";
//                    die("dead");


                    $religion = $array['religion'];
                    $bloodGroup = $array['blood_group_id'];
                    $education = $array['education'];
                    $currentStatus = $array['current_status_id'];
                    $exService = $array['ex'];
                    $regionalOffice = $array['regional_office_id'];
                    $enrolledBy = $array['enrolled_by'];


                    $supervisor = '';
                    $manager = '';
                    $managerOfSupervisor = '';


                    $guardSupervisor = $guardModel->getCurrentSupervisor($array['id']);

                    if ($guardSupervisor) {
                        $supervisor = $guardSupervisor->name;

                        if ($supervisor) {

                            $managerOfSupervisor = $managerSupervisorAssociation->managerOfSupervisor($guardSupervisor->id);

                            if ($managerOfSupervisor) {
                                $managerId = $managerOfSupervisor->manager_id;
                                $manager = $userModel->getUserById($managerId)->name;
                            }
                        }
                    }


//                    echo "<pre>";
//                    print_r($bloodGroupModel);
//                    echo "</pre>";
//                    die("dead") ;

                    if ($religion > 0) {
                        $religion = $religionModel->getModelById($religion)->name;
                    }
                    if ($bloodGroup > 0) {
                        $bloodGroup = $bloodGroupModel->getModelById($bloodGroup)->name;
                    }
                    if ($education > 0) {
                        $education = $educationModel->getModelById($education)->name;
                    } else {
                        $education = '';
                    }
                    if ($currentStatus > 0) {
                        $currentStatus = $guardStatusModel->getModelById($currentStatus)->value;
                    }
                    if ($exService > 0) {
                        $exService = $exServiceModel->getModelById($exService)->name;
                    }
                    if ($regionalOffice > 0) {
                        $regionalOffice = $regionalOfficeModel->getModelById($regionalOffice)->office_head;
                    }
                    if ($enrolledBy > 0) {
                        $enrolledBy = $userModel->getUserById($enrolledBy)->name;
                    }
                    if ($array['cnic_issue_date']) {
                        $array['cnic_issue_date'] = $carbonObject->parse($array['cnic_issue_date'])->format('d  M  Y');

                    }
                    if ($array['cnic_expiry_date']) {
                        $array['cnic_expiry_date'] = $carbonObject->parse($array['cnic_expiry_date'])->format('d  M  Y');

                    }
                    if ($array['date_of_birth']) {
                        $array['date_of_birth'] = $carbonObject->parse($array['date_of_birth'])->format('d  M  Y');
                    }
                    if ($array['passport_expiry_date']) {


                        if ($array['passport_expiry_date'] == '0000-00-00') {
                            $array['passport_expiry_date'] = '';
                        } else {
                            $array['passport_expiry_date'] = $carbonObject->parse($array['passport_expiry_date'])->format('d  M  Y');
                        }


                    }
                    if ($array['army_joining_date']) {
                        $array['army_joining_date'] = $carbonObject->parse($array['army_joining_date'])->format('d  M  Y');
                    }
                    if ($array['army_leaving_date']) {
                        $array['army_leaving_date'] = $carbonObject->parse($array['army_leaving_date'])->format('d  M  Y');
                    }
                    if ($array['termination_date']) {
                        $array['termination_date'] = $carbonObject->parse($array['termination_date'])->format('d  M  Y');
                    }

                    $array['designation'] = $guardDesignationModel->getModelById($array['designation'])->name;
//                    echo "<pre>";
//                    print_r($enrolledBy);
//                    echo "</pre>";
//                    die("dead") ;

                    $sheet->row($count, array(
                        ucfirst($array['parwest_id']), ucfirst($array['name']), ucfirst($array['father_name']), ucfirst($array['mother_name']),
                        ucfirst($array['next_of_ken']), ucfirst($array['cnic_no']), ucfirst($array['cnic_issue_date']), ucfirst($array['cnic_expiry_date']),
                        ucfirst($array['passport_no']), ucfirst($array['passport_expiry_date']), ucfirst($array['date_of_birth']),
                        ucfirst($array['age']), ucfirst($array['contact_no']), ucfirst($religion),
                        ucfirst($array['cast']), ucfirst($array['sect']), ucfirst($bloodGroup),
                        ucfirst($education), ucfirst($array['education_passing_year']), ucfirst($array['education_istitution']),
                        ucfirst($array['current_address']), ucfirst($array['current_address_contact_no']), ucfirst($array['permanent_address']),
                        ucfirst($array['permanent_address_contact_no']), ucfirst($array['police_station']), ucfirst($array['salary']),
                        ucfirst($array['introducer']), ucfirst($array['introducer_cnic_no']), ucfirst($array['introducer_address']),
                        ucfirst($array['introducer_contact_no']), ucfirst($array['height']), ucfirst($array['weight']),
                        ucfirst($array['eye_color']), ucfirst($array['hair_color']), ucfirst($array['mark_of_identification']),
                        ucfirst($array['disablity']), ucfirst($array['designation']), ucfirst($currentStatus),
                        ucfirst($exService), ucfirst($array['other']), ucfirst($array['army_no']),
                        ucfirst($array['army_rank']), ucfirst($array['army_group']), ucfirst($array['army_service_period']),
                        ucfirst($array['army_service_years']), ucfirst($array['army_service_months']), ucfirst($array['army_joining_date']),
                        ucfirst($array['army_leaving_date']), ucfirst($array['remarks']), ucfirst($regionalOffice),
                        ucfirst($array['termination_date']), ucfirst($enrolledBy),

                        ucfirst($supervisor), ucfirst($manager),
                        $carbonObject->parse($array['created_at'])->format('d  M  Y'),
                        $carbonObject->parse($array['created_at'])->format('H:i'),


                        ucfirst($employmentCompanyName1), ucfirst($employmentCompanyDuration1), ucfirst($employmentCompanyStartDate1), ucfirst($employmentCompanyEndDate1),
                        ucfirst($employmentCompanyName2), ucfirst($employmentCompanyDuration2), ucfirst($employmentCompanyStartDate2), ucfirst($employmentCompanyEndDate2),
                        ucfirst($employmentCompanyName3), ucfirst($employmentCompanyDuration3), ucfirst($employmentCompanyStartDate3), ucfirst($employmentCompanyEndDate3),


                        ucfirst($nearestRelativeName1), ucfirst($nearestRelativeFatherName1), ucfirst($nearestRelativeRelation1), ucfirst($nearestRelativeContactNo1),
                        ucfirst($nearestRelativeCnicNo1), ucfirst($nearestRelativeProfession1), ucfirst($nearestRelativeAddress1),
                        ucfirst($nearestRelativeName2), ucfirst($nearestRelativeFatherName2), ucfirst($nearestRelativeRelation2), ucfirst($nearestRelativeContactNo2),
                        ucfirst($nearestRelativeCnicNo2), ucfirst($nearestRelativeProfession2), ucfirst($nearestRelativeAddress2),
                        ucfirst($nearestRelativeName3), ucfirst($nearestRelativeFatherName3), ucfirst($nearestRelativeRelation3), ucfirst($nearestRelativeContactNo3),
                        ucfirst($nearestRelativeCnicNo3), ucfirst($nearestRelativeProfession3), ucfirst($nearestRelativeAddress3),


                        ucfirst($familyMemberName1), ucfirst($familyMemberRelation1), ucfirst($familyMemberAge1),
                        ucfirst($familyMemberProfession1), ucfirst($familyMemberAddress1),
                        ucfirst($familyMemberName2), ucfirst($familyMemberRelation2), ucfirst($familyMemberAge2),
                        ucfirst($familyMemberProfession2), ucfirst($familyMemberAddress2),
                        ucfirst($familyMemberName3), ucfirst($familyMemberRelation3), ucfirst($familyMemberAge3),
                        ucfirst($familyMemberProfession3), ucfirst($familyMemberAddress3),


                        ucfirst($judicialCaseNumber1), ucfirst($judicialCaseDate1), ucfirst($judicialCasePoliceStation1),
                        ucfirst($judicialCaseInvestigationResult1), ucfirst($judicialCaseCourtResult1),
                        ucfirst($judicialCaseNumber2), ucfirst($judicialCaseDate2), ucfirst($judicialCasePoliceStation2),
                        ucfirst($judicialCaseInvestigationResult2), ucfirst($judicialCaseCourtResult2),
                        ucfirst($judicialCaseNumber3), ucfirst($judicialCaseDate3), ucfirst($judicialCasePoliceStation3),
                        ucfirst($judicialCaseInvestigationResult3), ucfirst($judicialCaseCourtResult3),


                    ));
                    $count += 1;
                }
            });

        })->store('xlsx', Config::get('globalvariables.$pathToSaveTemporaryExcelFiles'));

        $fileNameToDownload = $fileName . '.xlsx';

        $data = array('fileNameToDownload' => $fileNameToDownload);

        return ['responseCode' => 1, 'responseStatus' => 'successful', 'message' => 'your file is ready, and will download in few seconds', 'data' => $data];

    }

    public function downloadGuardSearchDocument($filename)
    {


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

    public function makeExcelSheetOfGuardListResult()
    {
        $guardModel = new Guards\Guards();
        $searchResults = $guardModel->allGuardsWithoutPaginate();


        $fileName = md5(microtime());


        Excel::create($fileName, function ($excel) use ($searchResults, $guardModel) {


            $excel->setTitle('Guard List Result');
            $excel->setDescription('Following is result of Guard List');


            $excel->sheet('Sheet1', function ($sheet) use ($searchResults, $guardModel) {
                $religionModel = new Mix\ReligionModel();
                $bloodGroupModel = new Mix\BloodGroupsModel();
                $educationModel = new Guards\GuardEducationsTypesModel();
                $guardStatusModel = new Guards\GuardStatusesModel();
                $exServiceModel = new Guards\GuardExServices();
                $regionalOfficeModel = new Guards\RegionalOfficeModel();
                $userModel = new UserModel();
                $carbonObject = new Carbon();
                $guardDesignationModel = new Guards\GuardDesignationModel();
                $employmentHistoryModel = new Guards\GuardEmploymentHistoryModel();
                $nearestRelativeModel = new Guards\GuardNearestRelativeModel();
                $familyModel = new Guards\GuardFamilyModel();
                $judicialCasesModel = new Guards\GuardJudicialCaseModel();
                $guardSupervisorRelationshipModel = new Guards\GuardsUnderSupervisorHistory();
                $managerSupervisorAssociation = new ManagerSupervisorAssociation();


                $sheet->row(1, array(
                    'Parwest Id', 'Name', 'Father Name', 'Mother Name',
                    'Next Of Ken', 'Cnic No', 'Cnic Issue Date', 'Cnic Expiry Date',
                    'Passport No', 'Passport Expiry Date', 'Date Of Birth',
                    'Age', 'Contact_ No', 'Religion',
                    'Cast', 'Sect', 'Blood Group Id',
                    'Education', 'Education Passing Year', 'Education Istitution',
                    'Current Address', 'Current Address Contact No', 'Permanent Address',
                    'Permanent Address Contact No', 'Police Station', 'Salary',
                    'Introducer', 'Introducer Cnic No', 'Introducer Address',
                    'Introducer Contact No', 'Height', 'Weight',
                    'Eye Color', 'Hair Color', 'Mark Of Identification',
                    'Disablity', 'Designation', 'Current Status Id',
                    'Ex Service', 'Other', 'Army No',
                    'Army Rank', 'Army Group', 'Army Service Period',
                    'Army Service Years', 'Army Service Months', 'Army Joining Date',
                    'Army Leaving Date', 'Remarks', 'Regional Office Id',
                    'Termination Date', 'Enrolled By',

                    'Supervisor', 'Manger',

                    'Parwest Joining Date','Parwest Joining Time',

                    'Employment History Company 1', 'Service Duration', 'Enrollment Date', 'Discharge Date',
                    'Employment History Company 2', 'Service Duration', 'Enrollment Date', 'Discharge Date',
                    'Employment History Company 3', 'Service Duration', 'Enrollment Date', 'Discharge Date',

                    'Nearest Relative Name 1', 'Father Name', 'Relation', 'Contact #', 'CNIC #', 'Profession', 'Address',
                    'Nearest Relative Name 2', 'Father Name', 'Relation', 'Contact #', 'CNIC #', 'Profession', 'Address',
                    'Nearest Relative Name 3', 'Father Name', 'Relation', 'Contact #', 'CNIC #', 'Profession', 'Address',

                    'Family Member Name 1', 'Relation', 'Age', 'Profession ', 'Address',
                    'Family Member Name 2', 'Relation', 'Age', 'Profession ', 'Address',
                    'Family Member Name 3', 'Relation', 'Age', 'Profession ', 'Address',

                    'Judicial Case 1', 'Date', 'Police Station', 'Investigation Result ', 'Court Result',
                    'Judicial Case 2', 'Date', 'Police Station', 'Investigation Result ', 'Court Result',
                    'Judicial Case 3', 'Date', 'Police Station', 'Investigation Result ', 'Court Result',


                ));
                $count = 2;
                foreach ($searchResults as $key => $array) {


                    $employmentCompanyName1 = '';
                    $employmentCompanyDuration1 = '';
                    $employmentCompanyStartDate1 = '';
                    $employmentCompanyEndDate1 = '';

                    $employmentCompanyName2 = '';
                    $employmentCompanyDuration2 = '';
                    $employmentCompanyStartDate2 = '';
                    $employmentCompanyEndDate2 = '';

                    $employmentCompanyName3 = '';
                    $employmentCompanyDuration3 = '';
                    $employmentCompanyStartDate3 = '';
                    $employmentCompanyEndDate3 = '';

                    $guardEmploymentHistory = $employmentHistoryModel->getByGuardId($array['id']);
                    foreach ($guardEmploymentHistory as $key => $val) {
                        if ($key == 0) {
                            $employmentCompanyName1 = $val->name_of_company;
                            $employmentCompanyDuration1 = $val->duration;
                            if ($val->duration_type == 1) {
                                $employmentCompanyDuration1 = $employmentCompanyDuration1 . ' Days';
                            } elseif ($val->duration_type == 2) {
                                $employmentCompanyDuration1 = $employmentCompanyDuration1 . ' Months';
                            } elseif ($val->duration_type == 3) {
                                $employmentCompanyDuration1 = $employmentCompanyDuration1 . ' Years';
                            }
                            $employmentCompanyStartDate1 = $val->start_date;
                            $employmentCompanyEndDate1 = $val->end_date;
                        }
                        if ($key == 1) {
                            $employmentCompanyName2 = $val->name_of_company;
                            $employmentCompanyDuration2 = $val->duration;
                            if ($val->duration_type == 1) {
                                $employmentCompanyDuration2 = $employmentCompanyDuration2 . ' Days';
                            } elseif ($val->duration_type == 2) {
                                $employmentCompanyDuration2 = $employmentCompanyDuration2 . ' Months';
                            } elseif ($val->duration_type == 3) {
                                $employmentCompanyDuration2 = $employmentCompanyDuration2 . ' Years';
                            }
                            $employmentCompanyStartDate2 = $val->start_date;
                            $employmentCompanyEndDate2 = $val->end_date;
                        }
                        if ($key == 2) {
                            $employmentCompanyName3 = $val->name_of_company;
                            $employmentCompanyDuration3 = $val->duration;
                            if ($val->duration_type == 1) {
                                $employmentCompanyDuration3 = $employmentCompanyDuration3 . ' Days';
                            } elseif ($val->duration_type == 2) {
                                $employmentCompanyDuration3 = $employmentCompanyDuration3 . ' Months';
                            } elseif ($val->duration_type == 3) {
                                $employmentCompanyDuration3 = $employmentCompanyDuration3 . ' Years';
                            }
                            $employmentCompanyStartDate3 = $val->start_date;
                            $employmentCompanyEndDate3 = $val->end_date;
                        }

                    }


                    $nearestRelativeName1 = '';
                    $nearestRelativeFatherName1 = '';
                    $nearestRelativeRelation1 = '';
                    $nearestRelativeContactNo1 = '';
                    $nearestRelativeCnicNo1 = '';
                    $nearestRelativeProfession1 = '';
                    $nearestRelativeAddress1 = '';


                    $nearestRelativeName2 = '';
                    $nearestRelativeFatherName2 = '';
                    $nearestRelativeRelation2 = '';
                    $nearestRelativeContactNo2 = '';
                    $nearestRelativeCnicNo2 = '';
                    $nearestRelativeProfession2 = '';
                    $nearestRelativeAddress2 = '';

                    $nearestRelativeName3 = '';
                    $nearestRelativeFatherName3 = '';
                    $nearestRelativeRelation3 = '';
                    $nearestRelativeContactNo3 = '';
                    $nearestRelativeCnicNo3 = '';
                    $nearestRelativeProfession3 = '';
                    $nearestRelativeAddress3 = '';

                    $guardRelativeHistory = $nearestRelativeModel->getByGuardId($array['id']);
                    foreach ($guardRelativeHistory as $key => $val) {
                        if ($key == 0) {
                            $nearestRelativeName1 = $val->relative_name;
                            $nearestRelativeFatherName1 = $val->relative_father_name;
                            $nearestRelativeRelation1 = $val->relative_relation;
                            $nearestRelativeContactNo1 = $val->relative_cnic_no;
                            $nearestRelativeCnicNo1 = $val->relative_address;
                            $nearestRelativeProfession1 = $val->relative_profession;
                            $nearestRelativeAddress1 = $val->relative_contact_no;
                        }
                        if ($key == 1) {
                            $nearestRelativeName2 = $val->relative_name;
                            $nearestRelativeFatherName2 = $val->relative_father_name;
                            $nearestRelativeRelation2 = $val->relative_relation;
                            $nearestRelativeContactNo2 = $val->relative_cnic_no;
                            $nearestRelativeCnicNo2 = $val->relative_address;
                            $nearestRelativeProfession2 = $val->relative_profession;
                            $nearestRelativeAddress2 = $val->relative_contact_no;
                        }
                        if ($key == 2) {
                            $nearestRelativeName3 = $val->relative_name;
                            $nearestRelativeFatherName3 = $val->relative_father_name;
                            $nearestRelativeRelation3 = $val->relative_relation;
                            $nearestRelativeContactNo3 = $val->relative_cnic_no;
                            $nearestRelativeCnicNo3 = $val->relative_address;
                            $nearestRelativeProfession3 = $val->relative_profession;
                            $nearestRelativeAddress3 = $val->relative_contact_no;
                        }

                    }


                    $familyMemberName1 = '';
                    $familyMemberRelation1 = '';
                    $familyMemberAge1 = '';
                    $familyMemberProfession1 = '';
                    $familyMemberAddress1 = '';

                    $familyMemberName2 = '';
                    $familyMemberRelation2 = '';
                    $familyMemberAge2 = '';
                    $familyMemberProfession2 = '';
                    $familyMemberAddress2 = '';

                    $familyMemberName3 = '';
                    $familyMemberRelation3 = '';
                    $familyMemberAge3 = '';
                    $familyMemberProfession3 = '';
                    $familyMemberAddress3 = '';


                    $guardFamily = $familyModel->getByGuardId($array['id']);
                    foreach ($guardFamily as $key => $val) {
                        if ($key == 0) {
                            $familyMemberName1 = $val->family_member_name;
                            $familyMemberRelation1 = $val->family_member_relation;
                            $familyMemberAge1 = $val->family_member_age;
                            $familyMemberProfession1 = $val->family_member_profession;
                            $familyMemberAddress1 = $val->family_member_address;
                        }
                        if ($key == 1) {
                            $familyMemberName2 = $val->family_member_name;
                            $familyMemberRelation2 = $val->family_member_relation;
                            $familyMemberAge2 = $val->family_member_age;
                            $familyMemberProfession2 = $val->family_member_profession;
                            $familyMemberAddress2 = $val->family_member_address;
                        }
                        if ($key == 2) {
                            $familyMemberName3 = $val->family_member_name;
                            $familyMemberRelation3 = $val->family_member_relation;
                            $familyMemberAge3 = $val->family_member_age;
                            $familyMemberProfession3 = $val->family_member_profession;
                            $familyMemberAddress3 = $val->family_member_address;
                        }

                    }


                    $judicialCaseNumber1 = '';
                    $judicialCaseDate1 = '';
                    $judicialCasePoliceStation1 = '';
                    $judicialCaseInvestigationResult1 = '';
                    $judicialCaseCourtResult1 = '';

                    $judicialCaseNumber2 = '';
                    $judicialCaseDate2 = '';
                    $judicialCasePoliceStation2 = '';
                    $judicialCaseInvestigationResult2 = '';
                    $judicialCaseCourtResult2 = '';

                    $judicialCaseNumber3 = '';
                    $judicialCaseDate3 = '';
                    $judicialCasePoliceStation3 = '';
                    $judicialCaseInvestigationResult3 = '';
                    $judicialCaseCourtResult3 = '';


                    $judicialCases = $judicialCasesModel->getByGuardId($array['id']);
                    foreach ($judicialCases as $key => $val) {
                        if ($key == 0) {
                            $judicialCaseNumber1 = $val->judicial_case_no;
                            $judicialCaseDate1 = $val->judicial_case_date;
                            $judicialCasePoliceStation1 = $val->judicial_case_police_station;
                            $judicialCaseInvestigationResult1 = $val->judicial_case_investigatoin_result;
                            $judicialCaseCourtResult1 = $val->judicial_case_court_result;
                        }
                        if ($key == 1) {
                            $judicialCaseNumber2 = $val->judicial_case_no;
                            $judicialCaseDate2 = $val->judicial_case_date;
                            $judicialCasePoliceStation2 = $val->judicial_case_police_station;
                            $judicialCaseInvestigationResult2 = $val->judicial_case_investigatoin_result;
                            $judicialCaseCourtResult2 = $val->judicial_case_court_result;
                        }
                        if ($key == 2) {
                            $judicialCaseNumber3 = $val->judicial_case_no;
                            $judicialCaseDate3 = $val->judicial_case_date;
                            $judicialCasePoliceStation3 = $val->judicial_case_police_station;
                            $judicialCaseInvestigationResult3 = $val->judicial_case_investigatoin_result;
                            $judicialCaseCourtResult3 = $val->judicial_case_court_result;
                        }

                    }


//                    echo "<pre>";
//                    print_r(count($guardEmploymentHistory));
//                    echo "</pre>";
//                    die("dead");


                    $religion = $array['religion'];
                    $bloodGroup = $array['blood_group_id'];
                    $education = $array['education'];
                    $currentStatus = $array['current_status_id'];
                    $exService = $array['ex'];
                    $regionalOffice = $array['regional_office_id'];
                    $enrolledBy = $array['enrolled_by'];
                    $supervisor = '';
                    $manager = '';
                    $managerOfSupervisor = '';


                    $guardSupervisor = $guardModel->getCurrentSupervisor($array['id']);

                    if ($guardSupervisor) {
                        $supervisor = $guardSupervisor->name;

                        if ($supervisor) {

                            $managerOfSupervisor = $managerSupervisorAssociation->managerOfSupervisor($guardSupervisor->id);

                            if ($managerOfSupervisor) {
                                $managerId = $managerOfSupervisor->manager_id;
                                $manager = $userModel->getUserById($managerId)->name;
                            }
                        }
                    }


//                    echo "<pre>";
//                    print_r($bloodGroupModel);
//                    echo "</pre>";
//                    die("dead") ;

                    if ($religion > 0) {
                        $religion = $religionModel->getModelById($religion)->name;
                    }
                    if ($bloodGroup > 0) {
                        $bloodGroup = $bloodGroupModel->getModelById($bloodGroup)->name;
                    }
                    if ($education > 0) {
                        $education = $educationModel->getModelById($education)->name;
                    } else {
                        $education = '';
                    }
                    if ($currentStatus > 0) {
                        $currentStatus = $guardStatusModel->getModelById($currentStatus)->value;
                    }
                    if ($exService > 0) {
                        $exService = $exServiceModel->getModelById($exService)->name;
                    }
                    if ($regionalOffice > 0) {
                        $regionalOffice = $regionalOfficeModel->getModelById($regionalOffice)->office_head;
                    }
                    if ($enrolledBy > 0) {
                        $enrolledBy = $userModel->getUserById($enrolledBy)->name;
                    }
                    if ($array['cnic_expiry_date']) {
                        $array['cnic_expiry_date'] = $carbonObject->parse($array['cnic_expiry_date'])->format('d  M  Y');

                    }
                    if ($array['cnic_issue_date']) {
                        $array['cnic_issue_date'] = $carbonObject->parse($array['cnic_issue_date'])->format('d  M  Y');

                    }
                    if ($array['date_of_birth']) {
                        $array['date_of_birth'] = $carbonObject->parse($array['date_of_birth'])->format('d  M  Y');
                    }
                    if ($array['passport_expiry_date']) {


                        if ($array['passport_expiry_date'] == '0000-00-00') {
                            $array['passport_expiry_date'] = '';
                        } else {
                            $array['passport_expiry_date'] = $carbonObject->parse($array['passport_expiry_date'])->format('d  M  Y');
                        }


                    }
                    if ($array['army_joining_date']) {
                        $array['army_joining_date'] = $carbonObject->parse($array['army_joining_date'])->format('d  M  Y');
                    }
                    if ($array['army_leaving_date']) {
                        $array['army_leaving_date'] = $carbonObject->parse($array['army_leaving_date'])->format('d  M  Y');
                    }
                    if ($array['termination_date']) {
                        $array['termination_date'] = $carbonObject->parse($array['termination_date'])->format('d  M  Y');
                    }

                    $array['designation'] = $guardDesignationModel->getModelById($array['designation'])->name;
//                    echo "<pre>";
//                    print_r($enrolledBy);
//                    echo "</pre>";
//                    die("dead") ;

                    $sheet->row($count, array(
                        ucfirst($array['parwest_id']), ucfirst($array['name']), ucfirst($array['father_name']), ucfirst($array['mother_name']),
                        ucfirst($array['next_of_ken']), ucfirst($array['cnic_no']), ucfirst($array['cnic_issue_date']), ucfirst($array['cnic_expiry_date']),
                        ucfirst($array['passport_no']), ucfirst($array['passport_expiry_date']), ucfirst($array['date_of_birth']),
                        ucfirst($array['age']), ucfirst($array['contact_no']), ucfirst($religion),
                        ucfirst($array['cast']), ucfirst($array['sect']), ucfirst($bloodGroup),
                        ucfirst($education), ucfirst($array['education_passing_year']), ucfirst($array['education_istitution']),
                        ucfirst($array['current_address']), ucfirst($array['current_address_contact_no']), ucfirst($array['permanent_address']),
                        ucfirst($array['permanent_address_contact_no']), ucfirst($array['police_station']), ucfirst($array['salary']),
                        ucfirst($array['introducer']), ucfirst($array['introducer_cnic_no']), ucfirst($array['introducer_address']),
                        ucfirst($array['introducer_contact_no']), ucfirst($array['height']), ucfirst($array['weight']),
                        ucfirst($array['eye_color']), ucfirst($array['hair_color']), ucfirst($array['mark_of_identification']),
                        ucfirst($array['disablity']), ucfirst($array['designation']), ucfirst($currentStatus),
                        ucfirst($exService), ucfirst($array['other']), ucfirst($array['army_no']),
                        ucfirst($array['army_rank']), ucfirst($array['army_group']), ucfirst($array['army_service_period']),
                        ucfirst($array['army_service_years']), ucfirst($array['army_service_months']), ucfirst($array['army_joining_date']),
                        ucfirst($array['army_leaving_date']), ucfirst($array['remarks']), ucfirst($regionalOffice),
                        ucfirst($array['termination_date']), ucfirst($enrolledBy),


                        ucfirst($supervisor), ucfirst($manager),
                        ucfirst($carbonObject->parse($array['created_at'])->format('d  M  Y')),
                        ucfirst($carbonObject->parse($array['created_at'])->format('H:i')),


                        ucfirst($employmentCompanyName1), ucfirst($employmentCompanyDuration1), ucfirst($employmentCompanyStartDate1), ucfirst($employmentCompanyEndDate1),
                        ucfirst($employmentCompanyName2), ucfirst($employmentCompanyDuration2), ucfirst($employmentCompanyStartDate2), ucfirst($employmentCompanyEndDate2),
                        ucfirst($employmentCompanyName3), ucfirst($employmentCompanyDuration3), ucfirst($employmentCompanyStartDate3), ucfirst($employmentCompanyEndDate3),


                        ucfirst($nearestRelativeName1), ucfirst($nearestRelativeFatherName1), ucfirst($nearestRelativeRelation1), ucfirst($nearestRelativeContactNo1),
                        ucfirst($nearestRelativeCnicNo1), ucfirst($nearestRelativeProfession1), ucfirst($nearestRelativeAddress1),
                        ucfirst($nearestRelativeName2), ucfirst($nearestRelativeFatherName2), ucfirst($nearestRelativeRelation2), ucfirst($nearestRelativeContactNo2),
                        ucfirst($nearestRelativeCnicNo2), ucfirst($nearestRelativeProfession2), ucfirst($nearestRelativeAddress2),
                        ucfirst($nearestRelativeName3), ucfirst($nearestRelativeFatherName3), ucfirst($nearestRelativeRelation3), ucfirst($nearestRelativeContactNo3),
                        ucfirst($nearestRelativeCnicNo3), ucfirst($nearestRelativeProfession3), ucfirst($nearestRelativeAddress3),


                        ucfirst($familyMemberName1), ucfirst($familyMemberRelation1), ucfirst($familyMemberAge1),
                        ucfirst($familyMemberProfession1), ucfirst($familyMemberAddress1),
                        ucfirst($familyMemberName2), ucfirst($familyMemberRelation2), ucfirst($familyMemberAge2),
                        ucfirst($familyMemberProfession2), ucfirst($familyMemberAddress2),
                        ucfirst($familyMemberName3), ucfirst($familyMemberRelation3), ucfirst($familyMemberAge3),
                        ucfirst($familyMemberProfession3), ucfirst($familyMemberAddress3),


                        ucfirst($judicialCaseNumber1), ucfirst($judicialCaseDate1), ucfirst($judicialCasePoliceStation1),
                        ucfirst($judicialCaseInvestigationResult1), ucfirst($judicialCaseCourtResult1),
                        ucfirst($judicialCaseNumber2), ucfirst($judicialCaseDate2), ucfirst($judicialCasePoliceStation2),
                        ucfirst($judicialCaseInvestigationResult2), ucfirst($judicialCaseCourtResult2),
                        ucfirst($judicialCaseNumber3), ucfirst($judicialCaseDate3), ucfirst($judicialCasePoliceStation3),
                        ucfirst($judicialCaseInvestigationResult3), ucfirst($judicialCaseCourtResult3),


                    ));
                    $count += 1;
                }
            });


//            $excel->sheet('Sheet1', function ($sheet) use ($searchResults) {
//                // Sheet manipulation
//                $sheet->setOrientation('landscape');
//                $sheet->fromArray($searchResults, null, 'A1', true, true);
//            });

        })->store('xlsx', Config::get('globalvariables.$pathToSaveTemporaryExcelFiles'));

        $fileNameToDownload = $fileName . '.xlsx';

        $data = array('fileNameToDownload' => $fileNameToDownload);

        return ['responseCode' => 1, 'responseStatus' => 'successful', 'message' => 'your file is ready, and will download in few seconds', 'data' => $data];

    }

    public function downloadGuardListDocument($filename)
    {
        // Check if file exists in app/storage/file folder
        $file_path = Config::get('globalvariables.$pathToSaveTemporaryExcelFiles') . '/' . $filename;
        if (file_exists($file_path)) {
            // Send Download
            return \Illuminate\Support\Facades\Response::download($file_path, $filename, [
                'Content-Length: ' . filesize($file_path)
            ])->deleteFileAfterSend(true);
        } else {
            // Error
            exit('Requested file does not exist on our server!');
        }
    }

    public function updateSpecialBranchCheck(Request $request)
    {

        $guardModel = new Guards\Guards();
        $guardDetail = $guardModel->guardDetail($request->guard_id);
        $updateSpecialBranchFlag = $guardModel->updateSpecialBranchCheck($request->guard_id);
        $guardFolderName = $guardDetail->parwest_id;


        // saving in special check history table


        $guardSpecialBranchHistoryModel = new Guards\GuardSpecialBranchCheckHistoryModel();


        $ds = DIRECTORY_SEPARATOR;  //1
        $storeFolder = 'guard_documents';

        $pathToSaveFile = public_path() . $ds . $storeFolder . $ds . $guardFolderName;
        if (!is_dir($pathToSaveFile)) {
            //Directory does not exist, so lets create it.
//            echo '<pre>';
//            echo $pathToSaveFile;
//            echo '</pre>';
            mkdir($pathToSaveFile);

        }


        if (!empty($_FILES)) {

            $randomString = 'special_branch_check' . md5(microtime());
            $fileName = $randomString . preg_replace('/\s+/', '', $_FILES['file']['name']);
            $tempFile = $_FILES['file']['tmp_name'];          //3


            $targetFile = $pathToSaveFile . $ds . $fileName;  //5

            move_uploaded_file($tempFile, $targetFile); //6


            $guardSpecialBranchHistoryModel = $guardSpecialBranchHistoryModel->saveNewModel($request->guard_id, $guardFolderName . '/' . $fileName);


            return ['responseCode' => 1, 'responseStatus' => 'successful', 'message' => 'successfully done special branch check'];
        } else {
            return ['responseCode' => 0, 'responseStatus' => 'failed', 'message' => 'failed to do special branch check'];
        }

    }

    public function guardSalaryCategories(Request $request)
    {
        $salaryCategoryModel = new Guards\GuardSalaryCategoriesModel();
        $allSalaryCategories = $salaryCategoryModel->getAll();

        $data = array(
            'allSalaryCategories' => $allSalaryCategories,
        );
        return view('guards/salaryCategoriesList')->with($data);


    }

    public function addNewSalaryCategory()
    {
        return view('guards/addNewSalaryCategory');
    }

    public function storeNewSalaryCategory(Request $request)
    {
        $salaryCategoryName = strtolower($request->new_salary_category_name);
        $salaryCategoryAmount = strtolower($request->new_salary_category_amount);

        $salaryCategoryObject = new Guards\GuardSalaryCategoriesModel();
        $response = $salaryCategoryObject->addNewModel($salaryCategoryName, $salaryCategoryAmount);
        if ($response) {
            return redirect(url('guard/mergedOptions'))->with('salaryCategoryStatus', 1)
                ->with('salaryCategoryMessage', 'Successfully added new Salary Category');
        } else {
            return redirect(url('guard/mergedOptions'))->with('salaryCategoryStatus', 0)
                ->with('salaryCategoryMessage', 'Failed to add new salary category, because there is already a salary category with same name or amount');
        }

    }

    public function updateSalaryCategoryForm($salaryCategory)
    {


        $salaryCategoryObject = new Guards\GuardSalaryCategoriesModel();
        $salaryCategoryModel = $salaryCategoryObject->getModelById($salaryCategory);

        $data = array(

            'salaryCategoryModel' => $salaryCategoryModel,
        );
        return view('guards/updateSalaryCategoryForm')->with($data);
    }

    public function saveUpdatedSalaryCategory(Request $request)
    {
        $modelId = strtolower($request->salaryCategoryId);
        $salaryCategoryName = strtolower($request->salary_category_name);
        $salaryCategoryAmount = strtolower($request->salary_category_amount);

        $salaryCategoryObject = new Guards\GuardSalaryCategoriesModel();
        $response = $salaryCategoryObject->updateModel($modelId, $salaryCategoryName, $salaryCategoryAmount);
        if ($response) {
            return redirect(url('guard/mergedOptions'))->with('salaryCategoryStatus', 1)->with('salaryCategoryMessage', 'Successfully added new Salary Category');
        } else {
            return redirect(url('guard/mergedOptions'))->with('salaryCategoryStatus', 0)->with('salaryCategoryMessage', 'Failed to add new salary category, because there is already a salary category with same name or amount');
        }
    }

    public function updateGuardStatusByAjax(Request $request)
    {
        $status_date = Carbon::now();
        if(isset($request->status_date))
        {

            $status_date = Carbon::parse($request->status_date);
        }
        $newStatus = $request->new_status;
        $guardId = $request->guard_id;
        $guardModel = new Guards\Guards();
        $response = $guardModel->updatedCurrentStatusByAjax($guardId, $newStatus,$status_date);

        $guardStatusModel = new Guards\GuardStatusesModel();
        $newStatusText = $guardStatusModel->getModelById($newStatus);


        if ($response) {


            return ['responseCode' => 1, 'responseStatus' => 'successful', 'message' => 'Guard\'s Status Updated Successfully', 'newStatusName' => $newStatusText->value];
        } else {
            return ['responseCode' => 0, 'responseStatus' => 'failed', 'message' => 'Failed To Update Guard\'s Status'];
        }
    }

    public function getDataOfTerminatedGuardByAjax(Request $request)
    {
        $cnicNo = $request->cnic_number;

        $guardModel = new Guards\Guards();
        $isTerminatedModelExists = $guardModel->getTerminatedGuardByCnic($cnicNo);
        $isNonTerminatedModelExists = $guardModel->getNonTerminatedGuardByCnic($cnicNo);

        if ($isNonTerminatedModelExists) {
            return ['responseCode' => 1, 'responseStatus' => 'successful', 'message' => 'data returned successfully', 'data' => $isNonTerminatedModelExists];

        } else {
            if ($isTerminatedModelExists) {
                return ['responseCode' => 1, 'responseStatus' => 'successful', 'message' => 'data returned successfully', 'data' => $isTerminatedModelExists];

            } else {
                return ['responseCode' => 0, 'responseStatus' => 'failed', 'message' => 'no record available', 'data' => null];
            }
        }
        return $request->cnic_number;
    }

    public function addGuardVerification(Request $request)
    {
        $guardId = $request->guard_id;
        $verificationTypeId = $request->verification_type_id;
        $verificationStatusId = $request->verification_status_id;
        $verificationComments = $request->verification_comments;

        $guardVerificationModel = new Guards\GuardVerificationModel();

        $response = $guardVerificationModel->saveValidationAgainstGuard($guardId, $verificationTypeId, $verificationStatusId, $verificationComments);

        if ($response) {

            $validationTypeName = $response->getVerificationType->name;
            $validationStatusName = $response->getVerificationStatus->name;
            $validationUserName = $response->getUserWhoVerified->name;
            $validationComments = $response->comment;

            if ($request->file('file')) {

                $guardModel = new Guards\Guards();
                $guardParwestId = $guardModel->guardDetail($guardId)->parwest_id;

                $file = $request->file('file');
                $nameForFile = 'verification' . str_random(40) . '.' . $file->getClientOriginalExtension();


                if (!file_exists(public_path('guard_documents/' . $guardParwestId))) {
                    mkdir(public_path('guard_documents/' . $guardParwestId), 0777, true);
                }

//                dd(var_dump(public_path('guard_documents\\' . $guardParwestId . '\\' . $nameForFile)));

//                $file->move(public_path('guard_documents\\' . $guardParwestId . '\\'), $nameForFile);

                File::move($file, public_path('guard_documents/' . $guardParwestId . '/' . $nameForFile));
                $responseForFilePath = $guardVerificationModel->updateFilePath($response->id, $guardParwestId . '/' . $nameForFile);

                return [
                    'responseCode' => 1,
                    'responseStatus' => 'successful',
                    'message' => 'successfully added verification',
                    'data' => $responseForFilePath,
                    'validationTypeName' => $validationTypeName,
                    'validationStatusName' => $validationStatusName,
                    'validationUserName' => $validationUserName,
                    'validationComments' => $validationComments,
                ];
            }
            return [
                'responseCode' => 1,
                'responseStatus' => 'successful',
                'message' => 'successfully added verification',
                'data' => $response,
                'validationTypeName' => $validationTypeName,
                'validationTypeName' => $validationTypeName,
                'validationStatusName' => $validationStatusName,
                'validationUserName' => $validationUserName,
                'validationComments' => $validationComments,
            ];
        } else {
            return [
                'responseCode' => 0,
                'responseStatus' => 'failed',
                'message' => 'failed to save verification',
                'data' => null
            ];
        }
    }

    public function guardAgeLimitForm(Request $request)
    {

        $settingsModel = new SettingsModel();
        $allSettings = $settingsModel->getAllSettings();

        return view('guards.guardAgeLimit')->with('allSettings', $allSettings);
    }

    public function updateGuardAgeLimits(Request $request)
    {
        $keys = array('guard_min_age', 'guard_max_age');
        $values = array($request->guardMinimumAge, $request->guardMaximumAge);

        $settingsModel = new SettingsModel();
        $response = $settingsModel->updateSettingsValue($keys, $values);

        return redirect()->back()->with('successful', 'Values Updated Successfully');
    }

    public function storeNewGuardPledgeableDocumentType(Request $request)
    {

        $docName = $request->document_type_name;
        $guardPleadgeableDocumentTypeModel = new Guards\GuardPledgableDocumentsTypeModel();
        $response = $guardPleadgeableDocumentTypeModel->saveNewDocumentType($request);
        if ($response) {
            return redirect('guard/guardPledgeableDocumentTypeList')->with('successful', 'New Document Type Added Successfully');
        } else {
            return redirect()->back()->with('docName',$docName)->with('error', 'Failed : There Is Already A Document With Same Name');
        }
    }

    public function guardPledgeableDocumentTypeList(Request $request)
    {
        $guardPleadgeableDocumentTypeModel = new Guards\GuardPledgableDocumentsTypeModel();
        $allPledgeableDocumentTypes = $guardPleadgeableDocumentTypeModel->getAll();
        $data = array('allPledgeableDocumentTypes' => $allPledgeableDocumentTypes);
        return view('guards.pledgeableDocumentTypeList')->with($data);
    }

    public function addNewGuardPledgeableDocumentTypeForm(Request $request)
    {


        return view('guards.addNewPledgeableDocumentTypeForm');
    }

    public function addNewGaurdPlegedDocument(Request $request)
    {

        $guardPledgedDocumentModel = new Guards\GuardPledgedDocument();
        $response = $guardPledgedDocumentModel->saveReceivedDocumentAgainstGuard($request);
        if ($response) {
            if ($request->file('file')) {

                $guardModel = new Guards\Guards();
                $guardParwestId = $guardModel->guardDetail($request->guard_id)->parwest_id;

                $file = $request->file('file');
                $nameForFile = 'pleged_document' . str_random(40) . '.' . $file->getClientOriginalExtension();

                $directoryPath = public_path('guard_documents/' . $guardParwestId . '/pledged_documents');
                if (!file_exists($directoryPath)) {
                    mkdir($directoryPath, 0777, true);
                }

                File::move($file, $directoryPath . '/' . $nameForFile);
                $responseForFilePath = $guardPledgedDocumentModel->updateFilePath($response->id, $guardParwestId . '/pledged_documents/' . $nameForFile);

                return [
                    'responseCode' => 1,
                    'responseStatus' => 'successful',
                    'message' => 'Successfully Added Document',
                    'filePath' => $responseForFilePath,
                    'data' => $response

                ];

            } else {
                return [
                    'responseCode' => 1,
                    'responseStatus' => 'successful',
                    'message' => 'Successfully Added Document',
                    'filePath' => null,
                    'data' => $response

                ];
            }
        } else {
            return [
                'responseCode' => 0,
                'responseStatus' => 'failed',
                'message' => 'Failed : Document Is Already Pledged',
                'filePath' => null,
                'data' => null

            ];
        }
    }

    public function returnGuardPledgedDocument(Request $request)
    {
        $guardPledgedDocumentModel = new Guards\GuardPledgedDocument();

        $response = $guardPledgedDocumentModel->returnDocument($request->document_id);
        return ['responseCode' => 1, 'responseStatus' => 'Successful', 'message' => 'Document Returned SuccessFull', 'returnedBy' => Auth::guard('user')->user()];
    }

    public function updateEmploymentHistory(Request $request)
    {

        try {
            $employmentObject = new Guards\GuardEmploymentHistoryModel();
            $details = $employmentObject->updateDetails($request->all());

            return json_encode([
                'success' => 1,
                'data' => $details,
                'message' => 'Employment History Updated Successfully!'
            ]);
        } catch (Exception $exception) {
            return json_encode([
                'success' => 0,
                'data' => [],
                'message' => 'Something went wrong, Please Try again!'
            ]);
        }
    }

    public function updateJudicialCase(Request $request)
    {
        try {
            $caseObject = new Guards\GuardJudicialCaseModel();
            $details = $caseObject->updateDetails($request->all());

            return json_encode([
                'success' => 1,
                'data' => $details,
                'message' => 'Judicial Record Updated Successfully!'
            ]);
        } catch (Exception $exception) {
            return json_encode([
                'success' => 0,
                'data' => [],
                'message' => 'Something went wrong, Please Try again!'
            ]);
        }
    }

    public function updateFamilyMember(Request $request)
    {
        try {
            $caseObject = new Guards\GuardFamilyModel();
            $details = $caseObject->updateDetails($request->all());

            return json_encode([
                'success' => 1,
                'data' => $details,
                'message' => 'Family Member Details Updated Successfully!'
            ]);
        } catch (Exception $exception) {
            return json_encode([
                'success' => 0,
                'data' => [],
                'message' => 'Something went wrong, Please Try again!'
            ]);
        }
    }

    public function updateRelativeDetails(Request $request)
    {
        try {
            $caseObject = new Guards\GuardNearestRelativeModel();
            $details = $caseObject->updateDetails($request->all());

            return json_encode([
                'success' => 1,
                'data' => $details,
                'message' => 'Relative Details Updated Successfully!'
            ]);
        } catch (Exception $exception) {
            return json_encode([
                'success' => 0,
                'data' => [],
                'message' => 'Something went wrong, Please Try again!'
            ]);
        }
    }

    public function updateIntroducer(Request $request)
    {
        try {
            $caseObject = new Guards\Guards();
            $details = $caseObject->updateIntroducer($request->all());

            return json_encode([
                'success' => 1,
                'data' => $details,
                'message' => 'Introducer Details Updated Successfully!'
            ]);
        } catch (Exception $exception) {
            return json_encode([
                'success' => 0,
                'data' => [],
                'message' => 'Something went wrong, Please Try again!'
            ]);
        }
    }

    public function addGuardInBulk(Request $request)
    {

        if ($request->file) {
            $guardsModel = new Guards\Guards();
            $response = $guardsModel->saveGuardInBulk($request);
            if ($response) {
                return redirect()->back()->with('success_message', 'All Guards Saved Successfully');
            } else {
                return redirect()->back()->with('fail_message', 'Either File Format Is Wrong Or No Record To Be Saved');
            };

        } else {
            return 'no';;
        }
    }

    public function addGuardEmploymentHistoryInBulk(Request $request)
    {
        if ($request->file) {
            $guardsModel = new Guards\Guards();
            $response = $guardsModel->saveGuardEmploymentHistoryInBulk($request);
            if ($response) {
                return redirect()->back()->with('success_message', 'All Employment Histories Saved Successfully');
            } else {
                return redirect()->back()->with('fail_message', 'Either File Format Is Wrong Or No Record To Be Saved');
            };

        } else {
            return 'no';;
        }
    }

    public function addGuardRelativesInBulk(Request $request)
    {
        if ($request->file) {
            $guardsModel = new Guards\Guards();
            $response = $guardsModel->saveGuardRelativesInBulk($request);
            if ($response) {
                return redirect()->back()->with('success_message', 'All Relatives Information Saved Successfully');
            } else {
                return redirect()->back()->with('fail_message', 'Either File Format Is Wrong Or No Record To Be Saved');
            };

        } else {
            return 'no';;
        }
    }

    public function addGuardFamilyInBulk(Request $request)
    {
        if ($request->file) {
            $guardsModel = new Guards\Guards();
            $response = $guardsModel->saveGuardFamilyInBulk($request);
            if ($response) {
                return redirect()->back()->with('success_message', 'All Family Information Saved Successfully');
            } else {
                return redirect()->back()->with('fail_message', 'Either File Format Is Wrong Or No Record To Be Saved');
            };

        } else {
            return 'no';;
        }
    }

    public function addGuardJudicialCasesInBulk(Request $request)
    {
        if ($request->file) {
            $guardsModel = new Guards\Guards();
            $response = $guardsModel->saveGuardJudicialCasesInBulk($request);
            if ($response) {
                return redirect()->back()->with('success_message', 'All Judicial Cases Information Saved Successfully');
            } else {
                return redirect()->back()->with('fail_message', 'Either File Format Is Wrong Or No Record To Be Saved');
            };

        } else {
            return 'no';;
        }
    }

    public function markAttendanceInBulkForm(Request $request)
    {


        if ($request->file) {
            $guardAttendanceModel = new Guards\GuardAttendance();
//            $response = $guardAttendanceModel->markAttendanceInBulkForm($request);
            $response = $guardAttendanceModel->markAttendanceInBulkFormNew($request);
            if ($response) {
                return redirect()->back()->with('success_message', 'All Attendances Marked Successfully');
            } else {
                return redirect()->back()->with('fail_message', 'Either File Format Is Wrong Or No Record To Be Saved');
            };

        } else {
            return 'no';;
        }
    }

    public function addPledgedDocumentInBulk(Request $request)
    {
        if ($request->file) {
            $guardPledgedDocumentModel = new Guards\GuardPledgedDocument();
            $response = $guardPledgedDocumentModel->pledgeDocumentInBulk($request);
            if ($response) {
                return redirect()->back()->with('success_message', 'All Documents Pledged Successfully');
            } else {
                return redirect()->back()->with('fail_message', 'Either File Format Is Wrong Or No Record To Be Saved');
            };

        } else {
            return 'no';;
        }
    }

    public function addVerificationsInBulk(Request $request)
    {

        if ($request->file) {
            $guardVerificationsModel = new Guards\GuardVerificationModel();
            $response = $guardVerificationsModel->addVerificationsInBulk($request);
            if ($response) {
                return redirect()->back()->with('success_message', 'All Verifications Added Successfully');
            } else {
                return redirect()->back()->with('fail_message', 'Either File Format Is Wrong Or No Record To Be Saved');
            };

        } else {
            return 'no';;
        }
    }

    public function allGuardsVerifications(Request $request)
    {
        $guardVerificationModel = new Guards\GuardVerificationModel();
        $guardModel = new Guards\Guards();
        $allGuardsVerifications = $guardVerificationModel->getAllWithPagination(10);
        $data = array('allGuardsVerifications' => $allGuardsVerifications);


        return view('guards.allGuardsVerifications')->with($data);
    }

    public function blackListedGuards(Request $request)
    {
        $userModel = new UserModel();
        $blackListedGuardModel = new Guards\BlackListedGuards();
        $blackListedGuards = $blackListedGuardModel->getAll();
        $data = array(
          $blackListedGuards,$userModel
          // 'blackListedGuards' => $blackListedGuards,
          //  'userModel' => $userModel
          );

        return ($data);
        // return view('guards.blackListedGuard')->with($data);
    }

    public function addNewGuardCnicInBlackList(Request $request)
    {
        $blackListedGuardModel = new Guards\BlackListedGuards();
        $response = $blackListedGuardModel->addNewCnicInBlackList($request->cnic_no_to_blacklist);
        return $response;
    }

    public function removeGuardIdFromBlackList(Request $request)
    {
        $blackListedGuardModel = new Guards\BlackListedGuards();
        $response = $blackListedGuardModel->deleteByModelId($request->blackListedId);
        return $response;
    }

    public function deactivateGuardAsSystemUser(Request $request)
    {
        $guardId = $request->guard_id;
        $guardAsSystemUser = new Guards\GuardAsSystemUser();
        $response = $guardAsSystemUser->deactivateGuardSystemRole($guardId);


        return ['responseCode' => 1, 'responseStatus' => 'Successful', 'message' => 'Guard Role Updated Successfully', 'data' => $response];
    }

    public function activateGuardAsSystemUser(Request $request)
    {
        $guardId = $request->guard_id;
        $guardAsSystemUser = new Guards\GuardAsSystemUser();
        $response = $guardAsSystemUser->activateOldModel($guardId);
        return ['responseCode' => 1, 'responseStatus' => 'Successful', 'message' => 'Guard Role Updated Successfully', 'data' => $response];
    }

    public function seeIsStatusColorIsFree(Request $request)
    {
        $color = $request->selectedColorId;
        $guardStatusModel = new Guards\GuardStatusesModel();
        $response = $guardStatusModel->getModelByColorId($color);
        if (!$response) {
            return ['responseCode' => 1, 'responseStatus' => 'Successful', 'message' => 'Color Is Available'];
        } else {
            return ['responseCode' => 0, 'responseStatus' => 'Fail', 'message' => 'Color Is Already Used Against Some Status'];
        }

    }

    public function updatedColorOfStatus(Request $request)
    {
        $statusId = $request->status_id;
        $colorId = $request->color_id;

        $guardStatusModel = new Guards\GuardStatusesModel();
        $response = $guardStatusModel->updatedColorOfStatus($statusId, $colorId);

        $statusColorModel = new Guards\GuardStatusColors();
        $updatedColor = $statusColorModel->getModelById($colorId)->value;
        return ['responseCode' => 1, 'responseStatus' => 'Successful', 'message' => 'Color of Status Updated Successfully', 'data' => $updatedColor];
    }

    public function acceptedRejectedByCol(Request $request)
    {

        $guardsModel = new Guards\Guards();
        $allPendingGuards = $guardsModel->getAllGuardsByColStatusId(1);
        $allAcceptedGuards = $guardsModel->getAllGuardsByColStatusId(2);
        $allRejectedGuards = $guardsModel->getAllGuardsByColStatusId(3);
        $ageLimit = new SettingsModel();
        $ageLimit = $ageLimit->pluck('guard_max_age');
        foreach ($allPendingGuards as $guard)
        {
            if($guard->age >= $ageLimit[0])
            {
                $guard->over_age = 1;
            }
            else
            {
                $guard->over_age = 0;
            }
        }
        foreach ($allAcceptedGuards as $guard)
        {
            if($guard->age >= 55)
            {
                $guard->over_age = 1;
            }
            else
            {
                $guard->over_age = 0;
            }
        }
        foreach ($allRejectedGuards as $guard)
        {
            if($guard->age >= 55)
            {
                $guard->over_age = 1;
            }
            else
            {
                $guard->over_age = 0;
            }
        }

        $data = array(
            'allPendingGuards' => $allPendingGuards,
            'allAcceptedGuards' => $allAcceptedGuards,
            'allRejectedGuards' => $allRejectedGuards
        );

        return view('guards.acceptedRejectedGuardsByCol')->with($data);
    }

    public function updateStatusByCol(Request $request)
    {
        $guardId = $request->guard_id;
        $statusId = $request->status_id;
        $comment = $request->comment;

        $guardModel = new Guards\Guards();
        $guardStatusByColModel = new Guards\GuardStatusByColModel();


        $guardToUpdate = $guardModel->guardDetail($guardId);
        $guardToUpdate->status_by_col = $statusId;
        $guardToUpdate->comment_by_col = $comment;

        $updatedStatus = $guardStatusByColModel->getModelById($statusId)->name;
        $guardToUpdate->save();

        return ['responseCode' => 1, 'responseStatus' => 'Successful', 'message' => 'Status Updated Successfully', 'data' => ['status' => $updatedStatus, 'comment' => $comment]];


    }
    public function mergedOptions(Request $request)
    {




        $guard = new Guards\GuardsPrerequisite();
        $prerequisite = $guard->prerequisite();




        $guardColorStatusModel = new Guards\GuardStatusColors();

        $allGuardStatusesColors = $guardColorStatusModel->getAll();
        $all_statuses = new Guards\GuardStatusesModel();
        $all_statuses = $all_statuses->getAll();

        $salaryCategoryModel = new Guards\GuardSalaryCategoriesModel();
        $allSalaryCategories = $salaryCategoryModel->getAll();


        $allDocumentsType = new DocumentType();
        $allDocumentsType = $allDocumentsType->getAll();

        $data = array(
            // 'prerequisite'=>$prerequisite.","$all_statuses,
            // 'all_statuses' => $all_statuses,
            // 'allGuardStatusesColors' => $allGuardStatusesColors,
            // 'allSalaryCategories' => $allSalaryCategories,
            // 'allDocumentsType' => $allDocumentsType
            // $all_statuses,$allSalaryCategories,$allDocumentsType,$prerequisite,$allGuardStatusesColors
            // $all_statuses,$allSalaryCategories,$allDocumentsType,$prerequisite,$allGuardStatusesColors
            $prerequisite,$all_statuses,$allGuardStatusesColors,$allSalaryCategories,$allDocumentsType
        );

        return ($data);
    }

    public function deleteGuardModelSoftly(Request $request)
    {
        $guardModel = new Guards\Guards();
        $guardUpdateHistoryModel = new Guards\GuardUpdateHistoryModel();

        $response = $guardModel->softDeleteModelById($request->guard_id);
        if($response)
        {

            $guardUpdateHistoryModel->saveModel($request->guard_id,1,0,$request->deleted_date);

            return ['responseCode'=>1, 'responseStatus'=>'Successful', 'message'=>'Guard Deleted Successfully','data'=>'null'];
        }else
        {
            return ['responseCode'=>0, 'responseStatus'=>'Failed', 'message'=>'Failed To Delete Guard','data'=>'null'];
        }
    }

    public function personalVerificationGuardGuarantors($guardId)
    {
        $guard_details = new Guards\Guards();
        $guard_details  = $guard_details::find($guardId);
        $nearest_relatives = new Guards\GuardNearestRelativeModel();
        $nearest_relatives = $nearest_relatives::where('guard_id' , $guardId)->get();
        $data = array('guard_details' => $guard_details,
            'nearest_relatives' => $nearest_relatives);
        $paper_size = array(0, 0, 1250, 1500);
        $pdf = PDF::loadView('pdf.personal_verification_guard_guarantors', $data)->setPaper($paper_size, 'portrait');
        return $pdf->stream();
    }
    public function downloadPersonalVerificationGuardGuarantors($guardId)
    {
        $guard_details = new Guards\Guards();
        $guard_details  = $guard_details::find($guardId);
        $nearest_relatives = new Guards\GuardNearestRelativeModel();
        $nearest_relatives = $nearest_relatives::where('guard_id' , $guardId)->get();
        $data = array('guard_details' => $guard_details,
            'nearest_relatives' => $nearest_relatives);
        $paper_size = array(0, 0, 1250, 1500);
        $pdf = PDF::loadView('pdf.personal_verification_guard_guarantors', $data)->setPaper($paper_size, 'portrait');


        return $pdf->download($guardId . 'personal_verification_guard_guarantors.pdf');

//        return $pdf->download($guardId.'form_A.pdf');

    }
    public function trainingCertificate($guardId)
    {

        $guard_details = new Guards\Guards();
        $guard_details  = $guard_details::find($guardId);
        $data = array('guard_details' => $guard_details);
//        dd($guard_details['parwest_id']);
        $pdf = PDF::loadView('pdf.trainingCertificate', $data)->setPaper('A4', 'landscape');
        return $pdf->stream();
//return view('pdf.trainingCertificate', $data);

    }
    public function downloadTrainingCertificate($guardId)
    {

        $guard_details = new Guards\Guards();
        $guard_details  = $guard_details::find($guardId);
        $data = array('guard_details' => $guard_details);
//        return view('pdf.trainingCertificate')->with($data);
        $pdf = PDF::loadView('pdf.trainingCertificate', $data)->setPaper('A4', 'landscape');

        return $pdf->download($guardId . '_training_certificate.pdf');


    }

    public function guardMentalHealthForm()
    {
        $settingsModel = new SettingsModel();
        $allSettings = $settingsModel->getAllSettings();

        return view('guards.mentalHealthForm')->with('allSettings', $allSettings);
    }
    public function updateguardMentalHealthForm(Request $request)
    {

        $value = $request->guardmhrecheck;
        $settingsModel = new SettingsModel();
        $response = $settingsModel->first();
        $response->mental_health_recheck_months = $value;
        $response->save();

        return redirect()->back()->with('successful', 'Value Updated Successfully');
    }



    public function characterCertificate($guardId)
    {

        $guard_details = new Guards\Guards();
        $guard_details  = $guard_details::find($guardId);
        $data = array('guard_details' => $guard_details);
        $pdf = PDF::loadView('pdf.characterCertificate', $data)->setPaper('A4', 'portrait');
        return $pdf->stream();


    }
    public function downloadCharacterCertificate($guardId)
    {
        $guard_details = new Guards\Guards();
        $guard_details  = $guard_details::find($guardId);
        $data = array('guard_details' => $guard_details);
        $pdf = PDF::loadView('pdf.characterCertificate', $data)->setPaper('A4', 'portrait');

        return $pdf->download($guardId . '_character_certificate.pdf');


    }


    public function checklistOfGuardDocument($guardId)
    {

        $guard_details = new Guards\Guards();
        $guard_details  = $guard_details::find($guardId);
        $ex_services = new Guards\GuardExServices();
        $ex_services = $ex_services::find($guard_details->ex);
        if($guard_details->ex == 1 )
        {
            $ex_services->name = $guard_details->other;
        }
        $data = array('guard_details' => $guard_details,
            'ex_services' =>$ex_services);
        $pdf = PDF::loadView('pdf.checklistOfGuardDocument', $data)->setPaper('A4', 'portrait');
        return $pdf->stream();


    }
    public function downloadChecklistOfGuardDocument($guardId)
    {
        $guard_details = new Guards\Guards();
        $guard_details  = $guard_details::find($guardId);
        $ex_services = new Guards\GuardExServices();
        $ex_services = $ex_services::find($guard_details->ex);
        if($guard_details->ex == 1 )
        {
            $ex_services->name = $guard_details->other;
        }
        $data = array('guard_details' => $guard_details,
            'ex_services' =>$ex_services);
        $pdf = PDF::loadView('pdf.checklistOfGuardDocument', $data)->setPaper('A4', 'portrait');

        return $pdf->download($guardId . '_document_check_list.pdf');


    }

    public function medicalCertificateOfGuard($guardId)
    {
        $guard_details = new Guards\Guards();
        $guard_details  = $guard_details::find($guardId);
        $data = array('guard_details' => $guard_details);
        $pdf = PDF::loadView('pdf.medicalCertificate', $data)->setPaper('A4', 'portrait');
        return $pdf->stream();
    }
    public function downloadMedicalCertificateOfGuard($guardId)
    {
        $guard_details = new Guards\Guards();
        $guard_details  = $guard_details::find($guardId);
        $data = array('guard_details' => $guard_details);
        $pdf = PDF::loadView('pdf.medicalCertificate', $data)->setPaper('A4', 'portrait');

        return $pdf->download($guardId . '_medical_certificate_form.pdf');
    }

    public function guardAntecedentsVerification($guardId)
    {
        $guard_details = new Guards\Guards();
        $guard_details  = $guard_details::find($guardId);
        $data = array('guard_details' => $guard_details);
        $pdf = PDF::loadView('pdf.guardAntecedentsVerification', $data)->setPaper('A4', 'portrait');
        return $pdf->stream();
    }
    public function downloadGuardAntecedentsVerification($guardId)
    {
        $guard_details = new Guards\Guards();
        $guard_details  = $guard_details::find($guardId);
        $data = array('guard_details' => $guard_details);
        $pdf = PDF::loadView('pdf.guardAntecedentsVerification', $data)->setPaper('A4', 'portrait');

        return $pdf->download($guardId . '_guard_antecedents_verification.pdf');
    }


    public function iqrarNamaDefault($guardId)
    {
        $guard_details = new Guards\Guards();
        $guard_details  = $guard_details::find($guardId);
        $data = array('guard_details' => $guard_details);
//        $data = array('asd',1);
        $pdf = PDF::loadView('pdf.iqrarName', $data)->setPaper('A4', 'portrait');
        return $pdf->stream();
//        return view('pdf.iqrarName')->with($data);
    }
//    public function iqrarNamaDefault($guardId)
//    {
//        $guard = new Guards\Guards();
//        $guard = $guard->guardDetail($guardId);
//
//        $designationModel = new Guards\GuardDesignationModel();
//        $designationModel = $designationModel->getModelById($guard->designation);
//
//        $guardDesignation = $designationModel->name;
//        $bloodGroupModel = new Mix\BloodGroupsModel();
//        $guardBloodGroup = 'Not Defined';
//        if ($guard->blood_group_id) {
//            $guardBloodGroup = $bloodGroupModel->getModelById($guard->blood_group_id)->name;
//        }
//
//
//        $paper_size = array(0, 0, 1000, 1500);
////        $pdf = PDF::loadView('pdf.iqrar', array('guard' => $guard, 'guardDesignation' => $guardDesignation, 'guardBloodGroup' => $guardBloodGroup))->setPaper($paper_size, 'portrait');
////        return $pdf->stream();
//    }downloadTrainingCertificate
    public function downloadIqrarNamaDefault($guardId)
    {
        $guard_details = new Guards\Guards();
        $guard_details  = $guard_details::find($guardId);
        $data = array('guard_details' => $guard_details);
        $pdf = PDF::loadView('pdf.iqrarName', $data)->setPaper('A4', 'portrait');

        return $pdf->download($guardId . '_iqrarName.pdf');
    }

    public function undoSoftDeletedGuard(Request $request)
    {
        $guardId = $request->guard_id;
        $guardUpdateHistoryModel = new Guards\GuardUpdateHistoryModel();

        $response =DB::update('update guards set deleted_at = null where id = ?', [$guardId]);

        $guardUpdateHistoryModel->saveModel($guardId,0,1,$request->activation_date);

        return ['responseCode'=>1, 'responseStatus'=>'Successful', 'message'=>'Guard Activated Successfully', 'data'=>null];

    }

    public function softDeletedGuardList(Request $request)
    {
        $guardModel = new Guards\Guards();

        $softDeletedGuards = $guardModel->allNonDeletedGuards();

        $data = array('softDeletedGuards'=>$softDeletedGuards);

        return ($softDeletedGuards);
    }

    public function isGuardAlreadyExistsById(Request $request)
    {
        $guardModel = new Guards\Guards();

        $cnic = $request->cnic_no;
        $response = $guardModel->isGuardExistWithoutTerminate($cnic);
        if($response)
        {
            return ['responseCode'=>1, 'responseStatus'=>'Successful','message'=>'Guard Already Exists With Provided CNIC'];
        }
        else
        {
            return ['responseCode'=>0, 'responseStatus'=>'Failed','message'=>'No Guard Exists With Provided CNIC'];
        }
    }

    public function checkIsRegionalOfficeExists(Request $request)
    {

        $name = $request->office_head;
        $shortName = $request->office_head_short_name;

        $regionalOfficeModel = new Guards\RegionalOfficeModel();

        $isExistsByName = $regionalOfficeModel->isRegionalOfficeExistsByOfficeHead($name);
//        $isExistsByShortName = $regionalOfficeModel->isRegionalOfficeExistsByOfficeShortName($shortName);
//        dd($isExistsByName);





        if($isExistsByName)
        {
            return ['responseCode'=>1, 'responseData'=>'Successful', 'message'=>'Regional Office Exists By Name Or Short Name'];
        }
        else
        {
            return ['responseCode'=>0, 'responseData'=>'Failed', 'message'=>'No Regional Office Exists By Name Or Short Name'];
        }
    }

    public function checkIfParawestIdAvailable (Request $request)
    {
        $parwest = new Guards\Guards();
        $parwestId = $parwest::where('parwest_id','=',$request->id)->pluck('parwest_id');
//        dd($parwestId);
        if(isset($parwestId[0]) )
        {
            if ( $parwestId[0]  == $request->id  )
            {
                return ['responseCode'=>0, 'responseData'=>'Failed', 'message'=>'Parwest ID Already Exists'];
            }

        }
        else
        {
            $arrayOfValidation =  array () ;
            $arrayOfValidation = explode('-',$request->id);
            try {
                $shortNames = Guards\RegionalOfficeModel::where('short_name', $arrayOfValidation[0])->pluck('id');
                if(isset($arrayOfValidation[1]))
                {
                    $output = preg_match("/[a-z]/i", $arrayOfValidation[1] );
                    if($output == 1)
                    {
                        return ['responseCode'=>0, 'responseData'=>'Failed', 'message'=>'Parwest ID Is not Valid!'];
                    }
                }


            }catch(Exception $e)
            {
                return ['responseCode'=>0, 'responseData'=>'Failed', 'message'=>'Parwest ID Is not Valid!'];
            }


            return ['responseCode'=>1, 'responseData'=>'Successful', 'message'=>'Successful' , 'regional_id'=>$shortNames];
        }
    }

    public function getClients ()
    {
      $allClients = new Clients();
      $allClients = $allClients->getAllClientsWithoutPaginate();

      return $allClients;
    }
    public function GuardDeployment ($client=NULL, $branch=NULL)
    {
        //used if a clients click on its branch on route client/show/{id}
        $clientId = $client;
        $branchId = $branch;



        $user_id = Auth::guard('user')->id();


        $userModel = new UserModel();
        $user = $userModel->getUserById($user_id);

        $guard_under_user = new Guards\Guards();
        $guard_under_user = $guard_under_user->getGuardsBySupervisor($user_id);


        $allAttendanceModel = new Guards\GuardAttendance();
//        $allAttendanceYears = $allAttendanceYears->getAllActiveAttendanceYears();

        $guardAttendanceYearModel = new Guards\GuardAttendanceYearsModel();
        $allAttendanceYears = $guardAttendanceYearModel->getAll();

        $dashboardOptionsByUserRoleModel = new DashboardOptionsByUserRoleModel();
        $dashboardOptionsByUserRole = $dashboardOptionsByUserRoleModel->getAllByUserRoleId($user->role_id);

        $guardDesignationModel = new Guards\GuardDesignationModel();
        $allGuardDesignations = $guardDesignationModel->getAll();


        $month_names = ['january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december'];


        $allClients = new Clients();
        $allClients = $allClients->getAllClientsWithoutPaginate();


        $clientUserAssociationModel = new ClientUserAssociationModel();
        $assignedClients = $clientUserAssociationModel->getClientsAssignedToUser($user_id);


        $assignedBranches = $clientUserAssociationModel->getBranchesAssignedToUser($user_id);


        $currentlyAssignedTickets = new TicketAssignHistoryModel();
        $currentlyAssignedTickets = $currentlyAssignedTickets->currentlyAssignedTicketsToUser($user_id);


//            SELECT * from guards where id NOT IN ( SELECT guard_id from client_guard_association WHERE is_currently_deployed = 1 )


        $mailQueuesBYUserModel = new MailQueuesByUsersModel();
        $userAllMailQueues = $mailQueuesBYUserModel->getAllByUserId($user_id);


        $managerSupervisorAssociationModel = new ManagerSupervisorAssociation();
        $allCurrentlyAssignedSupervisorsToUser = $managerSupervisorAssociationModel->getAllActiveAssociationsByManagerId($user_id);


        $clientGuardAssociationModel = new ClientGuardsAssociation();
        $userPersonalInformationModel = new UserPersonalInformationModel();
        $rolesModel = new Guards\GuardRoles();

        $dashboardSubOptionsModel = new DashboardSubOptionsModel();
        $dashboardChoicesByUsersModel = new DashboardOptionsByUsersModel();


        $userPersonalInformation = $userPersonalInformationModel->getModelByUserId($user_id);
        $already_marked_dates = $allAttendanceModel->allAttendanceMarkedDatesByUser();


        $dashboardAllSubOptions = $dashboardSubOptionsModel->getAllModelsWithMainOptionsName();
        $allDashboardOptionsByUser = $dashboardChoicesByUsersModel->getAllByUserId($user_id);


        $minPasswordLength = Config::get('globalvariables.$minPasswordLength');
        $maxPasswordLength = Config::get('globalvariables.$maxPasswordLength');

        $ticketModel = new Ticket();
        $ticketStatusModel = new Status();

        $allExServices = new Guards\GuardExServices();
        $allExServices = $allExServices->getAll();
        $data = array(
            'userModel' => $userModel,
            'user' => $user,
            'guard_under_user' => $guard_under_user,
            'allAttendanceYears' => $allAttendanceYears,
            'month_names' => $month_names,
            'allClients' => $allClients,
//            'allNonDeployedGuards' => $guard_under_user,
            'assignedClients' => $assignedClients,
            'currentlyAssignedTickets' => $currentlyAssignedTickets,
            'ticketModel' => $ticketModel,
            'ticketStatusModel' => $ticketStatusModel,
            'userAllMailQueues' => $userAllMailQueues,
            'mailQueuesBYUserModel' => $mailQueuesBYUserModel,
            'allCurrentlyAssignedSupervisorsToUser' => $allCurrentlyAssignedSupervisorsToUser,
            'minPasswordLength' => $minPasswordLength,
            'maxPasswordLength' => $maxPasswordLength,
            'clientGuardAssociationModel' => $clientGuardAssociationModel,
            'assignedBranches' => $assignedBranches,
            'allAttendanceModel' => $allAttendanceModel,
            'userPersonalInformation' => $userPersonalInformation,
            'already_marked_dates' => $already_marked_dates,
            'rolesModel' => $rolesModel,

            'dashboardAllSubOptions' => $dashboardAllSubOptions,
            'allDashboardOptionsByUser' => $allDashboardOptionsByUser,
            'dashboardChoicesByUsersModel' => $dashboardChoicesByUsersModel,
            'dashboardOptionsByUserRole' => $dashboardOptionsByUserRole,
            'allGuardDesignations' => $allGuardDesignations,
            'allExServices' =>$allExServices,

        );


        return view('guards.deployGuard')->with($data)->with('clientId',$clientId)->with('branchId',$branchId);

    }
    public function GuardDeploymentRate ($client=NULL, $branch=NULL)
    {
        //used if a clients click on its branch on route client/show/{id}
        $clientId = $client;
        $branchId = $branch;



        $user_id = Auth::guard('user')->id();


        $userModel = new UserModel();
        $user = $userModel->getUserById($user_id);

        $guard_under_user = new Guards\Guards();
        $guard_under_user = $guard_under_user->getGuardsBySupervisor($user_id);


        $allAttendanceModel = new Guards\GuardAttendance();
//        $allAttendanceYears = $allAttendanceYears->getAllActiveAttendanceYears();

        $guardAttendanceYearModel = new Guards\GuardAttendanceYearsModel();
        $allAttendanceYears = $guardAttendanceYearModel->getAll();

        $dashboardOptionsByUserRoleModel = new DashboardOptionsByUserRoleModel();
        $dashboardOptionsByUserRole = $dashboardOptionsByUserRoleModel->getAllByUserRoleId($user->role_id);

        $guardDesignationModel = new Guards\GuardDesignationModel();
        $allGuardDesignations = $guardDesignationModel->getAll();


        $month_names = ['january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december'];


        $allClients = new Clients();
        $allClients = $allClients->getAllClientsWithoutPaginate();


        $allDesignation = new GuardDesignationModel();
        $allDesignation = $allDesignation->getAll();

//        $allExServices = new GuardExServices();
//        $allExServices = $allExServices->getAll();


        $clientUserAssociationModel = new ClientUserAssociationModel();
        $assignedClients = $clientUserAssociationModel->getClientsAssignedToUser($user_id);


        $assignedBranches = $clientUserAssociationModel->getBranchesAssignedToUser($user_id);


        $currentlyAssignedTickets = new TicketAssignHistoryModel();
        $currentlyAssignedTickets = $currentlyAssignedTickets->currentlyAssignedTicketsToUser($user_id);


//            SELECT * from guards where id NOT IN ( SELECT guard_id from client_guard_association WHERE is_currently_deployed = 1 )


        $mailQueuesBYUserModel = new MailQueuesByUsersModel();
        $userAllMailQueues = $mailQueuesBYUserModel->getAllByUserId($user_id);


        $managerSupervisorAssociationModel = new ManagerSupervisorAssociation();
        $allCurrentlyAssignedSupervisorsToUser = $managerSupervisorAssociationModel->getAllActiveAssociationsByManagerId($user_id);


        $clientGuardAssociationModel = new ClientGuardsAssociation();
        $userPersonalInformationModel = new UserPersonalInformationModel();
        $rolesModel = new Guards\GuardRoles();

        $dashboardSubOptionsModel = new DashboardSubOptionsModel();
        $dashboardChoicesByUsersModel = new DashboardOptionsByUsersModel();


        $userPersonalInformation = $userPersonalInformationModel->getModelByUserId($user_id);
        $already_marked_dates = $allAttendanceModel->allAttendanceMarkedDatesByUser();


        $dashboardAllSubOptions = $dashboardSubOptionsModel->getAllModelsWithMainOptionsName();
        $allDashboardOptionsByUser = $dashboardChoicesByUsersModel->getAllByUserId($user_id);


        $minPasswordLength = Config::get('globalvariables.$minPasswordLength');
        $maxPasswordLength = Config::get('globalvariables.$maxPasswordLength');

        $ticketModel = new Ticket();
        $ticketStatusModel = new Status();

        $allExServices = new Guards\GuardExServices();
        $allExServices = $allExServices->getAll();
        $data = array(
            'userModel' => $userModel,
            'user' => $user,
            'guard_under_user' => $guard_under_user,
            'allAttendanceYears' => $allAttendanceYears,
            'month_names' => $month_names,
            'allClients' => $allClients,
            'allDesignation' => $allDesignation,
//            'allNonDeployedGuards' => $guard_under_user,
            'assignedClients' => $assignedClients,
            'currentlyAssignedTickets' => $currentlyAssignedTickets,
            'ticketModel' => $ticketModel,
            'ticketStatusModel' => $ticketStatusModel,
            'userAllMailQueues' => $userAllMailQueues,
            'mailQueuesBYUserModel' => $mailQueuesBYUserModel,
            'allCurrentlyAssignedSupervisorsToUser' => $allCurrentlyAssignedSupervisorsToUser,
            'minPasswordLength' => $minPasswordLength,
            'maxPasswordLength' => $maxPasswordLength,
            'clientGuardAssociationModel' => $clientGuardAssociationModel,
            'assignedBranches' => $assignedBranches,
            'allAttendanceModel' => $allAttendanceModel,
            'userPersonalInformation' => $userPersonalInformation,
            'already_marked_dates' => $already_marked_dates,
            'rolesModel' => $rolesModel,

            'dashboardAllSubOptions' => $dashboardAllSubOptions,
            'allDashboardOptionsByUser' => $allDashboardOptionsByUser,
            'dashboardChoicesByUsersModel' => $dashboardChoicesByUsersModel,
            'dashboardOptionsByUserRole' => $dashboardOptionsByUserRole,
            'allGuardDesignations' => $allGuardDesignations,
            'allExServices' =>$allExServices,

        );


        return view('guards.deployGuardRate')->with($data)->with('clientId',$clientId)->with('branchId',$branchId);

    }
    public function getGuardForExtraHour(Request $request){
        $guard = new Guards\Guards();
        $guard = $guard::where('parwest_id',$request->parwest_id)->first();

        $guard_status = new Guards\GuardStatusesModel();
        $guard_status = $guard_status->find($guard->current_status_id);
        $guard->status_name = $guard_status->value;

        // $location = new ClientGuardsAssociation();
        // $location = $location->getLocationByGuardId($guard->id);
        // $guard->location = $location->client_name." ".$location->branch_name;
        // $allDesignations = new Guards\GuardDesignationModel();
        // $allDesignations = $allDesignations->getAll();

        // foreach($allDesignations as $allDes)
        // {
        //     if($allDes->id == $guard->designation)
        //     {
        //         $guard->designation_name = strtoupper($allDes->name);
        //     }
        // }

        return ($guard);

    }
    public function getGuardForSpecialDuty(Request $request){
        $guard = new Guards\Guards();
        $guard = $guard::where('parwest_id',$request->parwest_id)->first();

        $guard_status = new Guards\GuardStatusesModel();
        $guard_status = $guard_status->find($guard->current_status_id);
        $guard->status_name = $guard_status->value;

        $allDesignations = new Guards\GuardDesignationModel();
        $allDesignations = $allDesignations->getAll();

        foreach($allDesignations as $allDes)
        {
            if($allDes->id == $guard->designation)
            {
                $guard->designation_name = strtoupper($allDes->name);
            }
        }

        return (['responseCode' => 1 , 'message' => 'Guard Retrieved Successfully' , 'data'=>$guard]);

    }
    public function getPayrollSalaryMonth(Request $request){
        $input = $request->all();
//        return $input['from_date'];
        $input_from = $input['date'];
//        $input_to = $input['to_date'];
        $month_from = $this->getMonthByDate($input_from);
        $year_from = $this->getYearByDate($input_from);

        $getSalaryMonth = new Guards\PayrollSalaryMonth();
        $getSalaryMonth = $getSalaryMonth->getAll();
//        return $getSalaryMonth['salary_month'];
//        return $year_from;
//        return $month_from;
        $salary_year = substr($getSalaryMonth['salary_year'], -2);
        $salary_month = $getSalaryMonth['salary_month'];
//        return 'month from' .$month_from;
        if($year_from == $salary_year){
            if($month_from == $salary_month){

                return 'success';
            }else{
                return 'fail';
            }
        }else{
            return 'fail';
        }
//        if($year_from == $salary_year &&  $month_from ==  $getSalaryMonth['salary_month']){
////        return $salary_year;
//            return 'success';
//        }else{
//            return 'fail';
//        }
//
//
//        if()


    }
    public function getGuardDetailsAJAXLoan (Request $request)
    {


        $ex_services = null;
        $guard = new Guards\Guards();
        $guard_status = new Guards\GuardStatusesModel();

        $guard = $guard::where('parwest_id',$request->parwest_id)->first();
        if(isset($guard->id))
        {

            $guard_status = $guard_status->find($guard->current_status_id);
            $guard->status_name = $guard_status->value;
            $ex_services = $guard->ex;

            return (['responseCode' => 1 , 'message' => 'Guard Retrieved Successfully' , 'data'=>$guard ,'ex' =>$ex_services]);
        }
        else
        {
            $guards = new Guards\Guards();
            $guards = $guards::where('parwest_id',$request->parwest_id)->first();
            if(isset($guards->id))
            {
                return (['responseCode' => 0 , 'message' => 'Guard is not present' , 'data'=>null]);
            }
            else
            {
                return (['responseCode' => 0 , 'message' => 'No Guard Found' , 'data'=>null]);
            }

        }
    }
    public function getGuardDetailsAJAX (Request $request)
    {

//        dd($request->all());
        $client_ = $request->client_id;
        $branch_ = $request->branch_id;
        $designation_ = $request->designation;
        $salary = new ClientBranchWiseGuardSalary();
        $ex_services = null;
        $guard = new Guards\Guards();
        $guard_status = new Guards\GuardStatusesModel();
//        $guard = $guard::where('parwest_id',$request->parwest_id) //Current Status of present Guards
//            ->where('current_status_id',1)->first();
        $guard = $guard::where('parwest_id',$request->parwest_id)->first();
        if(isset($guard->id))
        {
            $guardInventoryCount = new ClientGuardsAssociation();
            $guardInventoryCount = $guardInventoryCount->getInventoryByGuardId($guard->id);
            if($guardInventoryCount){
                $guard->inventory_count =  $guardInventoryCount[0]->totalitems;
            }
            $guard_status = $guard_status->find($guard->current_status_id);
            $guard->status_name = $guard_status->value;
            $ex_services = $guard->ex;
            $clientGuardAssociation = new ClientGuardsAssociation();
            $clientGuardAssociation =  $clientGuardAssociation::where('guard_id', '=', $guard->id)->where('end_date', '=', null)->get();
            if(isset($clientGuardAssociation[0]->client_id))
            {

                foreach($clientGuardAssociation as $association)
                {
                    $clients = new Clients();
                    $branches = new ClientBranchesModel();
                    $clients = $clients::find($association->client_id);
                    $branches = $branches::find($association->branch_id);
                    $association->client_name = $clients->name;
                    $association->branch_name = $branches->name;
                    if($association->is_overtime ==0)
                    {
                        $association->duty = 'Regular';
                    }
                    else
                    {
                        $association->duty = 'Double';
                    }
                    if($association->shift_day_night == 1)
                    {
                        $association->shift_time = 'Day';
                    }
                    else
                    {
                        $association->shift_time = 'Night';
                    }
                }
            }
            $allDesignations = new Guards\GuardDesignationModel();
            $allDesignations = $allDesignations->getAll();
            $allExServices = new Guards\GuardExServices();
            $allExServices = $allExServices->getAll();
            foreach($allDesignations as $allDes)
            {
                if($allDes->id == $guard->designation)
                {
                    $guard->designation_name = strtoupper($allDes->name);
                }
            }
            foreach($allExServices as $allEx)
            {
                if($allEx->id == $guard->ex)
                {
                    $guard->ex_name= strtoupper($allEx->name);
                }
            }


            $salary = $salary->getSalaryAgainstBranchAndGuardType($client_, $branch_ , $designation_ , $ex_services ,$request->shift);



            return (['responseCode' => 1 , 'message' => 'Guard Retrieved Successfully' , 'data'=>$guard , 'GuardAssociation' => $clientGuardAssociation , 'salary' => $salary ,'ex' =>$ex_services]);
        }
        else
        {
            $guards = new Guards\Guards();
            $guards = $guards::where('parwest_id',$request->parwest_id)->first();
            if(isset($guards->id))
            {
                return (['responseCode' => 0 , 'message' => 'Guard is not present' , 'data'=>null]);
            }
            else
            {
                return (['responseCode' => 0 , 'message' => 'No Guard Found' , 'data'=>null]);
            }

        }
    }
    public function GuardDeploymentRateUpdate (Request $request)
    {
//        dd($request);
        $shift = $request->shift;
        if($shift == 2){
            $salary_exists = new ClientBranchWiseGuardSalary();
            $salary_exists = $salary_exists::where('client_id',$request->client_id_on_user_profile)
                ->where('branch_id',$request->branch_id_on_user_profile)
                ->where('guard_type',$request->deployGuardAsDesignation)
                ->where('ex_services', $request->exService)
                ->where('day', 1)
                ->where('night', 0)
//                ->where( function ($query) use ($shift) {
//                    if($shift == 1)
//                    {
//                        $query->where('day',1);
//                        $query->where('night',0);
//                    }
//                    if($shift == 0)
//                    {
//                        $query->where('day',0);
//                        $query->where('night',1);
//                    }
//
//                })
//            ->first();
                ->orderBy('id', 'DESC')
                ->first();
            if(isset($salary_exists->id) &&
                (
                    $salary_exists->salary != $request->guardSalary ||
                    $salary_exists->overtime != $request->guardOvertimeSalary ||
                    $salary_exists->extra_hours != $request->guardExtraHoursSalary ||
                    $salary_exists->post_allowance !=$request->guardsPostAllowance
                ))
            {
                $salary = new ClientBranchWiseGuardSalary();
                $salary = $salary->find($salary_exists->id);

                if($salary_exists->salary != $request->guardSalary)
                {
                    $salary->salary = $request->guardSalary;
                }
                if($salary_exists->overtime != $request->guardOvertimeSalary)
                {
                    $salary->overtime = $request->guardOvertimeSalary;
                }
                if($salary_exists->extra_hours != $request->guardExtraHoursSalary)
                {
                    $salary->extra_hours =$request->guardExtraHoursSalary;
                }
                if($salary_exists->post_allowance !=$request->guardsPostAllowance)
                {
                    $salary->post_allowance =$request->guardsPostAllowance;
                }
                $salary->save();
            }
            else
            {
                $salary_addition = new ClientBranchWiseGuardSalary();
                $salary_addition->client_id = $request->client_id_on_user_profile;
                $salary_addition->branch_id = $request->branch_id_on_user_profile;
                $salary_addition->guard_type = $request->deployGuardAsDesignation;
                $salary_addition->ex_services = $request->exService;
//                if ($request->shift == 1)
//                {
                    $salary_addition->day = 1;
                    $salary_addition->night =  0;

//                } else if ($request->shift == 0)
//                {
//                    $salary_addition->day = 0;
//                    $salary_addition->night =  1;
//                }

                $salary_addition->salary = $request->guardSalary;
                $salary_addition->overtime = $request->guardOvertimeSalary;
                $salary_addition->extra_hours =$request->guardExtraHoursSalary;
                $salary_addition->post_allowance =$request->guardsPostAllowance;
                $salary_addition->save();
            }


            $salary_exists = new ClientBranchWiseGuardSalary();
            $salary_exists = $salary_exists::where('client_id',$request->client_id_on_user_profile)
                ->where('branch_id',$request->branch_id_on_user_profile)
                ->where('guard_type',$request->deployGuardAsDesignation)
                ->where('ex_services', $request->exService)
                ->where('day', 0)
                ->where('night', 1)
//                ->where( function ($query) use ($shift) {
//                    if($shift == 1)
//                    {
//                        $query->where('day',1);
//                        $query->where('night',0);
//                    }
//                    if($shift == 0)
//                    {
//                        $query->where('day',0);
//                        $query->where('night',1);
//                    }
//
//                })
//            ->first();
                ->orderBy('id', 'DESC')
                ->first();
            if(isset($salary_exists->id) &&
                (
                    $salary_exists->salary != $request->guardSalary ||
                    $salary_exists->overtime != $request->guardOvertimeSalary ||
                    $salary_exists->extra_hours != $request->guardExtraHoursSalary ||
                    $salary_exists->post_allowance !=$request->guardsPostAllowance
                ))
            {
                $salary = new ClientBranchWiseGuardSalary();
                $salary = $salary->find($salary_exists->id);

                if($salary_exists->salary != $request->guardSalary)
                {
                    $salary->salary = $request->guardSalary;
                }
                if($salary_exists->overtime != $request->guardOvertimeSalary)
                {
                    $salary->overtime = $request->guardOvertimeSalary;
                }
                if($salary_exists->extra_hours != $request->guardExtraHoursSalary)
                {
                    $salary->extra_hours =$request->guardExtraHoursSalary;
                }
                if($salary_exists->post_allowance !=$request->guardsPostAllowance)
                {
                    $salary->post_allowance =$request->guardsPostAllowance;
                }
                $salary->save();
            }
            else
            {
                $salary_addition = new ClientBranchWiseGuardSalary();
                $salary_addition->client_id = $request->client_id_on_user_profile;
                $salary_addition->branch_id = $request->branch_id_on_user_profile;
                $salary_addition->guard_type = $request->deployGuardAsDesignation;
                $salary_addition->ex_services = $request->exService;
//                if ($request->shift == 1)
//                {
//                    $salary_addition->day = 1;
//                    $salary_addition->night =  0;
//
//                } else if ($request->shift == 0)
//                {
                    $salary_addition->day = 0;
                    $salary_addition->night =  1;
//                }

                $salary_addition->salary = $request->guardSalary;
                $salary_addition->overtime = $request->guardOvertimeSalary;
                $salary_addition->extra_hours =$request->guardExtraHoursSalary;
                $salary_addition->post_allowance =$request->guardsPostAllowance;
                $salary_addition->save();
            }

        }else{
            $salary_exists = new ClientBranchWiseGuardSalary();
            $salary_exists = $salary_exists::where('client_id',$request->client_id_on_user_profile)
                ->where('branch_id',$request->branch_id_on_user_profile)
                ->where('guard_type',$request->deployGuardAsDesignation)
                ->where('ex_services', $request->exService)
                ->where( function ($query) use ($shift) {
                    if($shift == 1)
                    {
                        $query->where('day',1);
                        $query->where('night',0);
                    }
                    if($shift == 0)
                    {
                        $query->where('day',0);
                        $query->where('night',1);
                    }

                })
//            ->first();
                ->orderBy('id', 'DESC')
                ->first();
            if(isset($salary_exists->id) &&
                (
                    $salary_exists->salary != $request->guardSalary ||
                    $salary_exists->overtime != $request->guardOvertimeSalary ||
                    $salary_exists->extra_hours != $request->guardExtraHoursSalary ||
                    $salary_exists->post_allowance !=$request->guardsPostAllowance
                ))
            {
                $salary = new ClientBranchWiseGuardSalary();
                $salary = $salary->find($salary_exists->id);

                if($salary_exists->salary != $request->guardSalary)
                {
                    $salary->salary = $request->guardSalary;
                }
                if($salary_exists->overtime != $request->guardOvertimeSalary)
                {
                    $salary->overtime = $request->guardOvertimeSalary;
                }
                if($salary_exists->extra_hours != $request->guardExtraHoursSalary)
                {
                    $salary->extra_hours =$request->guardExtraHoursSalary;
                }
                if($salary_exists->post_allowance !=$request->guardsPostAllowance)
                {
                    $salary->post_allowance =$request->guardsPostAllowance;
                }
                $salary->save();
            }
            else
            {
                $salary_addition = new ClientBranchWiseGuardSalary();
                $salary_addition->client_id = $request->client_id_on_user_profile;
                $salary_addition->branch_id = $request->branch_id_on_user_profile;
                $salary_addition->guard_type = $request->deployGuardAsDesignation;
                $salary_addition->ex_services = $request->exService;
                if ($request->shift == 1)
                {
                    $salary_addition->day = 1;
                    $salary_addition->night =  0;

                } else if ($request->shift == 0)
                {
                    $salary_addition->day = 0;
                    $salary_addition->night =  1;
                }

                $salary_addition->salary = $request->guardSalary;
                $salary_addition->overtime = $request->guardOvertimeSalary;
                $salary_addition->extra_hours =$request->guardExtraHoursSalary;
                $salary_addition->post_allowance =$request->guardsPostAllowance;
                $salary_addition->save();
            }
        }

//        return redirect()->back()->with(['success_central' => 'Salary Genrated ']);
        return ['responseCode'=>1,'message'=>'successfully updated Rate' ];

    }
    public function getGuardDetailsAJAXRATE (Request $request)
    {

//        dd($request->all());
        $client_ = $request->client_id;
        $branch_ = $request->branch_id;
        $designation_ = $request->designation;
        $exService = $request->exService;
        $salary = new ClientBranchWiseGuardSalary();

        $guard = 0;
        $clientGuardAssociation = 0;


            $salary = $salary->getSalaryAgainstBranchAndGuardType($client_, $branch_ , $designation_ , $exService ,$request->shift);



            return (['responseCode' => 1 , 'message' => 'Guard Retrieved Successfully' , 'data'=>$guard , 'GuardAssociation' => $clientGuardAssociation , 'salary' => $salary ,'ex' =>$exService]);
//        }
//        else
//        {
//            $guards = new Guards\Guards();
//            $guards = $guards::where('parwest_id',$request->parwest_id)->first();
//            if(isset($guards->id))
//            {
//                return (['responseCode' => 0 , 'message' => 'Guard is not present' , 'data'=>null]);
//            }
//            else
//            {
//                return (['responseCode' => 0 , 'message' => 'No Guard Found' , 'data'=>null]);
//            }
//
//        }
    }
    public function getBranchDetailsAJAXRATE (Request $request)
    {

//        dd($request->all());
        $client_ = $request->client_id;
        $branch_ = $request->branch_id;
//        $designation_ = $request->designation;
//        $exService = $request->exService;
        $salary = new ClientBranchWiseGuardSalary();

        $guard = 0;
        $clientGuardAssociation = 0;


            $salary = $salary->getSalaryAgainstBranch($client_, $branch_ );



            return (['responseCode' => 1 , 'message' => 'Guard Retrieved Successfully' , 'data'=>$guard , 'GuardAssociation' => $clientGuardAssociation , 'salary' => $salary ,'ex' =>'0']);
//        }
//        else
//        {
//            $guards = new Guards\Guards();
//            $guards = $guards::where('parwest_id',$request->parwest_id)->first();
//            if(isset($guards->id))
//            {
//                return (['responseCode' => 0 , 'message' => 'Guard is not present' , 'data'=>null]);
//            }
//            else
//            {
//                return (['responseCode' => 0 , 'message' => 'No Guard Found' , 'data'=>null]);
//            }
//
//        }
    }

    public function editRegionalOfficePhoneNumbersAJAX(Request $request)
    {
        $phoneNumbersEdit = new Guards\RegionalOfficesContactNumbersModel();
        $phoneNumbersEdit = $phoneNumbersEdit::find($request->id);
        $phoneNumbersEdit->phone_number = $request->phoneNumber;
        $phoneNumbersEdit->mobile_number = $request->mobileNumber;
        $phoneNumbersEdit->save();
        return (['responseCode' => 1 ,'message' => 'Numbers Edited Successfully','data' => $phoneNumbersEdit]);

    }
    public function revokeShift (Request $request)
    {
//        dd($request);
        if($request->overtime != null)
        {
            $clients = new ClientGuardsAssociation();
            $clients = $clients->revokeGuardFromCurrentClient($request->guard_id,$request->overtime, $request->revokeDate,$request->del);
        }
        else if($request->regular != null)
        {
            $clients = new ClientGuardsAssociation();
            $clients = $clients->revokeGuardFromCurrentClient($request->guard_id,$request->regular,$request->revokeDate,$request->del);
            $isGuardDeployedOvertime = new ClientGuardsAssociation();
            $isGuardDeployedOvertime = $isGuardDeployedOvertime->checkIsGuardIsCurrentlyDeployed($request->guard_id,1,$request->revokeDate);
            if($isGuardDeployedOvertime) //Revoke its's overtime as well
            {
                $clients = new ClientGuardsAssociation();
                $clients = $clients->revokeGuardFromCurrentClient($request->guard_id,1,$request->revokeDate,$request->del);
            }
        }
        return (['responseCode'=> 1, 'message'=>'Guard deployment revoked successfully!']);
    }



    public function getSalaryAndLoans() //Consider sending data of the respective regional offices only
    {
        $user_id = Auth::guard('user')->id();
        $regional_offices = new Guards\RegionalOfficeModel();
        $regional_offices = $regional_offices->getAll();
        return view('salary.index')->with('regional_offices',$regional_offices);
    }

    public function getLoans() //Consider sending data of the respective regional offices only
    {
        $user_id = Auth::guard('user')->id();
        $loans = new Guards\GuardLoansModel();
        $loans = $loans->getAll();
        return view('salary.loan')->with('loans',$loans);
    }
    public function getLoansAndSalary() //Consider sending data of the respective regional offices only
    {
//        $user_id = Auth::guard('user')->id();
        $provinces = new ClientProvincesModel();
        $provinces = $provinces->getAll();
        $cities = new CitiesModel();
        $cities = $cities->getAll();
        $guardTypes = new GuardDesignationModel();
        $guardTypes = $guardTypes->getAll();
        $clientTypes = new ClientType();
        $clientTypes = $clientTypes->getAllClientTypes();
        $equipmentTypes = new InventoryProductsNamesModel();
        $equipmentTypes = $equipmentTypes->getAll();
        $defaultPricing = new ClientContractsRates();
        $defaultPricing = $defaultPricing::where('is_default', 1)->get();
        foreach ($defaultPricing as $default) {
            foreach ($provinces as $province) {
                if ($default->province_id == $province->id) {
                    $default->province_name = $province->name;
                }
            }
            foreach($cities as $city)
            {
                if ($default->city_id == $city->id) {
                    $default->city_name = $city->name;
                }
            }
            foreach ($guardTypes as $guardType) {
                if ($default->guard_type_id == $guardType->id) {
                    $default->guard_type = $guardType->name;
                }
            }
            foreach ($clientTypes as $clientType) {
                if ($default->client_type_id == $clientType->id) {
                    $default->client_type = $clientType->type;
                }
            }
            foreach ($equipmentTypes as $equipmentType) {
                if ($default->product_name_id == $equipmentType->id) {
                    $default->product_name = $equipmentType->name;
                }
            }
        }
        $regions =  new Guards\RegionalOfficeModel();
        $regions = $regions->getAll();

        $allManagers = new UserModel();
        $allManagers = $allManagers->getAllManagers();



        $loans = new Guards\GuardLoansModel();
        $loans = $loans->getAll();

        $allSupervisors = new UserModel();
        $allSupervisors = $allSupervisors->getAllSupervisors();

        $branches = new ClientBranchesModel();
        $branches = $branches->getAll();

        $allSalaries = new Guards\GuardSalaryModel();
        $allSalaries = $allSalaries->getAll();

        $allGuardsExtraHours = new Guards\GuardExtraHoursModel();
        $allGuardsExtraHours = $allGuardsExtraHours->getAll();

        $allGuardsUnpaidSalary = new Guards\GuardUnpaidSalaryModel();
        $allGuardsUnpaidSalary = $allGuardsUnpaidSalary->getAll();

        $data = array(
            'provinces' => $provinces,
            'cities' => $cities,
            'clientProvinces' => $provinces,
            'guardTypes' => $guardTypes,
            'clientTypes' => $clientTypes,
            'equipmentTypes' => $equipmentTypes,
            'productNames' => $equipmentTypes,
            'defaultPricing' => $defaultPricing,
            'branches' => $branches ,
            'supervisors' => $allSupervisors ,
            'loans' => $loans ,
            'regions' => $regions ,
            'allSalaries' => $allSalaries ,
            'allManagers' => $allManagers ,
            'allGuardsExtraHours' => $allGuardsExtraHours ,
            'allGuardsUnpaidSalary' => $allGuardsUnpaidSalary ,
        );




        return view('guards.salaryLoanClearance')->with($data);
    }
    public function accountLoan() //Consider sending data of the respective regional offices only
    {
        $user_id = Auth::guard('user')->id();
//        $user_id = 1 ;
//        dd($user_id);
        $provinces = new ClientProvincesModel();
        $provinces = $provinces->getAll();
        $cities = new CitiesModel();
        $cities = $cities->getAll();
        $guardTypes = new GuardDesignationModel();
        $guardTypes = $guardTypes->getAll();
        $clientTypes = new ClientType();
        $clientTypes = $clientTypes->getAllClientTypes();
        $equipmentTypes = new InventoryProductsNamesModel();
        $equipmentTypes = $equipmentTypes->getAll();
        $defaultPricing = new ClientContractsRates();
        $defaultPricing = $defaultPricing::where('is_default', 1)->get();
        foreach ($defaultPricing as $default) {
            foreach ($provinces as $province) {
                if ($default->province_id == $province->id) {
                    $default->province_name = $province->name;
                }
            }
            foreach($cities as $city)
            {
                if ($default->city_id == $city->id) {
                    $default->city_name = $city->name;
                }
            }
            foreach ($guardTypes as $guardType) {
                if ($default->guard_type_id == $guardType->id) {
                    $default->guard_type = $guardType->name;
                }
            }
            foreach ($clientTypes as $clientType) {
                if ($default->client_type_id == $clientType->id) {
                    $default->client_type = $clientType->type;
                }
            }
            foreach ($equipmentTypes as $equipmentType) {
                if ($default->product_name_id == $equipmentType->id) {
                    $default->product_name = $equipmentType->name;
                }
            }
        }
        $regions =  new Guards\RegionalOfficeModel();
        $regions = $regions->getAll();

        $allManagers = new UserModel();
        $allManagers = $allManagers->getAllManagers();


        $loggedInUser = Auth::guard('user')->id();
        $loans = new Guards\GuardLoansModel();
//        $loans = $loans->getModelByUserId($loggedInUser);
        $loans = $loans->getModelByUserIdAndStatus($loggedInUser);

        $allLoans = new  Guards\GuardLoansModel();
        $allLoans = $allLoans->getAllBulk();

        $finalised = new Guards\UsersFinalizeLoan();
        $finalised = $finalised->getAll($loggedInUser);

        $finalised_all = new Guards\UsersFinalizeLoan();
        $finalised_all = $finalised_all->getAllFinal();


//dd($allLoans);
        $allSupervisors = new UserModel();
        $allSupervisors = $allSupervisors->getAllSupervisors();

        $allClients = new Clients();
        $allClients = $allClients->getAllClientsWithoutPaginate();


        $branches = new ClientBranchesModel();
        $branches = $branches->getAll();

        $allSalaries = new Guards\GuardSalaryModel();
        $allSalaries = $allSalaries->getAll();

        $allGuardsExtraHours = new Guards\GuardExtraHoursModel();
        $allGuardsExtraHours = $allGuardsExtraHours->getAll();

        $allGuardsUnpaidSalary = new Guards\GuardUnpaidSalaryModel();
        $allGuardsUnpaidSalary = $allGuardsUnpaidSalary->getAll();

        $data = array(
            'provinces' => $provinces,
            'cities' => $cities,
            'clientProvinces' => $provinces,
            'guardTypes' => $guardTypes,
            'clientTypes' => $clientTypes,
            'equipmentTypes' => $equipmentTypes,
            'productNames' => $equipmentTypes,
            'defaultPricing' => $defaultPricing,
            'branches' => $branches ,
            'clients' => $allClients ,
            'supervisors' => $allSupervisors ,
            'loans' => $loans ,
            'allLoans' => $allLoans ,
            'finalised' => $finalised ,
            'finalised_all' => $finalised_all ,
            'regions' => $regions ,
            'allSalaries' => $allSalaries ,
            'allManagers' => $allManagers ,
            'allGuardsExtraHours' => $allGuardsExtraHours ,
            'allGuardsUnpaidSalary' => $allGuardsUnpaidSalary ,
            'user_id' => $user_id ,
        );




        return view('guards.accountLoan')->with($data);
    }
    public function accountSalary() //Consider sending data of the respective regional offices only
    {
        $user_id = Auth::guard('user');
//        dd($user_id);
        $active_month = new Guards\PayrollSalaryMonth();
        $active_month = $active_month->getAll();
//        dd($active_month['date_from']);
        $salary_year = $active_month['salary_year'];

        $salary_month =  substr($active_month['date_from'], 0, -3);
        $monthNum = substr($salary_month,5);
//        dd($monthNum);
//        $monthNum  = 3;
        $dateObj   = DateTime::createFromFormat('!m', $monthNum);
        $monthName = $dateObj->format('F'); // March
//        dd($monthName);
//        $provinces = new ClientProvincesModel();
//        $provinces = $provinces->getAll();
//        $cities = new CitiesModel();
//        $cities = $cities->getAll();
//        $guardTypes = new GuardDesignationModel();
//        $guardTypes = $guardTypes->getAll();
//        $clientTypes = new ClientType();
//        $clientTypes = $clientTypes->getAllClientTypes();
//        $equipmentTypes = new InventoryProductsNamesModel();
//        $equipmentTypes = $equipmentTypes->getAll();
//        $defaultPricing = new ClientContractsRates();
//        $defaultPricing = $defaultPricing::where('is_default', 1)->get();
//        foreach ($defaultPricing as $default) {
//            foreach ($provinces as $province) {
//                if ($default->province_id == $province->id) {
//                    $default->province_name = $province->name;
//                }
//            }
//            foreach($cities as $city)
//            {
//                if ($default->city_id == $city->id) {
//                    $default->city_name = $city->name;
//                }
//            }
//            foreach ($guardTypes as $guardType) {
//                if ($default->guard_type_id == $guardType->id) {
//                    $default->guard_type = $guardType->name;
//                }
//            }
//            foreach ($clientTypes as $clientType) {
//                if ($default->client_type_id == $clientType->id) {
//                    $default->client_type = $clientType->type;
//                }
//            }
//            foreach ($equipmentTypes as $equipmentType) {
//                if ($default->product_name_id == $equipmentType->id) {
//                    $default->product_name = $equipmentType->name;
//                }
//            }
//        }
        $regions =  new Guards\RegionalOfficeModel();
        $regions = $regions->getAll();

//        $allManagers = new UserModel();
//        $allManagers = $allManagers->getAllManagers();
//        $allowanceType = new Guards\AllowanceTypeModel();
//        $allowanceType = $allowanceType->getAll();
////dd($allowanceType->name);
//
//        $allowanceTypeAddition = new Guards\AllowanceTypeAdditionModel();
//        $allowanceTypeAddition = $allowanceTypeAddition->getModelWithRegionAllowanceType();
////        dd($allowanceTypeAddition);
//
//        $loans = new Guards\GuardLoansModel();
//        $loans = $loans->getAll();
//
//        $allSupervisors = new UserModel();
//        $allSupervisors = $allSupervisors->getAllSupervisors();
//        $allClients = new Clients();
//        $allClients = $allClients->getAllClientsWithoutPaginate();
//
//        $branches = new ClientBranchesModel();
//        $branches = $branches->getAll();
//
//        $allSalaries = new Guards\GuardSalaryModel();
//        $allSalaries = $allSalaries->getAll();
//
//        $allGuardsExtraHours = new Guards\GuardExtraHoursModel();
//        $allGuardsExtraHours = $allGuardsExtraHours->getAll();
//
//        $allGuardsUnpaidSalary = new Guards\GuardUnpaidSalaryModel();
//        $allGuardsUnpaidSalary = $allGuardsUnpaidSalary->getAll();
//
//        $special_duty = new Guards\PayrollSpecialDuty();
//        $special_duty = $special_duty->getAll();
//
//        $cwf = new Guards\CwfDeductionModel();
//        $cwf = $cwf->getModelWithRegion();
//
//        $apsaa = new Guards\ApsaaDeductionModel();
//        $apsaa = $apsaa->getModelWithRegion();
//
//        $specialBranch = new Guards\SpecialBranchDeductionModel();
//        $specialBranch = $specialBranch->getModelWithRegion();
//
//        $eidi = new Guards\EidAllowanceModel();
//        $eidi = $eidi->getModelWithRegion();

//        $allGuardsUnpaidSalary = new Guards\GuardUnpaidSalaryModel();
//        $allGuardsUnpaidSalary = $allGuardsUnpaidSalary->getAll();


        $salaryHistory = new Guards\GuardSalaryHistoryStatModel();
        $salaryHistory = $salaryHistory->getModelWithUser();
//dd($eidi);
        $data = array(
//            'provinces' => $provinces,
//            'cities' => $cities,
//            'clientProvinces' => $provinces,
//            'guardTypes' => $guardTypes,
//            'clientTypes' => $clientTypes,
//            'equipmentTypes' => $equipmentTypes,
//            'productNames' => $equipmentTypes,
//            'defaultPricing' => $defaultPricing,
//            'branches' => $branches ,
//            'clients' => $allClients ,
//            'supervisors' => $allSupervisors ,
//            'loans' => $loans ,
            'regions' => $regions ,
//            'allSalaries' => $allSalaries ,
//            'allManagers' => $allManagers ,
//            'allGuardsExtraHours' => $allGuardsExtraHours ,
//            'allGuardsUnpaidSalary' => $allGuardsUnpaidSalary ,
//            'cwf' => $cwf ,
//            'apsaa' => $apsaa ,
//            'specialBranch' => $specialBranch ,
//            'eidi' => $eidi ,
//            'special_duty' => $special_duty ,
//            'allowanceType' => $allowanceType ,
            'salaryHistory' => $salaryHistory ,
//            'allowanceTypeAddition' => $allowanceTypeAddition ,
            'monthName' => $monthName ,
            'salary_year' => $salary_year ,
//            'allGuardsUnpaidSalary' => $allGuardsUnpaidSalary ,
        );




        return view('guards.accountSalary')->with($data);
    }
    public function accountSalaryExport() //Consider sending data of the respective regional offices only
    {
//        $user_id = Auth::guard('user')->id();

        $active_month = new Guards\PayrollSalaryMonth();
        $active_month = $active_month->getAll();
//        dd($active_month['date_from']);
        $salary_year = $active_month['salary_year'];

        $salary_month =  substr($active_month['date_from'], 0, -3);
        $monthNum = substr($salary_month,5);
//        dd($monthNum);
//        $monthNum  = 3;
        $dateObj   = DateTime::createFromFormat('!m', $monthNum);
        $monthName = $dateObj->format('F'); // March
//        dd($monthName);
//        $provinces = new ClientProvincesModel();
//        $provinces = $provinces->getAll();
//        $cities = new CitiesModel();
//        $cities = $cities->getAll();
//        $guardTypes = new GuardDesignationModel();
//        $guardTypes = $guardTypes->getAll();
//        $clientTypes = new ClientType();
//        $clientTypes = $clientTypes->getAllClientTypes();
//        $equipmentTypes = new InventoryProductsNamesModel();
//        $equipmentTypes = $equipmentTypes->getAll();
//        $defaultPricing = new ClientContractsRates();
//        $defaultPricing = $defaultPricing::where('is_default', 1)->get();
//        foreach ($defaultPricing as $default) {
//            foreach ($provinces as $province) {
//                if ($default->province_id == $province->id) {
//                    $default->province_name = $province->name;
//                }
//            }
//            foreach($cities as $city)
//            {
//                if ($default->city_id == $city->id) {
//                    $default->city_name = $city->name;
//                }
//            }
//            foreach ($guardTypes as $guardType) {
//                if ($default->guard_type_id == $guardType->id) {
//                    $default->guard_type = $guardType->name;
//                }
//            }
//            foreach ($clientTypes as $clientType) {
//                if ($default->client_type_id == $clientType->id) {
//                    $default->client_type = $clientType->type;
//                }
//            }
//            foreach ($equipmentTypes as $equipmentType) {
//                if ($default->product_name_id == $equipmentType->id) {
//                    $default->product_name = $equipmentType->name;
//                }
//            }
//        }
        $regions =  new Guards\RegionalOfficeModel();
        $regions = $regions->getAll();

//        $allManagers = new UserModel();
//        $allManagers = $allManagers->getAllManagers();
//        $allowanceType = new Guards\AllowanceTypeModel();
//        $allowanceType = $allowanceType->getAll();
////dd($allowanceType->name);
//
//        $allowanceTypeAddition = new Guards\AllowanceTypeAdditionModel();
//        $allowanceTypeAddition = $allowanceTypeAddition->getModelWithRegionAllowanceType();
////        dd($allowanceTypeAddition);
//
//        $loans = new Guards\GuardLoansModel();
//        $loans = $loans->getAll();
//
//        $allSupervisors = new UserModel();
//        $allSupervisors = $allSupervisors->getAllSupervisors();
//        $allClients = new Clients();
//        $allClients = $allClients->getAllClientsWithoutPaginate();
//
//        $branches = new ClientBranchesModel();
//        $branches = $branches->getAll();
//
//        $allSalaries = new Guards\GuardSalaryModel();
//        $allSalaries = $allSalaries->getAll();
//
//        $allGuardsExtraHours = new Guards\GuardExtraHoursModel();
//        $allGuardsExtraHours = $allGuardsExtraHours->getAll();
//
//        $allGuardsUnpaidSalary = new Guards\GuardUnpaidSalaryModel();
//        $allGuardsUnpaidSalary = $allGuardsUnpaidSalary->getAll();
//
//        $special_duty = new Guards\PayrollSpecialDuty();
//        $special_duty = $special_duty->getAll();
//
//        $cwf = new Guards\CwfDeductionModel();
//        $cwf = $cwf->getModelWithRegion();
//
//        $apsaa = new Guards\ApsaaDeductionModel();
//        $apsaa = $apsaa->getModelWithRegion();
//
//        $specialBranch = new Guards\SpecialBranchDeductionModel();
//        $specialBranch = $specialBranch->getModelWithRegion();

//        $eidi = new Guards\EidAllowanceModel();
//        $eidi = $eidi->getModelWithRegion();

//        $allGuardsUnpaidSalary = new Guards\GuardUnpaidSalaryModel();
//        $allGuardsUnpaidSalary = $allGuardsUnpaidSalary->getAll();

//
//        $salaryHistory = new Guards\GuardSalaryHistoryStatModel();
//        $salaryHistory = $salaryHistory->getModelWithUser();
//dd($eidi);
        $data = array(
//            'provinces' => $provinces,
//            'cities' => $cities,
//            'clientProvinces' => $provinces,
//            'guardTypes' => $guardTypes,
//            'clientTypes' => $clientTypes,
//            'equipmentTypes' => $equipmentTypes,
//            'productNames' => $equipmentTypes,
//            'defaultPricing' => $defaultPricing,
//            'branches' => $branches ,
//            'clients' => $allClients ,
//            'supervisors' => $allSupervisors ,
//            'loans' => $loans ,
            'regions' => $regions ,
//            'allSalaries' => $allSalaries ,
//            'allManagers' => $allManagers ,
//            'allGuardsExtraHours' => $allGuardsExtraHours ,
//            'allGuardsUnpaidSalary' => $allGuardsUnpaidSalary ,
//            'cwf' => $cwf ,
//            'apsaa' => $apsaa ,
//            'specialBranch' => $specialBranch ,
//            'eidi' => $eidi ,
//            'special_duty' => $special_duty ,
//            'allowanceType' => $allowanceType ,
//            'salaryHistory' => $salaryHistory ,
//            'allowanceTypeAddition' => $allowanceTypeAddition ,
            'monthName' => $monthName ,
            'salary_year' => $salary_year ,
//            'allGuardsUnpaidSalary' => $allGuardsUnpaidSalary ,
        );




        return view('guards.accountSalaryExport')->with($data);
    }
    public function accountSalaryExportUnpaid() //Consider sending data of the respective regional offices only
    {
//        $user_id = Auth::guard('user')->id();

//        $active_month = new Guards\PayrollSalaryMonth();
//        $active_month = $active_month->getAll();
////        dd($active_month['date_from']);
//        $salary_year = $active_month['salary_year'];
//
//        $salary_month =  substr($active_month['date_from'], 0, -3);
//        $monthNum = substr($salary_month,5);
////        dd($monthNum);
////        $monthNum  = 3;
//        $dateObj   = DateTime::createFromFormat('!m', $monthNum);
//        $monthName = $dateObj->format('F'); // March
////        dd($monthName);
//        $provinces = new ClientProvincesModel();
//        $provinces = $provinces->getAll();
//        $cities = new CitiesModel();
//        $cities = $cities->getAll();
//        $guardTypes = new GuardDesignationModel();
//        $guardTypes = $guardTypes->getAll();
//        $clientTypes = new ClientType();
//        $clientTypes = $clientTypes->getAllClientTypes();
//        $equipmentTypes = new InventoryProductsNamesModel();
//        $equipmentTypes = $equipmentTypes->getAll();
//        $defaultPricing = new ClientContractsRates();
//        $defaultPricing = $defaultPricing::where('is_default', 1)->get();
//        foreach ($defaultPricing as $default) {
//            foreach ($provinces as $province) {
//                if ($default->province_id == $province->id) {
//                    $default->province_name = $province->name;
//                }
//            }
//            foreach($cities as $city)
//            {
//                if ($default->city_id == $city->id) {
//                    $default->city_name = $city->name;
//                }
//            }
//            foreach ($guardTypes as $guardType) {
//                if ($default->guard_type_id == $guardType->id) {
//                    $default->guard_type = $guardType->name;
//                }
//            }
//            foreach ($clientTypes as $clientType) {
//                if ($default->client_type_id == $clientType->id) {
//                    $default->client_type = $clientType->type;
//                }
//            }
//            foreach ($equipmentTypes as $equipmentType) {
//                if ($default->product_name_id == $equipmentType->id) {
//                    $default->product_name = $equipmentType->name;
//                }
//            }
//        }
//        $regions =  new Guards\RegionalOfficeModel();
//        $regions = $regions->getAll();
//
//        $allManagers = new UserModel();
//        $allManagers = $allManagers->getAllManagers();
//        $allowanceType = new Guards\AllowanceTypeModel();
//        $allowanceType = $allowanceType->getAll();
////dd($allowanceType->name);
//
//        $allowanceTypeAddition = new Guards\AllowanceTypeAdditionModel();
//        $allowanceTypeAddition = $allowanceTypeAddition->getModelWithRegionAllowanceType();
////        dd($allowanceTypeAddition);
//
//        $loans = new Guards\GuardLoansModel();
//        $loans = $loans->getAll();
//
//        $allSupervisors = new UserModel();
//        $allSupervisors = $allSupervisors->getAllSupervisors();
//        $allClients = new Clients();
//        $allClients = $allClients->getAllClientsWithoutPaginate();
//
//        $branches = new ClientBranchesModel();
//        $branches = $branches->getAll();
//
//        $allSalaries = new Guards\GuardSalaryModel();
//        $allSalaries = $allSalaries->getAll();
//
//        $allGuardsExtraHours = new Guards\GuardExtraHoursModel();
//        $allGuardsExtraHours = $allGuardsExtraHours->getAll();
//
//        $allGuardsUnpaidSalary = new Guards\GuardUnpaidSalaryModel();
//        $allGuardsUnpaidSalary = $allGuardsUnpaidSalary->getAll();
//
//        $special_duty = new Guards\PayrollSpecialDuty();
//        $special_duty = $special_duty->getAll();
//
//        $cwf = new Guards\CwfDeductionModel();
//        $cwf = $cwf->getModelWithRegion();
//
//        $apsaa = new Guards\ApsaaDeductionModel();
//        $apsaa = $apsaa->getModelWithRegion();
//
//        $specialBranch = new Guards\SpecialBranchDeductionModel();
//        $specialBranch = $specialBranch->getModelWithRegion();
//
//        $eidi = new Guards\EidAllowanceModel();
//        $eidi = $eidi->getModelWithRegion();
//
//        $allGuardsUnpaidSalary = new Guards\GuardUnpaidSalaryModel();
//        $allGuardsUnpaidSalary = $allGuardsUnpaidSalary->getAll();
//
//
//        $salaryHistory = new Guards\GuardSalaryHistoryStatModel();
//        $salaryHistory = $salaryHistory->getModelWithUser();
//dd($eidi);
        $data = array(
//            'provinces' => $provinces,
//            'cities' => $cities,
//            'clientProvinces' => $provinces,
//            'guardTypes' => $guardTypes,
//            'clientTypes' => $clientTypes,
//            'equipmentTypes' => $equipmentTypes,
//            'productNames' => $equipmentTypes,
//            'defaultPricing' => $defaultPricing,
//            'branches' => $branches ,
//            'clients' => $allClients ,
//            'supervisors' => $allSupervisors ,
//            'loans' => $loans ,
//            'regions' => $regions ,
//            'allSalaries' => $allSalaries ,
//            'allManagers' => $allManagers ,
//            'allGuardsExtraHours' => $allGuardsExtraHours ,
//            'allGuardsUnpaidSalary' => $allGuardsUnpaidSalary ,
//            'cwf' => $cwf ,
//            'apsaa' => $apsaa ,
//            'specialBranch' => $specialBranch ,
//            'eidi' => $eidi ,
//            'special_duty' => $special_duty ,
//            'allowanceType' => $allowanceType ,
//            'salaryHistory' => $salaryHistory ,
//            'allowanceTypeAddition' => $allowanceTypeAddition ,
//            'monthName' => $monthName ,
//            'salary_year' => $salary_year ,
//            'allGuardsUnpaidSalary' => $allGuardsUnpaidSalary ,
        );




        return view('guards.accountSalaryExportUnpaid')->with($data);
    }
    public function getRecentGuardDetailsUnpaidSalaryExport(Request $request){
        if($request->parwest_id){
            if($request->month){
                $month = $request->month;
                $input = $request->all();
                $sal_month =  substr($input['month'], 5);


                $parwest__id = $request->parwest_id;
                $unpaid_model = new Guards\GuardUnpaidSalariesModel();
                $unpaid_model = $unpaid_model->getModelByParwestIdAndMonth($parwest__id,$sal_month);
                return ['responseCode' => 1, 'responseStatus' => 'Successful','data' => $unpaid_model,
                    'message' => 'Guard Not Exists' ];
            }
        }



    }
    public function monthInitialise() //Consider sending data of the respective regional offices only
    {
        $active_month = new Guards\PayrollSalaryMonth();
        $active_month = $active_month->getAll();
//        dd($active_month['date_from']);
        $salary_year = $active_month['salary_year'];

        $salary_month =  substr($active_month['date_from'], 0, -3);
        $monthNum = substr($salary_month,5);
        if($monthNum == 12){
            $nextMonthNumber = 01;
            $dateObj   = DateTime::createFromFormat('!m', $nextMonthNumber);
            $nextMonthName = $dateObj->format('F'); // March
            $nextSalary_year = $salary_year+1;
        }else{
            $nextMonthNumber = $monthNum + 01;
            $dateObj   = DateTime::createFromFormat('!m', $nextMonthNumber);
            $nextMonthName = $dateObj->format('F'); // March
            $nextSalary_year = $salary_year;

        }
//        dd($monthNum);
//        $monthNum  = 3;
        $dateObj   = DateTime::createFromFormat('!m', $monthNum);
        $monthName = $dateObj->format('F'); // March
//        dd($monthName);

        $salary_month = new Guards\PayrollSalaryMonth();
        $salary_month = $salary_month->getAll();
//        dd($salary_month);

        $data = array(
            'salary_month' => $salary_month,
            'monthName' => $monthName,
            'salary_year' => $salary_year,
            'nextMonthName' => $nextMonthName,
            'nextSalary_year' => $nextSalary_year,
        );




        return view('guards.payroll.monthInitialise')->with($data);
    }
    public function updatePayrollMonth(){
//        $lastEntry = new Guards\PayrollSalaryMonth();
//        $lastEntry = $lastEntry->first();

        $updateEntry = new Guards\PayrollSalaryMonth();
        $updateEntry = $updateEntry->updateMonth();

        return \redirect()->back();


    }

    public function accountClearance() //Consider sending data of the respective regional offices only
    {
//        $user_id = Auth::guard('user')->id();
//        $provinces = new ClientProvincesModel();
//        $provinces = $provinces->getAll();
//        $cities = new CitiesModel();
//        $cities = $cities->getAll();
//        $guardTypes = new GuardDesignationModel();
//        $guardTypes = $guardTypes->getAll();
//        $clientTypes = new ClientType();
//        $clientTypes = $clientTypes->getAllClientTypes();
//        $equipmentTypes = new InventoryProductsNamesModel();
//        $equipmentTypes = $equipmentTypes->getAll();
//        $defaultPricing = new ClientContractsRates();
//        $defaultPricing = $defaultPricing::where('is_default', 1)->get();
//        foreach ($defaultPricing as $default) {
//            foreach ($provinces as $province) {
//                if ($default->province_id == $province->id) {
//                    $default->province_name = $province->name;
//                }
//            }
//            foreach($cities as $city)
//            {
//                if ($default->city_id == $city->id) {
//                    $default->city_name = $city->name;
//                }
//            }
//            foreach ($guardTypes as $guardType) {
//                if ($default->guard_type_id == $guardType->id) {
//                    $default->guard_type = $guardType->name;
//                }
//            }
//            foreach ($clientTypes as $clientType) {
//                if ($default->client_type_id == $clientType->id) {
//                    $default->client_type = $clientType->type;
//                }
//            }
//            foreach ($equipmentTypes as $equipmentType) {
//                if ($default->product_name_id == $equipmentType->id) {
//                    $default->product_name = $equipmentType->name;
//                }
//            }
//        }
//        $regions =  new Guards\RegionalOfficeModel();
//        $regions = $regions->getAll();
//
//        $allManagers = new UserModel();
//        $allManagers = $allManagers->getAllManagers();
//
//
//
//        $loans = new Guards\GuardLoansModel();
//        $loans = $loans->getAll();
//
//        $allSupervisors = new UserModel();
//        $allSupervisors = $allSupervisors->getAllSupervisors();
//
//        $branches = new ClientBranchesModel();
//        $branches = $branches->getAll();
//
//        $allSalaries = new Guards\GuardSalaryModel();
//        $allSalaries = $allSalaries->getAll();

//        $allGuardsExtraHours = new Guards\GuardSalaryHistoryStatModel();
//        $allGuardsExtraHours = $allGuardsExtraHours->getModelWithParwest();
        $allGuardsExtraHours = new Guards\GuardClearanceHistoryStatModel();
        $allGuardsExtraHours = $allGuardsExtraHours->getModelWithParwest();
//        dd($allGuardsExtraHours);
//        $allGuardsUnpaidSalary = new Guards\GuardUnpaidSalaryModel();
//        $allGuardsUnpaidSalary = $allGuardsUnpaidSalary->getAll();

        $data = array(
//            'provinces' => $provinces,
//            'cities' => $cities,
//            'clientProvinces' => $provinces,
//            'guardTypes' => $guardTypes,
//            'clientTypes' => $clientTypes,
//            'equipmentTypes' => $equipmentTypes,
//            'productNames' => $equipmentTypes,
//            'defaultPricing' => $defaultPricing,
//            'branches' => $branches ,
//            'supervisors' => $allSupervisors ,
//            'loans' => $loans ,
//            'regions' => $regions ,
//            'allSalaries' => $allSalaries ,
//            'allManagers' => $allManagers ,
            'allGuardsExtraHours' => $allGuardsExtraHours ,
//            'allGuardsUnpaidSalary' => $allGuardsUnpaidSalary ,

        );




        return view('guards.accountClearance')->with($data);
    }
    public function accountClearanceExport() //Consider sending data of the respective regional offices only
    {
//        $user_id = Auth::guard('user')->id();
//        $provinces = new ClientProvincesModel();
//        $provinces = $provinces->getAll();
//        $cities = new CitiesModel();
//        $cities = $cities->getAll();
//        $guardTypes = new GuardDesignationModel();
//        $guardTypes = $guardTypes->getAll();
//        $clientTypes = new ClientType();
//        $clientTypes = $clientTypes->getAllClientTypes();
//        $equipmentTypes = new InventoryProductsNamesModel();
//        $equipmentTypes = $equipmentTypes->getAll();
//        $defaultPricing = new ClientContractsRates();
//        $defaultPricing = $defaultPricing::where('is_default', 1)->get();
//        foreach ($defaultPricing as $default) {
//            foreach ($provinces as $province) {
//                if ($default->province_id == $province->id) {
//                    $default->province_name = $province->name;
//                }
//            }
//            foreach($cities as $city)
//            {
//                if ($default->city_id == $city->id) {
//                    $default->city_name = $city->name;
//                }
//            }
//            foreach ($guardTypes as $guardType) {
//                if ($default->guard_type_id == $guardType->id) {
//                    $default->guard_type = $guardType->name;
//                }
//            }
//            foreach ($clientTypes as $clientType) {
//                if ($default->client_type_id == $clientType->id) {
//                    $default->client_type = $clientType->type;
//                }
//            }
//            foreach ($equipmentTypes as $equipmentType) {
//                if ($default->product_name_id == $equipmentType->id) {
//                    $default->product_name = $equipmentType->name;
//                }
//            }
//        }
//        $regions =  new Guards\RegionalOfficeModel();
//        $regions = $regions->getAll();
//
//        $allManagers = new UserModel();
//        $allManagers = $allManagers->getAllManagers();
//
//
//
//        $loans = new Guards\GuardLoansModel();
//        $loans = $loans->getAll();
//
//        $allSupervisors = new UserModel();
//        $allSupervisors = $allSupervisors->getAllSupervisors();
//
//        $branches = new ClientBranchesModel();
//        $branches = $branches->getAll();
//
//        $allSalaries = new Guards\GuardSalaryModel();
//        $allSalaries = $allSalaries->getAll();

        $allGuardsExtraHours = new Guards\GuardClearanceHistoryStatModel();
        $allGuardsExtraHours = $allGuardsExtraHours->getModelWithParwest();
//        dd($allGuardsExtraHours);
//        $allGuardsUnpaidSalary = new Guards\GuardUnpaidSalaryModel();
//        $allGuardsUnpaidSalary = $allGuardsUnpaidSalary->getAll();

        $data = array(
//            'provinces' => $provinces,
//            'cities' => $cities,
//            'clientProvinces' => $provinces,
//            'guardTypes' => $guardTypes,
//            'clientTypes' => $clientTypes,
//            'equipmentTypes' => $equipmentTypes,
//            'productNames' => $equipmentTypes,
//            'defaultPricing' => $defaultPricing,
//            'branches' => $branches ,
//            'supervisors' => $allSupervisors ,
//            'loans' => $loans ,
//            'regions' => $regions ,
//            'allSalaries' => $allSalaries ,
//            'allManagers' => $allManagers ,
            'allGuardsExtraHours' => $allGuardsExtraHours ,
//            'allGuardsUnpaidSalary' => $allGuardsUnpaidSalary ,

        );




        return view('guards.accountClearanceExport')->with($data);
    }
    public function accountUnPaid() //Consider sending data of the respective regional offices only
    {
//        $user_id = Auth::guard('user')->id();

        $active_month = new Guards\PayrollSalaryMonth();
        $active_month = $active_month->getAll();
//        dd($active_month['date_from']);
        $salary_year = $active_month['salary_year'];

        $salary_month =  substr($active_month['date_from'], 0, -3);
        $monthNum = substr($salary_month,5);

//        $salary_year
//        $monthNum  = 3;
        $dateObj   = DateTime::createFromFormat('!m', $monthNum);
        $monthName = $dateObj->format('F'); // March
//        $provinces = new ClientProvincesModel();
//        $provinces = $provinces->getAll();
//        $cities = new CitiesModel();
//        $cities = $cities->getAll();
//        $guardTypes = new GuardDesignationModel();
//        $guardTypes = $guardTypes->getAll();
//        $clientTypes = new ClientType();
//        $clientTypes = $clientTypes->getAllClientTypes();
//        $equipmentTypes = new InventoryProductsNamesModel();
//        $equipmentTypes = $equipmentTypes->getAll();
//        $defaultPricing = new ClientContractsRates();
//        $defaultPricing = $defaultPricing::where('is_default', 1)->get();
//        foreach ($defaultPricing as $default) {
//            foreach ($provinces as $province) {
//                if ($default->province_id == $province->id) {
//                    $default->province_name = $province->name;
//                }
//            }
//            foreach($cities as $city)
//            {
//                if ($default->city_id == $city->id) {
//                    $default->city_name = $city->name;
//                }
//            }
//            foreach ($guardTypes as $guardType) {
//                if ($default->guard_type_id == $guardType->id) {
//                    $default->guard_type = $guardType->name;
//                }
//            }
//            foreach ($clientTypes as $clientType) {
//                if ($default->client_type_id == $clientType->id) {
//                    $default->client_type = $clientType->type;
//                }
//            }
//            foreach ($equipmentTypes as $equipmentType) {
//                if ($default->product_name_id == $equipmentType->id) {
//                    $default->product_name = $equipmentType->name;
//                }
//            }
//        }
//        $regions =  new Guards\RegionalOfficeModel();
//        $regions = $regions->getAll();
//
//        $allManagers = new UserModel();
//        $allManagers = $allManagers->getAllManagers();
//
//
//
//        $loans = new Guards\GuardLoansModel();
//        $loans = $loans->getAll();
//
//        $allSupervisors = new UserModel();
//        $allSupervisors = $allSupervisors->getAllSupervisors();
//
//        $branches = new ClientBranchesModel();
//        $branches = $branches->getAll();
//
//        $allSalaries = new Guards\GuardSalaryModel();
//        $allSalaries = $allSalaries->getAll();
//
//        $allGuardsExtraHours = new Guards\GuardSalaryHistoryStatModel();
//        $allGuardsExtraHours = $allGuardsExtraHours->getModelWithParwest();
////        dd($allGuardsExtraHours);
//        //        $salary_year
////        $monthNum  = 3;
        $allGuardsUnpaidSalary = new Guards\GuardUnpaidSalaryModel();
        $allGuardsUnpaidSalary = $allGuardsUnpaidSalary->getModelByMonths($monthNum,$salary_year);

        $data = array(
//            'provinces' => $provinces,
//            'cities' => $cities,
//            'clientProvinces' => $provinces,
//            'guardTypes' => $guardTypes,
//            'clientTypes' => $clientTypes,
//            'equipmentTypes' => $equipmentTypes,
//            'productNames' => $equipmentTypes,
//            'defaultPricing' => $defaultPricing,
//            'branches' => $branches ,
//            'supervisors' => $allSupervisors ,
//            'loans' => $loans ,
//            'regions' => $regions ,
//            'allSalaries' => $allSalaries ,
//            'allManagers' => $allManagers ,
//            'allGuardsExtraHours' => $allGuardsExtraHours ,
            'allGuardsUnpaidSalary' => $allGuardsUnpaidSalary ,
            'salary_year' => $salary_year ,
            'monthName' => $monthName ,

        );




        return view('guards.accountUnPaid')->with($data);
    }
    public function storeGuardExtraHours(Request $request){
        $guard_extra_hours = new Guards\GuardExtraHoursModel();
        $guard_extra_hours = $guard_extra_hours->saveModel($request);
//        return view('guards.salaryLoanClearance');
        return Redirect::to(url('guard/payrollExtraHours'));

    }
    public function storeGuardOtherDeductions(Request $request){
        $guard_extra_hours = new Guards\PayrollOtherDeductionsModel();
        $guard_extra_hours = $guard_extra_hours->saveModel($request);
//        return view('guards.salaryLoanClearance');
        return Redirect::to(url('guard/payrollOtherDeductions'));

    }
    public function storeCwfDeduction(Request $request){
//        $guard_extra_hours = new Guards\GuardExtraHoursModel();
//        $guard_extra_hours = $guard_extra_hours->saveModel($request);
//        return view('guards.salaryLoanClearance');
//        dd($request);
        $cwf_deduction = new Guards\CwfDeductionModel();
        $cwf_deduction = $cwf_deduction->saveModel($request);
        return Redirect::to(url('guard/accountSalary'));

    }
    public function storeApsaaDeduction(Request $request){
//        $guard_extra_hours = new Guards\GuardExtraHoursModel();
//        $guard_extra_hours = $guard_extra_hours->saveModel($request);
//        return view('guards.salaryLoanClearance');
//        $cwf_deduction = new Guards\CwfDeductionModel();
//        $cwf_deduction = $cwf_deduction->saveModel($request);
        $apsaaDeduction = new Guards\ApsaaDeductionModel();
        $apsaaDeduction = $apsaaDeduction->saveModel($request);
        return Redirect::to(url('guard/accountSalary'));

    }
    public function storeSpecialBranchDeduction(Request $request){
//        $guard_extra_hours = new Guards\GuardExtraHoursModel();
//        $guard_extra_hours = $guard_extra_hours->saveModel($request);
//        return view('guards.salaryLoanClearance');
        $sb_deduction = new Guards\SpecialBranchDeductionModel();
        $sb_deduction = $sb_deduction->saveModel($request);
        return Redirect::to(url('guard/accountSalary'));

    }
    public function storeGuardUnpaidSalary(Request $request){
        $guard_extra_hours = new Guards\GuardUnpaidSalaryModel();
        $guard_extra_hours = $guard_extra_hours->updateModel($request,0);
//        return view('guards.salaryLoanClearance');
        return Redirect::to(url('guard/accountUnPaid'));

    }
    public function storeEidAllowance(Request $request){
//        dd($request);
       $eid_allowance = new Guards\EidAllowanceModel();
        $eid_allowance = $eid_allowance->saveModel($request);
//        return view('guards.salaryLoanClearance');
        return Redirect::to(url('guard/payrollHolidays'));

    }
    public function updateEidAllowance(Request $request){
//        $input = $request->all();
       $eid_allowance = new Guards\EidAllowanceModel();
        $eid_allowance = $eid_allowance->updateModel($request);
//        return view('guards.salaryLoanClearance');
        return Redirect::to(url('guard/payrollHolidays'));

    }
    public function updateGuardExtraHours(Request $request){
//        dd($request);
//        $input = $request->all();
       $eid_allowance = new Guards\GuardExtraHoursModel();
        $eid_allowance = $eid_allowance->updateModel($request);
//        return view('guards.salaryLoanClearance');
        return Redirect::to(url('guard/payrollExtraHours'));

    }
    public function updateOtherDedutions(Request $request){
//        dd($request);
//        $input = $request->all();
       $edit_other_deductions = new Guards\PayrollOtherDeductionsModel();
        $edit_other_deductions = $edit_other_deductions->updateModel($request);
//        return view('guards.salaryLoanClearance');
        return Redirect::to(url('guard/payrollOtherDeductions'));

    }

    public function storeSpecialDutyAllowance(Request $request){
//        dd($request);
       $special_duty_allowance = new Guards\PayrollSpecialDuty();
        $special_duty_allowance = $special_duty_allowance->saveModel($request);
//        return view('guards.salaryLoanClearance');
        return Redirect::to(url('guard/payrollSpecialDuty'));

    }

    public function storeAllowanceType(Request $request){
//        dd($request);
       $special_duty_allowance = new Guards\AllowanceTypeModel();
        $special_duty_allowance = $special_duty_allowance->saveModel($request);
//        return view('guards.salaryLoanClearance');
        return Redirect::to(url('guard/accountSalary'));

    }
    public function storeAllowanceTypeAddition(Request $request){
//        dd($request);
       $special_duty_allowance = new Guards\AllowanceTypeAdditionModel();
        $special_duty_allowance = $special_duty_allowance->saveModel($request);
//        return view('guards.salaryLoanClearance');
        return Redirect::to(url('guard/accountSalary'));

    }
    public function storeDeductionType(Request $request){
//        dd($request);
       $special_duty_allowance = new Guards\AllowanceTypeModel();
        $special_duty_allowance = $special_duty_allowance->saveModel($request);
//        return view('guards.salaryLoanClearance');
        return Redirect::to(url('guard/accountSalary'));

    }


    public function attendance(){


        return view('guards.attendance');
    }
    public function clientAttendance(){
        $clients = new Clients();
//        $clients = $clients->getAllClientsWithoutPaginate();
        $clients = $clients->getAllActiveClientsWithoutPaginate();

        $regional_office = new Guards\RegionalOfficeModel();
        $regional_office = $regional_office->getAll();

        $data= [
            'clients' => $clients,
            'regional_office' => $regional_office,

        ];


        return view('guards.clientAttendance')->with($data);
    }


    public function attendanceResult(Request $request){

        //server side validation. check if guard is soft deleted than also handle it
        $this->validate($request,[
            'parwest_id' => 'required',
            'startDate' => 'required',
            'endDate' => 'required',
        ]);


//        assign values to variables
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $guardParwestId = $request->parwest_id;

        //$parameters to be sent back to sustain the form
        $parameters = [
            'startDate'=>$startDate,
            'endDate'=>$endDate,
            'guardParwestId'=>$guardParwestId,
        ];

        //finding guards id based on guards parwest id
        $guard = new Guards\Guards();
        $guard = $guard->where('parwest_id' , '=' ,$guardParwestId)->get();
//        dd(count($guard));
        if(count($guard) == 0){
            return view('guards.attendance')->with(['parameters' => $parameters ,
                'invalid' => 'Selected parwest ID is invalid']);

        }
        else{
            $guardId = $guard[0]->id;

            //finding the deployments based on the dates
            $clientGuardAcciciationModel = new ClientGuardsAssociation();
            $guardDeployments = $clientGuardAcciciationModel->deployedGuardByIdForAttendance($guardId , $startDate, $endDate);

            if(count($guardDeployments) > 0){
                $data = [
                    'guardDeployments'=>$guardDeployments,
                    'startDate'=>$startDate,
                    'endDate'=>$endDate,
                ];

                $guardsDataAndAttendanceEncoded = base64_encode(serialize($data));

                return view('guards.attendance')->with(['parameters' => $parameters ,
                    'guardsDataAndAttendanceEncoded' => $guardsDataAndAttendanceEncoded]);
            }
            else{

                return view('guards.attendance')->with(['parameters' => $parameters ,
                    'noRecordFound' => 'No record Found']);
            }

        }
    }
    public function clientAttendanceResult(Request $request){
//        dd($request->client_branches);

        //server side validation. check if guard is soft deleted than also handle it



//        assign values to variables
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $branchId = $request->client_branches;


        //$parameters to be sent back to sustain the form
        $parameters = [
            'startDate'=>$startDate,
            'endDate'=>$endDate,

        ];
//        $branchId = 193;


            //finding the deployments based on the dates
            $clientGuardAcciciationModel = new ClientGuardsAssociation();
            $guardDeployments = $clientGuardAcciciationModel->clientDeployedGuardByIdForAttendance($branchId , $startDate, $endDate);
//            dd($guardDeployments);
            if(count($guardDeployments) > 0){
                $data = [
                    'guardDeployments'=>$guardDeployments,
                    'startDate'=>$startDate,
                    'endDate'=>$endDate,
                ];

//                $guardsDataAndAttendanceEncoded = base64_encode(serialize($data));
//                $searchResults = json_decode($guardsDataAndAttendanceEncoded, true);
                $searchResults = $data;
//                dd($searchResults);
                $fileName = 'client base attendance ';

                Excel::create(/**
                 * @param $excel
                 */
                    $fileName, function ($excel) use ($searchResults) {
                    //dd($searchResults);
                    $attendanceMonth = array();
                    $serialArray = array();
                    $sheetsArray = array();
                    $sheetDayArray = array('01'=>'F', '02'=>'G', '03'=>'H', '04'=>'I', '05'=>'J', '06'=>'K', '07'=>'L', '08'=>'M',
                        '09'=>'N', '10'=>'O', '11'=>'P', '12'=>'Q', '13'=>'R', '14'=>'S', '15'=>'T', '16'=>'U', '17'=>'V', '18'=>'W',
                        '19'=>'X', '20'=>'Y', '21'=>'Z', '22'=>'AA', '23'=>'AB', '24'=>'AC', '25'=>'AD', '26'=>'AE', '27'=>'AF',
                        '28'=>'AG', '29'=>'AH', '30'=>'AI', '31'=>'AJ');
                    //find the numbebr of months between start date end date filters. and create a serailArray.
                    $startDate = $searchResults['startDate']; //search filter start date
                    $endDate = $searchResults['endDate']; // search filter end date

                    $begin = new DateTime( $startDate );
                    $end = new DateTime( $endDate );
                    $end = $end->modify( '+1 day' );

                    $interval = new DateInterval('P1M');
                    $daterange = new DatePeriod($begin, $interval ,$end);


                    foreach($daterange as $key=>$date){

                        $attendanceMonth[$date->format('mY')] = $date->format('M-Y');
                        $serialArray[$date->format('mY')] = $date->format('mY');
                        $sheetsArray[$date->format('mY')] = array();

                    }

//                    dd($searchResults);
                    //iterate deployment entries
                    foreach($searchResults['guardDeployments'] as $key=>$value){

                        $dayNightRegularOvertimeTotal = 0;
                        $deploymentEndDate = 0;
                        $deploymentStartDate = 0;

                        //setting start date
                        $deploymentDate = explode(' ' ,$value->created_at); //date of deployment available in db 'client_guard_association'
                        if($startDate > $deploymentDate[0] ){

                            $deploymentStartDate =new DateTime($startDate) ;

                        }else{
                            $deploymentStartDate = new DateTime($value->created_at); //date of deployment available in db 'client_guard_association'
                        }
//              setting end date

                        if($value->end_date == NULL){

                            $carbonNow = explode(' ' ,carbon::now());

                            if($endDate >= $carbonNow[0]){
                                $deploymentEndDate = new DateTime($carbonNow[0]);
                                $deploymentEndDate = $deploymentEndDate->modify( '+1 day' );
                            }
                            elseif($endDate < $carbonNow[0]){

                                $deploymentEndDate = new DateTime($endDate);
                                $deploymentEndDate = $deploymentEndDate->modify( '+1 day' );
                            }

                        }
                        elseif($value->end_date != NULL){

                            $revoketDate = explode(' ' ,$value->end_date);//revoke date available in db 'client_guard_association'
                            if($endDate > $revoketDate[0]){
                                $deploymentEndDate = new DateTime( $value->end_date);
                                $deploymentEndDate = $deploymentEndDate->modify( '+1 day' );
                            }
                            else{
                                $deploymentEndDate = new DateTime($endDate);//revoke date available in db 'client_guard_association'
                                $deploymentEndDate = $deploymentEndDate->modify( '+1 day' );
                            }
                        }


                        //interval between two dates
                        $interval = new DateInterval('P1D');
                        $deploymentDateRange = new DatePeriod($deploymentStartDate, $interval ,$deploymentEndDate);

                        //check that the deployment is not overtime
                        if($value->is_overtime == 0 || true){
                            $currentSheet = 0;


                            foreach($deploymentDateRange as $counter => $date){
                                if($currentSheet != $date->format('mY')){

                                    $currentSheet = $date->format('mY');
//                            $currentSheet = $date->format('m ([ \t.-])* YY');
//dd($searchResults);
                                    $count = count($sheetsArray[$currentSheet]);
                                    $sheetsArray[$currentSheet][$count] = array();
                                    $sheetsArray[$currentSheet][$count]['A'.($count*4+10)] =  $count + 1 ;
                                    $sheetsArray[$currentSheet][$count]['B'.($count*4+10)] = $searchResults['guardDeployments'][$key]->location_rate ;
                                    $sheetsArray[$currentSheet][$count]['C'.($count*4+10)] = $searchResults['guardDeployments'][$key]->parwest_id ;
                                    $sheetsArray[$currentSheet][$count]['D'.($count*4+10)] = $searchResults['guardDeployments'][$key]->guard_Name ;
                                    $sheetsArray[$currentSheet][$count]['E'.($count*4+10)] = "Day Regular" ;
                                    $sheetsArray[$currentSheet][$count]['E'.($count*4+11)] = "Night Regular" ;
                                    $sheetsArray[$currentSheet][$count]['E'.($count*4+12)] = "Day Double Duty" ;
                                    $sheetsArray[$currentSheet][$count]['E'.($count*4+13)] = "Night Double Duty" ;
                                    $sheetsArray[$currentSheet][$count]['AK'.($count*4+10)] = "Presents" ;
                                    $sheetsArray[$currentSheet][$count]['AK'.($count*4+11)] = "Presents" ;
                                    $sheetsArray[$currentSheet][$count]['AK'.($count*4+12)] = "Time" ;
                                    $sheetsArray[$currentSheet][$count]['AK'.($count*4+13)] = "Time" ;
                                    $sheetsArray[$currentSheet][$count]['AL'.($count*4+10)] = 0 ;
                                    $sheetsArray[$currentSheet][$count]['AL'.($count*4+11)] = 0 ;
                                    $sheetsArray[$currentSheet][$count]['AL'.($count*4+12)] = 0 ;
                                    $sheetsArray[$currentSheet][$count]['AL'.($count*4+13)] = 0 ;
                                    if(!isset($sheetsArray[$currentSheet][0]['AL9'])){
                                        $sheetsArray[$currentSheet][0]['AL9'] = 0;
                                    }
                                    $sheetsArray[$currentSheet][$count]['AN'.($count*4+10)] = $searchResults['guardDeployments'][$key]->branch_supervisor;
                                    $sheetsArray[$currentSheet][$count]['AO'.($count*4+10)] = $searchResults['guardDeployments'][$key]->branch_manager;

                                    $sheetsArray[$currentSheet][$count]['A'.($count*4+10).':A'.($count*4+13)] = 0;
                                    $sheetsArray[$currentSheet][$count]['B'.($count*4+10).':B'.($count*4+13)] = 0;
                                    $sheetsArray[$currentSheet][$count]['C'.($count*4+10).':C'.($count*4+13)] = 0;
                                    $sheetsArray[$currentSheet][$count]['D'.($count*4+10).':D'.($count*4+13)] = 0;
                                    $sheetsArray[$currentSheet][$count]['AN'.($count*4+10).':AN'.($count*4+13)] = 0;
                                    $sheetsArray[$currentSheet][$count]['AO'.($count*4+10).':AO'.($count*4+13)] = 0;
                                    if(!isset($sheetsArray[$currentSheet][0]['E5'])) {
                                        $sheetsArray[$currentSheet][0]['E5'] = 0;
                                    }
                                    if(!isset($sheetsArray[$currentSheet][0]['E6'])) {
                                        $sheetsArray[$currentSheet][0]['E6'] = 0;
                                    }
                                    if(!isset($sheetsArray[$currentSheet][0]['E7'])) {
                                        $sheetsArray[$currentSheet][0]['E7'] = 0;
                                    }

                                }


                                if($value->is_overtime == 0) {
                                    if ($searchResults['guardDeployments'][$key]->shift_day_night == 1) { //day regular
                                        $sheetsArray[$currentSheet][$count][($sheetDayArray[$date->format('d')]) . ($count * 4 + 11)] = "A";
                                        $sheetsArray[$currentSheet][$count][($sheetDayArray[$date->format('d')]) . ($count * 4 + 12)] = "A";
                                        $sheetsArray[$currentSheet][$count][($sheetDayArray[$date->format('d')]) . ($count * 4 + 13)] = "A";
                                        $sheetsArray[$currentSheet][$count][($sheetDayArray[$date->format('d')]) . ($count * 4 + 10)] = "P";
                                        $sheetsArray[$currentSheet][$count]['AL' . ($count * 4 + 10)]++;
                                        $sheetsArray[$currentSheet][0]['AL9']++;
                                        $sheetsArray[$currentSheet][0]['E5']++;
                                        $sheetsArray[$currentSheet][0]['E6']++;
                                    } else {//night regular
                                        $sheetsArray[$currentSheet][$count][($sheetDayArray[$date->format('d')]) . ($count * 4 + 10)] = "A";
                                        $sheetsArray[$currentSheet][$count][($sheetDayArray[$date->format('d')]) . ($count * 4 + 12)] = "A";
                                        $sheetsArray[$currentSheet][$count][($sheetDayArray[$date->format('d')]) . ($count * 4 + 13)] = "A";
                                        $sheetsArray[$currentSheet][$count][($sheetDayArray[$date->format('d')]) . ($count * 4 + 11)] = "P";
                                        $sheetsArray[$currentSheet][$count]['AL' . ($count * 4 + 11)]++;
                                        $sheetsArray[$currentSheet][0]['AL9']++;
                                        $sheetsArray[$currentSheet][0]['E5']++;
                                        $sheetsArray[$currentSheet][0]['E6']++;
                                    }
                                }else{

                                    if ($searchResults['guardDeployments'][$key]->shift_day_night == 1) { //day overtime / doubleduty
                                        $sheetsArray[$currentSheet][$count][($sheetDayArray[$date->format('d')]).($count * 4 + 12)] = "t";
                                        $sheetsArray[$currentSheet][$count]['AL'.($count*4+12)]++;
                                        $sheetsArray[$currentSheet][0]['AL9']++;
                                        $sheetsArray[$currentSheet][0]['E5']++;
                                        $sheetsArray[$currentSheet][0]['E7']++;

                                    } else {//night overtime / doubleduty
                                        $sheetsArray[$currentSheet][$count][($sheetDayArray[$date->format('d')]).($count * 4 + 13)] = "t";
                                        $sheetsArray[$currentSheet][$count]['AL'.($count*4+13)]++;
                                        //day night regular overtime counter increment
                                        $sheetsArray[$currentSheet][0]['AL9']++;
                                        $sheetsArray[$currentSheet][0]['E5']++;
                                        $sheetsArray[$currentSheet][0]['E7']++;
                                    }


                                }
                            }
                        }
                        else{

//                    $deploymentStartDate = new DateTime($value['created_at']);
//                    $deploymentEndDate = new DateTime( $value['end_date']);
//
//                    $deploymentEndDate = $deploymentEndDate->modify( '+1 day' );

                            if($value->end_date == NULL){

                                $carbonNow = explode(' ' ,carbon::now());

                                if($endDate > $carbonNow[0]){
                                    $deploymentEndDate = new DateTime($carbonNow[0]);
                                    $deploymentEndDate = $deploymentEndDate->modify( '+1 day' );
                                }
                                elseif($endDate < $carbonNow[0]){

                                    $deploymentEndDate = new DateTime($endDate);
                                    $deploymentEndDate = $deploymentEndDate->modify( '+1 day' );
                                }

                            }
                            elseif($value->end_date != NULL){

                                $revoketDate = explode(' ' ,$value['end_date']);//revoke date available in db 'client_guard_association'
                                if($endDate > $revoketDate[0]){
                                    $deploymentEndDate = new DateTime( $value['end_date']);
                                    $deploymentEndDate = $deploymentEndDate->modify( '+1 day' );
                                }
                                else{
                                    $deploymentEndDate = new DateTime($endDate);//revoke date available in db 'client_guard_association'
                                    $deploymentEndDate = $deploymentEndDate->modify( '+1 day' );
                                }
                            }


                            //interval between two dates
                            $interval = new DateInterval('P1D');
                            $deploymentDateRange = new DatePeriod($deploymentStartDate, $interval ,$deploymentEndDate);

                            //check that the deployment is not overtime
                            foreach($deploymentDateRange as $date) {
                                $currentSheet = 0;

                                if($currentSheet != $date->format('mY')) {

                                    $currentSheet = $date->format('mY');
                                }

                                if ($searchResults['guardDeployments'][$key]['shift_day_night'] == 1) { //day overtime / doubleduty
                                    $sheetsArray[$currentSheet][$count][($sheetDayArray[$date->format('d')]).($count * 4 + 12)] = "t";
                                    $sheetsArray[$currentSheet][$count]['AL'.($count*4+12)]++;
                                    $sheetsArray[$currentSheet][0]['AL9']++;
                                    $sheetsArray[$currentSheet][0]['E5']++;
                                    $sheetsArray[$currentSheet][0]['E7']++;

                                } else {//night overtime / doubleduty
                                    $sheetsArray[$currentSheet][$count][($sheetDayArray[$date->format('d')]).($count * 4 + 13)] = "t";
                                    $sheetsArray[$currentSheet][$count]['AL'.($count*4+13)]++;
                                    //day night regular overtime counter increment
                                    $sheetsArray[$currentSheet][0]['AL9']++;
                                    $sheetsArray[$currentSheet][0]['E5']++;
                                    $sheetsArray[$currentSheet][0]['E7']++;
                                }
                            }
                        }
                    }

                    $excel->setTitle('Guards Attandance');
                    $excel->setDescription('Guards Attandance');

                    //center align
                    $excel->getDefaultStyle()
                        ->getAlignment()
                        ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                        ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
//dd($sheetsArray);
                    foreach ($sheetsArray as $sheetName=>$arraySheet) {
//                dd($arraySheet);
                        $currentSheetName = DateTime::createFromFormat('mY', $serialArray[$sheetName])->format('M Y');
                        $excel->sheet($currentSheetName, function ($sheet) use ($searchResults, $arraySheet,$attendanceMonth,$sheetName) {

                            //setting sheet fontname
                            $sheet->setStyle(array(
                                'font' => array(
                                    'name' => 'Calibri',
                                )
                            ));

                            $sheet->mergeCells('A1:AJ1');
                            $sheet->getStyle('A1:AJ1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                            $sheet->cells('A1:AJ1', function ($cells) {

                                $cells->setBackground('#000000');
                                $cells->setFontColor('#ffffff');
                                $cells->setFont(array(
                                    'size' => '20',
                                    'bold' => true
                                ));

                            });

                            $sheet->cells('A2:A4', function ($cells) {
                                $cells->setFont(array(
                                    'bold' => true
                                ));

                            });
                            $sheet->cells('D2:D7', function ($cells) {
                                $cells->setFont(array(
                                    'bold' => true
                                ));

                            });


                            $sheet->row(1, array(
                                'Attendance Month of: '.$attendanceMonth[$sheetName]

                            ));


                            $sheet->cells('E2:AJ2', function ($cells) {
                                $cells->setBackground('#ffff00');

                                //Set all borders (top, right, bottom, left)
                                $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
                                $cells->setFont(array(
                                    'size' => '16',
                                    'bold' => true
                                ));
                            });
                            $sheet->mergeCells('e2:AJ2');
                            $sheet->row(2, array(
                                'Manager Name:',$searchResults['guardDeployments'][0]->guard_manager_name,

                            ));


                            $sheet->cells('E3:AJ3', function ($cells) {
                                $cells->setBackground('#bee0b4');
                                $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

                            });
                            $sheet->mergeCells('e3:AJ3');
                            $sheet->row(3, array(
                                'Supervisor Name:', $searchResults['guardDeployments'][0]->guard_supervisor_name,
                            ));
                            $sheet->cell('D2', function ($cell) {
                                $cell->setValue('Location');
                            });
                            $sheet->cell('D3', function ($cell) {
                                $cell->setValue('NO OF Guards');
                            });
                            $sheet->cell('D4', function ($cell) {
                                $cell->setValue('Salary');
                            });
                            $army_rate = $searchResults['guardDeployments'][0]->location_rate_army;
                            $civil_rate = $searchResults['guardDeployments'][0]->location_rate_civil;
                            $sheet->cell('E4', function ($cell) {
                                $cell->setValue('Civil');
                            });
                            $sheet->cell('F4', function ($cell) use ($civil_rate) {
                                $cell->setValue($civil_rate);
                            });
                            $sheet->cell('G4', function ($cell) {
                                $cell->setValue('Army');
                            });
                            $sheet->cell('H4', function ($cell) use($army_rate){
                                $cell->setValue($army_rate);
                            });
                            $client_branch = $searchResults['guardDeployments'][0]->branch_name;
                            $sheet->cell('E2', function ($cell) use ($client_branch) {
                                $cell->setValue($client_branch);
                            });


                            $sheet->cells('E4:AJ4', function ($cells) {
                                $cells->setBackground('#ffff00');
                                $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);


                            });
//                            $sheet->mergeCells('e4:AJ4');
//                            $sheet->row(4, array(
//                                'Introducer Name:', $searchResults['guardDeployments'][0]['introducer'], "      ", 'Guard Status', $searchResults['guardDeployments'][0]['current_status_id']
//
//                            ));


                            //total present
                            $sheet->cells('E5:AJ5', function ($cells) {
                                $cells->setBackground('#ffff00');
                                $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

                            });
                            $sheet->mergeCells('e5:AJ5');
                            $sheet->cell('D5', function ($cell) {
                                $cell->setValue('Total Present');
                            });

//                                $sheet->cell('E5', function ($cell) {
//                                    $cell->setValue('some value');
//                                });


                            //regular duty
                            $sheet->cells('E6:AJ6', function ($cells) {
                                $cells->setBackground('#ffff00');
                                $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

                            });
                            $sheet->mergeCells('E6:AJ6');
                            $sheet->cell('D6', function ($cell) {
                                $cell->setValue('Regular Duty');
                            });
//                                $sheet->cell('E6', function ($cell) {
//                                    $cell->setValue('some value');
//                                });


                            //double duty
                            $sheet->cells('E7:AJ7', function ($cells) {
                                $cells->setBackground('#ffff00');
                                $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

                            });
                            $sheet->mergeCells('E7:AJ7');
                            $sheet->cell('D7', function ($cell) {
                                $cell->setValue('Double Duty');
                            });
//                                $sheet->cell('E7', function ($cell) {
//                                    $cell->setValue('some value');
//                                });


                            $sheet->cells('A9:AO9', function ($cells) {
                                $cells->setFont(array(
                                    'bold' => true
                                ));

                                $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

                            });
                            $sheet->row(9, array(
                                'Sr. #.', 'Location Rate', 'Parwest ID', 'Guard Name', 'Shift', '1', '2', '3', '4',
                                '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18',
                                '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31',
                                'Total', '        ', 'Remarks', 'Supervisor', 'Manager'
                            ));
                            foreach ($arraySheet as $key=>$clients) {

                                foreach ($clients as $keyClient => $valueClient) {




                                    $sheet->cell($keyClient, function($cell) use ($valueClient) {
                                        // manipulate the cell
                                        $cell->setValue($valueClient);

                                        //border
                                        $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                        $cell->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
                                    });


                                    if(preg_match('/\bDay Regular\b/', $valueClient)){
                                        $sheet->cells($keyClient, function ($cells) {
                                            $cells->setBackground('#fccf7c');
                                            $cells->setFont(array(
                                                'bold' => true
                                            ));

                                        });
                                    }

                                    elseif (preg_match('/\bNight Regular\b/', $valueClient)){

                                        $sheet->cells($keyClient, function ($cells) {
                                            $cells->setBackground('#eda521');
                                            $cells->setFont(array(
                                                'bold' => true
                                            ));

                                        });

                                    }
                                    elseif(preg_match('/\bDay Double Duty\b/', $valueClient)){

                                        $sheet->cells($keyClient, function ($cells) {
                                            $cells->setBackground('#00bbff');
                                            $cells->setFont(array(
                                                'bold' => true
                                            ));

                                        });

                                    }
                                    elseif (preg_match('/\bNight Double Duty\b/', $valueClient)){

                                        $sheet->cells($keyClient, function ($cells) {
                                            $cells->setBackground('#0099d1');
                                            $cells->setFont(array(
                                                'bold' => true
                                            ));

                                        });

                                    }
                                    elseif (preg_match('/\bPresents\b/', $valueClient)){

                                        $sheet->cells($keyClient, function ($cells) {
                                            $cells->setBackground('#bee0b4');
                                            $cells->setFont(array(
                                                'bold' => true
                                            ));

                                        });

                                    }
                                    elseif (preg_match('/\bTime\b/', $valueClient)){

                                        $sheet->cells($keyClient, function ($cells) {
                                            $cells->setBackground('#b4c6e7');
                                            $cells->setFont(array(
                                                'bold' => true
                                            ));

                                        });

                                    }
                                    elseif (preg_match('/\bP\b/', $valueClient)){

                                        $sheet->cells($keyClient, function ($cells) {
                                            $cells->setBackground('#00ff00');
                                            $cells->setFont(array(
                                                'bold' => true
                                            ));

                                        });

                                    }
                                    elseif (preg_match('/\bA\b/', $valueClient)){

                                        $sheet->cells($keyClient, function ($cells) {
                                            $cells->setBackground('##ff0000');
                                            $cells->setFont(array(
                                                'bold' => true
                                            ));

                                        });

                                    }
                                    elseif (preg_match('/\bt\b/', $valueClient)){

                                        $sheet->cells($keyClient, function ($cells) {
                                            $cells->setBackground('#94bdff');
                                            $cells->setFont(array(
                                                'bold' => true
                                            ));

                                        });

                                    }
                                    elseif(strpos($keyClient , ':') == true){
                                        $sheet->mergeCells($keyClient);
                                        $sheet->getStyle($keyClient)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                    }
                                }

                            }
                        });
                    }



                })->store('xlsx', Config::get('globalvariables.$pathToSaveTemporaryExcelFiles'));


                $fileNameToDownload = $fileName . '.xlsx';

                $file_path = Config::get('globalvariables.$pathToSaveTemporaryExcelFiles') . '/' . $fileNameToDownload;
                if (file_exists($file_path)) {
                    // Send Download
                    return Response::download($file_path, $fileNameToDownload, [
                        'Content-Length: ' . filesize($file_path)
                    ])->deleteFileAfterSend(true);
                } else {
                    // Error
                    exit('Requested file does not exist on our server!');
                }


                $data = array('fileNameToDownload' => $fileNameToDownload);

                return ['responseCode' => 1, 'responseStatus' => 'successful', 'message' => 'your file is ready, and will download in few seconds', 'data' => $data];






            }



    }
    public function payrollExtraHours(){
        $active_month = new Guards\PayrollSalaryMonth();
        $active_month = $active_month->getAll();
//        dd($active_month['date_from']);
        $salary_year = $active_month['salary_year'];

        $salary_month =  substr($active_month['date_from'], 0, -3);
        $monthNum = substr($salary_month,5);
//        dd($monthNum);
//        $monthNum  = 3;
        $dateObj   = DateTime::createFromFormat('!m', $monthNum);
        $monthName = $dateObj->format('F'); // March
        $allGuardsExtraHours = new Guards\GuardExtraHoursModel();
        $allGuardsExtraHours = $allGuardsExtraHours->getAll();
//        dd($allGuardsExtraHours);

        $allClients = new Clients();
        $allClients = $allClients->getAllClientsWithoutPaginate();

        $branches = new ClientBranchesModel();
        $branches = $branches->getAll();

        $data = array(
            'allGuardsExtraHours' => $allGuardsExtraHours,
            'allClients' => $allClients,
            'branches' => $branches,
            'monthName' => $monthName,
            'salary_year' => $salary_year,
//            'regions' => $regions,

        );

        return view('guards.payroll.payrollExtraHours')->with($data);
    }
    public function payrollOtherDeductions(){
        $active_month = new Guards\PayrollSalaryMonth();
        $active_month = $active_month->getAll();
//        dd($active_month['date_from']);
        $salary_year = $active_month['salary_year'];

        $salary_month =  substr($active_month['date_from'], 0, -3);
        $monthNum = substr($salary_month,5);
//        dd($monthNum);
//        $monthNum  = 3;
        $dateObj   = DateTime::createFromFormat('!m', $monthNum);
        $monthName = $dateObj->format('F'); // March
        $allGuardsExtraHours = new Guards\PayrollOtherDeductionsModel();
        $allGuardsExtraHours = $allGuardsExtraHours->getAll();
//        dd($allGuardsExtraHours);

        $allClients = new Clients();
        $allClients = $allClients->getAllClientsWithoutPaginate();

        $branches = new ClientBranchesModel();
        $branches = $branches->getAll();

        $data = array(
            'allGuardsExtraHours' => $allGuardsExtraHours,
            'allClients' => $allClients,
            'branches' => $branches,
            'monthName' => $monthName,
            'salary_year' => $salary_year,
//            'regions' => $regions,

        );

        return view('guards.payroll.payrollOtherDeductions')->with($data);
    }
    public function payrollSpecialDuty(){
//        $allGuardsExtraHours = new Guards\GuardExtraHoursModel();
//        $allGuardsExtraHours = $allGuardsExtraHours->getAll();
//
//        $allClients = new Clients();
//        $allClients = $allClients->getAllClientsWithoutPaginate();
//
//        $branches = new ClientBranchesModel();
//        $branches = $branches->getAll();

        $special_duty = new Guards\PayrollSpecialDuty();
        $special_duty = $special_duty->getAll();

        $data = array(
//            'allGuardsExtraHours' => $allGuardsExtraHours,
//            'allClients' => $allClients,
//            'branches' => $branches,
            'special_duty' => $special_duty,
//            'regions' => $regions,

        );

        return view('guards.payroll.payrollSpecialDuty')->with($data);
    }
    public function editPayrollSpecialDuty(Request $request){
        $input = $request->all();
        $editPayrollSpecialDuty = new Guards\PayrollSpecialDuty();
        $editPayrollSpecialDuty = $editPayrollSpecialDuty->getModelById($input['id']);
        return $editPayrollSpecialDuty;
    }
    public function updateSpecialDutyAllowance(Request $request){
//        dd($request);
        $specialDuty = new Guards\PayrollSpecialDuty();
        $specialDuty = $specialDuty->updateModel($request);
        return Redirect::to(url('guard/payrollSpecialDuty'));
    }
    public function payrollHolidays(){
//        $allGuardsExtraHours = new Guards\GuardExtraHoursModel();
//        $allGuardsExtraHours = $allGuardsExtraHours->getAll();
//
//        $allClients = new Clients();
//        $allClients = $allClients->getAllClientsWithoutPaginate();
//
//        $branches = new ClientBranchesModel();
//        $branches = $branches->getAll();
//
//        $special_duty = new Guards\SpecialBranchAllownaces();
//        $special_duty = $special_duty->getAll();
        $regions =  new Guards\RegionalOfficeModel();
        $regions = $regions->getAll();

        $eidi = new Guards\EidAllowanceModel();
        $eidi = $eidi->getModelWithRegion();

        $data = array(
//            'allGuardsExtraHours' => $allGuardsExtraHours,
//            'allClients' => $allClients,
//            'branches' => $branches,
            'eidi' => $eidi,
            'regions' => $regions,

        );

        return view('guards.payroll.payrollHolidays')->with($data);


    }
    public function payrollDefaults(){
        $regions =  new Guards\RegionalOfficeModel();
        $regions = $regions->getAll();

        $payrollDefaults = new Guards\PayrollDefaultsModel();
        $payrollDefaults = $payrollDefaults->getAll();
        $data = array(
            'payrollDefaults' => $payrollDefaults,
            'regions' => $regions,

        );

        return view('guards.payroll.payrollDefaults')->with($data);

    }
    public function storePayrollDefaults(Request $request){
        $storePayrollDefaults = new Guards\PayrollDefaultsModel();
        $storePayrollDefaults = $storePayrollDefaults->saveModel($request);
        if($storePayrollDefaults == 0){

            return redirect()->back()->with('error_central', ' Region Already Exists ');
        }else{
            return redirect()->back()->with('success_central', ' Added Successfully!');

        }

    }
    public function editPayrollDefaults(Request $request){
        $input = $request->all();
        $editPayrollDefaults = new Guards\PayrollDefaultsModel();
        $editPayrollDefaults = $editPayrollDefaults->getModelById($input['id']);
        return $editPayrollDefaults;
    }
    public function updatePayrollDefault(Request $request){
        $updatePayrollDefault = new Guards\PayrollDefaultsModel();
        $updatePayrollDefault = $updatePayrollDefault->updateModel($request);
//        return Redirect::to(url('guard/payrollDefaults'));
        if($updatePayrollDefault == 0){

            return redirect()->back()->with('error_central', ' Region Already Exists ');
        }else{
            return redirect()->back()->with('success_central', ' Added Successfully!');

        }

    }
    public function payrollSalaryRule(){
//        $regions =  new Guards\RegionalOfficeModel();
//        $regions = $regions->getAll();
        $payrollSalaryRuleCategories = new Guards\PayrollSalaryRuleCategoriesModel();
        $payrollSalaryRuleCategories = $payrollSalaryRuleCategories->getAll();

        $payrollSalaryRuleAmountType = new Guards\PayrollSalaryRuleAmountTypeModel();
        $payrollSalaryRuleAmountType = $payrollSalaryRuleAmountType->getAll();

        $payrollSalaryRule = new Guards\PayrollSalaryRuleModel();
        $payrollSalaryRule = $payrollSalaryRule->getAll();
//        dd($payrollSalaryRule);
        $data = array(
            'payrollSalaryRule' => $payrollSalaryRule,
            'payrollSalaryRuleCategories' => $payrollSalaryRuleCategories,
            'payrollSalaryRuleAmountType' => $payrollSalaryRuleAmountType,
//            'regions' => $regions,

        );

        return view('guards.payroll.payrollSalaryRule')->with($data);

    }
    public function storePayrollSalaryRule(Request $request){
        $storePayrollDefaults = new Guards\PayrollSalaryRuleModel();
        $storePayrollDefaults = $storePayrollDefaults->saveModel($request);
        return \redirect()->back();

    }
    public function editPayrollSalaryRule(Request $request){
        $input = $request->all();
        $editPayrollDefaults = new Guards\PayrollSalaryRuleModel();
        $editPayrollDefaults = $editPayrollDefaults->getModelById($input['id']);
        return $editPayrollDefaults;
    }
    public function updatePayrollSalaryRule(Request $request){
        $updatePayrollSalaryRule = new Guards\PayrollSalaryRuleModel();
        $updatePayrollSalaryRule = $updatePayrollSalaryRule->updateModel($request);
        return Redirect::to(url('guard/payrollSalaryRule'));
    }
//    public function guardClearanceResult(Request $request){
////dd($request);
//        //server side validation. check if guard is soft deleted than also handle it
////        $this->validate($request,[
////            'parwest_id' => 'required',
////            'salary_month' => 'required',
////        ]);
//
//        $name = Carbon::now();
//        $file = $request->file('image_upload');
//        if($file){
//            //        dd($file);
//            $destinationPath = public_path('images');
////        $file->move($destinationPath, $name.'logo.png');
//
////        $destinationPath = 'uploads';
//            // GET THE FILE EXTENSION
//            $extension = $file->getClientOriginalExtension();
//            // RENAME THE UPLOAD WITH RANDOM NUMBER
//            $fileName = rand(11111, 99999) . '.' . $extension;
//            // MOVE THE UPLOADED FILES TO THE DESTINATION DIRECTORY
//            $upload_success = $file->move($destinationPath, $fileName);
////        $file = move_uploaded_file($_FILES['document']['name'],$destinationPath);
////        dd($upload_success);
//
//        }
//
//
//        $guardParwestId = $request->parwest_id;
//        $guardSalaryMonth = $request->salary_month;
//
//        $startDate = getStartDateByMonthDate($guardSalaryMonth);
//        $endDate = getEndDateByMonthDate($guardSalaryMonth);
//
//        $parameters = [
//            'startDate'=>$startDate,
//            'endDate'=>$endDate,
//            'guardParwestId'=>$guardParwestId,
//        ];
//
//        //finding guards id based on guards parwest id
//        $guard = new Guards\Guards();
//        $guard = $guard->where('parwest_id' , '=' ,$guardParwestId)->get();
////        dd(count($guard));
//        if(count($guard) == 0){
//            return view('guards.accountClearance')->with(['parameters' => $parameters ,
//                'invalid' => 'Selected parwest ID is invalid']);
//
//        }
//        else{
//            $guardId = $guard[0]->id;
//
//
//            $clientGuardAcciciationModel = new ClientGuardsAssociation();
//            $guardDeployments = $clientGuardAcciciationModel->deployedGuardByIdForClearance($guardId, $startDate, $endDate);
////            dd($guardDeployments);
//            if($guardDeployments){
//
//
//                $fileName = 'Clearance';
//
//
//
//                Excel::create(/**
//                 * @param $excel
//                 */
//                    $fileName, function ($excel) use ($guardDeployments) {
//                    //dd($searchResults);
//                    $attendanceMonth = array();
//                    $serialArray = array();
//                    $sheetsArray = array();
//                    $sheetDayArray = array('01'=>'F', '02'=>'G', '03'=>'H', '04'=>'I', '05'=>'J', '06'=>'K', '07'=>'L', '08'=>'M',
//                        '09'=>'N', '10'=>'O', '11'=>'P', '12'=>'Q', '13'=>'R');
//
//
//
//
//
//
//
//
//
//
//
//                    $excel->setTitle('Guards Attandance');
//                    $excel->setDescription('Guards Attandance');
//
//                    //center align
//                    $excel->getDefaultStyle()
//                        ->getAlignment()
//                        ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
//                        ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
//
//                        $currentSheetName = 'salary';
//                        $excel->sheet($currentSheetName, function ($sheet) use ($guardDeployments,$attendanceMonth) {
//                            $deployments_number = 6;
//                            $allguardDeployments = $guardDeployments;
//                            foreach($allguardDeployments  as $deployments){
////                                dd($deployments['parwest_id']);//
//                               // calculateCwf($deployments['joining_date'])
//                                $parwest_id = $deployments->parwest_id;
//                                $guard_name = $deployments->guard_Name;
//                                $location_rate = $deployments->location_rate;
//                                $guard_designation = $deployments->guard_designation;
//                                $loan = $deployments->loan;
//                                $manager = $deployments->branch_manager;
//                                $overtime = $deployments->is_overtime;
//                                $overtime_rate = $deployments->overtime_rate;
//                                $is_extra = $deployments->isExtra;
//                                $extra_hours_rate = $deployments->extra_hours_rate;
//                                $days = getDaysBetweenDates($deployments->created_at,$deployments->end_date) +1;
//                                $cost = getDaysCostByLocationRate($deployments->location_rate,$deployments->created_at,$deployments->end_date);
//
////                                dd($manager);
//                                $sheet->cell('A'.$deployments_number,$parwest_id, function ($cell,$parwest_id) {
//                                    $cell->setValue($parwest_id);
//                                });
//                                $sheet->cell('B'.$deployments_number,$guard_name, function ($cell,$guard_name) {
//                                    $cell->setValue($guard_name);
//                                });
//                                $sheet->cell('C'.$deployments_number,$guard_designation, function ($cell,$guard_designation) {
//                                    $cell->setValue($guard_designation);
//                                });
//                                $sheet->cell('D'.$deployments_number,$location_rate, function ($cell,$location_rate) {
//                                    $cell->setValue($location_rate);
//                                });
//                                $sheet->cell('E'.$deployments_number,$days, function ($cell,$days) {
//                                    $cell->setValue($days);
//                                });
//                                $sheet->cell('F'.$deployments_number,$cost, function ($cell,$cost) {
//                                    $cell->setValue($cost);
//                                });
//                                $sheet->cell('K'.$deployments_number,$cost, function ($cell,$cost) {
//                                    $cell->setValue($cost);
//                                });
//                                if($overtime == 0){
//                                    $sheet->cell('G'.$deployments_number, function ($cell) {
//                                        $cell->setValue('0');
//                                    });
//                                }else{
//                                    $sheet->cell('G'.$deployments_number,$overtime, function ($cell,$overtime) {
//                                        $cell->setValue($overtime);
//                                    });
//                                }
//
//                                $sheet->cell('H'.$deployments_number,$overtime_rate, function ($cell,$overtime_rate) {
//                                    $cell->setValue($overtime_rate);
//                                });
//
//                                $sheet->cell('R'.$deployments_number,$manager, function ($cell,$manager) {
//                                    $cell->setValue($manager);
//                                });
//
//
//
//                                $deployments_number= $deployments_number+1;
//
//                            }
//                            $sheet->cell('M'.$deployments_number,$loan, function ($cell,$loan) {
//                                $cell->setValue($loan);
//                            });
//                            $sheet->cell('A'.$deployments_number, function ($cell) {
//                                $cell->setValue('Grand Total ');
//                            });
//                            $sheet->cell('N'.$deployments_number, function ($cell) {
//                                $cell->setValue('50');
//                            });
//
//
//
//                            //setting sheet fontname
//                            $sheet->setStyle(array(
//                                'font' => array(
//                                    'name' => 'Calibri',
//                                )
//                            ));
//
//                            $sheet->mergeCells('A1:R1');
//                            $sheet->getStyle('A1:R1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//                            $sheet->cells('A1:R1', function ($cells) {
//
//                                $cells->setBackground('#ffffff');
//                                $cells->setFontColor('#000000');
//                                $cells->setFont(array(
//                                    'size' => '20',
//                                    'bold' => true
//                                ));
//
//                            });
//
//                            $sheet->cells('A2:A4', function ($cells) {
//                                $cells->setFont(array(
//                                    'bold' => true
//                                ));
//
//                            });
//                            $sheet->cells('D2:D7', function ($cells) {
//                                $cells->setFont(array(
//                                    'bold' => true
//                                ));
//
//                            });
//
//                            $sheet->mergeCells('A1:R1');
//                            $sheet->mergeCells('A2:R2');
//                            $sheet->mergeCells('A3:R3');
//                            $sheet->row(1, array(
//                                'PARWEST PACIFIC SECURITY ( PVT ) LTD.'
//
//                            ));
//                            $sheet->row(2, array(
//                                '176-CAVALARY GROUND, LAHORE CANTT.'
//
//                            ));
//                            $sheet->row(3, array(
//                                'PAY FOR THE Special Duty of  '
//
//                            ));
//
//
//
//                            $sheet->cells('A9:AO9', function ($cells) {
//                                $cells->setFont(array(
//                                    'bold' => true
//                                ));
//
//                                $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
//                                $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
//
//                            });
//                            $sheet->row(5, array(
//                                'PPS No.', 'Name', 'Desig', 'Rate', 'Days', 'Wages', 'OT', 'O.T Wages', 'Eid Days',
//                                'Eidi', 'Net Pay', 'S.BR', 'Ops Loan', 'CWF', 'Balance', 'Post', 'Sign', 'Manger'
//                            ));
////                            foreach ($arraySheet as $key=>$clients) {
////
////                                foreach ($clients as $keyClient => $valueClient) {
////
////
////
////
////                                    $sheet->cell($keyClient, function($cell) use ($valueClient) {
////                                        // manipulate the cell
////                                        $cell->setValue($valueClient);
////
////                                        //border
////                                        $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
////                                        $cell->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
////                                    });
////
////
////                                    if(preg_match('/\bDay Regular\b/', $valueClient)){
////                                        $sheet->cells($keyClient, function ($cells) {
////                                            $cells->setBackground('#fccf7c');
////                                            $cells->setFont(array(
////                                                'bold' => true
////                                            ));
////
////                                        });
////                                    }
////
////                                    elseif (preg_match('/\bNight Regular\b/', $valueClient)){
////
////                                        $sheet->cells($keyClient, function ($cells) {
////                                            $cells->setBackground('#eda521');
////                                            $cells->setFont(array(
////                                                'bold' => true
////                                            ));
////
////                                        });
////
////                                    }
////                                    elseif(preg_match('/\bDay Double Duty\b/', $valueClient)){
////
////                                        $sheet->cells($keyClient, function ($cells) {
////                                            $cells->setBackground('#00bbff');
////                                            $cells->setFont(array(
////                                                'bold' => true
////                                            ));
////
////                                        });
////
////                                    }
////                                    elseif (preg_match('/\bNight Double Duty\b/', $valueClient)){
////
////                                        $sheet->cells($keyClient, function ($cells) {
////                                            $cells->setBackground('#0099d1');
////                                            $cells->setFont(array(
////                                                'bold' => true
////                                            ));
////
////                                        });
////
////                                    }
////                                    elseif (preg_match('/\bPresents\b/', $valueClient)){
////
////                                        $sheet->cells($keyClient, function ($cells) {
////                                            $cells->setBackground('#bee0b4');
////                                            $cells->setFont(array(
////                                                'bold' => true
////                                            ));
////
////                                        });
////
////                                    }
////                                    elseif (preg_match('/\bTime\b/', $valueClient)){
////
////                                        $sheet->cells($keyClient, function ($cells) {
////                                            $cells->setBackground('#b4c6e7');
////                                            $cells->setFont(array(
////                                                'bold' => true
////                                            ));
////
////                                        });
////
////                                    }
////                                    elseif (preg_match('/\bP\b/', $valueClient)){
////
////                                        $sheet->cells($keyClient, function ($cells) {
////                                            $cells->setBackground('#00ff00');
////                                            $cells->setFont(array(
////                                                'bold' => true
////                                            ));
////
////                                        });
////
////                                    }
////                                    elseif (preg_match('/\bA\b/', $valueClient)){
////
////                                        $sheet->cells($keyClient, function ($cells) {
////                                            $cells->setBackground('##ff0000');
////                                            $cells->setFont(array(
////                                                'bold' => true
////                                            ));
////
////                                        });
////
////                                    }
////                                    elseif (preg_match('/\bt\b/', $valueClient)){
////
////                                        $sheet->cells($keyClient, function ($cells) {
////                                            $cells->setBackground('#94bdff');
////                                            $cells->setFont(array(
////                                                'bold' => true
////                                            ));
////
////                                        });
////
////                                    }
////                                    elseif(strpos($keyClient , ':') == true){
////                                        $sheet->mergeCells($keyClient);
////                                        $sheet->getStyle($keyClient)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
////                                    }
////                                }
////
////                            }
//                        });
////                    }
//
//
//
//                })->store('xlsx', Config::get('globalvariables.$pathToSaveTemporaryExcelFiles'));
//
//
//                $fileNameToDownload = $fileName . '.xlsx';
//
//                $file_path = Config::get('globalvariables.$pathToSaveTemporaryExcelFiles') . '/' . $fileNameToDownload;
//                if (file_exists($file_path)) {
//                    // Send Download
//                    return Response::download($file_path, $fileNameToDownload, [
//                        'Content-Length: ' . filesize($file_path)
//                    ])->deleteFileAfterSend(true);
//                } else {
//                    // Error
//                    exit('Requested file does not exist on our server!');
//                }
//
//
//                $data = array('fileNameToDownload' => $fileNameToDownload);
//
//                return ['responseCode' => 1, 'responseStatus' => 'successful', 'message' => 'your file is ready, and will download in few seconds', 'data' => $data];
//
//
//            }
//            else{
//
//                return Redirect::back()->withErrors(['msg', 'The Message']);
//            }
//
//        }
//
//
//
//    }
    public function guardClearanceResult(Request $request){




//        dd($request);

        $guard = new Guards\Guards();
        $guard = $guard->getByParwestId($request->parwest_id);
        $guard_id = $guard['id'];
        $reg_id = $guard['regional_office_id'];
//        dd($guard_id);
//        $clearance = new Guards\GuardClearanceModel();
//        $clearance = $clearance->saveModel($request,$guard_id);




        $active_month = new Guards\PayrollSalaryMonth();
        $active_month = $active_month->getAll();
//        dd($active_month['date_from']);

        $salary_month =  substr($active_month['date_from'], 0, -3);
//        dd($salary_month);
        $month_days = $active_month['month_days'];
        $salary_month_ac = $active_month['salary_month'];



        $month_number = $active_month['salary_month'];
        $month_year = $active_month['salary_year'];

//        dd($month_number);



        //check if the managers have not yet finalize any loan


        //genrate all deployments records and store in database

        // export with manager selection .. for this we can use  guardSalaryResult and its query by manager id



//
//        $this->validate($request,[
//            'regions' => 'required',
////            'salary_month' => 'required',
//        ]);


        $guardSalaryMonth = $request->month;
//        $guardSalaryMonth = $salary_month;
// total days from monthly date formate amd last date in month
        $total_days = $this->getDaysInMonthByDate($guardSalaryMonth);
//
        $guardSalaryMonthNo = $this->getMonthByMonthlyDate($guardSalaryMonth);
//        dd($guardSalaryMonth);
        $guardSalaryYear = $this->getYearByMonthlyDate($guardSalaryMonth);
        $startDate = $this->setDate('01',$guardSalaryMonthNo,$guardSalaryYear);
        $endDate = $this->setDate($total_days,$guardSalaryMonthNo,$guardSalaryYear);
//        dd($startDate);
//        $regionId = 0;
        $parwest_id = $request->parwest_id;

        $clientGuardAcciciationModel = new ClientGuardsAssociation();
        $guardDeployments = $clientGuardAcciciationModel->deployedGuardByRegionalOfficeForSalaryClearance($parwest_id, $startDate, $endDate);
//        dd($guardDeployments);
        if($guardDeployments){
            $clearance = new Guards\GuardClearanceModel();
            $clearance = $clearance->saveModel($request,$guard_id);



            $salaryS = new Guards\GuardDefaultClearanceModel();
            $salaryS = $salaryS->getLast();
//        dd($salaryS);
            if(isset($salaryS) ){
                $salaryHistoryID = $salaryS->salary_history_id+1;



            }else{
                $salaryHistoryID = 1 ;
            }
            $salaryHistoryPivot = new Guards\GuardClearanceHistoryStatModel();
            $salaryHistoryPivot = $salaryHistoryPivot->saveModelClearance($salaryHistoryID,$reg_id,$guardSalaryMonthNo,$parwest_id);

//dd($guardDeployments);
            foreach ($guardDeployments as  $key => $guardDeployment){
//                $startDay = strtotime('2019-11-01');
//            $createDeploy = strtotime($guardDeployment->created_at);
                $old_start_deploy = '';
                $days = 0;
//
                $old_start_deploy = $guardDeployment->created_at;



                if($old_start_deploy > $startDate){

                    $startDatess = $old_start_deploy;
                }else{
                    $startDatess = $startDate;

                }


                if($guardDeployment->end_date == null){
                    $endDay = $endDate;
                }else{
                    $endDay = $guardDeployment->end_date;
                }
//            dd($endDay);
                $days = $this->getDaysBetweenDates($startDatess,$endDay) +1;
                $guardDeployment->days = $days;
//            $guardDeployment->cost = $this->getDaysCostByLocationRate((int)$guardDeployment->location_rate,$startDate,$endDay);

                $guardDeployment->cost  = (((int)$guardDeployment->location_rate/$total_days) * (int)$guardDeployment->days);

                $guardDeployment->startDay = $startDatess;
                $guardDeployment->endDay = $endDay;

//
//            dd($guardDeployment);
                $salary = new Guards\GuardDefaultClearanceModel();
                $salary = $salary->saveModel($guardDeployment,$salaryHistoryID,$reg_id);

                $payrollSalaryRule = new Guards\PayrollSalaryRuleModel();
                $payrollSalaryRule = $payrollSalaryRule->getAll();

                $gross = 0 ;
                $payrollSalaryRuleDetail = 0 ;
                foreach ($payrollSalaryRule as $salaryRule){

                    $payrollSalaryRuleDetail = new Guards\PayrollClearanceRuleDetailModel();
                    $payrollSalaryRuleDetail = $payrollSalaryRuleDetail->saveModel($salaryRule,$guardDeployment,$reg_id,$salaryHistoryID);
//                if($payrollSalaryRuleDetail != true || is_numeric($payrollSalaryRuleDetail) || $payrollSalaryRuleDetail >0){
//                    if($salaryRule->code == 'NPY'){
////                        dd($gross + $payrollSalaryRuleDetail);
//                        $objectToSave = new Guards\PayrollSalaryRuleDetailModel();
//                        $objectToSave = $objectToSave->getModelByids($guardDeployment->id,$salaryRule->id);
//                        $objectToSave->salary_rule_value = $gross + $payrollSalaryRuleDetail;
//                        $objectToSave->save();
//                    }else{
//                        $gross  += (int)$payrollSalaryRuleDetail;
//
//                    }

//                }
//                if($payrollSalaryRuleDetail == true){
//                    if($salaryRule->code == 'GRS'){
////                        dd($gross);
//                        $objectToSave = new Guards\PayrollSalaryRuleDetailModel();
//                        $objectToSave = $objectToSave->getModelByids($guardDeployment->id,$salaryRule->id);
//                        $objectToSave->salary_rule_value = $gross;
//                        $objectToSave->save();
//                    }
//                }
                }
            }
            $salaryDeduction = new Guards\GuardClearanceHistoryStatModel();
            $salaryDeduction = $salaryDeduction->salaryDeduction($guard_id,$reg_id,$salaryHistoryID,$startDate,$endDate,$month_number,$month_year);





            return redirect()->back()->with(['success_central' => 'Salary Genrated ']);

        }else{
            return redirect()->back()->with(['fail_central' => 'Deployment not exists !!!']);
        }







    }
    public function getLoanFinalize(Request $request)
    {
        $input = $request->all();
        $user_id = Auth::guard('user')->id();


//
        $get_unfinalisedLoans = new Guards\GuardLoansModel();
        $get_unfinalisedLoans = $get_unfinalisedLoans->getUnfinalisedLoansById($user_id);

//        $date = Carbon::now()->format('D-M-Y');
        $monthIn = new PayrollSalaryMonth();
        $monthIn = $monthIn->getAll();
        $month = $monthIn['salary_month'];
        $year= $monthIn['salary_year'];
        $setFinalize = new Guards\UsersFinalizeLoan();
        $setFinalize = $setFinalize->store($month,$year);
//        dd($get_unfinalisedLoans);

        $storeFinalisedLoansByUserHistory = new Guards\UserFinaliseLoanHistoryModel();
        $storeFinalisedLoansByUserHistory = $storeFinalisedLoansByUserHistory->saveModel($get_unfinalisedLoans,$setFinalize->id);


        $loans = new Guards\GuardLoansModel();
        $loans = $loans->changeStatusByUserId($user_id);
        return \redirect()->back();
    }
    public function getLoanExport(Request $request){
        $input = $request->all();
        $user_id =   Auth::guard('user')->id();
//        $user_name =   Auth::guard('user')->name;
        $user = new UserModel();
        $user = $user->getUserById($user_id);
//        dd($user['name']);


//        $loans = new Guards\GuardLoansModel();
//        $loans = $loans->getModelByUserId($user_id);
        $notFinalisedloans = new Guards\GuardLoansModel();
//        $notFinalisedloans = $notFinalisedloans->getModelByUserIdAndStatus($user_id);
        $notFinalisedloans = $notFinalisedloans->getModelByParwestIdWithLocation($user_id);
//        dd($notFinalisedloans);
        $loans = $notFinalisedloans;
        $loan['manager'] = $user['name'];
//dd($loans);
        if(count($loans) == 0){

        }
        else{


            if(count($loans) > 0){



                $fileName = 'Operation Loan';
                $sheetsArray = array();


                Excel::create(/**
                 * @param $excel
                 */
                    $fileName, function ($excel) use ($loans,$user) {
                    //dd($searchResults);


                    $excel->setTitle('Guards Attandance');
                    $excel->setDescription('Guards Attandance');

                    //center align
                    $excel->getDefaultStyle()
                        ->getAlignment()
                        ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                        ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

                        $currentSheetName = 'Operation Loan';

                    $excel->sheet($currentSheetName, function ($sheet) use ($loans,$user) {
                        $sheet->row(1, array(
                            'PARWEST PACIFIC SECURITY ( PVT ) LTD.'

                        ));
                        $sheet->row(2, array(
                            '176-CAVALARY GROUND, LAHORE CANTT.'

                        ));
                        $sheet->row(3, array(
                            'Loans Details | Before Finalize  |   '.$user['name']

                        ));

                        $deployments_number = 6;
                        $grand_total = 0;
                        $SUM = 0;
                            $number_of_rows_with_total = 0 ;
                            $temp_g_id = '';
                        $sheet->cells('A5:L5', function ($cells) {
                            $cells->setBackground('#C4BD97');
                            $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                            $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);


                        });
                            foreach ($loans as $loan) {
//                            dd($loan);

                                if ($temp_g_id && $temp_g_id != $loan['parwest_id']) {

//                                    $grand_total = $grand_total + $SUM;
                                    $sheet->cells('A'.$deployments_number.':L'.$deployments_number, function ($cells) {
                                        $cells->setBackground('#E8E8E8');
                                        $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                        $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);


                                    });
                                    $sheet->getCell('H'.$deployments_number)->setValueExplicit($SUM, \PHPExcel_Cell_DataType::TYPE_NUMERIC);

                                    $sheet->cell('A' . $deployments_number, function ($cell) {
                                        $cell->setValue('Sub Total');
                                    });
                                    $SUM = 0;
                                    $deployments_number++;
                                }
                                $parwest_id = $loan['parwest_id'];
                                $temp_g_id = $loan['parwest_id'];
                                $status = $loan['status'];
                                $designation = 'guard';
                                $name = $loan['guard_name'];
                                $phone_number = $loan['guards_phone'];

                                $slip_number = $loan['slip_number_loan'];
                                $date = $loan['created_at'];
                                $paid_date = $loan['loan_paid_to_guard_date'];
                                $days = $loan['deployment_days'];
                                $amount = $loan['amount_paid'];
                                $SUM = $SUM + $amount;
                                $supervisor = $loan['guards_current_supervisor'];
                                $manager = $user['name'];
                                $location = $loan['current_deployment'];

                                $sheet->row(5, array(
                                    ' Date ', 'PPS No.', 'Name', 'Designation', 'Phone Number', 'Slip Number', 'Days', 'Amount', 'location', 'supervisor', 'Manager', 'Loan Paid Date'
                                ));
                                $sheet->cell('A' . $deployments_number, $date, function ($cell, $date) {
                                    $cell->setValue($date);
                                });
                                $sheet->cell('B' . $deployments_number, $parwest_id, function ($cell, $parwest_id) {
                                    $cell->setValue($parwest_id);
                                });
//                            $sheet->cell('C'.$deployments_number,$status, function ($cell,$status) {
//                                $cell->setValue($status);
//                            });
                                $sheet->cell('C' . $deployments_number, $name, function ($cell, $name) {
                                    $cell->setValue($name);
                                });
                                $sheet->cell('D' . $deployments_number, $designation, function ($cell, $designation) {
                                    $cell->setValue($designation);
                                });
                                $sheet->cell('G' . $deployments_number, $days, function ($cell, $days) {
                                    $cell->setValue($days);
                                });
                                $sheet->cell('E' . $deployments_number, $phone_number, function ($cell, $phone_number) {
                                    $cell->setValue($phone_number);
                                });
                                $sheet->cell('F' . $deployments_number, $slip_number, function ($cell, $slip_number) {
                                    $cell->setValue($slip_number);
                                });

//                                $sheet->cell('H' . $deployments_number, $amount, function ($cell, $amount) {
//                                    $cell->setValue($amount);
//                                });
                                $sheet->getCell('H'.$deployments_number)->setValueExplicit($amount, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
//                             $sheet->cell('J'.$deployments_number,$date, function ($cell,$date) {
//                                                            $cell->setValue($date);
//                                                        });
                                $sheet->cell('I' . $deployments_number, $location, function ($cell, $location) {
                                    $cell->setValue($location);
                                });
                                $sheet->cell('J' . $deployments_number, $supervisor, function ($cell, $supervisor) {
                                    $cell->setValue($supervisor);
                                });
                                $sheet->cell('K' . $deployments_number, $manager, function ($cell, $manager) {
                                    $cell->setValue($manager);
                                });
                                $sheet->cell('L' . $deployments_number, $paid_date, function ($cell, $paid_date) {
                                    $cell->setValue($paid_date);
                                });
                                $grand_total =$grand_total + $loan['amount_paid'];
                                $deployments_number = $deployments_number + 1;

                        }
                        $sheet->cells('A'.$deployments_number.':L'.$deployments_number, function ($cells) {
                            $cells->setBackground('#E8E8E8');
                            $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                            $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);


                        });
                        $sheet->cell('A'.$deployments_number, function ($cell) {
                            $cell->setValue('Sub Total');
                        });
                        $sheet->getCell('H'.$deployments_number)->setValueExplicit($SUM, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $deployments_number ++;
//                        $sheet->cells('A'.$deployments_number.':AO'.$deployments_number, function ($cells) {
//                            $cells->setFont(array(
//                                'bold' => true
//                            ));
//
//                            $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
//                            $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
//
//                        });
                        $sheet->cells('A'.$deployments_number.':L'.$deployments_number, function ($cells) {
                            $cells->setBackground('#E8E8E8');
                            $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                            $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);


                        });
                        $sheet->cell('A'.$deployments_number, function ($cell) {
                            $cell->setValue('Grand Total');
                        });
                        $sheet->getCell('H'.$deployments_number)->setValueExplicit($grand_total, \PHPExcel_Cell_DataType::TYPE_NUMERIC);

//                        $sheet->cell('H'.$deployments_number,$grand_total, function ($cell,$grand_total) {
//                            $cell->setValue($grand_total);
//                        });
//                                if($overtime == 0){
//                                    $sheet->cell('G'.$deployments_number, function ($cell) {
//                                        $cell->setValue('0');
//                                    });
//                                }else{
//                                    $sheet->cell('G'.$deployments_number,$overtime, function ($cell,$overtime) {
//                                        $cell->setValue($overtime);
//                                    });
//                                }
//
//                                $sheet->cell('H'.$deployments_number,$overtime_rate, function ($cell,$overtime_rate) {
//                                    $cell->setValue($overtime_rate);
//                                });
//
//                                $sheet->cell('R'.$deployments_number,$manager, function ($cell,$manager) {
//                                    $cell->setValue($manager);
//                                });



//                                $deployments_number= $deployments_number+1;

//                            }
//                            $sheet->cell('M'.$deployments_number,$loan, function ($cell,$loan) {
//                                $cell->setValue($loan);
//                            });
//                            $sheet->cell('A'.$deployments_number, function ($cell) {
//                                $cell->setValue('Grand Total ');
//                            });
//                            $sheet->cell('N'.$deployments_number, function ($cell) {
//                                $cell->setValue('50');
//                            });



                            //setting sheet fontname
                            $sheet->setStyle(array(
                                'font' => array(
                                    'name' => 'Calibri',
                                )
                            ));

                            $sheet->mergeCells('A1:R1');
                            $sheet->getStyle('A1:R1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                            $sheet->cells('A1:R1', function ($cells) {

                                $cells->setBackground('#ffffff');
                                $cells->setFontColor('#000000');
                                $cells->setFont(array(
                                    'size' => '20',
                                    'bold' => true
                                ));

                            });

                            $sheet->cells('A2:A4', function ($cells) {
                                $cells->setFont(array(
                                    'bold' => true
                                ));

                            });
//                            $sheet->cells('D2:D7', function ($cells) {
//                                $cells->setFont(array(
//                                    'bold' => true
//                                ));
//
//                            });

                            $sheet->mergeCells('A1:R1');
                            $sheet->mergeCells('A2:R2');
                            $sheet->mergeCells('A3:R3');







                        });



                })->store('xlsx', Config::get('globalvariables.$pathToSaveTemporaryExcelFiles'));


                $fileNameToDownload = $fileName . '.xlsx';

                $file_path = Config::get('globalvariables.$pathToSaveTemporaryExcelFiles') . '/' . $fileNameToDownload;
                if (file_exists($file_path)) {
                    // Send Download
                    return Response::download($file_path, $fileNameToDownload, [
                        'Content-Length: ' . filesize($file_path)
                    ])->deleteFileAfterSend(true);
                } else {
                    // Error
                    exit('Requested file does not exist on our server!');
                }


                $data = array('fileNameToDownload' => $fileNameToDownload);

                return ['responseCode' => 1, 'responseStatus' => 'successful', 'message' => 'your file is ready, and will download in few seconds', 'data' => $data];


            }
            else{

                return view('guards.attendance')->with(['noRecordFound' => 'No record Found']);
            }

        }



    }
    public function payrollLoanDemand(){
        $payroll_loan_demand = new Guards\PayrollLoanDemandModel();
        $payroll_loan_demand = $payroll_loan_demand->getAll();
//        dd($payroll_loan_demand);
        $users = new UserModel();
        $users = $users->getAllManagers();

        $data = array(
//            'allGuardsExtraHours' => $allGuardsExtraHours,
//            'allClients' => $allClients,
//            'branches' => $branches,
            'payroll_loan_demand' => $payroll_loan_demand,
            'users' => $users,

        );

        return view('guards.payroll.payrollLoanDemand')->with($data);

    }
    public function storePayrollLoanDemand(Request $request){
//        dd($request);
        $loan_demand = new Guards\PayrollLoanDemandModel();
        $loan_demand = $loan_demand->saveModel($request);
        return Redirect::to(url('guard/payrollLoanDemand'));

    }
    public function editEidi(Request $request){
        $input = $request->all();
        $eidi = new Guards\EidAllowanceModel();
        $eidi = $eidi->getModelById($input['id']);
        return $eidi;
    }
    public function editOtherDeductions(Request $request){
        $input = $request->all();
        $other_deduction = new Guards\PayrollOtherDeductionsModel();
        $other_deduction = $other_deduction->getModelById($input['parwest_id']);
        return $other_deduction;
    }
    public function editExtrahours(Request $request){
        $input = $request->all();
        $eidi = new Guards\GuardExtraHoursModel();
        $eidi = $eidi->getModelById($input['id']);
        return $eidi;
    }


    public function getLoanExportHistory(Request $request){
        $input = $request->all();

//        dd($input['tags_id']);
        $get_finalised_history_by_loan = new Guards\UserFinaliseLoanHistoryModel();
        $get_finalised_history_by_loan = $get_finalised_history_by_loan->getModelByValue($input['tags_id']);
//        dd($get_finalised_history_by_loan);

        $user_id =   Auth::guard('user')->id();
//        $user_name =   Auth::guard('user')->name;
        $user = new UserModel();
        $user = $user->getUserById($user_id);
//        dd($user['name']);


//        $loans = new Guards\GuardLoansModel();
//        $loans = $loans->getModelByUserId($user_id);
        $notFinalisedloans = new Guards\GuardLoansModel();
        $total = new Guards\GuardLoansModel();
        $total = $total->getTotal($user_id);
//        $notFinalisedloans = $notFinalisedloans->getModelByUserIdAndStatus($user_id);
        $notFinalisedloans = $notFinalisedloans->getModelByParwestIdWithLocation($user_id);
//        dd($notFinalisedloans);
        $loans = $get_finalised_history_by_loan;
        $loan['manager'] = $user['name'];
//dd($loans);
        if(count($loans) == 0){

        }
        else{


            if(count($loans) > 0){



                $fileName = 'Operation Loan';
                $sheetsArray = array();


                Excel::create(/**
                 * @param $excel
                 */
                    $fileName, function ($excel) use ($loans,$user,$total) {
                    //dd($searchResults);


                    $excel->setTitle('Guards Attandance');
                    $excel->setDescription('Guards Attandance');

                    //center align
                    $excel->getDefaultStyle()
                        ->getAlignment()
                        ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                        ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

                    $currentSheetName = 'Operation Loan';

                    $excel->sheet($currentSheetName, function ($sheet) use ($loans,$user,$total) {
                        $deployments_number = 6;
                        $SUM = 0;
                        $grand_total = 0;
                        $number_of_rows_with_total = 0 ;
                        $temp_g_id = '';
                        $sub_amount = 0 ;
                        $sheet->cells('A5:L5', function ($cells) {
                            $cells->setBackground('#C4BD97');
                            $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                            $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);


                        });
                        foreach ($loans as $loan) {
//                            dd($loans);
                            $sub_amount = $sub_amount + $loan->amount_paid;
                            $grand_total = $grand_total + $loan->amount_paid;
                            if ($temp_g_id && $temp_g_id != $loan->parwest_id) {
                                $sheet->cells('A' . $deployments_number . ':L' . $deployments_number, function ($cells) {
                                    $cells->setBackground('#E8E8E8');
                                    $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                    $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);


                                });
                                $sheet->getCell('H' . $deployments_number)->setValueExplicit($SUM, \PHPExcel_Cell_DataType::TYPE_NUMERIC);

                                $sheet->cell('A' . $deployments_number, function ($cell) {
                                    $cell->setValue('Sub Total');
                                });
                                $SUM = 0;
                                $deployments_number++;
//                                $sheet->cell('A'.$deployments_number, function ($cell) {
//                                    $cell->setValue("Sub Total");
//                                });
//                                $sub_amount =  $sub_amount - $loan->amount_paid;
//                                $sheet->cell('I'.$deployments_number,$sub_amount, function ($cell,$sub_amount) {
//                                    $cell->setValue($sub_amount);
//                                });
//
//                                $deployments_number ++;
//                                $sub_amount = 0;


                            }
                            $parwest_id = $loan->parwest_id;
                            $temp_g_id = $loan->parwest_id;
                            $status = $loan->status;
                            $designation = 'guard';
                            $name = $loan->guard_name;
                            $phone_number = $loan->guards_phone;
//                            dd($phone_number);
                            $slip_number = $loan->slip_number_loan;
                            $date = $loan->created_at;
                            $paid_date = $loan->loan_paid_to_guard_date;
                            $days = $loan->deployment_days;
                            $amount = $loan->amount_paid;
                            $SUM = $SUM + $amount;
                            $supervisor = $loan->guards_current_supervisor;
                            $manager = $user->name;
                            $location = $loan->current_deployment;
//                            dd($location);
//                            if($location = null){
//                                $location = $loan['current_deployment'];
//                                dd($location);
//                            }


//                            foreach($loan  as $deployments){

                            //  dd($deployments);//
                            // calculateCwf($deployments['joining_date'])
//                                $parwest_id = $deployments['parwest_id'];
//                                $guard_name = $deployments['guard_Name'];
//                                $location_rate = $deployments['location_rate'];
//                                $guard_designation = $deployments['guard_designation'];
//                                $loan = $deployments['loan'];
//                                $manager = $deployments['branch_manager'];
//                                $overtime = $deployments['is_overtime'];
//                                $overtime_rate = $deployments['overtime_rate'];
//                                $is_extra = $deployments['isExtra'];
//                                $extra_hours_rate = $deployments['extra_hours_rate'];
//                                $days = getDaysBetweenDates($deployments['created_at'],$deployments['end_date']) +1;
//                                $cost = getDaysCostByLocationRate($deployments['location_rate'],$deployments['created_at'],$deployments['end_date']);

//                                dd($manager);
                            $sheet->cell('A' . $deployments_number, $date, function ($cell, $date) {
                                $cell->setValue($date);
                            });
                            $sheet->cell('B' . $deployments_number, $parwest_id, function ($cell, $parwest_id) {
                                $cell->setValue($parwest_id);
                            });
//                            $sheet->cell('C'.$deployments_number,$status, function ($cell,$status) {
//                                $cell->setValue($status);
//                            });
                            $sheet->cell('C' . $deployments_number, $designation, function ($cell, $designation) {
                                $cell->setValue($designation);
                            });
                            $sheet->cell('D' . $deployments_number, $name, function ($cell, $name) {
                                $cell->setValue($name);
                            });
                            $sheet->cell('E' . $deployments_number, $days, function ($cell, $days) {
                                $cell->setValue($days);
                            });
                            $sheet->cell('F' . $deployments_number, $phone_number, function ($cell, $phone_number) {
                                $cell->setValue($phone_number);
                            });
                            $sheet->cell('G' . $deployments_number, $slip_number, function ($cell, $slip_number) {
                                $cell->setValue($slip_number);
                            });
                            $sheet->getCell('H' . $deployments_number)->setValueExplicit($amount, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
//                            $sheet->cell('I'.$deployments_number,$amount, function ($cell,$amount) {
//                                $cell->setValue($amount);
//                            });
//                             $sheet->cell('J'.$deployments_number,$date, function ($cell,$date) {
//                                                            $cell->setValue($date);
//                                                        });
                            $sheet->cell('I' . $deployments_number, $location, function ($cell, $location) {
                                $cell->setValue($location);
                            });
                            $sheet->cell('J' . $deployments_number, $supervisor, function ($cell, $supervisor) {
                                $cell->setValue($supervisor);
                            });
                            $sheet->cell('K' . $deployments_number, $manager, function ($cell, $manager) {
                                $cell->setValue($manager);
                            });
                            $sheet->cell('L' . $deployments_number, $paid_date, function ($cell, $paid_date) {
                                $cell->setValue($paid_date);
                            });
//                            $grand_total =$grand_total + $loan->amount_paid;
                            $deployments_number = $deployments_number + 1;

                        }

//                                if($overtime == 0){
//                                    $sheet->cell('G'.$deployments_number, function ($cell) {
//                                        $cell->setValue('0');
//                                    });
//                                }else{
//                                    $sheet->cell('G'.$deployments_number,$overtime, function ($cell,$overtime) {
//                                        $cell->setValue($overtime);
//                                    });
//                                }
//
//                                $sheet->cell('H'.$deployments_number,$overtime_rate, function ($cell,$overtime_rate) {
//                                    $cell->setValue($overtime_rate);
//                                });
//
//                                $sheet->cell('R'.$deployments_number,$manager, function ($cell,$manager) {
//                                    $cell->setValue($manager);
//                                });



//                                $deployments_number= $deployments_number+1;

//                            }
//                            $sheet->cell('M'.$deployments_number,$loan, function ($cell,$loan) {
//                                $cell->setValue($loan);
//                            });
//                            $sheet->cell('A'.$deployments_number, function ($cell) {
//                                $cell->setValue('Grand Total ');
//                            });
//                            $sheet->cell('N'.$deployments_number, function ($cell) {
//                                $cell->setValue('50');
//                            });



                            //setting sheet fontname
                            $sheet->setStyle(array(
                                'font' => array(
                                    'name' => 'Calibri',
                                )
                            ));

                            $sheet->mergeCells('A1:R1');
                            $sheet->getStyle('A1:R1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                            $sheet->cells('A1:R1', function ($cells) {

                                $cells->setBackground('#ffffff');
                                $cells->setFontColor('#000000');
                                $cells->setFont(array(
                                    'size' => '20',
                                    'bold' => true
                                ));

                            });

                            $sheet->cells('A2:A4', function ($cells) {
                                $cells->setFont(array(
                                    'bold' => true
                                ));

                            });
//                            $sheet->cells('D2:D7', function ($cells) {
//                                $cells->setFont(array(
//                                    'bold' => true
//                                ));
//
//                            });

                            $sheet->mergeCells('A1:R1');
                            $sheet->mergeCells('A2:R2');
                            $sheet->mergeCells('A3:R3');
                            $sheet->row(1, array(
                                'PARWEST PACIFIC SECURITY ( PVT ) LTD.'

                            ));
                            $sheet->row(2, array(
                                '176-CAVALARY GROUND, LAHORE CANTT.'

                            ));
                            $sheet->row(3, array(
                                'Loans Details | After Finalized  | '.$user['name']

                            ));


//                            $sheet->cells('E2:AJ2', function ($cells) {
//                                $cells->setBackground('#ffff00');
//
//                                //Set all borders (top, right, bottom, left)
//                                $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
//                                $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
//                                $cells->setFont(array(
//                                    'size' => '16',
//                                    'bold' => true
//                                ));
//                            });
//                            $sheet->mergeCells('e2:AJ2');
//                            $sheet->row(2, array(
//                                'Manager Name:',$searchResults['guardDeployments'][0]['guard_manager_name'], "      ", $searchResults['guardDeployments'][0]['parwest_id'], $searchResults['guardDeployments'][0]['guard_Name']
//
//                            ));


//                            $sheet->cells('E3:AJ3', function ($cells) {
//                                $cells->setBackground('#bee0b4');
//                                $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
//                                $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
//
//                            });
//                            $sheet->mergeCells('e3:AJ3');
//                            $sheet->row(3, array(
//                                'Supervisor Name:', $searchResults['guardDeployments'][0]['guard_supervisor_name'], "      ", 'Guard Status', $searchResults['guardDeployments'][0]['ex_service']
//
//                            ));


//                            $sheet->cells('E4:AJ4', function ($cells) {
//                                $cells->setBackground('#ffff00');
//                                $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
//                                $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
//
//
//                            });
//                            $sheet->mergeCells('e4:AJ4');
//                            $sheet->row(4, array(
//                                'Introducer Name:', $searchResults['guardDeployments'][0]['introducer'], "      ", 'Guard Status', $searchResults['guardDeployments'][0]['current_status_id']
//
//                            ));


                            //total present
//                            $sheet->cells('E5:AJ5', function ($cells) {
//                                $cells->setBackground('#ffff00');
//                                $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
//                                $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
//
//                            });
//                            $sheet->mergeCells('e5:AJ5');
//                            $sheet->cell('D5', function ($cell) {
//                                $cell->setValue('Total Present');
//                            });

//                                $sheet->cell('E5', function ($cell) {
//                                    $cell->setValue('some value');
//                                });


                            //regular duty
//                            $sheet->cells('E6:AJ6', function ($cells) {
//                                $cells->setBackground('#ffff00');
//                                $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
//                                $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
//
//                            });
//                            $sheet->mergeCells('E6:AJ6');
//                            $sheet->cell('D6', function ($cell) {
//                                $cell->setValue('Regular Duty');
//                            });
//                                $sheet->cell('E6', function ($cell) {
//                                    $cell->setValue('some value');
//                                });


                            //double duty
//                            $sheet->cells('E7:AJ7', function ($cells) {
//                                $cells->setBackground('#ffff00');
//                                $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
//                                $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
//
//                            });
//                            $sheet->mergeCells('E7:AJ7');
//                            $sheet->cell('D7', function ($cell) {
//                                $cell->setValue('Double Duty');
//                            });
//                                $sheet->cell('E7', function ($cell) {
//                                    $cell->setValue('some value');
//                                });



                            $sheet->row(5, array(
                                ' Date ', 'PPS No.', 'Designation', 'Name','Days' , 'Phone Number', 'Slip Number', 'Amount','location','supervisor','Manager','Loan Paid Date'
                            ));

//                        $sheet->cell('A'.$deployments_number, function ($cell) {
//                            $cell->setValue("Sub Total");
//                        });
//                        $sub_amount =  $sub_amount - $loan->amount_paid;
//
//                        if($sub_amount >0){
//                            $sheet->cell('I'.$deployments_number,$sub_amount, function ($cell,$sub_amount) {
//                                $cell->setValue(abs(1));
//                            });
//                        }else{
////                            $sub_amount =    $loan->amount_paid ;
//                            $sheet->cell('I'.$deployments_number,$sub_amount, function ($cell,$sub_amount) {
//                                $cell->setValue(abs('1'));
//                            });
//                        }
//                        dd($sub_amount);
                        $sheet->cells('A'.$deployments_number.':L'.$deployments_number, function ($cells) {
                            $cells->setBackground('#E8E8E8');
                            $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                            $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);


                        });
                        $sheet->getCell('H'.$deployments_number)->setValueExplicit($SUM, \PHPExcel_Cell_DataType::TYPE_NUMERIC);

                        $sheet->cell('A' . $deployments_number, function ($cell) {
                            $cell->setValue('Sub Total');
                        });
                        $SUM = 0;
//                        $deployments_number++;


                        $deployments_number ++;
                        $sub_amount = 0;
                        $deployments_number ++;
//                        $sheet->cells('A'.$deployments_number.':AO'.$deployments_number, function ($cells) {
//                            $cells->setFont(array(
//                                'bold' => true
//                            ));
//
//                            $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
//                            $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
//
//                        });
                        $sheet->cells('A'.$deployments_number.':L'.$deployments_number, function ($cells) {
                            $cells->setBackground('#E8E8E8');
                            $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                            $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);


                        });
                        $sheet->cell('A'.$deployments_number, function ($cell) {
                            $cell->setValue('Grand Total');
                        });
                        $sheet->getCell('H'.$deployments_number)->setValueExplicit($grand_total, \PHPExcel_Cell_DataType::TYPE_NUMERIC);

//                        $sheet->cell('I'.$deployments_number,$total, function ($cell,$total) {
//                            $cell->setValue($total);
//                        });
                    });



                })->store('xlsx', Config::get('globalvariables.$pathToSaveTemporaryExcelFiles'));


                $fileNameToDownload = $fileName . '.xlsx';

                $file_path = Config::get('globalvariables.$pathToSaveTemporaryExcelFiles') . '/' . $fileNameToDownload;
                if (file_exists($file_path)) {
                    // Send Download
                    return Response::download($file_path, $fileNameToDownload, [
                        'Content-Length: ' . filesize($file_path)
                    ])->deleteFileAfterSend(true);
                } else {
                    // Error
                    exit('Requested file does not exist on our server!');
                }


                $data = array('fileNameToDownload' => $fileNameToDownload);

                return ['responseCode' => 1, 'responseStatus' => 'successful', 'message' => 'your file is ready, and will download in few seconds', 'data' => $data];


            }
            else{

                return view('guards.attendance')->with(['noRecordFound' => 'No record Found']);
            }

        }



    }
    public function getRulesValuesByDeploymentId($dId,$hId){
        $salaryRulesValues = new Guards\PayrollSalaryRuleDetailModel();
        $salaryRulesValues = $salaryRulesValues->getRulesValuesByDeploymentId($dId,$hId);
//        dd($salaryRulesValues);
        return $salaryRulesValues;
    }
    public function getRulesValuesByDeploymentIdClearance($dId,$hId){
        $salaryRulesValues = new Guards\PayrollClearanceRuleDetailModel();
        $salaryRulesValues = $salaryRulesValues->getRulesValuesByDeploymentId($dId,$hId);
//        dd($salaryRulesValues);
        return $salaryRulesValues;
    }
    public function getRulesValuesByDeploymentIdSheet($dId,$hId,$manager_id,$supervisor_id){
        $salaryRulesValues = new Guards\PayrollSalaryRuleDetailModel();
        $salaryRulesValues = $salaryRulesValues->getRulesValuesByDeploymentIdSheet($dId,$hId,$manager_id,$supervisor_id);
//        dd($salaryRulesValues);
        return $salaryRulesValues;
    }
    public function toNum($data) {
        $alphabet = array( 'a', 'b', 'c', 'd', 'e',
            'f', 'g', 'h', 'i', 'j',
            'k', 'l', 'm', 'n', 'o',
            'p', 'q', 'r', 's', 't',
            'u', 'v', 'w', 'x', 'y',
            'z'
        );
        $alpha_flip = array_flip($alphabet);
        $return_value = -1;
        $length = strlen($data);
        for ($i = 0; $i < $length; $i++) {
            $return_value +=
                ($alpha_flip[$data[$i]] + 1) * pow(26, ($length - $i - 1));
        }
        return $return_value;
    }

    function getcolumnrange($min,$max){
        $pointer=strtoupper($min);
        $output=array();
        while($this->positionalcomparison($pointer,strtoupper($max))<=0){
            array_push($output,$pointer);
            $pointer++;
        }
        return $output;
    }

    function positionalcomparison($a,$b){
        $a1=$this->stringtointvalue($a); $b1=$this->stringtointvalue($b);
        if($a1>$b1)return 1;
        else if($a1<$b1)return -1;
        else return 0;
    }
    function getExtraData(Request $request){
        $status = array();
//        $date = $request->date;
//        $date = $date.'-01';
//        return $date.'-01';
//        $status = 'false';
        $status['date'] = 'false';
        $status['location'] = 'false';

        $parwest_id = $request->parwest_id;
        $guard = new Guards\Guards();
        $guard = $guard->getByParwestId($parwest_id);

        $guard_id =  $guard['id'];;
        $client_id = $request->client_id;
        $branch_id = $request->branch_id;
        $checkDeploy = new ClientGuardsAssociation();
        $checkDeploy = $checkDeploy->getCurrentDataOfClient($guard_id,$client_id,$branch_id);
//        return $checkDeploy['id'];
        if($checkDeploy['id'] > 0){

            $status['location'] =  'true';
        }else{
            $status['location'] =  'false';
        }
//            $initialiseMonth = new PayrollSalaryMonth();
//            $initialiseMonth = $initialiseMonth->getAll();
//
////            foreach($checkDeploy as $deploy){
//                $deployment_start_date = date('Y-m-d', strtotime($checkDeploy['created_at']));
//
//                if($checkDeploy['end_date'] == null || $checkDeploy['end_date'] == ''){
//                    $deployment_end_date = $initialiseMonth['date_to'];
//                    if($date >= $deployment_start_date && $date <= $deployment_end_date){
//                        $status['date'] =  'true';
//                    }else{
//                        $status['date'] =  'false';
//                    }
//
//                }else{
//                    $deployment_start_date = $initialiseMonth['date_from'];
//                    $deployment_end_date = $initialiseMonth['date_to'];
//                    if($date >= $deployment_start_date && $date <= $deployment_end_date){
//                        $status['date'] =  'true';
//                    }else{
//                        $status['date'] = 'false';
//                    }
//                }
////            }

        return $status;

    }
    function stringtointvalue($str){
        $amount=0;
        $strarra=array_reverse(str_split($str));

        for($i=0;$i<strlen($str);$i++){
            $amount+=(ord($strarra[$i])-64)*pow(26,$i);
        }
        return $amount;
    }
    public function getSalaryExportHistorySheet(Request $request){
        $active_month = new Guards\PayrollSalaryMonth();
        $active_month = $active_month->getAll();
//        dd($active_month['date_from']);
        $salary_year = $active_month['salary_year'];


        $salary_month =  substr($active_month['date_from'], 0, -3);
        $monthNum = substr($salary_month,5);
//        dd($monthNum);
//        $monthNum  = 3;
        $dateObj   = DateTime::createFromFormat('!m', $monthNum);
        $monthName = $dateObj->format('F'); // March
        set_time_limit(0);
        error_reporting(E_ALL);
        $input = $request->all();
        $sal_month =  substr($input['salary_month'], 5);
        $sal_year =  substr($input['salary_month'], 0, -3);
//        dd($request);
        $dateObj1   = DateTime::createFromFormat('!m', $sal_month);
        $monthName1 = $dateObj1->format('F'); // March
        $monthName =$monthName1;
        $manager_id = null;
        $supervisor_id = null;
        if(isset($input['manager'])){

            $manager_id = $input['manager'];
        }if(isset($input['supervisor'])){

            $supervisor_id = $input['supervisor'];
        }
//        $monthNum = substr($salary_month,5);$manager_id,$supervisor_id
//        $colString = PHPExcel_Cell::stringFromColumnIndex(1);
        $colString = $this->getcolumnrange('A','ZZ');
        $rules = new Guards\PayrollSalaryRuleModel();
        $rules = $rules->getAll();
//        dd($colString);
        $number = count($rules);
        $endAlpha = $colString[$number];
//        dd($colString[$number]);

//        dd($input['tags_id']);
        $get_history = new Guards\PayrollPostIndicatorModel();
        $get_history = $get_history->getModelByValue($input['region'],$sal_month,$sal_year);
//        dd($get_history['history_id']);
        $get_finalised_history_by_loan = new Guards\GuardSalaryModel();
        $get_finalised_history_by_loan = $get_finalised_history_by_loan->getModelByValueSheet($get_history['history_id'],$input['region'],$manager_id,$supervisor_id);
//        dd($get_finalised_history_by_loan);
        $history_id =  $get_history['history_id'];
        $regional_office = $input['region'];
        $regional = new Guards\RegionalOfficeModel();
        $regional = $regional->getModelById($regional_office);
//        dd($regional['office_head']);
        $reg = $regional['office_head'];
        $user_id =   Auth::guard('user')->id();
//        $user_name =   Auth::guard('user')->name;
        $user = new UserModel();
        $loans = $get_finalised_history_by_loan;
        if(count($loans) == 0){

        }
        else{


            if(count($loans) > 0){

                $fileName = 'salary'." ".$salary_month." ".$reg;

//                if($request->salary_month){
//                    $fileName = 'salary'.$request->salary_month;
//                }else{
//
//                }
                $sheetsArray = array();


                Excel::create(/**
                 * @param $excel
                 */
                    $fileName, function ($excel) use ($loans,$user,$endAlpha,$colString,$regional_office,$monthName,$salary_year,$reg,$history_id,$manager_id,$supervisor_id) {
                    //dd($searchResults);


                    $excel->setTitle('Guards Attandance');
                    $excel->setDescription('Guards Attandance');
//                    $sheet->setAllBorders('thin');
//                    $excel->setActiveSheetIndex(0);

//                    center align
//                    $excel->getDefaultStyle()
//                        ->getAlignment()
//                        ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
//                        ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

                    $currentSheetName = 'Operation Salary';
                    $excel->sheet($currentSheetName, function ($sheet) use ($loans,$user,$endAlpha,$colString,$regional_office,$monthName,$salary_year,$reg,$history_id,$manager_id,$supervisor_id) {
                        $deployments_number = 6;
                        $temp_g_id = 0;
                        $temp_g_real_id = 0;
                        $temp_name = 0;
                        $temp_supervisor = 0;
                        $temp_manager = 0;
                        $temp_g_sd = 0;
                        $temp_g_ed = 0;
                        $loanp = 0;
                        $grand_loan = 0;
                        $pa = 0;
                        $grand_ap = 0;
                        $dsbv = 0;
                        $grand_sbv = 0;
                        $grand_misc = 0;
                        $dapsaa = 0;
                        $grand_apsaa = 0;
                        $dcwf = 0;
                        $grand_cwf = 0;
                        $grand_sdw = 0;

                        $rate= 0;
                        $days_p=0;
                        $days_for_each = 0;
                        $wages_for_each = 0;
                        $ot_days_for_each = 0;
                        $ot_days_wages_for_each = 0;
                        $ex_hours = 0;
                        $ex_hours_wages = 0;
                        $special_duty_wages = 0;
                        $holidays = 0 ;
                        $holidays_wages = 0 ;
                        $foreach_holidays_wages = 0 ;
                        $np_foreach = 0;
                        $apsa_foreach = 0;
                        $net_foreach = 0;
                        $ot_total_for_each = 0;

                        $grand_ot_days_for_each = 0;
                        $grand_ot_days_wages_for_each = 0;
                        $grand_ex_hours = 0;
                        $grand_ex_hours_wages = 0;
                        $grand_special_duty_wages = 0;
                        $grand_holidays = 0 ;
                        $grand_holidays_wages = 0 ;
                        $grand_np_foreach = 0;
                        $grand_apsa_foreach = 0;
                        $grand_net_foreach = 0;

                        $np_bf_total_for_each = 0;
                        $grand_total_for_rate = 0;
                        $np_af_total_for_each = 0;

                        $grand_total_for_days = 0;
                        $grand_total_for_wages = 0;
                        $grand_sum = 0;

                        foreach ($loans as $loan) {
//                            dd($loans);

                            $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {

                                $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

                            });
                            if($temp_g_id && $temp_g_id!= $loan['parwest_id']){
                                $sd = $this->calculateSd($temp_g_id,$regional_office);
                                if($sd){
                                    foreach ($sd as $special_duty){

//                                    $deployments_number ++;
//                                        dd($special_duty);
                                        $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {



                                            $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                            $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

                                        });
                                        $sheet->getStyle('E'.$deployments_number.':W'.$deployments_number)->getNumberFormat()->setFormatCode('_(* #,##0_);_(* (#,###);_(* "-"??_);_(@_)');

//                                    dd($loan);
                                        $sheet->getCell('A'.$deployments_number)->setValueExplicit($temp_g_id);
                                        $sheet->getCell('B'.$deployments_number)->setValueExplicit($temp_name);
                                        $sheet->getCell('Z'.$deployments_number)->setValueExplicit($temp_manager);
                                        $sheet->getCell('AA'.$deployments_number)->setValueExplicit($temp_supervisor);

                                        $sheet->getCell('M'.$deployments_number)->setValueExplicit($special_duty['special_duty_hours'], \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                        $sheet->getCell('N'.$deployments_number)->setValueExplicit($special_duty['special_duty_hour_rate'], \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                        $sheet->getCell('O'.$deployments_number)->setValueExplicit(($special_duty['special_duty_hours'] * $special_duty['special_duty_hour_rate']), \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                        $sheet->getCell('Y'.$deployments_number)->setValueExplicit($special_duty['special_duty_remarks'] );

//                                    });
                                        $sd_wagess= 0;
                                        $sd_wagess = $special_duty['special_duty_hours'] * $special_duty['special_duty_hour_rate'];
                                        $np_foreach = $np_foreach + ($special_duty['special_duty_hours'] * $special_duty['special_duty_hour_rate']);
                                        $sheet->getCell('R'.$deployments_number)->setValueExplicit($sd_wagess, \PHPExcel_Cell_DataType::TYPE_NUMERIC );
                                        $deployments_number ++;
//                                        $np_foreach = $np_foreach + ($special_duty['special_duty_hours'] * $special_duty['special_duty_hour_rate']);
//                                        $sheet->getCell('Q'.$deployments_number)->setValueExplicit($np_foreach, \PHPExcel_Cell_DataType::TYPE_NUMERIC );
//                                        $deployments_number ++;
                                    }
                                }


                            }
                            if($temp_g_id && $temp_g_id!= $loan['parwest_id']){
                                $loanp = new GuardLoansModel();
                                $loanp = $loanp->getLoansByGuardWithInterval($temp_g_id,$temp_g_sd,$temp_g_ed);
                                if($loanp){
                                    foreach ($loanp as $loanslist){

//                                    $deployments_number ++;
//                                        dd($special_duty);
                                        $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {



                                            $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                            $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

                                        });
                                        $sheet->getStyle('E'.$deployments_number.':W'.$deployments_number)->getNumberFormat()->setFormatCode('_(* #,##0_);_(* (#,###);_(* "-"??_);_(@_)');

//                                    dd($loan);
                                        $sheet->getCell('A'.$deployments_number)->setValueExplicit($temp_g_id);
                                        $sheet->getCell('B'.$deployments_number)->setValueExplicit($temp_name);
                                        $sheet->getCell('T'.$deployments_number)->setValueExplicit($loanslist->amount_paid, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                        $sheet->getCell('Y'.$deployments_number)->setValueExplicit($loanslist->supervisor_name);
                                        $sheet->getCell('Z'.$deployments_number)->setValueExplicit($temp_manager);
                                        $sheet->getCell('AA'.$deployments_number)->setValueExplicit($temp_supervisor);

//                                        $sheet->getCell('M'.$deployments_number)->setValueExplicit($special_duty['special_duty_hours'], \PHPExcel_Cell_DataType::TYPE_NUMERIC);
//                                        $sheet->getCell('N'.$deployments_number)->setValueExplicit($special_duty['special_duty_hour_rate'], \PHPExcel_Cell_DataType::TYPE_NUMERIC);
//                                        $sheet->getCell('O'.$deployments_number)->setValueExplicit(($special_duty['special_duty_hours'] * $special_duty['special_duty_hour_rate']), \PHPExcel_Cell_DataType::TYPE_NUMERIC);
//                                        $sheet->getCell('Y'.$deployments_number)->setValueExplicit($special_duty['special_duty_remarks'] );

//                                    });
                                        $sd_wagess= 0;
//                                        $sd_wagess = $special_duty['special_duty_hours'] * $special_duty['special_duty_hour_rate'];
//                                        $np_foreach = $np_foreach + ($special_duty['special_duty_hours'] * $special_duty['special_duty_hour_rate']);
//                                        $sheet->getCell('R'.$deployments_number)->setValueExplicit($sd_wagess, \PHPExcel_Cell_DataType::TYPE_NUMERIC );
                                        $deployments_number ++;
//                                        $np_foreach = $np_foreach + ($special_duty['special_duty_hours'] * $special_duty['special_duty_hour_rate']);
//                                        $sheet->getCell('Q'.$deployments_number)->setValueExplicit($np_foreach, \PHPExcel_Cell_DataType::TYPE_NUMERIC );
//                                        $deployments_number ++;
                                    }
                                }


                            }

                            if($temp_g_id && $temp_g_id!= $loan['parwest_id']){
//                                $sbv = $this->calculateSBV($temp_g_id,$regional_office,$days_p);
//                                if($temp_g_id == 'L-10358'){
//                                    dd($sbv);
//                                }
//                                $sheet->setBorder('A5:AA'.$deployments_number, 'thin');
//                                $sheet->getStyle('D'.$deployments_number.':W'.$deployments_number)->getNumberFormat()->setFormatCode('#,##0_);(#,##0)');
                                $sheet->getStyle('E'.$deployments_number.':X'.$deployments_number)->getNumberFormat()->setFormatCode('_(* #,##0_);_(* (#,###);_(* "-"??_);_(@_)');
//                                $sheet->getStyle('A'.$deployments_number.':AA'.$deployments_number)->setBackground('#CCCCCC');
//                                $sheet->getStyle('A'.$deployments_number.':AA'.$deployments_number)->applyFromArray(array(
//                                    'fill' => array(
////                                        'type'  => PHPExcel_Style_Fill::FILL_SOLID,
//                                        'color' => array('rgb' => 'FF0000')
//                                    )
//                                ));
//                                $sheet->cells('A1:D1', function ($cells) {
//                                    $cells->setBackground('#008686');
//                                    $cells->setAlignment('center');
//                                });
                                $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {
                                    $cells->setBackground('#E8E8E8');
                                    $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                    $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);


                                });


//                                $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {
//
//                                    $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
//                                    $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
//
//                                });
//                                if($special_duty_wages > 0){
//
//
//                                    $sheet->getCell('N'.$deployments_number)->setValueExplicit($special_duty_wages, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
//
//                                    $deployments_number ++;
//                                    $special_duty_wages = 0;
//                                }else{
//                                    dd($temp_g_id);

                                $sheet->cell('A'.$deployments_number, function ($cell) {
                                        $cell->setValue('Sub Total');
                                    });
                                $sheet->cell('B'.$deployments_number,$temp_g_id, function ($cell,$temp_g_id) {
                                        $cell->setValue($temp_g_id);
                                    });

//                                    $sheet->getCell('D'.$deployments_number)->setValueExplicit($rate, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $sheet->getCell('F'.$deployments_number)->setValueExplicit($days_for_each, \PHPExcel_Cell_DataType::TYPE_NUMERIC);

                                $sheet->getCell('G'.$deployments_number)->setValueExplicit($wages_for_each, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $sheet->getCell('H'.$deployments_number)->setValueExplicit($ot_days_for_each, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $sheet->getCell('I'.$deployments_number)->setValueExplicit($ot_days_wages_for_each, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $sheet->getCell('J'.$deployments_number)->setValueExplicit($ex_hours, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $sheet->getCell('K'.$deployments_number)->setValueExplicit($ex_hours_wages, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
//                                    $sheet->getCell('M'.$deployments_number)->setValueExplicit($special_duty_wages, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
//                                $sheet->getCell('N'.$deployments_number)->setValueExplicit($holidays, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $grand_ap = $grand_ap+$pa;
                                $sheet->getCell('L'.$deployments_number)->setValueExplicit($pa, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                round($holidays_wages,2);
                                    $sheet->getCell('Q'.$deployments_number)->setValueExplicit($foreach_holidays_wages, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $sheet->getCell('R'.$deployments_number)->setValueExplicit($np_foreach, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
//                                $loanp = new GuardLoansModel();
//                                $loanp = $loanp->getModelByGuardWithInterval($temp_g_id,$temp_g_sd,$temp_g_ed);

                                $deduction = new Guards\PayrollDeductionModel();
                                $deduction = $deduction->getByUserIdn($temp_g_real_id,$history_id);
//                                dd($temp_g_real_id);
                                $cw = 0;
                                $sbv=0;
                                $misc=0;
                                $loanp  = $deduction['oploan_amount'];
                                $cw  = $deduction['cwf_amount'];
                                $sbv  = $deduction['sbv_amount'];
                                $misc  = $deduction['misc_amount'];
                                $ap  = $deduction['apsa_amount'];
                                $grand_loan = $grand_loan + $loanp;

                                $sheet->getCell('T'.$deployments_number)->setValueExplicit($loanp, \PHPExcel_Cell_DataType::TYPE_NUMERIC);

//                                $ap = $this->calculateApsa($temp_g_id,$regional_office);
                                    $grand_apsaa = $grand_apsaa + $ap;

//                                    $sbv = $this->calculateSBV($temp_g_id,$regional_office,$days_for_each);
                                    $grand_sbv = $grand_sbv + $sbv;
                                    $grand_misc = $grand_misc + $misc;

//

//                                $status = $this->calculateGuardStatus($temp_g_id);
//                                if($status == 1){
//                                    $cwf = new PayrollDefaultsModel();
//                                    $cwf = $cwf->getModelByGuardWithInterval($regional_office);
//                                    $cw = $cwf['cwf_value'];
//
//                                }
                                $grand_cwf = $grand_cwf + $cw;

                                $sdt= $this->calculateSdTotal($temp_g_id,$regional_office);
//                                dd($sdt);




                                $sum_np = $np_foreach  - $loanp -$ap - $cw -$sbv - $misc;
                                $grand_sum = $grand_sum + $sum_np;
//                                  $sum_np = $np_foreach - $loanp  - $cw;
//                                $grand_sum = $grand_sum + $sum_np;
                                $grand_sdw = $grand_sdw + $sdt;
                                $sheet->getCell('O'.$deployments_number)->setValueExplicit($sdt, \PHPExcel_Cell_DataType::TYPE_NUMERIC);

                                $sheet->getCell('U'.$deployments_number)->setValueExplicit($ap, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
//                                    $sheet->getCell('T'.$deployments_number)->setValue($ap);
//                                $sheet->cell('T'.$deployments_number,$ap, function ($cell,$ap) {
//                                    $cell->setValue($ap);
//                                });
                                    $sheet->getCell('V'.$deployments_number)->setValueExplicit($cw, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                    $sheet->getCell('W'.$deployments_number)->setValueExplicit($misc, \PHPExcel_Cell_DataType::TYPE_NUMERIC);

                                    $sheet->getCell('X'.$deployments_number)->setValueExplicit($sum_np, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                    $sheet->getCell('S'.$deployments_number)->setValueExplicit($sbv, \PHPExcel_Cell_DataType::TYPE_NUMERIC);

//                                    $sheet->cell('R'.$deployments_number,$sbv, function ($cell,$sbv) {
//                                        $cell->setValue($sbv);
//                                    });
//                                $sheet->getStyle('D'.$deployments_number.':W'.$deployments_number)->getNumberFormat()->setFormatCode('#,##0_);(#,##0)');
                                $sheet->getStyle('E'.$deployments_number.':X'.$deployments_number)->getNumberFormat()->setFormatCode('_(* #,##0_);_(* (#,###);_(* "-"??_);_(@_)');

//                                    $sheet->cells('A'.$deployments_number.':AO'.$deployments_number, function ($cells) {
//
//                                        $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
//                                        $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
//
//                                    });


                                    $deployments_number ++;
                                    $rate = 0;
                                    $days_for_each = 0;
                                    $wages_for_each = 0;
                                    $ot_days_for_each = 0;
                                    $ot_days_wages_for_each = 0;
                                    $ex_hours = 0;
                                    $ex_hours_wages = 0;
                                    $special_duty_wages = 0;
                                    $holidays = 0;
                                    $holidays_wages = 0;
                                    $np_foreach = 0;
                                    $apsa_foreach = 0;
                                    $net_foreach = 0;
                                    $pa =0;
                                    $loanp =0;
                                $foreach_holidays_wages = 0;


                                    $ot_total_for_each = 0;

                                    $np_bf_total_for_each = 0;

                                    $np_af_total_for_each = 0;


//                                }

                            }

                            $sheet->row(1, array(
                                'PARWEST PACIFIC SECURITY ( PVT ) LTD.'

                            ));
                            $sheet->row(2, array(
                                '176-CAVALARY GROUND, LAHORE CANTT.'

                            ));
                            $sheet->row(3, array(
                                'Salary for the month of   '.$monthName."  ".$salary_year

                            ));
                            $sheet->row(4, array(
                                'Region :     '.$reg."  "

                            ));
                            $sheet->getStyle('A5:AA5')->getAlignment()->setWrapText(true);
////                            $sheet->getColumnDimension('A')->setWidth(0);
                            $sheet->getStyle('A5:AA5')->getAlignment()->applyFromArray(
                                    array(
                                         'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                        'vertical'   => \PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                        'rotation'   => 0,
                                        'wrap'       => TRUE
                                    )
                              );
//                            $sheet->setAllBorders('thin');
//                            $sheet->getDefaultStyle('A5:AA5')->getAlignment()
//
//                            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
//                            ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);


//                            $sheet->getStyle('A5:AA5')->applyFromArray([
//                                'alignment' => array(
//                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
//                                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
//                                    'rotation'   => 0,
//                                    'wrap'       => true
//                                )]);
//                            $sheet->getStyle('A5:AA5')->getColumnDimension('G')->setWidth(13);
//                            $sheet->setWidth('G', 12);
//                            $sheet->getColumnDimension('G')->setWidth(12);
                            $rules = new Guards\PayrollSalaryRuleModel();
                            $rules = $rules->getAll();

                            $number = count($rules);
//                            dd($number);

                            for($i = 0; $i < $number; $i++){

                                    $value = $rules[$i]->name;
                                    $code = $rules[$i]->code;
//
                                if($code == 'MGI' || $code == 'SPI'){
                                    //notjomog

                                }else{
                                    if(isset($value)){

                                        $sheet->cell($colString[$i].'5',$value, function ($cell,$value) {
                                            $cell->setValue($value);
                                        });
                                    }
                                }


                            }


                            $temp_g_id = $loan['parwest_id'];
                            $temp_g_real_id = $loan['guard_id'];
                            $temp_name  = $loan['guard_Name'];
                            $temp_supervisor = $loan['guard_supervisor_name'];
                            $temp_manager = $loan['guard_manager_name'];
                            //                                        $sheet->getCell('B'.$deployments_number)->setValueExplicit($loan['guard_Name']);
//                                        $sheet->getCell('Y'.$deployments_number)->setValueExplicit($loan['guard_manager_name']);
//                                        $sheet->getCell('AA'.$deployments_number)->setValueExplicit($loan['guard_supervisor_name']);


                            $days_p = $loan['days'];
                            $temp_g_sd = $loan['startDay'];
                            $temp_g_ed = $loan['endDay'];

                            if($loan['is_overtime'] == 0){
                                $rate =  $rate + $loan['location_rate'];
                                $days_for_each =  $days_for_each + $loan['days'];
                                $wages_for_each =  $wages_for_each + ($loan['location_rate'] / 31)* $loan['days'];
                                $wages_for_each = (int)$wages_for_each;
                            }


                            $ot_total_for_each =  $ot_total_for_each + $loan['is_overtime'] * $loan['overtime_cost'];




                            $days = $loan['days'];
                            $wages = ($loan['location_rate'] / 31)* $loan['days'];


                            $eh = $loan['is_extra'];
                            $eh_wages = $loan['extrahours_cost'];

                            $count = count($rules);

                            $rule_values  = $this->getRulesValuesByDeploymentIdSheet($loan['deployment_id'],$history_id,$manager_id,$supervisor_id);
//                            dd($rule_values);
                            for($i = 0; $i < $number; $i++){
                                $value = 0;
                                if(isset($count)){

                                    $value = $rule_values[$i]->salary_rule_value;
                                    $code = $rule_values[$i]->code;

                                    if($rule_values[$i]->code == 'RAT'){
                                        $grand_total_for_rate = $grand_total_for_rate + (int)$value;

                                    }
                                    if($rule_values[$i]->code == 'DAY'){
                                        $grand_total_for_days = $grand_total_for_days + (int)$value;

                                    }
                                    if($rule_values[$i]->code == 'WGS'){
                                        $np_af_total_for_each = $np_af_total_for_each + (int)$value;
                                        $grand_total_for_wages = $grand_total_for_wages + (int)$value;

                                    }
                                    if($rule_values[$i]->code == 'OTD'){
                                        $ot_days_for_each = $ot_days_for_each + (int)$value;
                                        $grand_ot_days_for_each = $grand_ot_days_for_each + (int)$value;

//

                                    }if($rule_values[$i]->code == 'OTW'){
                                        $ot_days_wages_for_each = $ot_days_wages_for_each + (int)$value;
                                        $grand_ot_days_wages_for_each = $grand_ot_days_wages_for_each + (int)$value;
//

                                    }if($rule_values[$i]->code == 'EHC'){
                                        $ex_hours = $ex_hours + (int)$value;
                                        $grand_ex_hours = $grand_ex_hours + (int)$value;
//

                                    }if($rule_values[$i]->code == 'EHW'){
                                        $ex_hours_wages = $ex_hours_wages + (int)$value;
                                        $grand_ex_hours_wages = $grand_ex_hours_wages + (int)$value;
//

                                    }if($rule_values[$i]->code == 'SPD'){
                                        $special_duty_wages = $special_duty_wages + (int)$value;
                                        $grand_special_duty_wages = $grand_special_duty_wages + (int)$value;
//

                                    }if($rule_values[$i]->code == 'HOD'){
                                        $holidays = $holidays + (int)$value;
                                        $grand_holidays = $grand_holidays + (int)$value;
//

                                    }if($rule_values[$i]->code == 'HOW'){


                                        $holidays_wages = $holidays_wages + (int)$value;
                                        $grand_holidays_wages = $grand_holidays_wages + (int)$value;
                                        $foreach_holidays_wages = $foreach_holidays_wages + (int)$value;

//

                                    }if($rule_values[$i]->code == 'GRS'){
                                        $np_foreach = $np_foreach + (int)$value;
                                        $np_foreach = (int)$np_foreach;
                                        $grand_np_foreach = $grand_np_foreach + (int)$value;
//

                                    }
                                    if($rule_values[$i]->code == 'APS'){
                                        $apsa_foreach = $apsa_foreach + (int)$value;
                                        $grand_apsa_foreach = $grand_apsa_foreach + (int)$value;
//

                                    }if($rule_values[$i]->code == 'NPY'){
                                        $net_foreach = $net_foreach + (int)$value;
                                        $grand_net_foreach = $grand_net_foreach + (int)$value;
//

                                    }
                                    if($code == 'POA'){
                                        //notjomog
                                        $pa = $pa + (int)$value;

                                    }
//                                    $sheet->getCell($colString[$i].$deployments_number)->setValueExplicit($value, \PHPExcel_Cell_DataType::TYPE_NUMERIC);

//
                                    if($code == 'MGI' || $code == 'SPI'){
                                        //notjomog

                                    }
//                                    elseif($code == 'RAT' && $loan['is_overtime'] == 1){
////                                        dd($value);
//
//                                    }
                                    else{
                                        $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
//                                        0
//                                        if (is_numeric($value)) {


//                                            $value = (int)$value;
//                                            if(ctype_digit($value)){
//                                                $value =  number_format($value);

//                                                $sheet->getCell($colString[$i].$deployments_number)->setValueExplicit($value, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                                $sheet->getCell($colString[$i].$deployments_number)->setValue($value);
//                                                $cell->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

//                                            }



//                                            else{
////                                                $value =  number_format($value);
//                                                $sheet->cell($colString[$i].$deployments_number,$value,$thinBorder, function ($cell,$value,$thinBorder) {
////                                                    $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);
//                                                    $cell->setValue($value);
//                                                    $cell->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
//
//                                                });
//                                            }

//                                        } else {
////                                            $value =  number_format($value);
//                                            $sheet->cell($colString[$i].$deployments_number,$value,$thinBorder, function ($cell,$value,$thinBorder) {
////                                                    $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);
////                                                $cell->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
//                                                $cell->setValue($value);
//                                            });
////                                                                               $cell->setValue($value);
//                                        }


                                    }

                                }
                            }

//                            $grand_total_for_days = $grand_total_for_days + $days;
//                            $grand_total_for_wages = $grand_total_for_wages + $wages;
//                            $grand_total_for_wages = (int)$grand_total_for_wages;
//
//                            $sheet->getStyle('D'.$deployments_number.':W'.$deployments_number)->getNumberFormat()->setFormatCode('#,##0_);(#,##0)');
                            $sheet->getStyle('E'.$deployments_number.':X'.$deployments_number)->getNumberFormat()->setFormatCode('_(* #,##0_);_(* (#,###);_(* "-"??_);_(@_)');

                            $deployments_number = $deployments_number+1;

                            $sheet->cells('A5:AA5', function ($cells) {
                                $cells->setFont(array(
                                    'bold' => true
                                ));

                                $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

                            });
//                            $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {
//
//
//                                $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
//                                $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
//
//                            });

                            $sheet->setStyle(array(
                                'font' => array(
                                    'name' => 'Calibri',
                                ),
                                'setSize' => array(
                                    'height' => '35',
                                )
                            ));
//
                            $sheet->mergeCells('A1:R1');
                            $sheet->getStyle('A1:R1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);



                            $sheet->cells('A1:R1', function ($cells) {

                                $cells->setBackground('#ffffff');
                                $cells->setFontColor('#000000');
                                $cells->setFont(array(
                                    'size' => '20',
                                    'bold' => true
                                ));

                            });
                            $sheet->cells('A3:Z3', function ($cells) {

                                $cells->setBackground('#ffffff');
                                $cells->setFontColor('#000000');
                                $cells->setFont(array(
                                    'size' => '12',
                                    'bold' => true,

                                ));

                            });
                            $sheet->cells('A5:Z5', function ($cells) {

                                $cells->setBackground('#ffffff');
                                $cells->setFontColor('#000000');
                                $cells->setFont(array(
                                    'size' => '12',
                                    'bold' => true,

                                ));

                            });

                            $sheet->cells('A2:A4', function ($cells) {
                                $cells->setFont(array(
                                    'bold' => true
                                ));

                            });
//
//                            $sheet->getStyle()->getNumberFormat()->setFormatCode('#,##0_);(#,##0)');
                            $sheet->getStyle()->getNumberFormat()->setFormatCode('_(* #,##0_);_(* (#,###);_(* "-"??_);_(@_)');

                            $sheet->mergeCells('A1:R1');
                            $sheet->mergeCells('A2:R2');
                            $sheet->mergeCells('A3:R3');
//                            $sheet->setWidth('A', 12);
                            $sheet->setWidth(array(
                                'A'     =>  13,
                                'B'     =>  30,
                                'C'     =>  15,
                                'D'     =>  15,
                                'E'     =>  15,
                                'F'     =>  15,
                                'G'     =>  15,
                                'H'     =>  15,
                                'I'     =>  15,
                                'J'     =>  15,
                                'K'     =>  15,
                                'L'     =>  15,
                                'M'     =>  15,
                                'N'     =>  15,
                                'O'     =>  15,
                                'P'     =>  15,
                                'Q'     =>  15,
                                'R'     =>  15,
                                'S'     =>  15,
                                'T'     =>  15,
                                'U'     =>  15,
                                'V'     =>  15,
                                'W'     =>  15,
                                'X'     =>  25,
                                'Y'     =>  25,
                                'Z'     =>  25,
                                'AA'     =>  25
                            ));
//                            $sd = $this->calculateSd($temp_g_id,$regional_office);
//                            if($sd){
//                                foreach ($sd as $special_duty){
//
////                                    $deployments_number ++;
////                                        dd($special_duty);
//                                    $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {
//
//
//
//                                        $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
//                                        $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
//
//                                    });
//                                    $sheet->getStyle('D'.$deployments_number.':W'.$deployments_number)->getNumberFormat()->setFormatCode('_(* #,##0_);_(* (#,###);_(* "-"??_);_(@_)');
//
////                                    dd($loan);
//                                    $sheet->getCell('A'.$deployments_number)->setValueExplicit($temp_g_id);
//                                    $sheet->getCell('B'.$deployments_number)->setValueExplicit($loan['guard_Name']);
//                                    $sheet->getCell('Y'.$deployments_number)->setValueExplicit($loan['guard_manager_name']);
//                                    $sheet->getCell('AA'.$deployments_number)->setValueExplicit($loan['guard_supervisor_name']);
//
//                                    $sheet->getCell('L'.$deployments_number)->setValueExplicit($special_duty['special_duty_hours'], \PHPExcel_Cell_DataType::TYPE_NUMERIC);
//                                    $sheet->getCell('M'.$deployments_number)->setValueExplicit($special_duty['special_duty_hour_rate'], \PHPExcel_Cell_DataType::TYPE_NUMERIC);
//                                    $sheet->getCell('N'.$deployments_number)->setValueExplicit(($special_duty['special_duty_hours'] * $special_duty['special_duty_hour_rate']), \PHPExcel_Cell_DataType::TYPE_NUMERIC);
//                                    $sheet->getCell('X'.$deployments_number)->setValueExplicit($special_duty['special_duty_remarks'] );
//
////                                    });
//                                    $deployments_number ++;
//                                }
//                            }



//                            $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {
//                                $cells->setBackground('#E8E8E8');
//                                $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
//                                $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
//
//
//                            });

                        }
                        $sd = $this->calculateSd($temp_g_id,$regional_office);
                        if($sd){
                            foreach ($sd as $special_duty){

//                                    $deployments_number ++;
//                                        dd($special_duty);
                                $sheet->cells('A'.$deployments_number.':AB'.$deployments_number, function ($cells) {



                                    $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                    $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

                                });
                                $sheet->getStyle('E'.$deployments_number.':X'.$deployments_number)->getNumberFormat()->setFormatCode('_(* #,##0_);_(* (#,###);_(* "-"??_);_(@_)');

//                                    dd($loan);
                                $sheet->getCell('A'.$deployments_number)->setValueExplicit($temp_g_id);
                                $sheet->getCell('B'.$deployments_number)->setValueExplicit($temp_name);
                                $sheet->getCell('Z'.$deployments_number)->setValueExplicit($temp_manager);
                                $sheet->getCell('AA'.$deployments_number)->setValueExplicit($temp_supervisor);

                                $sheet->getCell('M'.$deployments_number)->setValueExplicit($special_duty['special_duty_hours'], \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $sheet->getCell('N'.$deployments_number)->setValueExplicit($special_duty['special_duty_hour_rate'], \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $sheet->getCell('O'.$deployments_number)->setValueExplicit(($special_duty['special_duty_hours'] * $special_duty['special_duty_hour_rate']), \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $sheet->getCell('Y'.$deployments_number)->setValueExplicit($special_duty['special_duty_remarks'] );

//                                    });
                                $sd_wagess= 0;
                                $sd_wagess = $special_duty['special_duty_hours'] * $special_duty['special_duty_hour_rate'];
                                $np_foreach = $np_foreach + ($special_duty['special_duty_hours'] * $special_duty['special_duty_hour_rate']);
                                $sheet->getCell('R'.$deployments_number)->setValueExplicit($sd_wagess, \PHPExcel_Cell_DataType::TYPE_NUMERIC );
                                $deployments_number ++;
                            }
                        }
                        $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {
                            $cells->setBackground('#E8E8E8');
                            $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                            $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);


                        });

                        $sheet->cell('A'.$deployments_number, function ($cell) {
                            $cell->setValue('Sub Total');
                        });
                        $sheet->cell('B'.$deployments_number,$temp_g_id, function ($cell,$temp_g_id) {
                            $cell->setValue($temp_g_id);
                        });
//                                dd($temp_g_id);
//                            $sheet->getCell('D'.$deployments_number)->setValueExplicit($rate, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('F'.$deployments_number)->setValueExplicit($days_for_each, \PHPExcel_Cell_DataType::TYPE_NUMERIC);

                        $sheet->getCell('G'.$deployments_number)->setValueExplicit($wages_for_each, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('H'.$deployments_number)->setValueExplicit($ot_days_for_each, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('I'.$deployments_number)->setValueExplicit($ot_days_wages_for_each, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('J'.$deployments_number)->setValueExplicit($ex_hours, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('K'.$deployments_number)->setValueExplicit($ex_hours_wages, \PHPExcel_Cell_DataType::TYPE_NUMERIC);





                        $sheet->getCell('P'.$deployments_number)->setValueExplicit($holidays, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        round($holidays_wages,2);
                        $sheet->getCell('Q'.$deployments_number)->setValueExplicit($holidays_wages, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sdt= $this->calculateSdTotal($temp_g_id,$regional_office);
//                        $np_foreach = $np_foreach + $sdt ;
                            $sheet->getCell('O'.$deployments_number)->setValueExplicit($sdt, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('R'.$deployments_number)->setValueExplicit($np_foreach, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $deduction = new Guards\PayrollDeductionModel();
                        $deduction = $deduction->getByUserIdn($temp_g_real_id,$history_id);
                        $cw = 0;
                        $sbv=0;
                        $misc=0;
                        $loanp  = $deduction['oploan_amount'];
                        $cw  = $deduction['cwf_amount'];
                        $sbv  = $deduction['sbv_amount'];
                        $misc  = $deduction['misc_amount'];
                        $ap  = $deduction['apsa_amount'];
//                        $ap = $this->calculateApsa($temp_g_id,$regional_office);
//                        $sbv = $this->calculateSBV($temp_g_id,$regional_office,$days_for_each);
//                            if($temp_g_id == 'L-10358'){
//                                dd($sbv);
//                            }
//                             $sdt= $this->calculateSdTotal($temp_g_id,$regional_office);

//                             dd($sdt);

//                        $cw = 0;
//                        $status = $this->calculateGuardStatus($temp_g_id);
//                        if($status == 1){
//                            $cwf = new PayrollDefaultsModel();
//                            $cwf = $cwf->getModelByGuardWithInterval($regional_office);
//                            $cw = $cwf['cwf_value'];
//                        }
//                            $grand_loan = $grand_loan + $loanp;
//                            $grand_sbv = $grand_sbv + $sbv;
//                            $grand_apsaa = $grand_apsaa + $ap ;
//                            $grand_cwf = $grand_cwf + $cw;
//                        $loanp = new GuardLoansModel();
//                        $loanp = $loanp->getModelByGuardWithInterval($temp_g_id,$temp_g_sd,$temp_g_ed);

                        $sum_np = $np_foreach   - $loanp -$ap - $cw -$sbv - $misc;
//                            $grand_sum = $np_foreach+$sum_np;
//                            $grand_sdw = $grand_sdw + $sdt;
//                        $sheet->getCell('N'.$deployments_number)->setValueExplicit($sdt, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('U'.$deployments_number)->setValueExplicit($ap, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('V'.$deployments_number)->setValueExplicit($cw, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('W'.$deployments_number)->setValueExplicit($misc, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('S'.$deployments_number)->setValueExplicit($sbv, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('X'.$deployments_number)->setValueExplicit($sum_np, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getStyle('E'.$deployments_number.':X'.$deployments_number)->getNumberFormat()->setFormatCode('_(* #,##0_);_(* (#,###);_(* "-"??_);_(@_)');
                        $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {



                            $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                            $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

                        });

                        $deployments_number = $deployments_number +2;
                        $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {
                            $cells->setBackground('#E8E8E8');
                            $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                            $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);


                        });
                        $sheet->cell('A'.$deployments_number, function ($cell) {
                            $cell->setValue('Grand Total');
                        });

                        $grand_loan = $grand_loan + $loanp;
//                        dd($grand_loan);

                            $grand_sbv = $grand_sbv + $sbv;
                            $grand_misc = $grand_misc + $sbv;
                            $grand_apsaa = $grand_apsaa + $ap ;
                            $grand_cwf = $grand_cwf + $cw;
                        $grand_sdw = $grand_sdw + $sdt;
                        $grand_np_foreach = $grand_np_foreach +$grand_sdw;
                        $grand_sum =$grand_sum + $sum_np;
//                        $sheet->getStyle('D'.$deployments_number)->getNumberFormat()->setFormatCode('#,##0_);(#,##0)');
                        $sheet->getStyle('E'.$deployments_number)->getNumberFormat()->setFormatCode('_(* #,##0_);_(* (#,###);_(* "-"??_);_(@_)');
//                        $sheet->getCell('D'.$deployments_number)->setValueExplicit($grand_total_for_rate, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('F'.$deployments_number)->setValueExplicit($grand_total_for_days, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('G'.$deployments_number)->setValueExplicit($grand_total_for_wages, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('H'.$deployments_number)->setValueExplicit($grand_ot_days_for_each, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('I'.$deployments_number)->setValueExplicit($grand_ot_days_wages_for_each, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('J'.$deployments_number)->setValueExplicit($grand_ex_hours, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('K'.$deployments_number)->setValueExplicit($grand_ex_hours_wages, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('L'.$deployments_number)->setValueExplicit($grand_ap, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('O'.$deployments_number)->setValueExplicit($grand_sdw, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('P'.$deployments_number)->setValueExplicit($grand_holidays, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('Q'.$deployments_number)->setValueExplicit($grand_holidays_wages, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('R'.$deployments_number)->setValueExplicit($grand_np_foreach, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('T'.$deployments_number)->setValueExplicit($grand_loan, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('S'.$deployments_number)->setValueExplicit($grand_sbv, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('U'.$deployments_number)->setValueExplicit($grand_apsaa, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('V'.$deployments_number)->setValueExplicit($grand_cwf, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('W'.$deployments_number)->setValueExplicit($grand_misc, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
//                        $sheet->getCell('O'.$deployments_number)->setValueExplicit($grand_apsa_foreach, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('X'.$deployments_number)->setValueExplicit($grand_sum, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getStyle('E'.$deployments_number.':X'.$deployments_number)->getNumberFormat()->setFormatCode('#,##0_);(#,##0)');
                        $sheet->setBorder('A5:AB'.$deployments_number, 'thin','thin','thin','thin');
                        $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {


                            $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                            $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

                        });

                    });
//

                })->store('xlsx', Config::get('globalvariables.$pathToSaveTemporaryExcelFiles'));


                $fileNameToDownload = $fileName . '.xlsx';

                $file_path = Config::get('globalvariables.$pathToSaveTemporaryExcelFiles') . '/' . $fileNameToDownload;
                if (file_exists($file_path)) {
                    // Send Download
                    return Response::download($file_path, $fileNameToDownload, [
                        'Content-Length: ' . filesize($file_path)
                    ])->deleteFileAfterSend(true);
                } else {
                    // Error
                    exit('Requested file does not exist on our server!');
                }


                $data = array('fileNameToDownload' => $fileNameToDownload);

                return ['responseCode' => 1, 'responseStatus' => 'successful', 'message' => 'your file is ready, and will download in few seconds', 'data' => $data];


            }
            else{

                return view('guards.attendance')->with(['noRecordFound' => 'No record Found']);
            }

        }


    }
    public function getSalaryExportHistorySheetUnpaid(Request $request){

//        if($request->salary_status == 'Paid'){
//            dd('paid');
//        }else{
//            dd('unpaidpaid');
//
//        }
        $active_month = new Guards\PayrollSalaryMonth();
        $active_month = $active_month->getAll();
//        dd($active_month['date_from']);
        $salary_year = $active_month['salary_year'];


        $salary_month =  substr($active_month['date_from'], 0, -3);
        $monthNum = substr($salary_month,5);
//        dd($monthNum);
//        $monthNum  = 3;
        $dateObj   = DateTime::createFromFormat('!m', $monthNum);
        $monthName = $dateObj->format('F'); // March
        set_time_limit(0);
        error_reporting(E_ALL);
        $input = $request->all();
        $parwest_id = $input['parwest_id'];
        $sal_month =  substr($input['month'], 5);
        $sal_year =  substr($input['month'], 0, -3);
//        dd($request);
        $manager_id = null;
        $supervisor_id = null;
        if(isset($input['manager'])){

            $manager_id = $input['manager'];
        }if(isset($input['supervisor'])){

            $supervisor_id = $input['supervisor'];
        }
//        $monthNum = substr($salary_month,5);$manager_id,$supervisor_id
//        $colString = PHPExcel_Cell::stringFromColumnIndex(1);
        $colString = $this->getcolumnrange('A','ZZ');
        $rules = new Guards\PayrollSalaryRuleModel();
        $rules = $rules->getAll();
//        dd($colString);
        $number = count($rules);
        $endAlpha = $colString[$number];
//        dd($colString[$number]);

//        dd($input['tags_id']);
        $guard_model = new Guards\Guards();
        $guard_model = $guard_model->getByParwestId($parwest_id);
        $get_history = new Guards\PayrollPostIndicatorModel();
        $get_history = $get_history->getModelByValue($guard_model['regional_office_id'],$sal_month,$sal_year);
//        dd($get_history['history_id']);
        $get_finalised_history_by_loan = new Guards\GuardSalaryModel();
        $get_finalised_history_by_loan = $get_finalised_history_by_loan->getModelByValueSheetUnpaid($get_history['history_id'],$guard_model['regional_office_id'],$manager_id,$supervisor_id,$parwest_id);
//        dd($get_finalised_history_by_loan);
        $history_id =  $get_history['history_id'];
        $regional_office = $guard_model['regional_office_id'];
        $regional = new Guards\RegionalOfficeModel();
        $regional = $regional->getModelById($regional_office);
//        dd($regional['office_head']);
        $reg = $regional['office_head'];
        $user_id =   Auth::guard('user')->id();
//        $user_name =   Auth::guard('user')->name;
        $user = new UserModel();
        $loans = $get_finalised_history_by_loan;
        if(count($loans) == 0){

        }
        else{


            if(count($loans) > 0){

                $fileName = 'salary'." ".$salary_month." ".$reg;

//                if($request->salary_month){
//                    $fileName = 'salary'.$request->salary_month;
//                }else{
//
//                }
                $sheetsArray = array();


                Excel::create(/**
                 * @param $excel
                 */
                    $fileName, function ($excel) use ($loans,$user,$endAlpha,$colString,$regional_office,$monthName,$salary_year,$reg,$history_id,$manager_id,$supervisor_id) {
                    //dd($searchResults);


                    $excel->setTitle('Guards Attandance');
                    $excel->setDescription('Guards Attandance');
//                    $sheet->setAllBorders('thin');
//                    $excel->setActiveSheetIndex(0);

//                    center align
//                    $excel->getDefaultStyle()
//                        ->getAlignment()
//                        ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
//                        ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

                    $currentSheetName = 'Operation Salary';
                    $excel->sheet($currentSheetName, function ($sheet) use ($loans,$user,$endAlpha,$colString,$regional_office,$monthName,$salary_year,$reg,$history_id,$manager_id,$supervisor_id) {
                        $deployments_number = 6;
                        $temp_g_id = 0;
                        $temp_g_real_id = 0;
                        $temp_name = 0;
                        $temp_supervisor = 0;
                        $temp_manager = 0;
                        $temp_g_sd = 0;
                        $temp_g_ed = 0;
                        $loanp = 0;
                        $grand_loan = 0;
                        $pa = 0;
                        $grand_ap = 0;
                        $dsbv = 0;
                        $grand_sbv = 0;
                        $dapsaa = 0;
                        $grand_apsaa = 0;
                        $dcwf = 0;
                        $grand_cwf = 0;
                        $grand_sdw = 0;

                        $rate= 0;
                        $days_p=0;
                        $days_for_each = 0;
                        $wages_for_each = 0;
                        $ot_days_for_each = 0;
                        $ot_days_wages_for_each = 0;
                        $ex_hours = 0;
                        $ex_hours_wages = 0;
                        $special_duty_wages = 0;
                        $holidays = 0 ;
                        $holidays_wages = 0 ;
                        $foreach_holidays_wages = 0 ;
                        $np_foreach = 0;
                        $apsa_foreach = 0;
                        $net_foreach = 0;
                        $ot_total_for_each = 0;

                        $grand_ot_days_for_each = 0;
                        $grand_ot_days_wages_for_each = 0;
                        $grand_ex_hours = 0;
                        $grand_ex_hours_wages = 0;
                        $grand_special_duty_wages = 0;
                        $grand_holidays = 0 ;
                        $grand_holidays_wages = 0 ;
                        $grand_np_foreach = 0;
                        $grand_apsa_foreach = 0;
                        $grand_net_foreach = 0;

                        $np_bf_total_for_each = 0;
                        $grand_total_for_rate = 0;
                        $np_af_total_for_each = 0;

                        $grand_total_for_days = 0;
                        $grand_total_for_wages = 0;
                        $grand_sum = 0;

                        foreach ($loans as $loan) {
//                            dd($loans);

                            $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {

                                $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

                            });
                            if($temp_g_id && $temp_g_id!= $loan['parwest_id']){
                                $sd = $this->calculateSd($temp_g_id,$regional_office);
                                if($sd){
                                    foreach ($sd as $special_duty){

//                                    $deployments_number ++;
//                                        dd($special_duty);
                                        $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {



                                            $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                            $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

                                        });
                                        $sheet->getStyle('E'.$deployments_number.':W'.$deployments_number)->getNumberFormat()->setFormatCode('_(* #,##0_);_(* (#,###);_(* "-"??_);_(@_)');

//                                    dd($loan);
                                        $sheet->getCell('A'.$deployments_number)->setValueExplicit($temp_g_id);
                                        $sheet->getCell('B'.$deployments_number)->setValueExplicit($temp_name);
                                        $sheet->getCell('Z'.$deployments_number)->setValueExplicit($temp_manager);
                                        $sheet->getCell('AA'.$deployments_number)->setValueExplicit($temp_supervisor);

                                        $sheet->getCell('M'.$deployments_number)->setValueExplicit($special_duty['special_duty_hours'], \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                        $sheet->getCell('N'.$deployments_number)->setValueExplicit($special_duty['special_duty_hour_rate'], \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                        $sheet->getCell('O'.$deployments_number)->setValueExplicit(($special_duty['special_duty_hours'] * $special_duty['special_duty_hour_rate']), \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                        $sheet->getCell('Y'.$deployments_number)->setValueExplicit($special_duty['special_duty_remarks'] );

//                                    });
                                        $sd_wagess= 0;
                                        $sd_wagess = $special_duty['special_duty_hours'] * $special_duty['special_duty_hour_rate'];
                                        $np_foreach = $np_foreach + ($special_duty['special_duty_hours'] * $special_duty['special_duty_hour_rate']);
                                        $sheet->getCell('R'.$deployments_number)->setValueExplicit($sd_wagess, \PHPExcel_Cell_DataType::TYPE_NUMERIC );
                                        $deployments_number ++;
//                                        $np_foreach = $np_foreach + ($special_duty['special_duty_hours'] * $special_duty['special_duty_hour_rate']);
//                                        $sheet->getCell('Q'.$deployments_number)->setValueExplicit($np_foreach, \PHPExcel_Cell_DataType::TYPE_NUMERIC );
//                                        $deployments_number ++;
                                    }
                                }


                            }
                            if($temp_g_id && $temp_g_id!= $loan['parwest_id']){
                                $loanp = new GuardLoansModel();
                                $loanp = $loanp->getLoansByGuardWithInterval($temp_g_id,$temp_g_sd,$temp_g_ed);
                                if($loanp){
                                    foreach ($loanp as $loanslist){

//                                    $deployments_number ++;
//                                        dd($special_duty);
                                        $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {



                                            $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                            $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

                                        });
                                        $sheet->getStyle('E'.$deployments_number.':W'.$deployments_number)->getNumberFormat()->setFormatCode('_(* #,##0_);_(* (#,###);_(* "-"??_);_(@_)');

//                                    dd($loan);
                                        $sheet->getCell('A'.$deployments_number)->setValueExplicit($temp_g_id);
                                        $sheet->getCell('B'.$deployments_number)->setValueExplicit($temp_name);
                                        $sheet->getCell('T'.$deployments_number)->setValueExplicit($loanslist->amount_paid, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                        $sheet->getCell('Y'.$deployments_number)->setValueExplicit($loanslist->supervisor_name);
                                        $sheet->getCell('Z'.$deployments_number)->setValueExplicit($temp_manager);
                                        $sheet->getCell('AA'.$deployments_number)->setValueExplicit($temp_supervisor);

//                                        $sheet->getCell('M'.$deployments_number)->setValueExplicit($special_duty['special_duty_hours'], \PHPExcel_Cell_DataType::TYPE_NUMERIC);
//                                        $sheet->getCell('N'.$deployments_number)->setValueExplicit($special_duty['special_duty_hour_rate'], \PHPExcel_Cell_DataType::TYPE_NUMERIC);
//                                        $sheet->getCell('O'.$deployments_number)->setValueExplicit(($special_duty['special_duty_hours'] * $special_duty['special_duty_hour_rate']), \PHPExcel_Cell_DataType::TYPE_NUMERIC);
//                                        $sheet->getCell('Y'.$deployments_number)->setValueExplicit($special_duty['special_duty_remarks'] );

//                                    });
                                        $sd_wagess= 0;
//                                        $sd_wagess = $special_duty['special_duty_hours'] * $special_duty['special_duty_hour_rate'];
//                                        $np_foreach = $np_foreach + ($special_duty['special_duty_hours'] * $special_duty['special_duty_hour_rate']);
//                                        $sheet->getCell('R'.$deployments_number)->setValueExplicit($sd_wagess, \PHPExcel_Cell_DataType::TYPE_NUMERIC );
                                        $deployments_number ++;
//                                        $np_foreach = $np_foreach + ($special_duty['special_duty_hours'] * $special_duty['special_duty_hour_rate']);
//                                        $sheet->getCell('Q'.$deployments_number)->setValueExplicit($np_foreach, \PHPExcel_Cell_DataType::TYPE_NUMERIC );
//                                        $deployments_number ++;
                                    }
                                }


                            }

                            if($temp_g_id && $temp_g_id!= $loan['parwest_id']){
//                                $sbv = $this->calculateSBV($temp_g_id,$regional_office,$days_p);
//                                if($temp_g_id == 'L-10358'){
//                                    dd($sbv);
//                                }
//                                $sheet->setBorder('A5:AA'.$deployments_number, 'thin');
//                                $sheet->getStyle('D'.$deployments_number.':W'.$deployments_number)->getNumberFormat()->setFormatCode('#,##0_);(#,##0)');
                                $sheet->getStyle('E'.$deployments_number.':X'.$deployments_number)->getNumberFormat()->setFormatCode('_(* #,##0_);_(* (#,###);_(* "-"??_);_(@_)');
//                                $sheet->getStyle('A'.$deployments_number.':AA'.$deployments_number)->setBackground('#CCCCCC');
//                                $sheet->getStyle('A'.$deployments_number.':AA'.$deployments_number)->applyFromArray(array(
//                                    'fill' => array(
////                                        'type'  => PHPExcel_Style_Fill::FILL_SOLID,
//                                        'color' => array('rgb' => 'FF0000')
//                                    )
//                                ));
//                                $sheet->cells('A1:D1', function ($cells) {
//                                    $cells->setBackground('#008686');
//                                    $cells->setAlignment('center');
//                                });
                                $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {
                                    $cells->setBackground('#E8E8E8');
                                    $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                    $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);


                                });


//                                $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {
//
//                                    $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
//                                    $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
//
//                                });
//                                if($special_duty_wages > 0){
//
//
//                                    $sheet->getCell('N'.$deployments_number)->setValueExplicit($special_duty_wages, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
//
//                                    $deployments_number ++;
//                                    $special_duty_wages = 0;
//                                }else{
//                                    dd($temp_g_id);

                                $sheet->cell('A'.$deployments_number, function ($cell) {
                                        $cell->setValue('Sub Total');
                                    });
                                $sheet->cell('B'.$deployments_number,$temp_g_id, function ($cell,$temp_g_id) {
                                        $cell->setValue($temp_g_id);
                                    });

//                                    $sheet->getCell('D'.$deployments_number)->setValueExplicit($rate, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $sheet->getCell('F'.$deployments_number)->setValueExplicit($days_for_each, \PHPExcel_Cell_DataType::TYPE_NUMERIC);

                                $sheet->getCell('G'.$deployments_number)->setValueExplicit($wages_for_each, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $sheet->getCell('H'.$deployments_number)->setValueExplicit($ot_days_for_each, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $sheet->getCell('I'.$deployments_number)->setValueExplicit($ot_days_wages_for_each, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $sheet->getCell('J'.$deployments_number)->setValueExplicit($ex_hours, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $sheet->getCell('K'.$deployments_number)->setValueExplicit($ex_hours_wages, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
//                                    $sheet->getCell('M'.$deployments_number)->setValueExplicit($special_duty_wages, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
//                                $sheet->getCell('N'.$deployments_number)->setValueExplicit($holidays, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $grand_ap = $grand_ap+$pa;
                                $sheet->getCell('L'.$deployments_number)->setValueExplicit($pa, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                round($holidays_wages,2);
                                    $sheet->getCell('Q'.$deployments_number)->setValueExplicit($foreach_holidays_wages, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $sheet->getCell('R'.$deployments_number)->setValueExplicit($np_foreach, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
//                                $loanp = new GuardLoansModel();
//                                $loanp = $loanp->getModelByGuardWithInterval($temp_g_id,$temp_g_sd,$temp_g_ed);

                                $deduction = new Guards\PayrollDeductionModel();
                                $deduction = $deduction->getByUserIdn($temp_g_real_id);
//                                dd($temp_g_real_id);
                                $cw = 0;
                                $sbv=0;
                                $loanp  = $deduction['oploan_amount'];
                                $cw  = $deduction['cwf_amount'];
                                $sbv  = $deduction['sbv_amount'];
                                $ap  = $deduction['apsa_amount'];
                                $grand_loan = $grand_loan + $loanp;

                                $sheet->getCell('T'.$deployments_number)->setValueExplicit($loanp, \PHPExcel_Cell_DataType::TYPE_NUMERIC);

//                                $ap = $this->calculateApsa($temp_g_id,$regional_office);
                                    $grand_apsaa = $grand_apsaa + $ap;

//                                    $sbv = $this->calculateSBV($temp_g_id,$regional_office,$days_for_each);
                                    $grand_sbv = $grand_sbv + $sbv;

//

//                                $status = $this->calculateGuardStatus($temp_g_id);
//                                if($status == 1){
//                                    $cwf = new PayrollDefaultsModel();
//                                    $cwf = $cwf->getModelByGuardWithInterval($regional_office);
//                                    $cw = $cwf['cwf_value'];
//
//                                }
                                $grand_cwf = $grand_cwf + $cw;

                                $sdt= $this->calculateSdTotal($temp_g_id,$regional_office);
//                                dd($sdt);




                                $sum_np = $np_foreach  - $loanp -$ap - $cw -$sbv;
                                $grand_sum = $grand_sum + $sum_np;
//                                  $sum_np = $np_foreach - $loanp  - $cw;
//                                $grand_sum = $grand_sum + $sum_np;
                                $grand_sdw = $grand_sdw + $sdt;
                                $sheet->getCell('O'.$deployments_number)->setValueExplicit($sdt, \PHPExcel_Cell_DataType::TYPE_NUMERIC);

                                $sheet->getCell('U'.$deployments_number)->setValueExplicit($ap, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
//                                    $sheet->getCell('T'.$deployments_number)->setValue($ap);
//                                $sheet->cell('T'.$deployments_number,$ap, function ($cell,$ap) {
//                                    $cell->setValue($ap);
//                                });
                                    $sheet->getCell('V'.$deployments_number)->setValueExplicit($cw, \PHPExcel_Cell_DataType::TYPE_NUMERIC);

                                    $sheet->getCell('X'.$deployments_number)->setValueExplicit($sum_np, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                    $sheet->getCell('S'.$deployments_number)->setValueExplicit($sbv, \PHPExcel_Cell_DataType::TYPE_NUMERIC);

//                                    $sheet->cell('R'.$deployments_number,$sbv, function ($cell,$sbv) {
//                                        $cell->setValue($sbv);
//                                    });
//                                $sheet->getStyle('D'.$deployments_number.':W'.$deployments_number)->getNumberFormat()->setFormatCode('#,##0_);(#,##0)');
                                $sheet->getStyle('E'.$deployments_number.':X'.$deployments_number)->getNumberFormat()->setFormatCode('_(* #,##0_);_(* (#,###);_(* "-"??_);_(@_)');

//                                    $sheet->cells('A'.$deployments_number.':AO'.$deployments_number, function ($cells) {
//
//                                        $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
//                                        $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
//
//                                    });


                                    $deployments_number ++;
                                    $rate = 0;
                                    $days_for_each = 0;
                                    $wages_for_each = 0;
                                    $ot_days_for_each = 0;
                                    $ot_days_wages_for_each = 0;
                                    $ex_hours = 0;
                                    $ex_hours_wages = 0;
                                    $special_duty_wages = 0;
                                    $holidays = 0;
                                    $holidays_wages = 0;
                                    $np_foreach = 0;
                                    $apsa_foreach = 0;
                                    $net_foreach = 0;
                                    $pa =0;
                                    $loanp =0;
                                $foreach_holidays_wages = 0;


                                    $ot_total_for_each = 0;

                                    $np_bf_total_for_each = 0;

                                    $np_af_total_for_each = 0;


//                                }

                            }

                            $sheet->row(1, array(
                                'PARWEST PACIFIC SECURITY ( PVT ) LTD.'

                            ));
                            $sheet->row(2, array(
                                '176-CAVALARY GROUND, LAHORE CANTT.'

                            ));
                            $sheet->row(3, array(
                                'Salary for the month of   '.$monthName."  ".$salary_year

                            ));
                            $sheet->row(4, array(
                                'Region :     '.$reg."  "

                            ));
                            $sheet->getStyle('A5:AA5')->getAlignment()->setWrapText(true);
////                            $sheet->getColumnDimension('A')->setWidth(0);
                            $sheet->getStyle('A5:AA5')->getAlignment()->applyFromArray(
                                    array(
                                         'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                        'vertical'   => \PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                        'rotation'   => 0,
                                        'wrap'       => TRUE
                                    )
                              );
//                            $sheet->setAllBorders('thin');
//                            $sheet->getDefaultStyle('A5:AA5')->getAlignment()
//
//                            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
//                            ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);


//                            $sheet->getStyle('A5:AA5')->applyFromArray([
//                                'alignment' => array(
//                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
//                                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
//                                    'rotation'   => 0,
//                                    'wrap'       => true
//                                )]);
//                            $sheet->getStyle('A5:AA5')->getColumnDimension('G')->setWidth(13);
//                            $sheet->setWidth('G', 12);
//                            $sheet->getColumnDimension('G')->setWidth(12);
                            $rules = new Guards\PayrollSalaryRuleModel();
                            $rules = $rules->getAll();

                            $number = count($rules);
//                            dd($number);

                            for($i = 0; $i < $number; $i++){

                                    $value = $rules[$i]->name;
                                    $code = $rules[$i]->code;
//
                                if($code == 'MGI' || $code == 'SPI'){
                                    //notjomog

                                }else{
                                    if(isset($value)){

                                        $sheet->cell($colString[$i].'5',$value, function ($cell,$value) {
                                            $cell->setValue($value);
                                        });
                                    }
                                }


                            }


                            $temp_g_id = $loan['parwest_id'];
                            $temp_g_real_id = $loan['guard_id'];
                            $temp_name  = $loan['guard_Name'];
                            $temp_supervisor = $loan['guard_supervisor_name'];
                            $temp_manager = $loan['guard_manager_name'];
                            //                                        $sheet->getCell('B'.$deployments_number)->setValueExplicit($loan['guard_Name']);
//                                        $sheet->getCell('Y'.$deployments_number)->setValueExplicit($loan['guard_manager_name']);
//                                        $sheet->getCell('AA'.$deployments_number)->setValueExplicit($loan['guard_supervisor_name']);


                            $days_p = $loan['days'];
                            $temp_g_sd = $loan['startDay'];
                            $temp_g_ed = $loan['endDay'];

                            if($loan['is_overtime'] == 0){
                                $rate =  $rate + $loan['location_rate'];
                                $days_for_each =  $days_for_each + $loan['days'];
                                $wages_for_each =  $wages_for_each + ($loan['location_rate'] / 31)* $loan['days'];
                                $wages_for_each = (int)$wages_for_each;
                            }


                            $ot_total_for_each =  $ot_total_for_each + $loan['is_overtime'] * $loan['overtime_cost'];




                            $days = $loan['days'];
                            $wages = ($loan['location_rate'] / 31)* $loan['days'];


                            $eh = $loan['is_extra'];
                            $eh_wages = $loan['extrahours_cost'];

                            $count = count($rules);

                            $rule_values  = $this->getRulesValuesByDeploymentIdSheet($loan['deployment_id'],$history_id,$manager_id,$supervisor_id);
//                            dd($rule_values);
                            for($i = 0; $i < $number; $i++){
                                $value = 0;
                                if(isset($count)){

                                    $value = $rule_values[$i]->salary_rule_value;
                                    $code = $rule_values[$i]->code;

                                    if($rule_values[$i]->code == 'RAT'){
                                        $grand_total_for_rate = $grand_total_for_rate + (int)$value;

                                    }
                                    if($rule_values[$i]->code == 'DAY'){
                                        $grand_total_for_days = $grand_total_for_days + (int)$value;

                                    }
                                    if($rule_values[$i]->code == 'WGS'){
                                        $np_af_total_for_each = $np_af_total_for_each + (int)$value;
                                        $grand_total_for_wages = $grand_total_for_wages + (int)$value;

                                    }
                                    if($rule_values[$i]->code == 'OTD'){
                                        $ot_days_for_each = $ot_days_for_each + (int)$value;
                                        $grand_ot_days_for_each = $grand_ot_days_for_each + (int)$value;

//

                                    }if($rule_values[$i]->code == 'OTW'){
                                        $ot_days_wages_for_each = $ot_days_wages_for_each + (int)$value;
                                        $grand_ot_days_wages_for_each = $grand_ot_days_wages_for_each + (int)$value;
//

                                    }if($rule_values[$i]->code == 'EHC'){
                                        $ex_hours = $ex_hours + (int)$value;
                                        $grand_ex_hours = $grand_ex_hours + (int)$value;
//

                                    }if($rule_values[$i]->code == 'EHW'){
                                        $ex_hours_wages = $ex_hours_wages + (int)$value;
                                        $grand_ex_hours_wages = $grand_ex_hours_wages + (int)$value;
//

                                    }if($rule_values[$i]->code == 'SPD'){
                                        $special_duty_wages = $special_duty_wages + (int)$value;
                                        $grand_special_duty_wages = $grand_special_duty_wages + (int)$value;
//

                                    }if($rule_values[$i]->code == 'HOD'){
                                        $holidays = $holidays + (int)$value;
                                        $grand_holidays = $grand_holidays + (int)$value;
//

                                    }if($rule_values[$i]->code == 'HOW'){


                                        $holidays_wages = $holidays_wages + (int)$value;
                                        $grand_holidays_wages = $grand_holidays_wages + (int)$value;
                                        $foreach_holidays_wages = $foreach_holidays_wages + (int)$value;

//

                                    }if($rule_values[$i]->code == 'GRS'){
                                        $np_foreach = $np_foreach + (int)$value;
                                        $np_foreach = (int)$np_foreach;
                                        $grand_np_foreach = $grand_np_foreach + (int)$value;
//

                                    }
                                    if($rule_values[$i]->code == 'APS'){
                                        $apsa_foreach = $apsa_foreach + (int)$value;
                                        $grand_apsa_foreach = $grand_apsa_foreach + (int)$value;
//

                                    }if($rule_values[$i]->code == 'NPY'){
                                        $net_foreach = $net_foreach + (int)$value;
                                        $grand_net_foreach = $grand_net_foreach + (int)$value;
//

                                    }
                                    if($code == 'POA'){
                                        //notjomog
                                        $pa = $pa + (int)$value;

                                    }
//                                    $sheet->getCell($colString[$i].$deployments_number)->setValueExplicit($value, \PHPExcel_Cell_DataType::TYPE_NUMERIC);

//
                                    if($code == 'MGI' || $code == 'SPI'){
                                        //notjomog

                                    }
//                                    elseif($code == 'RAT' && $loan['is_overtime'] == 1){
////                                        dd($value);
//
//                                    }
                                    else{
                                        $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
//                                        0
//                                        if (is_numeric($value)) {


//                                            $value = (int)$value;
//                                            if(ctype_digit($value)){
//                                                $value =  number_format($value);

//                                                $sheet->getCell($colString[$i].$deployments_number)->setValueExplicit($value, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                                $sheet->getCell($colString[$i].$deployments_number)->setValue($value);
//                                                $cell->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

//                                            }



//                                            else{
////                                                $value =  number_format($value);
//                                                $sheet->cell($colString[$i].$deployments_number,$value,$thinBorder, function ($cell,$value,$thinBorder) {
////                                                    $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);
//                                                    $cell->setValue($value);
//                                                    $cell->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
//
//                                                });
//                                            }

//                                        } else {
////                                            $value =  number_format($value);
//                                            $sheet->cell($colString[$i].$deployments_number,$value,$thinBorder, function ($cell,$value,$thinBorder) {
////                                                    $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);
////                                                $cell->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
//                                                $cell->setValue($value);
//                                            });
////                                                                               $cell->setValue($value);
//                                        }


                                    }

                                }
                            }

//                            $grand_total_for_days = $grand_total_for_days + $days;
//                            $grand_total_for_wages = $grand_total_for_wages + $wages;
//                            $grand_total_for_wages = (int)$grand_total_for_wages;
//
//                            $sheet->getStyle('D'.$deployments_number.':W'.$deployments_number)->getNumberFormat()->setFormatCode('#,##0_);(#,##0)');
                            $sheet->getStyle('E'.$deployments_number.':X'.$deployments_number)->getNumberFormat()->setFormatCode('_(* #,##0_);_(* (#,###);_(* "-"??_);_(@_)');

                            $deployments_number = $deployments_number+1;

                            $sheet->cells('A5:AA5', function ($cells) {
                                $cells->setFont(array(
                                    'bold' => true
                                ));

                                $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

                            });
//                            $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {
//
//
//                                $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
//                                $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
//
//                            });

                            $sheet->setStyle(array(
                                'font' => array(
                                    'name' => 'Calibri',
                                ),
                                'setSize' => array(
                                    'height' => '35',
                                )
                            ));
//
                            $sheet->mergeCells('A1:R1');
                            $sheet->getStyle('A1:R1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);



                            $sheet->cells('A1:R1', function ($cells) {

                                $cells->setBackground('#ffffff');
                                $cells->setFontColor('#000000');
                                $cells->setFont(array(
                                    'size' => '20',
                                    'bold' => true
                                ));

                            });
                            $sheet->cells('A3:Z3', function ($cells) {

                                $cells->setBackground('#ffffff');
                                $cells->setFontColor('#000000');
                                $cells->setFont(array(
                                    'size' => '12',
                                    'bold' => true,

                                ));

                            });
                            $sheet->cells('A5:Z5', function ($cells) {

                                $cells->setBackground('#ffffff');
                                $cells->setFontColor('#000000');
                                $cells->setFont(array(
                                    'size' => '12',
                                    'bold' => true,

                                ));

                            });

                            $sheet->cells('A2:A4', function ($cells) {
                                $cells->setFont(array(
                                    'bold' => true
                                ));

                            });
//
//                            $sheet->getStyle()->getNumberFormat()->setFormatCode('#,##0_);(#,##0)');
                            $sheet->getStyle()->getNumberFormat()->setFormatCode('_(* #,##0_);_(* (#,###);_(* "-"??_);_(@_)');

                            $sheet->mergeCells('A1:R1');
                            $sheet->mergeCells('A2:R2');
                            $sheet->mergeCells('A3:R3');
//                            $sheet->setWidth('A', 12);
                            $sheet->setWidth(array(
                                'A'     =>  13,
                                'B'     =>  30,
                                'C'     =>  15,
                                'D'     =>  15,
                                'E'     =>  15,
                                'F'     =>  15,
                                'G'     =>  15,
                                'H'     =>  15,
                                'I'     =>  15,
                                'J'     =>  15,
                                'K'     =>  15,
                                'L'     =>  15,
                                'M'     =>  15,
                                'N'     =>  15,
                                'O'     =>  15,
                                'P'     =>  15,
                                'Q'     =>  15,
                                'R'     =>  15,
                                'S'     =>  15,
                                'T'     =>  15,
                                'U'     =>  15,
                                'V'     =>  15,
                                'W'     =>  15,
                                'X'     =>  25,
                                'Y'     =>  25,
                                'Z'     =>  25,
                                'AA'     =>  25
                            ));
//                            $sd = $this->calculateSd($temp_g_id,$regional_office);
//                            if($sd){
//                                foreach ($sd as $special_duty){
//
////                                    $deployments_number ++;
////                                        dd($special_duty);
//                                    $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {
//
//
//
//                                        $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
//                                        $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
//
//                                    });
//                                    $sheet->getStyle('D'.$deployments_number.':W'.$deployments_number)->getNumberFormat()->setFormatCode('_(* #,##0_);_(* (#,###);_(* "-"??_);_(@_)');
//
////                                    dd($loan);
//                                    $sheet->getCell('A'.$deployments_number)->setValueExplicit($temp_g_id);
//                                    $sheet->getCell('B'.$deployments_number)->setValueExplicit($loan['guard_Name']);
//                                    $sheet->getCell('Y'.$deployments_number)->setValueExplicit($loan['guard_manager_name']);
//                                    $sheet->getCell('AA'.$deployments_number)->setValueExplicit($loan['guard_supervisor_name']);
//
//                                    $sheet->getCell('L'.$deployments_number)->setValueExplicit($special_duty['special_duty_hours'], \PHPExcel_Cell_DataType::TYPE_NUMERIC);
//                                    $sheet->getCell('M'.$deployments_number)->setValueExplicit($special_duty['special_duty_hour_rate'], \PHPExcel_Cell_DataType::TYPE_NUMERIC);
//                                    $sheet->getCell('N'.$deployments_number)->setValueExplicit(($special_duty['special_duty_hours'] * $special_duty['special_duty_hour_rate']), \PHPExcel_Cell_DataType::TYPE_NUMERIC);
//                                    $sheet->getCell('X'.$deployments_number)->setValueExplicit($special_duty['special_duty_remarks'] );
//
////                                    });
//                                    $deployments_number ++;
//                                }
//                            }



//                            $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {
//                                $cells->setBackground('#E8E8E8');
//                                $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
//                                $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
//
//
//                            });

                        }
                        $sd = $this->calculateSd($temp_g_id,$regional_office);
                        if($sd){
                            foreach ($sd as $special_duty){

//                                    $deployments_number ++;
//                                        dd($special_duty);
                                $sheet->cells('A'.$deployments_number.':AB'.$deployments_number, function ($cells) {



                                    $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                    $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

                                });
                                $sheet->getStyle('E'.$deployments_number.':X'.$deployments_number)->getNumberFormat()->setFormatCode('_(* #,##0_);_(* (#,###);_(* "-"??_);_(@_)');

//                                    dd($loan);
                                $sheet->getCell('A'.$deployments_number)->setValueExplicit($temp_g_id);
                                $sheet->getCell('B'.$deployments_number)->setValueExplicit($temp_name);
                                $sheet->getCell('Z'.$deployments_number)->setValueExplicit($temp_manager);
                                $sheet->getCell('AA'.$deployments_number)->setValueExplicit($temp_supervisor);

                                $sheet->getCell('M'.$deployments_number)->setValueExplicit($special_duty['special_duty_hours'], \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $sheet->getCell('N'.$deployments_number)->setValueExplicit($special_duty['special_duty_hour_rate'], \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $sheet->getCell('O'.$deployments_number)->setValueExplicit(($special_duty['special_duty_hours'] * $special_duty['special_duty_hour_rate']), \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $sheet->getCell('Y'.$deployments_number)->setValueExplicit($special_duty['special_duty_remarks'] );

//                                    });
                                $sd_wagess= 0;
                                $sd_wagess = $special_duty['special_duty_hours'] * $special_duty['special_duty_hour_rate'];
                                $np_foreach = $np_foreach + ($special_duty['special_duty_hours'] * $special_duty['special_duty_hour_rate']);
                                $sheet->getCell('R'.$deployments_number)->setValueExplicit($sd_wagess, \PHPExcel_Cell_DataType::TYPE_NUMERIC );
                                $deployments_number ++;
                            }
                        }
                        $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {
                            $cells->setBackground('#E8E8E8');
                            $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                            $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);


                        });

                        $sheet->cell('A'.$deployments_number, function ($cell) {
                            $cell->setValue('Sub Total');
                        });
                        $sheet->cell('B'.$deployments_number,$temp_g_id, function ($cell,$temp_g_id) {
                            $cell->setValue($temp_g_id);
                        });
//                                dd($temp_g_id);
//                            $sheet->getCell('D'.$deployments_number)->setValueExplicit($rate, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('F'.$deployments_number)->setValueExplicit($days_for_each, \PHPExcel_Cell_DataType::TYPE_NUMERIC);

                        $sheet->getCell('G'.$deployments_number)->setValueExplicit($wages_for_each, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('H'.$deployments_number)->setValueExplicit($ot_days_for_each, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('I'.$deployments_number)->setValueExplicit($ot_days_wages_for_each, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('J'.$deployments_number)->setValueExplicit($ex_hours, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('K'.$deployments_number)->setValueExplicit($ex_hours_wages, \PHPExcel_Cell_DataType::TYPE_NUMERIC);





                        $sheet->getCell('P'.$deployments_number)->setValueExplicit($holidays, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        round($holidays_wages,2);
                        $sheet->getCell('Q'.$deployments_number)->setValueExplicit($holidays_wages, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sdt= $this->calculateSdTotal($temp_g_id,$regional_office);
//                        $np_foreach = $np_foreach + $sdt ;
                            $sheet->getCell('O'.$deployments_number)->setValueExplicit($sdt, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('R'.$deployments_number)->setValueExplicit($np_foreach, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $deduction = new Guards\PayrollDeductionModel();
                        $deduction = $deduction->getByUserIdn($temp_g_real_id);
                        $cw = 0;
                        $sbv=0;
                        $loanp  = $deduction['oploan_amount'];
                        $cw  = $deduction['cwf_amount'];
                        $sbv  = $deduction['sbv_amount'];
                        $ap  = $deduction['apsa_amount'];
//                        $ap = $this->calculateApsa($temp_g_id,$regional_office);
//                        $sbv = $this->calculateSBV($temp_g_id,$regional_office,$days_for_each);
//                            if($temp_g_id == 'L-10358'){
//                                dd($sbv);
//                            }
//                             $sdt= $this->calculateSdTotal($temp_g_id,$regional_office);

//                             dd($sdt);

//                        $cw = 0;
//                        $status = $this->calculateGuardStatus($temp_g_id);
//                        if($status == 1){
//                            $cwf = new PayrollDefaultsModel();
//                            $cwf = $cwf->getModelByGuardWithInterval($regional_office);
//                            $cw = $cwf['cwf_value'];
//                        }
//                            $grand_loan = $grand_loan + $loanp;
//                            $grand_sbv = $grand_sbv + $sbv;
//                            $grand_apsaa = $grand_apsaa + $ap ;
//                            $grand_cwf = $grand_cwf + $cw;
//                        $loanp = new GuardLoansModel();
//                        $loanp = $loanp->getModelByGuardWithInterval($temp_g_id,$temp_g_sd,$temp_g_ed);

                        $sum_np = $np_foreach   - $loanp -$ap - $cw -$sbv;
//                            $grand_sum = $np_foreach+$sum_np;
//                            $grand_sdw = $grand_sdw + $sdt;
//                        $sheet->getCell('N'.$deployments_number)->setValueExplicit($sdt, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('U'.$deployments_number)->setValueExplicit($ap, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('V'.$deployments_number)->setValueExplicit($cw, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('S'.$deployments_number)->setValueExplicit($sbv, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('X'.$deployments_number)->setValueExplicit($sum_np, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getStyle('E'.$deployments_number.':X'.$deployments_number)->getNumberFormat()->setFormatCode('_(* #,##0_);_(* (#,###);_(* "-"??_);_(@_)');
                        $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {



                            $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                            $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

                        });

                        $deployments_number = $deployments_number +2;
                        $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {
                            $cells->setBackground('#E8E8E8');
                            $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                            $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);


                        });
                        $sheet->cell('A'.$deployments_number, function ($cell) {
                            $cell->setValue('Grand Total');
                        });

                        $grand_loan = $grand_loan + $loanp;
//                        dd($grand_loan);

                            $grand_sbv = $grand_sbv + $sbv;
                            $grand_apsaa = $grand_apsaa + $ap ;
                            $grand_cwf = $grand_cwf + $cw;
                        $grand_sdw = $grand_sdw + $sdt;
                        $grand_np_foreach = $grand_np_foreach +$grand_sdw;
                        $grand_sum =$grand_sum + $sum_np;
//                        $sheet->getStyle('D'.$deployments_number)->getNumberFormat()->setFormatCode('#,##0_);(#,##0)');
                        $sheet->getStyle('E'.$deployments_number)->getNumberFormat()->setFormatCode('_(* #,##0_);_(* (#,###);_(* "-"??_);_(@_)');
//                        $sheet->getCell('D'.$deployments_number)->setValueExplicit($grand_total_for_rate, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('F'.$deployments_number)->setValueExplicit($grand_total_for_days, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('G'.$deployments_number)->setValueExplicit($grand_total_for_wages, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('H'.$deployments_number)->setValueExplicit($grand_ot_days_for_each, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('I'.$deployments_number)->setValueExplicit($grand_ot_days_wages_for_each, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('J'.$deployments_number)->setValueExplicit($grand_ex_hours, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('K'.$deployments_number)->setValueExplicit($grand_ex_hours_wages, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('L'.$deployments_number)->setValueExplicit($grand_ap, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('O'.$deployments_number)->setValueExplicit($grand_sdw, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('P'.$deployments_number)->setValueExplicit($grand_holidays, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('Q'.$deployments_number)->setValueExplicit($grand_holidays_wages, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('R'.$deployments_number)->setValueExplicit($grand_np_foreach, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('T'.$deployments_number)->setValueExplicit($grand_loan, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('S'.$deployments_number)->setValueExplicit($grand_sbv, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('U'.$deployments_number)->setValueExplicit($grand_apsaa, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('V'.$deployments_number)->setValueExplicit($grand_cwf, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
//                        $sheet->getCell('O'.$deployments_number)->setValueExplicit($grand_apsa_foreach, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('X'.$deployments_number)->setValueExplicit($grand_sum, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getStyle('E'.$deployments_number.':X'.$deployments_number)->getNumberFormat()->setFormatCode('#,##0_);(#,##0)');
                        $sheet->setBorder('A5:AB'.$deployments_number, 'thin','thin','thin','thin');
                        $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {


                            $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                            $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

                        });

                    });
//

                })->store('xlsx', Config::get('globalvariables.$pathToSaveTemporaryExcelFiles'));


                $fileNameToDownload = $fileName . '.xlsx';

                $file_path = Config::get('globalvariables.$pathToSaveTemporaryExcelFiles') . '/' . $fileNameToDownload;
                if (file_exists($file_path)) {
                    // Send Download
                    return Response::download($file_path, $fileNameToDownload, [
                        'Content-Length: ' . filesize($file_path)
                    ])->deleteFileAfterSend(true);
                } else {
                    // Error
                    exit('Requested file does not exist on our server!');
                }


                $data = array('fileNameToDownload' => $fileNameToDownload);

                return ['responseCode' => 1, 'responseStatus' => 'successful', 'message' => 'your file is ready, and will download in few seconds', 'data' => $data];


            }
            else{

                return view('guards.attendance')->with(['noRecordFound' => 'No record Found']);
            }

        }


    }
    public function getSalaryExportHistory(Request $request){
        $active_month = new Guards\PayrollSalaryMonth();
        $active_month = $active_month->getAll();
//        dd($active_month['date_from']);
        $salary_year = $active_month['salary_year'];


        $salary_month =  substr($active_month['date_from'], 0, -3);
        $monthNum = substr($salary_month,5);
//        dd($monthNum);
//        $monthNum  = 3;
        $dateObj   = DateTime::createFromFormat('!m', $monthNum);
        $monthName = $dateObj->format('F'); // March
        set_time_limit(0);
        error_reporting(E_ALL);
        $input = $request->all();
//        $colString = PHPExcel_Cell::stringFromColumnIndex(1);
        $colString = $this->getcolumnrange('A','ZZ');
        $rules = new Guards\PayrollSalaryRuleModel();
        $rules = $rules->getAll();
//        dd($colString);
        $number = count($rules);
        $endAlpha = $colString[$number];
//        dd($colString[$number]);

//        dd($input['tags_id']);
        $get_finalised_history_by_loan = new Guards\GuardSalaryModel();
        $get_finalised_history_by_loan = $get_finalised_history_by_loan->getModelByValue($input['tags_id'],$input['region_id']);
//        dd($get_finalised_history_by_loan[0]);
        $history_id =  $input['tags_id'];
        $regional_office = $input['region_id'];
        $regional = new Guards\RegionalOfficeModel();
        $regional = $regional->getModelById($regional_office);
//        dd($regional['office_head']);
        $reg = $regional['office_head'];
        $user_id =   Auth::guard('user')->id();
//        $user_name =   Auth::guard('user')->name;
        $user = new UserModel();
        $loans = $get_finalised_history_by_loan;

//        dd($loans[0]);
        if(count($loans) == 0){

        }
        else{


            if(count($loans) > 0){

                $fileName = 'salary'." ".$salary_month." ".$reg;

//                if($request->salary_month){
//                    $fileName = 'salary'.$request->salary_month;
//                }else{
//
//                }
                $sheetsArray = array();


                Excel::create(/**
                 * @param $excel
                 */
                    $fileName, function ($excel) use ($loans,$user,$endAlpha,$colString,$regional_office,$monthName,$salary_year,$reg,$history_id) {
                    //dd($searchResults);


                    $excel->setTitle('Guards Attandance');
                    $excel->setDescription('Guards Attandance');
//                    $sheet->setAllBorders('thin');
//                    $excel->setActiveSheetIndex(0);

//                    center align
//                    $excel->getDefaultStyle()
//                        ->getAlignment()
//                        ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
//                        ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

                    $currentSheetName = 'Operation Salary';
                    $excel->sheet($currentSheetName, function ($sheet) use ($loans,$user,$endAlpha,$colString,$regional_office,$monthName,$salary_year,$reg,$history_id) {
                        $deployments_number = 6;
                        $temp_g_id = 0;
                        //guard_id
                        $temp_g_real_id = 0;
                        $temp_name = 0;
                        $temp_supervisor = 0;
                        $temp_manager = 0;
                        $temp_g_sd = 0;
                        $temp_g_ed = 0;
                        $loanp = 0;
                        $grand_loan = 0;
                        $pa = 0;
                        $grand_ap = 0;
                        $dsbv = 0;
                        $grand_sbv = 0;
                        $grand_mics = 0;
                        $dapsaa = 0;
                        $grand_apsaa = 0;
                        $dcwf = 0;
                        $grand_cwf = 0;
                        $grand_sdw = 0;

                        $rate= 0;
                        $days_p=0;
                        $days_for_each = 0;
                        $wages_for_each = 0;
                        $ot_days_for_each = 0;
                        $ot_days_wages_for_each = 0;
                        $ex_hours = 0;
                        $ex_hours_wages = 0;
                        $special_duty_wages = 0;
                        $holidays = 0 ;
                        $holidays_wages = 0 ;
                        $foreach_holidays_wages = 0 ;
                        $np_foreach = 0;
                        $apsa_foreach = 0;
                        $net_foreach = 0;
                        $ot_total_for_each = 0;

                        $grand_ot_days_for_each = 0;
                        $grand_ot_days_wages_for_each = 0;
                        $grand_ex_hours = 0;
                        $grand_ex_hours_wages = 0;
                        $grand_special_duty_wages = 0;
                        $grand_holidays = 0 ;
                        $grand_holidays_wages = 0 ;
                        $grand_np_foreach = 0;
                        $grand_apsa_foreach = 0;
                        $grand_net_foreach = 0;

                        $np_bf_total_for_each = 0;
                        $grand_total_for_rate = 0;
                        $np_af_total_for_each = 0;

                        $grand_total_for_days = 0;
                        $grand_total_for_wages = 0;
                        $grand_sum = 0;

                        foreach ($loans as $loan) {
//                            dd($loans);

                            $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {

                                $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

                            });
                            if($temp_g_id && $temp_g_id!= $loan['parwest_id']){
                                $sd = $this->calculateSd($temp_g_id,$regional_office);
                                if($sd){
                                    foreach ($sd as $special_duty){

//                                    $deployments_number ++;
//                                        dd($special_duty);
                                        $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {



                                            $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                            $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

                                        });
                                        $sheet->getStyle('E'.$deployments_number.':W'.$deployments_number)->getNumberFormat()->setFormatCode('_(* #,##0_);_(* (#,###);_(* "-"??_);_(@_)');

//                                    dd($loan);
                                        $sheet->getCell('A'.$deployments_number)->setValueExplicit($temp_g_id);
                                        $sheet->getCell('B'.$deployments_number)->setValueExplicit($temp_name);
                                        $sheet->getCell('Z'.$deployments_number)->setValueExplicit($temp_manager);
                                        $sheet->getCell('AA'.$deployments_number)->setValueExplicit($temp_supervisor);

                                        $sheet->getCell('M'.$deployments_number)->setValueExplicit($special_duty['special_duty_hours'], \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                        $sheet->getCell('N'.$deployments_number)->setValueExplicit($special_duty['special_duty_hour_rate'], \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                        $sheet->getCell('O'.$deployments_number)->setValueExplicit(($special_duty['special_duty_hours'] * $special_duty['special_duty_hour_rate']), \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                        $sheet->getCell('Y'.$deployments_number)->setValueExplicit($special_duty['special_duty_remarks'] );

//                                    });
                                        $sd_wagess= 0;
                                        $sd_wagess = $special_duty['special_duty_hours'] * $special_duty['special_duty_hour_rate'];
                                        $np_foreach = $np_foreach + ($special_duty['special_duty_hours'] * $special_duty['special_duty_hour_rate']);
                                        $sheet->getCell('R'.$deployments_number)->setValueExplicit($sd_wagess, \PHPExcel_Cell_DataType::TYPE_NUMERIC );
                                        $deployments_number ++;
//                                        $np_foreach = $np_foreach + ($special_duty['special_duty_hours'] * $special_duty['special_duty_hour_rate']);
//                                        $sheet->getCell('Q'.$deployments_number)->setValueExplicit($np_foreach, \PHPExcel_Cell_DataType::TYPE_NUMERIC );
//                                        $deployments_number ++;
                                    }
                                }


                            }
                            if($temp_g_id && $temp_g_id!= $loan['parwest_id']){
                                $loanp = new GuardLoansModel();
                                $loanp = $loanp->getLoansByGuardWithInterval($temp_g_id,$temp_g_sd,$temp_g_ed);
                                if($loanp){
                                    foreach ($loanp as $loanslist){

//                                    $deployments_number ++;
//                                        dd($special_duty);
                                        $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {



                                            $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                            $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

                                        });
                                        $sheet->getStyle('E'.$deployments_number.':W'.$deployments_number)->getNumberFormat()->setFormatCode('_(* #,##0_);_(* (#,###);_(* "-"??_);_(@_)');

//                                    dd($loan);
                                        $sheet->getCell('A'.$deployments_number)->setValueExplicit($temp_g_id);
                                        $sheet->getCell('B'.$deployments_number)->setValueExplicit($temp_name);
                                        $sheet->getCell('T'.$deployments_number)->setValueExplicit($loanslist->amount_paid, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                        $sheet->getCell('Y'.$deployments_number)->setValueExplicit($loanslist->supervisor_name);
                                        $sheet->getCell('Z'.$deployments_number)->setValueExplicit($temp_manager);
                                        $sheet->getCell('AA'.$deployments_number)->setValueExplicit($temp_supervisor);

//                                        $sheet->getCell('M'.$deployments_number)->setValueExplicit($special_duty['special_duty_hours'], \PHPExcel_Cell_DataType::TYPE_NUMERIC);
//                                        $sheet->getCell('N'.$deployments_number)->setValueExplicit($special_duty['special_duty_hour_rate'], \PHPExcel_Cell_DataType::TYPE_NUMERIC);
//                                        $sheet->getCell('O'.$deployments_number)->setValueExplicit(($special_duty['special_duty_hours'] * $special_duty['special_duty_hour_rate']), \PHPExcel_Cell_DataType::TYPE_NUMERIC);
//                                        $sheet->getCell('Y'.$deployments_number)->setValueExplicit($special_duty['special_duty_remarks'] );

//                                    });
                                        $sd_wagess= 0;
//                                        $sd_wagess = $special_duty['special_duty_hours'] * $special_duty['special_duty_hour_rate'];
//                                        $np_foreach = $np_foreach + ($special_duty['special_duty_hours'] * $special_duty['special_duty_hour_rate']);
//                                        $sheet->getCell('R'.$deployments_number)->setValueExplicit($sd_wagess, \PHPExcel_Cell_DataType::TYPE_NUMERIC );
                                        $deployments_number ++;
//                                        $np_foreach = $np_foreach + ($special_duty['special_duty_hours'] * $special_duty['special_duty_hour_rate']);
//                                        $sheet->getCell('Q'.$deployments_number)->setValueExplicit($np_foreach, \PHPExcel_Cell_DataType::TYPE_NUMERIC );
//                                        $deployments_number ++;
                                    }
                                }


                            }

                            if($temp_g_id && $temp_g_id!= $loan['parwest_id']){
//                                $sbv = $this->calculateSBV($temp_g_id,$regional_office,$days_p);
//                                if($temp_g_id == 'L-10358'){
//                                    dd($sbv);
//                                }
//                                $sheet->setBorder('A5:AA'.$deployments_number, 'thin');
//                                $sheet->getStyle('D'.$deployments_number.':W'.$deployments_number)->getNumberFormat()->setFormatCode('#,##0_);(#,##0)');
                                $sheet->getStyle('E'.$deployments_number.':X'.$deployments_number)->getNumberFormat()->setFormatCode('_(* #,##0_);_(* (#,###);_(* "-"??_);_(@_)');
//                                $sheet->getStyle('A'.$deployments_number.':AA'.$deployments_number)->setBackground('#CCCCCC');
//                                $sheet->getStyle('A'.$deployments_number.':AA'.$deployments_number)->applyFromArray(array(
//                                    'fill' => array(
////                                        'type'  => PHPExcel_Style_Fill::FILL_SOLID,
//                                        'color' => array('rgb' => 'FF0000')
//                                    )
//                                ));
//                                $sheet->cells('A1:D1', function ($cells) {
//                                    $cells->setBackground('#008686');
//                                    $cells->setAlignment('center');
//                                });
                                $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {
                                    $cells->setBackground('#E8E8E8');
                                    $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                    $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);


                                });


//                                $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {
//
//                                    $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
//                                    $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
//
//                                });
//                                if($special_duty_wages > 0){
//
//
//                                    $sheet->getCell('N'.$deployments_number)->setValueExplicit($special_duty_wages, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
//
//                                    $deployments_number ++;
//                                    $special_duty_wages = 0;
//                                }else{
//                                    dd($temp_g_id);

                                $sheet->cell('A'.$deployments_number, function ($cell) {
                                        $cell->setValue('Sub Total');
                                    });
                                $sheet->cell('B'.$deployments_number,$temp_g_id, function ($cell,$temp_g_id) {
                                        $cell->setValue($temp_g_id);
                                    });

//                                    $sheet->getCell('D'.$deployments_number)->setValueExplicit($rate, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $sheet->getCell('F'.$deployments_number)->setValueExplicit($days_for_each, \PHPExcel_Cell_DataType::TYPE_NUMERIC);

                                $sheet->getCell('G'.$deployments_number)->setValueExplicit($wages_for_each, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $sheet->getCell('H'.$deployments_number)->setValueExplicit($ot_days_for_each, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $sheet->getCell('I'.$deployments_number)->setValueExplicit($ot_days_wages_for_each, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $sheet->getCell('J'.$deployments_number)->setValueExplicit($ex_hours, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $sheet->getCell('K'.$deployments_number)->setValueExplicit($ex_hours_wages, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
//                                    $sheet->getCell('M'.$deployments_number)->setValueExplicit($special_duty_wages, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
//                                $sheet->getCell('N'.$deployments_number)->setValueExplicit($holidays, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $grand_ap = $grand_ap+$pa;
                                $sheet->getCell('L'.$deployments_number)->setValueExplicit($pa, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                round($holidays_wages,2);
                                    $sheet->getCell('Q'.$deployments_number)->setValueExplicit($foreach_holidays_wages, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $sheet->getCell('R'.$deployments_number)->setValueExplicit($np_foreach, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
//                                $loanp = new GuardLoansModel();
//                                $loanp = $loanp->getLoansByGuardWithInterval($temp_g_id,$temp_g_sd,$temp_g_ed);
                                $deduction = new Guards\PayrollDeductionModel();
                                $deduction = $deduction->getByUserIdn($temp_g_real_id,$history_id);
//                                dd($deduction);
//                                if($temp_g_id = 14750){
//                                    dd($deduction);
//                                }
                                $loanp  = $deduction['oploan_amount'];

                                $sheet->getCell('T'.$deployments_number)->setValueExplicit($loanp, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $grand_loan = $grand_loan + $loanp;


//                                $ap = $this->calculateApsa($temp_g_id,$regional_office);
                                $ap  = $deduction['apsa_amount'];
                                    $grand_apsaa = $grand_apsaa + $ap;
                                    $sbv=0;
                                $mics=0;
//                                    $sbv = $this->calculateSBV($temp_g_id,$regional_office,$days_for_each);
                                $sbv  = $deduction['sbv_amount'];
                                $mics  = $deduction['misc_amount'];
                                $grand_mics = $grand_mics + $mics;
                                $grand_sbv = $grand_sbv + $sbv;

                                $cw = 0;
                                $cw  = $deduction['cwf_amount'];

//                                $status = $this->calculateGuardStatus($temp_g_id);
//                                if($status == 1){
//                                    $cwf = new PayrollDefaultsModel();
//                                    $cwf = $cwf->getModelByGuardWithInterval($regional_office);
//                                    $cw = $cwf['cwf_value'];
//
//                                }
                                $grand_cwf = $grand_cwf + $cw;

                                $sdt= $this->calculateSdTotal($temp_g_id,$regional_office);
//                                dd($sdt);




                                $sum_np = $np_foreach  - $loanp -$ap - $cw -$sbv -$mics;
                                $grand_sum = $grand_sum + $sum_np;
//                                  $sum_np = $np_foreach - $loanp  - $cw;
//                                $grand_sum = $grand_sum + $sum_np;
                                $grand_sdw = $grand_sdw + $sdt;
                                $sheet->getCell('O'.$deployments_number)->setValueExplicit($sdt, \PHPExcel_Cell_DataType::TYPE_NUMERIC);

                                $sheet->getCell('U'.$deployments_number)->setValueExplicit($ap, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
//                                    $sheet->getCell('T'.$deployments_number)->setValue($ap);
//                                $sheet->cell('T'.$deployments_number,$ap, function ($cell,$ap) {
//                                    $cell->setValue($ap);
//                                });
                                    $sheet->getCell('V'.$deployments_number)->setValueExplicit($cw, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                    $sheet->getCell('W'.$deployments_number)->setValueExplicit($mics, \PHPExcel_Cell_DataType::TYPE_NUMERIC);

                                    $sheet->getCell('X'.$deployments_number)->setValueExplicit($sum_np, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                    $sheet->getCell('S'.$deployments_number)->setValueExplicit($sbv, \PHPExcel_Cell_DataType::TYPE_NUMERIC);

//                                    $sheet->cell('R'.$deployments_number,$sbv, function ($cell,$sbv) {
//                                        $cell->setValue($sbv);
//                                    });
//                                $sheet->getStyle('D'.$deployments_number.':W'.$deployments_number)->getNumberFormat()->setFormatCode('#,##0_);(#,##0)');
                                $sheet->getStyle('E'.$deployments_number.':X'.$deployments_number)->getNumberFormat()->setFormatCode('_(* #,##0_);_(* (#,###);_(* "-"??_);_(@_)');

//                                    $sheet->cells('A'.$deployments_number.':AO'.$deployments_number, function ($cells) {
//
//                                        $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
//                                        $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
//
//                                    });


                                    $deployments_number ++;
                                    $rate = 0;
                                    $days_for_each = 0;
                                    $wages_for_each = 0;
                                    $ot_days_for_each = 0;
                                    $ot_days_wages_for_each = 0;
                                    $ex_hours = 0;
                                    $ex_hours_wages = 0;
                                    $special_duty_wages = 0;
                                    $holidays = 0;
                                    $holidays_wages = 0;
                                    $np_foreach = 0;
                                    $apsa_foreach = 0;
                                    $net_foreach = 0;
                                    $pa =0;
                                    $loanp =0;
                                $foreach_holidays_wages = 0;


                                    $ot_total_for_each = 0;

                                    $np_bf_total_for_each = 0;

                                    $np_af_total_for_each = 0;


//                                }

                            }

                            $sheet->row(1, array(
                                'PARWEST PACIFIC SECURITY ( PVT ) LTD.'

                            ));
                            $sheet->row(2, array(
                                '176-CAVALARY GROUND, LAHORE CANTT.'

                            ));
                            $sheet->row(3, array(
                                'Salary for the month of   '.$monthName."  ".$salary_year

                            ));
                            $sheet->row(4, array(
                                'Region :     '.$reg."  "

                            ));
                            $sheet->getStyle('A5:AA5')->getAlignment()->setWrapText(true);
////                            $sheet->getColumnDimension('A')->setWidth(0);
                            $sheet->getStyle('A5:AA5')->getAlignment()->applyFromArray(
                                    array(
                                         'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                        'vertical'   => \PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                        'rotation'   => 0,
                                        'wrap'       => TRUE
                                    )
                              );
//                            $sheet->setAllBorders('thin');
//                            $sheet->getDefaultStyle('A5:AA5')->getAlignment()
//
//                            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
//                            ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);


//                            $sheet->getStyle('A5:AA5')->applyFromArray([
//                                'alignment' => array(
//                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
//                                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
//                                    'rotation'   => 0,
//                                    'wrap'       => true
//                                )]);
//                            $sheet->getStyle('A5:AA5')->getColumnDimension('G')->setWidth(13);
//                            $sheet->setWidth('G', 12);
//                            $sheet->getColumnDimension('G')->setWidth(12);
                            $rules = new Guards\PayrollSalaryRuleModel();
                            $rules = $rules->getAll();

                            $number = count($rules);
//                            dd($number);

                            for($i = 0; $i < $number; $i++){

                                    $value = $rules[$i]->name;
                                    $code = $rules[$i]->code;
//
                                if($code == 'MGI' || $code == 'SPI'){
                                    //notjomog

                                }else{
                                    if(isset($value)){

                                        $sheet->cell($colString[$i].'5',$value, function ($cell,$value) {
                                            $cell->setValue($value);
                                        });
                                    }
                                }


                            }


                            $temp_g_id = $loan['parwest_id'];
                            $temp_g_real_id = $loan['guard_id'];
                            $temp_name  = $loan['guard_Name'];
                            $temp_supervisor = $loan['guard_supervisor_name'];
                            $temp_manager = $loan['guard_manager_name'];
                            //                                        $sheet->getCell('B'.$deployments_number)->setValueExplicit($loan['guard_Name']);
//                                        $sheet->getCell('Y'.$deployments_number)->setValueExplicit($loan['guard_manager_name']);
//                                        $sheet->getCell('AA'.$deployments_number)->setValueExplicit($loan['guard_supervisor_name']);


                            $days_p = $loan['days'];
                            $temp_g_sd = $loan['startDay'];
                            $temp_g_ed = $loan['endDay'];

                            if($loan['is_overtime'] == 0){
                                $rate =  $rate + $loan['location_rate'];
                                $days_for_each =  $days_for_each + $loan['days'];
                                $wages_for_each =  $wages_for_each + ($loan['location_rate'] / 31)* $loan['days'];
                                $wages_for_each = (int)$wages_for_each;
                            }


                            $ot_total_for_each =  $ot_total_for_each + $loan['is_overtime'] * $loan['overtime_cost'];




                            $days = $loan['days'];
                            $wages = ($loan['location_rate'] / 31)* $loan['days'];


                            $eh = $loan['is_extra'];
                            $eh_wages = $loan['extrahours_cost'];

                            $count = count($rules);

                            $rule_values  = $this->getRulesValuesByDeploymentId($loan['deployment_id'],$history_id);
//                            dd($rule_values);
                            for($i = 0; $i < $number; $i++){
                                $value = 0;
                                if(isset($count)){

                                    $value = $rule_values[$i]->salary_rule_value;
                                    $code = $rule_values[$i]->code;

                                    if($rule_values[$i]->code == 'RAT'){
                                        $grand_total_for_rate = $grand_total_for_rate + (int)$value;

                                    }
                                    if($rule_values[$i]->code == 'DAY'){
                                        $grand_total_for_days = $grand_total_for_days + (int)$value;

                                    }
                                    if($rule_values[$i]->code == 'WGS'){
                                        $np_af_total_for_each = $np_af_total_for_each + (int)$value;
                                        $grand_total_for_wages = $grand_total_for_wages + (int)$value;

                                    }
                                    if($rule_values[$i]->code == 'OTD'){
                                        $ot_days_for_each = $ot_days_for_each + (int)$value;
                                        $grand_ot_days_for_each = $grand_ot_days_for_each + (int)$value;

//

                                    }if($rule_values[$i]->code == 'OTW'){
                                        $ot_days_wages_for_each = $ot_days_wages_for_each + (int)$value;
                                        $grand_ot_days_wages_for_each = $grand_ot_days_wages_for_each + (int)$value;
//

                                    }if($rule_values[$i]->code == 'EHC'){
                                        $ex_hours = $ex_hours + (int)$value;
                                        $grand_ex_hours = $grand_ex_hours + (int)$value;
//

                                    }if($rule_values[$i]->code == 'EHW'){
                                        $ex_hours_wages = $ex_hours_wages + (int)$value;
                                        $grand_ex_hours_wages = $grand_ex_hours_wages + (int)$value;
//

                                    }if($rule_values[$i]->code == 'SPD'){
                                        $special_duty_wages = $special_duty_wages + (int)$value;
                                        $grand_special_duty_wages = $grand_special_duty_wages + (int)$value;
//

                                    }if($rule_values[$i]->code == 'HOD'){
                                        $holidays = $holidays + (int)$value;
                                        $grand_holidays = $grand_holidays + (int)$value;
//

                                    }if($rule_values[$i]->code == 'HOW'){


                                        $holidays_wages = $holidays_wages + (int)$value;
                                        $grand_holidays_wages = $grand_holidays_wages + (int)$value;
                                        $foreach_holidays_wages = $foreach_holidays_wages + (int)$value;

//

                                    }if($rule_values[$i]->code == 'GRS'){
                                        $np_foreach = $np_foreach + (int)$value;
                                        $np_foreach = (int)$np_foreach;
                                        $grand_np_foreach = $grand_np_foreach + (int)$value;
//

                                    }
                                    if($rule_values[$i]->code == 'APS'){
                                        $apsa_foreach = $apsa_foreach + (int)$value;
                                        $grand_apsa_foreach = $grand_apsa_foreach + (int)$value;
//

                                    }if($rule_values[$i]->code == 'NPY'){
                                        $net_foreach = $net_foreach + (int)$value;
                                        $grand_net_foreach = $grand_net_foreach + (int)$value;
//

                                    }
                                    if($code == 'POA'){
                                        //notjomog
                                        $pa = $pa + (int)$value;

                                    }
//                                    $sheet->getCell($colString[$i].$deployments_number)->setValueExplicit($value, \PHPExcel_Cell_DataType::TYPE_NUMERIC);

//
                                    if($code == 'MGI' || $code == 'SPI'){
                                        //notjomog

                                    }
//                                    elseif($code == 'RAT' && $loan['is_overtime'] == 1){
////                                        dd($value);
//
//                                    }
                                    else{
                                        $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
//                                        0
//                                        if (is_numeric($value)) {


//                                            $value = (int)$value;
//                                            if(ctype_digit($value)){
//                                                $value =  number_format($value);

//                                                $sheet->getCell($colString[$i].$deployments_number)->setValueExplicit($value, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                                $sheet->getCell($colString[$i].$deployments_number)->setValue($value);
//                                                $cell->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

//                                            }



//                                            else{
////                                                $value =  number_format($value);
//                                                $sheet->cell($colString[$i].$deployments_number,$value,$thinBorder, function ($cell,$value,$thinBorder) {
////                                                    $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);
//                                                    $cell->setValue($value);
//                                                    $cell->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
//
//                                                });
//                                            }

//                                        } else {
////                                            $value =  number_format($value);
//                                            $sheet->cell($colString[$i].$deployments_number,$value,$thinBorder, function ($cell,$value,$thinBorder) {
////                                                    $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);
////                                                $cell->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
//                                                $cell->setValue($value);
//                                            });
////                                                                               $cell->setValue($value);
//                                        }


                                    }

                                }
                            }

//                            $grand_total_for_days = $grand_total_for_days + $days;
//                            $grand_total_for_wages = $grand_total_for_wages + $wages;
//                            $grand_total_for_wages = (int)$grand_total_for_wages;
//
//                            $sheet->getStyle('D'.$deployments_number.':W'.$deployments_number)->getNumberFormat()->setFormatCode('#,##0_);(#,##0)');
                            $sheet->getStyle('E'.$deployments_number.':X'.$deployments_number)->getNumberFormat()->setFormatCode('_(* #,##0_);_(* (#,###);_(* "-"??_);_(@_)');

                            $deployments_number = $deployments_number+1;

                            $sheet->cells('A5:AA5', function ($cells) {
                                $cells->setFont(array(
                                    'bold' => true
                                ));

                                $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

                            });
//                            $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {
//
//
//                                $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
//                                $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
//
//                            });

                            $sheet->setStyle(array(
                                'font' => array(
                                    'name' => 'Calibri',
                                ),
                                'setSize' => array(
                                    'height' => '35',
                                )
                            ));
//
                            $sheet->mergeCells('A1:R1');
                            $sheet->getStyle('A1:R1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);



                            $sheet->cells('A1:R1', function ($cells) {

                                $cells->setBackground('#ffffff');
                                $cells->setFontColor('#000000');
                                $cells->setFont(array(
                                    'size' => '20',
                                    'bold' => true
                                ));

                            });
                            $sheet->cells('A3:Z3', function ($cells) {

                                $cells->setBackground('#ffffff');
                                $cells->setFontColor('#000000');
                                $cells->setFont(array(
                                    'size' => '12',
                                    'bold' => true,

                                ));

                            });
                            $sheet->cells('A5:Z5', function ($cells) {

                                $cells->setBackground('#ffffff');
                                $cells->setFontColor('#000000');
                                $cells->setFont(array(
                                    'size' => '12',
                                    'bold' => true,

                                ));

                            });

                            $sheet->cells('A2:A4', function ($cells) {
                                $cells->setFont(array(
                                    'bold' => true
                                ));

                            });
//
//                            $sheet->getStyle()->getNumberFormat()->setFormatCode('#,##0_);(#,##0)');
                            $sheet->getStyle()->getNumberFormat()->setFormatCode('_(* #,##0_);_(* (#,###);_(* "-"??_);_(@_)');

                            $sheet->mergeCells('A1:R1');
                            $sheet->mergeCells('A2:R2');
                            $sheet->mergeCells('A3:R3');
//                            $sheet->setWidth('A', 12);
                            $sheet->setWidth(array(
                                'A'     =>  13,
                                'B'     =>  30,
                                'C'     =>  15,
                                'D'     =>  15,
                                'E'     =>  15,
                                'F'     =>  15,
                                'G'     =>  15,
                                'H'     =>  15,
                                'I'     =>  15,
                                'J'     =>  15,
                                'K'     =>  15,
                                'L'     =>  15,
                                'M'     =>  15,
                                'N'     =>  15,
                                'O'     =>  15,
                                'P'     =>  15,
                                'Q'     =>  15,
                                'R'     =>  15,
                                'S'     =>  15,
                                'T'     =>  15,
                                'U'     =>  15,
                                'V'     =>  15,
                                'W'     =>  15,
                                'X'     =>  25,
                                'Y'     =>  25,
                                'Z'     =>  25,
                                'AA'     =>  25
                            ));
//                            $sd = $this->calculateSd($temp_g_id,$regional_office);
//                            if($sd){
//                                foreach ($sd as $special_duty){
//
////                                    $deployments_number ++;
////                                        dd($special_duty);
//                                    $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {
//
//
//
//                                        $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
//                                        $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
//
//                                    });
//                                    $sheet->getStyle('D'.$deployments_number.':W'.$deployments_number)->getNumberFormat()->setFormatCode('_(* #,##0_);_(* (#,###);_(* "-"??_);_(@_)');
//
////                                    dd($loan);
//                                    $sheet->getCell('A'.$deployments_number)->setValueExplicit($temp_g_id);
//                                    $sheet->getCell('B'.$deployments_number)->setValueExplicit($loan['guard_Name']);
//                                    $sheet->getCell('Y'.$deployments_number)->setValueExplicit($loan['guard_manager_name']);
//                                    $sheet->getCell('AA'.$deployments_number)->setValueExplicit($loan['guard_supervisor_name']);
//
//                                    $sheet->getCell('L'.$deployments_number)->setValueExplicit($special_duty['special_duty_hours'], \PHPExcel_Cell_DataType::TYPE_NUMERIC);
//                                    $sheet->getCell('M'.$deployments_number)->setValueExplicit($special_duty['special_duty_hour_rate'], \PHPExcel_Cell_DataType::TYPE_NUMERIC);
//                                    $sheet->getCell('N'.$deployments_number)->setValueExplicit(($special_duty['special_duty_hours'] * $special_duty['special_duty_hour_rate']), \PHPExcel_Cell_DataType::TYPE_NUMERIC);
//                                    $sheet->getCell('X'.$deployments_number)->setValueExplicit($special_duty['special_duty_remarks'] );
//
////                                    });
//                                    $deployments_number ++;
//                                }
//                            }



//                            $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {
//                                $cells->setBackground('#E8E8E8');
//                                $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
//                                $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
//
//
//                            });

                        }
                        $sd = $this->calculateSd($temp_g_id,$regional_office);
                        if($sd){
                            foreach ($sd as $special_duty){

//                                    $deployments_number ++;
//                                        dd($special_duty);
                                $sheet->cells('A'.$deployments_number.':AB'.$deployments_number, function ($cells) {



                                    $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                    $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

                                });
                                $sheet->getStyle('E'.$deployments_number.':X'.$deployments_number)->getNumberFormat()->setFormatCode('_(* #,##0_);_(* (#,###);_(* "-"??_);_(@_)');

//                                    dd($loan);
                                $sheet->getCell('A'.$deployments_number)->setValueExplicit($temp_g_id);
                                $sheet->getCell('B'.$deployments_number)->setValueExplicit($temp_name);
                                $sheet->getCell('Z'.$deployments_number)->setValueExplicit($temp_manager);
                                $sheet->getCell('AA'.$deployments_number)->setValueExplicit($temp_supervisor);

                                $sheet->getCell('M'.$deployments_number)->setValueExplicit($special_duty['special_duty_hours'], \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $sheet->getCell('N'.$deployments_number)->setValueExplicit($special_duty['special_duty_hour_rate'], \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $sheet->getCell('O'.$deployments_number)->setValueExplicit(($special_duty['special_duty_hours'] * $special_duty['special_duty_hour_rate']), \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $sheet->getCell('Y'.$deployments_number)->setValueExplicit($special_duty['special_duty_remarks'] );

//                                    });
                                $sd_wagess= 0;
                                $sd_wagess = $special_duty['special_duty_hours'] * $special_duty['special_duty_hour_rate'];
                                $np_foreach = $np_foreach + ($special_duty['special_duty_hours'] * $special_duty['special_duty_hour_rate']);
                                $sheet->getCell('R'.$deployments_number)->setValueExplicit($sd_wagess, \PHPExcel_Cell_DataType::TYPE_NUMERIC );
                                $deployments_number ++;
                            }
                        }
                        $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {
                            $cells->setBackground('#E8E8E8');
                            $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                            $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);


                        });

                        $sheet->cell('A'.$deployments_number, function ($cell) {
                            $cell->setValue('Sub Total');
                        });
                        $sheet->cell('B'.$deployments_number,$temp_g_id, function ($cell,$temp_g_id) {
                            $cell->setValue($temp_g_id);
                        });
//                                dd($temp_g_id);
//                            $sheet->getCell('D'.$deployments_number)->setValueExplicit($rate, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('F'.$deployments_number)->setValueExplicit($days_for_each, \PHPExcel_Cell_DataType::TYPE_NUMERIC);

                        $sheet->getCell('G'.$deployments_number)->setValueExplicit($wages_for_each, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('H'.$deployments_number)->setValueExplicit($ot_days_for_each, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('I'.$deployments_number)->setValueExplicit($ot_days_wages_for_each, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('J'.$deployments_number)->setValueExplicit($ex_hours, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('K'.$deployments_number)->setValueExplicit($ex_hours_wages, \PHPExcel_Cell_DataType::TYPE_NUMERIC);





                        $sheet->getCell('P'.$deployments_number)->setValueExplicit($holidays, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        round($holidays_wages,2);
                        $sheet->getCell('Q'.$deployments_number)->setValueExplicit($holidays_wages, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sdt= $this->calculateSdTotal($temp_g_id,$regional_office);
//                        $np_foreach = $np_foreach + $sdt ;
                            $sheet->getCell('O'.$deployments_number)->setValueExplicit($sdt, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('R'.$deployments_number)->setValueExplicit($np_foreach, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $cw = 0;
                        $deduction = new Guards\PayrollDeductionModel();
                        $deduction = $deduction->getByUserIdn($temp_g_real_id,$history_id);
                        $loanp  = $deduction['oploan_amount'];
                        $cw  = $deduction['cwf_amount'];
                        $sbv  = $deduction['sbv_amount'];
                        $misc  = $deduction['misc_amount'];
                        $ap  = $deduction['apsa_amount'];
//                        $ap = $this->calculateApsa($temp_g_id,$regional_office);
//                        $sbv = $this->calculateSBV($temp_g_id,$regional_office,$days_for_each);
//                            if($temp_g_id == 'L-10358'){
//                                dd($sbv);
//                            }
//                             $sdt= $this->calculateSdTotal($temp_g_id,$regional_office);

//                             dd($sdt);

//                        $cw = 0;
//                        $status = $this->calculateGuardStatus($temp_g_id);
//                        if($status == 1){
//                            $cwf = new PayrollDefaultsModel();
//                            $cwf = $cwf->getModelByGuardWithInterval($regional_office);
//                            $cw = $cwf['cwf_value'];
//                        }
//                            $grand_loan = $grand_loan + $loanp;
//                            $grand_sbv = $grand_sbv + $sbv;
//                            $grand_apsaa = $grand_apsaa + $ap ;
//                            $grand_cwf = $grand_cwf + $cw;
//                        $loanp = new GuardLoansModel();
//                        $loanp = $loanp->getModelByGuardWithInterval($temp_g_id,$temp_g_sd,$temp_g_ed);

                        $sum_np = $np_foreach   - $loanp -$ap - $cw -$sbv -$misc;
//                            $grand_sum = $np_foreach+$sum_np;
//                            $grand_sdw = $grand_sdw + $sdt;
//                        $sheet->getCell('N'.$deployments_number)->setValueExplicit($sdt, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('U'.$deployments_number)->setValueExplicit($ap, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('V'.$deployments_number)->setValueExplicit($cw, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('W'.$deployments_number)->setValueExplicit($misc, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('S'.$deployments_number)->setValueExplicit($sbv, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('X'.$deployments_number)->setValueExplicit($sum_np, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getStyle('E'.$deployments_number.':X'.$deployments_number)->getNumberFormat()->setFormatCode('_(* #,##0_);_(* (#,###);_(* "-"??_);_(@_)');
                        $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {



                            $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                            $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

                        });

                        $deployments_number = $deployments_number +2;
                        $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {
                            $cells->setBackground('#E8E8E8');
                            $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                            $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);


                        });
                        $sheet->cell('A'.$deployments_number, function ($cell) {
                            $cell->setValue('Grand Total');
                        });

                        $grand_loan = $grand_loan + $loanp;
//                        dd($grand_loan);

                            $grand_sbv = $grand_sbv + $sbv;
                            $grand_mics = $grand_mics + $mics;
                            $grand_apsaa = $grand_apsaa + $ap ;
                            $grand_cwf = $grand_cwf + $cw;
                        $grand_sdw = $grand_sdw + $sdt;
                        $grand_np_foreach = $grand_np_foreach +$grand_sdw;
                        $grand_sum =$grand_sum + $sum_np;
//                        $sheet->getStyle('D'.$deployments_number)->getNumberFormat()->setFormatCode('#,##0_);(#,##0)');
                        $sheet->getStyle('E'.$deployments_number)->getNumberFormat()->setFormatCode('_(* #,##0_);_(* (#,###);_(* "-"??_);_(@_)');
//                        $sheet->getCell('D'.$deployments_number)->setValueExplicit($grand_total_for_rate, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('F'.$deployments_number)->setValueExplicit($grand_total_for_days, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('G'.$deployments_number)->setValueExplicit($grand_total_for_wages, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('H'.$deployments_number)->setValueExplicit($grand_ot_days_for_each, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('I'.$deployments_number)->setValueExplicit($grand_ot_days_wages_for_each, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('J'.$deployments_number)->setValueExplicit($grand_ex_hours, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('K'.$deployments_number)->setValueExplicit($grand_ex_hours_wages, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('L'.$deployments_number)->setValueExplicit($grand_ap, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('O'.$deployments_number)->setValueExplicit($grand_sdw, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('P'.$deployments_number)->setValueExplicit($grand_holidays, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('Q'.$deployments_number)->setValueExplicit($grand_holidays_wages, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('R'.$deployments_number)->setValueExplicit($grand_np_foreach, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('T'.$deployments_number)->setValueExplicit($grand_loan, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('S'.$deployments_number)->setValueExplicit($grand_sbv, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('U'.$deployments_number)->setValueExplicit($grand_apsaa, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('V'.$deployments_number)->setValueExplicit($grand_cwf, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('W'.$deployments_number)->setValueExplicit($grand_mics, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
//                        $sheet->getCell('O'.$deployments_number)->setValueExplicit($grand_apsa_foreach, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('X'.$deployments_number)->setValueExplicit($grand_sum, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getStyle('E'.$deployments_number.':X'.$deployments_number)->getNumberFormat()->setFormatCode('#,##0_);(#,##0)');
                        $sheet->setBorder('A5:AB'.$deployments_number, 'thin','thin','thin','thin');
                        $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {


                            $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                            $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

                        });

                    });
//

                })->store('xlsx', Config::get('globalvariables.$pathToSaveTemporaryExcelFiles'));


                $fileNameToDownload = $fileName . '.xlsx';

                $file_path = Config::get('globalvariables.$pathToSaveTemporaryExcelFiles') . '/' . $fileNameToDownload;
                if (file_exists($file_path)) {
                    // Send Download
                    return Response::download($file_path, $fileNameToDownload, [
                        'Content-Length: ' . filesize($file_path)
                    ])->deleteFileAfterSend(true);
                } else {
                    // Error
                    exit('Requested file does not exist on our server!');
                }


                $data = array('fileNameToDownload' => $fileNameToDownload);

                return ['responseCode' => 1, 'responseStatus' => 'successful', 'message' => 'your file is ready, and will download in few seconds', 'data' => $data];


            }
            else{

                return view('guards.attendance')->with(['noRecordFound' => 'No record Found']);
            }

        }


    }
    public function getSalaryExportHistoryClearance(Request $request){
        $input = $request->all();
        $active_month = new Guards\PayrollSalaryMonth();
        $active_month = $active_month->getAll();
//        dd($active_month['date_from']);

        $clearancePivot = new Guards\GuardClearanceHistoryStatModel();
        $clearancePivot = $clearancePivot->getModelByValue($input['tags_id'],$input['region_id']);

//        dd($input['tags_id']);
        $clearacne_month = $clearancePivot['month'];
        $salary_year = $active_month['salary_year'];
        $total_days = cal_days_in_month(CAL_GREGORIAN, $clearacne_month, $salary_year);
//        dd($number);
//        $total_days = $this->getDaysInMonthByDate($clearacne_month);
        $salary_month =  substr($active_month['date_from'], 0, -3);
        $monthNum = substr($salary_month,5);
//        dd($monthNum);
//        $monthNum  = 3;
        $dateObj   = DateTime::createFromFormat('!m', $clearacne_month);
        $monthName = $dateObj->format('F'); // March
        set_time_limit(0);
        error_reporting(E_ALL);

//        $colString = PHPExcel_Cell::stringFromColumnIndex(1);
        $colString = $this->getcolumnrange('A','ZZ');
        $rules = new Guards\PayrollSalaryRuleModel();
        $rules = $rules->getAll();
//        dd($colString);
        $number = count($rules);
        $endAlpha = $colString[$number];
//        dd($colString[$number]);

        $get_finalised_history_by_loan = new Guards\GuardDefaultClearanceModel();
        $get_finalised_history_by_loan = $get_finalised_history_by_loan->getModelByValue($input['tags_id'],$input['region_id']);
//        dd($get_finalised_history_by_loan[0]);
        $history_id =  $input['tags_id'];
        $regional_office = 1;
//        $regional = new Guards\RegionalOfficeModel();
//        $regional = $regional->getModelById($regional_office);
//        dd($regional['office_head']);
        $reg = 1;
        $user_id =   Auth::guard('user')->id();
//        $user_name =   Auth::guard('user')->name;
        $user = new UserModel();
        $loans = $get_finalised_history_by_loan;
        if(count($loans) == 0){

        }
        else{


            if(count($loans) > 0){

                $fileName = 'Clearance'." ".$salary_month." ";

//                if($request->salary_month){
//                    $fileName = 'salary'.$request->salary_month;
//                }else{
//
//                }
                $sheetsArray = array();


                Excel::create(/**
                 * @param $excel
                 */
                    $fileName, function ($excel) use ($loans,$total_days,$user,$endAlpha,$colString,$monthName,$salary_year,$regional_office,$reg,$history_id) {
                    //dd($searchResults);


                    $excel->setTitle('Guards Attandance');
                    $excel->setDescription('Guards Attandance');
//                    $sheet->setAllBorders('thin');
//                    $excel->setActiveSheetIndex(0);

//                    center align
//                    $excel->getDefaultStyle()
//                        ->getAlignment()
//                        ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
//                        ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

                    $currentSheetName = 'Operation Salary';
                    $excel->sheet($currentSheetName, function ($sheet) use ($loans,$total_days,$user,$endAlpha,$colString,$monthName,$salary_year,$reg,$regional_office,$history_id) {
                        $deployments_number = 6;
                        $temp_g_id = 0;
                        $temp_name = 0;
                        $temp_supervisor = 0;
                        $temp_manager = 0;
                        $temp_g_sd = 0;
                        $temp_g_ed = 0;
                        $loanp = 0;
                        $grand_loan = 0;
                        $pa = 0;
                        $grand_ap = 0;
                        $dsbv = 0;
                        $grand_sbv = 0;
                        $dapsaa = 0;
                        $grand_apsaa = 0;
                        $dcwf = 0;
                        $grand_cwf = 0;
                        $grand_sdw = 0;

                        $rate= 0;
                        $days_p=0;
                        $days_for_each = 0;
                        $wages_for_each = 0;
                        $ot_days_for_each = 0;
                        $ot_days_wages_for_each = 0;
                        $ex_hours = 0;
                        $ex_hours_wages = 0;
                        $special_duty_wages = 0;
                        $holidays = 0 ;
                        $holidays_wages = 0 ;
                        $foreach_holidays_wages = 0 ;
                        $np_foreach = 0;
                        $apsa_foreach = 0;
                        $net_foreach = 0;
                        $ot_total_for_each = 0;

                        $grand_ot_days_for_each = 0;
                        $grand_ot_days_wages_for_each = 0;
                        $grand_ex_hours = 0;
                        $grand_ex_hours_wages = 0;
                        $grand_special_duty_wages = 0;
                        $grand_holidays = 0 ;
                        $grand_holidays_wages = 0 ;
                        $grand_np_foreach = 0;
                        $grand_apsa_foreach = 0;
                        $grand_net_foreach = 0;

                        $np_bf_total_for_each = 0;
                        $grand_total_for_rate = 0;
                        $np_af_total_for_each = 0;

                        $grand_total_for_days = 0;
                        $grand_total_for_wages = 0;
                        $grand_sum = 0;

                        foreach ($loans as $loan) {
//                            dd($loans);

                            $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {

                                $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

                            });
                            if($temp_g_id && $temp_g_id!= $loan['parwest_id']){
                                $sd = $this->calculateSd($temp_g_id,$regional_office);
                                if($sd){
                                    foreach ($sd as $special_duty){

//                                    $deployments_number ++;
//                                        dd($special_duty);
                                        $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {



                                            $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                            $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

                                        });
                                        $sheet->getStyle('E'.$deployments_number.':W'.$deployments_number)->getNumberFormat()->setFormatCode('_(* #,##0_);_(* (#,###);_(* "-"??_);_(@_)');

//                                    dd($loan);
                                        $sheet->getCell('A'.$deployments_number)->setValueExplicit($temp_g_id);
                                        $sheet->getCell('B'.$deployments_number)->setValueExplicit($temp_name);
                                        $sheet->getCell('Z'.$deployments_number)->setValueExplicit($temp_manager);
                                        $sheet->getCell('AA'.$deployments_number)->setValueExplicit($temp_supervisor);

                                        $sheet->getCell('M'.$deployments_number)->setValueExplicit($special_duty['special_duty_hours'], \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                        $sheet->getCell('N'.$deployments_number)->setValueExplicit($special_duty['special_duty_hour_rate'], \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                        $sheet->getCell('O'.$deployments_number)->setValueExplicit(($special_duty['special_duty_hours'] * $special_duty['special_duty_hour_rate']), \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                        $sheet->getCell('Y'.$deployments_number)->setValueExplicit($special_duty['special_duty_remarks'] );

//                                    });
                                        $sd_wagess= 0;
                                        $sd_wagess = $special_duty['special_duty_hours'] * $special_duty['special_duty_hour_rate'];
                                        $np_foreach = $np_foreach + ($special_duty['special_duty_hours'] * $special_duty['special_duty_hour_rate']);
                                        $sheet->getCell('R'.$deployments_number)->setValueExplicit($sd_wagess, \PHPExcel_Cell_DataType::TYPE_NUMERIC );
                                        $deployments_number ++;
//                                        $np_foreach = $np_foreach + ($special_duty['special_duty_hours'] * $special_duty['special_duty_hour_rate']);
//                                        $sheet->getCell('Q'.$deployments_number)->setValueExplicit($np_foreach, \PHPExcel_Cell_DataType::TYPE_NUMERIC );
//                                        $deployments_number ++;
                                    }
                                }


                            }

                            if($temp_g_id && $temp_g_id!= $loan['parwest_id']){
//                                $sbv = $this->calculateSBV($temp_g_id,$regional_office,$days_p);
//                                if($temp_g_id == 'L-10358'){
//                                    dd($sbv);
//                                }
//                                $sheet->setBorder('A5:AA'.$deployments_number, 'thin');
//                                $sheet->getStyle('D'.$deployments_number.':W'.$deployments_number)->getNumberFormat()->setFormatCode('#,##0_);(#,##0)');
                                $sheet->getStyle('E'.$deployments_number.':X'.$deployments_number)->getNumberFormat()->setFormatCode('_(* #,##0_);_(* (#,###);_(* "-"??_);_(@_)');
//                                $sheet->getStyle('A'.$deployments_number.':AA'.$deployments_number)->setBackground('#CCCCCC');
//                                $sheet->getStyle('A'.$deployments_number.':AA'.$deployments_number)->applyFromArray(array(
//                                    'fill' => array(
////                                        'type'  => PHPExcel_Style_Fill::FILL_SOLID,
//                                        'color' => array('rgb' => 'FF0000')
//                                    )
//                                ));
//                                $sheet->cells('A1:D1', function ($cells) {
//                                    $cells->setBackground('#008686');
//                                    $cells->setAlignment('center');
//                                });
                                $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {
                                    $cells->setBackground('#E8E8E8');
                                    $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                    $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);


                                });


//                                $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {
//
//                                    $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
//                                    $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
//
//                                });
//                                if($special_duty_wages > 0){
//
//
//                                    $sheet->getCell('N'.$deployments_number)->setValueExplicit($special_duty_wages, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
//
//                                    $deployments_number ++;
//                                    $special_duty_wages = 0;
//                                }else{
//                                    dd($temp_g_id);

                                $sheet->cell('A'.$deployments_number, function ($cell) {
                                        $cell->setValue('Sub Total');
                                    });
                                $sheet->cell('B'.$deployments_number,$temp_g_id, function ($cell,$temp_g_id) {
                                        $cell->setValue($temp_g_id);
                                    });

//                                    $sheet->getCell('D'.$deployments_number)->setValueExplicit($rate, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $sheet->getCell('F'.$deployments_number)->setValueExplicit($days_for_each, \PHPExcel_Cell_DataType::TYPE_NUMERIC);

                                $sheet->getCell('G'.$deployments_number)->setValueExplicit($wages_for_each, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $sheet->getCell('H'.$deployments_number)->setValueExplicit($ot_days_for_each, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $sheet->getCell('I'.$deployments_number)->setValueExplicit($ot_days_wages_for_each, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $sheet->getCell('J'.$deployments_number)->setValueExplicit($ex_hours, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $sheet->getCell('K'.$deployments_number)->setValueExplicit($ex_hours_wages, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
//                                    $sheet->getCell('M'.$deployments_number)->setValueExplicit($special_duty_wages, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
//                                $sheet->getCell('N'.$deployments_number)->setValueExplicit($holidays, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $grand_ap = $grand_ap+$pa;
                                $sheet->getCell('L'.$deployments_number)->setValueExplicit($pa, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                round($holidays_wages,2);
                                    $sheet->getCell('Q'.$deployments_number)->setValueExplicit($foreach_holidays_wages, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $sheet->getCell('R'.$deployments_number)->setValueExplicit($np_foreach, \PHPExcel_Cell_DataType::TYPE_NUMERIC);

                                $deduction = new Guards\GuardClearanceModel();
                                $deduction = $deduction->getByUserIdn($temp_g_id);
                                $cw = 0;
//                                $sbv=0;
                                $sbv=0;
                                $loanp  = $deduction['clearance_loan'];
//                                $cw  = $deduction['cwf_amount'];
                                $sbv  = $deduction['sbv_amount'];
                                $ap  = $deduction['apsa_amount'];


//                                $loanp = new GuardLoansModel();
//                                $loanp = $loanp->getModelByGuardWithInterval($temp_g_id,$temp_g_sd,$temp_g_ed);
                                $sheet->getCell('T'.$deployments_number)->setValueExplicit($loanp, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $grand_loan = $grand_loan + $loanp;


//                                $ap = $this->calculateApsa($temp_g_id,$regional_office);
                                $grand_apsaa = $grand_apsaa + $ap;
//                                    $sbv = $this->calculateSBV($temp_g_id,$regional_office,$days_for_each);
                                    $grand_sbv = $grand_sbv + $sbv;

                                    $cw = 0;

//                                $status = $this->calculateGuardStatus($temp_g_id);
//                                if($status == 1){
//                                    $cwf = new PayrollDefaultsModel();
//                                    $cwf = $cwf->getModelByGuardWithInterval($regional_office);
//                                    $cw = $cwf['cwf_value'];
                                    $grand_cwf = $grand_cwf + $cw;
//
//                                }

                                $sdt= $this->calculateSdTotal($temp_g_id,$regional_office);
//                                dd($sdt);




                                $sum_np = $np_foreach  - $loanp -$ap - $cw -$sbv;
                                $grand_sum = $grand_sum + $sum_np;
//                                  $sum_np = $np_foreach - $loanp  - $cw;
//                                $grand_sum = $grand_sum + $sum_np;
                                $grand_sdw = $grand_sdw + $sdt;
                                $sheet->getCell('O'.$deployments_number)->setValueExplicit($sdt, \PHPExcel_Cell_DataType::TYPE_NUMERIC);

                                $sheet->getCell('U'.$deployments_number)->setValueExplicit($ap, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
//                                    $sheet->getCell('T'.$deployments_number)->setValue($ap);
//                                $sheet->cell('T'.$deployments_number,$ap, function ($cell,$ap) {
//                                    $cell->setValue($ap);
//                                });
                                    $sheet->getCell('V'.$deployments_number)->setValueExplicit($cw, \PHPExcel_Cell_DataType::TYPE_NUMERIC);

                                    $sheet->getCell('X'.$deployments_number)->setValueExplicit($sum_np, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                    $sheet->getCell('S'.$deployments_number)->setValueExplicit($sbv, \PHPExcel_Cell_DataType::TYPE_NUMERIC);

//                                    $sheet->cell('R'.$deployments_number,$sbv, function ($cell,$sbv) {
//                                        $cell->setValue($sbv);
//                                    });
//                                $sheet->getStyle('D'.$deployments_number.':W'.$deployments_number)->getNumberFormat()->setFormatCode('#,##0_);(#,##0)');
                                $sheet->getStyle('E'.$deployments_number.':X'.$deployments_number)->getNumberFormat()->setFormatCode('_(* #,##0_);_(* (#,###);_(* "-"??_);_(@_)');

//                                    $sheet->cells('A'.$deployments_number.':AO'.$deployments_number, function ($cells) {
//
//                                        $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
//                                        $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
//
//                                    });


                                    $deployments_number ++;
                                    $rate = 0;
                                    $days_for_each = 0;
                                    $wages_for_each = 0;
                                    $ot_days_for_each = 0;
                                    $ot_days_wages_for_each = 0;
                                    $ex_hours = 0;
                                    $ex_hours_wages = 0;
                                    $special_duty_wages = 0;
                                    $holidays = 0;
                                    $holidays_wages = 0;
                                    $np_foreach = 0;
                                    $apsa_foreach = 0;
                                    $net_foreach = 0;
                                    $pa =0;
                                    $loanp =0;
                                $foreach_holidays_wages = 0;


                                    $ot_total_for_each = 0;

                                    $np_bf_total_for_each = 0;

                                    $np_af_total_for_each = 0;


//                                }

                            }

                            $sheet->row(1, array(
                                'PARWEST PACIFIC SECURITY ( PVT ) LTD.'

                            ));
                            $sheet->row(2, array(
                                '176-CAVALARY GROUND, LAHORE CANTT.'

                            ));
                            $sheet->row(3, array(
                                'Clearance for the month of   '.$monthName."  ".$salary_year

                            ));
//                            $sheet->row(4, array(
//                                'Region :     '.$reg."  "
//
//                            ));
                            $sheet->getStyle('A5:AA5')->getAlignment()->setWrapText(true);
////                            $sheet->getColumnDimension('A')->setWidth(0);
                            $sheet->getStyle('A5:AA5')->getAlignment()->applyFromArray(
                                    array(
                                         'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                        'vertical'   => \PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                        'rotation'   => 0,
                                        'wrap'       => TRUE
                                    )
                              );
//                            $sheet->setAllBorders('thin');
//                            $sheet->getDefaultStyle('A5:AA5')->getAlignment()
//
//                            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
//                            ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);


//                            $sheet->getStyle('A5:AA5')->applyFromArray([
//                                'alignment' => array(
//                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
//                                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
//                                    'rotation'   => 0,
//                                    'wrap'       => true
//                                )]);
//                            $sheet->getStyle('A5:AA5')->getColumnDimension('G')->setWidth(13);
//                            $sheet->setWidth('G', 12);
//                            $sheet->getColumnDimension('G')->setWidth(12);
                            $rules = new Guards\PayrollSalaryRuleModel();
                            $rules = $rules->getAll();

                            $number = count($rules);
//                            dd($number);

                            for($i = 0; $i < $number; $i++){

                                    $value = $rules[$i]->name;
                                    $code = $rules[$i]->code;
//
                                if($code == 'MGI' || $code == 'SPI'){
                                    //notjomog

                                }else{
                                    if(isset($value)){

                                        $sheet->cell($colString[$i].'5',$value, function ($cell,$value) {
                                            $cell->setValue($value);
                                        });
                                    }
                                }


                            }


                            $temp_g_id = $loan['parwest_id'];
                            $temp_name  = $loan['guard_Name'];
                            $temp_supervisor = $loan['guard_supervisor_name'];
                            $temp_manager = $loan['guard_manager_name'];
                            //                                        $sheet->getCell('B'.$deployments_number)->setValueExplicit($loan['guard_Name']);
//                                        $sheet->getCell('Y'.$deployments_number)->setValueExplicit($loan['guard_manager_name']);
//                                        $sheet->getCell('AA'.$deployments_number)->setValueExplicit($loan['guard_supervisor_name']);


                            $days_p = $loan['days'];
                            $temp_g_sd = $loan['startDay'];
                            $temp_g_ed = $loan['endDay'];

                            if($loan['is_overtime'] == 0){
                                $rate =  $rate + $loan['location_rate'];
                                $days_for_each =  $days_for_each + $loan['days'];
                                $wages_for_each =  $wages_for_each + ($loan['location_rate'] / $total_days)* $loan['days'];
                                $wages_for_each = (int)$wages_for_each;
                            }


                            $ot_total_for_each =  $ot_total_for_each + $loan['is_overtime'] * $loan['overtime_cost'];




                            $days = $loan['days'];
                            $wages = ($loan['location_rate'] / 31)* $loan['days'];


                            $eh = $loan['is_extra'];
                            $eh_wages = $loan['extrahours_cost'];

                            $count = count($rules);

                            $rule_values  = $this->getRulesValuesByDeploymentIdClearance($loan['deployment_id'],$history_id);
//                            dd($rule_values);
                            for($i = 0; $i < $number; $i++){
                                $value = 0;
                                if(isset($count)){

                                    $value = $rule_values[$i]->salary_rule_value;
                                    $code = $rule_values[$i]->code;

                                    if($rule_values[$i]->code == 'RAT'){
                                        $grand_total_for_rate = $grand_total_for_rate + (int)$value;

                                    }
                                    if($rule_values[$i]->code == 'DAY'){
                                        $grand_total_for_days = $grand_total_for_days + (int)$value;

                                    }
                                    if($rule_values[$i]->code == 'WGS'){
                                        $np_af_total_for_each = $np_af_total_for_each + (int)$value;
                                        $grand_total_for_wages = $grand_total_for_wages + (int)$value;

                                    }
                                    if($rule_values[$i]->code == 'OTD'){
                                        $ot_days_for_each = $ot_days_for_each + (int)$value;
                                        $grand_ot_days_for_each = $grand_ot_days_for_each + (int)$value;

//

                                    }if($rule_values[$i]->code == 'OTW'){
                                        $ot_days_wages_for_each = $ot_days_wages_for_each + (int)$value;
                                        $grand_ot_days_wages_for_each = $grand_ot_days_wages_for_each + (int)$value;
//

                                    }if($rule_values[$i]->code == 'EHC'){
                                        $ex_hours = $ex_hours + (int)$value;
                                        $grand_ex_hours = $grand_ex_hours + (int)$value;
//

                                    }if($rule_values[$i]->code == 'EHW'){
                                        $ex_hours_wages = $ex_hours_wages + (int)$value;
                                        $grand_ex_hours_wages = $grand_ex_hours_wages + (int)$value;
//

                                    }if($rule_values[$i]->code == 'SPD'){
                                        $special_duty_wages = $special_duty_wages + (int)$value;
                                        $grand_special_duty_wages = $grand_special_duty_wages + (int)$value;
//

                                    }if($rule_values[$i]->code == 'HOD'){
                                        $holidays = $holidays + (int)$value;
                                        $grand_holidays = $grand_holidays + (int)$value;
//

                                    }if($rule_values[$i]->code == 'HOW'){


                                        $holidays_wages = $holidays_wages + (int)$value;
                                        $grand_holidays_wages = $grand_holidays_wages + (int)$value;
                                        $foreach_holidays_wages = $foreach_holidays_wages + (int)$value;

//

                                    }if($rule_values[$i]->code == 'GRS'){
                                        $np_foreach = $np_foreach + (int)$value;
                                        $np_foreach = (int)$np_foreach;
                                        $grand_np_foreach = $grand_np_foreach + (int)$value;
//

                                    }
                                    if($rule_values[$i]->code == 'APS'){
                                        $apsa_foreach = $apsa_foreach + (int)$value;
                                        $grand_apsa_foreach = $grand_apsa_foreach + (int)$value;
//

                                    }if($rule_values[$i]->code == 'NPY'){
                                        $net_foreach = $net_foreach + (int)$value;
                                        $grand_net_foreach = $grand_net_foreach + (int)$value;
//

                                    }
                                    if($code == 'POA'){
                                        //notjomog
                                        $pa = $pa + (int)$value;

                                    }
//                                    $sheet->getCell($colString[$i].$deployments_number)->setValueExplicit($value, \PHPExcel_Cell_DataType::TYPE_NUMERIC);

//
                                    if($code == 'MGI' || $code == 'SPI'){
                                        //notjomog

                                    }
//                                    elseif($code == 'RAT' && $loan['is_overtime'] == 1){
////                                        dd($value);
//
//                                    }
                                    else{
                                        $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
//                                        0
//                                        if (is_numeric($value)) {


//                                            $value = (int)$value;
//                                            if(ctype_digit($value)){
//                                                $value =  number_format($value);

//                                                $sheet->getCell($colString[$i].$deployments_number)->setValueExplicit($value, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                                $sheet->getCell($colString[$i].$deployments_number)->setValue($value);
//                                                $cell->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

//                                            }



//                                            else{
////                                                $value =  number_format($value);
//                                                $sheet->cell($colString[$i].$deployments_number,$value,$thinBorder, function ($cell,$value,$thinBorder) {
////                                                    $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);
//                                                    $cell->setValue($value);
//                                                    $cell->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
//
//                                                });
//                                            }

//                                        } else {
////                                            $value =  number_format($value);
//                                            $sheet->cell($colString[$i].$deployments_number,$value,$thinBorder, function ($cell,$value,$thinBorder) {
////                                                    $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);
////                                                $cell->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
//                                                $cell->setValue($value);
//                                            });
////                                                                               $cell->setValue($value);
//                                        }


                                    }

                                }
                            }

//                            $grand_total_for_days = $grand_total_for_days + $days;
//                            $grand_total_for_wages = $grand_total_for_wages + $wages;
//                            $grand_total_for_wages = (int)$grand_total_for_wages;
//
//                            $sheet->getStyle('D'.$deployments_number.':W'.$deployments_number)->getNumberFormat()->setFormatCode('#,##0_);(#,##0)');
                            $sheet->getStyle('E'.$deployments_number.':X'.$deployments_number)->getNumberFormat()->setFormatCode('_(* #,##0_);_(* (#,###);_(* "-"??_);_(@_)');

                            $deployments_number = $deployments_number+1;

                            $sheet->cells('A5:AA5', function ($cells) {
                                $cells->setFont(array(
                                    'bold' => true
                                ));

                                $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

                            });
//                            $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {
//
//
//                                $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
//                                $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
//
//                            });

                            $sheet->setStyle(array(
                                'font' => array(
                                    'name' => 'Calibri',
                                ),
                                'setSize' => array(
                                    'height' => '35',
                                )
                            ));
//
                            $sheet->mergeCells('A1:R1');
                            $sheet->getStyle('A1:R1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);



                            $sheet->cells('A1:R1', function ($cells) {

                                $cells->setBackground('#ffffff');
                                $cells->setFontColor('#000000');
                                $cells->setFont(array(
                                    'size' => '20',
                                    'bold' => true
                                ));

                            });
                            $sheet->cells('A3:Z3', function ($cells) {

                                $cells->setBackground('#ffffff');
                                $cells->setFontColor('#000000');
                                $cells->setFont(array(
                                    'size' => '12',
                                    'bold' => true,

                                ));

                            });
                            $sheet->cells('A5:Z5', function ($cells) {

                                $cells->setBackground('#ffffff');
                                $cells->setFontColor('#000000');
                                $cells->setFont(array(
                                    'size' => '12',
                                    'bold' => true,

                                ));

                            });

                            $sheet->cells('A2:A4', function ($cells) {
                                $cells->setFont(array(
                                    'bold' => true
                                ));

                            });
//
//                            $sheet->getStyle()->getNumberFormat()->setFormatCode('#,##0_);(#,##0)');
                            $sheet->getStyle()->getNumberFormat()->setFormatCode('_(* #,##0_);_(* (#,###);_(* "-"??_);_(@_)');

                            $sheet->mergeCells('A1:R1');
                            $sheet->mergeCells('A2:R2');
                            $sheet->mergeCells('A3:R3');
//                            $sheet->setWidth('A', 12);
                            $sheet->setWidth(array(
                                'A'     =>  13,
                                'B'     =>  30,
                                'C'     =>  15,
                                'D'     =>  15,
                                'E'     =>  15,
                                'F'     =>  15,
                                'G'     =>  15,
                                'H'     =>  15,
                                'I'     =>  15,
                                'J'     =>  15,
                                'K'     =>  15,
                                'L'     =>  15,
                                'M'     =>  15,
                                'N'     =>  15,
                                'O'     =>  15,
                                'P'     =>  15,
                                'Q'     =>  15,
                                'R'     =>  15,
                                'S'     =>  15,
                                'T'     =>  15,
                                'U'     =>  15,
                                'V'     =>  15,
                                'W'     =>  15,
                                'X'     =>  25,
                                'Y'     =>  25,
                                'Z'     =>  25,
                                'AA'     =>  25
                            ));
//                            $sd = $this->calculateSd($temp_g_id,$regional_office);
//                            if($sd){
//                                foreach ($sd as $special_duty){
//
////                                    $deployments_number ++;
////                                        dd($special_duty);
//                                    $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {
//
//
//
//                                        $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
//                                        $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
//
//                                    });
//                                    $sheet->getStyle('D'.$deployments_number.':W'.$deployments_number)->getNumberFormat()->setFormatCode('_(* #,##0_);_(* (#,###);_(* "-"??_);_(@_)');
//
////                                    dd($loan);
//                                    $sheet->getCell('A'.$deployments_number)->setValueExplicit($temp_g_id);
//                                    $sheet->getCell('B'.$deployments_number)->setValueExplicit($loan['guard_Name']);
//                                    $sheet->getCell('Y'.$deployments_number)->setValueExplicit($loan['guard_manager_name']);
//                                    $sheet->getCell('AA'.$deployments_number)->setValueExplicit($loan['guard_supervisor_name']);
//
//                                    $sheet->getCell('L'.$deployments_number)->setValueExplicit($special_duty['special_duty_hours'], \PHPExcel_Cell_DataType::TYPE_NUMERIC);
//                                    $sheet->getCell('M'.$deployments_number)->setValueExplicit($special_duty['special_duty_hour_rate'], \PHPExcel_Cell_DataType::TYPE_NUMERIC);
//                                    $sheet->getCell('N'.$deployments_number)->setValueExplicit(($special_duty['special_duty_hours'] * $special_duty['special_duty_hour_rate']), \PHPExcel_Cell_DataType::TYPE_NUMERIC);
//                                    $sheet->getCell('X'.$deployments_number)->setValueExplicit($special_duty['special_duty_remarks'] );
//
////                                    });
//                                    $deployments_number ++;
//                                }
//                            }



//                            $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {
//                                $cells->setBackground('#E8E8E8');
//                                $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
//                                $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
//
//
//                            });

                        }
                        $sd = $this->calculateSd($temp_g_id,$regional_office);
                        if($sd){
                            foreach ($sd as $special_duty){

//                                    $deployments_number ++;
//                                        dd($special_duty);
                                $sheet->cells('A'.$deployments_number.':AB'.$deployments_number, function ($cells) {



                                    $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                    $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

                                });
                                $sheet->getStyle('E'.$deployments_number.':X'.$deployments_number)->getNumberFormat()->setFormatCode('_(* #,##0_);_(* (#,###);_(* "-"??_);_(@_)');

//                                    dd($loan);
                                $sheet->getCell('A'.$deployments_number)->setValueExplicit($temp_g_id);
                                $sheet->getCell('B'.$deployments_number)->setValueExplicit($temp_name);
                                $sheet->getCell('Z'.$deployments_number)->setValueExplicit($temp_manager);
                                $sheet->getCell('AA'.$deployments_number)->setValueExplicit($temp_supervisor);

                                $sheet->getCell('M'.$deployments_number)->setValueExplicit($special_duty['special_duty_hours'], \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $sheet->getCell('N'.$deployments_number)->setValueExplicit($special_duty['special_duty_hour_rate'], \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $sheet->getCell('O'.$deployments_number)->setValueExplicit(($special_duty['special_duty_hours'] * $special_duty['special_duty_hour_rate']), \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $sheet->getCell('Y'.$deployments_number)->setValueExplicit($special_duty['special_duty_remarks'] );

//                                    });
                                $sd_wagess= 0;
                                $sd_wagess = $special_duty['special_duty_hours'] * $special_duty['special_duty_hour_rate'];
                                $np_foreach = $np_foreach + ($special_duty['special_duty_hours'] * $special_duty['special_duty_hour_rate']);
                                $sheet->getCell('R'.$deployments_number)->setValueExplicit($sd_wagess, \PHPExcel_Cell_DataType::TYPE_NUMERIC );
                                $deployments_number ++;
                            }
                        }
                        $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {
                            $cells->setBackground('#E8E8E8');
                            $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                            $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);


                        });

                        $sheet->cell('A'.$deployments_number, function ($cell) {
                            $cell->setValue('Sub Total');
                        });
                        $sheet->cell('B'.$deployments_number,$temp_g_id, function ($cell,$temp_g_id) {
                            $cell->setValue($temp_g_id);
                        });
//                                dd($temp_g_id);
//                            $sheet->getCell('D'.$deployments_number)->setValueExplicit($rate, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('F'.$deployments_number)->setValueExplicit($days_for_each, \PHPExcel_Cell_DataType::TYPE_NUMERIC);

                        $sheet->getCell('G'.$deployments_number)->setValueExplicit($wages_for_each, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('H'.$deployments_number)->setValueExplicit($ot_days_for_each, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('I'.$deployments_number)->setValueExplicit($ot_days_wages_for_each, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('J'.$deployments_number)->setValueExplicit($ex_hours, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('K'.$deployments_number)->setValueExplicit($ex_hours_wages, \PHPExcel_Cell_DataType::TYPE_NUMERIC);





                        $sheet->getCell('P'.$deployments_number)->setValueExplicit($holidays, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        round($holidays_wages,2);
                        $sheet->getCell('Q'.$deployments_number)->setValueExplicit($holidays_wages, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sdt= $this->calculateSdTotal($temp_g_id,$regional_office);
//                        $np_foreach = $np_foreach + $sdt ;
                            $sheet->getCell('O'.$deployments_number)->setValueExplicit($sdt, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('R'.$deployments_number)->setValueExplicit($np_foreach, \PHPExcel_Cell_DataType::TYPE_NUMERIC);

                        $ap = $this->calculateApsa($temp_g_id,$regional_office);
                        $sbv = $this->calculateSBV($temp_g_id,$regional_office,$days_for_each);
//                            if($temp_g_id == 'L-10358'){
//                                dd($sbv);
//                            }
//                             $sdt= $this->calculateSdTotal($temp_g_id,$regional_office);

//                             dd($sdt);

                        $cw = 0;
                        $status = $this->calculateGuardStatus($temp_g_id);
                        if($status == 1){
                            $cwf = new PayrollDefaultsModel();
                            $cwf = $cwf->getModelByGuardWithInterval($regional_office);
                            $cw = $cwf['cwf_value'];
                        }
//                            $grand_loan = $grand_loan + $loanp;
//                            $grand_sbv = $grand_sbv + $sbv;
//                            $grand_apsaa = $grand_apsaa + $ap ;
//                            $grand_cwf = $grand_cwf + $cw;
                        $loanp = new GuardLoansModel();
                        $loanp = $loanp->getModelByGuardWithInterval($temp_g_id,$temp_g_sd,$temp_g_ed);

                        $sum_np = $np_foreach   - $loanp -$ap - $cw -$sbv;
//                            $grand_sum = $np_foreach+$sum_np;
//                            $grand_sdw = $grand_sdw + $sdt;
//                        $sheet->getCell('N'.$deployments_number)->setValueExplicit($sdt, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('U'.$deployments_number)->setValueExplicit($ap, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('V'.$deployments_number)->setValueExplicit($cw, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('S'.$deployments_number)->setValueExplicit($sbv, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('X'.$deployments_number)->setValueExplicit($sum_np, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getStyle('E'.$deployments_number.':X'.$deployments_number)->getNumberFormat()->setFormatCode('_(* #,##0_);_(* (#,###);_(* "-"??_);_(@_)');
                        $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {



                            $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                            $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

                        });

                        $deployments_number = $deployments_number +2;
                        $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {
                            $cells->setBackground('#E8E8E8');
                            $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                            $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);


                        });
                        $sheet->cell('A'.$deployments_number, function ($cell) {
                            $cell->setValue('Grand Total');
                        });

                        $grand_loan = $grand_loan + $loanp;
//                        dd($grand_loan);

                            $grand_sbv = $grand_sbv + $sbv;
                            $grand_apsaa = $grand_apsaa + $ap ;
                            $grand_cwf = $grand_cwf + $cw;
                        $grand_sdw = $grand_sdw + $sdt;
                        $grand_np_foreach = $grand_np_foreach +$grand_sdw;
                        $grand_sum =$grand_sum + $sum_np;
//                        $sheet->getStyle('D'.$deployments_number)->getNumberFormat()->setFormatCode('#,##0_);(#,##0)');
                        $sheet->getStyle('E'.$deployments_number)->getNumberFormat()->setFormatCode('_(* #,##0_);_(* (#,###);_(* "-"??_);_(@_)');
//                        $sheet->getCell('D'.$deployments_number)->setValueExplicit($grand_total_for_rate, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('F'.$deployments_number)->setValueExplicit($grand_total_for_days, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('G'.$deployments_number)->setValueExplicit($grand_total_for_wages, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('H'.$deployments_number)->setValueExplicit($grand_ot_days_for_each, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('I'.$deployments_number)->setValueExplicit($grand_ot_days_wages_for_each, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('J'.$deployments_number)->setValueExplicit($grand_ex_hours, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('K'.$deployments_number)->setValueExplicit($grand_ex_hours_wages, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('L'.$deployments_number)->setValueExplicit($grand_ap, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('O'.$deployments_number)->setValueExplicit($grand_sdw, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('P'.$deployments_number)->setValueExplicit($grand_holidays, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('Q'.$deployments_number)->setValueExplicit($grand_holidays_wages, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('R'.$deployments_number)->setValueExplicit($grand_np_foreach, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('T'.$deployments_number)->setValueExplicit($grand_loan, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('S'.$deployments_number)->setValueExplicit($grand_sbv, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('U'.$deployments_number)->setValueExplicit($grand_apsaa, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('V'.$deployments_number)->setValueExplicit($grand_cwf, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
//                        $sheet->getCell('O'.$deployments_number)->setValueExplicit($grand_apsa_foreach, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getCell('X'.$deployments_number)->setValueExplicit($grand_sum, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $sheet->getStyle('E'.$deployments_number.':X'.$deployments_number)->getNumberFormat()->setFormatCode('#,##0_);(#,##0)');
                        $sheet->setBorder('A5:AB'.$deployments_number, 'thin','thin','thin','thin');
                        $sheet->cells('A'.$deployments_number.':AA'.$deployments_number, function ($cells) {


                            $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                            $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

                        });

                    });
//

                })->store('xlsx', Config::get('globalvariables.$pathToSaveTemporaryExcelFiles'));


                $fileNameToDownload = $fileName . '.xlsx';

                $file_path = Config::get('globalvariables.$pathToSaveTemporaryExcelFiles') . '/' . $fileNameToDownload;
                if (file_exists($file_path)) {
                    // Send Download
                    return Response::download($file_path, $fileNameToDownload, [
                        'Content-Length: ' . filesize($file_path)
                    ])->deleteFileAfterSend(true);
                } else {
                    // Error
                    exit('Requested file does not exist on our server!');
                }


                $data = array('fileNameToDownload' => $fileNameToDownload);

                return ['responseCode' => 1, 'responseStatus' => 'successful', 'message' => 'your file is ready, and will download in few seconds', 'data' => $data];


            }
            else{

                return view('guards.attendance')->with(['noRecordFound' => 'No record Found']);
            }

        }


    }

    public function calculateGuardStatus($id){
        $guard = new Guards\Guards();
        $guard = $guard->getByParwestId($id);
        $status = $guard['current_status_id'];
        if($status == 3 || $status == 13){
            return 0;
        }else{
            return 1;
        }

    }
    public function calculateSBV($id,$regional_office,$guard_days){
        $guard_id = new Guards\Guards();
        $guard_id = $guard_id->getByParwestId($id);
        // $guard_age
        $guard_age = $guard_id['age'];
        $special_branch = new GuardVerificationModel();
//                $special_branch = $special_branch->getAllByGuardId($guardDeployment->guard_id);
        $special_branch = $special_branch->seeIfValidationDoneAgainstGuard($guard_id['id'], 1, 2);

        if ($special_branch) {

            $special_branch_cost = new PayrollDefaultsModel();
            $special_branch_cost = $special_branch_cost->getModelByGuardWithInterval($regional_office);
            $final_cost = $special_branch_cost['verification_cost'];
            $defualt_age = $special_branch_cost['verification_age'];//55
            $defualt_days = $special_branch_cost['verification_days'];//4
            // 	 55  < SBrV Age Limit
            //	4 > SBrV Days
            if($guard_days > $defualt_days){
                if($guard_age < $defualt_age){

                    return $final_cost;
                }else{
                    return 0;
                }
            }else{
                return 0;
            }


        } else {
            return 0;
        }

    }
    public function calculateSd($id,$regional_office){
        $sd = new Guards\PayrollSpecialDuty();
        $sd = $sd->getModelByGuardWithInterval($id);
        if(count($sd) > 0){
            return $sd;
        }else{
            return 0;
        }


    }
    public function calculateSdTotal($id,$regional_office){


        $sp_hours = 0;
        $sp_hour_rate = 0;
        $specialD = 0;
        $specialDuty = new PayrollSpecialDuty();
        $specialDuty = $specialDuty->getModelByGuardWithInterval($id);

        //                if($specialDuty){
        foreach ($specialDuty as $sp){
            $sp_hours =   $sp['special_duty_hours'];
            $sp_hour_rate =  $sp['special_duty_hour_rate'];
            $specialD = $specialD  + $sp_hours * $sp_hour_rate;
        }
//            dd($specialD);
        if($specialD > 0){

            return $specialD;
        }else{
            return 0 ;
        }

//        $sd = new Guards\PayrollSpecialDuty();
//        $sd = $sd->getModelByGuardWithInterval($id);
//        if(count($sd) > 0){
//            return $sd;
////            $total_sd = $sd[]
//        }else{
//            return 0;
//        }


    }
    public function calculateApsa($id,$regional_office){

        $guard = new Guards\Guards();
        $guard = $guard->getByParwestId($id);
//        dd($guard['created_at']);
        $ap = 0;

        $old_date_timestamp = strtotime($guard['created_at']);
        $new_date = date('Y-m-d', $old_date_timestamp);
        $new = new Guards\PayrollSalaryMonth();
        $new = $new->getAll();
        $now = $new['date_to'];

        $now = strtotime($now);

        $your_date = strtotime($new_date);
        $datediff = $now - $your_date;

        $dff =  round($datediff / (60 * 60 * 24));
//dd($dff);
        if($dff > 365){
            $ap = 0;
        }
        else{
            $ap = 0;
            $apsaa = new PayrollDefaultsModel();
            $apsaa = $apsaa->getModelByGuardWithInterval($regional_office);


            $ap =  $apsaa['apsa_month_value'];

        }
//        $ap = $loan['parwest_id'];
        return $ap;
    }
    public function getSalaryFinalPost(Request $request){
        $input = $request->all();
//        dd($input);
//        $colString = PHPExcel_Cell::stringFromColumnIndex(1);
//        dd($colString);

//        dd($input['tags_id']);postSalary
        $monthData = new PayrollSalaryMonth();
        $monthData = $monthData->getAll();
//        dd($monthData);
        $salary_month = $monthData['salary_month'];
        $salary_year = $monthData['salary_year'];

        $final_salary_post = new Guards\GuardSalaryPostedModel();
        $final_salary_post = $final_salary_post->postSalary($input['tags_id'],$input['region_id'],$salary_month,$salary_year);

        $history_indicator = new Guards\PayrollPostIndicatorModel();
        $history_indicator = $history_indicator->saveModel($input['tags_id'],$input['region_id'],$salary_month,$salary_year);

        $history_disable = new Guards\GuardSalaryHistoryStatModel();
        $history_disable = $history_disable->getModelByRegion($input['region_id']);
//        dd($history_disable);


        if($history_disable){
            return redirect()->back()->with(['success  ' => 'Salary Genrated ']);

        }
//        $get_finalised_history_by_loan = new Guards\GuardSalaryModel();
//        $get_finalised_history_by_loan = $get_finalised_history_by_loan->getModelByValue($input['tags_id']);
//        dd($get_finalised_history_by_loan);

//        foreach ($get_finalised_history_by_loan as $postedSalary){
//            $salary = new Guards\PayrollSalaryModel();
//            $salary = $salary->saveModel($postedSalary);
//        }


    }

    public function getFinalisedStatus(Request $request){
        $region = $request->region;
//        $month = $request->month;
//        $users_ids = new CustomUserPermissions();
//        $users_ids = $users_ids->havePermission('17','27');
        $new_month = new PayrollSalaryMonth();
        $new_month = $new_month->getAll();
        $salary_month =  $new_month['salary_month'];
        $salary_year =  $new_month['salary_year'];
//        $month=date_create($month);
//        $month =  date_format($month,"Y-m-d");
        $indicator = new Guards\PayrollPostIndicatorModel();
        $indicator = $indicator->getModelByValue($region,$salary_month,$salary_year);
        $customUser = new CustomUserPermissions();
        $customUser = $customUser->getModelByC();
        if($indicator){
            $post_status = true;
            $genrate_status = false;
//            $customUser = 0;

        }else{

            if($customUser){
                $genrate_status = false;
            }else{
                $genrate_status = true;
                $post_status = true;

            }
            $post_status = false;
        }

//        $users = new UserModel();
//        $users = $users->getunfinalizedUsers($salary_month,$salary_year,$region);

//        $customUser = $customUser>havePermission(21,30);
//        $customUser = $customUser->havePermission(21,30);
//        $customUser = $customUser->getModelBy();
//        $customUser =  (array)$customUser;

        $array = array();
        $start = 0;
//        foreach($customUser as $user){
//            $us = $user[$start];
////            return $user[0]->id;
//            $in_ont = new Guards\UsersFinalizeLoan();
//            $in_ont = $in_ont->getByUserIdn($us->user_id);
//            if($in_ont){
//
////                $array[$start] = $us->username;
//            }else{
//                $array[$start] = $us->username;
//
//            }
//            $start++;
//        }




//        $isPermissionOnSubmodule = new CustomPermissionsOnSubModules();
//        $respone = $isPermissionOnSubmodule->isPermissionOnSubModuleExists(21,30);
//        if($users){
//            $genrate_status = false;
//        }else{
//            $genrate_status = true;
//
//        }
//        $finalUsers = new UserModel();
//        $finalUsers = $finalUsers->getFinalizedUsers($salary_month,$salary_year,$region);
//        $finalUsers = (array)$finalUsers;
//        $result=array_diff($customUser,$finalUsers);


        $data = [
//            'users' => $users,
//            'finalUsers' => $finalUsers,
            'indicator' => $indicator,
            'post_status' => $post_status,
            'genrate_status' => $genrate_status,
            'customUser' => $customUser,
//            'respone' => $respone,
            'arrays' => $array,
//            'differ' => $result,
        ];

        return $data;
//        if($users_ids == false){
//
//
//            $users = UserModel::all()->where('regional_office_id',$region);
//            return $users;
//        }else{
//            return 'dddddd';
//        }
    }
    public function getFinalisedStatusRegion(Request $request){
        $region = 1;
//        $month = $request->month;
//        $users_ids = new CustomUserPermissions();
//        $users_ids = $users_ids->havePermission('17','27');
        $new_month = new PayrollSalaryMonth();
        $new_month = $new_month->getAll();
        $salary_month =  $new_month['salary_month'];
        $salary_year =  $new_month['salary_year'];
//        $month=date_create($month);
//        $month =  date_format($month,"Y-m-d");
        $indicator = new Guards\PayrollPostIndicatorModel();
        $indicator = $indicator->getModelByValueRegion($region,$salary_month,$salary_year);
        if($indicator){
            $post_status = true;
        }else{
            $post_status = false;
        }

//        $users = new UserModel();
//        $users = $users->getunfinalizedUsers($salary_month,$salary_year,$region);
//        if($users){
//            $genrate_status = false;
//        }else{
//            $genrate_status = true;
//
//        }
//        $finalUsers = new UserModel();
//        $finalUsers = $finalUsers->getFinalizedUsers($salary_month,$salary_year,$region);
        $data = [
//            'users' => $users,
//            'finalUsers' => $finalUsers,
            'indicator' => $indicator,
            'post_status' => $post_status,
//            'genrate_status' => $genrate_status,
        ];

        return $data;
//        if($users_ids == false){
//
//
//            $users = UserModel::all()->where('regional_office_id',$region);
//            return $users;
//        }else{
//            return 'dddddd';
//        }
    }
    public function getSalaryExport(Request $request){
        $input = $request->all();

//        $startDate = getStartDateByMonthDate($input['salary_month']);
//        $endDate = getEndDateByMonthDate($input['salary_month']);
        $regionId = $input['region'];
        $managerId = $input['manager'];
        $supervisor = $input['supervisor'];
        $getSalaryData = new Guards\GuardSalaryModel();
        $getSalaryData = $getSalaryData->getModelBySalaryExport($regionId,$managerId,$supervisor);
//        dd($getSalaryData);

        $user_id =   Auth::guard('user')->id();
//        $user_name =   Auth::guard('user')->name;
        $user = new UserModel();
        $user = $user->getUserById($user_id);
//        dd($user['name']);

//        $clientGuardAcciciationModel = new ClientGuardsAssociation();
//        $guardDeployments = $clientGuardAcciciationModel->deployedGuardByExportFiltersSalary($regionId,$managerId,$supervisor, $startDate, $endDate);
//dd($guardDeployments);
//        $loans = new Guards\GuardLoansModel();
//        $loans = $loans->getModelByUserId($user_id);
//        $notFinalisedloans = new Guards\GuardLoansModel();
//        $notFinalisedloans = $notFinalisedloans->getModelByUserIdAndStatus($user_id);
//        $notFinalisedloans = $notFinalisedloans->getModelByParwestIdWithLocation($user_id);
//        dd($notFinalisedloans);
//        $loans = $notFinalisedloans;
//        $loan['manager'] = $user['name'];
        $loans = $getSalaryData;
//dd($loans);
        if(count($loans) == 0){

        }
        else{


            if(count($loans) > 0){


                if($request->salary_month){
                    $fileName = 'salary'.$request->salary_month;
                }else{

                    $fileName = 'salary';
                }
                $sheetsArray = array();


                Excel::create(/**
                 * @param $excel
                 */
                    $fileName, function ($excel) use ($loans,$user) {
                    //dd($searchResults);


                    $excel->setTitle('Guards Attandance');
                    $excel->setDescription('Guards Attandance');

                    //center align
                    $excel->getDefaultStyle()
                        ->getAlignment()
                        ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                        ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

                        $currentSheetName = 'Operation Salary';

                    $excel->sheet($currentSheetName, function ($sheet) use ($loans,$user) {
                        $deployments_number = 6;
                        $startsFromDeploymentNumber = 6;
                            $number_of_rows_with_total = 0 ;
                            $temp_g_id = '';
                        $temp_g_sd = '';
                            $temp_g_ed = '';
                            $total_loan = 0;
                            $rate= 0;
                            $days_for_each = 0;
                            $wages_for_each = 0;
                            $ot_total_for_each = 0;
                            $eh_total_for_each = 0;
                            $np_bf_total_for_each = 0;
                            $sbd_total_for_each = 100;
                            $ops_loan_total_for_each = 100;
                            $apsaa_loan_total_for_each = 100;
                            $cwf_loan_total_for_each = 100;
                            $np_af_total_for_each = 0;
                        $grand_total_af_for_each = 0;
                        $grand_total_bf_for_each = 0;
                        $grand_total_for_days = 0;
                        $grand_total_for_wages = 0;

                            foreach ($loans as $loan) {
//                                dd($loan['parwest_id']);

                                if($temp_g_id && $temp_g_id!= $loan['parwest_id']){
                                    $sheet->cell('A'.$deployments_number, function ($cell) {
                                        $cell->setValue('Sub Total');
                                    });
                                    $sheet->cell('D'.$deployments_number,$rate, function ($cell,$rate) {
                                        $cell->setValue($rate);
                                    });
                                    $sheet->cell('E'.$deployments_number,$days_for_each, function ($cell,$days_for_each) {
                                        $cell->setValue($days_for_each);
                                    });
                                    $sheet->cell('F'.$deployments_number,$wages_for_each, function ($cell,$wages_for_each) {
                                        $cell->setValue($wages_for_each);
                                    });
                                    $sheet->cell('I'.$deployments_number,$ot_total_for_each, function ($cell,$ot_total_for_each) {
                                        $cell->setValue($ot_total_for_each);
                                    });
//                                    $sheet->cell('L'.$deployments_number,$eh_total_for_each, function ($cell,$eh_total_for_each) {
//                                        $cell->setValue($eh_total_for_each);
//                                    });



                                    $sheet->cell('Q'.$deployments_number,$sbd_total_for_each, function ($cell,$sbd_total_for_each) {
                                        $cell->setValue($sbd_total_for_each);
                                    });

                                    $sheet->cell('R'.$deployments_number,$ops_loan_total_for_each, function ($cell,$ops_loan_total_for_each) {
                                        $cell->setValue($ops_loan_total_for_each);
                                    });
                                    $sheet->cell('S'.$deployments_number,$apsaa_loan_total_for_each, function ($cell,$apsaa_loan_total_for_each) {
                                        $cell->setValue($apsaa_loan_total_for_each);
                                    });
                                    $sheet->cell('T'.$deployments_number,$cwf_loan_total_for_each, function ($cell,$cwf_loan_total_for_each) {
                                        $cell->setValue($cwf_loan_total_for_each);
                                    });
                                    $sheet->cell('P'.$deployments_number,$np_bf_total_for_each, function ($cell,$np_bf_total_for_each) {
                                        $cell->setValue($np_bf_total_for_each);
                                    });

                                    $sheet->cell('U'.$deployments_number,$np_af_total_for_each, function ($cell,$np_af_total_for_each) {
                                        $cell->setValue($np_af_total_for_each);
                                    });



//                                    $grand_total_for_each = $grand_total_for_each + $np_af_total_for_each;
//                                    dd($np_af_total_for_each);
                                    $deployments_number ++;
                                    $rate = 0;
                                    $days_for_each = 0;
                                    $wages_for_each = 0;
                                    $ot_total_for_each = 0;
                                    $eh_total_for_each = 0;
                                    $np_bf_total_for_each = 0;
                                    $sbd_total_for_each = 100;
                                    $ops_loan_total_for_each = 100;
                                    $apsaa_loan_total_for_each = 100;
                                    $cwf_loan_total_for_each = 100;
                                    $np_af_total_for_each = 0;

                                }

                                $sheet->row(5, array(
                                    'PPS No.','Name', 'Desig', 'Rate', 'Days',
                                    'Wages','OT','OT Wages','OT Total','E.H','EH Wages','EH Total','Eid Days','Eidi Wages','Eidi Total','Net Pay'
                                ,'S.BR Verfication' ,'Ops Loan','APSAA','CWF','Net Payable','post','supervisor','Manager','Status ','Signature'
                                ));
                                $parwest_id = $loan['parwest_id'];
                                $temp_g_id = $loan['parwest_id'];

                                $rate =  $rate + $loan['location_rate'];
                                $days_for_each =  $days_for_each + $loan['days'];
                                $wages_for_each =  $wages_for_each + ($loan['location_rate'] / 30)* $loan['days'];
                                $ot_total_for_each =  $ot_total_for_each + $loan['is_overtime'] * $loan['overtime_cost'];
//                                $ot_total_for_each =  $ot_total_for_each + $loan['is_overtime'] * $loan['overtime_cost'];
//                                $ops_loan_total_for_each =  $ops_loan_total_for_each + $loan['is_overtime'] * $loan['overtime_cost'];
//                                $apsaa_loan_total_for_each =  $apsaa_loan_total_for_each + $loan['is_overtime'] * $loan['overtime_cost'];
                                $np_af_total_for_each +=  $np_af_total_for_each ;


                                $name = $loan['guard_Name'];
                                $designation = 'guard';
                                $location_rate = $loan['location_rate'];
                                $days = $loan['days'];
                                $wages = ($loan['location_rate'] / 30)* $loan['days'];
//                                $eid_days = $loan['location_rate'];
                                $eid_days = 0;
                                $eid_wages = 0;
//                                $eid_wages = $loan['location_rate'];
                                $eid_total = 0;
                                $ot = $loan['is_overtime'];
                                $ot_wages = $loan['overtime_cost'];
                                $ot_total = $loan['is_overtime'] * $loan['overtime_cost'];
                                $eh = $loan['is_extra'];
                                $eh_wages = $loan['extrahours_cost'];
                                $eh_total = $eh * $eh_wages;
                                $net_pay_before = $wages + $eid_total +$ot_total+$eh_total;
                                $special_branch_deduction = 100;
                                $ops_loan = $loan['location_rate'];
                                $ops_loan = 0;
                                $apsaa = 0;
                                $cwf = 0;
                                $net_pay_after = $net_pay_before - $special_branch_deduction - $ops_loan -$apsaa -$cwf;
                                $post = $loan['client_name']."  ".$loan['branch_name'];
                                $supervisor = $loan['guard_supervisor_name'];
                                $manager = $loan['guard_manager_name'];

//
                            $sheet->cell('A'.$deployments_number,$parwest_id, function ($cell,$parwest_id) {
                                $cell->setValue($parwest_id);
                            });
                            $sheet->cell('B'.$deployments_number,$name, function ($cell,$name) {
                                $cell->setValue($name);
                            });
                            $sheet->cell('C'.$deployments_number,$designation, function ($cell,$designation) {
                                $cell->setValue($designation);
                            });
                            $sheet->cell('D'.$deployments_number,$location_rate, function ($cell,$location_rate) {
//                                $rate  + = $location_rate;
                                $cell->setValue($location_rate);
                            });
                            $sheet->cell('E'.$deployments_number,$days, function ($cell,$days) {
                                $cell->setValue($days);
                            });
                            $sheet->cell('F'.$deployments_number,$wages, function ($cell,$wages) {
                                $cell->setValue($wages);
                            });
                            $sheet->cell('G'.$deployments_number,$ot, function ($cell,$ot) {
                                $cell->setValue($ot);
                            });
                            $sheet->cell('H'.$deployments_number,$ot_wages, function ($cell,$ot_wages) {
                                $cell->setValue($ot_wages);
                            });
                            $sheet->cell('I'.$deployments_number,$ot_total, function ($cell,$ot_total) {
                                $cell->setValue($ot_total);
                            });
                            $sheet->cell('J'.$deployments_number,$eh, function ($cell,$eh_wages) {
                                $cell->setValue($eh_wages);
                            });
                            $sheet->cell('K'.$deployments_number,$eh_wages, function ($cell,$eh_wages) {
                                $cell->setValue($eh_wages);
                            });
                            $sheet->cell('L'.$deployments_number,$eh_total, function ($cell,$eh_total) {
                                $cell->setValue($eh_total);
                            });
                            $sheet->cell('M'.$deployments_number,$eid_days, function ($cell,$eid_days) {
                                $cell->setValue($eid_days);
                            });
                            $sheet->cell('N'.$deployments_number,$eid_wages, function ($cell,$eid_wages) {
                                $cell->setValue($eid_wages);
                            });
                            $sheet->cell('O'.$deployments_number,$eid_total, function ($cell,$eid_total) {
                                $cell->setValue($eid_total);
                            });
//                            dd($net_pay_before);
                                $sheet->cell('P'.$deployments_number,$net_pay_before, function ($cell,$net_pay_before) {
                                    $cell->setValue($net_pay_before);
                                });
                                $sheet->cell('U'.$deployments_number,$net_pay_after, function ($cell,$net_pay_after) {
                                    $cell->setValue($net_pay_after);
                                });
                                $grand_total_bf_for_each = $grand_total_bf_for_each + $net_pay_before;
                                $grand_total_af_for_each = $grand_total_af_for_each + $net_pay_after;
                                $grand_total_for_days = $grand_total_for_days + $days;
                                $grand_total_for_wages = $grand_total_for_wages + $wages;
//                            $sheet->cell('Q'.$deployments_number,$special_branch_deduction, function ($cell,$special_branch_deduction) {
//                                $cell->setValue($special_branch_deduction);
//                            });
//
//                             $sheet->cell('R'.$deployments_number,$ops_loan, function ($cell,$ops_loan) {
//                                    $cell->setValue($ops_loan);
//                             });
//                             $sheet->cell('S'.$deployments_number,$apsaa, function ($cell,$apsaa) {
//                                    $cell->setValue($apsaa);
//                             });
//                             $sheet->cell('T'.$deployments_number,$cwf, function ($cell,$cwf) {
//                                $cell->setValue($cwf);
//                             });
                             $sheet->cell('V'.$deployments_number,$post, function ($cell,$post) {
                                $cell->setValue($post);
                            });
                             $sheet->cell('W'.$deployments_number,$supervisor, function ($cell,$supervisor) {
                                $cell->setValue($supervisor);
                            });
                             $sheet->cell('X'.$deployments_number,$manager, function ($cell,$manager) {
                                $cell->setValue($manager);
                            });


                                $deployments_number = $deployments_number+1;

                            //setting sheet fontname
                            $sheet->setStyle(array(
                                'font' => array(
                                    'name' => 'Calibri',
                                ),
                                'setSize' => array(
                                    'height' => '35',
                                )
                            ));

                            $sheet->mergeCells('A1:R1');
                            $sheet->getStyle('A1:R1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                            $sheet->cells('A1:R1', function ($cells) {

                                $cells->setBackground('#ffffff');
                                $cells->setFontColor('#000000');
                                $cells->setFont(array(
                                    'size' => '20',
                                    'bold' => true
                                ));

                            });

                            $sheet->cells('A2:A4', function ($cells) {
                                $cells->setFont(array(
                                    'bold' => true
                                ));

                            });
                            $sheet->cells('D2:D7', function ($cells) {
                                $cells->setFont(array(
                                    'bold' => true
                                ));

                            });

                            $sheet->mergeCells('A1:R1');
                            $sheet->mergeCells('A2:R2');
                            $sheet->mergeCells('A3:R3');
                            $sheet->row(1, array(
                                'PARWEST PACIFIC SECURITY ( PVT ) LTD.'

                            ));
                            $sheet->row(2, array(
                                '176-CAVALARY GROUND, LAHORE CANTT.'

                            ));
                            $sheet->row(3, array(
                                'Salary'  .$manager

                            ));
                            }
                        $deployments_number = $deployments_number +2;
                        $sheet->cell('A'.$deployments_number, function ($cell) {
                            $cell->setValue('Grand Total');
                        });
                        $sheet->cell('P'.$deployments_number,$grand_total_bf_for_each, function ($cell,$grand_total_bf_for_each) {
                            $cell->setValue($grand_total_bf_for_each);
                        });
                         $sheet->cell('U'.$deployments_number,$grand_total_af_for_each, function ($cell,$grand_total_af_for_each) {
                            $cell->setValue($grand_total_af_for_each);
                        });  $sheet->cell('E'.$deployments_number,$grand_total_for_days, function ($cell,$grand_total_for_days) {
                            $cell->setValue($grand_total_for_days);
                        });
                         $sheet->cell('F'.$deployments_number,$grand_total_for_wages, function ($cell,$grand_total_for_wages) {
                            $cell->setValue($grand_total_for_wages);
                        });

//                        $sheet->cell('P'.$deployments_number,$grand_total_for_each, function ($cell,$grand_total_for_each) {
//                            $cell->setValue("ppp");
//                        });
//                        $sheet->cell('U'.$deployments_number,$grand_total_for_each, function ($cell,$grand_total_for_each) {
//                            $cell->setValue("uuu");
//                        });

                    });
                })->store('xlsx', Config::get('globalvariables.$pathToSaveTemporaryExcelFiles'));


                $fileNameToDownload = $fileName . '.xlsx';

                $file_path = Config::get('globalvariables.$pathToSaveTemporaryExcelFiles') . '/' . $fileNameToDownload;
                if (file_exists($file_path)) {
                    // Send Download
                    return Response::download($file_path, $fileNameToDownload, [
                        'Content-Length: ' . filesize($file_path)
                    ])->deleteFileAfterSend(true);
                } else {
                    // Error
                    exit('Requested file does not exist on our server!');
                }


                $data = array('fileNameToDownload' => $fileNameToDownload);

                return ['responseCode' => 1, 'responseStatus' => 'successful', 'message' => 'your file is ready, and will download in few seconds', 'data' => $data];


            }
            else{

                return view('guards.attendance')->with(['noRecordFound' => 'No record Found']);
            }

        }



    }
    public function getSalaryFinalExport(Request $request){
        $input = $request->all();

//        $startDate = getStartDateByMonthDate($input['salary_month']);
//        $endDate = getEndDateByMonthDate($input['salary_month']);
        $regionId = $input['region'];
        $managerId = $input['manager'];
        $supervisor = $input['supervisor'];
        $getSalaryData = new Guards\PayrollSalaryModel();
        $getSalaryData = $getSalaryData->getAll();
//        dd($getSalaryData);

        $user_id =   Auth::guard('user')->id();
//        $user_name =   Auth::guard('user')->name;
        $user = new UserModel();
        $user = $user->getUserById($user_id);
//        dd($user['name']);
        $loans = $getSalaryData;
//dd($loans);
        if(count($loans) == 0){

        }
        else{


            if(count($loans) > 0){


                if($request->salary_month){
                    $fileName = 'salary'.$request->salary_month;
                }else{

                    $fileName = 'salary';
                }
                $sheetsArray = array();


                Excel::create(/**
                 * @param $excel
                 */
                    $fileName, function ($excel) use ($loans,$user) {
                    //dd($searchResults);


                    $excel->setTitle('Guards Attandance');
                    $excel->setDescription('Guards Attandance');

                    //center align
                    $excel->getDefaultStyle()
                        ->getAlignment()
                        ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                        ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

                    $currentSheetName = 'Operation Salary';
                    $excel->getActiveSheet()->getStyle('E6:R90')->getNumberFormat()->setFormatCode('#,##0_);[Red](#,##0)');

                    $excel->sheet($currentSheetName, function ($sheet) use ($loans,$user) {
                        $deployments_number = 6;
                        $startsFromDeploymentNumber = 6;
                        $number_of_rows_with_total = 0 ;
                        $temp_g_id = '';
                        $total_loan = 0;
                        $rate= 0;
                        $days_for_each = 0;
                        $wages_for_each = 0;
                        $ot_total_for_each = 0;
                        $eh_total_for_each = 0;
                        $np_bf_total_for_each = 0;
                        $sbd_total_for_each = 100;
                        $ops_loan_total_for_each = 100;
                        $apsaa_loan_total_for_each = 100;
                        $cwf_loan_total_for_each = 100;
                        $np_af_total_for_each = 0;
                        $grand_total_af_for_each = 0;
                        $grand_total_bf_for_each = 0;
                        $grand_total_for_days = 0;
                        $grand_total_for_wages = 0;

                        foreach ($loans as $loan) {
//                                dd($loan['parwest_id']);

                            if($temp_g_id && $temp_g_id!= $loan['parwest_id']){
                                $sheet->cell('A'.$deployments_number, function ($cell) {
                                    $cell->setValue('Sub Total');
                                });
                                $sheet->cell('D'.$deployments_number,$rate, function ($cell,$rate) {
                                    $cell->setValue($rate);
                                });
                                $sheet->cell('E'.$deployments_number,$days_for_each, function ($cell,$days_for_each) {
                                    $cell->setValue($days_for_each);
                                });
                                $sheet->cell('F'.$deployments_number,$wages_for_each, function ($cell,$wages_for_each) {
                                    $cell->setValue($wages_for_each);
                                });
                                $sheet->cell('I'.$deployments_number,$ot_total_for_each, function ($cell,$ot_total_for_each) {
                                    $cell->setValue($ot_total_for_each);
                                });
//                                    $sheet->cell('L'.$deployments_number,$eh_total_for_each, function ($cell,$eh_total_for_each) {
//                                        $cell->setValue($eh_total_for_each);
//                                    });



                                $sheet->cell('Q'.$deployments_number,$sbd_total_for_each, function ($cell,$sbd_total_for_each) {
                                    $cell->setValue($sbd_total_for_each);
                                });

                                $sheet->cell('R'.$deployments_number,$ops_loan_total_for_each, function ($cell,$ops_loan_total_for_each) {
                                    $cell->setValue($ops_loan_total_for_each);
                                });
                                $sheet->cell('S'.$deployments_number,$apsaa_loan_total_for_each, function ($cell,$apsaa_loan_total_for_each) {
                                    $cell->setValue($apsaa_loan_total_for_each);
                                });
                                $sheet->cell('T'.$deployments_number,$cwf_loan_total_for_each, function ($cell,$cwf_loan_total_for_each) {
                                    $cell->setValue($cwf_loan_total_for_each);
                                });
                                $sheet->cell('P'.$deployments_number,$np_bf_total_for_each, function ($cell,$np_bf_total_for_each) {
                                    $cell->setValue($np_bf_total_for_each);
                                });

                                $sheet->cell('U'.$deployments_number,$np_af_total_for_each, function ($cell,$np_af_total_for_each) {
                                    $cell->setValue($np_af_total_for_each);
                                });



//                                    $grand_total_for_each = $grand_total_for_each + $np_af_total_for_each;
//                                    dd($np_af_total_for_each);
                                $deployments_number ++;
                                $rate = 0;
                                $days_for_each = 0;
                                $wages_for_each = 0;
                                $ot_total_for_each = 0;
                                $eh_total_for_each = 0;
                                $np_bf_total_for_each = 0;
                                $sbd_total_for_each = 100;
                                $ops_loan_total_for_each = 100;
                                $apsaa_loan_total_for_each = 100;
                                $cwf_loan_total_for_each = 100;
                                $np_af_total_for_each = 0;

                            }
                            $sheet->row(1, array(
                                'PARWEST PACIFIC SECURITY ( PVT ) LTD.'

                            ));
                            $sheet->row(2, array(
                                '176-CAVALARY GROUND, LAHORE CANTT.'

                            ));
                            $sheet->row(3, array(
                                'Salary'

                            ));
//                            $sheet->row(5, array(
//                                'PPS No.','Name', 'Desig', 'Rate', 'Days',
//                                'Wages','OT','OT Wages','OT Total','E.H','EH Wages','EH Total','Eid Days','Eidi Wages','Eidi Total','Net Pay'
//                            ,'S.BR Verfication' ,'Ops Loan','APSAA','CWF','Net Payable','post','supervisor','Manager','Status ','Signature'
//                            ));
                            $rules = new Guards\PayrollSalaryRuleModel();
                            $rules = $rules->getAll();
//                            $sheet->row(5,
//                                $rules
//                           );
                            $sheet->cell('A'.'5', function ($cell) {
//                                $rate  + = $location_rate;
                                $cell->setValue('PPS No.');
                            });
                            $sheet->cell('B'.'5', function ($cell) {
//
                                $cell->setValue('Name');
                            });
                            $sheet->cell('C'.'5', function ($cell) {
//
                                $cell->setValue('Desig');
                            });
                            $sheet->cell('D'.'5', function ($cell) {
//
                                $cell->setValue('Rate');
                            });
                            $sheet->cell('E'.'5', function ($cell) {
//
                                $cell->setValue('Days');
                            });
                            $sheet->cell('F'.'5', function ($cell) {
//
                                $cell->setValue('Wages');
                            });
//                            dd($rules);
                            foreach( range('G', 'Q') as $key=> $elements) {
                                $value = $rules[$key]->name;
//                                dd($elements);
                                if(isset($value)){
                                    $sheet->cell($elements.'5',$value, function ($cell,$value) {
                                        $cell->setValue($value);
                                    });
                                }
                            }

                            $parwest_id = $loan['parwest_id'];
                            $temp_g_id = $loan['parwest_id'];

                            $rate =  $rate + $loan['location_rate'];
                            $days_for_each =  $days_for_each + $loan['days'];
                            $wages_for_each =  $wages_for_each + ($loan['location_rate'] / 30)* $loan['days'];
                            $ot_total_for_each =  $ot_total_for_each + $loan['is_overtime'] * $loan['overtime_cost'];
//                                $ot_total_for_each =  $ot_total_for_each + $loan['is_overtime'] * $loan['overtime_cost'];
//                                $ops_loan_total_for_each =  $ops_loan_total_for_each + $loan['is_overtime'] * $loan['overtime_cost'];
//                                $apsaa_loan_total_for_each =  $apsaa_loan_total_for_each + $loan['is_overtime'] * $loan['overtime_cost'];
                            $np_af_total_for_each +=  $np_af_total_for_each ;


                            $name = $loan['guard_Name'];
                            $designation = 'guard';
                            $location_rate = $loan['location_rate'];
                            $days = $loan['days'];
                            $wages = ($loan['location_rate'] / 30)* $loan['days'];
//                                $eid_days = $loan['location_rate'];
                            $eid_days = 0;
                            $eid_wages = 0;
//                                $eid_wages = $loan['location_rate'];
                            $eid_total = 0;
                            $ot = $loan['is_overtime'];
                            $ot_wages = $loan['overtime_cost'];
                            $ot_total = $loan['is_overtime'] * $loan['overtime_cost'];
                            $eh = $loan['is_extra'];
                            $eh_wages = $loan['extrahours_cost'];
                            $eh_total = $eh * $eh_wages;
                            $net_pay_before = $wages + $eid_total +$ot_total+$eh_total;
                            $special_branch_deduction = 100;
                            $ops_loan = $loan['location_rate'];
                            $ops_loan = 0;
                            $apsaa = 0;
                            $cwf = 0;
                            $net_pay_after = $net_pay_before - $special_branch_deduction - $ops_loan -$apsaa -$cwf;
                            $post = $loan['client_name']."  ".$loan['branch_name'];
                            $supervisor = $loan['guard_supervisor_name'];
                            $manager = $loan['guard_manager_name'];

//
                            $sheet->cell('A'.$deployments_number,$parwest_id, function ($cell,$parwest_id) {
                                $cell->setValue($parwest_id);
                            });
                            $sheet->cell('B'.$deployments_number,$name, function ($cell,$name) {
                                $cell->setValue($name);
                            });
                            $sheet->cell('C'.$deployments_number,$designation, function ($cell,$designation) {
                                $cell->setValue($designation);
                            });
                            $sheet->cell('D'.$deployments_number,$location_rate, function ($cell,$location_rate) {
//                                $rate  + = $location_rate;
                                $cell->setValue($location_rate);
                            });
                            $sheet->cell('E'.$deployments_number,$days, function ($cell,$days) {
                                $cell->setValue($days);
                            });
                            $sheet->cell('F'.$deployments_number,$wages, function ($cell,$wages) {
                                $cell->setValue($wages);
                            });

                            $count = count($rules);
                            foreach( range('G', 'Q') as $key=> $elements) {
//
//                            dd($value);
                                if(isset($count)){
                                    //get all values by deployment id
//                                    dd($loan);
                                    $rule_values  = $this->getRulesValuesByDeploymentId($loan['deployment_id']);
                                    $value = $rule_values[$key]->salary_rule_value;
                                    $sheet->cell($elements.$deployments_number,$value, function ($cell,$value) {
                                        $cell->setValue($value);
                                    });
//                                    $sheet->cell($elements.'5',$value, function ($cell,$value) {
//                                        $cell->setValue($value);
//                                    });
                                }
                            }

//                            $sheet->cell('G'.$deployments_number,$ot, function ($cell,$ot) {
//                                $cell->setValue($ot);
//                            });
//                            $sheet->cell('H'.$deployments_number,$ot_wages, function ($cell,$ot_wages) {
//                                $cell->setValue($ot_wages);
//                            });
//                            $sheet->cell('I'.$deployments_number,$ot_total, function ($cell,$ot_total) {
//                                $cell->setValue($ot_total);
//                            });
//                            $sheet->cell('J'.$deployments_number,$eh, function ($cell,$eh_wages) {
//                                $cell->setValue($eh_wages);
//                            });
//                            $sheet->cell('K'.$deployments_number,$eh_wages, function ($cell,$eh_wages) {
//                                $cell->setValue($eh_wages);
//                            });
//                            $sheet->cell('L'.$deployments_number,$eh_total, function ($cell,$eh_total) {
//                                $cell->setValue($eh_total);
//                            });
//                            $sheet->cell('M'.$deployments_number,$eid_days, function ($cell,$eid_days) {
//                                $cell->setValue($eid_days);
//                            });
//                            $sheet->cell('N'.$deployments_number,$eid_wages, function ($cell,$eid_wages) {
//                                $cell->setValue($eid_wages);
//                            });
//                            $sheet->cell('O'.$deployments_number,$eid_total, function ($cell,$eid_total) {
//                                $cell->setValue($eid_total);
//                            });
////                            dd($net_pay_before);
//                            $sheet->cell('P'.$deployments_number,$net_pay_before, function ($cell,$net_pay_before) {
//                                $cell->setValue($net_pay_before);
//                            });
//                            $sheet->cell('U'.$deployments_number,$net_pay_after, function ($cell,$net_pay_after) {
//                                $cell->setValue($net_pay_after);
//                            });
                            $grand_total_bf_for_each = $grand_total_bf_for_each + $net_pay_before;
                            $grand_total_af_for_each = $grand_total_af_for_each + $net_pay_after;
                            $grand_total_for_days = $grand_total_for_days + $days;
                            $grand_total_for_wages = $grand_total_for_wages + $wages;
//                            $sheet->cell('Q'.$deployments_number,$special_branch_deduction, function ($cell,$special_branch_deduction) {
//                                $cell->setValue($special_branch_deduction);
//                            });
//
//                             $sheet->cell('R'.$deployments_number,$ops_loan, function ($cell,$ops_loan) {
//                                    $cell->setValue($ops_loan);
//                             });
//                             $sheet->cell('S'.$deployments_number,$apsaa, function ($cell,$apsaa) {
//                                    $cell->setValue($apsaa);
//                             });
//                             $sheet->cell('T'.$deployments_number,$cwf, function ($cell,$cwf) {
//                                $cell->setValue($cwf);
//                             });
//                            $sheet->cell('V'.$deployments_number,$post, function ($cell,$post) {
//                                $cell->setValue($post);
//                            });
//                            $sheet->cell('W'.$deployments_number,$supervisor, function ($cell,$supervisor) {
//                                $cell->setValue($supervisor);
//                            });
//                            $sheet->cell('X'.$deployments_number,$manager, function ($cell,$manager) {
//                                $cell->setValue($manager);
//                            });


                            $deployments_number = $deployments_number+1;

                            //setting sheet fontname
                            $sheet->setStyle(array(
                                'font' => array(
                                    'name' => 'Calibri',
                                ),
                                'setSize' => array(
                                    'height' => '35',
                                )
                            ));

                            $sheet->mergeCells('A1:R1');
                            $sheet->getStyle('A1:R1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                            $sheet->cells('A1:R1', function ($cells) {

                                $cells->setBackground('#ffffff');
                                $cells->setFontColor('#000000');
                                $cells->setFont(array(
                                    'size' => '20',
                                    'bold' => true
                                ));

                            });

                            $sheet->cells('A2:A4', function ($cells) {
                                $cells->setFont(array(
                                    'bold' => true
                                ));

                            });
                            $sheet->cells('D2:D7', function ($cells) {
                                $cells->setFont(array(
                                    'bold' => true
                                ));

                            });

                            $sheet->mergeCells('A1:R1');
                            $sheet->mergeCells('A2:R2');
                            $sheet->mergeCells('A3:R3');

                        }
                        $deployments_number = $deployments_number +2;
                        $sheet->cell('A'.$deployments_number, function ($cell) {
                            $cell->setValue('Grand Total');
                        });
                        $sheet->cell('P'.$deployments_number,$grand_total_bf_for_each, function ($cell,$grand_total_bf_for_each) {
                            $cell->setValue($grand_total_bf_for_each);
                        });
                        $sheet->cell('U'.$deployments_number,$grand_total_af_for_each, function ($cell,$grand_total_af_for_each) {
                            $cell->setValue($grand_total_af_for_each);
                        });  $sheet->cell('E'.$deployments_number,$grand_total_for_days, function ($cell,$grand_total_for_days) {
                            $cell->setValue($grand_total_for_days);
                        });
                        $sheet->cell('F'.$deployments_number,$grand_total_for_wages, function ($cell,$grand_total_for_wages) {
                            $cell->setValue($grand_total_for_wages);
                        });

//                        $sheet->cell('P'.$deployments_number,$grand_total_for_each, function ($cell,$grand_total_for_each) {
//                            $cell->setValue("ppp");
//                        });
//                        $sheet->cell('U'.$deployments_number,$grand_total_for_each, function ($cell,$grand_total_for_each) {
//                            $cell->setValue("uuu");
//                        });

                    });
                })->store('xlsx', Config::get('globalvariables.$pathToSaveTemporaryExcelFiles'));


                $fileNameToDownload = $fileName . '.xlsx';

                $file_path = Config::get('globalvariables.$pathToSaveTemporaryExcelFiles') . '/' . $fileNameToDownload;
                if (file_exists($file_path)) {
                    // Send Download
                    return Response::download($file_path, $fileNameToDownload, [
                        'Content-Length: ' . filesize($file_path)
                    ])->deleteFileAfterSend(true);
                } else {
                    // Error
                    exit('Requested file does not exist on our server!');
                }


                $data = array('fileNameToDownload' => $fileNameToDownload);

                return ['responseCode' => 1, 'responseStatus' => 'successful', 'message' => 'your file is ready, and will download in few seconds', 'data' => $data];


            }
            else{

                return view('guards.attendance')->with(['noRecordFound' => 'No record Found']);
            }

        }





    }


//    public function getSalaryExport1(Request $request){
//        return ['responseCode' => 1, 'responseStatus' => 'successful', 'message' => 'your file is ready, and will download in few seconds', 'data' => $data];
//
//        $input = $request->all();
//        $user_id =   Auth::guard('user')->id();
//
//
//        $loans = new Guards\GuardLoansModel();
//        $loans = $loans->getModelByUserId($user_id);
////dd($loans);
//        if(count($loans) == 0){
//        }
//        else{
//            if(count($loans) > 0){
//                $fileName = 'salary';
//                $sheetsArray = array();
//                Excel::create(/**
//                 * @param $excel
//                 */
//                    $fileName, function ($excel) use ($loans) {
//                    //dd($searchResults);
//
//
//                    $excel->setTitle('Guards Attandance');
//                    $excel->setDescription('Guards Attandance');
//
//                    //center align
//                    $excel->getDefaultStyle()
//                        ->getAlignment()
//                        ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
//                        ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
//                        $currentSheetName = 'salary';
//                    $excel->setHeight(3, 50);
//                    $excel->sheet($currentSheetName, function ($sheet) use ($loans) {
//                        $deployments_number = 6;
//
//                        foreach ($loans as $loan) {
////                            dd($loan);
//                            $sheet->setHeight(array(
//                                $deployments_number =>  30
//                            ));
//                            $parwest_id = $loan['parwest_id'];
//                            $status = $loan['status'];
//                            $designation = 'guard';
//                            $name = $loan['guard_name'];
//                            $phone_number = $loan['guards_phone'];
//                            $slip_number = $loan['slip_number_loan'];
//                            $date = $loan['created_at'];
//                            $days = $loan['deployment_days'];
//                            $amount = $loan['amount_paid'];
//
//                            $sheet->cell('A'.$deployments_number,$parwest_id, function ($cell,$parwest_id) {
//                                $cell->setValue($parwest_id);
//                            });
//                            $sheet->cell('B'.$deployments_number,$status, function ($cell,$status) {
//                                $cell->setValue($status);
//                            });
//                            $sheet->cell('C'.$deployments_number,$designation, function ($cell,$designation) {
//                                $cell->setValue($designation);
//                            });
//                            $sheet->cell('D'.$deployments_number,$name, function ($cell,$name) {
//                                $cell->setValue($name);
//                            });
//                            $sheet->cell('H'.$deployments_number,$days, function ($cell,$days) {
//                                $cell->setValue($days);
//                            });
//                            $sheet->cell('E'.$deployments_number,$phone_number, function ($cell,$phone_number) {
//                                $cell->setValue($phone_number);
//                            });
//                            $sheet->cell('F'.$deployments_number,$slip_number, function ($cell,$slip_number) {
//                                $cell->setValue($slip_number);
//                            });
//                                $sheet->cell('G'.$deployments_number,$date, function ($cell,$date) {
//                                    $cell->setValue($date);
//                                });
//                            $sheet->cell('I'.$deployments_number,$amount, function ($cell,$amount) {
//                                $cell->setValue($amount);
//                            });
//
//                            $deployments_number = $deployments_number+1;
//                            $sheet->setSize(array(
//                                'A5:U5' => array(
////                                    'width'     => 50,
//                                    'height'    => 35
//                                )
//                            ));
//                            $sheet->setStyle(array(
//                                'font' => array(
//                                    'name' => 'Calibri',
//                                )
//                            ));
//                            $sheet->mergeCells('A1:R1');
//                            $sheet->getStyle('A1:R1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//                            $sheet->cells('A1:R1', function ($cells) {
//
//                                $cells->setBackground('#ffffff');
//                                $cells->setFontColor('#000000');
//                                $cells->setFont(array(
//                                    'size' => '20',
//                                    'bold' => true
//                                ));
//
//                            });
//
//                            $sheet->cells('A2:A4', function ($cells) {
//                                $cells->setFont(array(
//                                    'bold' => true
//                                ));
//
//                            });
//                            $sheet->cells('D2:D7', function ($cells) {
//                                $cells->setFont(array(
//                                    'bold' => true
//                                ));
//
//                            });
//                            $sheet->mergeCells('A1:R1');
//                            $sheet->mergeCells('A2:R2');
//                            $sheet->mergeCells('A3:R3');
//                            $sheet->row(1, array(
//                                'PARWEST PACIFIC SECURITY ( PVT ) LTD.'
//
//                            ));
//                            $sheet->row(2, array(
//                                '176-CAVALARY GROUND, LAHORE CANTT.'
//
//                            ));
//                            $sheet->row(3, array(
//                                'Loans Details '
//
//                            ));
//                            $sheet->cells('A9:AO9', function ($cells) {
//                                $cells->setFont(array(
//                                    'bold' => true
//                                ));
//
//                                $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
//                                $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
//
//                            });
//                            $sheet->row(5, array(
//                                'PPS No.', 'Status', 'Desig', 'Name', 'Phone Number', 'Slip Number', 'Date', 'Days', 'Amount'
//                            ));
//                        }
//                    });
//                })->store('xlsx', Config::get('globalvariables.$pathToSaveTemporaryExcelFiles'));
//
//
//                $fileNameToDownload = $fileName . '.xlsx';
//
//                $file_path = Config::get('globalvariables.$pathToSaveTemporaryExcelFiles') . '/' . $fileNameToDownload;
//                if (file_exists($file_path)) {
//                    // Send Download
//                    return Response::download($file_path, $fileNameToDownload, [
//                        'Content-Length: ' . filesize($file_path)
//                    ])->deleteFileAfterSend(true);
//                } else {
//                    // Error
//                    exit('Requested file does not exist on our server!');
//                }
//
//
//                $data = array('fileNameToDownload' => $fileNameToDownload);
//
//                return ['responseCode' => 1, 'responseStatus' => 'successful', 'message' => 'your file is ready, and will download in few seconds', 'data' => $data];
//
//
//            }
//            else{
//
//                return view('guards.accountSalary')->with(['noRecordFound' => 'No record Found']);
//            }
//
//        }
//
//
//
//    }


    function getMonthByDate($date)
    {
        // ...
        $date = strtotime($date);
        $month = date('m',$date);
        return $month;
    }
    function getYearByDate($date)
    {
        // ...
        $date = strtotime($date);
        $month = date('y',$date);
        return $month;
    }

    function getAllByDeploymentId($deploymentId){


    }


    function getDaysBetweenDates($start_date,$end_date){

        // Declare and define two dates
//        $date1 = strtotime("2016-06-01 22:45:00");
        $date1 = strtotime($start_date);
//        $date2 = strtotime("2018-09-21 10:44:01");
        $date2 = strtotime($end_date);

// Formulate the Difference between two dates
        $diff = abs($date2 - $date1);


// To get the year divide the resultant date into
// total seconds in a year (365*60*60*24)
        $years = floor($diff / (365*60*60*24));


// To get the month, subtract it with years and
// divide the resultant date into
// total seconds in a month (30*60*60*24)
        $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));


// To get the day, subtract it with years and
// months and divide the resultant date into
//// total seconds in a days (60*60*24)
//        $days = floor(($diff - $years * 365*60*60*24 -
//                $months*30*60*60*24)/ (60*60*24));
        $days = floor(($diff )/ (60*60*24));
//  $days = $date1;


// To get the hour, subtract it with years,
// months & seconds and divide the resultant
// date into total seconds in a hours (60*60)
        $hours = floor(($diff - $years * 365*60*60*24
                - $months*30*60*60*24 - $days*60*60*24)
            / (60*60));


// To get the minutes, subtract it with years,
// months, seconds and hours and divide the
// resultant date into total seconds i.e. 60
        $minutes = floor(($diff - $years * 365*60*60*24
                - $months*30*60*60*24 - $days*60*60*24
                - $hours*60*60)/ 60);


// To get the minutes, subtract it with years,
// months, seconds, hours and minutes
        $seconds = floor(($diff - $years * 365*60*60*24
            - $months*30*60*60*24 - $days*60*60*24
            - $hours*60*60 - $minutes*60));

// Print the result
//        printf("%d years, %d months, %d days, %d hours, " . "%d minutes, %d seconds", $years, $months, $days, $hours, $minutes, $seconds);


        return $days;

//        $now = time(); // or your date as well
//        $start_date = strtotime($start_date);
//        $end_date = strtotime($end_date);
//        $datediff =  $end_date - $start_date;
//
//        return round($datediff / (60 * 60 * 24));
    }

    function getDaysInMonthByDate($date){
        $date = strtotime($date);
        $days = date("t",$date);
        return $days;
    }

    function getCostByLocationRate($rate,$start_date,$end_date){
        $days_in_month = $this->getDaysInMonthByDate($start_date);
        $days_worked = $this->getDaysBetweenDates($start_date,$end_date);
        $cost = ($rate/$days_in_month ) * $days_worked;
        return $cost;
    }

    function getDaysCostByLocationRate($rate,$start_date,$end_date){
        $days_in_month = $this->getDaysInMonthByDate($start_date);
        $days_worked = $this->getDaysBetweenDates($start_date,$end_date);
        $cost = ($rate/$days_in_month ) * ($days_worked+1);
        return $cost;
    }

    function setDate($day,$month,$year){
        $date=date_create();
        date_date_set($date,$year,$month,$day);
        return date_format($date,"Y-m-d");
    }

    function getStartDateByMonthDate($monthlyDate){
        $guardSalaryMonth = $monthlyDate;
//        dd($guardSalaryMonth);
// total days from monthly date formate amd last date in month
        $total_days = $this->getDaysInMonthByDate($guardSalaryMonth);

        $guardSalaryMonthNo = $this->getMonthByMonthlyDate($guardSalaryMonth);
        $guardSalaryYear = $this->getYearByMonthlyDate($guardSalaryMonth);
        $startDate = $this->setDate('01',$guardSalaryMonthNo,$guardSalaryYear);
//        $endDate = $this->setDate($total_days,$guardSalaryMonthNo,$guardSalaryYear);
        return $startDate;

    }

    function getEndDateByMonthDate($monthlyDate){
        $guardSalaryMonth = $monthlyDate;
//        dd($guardSalaryMonth);
// total days from monthly date formate amd last date in month
        $total_days = $this->getDaysInMonthByDate($guardSalaryMonth);

        $guardSalaryMonthNo = $this->getMonthByMonthlyDate($guardSalaryMonth);
        $guardSalaryYear = $this->getYearByMonthlyDate($guardSalaryMonth);
//        $startDate = $this->setDate('01',$guardSalaryMonthNo,$guardSalaryYear);
        $endDate = $this->setDate($total_days,$guardSalaryMonthNo,$guardSalaryYear);
        return $endDate;

    }

    function getYearByMonthlyDate($guardSalaryMonth){
        $guardSalaryMonth = strrev($guardSalaryMonth);
        $guardSalaryYear = substr($guardSalaryMonth, strpos($guardSalaryMonth, "_") + 3);
        return $guardSalaryYear = strrev($guardSalaryYear);
    }

    function getMonthByMonthlyDate($guardSalaryMonth){

        return $guardSalaryMonth = substr($guardSalaryMonth, strpos($guardSalaryMonth, "_") + 5);

    }

    function calculateCwf($joining_date,$current_date){
        $cwf_per_month = 0;
        $cwf_total = 600;
        $cwf_per_month = $cwf_total/24;
        $days = $this->getDaysBetweenDates($joining_date,$current_date);
        if($days> 365){
            return $cwf_per_month;
        }else{
            return $cwf_per_month;
        }
//        return $guardSalaryMonth = substr($guardSalaryMonth, strpos($guardSalaryMonth, "_") + 5);

    }


    public function guardSalaryByRegion(Request $request){






        $active_month = new Guards\PayrollSalaryMonth();
        $active_month = $active_month->getAll();
//        dd($active_month['date_from']);

        $salary_month =  substr($active_month['date_from'], 0, -3);
//        dd($salary_month);
        $month_days = $active_month['month_days'];
        $salary_month_ac = $active_month['salary_month'];

        //check if the managers have not yet finalize any loan


        //genrate all deployments records and store in database

        // export with manager selection .. for this we can use  guardSalaryResult and its query by manager id




        $this->validate($request,[
            'regions' => 'required',
//            'salary_month' => 'required',
        ]);


//        $guardSalaryMonth = $request->salary_month;
        $guardSalaryMonth = $salary_month;
// total days from monthly date formate amd last date in month
        $total_days = $this->getDaysInMonthByDate($guardSalaryMonth);
//
        $guardSalaryMonthNo = $this->getMonthByMonthlyDate($guardSalaryMonth);
        $guardSalaryYear = $this->getYearByMonthlyDate($guardSalaryMonth);
        $startDate = $this->setDate('01',$guardSalaryMonthNo,$guardSalaryYear);
        $endDate = $this->setDate($total_days,$guardSalaryMonthNo,$guardSalaryYear);
//        dd($endDate);
        $regionId = $request->regions;
        $clientGuardAcciciationModel = new ClientGuardsAssociation();
        $guardDeployments = $clientGuardAcciciationModel->deployedGuardByRegionalOfficeForSalary($regionId, $startDate, $endDate);
//        dd($guardDeployments);




        $salaryS = new Guards\GuardSalaryModel();
        $salaryS = $salaryS->getLast();
//        dd($salaryS);
        if(isset($salaryS) ){
            $salaryHistoryID = $salaryS->salary_history_id+1;



        }else{
            $salaryHistoryID = 1 ;
        }



        $salaryHistoryPivot = new Guards\GuardSalaryHistoryStatModel();
        $salaryHistoryPivot = $salaryHistoryPivot->saveModel($salaryHistoryID,$regionId,$salary_month_ac);

//

//dd($salaryDeduction);
        foreach ($guardDeployments as  $key => $guardDeployment){
//                $startDay = strtotime('2019-11-01');
//            $createDeploy = strtotime($guardDeployment->created_at);
            $old_start_deploy = '';
            $days = 0;
//
            $old_start_deploy = $guardDeployment->created_at;



            if($old_start_deploy > $startDate){

                $startDatess = $old_start_deploy;
            }else{
                $startDatess = $startDate;

            }


            if($guardDeployment->end_date == null){
                $endDay = $endDate;
            }else{
                $endDay = $guardDeployment->end_date;
            }
//            dd($endDay);
            $days = $this->getDaysBetweenDates($startDatess,$endDay) +1;
            $guardDeployment->days = $days;
//            $guardDeployment->cost = $this->getDaysCostByLocationRate((int)$guardDeployment->location_rate,$startDate,$endDay);

            $guardDeployment->cost  = (((int)$guardDeployment->location_rate/$month_days) * (int)$guardDeployment->days);

            $guardDeployment->startDay = $startDatess;
            $guardDeployment->endDay = $endDay;

//
//            dd($guardDeployment);
            $salary = new Guards\GuardSalaryModel();
            $salary = $salary->saveModel($guardDeployment,$salaryHistoryID,$regionId);

            $payrollSalaryRule = new Guards\PayrollSalaryRuleModel();
            $payrollSalaryRule = $payrollSalaryRule->getAll();

            $gross = 0 ;
            $payrollSalaryRuleDetail = 0 ;
            foreach ($payrollSalaryRule as $salaryRule){

                $payrollSalaryRuleDetail = new Guards\PayrollSalaryRuleDetailModel();
                $payrollSalaryRuleDetail = $payrollSalaryRuleDetail->saveModel($salaryRule,$guardDeployment,$regionId,$salaryHistoryID);
//                if($payrollSalaryRuleDetail != true || is_numeric($payrollSalaryRuleDetail) || $payrollSalaryRuleDetail >0){
//                    if($salaryRule->code == 'NPY'){
////                        dd($gross + $payrollSalaryRuleDetail);
//                        $objectToSave = new Guards\PayrollSalaryRuleDetailModel();
//                        $objectToSave = $objectToSave->getModelByids($guardDeployment->id,$salaryRule->id);
//                        $objectToSave->salary_rule_value = $gross + $payrollSalaryRuleDetail;
//                        $objectToSave->save();
//                    }else{
//                        $gross  += (int)$payrollSalaryRuleDetail;
//
//                    }

//                }
//                if($payrollSalaryRuleDetail == true){
//                    if($salaryRule->code == 'GRS'){
////                        dd($gross);
//                        $objectToSave = new Guards\PayrollSalaryRuleDetailModel();
//                        $objectToSave = $objectToSave->getModelByids($guardDeployment->id,$salaryRule->id);
//                        $objectToSave->salary_rule_value = $gross;
//                        $objectToSave->save();
//                    }
//                }
            }
        }

        $month_number = $active_month['salary_month'];
        $month_year = $active_month['salary_year'];

//        dd($month_number);
        $salaryDeduction = new Guards\GuardSalaryHistoryStatModel();
        $salaryDeduction = $salaryDeduction->salaryDeduction($regionId,$salaryHistoryID,$startDate,$endDate,$month_number,$month_year);


        return redirect()->back()->with(['success_central' => 'Salary Genrated ']);

    }
    public function guardSalaryResult(Request $request){

        //server side validation. check if guard is soft deleted than also handle it
        $this->validate($request,[
            'parwest_id' => 'required',
            'salary_month' => 'required',
        ]);


        $guardParwestId = $request->parwest_id;
// date formate for month and year  => $guardSalaryMonth
        $guardSalaryMonth = $request->salary_month;
// total days from monthly date formate amd last date in month
        $total_days = getDaysInMonthByDate($guardSalaryMonth);

        $guardSalaryMonthNo = getMonthByMonthlyDate($guardSalaryMonth);
        $guardSalaryYear = getYearByMonthlyDate($guardSalaryMonth);
        $startDate = setDate('01',$guardSalaryMonthNo,$guardSalaryYear);
        $endDate = setDate($total_days,$guardSalaryMonthNo,$guardSalaryYear);
//dd($startDate);
        //$pa   rameters to be sent back to sustain the form
        $parameters = [
            'startDate'=>$startDate,
            'endDate'=>$endDate,
            'guardParwestId'=>$guardParwestId,
        ];

        //finding guards id based on guards parwest id
        $guard = new Guards\Guards();
        $guard = $guard->where('parwest_id' , '=' ,$guardParwestId)->get();
//        dd(count($guard));
        if(count($guard) == 0){
            return view('guards.attendance')->with(['parameters' => $parameters ,
                'invalid' => 'Selected parwest ID is invalid']);

        }
        else{
            $guardId = $guard[0]->id;

//            dd($guardId);
            //finding the deployments based on the dates
            $clientGuardAcciciationModel = new ClientGuardsAssociation();
            $guardDeployments = $clientGuardAcciciationModel->deployedGuardByManagerIdForSalary($guardId, $startDate, $endDate);
//            dd($guardDeployments);
            if(count($guardDeployments) > 0){
                $data = [
                    'guardDeployments'=>$guardDeployments,
                    'startDate'=>$startDate,
                    'endDate'=>$endDate,
                ];

                $guardsDataAndAttendanceEncoded = base64_encode(serialize($data));

                $searchResults = (object)(unserialize(base64_decode($guardsDataAndAttendanceEncoded)));
                $searchResults = json_decode(json_encode($searchResults), true);


                $fileName = 'salary';



                Excel::create(/**
                 * @param $excel
                 */
                    $fileName, function ($excel) use ($searchResults) {
                    //dd($searchResults);
                    $attendanceMonth = array();
                    $serialArray = array();
                    $sheetsArray = array();
                    $sheetDayArray = array('01'=>'F', '02'=>'G', '03'=>'H', '04'=>'I', '05'=>'J', '06'=>'K', '07'=>'L', '08'=>'M',
                        '09'=>'N', '10'=>'O', '11'=>'P', '12'=>'Q', '13'=>'R');
                    //find the numbebr of months between start date end date filters. and create a serailArray.
                    $startDate = $searchResults['startDate']; //search filter start date
                    $endDate = $searchResults['endDate']; // search filter end date
//                    dd($searchResults['guardDeployments'] );
                    $currentSheet = 0;
//                    foreach($searchResults['guardDeployments']  as $deployments){
//                        $count = count($searchResults['guardDeployments']);
////                        dd($count);
////                        $sheetsArray[$currentSheet][$count] = array();
//                        $sheetsArray[0][0]['A10'] = 'here';
//
//                    }




















                    $begin = new DateTime( $startDate );
                    $end = new DateTime( $endDate );
                    $end = $end->modify( '+1 day' );

                    $interval = new DateInterval('P1M');
                    $daterange = new DatePeriod($begin, $interval ,$end);


                    foreach($daterange as $key=>$date){

                        $attendanceMonth[$date->format('mY')] = $date->format('M-Y');
                        $serialArray[$date->format('mY')] = $date->format('mY');
                        $sheetsArray[$date->format('mY')] = array();

                    }


                    //iterate deployment entries
                    foreach($searchResults['guardDeployments'] as $key=>$value){

                        $dayNightRegularOvertimeTotal = 0;
                        $deploymentEndDate = 0;
                        $deploymentStartDate = 0;

                        //setting start date
                        $deploymentDate = explode(' ' ,$value['created_at']); //date of deployment available in db 'client_guard_association'
                        if($startDate > $deploymentDate[0] ){

                            $deploymentStartDate =new DateTime($startDate) ;

                        }else{
                            $deploymentStartDate = new DateTime($value['created_at']); //date of deployment available in db 'client_guard_association'
                        }
//              setting end date

                        if($value['end_date'] == NULL){

                            $carbonNow = explode(' ' ,carbon::now());

                            if($endDate >= $carbonNow[0]){
                                $deploymentEndDate = new DateTime($carbonNow[0]);
                                $deploymentEndDate = $deploymentEndDate->modify( '+1 day' );
                            }
                            elseif($endDate < $carbonNow[0]){

                                $deploymentEndDate = new DateTime($endDate);
                                $deploymentEndDate = $deploymentEndDate->modify( '+1 day' );
                            }

                        }
                        elseif($value['end_date'] != NULL){

                            $revoketDate = explode(' ' ,$value['end_date']);//revoke date available in db 'client_guard_association'
                            if($endDate > $revoketDate[0]){
                                $deploymentEndDate = new DateTime( $value['end_date']);
                                $deploymentEndDate = $deploymentEndDate->modify( '+1 day' );
                            }
                            else{
                                $deploymentEndDate = new DateTime($endDate);//revoke date available in db 'client_guard_association'
                                $deploymentEndDate = $deploymentEndDate->modify( '+1 day' );
                            }
                        }


                        //interval between two dates
                        $interval = new DateInterval('P1D');
                        $deploymentDateRange = new DatePeriod($deploymentStartDate, $interval ,$deploymentEndDate);

                        //check that the deployment is not overtime
                        if($value['is_overtime'] == 0 || true){
                            $currentSheet = 0;


                            foreach($deploymentDateRange as $counter => $date){
//                                if($currentSheet != $date->format('mY')){
//
//                                    $currentSheet = $date->format('mY');
////                            $currentSheet = $date->format('m ([ \t.-])* YY');
//
//                                    $count = count($sheetsArray[$currentSheet]);
//                                    $sheetsArray[$currentSheet][$count] = array();
//                                    $sheetsArray[$currentSheet][$count]['A'.($count*4+10)] =  $count + 1 ;
//                                    $sheetsArray[$currentSheet][$count]['B'.($count*4+10)] = $searchResults['guardDeployments'][$key]['location_rate'] ;
//                                    $sheetsArray[$currentSheet][$count]['C'.($count*4+10)] = $searchResults['guardDeployments'][$key]['client_name'] ;
//                                    $sheetsArray[$currentSheet][$count]['D'.($count*4+10)] = $searchResults['guardDeployments'][$key]['branch_name'] ;
//                                    $sheetsArray[$currentSheet][$count]['E'.($count*4+10)] = "Day Regular" ;
//                                    $sheetsArray[$currentSheet][$count]['E'.($count*4+11)] = "Night Regular" ;
//                                    $sheetsArray[$currentSheet][$count]['E'.($count*4+12)] = "Day Double Duty" ;
//                                    $sheetsArray[$currentSheet][$count]['E'.($count*4+13)] = "Night Double Duty" ;
//                                    $sheetsArray[$currentSheet][$count]['AK'.($count*4+10)] = "Presents" ;
//                                    $sheetsArray[$currentSheet][$count]['AK'.($count*4+11)] = "Presents" ;
//                                    $sheetsArray[$currentSheet][$count]['AK'.($count*4+12)] = "Time" ;
//                                    $sheetsArray[$currentSheet][$count]['AK'.($count*4+13)] = "Time" ;
//                                    $sheetsArray[$currentSheet][$count]['AL'.($count*4+10)] = 0 ;
//                                    $sheetsArray[$currentSheet][$count]['AL'.($count*4+11)] = 0 ;
//                                    $sheetsArray[$currentSheet][$count]['AL'.($count*4+12)] = 0 ;
//                                    $sheetsArray[$currentSheet][$count]['AL'.($count*4+13)] = 0 ;
//                                    if(!isset($sheetsArray[$currentSheet][0]['AL9'])){
//                                        $sheetsArray[$currentSheet][0]['AL9'] = 0;
//                                    }
//                                    $sheetsArray[$currentSheet][$count]['AN'.($count*4+10)] = $searchResults['guardDeployments'][$key]['branch_supervisor']; ;
//                                    $sheetsArray[$currentSheet][$count]['AO'.($count*4+10)] = $searchResults['guardDeployments'][$key]['branch_manager'];
//
//                                    $sheetsArray[$currentSheet][$count]['A'.($count*4+10).':A'.($count*4+13)] = 0;
//                                    $sheetsArray[$currentSheet][$count]['B'.($count*4+10).':B'.($count*4+13)] = 0;
//                                    $sheetsArray[$currentSheet][$count]['C'.($count*4+10).':C'.($count*4+13)] = 0;
//                                    $sheetsArray[$currentSheet][$count]['D'.($count*4+10).':D'.($count*4+13)] = 0;
//                                    $sheetsArray[$currentSheet][$count]['AN'.($count*4+10).':AN'.($count*4+13)] = 0;
//                                    $sheetsArray[$currentSheet][$count]['AO'.($count*4+10).':AO'.($count*4+13)] = 0;
//                                    if(!isset($sheetsArray[$currentSheet][0]['E5'])) {
//                                        $sheetsArray[$currentSheet][0]['E5'] = 0;
//                                    }
//                                    if(!isset($sheetsArray[$currentSheet][0]['E6'])) {
//                                        $sheetsArray[$currentSheet][0]['E6'] = 0;
//                                    }
//                                    if(!isset($sheetsArray[$currentSheet][0]['E7'])) {
//                                        $sheetsArray[$currentSheet][0]['E7'] = 0;
//                                    }
//
//                                }



                            }
                        }
                    }

                    $excel->setTitle('Guards Attandance');
                    $excel->setDescription('Guards Attandance');

                    //center align
                    $excel->getDefaultStyle()
                        ->getAlignment()
                        ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                        ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
//dd($sheetsArray);
                    foreach ($sheetsArray as $sheetName=>$arraySheet) {
//                dd($arraySheet);
                        $currentSheetName = 'salary';
                        $excel->sheet($currentSheetName, function ($sheet) use ($searchResults, $arraySheet,$attendanceMonth,$sheetName) {
                            $deployments_number = 6;
                            foreach($searchResults['guardDeployments']  as $deployments){
//                                dd($deployments);

                                $parwest_id = $deployments['parwest_id'];
                                $guard_name = $deployments['guard_Name'];
                                $location_rate = $deployments['location_rate'];
                                $guard_designation = $deployments['guard_designation'];
                                $loan = $deployments['loan'];
                                $days = getDaysBetweenDates($deployments['created_at'],$deployments['end_date']) +1;
                                $cost = getDaysCostByLocationRate($deployments['location_rate'],$deployments['created_at'],$deployments['end_date']);

//                                dd($loan);
                                $sheet->cell('A'.$deployments_number,$parwest_id, function ($cell,$parwest_id) {
                                    $cell->setValue($parwest_id);
                                });
                                $sheet->cell('B'.$deployments_number,$guard_name, function ($cell,$guard_name) {
                                    $cell->setValue($guard_name);
                                });
                                $sheet->cell('C'.$deployments_number,$guard_designation, function ($cell,$guard_designation) {
                                    $cell->setValue($guard_designation);
                                });
                                $sheet->cell('D'.$deployments_number,$location_rate, function ($cell,$location_rate) {
                                    $cell->setValue($location_rate);
                                });
                                $sheet->cell('E'.$deployments_number,$days, function ($cell,$days) {
                                    $cell->setValue($days);
                                });
                                $sheet->cell('F'.$deployments_number,$cost, function ($cell,$cost) {
                                    $cell->setValue($cost);
                                });

                                $deployments_number= $deployments_number+1;

                            }
                            $sheet->cell('M'.$deployments_number,$loan, function ($cell,$loan) {
                                $cell->setValue($loan);
                            });



                            //setting sheet fontname
                            $sheet->setStyle(array(
                                'font' => array(
                                    'name' => 'Calibri',
                                )
                            ));

                            $sheet->mergeCells('A1:R1');
                            $sheet->getStyle('A1:R1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                            $sheet->cells('A1:R1', function ($cells) {

                                $cells->setBackground('#ffffff');
                                $cells->setFontColor('#000000');
                                $cells->setFont(array(
                                    'size' => '20',
                                    'bold' => true
                                ));

                            });

                            $sheet->cells('A2:A4', function ($cells) {
                                $cells->setFont(array(
                                    'bold' => true
                                ));

                            });
                            $sheet->cells('D2:D7', function ($cells) {
                                $cells->setFont(array(
                                    'bold' => true
                                ));

                            });

                            $sheet->mergeCells('A1:R1');
                            $sheet->mergeCells('A2:R2');
                            $sheet->mergeCells('A3:R3');
                            $sheet->row(1, array(
                                'PARWEST PACIFIC SECURITY ( PVT ) LTD.'

                            ));
                            $sheet->row(2, array(
                                '176-CAVALARY GROUND, LAHORE CANTT.'

                            ));
                            $sheet->row(3, array(
                                'PAY FOR THE Special Duty of  '

                            ));


//                            $sheet->cells('E2:AJ2', function ($cells) {
//                                $cells->setBackground('#ffff00');
//
//                                //Set all borders (top, right, bottom, left)
//                                $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
//                                $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
//                                $cells->setFont(array(
//                                    'size' => '16',
//                                    'bold' => true
//                                ));
//                            });
//                            $sheet->mergeCells('e2:AJ2');
//                            $sheet->row(2, array(
//                                'Manager Name:',$searchResults['guardDeployments'][0]['guard_manager_name'], "      ", $searchResults['guardDeployments'][0]['parwest_id'], $searchResults['guardDeployments'][0]['guard_Name']
//
//                            ));


//                            $sheet->cells('E3:AJ3', function ($cells) {
//                                $cells->setBackground('#bee0b4');
//                                $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
//                                $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
//
//                            });
//                            $sheet->mergeCells('e3:AJ3');
//                            $sheet->row(3, array(
//                                'Supervisor Name:', $searchResults['guardDeployments'][0]['guard_supervisor_name'], "      ", 'Guard Status', $searchResults['guardDeployments'][0]['ex_service']
//
//                            ));


//                            $sheet->cells('E4:AJ4', function ($cells) {
//                                $cells->setBackground('#ffff00');
//                                $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
//                                $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
//
//
//                            });
//                            $sheet->mergeCells('e4:AJ4');
//                            $sheet->row(4, array(
//                                'Introducer Name:', $searchResults['guardDeployments'][0]['introducer'], "      ", 'Guard Status', $searchResults['guardDeployments'][0]['current_status_id']
//
//                            ));


                            //total present
//                            $sheet->cells('E5:AJ5', function ($cells) {
//                                $cells->setBackground('#ffff00');
//                                $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
//                                $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
//
//                            });
//                            $sheet->mergeCells('e5:AJ5');
//                            $sheet->cell('D5', function ($cell) {
//                                $cell->setValue('Total Present');
//                            });

//                                $sheet->cell('E5', function ($cell) {
//                                    $cell->setValue('some value');
//                                });


                            //regular duty
//                            $sheet->cells('E6:AJ6', function ($cells) {
//                                $cells->setBackground('#ffff00');
//                                $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
//                                $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
//
//                            });
//                            $sheet->mergeCells('E6:AJ6');
//                            $sheet->cell('D6', function ($cell) {
//                                $cell->setValue('Regular Duty');
//                            });
//                                $sheet->cell('E6', function ($cell) {
//                                    $cell->setValue('some value');
//                                });


                            //double duty
//                            $sheet->cells('E7:AJ7', function ($cells) {
//                                $cells->setBackground('#ffff00');
//                                $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
//                                $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
//
//                            });
//                            $sheet->mergeCells('E7:AJ7');
//                            $sheet->cell('D7', function ($cell) {
//                                $cell->setValue('Double Duty');
//                            });
//                                $sheet->cell('E7', function ($cell) {
//                                    $cell->setValue('some value');
//                                });


                            $sheet->cells('A9:AO9', function ($cells) {
                                $cells->setFont(array(
                                    'bold' => true
                                ));

                                $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

                            });
                            $sheet->row(5, array(
                                'PPS No.', 'Name', 'Desig', 'Rate', 'Days', 'Wages', 'OT', 'O.T Wages', 'Eid Days',
                                'Eidi', 'Net Pay', 'S.BR', 'Ops Loan', 'CWF', 'Balance', 'Post', 'Sign', 'Manger'
                            ));
                            foreach ($arraySheet as $key=>$clients) {

                                foreach ($clients as $keyClient => $valueClient) {




                                    $sheet->cell($keyClient, function($cell) use ($valueClient) {
                                        // manipulate the cell
                                        $cell->setValue($valueClient);

                                        //border
                                        $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                        $cell->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
                                    });


                                    if(preg_match('/\bDay Regular\b/', $valueClient)){
                                        $sheet->cells($keyClient, function ($cells) {
                                            $cells->setBackground('#fccf7c');
                                            $cells->setFont(array(
                                                'bold' => true
                                            ));

                                        });
                                    }

                                    elseif (preg_match('/\bNight Regular\b/', $valueClient)){

                                        $sheet->cells($keyClient, function ($cells) {
                                            $cells->setBackground('#eda521');
                                            $cells->setFont(array(
                                                'bold' => true
                                            ));

                                        });

                                    }
                                    elseif(preg_match('/\bDay Double Duty\b/', $valueClient)){

                                        $sheet->cells($keyClient, function ($cells) {
                                            $cells->setBackground('#00bbff');
                                            $cells->setFont(array(
                                                'bold' => true
                                            ));

                                        });

                                    }
                                    elseif (preg_match('/\bNight Double Duty\b/', $valueClient)){

                                        $sheet->cells($keyClient, function ($cells) {
                                            $cells->setBackground('#0099d1');
                                            $cells->setFont(array(
                                                'bold' => true
                                            ));

                                        });

                                    }
                                    elseif (preg_match('/\bPresents\b/', $valueClient)){

                                        $sheet->cells($keyClient, function ($cells) {
                                            $cells->setBackground('#bee0b4');
                                            $cells->setFont(array(
                                                'bold' => true
                                            ));

                                        });

                                    }
                                    elseif (preg_match('/\bTime\b/', $valueClient)){

                                        $sheet->cells($keyClient, function ($cells) {
                                            $cells->setBackground('#b4c6e7');
                                            $cells->setFont(array(
                                                'bold' => true
                                            ));

                                        });

                                    }
                                    elseif (preg_match('/\bP\b/', $valueClient)){

                                        $sheet->cells($keyClient, function ($cells) {
                                            $cells->setBackground('#00ff00');
                                            $cells->setFont(array(
                                                'bold' => true
                                            ));

                                        });

                                    }
                                    elseif (preg_match('/\bA\b/', $valueClient)){

                                        $sheet->cells($keyClient, function ($cells) {
                                            $cells->setBackground('##ff0000');
                                            $cells->setFont(array(
                                                'bold' => true
                                            ));

                                        });

                                    }
                                    elseif (preg_match('/\bt\b/', $valueClient)){

                                        $sheet->cells($keyClient, function ($cells) {
                                            $cells->setBackground('#94bdff');
                                            $cells->setFont(array(
                                                'bold' => true
                                            ));

                                        });

                                    }
                                    elseif(strpos($keyClient , ':') == true){
                                        $sheet->mergeCells($keyClient);
                                        $sheet->getStyle($keyClient)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                    }
                                }

                            }
                        });
                    }



                })->store('xlsx', Config::get('globalvariables.$pathToSaveTemporaryExcelFiles'));


                $fileNameToDownload = $fileName . '.xlsx';

                $file_path = Config::get('globalvariables.$pathToSaveTemporaryExcelFiles') . '/' . $fileNameToDownload;
                if (file_exists($file_path)) {
                    // Send Download
                    return Response::download($file_path, $fileNameToDownload, [
                        'Content-Length: ' . filesize($file_path)
                    ])->deleteFileAfterSend(true);
                } else {
                    // Error
                    exit('Requested file does not exist on our server!');
                }


                $data = array('fileNameToDownload' => $fileNameToDownload);

                return ['responseCode' => 1, 'responseStatus' => 'successful', 'message' => 'your file is ready, and will download in few seconds', 'data' => $data];


            }
            else{

                return view('guards.attendance')->with(['parameters' => $parameters ,
                    'noRecordFound' => 'No record Found']);
            }

        }



    }


    public function guardSalaryByMonth(Request $request){
        //server side validation. check if guard is soft deleted than also handle it
//        $this->validate($request,[
//            'parwest_id' => 'required',
//            'startDate' => 'required',
//            'endDate' => 'required',
//        ]);


//        assign values to variables
//        $startDate = $request->startDate;
//        $endDate = $request->endDate;
        $name = Carbon::now();
        $file = $request->file('document');
//        dd($file);
        $destinationPath = public_path('images');
//        $file->move($destinationPath, $name.'logo.png');

//        $destinationPath = 'uploads';
        // GET THE FILE EXTENSION
        $extension = $file->getClientOriginalExtension();
        // RENAME THE UPLOAD WITH RANDOM NUMBER
        $fileName = rand(11111, 99999) . '.' . $extension;
        // MOVE THE UPLOADED FILES TO THE DESTINATION DIRECTORY
        $upload_success = $file->move($destinationPath, $fileName);
//        $file = move_uploaded_file($_FILES['document']['name'],$destinationPath);
//        dd($upload_success);
        $cost = 0;
        $guardParwestId = $request->parwest_id;
        // date formate for month and year  => $guardSalaryMonth
        $guardSalaryMonth = $request->salary_month;
        // total days from monthly date formate amd last date in month
        $total_days = getDaysInMonthByDate($guardSalaryMonth);

        $guardSalaryMonthNo = getMonthByMonthlyDate($guardSalaryMonth);
        $guardSalaryYear = getYearByMonthlyDate($guardSalaryMonth);
        $startDate = setDate('01',$guardSalaryMonthNo,$guardSalaryYear);
        $endDate = setDate($total_days,$guardSalaryMonthNo,$guardSalaryYear);
//        dd($startDate);

//        echo $whatIWant;

        //$parameters to be sent back to sustain the form
        $parameters = [
            'startDate'=>$startDate,
            'endDate'=>$endDate,
            'guardParwestId'=>$guardParwestId,
        ];

        //finding guards id based on guards parwest id
        $guard = new Guards\Guards();
        $guard = $guard->where('parwest_id' , '=' ,$guardParwestId)->get();

        if(count($guard) == 0){
            return view('guards.attendance')->with(['parameters' => $parameters ,
                'invalid' => 'Selected parwest ID is invalid']);

        }
        else {
            $guardId = $guard[0]->id;
//            dd($guardId);

            //finding the deployments based on the dates
            $clientGuardAcciciationModel = new ClientGuardsAssociation();
            $guardDeployments = $clientGuardAcciciationModel->deployedGuardByIdForSalary('393', '2019-04-01', '2019-04-30');
            dd($guardDeployments);
            $number_of_days_worked = getDaysBetweenDates($startDate, $endDate);


            foreach ($guardDeployments as $deploy) {
                $cost = $cost + getCostByLocationRate($deploy->location_rate, $startDate, $deploy->end_date);
            }
//            dd($cost);
            $salary_data = array(
                'parwest_id' => $guardParwestId,
                'guard_id' => $guardId,
                'month' => $guardSalaryMonth,
                'regional_office_salary' => $request->regional_office_salary,
                'cwf_salary_deduction'=>$request->cwf_salary_deduction,
                'apsa_salary_deduction' => $request->apsa_salary_deduction,
                'special_branch_deductions' =>$request->special_branch_deductions,
                'total_salary' => $cost,
                'document' => $fileName,


            );

            $salary = new Guards\GuardSalaryModel();
            $salary = $salary->create($salary_data);
            dd($salary);

        }
    }

    public function guardAttendanceInExcel(Request $request){

        $searchResults = (object)(unserialize(base64_decode($request->guardsDataAndAttendanceEncoded)));
        $searchResults = json_decode(json_encode($searchResults), true);


        $fileName = $searchResults['guardDeployments'][0]['parwest_id'];



        Excel::create(/**
         * @param $excel
         */
            $fileName, function ($excel) use ($searchResults) {
            //dd($searchResults);
            $attendanceMonth = array();
            $serialArray = array();
            $sheetsArray = array();
            $sheetDayArray = array('01'=>'F', '02'=>'G', '03'=>'H', '04'=>'I', '05'=>'J', '06'=>'K', '07'=>'L', '08'=>'M',
                '09'=>'N', '10'=>'O', '11'=>'P', '12'=>'Q', '13'=>'R', '14'=>'S', '15'=>'T', '16'=>'U', '17'=>'V', '18'=>'W',
                '19'=>'X', '20'=>'Y', '21'=>'Z', '22'=>'AA', '23'=>'AB', '24'=>'AC', '25'=>'AD', '26'=>'AE', '27'=>'AF',
                '28'=>'AG', '29'=>'AH', '30'=>'AI', '31'=>'AJ');
            //find the numbebr of months between start date end date filters. and create a serailArray.
            $startDate = $searchResults['startDate']; //search filter start date
            $endDate = $searchResults['endDate']; // search filter end date

            $begin = new DateTime( $startDate );
            $end = new DateTime( $endDate );
            $end = $end->modify( '+1 day' );

            $interval = new DateInterval('P1M');
            $daterange = new DatePeriod($begin, $interval ,$end);


            foreach($daterange as $key=>$date){

                $attendanceMonth[$date->format('mY')] = $date->format('M-Y');
                $serialArray[$date->format('mY')] = $date->format('mY');
                $sheetsArray[$date->format('mY')] = array();

            }


            //iterate deployment entries
            foreach($searchResults['guardDeployments'] as $key=>$value){

                $dayNightRegularOvertimeTotal = 0;
                $deploymentEndDate = 0;
                $deploymentStartDate = 0;

                //setting start date
                $deploymentDate = explode(' ' ,$value['created_at']); //date of deployment available in db 'client_guard_association'
                if($startDate > $deploymentDate[0] ){

                    $deploymentStartDate =new DateTime($startDate) ;

                }else{
                    $deploymentStartDate = new DateTime($value['created_at']); //date of deployment available in db 'client_guard_association'
                }
//              setting end date

                if($value['end_date'] == NULL){

                    $carbonNow = explode(' ' ,carbon::now());

                    if($endDate >= $carbonNow[0]){
                        $deploymentEndDate = new DateTime($carbonNow[0]);
                        $deploymentEndDate = $deploymentEndDate->modify( '+1 day' );
                    }
                    elseif($endDate < $carbonNow[0]){

                        $deploymentEndDate = new DateTime($endDate);
                        $deploymentEndDate = $deploymentEndDate->modify( '+1 day' );
                    }

                }
                elseif($value['end_date'] != NULL){

                    $revoketDate = explode(' ' ,$value['end_date']);//revoke date available in db 'client_guard_association'
                    if($endDate > $revoketDate[0]){
                        $deploymentEndDate = new DateTime( $value['end_date']);
                        $deploymentEndDate = $deploymentEndDate->modify( '+1 day' );
                    }
                    else{
                        $deploymentEndDate = new DateTime($endDate);//revoke date available in db 'client_guard_association'
                        $deploymentEndDate = $deploymentEndDate->modify( '+1 day' );
                    }
                }


                //interval between two dates
                $interval = new DateInterval('P1D');
                $deploymentDateRange = new DatePeriod($deploymentStartDate, $interval ,$deploymentEndDate);

                //check that the deployment is not overtime
                if($value['is_overtime'] == 0 || true){
                    $currentSheet = 0;


                    foreach($deploymentDateRange as $counter => $date){
                        if($currentSheet != $date->format('mY')){

                            $currentSheet = $date->format('mY');
//                            $currentSheet = $date->format('m ([ \t.-])* YY');

                            $count = count($sheetsArray[$currentSheet]);
                            $sheetsArray[$currentSheet][$count] = array();
                            $sheetsArray[$currentSheet][$count]['A'.($count*4+10)] =  $count + 1 ;
                            $sheetsArray[$currentSheet][$count]['B'.($count*4+10)] = $searchResults['guardDeployments'][$key]['location_rate'] ;
                            $sheetsArray[$currentSheet][$count]['C'.($count*4+10)] = $searchResults['guardDeployments'][$key]['client_name'] ;
                            $sheetsArray[$currentSheet][$count]['D'.($count*4+10)] = $searchResults['guardDeployments'][$key]['branch_name'] ;
                            $sheetsArray[$currentSheet][$count]['E'.($count*4+10)] = "Day Regular" ;
                            $sheetsArray[$currentSheet][$count]['E'.($count*4+11)] = "Night Regular" ;
                            $sheetsArray[$currentSheet][$count]['E'.($count*4+12)] = "Day Double Duty" ;
                            $sheetsArray[$currentSheet][$count]['E'.($count*4+13)] = "Night Double Duty" ;
                            $sheetsArray[$currentSheet][$count]['AK'.($count*4+10)] = "Presents" ;
                            $sheetsArray[$currentSheet][$count]['AK'.($count*4+11)] = "Presents" ;
                            $sheetsArray[$currentSheet][$count]['AK'.($count*4+12)] = "Time" ;
                            $sheetsArray[$currentSheet][$count]['AK'.($count*4+13)] = "Time" ;
                            $sheetsArray[$currentSheet][$count]['AL'.($count*4+10)] = 0 ;
                            $sheetsArray[$currentSheet][$count]['AL'.($count*4+11)] = 0 ;
                            $sheetsArray[$currentSheet][$count]['AL'.($count*4+12)] = 0 ;
                            $sheetsArray[$currentSheet][$count]['AL'.($count*4+13)] = 0 ;
                            if(!isset($sheetsArray[$currentSheet][0]['AL9'])){
                                $sheetsArray[$currentSheet][0]['AL9'] = 0;
                            }
                            $sheetsArray[$currentSheet][$count]['AN'.($count*4+10)] = $searchResults['guardDeployments'][$key]['branch_supervisor']; ;
                            $sheetsArray[$currentSheet][$count]['AO'.($count*4+10)] = $searchResults['guardDeployments'][$key]['branch_manager'];

                            $sheetsArray[$currentSheet][$count]['A'.($count*4+10).':A'.($count*4+13)] = 0;
                            $sheetsArray[$currentSheet][$count]['B'.($count*4+10).':B'.($count*4+13)] = 0;
                            $sheetsArray[$currentSheet][$count]['C'.($count*4+10).':C'.($count*4+13)] = 0;
                            $sheetsArray[$currentSheet][$count]['D'.($count*4+10).':D'.($count*4+13)] = 0;
                            $sheetsArray[$currentSheet][$count]['AN'.($count*4+10).':AN'.($count*4+13)] = 0;
                            $sheetsArray[$currentSheet][$count]['AO'.($count*4+10).':AO'.($count*4+13)] = 0;
                            if(!isset($sheetsArray[$currentSheet][0]['E5'])) {
                                $sheetsArray[$currentSheet][0]['E5'] = 0;
                            }
                            if(!isset($sheetsArray[$currentSheet][0]['E6'])) {
                                $sheetsArray[$currentSheet][0]['E6'] = 0;
                            }
                            if(!isset($sheetsArray[$currentSheet][0]['E7'])) {
                                $sheetsArray[$currentSheet][0]['E7'] = 0;
                            }

                        }


                        if($value['is_overtime'] == 0) {
                            if ($searchResults['guardDeployments'][$key]['shift_day_night'] == 1) { //day regular
                                $sheetsArray[$currentSheet][$count][($sheetDayArray[$date->format('d')]) . ($count * 4 + 11)] = "A";
                                $sheetsArray[$currentSheet][$count][($sheetDayArray[$date->format('d')]) . ($count * 4 + 12)] = "A";
                                $sheetsArray[$currentSheet][$count][($sheetDayArray[$date->format('d')]) . ($count * 4 + 13)] = "A";
                                $sheetsArray[$currentSheet][$count][($sheetDayArray[$date->format('d')]) . ($count * 4 + 10)] = "P";
                                $sheetsArray[$currentSheet][$count]['AL' . ($count * 4 + 10)]++;
                                $sheetsArray[$currentSheet][0]['AL9']++;
                                $sheetsArray[$currentSheet][0]['E5']++;
                                $sheetsArray[$currentSheet][0]['E6']++;
                            } else {//night regular
                                $sheetsArray[$currentSheet][$count][($sheetDayArray[$date->format('d')]) . ($count * 4 + 10)] = "A";
                                $sheetsArray[$currentSheet][$count][($sheetDayArray[$date->format('d')]) . ($count * 4 + 12)] = "A";
                                $sheetsArray[$currentSheet][$count][($sheetDayArray[$date->format('d')]) . ($count * 4 + 13)] = "A";
                                $sheetsArray[$currentSheet][$count][($sheetDayArray[$date->format('d')]) . ($count * 4 + 11)] = "P";
                                $sheetsArray[$currentSheet][$count]['AL' . ($count * 4 + 11)]++;
                                $sheetsArray[$currentSheet][0]['AL9']++;
                                $sheetsArray[$currentSheet][0]['E5']++;
                                $sheetsArray[$currentSheet][0]['E6']++;
                            }
                        }else{

                            if ($searchResults['guardDeployments'][$key]['shift_day_night'] == 1) { //day overtime / doubleduty
                                $sheetsArray[$currentSheet][$count][($sheetDayArray[$date->format('d')]).($count * 4 + 12)] = "t";
                                $sheetsArray[$currentSheet][$count]['AL'.($count*4+12)]++;
                                $sheetsArray[$currentSheet][0]['AL9']++;
                                $sheetsArray[$currentSheet][0]['E5']++;
                                $sheetsArray[$currentSheet][0]['E7']++;

                            } else {//night overtime / doubleduty
                                $sheetsArray[$currentSheet][$count][($sheetDayArray[$date->format('d')]).($count * 4 + 13)] = "t";
                                $sheetsArray[$currentSheet][$count]['AL'.($count*4+13)]++;
                                //day night regular overtime counter increment
                                $sheetsArray[$currentSheet][0]['AL9']++;
                                $sheetsArray[$currentSheet][0]['E5']++;
                                $sheetsArray[$currentSheet][0]['E7']++;
                            }


                        }
                    }
                }
                else{

//                    $deploymentStartDate = new DateTime($value['created_at']);
//                    $deploymentEndDate = new DateTime( $value['end_date']);
//
//                    $deploymentEndDate = $deploymentEndDate->modify( '+1 day' );

                    if($value['end_date'] == NULL){

                        $carbonNow = explode(' ' ,carbon::now());

                        if($endDate > $carbonNow[0]){
                            $deploymentEndDate = new DateTime($carbonNow[0]);
                            $deploymentEndDate = $deploymentEndDate->modify( '+1 day' );
                        }
                        elseif($endDate < $carbonNow[0]){

                            $deploymentEndDate = new DateTime($endDate);
                            $deploymentEndDate = $deploymentEndDate->modify( '+1 day' );
                        }

                    }
                    elseif($value['end_date'] != NULL){

                        $revoketDate = explode(' ' ,$value['end_date']);//revoke date available in db 'client_guard_association'
                        if($endDate > $revoketDate[0]){
                            $deploymentEndDate = new DateTime( $value['end_date']);
                            $deploymentEndDate = $deploymentEndDate->modify( '+1 day' );
                        }
                        else{
                            $deploymentEndDate = new DateTime($endDate);//revoke date available in db 'client_guard_association'
                            $deploymentEndDate = $deploymentEndDate->modify( '+1 day' );
                        }
                    }


                    //interval between two dates
                    $interval = new DateInterval('P1D');
                    $deploymentDateRange = new DatePeriod($deploymentStartDate, $interval ,$deploymentEndDate);

                    //check that the deployment is not overtime
                    foreach($deploymentDateRange as $date) {
                        $currentSheet = 0;

                        if($currentSheet != $date->format('mY')) {

                            $currentSheet = $date->format('mY');
                        }

                        if ($searchResults['guardDeployments'][$key]['shift_day_night'] == 1) { //day overtime / doubleduty
                            $sheetsArray[$currentSheet][$count][($sheetDayArray[$date->format('d')]).($count * 4 + 12)] = "t";
                            $sheetsArray[$currentSheet][$count]['AL'.($count*4+12)]++;
                            $sheetsArray[$currentSheet][0]['AL9']++;
                            $sheetsArray[$currentSheet][0]['E5']++;
                            $sheetsArray[$currentSheet][0]['E7']++;

                        } else {//night overtime / doubleduty
                            $sheetsArray[$currentSheet][$count][($sheetDayArray[$date->format('d')]).($count * 4 + 13)] = "t";
                            $sheetsArray[$currentSheet][$count]['AL'.($count*4+13)]++;
                            //day night regular overtime counter increment
                            $sheetsArray[$currentSheet][0]['AL9']++;
                            $sheetsArray[$currentSheet][0]['E5']++;
                            $sheetsArray[$currentSheet][0]['E7']++;
                        }
                    }
                }
            }

            $excel->setTitle('Guards Attandance');
            $excel->setDescription('Guards Attandance');

            //center align
            $excel->getDefaultStyle()
                ->getAlignment()
                ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
//dd($sheetsArray);
            foreach ($sheetsArray as $sheetName=>$arraySheet) {
//                dd($arraySheet);
                $currentSheetName = DateTime::createFromFormat('mY', $serialArray[$sheetName])->format('M Y');
                $excel->sheet($currentSheetName, function ($sheet) use ($searchResults, $arraySheet,$attendanceMonth,$sheetName) {

                                //setting sheet fontname
                                $sheet->setStyle(array(
                                    'font' => array(
                                        'name' => 'Calibri',
                                    )
                                ));

                                $sheet->mergeCells('A1:AJ1');
                                $sheet->getStyle('A1:AJ1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                $sheet->cells('A1:AJ1', function ($cells) {

                                    $cells->setBackground('#000000');
                                    $cells->setFontColor('#ffffff');
                                    $cells->setFont(array(
                                        'size' => '20',
                                        'bold' => true
                                    ));

                                });

                                $sheet->cells('A2:A4', function ($cells) {
                                    $cells->setFont(array(
                                        'bold' => true
                                    ));

                                });
                                $sheet->cells('D2:D7', function ($cells) {
                                    $cells->setFont(array(
                                        'bold' => true
                                    ));

                                });


                                $sheet->row(1, array(
                                    'Attendance Month of: '.$attendanceMonth[$sheetName]

                                ));


                                $sheet->cells('E2:AJ2', function ($cells) {
                                    $cells->setBackground('#ffff00');

                                    //Set all borders (top, right, bottom, left)
                                    $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                    $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
                                    $cells->setFont(array(
                                        'size' => '16',
                                        'bold' => true
                                    ));
                                });
                                $sheet->mergeCells('e2:AJ2');
                                $sheet->row(2, array(
                                    'Manager Name:',$searchResults['guardDeployments'][0]['guard_manager_name'], "      ", $searchResults['guardDeployments'][0]['parwest_id'], $searchResults['guardDeployments'][0]['guard_Name']

                                ));


                                $sheet->cells('E3:AJ3', function ($cells) {
                                    $cells->setBackground('#bee0b4');
                                    $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                    $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

                                });
                                $sheet->mergeCells('e3:AJ3');
                                $sheet->row(3, array(
                                    'Supervisor Name:', $searchResults['guardDeployments'][0]['guard_supervisor_name'], "      ", 'Guard Status', $searchResults['guardDeployments'][0]['ex_service']

                                ));


                                $sheet->cells('E4:AJ4', function ($cells) {
                                    $cells->setBackground('#ffff00');
                                    $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                    $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);


                                });
                                $sheet->mergeCells('e4:AJ4');
                                $sheet->row(4, array(
                                    'Introducer Name:', $searchResults['guardDeployments'][0]['introducer'], "      ", 'Guard Status', $searchResults['guardDeployments'][0]['current_status_id']

                                ));


                                //total present
                                $sheet->cells('E5:AJ5', function ($cells) {
                                    $cells->setBackground('#ffff00');
                                    $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                    $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

                                });
                                $sheet->mergeCells('e5:AJ5');
                                $sheet->cell('D5', function ($cell) {
                                    $cell->setValue('Total Present');
                                });

//                                $sheet->cell('E5', function ($cell) {
//                                    $cell->setValue('some value');
//                                });


                                //regular duty
                                $sheet->cells('E6:AJ6', function ($cells) {
                                    $cells->setBackground('#ffff00');
                                    $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                    $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

                                });
                                $sheet->mergeCells('E6:AJ6');
                                $sheet->cell('D6', function ($cell) {
                                    $cell->setValue('Regular Duty');
                                });
//                                $sheet->cell('E6', function ($cell) {
//                                    $cell->setValue('some value');
//                                });


                                //double duty
                                $sheet->cells('E7:AJ7', function ($cells) {
                                    $cells->setBackground('#ffff00');
                                    $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                    $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

                                });
                                $sheet->mergeCells('E7:AJ7');
                                $sheet->cell('D7', function ($cell) {
                                    $cell->setValue('Double Duty');
                                });
//                                $sheet->cell('E7', function ($cell) {
//                                    $cell->setValue('some value');
//                                });


                                $sheet->cells('A9:AO9', function ($cells) {
                                    $cells->setFont(array(
                                        'bold' => true
                                    ));

                                    $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                    $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

                                });
                                $sheet->row(9, array(
                                    'Sr. #.', 'Location Rate', 'Client Name', 'Location', 'Shift', '1', '2', '3', '4',
                                    '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18',
                                    '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31',
                                    'Total', '        ', 'Remarks', 'Supervisor', 'Manager'
                                ));
                    foreach ($arraySheet as $key=>$clients) {

                                foreach ($clients as $keyClient => $valueClient) {




                                        $sheet->cell($keyClient, function($cell) use ($valueClient) {
                                            // manipulate the cell
                                            $cell->setValue($valueClient);

                                            //border
                                            $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                            $cell->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
                                        });


                                    if(preg_match('/\bDay Regular\b/', $valueClient)){
                                        $sheet->cells($keyClient, function ($cells) {
                                            $cells->setBackground('#fccf7c');
                                            $cells->setFont(array(
                                                'bold' => true
                                            ));

                                        });
                                    }

                                    elseif (preg_match('/\bNight Regular\b/', $valueClient)){

                                        $sheet->cells($keyClient, function ($cells) {
                                            $cells->setBackground('#eda521');
                                            $cells->setFont(array(
                                                'bold' => true
                                            ));

                                        });

                                    }
                                    elseif(preg_match('/\bDay Double Duty\b/', $valueClient)){

                                        $sheet->cells($keyClient, function ($cells) {
                                            $cells->setBackground('#00bbff');
                                            $cells->setFont(array(
                                                'bold' => true
                                            ));

                                        });

                                    }
                                    elseif (preg_match('/\bNight Double Duty\b/', $valueClient)){

                                        $sheet->cells($keyClient, function ($cells) {
                                            $cells->setBackground('#0099d1');
                                            $cells->setFont(array(
                                                'bold' => true
                                            ));

                                        });

                                    }
                                    elseif (preg_match('/\bPresents\b/', $valueClient)){

                                        $sheet->cells($keyClient, function ($cells) {
                                            $cells->setBackground('#bee0b4');
                                            $cells->setFont(array(
                                                'bold' => true
                                            ));

                                        });

                                    }
                                    elseif (preg_match('/\bTime\b/', $valueClient)){

                                        $sheet->cells($keyClient, function ($cells) {
                                            $cells->setBackground('#b4c6e7');
                                            $cells->setFont(array(
                                                'bold' => true
                                            ));

                                        });

                                    }
                                    elseif (preg_match('/\bP\b/', $valueClient)){

                                        $sheet->cells($keyClient, function ($cells) {
                                            $cells->setBackground('#00ff00');
                                            $cells->setFont(array(
                                                'bold' => true
                                            ));

                                        });

                                    }
                                    elseif (preg_match('/\bA\b/', $valueClient)){

                                        $sheet->cells($keyClient, function ($cells) {
                                            $cells->setBackground('##ff0000');
                                            $cells->setFont(array(
                                                'bold' => true
                                            ));

                                        });

                                    }
                                    elseif (preg_match('/\bt\b/', $valueClient)){

                                        $sheet->cells($keyClient, function ($cells) {
                                            $cells->setBackground('#94bdff');
                                            $cells->setFont(array(
                                                'bold' => true
                                            ));

                                        });

                                    }
                                    elseif(strpos($keyClient , ':') == true){
                                        $sheet->mergeCells($keyClient);
                                        $sheet->getStyle($keyClient)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                    }
                                }

                        }
                });
            }



        })->store('xlsx', Config::get('globalvariables.$pathToSaveTemporaryExcelFiles'));


        $fileNameToDownload = $fileName . '.xlsx';

        $data = array('fileNameToDownload' => $fileNameToDownload);

        return ['responseCode' => 1, 'responseStatus' => 'successful', 'message' => 'your file is ready, and will download in few seconds', 'data' => $data];

    }
    public function clientGuardAttendanceInExcel(Request $request){

        $searchResults = (object)(unserialize(base64_decode($request->guardsDataAndAttendanceEncoded)));
        $searchResults = json_decode(json_encode($searchResults), true);


        $fileName = $searchResults['guardDeployments'][0]['parwest_id'];



        Excel::create(/**
         * @param $excel
         */
            $fileName, function ($excel) use ($searchResults) {
            //dd($searchResults);
            $attendanceMonth = array();
            $serialArray = array();
            $sheetsArray = array();
            $sheetDayArray = array('01'=>'F', '02'=>'G', '03'=>'H', '04'=>'I', '05'=>'J', '06'=>'K', '07'=>'L', '08'=>'M',
                '09'=>'N', '10'=>'O', '11'=>'P', '12'=>'Q', '13'=>'R', '14'=>'S', '15'=>'T', '16'=>'U', '17'=>'V', '18'=>'W',
                '19'=>'X', '20'=>'Y', '21'=>'Z', '22'=>'AA', '23'=>'AB', '24'=>'AC', '25'=>'AD', '26'=>'AE', '27'=>'AF',
                '28'=>'AG', '29'=>'AH', '30'=>'AI', '31'=>'AJ');
            //find the numbebr of months between start date end date filters. and create a serailArray.
            $startDate = $searchResults['startDate']; //search filter start date
            $endDate = $searchResults['endDate']; // search filter end date

            $begin = new DateTime( $startDate );
            $end = new DateTime( $endDate );
            $end = $end->modify( '+1 day' );

            $interval = new DateInterval('P1M');
            $daterange = new DatePeriod($begin, $interval ,$end);


            foreach($daterange as $key=>$date){

                $attendanceMonth[$date->format('mY')] = $date->format('M-Y');
                $serialArray[$date->format('mY')] = $date->format('mY');
                $sheetsArray[$date->format('mY')] = array();

            }

            dd($searchResults);
            //iterate deployment entries
            foreach($searchResults['guardDeployments'] as $key=>$value){

                $dayNightRegularOvertimeTotal = 0;
                $deploymentEndDate = 0;
                $deploymentStartDate = 0;

                //setting start date
                $deploymentDate = explode(' ' ,$value['created_at']); //date of deployment available in db 'client_guard_association'
                if($startDate > $deploymentDate[0] ){

                    $deploymentStartDate =new DateTime($startDate) ;

                }else{
                    $deploymentStartDate = new DateTime($value['created_at']); //date of deployment available in db 'client_guard_association'
                }
//              setting end date

                if($value['end_date'] == NULL){

                    $carbonNow = explode(' ' ,carbon::now());

                    if($endDate >= $carbonNow[0]){
                        $deploymentEndDate = new DateTime($carbonNow[0]);
                        $deploymentEndDate = $deploymentEndDate->modify( '+1 day' );
                    }
                    elseif($endDate < $carbonNow[0]){

                        $deploymentEndDate = new DateTime($endDate);
                        $deploymentEndDate = $deploymentEndDate->modify( '+1 day' );
                    }

                }
                elseif($value['end_date'] != NULL){

                    $revoketDate = explode(' ' ,$value['end_date']);//revoke date available in db 'client_guard_association'
                    if($endDate > $revoketDate[0]){
                        $deploymentEndDate = new DateTime( $value['end_date']);
                        $deploymentEndDate = $deploymentEndDate->modify( '+1 day' );
                    }
                    else{
                        $deploymentEndDate = new DateTime($endDate);//revoke date available in db 'client_guard_association'
                        $deploymentEndDate = $deploymentEndDate->modify( '+1 day' );
                    }
                }


                //interval between two dates
                $interval = new DateInterval('P1D');
                $deploymentDateRange = new DatePeriod($deploymentStartDate, $interval ,$deploymentEndDate);

                //check that the deployment is not overtime
                if($value['is_overtime'] == 0 || true){
                    $currentSheet = 0;


                    foreach($deploymentDateRange as $counter => $date){
                        if($currentSheet != $date->format('mY')){

                            $currentSheet = $date->format('mY');
//                            $currentSheet = $date->format('m ([ \t.-])* YY');

                            $count = count($sheetsArray[$currentSheet]);
                            $sheetsArray[$currentSheet][$count] = array();
                            $sheetsArray[$currentSheet][$count]['A'.($count*4+10)] =  $count + 1 ;
                            $sheetsArray[$currentSheet][$count]['B'.($count*4+10)] = $searchResults['guardDeployments'][$key]['location_rate'] ;
                            $sheetsArray[$currentSheet][$count]['C'.($count*4+10)] = $searchResults['guardDeployments'][$key]['client_name'] ;
                            $sheetsArray[$currentSheet][$count]['D'.($count*4+10)] = $searchResults['guardDeployments'][$key]['branch_name'] ;
                            $sheetsArray[$currentSheet][$count]['E'.($count*4+10)] = "Day Regular" ;
                            $sheetsArray[$currentSheet][$count]['E'.($count*4+11)] = "Night Regular" ;
                            $sheetsArray[$currentSheet][$count]['E'.($count*4+12)] = "Day Double Duty" ;
                            $sheetsArray[$currentSheet][$count]['E'.($count*4+13)] = "Night Double Duty" ;
                            $sheetsArray[$currentSheet][$count]['AK'.($count*4+10)] = "Presents" ;
                            $sheetsArray[$currentSheet][$count]['AK'.($count*4+11)] = "Presents" ;
                            $sheetsArray[$currentSheet][$count]['AK'.($count*4+12)] = "Time" ;
                            $sheetsArray[$currentSheet][$count]['AK'.($count*4+13)] = "Time" ;
                            $sheetsArray[$currentSheet][$count]['AL'.($count*4+10)] = 0 ;
                            $sheetsArray[$currentSheet][$count]['AL'.($count*4+11)] = 0 ;
                            $sheetsArray[$currentSheet][$count]['AL'.($count*4+12)] = 0 ;
                            $sheetsArray[$currentSheet][$count]['AL'.($count*4+13)] = 0 ;
                            if(!isset($sheetsArray[$currentSheet][0]['AL9'])){
                                $sheetsArray[$currentSheet][0]['AL9'] = 0;
                            }
                            $sheetsArray[$currentSheet][$count]['AN'.($count*4+10)] = $searchResults['guardDeployments'][$key]['branch_supervisor']; ;
                            $sheetsArray[$currentSheet][$count]['AO'.($count*4+10)] = $searchResults['guardDeployments'][$key]['branch_manager'];

                            $sheetsArray[$currentSheet][$count]['A'.($count*4+10).':A'.($count*4+13)] = 0;
                            $sheetsArray[$currentSheet][$count]['B'.($count*4+10).':B'.($count*4+13)] = 0;
                            $sheetsArray[$currentSheet][$count]['C'.($count*4+10).':C'.($count*4+13)] = 0;
                            $sheetsArray[$currentSheet][$count]['D'.($count*4+10).':D'.($count*4+13)] = 0;
                            $sheetsArray[$currentSheet][$count]['AN'.($count*4+10).':AN'.($count*4+13)] = 0;
                            $sheetsArray[$currentSheet][$count]['AO'.($count*4+10).':AO'.($count*4+13)] = 0;
                            if(!isset($sheetsArray[$currentSheet][0]['E5'])) {
                                $sheetsArray[$currentSheet][0]['E5'] = 0;
                            }
                            if(!isset($sheetsArray[$currentSheet][0]['E6'])) {
                                $sheetsArray[$currentSheet][0]['E6'] = 0;
                            }
                            if(!isset($sheetsArray[$currentSheet][0]['E7'])) {
                                $sheetsArray[$currentSheet][0]['E7'] = 0;
                            }

                        }


                        if($value['is_overtime'] == 0) {
                            if ($searchResults['guardDeployments'][$key]['shift_day_night'] == 1) { //day regular
                                $sheetsArray[$currentSheet][$count][($sheetDayArray[$date->format('d')]) . ($count * 4 + 11)] = "A";
                                $sheetsArray[$currentSheet][$count][($sheetDayArray[$date->format('d')]) . ($count * 4 + 12)] = "A";
                                $sheetsArray[$currentSheet][$count][($sheetDayArray[$date->format('d')]) . ($count * 4 + 13)] = "A";
                                $sheetsArray[$currentSheet][$count][($sheetDayArray[$date->format('d')]) . ($count * 4 + 10)] = "P";
                                $sheetsArray[$currentSheet][$count]['AL' . ($count * 4 + 10)]++;
                                $sheetsArray[$currentSheet][0]['AL9']++;
                                $sheetsArray[$currentSheet][0]['E5']++;
                                $sheetsArray[$currentSheet][0]['E6']++;
                            } else {//night regular
                                $sheetsArray[$currentSheet][$count][($sheetDayArray[$date->format('d')]) . ($count * 4 + 10)] = "A";
                                $sheetsArray[$currentSheet][$count][($sheetDayArray[$date->format('d')]) . ($count * 4 + 12)] = "A";
                                $sheetsArray[$currentSheet][$count][($sheetDayArray[$date->format('d')]) . ($count * 4 + 13)] = "A";
                                $sheetsArray[$currentSheet][$count][($sheetDayArray[$date->format('d')]) . ($count * 4 + 11)] = "P";
                                $sheetsArray[$currentSheet][$count]['AL' . ($count * 4 + 11)]++;
                                $sheetsArray[$currentSheet][0]['AL9']++;
                                $sheetsArray[$currentSheet][0]['E5']++;
                                $sheetsArray[$currentSheet][0]['E6']++;
                            }
                        }else{

                            if ($searchResults['guardDeployments'][$key]['shift_day_night'] == 1) { //day overtime / doubleduty
                                $sheetsArray[$currentSheet][$count][($sheetDayArray[$date->format('d')]).($count * 4 + 12)] = "t";
                                $sheetsArray[$currentSheet][$count]['AL'.($count*4+12)]++;
                                $sheetsArray[$currentSheet][0]['AL9']++;
                                $sheetsArray[$currentSheet][0]['E5']++;
                                $sheetsArray[$currentSheet][0]['E7']++;

                            } else {//night overtime / doubleduty
                                $sheetsArray[$currentSheet][$count][($sheetDayArray[$date->format('d')]).($count * 4 + 13)] = "t";
                                $sheetsArray[$currentSheet][$count]['AL'.($count*4+13)]++;
                                //day night regular overtime counter increment
                                $sheetsArray[$currentSheet][0]['AL9']++;
                                $sheetsArray[$currentSheet][0]['E5']++;
                                $sheetsArray[$currentSheet][0]['E7']++;
                            }


                        }
                    }
                }
                else{

//                    $deploymentStartDate = new DateTime($value['created_at']);
//                    $deploymentEndDate = new DateTime( $value['end_date']);
//
//                    $deploymentEndDate = $deploymentEndDate->modify( '+1 day' );

                    if($value['end_date'] == NULL){

                        $carbonNow = explode(' ' ,carbon::now());

                        if($endDate > $carbonNow[0]){
                            $deploymentEndDate = new DateTime($carbonNow[0]);
                            $deploymentEndDate = $deploymentEndDate->modify( '+1 day' );
                        }
                        elseif($endDate < $carbonNow[0]){

                            $deploymentEndDate = new DateTime($endDate);
                            $deploymentEndDate = $deploymentEndDate->modify( '+1 day' );
                        }

                    }
                    elseif($value['end_date'] != NULL){

                        $revoketDate = explode(' ' ,$value['end_date']);//revoke date available in db 'client_guard_association'
                        if($endDate > $revoketDate[0]){
                            $deploymentEndDate = new DateTime( $value['end_date']);
                            $deploymentEndDate = $deploymentEndDate->modify( '+1 day' );
                        }
                        else{
                            $deploymentEndDate = new DateTime($endDate);//revoke date available in db 'client_guard_association'
                            $deploymentEndDate = $deploymentEndDate->modify( '+1 day' );
                        }
                    }


                    //interval between two dates
                    $interval = new DateInterval('P1D');
                    $deploymentDateRange = new DatePeriod($deploymentStartDate, $interval ,$deploymentEndDate);

                    //check that the deployment is not overtime
                    foreach($deploymentDateRange as $date) {
                        $currentSheet = 0;

                        if($currentSheet != $date->format('mY')) {

                            $currentSheet = $date->format('mY');
                        }

                        if ($searchResults['guardDeployments'][$key]['shift_day_night'] == 1) { //day overtime / doubleduty
                            $sheetsArray[$currentSheet][$count][($sheetDayArray[$date->format('d')]).($count * 4 + 12)] = "t";
                            $sheetsArray[$currentSheet][$count]['AL'.($count*4+12)]++;
                            $sheetsArray[$currentSheet][0]['AL9']++;
                            $sheetsArray[$currentSheet][0]['E5']++;
                            $sheetsArray[$currentSheet][0]['E7']++;

                        } else {//night overtime / doubleduty
                            $sheetsArray[$currentSheet][$count][($sheetDayArray[$date->format('d')]).($count * 4 + 13)] = "t";
                            $sheetsArray[$currentSheet][$count]['AL'.($count*4+13)]++;
                            //day night regular overtime counter increment
                            $sheetsArray[$currentSheet][0]['AL9']++;
                            $sheetsArray[$currentSheet][0]['E5']++;
                            $sheetsArray[$currentSheet][0]['E7']++;
                        }
                    }
                }
            }

            $excel->setTitle('Guards Attandance');
            $excel->setDescription('Guards Attandance');

            //center align
            $excel->getDefaultStyle()
                ->getAlignment()
                ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
//dd($sheetsArray);
            foreach ($sheetsArray as $sheetName=>$arraySheet) {
//                dd($arraySheet);
                $currentSheetName = DateTime::createFromFormat('mY', $serialArray[$sheetName])->format('M Y');
                $excel->sheet($currentSheetName, function ($sheet) use ($searchResults, $arraySheet,$attendanceMonth,$sheetName) {

                                //setting sheet fontname
                                $sheet->setStyle(array(
                                    'font' => array(
                                        'name' => 'Calibri',
                                    )
                                ));

                                $sheet->mergeCells('A1:AJ1');
                                $sheet->getStyle('A1:AJ1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                $sheet->cells('A1:AJ1', function ($cells) {

                                    $cells->setBackground('#000000');
                                    $cells->setFontColor('#ffffff');
                                    $cells->setFont(array(
                                        'size' => '20',
                                        'bold' => true
                                    ));

                                });

                                $sheet->cells('A2:A4', function ($cells) {
                                    $cells->setFont(array(
                                        'bold' => true
                                    ));

                                });
                                $sheet->cells('D2:D7', function ($cells) {
                                    $cells->setFont(array(
                                        'bold' => true
                                    ));

                                });


                                $sheet->row(1, array(
                                    'Attendance Month of: '.$attendanceMonth[$sheetName]

                                ));


                                $sheet->cells('E2:AJ2', function ($cells) {
                                    $cells->setBackground('#ffff00');

                                    //Set all borders (top, right, bottom, left)
                                    $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                    $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
                                    $cells->setFont(array(
                                        'size' => '16',
                                        'bold' => true
                                    ));
                                });
                                $sheet->mergeCells('e2:AJ2');
                                $sheet->row(2, array(
                                    'Manager Name:',$searchResults['guardDeployments'][0]['guard_manager_name'], "      ", $searchResults['guardDeployments'][0]['parwest_id'], $searchResults['guardDeployments'][0]['guard_Name']

                                ));


                                $sheet->cells('E3:AJ3', function ($cells) {
                                    $cells->setBackground('#bee0b4');
                                    $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                    $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

                                });
                                $sheet->mergeCells('e3:AJ3');
                                $sheet->row(3, array(
                                    'Supervisor Name:', $searchResults['guardDeployments'][0]['guard_supervisor_name'], "      ", 'Guard Status', $searchResults['guardDeployments'][0]['ex_service']

                                ));


                                $sheet->cells('E4:AJ4', function ($cells) {
                                    $cells->setBackground('#ffff00');
                                    $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                    $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);


                                });
                                $sheet->mergeCells('e4:AJ4');
                                $sheet->row(4, array(
                                    'Introducer Name:', $searchResults['guardDeployments'][0]['introducer'], "      ", 'Guard Status', $searchResults['guardDeployments'][0]['current_status_id']

                                ));


                                //total present
                                $sheet->cells('E5:AJ5', function ($cells) {
                                    $cells->setBackground('#ffff00');
                                    $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                    $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

                                });
                                $sheet->mergeCells('e5:AJ5');
                                $sheet->cell('D5', function ($cell) {
                                    $cell->setValue('Total Present');
                                });

//                                $sheet->cell('E5', function ($cell) {
//                                    $cell->setValue('some value');
//                                });


                                //regular duty
                                $sheet->cells('E6:AJ6', function ($cells) {
                                    $cells->setBackground('#ffff00');
                                    $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                    $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

                                });
                                $sheet->mergeCells('E6:AJ6');
                                $sheet->cell('D6', function ($cell) {
                                    $cell->setValue('Regular Duty');
                                });
//                                $sheet->cell('E6', function ($cell) {
//                                    $cell->setValue('some value');
//                                });


                                //double duty
                                $sheet->cells('E7:AJ7', function ($cells) {
                                    $cells->setBackground('#ffff00');
                                    $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                    $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

                                });
                                $sheet->mergeCells('E7:AJ7');
                                $sheet->cell('D7', function ($cell) {
                                    $cell->setValue('Double Duty');
                                });
//                                $sheet->cell('E7', function ($cell) {
//                                    $cell->setValue('some value');
//                                });


                                $sheet->cells('A9:AO9', function ($cells) {
                                    $cells->setFont(array(
                                        'bold' => true
                                    ));

                                    $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                    $cells->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);

                                });
                                $sheet->row(9, array(
                                    'Sr. #.', 'Location Rate', 'Client Name', 'Location', 'Shift', '1', '2', '3', '4',
                                    '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18',
                                    '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31',
                                    'Total', '        ', 'Remarks', 'Supervisor', 'Manager'
                                ));
                    foreach ($arraySheet as $key=>$clients) {

                                foreach ($clients as $keyClient => $valueClient) {




                                        $sheet->cell($keyClient, function($cell) use ($valueClient) {
                                            // manipulate the cell
                                            $cell->setValue($valueClient);

                                            //border
                                            $thinBorder = \PHPExcel_Style_Border::BORDER_THIN;
                                            $cell->setBorder($thinBorder, $thinBorder, $thinBorder, $thinBorder);
                                        });


                                    if(preg_match('/\bDay Regular\b/', $valueClient)){
                                        $sheet->cells($keyClient, function ($cells) {
                                            $cells->setBackground('#fccf7c');
                                            $cells->setFont(array(
                                                'bold' => true
                                            ));

                                        });
                                    }

                                    elseif (preg_match('/\bNight Regular\b/', $valueClient)){

                                        $sheet->cells($keyClient, function ($cells) {
                                            $cells->setBackground('#eda521');
                                            $cells->setFont(array(
                                                'bold' => true
                                            ));

                                        });

                                    }
                                    elseif(preg_match('/\bDay Double Duty\b/', $valueClient)){

                                        $sheet->cells($keyClient, function ($cells) {
                                            $cells->setBackground('#00bbff');
                                            $cells->setFont(array(
                                                'bold' => true
                                            ));

                                        });

                                    }
                                    elseif (preg_match('/\bNight Double Duty\b/', $valueClient)){

                                        $sheet->cells($keyClient, function ($cells) {
                                            $cells->setBackground('#0099d1');
                                            $cells->setFont(array(
                                                'bold' => true
                                            ));

                                        });

                                    }
                                    elseif (preg_match('/\bPresents\b/', $valueClient)){

                                        $sheet->cells($keyClient, function ($cells) {
                                            $cells->setBackground('#bee0b4');
                                            $cells->setFont(array(
                                                'bold' => true
                                            ));

                                        });

                                    }
                                    elseif (preg_match('/\bTime\b/', $valueClient)){

                                        $sheet->cells($keyClient, function ($cells) {
                                            $cells->setBackground('#b4c6e7');
                                            $cells->setFont(array(
                                                'bold' => true
                                            ));

                                        });

                                    }
                                    elseif (preg_match('/\bP\b/', $valueClient)){

                                        $sheet->cells($keyClient, function ($cells) {
                                            $cells->setBackground('#00ff00');
                                            $cells->setFont(array(
                                                'bold' => true
                                            ));

                                        });

                                    }
                                    elseif (preg_match('/\bA\b/', $valueClient)){

                                        $sheet->cells($keyClient, function ($cells) {
                                            $cells->setBackground('##ff0000');
                                            $cells->setFont(array(
                                                'bold' => true
                                            ));

                                        });

                                    }
                                    elseif (preg_match('/\bt\b/', $valueClient)){

                                        $sheet->cells($keyClient, function ($cells) {
                                            $cells->setBackground('#94bdff');
                                            $cells->setFont(array(
                                                'bold' => true
                                            ));

                                        });

                                    }
                                    elseif(strpos($keyClient , ':') == true){
                                        $sheet->mergeCells($keyClient);
                                        $sheet->getStyle($keyClient)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                    }
                                }

                        }
                });
            }



        })->store('xlsx', Config::get('globalvariables.$pathToSaveTemporaryExcelFiles'));


        $fileNameToDownload = $fileName . '.xlsx';

        $data = array('fileNameToDownload' => $fileNameToDownload);

        return ['responseCode' => 1, 'responseStatus' => 'successful', 'message' => 'your file is ready, and will download in few seconds', 'data' => $data];

    }


    public function guardAttendanceInExcelDownload($filename){

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
    public function clientGuardAttendanceInExcelDownload($filename){

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

    //save record for extra guard demand on a location
    public function extraGuardsDemand(Request $request){
//dd($request);
        $clientBranchExtraGuardDemands = new ClientBranchesExtraGuardDemands();
        $clientBranchExtraGuardDemands->saveDemand($request);

        $date = explode(' ' ,$request->date );
        $data = [
            'comment' => $request->comment,
            'date' => $date[0]
        ];
        return ['responseCode' => 1, 'responseStatus' => 'Successful',
            'message' => 'Extra Guard Comment added successfully' , 'data' => $data];

    }
//testing
    //retrieve all records for extra guard demand
    public function extraGuardsDemandHistory(Request $request){

        $clientBranchExtraGuardDemands = new ClientBranchesExtraGuardDemands();
        $data = $clientBranchExtraGuardDemands->showDemand($request);
//dd($data);
        return ['responseCode' => 1, 'responseStatus' => 'Successful',
            'message' => 'Extra Guard Comment Retrieved Successfully' , 'data' => $data ];

    }

    public  function getGuardDetailsUNpaid(Request $request){
        $user_id = Auth::guard('user')->id();
        $input = $request->all();
        $status = false;
//supervisor_id
        $guard_salary_status = new Guards\GuardUnpaidSalariesModel();
        $guard_salary_status = $guard_salary_status->getModelByParwestId($input['parwest_id']);

        $guard = new Guards\Guards();
        $guard = $guard->getByParwestId($input['parwest_id']);
        $guard_name = $guard['name'];
        $payrollHistory = new Guards\GuardSalaryModel();
        $payrollHistory = $payrollHistory->getModelByParwestId($input['parwest_id']);
        foreach ($payrollHistory as $check){
            if($check['manager_id'] == $user_id ||  $check['supervisor_id'] == $user_id){
                $status = true;
            }else{
                $status = false;

            }
        }


        $data = [
            'guard' => $guard_salary_status,
            'status' => $status,
            'guard_name' => $guard_name,

        ];

        return ['responseCode' => 1, 'responseStatus' => 'Successful',
            'message' => 'Guard Retrieved Sucessfully' , 'data' => $data];



    }
    public  function getGuardDetails(Request $request){
        $input = $request->all();
        $guard = Guards\Guards::getModelByParwestId($input['parwest_id']);
//        $loan = Guards\GuardLoansModel::getModelByParwestId($input['parwest_id']);
        $loan = new Guards\GuardLoansModel();
        $loan = $loan->getModelByParwestId($input['parwest_id']);


        $supervisors = new ManagerSupervisorAssociation();
        $supervisors = $supervisors->getSupervisorsAgainstManager();

        $data = [
            'guard' => $guard,
            'supervisors' => $supervisors,
            'loan' => $loan
        ];

        return ['responseCode' => 1, 'responseStatus' => 'Successful',
            'message' => 'Guard Retrieved Sucessfully' , 'data' => $data];



    }
    public  function getClientBranchesLoan(Request $request){
        $branches = new ClientBranchesModel();
        $branches = $branches->getModelsByClientId($request->client_id);
        $data = [
            'branches' => $branches
        ];

        return ['responseCode' => 1, 'responseguardClearanceResultStatus' => 'Successful',
            'message' => 'Guard Retrieved Sucessfully' , 'data' => $data];
    }
    public  function getRecentGuardDetails(Request $request){

        $number_of_day = 0;
        $input = $request->all();
        $client= "";
        $client_id= "";
        $branch= "";
        $branch_id= "";
        $branchSupervisor= "";
        $endDateDeploy= "";
        if($input['parwest_id']){
//            $guard = Guards\Guards::getModelByParwestId($input['parwest_id']);
//            return $guard;
            try {
                $guard = Guards\Guards::getModelByParwestId($input['parwest_id']);
                if(count($guard) >  0){
//                    foreach ($guard as )
                    $guardId = $guard[0]->id;
//                    return $guardId;
                }else{
                    return ['responseCode' => 2, 'responseStatus' => 'Successful',
                        'message' => 'Guard Not Exists' ];
                }
            }

//catch exception
            catch(Exception $e) {
//                echo 'Message: ' .$e->getMessage();
                return ['responseCode' => 2, 'responseStatus' => 'Successful',
                    'message' => 'Guard Not Exists' ];
            }

            if($input['month']){

                $month_no =  $this->getMonthByDate($input['month']);
                $loan = new Guards\GuardLoansModel();
                $loan = $loan->getModelByMonth($input['month'],$input['parwest_id']);
            }
//            $loan = $month_no;
//            $loan = new Guards\GuardLoansModel();
//            $loan = $loan->getModelByParwestId($input['parwest_id']);


//            return $guard;
//            dd($guard);


        }else{
            $guard = '';
            $loan = '';
            $client = '';
            $client_id = '';
            $branch = '';
            $branch_id = '';
            $branchSupervisor = '';
        }
        if($input['month']){

//            $month_number = getMonthByMonthlyDate($input['month']);
            $startDate = $this->getStartDateByMonthDate($input['month']);
            $endDate = $this->getEndDateByMonthDate($input['month']);
            $clientGuardAcciciationModel = new ClientGuardsAssociation();
            $guardDeployments = $clientGuardAcciciationModel->deployedGuardByMonthForLoan($guardId, $startDate, $endDate);
//        $day = getDaysBetweenDates($guardDeployments[0]['created_at'],$guardDeployments[0]['end_date']);
            foreach ($guardDeployments as $guardDeployment){
//
                if($guardDeployment->end_date == null){
                    $endDateDeploy = $endDate;
//                    $endDateDeploy = $endDate;
                }else{
                    $endDateDeploy = $guardDeployment->end_date ;
                }
//                $number_of_day  = $number_of_day + getDaysBetweenDates($guardDeployment->created_at,$endDateDeploy) +1;
//                $number_of_day  = $number_of_day + getDaysBetweenDates($guardDeployment->created_at,$endDateDeploy) ;
                $number_of_day  = $number_of_day + $this->getDaysBetweenDates($startDate,$endDateDeploy) ;
//            $number_of_day  = $guardDeployment;
//
                $client = $guardDeployment->client_name;
                $client_id = $guardDeployment->client_id;
                $branch_id = $guardDeployment->branch_id;
                $branch = $guardDeployment->branch_name;
                $branchSupervisor = $guardDeployment->branch_supervisor;
            }


        }else{
            $number_of_day = " ";
//            $guardDeployment = "";
            $client = "";
            $client_id = "";
            $branch = "";
            $branch_id = "";
            $branchSupervisor = "";
            $guard = '';
            $loan = '';
        }

        $first_day_this_month = date('Y-m-01'); // hard-coded '01' for first day
        $last_day_this_month  = date('Y-m-t');



//        $guard = new Guards\Guards();
//        $guard = $guard->where('parwest_id' , '=' ,$input['parwest_id'])->get();
//        $guardId = $guard[0]->id;
//        $loan = Guards\GuardLoansModel::getModelByParwestId($input['parwest_id']);
//        $clientGuardAcciciationModel = new ClientGuardsAssociation();
//        $guardDeployments = $clientGuardAcciciationModel->deployedLastGuardByIdForLoan($guardId);
////            dd($guardDeployments);


//        $supervisors = new ManagerSupervisorAssociation();
//        $supervisors = $supervisors->getSupervisorsAgainstManager();
//        $number_of_day = getDaysBetweenDates('2019-10-01 00:00:00','2019-10-10 00:00:00');
        $data = [
            'guard' => $guard,
            'guardDeployments' => $guardDeployments,
            'endDateDeploy' => $endDateDeploy,
            'days' => abs($number_of_day),
            'client_name' => $client,
            'client_id' => $client_id,
            'branch_name' => $branch,
            'branch_id' => $branch_id,
            'branch_supervisor' => $branchSupervisor,
            'loan' => $loan,
            'start_date' => $startDate,
            'end_date' => $endDateDeploy

        ];
        // check for loan finalised in this month
        // check add if amount in zero or deployment days are zero
        //add tolltip about currently deployed and status of guard
        //

        return ['responseCode' => 1, 'responseStatus' => 'Successful',
            'message' => 'Guard Retrieved Sucessfully' , 'data' => $data];



    }
    public  function getRecentGuardDetailsClearance(Request $request){

        $guard = new Guards\Guards();
        $guard = $guard::where('parwest_id',$request->parwest_id)->first();

        if($guard){
            $guard_status = new Guards\GuardStatusesModel();
            $guard_status = $guard_status->find($guard->current_status_id);
            $guard->status_name = $guard_status->value;

            $location = new ClientGuardsAssociation();
            $location = $location->getLocationByGuardId($guard->id);

            $inventory = new ClientGuardsAssociation();
            $inventory = $inventory->getInventoryByGuardId($guard->id);
            if($location){

                $guard->location = $location->client_name." ".$location->branch_name;
            }else{
                $guard->location = 'Not Deployed ';
            }
            $allDesignations = new Guards\GuardDesignationModel();
            $allDesignations = $allDesignations->getAll();

            $loan = new GuardLoansModel();
            $loan = $loan->getModelByGuardWithIntervalGuard($request->parwest_id);
            $guard->loan = $loan;
            $guard->inventory = $inventory[0]->totalitems;
            foreach($allDesignations as $allDes)
            {
                if($allDes->id == $guard->designation)
                {
                    $guard->designation_name = strtoupper($allDes->name);
                }
            }
            return ['responseCode' => 1, 'responseStatus' => 'Successful',
                'message' => 'Guard Retrieved Sucessfully' , 'data' => $guard];


        }

    }
    public  function getRecentGuardDetailsForExtraHours(Request $request){

        $number_of_day = 0;
        $input = $request->all();
        $client= "";
        $branch= "";
        $branchSupervisor= "";
        if($input['parwest_id']){
            $loan = new Guards\GuardLoansModel();
            $loan = $loan->getModelByParwestId($input['parwest_id']);

            $guard = Guards\Guards::getModelByParwestId($input['parwest_id']);
            $guardId = $guard[0]->id;
//            return ['responseCode' => 1, 'responseStatus' => 'Successful',
//                'message' => 'Guard Retrieved Sucessfully' , 'data' => $guardId];

        }else{
            $guard = '';
            $loan = '';
            $client = '';
            $branch = '';
            $branchSupervisor = '';
        }
        if($input['month']){

            $month_number = getMonthByMonthlyDate($input['month']);
            $startDate = getStartDateByMonthDate($input['month']);
            $endDate = getEndDateByMonthDate($input['month']);
            $clientGuardAcciciationModel = new ClientGuardsAssociation();
            $guardDeployments = $clientGuardAcciciationModel->deployedGuardByMonthForLoan($guardId, $startDate, $endDate);
//        $day = getDaysBetweenDates($guardDeployments[0]['created_at'],$guardDeployments[0]['end_date']);
            foreach ($guardDeployments as $guardDeployment){
//
//                $number_of_day  = $number_of_day + getDaysBetweenDates($guardDeployment->created_at,$guardDeployment->end_date) +1;
//            $number_of_day  = $guardDeployment;
//
                $client = $guardDeployment->client_name;
                $branch = $guardDeployment->branch_name;
//                $branchSupervisor = $guardDeployment->branch_supervisor;
            }


        }else{
            $number_of_day = " ";
//            $guardDeployment = "";
            $client = "";
            $branch = "";
            $branchSupervisor = "";
            $guard = '';
            $loan = '';
        }

        $first_day_this_month = date('Y-m-01'); // hard-coded '01' for first day
        $last_day_this_month  = date('Y-m-t');



//        $guard = new Guards\Guards();
//        $guard = $guard->where('parwest_id' , '=' ,$input['parwest_id'])->get();
//        $guardId = $guard[0]->id;
//        $loan = Guards\GuardLoansModel::getModelByParwestId($input['parwest_id']);
//        $clientGuardAcciciationModel = new ClientGuardsAssociation();
//        $guardDeployments = $clientGuardAcciciationModel->deployedLastGuardByIdForLoan($guardId);
////            dd($guardDeployments);


//        $supervisors = new ManagerSupervisorAssociation();
//        $supervisors = $supervisors->getSupervisorsAgainstManager();
        $ex_services = null;
        $guard = new Guards\Guards();
        $guard_status = new Guards\GuardStatusesModel();
        $guard_exx = new GuardExServices();

        $guard = $guard::where('parwest_id',$request->parwest_id)->first();
        if(isset($guard->id)) {
            $guard_status = $guard_status->find($guard->current_status_id);
            $guard->status_name = $guard_status->value;
            $ex_services = $guard_exx->find($guard->ex);
        }
        $data = [
            'guard' => $guard,
//            'days' => $number_of_day,
            'client_name' => $client,
            'branch_name' => $branch,
            'guard_type' => $ex_services,
//            'branch_supervisor' => $branchSupervisor,
//            'loan' => $loan
        ];

        return ['responseCode' => 1, 'responseStatus' => 'Successful',
            'message' => 'Guard Retrieved Sucessfully' , 'data' => $data];



    }
    public  function getGuardsDeploymentData(Request $request){
        $input = $request->all();
        $guard = Guards\Guards::getModelByParwestId($input['parwest_id']);
        $month_number = getMonthByMonthlyDate($input['month']);
        $startDate = getStartDateByMonthDate($input['month']);
        $endDate = getEndDateByMonthDate($input['month']);
//        $clientGuardAcciciationModel = new ClientGuardsAssociation();
//        $guardDeployments = $clientGuardAcciciationModel->deployedGuardByIdForClearance($guardId, $startDate, $endDate);
//            dd($guardDeployments);
        $first_day_this_month = date('Y-$month_number-01'); // hard-coded '01' for first day
        $last_day_this_month  = date('Y-m-t');
//
//        $guardSalaryMonth = $input['month'];
////        dd($guardSalaryMonth);
//// total days from monthly date formate amd last date in month
//        $total_days = getDaysInMonthByDate($guardSalaryMonth);
//
//        $guardSalaryMonthNo = getMonthByMonthlyDate($guardSalaryMonth);
//        $guardSalaryYear = getYearByMonthlyDate($guardSalaryMonth);
//        $startDate = setDate('01',$guardSalaryMonthNo,$guardSalaryYear);
//        $endDate = setDate($total_days,$guardSalaryMonthNo,$guardSalaryYear);

//        $guard = new Guards\Guards();
//        $guard = $guard->where('parwest_id' , '=' ,$input['parwest_id'])->get();
//        $guardId = $guard[0]->id;
//        $loan = Guards\GuardLoansModel::getModelByParwestId($input['parwest_id']);
//        $clientGuardAcciciationModel = new ClientGuardsAssociation();
//        $guardDeployments = $clientGuardAcciciationModel->deployedLastGuardByIdForLoan($guardId);
////            dd($guardDeployments);
//        $loan = new Guards\GuardLoansModel();
//        $loan = $loan->getModelByParwestId($input['parwest_id']);


//        $supervisors = new ManagerSupervisorAssociation();
//        $supervisors = $supervisors->getSupervisorsAgainstManager();

        $data = [
//            'guard' => $guard,
//            'supervisors' => $supervisors,
//            'loan' => $loan
        ];

        return ['responseCode' => 1, 'responseStatus' => 'Successful',
            'message' => 'Guard Retrieved Sucessfully' , 'data' => $guard];



    }
    public  function getLoanDetails(Request $request){
        $input = $request->all();
//        return $input;
//        $guard = Guards\Guards::getModelByParwestId($input['parwest_id']);

//        $loan = Guards\GuardLoansModel::getModelByParwestId($input['parwest_id']);
        $loan = new Guards\GuardLoansModel();
//        $loan = $loan->getModelByParwestId($input['id']);
        $loan = $loan->getModelById($input['id']);


//        $supervisors = new ManagerSupervisorAssociation();
//        $supervisors = $supervisors->getSupervisorsAgainstManager();

//        $data = [
//            'guard' => $guard,
//            'supervisors' => $supervisors,
//            'loan' => $loan
//        ];

        return ['responseCode' => 1, 'responseStatus' => 'Successful',
            'message' => 'Loan Retrieved Sucessfully' , 'data' => $loan];



    }





}
