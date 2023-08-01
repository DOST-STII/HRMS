<?php

namespace App\Http\Controllers\Maintenance;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App;
use Carbon\Carbon;
use Auth;

class Maintenance extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        $data = [
                    'dtr_option' => collect(App\DTROption::get()),
                    'holiday' => collect(App\Holiday::orderBy('holiday_date','DESC')->get()),
                    'suspension' => collect(App\Suspension::orderBy('fldSuspensionDate','DESC')->get()),
                    'exemption' => collect(App\User::where('dtr_exe',1)->get())
                ];

        return view('dtr.admin.maintenance')->with('data',$data);
    }

    public function workschedule()
    {
        //UPDATE LAST WS
        $ws = new App\WorkSchedule;
        $ws = $ws->where('id',request()->ws_id)
                ->update([
                            'date_to' => date('Y-m-d'),
                        ]);

        $ws = new App\WorkSchedule;
        $ws->dtr_option_id = request()->dtroptions;
        $ws->date_from = date('Y-m-d');
        $ws->save();

        return redirect('maintenance');
    }

    public function library($action)
    {
        $act = explode("-", $action);

        switch ($act[0]) {
            case 'add':
                    switch ($act[1]) {
                        case 'suspension':

                            //TIME
                            switch(request()->sus_time)
                            {
                                case "Wholeday":
                                    $tm = 8;
                                break;

                                case "AM":
                                case "PM":
                                    $tm = 4;
                                break;
                            }

                            $suspension = new App\Suspension;
                            $suspension->fldSuspensionDate =  request()->sus_date;
                            $suspension->fldSuspensionTime =  request()->sus_tm;
                            $suspension->fldMinHrs =  request()->sus_minhr;
                            $suspension->fldSuspensionRemarks =  request()->sus_remarks;
                            $suspension->suspension_time_desc =  request()->sus_time;
                            $suspension->suspension_time =  $tm;
                            $suspension->save();

                        break;
                    }
            break;
        }

        return redirect('maintenance');
    }

    public function reversedtr()
    {
        $code = request()->process_code;
        
        //DELETE PROCESSED
        App\DTRProcessed::where('process_code',$code)->delete();

        //DELETE SALA
        App\Employee_sala::where('process_code',$code)->delete();

        //DELETE MC
        App\Payroll\MC::where('process_code',$code)->delete();

        //DELETE MC DAYS
        App\Payroll\MCDays::where('process_code',$code)->delete();

        //DELETE HP
        App\Employee_hp::where('process_code',$code)->delete();

        //DELETE LEAVES EARNED/DEDUCTED
        App\Employee_leave::where('process_code',$code)->delete(); 

        //RETURN STATUS LEAVES
        App\Request_leave::where('process_code',$code)->update(["process_code" => null]); 

        //RETURN STATUS T.O
        App\RequestTO::where('process_code',$code)->update(["process_code" => null]);

        //TARDY
        App\Employee_tardy::where('process_code',$code)->delete();

        return redirect('dtr/reverse');

    }

    public function leavejson($userid)
    {
        $vl = App\Employee_leave::where('user_id',$userid)->where('leave_id',1)->orderBy('created_at','DESC')->first();
        $vl_bal = $vl['leave_bal'];

        $sl = App\Employee_leave::where('user_id',$userid)->where('leave_id',2)->orderBy('created_at','DESC')->first();
        $sl_bal = $sl['leave_bal'];

        $leave_bal = collect(['vl_bal' => $vl_bal, 'sl_bal' => $sl_bal]);

        return json_encode($leave_bal);
    }

    public function monetization()
    {
        //return request()->emp_list_12." - ".request()->prev_bal_vl." ".request()->prev_bal_sl;
        $process_code = randomCode(45);
        $parent_code = randomCode(25);

        $user = explode('|',request()->emp_list_12);
        $userid = $user[0];
        $empcode = $user[1]; 
        $div = $user[2]; 
        $ty = $user[3]; 

        $dir = 'NO';
        if($ty == 'Director')
            $dir = 'YES';
        
        $new_bal_vl = 0;
        $new_bal_sl = 0;

        //CHECK ANG IBABAWAS
        if(request()->new_bal_vl != '' || request()->new_bal_vl != 0 || request()->new_bal_vl != null)
        {
            $prev_vl = request()->prev_bal_vl;
            $new_bal_vl = request()->new_bal_vl;

            $vl_bal = $prev_vl - $new_bal_vl;

            $lv = new App\Employee_leave;
            $lv->user_id = $userid;
            $lv->empcode = $empcode;
            $lv->leave_id = 1;
            $lv->leave_bal_prev = $prev_vl;
            $lv->leave_bal = $vl_bal;
            $lv->process_code = $process_code;
            $lv->save();
            
            $req = new App\Request_leave;
            $req->user_id =  $userid;
            $req->empcode = $empcode;
            $req->leave_id = 1;
            $req->user_div = $div;
            $req->director = $dir;
            $req->leave_date_from = request()->monetize_date;
            $req->leave_date_to = request()->monetize_date;
            $req->parent = 'YES';
            $req->process_code = $process_code;
            $req->parent_leave = $parent_code;
            $req->parent_leave_code = $parent_code;
            $req->leave_action_by = Auth::user()->username;
            $req->leave_deduction = $new_bal_vl;
            $req->leave_deduction_time = 'wholeday';
            $req->leave_action_status = 'Monetized';
            $req->save();

        }


        $process_code = randomCode(45);
        $parent_code = randomCode(25);

        if(request()->new_bal_sl != '' || request()->new_bal_sl != 0 || request()->new_bal_sl != null)
        {
            $prev_sl = request()->prev_bal_sl;
            $new_bal_sl = request()->new_bal_sl;

            $sl_bal = $prev_sl - $new_bal_sl;

            $lv = new App\Employee_leave;
            $lv->user_id = $userid;
            $lv->empcode = $empcode;
            $lv->leave_id = 2;
            $lv->leave_bal_prev = $prev_sl;
            $lv->leave_bal = $sl_bal;
            $lv->process_code = $process_code;
            $lv->save();
            
            $req = new App\Request_leave;
            $req->user_id =  $userid;
            $req->empcode = $empcode;
            $req->leave_id = 2;
            $req->user_div = $div;
            $req->director = $dir;
            $req->leave_date_from = request()->monetize_date;
            $req->leave_date_to = request()->monetize_date;
            $req->parent = 'YES';
            $req->process_code = $process_code;
            $req->parent_leave = $parent_code;
            $req->parent_leave_code = $parent_code;
            $req->leave_action_by = Auth::user()->username;
            $req->leave_deduction = $new_bal_sl;
            $req->leave_deduction_time = 'wholeday';
            $req->leave_action_status = 'Monetized';
            $req->save();

        }

        return redirect('maintenance');
    }

    function monetizationcancel()
    {
        App\Employee_leave::where('process_code',request()->mon_process_code)->delete();

        App\Request_leave::where('process_code',request()->mon_process_code)->update(['deleted_by' => Auth::user()->username,'deleted_at' => date('Y-m-d H:i:s')]);
        
        return redirect('maintenance');
    }
}
