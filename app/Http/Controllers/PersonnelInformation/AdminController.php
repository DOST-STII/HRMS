<?php

namespace App\Http\Controllers\PersonnelInformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App;
use Carbon\Carbon;
use Auth;

use Illuminate\Support\Facades\Mail;
use App\Mail\SendToPSB;

use Illuminate\Support\Str;

use App\Imports\SalaryImport;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['auth','admin']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function dashboard($division)
    {
            $data = [
                        "nav" => nav("dashboard"),
                        "division" => $division,
                        'total_trainings' => App\View_total_training::orderBy('division_acro')->get(),
                    ];
            return view('pis.admin.index')->with("data",$data);
    }
    public function index()
    {
        // $employees = new App\View_user;
        // $employees = $employee   s->get();

        $data = [
                    "nav" => nav("numemp"),
                    "employee" => App\User::join('divisions', 'users.division', '=', 'divisions.division_id')->where('users.usertype', '!=', 'Administrator')->whereNotIn('users.employment_id', [9, 10, 12])->orderBy('users.username')->get(),
                    "employee_temp" => App\View_users_temp::where('usertype','!=','Administrator')->whereNotIn('employment_id',[9,10,12])->orderBy('username')->get(),
                    "employment" => App\Employment::orderBy('employment_id')->get(),
                ];

        return view('pis.admin.list-of-employee')->with("data",$data);
    }

    public function archived()
    {
        // $employees = new App\View_user;
        // $employees = $employees->get();

        $data = [
                    "nav" => nav("arvnum"),
                    "employee" => App\View_archived_user::where('usertype','!=','Administrator')->orderBy('username')->groupBy('username')->get(),
                ];

        return view('pis.admin.archived-employee')->with("data",$data);
    }

    public function applicants($id)
    {

        $data = [
                    "nav" => nav("vacant"),
                    "employment" => App\Employment::whereIn('employment_id',[1,13,14])->orderBy('employment_id')->get(),
                    "designation" => App\Designation::orderBy('designation_abbr')->get(),
                    "employee" => App\User::where('usertype','!=','Administrator')->whereNotIn('employment_id',[9,10,12])->orderBy('username')->get(),
                    "list" => App\View_job_application::where('vacant_plantilla_id',$id)->get(),
                    "detail" => App\View_vacant_plantilla::where('id',$id)->first()
                ];

        return view('pis.admin.list-of-applicant')->with("data",$data);
    }



    public function library($tbl)
    {
        $employees = new App\View_user;
        $employees = $employees->get();

        $salary = App\SalaryTable::get();
        // $salary = Excel::toCollection(new SalaryImport, storage_path('app/salarysched/'.$salarytbl['salary_filename'].'.xlsx'));
                            

        $data = [
                    "nav" => nav("pislibrary"),
                    "active_tab" => $tbl,
                    "division" => App\Division::orderBy('division_acro')->get(),
                    "position" => App\Position::orderBy('position_abbr')->get(),
                    "designation" => App\Designation::orderBy('designation_abbr')->get(),
                    "employment" => App\Employment::whereNotIn('employment_id',[9,10,12])->orderBy('employment_id')->get(),
                    "plantilla" => App\Unique_plantilla::orderBy('plantilla_item_number')->get(),
                    "salary" => $salary,
                ];

        return view('pis.admin.library')->with("data",$data);
    }

    public function retiree($div = 'ALL')
    {
        //BY DIVISION
        if($div == 'ALL')
        {
           //TOTAL RETIREES
            $user = App\Users_with_age::whereIn('employment_id',[1,15])->get();
            $total_retiree_id = array();
            foreach ($user as $users) {
                # code...
                // $age = Carbon::parse($users->birthdate)->age;
                if($users->age >= 59 &&  $users->age <= 65)
                {
                    array_push($total_retiree_id,$users->id);
                }
            }

            $user_retiree = App\Users_with_age::whereIn('id', $total_retiree_id)->orderBy('age','desc')->get();
            $division_retiree = App\View_division_retiree::whereNotNull('total')->orderBy('total','desc')->get(); 
        }
        else
        {
            //TOTAL RETIREES
            $user = App\Users_with_age::whereIn('employment_id',[1,15])->where('division',$div)->get();
            $total_retiree_id = array();
            foreach ($user as $users) {
                # code...
                // $age = Carbon::parse($users->birthdate)->age;
                if($users->age >= 60 &&  $users->age <= 65)
                {
                    array_push($total_retiree_id,$users->id);
                }
            }

            $user_retiree = App\Users_with_age::whereIn('id', $total_retiree_id)->where('division',$div)->orderBy('age','desc')->get();
            $division_retiree = App\View_division_retiree::whereNotNull('total')->orderBy('total','desc')->get();
        }
        

        $data = [
                    "nav" => nav("retiree"),
                    "retiree" => $user_retiree,
                    "division" => $division_retiree,
                ];
        return view('pis.admin.retiree')->with("data",$data);
    }


    public function servicerecord()
    {
        $data = [
                    "nav" => nav("service-record"),
                ];

        return view('pis.admin.service-record')->with("data",$data);
    }

    public function jos()
{
    $data = [
        "nav" => nav("jos"),
        "employee" => App\User::join('divisions', 'users.division', '=', 'divisions.division_id')
            ->whereIn('users.employment_id', [8, 5])
            ->whereNotIn('users.employment_id', [9, 10, 12])
            ->orderBy('users.division')
            ->get(),

        "countEmployee" => App\User::join('divisions', 'users.division', '=', 'divisions.division_id')
            ->whereIn('users.employment_id', [8, 5])
            ->whereNotIn('users.employment_id', [9, 10, 12])
            ->orderBy('users.division')
            ->select('divisions.division_acro', DB::raw('COUNT(*) as employee_count'))
            ->groupBy('divisions.division_acro')
            ->get(),
    ];

    return view('pis.admin.JO')->with("data", $data);
}

    public function requestHiring()
    {
        $data = [
                    "nav" => nav("hiring"),
                    "division" => App\Division::orderBy('division_acro')->get(),
                    "position" => App\Position::orderBy('position_abbr')->get(),
                    "request_list" => App\Request_for_hiring::whereNull('request_disapproved')->get(),
                    "training_list" => App\Employee_training_temp::whereIn('training_status',['Pending','Received'])->get()
                ];

        return view('pis.admin.request-for-hiring')->with("data",$data);
    }

    public function requestAction()
    {
        switch (request()->status) {
            case 'receive':
                $data = [
                            // 'request_status' => 'For FAD-Budget clearance',
                            'request_status' => 'Cleared from FAD-Personnel',
                            'request_seen' => null
                ];

                $status = "Cleared from Personnel, and forwarded to FAD-Budget";

                break;
            case 'approve':
                $data = [
                            'request_status' => 'Selection and Placement',
                            'request_seen' => null
                        ];
                $status = "Selection and Placement";
                break;
            case 'posted':
                $data = [
                            'request_status' => 'Posted',
                            'request_seen' => null
                        ];
                //IF THE REQUEST HAS A EXISTING PLANTILLA REMOVE FROM THE LIST AND ASSIGN TO VACANT POSITION
                if(request()->plantilla_id != "")
                {
                    App\Vacant_plantilla::where('id',request()->plantilla_id)->update([
                                            'plantilla_posted' => 1
                                        ]);
                }

                $status = "Posted";
                break;
            case 'reupload':
                $data = [
                            'request_status' => 'Waiting for reupload of Vacancy Advise',
                            'request_seen' => null
                        ];

                $status = "Reupload Vacancy Advise";
                break;
            case 'repost':
                $data = [
                            'request_status' => 'Division need to reupload Vacancy Advise',
                            'request_seen' => null
                        ];

                $history = new App\Recruitment_history;
                $history->request_id = request()->tblid;
                $history->request_status = "Tag as for reposting";
                $history->created_by = getDivision(Auth::user()->division);
                $history->userid = Auth::user()->id;
                $history->save();

                $status = "Reupload Vacancy Advise";
                break;
            case 'send-to-psb':
                $data = [
                            'request_status' => 'Sent to PSB',
                            'request_seen' => null
                        ];
                $status = "Sent list to PSB";

                //SEND EMAIL TO PSB
                $psb = App\PSB::get();

                //GENERTE TOKEN KEY FOR THE LINK
                $token = Str::random(32);

                $link =  new App\LinkToApplicant;
                $link->plantilla_id = request()->plantilla_id;
                $link->token = $token;
                $link->save();

                foreach ($psb as $value) {
                    $dt = [
                            'name' => $value->name,
                            'itemnum' => getPlantillaItemInfo('number',request()->plantilla_id),
                            'position' => getPlantillaItemInfo('position',request()->plantilla_id),
                            'division' => getPlantillaItemInfo('division',request()->plantilla_id),
                            'link' => url('list-of-applicants-for-psb/'.$token),
                          ];
                    Mail::to($value->email)->send(new SendToPSB($dt));
                }

                break;
            case 'disapprove':
                $data = [
                            'request_status' => 'Disapproved',
                            'request_seen' => null
                        ];

                break;
        }

        //UPDATE HISTORY
        $history = new App\Recruitment_history;
        $history->request_id = request()->tblid;
        $history->request_status = $status;
        $history->created_by = getDivision(Auth::user()->division);
        $history->save();

        App\Request_for_hiring::where('id',request()->tblid)->update($data);
    }

    public function requestAction2()
    {
      
                $status = "Sent list to PSB";

                //SEND EMAIL TO PSB
                $psb = App\PSB::get();

                //GENERTE TOKEN KEY FOR THE LINK
                $token = Str::random(32);

                $link =  new App\LinkToApplicant;
                $link->plantilla_id = request()->plantilla_id2;
                $link->token = $token;
                $link->save();

                foreach ($psb as $value) {
                    $dt = [
                            'name' => $value->name,
                            'itemnum' => getPlantillaItemInfo('number',request()->plantilla_id2),
                            'position' => getPlantillaItemInfo('position',request()->plantilla_id2),
                            'division' => getPlantillaItemInfo('division',request()->plantilla_id2),
                            'link' => url('list-of-applicants-for-psb/'.$token),
                            'deadline' => request()->deadline,
                          ];
                    Mail::to($value->email)->send(new SendToPSB($dt));
                }

        //UPDATE HISTORY
        $history = new App\Recruitment_history;
        $history->request_id = request()->tblid2;
        $history->request_status = $status;
        $history->created_by = getDivision(Auth::user()->division);
        $history->save();

        $data = [
                    'request_status' => 'Uploaded PSB Result',
                    'request_seen' => null
                ];

        App\Request_for_hiring::where('id',request()->tblid2)->update($data);

        return redirect('recruitment/index');
    }

    public function uploadpsb()
    {
        $path = null;
        if(request()->hasFile('psb_file'))
        {
            $path = request()->file('psb_file')->store('request_psb');
        }
        
        //ADD FILE
        $file = new App\Recruitment_file_history;
        $file->request_id = request()->request_id;
        $file->file_type = "PSB Result";
        $file->file_path = $path;
        $file->save();


        App\Request_for_hiring::where('id',request()->request_id)
                                ->update([
                                            'request_status' => 'Uploaded PSB Result',
                                            'request_seen' => null
                                        ]);

        //UPDATE HISTORY
        $history = new App\Recruitment_history;
        $history->request_id = request()->request_id;
        $history->request_status = "Uploaded PSB Result";
        $history->created_by = getDivision(Auth::user()->division);
        $history->save();

        return redirect('recruitment/index');

    }

    public function requestApprove()
    {
       $plantilla = new App\Vacant_plantilla;
       $plantilla->plantilla_item_number = request()->plantilla_item_number;
       $plantilla->plantilla_salary = request()->plantilla_salary;
       $plantilla->plantilla_special = request()->plantilla_special;
       $plantilla->plantilla_steps = request()->plantilla_steps;
       $plantilla->position_id = request()->position;
       $plantilla->division_id = request()->division;
       $plantilla->plantilla_posted = 1;
       $plantilla->save();
       $plantilla_id = $plantilla->id;

       App\Request_for_hiring::where('id',request()->tblid)
                                ->update([
                                            'plantilla_id' => $plantilla_id,
                                            'request_status' => 'Posted',
                                            'request_seen' => null,
                                            'request_approved' => date('Y-m-d H:i:s')
                                        ]);

        $history = new App\Recruitment_history;
        $history->request_id = request()->tblid;
        $history->request_status = 'Posted';
        $history->created_by = getDivision(Auth::user()->division);
        $history->save();

    }


    public function uploadPsycho()
    {
        $path_file_psycho = null;
        if(request()->hasFile('file_psycho'))
        {
            $path_file_psycho = request()->file('file_psycho')->store('applicant_psycho');
        }

        $applicant = App\Applicant::where('id',request()->applicant_id)
                    ->update([
                                "file_psycho" => $path_file_psycho
                            ]);


        return redirect('recruitment/list-of-applicants/'.request()->upload_plantilla_id.'/'.request()->upload_request_id);
    }


    public function terminalv($userid)
    {
       //USER
        $user = App\User::where('id',$userid)->first();

       //GET CURRENT SALARY
       $plantilla = getPlantillaInfo($user['username']);

       //GET LEAVE
       $vl = getLeaves($userid,1);
       $sl = getLeaves($userid,2);
       $total_lv = $vl + $sl;
       $total_lv = number_format((float)$total_lv, 3, '.', '');
       $total_amt = $plantilla['plantilla_salary'] * $total_lv * 0.0481927;
       $total_amt = number_format((float)$total_amt, 2, '.', '');

       $data =  [
                    "salary" => $plantilla['plantilla_salary'],
                    "total_lv" => $total_lv,
                    "total_amt" => formatCash($total_amt),
                ];

        return json_encode($data);
    }
}
