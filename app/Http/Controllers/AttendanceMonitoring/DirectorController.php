<?php

namespace App\Http\Controllers\AttendanceMonitoring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Auth;
use Carbon\Carbon;

class DirectorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function request()
    {
        $emp = App\User::where('id',Auth::user()->id)->first();
        $data = [
                    "empinfo" => $emp,
                    "nav" => nav("attendance"),
                ];

        return view('dtr.director.request-for-approval')->with("data",$data);
    }

    public function requestsubmit()
    {
        // Dahil sama-sama sa isang request() ang checkbox
        // need ihiwalay kada array yung mga IDs
        // para isang batch na lang kada request type(leave,ot,to)

        $arr_leaves = [];
        $arr_ots = [];
        $arr_tos = [];

        foreach (request()->check_request as $value) {
            $br = explode("_", $value);
            switch ($br[0]) {
                case 'leave':
                        array_push($arr_leave, (int)$br[1]);
                    break;
            }
        }


        //LEAVE
        if(isset($arr_leave))
        {
            App\Request_leave::whereIn('id',$arr_leaves)
                ->update([
                            'leave_approved_by' => Auth::user()->id,
                            'leave_approved_date' => date(''),
                        ]);
        }
        

        return $arr_leaves;
    }

    public function approvedLeaveRequest()
    {

        foreach (request()->check_request as $key => $values) {
            $request = App\Request_leave::where('id',$values)
                            ->update([
                                        'leave_action_status' => request()->leave_action_status,
                                    ]);
                            
            add_history_leave(request()->userid[$key],request()->leavedid[$key],request()->leavedates[$key],request()->leave_action_status);
        }

        return redirect('request-for-approval');
    }

    public function actionRequest()
    {
        //return request();
        $status = request()->request_action;
        

        switch (request()->request_type) {
                case 'leave':

                //GET PARENT CODE
                $codes = App\Request_leave::where('id',request()->request_id)->first();
                $code = $codes['parent_leave'];
                $leave = $codes['leave_id'];
                $user = $codes['user_id'];
                $pendingleave = $codes['leave_deduction'];
                $newpendingBalance = $pendingleave - 1;

                $request = App\Request_leave::where('parent_leave_code',$code)
                            ->update([
                                        'leave_action_status' => $status,
                                        'leave_action_by' => Auth::user()->lname.', '.Auth::user()->fname.' - '.Auth::user()->id,
                                        'leave_action_date' => date('Y-m-d H:i:s'),
                                        'leave_deduction' => $newpendingBalance,
                                    ]);

                $employeeLeave = App\Employee_leave::where('leave_id', $leave)->where('user_id', $user)->first();
                $newBalance = $employeeLeave->leave_bal - 1;
                $newPendingBalance = $employeeLeave->pending - 1;
                $employeeLeave->update(['leave_bal' => $newBalance, 'leave_bal' => $newBalance]); 

                if($codes['leave_deduction'] > 1)
                {
                    $dtr = App\Request_leave::where('parent_leave_code',$code)->whereNull('parent')->get();
                }
                else
                {
                    $dtr = App\Request_leave::where('parent_leave_code',$code)->where('parent')->get();
                }
                
                // CHECK IF HAS FORCE LEAVE
                // if($codes['leave_id'] == 1)
                // {
                //     $fl = App\Request_leave::whereIn('parent',['NO',NULL])->where('leave_date_from',$codes['leave_date_from'])->where('leave_id',6)->first();
                //     if(isset($fl))
                //     {
                //         App\Request_leave::where('id',$fl['id'])
                //             ->update([
                //                         'leave_action_status' => $status,
                //                         'leave_action_by' => Auth::user()->lname.', '.Auth::user()->fname,
                //                         'leave_action_date' => date('Y-m-d H:i:s'),
                //                     ]);
                //     }
                // }

                // foreach ($dtr as $key => $value) {
                    
                //         // add_history_leave($value->id,$value->leave_id,request()->requestid[$key],$value->leave_date_from,$status);

                //         //ADD TO DTR
                //         $lwop = null;

                //         if($status == 'Approved')
                //         {
                //             $remarks = getLeaveDesc($codes['leave_id']);

                //             $dtr = new App\Employee_dtr;
                //             $dtr->fldEmpCode =  getStaffInfo($codes['user_id'],'empcode');
                //             $dtr->employee_name =  getStaffInfo($codes['user_id']);
                //             $dtr->division =  getStaffInfo($codes['user_id'],'division_id');
                //             $dtr->user_id =  $codes['user_id'];
                //             $dtr->fldEmpDTRdate =  $value->leave_date_from;
                //             $dtr->fldEmpDTRamIn =  "08:00:00";
                //             $dtr->fldEmpDTRamOut =  "12:00:00";
                //             $dtr->fldEmpDTRpmIn =  "13:00:00";
                //             $dtr->fldEmpDTRpmOut =  "17:00:00";
                //             $dtr->request =  $remarks;
                //             $dtr->request_id =  request()->request_id;
                //             // $dtr->lwop =  $lwop;
                //             $dtr->dtr_remarks =  $remarks;
                //             $dtr->dtr_option_id = getDTROption();
                //             $dtr->save();
                //         }

                //         // $leave--;
                // }
                        
                        

                    break;
                case 'TO':
                        $req = App\RequestTO::where('id',request()->request_id)->first();

                        $req_code = $req['parent_to'];

                        $request = App\RequestTO::where('id',request()->request_id)
                            ->update([
                                        'to_status' => $status,
                                        'to_status_by' => Auth::user()->lname.', '.Auth::user()->fname,
                                        'to_status_date' => date('Y-m-d H:i:s'),
                                    ]);

                        //UPDATE CHILD T.O
                        $request = App\RequestTO::where('parent_to_code',$req_code)->whereNull('parent')
                            ->update([
                                        'to_status' => $status,
                                        'to_status_by' => Auth::user()->lname.', '.Auth::user()->fname,
                                        'to_status_date' => date('Y-m-d H:i:s'),
                                    ]);

                        // add_to_leave($request,request()->request_id[$values],request()->leavedates[$values],$status);

                        // if($status == 'Approved')
                        // {
                        //     $dtr = new App\Employee_dtr;
                        //     $dtr->fldEmpCode =  $req['empcode'];
                        //     $dtr->employee_name =  $req['employee_name'];
                        //     $dtr->division =  $req['division'];
                        //     $dtr->user_id =  $req['userid'];
                        //     $dtr->fldEmpDTRdate =  $req['to_date'];
                        //     $dtr->dtr_option_id = getDTROption();

                        //     //CHECK DURATION
                        //     if($req['to_deduction_time'] == 'wholeday')
                        //     {
                        //         $dtr->fldEmpDTRamIn =  "08:00:00";
                        //         $dtr->fldEmpDTRamOut =  "12:00:00";
                        //         $dtr->fldEmpDTRpmIn =  "13:00:00";
                        //         $dtr->fldEmpDTRpmOut =  "17:00:00";
                        //         $dtr->dtr_to =  "Wholeday";
                        //     }
                        //     else if($req['to_deduction_time'] == 'AM')
                        //     {
                        //         $dtr->fldEmpDTRamIn =  "08:00:00";
                        //         $dtr->fldEmpDTRamOut =  "12:00:00";
                        //         $dtr->dtr_to =  "AM";
                        //     }
                        //     else if($req['to_deduction_time'] == 'PM')
                        //     {
                        //         $dtr->fldEmpDTRpmIn =  "13:00:00";
                        //         $dtr->fldEmpDTRpmOut =  "17:00:00";
                        //         $dtr->dtr_to =  "PM";
                        //     }
                            
                        //     $dtr->request =  "T.O";
                        //     $dtr->request_id =  $req['id'];
                        //     $dtr->save();
                        // }

                    break;
                case 'OT':
                    request()->request_ot_cto;

                        //GET OT DETAILS
                        $otreq = App\RequestOT::where('id',request()->request_id)->first();


                        //CHECK IF MAY VALUE NA YUNG CTO EARN
                        if($otreq['ot_in'] == null)
                        {
                            if($status == 'OED Approved' && request()->request_ot_cto == 'NO')
                                $status = 'Approved';

                            App\RequestOT::where('id',request()->request_id)
                            ->update([
                                        'cto' => request()->request_ot_cto,
                                        'ot_supervisor' => request()->request_ot_supervisor,
                                        'ot_status' => $status,
                                        'ot_status_by' => Auth::user()->lname.', '.Auth::user()->fname,
                                        'ot_status_date' => date('Y-m-d H:i:s'),
                                        'ot_in' =>  request()->request_ot_in,
                                        'ot_out' =>  request()->request_ot_out,
                                    ]);
                            $otin = request()->request_ot_in;
                            $otout = request()->request_ot_out;
                        }
                        else
                        {
                            App\RequestOT::where('id',request()->request_id)
                            ->update([
                                        'cto' => request()->request_ot_cto,
                                        'ot_supervisor' => request()->request_ot_supervisor,
                                        'ot_status' => $status,
                                        'ot_in' =>  request()->request_ot_in,
                                        'ot_out' =>  request()->request_ot_out,
                                    ]);
                            $otin = request()->request_ot_in;
                            $otout = request()->request_ot_out;
                        }

                        

                        $requests = App\RequestOT::where('id',request()->request_id)->first();

                        $cto_request = $requests['cto'];

                        //COUNT HOURS
                        $startTime = Carbon::parse($requests['ot_date'].' '.$otin);
                        $finishTime = Carbon::parse($requests['ot_date'].' '.$otout);

                        $totalhrs = $startTime->diff($finishTime)->format('%H:%I:%S');

                        $totalmins = $finishTime->diffInMinutes($startTime);

                        $earncto = $totalmins / 480;

                        //CHECK IF HOLIDAY

                        if(checkIfHoliday($requests['ot_date']))
                        {
                            $earncto = $earncto * 1.5;
                        }
                        else
                        {
                            $dt = Carbon::parse($requests['ot_date']);
                            if($dt->isWeekend())
                            {
                                $earncto = $earncto * 1.5;
                            }
                        }

                        //return $status;

                        App\RequestOT::where('id',request()->request_id)
                            ->update([
                                        'ot_hours' => $totalhrs,
                                        'ot_min' => $totalmins,
                                        'cto_earn' => number_format($earncto,3)
                                    ]);
                        //IF LESS THAN 2HOURS CONVERT TO NON-CTO
                        if($totalmins < 120)
                        {
                            App\RequestOT::where('id',request()->request_id)
                            ->update([
                                        'cto' => 'NO',
                                    ]);
                        }
                        else
                        {
                            //IF CTO EARN
                            if(request()->request_ot_cto == 'YES')
                            {
                                    //INITIAL APPROVAL IF DIRECTOR
                                    if($status == 'Approved' || $status = 'Time Edited')
                                    {
                                        //GET PREVIOUS BALANCE
                                        //$prevlv = App\Employee_cto::where('empcode',$otreq['empcode'])->where('leave_id',5)->orderBy('created_at','DESC')->first();
                                        $prevlv = App\Employee_cto::where('empcode',$otreq['empcode'])->orderBy('created_at','DESC')->first();

                                        if($prevlv)
                                        {
                                            //COMPUTE CTO
                                            $prevl = $prevlv['cto_bal'];
                                        }
                                        else
                                        {
                                            $prevl = 0;
                                        }
                                        

                                        if($prevl <= 0)
                                        {
                                            $prevl = 0;
                                        }

                                        $newctobal = $prevl + $earncto;

                                        $lv = new App\Employee_leave;
                                        $lv->leave_id = 5;
                                        $lv->user_id = $otreq['userid'];
                                        $lv->empcode = $otreq['empcode'];
                                        $lv->leave_bal_prev = $prevl;
                                        $lv->leave_bal = number_format($newctobal,3);
                                        $lv->save();

                                        //UPDATE CTO BALANCE
                                        $cto = new App\Employee_cto;
                                        $cto->user_id = $otreq['userid'];
                                        $cto->empcode = $otreq['empcode'];
                                        $cto->cto_year = date('Y',strtotime($otreq['ot_date']));
                                        $cto->cto_bal = number_format($newctobal,3);
                                        $cto->save();
                                    }                 

                            }

                        }

                        
                        add_to_leave($requests['userid'],request()->request_id,$requests['ot_date'],$status);
                        
                        if($status == 'Approved')
                        {
                            //CHECK IF HAS A DTR ALREADY, NAHULI FILLING
                            $dtrs = App\Employee_dtr::where('fldEmpDTRdate',$requests['ot_date'])->where('user_id',$requests['userid'])->count();

                            if($dtrs > 0)
                            {
                                $dtr = App\Employee_dtr::where('fldEmpDTRdate',$requests['ot_date'])->where('user_id',$requests['userid'])
                                    ->update([
                                                'fldEmpDTRotIn' => request()->request_ot_in,
                                                'fldEmpDTRotOut' => request()->request_ot_out,
                                                'request_id' => $requests['id'],
                                            ]);
                            }
                            else
                            {
                                $dtr = new App\Employee_dtr;
                                $dtr->fldEmpCode =  getStaffInfo($requests['userid'],'empcode');
                                $dtr->employee_name =  getStaffInfo($requests['userid']);
                                $dtr->division =  getStaffInfo($requests['userid'],'division_id');
                                $dtr->user_id =  $requests['userid'];
                                $dtr->fldEmpDTRdate =  $requests['ot_date'];
                                $dtr->fldEmpDTRotIn =  request()->request_ot_in;
                                $dtr->fldEmpDTRotOut =  request()->request_ot_out;
                                $dtr->request_id =  $requests['id'];
                                $dtr->dtr_option_id = getDTROption();
                                $dtr->save();
                            }
                            
                        }
                    break;
            }

            // return request()->check_request[$key]." - ".request()->request_index[$key]." - ".$status;

            // $i++;

            // echo request()->requestype[$values]."<br>";

        // return request()->requestype[];
        return redirect('request-for-approval');
    }


}
