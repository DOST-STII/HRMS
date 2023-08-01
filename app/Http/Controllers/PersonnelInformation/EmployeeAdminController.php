<?php

namespace App\Http\Controllers\PersonnelInformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App;
use Auth;
use DB;

use Illuminate\Support\Facades\Mail;
use App\Mail\RegretLetterNonPC;
use App\Mail\RegretLetterNonPC3;
use App\Mail\RegretLetterPC;
use App\Mail\AlertVacant;
use Illuminate\Support\Facades\App as FacadesApp;

class EmployeeAdminController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['auth','admin']);
    }

    public function index()
    {
    	$data = [
                    "nav" => nav("myprofile"),
                    "division" => App\Division::orderBy('division_acro')->get(),
                    "position" => App\Position::orderBy('position_abbr')->get(),
                    "employment" => App\Employment::orderBy('employment_id')->get(),
                    "designation" => App\Designation::orderBy('designation_abbr')->get(),
                ];

        return view('pis.admin.add-new-employee')->with("data",$data);
    }

    public function hire()
    {
        $data = [
                    "nav" => nav("myprofile"),
                    "employment" => App\Employment::whereIn('employment_id',[1,13,14])->orderBy('employment_id')->get(),
                    "designation" => App\Designation::orderBy('designation_abbr')->get(),
                    "applicant" => App\View_job_application::where('id',request()->job_application_id)->first(),
                ];

        return view('pis.admin.hire-new-employee')->with("data",$data);
    }

    public function dashboard($div)
    {

        $data = [
                        "nav" => nav("dashboard"),
                        "division" => $div,
                ];

        return view('pis.admin.index2')->with("data",$data);
    }

    public function dashboard2($div)
    {
        // $employees = new App\View_user;
        // $employees = $employees->get();

        $data = [
                        "nav" => nav("dashboard"),
                        "division" => $div,
                ];

        return view('pis.admin.index3')->with("data",$data);
    }

    public function updateview($id)
    {
        $users = App\User::where('id',$id)->first();

        switch ($users['employment_id']) {
            case 1:
            case 13:
            case 14:
            $emp = App\User::where('id',$id)->first();

            $data = [
                    "nav" => nav("myprofile"),
                    "empinfo" => $emp,
                    "rfid" => $users['rfid'],
                    "division" => App\Division::orderBy('division_acro')->get(),
                    "position" => App\Position::orderBy('position_abbr')->get(),
                    "employment" => App\Employment::whereNotIn('employment_id',[9,10,12])->orderBy('employment_id')->get(),
                    "designation" => App\Designation::orderBy('designation_abbr')->get(),
                    "positionemp" => App\Plantilla::where('user_id',$emp->id)->first()
                ];
            break;
            default:
                # code...
            $emp = App\View_users_temp::where('id',$id)->first();

            $data = [
                    "nav" => nav("myprofile"),
                    "empinfo" => $emp,
                    "rfid" => $users['rfid'],
                    "division" => App\Division::orderBy('division_acro')->get(),
                    "position" => App\Position::orderBy('position_abbr')->get(),
                    "employment" => App\Employment::whereNotIn('employment_id',[9,10,12])->orderBy('employment_id')->get(),
                    "designation" => App\Designation::orderBy('designation_abbr')->get(),
                    "workemp" => App\Employee_work::where('user_id',$emp->id)->first(),
                    'salary' => App\Payroll\COS_Payroll::where('user_id',$emp->id)->first()
                ];

                break;
        }
        

        return view('pis.admin.update-employee')->with("data",$data);
    }

    public function create()
    {
        $error_unq = false;
        $error_msg = "";

        //CHECK UNIQUE RFID
        $rf = App\User::where('rfid',request()->rfid)->count();
        if($rf > 0)
        {
            $error_unq = true;
            $error_msg .= "RFID ALREADY IN USE<br/>";
        }

        //CHECK UNIQUE RFID
        $un = App\User::where('username',request()->empcode)->count();
        if($un > 0)
        {
            $error_unq = true;
            $error_msg .= "USERNAME ALREADY IN USE<br/>";
        }

        if($error_unq)
        {
            return view('error-message')->with('error_message',$error_msg);
        }

        //PICTURE
        $path = "photos/add-photo.png";
        if(request()->hasFile('photo'))
        {
            $path = request()->file('photo')->store('photos');
        }
        

    	$employee = new App\User;
    	$employee->username = request()->empcode;
        $employee->password = bcrypt('qweasdzxc');
    	$employee->division = request()->division;
    	$employee->usertype = request()->usertype;
        $employee->rfid = request()->rfid;
    	$employee->lname = request()->lname;
    	$employee->fname = request()->fname;
    	$employee->mname = request()->mname;
    	$employee->exname = request()->exname;
        $employee->employment_id = request()->employmentstatus;
        $employee->fldservice = request()->fldservice;
        $employee->image_path = $path;
    	$employee->save();
        $user_id = $employee->id;


        switch (request()->employmentstatus) {
            case "2":
            case "3":
            case "5":
            case "6":
            case "7":
            case "8":
                # code...

                $position = new App\Employee_temp_position;
                $position->user_id = $user_id;
                $position->workexp_date_from = request()->fldservice;
                $position->position_id = request()->position;
                $position->division_id = request()->division;
                $position->designation_id = request()->designation;
                $position->workexp_salary = request()->salary;
                $position->employment_id = request()->employmentstatus;
                $position->workexp_admin = Auth::user()->id;
                $position->save();

                //FOR HISTORY PUPROSES
                $position_history = new App\Employee_temp_position_history;
                $position_history->user_id = $user_id;
                $position_history->workexp_date_from = request()->fldservice;
                $position_history->position_id = request()->position;
                $position_history->division_id = request()->division;
                $position_history->designation_id = request()->designation;
                $position_history->workexp_salary = request()->salary;
                $position_history->employment_id = request()->employmentstatus;
                $position_history->workexp_admin = Auth::user()->id;
                $position_history->save();

                //PAYROL FOR COS
                $payroll = new App\Payroll\COS_Payroll;
                $payroll->user_id = $user_id;
                $payroll->empcode = request()->empcode;
                $payroll->position_id = request()->position;
                $payroll->salary = request()->salary;
                $payroll->atm = request()->atm;
                $payroll->ors = request()->ors;
                $payroll->charging = request()->charging;
                $payroll->tax_rate = request()->taxrate;
                $payroll->save();


            break;
            
            default:
                 //EMPLOYEE DIVISION HISTORY
                // $empdiv = new App\EmployeeDivision;
                // $empdiv->username = request()->empcode;
                // $empdiv->division_id = request()->division;
                // $empdiv->designation_id = request()->designation;
                // $empdiv->position_id = request()->position;
                // $empdiv->emp_div_special = request()->special;
                // $empdiv->emp_desig_from = request()->fdservice;
                // $empdiv->save();

                //EMPINFO
                $emp = new App\Employee_basicinfo;
                $emp->user_id = $user_id;
                $emp->empcode = request()->empcode;
                $emp->save();

                $emp = new App\Employee_addinfo;
                $emp->user_id = $user_id;
                $emp->empcode = request()->empcode;
                $emp->save();

                $emp = new App\Employee_address_permanent;
                $emp->user_id = $user_id;
                $emp->empcode = request()->empcode;
                $emp->save();

                $emp = new App\Employee_address_residential;
                $emp->user_id = $user_id;
                $emp->empcode = request()->empcode;
                $emp->save();

                $emp = new App\Employee_contact;
                $emp->user_id = $user_id;
                $emp->empcode = request()->empcode;
                $emp->save();

                $emp = new App\Employee_family;
                $emp->user_id = $user_id;
                $emp->empcode = request()->empcode;
                $emp->save();

               // PLANTILLA
                $plantilla = new App\Plantilla;
                $plantilla->username = request()->empcode;
                $plantilla->plantilla_item_number = request()->plantilla_item_number;
                $plantilla->plantilla_division = request()->division;
                $plantilla->position_id = request()->position;
                $plantilla->employment_id = request()->employmentstatus;
                $plantilla->plantilla_step = request()->steps;
                $plantilla->plantilla_special = request()->special;
                $plantilla->plantilla_date_from = request()->fldservice;
                $plantilla->plantilla_salary = request()->salary;
                $plantilla->plantilla_remarks = request()->remarks;
                $plantilla->save();

                // PLANTILLA HISTORY
                $plantilla_history = new App\Plantillas_history;
                $plantilla_history->username = request()->empcode;
                $plantilla_history->plantilla_item_number = request()->item_number;
                $plantilla_history->plantilla_division = request()->division;
                $plantilla_history->position_id = request()->position;
                $plantilla_history->employment_id = request()->employmentstatus;
                $plantilla_history->plantilla_step = request()->steps;
                $plantilla_history->plantilla_special = request()->special;
                $plantilla_history->plantilla_date_from = request()->fldservice;
                $plantilla_history->plantilla_salary = request()->salary;
                $plantilla_history->plantilla_remarks = request()->remarks;
                $plantilla_history->save();
            break;
        }
    }

    public function newhire()
    {

        //GET APPLICANT'S DETAIL
        $app = App\View_job_application::where('id',request()->job_application_id)->first();


        //PICTURE
        $path = $app['file_photo'];
        

        $employee = new App\User;
        $employee->username = request()->empcode;
        $employee->password = bcrypt('qweasdzxc');
        $employee->division = request()->division;
        $employee->usertype = request()->usertype;
        $employee->lname = $app['lname'];
        $employee->fname = $app['fname'];
        $employee->mname = $app['mname'];
        $employee->email = $app['email'];
        $employee->exname = request()->exname;
        $employee->employment_id = request()->employmentstatus;
        $employee->fldservice = request()->fldservice;
        $employee->image_path = $path;
        $employee->save();
        $user_id = $employee->id;


        // PLANTILLA
        $plantilla = new App\Plantilla;
        $plantilla->user_id = $user_id;
        $plantilla->username = request()->empcode;
        $plantilla->plantilla_item_number = $app['plantilla_item_number'];
        $plantilla->plantilla_division = $app['division_id'];
        $plantilla->position_id = $app['position_id'];
        $plantilla->employment_id = request()->employmentstatus;
        $plantilla->plantilla_step = $app['plantilla_steps'];
        $plantilla->plantilla_special = $app['plantilla_special'];
        $plantilla->plantilla_date_from = request()->fldservice;
        $plantilla->plantilla_salary = $app['plantilla_salary'];
        $plantilla->save();

        // PLANTILLA HISTORY
        $plantilla_history = new App\Plantillas_history;
        $plantilla_history->user_id = $user_id;
        $plantilla_history->username = request()->empcode;
        $plantilla_history->plantilla_item_number = $app['plantilla_item_number'];
        $plantilla_history->plantilla_division = $app['division_id'];
        $plantilla_history->position_id = $app['position_id'];
        $plantilla_history->employment_id = request()->employmentstatus;
        $plantilla_history->plantilla_step = $app['plantilla_steps'];
        $plantilla_history->plantilla_special = $app['plantilla_special'];
        $plantilla_history->plantilla_date_from = request()->fldservice;
        $plantilla_history->plantilla_salary = $app['plantilla_salary'];
        $plantilla_history->save();

        //INSERT BLANK ROW FOR THESE TABLES, KASI AYAW NG SERVER NG WALANG VALUE
        
        

        //UPDATE STATUS OF APPLICANT BEFORE DELETING
        App\Applicant_position_apply::where('id',$app['id'])->where('vacant_plantilla_id',$app['vacant_plantilla_id'])->update(['hired' => 'YES']);

        //EMAIL OTHERS
        $applicant = App\Applicant_position_apply::where('vacant_plantilla_id',$app['vacant_plantilla_id'])->where('div_shortlisted','YES')->whereNull('hired')->get();

        foreach ($applicant as $value) {
            //GET PLANTILLA INFO
            $plantilla = App\View_vacant_plantilla::where('id',$app['vacant_plantilla_id'])->first();
            //GET EMAIL
            $data = [
                        "position" => $plantilla['position_desc'],
                        "division" => $plantilla['division_acro'],
                    ];
            $appli = App\Applicant::where('id',$value->id)->first();

            if($value->pcaarrd == 'YES')
                {
                    Mail::to($appli['email'])->send(new RegretLetterPC($data));
                }
                else
                {
                    Mail::to($appli['email'])->send(new RegretLetterNonPC3($data));
                }
        }

        //UPLOAD FILE
        $file_appointment = null;
        if(request()->hasFile('file_appointment'))
        {
            $file_appointment = request()->file('file_appointment')->store('applicant_appointment');
        }

        $file_oath = null;
        if(request()->hasFile('file_oath'))
        {
            $file_oath = request()->file('file_oath')->store('applicant_oath');
        }

        $file_duty = null;
        if(request()->hasFile('file_duty'))
        {
            $file_duty = request()->file('file_duty')->store('applicant_duty');
        }
        
        //UPDATE FILE AND ASSIGN PERMANENT ID
        App\Applicant::where('id',$app['id'])
                        ->update([
                                    'user_id' => $user_id,
                                    'file_appointment' =>  $file_appointment,
                                    'file_oath' =>  $file_oath,
                                    'file_duty' =>  $file_duty,
                                ]);

        //CLOSED REQUEST
        App\Request_for_hiring::where('id',$app['request_id'])->update(['request_status' => 'Closed', 'deleted_at' => date('Y-m-d H:i:s')]);

        //DELETE VACANT POSITION
        App\Vacant_plantilla::where('id',$app['vacant_plantilla_id'])->delete();

        //DELETE APPLICATION
        App\Applicant_position_apply::where('id',$app['id'])->delete();
    }

    public function transferhire()
    {

        //GET APPLICANT'S DETAIL
        $app = App\View_job_application::where('id',request()->job_application_id)->first();

        //PICTURE
        $path = $app['file_photo'];

        //UPDATE USER
        App\User::where('id',request()->user_id)
                  ->update([
                                "division" => $app['division_id'],
                                "employment_id" => request()->employment,
                                "usertype" => request()->usertype,
                                "image_path" => $path,
                           ]);

        //GET PREVIUOS PLANTILLA FIRST AND PUT TO THE VACANT POSITION
        $plantilla_old = App\Plantilla::where('user_id',request()->user_id)->first();

        $vacant_plantilla = new App\Vacant_plantilla;
        $vacant_plantilla->plantilla_item_number = $plantilla_old['plantilla_item_number'];
        $vacant_plantilla->position_id = $plantilla_old['position_id'];
        $vacant_plantilla->division_id = $plantilla_old['division_id'];
        $vacant_plantilla->plantilla_salary = $plantilla_old['plantilla_salary'];
        $vacant_plantilla->plantilla_special = $plantilla_old['plantilla_special'];
        $vacant_plantilla->plantilla_steps = $plantilla_old['plantilla_steps'];
        $vacant_plantilla->save();


        //UPDATE PLANTILLA
        App\Plantilla::where('user_id',request()->user_id)
                        ->orderBy('id','desc')
                        ->take(1)
                        ->update([
                                    "plantilla_division" => $app['division_id'],
                                    "plantilla_item_number" => $app['plantilla_item_number'],
                                    "designation_id" => $app['designation'],
                                    "position_id" => $app['position_id'],
                                    "employment_id" => request()->employment,
                                    "plantilla_salary" => $app['plantilla_salary'],
                                    "plantilla_date_from" => request()->fldservice,
                                    "plantilla_date_to" => NULL,
                                ]);


        //UPDATE OLD PLANTILLA
        App\Plantillas_history::where('user_id',request()->user_id)
                        ->orderBy('plantilla_date_from','desc')
                        ->take(1)
                        ->update([
                                    "plantilla_date_to" => request()->fldservice,
                                ]);

        // PLANTILLA HISTORY
        $plantilla_history = new App\Plantillas_history;
        $plantilla_history->user_id = request()->user_id;
        $plantilla_history->plantilla_item_number = $app['plantilla_item_number'];
        $plantilla_history->plantilla_division = $app['division_id'];
        $plantilla_history->position_id = $app['position_id'];
        $plantilla_history->employment_id = request()->employment;
        $plantilla_history->plantilla_step = $app['plantilla_steps'];
        $plantilla_history->plantilla_special = $app['plantilla_special'];
        $plantilla_history->plantilla_date_from = request()->fldservice;
        $plantilla_history->plantilla_salary = $app['plantilla_salary'];
        $plantilla_history->save();

        //DELETE VACANT POSITION
        App\Vacant_plantilla::where('id',$app['vacant_plantilla_id'])->delete();

        //DELETE APPLICATION
        App\Applicant_position_apply::where('id',$app['id'])->delete();

        return redirect('list-of-employees');
    }

    public function delete()
    {
        $employee = new App\User;
        $employee = $employee
                        ->where('id',request()->tbl_name_id)
                        ->delete();
    }

    public function update()
    {
        $error_unq = false;
        $error_msg = "";

        //CHECK UNIQUE RFID
        $rf = App\User::where('rfid',request()->rfid)->first();
        if(isset($rf))
        {
            if($rf['id'] != request()->userid)
            {
                $error_unq = true;
                $error_msg .= "RFID ALREADY IN USE<br/>";
            }
        }
        
        

        //CHECK UNIQUE RFID
        $un = App\User::where('username',request()->empcode)->first();
        if(isset($un))
        {
            if($un['id'] != request()->userid)
            {
                $error_unq = true;
                $error_msg .= "USERNAME ALREADY IN USE<br/>";
            }
        }

        if($error_unq)
        {
            return view('error-message')->with('error_message',$error_msg);
        }

        //PICTURE
        $path = request()->origphoto;
        if(request()->hasFile('photo'))
        {
            $path = request()->file('photo')->store('photos');
        }
        

        $user = new App\User;
        $user = $user
                        ->where('id',request()->userid)
                        ->update([
                                    'username' => request()->empcode,
                                    'lname' => request()->lname,
                                    'fname' => request()->fname,
                                    'mname' => request()->mname,
                                    'exname' => request()->exname,
                                    'rfid' => request()->rfid,
                                    'division' => request()->division,
                                    'usertype' => request()->usertype,
                                    'image_path' => $path,
                                ]);
        //CGECK IF COS
        $user2 = App\User::where('id',request()->userid)->first();

        switch ($user2['employment_id']) {
            case 2:
            case 3:
            case 5:
            case 6:
            case 7:
            case 8:
                //UPDATE SALARY
                $salary = App\Payroll\COS_Payroll::where('user_id',request()->userid)
                        ->update([
                            'salary' => request()->salary,
                            'ors' => request()->ors,
                            'atm' => request()->atm,
                            'charging' => request()->charging,
                            'tax_rate' => request()->taxrate,
                            'position_id' => request()->position,
                        ]);
                
            break;
        }

        // switch (request()->employmentstatus) {
        //     case "2":
        //     case "3":
        //     case "5":
        //     case "6":
        //     case "7":
        //     case "8":
        //         # code...
        //         $workemp = new App\Employee_temp_position;
        //         $workemp = $workemp
        //                 ->where('user_id',request()->userid)
        //                 ->update([
        //                             'position_id' => request()->position,
        //                             'employment_id' => request()->employmentstatus,
        //                             'designation_id' => request()->designation,
        //                             'workexp_salary' => request()->salary,
        //                             'employment_id' => request()->employmentstatus,
        //                         ]);

        //         $workemp_history = new App\Employee_temp_position_history;
        //         $workemp_history = $workemp_history
        //                 ->where('user_id',request()->userid)
        //                 ->orderBy('id','desc')
        //                 ->take(1)
        //                 ->update([
        //                             'position_id' => request()->position,
        //                             'employment_id' => request()->employmentstatus,
        //                             'designation_id' => request()->designation,
        //                             'workexp_salary' => request()->salary,
        //                             'employment_id' => request()->employmentstatus,
        //                         ]);

        //     break;
            
        //     default:

            

        //         $plantilla = new App\Plantilla;
        //         $plantilla = $plantilla
        //                 ->where('user_id',request()->userid)
        //                 ->update([
        //                             'plantilla_item_number' => request()->item_number,
        //                             'plantilla_division' => request()->division,
        //                             'position_id' => request()->position,
        //                             'designation_id' => request()->designation,
        //                             'employment_id' => request()->employmentstatus,
        //                             'plantilla_step' => request()->steps,
        //                             'plantilla_special' => request()->special,
        //                             'plantilla_date_from' => request()->fldservice,
        //                             'plantilla_salary' => request()->salary,
        //                             'plantilla_remarks' => request()->remarks
        //                         ]);

        //         $plantilla_history = new App\Plantillas_history;
        //         $plantilla_history = $plantilla_history
        //                 ->where('user_id',request()->userid)
        //                 ->orderBy('id','desc')
        //                 ->take(1)
        //                 ->update([
        //                             'plantilla_item_number' => request()->item_number,
        //                             'plantilla_division' => request()->division,
        //                             'position_id' => request()->position,
        //                             'designation_id' => request()->designation,
        //                             'employment_id' => request()->employmentstatus,
        //                             'plantilla_step' => request()->steps,
        //                             'plantilla_special' => request()->special,
        //                             'plantilla_date_from' => request()->fldservice,
        //                             'plantilla_salary' => request()->salary,
        //                             'plantilla_remarks' => request()->remarks
        //                         ]);

        //         // EMAIL DIRECTORS
        //         $data = [
        //                     "position" => request()->item_number,
        //                     "division" => getDivision(request()->division),
        //                 ];
        //         // $appli = App\Applicant::where('id',$value->id)->first();

        //         Mail::to('diaz.mark.anthony@gmail.com')->send(new AlertVacant($data));

        //     break;
        // }


        return redirect('update-employee/'.request()->userid);


        // $userdiv = new App\Employee_division;
        // $userdiv = $userdiv
        //                 ->where('username',request()->empcode)
        //                 ->update([
        //                             'division_id' => request()->division,
        //                          ]);
    }

    public function resetpassword()
    {
        $user = new App\User;
        $user = $user
                        ->where('id',request()->tbl_name_id)
                        ->update([
                                    'password' => bcrypt('qweasdzxc')
                                ]);
    }

    public function transfer()
    {
        $user = new App\User;
        $user = $user
                        ->where('id',request()->tbl_name_id)
                        ->update([
                                    'division' => request()->transfer_division,
                                ]);
        //UPDATE RECENT DIVISION
        $user_div = DB::select("UPDATE employee_divisions SET emp_desig_to = '".request()->transfer_division_date."' WHERE user_id = ".request()->tbl_name_id." ORDER BY id DESC LIMIT 1");

        //ADD DIVISION EMPLOYEE HISTORY
        $emp_div = new App\Employee_division;
        $emp_div->user_id = request()->tbl_name_id;
        $emp_div->division_id = request()->transfer_division;
        $emp_div->emp_desig_from = request()->transfer_division_date;
        $emp_div->designation_id = request()->transfer_division_desig;
        $emp_div->save();

    }

    public function changestatus()
    {
        $user = new App\User;
        $user = $user
                        ->where('id',request()->tbl_name_id)
                        ->update([
                                    'employment_id' => request()->employmentstatus
                                ]);

        //IF REGULAR THEN NAMATAY, RETIRED OR RESIGN PUPUNTA SA VACANT TABLE ANG POSITION NYA
        switch (request()->employmentstatus) {
            case 9:
            case 10:
            case 12:
                # code...
                    //GET ITEM FROM PLANTILLA
                    $plantilla = App\Plantilla::where('user_id',request()->tbl_name_id)->first();

                    $plantilla_vacant = new App\Vacant_plantilla;
                    $plantilla_vacant->plantilla_item_number = $plantilla['plantilla_item_number'];
                    $plantilla_vacant->plantilla_salary = $plantilla['plantilla_salary'];
                    $plantilla_vacant->position_id = $plantilla['position_id'];
                    $plantilla_vacant->plantilla_special = $plantilla['plantilla_special'];
                    $plantilla_vacant->plantilla_steps = $plantilla['plantilla_step'];
                    $plantilla_vacant->save();

                    // EMAIL DIRECTORS
                    $data = [
                                "position" => $plantilla['plantilla_item_number'],
                                "division" => getDivision($plantilla['plantilla_division']),
                            ];
                    // $appli = App\Applicant::where('id',$value->id)->first();

                    Mail::to('diaz.mark.anthony@gmail.com')->send(new AlertVacant($data));

                break;
            }
    }
}
