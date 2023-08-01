<?php

namespace App\Http\Controllers\AttendanceMonitoring;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App;
use Carbon\Carbon;
use Auth;
use PhpParser\Node\Stmt\ElseIf_;

class FinalProcess extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        
        //GET ALL STAFF
        $staff = collect(App\View_user::whereIn('id',request()->check_request)->where('usertype','!=','Administrator')->orderBy('lname')->orderBy('fname')->get());

        $data = collect([]);


        
        foreach ($staff->all() as $staffs)
        {

            
            if(!checkIfProcess($staffs->id,request()->mon,request()->yr))
            {

            $process_code = randomCode(45);
                            
            $deficit = 0.000;

            $totaldeduction = 0.000;
            $totalvldeduction = 0.000;

            $vl_ctr = 0;

            $remarks = "";

            $remarks .= "<b>".$staffs->lname.", ".$staffs->fname."</b><br/>---Current Leave Balances---<br/>";


            $collecleave = collect([]);

            //LEAVE
            $orig_vl = 0.000;
            $orig_sl = 0.000;
            $orig_pl = 0.000;
            $orig_spl = 0.000;
            $orig_cto = 0.000;
            $orig_fl = 0.000;
            $orig_MatL = 0.000;
            $orig_PatL = 0.000;
            $orig_StL = 0.000;
            $orig_ReL = 0.000;
            $orig_el = 0.000;
            $orig_spec = 0.000;
            $orig_ml = 0.000;
            $orig_ua = 0.000;
            $orig_slwop = 0.000;

            //GET STAFF CURRENT LEAVE BALANCES
            foreach(showLeaves() AS $leaves){

                // switch ($leaves->id) {
                //     case 1:
                //                 $lv = getLVSummary(1,$staffs->id,request()->mon,request()->yr);
                //         break;
                //     case 2:
                //                 $lv = getLVSummary(2,$staffs->id,request()->mon,request()->yr);
                //         break;
                    
                //     default:
                //                 $lv = getLeaves($staffs->id,$leaves->id);
                //         break;
                // }
                
                $lv = getLeaves($staffs->id,$leaves->id);  
                $remarks .= $leaves->leave_desc." : <b>$lv</b><br>";

                //GET PREVIOUS LEAVE
                switch ($leaves->id)
                {
                        case 1:
                            # code...
                                $orig_vl += $lv;
                            break;
                        case 2:
                            # code...
                                $orig_sl += $lv;
                            break;
                        case 3:
                            # code...
                                $orig_pl += $lv;
                            break;
                        case 4:
                            # code...
                                $orig_spl += $lv;
                            break;
                        case 5:
                            # code...
                                $orig_cto += $lv;
                            break;
                        case 6:
                            # code...
                                $orig_fl += $lv;
                            break;
                        case 7:
                            # code...
                                $orig_MatL += $lv;
                            break;
                        case 8:
                            # code...
                                $orig_PatL += $lv;
                            break;
                        case 9:
                            # code...
                                $orig_StL += $lv;
                            break;
                        case 10:
                            # code...
                                $orig_ReL += $lv;
                            break;
                        case 11:
                            # code...
                                $orig_el += $lv;
                            break;
                        case 12:
                            # code...
                                $orig_spec += $lv;
                            break;
                        case 13:
                            # code...
                                $orig_el += $lv;
                            break;
                        case 14:
                            # code...
                                $orig_ua += $lv;
                            break;
                        case 15:
                            # code...
                                $orig_slwop += $lv;
                            break;
                    }

                $collecleave->put($leaves->leave_desc, $lv);
            }
            
            $remarks .= "<br/><br/>";

            
            if($staffs->dtr_exe != 1 || $staffs->employement_id == 15)
            {
                if($staffs->employement_id != 12)
                {
                    //GET L.W.P
                    $lwp = getLWP($staffs->id,request()->mon,request()->yr);

                    $lwp = explode("|", $lwp);

                    $no_lates = 

                    //COUNT LWP
                    $nolwp = countLWP($staffs->id,request()->mon,request()->yr);
                    // $earnleave = getLWPCount((string)$nolwp);
                    $earnleave = 1.25;

                    // return $lwp[0];


                    $remarks .= "Required Total Hours: <b>".$lwp[10]."</b><br>";
                    $remarks .= "Total Hours Rendered: <b>".$lwp[11]."</b><br>";
                    $deficit_hours = $lwp[10] - $lwp[11];
                    $remarks .= "Deficit hours: <b>".$deficit_hours."</b><br><br>";

                    //ABSENT
                    $remarks .= "---Absent---<br/>Total : <b>".$lwp[0]."</b><br/><br/>";

                    $remarks .= "---Leave Without Pay---<br/>";

                    // if($lwp[0] >= 0)
                    // {
                    //     //EARNED VL/SL
                    //     $lwps = getLWPCount($lwp[0]);

                    //     $remarks .= "LWP total : <b>".$nolwp."</b><br>";
                    // }
                    $remarks .= "LWP total : <b>".$nolwp."</b><br>";

                    $remarks .= "<br/>";

                    $remarks .= "VL/SL earn : <b>".$earnleave."</b><br><br>";

                    $remarks .= "---Deduction---<br/>";
                    
                    //GET LATES/UNDERTIME
                    $remarks .= $lwp[2]."<br/>Lates/Undertime Deduction : <b>".number_format((float)$lwp[1], 3, '.', '')."</b><br><br>";

                    $totaldeduction += number_format((float)$lwp[1], 3, '.', '');

                    $tardy = $this->getTardy($staffs->id,request()->mon,request()->yr);

                    $no_process_lates = $lwp[3]."h ".$lwp[4]."m";
                    $no_process_under = $lwp[8]."h ".$lwp[9]."m";
                    $no_process_lates_under = $lwp[5]."h ".$lwp[6]."m";
                    $no_lates_total = $lwp[7];
                    $no_process_absent = $lwp[0];
                }
                else
                {
                    $totaldeduction = 0.000;
                    $earnleave = 1.25;
                    $remarks .= "VL/SL earn : <b>".$earnleave."</b><br><br>";

                    $nolwp = 0;
                    $tardy = 0;
                    $no_process_lates = "0h 0m";
                    $no_process_under = "0h 0m";
                    $no_process_lates_under = "0h 0m";
                    $no_lates_total = "0h 0m";
                    $no_process_absent = 0;
                }
            }
            else
            {
                $totaldeduction = 0.000;
                $earnleave = 1.25;
                $remarks .= "VL/SL earn : <b>".$earnleave."</b><br><br>";

                $nolwp = 0;
                $tardy = 0;
                $no_process_lates = "0h 0m";
                $no_process_under = "0h 0m";
                $no_process_lates_under = "0h 0m";
                $no_lates_total = "0h 0m";
                $no_process_absent = 0;

            }
            

            //GET REQUEST
            $remarks .= "---Request---<br/>";

            //MULTIPLE DATES
            $leave_req_0 = collect(App\Request_leave::whereNull('parent_leave')->where('user_id',$staffs->id)->where('leave_action_status','Approved')->whereMonth('leave_date_from',request()->mon)->whereYear('leave_date_from',request()->yr)->get());

            //SINGLE DATE
            $leave_req_1 = collect(App\Request_leave::where('parent','YES')->where('leave_deduction','>=',0.5)->where('user_id',$staffs->id)->where('leave_action_status','Approved')->whereMonth('leave_date_from',request()->mon)->whereYear('leave_date_from',request()->yr)->where('leave_deduction','<=',1)->get());

            $leave_req = $leave_req_0->merge($leave_req_1);

            if($leave_req)
            {
                $leave_req = $leave_req->all();
                $vl = 0.000;
                $sl = 0.000;
                $pl = 0.000;
                $spl = 0.000;
                $cto = 0.000;
                $fl = 0.000;
                $MatL = 0.000;
                $PatL = 0.000;
                $StL = 0.000;
                $ReL = 0.000;
                $el = 0.000;
                $spec = 0.000;
                $ml = 0.000;
                $ua = 0.000;
                $slwop = 0.000;

                //UPDATE TO PROCESSED
                foreach ($leave_req as $leaves) 
                {
                    $remarks .= getLeaveInfo($leaves->leave_id)." - Date : ".$leaves->leave_date_from." to ".$leaves->leave_date_to." - Deduction : ".$leaves->leave_deduction."<br>";

                    switch ($leaves->leave_id) 
                    {
                        case 1:
                        # code...
                                $vl += $leaves->leave_deduction;
                                $totalvldeduction = $totaldeduction + $leaves->leave_deduction;

                                //$fl += $leaves->leave_deduction;

                                $vl_ctr += $leaves->leave_deduction;

                                $this->addToMCDays($staffs->id,$staffs->username,'Vacation Leave',$leaves->leave_deduction,$leaves->leave_date_from,$leaves->leave_date_to,$process_code);

                            break;
                        case 2:
                            # code...
                                $sl += $leaves->leave_deduction;
                                
                                $this->addToMCDays($staffs->id,$staffs->username,'Sick Leave',$leaves->leave_deduction,$leaves->leave_date_from,$leaves->leave_date_to,$process_code);
                            break;
                        case 3:
                            # code...
                                $pl += $leaves->leave_deduction;

                                $this->addToMCDays($staffs->id,$staffs->username,'Privilege Leave',$leaves->leave_deduction,$leaves->leave_date_from,$leaves->leave_date_to,$process_code);
                            break;
                        case 4:
                            # code...
                                $spl += $leaves->leave_deduction;

                                $this->addToMCDays($staffs->id,$staffs->username,'Solo Parent Leave',$leaves->leave_deduction,$leaves->leave_date_from,$leaves->leave_date_to,$process_code);
                            break;
                        case 5:
                            # code...
                                $cto += $leaves->leave_deduction;

                                 //DEDUC CTO
                                //GET PREV LEAVE

                                $prevcto = App\Employee_cto::where('user_id',$staffs->id)->orderBy('id','DESC')->first();
                                $prevcto = $prevcto['cto_bal'];

                                $currentcto = $prevcto - $leaves->leave_deduction;
                                
                                $latestcto = new App\Employee_cto;
                                $latestcto->user_id = $staffs->id;
                                $latestcto->empcode = $staffs->username;
                                $latestcto->cto_year = date('Y');
                                $latestcto->cto_bal = $currentcto;
                                $latestcto->save();
                            break;
                        case 6:
                            # code...
                                $fl += $leaves->leave_deduction;
                                $vl += $leaves->leave_deduction;

                                $vl_ctr += $leaves->leave_deduction;

                                $totalvldeduction = $totaldeduction + $leaves->leave_deduction;

                                $this->addToMCDays($staffs->id,$staffs->username,'Force Leave',$leaves->leave_deduction,$leaves->leave_date_from,$leaves->leave_date_to,$process_code);
                            break;
                        case 7:
                            # code...
                                $MatL += $leaves->leave_deduction;
                                $this->addToMCDays($staffs->id,$staffs->username,'Maternity Leave',$leaves->leave_deduction,$leaves->leave_date_from,$leaves->leave_date_to,$process_code);
                            break;
                        case 8:
                            # code...
                                $PatL += $leaves->leave_deduction;
                                $this->addToMCDays($staffs->id,$staffs->username,'Paternity Leave',$leaves->leave_deduction,$leaves->leave_date_from,$leaves->leave_date_to,$process_code);
                            break;
                        case 9:
                            # code...
                                $StL += $leaves->leave_deduction;
                            break;
                        case 10:
                            # code...
                                $ReL += $leaves->leave_deduction;
                                $this->addToMCDays($staffs->id,$staffs->username,'Rehabilitation Leave',$leaves->leave_deduction,$leaves->leave_date_from,$leaves->leave_date_to,$process_code);
                            break;
                        case 11:
                            # code...
                                $el += $leaves->leave_deduction;
                                $this->addToMCDays($staffs->id,$staffs->username,'Emergency Leave',$leaves->leave_deduction,$leaves->leave_date_from,$leaves->leave_date_to,$process_code);
                            break;
                        case 12:
                            # code...
                                $spec += $leaves->leave_deduction;
                                $this->addToMCDays($staffs->id,$staffs->username,'Special Leave (Magna Carta of Women) Leave',$leaves->leave_deduction,$leaves->leave_date_from,$leaves->leave_date_to,$process_code);
                            break;
                        // case 13:
                        //     # code...
                        //         $el += $leaves->leave_deduction;

                        //     break;
                        case 14:
                            # code...
                                $ua += $leaves->leave_deduction;
                            break;
                        case 15:
                            # code...
                                $slwop += $leaves->leave_deduction;
                            break;
                    }

                    App\Request_leave::where('id',$leaves->id)
                                    ->update([
                                                // 'leave_action_status' => 'Processed',
                                                'process_code' => $process_code
                                            ]);            
                }

                $remarks .= " LEAVE : ".$totaldeduction."<br>";
            }
            
            $remarks .= "<br/>Total VL Deduction : ".($vl_ctr + $totaldeduction)."<br/>";

            $remarks .= "<br/>---Ending Leave Balances---<br/>";

            foreach ($collecleave as $keybal => $valuebal) 
            {
                
                switch ($keybal) {
                    case 'Vacation Leave':
                        # code...
                            $val = ($valuebal  - $vl) + $earnleave;
                            $val = $val - $tardy;

                            $deficit = 0;
                            if($totalvldeduction > ($valuebal + $earnleave))
                            {
                                $deficit = ($vl + $totalvldeduction) - ($valuebal + $earnleave);
                            }


                            $vl_bal = $val;

                            //UPDATE LEAVE
                            if($val > -1)
                            {
                                $data->push(['leave_id' => 1,'user_id' => $staffs->id,'empcode' => $staffs->username,'leave_bal_prev' => $orig_vl,'leave_bal' => $val,'leave_bal_nega' => $deficit,'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),'process_code' => $process_code]);
                            }

                        break;
                    case 'Sick Leave':
                        # code...
                            // $val = $valuebal - $sl;
                            $val = ($valuebal + $earnleave) - $sl;

                            $sl_bal = $val;

                            $deficit = 0;
                            if($val < 0)
                            {
                                $deficit = ($sl + $totaldeduction) - ($valuebal + $earnleave);
                            }

                            //UPDATE LEAVE
                            if($val > -1)
                            {
                                $data->push(['leave_id' => 2,'user_id' => $staffs->id,'empcode' => $staffs->username,'leave_bal_prev' => $orig_sl,'leave_bal' => $val,'leave_bal_nega' => $deficit,'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),'process_code' => $process_code]);
                            }

                        break;
                    case 'Privilege Leave':
                        # code...
                            
                            $val = $valuebal - $pl;

                            if($orig_pl > 0)
                            {
                                $data->push(['leave_id' => 3,'user_id' => $staffs->id,'empcode' => $staffs->username,'leave_bal_prev' => $orig_pl,'leave_bal' => $val,'leave_bal_nega' => 0,'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),'process_code' => $process_code]);
                            }


                            if(request()->mon == 12)
                            {
                                $data->push(['leave_id' => 3,'user_id' => $staffs->id,'empcode' => $staffs->username,'leave_bal_prev' => $val,'leave_bal' => 3,'leave_bal_nega' => 0,'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),'process_code' => $process_code]);
                            }
                            
                        break;
                    case 'Solo Parent Leave':
                        # code...
                            $val = $valuebal - $spl;

                            if($orig_spl > 0)
                            {
                                $data->push(['leave_id' => 4,'user_id' => $staffs->id,'empcode' => $staffs->username,'leave_bal_prev' => $orig_spl,'leave_bal' => $val,'leave_bal_nega' => 0,'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),'process_code' => $process_code]);
                            }

                        break;
                    case 'Compensatory Time-Off':
                        # code...
                            $val = $valuebal - $cto;

                            if($orig_cto > 1)
                            {
                                $data->push(['leave_id' => 5,'user_id' => $staffs->id,'empcode' => $staffs->username,'leave_bal_prev' => $orig_cto,'leave_bal' => $val,'leave_bal_nega' => 0,'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),'process_code' => $process_code]);
                            }


                        break;
                    case 'Force Leave':
                        # code...
                            $val = $valuebal - $fl;

                            $deficit = 0;
                            //UPDATE LEAVE
                            if($val > 0)
                            {
                                $data->push(['leave_id' => 6,'user_id' => $staffs->id,'empcode' => $staffs->username,'leave_bal_prev' => $orig_fl,'leave_bal' => $val,'leave_bal_nega' => $deficit,'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),'process_code' => $process_code]);
                            }
                            elseif($val <= 0)
                            {
                                $data->push(['leave_id' => 6,'user_id' => $staffs->id,'empcode' => $staffs->username,'leave_bal_prev' => $orig_fl,'leave_bal' => 0,'leave_bal_nega' => $deficit,'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),'process_code' => $process_code]);
                            }


                            if(request()->mon == 12)
                            {
                                $data->push(['leave_id' => 6,'user_id' => $staffs->id,'empcode' => $staffs->username,'leave_bal_prev' => $val,'leave_bal' => 5,'leave_bal_nega' => 0,'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),'process_code' => $process_code]);
                            }

                        break;
                    case 'Maternity Leave':
                        # code...
                            $val = $valuebal - $MatL;

                            if($orig_MatL > 0)
                            {
                                $data->push(['leave_id' => 7,'user_id' => $staffs->id,'empcode' => $staffs->username,'leave_bal_prev' => $orig_MatL,'leave_bal' => $val,'leave_bal_nega' => 0,'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),'process_code' => $process_code]);
                            }
                        break;
                    case 'Paternity Leave':
                        # code...
                            $val = $valuebal - $PatL;

                            if($orig_PatL > 0)
                            {
                                $data->push(['leave_id' => 8,'user_id' => $staffs->id,'empcode' => $staffs->username,'leave_bal_prev' => $orig_PatL,'leave_bal' => $val,'leave_bal_nega' => 0,'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),'process_code' => $process_code]);
                            }
                        break;
                    case 'Study Leave':
                        # code...
                            $val = $valuebal - $StL;

                            if($orig_StL > 0)
                            {
                                $data->push(['leave_id' => 9,'user_id' => $staffs->id,'empcode' => $staffs->username,'leave_bal_prev' => $orig_StL,'leave_bal' => $val,'leave_bal_nega' => 0,'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),'process_code' => $process_code]);
                            }
                        break;
                    case 'Rehabilitation Leave':
                        # code...
                            $val = $valuebal - $ReL;

                            if($orig_ReL > 0)
                            {
                                $data->push(['leave_id' => 10,'user_id' => $staffs->id,'empcode' => $staffs->username,'leave_bal_prev' => $orig_ReL,'leave_bal' => $val,'leave_bal_nega' => 0,'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),'process_code' => $process_code]);
                            }
                        break;
                    case 'Emergency Leave':
                        # code...
                            $val = $valuebal - $el;

                            if($orig_el > 0)
                            {
                                $data->push(['leave_id' => 11,'user_id' => $staffs->id,'empcode' => $staffs->username,'leave_bal_prev' => $orig_el,'leave_bal' => $val,'leave_bal_nega' => 0,'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),'process_code' => $process_code]);
                            }
                        break;
                    case 'Monetize Leave':
                        # code...
                            $val = $valuebal - $ml;

                            if($orig_ml > 0)
                            {
                                $data->push(['leave_id' => 13,'user_id' => $staffs->id,'empcode' => $staffs->username,'leave_bal_prev' => $orig_ml,'leave_bal' => $val,'leave_bal_nega' => 0,'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),'process_code' => $process_code]);
                            }
                        break;
                    case 'Special Leave (Magna Carta of Women)':
                        # code...
                            $val = $valuebal - $spec;

                            if($orig_spec > 0)
                            {
                                $data->push(['leave_id' => 12,'user_id' => $staffs->id,'empcode' => $staffs->username,'leave_bal_prev' => $orig_spec,'leave_bal' => $val,'leave_bal_nega' => 0,'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),'process_code' => $process_code]);
                            }

                        break;
                    case 'Unauthorized Absence':
                        # code...
                            $val = $valuebal - $ua;

                            if($orig_ua > 0)
                            {
                                $data->push(['leave_id' => 14,'user_id' => $staffs->id,'empcode' => $staffs->username,'leave_bal_prev' => $orig_ua,'leave_bal' => $val,'leave_bal_nega' => 0,'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),'process_code' => $process_code]);
                            }
                        break;
                    case 'Sick Leave Without Pay':
                        # code...
                            $val = $valuebal - $slwop;

                            if($orig_slwop > 0)
                            {
                                $data->push(['leave_id' => 15,'user_id' => $staffs->id,'empcode' => $staffs->username,'leave_bal_prev' => $orig_slwop,'leave_bal' => $val,'leave_bal_nega' => 0,'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),'process_code' => $process_code]);
                            }
                        break;
                }

                $remarks .= $keybal." : <b>".$val."</b><br/>";
            }


            //PROCESS T.O

            //GET T.O
            $to_req = App\RequestTO::where('parent','YES')->where('userid',$staffs->id)->where('to_status','Approved')->whereMonth('to_date_from',request()->mon)->whereYear('to_date_from',request()->yr)->get();
            
            if($to_req)
            {
                foreach ($to_req as $key => $valueto) {
                    
                    $tos = App\RequestTO::where('id',$valueto->id)
                            ->update([
                                        // 'to_status' => "Processed",
                                        'process_code' => $process_code
                                    ]);
                    
                    if($valueto->to_total_day <= 1.0)
                    {
                        //$to_mc_deduc = $valueto->to_total_day;
                        $to_mc_deduc = 1;
                    }
                    else
                    {
                        $to_mc_deduc = 1;
                    }


                    //GET DATE
                    $from = Carbon::parse($valueto->to_date_from);
                    $to = Carbon::parse($valueto->to_date_to);
                    $diff = 1+($from->diffInDays($to));

                    for($i = 1; $i <= $diff; $i++)
                    {
                        
                        if($i == 1)
                        {
                            $dt = date('Y-m-d',strtotime($from));
                            $orig_from = $dt;
                        }
                        else
                        {
                            $dt = $from->addDays(1);             
                        }


                        if(!$this->checkIfWeekend($dt))
                                {
                                    if(!checkIfHoliday($dt))
                                    {  
                                        if($valueto->to_perdiem == 'YES')
                                            {
                                                $this->addToMCDays($staffs->id,$staffs->username,'Travel',$to_mc_deduc,$dt,$dt,$process_code);
                                            }
                                    }
                                }
                    }


                    

                    //UPDATE CHILED T.O
                    App\RequestTO::whereNull('parent')->where('parent_to_code',$valueto->process_to)
                            ->update([
                                        // 'to_status' => "Processed",
                                        'process_code' => $process_code
                                    ]);
                }   
            }

            // $remarks .= "<br/>---Deficit---<br/>$deficit";

            // SAVE TO DTR SUMMARY
            $processed = new App\DTRProcessed;
            $processed->process_code = $process_code;
            $processed->userid = $staffs->id;
            $processed->empcode = $staffs->username;
            $processed->employee_name = $staffs->lname . ", " .$staffs->fname . " " . $staffs->mname;
            $processed->dtr_mon = request()->mon;
            $processed->dtr_year = request()->yr;
            $processed->dtr_division = $staffs->division;
            $processed->vl_leave =  $vl_ctr;
            $processed->vl_lwop = $nolwp;
            $processed->vl_late = $no_process_lates;
            $processed->vl_totalunderlate = $no_process_under;
            $processed->vl_totalunderlatededuc = $totaldeduction;
            $processed->vl_bal = $vl_bal;
            $processed->vl_undertime = $no_process_lates_under;
            $processed->vl_tardy = $tardy;
            $processed->sl_leave = $sl;
            $processed->nolates = $no_lates_total; 
            $processed->noabsent = $no_process_absent;
            $processed->sl_bal = $sl_bal;
            $processed->save();
            $processed_id = $processed->id;


            $lv = App\Employee_leave::insert($data->all());


            //DISAPPROVED FL
            $leave_req_fl = App\Request_leave::whereNotNull('parent')->where('user_id',$staffs->id)->where('leave_id',6)->where('leave_action_status','Disapproved')->whereMonth('leave_date_from',request()->mon)->whereYear('leave_date_from',request()->yr)->get();

                if($leave_req_fl)
                {
                    //GET TOTAL FL DISAPPROVED
                    $total_fl = 0;
                    foreach ($leave_req_fl as $key => $fls) {
                        $total_fl += $fls->leave_deduction;
                    }

                    $bal = getLeaves($staffs->id,6);      
                }

            //SAVE HP/SALA
            if($staffs->employement_id != 15)
                {
                    $this->saveHP($process_code,$staffs->id,$staffs->username,request()->mon,request()->yr);
                    $this->saveSALA($process_code,$staffs->id,$staffs->username,request()->mon,request()->yr);

                    $this->saveMC($process_code,$staffs->id,$staffs->username,request()->mon,request()->yr);
                }

            }
        }
        
        return redirect('dtr/emp/'.request()->mon.'/'.request()->yr);
        // echo $remarks."<hr/>";
    }

    public function checkIfWeekend($dt)
    {
        $dt = Carbon::parse($dt);

        if($dt->isWeekend())
            return true;
        else
            return false;
    }

    function saveHP($process_code,$userid,$username,$mon,$yr)
    {

        $m = $mon;
        $y = $yr;
        if($m == 12)
            {
                $m = 1;
                ++$y;
            }
            else
            {
                ++$m;
            }

        //GET SALARY
        $plantilla = getPlantillaInfo($username);

        $hp = new App\Employee_hp;
        $hp->user_id = $userid;
        $hp->empcode = $username;
        $hp->hp_salary = $plantilla['plantilla_salary'];
        $hp->hp_per = 15;
        $hp->hp_mon = $m;
        $hp->hp_year = $y;
        $hp->process_code = $process_code;
        $hp->save();

    }

    function saveSALA($process_code,$userid,$username,$mon,$yr)
    {
        $m = $mon;
        $y = $yr;
        if($m == 12)
            {
                $m = 1;
                $y = ++$y;
            }
            else
            {
                ++$m;
            }

        $mon2 = date('F',mktime(0, 0, 0, $m, 10));

        $totaldays = Carbon::parse($mon2.'-'.$y)->daysInMonth;
        $weekdays = 0;
        for($i = 1;$i <= $totaldays;$i++)
            {
                $dtr_date = date("Y-m-d",strtotime($y.'-'.$m.'-'.$i));
                $dayDesc = weekDesc($dtr_date);
                switch ($dayDesc) {
                    case 'Sat':
                    case 'Sun':
                        # code...
                        break;
                                    
                    default:
                             ++$weekdays;
                         break;
                }
            }
        $sa_perday = 150;
        $sa_amt = $weekdays * $sa_perday;

        $la_amt = 500;
        $la_perday = 22.73;

        //COUNT HLODAY
        $holiday = countHolidays($m,$y);

        //COUNT WORK SUSPENSION
        $worksus = countWorkSus($m,$y);

        // $noday = countDaysInOffice($userid,$mon,$yr);

        $totalnoday = $holiday + $worksus;

        $sa_total = $sa_amt - ($totalnoday * $sa_perday);
        $la_total = $la_amt - ($totalnoday * $la_perday);

        $sala = new App\Employee_sala;
        $sala->user_id = $userid;
        $sala->empcode = $username;
        $sala->fullname = getStaffInfo($userid);
        $sala->division = Auth::user()->division;
        $sala->sa_amt = $sa_total;
        $sala->la_amt = $la_total;
        // $sala->noday = $noday;
        $sala->sala_mon = $m;
        $sala->sala_year = $y;
        $sala->process_code = $process_code;
        $sala->save();
    }

    function saveMC($process_code,$userid,$username,$mon,$yr)
    {
        //GET MC DEDUC
        $dudec = App\Payroll\MCDeduc::where('fldEmpCode',$username)->whereNotNull('MC_AMOUNT')->whereNotNull('SERV_CODE')->get();
        $gsis = 0;
        $hmo = 0;
        $pmpc = 0;
        $cdc = 0;
        $gfal = 0;
        $lb = 0;

        $m = $mon;
        $y = $yr;
        if($m == 12)
            {
                $m = 1;
                $y = ++$y;
            }
            else
            {
                ++$m;
            }
        
        foreach ($dudec as $key => $value) {

            $amt = $value->MC_AMOUNT;

            //GSIS
            if($value->ORG_CODE == 1)
            {
                switch($value->SERV_CODE)
                {
                    case '319A':
                        $gfal += $amt;
                    break;

                    default:
                        $gsis += $amt;
                }
            }

            //PMPC
            if($value->ORG_CODE == 6)
            {
                if($value->SERV_CODE == '933')
                    $hmo += $amt;
                else
                    $pmpc += $amt;
            }

            //CDC
            if($value->ORG_CODE == 5)
            {
                $cdc += $amt;
            }

            //LANDBANK
            if($value->ORG_CODE == 13)
            {
                $lb += $amt;
            }
            

        }
        //GET CURRENT SALARY
        $plantilla = getPlantillaInfo($username);


        //GET ITW
        $itw = getITW($userid);

        $lp = getLP($userid);

        //ENTITLED SA 30% HP
        //43,344,83,118,80,139,213,226,99
        switch ($userid) {
            case 43:
            case 344:
            case 83: 
            case 118:
            case 80:
            case 139:
            case 213:
            case 226: 
            case 99:  
                    $hprate = 0.30;
                break;
            
            default:
                    $hprate = 0.15;
                break;
        }

        //SALA
        //GET PREVIOUS MONTH
        $m2 = $m;
        $y2 = $y;
        if($m == 1)
        {
            $m2 = 12;
            $y2 = $y2 - 1;
        }
        else
        {
            $m2 = $m - 1;
        }

        //CHECK TARDIES
        $tardy_total = $this->getUnderTime($userid,$username,$m2,$y2,$process_code);
            

        // //LEAVE
        // //MULTIPLE DATE
        // $l1_total = App\Request_leave::where('user_id',$userid)->whereNull('parent_leave')->whereNotNull('parent_leave_code')->whereNotIn('leave_id',[5,16])->where('leave_action_status','Approved')->whereMonth('leave_date_from',$m2)->whereYear('leave_date_from',$y2)->count();

        // //SINGLE DATE
        // $l2_total = App\Request_leave::where('user_id',$userid)->where('parent','YES')->whereIn('leave_deduction',[1,0.5])->whereNotIn('leave_id',[5,16])->where('leave_action_status','Approved')->whereMonth('leave_date_from',$m2)->whereYear('leave_date_from',$y2)->sum('leave_deduction');   
        
        // //TARDY
        // $total_tardy = 0;
        // $tardy = App\Employee_tardy::where('user_id',$userid)->whereMonth('fldEmpDTRdate',$m2)->whereYear('fldEmpDTRdate',$y2)->get();
		
        // foreach ($tardy as $key => $tardies) {
		// 	$total_tardy += $tardies->total_day;
        // }

        // //T.O
        // $total_to = 0;

        // //MULTIPLE
        // $tos = App\RequestTO::where('userid',$userid)->where('to_perdiem','YES')->whereNull('parent')->where('to_status','Approved')->whereMonth('to_date_from',$m2)->whereYear('to_date_from',$y2)->get();
		
        // foreach ($tos as $key => $toss) {
		// 	//check if weekend
		// 	if(!checkIfWeekend($toss['to_date_from']))
		// 	{
		// 		$total_to += $toss['to_total_day'];
		// 	}
            
        // }

        // //SINGLE DATE
        // $tos = App\RequestTO::where('userid',$userid)->where('to_perdiem','YES')->where('parent','YES')->whereIn('to_total_day',[1.0,0.5])->where('to_status','Approved')->whereMonth('to_date_from',$m2)->whereYear('to_date_from',$y2)->get();
		
        // foreach ($tos as $key => $toss) {
		// 	//check if weekend
		// 	if(!checkIfWeekend($toss['to_date_from']))
		// 	{
		// 		$total_to += $toss['to_total_day'];
		// 	}
            
        // }
        $l1_total2 = 0;

        $l1_total = App\Request_leave::where('user_id',$userid)->whereNull('parent_leave')->whereNotNull('parent_leave_code')->whereNotIn('leave_id',[5,16,17,19])->where('leave_action_status','Approved')->whereMonth('leave_date_from',$m2)->whereYear('leave_date_from',$y2)->get();
            foreach ($l1_total as $k1 => $v1) {
                if(!checkIfWeekend($v1->leave_date_from))
                {
                    if($v1->leave_action_status == 'Approved')
                    {   
                        $l1_total2++;
                    }
                }
            }

            //SINGLE DATE (WHOLEDAY)
            $l2_total = App\Request_leave::where('user_id',$userid)->where('parent','YES')->where('leave_deduction','<=',1)->whereNotIn('leave_id',[5,16,17,19])->where('leave_action_status','Approved')->whereMonth('leave_date_from',$m2)->whereYear('leave_date_from',$y2)->where('leave_deduction',1)->get();
            foreach ($l2_total as $k2 => $v2) {
                if(!checkIfWeekend($v2->leave_date_from))
                {
                    if($v2->leave_action_status == 'Approved')
                    { 
                        $l1_total2++;
                    }
                }
            }

            $l_total = count($l1_total) + count($l2_total);


            //SINGLE DATE (HALFDAY)
            $leaveHalfDates = "";
            $l3_total = App\Request_leave::where('user_id',$userid)->where('parent','YES')->whereNotIn('leave_id',[5,16,17,19])->where('leave_action_status','Approved')->whereMonth('leave_date_from',$m2)->whereYear('leave_date_from',$y2)->where('leave_deduction',0.5)->get();
            
            foreach ($l3_total as $k3 => $v3) {
                if(!checkIfWeekend($v3->leave_date_from))
                {
                    $leaveHalfDates .= date('d',strtotime($v3->leave_date_from)).", ";
                }
            }


            //TARDY
            // $tardy_total = 0;
            // $tardy_total_half = 0;
            // $tardy = App\Employee_tardy::where('user_id',$userid)->whereMonth('fldEmpDTRdate',$m2)->whereYear('fldEmpDTRdate',$y2)->get();
            
            // foreach ($tardy as $trk => $trs) {
            //     if(!checkIfWeekend($trs->fldEmpDTRdate))
            //         {
            //             if(!checkIfHoliday($trs->fldEmpDTRdate))
            //                 $tardy_total += $trs['to_total_day'];
            //         }
            // }



            //TO PER DIEM YES MULTIPLE
            $perdiemYesDates = "";
            $l4_total = App\RequestTO::where('userid',$userid)->whereNull('parent')->whereNotNull('parent_to_code')->where('to_perdiem','YES')->where('to_status','Approved')->whereMonth('to_date_from',$m2)->whereYear('to_date_from',$y2)->get();

            $t_to_y_0 = 0;
            foreach ($l4_total as $k4 => $v4) {
                if(!checkIfWeekend($v4->to_date_from))
                {
                    if(!checkIfHoliday($v4->to_date_from))
                        $t_to_y_0 += $v4['to_total_day'];
                }
            }


            //TO PER DIEM YES SINGLE DATE
            $l5_total = App\RequestTO::where('userid',$userid)->where('parent','YES')->whereIn('to_total_day',[1.0,0.5])->where('to_perdiem','YES')->where('to_status','Approved')->whereMonth('to_date_from',$m2)->whereYear('to_date_from',$y2)->get();

            $t_to_y_1 = 0;
            foreach ($l5_total as $k5 => $v5) {
                if(!checkIfWeekend($v5->to_date_from))
                {   
                    if(!checkIfHoliday($v5->to_date_from))
                        //$t_to_y_1 += $v5['to_total_day'];
                        $t_to_y_1 += 1;
                }
            }


            //TO PER DIEM YES MULTIPLE
            // $perdiemNoDates = "";
            // $l6_total = App\RequestTO::where('userid',$userid)->whereNull('parent')->whereNotNull('parent_to_code')->where('to_perdiem','NO')->where('to_status','Approved')->whereMonth('to_date_from',$m2)->whereYear('to_date_from',$y2)->get();

            // foreach ($l6_total as $k6 => $v6) {
            //     if(!checkIfWeekend($v6->to_date_from))
            //     {
            //         $perdiemNoDates .= date('d',strtotime($v6->to_date_from)).", ";
            //     }
            // }


            //TO PER DIEM YES SINGLE DATE
            // $l7_total = App\RequestTO::where('userid',$userid)->where('parent','YES')->whereIn('to_total_day',[1.0,0.5])->where('to_perdiem','NO')->where('to_status','Approved')->whereMonth('to_date_from',$m2)->whereYear('to_date_from',$y2)->get();
            // foreach ($l7_total as $k7 => $v7) {
            //     if(!checkIfWeekend($v7->to_date_from))
            //     {
            //         $perdiemNoDates .= date('d',strtotime($v7->to_date_from)).", ";
            //     }
			// }

		$l_total = ($l1_total2 + $tardy_total) + (count($l3_total) * 0.5) + $t_to_y_0 + $t_to_y_1;


        //$l_total = $l1_total + $l2_total + $total_to + $total_tardy;

        $sa_amt = getWorkingDate($yr.'-'.$m.'-01');

		$sa = $sa_amt - ($l_total * 150);
        //$sa = $salas->sa_amt - ($l_total * 150);
        $l_1 = $l1_total2 + $tardy_total;
        $l_2 = count($l3_total) * 0.5;
        $la = 500 - ((500 / 22) * ($l_1 + $l_2));

		if($sa < 0)
		{
			$sa = 0;
		}

		if($la < 0)
		{
			$la = 0;
		}

        $mc = new App\Payroll\MC;
        $mc->payroll_mon = $m;
        $mc->payroll_yr = $y;
        $mc->empcode = $username;
        $mc->salary = $plantilla['plantilla_salary'];
        $mc->userid = $userid;
        $mc->sa = $sa;
        $mc->la = $la;
        $mc->lp = $lp;
        $mc->hmo = $hmo;
        $mc->gsis = $gsis;
        $mc->pmpc = $pmpc;
        $mc->cdc = $cdc;
        $mc->gfal = $gfal;
        $mc->landbank = $lb;
        $mc->itw = $itw;
        $mc->hprate = $hprate;
        $mc->process_code = $process_code;
        $mc->save();



    }

    function checkIfProcess($userid,$mon,$yr)
    {
        $dtr = App\DTRProcessed::where('userid',$userid)->where('dtr_mon',$mon)->where('dtr_year',$yr)->count();

        if($dtr > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    function addToMCDays($userid,$empcode,$reqtype,$deduc,$reqdate,$reqdate2,$processcode)
    {
        $mc = new App\MCday;
        $mc->userid = $userid;
        $mc->empcode = $empcode;
        $mc->req_type = $reqtype;
        $mc->req_deduc = $deduc;
        $mc->req_date_from = $reqdate;
        $mc->req_date_to = $reqdate2;
        $mc->process_code = $processcode;
        $mc->save();
    }

    public function getUnderTime($userid,$empcode,$mon,$yr,$process_code)
    {
        //return getWorkingDate('2022-05-01');
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 180);

        $total_tardy = 0;

        $dtr = App\Employee_dtr::where('user_id',$userid)->whereMonth('fldEmpDTRdate',$mon)->whereYear('fldEmpDTRdate',$yr)->orderBy('fldEmpDTRdate')->get();

        

        foreach ($dtr as $key => $dtrs) {

            $dayDesc = weekDesc($dtrs->fldEmpDTRdate);

            $flag = 0;

            if(!checkIfWeekend($dtrs->fldEmpDTRdate))
            {

                if(!checkIfHoliday($dtrs->fldEmpDTRdate))
                {
                    $totalhrs = 0;
                    if($dtrs->fldEmpDTRamIn != null && $dtrs->fldEmpDTRamOut != null && $dtrs->fldEmpDTRpmIn != null && $dtrs->fldEmpDTRpmOut != null)
                    {
                        $totalhrs = totalHrs($dayDesc,$dtrs->fldEmpDTRdate,$dtrs->fldEmpDTRamIn,$dtrs->fldEmpDTRpmOut,null,1);
                    }

                    if($dtrs->fldEmpDTRamIn != null && $dtrs->fldEmpDTRamOut != null && $dtrs->fldEmpDTRpmIn != null && $dtrs->fldEmpDTRpmOut == null)
                    {
                        $totalhrs = totalHrs($dayDesc,$dtrs->fldEmpDTRdate,$dtrs->fldEmpDTRamIn,$dtrs->fldEmpDTRamOut,null,1);
                    }

                    if($dtrs->fldEmpDTRamIn != null && $dtrs->fldEmpDTRamOut != null && $dtrs->fldEmpDTRpmIn == null && $dtrs->fldEmpDTRpmOut == null)
                    {
                        $dt = $dtrs->fldEmpDTRdate;
                        $tm1 = $dtrs->fldEmpDTRamIn;
                        $tm2 = $dtrs->fldEmpDTRamOut;
                        if($dayDesc == 'Mon')
                        {
                            if($tm1 <= "07:30:00")
                                $tm1 = "07:30:00";
                        }

                        if(checkIfHalfHoliday($dtrs->fldEmpDTRdate))
                        {
                            if($tm1 >= "07:30:00" && $tm1 <= "08:00:00")
                                $tm2 = "12:00:00";
                            elseif($tm1 >= "08:00:00" && $tm1 <= "08:30:00")
                                $tm2 = "12:30:00";
            
                        }
                        else
                        {
                            if($tm2 >= "12:00:00")
                                $tm2 = "12:00:00";
                        }
                        
                        
                        $dt1 = Carbon::parse($dt.' '.$tm1)->format('Y-m-d H:s:i');
                        $dt2 = Carbon::parse($dt.' '.$tm2)->format('Y-m-d H:s:i');
                        $to = Carbon::createFromFormat('Y-m-d H:s:i', $dt1);
                        $from = Carbon::createFromFormat('Y-m-d H:s:i',  $dt2);

                        $totalhrs = $to->diffInMinutes($from);

                        // if($empcode == 'GAR005')
                        // {
                        //     $tardy = new App\Employee_tardy;
                        //         $tardy->user_id = $userid;
                        //         $tardy->fldEmpCode = $empcode;
                        //         $tardy->fldEmpDTRdate = $dtrs->fldEmpDTRdate;
                        //         $tardy->totalHrs = $totalhrs;
                        //         $tardy->process_code = $process_code;
                        //         $tardy->save();
                        // }

                        
                    }

                    if($dtrs->fldEmpDTRamIn == null && $dtrs->fldEmpDTRamOut == null && $dtrs->fldEmpDTRpmIn != null && $dtrs->fldEmpDTRpmOut != null)
                    {
                        $dt = $dtrs->fldEmpDTRdate;
                        $tm1 = $dtrs->fldEmpDTRpmIn;
                        $tm2 = $dtrs->fldEmpDTRpmOut;
                        if($dayDesc == 'Mon')
                        {
                            if($tm1 <= "13:00:00")
                                $tm1 = "13:00:00";
                            
                            if($tm2 >= "17:00:00")
                                $tm2 = "17:00:00";
                        }

                        if($tm2 >= "17:30:00")
                                $tm2 = "17:30:00";
                        
                        $dt1 = Carbon::parse($dt.' '.$tm1)->format('Y-m-d H:s:i');
                        $dt2 = Carbon::parse($dt.' '.$tm2)->format('Y-m-d H:s:i');
                        $to = Carbon::createFromFormat('Y-m-d H:s:i', $dt1);
                        $from = Carbon::createFromFormat('Y-m-d H:s:i',  $dt2);

                        $totalhrs = $to->diffInMinutes($from);
                        //$totalhrs = totalHrs($dayDesc,$dtrs->fldEmpDTRdate,"13:00:00",$dtrs->fldEmpDTRpmOut,null,2,'PM');
                    }

                    if(checkIfHalfHoliday($dtrs->fldEmpDTRdate))
                    {
                        $totalhrs += 240;
                    }

                    $sus = App\Suspension::where('fldSuspensionDate',$dtrs->fldEmpDTRdate)->first();

                    if($sus)
                    {
                            $totalhrs = $totalhrs + ($sus['fldMinHrs'] * 60);
                    }
                    
                    if($totalhrs >= 240 && $totalhrs < 360)
                    {
                        if(checkIfHasLeave($dtrs->fldEmpDTRdate,$userid))
                        {
                            $flag++;

                            // $test = new App\PSB;
                            // $test->name = "DATE : ".$dtrs->fldEmpDTRdate." TOTAL HRS : ".$totalhrs. " HAS LEAVE";
                            // $test->save();
                        }

                        $tos = App\RequestTO::where('to_date_from',$dtrs->fldEmpDTRdate)->where('userid',$userid)->whereIn('to_status',['Approved','Processed'])->first();

                        if($tos)
                        {
                            $flag++;

                            // $test = new App\PSB;
                            // $test->name = "DATE : ".$dtrs->fldEmpDTRdate." TOTAL HRS : ".$totalhrs. " HAS T.O";
                            // $test->save();
                        }

                        if($flag == 0)
                        {
                            $tardy = new App\Employee_tardy;
                            $tardy->user_id = $userid;
                            $tardy->fldEmpCode = $empcode;
                            $tardy->fldEmpDTRdate = $dtrs->fldEmpDTRdate;
                            $tardy->process_code = $process_code;
                            $tardy->totalHrs = $totalhrs;
                            $tardy->total_day = 0.5;
                            $tardy->save();

                            $total_tardy += 0.5;
                        }
                        

                    }	
                    elseif($totalhrs < 240)
                    {
                        if(checkIfHasLeave($dtrs->fldEmpDTRdate,$userid))
                        {
                            $flag++;

                            // $test = new App\PSB;
                            // $test->name = "DATE : ".$dtrs->fldEmpDTRdate." TOTAL HRS : ".$totalhrs. " HAS LEAVE";
                            // $test->save();
                        }

                        $tos = App\RequestTO::where('to_date_from',$dtrs->fldEmpDTRdate)->where('userid',$userid)->whereIn('to_status',['Approved','Processed'])->first();

                        if($tos)
                        {
                            $flag++;

                            // $test = new App\PSB;
                            // $test->name = "DATE : ".$dtrs->fldEmpDTRdate." TOTAL HRS : ".$totalhrs. " HAS T.O";
                            // $test->save();
                        }

                        

                        if($flag == 0)
                            {
                                $tardy = new App\Employee_tardy;
                                $tardy->user_id = $userid;
                                $tardy->fldEmpCode = $empcode;
                                $tardy->fldEmpDTRdate = $dtrs->fldEmpDTRdate;
                                $tardy->totalHrs = $totalhrs;
                                $tardy->total_day = 1;
                                $tardy->process_code = $process_code;
                                $tardy->save();

                                $total_tardy += 1;
                            }
                            
                    }                   

                }
            }

        }

        return $total_tardy;
    }

    public function getTardy($userid,$mn,$yr)
    {
        // return request()->mon2;
        $worksched = getDTROption();

        $emp = App\User::where('id',$userid)->first();

        $rows = "";
        
        $mon = date('m',strtotime($mn));
        $mon2 = date('F',mktime(0, 0, 0, $mn, 10));
        $yr = $yr;
        $date = $mon2 ."-" . $yr;
        $month = ++$mon;

        $tLates = 0;
        $tUndertime = 0;
        $tLatesWeeks = 0;
        $tUndertimeWeeks = 0;
        $tUndertimeWeeks2 = "";
        $tLastLatesWeeks = 0;
        $tLastUndertimeWeeks = 0;

        $tLateCTR = 0;
        $tUndertimeCTR = 0;

        $grandTotalHrs = 0;
        $grandTotalHrsRendered = 0;


        $tUndertime = 0;
        $totalDeficit = 0;
        $tDaysExcess = 0;
        $tDaysDeficit = 0;
        $tLDaysExcess = 0;
        $tLDaysDeficit = 0;

        $tDeficit = 0;
        $tDaysLeave = 0;
        $tTimeWeek = 0;
        $lastTimeWeek = 0;
        $lastWeekLeaves = 0;

        $week1Time = 1920;

        $total = Carbon::parse($date)->daysInMonth;
                     
                      $prevweek = 1;
                      $rows .= "<tr><td></td><td colspan='8' align='center'>  <b>WEEK 1 </b> </td><td></td></tr>";
                      $week_num = 2;
                      $total_days = 0;
                      for($i = 1;$i <= $total;$i++)
                      {
                          $weeknum = weekOfMonth(date($yr.'-'.$mn.'-'.$i));
                            if($weeknum == $prevweek)
                            {
                              
                            }
                            else
                            {
                              
                            $totalhrsweek = readableTime($tTimeWeek);
                            //$tTotalDays = readableTime($total_days - $tDaysLeave);

                            if($total_days > $tDaysLeave)
                            {
                                $tTotalDays = readableTime($total_days - $tDaysLeave);
                                $grandTotalHrs += $total_days - $tDaysLeave;
                            } 
                            else
                            {
                                $tTotalDays = readableTime(0);
                                $grandTotalHrs += 0;
                            }
                                

                            //$tTotalDays = $total_days." - ".$tDaysLeave;
                              
                              $grandTotalHrsRendered += $tTimeWeek;

                            //   if((($total_days - $tDaysLeave) - $tTimeWeek) <= 0)
                            //   {
                            //     $tDeficit = readableTime(0);
                            //     $totalDeficit += 0;
                            //   }
                            //   else
                            //   {
                            //       $tDeficit = readableTime(($total_days - $tDaysLeave) - $tTimeWeek)." ";
                            //       $totalDeficit += ($total_days - $tDaysLeave) - $tTimeWeek;
                            //   }
                            
                              //DEFICIT
                              if($tDaysDeficit > $tDaysExcess)
                              {
                                $tDeficit = readableTime($tDaysDeficit - $tDaysExcess);
                                $totalDeficit += $tDaysDeficit - $tDaysExcess;
                              }
                              else
                              {
                                $tDeficit = readableTime(0);
                                $totalDeficit += 0;
                              }


                              $tLatesWeeks = readableTime($tLatesWeeks);
                              $tUndertimeWeeks = readableTime($tUndertimeWeeks);

                              
                                
                              
                              $rows .= "<tr><td></td><td colspan='4' align='right' style='padding-right:5px'> <b>TOTAL HRS (".$tTotalDays.")</b> </td><td align='center'><b>".$totalhrsweek."</b></td><td align='center'><b>".$tLatesWeeks."</b></td><td align='center'><b>".$tUndertimeWeeks."</b></td><td align='center'><b>".$tDeficit."</b></td><td></td></tr>";

                              $prevweek = $weeknum;
                              $rows .= "<tr><td></td><td colspan='8' align='center'> <b>WEEK $week_num </b> </td><td></td></tr>";
                              $week_num++;
                              $total_days = 0;
                              $tTimeWeek = 0;
                              $tDaysLeave = 0;
                              $tDeficit = 0;
                              $tLatesWeeks = 0;
                              $tUndertimeWeeks = 0;

                              $tDaysExcess = 0;
                              $tDaysDeficit = 0;
                              
                            }

                            

                            $dtr_date = date("Y-m-d",strtotime($yr.'-'.$mn.'-'.$i));

                            //echo $dtr_date."<br/>";

                            $dayDesc = weekDesc($dtr_date);
                            $dtr = getDTRemp($dtr_date,$emp['id'],$emp['employment_id'],$emp['username']);
                            if(!$dtr)
                            {
                                $dtr = array();
                            }

                            
                                // //UDERTIME
                                // $under = null;
                                // if(countUndertime($dtr_date,$dayDesc,$dtr['fldEmpDTRamIn'],$dtr['fldEmpDTRamOut'],$dtr['fldEmpDTRpmIn'],$dtr['fldEmpDTRpmOut'],6))
                                // {
                                //     $undertime = explode("|", countUndertime($dtr_date,$dayDesc,$dtr['fldEmpDTRamIn'],$dtr['fldEmpDTRamOut'],$dtr['fldEmpDTRpmIn'],$dtr['fldEmpDTRpmOut']));
                                    
                                //     $under = $undertime[0];
                                //     $tUndertime += $undertime[1];
                                // }

                                // //LATES
                                // $lates = null;
                                // if(computeLate($dtr_date,$dayDesc,$dtr['fldEmpDTRamIn'],6))
                                // {
                                //     $late = explode("|",computeLate($dtr_date,$dayDesc,$dtr['fldEmpDTRamIn']));
                                //     $lates = $late[0];
                                //     $tLates += $late[1];
                                // }

                                switch ($dayDesc) {
                                    case 'Sat':
                                    case 'Sun':
                                        # code...
                                        break;
                                    
                                    default:
                                        if(!checkIfHoliday($dtr_date))
	                                        {
                                                $total_days += 480;
                                            }
                                        break;
                                }

                                
                                if($dtr_date <= '2022-03-06')
                                {
                                    $rows .= showDate($dtr,$dtr_date,$i,$dayDesc,$emp['id'],$emp['employment_id'],$emp['username'],null,null);
                                    
                                    if(checkIfHasLeave($dtr_date,$emp['id']))
		                            {
                                        $leave = getLeaveDetails($dtr_date,$emp['id']);
                                        if($leave['leave_deduction_time'] == 'wholeday')
                                        {
                                            $week1Time -= 480;
                                            $tTimeWeek = $week1Time;
                                            $total_days -= 480;
                                        }
                                        else
                                        {
                                            $week1Time -= 240;
                                            $tTimeWeek = $week1Time;
                                            $total_days -= 240;
                                        }
                                    }
                                    else
                                    {
                                        $tTimeWeek = $week1Time;
                                    }
                                }
                                else
                                {
                                    $rowsArr = explode("|",plotDate($dtr,$i,$dayDesc,$dtr_date,$emp['id']));
                                    $rows .= $rowsArr[0];
                                    $tLates +=(int)$rowsArr[1];
                                    //$tUndertime +=(int)$rowsArr[5];
                                    $tTimeWeek +=(int)$rowsArr[3];
                                    $tDaysLeave +=(int)$rowsArr[4];
                                    $tDaysExcess +=(int)$rowsArr[6];
                                    $tDaysDeficit +=(int)$rowsArr[7];
                                    $tLateCTR +=(int)$rowsArr[9];


                                    //$tLatesWeeks += $tLates;
                                    if((int)$rowsArr[1] > 0)
                                    {
                                        $tUndertimeWeeks2 .= $rowsArr[5]."|".(int)$rowsArr[1]."<br/>";
                                        $tLatesWeeks += (int)$rowsArr[1];
                                        //$tLateCTR++;
                                    }

                                    if((int)$rowsArr[2] > 0)
                                    {
                                        $tUndertime +=(int)$rowsArr[2];
                                        $tUndertimeWeeks2 .= $rowsArr[5]."|".(int)$rowsArr[2]."<br/>";
                                        $tUndertimeWeeks += (int)$rowsArr[2];
                                        $tUndertimeCTR++;
                                    }

                                    // return $rowsArr;
                                }
                                
                                

                               if($i == $total)
                                {
                                    $lastTimeWeek = $tTimeWeek ;
                                    $lastWeekLeaves = $tDaysLeave;
                                    $tLastLatesWeeks = $tLatesWeeks;
                                    $tLastUndertimeWeeks = $tUndertimeWeeks;
                                    
                                    $tLDaysExcess += $tDaysExcess;
                                    $tLDaysDeficit += $tDaysDeficit;
                                }
                                
                            
                      }

                      //LAST WEEK
                      $totalhrsweek = readableTime($lastTimeWeek);
                      $tTotalDays = readableTime($total_days);
                      $tTotalDays = readableTime($total_days - $lastWeekLeaves);

                    //   if((($total_days - $lastWeekLeaves) - $lastTimeWeek) <= 0)
                    //   {
                    //     $tDeficit = readableTime(0);
                    //   }
                    //   else
                    //   {
                    //     $tDeficit = readableTime(($total_days - $lastWeekLeaves) - $lastTimeWeek)." ";


                    //     if($totalDeficit < 0)
                    //     {
                    //         $totalDeficit = 0;
                    //     }
                    //     else
                    //     {
                    //         if((($total_days - $lastWeekLeaves) - $lastTimeWeek) > 0)
                    //         {
                    //             $totalDeficit = $totalDeficit + (($total_days - $lastWeekLeaves) - $lastTimeWeek);
                    //         }
                                
                    //     }
                    //   }

                    //DEFICIT
                    if($tLDaysDeficit > $tLDaysExcess)
                    {
                      $tDeficit = readableTime($tLDaysDeficit - $tLDaysExcess);
                      $totalDeficit += $tLDaysDeficit - $tLDaysExcess;
                    }
                    else
                    {
                      $tDeficit = readableTime(0);
                      $totalDeficit += 0;
                    }
                        


                      $tLastLatesWeeks = readableTime($tLatesWeeks);
                      $tLastUndertimeWeeks = readableTime($tUndertimeWeeks);


                      $rows .= "<tr><td></td><td colspan='4' align='right' style='padding-right:5px'> <b>TOTAL HRS (".$tTotalDays.")</b> </td><td align='center'><b>".$totalhrsweek."</b></td><td align='center'><b>".$tLastLatesWeeks."</b></td><td align='center'><b>".$tLastUndertimeWeeks."</b></td><td align='center'><b>".$tDeficit."</b></td><td></td></tr>";
        //DISPLAY LATES
        // $hourslate = floor($tLates / 60);
        // $minuteslate = $tLates % 60;

        // //DISPLAY UNDERTIME
        // $hoursunder = floor($tUndertime / 60);
        // $minutesunder = $tUndertime % 60;

    //    if(getDTROption() == 6)
    //    {
    //          $hoursunder = 0;
    //          $minutesunder = 0;
    //    }

    //     if($emp['employment_id'] != 8)
    //     {
    //         $hourslate = 0;
    //         $minuteslate = 0;
    //     }

        //COUNT NO. LEAVES
        $leaves_total = 0;
        $l1_total = App\Request_leave::where('user_id',$emp['id'])->whereNull('parent_leave')->whereNotNull('parent_leave_code')->whereNotIn('leave_id',[5,16])->where('leave_action_status','Approved')->whereMonth('leave_date_from',$mn)->whereYear('leave_date_from',$yr)->get();

        $dtss = "";

        foreach ($l1_total as $key => $value) {
            $dayDesc2 = weekDesc($value->leave_date_from);
            if(!checkIfHoliday($value->leave_date_from))
			{
                if($dayDesc2 != 'Sat' && $dayDesc2 != 'Sun')
                {
                    
                    $leaves_total++;
                    $dtss .= $value->leave_date_from." -- ";
                }
            }
            
        }

        //SINGLE DATE
        $l2_total = App\Request_leave::where('user_id',$emp['id'])->where('parent','YES')->where('leave_deduction','<=',1)->whereNotIn('leave_id',[5,16])->where('leave_action_status','Approved')->whereMonth('leave_date_from',$mn)->whereYear('leave_date_from',$yr)->sum('leave_deduction');

        $l_total = $leaves_total + $l2_total;

        // $pdf = App::make('dompdf.wrapper');
        // $pdf->loadHTML('<!DOCTYPE html>
        //                     <html>
        //                     <head>
        //                       <title>HRMIS - DTR</title>
        //                       <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        //                     </head>
        //                     <style type="text/css">
        //                             @page {
        //                               margin: 20;
        //                             }
        //                         body
        //                         {
        //                             font-family:Helvetica;
        //                         }
        //                         th,td
        //                         {
        //                             border:1px solid #555;
        //                             font-size:13px;
        //                         }
        //                     </style>
        //                     <body>
        //                     <table width="100%" cellspacing="0" cellpadding="1" style="table-layout: fixed">
        //                         <tr>
        //                           <td style="border : 1px solid #FFF;width:20%" align="right">
        //                           <img src="'.asset('img/DOST.png').'" style="width:100px">
        //                           </td>
        //                           <td style="border : 1px solid #FFF;font-size:12px;" align="center">
        //                                 Republic of the Philippines<br/>
        //                                 PHILIPPINE COUNCIL FOR AGRICULTURE, AQUATIC AND NATURAL RESOURCES
        //                                 RESEARCH AND DEVELOPMENT<br/>
        //                                 Los Baños, Laguna
        //                           </td>
        //                           <td style="border : 1px solid #FFF;font-size:12px;width:20%" >

        //                           </td>
        //                         </tr>
        //                     </table>
        //                         <center><h3><b>Daily Time Record (DTR)<br/>'.$mon2.'  '.$yr.'</b></h3></center>
        //                         <table width="100%" cellspacing="0" cellpadding="1" style="table-layout: fixed">
        //                             <tr>
        //                                 <td style="width:90px"><b>Day</b></td><td style="width60px"><center><b>AM In</b></center></td><td style="width:65px"><center><b>AM Out</b></center></td><td style="width:60px"><center><b>PM In</b></center></td><td style="width:65px"><center><b>PM Out</b></center></td><td style="width:90px"><center><b>Total Hours</b></center></td><td><center><b>Lates</b></center></td><td><center><b>Undertime</b></center></td><td><center><b>Deficit</b></center></td><td style="width:150px"><center><b>Remarks</b></center></td>
        //                             </tr>
        //                             <tbody>
        //                                 '.$rows.'
        //                                 <tr><td></td><td colspan="4" align="right" style="padding-right:5px"> <b>TOTAL </b></td><td align="center"><b></b></td><td align="center"><b>'.readableTime($tLates).'</b></td><td align="center"><b>'.readableTime($tUndertime).'</b></td><td align="center"><b>'.readableTime($totalDeficit).'</b></td><td></td></tr>
        //                             </tbody>
        //                         </table>
        //                         <br>
        //                         <table width="100%" cellspacing="0" cellpadding="1" style="table-layout: fixed">
        //                         <tr>
        //                           <td style="border : 1px solid #FFF;font-size:12px">
        //                                 <b>Total no. of lates : </b> '.$tLateCTR.'<br>
        //                                 <b>Total no. of undertime : </b>'.$tUndertimeCTR.'<br>
        //                                 <b>Total hours deficit : </b>'.readableTime($totalDeficit).'<br>
        //                                 <b>Total no. of leave days : </b>'.$l_total.'d<br>
        //                                 <b>Total no. of unauthorized absences : </b><br>
        //                                 <br>
        //                                 <br>
        //                                 <br>
        //                                 <br>
        //                                 <br>
        //                           </td>
        //                           <td style="border : 1px solid #FFF;font-size:12px" valign="top">
        //                                 <b>Total late hours : </b> '.readableTime($tLates).'<br>
        //                                 <b>Total undertime hours : </b>'.readableTime($tUndertime).'<br>
        //                           </td>
        //                         </tr>
        //                         <tr>
        //                           <td style="border : 1px solid #FFF;font-size:15px" align="center">'.mb_strtoupper(strtolower($emp['fname'].' '.substr($emp['mname'],0,1).'. '.$emp['lname'])).'<br><small><b>Name of Employee</b></small></td>
        //                           <td style="border : 1px solid #FFF;font-size:15px" align="center">'.getDirector($emp['division'],$emp['id']).'</td>
        //                         <tr>
        //                         </table>
        //                     </body>
        //                     </html>')
        // ->setPaper('legal', 'portrait');
        // return $pdf->stream();

        $tardy = $tLates + $tUndertime + $totalDeficit;

                          
        $tardy = readableTime2($tardy);

        $tardy = explode('-',$tardy."-");
        $tardyh = $tardy[0];
        $tardym = $tardy[1];
        $tardyh = (int)$tardyh / 8;
        $tardym = (int)$tardym / 480;
        $total_tardy = $tardyh + $tardym;
        return round($total_tardy,3);
    }
}



