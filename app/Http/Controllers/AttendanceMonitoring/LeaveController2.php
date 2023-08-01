<?php

namespace App\Http\Controllers\AttendanceMonitoring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Auth;
use Carbon\Carbon;

use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemManager;
use File;

class LeaveController2 extends Controller
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
        
        $leaveid = request()->leave_id;

        //return $leaveid;

        $no_nega = true;

        if($leaveid  == 1 || $leaveid  == 2)
        {
            $no_nega = false;
        }

        if(isset(request()->wfhrequest))
        {
            $leaveid = 16;
        }

        if(isset(request()->ctorequest))
        {
            $leaveid = 5;
        }


        //return $leaveid;


        $duration = explode('-',request()->leave_duration2);

        if($leaveid  == 1 || $leaveid  == 6  || $leaveid == 10 || $leaveid == 19)
        {
            $duration = explode('-',request()->leave_duration);
        }
        elseif($leaveid == 2 || $leaveid  == 3)
        {
            $duration = explode('-',request()->leave_duration2);
        }
        elseif($leaveid == 9 || $leaveid == 7)
        {
            $duration = explode('-',request()->leave_duration3);
        }
        elseif($leaveid  == 5 || $leaveid == 16)
        {
            $duration = explode('-',request()->leave_duration4);
        }

        $remarks2 = "User ID : ".request()->userid2."<br/>".$duration[0]."-".$duration['1']."<br/>";

        $from = Carbon::parse($duration[0]);
        $from_orig = Carbon::parse($duration[0]);
        $to = Carbon::parse($duration[1]);

        $diff = $from->diffInDays($to);

        $totaldays = 0;

        $remarks2 .= "Difference Days : ".$diff."<br/>Leave ID : ".$leaveid."<br/>---Days---<br/>";

       //return $remarks2;

        for ($i=0; $i <= $diff; $i++)
        {

            if($i == 0)
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
                    if(!$this->checkIfHasLeave($dt,request()->userid2))
                    {
                        if(!$this->checkIfHasTO($dt,request()->userid2))
                        {
                            if(!$this->checkIfHasWFH($dt,request()->userid2))
                            {
                            
                                $remarks2 .= "-".date('Y-m-d',strtotime($dt))."<br/>";
                                
                                //return $remarks2;
                                $totaldays++;
                            }
                            else
                            {
                                $remarks2 .= "-".date('Y-m-d',strtotime($dt))." (WFH/Office)<br/>";

                                
                                //return $remarks2;
                            }
                        }
                        else
                        {
                            
                            $remarks2 .= "-".date('Y-m-d',strtotime($dt))." (T.O)<br/>";
                        }
                    }
                    else
                    {
                        $remarks2 .= "-".date('Y-m-d',strtotime($dt))." (Leave)<br/>";
                    }
                }
                else
                {
                    $remarks2 .= "-".date('Y-m-d',strtotime($dt))." (Holiday)<br/>";
                }
            }
            else
            {
                $remarks2 .= "-".date('Y-m-d',strtotime($dt))." (Weekend)<br/>";
            }
        }

        $remarks2 .= "Total Days : ".$totaldays."<br/>";

        //return $remarks2;

        //return request()->leave_time;
        //return $totaldays;

        //CHECK IF HALFDAY
        if($totaldays == 1)
        {
            if(request()->leave_time != 'wholeday')
                $totaldays = 0.5; 
        }

        //return $totaldays;

        //GET LEAVE BALANCE/PENDING
        $bal = getLeaves(request()->userid2,$leaveid);
        $pending = getPending($leaveid,request()->userid2);
        $projected = $bal - $pending;
        $rem_bal = $projected - $totaldays;

        //return $bal." - ".$pending;

        switch ($leaveid)
        {
            case 1:

                //SINGLE DATE
                if($totaldays == 1 || $totaldays == 0.5)
                {
                    $code = randomCode(25);

                    if($rem_bal < 0)
                    {
                        //LWOP
                        $this->addLeave($leaveid,$from_orig,$to,request()->leave_time,$totaldays,$code,$code,'YES','YES',request()->remarks,request()->vl_select,request()->vl_select_specify,request()->sl_select,request()->sl_select_specify,request()->wfh_reason,request()->wfh_output);

                        $remarks = $this->createTxt(request()->userid2,$leaveid,$from_orig,$to);
                    }
                    else
                    {
                        

                        $this->addLeave($leaveid,$from_orig,$to,request()->leave_time,$totaldays,$code,$code,'YES',null,request()->remarks,request()->vl_select,request()->vl_select_specify,request()->sl_select,request()->sl_select_specify,request()->wfh_reason,request()->wfh_output);

                        $remarks = $this->createTxt(request()->userid2,$leaveid,$from_orig,$to);
                    }
                    

                }
                //MULTIPLE
                elseif($totaldays > 1)
                {
                    
                    $code = randomCode(25);

                    //CREATE SUB REQUEST
                    $totaldeduc = $this->createLeave(request()->userid2,$leaveid,$from_orig,$to,$code);

                    //CREATE MAIN REQUEST PARA MACOUNT YUNG TOTAL
                    $this->addLeave($leaveid,$from_orig,$to,'multiple',$totaldeduc,$code,$code,'YES',null,request()->remarks,request()->vl_select,request()->vl_select_specify,request()->sl_select,request()->sl_select_specify,request()->wfh_reason,request()->wfh_output);

                    $remarks = $this->createTxt(request()->userid2,$leaveid,$from_orig,$to);

                }
            break;

            case 2:

            //SINGLE DATE
                if($totaldays == 1 || $totaldays == 0.5)
                {
                    //return "PASS";
                    $code = randomCode(25);

                    if($rem_bal < 0)
                    {
                        //LWOP
                        //CHECK IF MAY BALANCE PA SA VL
                        $bal = getLeaves(request()->userid2,1);
                        $pending = getPending(1);
                        $projected = $bal - $pending;
                        $rem_bal = $projected - $totaldays;

                        if($rem_bal > 0 || $rem_bal == 0)
                        {
                            $lvid = $this->addLeave(1,$from_orig,$to,request()->leave_time,$totaldays,$code,$code,'YES',null,request()->remarks,request()->vl_select,request()->vl_select_specify,request()->sl_select,request()->sl_select_specify,request()->wfh_reason,request()->wfh_output);
                        }
                        else
                        {
                            $lvid = $this->addLeave(2,$from_orig,$to,request()->leave_time,$totaldays,$code,$code,'YES','YES',request()->remarks,request()->vl_select,request()->vl_select_specify,request()->sl_select,request()->sl_select_specify,request()->wfh_reason,request()->wfh_output);
                        }
                        

                        $remarks = $this->createTxt(request()->userid2,$leaveid,$from_orig,$to);
                        App\Request_leave::where('id',$lvid)
                                ->update([
                                            'audit' => $remarks
                                        ]);
                    }
                    else
                    {

                        $lvid = $this->addLeave($leaveid,$from_orig,$to,request()->leave_time,$totaldays,$code,$code,'YES',null,request()->remarks,request()->vl_select,request()->vl_select_specify,request()->sl_select,request()->sl_select_specify,request()->wfh_reason,request()->wfh_output);

                        $remarks = $this->createTxt(request()->userid2,$leaveid,$from_orig,$to);

                        App\Request_leave::where('id',$lvid)
                                ->update([
                                            'audit' => $remarks
                                        ]);
                    }
                }
                    //MULTIPLE
                elseif($totaldays > 1)
                {
                        
                        $code = randomCode(25);

                        //CREATE SUB REQUEST
                        $totaldeduc = $this->createLeave(request()->userid2,$leaveid,$from_orig,$to,$code);

                        //CREATE MAIN REQUEST PARA MACOUNT YUNG TOTAL
                        $lvid = $this->addLeave($leaveid,$from_orig,$to,'multiple',$totaldeduc,$code,$code,'YES',null,request()->remarks,request()->vl_select,request()->vl_select_specify,request()->sl_select,request()->sl_select_specify,request()->wfh_reason,request()->wfh_output);

                        $remarks = $this->createTxt(request()->userid2,$leaveid,$from_orig,$to);

                        App\Request_leave::where('id',$lvid)
                                ->update([
                                            'audit' => $remarks
                                        ]);
                }
                
            break;

            case 16:
            case 17:
            case 19:

            //SINGLE DATE
            if($totaldays == 1 || $totaldays == 0.5)
                {
                    $code = randomCode(25);
                        
                        $lvid = $this->addLeave($leaveid,$from_orig,$to,request()->leave_time,$totaldays,$code,$code,'YES',null,request()->remarks,request()->vl_select,request()->vl_select_specify,request()->sl_select,request()->sl_select_specify,request()->wfh_reason,request()->wfh_output);

                        $remarks = $this->createTxt(request()->userid2,$leaveid,$from_orig,$to);

                        App\Request_leave::where('id',$lvid)
                                ->update([
                                            'audit' => $remarks
                                        ]);
                    
                }
                //MULTIPLE
                elseif($totaldays > 1)
                {
                    
                    $code = randomCode(25); 
                    //CREATE SUB REQUEST
                        $totaldeduc = $this->createLeave(request()->userid2,$leaveid,$from_orig,$to,$code);

                        //CREATE MAIN REQUEST PARA MACOUNT YUNG TOTAL
                        $lvid = $this->addLeave($leaveid,$from_orig,$to,'multiple',$totaldeduc,$code,$code,'YES',null,request()->remarks,request()->vl_select,request()->vl_select_specify,request()->sl_select,request()->sl_select_specify,request()->wfh_reason,request()->wfh_output);

                        $remarks = $this->createTxt(request()->userid2,$leaveid,$from_orig,$to);

                        App\Request_leave::where('id',$lvid)
                                ->update([
                                            'audit' => $remarks
                                        ]);

                    $code = randomCode(25);

                }

            break;
                
            default;

                //return $totaldays;
                //SINGLE DATE
                if($totaldays == 1 || $totaldays == 0.5 )
                {

                    $code = randomCode(25);

                    //return "dito";

                    if($rem_bal < 0)
                    {
                        return view('error-message')->with('error_message','Not enough leave balance..');
                    }
                    else
                    {
                        
                        $lvid = $this->addLeave($leaveid,$from_orig,$to,request()->leave_time,$totaldays,$code,$code,'YES',null,request()->remarks,request()->vl_select,request()->vl_select_specify,request()->sl_select,request()->sl_select_specify,request()->wfh_reason,request()->wfh_output);

                        $remarks = $this->createTxt(request()->userid2,$leaveid,$from_orig,$to);

                        App\Request_leave::where('id',$lvid)
                                ->update([
                                            'audit' => $remarks
                                        ]);
                    }
                    
                }
                //MULTIPLE
                elseif($totaldays > 1)
                {
                    //return $rem_bal;
                    //return "He...";
                    
                    if($rem_bal < 0)
                    {
                        return view('error-message')->with('error_message','Not enough leave balance..');
                    }
                    else
                    {
                        $code = randomCode(25);
                        //CREATE SUB REQUEST
                        $totaldeduc = $this->createLeave(request()->userid2,$leaveid,$from_orig,$to,$code);

                        //CREATE MAIN REQUEST PARA MACOUNT YUNG TOTAL
                        $lvid = $this->addLeave($leaveid,$from_orig,$to,'multiple',$totaldeduc,$code,$code,'YES',null,request()->remarks,request()->vl_select,request()->vl_select_specify,request()->sl_select,request()->sl_select_specify,request()->wfh_reason,request()->wfh_output);

                        $remarks = $this->createTxt(request()->userid2,$leaveid,$from_orig,$to);

                        App\Request_leave::where('id',$lvid)
                                ->update([
                                            'audit' => $remarks
                                        ]);
                    }

                    $code = randomCode(25);

                }

            break;
        }

        return redirect('staff/leave/'.request()->userid2);
        // return $remarks;
    }


public function send2() 
    {

        $leaveid = request()->leave_id;
        

        $no_nega = true;

        if($leaveid  == 1 || $leaveid  == 2)
        {
            $no_nega = false;
        }


        $duration = explode('-',request()->leave_duration2);

        if($leaveid  == 1 || $leaveid  == 3 || $leaveid  == 2 || $leaveid  == 6)
        {
            $duration = explode('-',request()->leave_duration2);
        }
        elseif($leaveid  == 5 || $leaveid == 16)
        {
            $duration = explode('-',request()->leave_duration3);
        }
        elseif($leaveid  == 7 || $leaveid == 9)
        {
            $duration = explode('-',request()->leave_duration4);
        }       

        $remarks2 = "User ID : ".request()->userid2."<br/>".$duration[0]."-".$duration['1']."<br/>";



        $from = Carbon::parse($duration[0]);
        $from_orig = Carbon::parse($duration[0]);
        $to = Carbon::parse($duration[1]);

        $diff = $from->diffInDays($to);

        $totaldays = 0;

        $remarks2 .= "Difference Days : ".$diff."<br/>Leave ID : ".$leaveid."<br/>---Days---<br/>";



        for ($i=0; $i <= $diff; $i++)
        {

            if($i == 0)
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
                    if(!$this->checkIfHasLeave($dt,request()->userid2))
                    {
                        if(!$this->checkIfHasTO($dt,request()->userid2))
                        {
                            if(!$this->checkIfHasWFH($dt,request()->userid2))
                            {
                                $remarks2 .= "-".date('Y-m-d',strtotime($dt))."<br/>";
                                $totaldays++;
                            }
                            else
                            {
                                $remarks2 .= "-".date('Y-m-d',strtotime($dt))." (WFH/Office)<br/>";
                            }
                        }
                        else
                        {
                            $remarks2 .= "-".date('Y-m-d',strtotime($dt))." (T.O)<br/>";
                        }
                    }
                    else
                    {
                        $remarks2 .= "-".date('Y-m-d',strtotime($dt))." (Leave)<br/>";
                    }
                }
                else
                {
                    $remarks2 .= "-".date('Y-m-d',strtotime($dt))." (Holiday)<br/>";
                }
            }
            else
            {
                $remarks2 .= "-".date('Y-m-d',strtotime($dt))." (Weekend)<br/>";
            }
        }

        $remarks2 .= "Total Days : ".$totaldays."<br/>";

        //GET LEAVE BALANCE/PENDING
        $bal = getLeaves(request()->userid2,$leaveid);
        $pending = getPending($leaveid);
        $projected = $bal - $pending;
        $rem_bal = $projected - $totaldays;

        // return $rem_bal;

        //return $remarks2;

        switch ($leaveid)
        {
            case 1:

                //SINGLE DATE
                if($totaldays == 1)
                {
                    $code = randomCode(25);

                    if($rem_bal < 0)
                    {
                        //LWOP
                        $this->addLeave($leaveid,$from_orig,$to,request()->leave_time,$totaldays,$code,$code,'YES','YES',request()->remarks,request()->vl_select,request()->vl_select_specify,request()->sl_select,request()->sl_select_specify,request()->wfh_reason,request()->wfh_output);

                        $remarks = $this->createTxt(request()->userid2,$leaveid,$from_orig,$to);
                    }
                    else
                    {

                        $this->addLeave($leaveid,$from_orig,$to,request()->leave_time,$totaldays,$code,$code,'YES',null,request()->remarks,request()->vl_select,request()->vl_select_specify,request()->sl_select,request()->sl_select_specify,request()->wfh_reason,request()->wfh_output);

                        $remarks = $this->createTxt(request()->userid2,$leaveid,$from_orig,$to);
                    }
                    

                }
                //MULTIPLE
                elseif($totaldays > 1)
                {
                    
                    $code = randomCode(25);

                    //CREATE SUB REQUEST
                    $totaldeduc = $this->createLeave(request()->userid2,$leaveid,$from_orig,$to,$code);

                    //CREATE MAIN REQUEST PARA MACOUNT YUNG TOTAL
                    $this->addLeave($leaveid,$from_orig,$to,'multiple',$totaldeduc,$code,$code,'YES',null,request()->remarks,request()->vl_select,request()->vl_select_specify,request()->sl_select,request()->sl_select_specify,request()->wfh_reason,request()->wfh_output);

                    $remarks = $this->createTxt(request()->userid2,$leaveid,$from_orig,$to);

                }
            break;

            case 2:

            //SINGLE DATE
                if($totaldays == 1)
                {
                    $code = randomCode(25);

                    if($rem_bal < 0)
                    {
                        //LWOP
                        //CHECK IF MAY BALANCE PA SA VL
                        $bal = getLeaves(request()->userid2,1);
                        $pending = getPending(1);
                        $projected = $bal - $pending;
                        $rem_bal = $projected - $totaldays;

                        if($rem_bal > 0 || $rem_bal == 0)
                        {
                            $lvid = $this->addLeave(1,$from_orig,$to,request()->leave_time,$totaldays,$code,$code,'YES',null,request()->remarks,request()->vl_select,request()->vl_select_specify,request()->sl_select,request()->sl_select_specify,request()->wfh_reason,request()->wfh_output);
                        }
                        else
                        {
                            $lvid = $this->addLeave(2,$from_orig,$to,request()->leave_time,$totaldays,$code,$code,'YES','YES',request()->remarks,request()->vl_select,request()->vl_select_specify,request()->sl_select,request()->sl_select_specify,request()->wfh_reason,request()->wfh_output);
                        }
                        

                        $remarks = $this->createTxt(request()->userid2,$leaveid,$from_orig,$to);
                        App\Request_leave::where('id',$lvid)
                                ->update([
                                            'audit' => $remarks
                                        ]);
                    }
                    else
                    {

                        $lvid = $this->addLeave($leaveid,$from_orig,$to,request()->leave_time,$totaldays,$code,$code,'YES',null,request()->remarks,request()->vl_select,request()->vl_select_specify,request()->sl_select,request()->sl_select_specify,request()->wfh_reason,request()->wfh_output);

                        $remarks = $this->createTxt(request()->userid2,$leaveid,$from_orig,$to);

                        App\Request_leave::where('id',$lvid)
                                ->update([
                                            'audit' => $remarks
                                        ]);
                    }
                }
                    //MULTIPLE
                elseif($totaldays > 1)
                {
                        
                        $code = randomCode(25);

                        //CREATE SUB REQUEST
                        $totaldeduc = $this->createLeave(request()->userid2,$leaveid,$from_orig,$to,$code);

                        //CREATE MAIN REQUEST PARA MACOUNT YUNG TOTAL
                        $lvid = $this->addLeave($leaveid,$from_orig,$to,'multiple',$totaldeduc,$code,$code,'YES',null,request()->remarks,request()->vl_select,request()->vl_select_specify,request()->sl_select,request()->sl_select_specify,request()->wfh_reason,request()->wfh_output);

                        $remarks = $this->createTxt(request()->userid2,$leaveid,$from_orig,$to);

                        App\Request_leave::where('id',$lvid)
                                ->update([
                                            'audit' => $remarks
                                        ]);
                }
                
            break;

            case 16:
            case 17:

            //SINGLE DATE
            if($totaldays == 1)
                {
                    $code = randomCode(25);
                        
                        $lvid = $this->addLeave($leaveid,$from_orig,$to,request()->leave_time,$totaldays,$code,$code,'YES',null,request()->remarks,request()->vl_select,request()->vl_select_specify,request()->sl_select,request()->sl_select_specify,request()->wfh_reason,request()->wfh_output);

                        $remarks = $this->createTxt(request()->userid2,$leaveid,$from_orig,$to);

                        App\Request_leave::where('id',$lvid)
                                ->update([
                                            'audit' => $remarks
                                        ]);
                    
                }
                //MULTIPLE
                elseif($totaldays > 1)
                {
                    
                    $code = randomCode(25); 
                    //CREATE SUB REQUEST
                        $totaldeduc = $this->createLeave(request()->userid2,$leaveid,$from_orig,$to,$code);

                        //CREATE MAIN REQUEST PARA MACOUNT YUNG TOTAL
                        $lvid = $this->addLeave($leaveid,$from_orig,$to,'multiple',$totaldeduc,$code,$code,'YES',null,request()->remarks,request()->vl_select,request()->vl_select_specify,request()->sl_select,request()->sl_select_specify,request()->wfh_reason,request()->wfh_output);

                        $remarks = $this->createTxt(request()->userid2,$leaveid,$from_orig,$to);

                        App\Request_leave::where('id',$lvid)
                                ->update([
                                            'audit' => $remarks
                                        ]);

                    $code = randomCode(25);

                }

            break;
                
            default;
                //SINGLE DATE

                //return "HELLO";
                if($totaldays == 1)
                {
                    $code = randomCode(25);

                    if($rem_bal < 0)
                    {
                        return view('error-message')->with('error_message','Not enough leave balance..');
                    }
                    else
                    {
                        
                        $lvid = $this->addLeave($leaveid,$from_orig,$to,request()->leave_time,$totaldays,$code,$code,'YES',null,request()->remarks,request()->vl_select,request()->vl_select_specify,request()->sl_select,request()->sl_select_specify,request()->wfh_reason,request()->wfh_output);

                        $remarks = $this->createTxt(request()->userid2,$leaveid,$from_orig,$to);

                        App\Request_leave::where('id',$lvid)
                                ->update([
                                            'audit' => $remarks
                                        ]);
                    }
                    
                }
                //MULTIPLE
                elseif($totaldays > 1)
                {
                    
                    if($rem_bal < 0)
                    {
                        return view('error-message')->with('error_message','Not enough leave balance..');
                    }
                    else
                    {
                        $code = randomCode(25);
                        //CREATE SUB REQUEST
                        $totaldeduc = $this->createLeave(request()->userid2,$leaveid,$from_orig,$to,$code);

                        //CREATE MAIN REQUEST PARA MACOUNT YUNG TOTAL
                        $lvid = $this->addLeave($leaveid,$from_orig,$to,'multiple',$totaldeduc,$code,$code,'YES',null,request()->remarks,request()->vl_select,request()->vl_select_specify,request()->sl_select,request()->sl_select_specify,request()->wfh_reason,request()->wfh_output);

                        $remarks = $this->createTxt(request()->userid2,$leaveid,$from_orig,$to);

                        App\Request_leave::where('id',$lvid)
                                ->update([
                                            'audit' => $remarks
                                        ]);
                    }

                    $code = randomCode(25);

                }

            break;
        }

        return redirect('staff/leave/'.request()->userid2);
        // return $remarks;
    }


    private function createTxt($userid,$leaveid,$dt1,$dt2)
    {
        $txt = "User ID : ".$userid."\n".date('Y-m-d',strtotime($dt1))."-".date('Y-m-d',strtotime($dt2))."\nLeave ID : ".$leaveid."\n";
        $remarks = "User ID : ".$userid."<br/>".date('Y-m-d',strtotime($dt1))."-".date('Y-m-d',strtotime($dt2))."<br/>Leave ID : ".$leaveid."<br/>";

        $from = Carbon::parse($dt1);
        $from_orig = Carbon::parse($dt1);
        $to = Carbon::parse($dt2);

        $diff = $from->diffInDays($to);

        $totaldays = 0;

        $txt .= "Difference Days : ".$diff."\n---Days---\n";
        $remarks .= "Difference Days : ".$diff."<br/>---Days---<br/>";

        for ($i=0; $i <= $diff; $i++)
        {


            if($i == 0)
            {
                $dt = date('Y-m-d',strtotime($from_orig));
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
                    if(!$this->checkIfHasLeave($dt,$userid))
                    {
                        if(!$this->checkIfHasTO($dt,$userid))
                        {
                            if(!$this->checkIfHasWFH($dt,$userid))
                            {
                                //GET LEAVE BALANCE/PENDING
                                $bal = getLeaves($userid,$leaveid);
                                $pending = getPending($leaveid);
                                $projected = $bal - $pending;
                                $rem_bal = $projected - $totaldays;

                                $lwop = null;
                                if($rem_bal < 0)
                                    $lwop = ' (LWOP)';

                                $txt .= "-".date('Y-m-d',strtotime($dt))."\n";
                                $remarks .= "-".date('Y-m-d',strtotime($dt))."$lwop<br/>";
                                $totaldays++;
                            }
                            else
                            {
                                $txt .= "-".date('Y-m-d',strtotime($dt))." (WFH/Office)\n";
                                $remarks .= "-".date('Y-m-d',strtotime($dt))." (WFH/Office)<br/>";
                            }
                        }
                        else
                        {
                            $txt .= "-".date('Y-m-d',strtotime($dt))." (T.O)\n";
                            $remarks .= "-".date('Y-m-d',strtotime($dt))." (T.O)<br/>";
                        }
                    }
                    else
                    {
                        $txt .= "-".date('Y-m-d',strtotime($dt))." (Leave)\n";
                        $remarks .= "-".date('Y-m-d',strtotime($dt))." (Leave)<br/>";
                    }
                }
                else
                {
                    $txt .= "-".date('Y-m-d',strtotime($dt))." (Holiday)\n";
                    $remarks .= "-".date('Y-m-d',strtotime($dt))." (Holiday)<br/>";
                }
            }
            else
            {
                $txt .= "-".date('Y-m-d',strtotime($dt))." (Holiday)\n";
                $remarks .= "-".date('Y-m-d',strtotime($dt))." (Weekend)<br/>";
            }
        }

        $txt .= "Total Days : ".$totaldays."\n";
        $remarks .= "Total Days : ".$totaldays."<br/>";

        //GET LEAVE BALANCE/PENDING
        $bal = getLeaves($userid,$leaveid);
        $pending = getPending($leaveid);
        $projected = $bal - $pending;
        $rem_bal = $projected - $totaldays;

        $remarks .= "BALANCE : ".$bal."<br/>";
        $remarks .= "PENDING : ".$pending."<br/>";
        $remarks .= "PROJECTED : ".$projected."<br/>";
        $remarks .= "REMAINING BALANCE : ".$rem_bal."<br/>";

        $txt .= "BALANCE : ".$bal."\n";
        $txt .= "PENDING : ".$pending."\n";
        $txt .= "PROJECTED : ".$projected."\n";
        $txt .= "REMAINING BALANCE : ".$rem_bal."\n";

        //SAVE FILE
        $fsMgr = new FilesystemManager(app());

        $localDisk = $fsMgr->createLocalDriver(['root' => storage_path('app/request_leave_audit/')]);

        $auditcode = randomCode(25);

        $localDisk->put($auditcode.'.txt', $txt);
        
        return $auditcode."txt";
    }


    private function createLeave($userid,$leaveid,$dt1,$dt2,$code)
    {

        $from = Carbon::parse($dt1);
        $from_orig = Carbon::parse($dt1);
        $to = Carbon::parse($dt2);

        $diff = $from->diffInDays($to);

        $totaldays = 0;

        for ($i=0; $i <= $diff; $i++)
        {

            if($i == 0)
            {
                $dt = date('Y-m-d',strtotime($from_orig));
            }
            else
            {
               $dt_main = $from->addDays(1);
               $dt = $dt_main;
            }
            
            //CALENDAR YEAR
            //ISASAMA ANG HOLIDAY AT WEEKEND SA COUNT
            if($leaveid == 7 || $leaveid == 9 || $leaveid == 10) 
            {
                //GET LEAVE BALANCE/PENDING
                                    $bal = getLeaves($userid,$leaveid);
                                    $pending = getPending($leaveid);
                                    $projected = $bal - $pending;
                                    $rem_bal = $projected - $totaldays;

                                    $lwop = null;
                                    if($rem_bal < 0)
                                        $lwop = 'YES';

                                    $totaldays++;

                                    $this->addLeave($leaveid,$dt,null,request()->leave_time,1,null,$code,null,$lwop,request()->remarks,request()->vl_select,request()->vl_select_specify,request()->sl_select,request()->sl_select_specify,request()->wfh_reason,request()->wfh_output);
            }
            else
            {
                if(!$this->checkIfWeekend($dt))
                {
                    if(!checkIfHoliday($dt))
                    {
                        if(!$this->checkIfHasLeave($dt,$userid))
                        {
                            if(!$this->checkIfHasTO($dt,$userid))
                            {
                                if(!$this->checkIfHasWFH($dt,$userid))
                                {
                                    //GET LEAVE BALANCE/PENDING
                                    $bal = getLeaves($userid,$leaveid);
                                    $pending = getPending($leaveid);
                                    $projected = $bal - $pending;
                                    $rem_bal = $projected - $totaldays;

                                    $lwop = null;
                                    if($rem_bal < 0)
                                        $lwop = 'YES';

                                    $totaldays++;

                                    $this->addLeave($leaveid,$dt,null,request()->leave_time,1,null,$code,null,$lwop,request()->remarks,request()->vl_select,request()->vl_select_specify,request()->sl_select,request()->sl_select_specify,request()->wfh_reason,request()->wfh_output);
                                }
                            }
                        }
                    }
                }
            }
            
        }

        return $totaldays;
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

            return $tblid;
        
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
            $user = App\User::where('id',request()->emp_list_1)->first();
            $cto = new App\Employee_cto;
            $cto->user_id = request()->emp_list_1;
            $cto->empcode = $user['username'];
            $cto->cto_year = date('Y');
            $cto->cto_bal = request()->leave_bal;
            $cto->save();
        }
        else
        {
            $lv = new App\Employee_leave;

            $lv->leave_id = request()->leave_list_1;
            $lv->user_id = request()->emp_list_1;
            // $lv->empcode = request()->leave_id;
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
        return url('/');
    }

    public function checkIfHasLeave($dt,$userid)
    {
        $dt = App\Request_leave::where('user_id',$userid)->where('leave_date_from',date('Y-m-d',strtotime($dt)))->where('leave_deduction',1)->whereIn('leave_action_status',['Approved','Processed'])->count();

        if($dt > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function checkIfHasTO($dt,$userid)
    {
        $dt = App\RequestTO::where('userid',$userid)->where('to_date_from',date('Y-m-d',strtotime($dt)))->where('to_deduction',1)->whereIn('to_status',['Approved','Processed'])->count();

        if($dt > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function checkIfHasWFH($dt,$userid)
    {
        $dt = App\WeekSchedule::where('userid',$userid)->where('sched_status','WFH')->where('sched_date',date('Y-m-d',strtotime($dt)))->count();

        if($dt > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
