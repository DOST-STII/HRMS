<?php

namespace App\Http\Controllers\AttendanceMonitoring;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Auth;
use Carbon\Carbon;

class TOController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {   
     	return view('dtr.request-to');   
    }

    public function send()
    {
        //GET DETAIL
        
        if(request()->to_id)
        {
            $req = App\RequestTO::where('id',request()->to_id)->first();
            $user = App\User::where('id',$req['userid'])->first();
        }
        else
        {
            $user = App\User::where('id',request()->userid2)->first();
        }


        $duration = explode('-',request()->leave_duration4);

        $from = Carbon::parse($duration[0]);
        $from_orig = Carbon::parse($duration[0]);
        $to = Carbon::parse($duration[1]);

        // $dt = Carbon::parse(request()->leave_duration3);

        // $this->addTO(date('Y-m-d',strtotime($dt)));

        $diff = 1+($from->diffInDays($to));

        // CHECK IF SINGLE DATE
        if($diff == 1)
        {
            $this->addTO(1,$from,$to,$user,request()->to_id);
        }
        else
        {
            $this->addTO(2,$from,$to,$user,request()->to_id);
        }

        //ADD HISTORY ON LEAVE

        if(request()->active_tab)
        {
            switch (request()->active_tab) {
                case 'TO':
                        return redirect('request-for-approval');
                    break;
                
                default:
                        return redirect('/');
                    break;
            }
        }
        else
        {
            return redirect('/');
        }
        
    }

    private function addTO($type,$from,$to,Object $user,$toid = null)
    {
        //IF EDIT T.O, DELETE NA LANG LUMA THEN INSERT NG BAGO
        //BUT REMAIN NAG STATUS

        //CHECK IF EDIT
        if($toid)
        {
            $req = App\RequestTO::where('id',$toid)->first();
            $to_status = $req['to_status'];
            $to_status_by = getStaffInfo(Auth::user()->id,'fullname');
            $to_status_date = $req['to_status_date'];

            //THEN DELETE ANG LUMA
            App\RequestTO::where('parent_to_code',$req['parent_to_code'])->delete();
        }
        else
        {
            $to_status = "Pending";
            $to_status_by = null;
            $to_status_date = null;
        }

            //GET DURATION
            if($type == 1)
            {
                switch (request()->leave_time) {
                case 'wholeday':
                        $deduc = 1;
                    break;
                
                default:
                        $deduc = 0.5;
                    break;
                }
            
                $code = randomCode(15);
                
                $request = new App\RequestTO;
                $request->userid = $user['id'];
                $request->empcode = $user['username'];
                $request->employee_name = $user['lname'].', '.$user['fname'].' ' .$user['mname'];
                $request->division = $user['division'];
                $request->to_date_from = $from;
                $request->to_date_to = $to;
                $request->to_total_day = $deduc;
                $request->parent = 'YES';
                $request->parent_to = $code;
                $request->parent_to_code = $code;
                $request->to_deduction = $deduc;
                $request->to_deduction_time = request()->leave_time;
                $request->to_vehicle = request()->vehicle;
                $request->to_perdiem = request()->perdiem;
                $request->to_cash_adv = request()->cash_adv;
                $request->to_place = request()->place;
                $request->to_purpose = request()->purpose;

                $request->to_acttitle = request()->acttitle;
                $request->to_fundsrc = request()->fundsrc;
                $request->to_pf_txt = request()->pf_txt;
                $request->to_others_txt = request()->others_txt;
                $request->to_perdiem_type = request()->perdiem_type;

                $request->to_status = $to_status;
                $request->to_status_by = $to_status_by;
                $request->to_status_date = $to_status_date;
                $request->save();

                $tblid = $request->id;

            }
            else
            {
                $code = randomCode(15);
                
                $orig_from = $from;

                $diff = 1+($from->diffInDays($to));

                $total_deduc = 0;

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

                            // if(!$this->checkIfWeekend($dt))
                            // {
                            //     if(!checkIfHoliday($dt))
                            //     {  
                            //         $total_deduc++;
                            //     }
                            // }
                            $total_deduc++;

                            $request = new App\RequestTO;
                            $request->userid = $user['id'];
                            $request->empcode = $user['username'];
                            $request->employee_name = $user['lname'].', '.$user['fname'].' ' .$user['mname'];
                            $request->division = $user['division'];
                            $request->to_perdiem = request()->perdiem;
                            $request->to_acttitle = request()->acttitle;
                            $request->to_fundsrc = request()->fundsrc;
                            $request->to_pf_txt = request()->pf_txt;
                            $request->to_others_txt = request()->others_txt;
                            $request->to_perdiem_type = request()->perdiem_type;
                            $request->to_date_from = $dt;
                            $request->to_total_day = 1;
                            $request->parent_to_code = $code;
                            $request->to_status = $to_status;
                            $request->to_status_by = $to_status_by;
                            $request->to_status_date = $to_status_date;
                            $request->save();    

                    }

                $request = new App\RequestTO;
                $request->userid = $user['id'];
                $request->empcode = $user['username'];
                $request->employee_name = $user['lname'].', '.$user['fname'].' ' .$user['mname'];
                $request->division = $user['division'];
                $request->to_date_from = $orig_from;
                $request->to_date_to = $to;
                $request->to_total_day = $total_deduc;
                $request->parent = 'YES';
                $request->parent_to = $code;
                $request->parent_to_code = $code;
                $request->to_deduction = $total_deduc;
                $request->to_deduction_time = request()->leave_time;
                $request->to_vehicle = request()->vehicle;
                $request->to_perdiem = request()->perdiem;
                $request->to_cash_adv = request()->cash_adv;
                $request->to_place = request()->place;
                $request->to_purpose = request()->purpose;
                $request->to_acttitle = request()->acttitle;
                $request->to_fundsrc = request()->fundsrc;
                $request->to_pf_txt = request()->pf_txt;
                $request->to_others_txt = request()->others_txt;
                $request->to_perdiem_type = request()->perdiem_type;
                $request->to_status = $to_status;
                $request->to_status_by = $to_status_by;
                $request->to_status_date = $to_status_date;
                $request->save();
            }

            // add_to_leave(Auth::user()->id,$tblid,$dt,"Requested");
        
    }

    public function checkIfWeekend($dt)
    {
        $dt = Carbon::parse($dt);

        if($dt->isWeekend())
            return true;
        else
            return false;
    }

    public function pdf()
    {
        //return '<img src="https://elibrary.pcaarrd.dost.gov.ph/slims/assets/images/constLogo/PCAARRD.jpg" style="width:100px">';
        //return '<img src="'.asset('img/PCAARRD.jpeg').'" style="width:100px">';
        //GET TO DETAILS
        $to = App\RequestTO::where('id',request()->req_id)->first();

        //GET EMPLOYEE DETAILS
        // $emp = App\User::where('id',$to[''])

        $plantilla = getPlantillaInfo($to['empcode']);

        //VEHICLE
        $to_vehicle_1 = "&#9744";
        $to_vehicle_2 = "&#9744";
        $to_vehicle_3 = "&#9744";

        if($to['to_vehicle'] == 'Official')
            $to_vehicle_1 = "&#9745";
        elseif($to['to_vehicle'] == 'Personal')
            $to_vehicle_2 = "&#9745";
        else
            $to_vehicle_3 = "&#9745";


        if($to['to_date_from'] == $to['to_date_to'])
        {
            $to_date = date('F d, Y',strtotime($to['to_date_from']));
        }
        else
        {
            $to_date = date('F d, Y',strtotime($to['to_date_from']))." - ".date('F d, Y',strtotime($to['to_date_to']));
        }

        //WHOLEDAY AM OR PM
        
        if($to['to_deduction_time'] == 'wholeday')
        {
            $time = "(WD)";
        }
        elseif($to['to_deduction_time'] == 'AM' || $to['to_deduction_time'] == 'PM')
        {
            $time = "(".$to['to_deduction_time'].")";
        }
        else
        {
            $time = "";
        }

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML('<!DOCTYPE html>
                            <html>
                            <head>
                              <title>HRMIS - T.O</title>
                              <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                              <!-- CSS -->
                              
                            </head>
                            <style type="text/css">
                                @page {
                                  size: 21cm 29.7cm;
                                  margin-top: 10;
                                  margin-left: 15;
                                  margin-right: 15;
                                }
                                body
                                {
                                    font-family: Arial;
                                }
                                th,td
                                {
                                    font-size:13px;
                                    padding: 7px;
                                   
                                }
                            </style>
                            <body>

                            <table width="100%" cellspacing="0" cellpadding="2">
                                <tr>
                                  <td style="border : 1px solid #FFF;width:20%"; align="center">
                                  '.showLogo().'
                                  </td>
                                  <td style="border : 1px solid #FFF;font-size:15px;" align="left">
                                        Republic of the Philippines<br/>
                                        <b>DEPARTMENT OF SCIENCE AND TECHNOLOGY</b>
                                        <br/>
                                        <b>SCIENCE AND TECHNOLOGY INFORMATION INSTITUTE</b>
                                  </td>
                                </tr>
                            </table>

                            <center><h4><b>TRAVEL ORDER REQUEST FORM</b></h4></center>
                           
                            <table class="table table-bordered table-striped" width="100%">
                                <tbody>
                                <p style="font-size: 14px"><i>Before accomplishing this form, please read the instructions found at the back.</i></p>
                                    <tr>
                                        <td align="center" style="border : 0px" width="100%">
                                            <table width="100%" cellspacing="0" border="0">
                                                <tr>
                                                    <td align="left" style="background-color: #e6e3e3; border: 1px solid black;"><b>1. Purpose</b></td>
                                                </tr>
                                                <tr>
                                                    <td align="left" style="border: 1px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;'.$to['to_purpose'].'</td>
                                                </tr>
                                                <tr>
                                                    <td align="left" style="background-color: #e6e3e3; border: 1px solid black;"><b>2. Title of Activity</b></td>
                                                </tr>
                                                <tr>
                                                    <td align="left" style="border: 1px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;'.$to['to_acttitle'].'</td>
                                                </tr>
                                                <tr>
                                                    <td align="left" style="background-color: #e6e3e3; border: 1px solid black;"><b>3. Date/s of Travel</b></td>
                                                </tr>
                                                <tr>
                                                    <td align="left" style="border: 1px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;'.$to_date.' '.$time.'</td>
                                                </tr>
                                                <tr>
                                                    <td align="left" style="background-color: #e6e3e3; border: 1px solid black;"><b>5. Venue / Location</b></td>
                                                </tr>
                                                <tr>
                                                    <td align="left" style="border: 1px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;'.$to['to_place'].'</td>
                                                </tr>
                                                <tr>
                                                    <td align="left" style="background-color: #e6e3e3; border: 1px solid black;"><b>6. Fund Source </b><i>(please specify if not GAA)</i></td>
                                                </tr>
                                                <tr>
                                                    <table width="100%" style="border: 1px solid black;">
                                                        <tr>
                                                        <td align="left" width="30%">  
                                                            <input type="checkbox" id="fund_gaa" name="fund_src" value="GAA" style="margin-left: 1em;">
                                                            <label for="fund_gaa">GAA</label>
                                                        </td>
                                                        <td align="left" width="30%">
                                                            <input type="checkbox" id="fund_pf" name="fund_src" value="Project Fund" checked>
                                                            <label for="fund_pf">Project Fund
                                                            <u>&nbsp;&nbsp;&nbsp;&nbsp;'.$to['to_pf_txt'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
                                                            </label>
                                                        </td>
                                                        <td align="left" width="30%">
                                                            <input type="checkbox" id="fund_others" name="fund_src" value="Others">
                                                            <label for="fund_others"> Other Source    
                                                            <u>&nbsp;&nbsp;&nbsp;&nbsp;'.$to['to_others_txt'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
                                                            </label>
                                                        </td>
                                                        </tr>
                                                    </table>
                                                </tr>
                                                <tr>
                                                    <td align="left" style="background-color: #e6e3e3; border: 0.5px solid black;"><b>7. Travel Expenses to be incurred:</i></td>
                                                </tr>
                                                <tr>
                                                    <table width="100%" style="border: 1px solid black;">
                                                        <tr>
                                                            <td align="left" width="50%; border-right: 1px solid black;">
                                                                <b> Actual </b><br>
                                                                <input type="radio" id="travel_pd_hotel" name="perdiem_type"> Hotel / Lodging <br>
                                                                <input type="radio" id="travel_pd_hotel" name="perdiem_type"> Meals <br>
                                                                <input type="radio" id="travel_pd_hotel" name="perdiem_type"> Incidental Expenses <br>
                                                            </td>
                                                            <td align="left" width="50%">
                                                                <b> Per Diem </b><br>
                                                                <input type="radio" id="travel_pd_hotel" name="perdiem_type"> Hotel / Lodging (50% of DTE) <br>
                                                                <input type="radio" id="travel_pd_hotel" name="perdiem_type"> Meals (20%) <br>
                                                                <input type="radio" id="travel_pd_hotel" name="perdiem_type"> Incidental Expenses (20%) <br>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </tr>
                                                <tr>
                                                    <td align="left" style="background-color: #e6e3e3; border: 0.5px solid black;"><b>8. Transportation Requested: </td>
                                                </tr>
                                                <tr>
                                                    <table width="100%" style="border: 1px solid black;">
                                                       <tr>
                                                            <td align="left" width="50%; border-right: 1px solid black;">
                                                                <input type="radio" id="travel_pd_hotel" name="perdiem_type"> Official Vehicle <br>
                                                                <input type="radio" id="travel_pd_hotel" name="perdiem_type"> Public Conveyance <br>
                                                                <input type="radio" id="travel_pd_hotel" name="perdiem_type"> Taxi / TNVS <br>
                                                            </td>
                                                            <td align="left" width="50%">
                                                                <input type="radio" id="travel_pd_hotel" name="perdiem_type"> Airplane <br>
                                                                <input type="radio" id="travel_pd_hotel" name="perdiem_type"> Bus <br>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </tr>
                                                <tr>
                                                    <td align="left" style="background-color: #e6e3e3; border: 0.5px solid black;"><b>9. Participants / Attendees: </td>
                                                </tr>
                                                <tr style="border: 1px solid black;">
                                                    <table width="100%" class="table table-bordered">
                                                     <tr>
                                                            <td align="center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 12px">Name</td>
                                                            <td align="center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 12px">Position</td>
                                                            <td align="center" style="border-bottom: 1px solid black; font-size: 12px">Office</td>
                                                        </tr>
                                                     <tr>
                                                            <td style="border-bottom: 1px solid black; border-right: 1px solid black;"></td>
                                                            <td style="border-bottom: 1px solid black; border-right: 1px solid black;"></td>
                                                            <td style="border-bottom: 1px solid black;"></td>
                                                        </tr>
                                                        <tr style="border: 1px solid black;">
                                                            <td style="border-bottom: 1px solid black; border-right: 1px solid black;"></td>
                                                            <td style="border-bottom: 1px solid black; border-right: 1px solid black;"></td>
                                                            <td style="border-bottom: 1px solid black;"></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-right: 1px solid black;"></td>
                                                            <td style="border-right: 1px solid black;"></td>
                                                            <td></td>
                                                        </tr>
                                                    </table>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    
                                </tbody>
                            </table>

                            <table width="100%" cellspacing="0" cellpadding="2">
                                <tr>
                                    <td style="border : 1px solid #FFF;" align="left" width="70%">
                                        <h4><b> Requested by: </b> </h4>
                                        <h4 style="margin-top: 2em; text-transform:uppercase;">'.$to['employee_name'].'</h4>
                                    </td>
                                    <td style="border : 1px solid #FFF;" align="left" width="30%">
                                        <h4><b> Noted by: </b> </h4>
                                        <h4 style="margin-top: 2em"></h4>
                                    </td>
                                </tr>
                            </table>
                            
                            <table width="100%" cellspacing="0" cellpadding="2" style="padding-top: 6px">
                                <tr>
                                  <td style="border : 1px solid #FFF;font-size:10px;" align="left" width="73%">
                                        DOST Complex, Gen. Santos Avenue, Bicutan <br/>
                                        1631Taguig City, Philippines <br/>
                                        P.O. Box 3596 Manila <br/>
                                        www.stii.dost.gov.ph
                                  </td>
                                  <td style="border : 1px solid #FFF;font-size:10px;" align="left">
                                  Tel. Nos.: +63 2 837 2071 to 82 <br/>
                                  Fax No. :+63 2 837 2071 to 82 Local 2131
                            </td>
                                </tr>
                            </table>



                            </body>
                            </html>')
        ->setPaper('a4', 'portrait');
        return $pdf->stream();
    }
}
