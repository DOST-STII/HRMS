<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your RecruitmentControllertion. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use PhpParser\Parser\Multiple;

Auth::routes();

Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index');
// Route::post('/', 'HomeController@index');
Route::get('home/{mon}/{yr}/{empid}/{weeknum}', 'HomeController@index2');
Route::get('home3/{mon}/{yr}/{empid}/{weeknum}', 'HomeController@index3');


/***********PERSONNEL INFORMATION***********/

Route::get('personal-information/{tab}/{content}', 'PersonnelInformation\StaffController@index');

//CALENDAR
Route::get('calendar', 'Calendar\CalendarController@index');


/*------ADMINISTRATOR PAGES------*/
Route::get('admin/dashboard/{division}', 'PersonnelInformation\AdminController@dashboard');
Route::get('list-of-employees', 'PersonnelInformation\AdminController@index');
Route::get('archived-employees', 'PersonnelInformation\AdminController@archived');
Route::get('list-of-applicants/{plantilla}', 'PersonnelInformation\AdminController@applicants');
Route::get('dashboard-employee/{div}', 'PersonnelInformation\EmployeeAdminController@dashboard');
Route::get('dashboard-staff/{div}', 'PersonnelInformation\EmployeeAdminController@dashboard2');
Route::get('add-new-employee', 'PersonnelInformation\EmployeeAdminController@index');
Route::get('retiree/{div}', 'PersonnelInformation\AdminController@retiree');
Route::get('update-employee/{id}', 'PersonnelInformation\EmployeeAdminController@updateview');
Route::get('service-record', 'PersonnelInformation\AdminController@servicerecord');
Route::get('pis-library/{tab}', 'PersonnelInformation\AdminController@library');
Route::get('contract-of-service', 'PersonnelInformation\AdminController@jos');
Route::get('retiree/terminal-leave/{userid}', 'PersonnelInformation\AdminController@terminalv');
Route::get('payroll/benefits', 'Payroll\Benefit@index');
Route::post('payroll/benefit-deduc', 'Payroll\Benefit@create');
Route::post('payroll/benefit-remove', 'Payroll\Benefit@removeBenefit');
Route::post('payroll/benefit-process', 'Payroll\Benefit@process');
Route::post('payroll/benefit-print', 'Payroll\Benefit@print');

Route::get('invites/pdf/{id}', 'PersonnelInformation\PDFController@invites');

Route::post('hire-new-employee', 'PersonnelInformation\EmployeeAdminController@hire');
Route::post('hire-transfer-employee', 'PersonnelInformation\EmployeeAdminController@transferhire');
Route::post('add-new-hire-employee', 'PersonnelInformation\EmployeeAdminController@newhire');

Route::post('reverse-dtr', 'Maintenance\Maintenance@reversedtr');

//REQUEST FOR HIRING
Route::get('letter-of-request-list', 'PersonnelInformation\AdminController@requestHiring');
Route::post('request-for-hiring/action', 'PersonnelInformation\AdminController@requestAction');
Route::post('request-for-hiring/send-to-psb', 'PersonnelInformation\AdminController@requestAction2');
Route::post('request-for-hiring/approve', 'PersonnelInformation\AdminController@requestApprove');
Route::post('request-for-hiring/upload-psb', 'PersonnelInformation\AdminController@uploadpsb');

//APPLICANT
Route::post('applicants/upload-psycho', 'PersonnelInformation\AdminController@uploadPsycho');


//INVITATION
Route::get('invitation/select/{id}', 'PersonnelInformation\InvitationController@select');
Route::post('invitation/create', 'PersonnelInformation\InvitationController@create');
Route::post('invitation/delete', 'PersonnelInformation\InvitationController@delete');
Route::post('invitation/update', 'PersonnelInformation\InvitationController@update');
Route::post('invitation/assign', 'PersonnelInformation\InvitationController@assign');
Route::get('invitation/json/{id}', 'PersonnelInformation\InvitationController@json');
Route::post('invitation/preview-list', 'PersonnelInformation\PDFController@previewInvitation');

//PLANTILLA
Route::get('vacant-position', 'PersonnelInformation\PlantillaController@index');
Route::post('plantilla/create', 'PersonnelInformation\PlantillaController@create');
Route::post('plantilla/delete', 'PersonnelInformation\PlantillaController@delete');
Route::post('plantilla/update', 'PersonnelInformation\PlantillaController@update');
Route::post('plantilla/assign', 'PersonnelInformation\PlantillaController@assign');
Route::post('plantilla/repost', 'PersonnelInformation\PlantillaController@repost');
Route::get('plantilla/json/{id}', 'PersonnelInformation\PlantillaController@json');


//POST EMPLOYEE
Route::post('employee/create', 'PersonnelInformation\EmployeeAdminController@create');
Route::post('employee/delete', 'PersonnelInformation\EmployeeAdminController@delete');
Route::post('employee/update', 'PersonnelInformation\EmployeeAdminController@update');

//POST DIVISION
Route::post('division/create', 'PersonnelInformation\PISLibraryDivisionController@create');
Route::post('division/delete', 'PersonnelInformation\PISLibraryDivisionController@delete');
Route::post('division/update', 'PersonnelInformation\PISLibraryDivisionController@update');

//POST POSITION
Route::post('position/create', 'PersonnelInformation\PISLibraryPositionController@create');
Route::post('position/delete', 'PersonnelInformation\PISLibraryPositionController@delete');
Route::post('position/update', 'PersonnelInformation\PISLibraryPositionController@update');

//POST DESIGNATION
Route::post('designation/create', 'PersonnelInformation\PISLibraryDesignationController@create');
Route::post('designation/delete', 'PersonnelInformation\PISLibraryDesignationController@delete');
Route::post('designation/update', 'PersonnelInformation\PISLibraryDesignationController@update');

//POST EMPLOYMENT
Route::post('employment/create', 'PersonnelInformation\PISLibraryEmploymentController@create');
Route::post('employment/delete', 'PersonnelInformation\PISLibraryEmploymentController@delete');
Route::post('employment/update', 'PersonnelInformation\PISLibraryEmploymentController@update');

//POST EMPLOYMENT
Route::post('salary/upload', 'PersonnelInformation\PISLibrarySalary@upload');

//RESET PASSWORD
Route::post('reset-password', 'PersonnelInformation\EmployeeAdminController@resetpassword');

//CHANGE STATUS
Route::post('change-status', 'PersonnelInformation\EmployeeAdminController@changestatus');

//TRANSFER
Route::post('transfer-employee', 'PersonnelInformation\EmployeeAdminController@transfer');


//LEAVE
Route::get('leave/json/{id}', 'JSON@leave');


//PDF REPORT
// Route::get('pdf-print', 'PersonnelInformation\PDFController@index');
Route::post('pdf/service-record', 'PersonnelInformation\PDFController@servicerecord');
Route::get('pdf/pds/', 'PersonnelInformation\PDFController@pds');
Route::get('position-classification/{division}/{class}', 'PersonnelInformation\PDFController@positionClass');
Route::get('position-description/{division}/{class}', 'PersonnelInformation\PDFController@positionDesc');
Route::get('trainings-list/{division}', 'PersonnelInformation\PDFController@trainingsList');
Route::get('employee-education/{division}/{class}', 'PersonnelInformation\PDFController@educationClass');

//POST TRAINING
Route::post('training/create', 'PersonnelInformation\TrainingController@create');
Route::post('training/delete', 'PersonnelInformation\TrainingController@delete');
Route::post('training/update', 'PersonnelInformation\TrainingController@update');
Route::get('training/json/{id}', 'PersonnelInformation\TrainingController@json');

Route::post('training/request/create', 'PersonnelInformation\TrainingController@createrequest');

//POST TRAINING TEMP
Route::post('request-for-training/create', 'PersonnelInformation\TrainingTempController@create');
Route::post('request-for-training/action', 'PersonnelInformation\TrainingTempController@action');
Route::post('request-for-training/delete', 'PersonnelInformation\TrainingTempController@delete');
Route::post('request-for-training/update', 'PersonnelInformation\TrainingTempController@update');
Route::post('request-for-training/complete', 'PersonnelInformation\TrainingTempController@complete');
Route::get('request-for-training/json/{id}', 'PersonnelInformation\TrainingTempController@json');

//EMPOYEE BASIC INFO
Route::post('basicinfo/check', 'PersonnelInformation\BasicinfoController@check');
Route::post('basicinfo/create', 'PersonnelInformation\BasicinfoController@create');
Route::post('basicinfo/update', 'PersonnelInformation\BasicinfoController@update');
Route::get('basicinfo/json/{id}', 'PersonnelInformation\BasicinfoController@json');
Route::post('basicinfo/update-photo', 'PersonnelInformation\BasicinfoController@updatePhoto');

//EMPOYEE FAMILY
Route::post('family/check', 'PersonnelInformation\FamilyController@check');
Route::post('family/create', 'PersonnelInformation\FamilyController@create');
Route::post('family/update', 'PersonnelInformation\FamilyController@update');
Route::get('family/json/{id}', 'PersonnelInformation\FamilyController@json');

//EMPOYEE CHILDREN
Route::post('children/create', 'PersonnelInformation\ChildrenController@create');
Route::post('children/update', 'PersonnelInformation\ChildrenController@update');
Route::post('children/delete', 'PersonnelInformation\ChildrenController@delete');
Route::get('children/json/{id}', 'PersonnelInformation\ChildrenController@json');

//EMPOYEE WORK
Route::post('education/create', 'PersonnelInformation\EducationController@create');
Route::post('education/update', 'PersonnelInformation\EducationController@update');
Route::post('education/delete', 'PersonnelInformation\EducationController@delete');
Route::get('education/json/{id}', 'PersonnelInformation\EducationController@json');

//EMPOYEE ADD INFO
Route::post('addinfo/check', 'PersonnelInformation\AddinfoController@check');
Route::post('addinfo/create', 'PersonnelInformation\AddinfoController@create');
Route::post('addinfo/update', 'PersonnelInformation\AddinfoController@update');
Route::get('addinfo/json/{id}', 'PersonnelInformation\AddinfoController@json');

//EMPOYEE ORGANIZATION
Route::post('organization/create', 'PersonnelInformation\OrganizationController@create');
Route::post('organization/update', 'PersonnelInformation\OrganizationController@update');
Route::post('organization/delete', 'PersonnelInformation\OrganizationController@delete');
Route::get('organization/json/{id}', 'PersonnelInformation\OrganizationController@json');

//EMPOYEE WORK
Route::post('work/create', 'PersonnelInformation\WorkController@create');
Route::post('work/update', 'PersonnelInformation\WorkController@update');
Route::post('work/delete', 'PersonnelInformation\WorkController@delete');
Route::get('work/json/{id}', 'PersonnelInformation\WorkController@json');

//EMPOYEE ELIGIBILITY
Route::post('eligibility/create', 'PersonnelInformation\EligibilityController@create');
Route::post('eligibility/update', 'PersonnelInformation\EligibilityController@update');
Route::post('eligibility/delete', 'PersonnelInformation\EligibilityController@delete');
Route::get('eligibility/json/{id}', 'PersonnelInformation\EligibilityController@json');

//EMPOYEE SKILL
Route::post('skill/create', 'PersonnelInformation\SkillController@create');
Route::post('skill/update', 'PersonnelInformation\SkillController@update');
Route::post('skill/delete', 'PersonnelInformation\SkillController@delete');
Route::get('skill/json/{id}', 'PersonnelInformation\SkillController@json');

//COMPETENCY SKILL
Route::post('competency/create', 'PersonnelInformation\CompetencyController@create');
Route::post('competency/update', 'PersonnelInformation\CompetencyController@update');
Route::post('competency/delete', 'PersonnelInformation\CompetencyController@delete');
Route::get('competency/json/{id}', 'PersonnelInformation\CompetencyController@json');

Route::post('competency-duty/create', 'PersonnelInformation\CompetencyDutyController@create');
Route::post('competency-duty/update', 'PersonnelInformation\CompetencyDutyController@update');
Route::post('competency-duty/delete', 'PersonnelInformation\CompetencyDutyController@delete');
Route::get('competency-duty/json/{id}', 'PersonnelInformation\CompetencyDutyController@json');

Route::post('competency-training/create', 'PersonnelInformation\CompetencyTrainingController@create');
Route::post('competency-training/update', 'PersonnelInformation\CompetencyTrainingController@update');
Route::post('competency-training/delete', 'PersonnelInformation\CompetencyTrainingController@delete');
Route::get('competency-training/json/{id}', 'PersonnelInformation\CompetencyTrainingController@json');

Route::post('core-competency/create', 'PersonnelInformation\CompetencyController@create2');
Route::post('core-competency/update', 'PersonnelInformation\CompetencyController@update2');
Route::post('core-competency/delete', 'PersonnelInformation\CompetencyController@delete2');
Route::get('core-competency/json/{id}', 'PersonnelInformation\CompetencyController@json2');

//EMPOYEE RECOGNITION
Route::post('recognition/create', 'PersonnelInformation\RecognitionController@create');
Route::post('recognition/update', 'PersonnelInformation\RecognitionController@update');
Route::post('recognition/delete', 'PersonnelInformation\RecognitionController@delete');
Route::get('recognition/json/{id}', 'PersonnelInformation\RecognitionController@json');

//EMPOYEE ASSOCIATION
Route::post('association/create', 'PersonnelInformation\AssociationController@create');
Route::post('association/update', 'PersonnelInformation\AssociationController@update');
Route::post('association/delete', 'PersonnelInformation\AssociationController@delete');
Route::get('association/json/{id}', 'PersonnelInformation\AssociationController@json');

//EMPOYEE REFERENCES
Route::post('reference/create', 'PersonnelInformation\ReferenceController@create');
Route::post('reference/update', 'PersonnelInformation\ReferenceController@update');
Route::post('reference/delete', 'PersonnelInformation\ReferenceController@delete');
Route::get('reference/json/{id}', 'PersonnelInformation\ReferenceController@json');


//EMPOYEE ADMIN CASES
Route::post('cases/create', 'PersonnelInformation\CaseController@create');

//EMPOYEE FILE
Route::post('file/create', 'PersonnelInformation\FileController@create');
Route::post('file/update', 'PersonnelInformation\FileController@update');
Route::post('file/delete', 'PersonnelInformation\FileController@delete');
Route::get('file/json/{id}', 'PersonnelInformation\FileController@json');


//EMPOYEE ADDRESS/CONTACT
Route::post('address/check', 'PersonnelInformation\AddressController@check');
Route::get('location/municipal/{prov_id}', 'PersonnelInformation\AddressController@municipal');
Route::get('location/barangay/{mun_id}', 'PersonnelInformation\AddressController@barangay');

//MARSHALL
Route::get('letter-request', 'PersonnelInformation\MarshalController@requestHiring');
Route::get('download-file/{url}/{file}', 'PersonnelInformation\MarshalController@downloadFile');
Route::get('recruitment/list-of-applicants/{id}/{letterid}', 'PersonnelInformation\SharedController@applicants');
Route::post('request-for-hiring/update-applicants', 'PersonnelInformation\SharedController@updateapplicants');
Route::post('request-for-hiring/create', 'PersonnelInformation\RequestForHiringController@create');
Route::post('request-for-hiring/update', 'PersonnelInformation\RequestForHiringController@update');
Route::post('request-for-hiring/delete', 'PersonnelInformation\RequestForHiringController@delete');
Route::post('request-for-hiring/repost', 'PersonnelInformation\RequestForHiringController@repost');
Route::get('request-for-hiring/json/{id}', 'PersonnelInformation\RequestForHiringController@json');
Route::get('request-for-hiring-alert/json', 'PersonnelInformation\RequestForHiringController@alert');
Route::get('request-for-hiring-alert/clear', 'PersonnelInformation\RequestForHiringController@clear');
Route::post('recruitment/upload/vacancy-advice', 'PersonnelInformation\RequestForHiringController@upload');
Route::get('recruitment/history/{id}', 'PersonnelInformation\PDFController@hiringHistory');


//RECRUITMENT
Route::get('recruitment/index', 'PersonnelInformation\RecruitmentController@index');
Route::get('recruitment/list-vacant-position', 'PersonnelInformation\RecruitmentController@vacant');

//LETTER CLEARANCE
Route::get('recruitment/letter-approval', 'PersonnelInformation\RequestForHiringController@approval');
Route::post('recruitment/clearance', 'PersonnelInformation\RequestForHiringController@clearance');


//LEARNING AND DEVELOPMENT




//STAFF
Route::get('invitation/list', 'PersonnelInformation\StaffController@invitation');
Route::get('invitation/alert', 'PersonnelInformation\StaffController@invitationalert');
Route::post('invitation/answer', 'PersonnelInformation\StaffController@invitationanswer');


/***********SHARED PAGES***********/
Route::get('trainings/update', 'PersonnelInformation\TrainingTempController@trainings');

/***********END PERSONNEL INFORMATION***********/




/***********PAYROLL***********/

//PDF REPORT
Route::post('pdf/my-payslip', 'Payroll\PDFController@myPayslip');

/***********END PAYROLL***********/



/***********APPLICANTS***********/

Route::get('job-vacancies', 'ApplicantController@vacancy');
Route::get('apply/{letter}/{item}', 'ApplicantController@index');
Route::get('thank-you', 'ApplicantController@thankyou');
Route::get('list-of-applicants-for-psb/{token}', 'ApplicantController@list');
Route::post('send-application', 'ApplicantController@create');

/***********END APPLICANTS***********/


/***********CALL FOR SUBMISSION***********/
Route::get('submission/list', 'Submission\PageController@index');
Route::get('submission-list/division', 'Submission\PageController@index2');
Route::post('submission-list/update', 'Submission\Submission@update2');

Route::post('submission/create', 'Submission\Submission@create');
Route::post('submission/update', 'Submission\Submission@update');
Route::post('submission/delete', 'Submission\Submission@delete');
Route::get('submission/json/{id}', 'Submission\Submission@json');

Route::get('submission/list/training-report', 'Submission\PageController@trainingreport');
Route::get('submission/list/training-certificate', 'Submission\PageController@trainingcertificate');

/***********CALL FOR SUBMISSION***********/


/***********NOTIFICATION***********/
Route::get('notifications', 'NotificationController@index');
/***********NOTIFICATION***********/


/***********LEARNING AND DEVELOPMENT***********/
Route::get('learning-development/index', 'PersonnelInformation\LearningDevController@index');
Route::post('learning-development/call-for-hrd-plan', 'PersonnelInformation\LearningDevController@hrdplandivision');
Route::get('learning-development/division-hrd-list/{id}', 'PersonnelInformation\LearningDevController@jsondivhrd');
Route::get('learning-development/hrdc-hrd-list/{id}', 'PersonnelInformation\LearningDevController@jsonhrdchrd');
Route::post('learning-development/division-upload-hrd-plan', 'PersonnelInformation\MarshalController@divuploadhrd');
Route::post('learning-development/send-to-hrdc', 'PersonnelInformation\LearningDevController@sendtohrdc');
Route::get('learning-development/list-hrd-approval', 'PersonnelInformation\SharedController@hrdapprovallist');
Route::post('learning-development/hrd-approval', 'PersonnelInformation\SharedController@hrdapproval');
Route::post('learning-development/send-to-oed', 'PersonnelInformation\LearningDevController@sendtooed');
Route::post('learning-development/oed-upload-final', 'PersonnelInformation\SharedController@oedupload');
Route::get('learning-development/hrd-plan/{hrd_degree_id}/{hrd_plan_id}', 'PersonnelInformation\SharedController@hrdplan');
Route::get('learning-development/json/hrd-plan-degree/{id}', 'PersonnelInformation\SharedController@jsonhrdplandegree');
Route::post('learning-development/save-hrd-plan-degree', 'PersonnelInformation\SharedController@savehrddegree');
Route::post('learning-development/save-hrd-plan-non-degree', 'PersonnelInformation\SharedController@savehrdnondegree');
Route::post('learning-development/update-hrd-plan-degree', 'PersonnelInformation\SharedController@updatehrddegree');
Route::post('learning-development/delete-hrd-plan-degree', 'PersonnelInformation\SharedController@deletehrddegree');
Route::post('learning-development/delete-hrd-plan-non-degree', 'PersonnelInformation\SharedController@deletehrdnondegree');
Route::get('learning-development/print/hrd-plan-degree/{degreeid}', 'PersonnelInformation\PDFController@hrddegree');
Route::get('learning-development/print/hrd-plan-non-degree/{degreeid}', 'PersonnelInformation\PDFController@hrdnondegree');
Route::post('learning-development/submit-hrd-plan', 'PersonnelInformation\SharedController@hrdsubmit');
Route::get('learning-development/print/hrd-plan-consolidated/{hrd_id}', 'PersonnelInformation\PDFController@hrdconsolidated');
Route::get('learning-development/print/hrd-plan-consolidated-degree/{hrd_id}', 'PersonnelInformation\PDFController@hrdconsolidated2');

Route::get('learning-development/print/hrd-plan-monitoring-non-degree/{hrd_id}', 'PersonnelInformation\PDFController@monitoringnondegree');
Route::get('learning-development/print/hrd-plan-monitoring-degree', 'PersonnelInformation\PDFController@monitoringdegree');

Route::get('learning-development/hrd-plan-review/{id}', 'ApplicantController@hrdcreview');
Route::post('learning-development/submit-hrd-plan-review', 'ApplicantController@hrdcreviewsubmit');
Route::post('learning-development/close-hrd', 'PersonnelInformation\LearningDevController@closehrd');

Route::get('learning-development/hrd-degree-json/{id}', 'PersonnelInformation\LearningDevController@degreejson');
Route::post('learning-development/hrd-degree-update/', 'PersonnelInformation\LearningDevController@degreeupdate');

/***********TRAINING AND DEVELOPMENT***********/



/***********PERFORMANCE MANAGEMENT***********/

Route::get('performance/index', 'PersonnelInformation\PerformanceController@index');
Route::get('performance/dpcr/json/{year}/{period}', 'PersonnelInformation\PerformanceController@jsondpcr');
Route::get('performance/division', 'PersonnelInformation\PerformanceController@division');
Route::post('performance/ipcr/create', 'PersonnelInformation\PerformanceController@ipcrcreate');
Route::post('performance/dpcr/create', 'PersonnelInformation\PerformanceController@dpcrcreate');
Route::post('performance/dpcr/submit', 'PersonnelInformation\PerformanceController@dpcrsubmit');
Route::post('performance/ipcr-staff/create', 'PersonnelInformation\PerformanceController@ipcruploadstaff');
Route::post('performance/ipcr-staff/delete', 'PersonnelInformation\PerformanceController@ipcrdeletefile/delete');



/***********PERFORMANCE MANAGEMENT***********/


/***********AWARDS AND RECOGNITION***********/

Route::get('rewards/index', 'PersonnelInformation\RewardsRecognitionController@index');


/***********AWARDS AND RECOGNITION***********/


// Route::get('test', function () {
//     $dt = "01-15-2021";

//     return $dt->Carbon::isWeekend();
// });


// Route::get('update-emp', function () {
//     $emp = new App\Employee_division;
//     $emp = $emp->orderBy('id')->get();

//     // return $emp;
//     foreach($emp as $emps)
//     x`//     	$user = new App\Plantilla;
//     	$user = $user
//     			->where('user_id', $emps->user_id)
//           		->update(['employment_id' => $emps->employment_id]);
//     }
// });

// Route::get('plantillas', function () {
//     $emp = new App\Plantillas_history;
//     $emp = $emp->groupBy('username')->get();

//     // return $emp;
//     foreach($emp as $emps)
//     {
//     	$emp2 = new App\Plantillas_history;
//     	$emp2 = $emp2
//     			->where('username',$emps->username)
//     			->orderBy('plantilla_date_from','desc')
//     			->first();

//     	// echo $emp2->username."-".$emp2->plantilla_date_from."<br/>";

//     	$emp3 = new App\Plantilla;
//     	$emp3->username = $emp2->username;
//     	$emp3->plantilla_item_number = $emp2->plantilla_item_number;
//     	$emp3->plantilla_division = $emp2->plantilla_division;
//     	$emp3->position_id = $emp2->position_id;
//     	$emp3->plantilla_step = $emp2->plantilla_step;
//     	$emp3->employment_id = $emp2->employment_id;
//     	$emp3->plantilla_salary = $emp2->plantilla_salary;
//     	$emp3->plantilla_date_from = $emp2->plantilla_date_from;
//     	$emp3->plantilla_date_to = $emp2->plantilla_date_to;
//     	$emp3->plantilla_remarks = $emp2->plantilla_remarks;
//     	$emp3->save();
//     }
// });

// Route::get('divisions', function () {
//     $emp = new App\Employee_division;
//     $emp = $emp->groupBy('username')->get();

//     // return $emp;
//     foreach($emp as $emps)
//     {
//     	$emp2 = new App\Employee_division;
//     	$emp2 = $emp2
//     			->where('username',$emps->username)
//     			->orderBy('emp_desig_from','desc')
//     			->first();

//     	// echo $emp2->username."-".$emp2->plantilla_date_from."<br/>";

//     	$emp3 = new App\User;
//     	$emp3 = $emp3->where('username', $emp2->username)
//           		     ->update(['division' => $emp2->division_id]);
//     }
// });

// Route::get('test', function () {
// 	return App\HRD_plan_staff::where('user_id',Auth::user()->id)->whereNull('submitted_at')->count();
// });

// Route::get('update-leave', function () {
//     $emp = App\User::get();

//     // return $emp;
//     foreach($emp as $emps)
//     {
//     	App\Employee_leave::where('empcode',$emps->username)
//           		     ->update(['user_id' => $emps->id]);
//     }
// });



//DOWNLOAD
Route::get('download-zip', 'ZipController@downloadZip');


/***********ATTENDANCE MONITORING***********/

Route::get('dtr/terminal', 'AttendanceMonitoring\DTRController@terminal');
Route::get('dtr/icos/{mon}/{year}/{id}', 'AttendanceMonitoring\DTRController@icosindex');
Route::get('dtr/icos-process', 'AttendanceMonitoring\DTRController@icosprocess');
Route::get('icos/payroll/{mon}/{yr}/{period}', 'AttendanceMonitoring\DTRController@icospayroll');
Route::get('cos/payroll-page', 'PersonnelInformation\StaffController@cospayrollpage');
Route::post('icos/update-deduction', 'AttendanceMonitoring\DTRController@icospayrollupdate');
Route::post('icos/update-ors', 'AttendanceMonitoring\DTRController@updateORS');
Route::get('dtr/cos-emp/{mon}/{yr}/{period}', 'AttendanceMonitoring\DTRController@cosemp');
Route::post('dtr/icos-final-process', 'AttendanceMonitoring\DTRController@cofinalprocess');
Route::get('dtr/cos-summary/{mon}/{yr}/{period}', 'AttendanceMonitoring\DTRController@printpcocdtrsummary');
Route::post('dtr/cos-reverse', 'AttendanceMonitoring\DTRController@cosreverse');
Route::post('payroll/cos/process', 'AttendanceMonitoring\DTRController@cofinalprocesspayroll');
Route::post('payroll/cos-print', 'AttendanceMonitoring\DTRController@printpcospayroll');
Route::get('payroll/cos-print/{mon}/{yr}/{period}/{date}/{type}/{charging}', 'AttendanceMonitoring\DTRController@printpayrollcos');

// Route::get('dtr/icos', 'AttendanceMonitoring\DTRController@icosindex');
Route::post('dtr/icos/wfh', 'AttendanceMonitoring\DTRController@icoswfh');

Route::get('dtr/emp/{mon}/{year}', 'AttendanceMonitoring\DTRController@emp');
Route::post('dtr/process', 'AttendanceMonitoring\DTRController@processDTR');
Route::post('dtr/final-process', 'AttendanceMonitoring\FinalProcess@index');
Route::post('dtr/edit-view', 'AttendanceMonitoring\DTRController@edit2');
Route::post('dtr/update', 'AttendanceMonitoring\DTRController@update');
Route::post('dtr/weekly-schedule', 'AttendanceMonitoring\DTRController@weeksched');
Route::post('dtr/weekly-schedule-edit', 'AttendanceMonitoring\DTRController@weekschededit');
Route::post('dtr/weekly-schedule-add', 'AttendanceMonitoring\DTRController@weekschedadd');
Route::get('dtr/json/weekly-schedule/{id}', 'AttendanceMonitoring\DTRController@schedule');

//Route::get('update-weekly-schedule/{mon}/{yr}', 'PersonnelInformation\StaffController@weeksched');
//Route::post('update-weekly-schedule-send', 'PersonnelInformation\StaffController@weekschedsend');


// Route::get('test', 'AttendanceMonitoring\RequestController@dt');

//GET LEAVE PENDING
Route::get('dtr/get-pending-leave/{id}', 'AttendanceMonitoring\LeaveController@getPending');
Route::get('dtr/cancel-leave/{id}', 'AttendanceMonitoring\LeaveController@cancelLeave');
Route::post('dtr/add-leave', 'AttendanceMonitoring\LeaveController@updateLeave');

//PDF REPORT
// Route::get('pdf/my-dtr/{date}', 'AttendanceMonitoring\PDFController@myDTR');
Route::post('pdf/my-dtr', 'AttendanceMonitoring\PDFController@myDTR');
Route::get('pdf/leave-form', 'PDF\LeaveForm@index');

//REQUEST FOR LEAVE/OT/TO

//REQUEST APPROVED/DISAPPROVE
// Route::post('request/action', 'AttendanceMonitoring\DirectorController@actionRequest');

Route::post('request/{type}', 'AttendanceMonitoring\RequestController@index');
Route::get('dtr/request-leave', 'AttendanceMonitoring\LeaveController@request');
Route::post('dtr/send-leave-request', 'AttendanceMonitoring\LeaveController2@send');
Route::post('dtr/print-leave', 'AttendanceMonitoring\RequestController@pdf');
Route::post('dtr/print-wfh', 'AttendanceMonitoring\RequestController@wfh');

Route::post('dtr/request-action-leave', 'AttendanceMonitoring\DirectorController@actionRequest');
Route::post('dtr/request-action-to', 'AttendanceMonitoring\DirectorController@actionRequest');
Route::post('dtr/request-action-ot', 'AttendanceMonitoring\DirectorController@actionRequest');

//TO
Route::get('dtr/request-for-to', 'AttendanceMonitoring\TOController@index');
Route::post('dtr/send-to-request', 'AttendanceMonitoring\TOController@send');
Route::post('dtr/print-to', 'AttendanceMonitoring\TOController@pdf');

//OT
Route::get('dtr/request-for-ot', 'AttendanceMonitoring\OTController@index');
Route::post('dtr/send-ot-request', 'AttendanceMonitoring\OTController@send');
Route::post('dtr/print-ot', 'AttendanceMonitoring\OTController@pdf');
Route::post('dtr/print-batch-ot', 'AttendanceMonitoring\OTController@batchPDF');

//JSON FOR EDITING
Route::get('request/json/{type}/{id}', 'AttendanceMonitoring\RequestController@json');

//WFH
Route::post('dtr/send-wfh-request', 'AttendanceMonitoring\LeaveController@wfh');

//PRINT PDF
Route::get('request/print/{reqid}', 'AttendanceMonitoring\RequestController@pdf');

//DIRECTOR

//LEAVE/OT/TO FOR APPROVAL
Route::get('request-for-approval', 'AttendanceMonitoring\DirectorController@request');
Route::get('director-trainings-list', 'PersonnelInformation\TrainingController@list');
Route::post('request-for-approval-submit', 'AttendanceMonitoring\DirectorController@approvedLeaveRequest');
Route::get('staff-all-request', 'AttendanceMonitoring\RequestController@staffAllRequest');



//DTR MONITORING
// Route::get('dtr/monitoring/{mon}/{yr}/{userid}', 'AttendanceMonitoring\DTRController@monitor');
Route::post('dtr/monitoring', 'AttendanceMonitoring\DTRController@monitor');
Route::post('dtr/admin-monitoring', 'AttendanceMonitoring\DTRController@monitor');
Route::post('dtr/pdf', 'AttendanceMonitoring\DTRController@pdf');
Route::post('dtr-icos/pdf', 'AttendanceMonitoring\DTRController@pdficos');
Route::post('dtr/edit', 'AttendanceMonitoring\DTRController@edit');
// Route::get('dtr/edit/{id}/{mon}/{yr}', 'AttendanceMonitoring\DTRController@edit2');
Route::get('change-password', 'AttendanceMonitoring\DTRController@password');
Route::post('change-password-send', 'HomeController@changepassword');
Route::post('dtr/icos/update', 'AttendanceMonitoring\DTRController@icosupdate');
Route::post('dtr/icos/add', 'AttendanceMonitoring\DTRController@icoswfhto');

Route::post('dtr/edit/submit', 'AttendanceMonitoring\DTRController@editdtr');

Route::get('dtr/employee', 'AttendanceMonitoring\DTRController@empdtrmonth');

Route::get('dtr/process/{mon}/{yr}', 'AttendanceMonitoring\DTRController@proccessdtr');

Route::get('dtr/report', 'AttendanceMonitoring\AdminController@report');

Route::get('json/dtr/{userid}/{type}/{col}/{t}', 'JSON@dtr');

Route::get('dtr/process-json/{user}', 'AttendanceMonitoring\AdminController@dtrprocess');

Route::get('dtr/reverse', 'AttendanceMonitoring\AdminController@dtrReverse');




//LEAVE
// Route::post('cams/apply-leave', 'AttendanceMonitoring\LeaveController@apply');

// Route::get('test', function () {
//    $division = App\Division::get();
//    foreach ($division as $divisions) {
   		
//    		for ($i=1; $i <= 12 ; $i++) { 
//    			$dtr = new App\DTRProcessed;
//    			$dtr->dtr_mon = $i;
//    			$dtr->dtr_year = 2021;
//    			$dtr->dtr_division = $divisions->division_id;
//    			$dtr->save();
//    		}
//    }
// });

// Route::get('test', function () {
//    return checkDTRStaff(2,2021,'K');
// });

Route::get('monitor-dtr/EzUt1cg19i/{datedtr}', function ($datedtr) {

   //TOTAL ICOS + REGULAR
    $total_emp = App\User::whereIn('employment_id',[1,5,8])->count();

    //GET ICOS ATTENDANCE
    $total_icos = App\Employee_icos_dtr::where('fldEmpDTRdate',$datedtr)->whereNull('dtr_remarks')->groupBy('fldEmpCode')->get();
    $total_icos = count($total_icos);

    //GET REGULAR ATTENDANCE
    $total_reg = App\Camsdtr::where('fldEmpDTRdate',$datedtr)->count();

    //PERCENTAGE
    $total = $total_icos + $total_reg;
    $percent = ($total / $total_emp) * 100;
    $percenttotal = number_format($percent, 0);

    $data = [
    			'datedtr' => $datedtr,
    			'total_reg' => $total_reg,
    			'total_icos' => $total_icos,
    			'percent' => $percenttotal
    		];
   return view('monitor-dtr')->with('data',$data);
});


// Route::get('test', function () {

//    return getLeaveInfo(1);
// });


/***********REPORT***********/

Route::post('dtr/report/dtr-summary', 'PDF\DTRSummary@index');
Route::post('dtr/report/daily-monitoring', 'PDF\DailyMonitoring@index');
Route::post('dtr/report/final-processed-dtr', 'PDF\FinalProcessedDTR@index');
Route::post('dtr/report/excessive-tardiness', 'PDF\ExcessiveTardiness@index');
Route::post('dtr/report/sala-attendance', 'PDF\SALAAttendance@index');
Route::post('dtr/report/hp-attendance', 'PDF\HPAttendance@index');
Route::post('dtr/report/travel-order', 'PDF\TravelOrder@index');
Route::post('dtr/report/leave-record', 'PDF\LeaveRecord@index');
Route::post('dtr/report/leave-without-pay', 'PDF\LeaveWoutPay@index');
Route::post('dtr/report/rendering-overtime', 'PDF\RenderingOvertime@index');
Route::post('dtr/print', 'PDF\DTR@index');


/***********CORE COMPETENCY***********/

Route::get('core-competency', 'PersonnelInformation\CompetencyController@core');


/***********MAINTENANCE***********/

Route::get('maintenance', 'Maintenance\Maintenance@index');
Route::post('maintenance/work-schedule/update', 'Maintenance\Maintenance@workschedule');
Route::post('maintenance/library/{action}', 'Maintenance\Maintenance@library');
Route::get('available-leave-balance/{userid}', 'Maintenance\Maintenance@leavejson');
Route::post('maintenance/monetization-leave', 'Maintenance\Maintenance@monetization');
Route::post('maintenance/monetization-cancel', 'Maintenance\Maintenance@monetizationcancel');


/***********DASHBOARD***********/

Route::get('dashboard', 'Dashboard@index');


/***********PAYROLL***********/

Route::get('payroll/emp', 'Payroll\EmployeeInfo@index');
Route::get('payroll/mc/{mon}/{yr}', 'Payroll\Report@mc');
Route::get('payroll/mc-pending/json/{mon}/{yr}', 'Payroll\Report@mcpending');
Route::get('payroll/mc-deduc/json/{id}/{col}', 'Payroll\Report@mcdeduc');
Route::post('payroll/mc-deduc-edit', 'Payroll\Report@mcdeducedit');
Route::post('payroll/mc-process', 'Payroll\Process@mcprocess');
Route::get('payroll/mc-textfile/{mon}/{yr}', 'Payroll\PDFController@printMCTextfile');

Route::get('payroll/emp/{empcode}', 'Payroll\EmployeeInfo@index2');

Route::get('payroll/library', 'Payroll\Library@index');
Route::get('payroll/report', 'Payroll\Report@index');

Route::get('payroll/salary-deduc-manda/json/{empcode}/{id}', 'Payroll\Report@deducjson');
Route::get('payroll/salary-deduc-loan/json/{empcode}/{id}', 'Payroll\Report@loanjson');
Route::get('payroll/emp-comp/json/{empcode}/{id}', 'Payroll\Report@compjson');
Route::post('payroll/salary-deduc-manda-loan-edit', 'Payroll\Report@deducmandaloan');


Route::get('payroll/remittance', 'Payroll\Report@remittance');
Route::post('payroll/remittance', 'Payroll\PDFController@printRemittance');


//PROCESS PAYROLL
Route::get('payroll/process', 'Payroll\Process@index');
Route::post('payroll/create', 'Payroll\Process@create');

Route::get('test-code', 'Payroll\Process@test');

//MC
Route::post('payroll/mc-report', 'Payroll\PDFController@MCreport');
Route::post('payroll/mc-print', 'Payroll\PDFController@printMC');

Route::get('payroll/json/{tbl}', 'Payroll\Library@json');
Route::post('payroll/preview', 'Payroll\PDFController@previewpayroll');

//SALARY LEDGER
Route::get('payroll/ledger', 'Payroll\Ledger@index');
Route::post('payroll/ledger', 'Payroll\Ledger@print');

Route::get('payroll/text-file/{mon}/{yr}/{wk}', 'Payroll\Ledger@textfile');

//ADD EMPTY ROWS FOR EMPLOYEE INFO
Route::get('add-info', function () {
   $user = App\User::get();


   foreach ($user as $users) {
   		
   		$tbl = App\Employee_addinfo::where('user_id',$users->id)->count();
   		if($tbl > 0)
   		{
   			$tbl = new App\Employee_addinfo;
   			$tbl->user_id = $users->id;
   			$tbl->save();
   		}

   		$tbl = App\Employee_address_permanent::where('user_id',$users->id)->count();
   		if($tbl > 0)
   		{
   			$tbl = new App\Employee_address_permanent;
   			$tbl->user_id = $users->id;
   			$tbl->save();
   		}

   		$tbl = App\Employee_family::where('user_id',$users->id)->count();
   		if($tbl > 0)
   		{
   			$tbl = new App\Employee_family;
   			$tbl->user_id = $users->id;
   			$tbl->save();
   		}

   		$tbl = App\Employee_contact::where('user_id',$users->id)->count();
   		if($tbl > 0)
   		{
   			$tbl = new App\Employee_contact;
   			$tbl->user_id = $users->id;
   			$tbl->save();
   		}

   }
});

Route::get('test-insert', function () {
	$data = collect(['division_acro' => 'HELLO']);
	$dt = App\Division::insertGetId($data->all());
	return $dt;
});

//NEW PAGES
Route::get('staff/attendance/{mon}/{y}/{userid}', 'PersonnelInformation\StaffController@attendance');
Route::get('staff/leave', 'PersonnelInformation\StaffController@leave');
Route::get('staff/leave/{id}', 'PersonnelInformation\StaffController@leave2');
Route::get('staff/to', 'PersonnelInformation\StaffController@to')->name("staff_to");
Route::get('staff/cto', 'PersonnelInformation\StaffController@cto');
Route::get('staff/json/cto/{id}', 'PersonnelInformation\StaffController@ctostaff');
Route::get('staff/json/cto-time/{id}', 'AttendanceMonitoring\OTController@ctotime');
Route::get('staff/payroll', 'PersonnelInformation\StaffController@payroll');
Route::get('staff/loan', 'PersonnelInformation\StaffController@loan');

//FOR TESTING
Route::get('staff/leave/test', 'PersonnelInformation\StaffController@leavetest');
Route::post('dtr/send-leave-request/test', 'AttendanceMonitoring\LeaveController2@send2');
Route::get('staff/leave-test/{id}', 'PersonnelInformation\StaffController@leave22');

Route::get('test', function () {
	
});

// use Illuminate\Support\Facades\Hash;
Route::get('update-password', function () {
	// $user = App\CamsUser::get();
	// foreach ($user as $key => $value) {
	// 	$emp = App\User::where('username',$value->fldUsername)
	// 			->update([
	// 						'password' => Hash::make($value->fldPassword)
	// 					]);
	// }

	return bcrypt('aleja123');
});

//Workforce Monitoring *maine
	Route::get('workforce-monitoring/{date}', function ($date) {
		$data = [
					"date" => $date,
				];
		return view('workforce-monitoring')->with("data",$data);
	});

	Route::get('workforce-monitoring', function () {
		$date = date('Y-m-d');

		return redirect('workforce-monitoring/'.$date);
	});


//gss2 *maine
	Route::get('gss2/{date}', function ($date) {
		$data = [	
					"date" => $date,
				];
		return view('gss2')->with("data",$data);
	});
	
	Route::get('gss2', function () {
		$date = date('Y-m-d');	
	
		return redirect('gss2/'.$date);
	});

//GSS
Route::get('gss', 'GSSController@index');
Route::get('gss/{mon}/{year}', 'GSSController@index2');

//Route::post('update-weekly-schedule-details', 'PersonnelInformation\StaffController@updadetails');


Route::get('json/ctobal/{userid}', 'JSONController@ctobal');


Route::get('schedule-monitor/{mon}/{yr}/{week}/{div}', function ($mon,$yr,$wk,$div) {
	$data = [
				"division" => $div,
				"mon" => $mon,
				"yr" => $yr,
				"weeknum" => $wk
			];
	return view('schedule-monitor')->with("data",$data);
});

Route::get('schedule-monitor', function () {
	$mon = date('m');
	$yr = date('Y');
	$week = date(1);

	return redirect('schedule-monitor/'.$mon."/".$yr."/".$week."/K");
});


// Route::get('revert-dtr', function () {
// 	return view('revert');
// });

Route::get('add-leave', function () {
	return view('addleave');
});

Route::post('add-leave-post', function () {
	
	// $user = App\User::where('id',request()->userid)->first();

	// if(request()->lv_pl != "" || request()->lv_pl != null)
	// {
	// 	$lv = new App\Employee_leave;
	// 	$lv->leave_id = 3;
	// 	$lv->user_id = request()->userid;
	// 	$lv->empcode = $user['username'];
	// 	$lv->leave_bal = request()->lv_pl;
	// 	$lv->save();
	// }

	// if(request()->lv_fl != "" || request()->lv_fl != null)
	// {
	// 	$lv2 = new App\Employee_leave;
	// 	$lv2->leave_id = 6;
	// 	$lv2->user_id = request()->userid;
	// 	$lv2->empcode = $user['username'];
	// 	$lv2->leave_bal = request()->lv_fl;
	// 	$lv2->save();
	// }

	// return redirect('add-leave');
});

Route::get('delete/dtr-process/{id}', function ($id) {
	// $code = App\DTRProcessed::where('id',$id)->first();
	// $processcode = $code['process_code'];

	// //DELETE PROCEESS
	// App\DTRProcessed::where('id',$id)->delete();
	// App\Employee_sala::where('process_code',$processcode)->delete();
	// App\Payroll\MC::where('process_code',$processcode)->delete();
	// App\Payroll\MCDays::where('process_code',$processcode)->delete();
	// App\Employee_hp::where('process_code',$processcode)->delete();
	// App\Employee_leave::where('process_code',$processcode)->delete();
	
	// //check leave
	// $l =  App\Request_leave::where('process_code',$processcode)->get();
	// if($l)
	// 	App\Request_leave::whereNotIn('leave_action_status',['Cancelled','Disapproved','Pending'])->where('process_code',$processcode)->update(["leave_action_status"=>"Approved", "process_code" => null]);


	// $t =  App\RequestTO::where('process_code',$processcode)->get();
	// if($t)
	// 	App\RequestTO::whereNotIn('to_status',['Cancelled','Pending'])->where('process_code',$processcode)->update(["to_status"=>"Approved", "process_code" => null]);

	// return redirect("revert-dtr");
});


Route::get('clean-dtr', function () {
	// $dtr = App\ICOSDTR::where('fldEmpDTRdate','2021-12-16')->get();

	// foreach ($dtr as $key => $value) {
	// 	echo $value->employee_name." - ".$value->fldEmpDTRdate." - ".$value->fldEmpDTRamIn;

	// 	// $check = App\Employee_icos_dtr2::where('fldEmpCode',$value->fldEmpCode)->where('fldEmpDTRdate',$value->fldEmpDTRdate)->whereIn('wfh',['Wholeday','AM','PM'])->first();
	// 	$check = App\Employee_icos_dtr2::where('fldEmpCode',$value->fldEmpCode)->where('fldEmpDTRdate',$value->fldEmpDTRdate)->whereNull('wfh')->first();

	// 	if($check)
	// 	{
	// 		App\Employee_icos_dtr2::where('id',$check['id'])
	// 		->update([
	// 					// 'fldEmpDTRpmIn' => $value->fldEmpDTRamOut,
	// 					'fldEmpDTRamOut' => $value->fldEmpDTRamOut,
	// 					'fldEmpDTRpmIn' => $value->fldEmpDTRpmIn,
	// 					'fldEmpDTRpmOut' => $value->fldEmpDTRpmOut,
	// 				]);

	// 		// echo "Doble to...Burahin ang WFH<br>";
	// 		// App\Employee_icos_dtr2::where('fldEmpCode',$value->fldEmpCode)->where('fldEmpDTRdate',$value->fldEmpDTRdate)->delete();

	// 		// $newdtr = new App\Employee_icos_dtr2;
	// 		// $newdtr->fldEmpDTRID = $value->id;
	// 		// $newdtr->fldEmpCode = $value->fldEmpCode;
	// 		// $newdtr->fldEmpDTRdate = $value->fldEmpDTRdate;
	// 		// $newdtr->fldEmpDTRamIn = $value->fldEmpDTRamIn;
	// 		// $newdtr->fldEmpDTRamOut = $value->fldEmpDTRamOut;
	// 		// $newdtr->fldEmpDTRpmIn = $value->fldEmpDTRpmIn;
	// 		// $newdtr->fldEmpDTRpmOut = $value->fldEmpDTRpmOut;
	// 		// $newdtr->save();
			
	// 	}
	// 	else
	// 	{
	// 		$newdtr = new App\Employee_icos_dtr2;
	// 		$newdtr->fldEmpDTRID = $value->id;
	// 		$newdtr->fldEmpCode = $value->fldEmpCode;
	// 		$newdtr->employee_name = $value->employee_name;
	// 		$newdtr->division = $value->division;
	// 		$newdtr->fldEmpDTRdate = $value->fldEmpDTRdate;
	// 		$newdtr->fldEmpDTRamIn = $value->fldEmpDTRamIn;
	// 		// $newdtr->fldEmpDTRamOut = $value->fldEmpDTRamOut;
	// 		// $newdtr->fldEmpDTRpmIn = $value->fldEmpDTRpmIn;
	// 		// $newdtr->fldEmpDTRpmOut = $value->fldEmpDTRpmOut;
	// 		$newdtr->save();
			
	// 	}
	// }

		// return "hello";

	// $mon = 12;
	// $yr = 2021;

	// $mon = date('F',mktime(0, 0, 0, $mon, 10));
	// $date = $mon ."-" . $yr;

	// $total = Carbon\Carbon::parse($date)->daysInMonth;
	
	// $d1 = 1;
    // $d2 = 14;

	// // $dtr = App\Employee_icos_dtr2::whereMonth('fldEmpDTRdate',12)->where('fldEmpDTRamIn','8:00:00')->where('fldEmpDTRamOut','12:00:00')->where('fldEmpDTRpmIn','13:00:00')->where('fldEmpDTRpmOut','17:00:00')->get();
	// $dtr = App\Employee_icos_dtr2::whereMonth('fldEmpDTRdate',12)->get();

	// foreach ($dtr as $key => $value) 
	// {
	// 	// App\Employee_icos_dtr2::where('id',$value->id)
	// 	// 	->update([
	// 	// 				'wfh' => 'Wholeday',
	// 	// 			]);
	// 	for($i = 1;$i <= $total; $i++)
	// 	{

	// 		if($i >= $d1 && $i <= $d2)
	// 		{
	// 			$d = $yr."-12-".$i;

	// 			$dayDesc = weekDesc(date($d));

	// 			if($dayDesc == 'Sat' || $dayDesc == 'Sun')
	// 			{
	// 				///
	// 			}
	// 			else
	// 			{
	// 				echo $value->fldEmpCode."-".$d;
	// 				$check = App\Employee_icos_dtr2::where('fldEmpCode',$value->fldEmpCode)->where('fldEmpDTRdate',$d)->first();
	// 				if(!$check)
	// 				{
	// 					$newdtr = new App\Employee_icos_dtr2;
	// 					$newdtr->fldEmpDTRID = $value->id;
	// 					$newdtr->fldEmpCode = $value->fldEmpCode;
	// 					$newdtr->fldEmpDTRdate = $d;
	// 					$newdtr->fldEmpDTRamIn = "8:00:00";
	// 					$newdtr->fldEmpDTRamOut = "12:00:00";
	// 					$newdtr->fldEmpDTRpmIn = "13:00:00";
	// 					$newdtr->fldEmpDTRpmOut = "17:00:00";
	// 					$newdtr->wfh = "Wholeday";
	// 					$newdtr->save();
	// 					$insertid = $newdtr->id;

	// 					echo "Insert...id: ".$insertid."<br>";
	// 				}
	// 				else
	// 				{
	// 					echo "<br>";
	// 				}
	// 			}
				
	// 		}
	// 	}
	// }

	
});


// Route::get('emp-pos', function () {
// 	$pos = App\PIS\EmpPos::orderBy('fldEmpPosID','DESC')->get();

// 	foreach ($pos as $key => $value) {
// 		echo $value->fldEmpCode." - ".$value->fldItemNumber." - ".$value->fldSalary." - ".$value->fldFromDate."....<br/>";
// 		$ps = App\PIS\Plantilla::where('username',$value->fldEmpCode)->count();

// 		if($ps <= 0)
// 		{
// 			$newpos = new App\PIS\Plantilla;
// 			$newpos->username = $value->fldEmpCode;
// 			$newpos->plantilla_item_number = $value->fldItemNumber;
// 			$newpos->position_id = $value->fldPosID;
// 			$newpos->plantilla_salary = $value->fldSalary;
// 			$newpos->plantilla_date_from = $value->fldFromDate;
// 			$newpos->plantilla_date_to = $value->fldFromDate;
// 			$newpos->plantilla_remarks = $value->fldRemarks;
// 			$newpos->save();
// 		}
// 	}
// });

Route::get('get-leave', function () {
	// $lv = App\Request_leave::where('leave_id',3)->whereIn('leave_action_status',['Approved','Processed','Disapproved'])->get();

	// foreach ($lv as $key => $value)
	// {
	// 	//GET PROCESSED CODE
	// 	//MONTH
	// 	$mon = date('m',strtotime($value->leave_date_from));
	// 	$yr = date('Y',strtotime($value->leave_date_from));

	// 	$proc = App\DTRProcessed::where('userid',$value->user_id)->where('dtr_mon',$mon)->where('dtr_year',$yr)->first();

	// 	if($proc)
	// 	{
	// 		echo $value->id." ~ ".$value->leave_date_from." ~ ".$value->parent." ~ ".$value->leave_id." ~ ".$value->parent_leave." ~ ".$value->parent_leave_code." ~ ".$value->leave_action_status." ~ ".$value->process_code." ------ ".$proc['process_code']."<br>";

	// 		//UPDATE PROCESS CODE
	// 		App\Request_leave::where('id',$value->id)->update(["process_code" => $proc['process_code']]);
	// 	}
	// 	else
	// 	{
	// 		echo $value->id." ~ ".$value->leave_date_from." ~ ".$value->parent." ~ ".$value->leave_id." ~ ".$value->parent_leave." ~ ".$value->parent_leave_code." ~ ".$value->leave_action_status." ~ ".$value->process_code." ------ MISSING<br>";
	// 	}

		
	// }
});


Route::get('sync-icos', function () {
	// $icos = App\DTRIcos::whereMonth('fldEmpDTRdate',12)->whereYear('fldEmpDTRdate',2021)->get();
	// foreach ($icos  as $key => $value) {
	// 	$icosnew = App\Employee_icos_dtr2::where('fldEmpDTRID',$value->id)->first();

	// 	if($icosnew)
	// 	{
	// 		echo "Date : ".$value->fldEmpDTRdate." ---- Old : ".$value->fldEmpDTRamIn."".$value->fldEmpDTRamOut."".$value->fldEmpDTRpmIn."".$value->fldEmpDTRpmOut." --- New : ".$icosnew['fldEmpDTRamIn']."".$icosnew['ldEmpDTRamOut']."".$icosnew['fldEmpDTRpmIn']."".$icosnew['fldEmpDTRpmOut']."<br>";
	// 		App\Employee_icos_dtr2::where('fldEmpDTRID',$value->id)
	// 								->update([
	// 											'fldEmpDTRamOut' => $value->fldEmpDTRamOut,
	// 											'fldEmpDTRpmIn' => $value->fldEmpDTRpmIn,
	// 											'fldEmpDTRpmOut' => $value->fldEmpDTRpmOut,
	// 											'dtr_remarks' => $value->dtr_remarks,
	// 								]);
	// 	}
	// }
});


Route::get('compute-cto', function () {
	// $cto = App\RequestOT::whereNotNull('ot_in')->whereNotNull('ot_out')->whereNotIn('ot_status',['Cancelled'])->get();
	// 	if($cto)
	// 	{
	// 		foreach ($cto as $key => $value) {

	// 			$start =  Carbon\Carbon::parse($value->ot_date." ".$value->ot_in);
	// 			$end =  Carbon\Carbon::parse($value->ot_date." ".$value->ot_out);

	// 			$totalDuration = $end->diffInMinutes($start);

	// 			$ctoearn = $totalDuration / 480;
	// 			$ctoearn = number_format($ctoearn,3);

	// 			echo "cto : ".$value->id." Employee : ".$value->employee_name." ".$totalDuration."<br/>";
	// 			App\RequestOT::where('id',$value->id)->update(['ot_min' => $totalDuration,'cto_earn' => $ctoearn]);
	// 		}
			
	// 	}

});

Route::get('test-MC', function () {

	// $mon = 2;
	// $yr = 2022;

	// $mc = App\Payroll\MC::where('payroll_mon',3)->where('payroll_yr',2022)->get();

	// foreach ($mc as $key => $value)
	// {
	// 	$user = App\User::where('id',$value->userid)->first();

	// 	if($user)
	// 	{
	// 		switch($user['employment_id'])
    //         {
    //             case 1:
    //             case 11:
    //             case 13:
    //             case 14:
    //             case 15:
	// 				//GET SALA
	// 					//TO
	// 					//GET T.O
	// 					$to_req = App\RequestTO::where('parent','YES')->where('userid',$value->userid)->where('to_status','Approved')->whereMonth('to_date_from',$mon)->whereYear('to_date_from',$yr)->get();
	// 					$to_total = 0;
	// 					if($to_req)
	// 					{
	// 						foreach ($to_req as $key => $valueto) {
								
	// 							if($valueto->to_total_day <= 1.0)
	// 							{
	// 								$to_mc_deduc = $valueto->to_total_day;
	// 							}
	// 							else
	// 							{
	// 								$to_mc_deduc = 1;
	// 							}
			
			
	// 							//GET DATE
	// 							$from = Carbon\Carbon::parse($valueto->to_date_from);
	// 							$to = Carbon\Carbon::parse($valueto->to_date_to);
	// 							$diff = 1+($from->diffInDays($to));
			
	// 							for($i = 1; $i <= $diff; $i++)
	// 							{
									
	// 								if($i == 1)
	// 								{
	// 									$dt = date('Y-m-d',strtotime($from));
	// 								}
	// 								else
	// 								{
	// 									$dt = $from->addDays(1);             
	// 								}
			
			
	// 								if(!checkIfWeekend($dt))
	// 								{
	// 									if(!checkIfHoliday($dt))
	// 									{  
	// 										if($valueto->to_perdiem == 'YES')
	// 											{
	// 												$to_total += $to_mc_deduc;
	// 											}
	// 									}
	// 								}
	// 							}
	// 						}   
	// 					}



	// 					//LEAVE
	// 					//MULTIPLE DATE
	// 					$l1_total = App\Request_leave::where('user_id',$value->userid)->whereNull('parent_leave')->whereNotNull('parent_leave_code')->whereNotIn('leave_id',[5,16])->where('leave_action_status','Approved')->whereMonth('leave_date_from',$mon)->whereYear('leave_date_from',$yr)->count();

	// 					//SINGLE DATE
	// 					$l2_total = App\Request_leave::where('user_id',$value->userid)->where('parent','YES')->whereIn('leave_deduction',[1,0.5])->whereNotIn('leave_id',[5,16])->where('leave_action_status','Approved')->whereMonth('leave_date_from',$mon)->whereYear('leave_date_from',$yr)->sum('leave_deduction');

	// 					$l_total = $l1_total + $l2_total;
	// 					$lv_to_total = $l_total + $to_total ;

	// 					$sa = 3150 - ($lv_to_total * 150);
    //                  $la = number_format(500 - ((500 / 22) * $lv_to_total),2);

	// 					if($user['employment_id'] == 15)
    //                           {
    //                             $sa = 0;
    //                             $la = 0;
    //                             $hp = 0;
    //                           }

	// 					echo $user['username']." SA : ".$sa." ---- LA : ".$la." ---- LEAVE : ".$l_total ." --- TO3 : ".$to_total."<br/>";

	// 					//UPDATE MC
	// 					App\Payroll\MC::where('id',$value->id)
	// 						  			->update([
	// 										  		'sa' => $sa,
	// 												'la' => $la
	// 									  ]);

	// 			break;
	// 			}
	// 	}
	// 	else
	// 	{
	// 		"Missing : ".$value->userid."<br/>";
	// 	}
	// }

});


Route::get('update-salary-mc', function () {
	// $mc = App\Payroll\MC::where('payroll_mon',1)->where('payroll_yr',2022)->get();

	// foreach ($mc as $key => $value) {
	// 		//GET SALARY
	// 		$plantilla = getPlantillaInfo($value->empcode);

	// 		//UPDATE SALARY
	// 		App\Payroll\MC::where('id',$value->id)
	// 			->update([
	// 				"salary" => $plantilla['plantilla_salary']
	// 			]);
	// }
		
});

Route::get('update-salary', function () {
	$user = App\User::whereIn('employment_id',[1,13,14,15])->orderBy('username')->get();

	foreach ($user as $key => $users) {
		$n = getStaffInfo($users->id,'fullname');
		$plantilla = getPlantillaInfo($users->username);

		//SG
		$salary = App\SalaryTable::where('salary_grade',$plantilla['salary_grade'])->where('version',2)->first();

		if($salary)
		{
			//STEP
			switch ($plantilla['plantilla_step']) {
				case 1:
					$sal = $salary['salary_1'];
				break;
				case 2:
					$sal = $salary['salary_2'];
				break;
				case 3:
					$sal = $salary['salary_3'];
				break;
				case 4:
					$sal = $salary['salary_4'];
				break;
				case 5:
					$sal = $salary['salary_5'];
				break;
				case 6:
					$sal = $salary['salary_6'];
				break;
				case 7:
					$sal = $salary['salary_7'];
				break;
				case 8:
					$sal = $salary['salary_8'];
				break;
			}
		}
		else
		{
			$sal = "N/A";
		}
		
		//OLD
		$userid = $users->id;
		$username = $users->username;
		$plantilla_division = $users->division;
		$plantilla_item_number = $plantilla['plantilla_item_number'];
		$position_id = $plantilla['position_id'];
		$designation_id = $plantilla['designation_id'];
		$plantilla_step = $plantilla['plantilla_step'];
		$employment_id = $plantilla['employment_id'];
		$plantilla_salary = $plantilla['plantilla_salary'];
		$plantilla_date_from = $plantilla['plantilla_date_from'];
		$plantilla_date_to = $plantilla['plantilla_date_to'];
		$plantilla_special = $plantilla['plantilla_special'];



		echo $n."<br/>";
		echo "OLD SALARY : ".$plantilla['plantilla_salary']."<br/>";
		echo "SALARY GRADE : ".$plantilla['salary_grade']."<br/>";
		echo "STEP : ".$plantilla['plantilla_step']."<br/>";
		echo "NEW SALARY : ".$sal."<br/><br/>";
		//echo "---userid : ".$plantilla."<br/><br/>";



	// 	echo "---NEW SALARY : ".$sal."<br/>";

		//CREATE NEW
		// $new_plantilla = new App\Plantilla;
		// $new_plantilla->user_id = $userid;
		// $new_plantilla->username = $username;
		// $new_plantilla->plantilla_division = $plantilla_division;
		// $new_plantilla->plantilla_item_number = $plantilla_item_number;
		// $new_plantilla->position_id = $position_id;
		// $new_plantilla->designation_id = $designation_id;
		// $new_plantilla->plantilla_step = $plantilla_step;
		// $new_plantilla->employment_id = $employment_id;
		// $new_plantilla->plantilla_salary = $sal;
		// $new_plantilla->plantilla_date_from = "2023-01-12";
		// //$new_plantilla->plantilla_date_to = $plantilla_date_to;
		// $new_plantilla->plantilla_special = $plantilla_special;
		// $new_plantilla->plantilla_remarks = "increased";
		// $new_plantilla->save();
		

	}
});


Route::get('update-sic', function () {
	// $user = App\User::whereIn("employment_id",[1,11,13,14,15])->get();

	// foreach ($user as $key => $users) {
	// 	//GET SIC FROM TABLE
	// 	foreach(getDeductions($users->username) AS $values)
	// 	{
	// 		//NEW SIC
	// 		$plantilla = getPlantillaInfo($users->username);

	// 		$sic = $plantilla['plantilla_salary'] * 0.09;

	// 		$diff = $sic - $values->deductAmount;

	// 		if($values->deductID == 2)
	// 		{
	// 			echo $users->username." - ".$values->deductAmount." - ".$sic." = ".$diff."<br/>";
	// 			$adj = new App\Payroll\SICAdj;
	// 			$adj->empcode = $users->username;
	// 			$adj->amount = $diff;
	// 			$adj->save();
	// 		}
				

	// 	}
	// }

	// $salary = 24136.96;

	// for ($i=1; $i <= 4 ; $i++) {
	// 	$salary = getSalaryWeek("DIA003",38150.00,$i,1);

	// 	echo $salary."<br/>";
	// }

	// $plantilla = getPlantillaInfo("CAL004");

	// return $plantilla;
		
});

Route::get('update-mc', function () {

	// $sala = App\Payroll\MC1::where('payroll_mon',5)->where('payroll_yr',2022)->get();

	// foreach ($sala as $key => $salas) {
		
	// 	$sa = $salas->sa - 300;

	// 	//UPDATE MC
	// 	$mc = App\Payroll\MC::where('userid',$salas->userid)->where('payroll_mon',5)->where('payroll_yr',2022)
	// 						->update([
	// 							'sa' => $sa
	// 						]);
	// }
		
});

Route::get('update-sala', function () {

	// $mon = 8;
	// $m2 = 07;
	// $y2 = 2022;

	// $sala = App\Payroll\MC::where('payroll_mon',$mon)->where('payroll_yr',$y2)->get();

	// //return $sala;

	// foreach($sala AS $list)
	// {

	// 	$l1_total2 = 0;

    //     $l1_total = App\Request_leave::where('user_id',$list->userid)->whereNull('parent_leave')->whereNotNull('parent_leave_code')->whereNotIn('leave_id',[5,16,19])->where('leave_action_status','Approved')->whereMonth('leave_date_from',$m2)->whereYear('leave_date_from',$y2)->get();
    //         foreach ($l1_total as $k1 => $v1) {
    //             if(!checkIfWeekend($v1->leave_date_from))
    //             {
    //                 if($v1->leave_action_status == 'Approved')
    //                 {   
    //                     $l1_total2++;
    //                 }
    //             }
    //         }

    //         //SINGLE DATE (WHOLEDAY)
    //         $l2_total = App\Request_leave::where('user_id',$list->userid)->where('parent','YES')->where('leave_deduction','<=',1)->whereNotIn('leave_id',[5,16,19])->where('leave_action_status','Approved')->whereMonth('leave_date_from',$m2)->whereYear('leave_date_from',$y2)->where('leave_deduction',1)->get();
    //         foreach ($l2_total as $k2 => $v2) {
    //             if(!checkIfWeekend($v2->leave_date_from))
    //             {
    //                 if($v2->leave_action_status == 'Approved')
    //                 { 
    //                     $l1_total2++;
    //                 }
    //             }
    //         }

    //         $l_total = count($l1_total) + count($l2_total);


    //         //SINGLE DATE (HALFDAY)
    //         $leaveHalfDates = "";
    //         $l3_total = App\Request_leave::where('user_id',$list->userid)->where('parent','YES')->where('leave_deduction','<=',1)->whereNotIn('leave_id',[5,16,19])->where('leave_action_status','Approved')->whereMonth('leave_date_from',$m2)->whereYear('leave_date_from',$y2)->where('leave_deduction',0.5)->get();
            
    //         foreach ($l3_total as $k3 => $v3) {
    //             if(!checkIfWeekend($v3->leave_date_from))
    //             {
    //                 $leaveHalfDates .= date('d',strtotime($v3->leave_date_from)).", ";
    //             }
    //         }


    //         //TARDY
    //         // $tardy_total = 0;
    //         // $tardy_total_half = 0;
    //         // $tardy = App\Employee_tardy::where('user_id',$userid)->whereMonth('fldEmpDTRdate',$m2)->whereYear('fldEmpDTRdate',$y2)->get();
            
    //         // foreach ($tardy as $trk => $trs) {
    //         //     if(!checkIfWeekend($trs->fldEmpDTRdate))
    //         //         {
    //         //             if(!checkIfHoliday($trs->fldEmpDTRdate))
    //         //                 $tardy_total += $trs['to_total_day'];
    //         //         }
    //         // }



    //         //TO PER DIEM YES MULTIPLE
    //         $perdiemYesDates = "";
    //         $l4_total = App\RequestTO::where('userid',$list->userid)->whereNull('parent')->whereNotNull('parent_to_code')->where('to_perdiem','YES')->where('to_status','Approved')->whereMonth('to_date_from',$m2)->whereYear('to_date_from',$y2)->get();

    //         $t_to_y_0 = 0;
    //         foreach ($l4_total as $k4 => $v4) {
    //             if(!checkIfWeekend($v4->to_date_from))
    //             {
    //                 if(!checkIfHoliday($v4->to_date_from))
    //                     $t_to_y_0 += $v4['to_total_day'];
    //             }
    //         }


    //         //TO PER DIEM YES SINGLE DATE
    //         $l5_total = App\RequestTO::where('userid',$list->userid)->where('parent','YES')->whereIn('to_total_day',[1.0,0.5])->where('to_perdiem','YES')->where('to_status','Approved')->whereMonth('to_date_from',$m2)->whereYear('to_date_from',$y2)->get();

    //         $t_to_y_1 = 0;
    //         foreach ($l5_total as $k5 => $v5) {
    //             if(!checkIfWeekend($v5->to_date_from))
    //             {   
    //                 if(!checkIfHoliday($v5->to_date_from))
    //                     $t_to_y_1 += $v5['to_total_day'];
    //             }
    //         }


    //         //TO PER DIEM YES MULTIPLE
    //         // $perdiemNoDates = "";
    //         // $l6_total = App\RequestTO::where('userid',$userid)->whereNull('parent')->whereNotNull('parent_to_code')->where('to_perdiem','NO')->where('to_status','Approved')->whereMonth('to_date_from',$m2)->whereYear('to_date_from',$y2)->get();

    //         // foreach ($l6_total as $k6 => $v6) {
    //         //     if(!checkIfWeekend($v6->to_date_from))
    //         //     {
    //         //         $perdiemNoDates .= date('d',strtotime($v6->to_date_from)).", ";
    //         //     }
    //         // }


    //         //TO PER DIEM YES SINGLE DATE
    //         // $l7_total = App\RequestTO::where('userid',$userid)->where('parent','YES')->whereIn('to_total_day',[1.0,0.5])->where('to_perdiem','NO')->where('to_status','Approved')->whereMonth('to_date_from',$m2)->whereYear('to_date_from',$y2)->get();
    //         // foreach ($l7_total as $k7 => $v7) {
    //         //     if(!checkIfWeekend($v7->to_date_from))
    //         //     {
    //         //         $perdiemNoDates .= date('d',strtotime($v7->to_date_from)).", ";
    //         //     }
	// 		// }
		
	// 	//TARDY
	// 	$tardy_total = 0;
	// 	$tardy_total_list = App\Employee_tardy::where('user_id',$list->userid)->whereMonth('fldEmpDTRdate',$m2)->whereYear('fldEmpDTRdate',$y2)->get();

	// 	foreach ($tardy_total_list as $key => $tardies) {
	// 		$tardy_total += $tardies->total_day;
	// 	}
		

	// 	$l_total = ($l1_total2 + $tardy_total) + (count($l3_total) * 0.5) + $t_to_y_0 + $t_to_y_1;


    //     //$l_total = $l1_total + $l2_total + $total_to + $total_tardy;

    //     $sa_amt = getWorkingDate($y2.'-'.$mon.'-01');

	// 	$sa = $sa_amt - ($l_total * 150);
    //     //$sa = $salas->sa_amt - ($l_total * 150);
    //     $l_1 = $l1_total2 + $tardy_total;
    //     $l_2 = count($l3_total) * 0.5;
    //     $la = 500 - ((500 / 22) * ($l_1 + $l_2));

	// 	if($sa < 0)
	// 	{
	// 		$sa = 0;
	// 	}

	// 	if($la < 0)
	// 	{
	// 		$la = 0;
	// 	}

	// 	App\Payroll\MC::where('id',$list->id)
	// 						->update([
	// 							'sa' => $sa,
	// 							'la' => $la,
	// 						]);

	// 	echo $list->empcode." ID : ".$list->id." SA : $sa -- LA : $la TOTAL : $l_total<hr>";
	// }

	// App\Payroll\MC::where('sa',150)
	// 						->update([
	// 							'sa' => 0,
	// 						]);
	
	// App\Payroll\MC::where('la',22.73)
	// 						->update([
	// 							'la' => 0,
	// 						]);
});



Route::get('monitor-armrd/EzUt1cg19i', function () {
   
   $user = App\User::where('id',22)->first();

        // return request()->yr2;

        if(checkifIcos($user['id']))
        {
            $dtr = App\Employee_icos_dtr::whereYear('fldEmpDTRdate',2022)->whereMonth('fldEmpDTRdate',3)->where('user_id',$user['id'])->get();
        }
        else
        {
            $dtr = App\Employee_dtr::whereYear('fldEmpDTRdate',2022)->whereMonth('fldEmpDTRdate',3)->where('user_id',$user['id'])->get();
        }

        // return $dtr;

        $data = [
                    'date' => date('F',mktime(0, 0, 0, 3, 10)).' 2022',
                    'user' => $user['lname'] . ', ' . $user['fname'],
                    'dtr' => $dtr,
                    'userid' => $user['id'],
                ];

   return view('armrd-monitor')->with('data',$data);
});

Route::post('monitor-armrd/EzUt1cg19i', function () {
   
   $user = App\User::where('id',request()->userid)->first();

        // return request()->yr2;

        if(checkifIcos($user['id']))
        {
            $dtr = App\Employee_icos_dtr::whereYear('fldEmpDTRdate',2022)->whereMonth('fldEmpDTRdate',3)->where('user_id',$user['id'])->get();
        }
        else
        {
            $dtr = App\Employee_dtr::whereYear('fldEmpDTRdate',2022)->whereMonth('fldEmpDTRdate',3)->where('user_id',$user['id'])->get();
        }

        // return $dtr;

        $data = [
                    'date' => date('F',mktime(0, 0, 0, 3, 10)).'2022',
                    'user' => $user['lname'] . ', ' . $user['fname'],
                    'dtr' => $dtr,
                    'userid' => $user['id'],
                ];

   return view('armrd-monitor')->with('data',$data);
});


use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
Route::get('delete-payroll-text', function () {
	$path = storage_path('app/payroll/2023-02_FEB');
	// // File::deleteDirectory($path);
	//$response = Storage::deleteDirectory($path);
	//dd($response);

	Storage::deleteDirectory($path);

	//$response = File::delete($path.'/*.txt');
	//$response = File::delete($path.'/PCFEBWK2_1674718119.txt');
	//$response = File::delete($path.'/PCFEBWK3_1674718121.txt');
	//$response = File::delete($path.'/PCFEBWK4_1674718124.txt');

	//Storage::move('/usr/home/hrms/storage/app/payroll/2023-01_JAN/PCJANWK4_1673316918.txt', '/usr/home/hrms/storage/app/payroll/PCJANWK4.txt');

	// $response = File::delete($path.'/PCAUGWK2.txt');
});


Route::get('get-salary', function () {
	//return getPayrollList2();
});


Route::get('batch-mc2', function () {
	//return getWorkingDate('2022-08-01');
	// ini_set('memory_limit', '512M');
    // ini_set('max_execution_time', 180);
	// $mon = 3;
	// $year = 2023;

	// $prevmon = 7;
	// $prevyr = 2022;

	$list = App\Payroll\MC::where('payroll_mon',3)->where('payroll_yr',2023)->get();

	// foreach($list AS $lists)
	// {
	// 	//$total_deduc_lv = 0;
	// 	//GET ALL DEDUCTION
	
	// 	//$tardy_total = getUnderTime($lists->userid,$lists->empcode,$prevmon,$prevyr,$lists->process_code);
	// 	//$total_deduc_lv += $tardy_total;
	// 	if($tardy_total != '')
	// 	{
	// 		$user = App\User::where('username',$lists->empcode)->first();
	// 		echo "EMPCODE : ".$user['lname'].','.$user['fname']." TOTAL DEDUCTION : ".$tardy_total."<hr>";
	// 	}
		
	// }

	foreach($list AS $lists)
	{
		$hp = $lists->hprate;
		if($hp == 0.3)
			$hp = 0.15;

		if($hp == 0.6)
			$hp = 0.3;
		App\Payroll\MC::where('id',$lists->id)->update([ 'hprate' => $hp ]);
	}	
		
});



Route::get('test-ph/{val}', function ($val) {
	return computePhil($val);
});


Route::get('reverse-dtr-all', function () {
	// $mon = 5;
	// $year = 2022;

	// $dtr = App\DTRProcessed::where('dtr_mon',$mon)->where('dtr_year',$year)->get();

	// foreach ($dtr as $key => $dtrs) {
	// 	$code = $dtrs->process_code;

	// 	//DELETE PROCESSED
    //     App\DTRProcessed::where('process_code',$code)->delete();

    //     //DELETE SALA
    //     App\Employee_sala::where('process_code',$code)->delete();

    //     //DELETE MC
    //     App\Payroll\MC::where('process_code',$code)->delete();

    //     //DELETE MC DAYS
    //     App\Payroll\MCDays::where('process_code',$code)->delete();

    //     //DELETE HP
    //     App\Employee_hp::where('process_code',$code)->delete();

    //     //DELETE LEAVES EARNED/DEDUCTED
    //     App\Employee_leave::where('process_code',$code)->delete(); 

    //     //RETURN STATUS LEAVES
    //     App\Request_leave::where('process_code',$code)->update(["process_code" => null]); 

    //     //RETURN STATUS T.O
    //     App\RequestTO::where('process_code',$code)->update(["process_code" => null]);

    //     //TARDY
    //     App\Employee_tardy::where('process_code',$code)->delete();
	// }
});


Route::get('dtr/monitoring/J6JblRa9yz', function () {

	$list = App\View_dtr_monitor::where('fldEmpDTRdate',date('Y-m-d'))->orderBy('employee_name')->get();

	$data = [
				'dt' => date('Y-m-d'),
				'list' => $list 
			];
	return view('dtr_monitoring')->with('data',$data);
});

Route::get('dtr/monitoring/J6JblRa9yz/{date}', function ($dt) {

	$list = App\View_dtr_monitor::where('fldEmpDTRdate',$dt)->orderBy('employee_name')->get();

	$data = [
		'dt' => $dt,
		'list' => $list 
	];

	return view('dtr_monitoring')->with('data',$data);
});

Route::get('dtr/monitoring/J6JblRa9yz-COS', function () {

	$list = App\View_dtr_monitor_cos::where('fldEmpDTRdate',date('Y-m-d'))->orderBy('division')->orderBy('employee_name')->get();

	$data = [
				'dt' => date('Y-m-d'),
				'list' => $list 
			];
	return view('dtr_monitoring_cos')->with('data',$data);
});

Route::get('dtr/monitoring/J6JblRa9yz-COS/{date}', function ($dt) {

	$list = App\View_dtr_monitor_cos::where('fldEmpDTRdate',$dt)->orderBy('division')->orderBy('employee_name')->get();

	$data = [
		'dt' => $dt,
		'list' => $list 
	];

	return view('dtr_monitoring_cos')->with('data',$data);
});


Route::get('test-cto', function () {
	// $lv = App\Request_leave::where('leave_id',5)
	// 						->where('leave_action_status','Approved')
	// 						->whereMonth('leave_date_from','<',10)
	// 						->whereYear('leave_date_from','<=',2022)
	// 						->whereNull('process_code')->get();
	// foreach ($lv as $key => $value) {

	// 	//GET MONTH/YEAR
	// 	$mon = date('m',strtotime($value->leave_date_from));
	// 	$yr = date('Y',strtotime($value->leave_date_from));

	// 	//GET PROCESS CODE
	// 	$pc = App\DTRProcessed::where('empcode',$value->empcode)->where('dtr_mon',$mon)->where('dtr_year',$yr)->first();

	// 	//UPDATE
	// 	if($pc)
	// 	{
	// 		//echo $value->id." - ".$pc['process_code']."<br>";

	// 		App\Request_leave::where('id',$value->id)
	// 						  ->update([
	// 							"process_code" => $pc['process_code']
	// 						  ]);
	// 	}
	// }
});

// Route::get('get-cto-earn/{div}/{yr}', function ($div,$yr) {

// 	$list = App\RequestOT::where('cto','YES')->where('ot_status','Approved')->where('division',$div)->whereYear('ot_date',$yr)->get(); 
// 	$data = [
// 		"division" => $div,
// 		"year" => $yr,
// 		'list' => $list
// 	];
// 	return view('get-cto-earn')->with('data',$data);
// });

// Route::get('nov-10', function () {

// 	$list = App\Employee_dtr::where('fldEmpDTRdate','2022-11-10')->get(); 
	
// 	echo "<table>";
// 	foreach($list AS $lists)
// 	{
// 		$mc = App\Payroll\MC::where('empcode',$lists->fldEmpCode)->where('payroll_mon',12)->where('payroll_yr',2022)->first();

// 		//GET DIVISION
// 		$dv = App\User::where('username',$lists->fldEmpCode)->first();
// 		$dv = getDivision($dv['division']);
		
// 		//UPDATE SA
// 		if($mc['sa'] == 0)
// 			$sa = 0;
// 		else
// 			$sa = $mc['sa'] - 150;

// 		App\Payroll\MC::where('id',$mc['id'])->update(['sa' => $sa]);

// 		echo "<tr><td>".$lists->employee_name."</td><td>".$mc['id']."</td></tr>";
// 	}
// 	echo "</table>";
// });


Route::get('view-leave', function () {

	$list = App\Request_leave::whereMonth('leave_date_from','<=',11)->whereYear('leave_date_from',2022)->whereIn('leave_action_status',['Approved','Processed'])->whereNull('process_code')->where('parent','YES')->get();
	
	echo "<table>";
	foreach($list AS $lists)
	{

		//GET DIVISION
		$dv = App\User::where('username',$lists->empcode)->first();
		//$dvs = getDivision($dv['division']);

		//GET MONTH
		$mon = date('m',strtotime($lists->leave_date_from));
		$yr = date('Y',strtotime($lists->leave_date_from));

		//GET PROCESS CODE
		
		if($lists->empcode)
		{
			$processcode = App\DTRProcessed::where('empcode',$lists->empcode)->where('dtr_mon',$mon)->where('dtr_year',$yr)->first();
			if($processcode)
				$procode = $processcode['process_code'];
			else
				$procode = "ERROR";
		}
		else
		{
			$procode = "ERROR";
		}

		//UPDATE LEAVE
		App\Request_leave::where('id',$lists->id)->update(["process_code" => $procode]);


		echo "<tr><td>".$lists->empcode."</td><td>".$lists->leave_date_from." - ".$lists->leave_date_to."</td><td>".$mon."-".$yr."</td><td>".$procode."</td></tr>";
	}
	echo "</table>";
});

Route::get('working-days', function () {
	return getWorkingDate('2023-07-01');
});



Route::get('update-leave-balance/{year}', function ($yr) {
	$list = App\User::where('employment_id',1)->orderBy('lname')->orderBy('fname')->get();

	$data = [
		'list' => $list,
		'yr' => $yr
	];

	return view('update-leave-balance')->with('data',$data);
});


Route::get('update-leave-balance-process', function () {

	// for ($y = 2020; $y <= 2023 ; $y++) { 
	// 	for($x = 1;$x <= 12; $x++)
	// 	{
	// 		$proc = App\DTRProcessed::where('dtr_mon',$x)->where('dtr_year',$y)->get();

	// 		foreach ($proc as $key => $value) {
	// 			$tbl = new App\Employee_update_leave;
	// 			$tbl->userid = $value->userid;
	// 			$tbl->mon = $value->dtr_mon;
	// 			$tbl->yr = $y;
	// 			$tbl->vl_bal = $value->vl_bal;
	// 			$tbl->save();
	// 		}
	// 	}
	// }
		
});

Route::post('update-leave-balance-process-post', 'ApplicantController@gelud');

Route::get('fake-names', function () {
	$faker = Faker\Factory::create();

    // $limit = 10;
    // for ($i = 0; $i < $limit; $i++) {
    // echo $faker->name . '<br/>';
    // }

	// $user = App\User::whereNull('remember_token')->get();

	// foreach ($user as $key => $users) {
	// 	# code...
	// 	$fakename = $faker->name;
	// 	$ctr_name = explode(" ",$fakename);
	// 	if(count($ctr_name) == 2)
	// 	{

	// 		$int = rand(0,51);
	// 		$a_z = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	// 		$rand_letter = $a_z[$int];

	// 		echo $faker->name . ' --'.$users->id.'<br/>';
	// 		App\User::where('id',$users->id)
	// 					->update([
	// 						'fname' => $ctr_name[0],
	// 						'lname' => $ctr_name[1],
	// 						'mname' => strtoupper($rand_letter),
	// 						'remember_token' => 1,
	// 					]);
	// 	}
	// }

});


Route::get('get-pending', function () {
	$bal = getPending2(1,262);

	//$bal = getPending(1,262);

	return $bal;
});


Route::get('terminal-leave', function () {
	echo "LEAVE BALANCE AS OF DECEMBER 2022<br><table border='1'><thead><th>Name</th><th>Age</th><th>VL</th><th>SL</th></thead><tbody>";
	$user = App\View_users_with_age::where('age','>=',60)->orderBy('age','DESC')->get();
	foreach ($user as $key => $value) {

		//GET VL/SL
		$code = App\DTRProcessed::where('userid',$value->id)->where('dtr_mon',12)->where('dtr_year',2022)->first();
		$code = $code['process_code'];

		$vl = App\Employee_leave::where('user_id',$value->id)->where('leave_id',1)->where('process_code',$code)->first();
		$sl = App\Employee_leave::where('user_id',$value->id)->where('leave_id',2)->where('process_code',$code)->first();

		if(isset($vl))
		{
			$vl = $vl['leave_bal'];
		}
		else
		{
			$vl = "-";
		}

		if(isset($sl))
		{
			$sl = $sl['leave_bal'];
		}
		else
		{
			$sl = "-";
		}

		echo "<tr><td>".$value->lname.", ".$value->fname." ".$value->mname."</td>";
		echo "<td>".$value->age."</td>";
		echo "<td>".$vl."</td>";
		echo "<td>".$sl."</td></tr>";
	}
	echo "</tbody></table>";
});

Route::get('show-cos-list', function () {
	$list = App\Payroll\COS_list::orderBy('Column_1')->get();

	foreach ($list as $key => $value) {
		$emp = explode(', ',$value->Column_1);

		if($emp[1])
		{
			$fname = substr($emp[1],0,-2);
			$mname = substr($emp[1],-2);
		}
		else
		{
			$fname = "ERROR";
			$mname = "ERROR";
		}
		

		//GET EMPCODE
		$user = App\User::where('lname',$emp[0])->whereIn('employment_id',[8,5])->first();
		if(!isset($user))
		{
			$username = "EEROR";
			$userid = null;
		}
		else
		{
			$username = $user['username'];
			$userid = $user['id'];
		}


		// //UPDATE
		// App\Payroll\COS_list::where('id',$value->id)
		// 			->update(
		// 					[
		// 						"user_id" => $userid,
		// 						"empcode" => $username,
		// 					]
		// 			

		//GET POSITION CODE
		// $ps = App\Position::where('position_desc',$value->position)->first();

		// if(!isset($ps))
		// {
		// 	$posi = $value->position." NOT FOUND";
		// }
		// else
		// {
		// 	$posi = $ps['position_desc'];
		// 	$posid = $ps['position_id'];
		// }

		// //UPDATE
		// App\Payroll\COS_list::where('id',$value->id)
		// 			->update(
		// 					[
		// 						"position_id" => $posid,
		// 					]
		// 				);

		//UPDATE
		// App\Payroll\SalaryCOS::where('user_id',$userid)
		// 			->update(
		// 					[
		// 						"ors" => $value->Column_4,
		// 					]
		// 				);


		// echo $emp[0].','.$fname." ".$mname." -- ".$value->Column_2." -- ".$value->Column_3."<br/>";
	}
});


Route::get('check-mc', function () {
	$list = App\Payroll\MC::where('payroll_mon',6)->where('payroll_yr',2023)->orderBy('empcode')->get();

	echo "<table border='1'><thead><th>EMPCODE</th><th>SA</th><th>LP</th><th>DEDUC</th><th>AMOUNT(SA/LP)</th></thead>";
	foreach ($list as $key => $value) {

		// $comment = "";
		$deduc = App\SALA_DEDUC::where('empcode',$value->empcode)->first();
		$val = 0;
		$deduc_val = 0;
		if($deduc)
		{
			if($value['sa'] <= 0)
			{
				$val = $value['lp'] - $deduc_val;
				App\Payroll\MC::where('id',$value->id)->update(['lp' => $val,"remarks" => "Deduction sa mga present sa office pero kumain..NO S.A kaya sa LP binawas..Orig LP - ".$value['lp']." amount na ibabawas - ".$deduc_val." bagong amount ng LP : ".$val]);
			}
			else
			{
				$val = $value['sa'] - $deduc['deduc'];
				$deduc_val = $deduc['deduc'];

				App\Payroll\MC::where('id',$value->id)->update(['sa' => $val,"remarks" => "Deduction sa mga present sa office pero kumain..Orig SA - ".$value['sa']." amount na ibabawas - ".$deduc_val." bagong amount ng SA : ".$val]);
			}

		}
		

		echo "<tr><td>".$value->empcode."</td><td align='right'>".$value['sa']."</td><td align='right'>".$value['lp']."</td><td align='right'>".$deduc_val."</td><td align='right'>".$val."</td>";

	}

	echo "</table>";
});

Route::get('update-cos-hdmf', function () {
	// $list = App\User::where('employment_id',8)->get();

	// foreach ($list as $key => $value) {
	// 	$list = App\Payroll\DeductionCOS::where('deduction','HDMF')->where('user_id',$value->id)->first();
	// 	if(isset($list))
	// 	{
	// 		//
	// 	}
	// 	else
	// 	{
	// 		$deduc = new App\Payroll\DeductionCOS;
	// 		$deduc->user_id = $value->id;
	// 		$deduc->deduction = 'HDMF';
	// 		$deduc->amt = 200;
	// 		$deduc->period = 1;
	// 		$deduc->created_by = 999;
	// 		$deduc->save();
	// 	}
	// }
});

Route::get('test-round', function () {
	$num = 23035.165290323;
	echo "EARNED : ".$num."<br/>";
	echo "CEILING : ".(ceil($num * 100) / 100)."<br/>";
	echo "ROUND : ".round($num,2)."<br/>";
});

Route::get('user-birthday-json', function () {
	$user = App\User::select('lname','fname','mname','exname','birthdate','image_path')->whereMonth('birthdate',date('m'))->whereDay('birthdate',date('d'))->whereIn('employment_id',[1,5,8,15])->orderBy('birthdate')->get()->toJson();
	//$user = App\User::select('lname','fname','mname','exname','birthdate','image_path')->whereMonth('birthdate',1)->whereIn('employment_id',[1,5,8,15])->orderBy('birthdate')->get()->toJson();
	return $user;
});

Route::get('user-birthday', function () {
	return view('user-birthday');
});


Route::get('get-position', function () {
	$user = App\User::where('employment_id',[1,13,14,15])->orderBy('lname')->orderBy('fname')->get();

	echo "<table border='1'><thead><tr><th>Name</th><th>Item</th><th>Salary Grade</th></tr></thead>";
	foreach ($user as $key => $users) {

		$plantilla = App\Plantilla::where('username',$users->username)->orderBy('plantilla_date_from','DESC')->first();
		$position = App\Position::where('position_id',$plantilla['position_id'])->first();

		echo "<tr><td>".$users->lname.", ".$users->fname."</td><td>".$position['position_desc']."</td><td>".$position['stepincrement_id']."</td></tr>";
	}
	echo "</table>";
});
