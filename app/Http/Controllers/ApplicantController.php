<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use Carbon\Carbon;

class ApplicantController extends Controller
{
    public function index($letter,$code)
    {
        if(isset($code))
        {
            $data = 
                [
                    "code" => $code,
                    "info" => App\View_vacant_plantilla::where('plantilla_item_number',$code)->first(),
                    "request_id" => $letter
                ];
            return view('applicants.index')->with('data',$data);
        }
        else
        {
            return redirect('job-vacancies');
        }
    	
    }

    public function vacancy()
    {
        $job = App\View_vacant_plantilla::whereNotNull('plantilla_posted')->get();

        return view('applicants.jobvacancies')->with('job',$job);
    }

    public function thankyou()
    {
    	return view('applicants.thank-you');
    }

    public function list($token)
    {
        //GET TOKEN EQUIVELANT TO ID
        $plantilla = App\LinkToApplicant::where('token',$token)->first();

        $data = 
                [
                    "info" => App\View_vacant_plantilla::where('id',$plantilla['plantilla_id'])->first(),
                    "list" => App\View_job_application::where('vacant_plantilla_id',$plantilla['plantilla_id'])->where('div_shortlisted','YES')->get(),
                ];
        return view('applicants.list-of-applicants-for-psb')->with('data',$data);
    }

    public function create()
    {
        //FILES

        $path_file_cv = null;
        if(request()->hasFile('cv'))
        {
            $path_file_cv = request()->file('cv')->store('applicant_cv');
        }

        $path_file_appletter = null;
        if(request()->hasFile('appletter'))
        {
            $path_file_appletter = request()->file('appletter')->store('applicant_letter');
        }

        $path_file_trainingcert = null;
        if(request()->hasFile('trainingcert'))
        {
            $path_file_trainingcert = request()->file('trainingcert')->store('applicant_training');
        }

        $path_file_photo = null;
        if(request()->hasFile('photo'))
        {
            $path_file_photo = request()->file('photo')->store('applicant_photo');
        }

        $path_file_servicerecords = null;
        if(request()->hasFile('servicerecord'))
        {
            $path_file_servicerecords = request()->file('servicerecord')->store('applicant_servicerecord');
        }

        $path_file_cs = null;
        if(request()->hasFile('cs'))
        {
            $path_file_cs = request()->file('cs')->store('applicant_cs');
        }

        $path_file_evaluationcert = null;
        if(request()->hasFile('evaluationcert'))
        {
            $path_file_evaluationcert = request()->file('evaluationcert')->store('applicant_evaluationcert');
        }

        

    	$applicant = new App\Applicant;
    	$applicant->lname = request()->lname;
    	$applicant->fname = request()->fname;
    	$applicant->mname = request()->mname;
        $applicant->contactnum = request()->contactnum;
    	$applicant->email = request()->email;
        $applicant->file_cv = $path_file_cv;
        $applicant->file_appletter = $path_file_appletter;
        $applicant->file_trainingcert = $path_file_trainingcert;
        $applicant->file_photo = $path_file_photo;
        $applicant->file_servicerecords = $path_file_servicerecords;
        $applicant->file_evaluationcert = $path_file_evaluationcert;
        $applicant->file_cs = $path_file_cs;

    	$applicant->email_me = request()->emailMe;
    	$applicant->save();
        $applicant_id = $applicant->id;

        $applicant_list = new App\Applicant_position_apply;
        $applicant_list->request_id = request()->request_id;
        $applicant_list->vacant_plantilla_id = $this->getVacantId(request()->itemcode);
        $applicant_list->applicant_id = $applicant->id;
        $applicant_list->save();

    	return redirect('thank-you');
    }

    public function getVacantId($id)
    {
        $q = App\Vacant_plantilla::where('plantilla_item_number',$id)->first();
        return $q['id'];
    }

    public function hrdcreview($id)
    {
        $data = [
                    'hrd' => App\HRD_plan::where('id',$id)->first(),
                    'division' => App\Division::whereNotNull('type')->get(),
                ];

        return view('pis.learningdev.hrd-review')->with('data',$data);
    }


    public function gelud()
    {
        $userid = request()->userid;
        $mh = request()->mh;
        // return request()->mon2;
        $worksched = getDTROption();

        $emp = App\User::where('id',$userid)->first();

        $rows = "";
        
        $mon = date('m',strtotime($mh));
        $mon2 = date('F',mktime(0, 0, 0, $mh, 10));
        $yr = request()->my;
        $date = $mon2 ."-" . request()->my;
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
                          $weeknum = weekOfMonth(date($yr.'-'.$mh.'-'.$i));
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

                            

                            $dtr_date = date("Y-m-d",strtotime($yr.'-'.$mh.'-'.$i));

                            //echo $dtr_date."<br/>";

                            $dayDesc = weekDesc($dtr_date);
                            $dtr = getDTRemp($dtr_date,$emp['id'],$emp['employment_id'],$emp['username']);
                            if(!$dtr)
                            {
                                $dtr = array();
                            }


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


                                    //$tLatesWeeks += $tLates;
                                    if((int)$rowsArr[1] > 0)
                                    {
                                        $tUndertimeWeeks2 .= $rowsArr[5]."|".(int)$rowsArr[1]."<br/>";
                                        $tLatesWeeks += (int)$rowsArr[1];
                                        $tLateCTR++;
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
        // $leaves_total = 0;
        // $l1_total = App\Request_leave::where('user_id',$emp['id'])->whereNull('parent_leave')->whereNotNull('parent_leave_code')->whereNotIn('leave_id',[5,16])->where('leave_action_status','Approved')->whereMonth('leave_date_from',request()->mon2)->whereYear('leave_date_from',request()->yr2)->get();

        // $dtss = "";

        // foreach ($l1_total as $key => $value) {
        //     $dayDesc2 = weekDesc($value->leave_date_from);
        //     if(!checkIfHoliday($value->leave_date_from))
		// 	{
        //         if($dayDesc2 != 'Sat' && $dayDesc2 != 'Sun')
        //         {
                    
        //             $leaves_total++;
        //             $dtss .= $value->leave_date_from." -- ";
        //         }
        //     }
            
        // }

        //SINGLE DATE
        //$l2_total = App\Request_leave::where('user_id',$emp['id'])->where('parent','YES')->where('leave_deduction','<=',1)->whereNotIn('leave_id',[5,16])->where('leave_action_status','Approved')->whereMonth('leave_date_from',request()->mon2)->whereYear('leave_date_from',request()->yr2)->sum('leave_deduction');

        //$l_total = $leaves_total + $l2_total;


       $total_lud = $totalDeficit +  $tLates + $tUndertime;

       //INSERT TO DATABASE
       $lv = App\Employee_update_leave::where('userid',request()->userid)->where('mon',request()->mh)->where('yr',request()->my)
            ->update([
                'late' => $tLates,
                'undertime' => $tUndertime,
                'deficit' => $totalDeficit,
                'total' => $total_lud,
            ]);


       return redirect('update-leave-balance/'.request()->my);
       //return readableTime($total_lud);
}


}
