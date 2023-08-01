<?php

namespace App\Http\Controllers\AttendanceMonitoring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Auth;
use Carbon\Carbon;

class LeaveController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function apply()
    {
        $leave = new App\Employee_leave_apply;
        $leave->user_id = Auth::user()->id;
        $leave->save();
    }

    public function getPending($id)
    {
        $leave = App\Request_leave::where('user_id',Auth::user()->id)->where('leave_id',$id)->update(['deleted_by' => Auth::user()->fname." ".Auth::user()->lname ]);


        $leave = App\Request_leave::where('user_id',Auth::user()->id)->where('leave_id',$id)->get();
        return json_encode($leave);
    }

    public function cancelLeave($id)
    {
        try {
              App\Request_leave::where('user_id',Auth::user()->id)->where('id',$id)->delete();

              return redirect('/');
            } catch (\Exception $e)
            {
               return redirect('/');
            }
    }

    public function request() 
    {
        return view('dtr.request-leave');
    }


    public function send() 
    {
        // return request()->leave_duration;
        $leaveid = request()->leave_id;

        return $leaveid." --error";
        
        $no_nega = true;

        if($leaveid  == 1 || $leaveid  == 2)
        {
            $no_nega = false;
        }


        $duration = explode('-',request()->leave_duration2);

        if($leaveid  == 1 || $leaveid  == 3 || $leaveid  == 2 || $leaveid  == 6)
        {
            $duration = explode('-',request()->leave_duration);
        }
        elseif($leaveid  == 5 || $leaveid == 16)
        {
            $duration = explode('-',request()->leave_duration3);
        } 
        elseif($leaveid  == 7 || $leaveid == 9)
        {
            $duration = explode('-',request()->leave_duration4);
        }  

        //return $duration;   

            //CHECK REMAINING BALANCE
            //CHECK HAS LEAVE BALANCE, WAG LANG VL AT SL
            $from = Carbon::parse($duration[0]);
            $from_orig = Carbon::parse($duration[0]);
            $to = Carbon::parse($duration[1]);

            $diff = 1+($from->diffInDays($to));

            $bal = getLeaves(request()->userid2,$leaveid);
            $pending = getPending($leaveid);
            $projected = $bal - $pending;
            $rem_bal = $projected - $diff;

            //COVERT NEGATIVE TO POSITIVE
            $rem_bal_check = abs($rem_bal);

            $msg = "
                        LEAVE : ID ".$leaveid."<br/>
                        DATE FROM : ".$from."<br/>
                        DATE TO : ".$to."<br/>
                        DIFFERENCE : ".$diff."<br/>
                        BALANCE : ".$bal."<br/>
                        PENDING : ".$pending."<br/>
                        PROJECTED : ".$projected."<br/>
                        LWOP : ".$rem_bal_check."<br/>
                    ";

            // return $msg;

        if($leaveid == 1)
        {        
            if($rem_bal < 0)
            {
                if($diff == $rem_bal_check)
                {
                    // return "all are LWOP";
                    $this->createLeave(true,1,$from,$to);
                }
                else
                {
                    $lwop = $rem_bal_check;
                    // return "LWOP ang ay " .$rem_bal_check;
                }
            }
            else
            {
                $last_date = $this->createLeave(false,1,$from,$to);
            }
        }
        elseif($leaveid == 2)
        {
            if($rem_bal < 0)
            {
                if($diff == $rem_bal_check)
                {
                    // return "all are LWOP";

                    //CHECK IF MAY VL PA
                    $bal2 = getLeaves(request()->userid2,1);
                    $pending2 = getPending(1);
                    $projected2 = $bal2 - $pending2;

                    $diff2 = 1+($from->diffInDays($to));

                    $rem_bal2 = $projected2 - $diff2;

                    //CHECK IF KUNG ILAN MATITIRA SA FOR SLWOP

                    if($rem_bal2 >= 0)
                        $this->createLeave(false,1,$from,$to); 
                    elseif($rem_bal2 < 0) 
                    {
                        $rem_bal_check2 = abs($rem_bal2);

                        if($diff2 == $rem_bal_check2)
                        {
                            $this->createLeave(true,2,$from,$to);
                        }
                        else
                        {
                            $totaldays2 = 0;
                            $code2 = randomCode(15);
                            $orig_from = date('Y-m-d',strtotime($from));
                            for($i = 1; $i < $rem_bal_check2; $i++)
                                {
                                    if($i == 1)
                                        {
                                            $dt = date('Y-m-d',strtotime($from));
                                        }
                                        else
                                        {
                                                $dt = $from->addDays(1);
                                            
                                        }

                                        if(!$this->checkIfWeekend($dt))
                                            {
                                                if(!checkIfHoliday($dt))
                                                {
                                                    if(!checkIfHasLeave($dt,request()->userid2))
                                                    {
                                                        $lwop = null;
                                                        
                                                        $this->addLeave(1,date('Y-m-d',strtotime($dt)),null,'multiple',1,null,$code2,null,$lwop,1,null,request()->vl_select,request()->vl_select_specify,request()->sl_select,request()->sl_select_specify,request()->wfh_reason,request()->wfh_output);

                                                        $last_date = date('Y-m-d',strtotime($dt));
                                                        
                                                        $totaldays2++;
                                                    }
                                                }

                                            }
                                }
                                $this->addLeave(1,$orig_from,$last_date,'multiple',$totaldays2,$code2,$code2,'YES',null,null,request()->vl_select,request()->vl_select_specify,request()->sl_select,request()->sl_select_specify,request()->wfh_reason,request()->wfh_output);

                                $last_date = Carbon::parse($last_date);
                                $last_date = $last_date->addDays(1);

                                $this->createLeave(true,2,$last_date,$to);
                        }

                    }                
                    else
                        $this->createLeave(true,2,$from,$to);
                }
                else
                {
                    $code2 = randomCode(15);
                    $lwop = $rem_bal_check;
                    $totaldays2 = 0;
                    $orig_from = date('Y-m-d',strtotime($from));

                        for($i = 1; $i <= $rem_bal_check; $i++)
                            {
                                if($i == 1)
                                    {
                                        $dt = date('Y-m-d',strtotime($from));
                                    }
                                    else
                                    {
                                            $dt = $from->addDays(1);
                                        
                                    }

                                    if(!$this->checkIfWeekend($dt))
                                        {
                                            if(!checkIfHoliday($dt))
                                            {
                                                if(!checkIfHasLeave($dt,request()->userid2))
                                                {
                                                    $this->addLeave(2,date('Y-m-d',strtotime($dt)),null,'multiple',1,null,$code2,null,'Sick Leave',1,null,request()->vl_select,request()->vl_select_specify,request()->sl_select,request()->sl_select_specify,request()->wfh_reason,request()->wfh_output);

                                                    $last_date = date('Y-m-d',strtotime($dt));
                                                    
                                                    $totaldays2++;
                                                }
                                            }

                                        }
                                
                            }
                            $this->addLeave(2,$orig_from,$last_date,'multiple',$totaldays2,$code2,$code2,'YES','YES',null,request()->vl_select,request()->vl_select_specify,request()->sl_select,request()->sl_select_specify,request()->wfh_reason,request()->wfh_output);

                            $last_date = Carbon::parse($last_date);
                            $last_date = $last_date->addDays(1);

                            $this->createLeave(false,2,$last_date,$to);
                }
            }
            else
            {
                $this->createLeave(false,2,$from,$to);          
            }
        }
        else
        {
            
            $from = Carbon::parse($duration[0]);
            $to = Carbon::parse($duration[1]);


            $diff = 1+($from->diffInDays($to));

            if($diff == 1)
            {
                if(request()->leave_time == 'AM' || request()->leave_time == 'PM')
                {
                    $diff = .5;
                }
            }

            $pending = getPending($leaveid,request()->userid2);
            $projected = $bal - $pending;

            $rem_bal = $projected - $diff;

            // return "Leave ID : ".$leaveid."<br/>Balance : ".$bal."<br>Pending : ".$pending."<br>Projected : ".$projected."<br/>Remaining : ".$rem_bal;

            if($rem_bal >= 0)
            {
                $this->createLeave(false,$leaveid,$from,$to,request()->leave_time); 
            }
            else
            {
                // return "Pag nakikita nyo to.. pls ignore may tinetest po ako.. - Mark ".$diff;
                return view('error-message')->with('error_message','Not enough leave balance..');
            }    
        }
        return redirect('/');
    }

    private function createLeave($lwop,$leaveid,$from,$to)
    {
        $from_orig = Carbon::parse($from);
        $from = Carbon::parse($from);
        $to = Carbon::parse($to);
        $diff = 1+($from->diffInDays($to));

        if($lwop)
        {
            $code = randomCode(15);
            
            //CHECK IF SINGLE DATE
            if($diff == 1)
            {
                if($leaveid == 1)
                    {
                        $lwop = "Vacation Leave";
                    }
                    elseif($leaveid == 2)
                    {
                        $lwop = "Sick Leave";
                    }

                $this->addLeave($leaveid,date('Y-m-d',strtotime($from)),date('Y-m-d',strtotime($to)),request()->leave_time,null,$code,$code,'YES',$lwop,null,request()->vl_select,request()->vl_select_specify,request()->sl_select,request()->sl_select_specify,request()->wfh_reason,request()->wfh_output);

                return $to;
            }
            else
            {    
                $totaldays = 0;
                
                $last_date = null; 

                for($i = 1; $i <= $diff; $i++)
                {

                    if($i == 1)
                        {
                            $dt = date('Y-m-d',strtotime($from));
                        }
                        else
                        {
                                $dt_main = $from->addDays(1);
                                $dt = $dt_main;
                            
                        }

                        if(!$this->checkIfWeekend($dt))
                            {
                                if(!checkIfHoliday($dt))
                                {
                                    if(!checkIfHasLeave($dt,request()->userid2))
                                    {
                                        // $lwp = 'NO';
                                        // if($leaves_loop <= 0)
                                        //     {
                                        //        $lwp = 'YES';
                                        //     }
                                        $lwop = null;
                                        $lwop_parent = null;
                                        if($leaveid == 1)
                                            $lwop = "Vacation Leave";
                                        elseif($leaveid == 2)
                                            $lwop = "Sick Leave";
                                        
                                        $this->addLeave($leaveid,date('Y-m-d',strtotime($dt)),null,'multiple',1,null,$code,null,$lwop,$totaldays,null,request()->vl_select,request()->vl_select_specify,request()->sl_select,request()->sl_select_specify,request()->wfh_reason,request()->wfh_output);

                                        $last_date = date('Y-m-d',strtotime($dt));
                                        
                                        $totaldays++;
                                    }
                                }

                            }
                    
                }
                // $lwop = countDiffLeave($leaves,$totaldays);

                $this->addLeave($leaveid,$from_orig,$last_date,'multiple',$totaldays,$code,$code,'YES','YES',null,request()->vl_select,request()->vl_select_specify,request()->sl_select,request()->sl_select_specify,request()->wfh_reason,request()->wfh_output);

                return $last_date;
            }
        }
        else
        {
            $code = randomCode(15);
            $last_date = null;
            //CHECK IF SINGLE DATE
            if($diff == 1)
            {
                $lwop = null;
                $this->addLeave($leaveid,date('Y-m-d',strtotime($from)),date('Y-m-d',strtotime($to)),request()->leave_time,null,$code,$code,'YES',$lwop,null,request()->vl_select,request()->vl_select_specify,request()->sl_select,request()->sl_select_specify,request()->wfh_reason,request()->wfh_output);

                $last_date = $to;
            }
            else
            {    
                $totaldays = 0;
                
                for($i = 1; $i <= $diff; $i++)
                {

                    if($i == 1)
                        {
                            $dt = date('Y-m-d',strtotime($from));
                        }
                        else
                        {
                                $dt_main = $from->addDays(1);
                                $dt = $dt_main;
                            
                        }

                        if($leaveid == 7 || $leaveid == 9)
                        {
                            $lwop = null;
                                        
                            $this->addLeave($leaveid,date('Y-m-d',strtotime($dt)),null,'multiple',1,null,$code,null,$lwop,$totaldays,null,request()->vl_select,request()->vl_select_specify,request()->sl_select,request()->sl_select_specify,request()->wfh_reason,request()->wfh_output);

                            $last_date = date('Y-m-d',strtotime($dt));
                                        
                            $totaldays++;
                        }
                        else
                        {
                           if(!$this->checkIfWeekend($dt))
                            {
                                if(!checkIfHoliday($dt))
                                {
                                    if(!checkIfHasLeave($dt,request()->userid2))
                                    {
                                        $lwop = null;
                                        
                                        $this->addLeave($leaveid,date('Y-m-d',strtotime($dt)),null,'multiple',1,null,$code,null,$lwop,$totaldays,null,request()->vl_select,request()->vl_select_specify,request()->sl_select,request()->sl_select_specify,request()->wfh_reason,request()->wfh_output);

                                        $last_date = date('Y-m-d',strtotime($dt));
                                        
                                        $totaldays++;
                                    }
                                }

                            } 
                        }
                        
                    
                }

                $this->addLeave($leaveid,$from_orig,$last_date,'multiple',$totaldays,$code,$code,'YES',null,null,request()->vl_select,request()->vl_select_specify,request()->sl_select,request()->sl_select_specify,request()->wfh_reason,request()->wfh_output);

                return $last_date;

            }
        }
    }

    private function addLeave($leaveid,$dt1,$dt2,$type,$dduc,$code1,$code2,$parent,$lwop,$rem = null,$vl_select = null,$vl_select_specify = null,$sl_select = null,$sl_select_specify = null,$wfh_reason = null,$wfh_output = null)
    {
            //GET DURATION
            switch ($type) {
                case 'wholeday':
                        $deduc = 1;
                    break;
                case 'multiple':
                        $deduc = $dduc;
                    break;
                default:
                        $deduc = 0.5;
                    break;
            }

            //GET USER INFO
            $user = App\User::where('id',request()->userid2)->first();

            //CHARGE VL TO FL 
            // $leaveid = request()->leave_id;
            
            //IF DIRECTOR
            $director = 'NO';
            if($user['usertype'] == 'Director')
                $director = 'YES';

            $request = new App\Request_leave;
            // $request->user_id = Auth::user()->id;
            $request->user_id = $user['id'];
            $request->empcode = $user['username'];
            $request->director = $director;
            $request->user_div = $user['division'];
            $request->leave_date_from = $dt1;
            $request->leave_date_to = $dt2;

            $request->parent = $parent;
            $request->parent_leave = $code1;
            $request->parent_leave_code = $code2;
            
            $request->leave_id = $leaveid;
            $request->leave_deduction = $deduc;
            $request->leave_deduction_time = request()->leave_time;

            // $request->leave_remarks = $rem;

            $request->lwop = $lwop;

            //ADDITIONAL INFO
            if($leaveid == 1)
            {
                $request->vl_select = $vl_select;
                if(isset($vl_select_specify))
                {
                    $request->vl_select_specify = $vl_select_specify;
                }
                
            }
            elseif($leaveid == 2)
            {
                $request->sl_select = $sl_select;
                if(isset($sl_select_specify))
                {
                    $request->sl_select_specify = $sl_select_specify;
                }
            }
            elseif($leaveid == 16)
            {
                $request->wfh_reason = $wfh_reason;
                $request->wfh_output = $wfh_output;
            }

            $request->save();

            $tblid = $request->id;

            $dt = $dt1 ."-".$dt2;
            
            if($dt1 == $dt2)
                $dt = $dt1;

            add_history_leave($user['id'],request()->leave_id,$tblid,$dt,'Requested');
        
    }

    public function checkIfWeekend($dt)
    {
        $dt = Carbon::parse($dt);

        if($dt->isWeekend())
            return true;
        else
            return false;
    }

    private function checkFL($userid)
    {
        $fl = getLeaves($userid,6);
        return $fl;
    }

    public function updateLeave()
    {

        if(request()->leave_list_1 == 5)
        {

            //GET PREVIUOS CTO
            $cto_last = App\Employee_cto::where('user_id',request()->emp_list_1)->orderBy('created_at','DESC')->first();
            $cto_last = $cto_last['cto_bal'];

            $user = App\User::where('id',request()->emp_list_1)->first();
            $cto = new App\Employee_cto;
            $cto->user_id = request()->emp_list_1;
            $cto->empcode = $user['username'];
            $cto->cto_year = date('Y');
            $cto->cto_bal = $cto_last + request()->leave_bal;
            $cto->save();
        }
        else
        {
            $emp = App\User::where('id',request()->emp_list_1)->first();
            $lv = new App\Employee_leave;

            $lv->leave_id = request()->leave_list_1;
            $lv->user_id = request()->emp_list_1;
            $lv->empcode = $emp['username'];
            $lv->leave_bal = request()->leave_bal;
            $lv->leave_bal_nega = request()->leave_bal_neg;
            $lv->save();
        }
        

        return redirect('maintenance');
    }

    public function wfh()
    {
        $code = randomCode(15);
        $this->addLeave(16,date('Y-m-d',strtotime(request()->leave_duration3)),date('Y-m-d',strtotime(request()->leave_duration3)),request()->leave_time,null,$code,$code,'YES',null,null,"Within the Philippines",null,null,null,request()->wfh_reason,request()->wfh_output);
        return redirect('/');
    }
}
