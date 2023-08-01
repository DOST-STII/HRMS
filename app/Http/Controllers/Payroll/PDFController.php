<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemManager;

class PDFController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function myPayslip()
    {
        //CHECK IF SINGLE MONTH
        //CHECK NA-MIX YUNG YEAR

        //return request()->employee;
        if(isset(request()->employee))
        {

            $year1 = request()->year;
            $year2 = request()->year;

            if($year1 > $year2)
            {
                $year1 = request()->year;
                $year2 = request()->year;
            }

            $date1 = $year1."-".request()->mon1;
            $date2 = $year2."-".request()->mon2;

            if($date1 == $date2)
            {
                $month1 = request()->mon1;
                $diff = 0;
            }
            else
            {
                $month1 = request()->mon1;
                $month2 = request()->mon2;

                $diff = (($year2 - $year1) * 12) + ($month2 - $month1);
            }

            $u = App\User::where('id',request()->employee)->first();
            $username = $u['username'];
            $userid = $u['id'];
            $userdiv = $u['division'];

        }
        else
        {

            
            $year1 = request()->payslip_year;
            $year2 = request()->payslip_year2;

            if($year1 > $year2)
            {
                $year1 = request()->payslip_year2;
                $year2 = request()->payslip_year;
            }

            $date1 = $year1."-".request()->payslip_mon;
            $date2 = $year2."-".request()->payslip_mon2;

            if($date1 == $date2)
            {
                $month1 = request()->payslip_mon;
                $diff = 0;
            }
            else
            {
                $month1 = request()->payslip_mon;
                $month2 = request()->payslip_mon2;

                $diff = (($year2 - $year1) * 12) + ($month2 - $month1);
            }

            $username = Auth::user()->username;
            $userid = Auth::user()->id;
            $userdiv = Auth::user()->division;
        }
        
        //return $year1;

        $rows = "";
        $test = "";
        $break = 1;
        for ($i=0; $i <= $diff; $i++) 
        { 
            
            if($month1 == 13)
            {
                $month1 = 1;
                $year1++;
            }

            //$test .= $month1." ".$year1."<br/>";

            $mon = date('F',mktime(0, 0, 0, $month1, 10));

            // $plantilla = getPlantillaInfo(Auth::user()->username);
            //GET PREV SALARY
            $sal = App\Payroll\PrevInfotbl::where('fldEmpCode',$username)->where('fldMonth',$month1)->where('fldYear',$year1)->first();

            //return $sal; 

            $total_manda_deduc = 0;
            $total_loan_deduc = 0;
            $total_deduc = 0;
            $deduc_td = "";
            $loans_td = "";
        
                            if($sal)
                            {
                                $sl = $sal['M_BASIC'];

                                $deductions = getDeductionsPrev($username,$month1,$year1);
                                $compensation = getCompensationPrev($username,$month1,$year1);

                                $plantilla = getPlantillaInfo($username);

                                $comp_td = "";
                                foreach($compensation AS $lists)
                                {
                                    if($lists->compAmount > 0)
                                        $comp_td .= $lists->compCode.' - '.formatCash($lists->compAmount).'<br/>';
                                }

                                $loans = getPersonalPrevLoans($username,$month1,$year1);
                                $loans_td = "";
        
                                foreach ($loans as $key => $value) {


        
                                        if($value->DED_AMOUNT > 0)
                                        {
                                            $total_deduc += $value->DED_AMOUNT;
                                            $total_loan_deduc += $value->DED_AMOUNT;
                                            $loans_td .= $value->ORG_ACRO.' - '.formatCash($value->DED_AMOUNT).'<br/>';
                                        }
                                        
                                    }

                                    $sic = $sl * 0.09;
                                    $total_deduc += $sic;
                                    $total_manda_deduc += $sic;
                                    $deduc_td .= 'SIC - '.formatCash($sic).'<br/>';
                                    
                                    //IF MONTH IS MAY OR BELOW 2020
                                    //Kasi by June 2022 ang Philhealth na ay 4%
                                    if($month1 <= 5 && $year1 <= 2022)
                                    {
                                        $philhealth = computePhil($sl,1);
                                        if($philhealth >= 900)
                                        {
                                            $philhealth = 900;
                                        }
                                    }
                                    else
                                    {
                                        $philhealth = computePhil($sl);
                                        if($philhealth >= 1600)
                                        {
                                            $philhealth = 1600;
                                        }
                                    }

                                    
                                    
                                    $total_deduc += $philhealth;
                                    $total_manda_deduc += $philhealth;
                                    $deduc_td .= 'PhilHealth - '.formatCash($philhealth).'<br/>';
                            }
                            else
                            {
                                $plantilla = getPlantillaInfo($username);
                                $sl = $plantilla['plantilla_salary'];

                                $deductions = getDeductions($username);

                                $compensation = getCompensation($username);

                                $comp_td = "";
                                foreach($compensation AS $lists)
                                {
                                    if($lists->compAmount > 0)
                                        $comp_td .= $lists->compCode.' - '.formatCash($lists->compAmount).'<br/>';
                                }
        
        
                                $loans = getPersonalLoans($username);
                                $loans_td = "";
        
                                foreach ($loans as $key => $value) {
        
                                        if($value->DED_AMOUNT > 0)
                                        {
                                            $total_deduc += $value->DED_AMOUNT;
                                            $total_loan_deduc += $value->DED_AMOUNT;
                                            $loans_td .= $value->ORG_ACRO.' - '.formatCash($value->DED_AMOUNT).'<br/>';
                                        }
                                        
                                    }

                                    $sic = $sl * 0.09;
                                    $total_deduc += $sic;
                                    $total_manda_deduc += $sic;
                                    $deduc_td .= 'SIC - '.formatCash($sic).'<br/>';
        
                                    $philhealth = computePhil($sl);
                                    if($philhealth >= 1600)
                                    {
                                        $philhealth = 1600;
                                    }

                                    $total_deduc += $philhealth;
                                    $total_manda_deduc += $philhealth;
                                    $deduc_td .= 'PhilHealth - '.formatCash($philhealth).'<br/>';
                            }

                            



                            foreach ($deductions as $key => $value)
                            {

                                if($value->deductID != 2 && $value->deductID != 3)
                                {
                                    if($value->deductCode != "")
                                    {
                                        if($value->deductAmount > 0)
                                        {
                                            $total_deduc += $value->deductAmount;
                                            $total_manda_deduc += $value->deductAmount;
                                            $deduc_td .= $value->deductCode.' - '.formatCash($value->deductAmount).'<br/>';
                                        }
                                    }
                                }

                            }

                                                

                            //RATA
                            $rata = 0;
                            foreach(getCompensation_rata($username,true) AS $values)
                            {
                                $rata += $values->compAmount;
                            }

                            //TOTAL COMPESATION
                            $total_comp = 0;
                            foreach ($compensation as $key => $value) {
                                if($value->compID == 1)
                                    $total_comp += $value->compAmount;
                            }

                            //NET
                            $net = ($sl + $total_comp) - $total_deduc;
                            
                            $sal_week1 = "";
                            for ($x=1; $x <= 2 ; $x++) {
                                    $salary = getWeekSalary($username,$net,$x);
                                    $sal_week1 .= "&#160 &#160 ".$salary;
                                }
                            $sal_week2 = "";
                                for ($y=3; $y <= 4 ; $y++) {
                                        $salary = getWeekSalary($username,$net,$y);
                                        $sal_week2 .= $salary." &#160 &#160 ";
                                        }
                                        
            $staff = getStaffInfo($userid,"fullname");
            $division = getDivision($userdiv);


            $rows .= '<div class="payslip">
                            <table width="100%" class="textLayer" cellspacing="0" cellpadding="2">
                                <tr>
                                <td style="border : 1px solid #FFF;width:15%" align="right">
                                    <img src="'.url('img/stii.png').'" style="width:50px">
                                </td>
                                <td style="border : 1px solid #FFF;font-size:12px;" align="center">
                                        Republic of the Philippines<br/>
                                        SCIENCE AND TECHNOLOGY INFORMATION INSTITUTE
                                        <br/>
                                        STII Building, DOST Complex, Bicutan, Taguig City
                                </td>
                                <td style="border : 1px solid #FFF;font-size:12px;width:15%" >

                                </td>
                                </tr>
                            </table>
                            <center><h3><b>STII PAYSLIP FOR THE MONTH OF : '.$mon.' '.$year1.'</b></h3></center
                            
                            <table width="75%" style="position:relative;left:15%">
                                
                            </table>

                            <table width="100%" class="textLayer">
                                <tr>
                                    <td colspan="3">
                                        <b>EMPLOYEE : </b>'.$staff.'<br/>
                                        <b>DIVISION/POSITION : </b> '.$division.' - '.strtoupper($plantilla['position_desc']).'
                                        <br/>
                                        <br/>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:33%"><b>GROSS PAY</b></td>
                                    <td style="width:33%"><b>DEDUCTIONS</b></td>
                                    <td style="width:33%"><b>OTHER DEDUCTIONS</b></td>
                                </tr>
                                <tbody>
                                    <tr>
                                        <td valign="top">
                                            SALARY - '.formatCash($sl).'<br>
                                            '.$comp_td.'
                                        </td>
                                        <td valign="top">
                                            '.$deduc_td.'
                                        </td>
                                        <td valign="top">
                                            '.$loans_td.'
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>TOTAL</b> - '.formatCash($sl + $total_comp).' </td>
                                        <td><b>TOTAL</b> - '.formatCash($total_manda_deduc).'</td>
                                        <td><b>TOTAL</b> - '.formatCash($total_loan_deduc).'</td>
                                    </tr>
                                    <tr>
                                        <td><b>TOTAL DEDUCTION</b> - '.formatCash($total_deduc).' </td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><b>NET SALARY</b> - '.formatCash(($sl + $total_comp) - $total_deduc).'</td>
                                        <td><b>PER WEEK - </b>'.$sal_week1.'</td>
                                        <td>'.$sal_week2.'</td>
                                    </tr>
                                    <tr valign="bottom">
                                        <td><spanborder="0" style="font-size:9px">Date Printed : '.Carbon::now().'</span></td>
                                        <td></td>
                                        <td>
                                            <br/>
                                            <br/>
                                            Certified by : <u>&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160    &#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160</u>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>';
            
            if($break == 2)
            {
                $rows .= '<div class="page-break"></div>';
                $break = 1;
            }

            $month1++;
            $break++;
        }

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML('<!DOCTYPE html>
                            <html>
                            <head>
                              <title>HRMIS - MY PAYSLIP</title>
                              <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                            </head>
                            <style type="text/css">
                                body
                                {
                                    font-family:Helvetica;
                                }
                                th,td
                                {
                                    border:0px solid #555;
                                    font-size:11px;
                                }

                                .payslip
                                {
                                    font-size : 10px;
                                    border:1px solid #999;
                                    padding:5px;
                                    width: 100%;
                                    margin-bottom: 2%;
                                }

                                .page-break {
                                    page-break-after: always;
                                   }
                            </style>
                            <body>
                                '.$rows.'
                            </body>
                            </html>')
        ->setPaper('a4', 'portrait');
        return $pdf->stream();
    }


    // public function MCreport()
    // {
    //     $date = request()->mon .' '.request()->yr;

    //     $mon2 = date('F',mktime(0, 0, 0, request()->mon, 10));

    //     //IF INDIVIDUAL
    //     if(request()->mcrequest == 'individual')
    //     {
    //         //ROW
    //         $mcs = App\View_employee_mc::where('user_id',Auth::user()->id)->first();

    //         //GET PROCESS CODE
    //         if(request()->mon == 1)
    //         {
    //             $codemon = 12;
    //             $codeyear = (request()->yr) - 1;
    //         }
    //         else
    //         {
    //             $codemon = (request()->mon) - 1;
    //             $codeyear = request()->yr;
    //         }

    //         $code = App\DTRProcessed::where('userid',Auth::user()->id)->where('dtr_mon',$codemon)->where('dtr_year',$codeyear)->first();


            

    //         //GET HP RATE
    //         $hp_rate = App\Payroll\MC::where('userid',Auth::user()->id)->where('payroll_mon',request()->mon)->where('payroll_yr',request()->yr)->first();

    //         //GET SALARY
    //         $plantilla = getPlantillaInfo(Auth::user()->username);

    //         $hp = $plantilla['plantilla_salary'] * $hp_rate['hprate'];
            

    //         $lp = getLP(Auth::user()->id);

    //         $itw = getITW(Auth::user()->id);

    //         $total_deduc = getTotalMCDeduc('total',Auth::user()->id,request()->mon,request()->yr) + $itw;

    //         //TOTAL S.A DEDUCTION
    //         $rows3 = "";
    //         $m = 0;

    //         $mcd = App\MCday::where('process_code',$code['process_code'])->get();

    //         foreach($mcd as $key => $value) {
    //                 $dt = date('M d, y',strtotime($value->req_date_from));
    //                 if($value->req_date_from != $value->req_date_to)
    //                 {
    //                     $dt = date('M d, y',strtotime($value->req_date_from))." - ".date('M d, y',strtotime($value->req_date_to));
    //                 }

    //                 $rows3 .= "<tr><td>".$value->req_type."</td><td>".$dt."</td><td>".$value->req_deduc."</td></tr>";
    //                 $m += $value->req_deduc;
    //         } 
            
    //         $total_sa = $mcs['sa_amt'] - ($m * 150);

    //         $total = ($lp + $total_sa + $mcs['la_amt'] + $mcs['hp_amt'] + $hp) - $total_deduc;

    //         $rows = "<tr>
    //                                     <td>".$mcs['fullname']."</td>
    //                                     <td>".formatCash($mcs['hp_salary'])."</td>
    //                                     <td>".formatCash($lp)."</td>
    //                                     <td>".formatCash($total_sa)."</td>
    //                                     <td>".formatCash($mcs['la_amt'])."</td>
    //                                     <td>".formatCash($hp)."</td>
    //                                     <td>".formatCash($itw)."</td>
    //                                     <td>".formatCash($total_deduc)."</td>
    //                                     <td>".formatCash($total)."</td>
    //                                 </tr>";

    //         //TOTAL DEDCUTION
    //         $rows2 = "";
    //         foreach (getTotalMCDeduc('list',Auth::user()->id,request()->mon,request()->yr) as $key => $value) {
    //             if($value > 0)
    //             {
    //                 $rows2 .= "<tr><td>".$key."</td><td>".formatCash($value)."</td><td></td></tr>";
    //             }
                
    //         }    
    //         $rows2 .= "<tr><td>ITW</td><td>".formatCash($itw)."</td><td></td></tr>";  

             

    //     }
    //     else
    //     {

    //     }

    //     $pdf = App::make('dompdf.wrapper');
    //     $pdf->loadHTML('<!DOCTYPE html>
    //                         <html>
    //                         <head>
    //                           <title>HRMIS - MC</title>
    //                           <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    //                         </head>
    //                         <style type="text/css">
    //                             body
    //                             {
    //                                 font-family:Helvetica;
    //                             }
    //                             th,td
    //                             {
    //                                 border:0px solid #555;
    //                                 font-size:11px;
    //                             }

    //                             .payslip
    //                             {
    //                                 font-size : 10px;
    //                                 border:1px solid #999;
    //                                 padding:5px;
    //                                 width: 100%;
    //                             }
    //                         </style>
    //                         <body>
    //                         <table width="100%" cellspacing="0" cellpadding="2">
    //                             <tr>
    //                               <td style="border : 1px solid #FFF;width:15%" align="right">
    //                                 <img src="'.url('img/DOST.png').'" style="width:100px">
    //                               </td>
    //                               <td style="border : 1px solid #FFF;font-size:12px;" align="center">
    //                                     Republic of the Philippines<br/>
    //                                     PHILIPPINE COUNCIL FOR AGRICULTURE, AQUATIC AND NATURAL RESOURCES
    //                                     RESEARCH AND DEVELOPMENT<br/>
    //                                     Los Baños, Laguna
    //                               </td>
    //                               <td style="border : 1px solid #FFF;font-size:12px;width:15%" >

    //                               </td>
    //                             </tr>
    //                         </table>

    //                         <center><h4>For the month of '.$mon2.' '.request()->yr.'</h4></center>
    //                         <table width="100%" cellspacing="0" cellpadding="2">
    //                             <tr>
    //                               <td style="border: 1px solid #FFF;width:15%;border-bottom:1px solid #000">
    //                                 <b>Employee</b>
    //                               </td>
    //                               <td style="border: 1px solid #FFF;width:10%;border-bottom:1px solid #000">
    //                                 <b>Salary</b>
    //                               </td>
    //                               <td style="border: 1px solid #FFF;width:10%;border-bottom:1px solid #000">
    //                                 <b>LP</b>
    //                               </td>
    //                                <td style="border: 1px solid #FFF;width:10%;border-bottom:1px solid #000">
    //                                 <b>SA</b>
    //                               </td>
    //                                <td style="border: 1px solid #FFF;width:10%;border-bottom:1px solid #000">
    //                                 <b>LA</b>
    //                               </td>
    //                                <td style="border: 1px solid #FFF;width:10%;border-bottom:1px solid #000">
    //                                 <b>HP</b>
    //                               </td>
    //                               <td style="border: 1px solid #FFF;width:10%;border-bottom:1px solid #000">
    //                                 <b>ITW</b>
    //                               </td>
    //                                <td style="border: 1px solid #FFF;width:10%;border-bottom:1px solid #000">
    //                                 <b>Total Deductions</b>
    //                               </td>
    //                               <td style="border: 1px solid #FFF;width:10%;border-bottom:1px solid #000">
    //                                 <b>NetMC</b>
    //                               </td>

    //                             </tr>
    //                             '.$rows.'
    //                         </table>
    //                         <br>
    //                         <br><br>
    //                         <table width="35%" cellspacing="0" cellpadding="5" style="border:1px solid #777;position:absolute;left:30%">
    //                             <tr>
    //                                 <td colspan="3" align="left"><b>Total Deductions</b></td>
    //                             </tr>
    //                              <tr>
    //                                 <td>Description</td><td>Amount</td><td></td>
    //                             </tr>
    //                             '.$rows2.'
    //                             <tr>
    //                                 <td colspan="3" align="left">Subsistence Allowance (SA) : <b>'.$this->formatCash($mcs['sa_amt']).'</b> <i>(less # of T.O(will claim)/Leave at 150 per day)</i></td>
    //                             </tr>
    //                              <tr>
    //                                 <td>Description</td><td>Dates</td><td>Days</td>
    //                             </tr>
    //                             '.$rows3.'
    //                         </table>
    //                         </body>
    //                         </html>')
    //     ->setPaper('legal', 'landscape');
    //     return $pdf->stream();
    // }


    public function MCreport()
    {
        $date = request()->mon .' '.request()->yr;

        $mon2 = date('F',mktime(0, 0, 0, request()->mon, 10));

        $name = getStaffInfo(Auth::user()->id,'fullname');

        $division = getDivision(Auth::user()->division);

        //MC
        $mc = App\Payroll\MC::where('userid',Auth::user()->id)->where('payroll_mon',request()->mon)->where('payroll_yr',request()->yr)->first();

        $hp = $mc['salary'] * $mc['hprate'];

        //COMPENSATION
        $comp = $mc->lp + $mc->sa + $mc->la + $hp;

        //DEDUCTION
        $deduc = $mc->hmo + $mc->gsis + $mc->pmpc + $mc->cdc + $mc->gfal + $mc->landbank + $mc->itw;

        //NET MC
        $net = $comp - $deduc;

        $custompaper = array(0,0,500,300);
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML('<!DOCTYPE html>
                            <html>
                            <head>
                              <title>HRMIS - MC</title>
                              <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                            </head>
                            <style type="text/css">
                                html
                                {
                                    padding:1%;
                                }
                                body
                                {
                                    font-family:Helvetica;
                                }
                                th,td
                                {
                                    font-size:11px;
                                }
                            </style>
                            <body>
                                <center><h5>Magna Carta(MC) for the month of '.$mon2.' '.request()->yr.'</h5><center>
                                <table width="100%" cellspacing="0" cellpadding="3" border="1">
                                    <tr valign="top">
                                        <td style="width:50%" colspan="2"><b>Name : </b>'.$name.'</td>
                                        <td colspan="2"><b>Division : </b>'.$division.'</td>
                                    </tr>
                                    <tr valign="top">
                                        <td colspan="2" style="width:50%;border-right:1px solid #FFF"></td>
                                        <td colspan="2" style="border-left:1px solid #FFF"><b>Deduction</b></td>
                                    </tr>
                                    <tr valign="top">
                                        <td>
                                            <b>Basic Salary : </b> <br/>
                                            <b>LP : </b><br/>
                                            <b>SA : </b><br/>
                                            <b>LA : </b><br/>
                                            <b>HP : </b><br/>
                                        </td>
                                        <td align="right">
                                            '.formatCash($mc->salary).'<br/>
                                            '.formatCash($mc->lp).'<br/>
                                            '.formatCash($mc->sa).'<br/>
                                            '.formatCash($mc->la).'<br/>
                                            '.formatCash($hp).'<br/>
                                        </td>
                                        <td>
                                            <b>ITW : </b><br/>
                                            <b>HMO : </b><br/>
                                            <b>PMPC : </b><br/>
                                            <b>CDC : </b><br/>
                                            <b>GSIS : </b><br/>
                                            <b>GFAL : </b><br/>
                                            <b>LANDBANK : </b><br/>
                                        </td>
                                        <td align="right">
                                            '.formatCash($mc->itw).'<br/>
                                            '.formatCash($mc->hmo).'<br/>
                                            '.formatCash($mc->pmpc).'<br/>
                                            '.formatCash($mc->cdc).'<br/>
                                            '.formatCash($mc->gsis).'<br/>
                                            '.formatCash($mc->gfal).'<br/>
                                            '.formatCash($mc->landbank).'<br/>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <td><b>TOTAL : </b></td><td align="right">'.formatCash($comp).'</td>
                                        <td></td><td align="right">'.formatCash($deduc).'</td>
                                    </tr>
                                    <tr valign="top">
                                        <td"><b>NET MC : </b></td><td colspan="3" align="right"><b>'.formatCash($net).'</b></td>
                                    </tr>
                                </table>
                                <br/>
                                <table width="100%" cellspacing="0" cellpadding="5">
                                <tr>
                                    <td>
                                        Certified by : <u>&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160    &#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160&#160</u>
                                    </td>
                                </tr>
                            </body>
                            </html>')
        ->setPaper($custompaper);
        return $pdf->stream();
    }


    public function previewpayroll()
    {
        $header = '<tr><td style="border : 1px solid #FFF;width:15%" align="right">
                                        <img src="'.asset('img/DOST.png').'" style="width:50px">
                                      </td>
                                      <td style="border : 1px solid #FFF;font-size:11px;" align="center">
                                            Republic of the Philippines<br/>
                                            PHILIPPINE COUNCIL FOR AGRICULTURE, AQUATIC AND NATURAL RESOURCES
                                            RESEARCH AND DEVELOPMENT<br/>
                                            Los Baños, Laguna
                                      </td>
                                      <td style="border : 1px solid #FFF;font-size:12px;width:15%" >
                                      </td>
                                    </tr>';
        // $header = '';
                                
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 180);

        $date = request()->payrollmon .' '.request()->payrollyear;

        $mon2 = date('F',mktime(0, 0, 0, request()->payrollmon, 10));

        //GET ALL DIVISION
        $row = "";
        $division = getDivisionList();

        $grandtotal_basic = 0;
        $grandtotal_pera_com = 0;
        $grandtotal_rep = 0;
        $grandtotal_trans = 0;
        $grandtotal_lwop = 0;
        $grandtotal_pera_ded = 0;
        $grandtotal_pera_adj = 0;
        $grandtotal_comp_adj = 0;
        $grandtotal_prevLWOP = 0;
        $grandtotal_gross = 0;
        $grandtotal_deduc = 0;
        $grandtotal_net = 0;

        $grandtotal_week_1 = 0;
        $grandtotal_week_2 = 0;
        $grandtotal_week_3 = 0;
        $grandtotal_week_4 = 0;

        
        
        foreach ($division as $key => $value) 
        {

            //GET EMPLOYEE
            //$emp = getStaffDivision2($value->division_id);
            $emp = App\User::whereIn('employment_id',[1,11,13,14,15])->where('division',$value->division_id)->where('payroll',1)->orderBy('lname')->orderBy('fname')->get();
            

            $staff = "";
            $staff2 = "";

            $total_basic = 0;
            $total_pera_com = 0;
            $total_rep = 0;
            $total_trans = 0;
            $total_lwop = 0;
            $total_pera_ded = 0;
            $total_pera_adj = 0;
            $total_comp_adj = 0;
            $total_prevLWOP = 0;
            $total_gross = 0;
            $total_deduc = 0;
            $total_net = 0;

            $total_deduc_itw = 0;
            $total_deduc_sic = 0;
            $total_deduc_ph = 0;
            $total_deduc_hdmf = 0;
            $total_deduc_gspol = 0;
            $total_deduc_gscon = 0;
            $total_deduc_gseml = 0;
            $total_deduc_gseduc = 0;
            $total_deduc_gsopt = 0;
            $total_deduc_gscp = 0;
            $total_deduc_gsmp = 0;
            $total_deduc_gsgfal = 0;
            $total_deduc_hmdfmp = 0;
            $total_deduc_hmdfhouse = 0;
            $total_deduc_cdcfd = 0;
            $total_deduc_cdcsd = 0;
            $total_deduc_cdcloan = 0;
            $total_deduc_pmpchmo = 0;
            $total_deduc_pmpcfd = 0;
            $total_deduc_pmpcsd = 0;
            $total_deduc_pmpcloan = 0;
            $total_deduc_ldp = 0;
            $total_deduc_others = 0;
            $total_deduc_total = 0;

            $total_week_1 = 0;
            $total_week_2 = 0;
            $total_week_3 = 0;
            $total_week_4 = 0;

            $txt = "";
            

            foreach ($emp as $ky => $vl) {

                //GET INFO
                $info = $this->getInfo(request()->payrollmon,request()->payrollyear,$vl->username);

                // $info = App\Payroll\PrevInfo::where('fldMonth',request()->payrollmon)->where('fldYear',request()->payrollyear)->where('fldEmpCode',$vl->username)->first();
                if($info)
                {
                    $plantilla = getPlantillaInfo($vl->username);
                    foreach ($info as $k => $v) {

                    $d_total = 0;
                    $d_itw = 0;
                    $d_sic = 0;
                    $d_ph = 0;
                    $d_hmdf = 0;
                    

                    //LOANS
                    $d_gspol = 0;
                    $d_gscon = 0;
                    $d_gseml = 0;
                    $d_gseduc = 0;
                    $d_gsopt = 0;
                    $d_gscp = 0;
                    $d_gsmp = 0;
                    $d_gsgfal = 0;
                    $d_hmdfmp = 0;
                    $d_hmdfhouse = 0;
                    $d_cdcfd = 0;
                    $d_cdcsd = 0;
                    $d_cdcloan = 0;
                    $d_pmpchmo = 0;
                    $d_pmpcfd = 0;
                    $d_pmpcsd = 0;
                    $d_pmpcloan = 0;
                    $d_ldp = 0;
                    $d_others = 0;

                    $gross = $v->M_BASIC + $v->M_PERA;
                    
                    $total_deduc = 0;
                    
                    //$deductions = getDeductions($vl->username);
                    //foreach ($deductions as $k1 => $v1) {
                    //      $total_deduc += $v1->deductAmount;
                    //    }

                    $deductions = getDeductionsPrev($vl->username,request()->payrollmon,request()->payrollyear);

                    foreach ($deductions as $kd => $vd) 
                        {
                                switch ($vd->deductID) {
                                    case 1:
                                            $d_itw = $vd->deductAmount;
                                            $total_deduc_itw += $vd->deductAmount;

                                            $total_deduc_total += $vd->deductAmount;
                                            $d_total += $vd->deductAmount;

                                        break;
                                    case 2:
                                            //$d_sic = $vd->deductAmount;
                                            // $d_sic = $v->M_BASIC * 0.09;
                                            // $total_deduc_sic += $d_sic;

                                            // $total_deduc_total += $d_sic;
                                            // $d_total += $d_sic;
                                        break;
                                    case 3:
                                            //$d_ph = $vd->deductAmount;
                                            // $d_ph = $plantilla['plantilla_salary'] * 0.015;

                                            // if($d_ph >= 900)
                                            // {
                                            //     $d_ph = 900;
                                            // }

                                            // $total_deduc_ph += $d_ph;
                                            // $total_deduc_total += $d_ph;
                                            // $d_total += $d_ph;

                                        break;
                                    case 4:
                                            $d_hmdf = $vd->deductAmount;
                                            $total_deduc_hdmf += $vd->deductAmount;

                                            $total_deduc_total += $vd->deductAmount;
                                            $d_total += $vd->deductAmount;
                                        break;
                                }
                        }

                        $d_sic = $v->M_BASIC * 0.09;
                        $total_deduc_sic += $d_sic;

                        $total_deduc_total += $d_sic;
                        $d_total += $d_sic;


                        $d_ph = computePhil($v->M_BASIC);

                        if($d_ph >= 1600)
                            {
                                $d_ph = 1600;
                            }

                        $total_deduc_ph += $d_ph;
                        $total_deduc_total += $d_ph;
                        $d_total += $d_ph;

                    $loans = getPersonalPrevLoans($vl->username,request()->payrollmon,request()->payrollyear);
                    foreach ($loans as $kls => $vls) 
                        {
                            $total_deduc_total += $vls->DED_AMOUNT;
                            $d_total += $vls->DED_AMOUNT;

                            switch ($vls->SERV_CODE) {
                                case "305":
                                        $d_gspol = $vls->DED_AMOUNT;
                                        $total_deduc_gspol += $vls->DED_AMOUNT;
                                    break;
                                case "319":
                                        $d_gscon = $vls->DED_AMOUNT;
                                        $total_deduc_gscon += $vls->DED_AMOUNT;
                                    break;
                                case "316":
                                        $d_gseml = $vls->DED_AMOUNT;
                                        $total_deduc_gseml += $vls->DED_AMOUNT;
                                    break;
                                case "315":
                                        $d_gseduc = $vls->DED_AMOUNT;
                                        $total_deduc_gseduc += $vls->DED_AMOUNT;
                                    break;
                                case "305A":
                                        $d_gsopt= $vls->DED_AMOUNT;
                                        $total_deduc_gsopt += $vls->DED_AMOUNT;
                                    break;
                                case "319C":
                                        $d_gscp= $vls->DED_AMOUNT;
                                        $total_deduc_gscp += $vls->DED_AMOUNT;
                                    break;
                                case "319B":
                                        $d_gsmp= $vls->DED_AMOUNT;
                                        $total_deduc_gsmp += $vls->DED_AMOUNT;
                                    break;
                                case "319A":
                                        $d_gsgfal= $vls->DED_AMOUNT;
                                        $total_deduc_gsgfal += $vls->DED_AMOUNT;
                                    break;
                                case "302A":
                                        $d_hmdfmp= $vls->DED_AMOUNT;
                                        $total_deduc_hmdfmp += $vls->DED_AMOUNT;
                                    break;
                                case "302B":
                                        $d_hmdfhouse= $vls->DED_AMOUNT;
                                        $total_deduc_hmdfhouse += $vls->DED_AMOUNT;
                                    break;
                                case "920":
                                        $d_cdcfd= $vls->DED_AMOUNT;
                                        $total_deduc_cdcfd += $vls->DED_AMOUNT;
                                    break;
                                case "922":
                                        $d_cdcsd= $vls->DED_AMOUNT;
                                        $total_deduc_cdcsd += $vls->DED_AMOUNT;
                                    break;
                                case "921":
                                case "921A":
                                case "923":
                                        $d_cdcloan= $vls->DED_AMOUNT;
                                        $total_deduc_cdcloan += $vls->DED_AMOUNT;
                                    break;
                                case "933":
                                        $d_pmpchmo= $vls->DED_AMOUNT;
                                        $total_deduc_pmpchmo += $vls->DED_AMOUNT;
                                    break;
                                case "930":
                                        $d_pmpcfd = $vls->DED_AMOUNT;
                                        $total_deduc_pmpcfd += $vls->DED_AMOUNT;
                                    break;
                                case "932":
                                        $d_pmpcsd= $vls->DED_AMOUNT;
                                        $total_deduc_pmpcsd += $vls->DED_AMOUNT;
                                    break;
                                case "931":
                                        $d_pmpcloan= $vls->DED_AMOUNT;
                                        $total_deduc_pmpcloan += $vls->DED_AMOUNT;
                                    break;
                                case "321":
                                        $d_ldp = $vls->DED_AMOUNT;
                                        $total_deduc_ldp += $vls->DED_AMOUNT;
                                    break;
                                default:
                                        $d_others = $vls->DED_AMOUNT;
                                        $total_deduc_others += $vls->DED_AMOUNT;
                                break;
                            }
                        }

                    $plantilla = getPlantillaInfo($vl->username);

                       
                    $comp = getCompensationPrev($vl->username,request()->payrollmon,request()->payrollyear);

                    //RATA
                    $ra = 0;
                    $ta = 0;
                    $pera = 0;

                    foreach ($comp as $c => $comps) {
                        switch ($comps->compID) {
                            case 1:
                                    $pera = $comps->compAmount;
                                break;
                            case 3:
                                    $ra = $comps->compAmount;
                                break;
                            case 4:
                                    $ta = $comps->compAmount;
                                break;
                        }
                    }

                    $lwopprevyear = request()->payrollyear - 1;
                    $prev_lwop = App\Payroll\LWOP::where('empcode',$vl->username)->whereYear('leave_date',$lwopprevyear)->whereNull('process_at')->sum('amt');

                    $lwop = 0;

                    $total_basic += $v->M_BASIC;
                    $total_pera_com += $v->M_PERA;
                    $total_rep += $v->M_REPN;
                    $total_trans += $v->M_TRANS;
                    $total_lwop += $lwop;
                    $total_pera_ded += $v->PERA_DED;
                    $total_pera_adj += $v->PERA_ADJ;
                    $total_comp_adj += $v->COMP_ADJ;
                    $total_prevLWOP += $prev_lwop;
                    $total_gross += $gross;
                    $total_deduc += $total_deduc;
                    //$total_net += $v->netSalary;
                    $total_net += $gross - $d_total;
                    $netsal = $gross - ($d_total + $prev_lwop);


                    $grandtotal_basic += $v->M_BASIC;
                    $grandtotal_pera_com += $v->M_PERA;
                    $grandtotal_rep += $v->M_REPN;
                    $grandtotal_trans += $v->M_TRANS;
                    $grandtotal_lwop += $v->LWOP;
                    $grandtotal_pera_ded += $v->PERA_DED;
                    $grandtotal_pera_adj += $v->PERA_ADJ;
                    $grandtotal_comp_adj += $v->COMP_ADJ;
                    $grandtotal_prevLWOP += $prev_lwop;
                    $grandtotal_gross += $gross;
                    $total_deduc_total += $total_deduc;
                    $grandtotal_net += $netsal;

                    // $total_week_1 += $v->AMOUNT1;
                    // $grandtotal_week_1 += $v->AMOUNT1;

                    // $total_week_2 += $v->AMOUNT2;
                    // $grandtotal_week_2 += $v->AMOUNT2;

                    // $total_week_3 += $v->AMOUNT3;
                    // $grandtotal_week_3 += $v->AMOUNT3;

                    // $total_week_4 += $v->AMOUNT4;
                    // $grandtotal_week_4 += $v->AMOUNT4;

                    //SALARY WEEK
                    //$netsal = $gross - ($d_total + $prev_lwop);
                    $wk1 = 0;
                    $wk2 = 0;
                    $wk3 = 0;
                    $wk4 = 0;
                    for ($i=1; $i <= 4 ; $i++) {
                        $salary = getWeekSalary($vl->username,$netsal,$i,1);
                            switch ($i) {
                                case 1:
                                    $wk1 = $salary;
                                    $total_week_1 += $salary;
                                    $grandtotal_week_1 += $salary;
                                break;

                                case 2:
                                    $wk2 = $salary;
                                    $total_week_2 += $salary;
                                    $grandtotal_week_2 += $salary;
                                break;

                                case 3:
                                    $wk3 = $salary;
                                    $total_week_3 += $salary;
                                    $grandtotal_week_3 += $salary;
                                break;

                                case 4:
                                    $wk4 = $salary;
                                    $total_week_4 += $salary;
                                    $grandtotal_week_4 += $salary;
                                break;
                            }
                    }


                    $staff .= '
                        <tr>
                        <td class="bd2">'.strtoupper($vl->lname.', '.$vl->fname.' '.substr($vl->mname,0,1).".").'</td>
                        <td class="bd2">'.$plantilla['position_abbr'].'</td>
                        <td class="bd2" align="right">'.$this->formatCash($v->M_BASIC).'</td>
                        <td class="bd2" align="right">'.$this->formatCash($v->M_PERA).'</td>
                        <td class="bd2" align="right">'.$this->formatCash($v->M_REPN).'</td>
                        <td class="bd2" align="right">'.$this->formatCash($v->M_TRANS).'</td>
                        <td class="bd2" align="right">'.$this->formatCash($lwop).'</td>
                        <td class="bd2" align="right">'.$this->formatCash($v->PERA_DED).'</td>
                        <td class="bd2" align="right">'.$this->formatCash($v->PERA_ADJ).'</td>
                        <td class="bd2" align="right">'.$this->formatCash($v->COMP_ADJ).'</td>
                        <td class="bd2" align="right">'.$this->formatCash($v->prevLWOP).'</td>
                        <td class="bd2" align="right">'.$this->formatCash($gross).'</td>
                        <td class="bd2" align="right">'.$this->formatCash($d_total).'</td>
                        <td class="bd2" align="right">'.$this->formatCash($netsal).'</td>
                        <td class="bd2" align="right">'.$this->formatCash($wk1).'</td>
                        <td class="bd2" align="right">'.$this->formatCash($wk2).'</td>
                        <td class="bd2" align="right">'.$this->formatCash($wk3).'</td>
                        <td class="bd2" align="right">'.$this->formatCash($wk4).'</td>
                    </tr>';
                    }
                }
                else
                {
                    $plantilla = getPlantillaInfo($vl->username);

                    $total_deduc_div = 0;
                    
                    // //$deductions = getDeductions($vl->username);
                    // //foreach ($deductions as $k1 => $v1) {
                    // //      $total_deduc += $v1->deductAmount;
                    // //    }

                    // $deductions = getDeductions($vl->username);
                    // foreach ($deductions as $k1 => $v1) {
                    //       $total_deduc += $v1->deductAmount;
                    //       $total_deduc_div += $v1->deductAmount;
                    //     }

                    // $loans = getPersonalLoans($vl->username);
                    // foreach ($loans as $k2 => $v2) {
                    //             $total_deduc += $v2->DED_AMOUNT;
                    //             $total_deduc_div += $v2->deductAmount;
                    //         }

                    //DEDUCTIONS
                    $d_total = 0;

                    $d_itw = 0;
                    $d_sic = 0;
                    $d_ph = 0;
                    $d_hmdf = 0;
                    
                    //MANDA
                    $deductions = getDeductions($vl->username);
                    
                    foreach ($deductions as $kd => $vd) 
                        {
                                switch ($vd->deductID) {
                                    case 1:
                                            $d_itw = $vd->deductAmount;
                                            $total_deduc_itw += $vd->deductAmount;

                                            $total_deduc_total += $vd->deductAmount;
                                            $d_total += $vd->deductAmount;

                                        break;
                                    case 2:
                                            //$d_sic = $vd->deductAmount;
                                            // $d_sic = $plantilla['plantilla_salary'] * 0.09;
                                            // $total_deduc_sic += $d_sic;

                                            // $total_deduc_total += $d_sic;
                                            // $d_total += $d_sic;
                                        break;
                                    case 3:
                                            //$d_ph = $vd->deductAmount;
                                            // $d_ph = $plantilla['plantilla_salary'] * 0.015;

                                            // if($d_ph >= 900)
                                            // {
                                            //     $d_ph = 900;
                                            // }

                                            
                                            
                                        break;
                                    case 4:
                                            $d_hmdf = $vd->deductAmount;
                                            $total_deduc_hdmf += $vd->deductAmount;

                                            $total_deduc_total += $vd->deductAmount;
                                            $d_total += $vd->deductAmount;
                                        break;
                                }
                        }

                        $d_sic = $plantilla['plantilla_salary'] * 0.09;
                                            $total_deduc_sic += $d_sic;

                                            $total_deduc_total += $d_sic;
                                            $d_total += $d_sic;

                        $d_ph = computePhil($plantilla['plantilla_salary']);

                                            if($d_ph >= 1600)
                                            {
                                                $d_ph = 1600;
                                            }

                                            $total_deduc_ph += $d_ph;
                                            $total_deduc_total += $d_ph;
                                            $d_total += $d_ph;

                        //LOANS
                        $d_gspol = 0;
                        $d_gscon = 0;
                        $d_gseml = 0;
                        $d_gseduc = 0;
                        $d_gsopt = 0;
                        $d_gscp = 0;
                        $d_gsmp = 0;
                        $d_gsgfal = 0;
                        $d_hmdfmp = 0;
                        $d_hmdfhouse = 0;
                        $d_cdcfd = 0;
                        $d_cdcsd = 0;
                        $d_cdcloan = 0;
                        $d_pmpchmo = 0;
                        $d_pmpcfd = 0;
                        $d_pmpcsd = 0;
                        $d_pmpcloan = 0;
                        $d_ldp = 0;
                        $d_others = 0;

                        $loans = getPersonalLoans($vl->username);

                        foreach ($loans as $kls => $vls) 
                        {
                            switch ($vls->SERV_CODE) {
                                case "305":
                                        $d_gspol = $vls->DED_AMOUNT;
                                        $total_deduc_gspol += $vls->DED_AMOUNT;

                                        $total_deduc_total += $vls->DED_AMOUNT;
                                        $d_total += $vls->DED_AMOUNT;
                                    break;
                                case "319":
                                        $d_gscon = $vls->DED_AMOUNT;
                                        $total_deduc_gscon += $vls->DED_AMOUNT;

                                        $total_deduc_total += $vls->DED_AMOUNT;
                                        $d_total += $vls->DED_AMOUNT;
                                    break;
                                case "316":
                                        $d_gseml = $vls->DED_AMOUNT;
                                        $total_deduc_gseml += $vls->DED_AMOUNT;

                                        $total_deduc_total += $vls->DED_AMOUNT;
                                        $d_total += $vls->DED_AMOUNT;
                                    break;
                                case "315":
                                        $d_gseduc = $vls->DED_AMOUNT;
                                        $total_deduc_gseduc += $vls->DED_AMOUNT;

                                        $total_deduc_total += $vls->DED_AMOUNT;
                                        $d_total += $vls->DED_AMOUNT;
                                    break;
                                case "305A":
                                        $d_gsopt= $vls->DED_AMOUNT;
                                        $total_deduc_gsopt += $vls->DED_AMOUNT;

                                        $total_deduc_total += $vls->DED_AMOUNT;
                                        $d_total += $vls->DED_AMOUNT;
                                    break;
                                case "319C":
                                        $d_gscp= $vls->DED_AMOUNT;
                                        $total_deduc_gscp += $vls->DED_AMOUNT;

                                        $total_deduc_total += $vls->DED_AMOUNT;
                                        $d_total += $vls->DED_AMOUNT;
                                    break;
                                case "319B":
                                        $d_gsmp= $vls->DED_AMOUNT;
                                        $total_deduc_gsmp += $vls->DED_AMOUNT;

                                        $total_deduc_total += $vls->DED_AMOUNT;
                                        $d_total += $vls->DED_AMOUNT;
                                    break;
                                case "319A":
                                        $d_gsgfal= $vls->DED_AMOUNT;
                                        $total_deduc_gsgfal += $vls->DED_AMOUNT;

                                        $total_deduc_total += $vls->DED_AMOUNT;
                                        $d_total += $vls->DED_AMOUNT;
                                    break;
                                case "302A":
                                        $d_hmdfmp= $vls->DED_AMOUNT;
                                        $total_deduc_hmdfmp += $vls->DED_AMOUNT;

                                        $total_deduc_total += $vls->DED_AMOUNT;
                                        $d_total += $vls->DED_AMOUNT;
                                    break;
                                case "302B":
                                        $d_hmdfhouse= $vls->DED_AMOUNT;
                                        $total_deduc_hmdfhouse += $vls->DED_AMOUNT;

                                        $total_deduc_total += $vls->DED_AMOUNT;
                                        $d_total += $vls->DED_AMOUNT;
                                    break;
                                case "920":
                                        //$d_cdcfd= $vls->DED_AMOUNT;
                                        //$d_cdcfd = $plantilla['plantilla_salary'] * 0.02;
                                        //$total_deduc_cdcfd += $d_cdcfd;
                                        if($vls->DED_AMOUNT > 0)
                                              $d_cdcfd = $plantilla['plantilla_salary'] * 0.02;
                                            else 
                                              $d_cdcfd = 0;

                                        $total_deduc_cdcfd += $d_cdcfd;

                                        $total_deduc_total += $d_cdcfd;
                                        $d_total += $d_cdcfd;
                                    break;
                                case "922":
                                        $d_cdcsd= $vls->DED_AMOUNT;
                                        $total_deduc_cdcsd += $vls->DED_AMOUNT;

                                        $total_deduc_total += $vls->DED_AMOUNT;
                                        $d_total += $vls->DED_AMOUNT;
                                    break;
                                case "921":
                                case "921A":
                                case "923":
                                        $d_cdcloan= $vls->DED_AMOUNT;
                                        $total_deduc_cdcloan += $vls->DED_AMOUNT;

                                        $total_deduc_total += $vls->DED_AMOUNT;
                                        $d_total += $vls->DED_AMOUNT;
                                    break;
                                case "933":
                                        $d_pmpchmo= $vls->DED_AMOUNT;
                                        $total_deduc_pmpchmo += $vls->DED_AMOUNT;

                                        $total_deduc_total += $vls->DED_AMOUNT;
                                        $d_total += $vls->DED_AMOUNT;
                                    break;
                                case "930":

                                        if($vls->DED_AMOUNT > 0)
                                              $d_pmpcfd = $plantilla['plantilla_salary'] * 0.02;
                                            else 
                                              $d_pmpcfd = 0;
                                        
                                        $total_deduc_pmpcfd += $d_pmpcfd;

                                        $total_deduc_total += $d_pmpcfd;
                                        $d_total += $d_pmpcfd;
                                    break;
                                case "932":
                                        $d_pmpcsd= $vls->DED_AMOUNT;
                                        $total_deduc_pmpcsd += $vls->DED_AMOUNT;

                                        $total_deduc_total += $vls->DED_AMOUNT;
                                        $d_total += $vls->DED_AMOUNT;
                                    break;
                                case "931":
                                        $d_pmpcloan= $vls->DED_AMOUNT;
                                        $total_deduc_pmpcloan += $vls->DED_AMOUNT;

                                        $total_deduc_total += $vls->DED_AMOUNT;
                                        $d_total += $vls->DED_AMOUNT;
                                    break;
                                case "321":
                                        $d_ldp = $vls->DED_AMOUNT;
                                        $total_deduc_ldp += $vls->DED_AMOUNT;

                                        $total_deduc_total += $vls->DED_AMOUNT;
                                        $d_total += $vls->DED_AMOUNT;
                                    break;
                                default:
                                        $d_others = $vls->DED_AMOUNT;
                                        $total_deduc_others += $vls->DED_AMOUNT;

                                        $total_deduc_total += $vls->DED_AMOUNT;
                                        $d_total += $vls->DED_AMOUNT;
                                break;
                            }
                        }

                    
                        //RATA
                        $ra = 0;
                        $ta = 0;
                        $pera = 0;
                        $comp_adj = 0;

                        $comp = collect(App\Payroll\Compensation::where('empCode',$vl->username)->get());
                        $comp = $comp->all();
                        foreach ($comp as $c => $comps) {
                            switch ($comps->compID) {
                                case 1:
                                        $pera = $comps->compAmount;
                                    break;
                                case 2:
                                        $comp_adj = $comps->compAmount;
                                    break;
                                case 3:
                                        $ra = $comps->compAmount;
                                    break;
                                case 4:
                                        $ta = $comps->compAmount;
                                    break;
                            }
                        }

                        //GET LWOP
                        $lwopprevyear = request()->payrollyear - 1;
                        $prev_lwop = App\Payroll\LWOP::where('empcode',$vl->username)->whereYear('leave_date',$lwopprevyear)->whereNull('process_at')->sum('amt');
                        //$d_total += $prev_lwop;

                        $gross = $plantilla['plantilla_salary'] + $pera;
                        $net = $gross - ($d_total + $prev_lwop);

                        $total_basic += $plantilla['plantilla_salary'];
                        $total_pera_com += $pera;
                        $total_rep += $ra;
                        $total_trans += $ta;
                        $total_comp_adj += $comp_adj;
                        $total_lwop += 0;
                        // $total_pera_ded += $v->PERA_DED;
                        // $total_pera_adj += $v->PERA_ADJ;
                        // $total_comp_adj += $v->COMP_ADJ;
                        $total_prevLWOP += $prev_lwop;
                        $total_gross += $gross;
                        // $total_deduc += $total_deduc;
                        $total_net += $net;


                        $grandtotal_basic += $plantilla['plantilla_salary'];
                        $grandtotal_pera_com += $pera;
                        $grandtotal_rep += $ra;
                        $grandtotal_trans += $ta;
                        // $grandtotal_lwop += $v->LWOP;
                        // $grandtotal_pera_ded += $v->PERA_DED;
                        // $grandtotal_pera_adj += $v->PERA_ADJ;
                        $grandtotal_comp_adj += $comp_adj;
                        $grandtotal_prevLWOP += $prev_lwop;
                        $grandtotal_gross += $gross;
                        // $grandtotal_deduc += $total_deduc;
                        $grandtotal_net += $net;

                        //SALARY WEEK
                        $wk1 = 0;
                        $wk2 = 0;
                        $wk3 = 0;
                        $wk4 = 0;
                        for ($i=1; $i <= 4 ; $i++) {
                            $salary = getWeekSalary($vl->username,$net,$i,1);
                                switch ($i) {
                                    case 1:
                                        $wk1 = $salary;
                                        $total_week_1 += $salary;
                                        $grandtotal_week_1 += $salary;
                                    break;

                                    case 2:
                                        $wk2 = $salary;
                                        $total_week_2 += $salary;
                                        $grandtotal_week_2 += $salary;
                                    break;

                                    case 3:
                                        $wk3 = $salary;
                                        $total_week_3 += $salary;
                                        $grandtotal_week_3 += $salary;
                                    break;

                                    case 4:
                                        $wk4 = $salary;
                                        $total_week_4 += $salary;
                                        $grandtotal_week_4 += $salary;
                                    break;
                                }
                        }
                    

                        //GRANDTOTAL DEDUC
                        $grandtotal_deduc += $d_total;
                        $staff .= '
                            <tr>
                            <td class="bd2">'.strtoupper($vl->lname.', '.$vl->fname.' '.substr($vl->mname,0,1).".").'</td>
                            <td class="bd2">'.$plantilla['position_abbr'].'</td>
                            <td class="bd2" align="right">'.$this->formatCash($plantilla['plantilla_salary']).'</td>
                            <td class="bd2" align="right">'.$this->formatCash($pera).'</td>
                            <td class="bd2" align="right">'.$this->formatCash($ra).'</td>
                            <td class="bd2" align="right">'.$this->formatCash($ta).'</td>
                            <td class="bd2" align="right">-</td>
                            <td class="bd2" align="right">-</td>
                            <td class="bd2" align="right">'.$this->formatCash($comp_adj).'</td>
                            <td class="bd2" align="right">-</td>
                            <td class="bd2" align="right">'.$this->formatCash($prev_lwop).'</td>
                            <td class="bd2" align="right">'.$this->formatCash($gross).'</td>
                            <td class="bd2" align="right">'.$this->formatCash($d_total).'</td>
                            <td class="bd2" align="right">'.$this->formatCash($net).'</td>
                            <td class="bd2" align="right">'.$this->formatCash($wk1).'</td>
                            <td class="bd2" align="right">'.$this->formatCash($wk2).'</td>
                            <td class="bd2" align="right">'.$this->formatCash($wk3).'</td>
                            <td class="bd2" align="right">'.$this->formatCash($wk4).'</td>
                        </tr>';
                }

                // <td class="bd4" align="right">'.$this->formatCash($d_ph).'</td>

                $staff2 .= '
                        <tr>
                        <td class="bd4">'.strtoupper($vl->lname.', '.$vl->fname.' '.substr($vl->mname,0,1).".").'</td>
                        <td class="bd4" align="right">'.$this->formatCash($d_itw).'</td>
                        <td class="bd4" align="right">'.$this->formatCash($d_sic).'</td>
                        <td class="bd4" align="right">'.$this->formatCash($d_ph).'</td>
                        <td class="bd4" align="right">'.$this->formatCash($d_hmdf).'</td>
                        <td class="bd4" align="right">'.$this->formatCash($d_gspol).'</td>
                        <td class="bd4" align="right">'.$this->formatCash($d_gscon).'</td>
                        <td class="bd4" align="right">'.$this->formatCash($d_gseml).'</td>
                        <td class="bd4" align="right">'.$this->formatCash($d_gseduc).'</td>
                        <td class="bd4" align="right">'.$this->formatCash($d_gsopt).'</td>
                        <td class="bd4" align="right">'.$this->formatCash($d_gscp).'</td>
                        <td class="bd4" align="right">'.$this->formatCash($d_gsmp).'</td>
                        <td class="bd4" align="right">'.$this->formatCash($d_gsgfal).'</td>
                        <td class="bd4" align="right">'.$this->formatCash($d_hmdfmp).'</td>
                        <td class="bd4" align="right">'.$this->formatCash($d_hmdfhouse).'</td>
                        <td class="bd4" align="right">'.$this->formatCash($d_cdcfd).'</td>
                        <td class="bd4" align="right">'.$this->formatCash($d_cdcsd).'</td>
                        <td class="bd4" align="right">'.$this->formatCash($d_cdcloan).'</td>
                        <td class="bd4" align="right">'.$this->formatCash($d_pmpchmo).'</td>
                        <td class="bd4" align="right">'.$this->formatCash($d_pmpcfd).'</td>
                        <td class="bd4" align="right">'.$this->formatCash($d_pmpcsd).'</td>
                        <td class="bd4" align="right">'.$this->formatCash($d_pmpcloan).'</td>
                        <td class="bd4" align="right">'.$this->formatCash($d_ldp).'</td>
                        <td class="bd4" align="right">'.$this->formatCash($d_others).'</td>
                        <td class="bd4" align="right">'.$this->formatCash($d_total).'</td>
                    </tr>';
                
                
            }

            $staff .= '
                    <tr>
                    <td class="bd2" style="border-top:1px solid #000" colspan="2"><b>*DIVISION TOTALS*</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($total_basic).'</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($total_pera_com).'</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($total_rep).'</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($total_trans).'</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($total_lwop).'</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($total_pera_ded).'</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($total_pera_adj).'</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($total_comp_adj).'</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($total_prevLWOP).'</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($total_gross).'</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($total_deduc_total).'</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($total_net).'</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($total_week_1).'</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($total_week_2).'</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($total_week_3).'</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($total_week_4).'</b></td>
                </tr>
                <tr>
                <td colspan="20"><br/><small>CERTIFIED: Legally created position exists with fixed compensation; services rendered under my direct supervision.</small>
                <br>
                <br>
                <br>
                <br>
                    '.getDirector($value->division_id).'
                </td>
                </tr>';

            $row .= '
                <table width="100%" cellspacing="0" cellpadding="0">
                    '.$header.'
                    <tr>
                        <td colspan="3"><center><h5>GENERAL PAYROLL UNDER LBP ATM CREDIT SYSTEM<br/>For the month of '.$mon2.' '.request()->payrollyear.'</h5></center></td>
                    </tr>
                </table>
                    <table width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td colspan="18"><b>Division : '.$value->division_desc.'</td>
                    </tr>
                    <tr>
                        <td class="bd1"><b>Employee</b></td>
                        <td class="bd1"><b>Position</b></td>
                        <td class="bd1" align="right"><b>Monthly Salary</b></td>
                        <td class="bd1" align="right"><b>PERA</b></td>
                        <td class="bd1" align="right"><b>Rep Allow*</b></td>
                        <td class="bd1" align="right"><b>Trans Allow*</b></td>
                        <td class="bd1" align="right"><b>LWOP</b></td>
                        <td class="bd1" align="right"><b>PERA Deduct</b></td>
                        <td class="bd1" align="right"><b>PERA Adj</b></td>
                        <td class="bd1" align="right"><b>Comp Adj</b></td>
                        <td class="bd1" align="right"><b>Prev Years LWOP</b></td>
                        <td class="bd1" align="right"><b>Gross</b></td>
                        <td class="bd1" align="right"><b>Total Deductions</b></td>
                        <td class="bd1" align="right"><b>Net Salary</b></td>
                        <td class="bd1" align="right"><b>Week1*</b></td>
                        <td class="bd1" align="right"><b>Week2</b></td>
                        <td class="bd1" align="right"><b>Week3</b></td>
                        <td class="bd1" align="right"><b>Week4</b></td>
                    </tr>
                    '.$staff.'
                    </table>
                    <div class="page-break"></div>';
                
                

                //DEDCUTIONS
                
                $row .= '
                <table width="100%" cellspacing="0" cellpadding="0">
                    '.$header.'
                    <tr>
                        <td colspan="3"><center><h5>TOTAL DEDUCTION REPORT<br/>For the month of '.$mon2.' '.request()->payrollyear.'</h5></center></td>
                    </tr>
                </table>
                    <table width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td colspan="18"><b>Division : '.$value->division_desc.'</td>
                    </tr>
                    <tr>
                        <td class="bd3"><b>Employee</b></td>
                        <td class="bd3" align="right"><b>ITW</b></td>
                        <td class="bd3" align="right"><b>SIC</b></td>
                        <td class="bd3" align="right"><b>Phil-Health</b></td>
                        <td class="bd3" align="right"><b>HDMF</b></td>
                        <td class="bd3" align="right"><b>GSIS Policy</b></td>
                        <td class="bd3" align="right"><b>GSIS Conso</b></td>
                        <td class="bd3" align="right"><b>GSIS EML</b></td>
                        <td class="bd3" align="right"><b>GSIS Educ</b></td>
                        <td class="bd3" align="right"><b>GSIS Opt Policy</b></td>
                        <td class="bd3" align="right"><b>GSIS CP</b></td>
                        <td class="bd3" align="right"><b>GSIS MP</b></td>
                        <td class="bd3" align="right"><b>GSIS GFAL</b></td>
                        <td class="bd3" align="right"><b>HDMF MP</b></td>
                        <td class="bd3" align="right"><b>HDMF Housing</b></td>
                        <td class="bd3" align="right"><b>CDC FD</b></td>
                        <td class="bd3" align="right"><b>CDC SD</b></td>
                        <td class="bd3" align="right"><b>CDC Loans</b></td>
                        <td class="bd3" align="right"><b>PMPC HMO</b></td>
                        <td class="bd3" align="right"><b>PMPC FD</b></td>
                        <td class="bd3" align="right"><b>PMPC SD</b></td>
                        <td class="bd3" align="right"><b>PMPC Loans</b></td>
                        <td class="bd3" align="right"><b>LBP</b></td>
                        <td class="bd3" align="right"><b>Others</b></td>
                        <td class="bd3" align="right"><b>Total Deductions</b></td>
                    </tr>
                    '.$staff2.'
                    <tr>
                        <td class="bd3"><b>DIVISION TOTAL</b></td>
                        <td class="bd3" align="right"><b>'.$this->formatCash($total_deduc_itw).'</b></td>
                        <td class="bd3" align="right"><b>'.$this->formatCash($total_deduc_sic).'</b></td>
                        <td class="bd3" align="right"><b>'.$this->formatCash($total_deduc_ph).'</b></td>
                        <td class="bd3" align="right"><b>'.$this->formatCash($total_deduc_hdmf).'</b></td>
                        <td class="bd3" align="right"><b>'.$this->formatCash($total_deduc_gspol).'</b></td>
                        <td class="bd3" align="right"><b>'.$this->formatCash($total_deduc_gscon).'</b></td>
                        <td class="bd3" align="right"><b>'.$this->formatCash($total_deduc_gseml).'</b></td>
                        <td class="bd3" align="right"><b>'.$this->formatCash($total_deduc_gseduc).'</b></td>
                        <td class="bd3" align="right"><b>'.$this->formatCash($total_deduc_gsopt).'</b></td>
                        <td class="bd3" align="right"><b>'.$this->formatCash($total_deduc_gscp).'</b></td>
                        <td class="bd3" align="right"><b>'.$this->formatCash($total_deduc_gsmp).'</b></td>
                        <td class="bd3" align="right"><b>'.$this->formatCash($total_deduc_gsgfal).'</b></td>
                        <td class="bd3" align="right"><b>'.$this->formatCash($total_deduc_hmdfmp).'</b></td>
                        <td class="bd3" align="right"><b>'.$this->formatCash($total_deduc_hmdfhouse).'</b></td>
                        <td class="bd3" align="right"><b>'.$this->formatCash($total_deduc_cdcfd).'</b></td>
                        <td class="bd3" align="right"><b>'.$this->formatCash($total_deduc_cdcsd).'</b></td>
                        <td class="bd3" align="right"><b>'.$this->formatCash($total_deduc_cdcloan).'</b></td>
                        <td class="bd3" align="right"><b>'.$this->formatCash($total_deduc_pmpchmo).'</b></td>
                        <td class="bd3" align="right"><b>'.$this->formatCash($total_deduc_pmpcfd).'</b></td>
                        <td class="bd3" align="right"><b>'.$this->formatCash($total_deduc_pmpcsd).'</b></td>
                        <td class="bd3" align="right"><b>'.$this->formatCash($total_deduc_pmpcloan).'</b></td>
                        <td class="bd3" align="right"><b>'.$this->formatCash($total_deduc_ldp).'</b></td>
                        <td class="bd3" align="right"><b>'.$this->formatCash($total_deduc_others).'</b></td>
                        <td class="bd3" align="right"><b>'.$this->formatCash($total_deduc_total).'</b></td>
                    </tr>
                    </table>
                    <div class="page-break"></div>';
        }

        $row .= '<table width="100%" cellspacing="0" cellpadding="0">
                    '.$header.'
                    <tr>
                        <td colspan="3"><center><h5>GENERAL PAYROLL UNDER LBP ATM CREDIT SYSTEM<br/>For the month of '.$mon2.' '.request()->payrollyear.'</h5></center></td>
                    </tr>
                </table>
                <table width="100%" cellspacing="0" cellpadding="0">
                <tr>
                        <td class="bd1"></td>
                        <td class="bd1" align="right"><b>Monthly Salary</b></td>
                        <td class="bd1" align="right"><b>PERA</b></td>
                        <td class="bd1" align="right"><b>Rep Allow*</b></td>
                        <td class="bd1" align="right"><b>Trans Allow*</b></td>
                        <td class="bd1" align="right"><b>LWOP</b></td>
                        <td class="bd1" align="right"><b>PERA Deduct</b></td>
                        <td class="bd1" align="right"><b>PERA Adj</b></td>
                        <td class="bd1" align="right"><b>Comp Adj</b></td>
                        <td class="bd1" align="right"><b>Prev Years LWOP</b></td>
                        <td class="bd1" align="right"><b>Gross</b></td>
                        <td class="bd1" align="right"><b>Total Deductions</b></td>
                        <td class="bd1" align="right"><b>Net Salary</b></td>
                        <td class="bd1" align="right"><b>Week1*</b></td>
                        <td class="bd1" align="right"><b>Week2</b></td>
                        <td class="bd1" align="right"><b>Week3</b></td>
                        <td class="bd1" align="right"><b>Week4</b></td>
                    </tr>
                    <tr>
                    <td class="bd2" style="border-top:1px solid #000"><b>*GRANDTOTAL*</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($grandtotal_basic).'</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($grandtotal_pera_com).'</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($grandtotal_rep).'</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($grandtotal_trans).'</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($grandtotal_lwop).'</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($grandtotal_pera_ded).'</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($grandtotal_pera_adj).'</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($grandtotal_comp_adj).'</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($grandtotal_prevLWOP).'</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($grandtotal_gross).'</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($grandtotal_deduc).'</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($grandtotal_net).'</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($grandtotal_week_1).'</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($grandtotal_week_2).'</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($grandtotal_week_3).'</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($grandtotal_week_4).'</b></td>
                </tr></table>
                <br>
                <br>
                <br>
                <br>
                <table width="100%" cellspacing="0" cellpadding="0">
                <tr valign="top">
                    <td>
                        A. PREPARED BY: <br/><br/><br/><br/>
                        <b>NIDA L. MANGALINDAN</b><br>Administrative Officer I<br/><br/><br/><br/>
                        C. CERTIFIED: Supporting documents complete<br/>and cash available in the amount of ________________________<br/><br/><br/><br/>
                        <b>ABEGAIL GRACE M. MARALIT</b><br>OIC FAD-Accounting<br/><br/><br/><br/><br/>
                        OBR No. ________________________<br/>
                        DATE ________________________<br/>
                        JEV No. ________________________<br/>
                        DATE    ________________________<br/>
                    </td>

                    <td>
                        B. CERTIFIED CORRECT: <br/><br/><br/><br/>
                        <b>GEORGIA M. LAWAS</b><br>Administrative Officer V<br/><br/><br/><br/>
                        D. Approved for payment:____________________________<br/><br/><br/><br/>
                        <b>REYNALDO V. EBORA</b><br>Executive Director<br/><br/><br/><br/>
                        E. CERTIFIED CORRECT Each employee whose name appears above has been paid the<br/>amount indicated opposite on his/her name:<br/><br/><br/><br/>
                        <b>HEIDELITA A. RAMOS</b><br>Disbursing Officer<br/><br/><br/><br/>
                        
                    </td>

                </tr>
                </table>
                <div class="page-break"></div>';

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML('<!DOCTYPE html>
                            <html>
                            <head>
                              <title>HRMS - SALARY</title>
                              <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                            </head>
                            <style type="text/css">
                                body
                                {
                                    font-family:Helvetica;
                                }
                                th,td
                                {
                                    border:0px solid #555;
                                    font-size:10px;
                                }

                                .page-break {
                                    page-break-after: always;
                                   }
                                .bd1
                                {
                                    border-top:1px solid #000;
                                    border-bottom:1px solid #000;
                                    font-size:10px;
                                }

                                .bd2
                                {
                                    font-size:10px;
                                }

                                .bd3
                                {
                                    border-top:1px solid #000;
                                    border-bottom:1px solid #000;
                                    font-size:10px;
                                }

                                .bd4
                                {
                                    font-size:10px;
                                }
    
                            </style>
                            <body>
                                '.$row.'
                            
                            </body>
                            </html>')
        ->setPaper('legal', 'landscape');
        return $pdf->stream();
    }


    public function printMC()
    {
        ini_set('memory_limit', '512M');

        $date = request()->print_mc_mon .' '.request()->print_mc_year;

        $mon2 = date('F',mktime(0, 0, 0, request()->print_mc_mon, 10));

        //GET ALL DIVISION
        $row = "";
        $division = getDivisionList();

        $grandtotal_basic = 0;
        $grandtotal_lp= 0;
        $grandtotal_sa = 0;
        $grandtotal_la = 0;
        $grandtotal_deduc = 0;
        $grandtotal_itw= 0;
        $grandtotal_hp = 0;

        $granddeduc_hmo = 0;
        $granddeduc_gsis = 0;
        $granddeduc_pmpc = 0;
        $granddeduc_cdc = 0;
        $granddeduc_gfal = 0;
        $granddeduc_landbank = 0;
        $granddeduc_itw = 0;
        $granddeduc_total = 0;
        $grandtotal_net = 0;
        
        foreach ($division as $key => $value) 
        {

            //GET EMPLOYEE
            $emp = getStaffDivisionMC($value->division_id);
            

            $staff = "";
            $staff2 = "";

            $total_basic = 0;
            $total_lp = 0;
            $total_sa = 0;
            $total_la = 0;
            $total_deduc = 0;
            $total_itw = 0;
            $total_hp = 0;
            $total_net = 0;
            


            $deduc_hmo = 0;
            $deduc_gsis = 0;
            $deduc_pmpc = 0;
            $deduc_cdc = 0;
            $deduc_gfal = 0;
            $deduc_landbank = 0;
            $deduc_itw = 0;
            $deduc_total = 0;

            foreach ($emp as $ky => $vl) {

                //GET INFO
                $info = $this->getInfoMC(request()->print_mc_mon,request()->print_mc_year,$vl->username);
                // $info = App\Payroll\PrevInfo::where('fldMonth',request()->payrollmon)->where('fldYear',request()->payrollyear)->where('fldEmpCode',$vl->username)->first();

                foreach ($info as $k => $v) {

                    $plantilla = getPlantillaInfo($vl->username);

                    //SA
                    // $m = 0;
                    // $mcd = App\MCday::where('process_code',$v->process_code)->get();

                    // foreach($mcd as $key => $v3) {
                    //         $dt = date('M d, y',strtotime($v3->req_date_from));
                    //         if($v3->req_date_from != $v3->req_date_to)
                    //         {
                    //             $dt = date('M d, y',strtotime($v3->req_date_from))." - ".date('M d, y',strtotime($v3->req_date_to));
                    //         }
                    //         if($v3->req_type != 'Travel')
                    //         {
                    //             $m += $v3->req_deduc;
                    //         }
                            
                    // }

                    //GET LEAVE

                    //COUNT NO. LEAVES
                    if(request()->print_mc_mon == 1)
                    {
                        $m_mon = 12;
                        $m_year = request()->print_mc_year - 1;
                    }
                    else
                    {
                        $m_mon = request()->print_mc_mon - 1;
                        $m_year = request()->print_mc_year;
                    }

                    //TO
                    //MULTIPLE DATE
                    // $t1_total = App\RequestTO::where('user_id',$vl->id)->whereNull('parent_leave')->whereNotNull('parent_leave_code')->whereNotIn('leave_id',[5,16])->where('leave_action_status','Approved')->whereMonth('leave_date_from',$m_mon)->whereYear('leave_date_from',$m_year)->count();

                    // //SINGLE DATE
                    // $t2_total = App\RequestTO::where('user_id',$vl->id)->where('parent','YES')->whereIn('leave_deduction',[1,0.5])->whereNotIn('leave_id',[5,16])->where('leave_action_status','Approved')->whereMonth('leave_date_from',$m_mon)->whereYear('leave_date_from',$m_year)->sum('leave_deduction');

                    // $t_total = $t1_total + $t2_total;


                    //LEAVE
                    //MULTIPLE DATE
                    // $l1_total = App\Request_leave::where('user_id',$vl->id)->whereNull('parent_leave')->whereNotNull('parent_leave_code')->whereNotIn('leave_id',[5,16])->where('leave_action_status','Approved')->whereMonth('leave_date_from',$m_mon)->whereYear('leave_date_from',$m_year)->count();

                    // //SINGLE DATE
                    // $l2_total = App\Request_leave::where('user_id',$vl->id)->where('parent','YES')->whereIn('leave_deduction',[1,0.5])->whereNotIn('leave_id',[5,16])->where('leave_action_status','Approved')->whereMonth('leave_date_from',$m_mon)->whereYear('leave_date_from',$m_year)->sum('leave_deduction');

                    // $l_total = $l1_total + $l2_total;


                    
                    //GET SA
                    // $sa = App\Employee_sala::where('process_code',$v->process_code)->first();
                    
                    // if($sa)
                    // {
                    //     //$t_sa = $sa['sa_amt'] - ($l_total * 150);
                    //     //$t_la = $sa['la_amt'] - (($sa['la_amt'] / 22) * $l_total);
                    //     $t_sa = $v->sa;
                    //     $t_la = $v->la;
                    // }
                    // else
                    // {
                    //     $t_sa = 0;
                    //     $t_la = 0;
                    // }

                    $t_sa = $v->sa;
                    $t_la = $v->la;

                    //HP
                    $t_hp = $v->salary * $v->hprate;
                    
                    if($vl->employment_id == 15)
                    {
                        $t_sa = 0;
                        $t_la = 0;
                        $t_hp = 0;
                    }
                

                    $total_basic += $v->salary;
                    $grandtotal_basic += $v->salary;

                    $total_lp += $v->lp;
                    $grandtotal_lp += $v->lp;

                    $total_sa += $t_sa;
                    $grandtotal_sa += $t_sa;

                    $total_la += $t_la;
                    $grandtotal_la += $t_la;
                    
                    $total_itw += $v->itw;
                    $grandtotal_itw += $v->itw;

                    $total_hp += $t_hp;
                    $grandtotal_hp += $t_hp;

                    //DEDUCTIONS
                    $total_deduc += getTotalMCDeduc('total',$vl->id,request()->print_mc_mon,request()->print_mc_year) + $v->itw;
                    $grandtotal_deduc += getTotalMCDeduc('total',$vl->id,request()->print_mc_mon,request()->print_mc_year) + $v->itw;

                    $net_mc = ($v->lp + $t_sa + $t_la + $t_hp) - (getTotalMCDeduc('total',$vl->id,request()->print_mc_mon,request()->print_mc_year) + $v->itw);
                    $total_net += ($v->lp + $t_sa + $t_la + $t_hp) - (getTotalMCDeduc('total',$vl->id,request()->print_mc_mon,request()->print_mc_year) + $v->itw);
                    $grandtotal_net += ($v->lp + $t_sa + $t_la + $t_hp) - (getTotalMCDeduc('total',$vl->id,request()->print_mc_mon,request()->print_mc_year) + $v->itw);

                    //HP


                    $staff .= '
                    <tr>
                    <td class="bd2">'.$vl->username.'</td>
                    <td class="bd2" align="left">'.strtoupper($vl->lname.', '.$vl->fname.' '.substr($vl->mname,0,1).".").'</td>
                    <td class="bd2" align="right">'.$this->formatCash($v->salary).'</td>
                    <td class="bd2" align="right">'.$this->formatCash($v->lp).'</td>
                    <td class="bd2" align="right">'.$this->formatCash($t_sa).'</td>
                    <td class="bd2" align="right">'.$this->formatCash($t_la).'</td>
                    <td class="bd2" align="right">'.$v->hprate.'</td>
                    <td class="bd2" align="right">'.$this->formatCash($t_hp).'</td>
                    <td class="bd2" align="right">'.$this->formatCash(getTotalMCDeduc('total',$vl->id,request()->print_mc_mon,request()->print_mc_year) + $v->itw).'</td>
                    <td class="bd2" align="right">'.$this->formatCash($net_mc).'</td>
                </tr>';
                
                

                $deduc_hmo += $v->hmo;
                $granddeduc_hmo += $v->hmo;

                $deduc_gsis += $v->gsis;
                $granddeduc_gsis += $v->gsis;

                $deduc_pmpc += $v->pmpc;
                $granddeduc_pmpc += $v->pmpc;

                $deduc_cdc += $v->cdc;
                $granddeduc_cdc += $v->cdc;

                $deduc_gfal += $v->gfal;
                $granddeduc_gfal += $v->gfal;

                $deduc_landbank += $v->landbank;
                $granddeduc_landbank += $v->landbank;

                $deduc_itw += $v->itw;
                $granddeduc_itw += $v->itw;
                

                $deduc_total += $v->hmo + $v->gsis + $v->pmpc + $v->cdc + $v->gfal + $v->landbank + $v->itw;

                $granddeduc_total += $v->hmo + $v->gsis + $v->pmpc + $v->cdc + $v->gfal + $v->landbank + $v->itw;

                $staff2 .= '
                    <tr>
                    <td class="bd2">'.$vl->username.'</td>
                    <td class="bd2" align="left">'.strtoupper($vl->lname.', '.$vl->fname.' '.substr($vl->mname,0,1).".").'</td>
                    <td class="bd2" align="right">'.$this->formatCash($v->hmo).'</td>
                    <td class="bd2" align="right">'.$this->formatCash($v->gsis).'</td>
                    <td class="bd2" align="right">'.$this->formatCash($v->pmpc).'</td>
                    <td class="bd2" align="right">'.$this->formatCash($v->cdc).'</td>
                    <td class="bd2" align="right">'.$this->formatCash($v->gfal).'</td>
                    <td class="bd2" align="right">'.$this->formatCash($v->landbank).'</td>
                    <td class="bd2" align="right">'.$this->formatCash($v->itw).'</td>
                    <td class="bd2" align="right">'.$this->formatCash($v->hmo + $v->gsis + $v->pmpc + $v->cdc + $v->gfal + $v->landbank + $v->itw).'</td>
                </tr>';
                }
                
            }

            $staff .= '
                    <tr>
                    <td class="bd2" style="border-top:1px solid #000" colspan="2"><b>*DIVISION TOTALS*</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($total_basic).'</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($total_lp).'</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($total_sa).'</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($total_la).'</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b></b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($total_hp).'</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($total_deduc).'</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($total_net).'</b></td>
                </tr>';


            $row .= '
                <table width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                    <td style="border : 1px solid #FFF;font-size:12px;" align="center">
                            PAYMENT FOR MAGNA CARTA(MC) BENEFITS<br/>
                            per DBM-DOST Joint Circular No. 1, s. 2013, dated June 23, 2013 
                            <br/>
                    </td>
                    </tr>
                    <tr>
                        <td><center><h5>For the month of '.$mon2.' '.request()->print_mc_year.'</h5></center></td>
                    </tr>
                </table>
                    <table width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                        <td colspan="10"><b>Division : '.$value->division_desc.'</td>
                    </tr>
                    <tr>
                        <td class="bd1"><b>ID</b></td>
                        <td class="bd1" align="left"><b>Employee</b></td>
                        <td class="bd1" align="right"><b>Basic Salary</b></td>
                        <td class="bd1" align="right"><b>LP Current</b></td>
                        <td class="bd1" align="right"><b>SA</b></td>
                        <td class="bd1" align="right"><b>LA</b></td>
                        <td class="bd1" align="right"><b>HP Rate</b></td>
                        <td class="bd1" align="right"><b>HP</b></td>
                        <td class="bd1" align="right"><b>Deductions</b></td>
                        <td class="bd1" align="right"><b>Net MC</b></td>
                    </tr>
                    '.$staff.'
                    </table>
                    <div class="page-break"></div>';

            //FOR DEDUCTION NAMAN
            $row .= '
                <table width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                    <td style="border : 1px solid #FFF;font-size:12px;" align="center">
                            PAYMENT FOR MAGNA CARTA(MC) BENEFITS<br/>
                            per DBM-DOST Joint Circular No. 1, s. 2013, dated June 23, 2013 
                            <br/>
                    </td>
                    </tr>
                    <tr>
                        <td><center><h5>(Deductions) For the month of '.$mon2.' '.request()->print_mc_year.'</h5></center></td>
                    </tr>
                </table>
                    <table width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                        <td colspan="10"><b>Division : '.$value->division_desc.'</td>
                    </tr>
                    <tr>
                        <td class="bd1"><b>ID</b></td>
                        <td class="bd1" align="left"><b>Employee</b></td>
                        <td class="bd1" align="right"><b>HMO</b></td>
                        <td class="bd1" align="right"><b>GSIS</b></td>
                        <td class="bd1" align="right"><b>PMPC</b></td>
                        <td class="bd1" align="right"><b>CDC</b></td>
                        <td class="bd1" align="right"><b>GFAL</b></td>
                        <td class="bd1" align="right"><b>Landbank</b></td>
                        <td class="bd1" align="right"><b>ITW</b></td>
                        <td class="bd1" align="right"><b>Total Deduction</b></td>
                    </tr>
                    '.$staff2.'
                    <tr>
                        <td class="bd2" style="border-top:1px solid #000" colspan="2"><b>*DIVISION TOTALS*</b></td>
                        <td class="bd2" style="border-top:1px solid #000" align="right"><b>'.$this->formatCash($deduc_hmo).'</b></td>
                        <td class="bd2" style="border-top:1px solid #000" align="right"><b>'.$this->formatCash($deduc_gsis).'</b></td>
                        <td class="bd2" style="border-top:1px solid #000" align="right"><b>'.$this->formatCash($deduc_pmpc).'</b></td>
                        <td class="bd2" style="border-top:1px solid #000" align="right"><b>'.$this->formatCash($deduc_cdc).'</b></td>
                        <td class="bd2" style="border-top:1px solid #000" align="right"><b>'.$this->formatCash($deduc_gfal).'</b></td>
                        <td class="bd2" style="border-top:1px solid #000" align="right"><b>'.$this->formatCash($deduc_landbank).'</b></td>
                        <td class="bd2" style="border-top:1px solid #000" align="right"><b>'.$this->formatCash($deduc_itw).'</b></td>
                        <td class="bd2" style="border-top:1px solid #000" align="right"><b>'.$this->formatCash($deduc_total).'</b></td>
                    </tr>
                    </table>
                    

                    <div class="page-break"></div>';
                    
        }

        $row .= '<table width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                    <td style="border : 1px solid #FFF;font-size:12px;" align="center">
                            PAYMENT FOR MAGNA CARTA(MC) BENEFITS<br/>
                            per DBM-DOST Joint Circular No. 1, s. 2013, dated June 23, 2013 
                            <br/>
                    </td>
                    </tr>
                    <tr>
                        <td><center><h5>For the month of '.$mon2.' '.request()->print_mc_year.'</h5></center></td>
                    </tr>
                </table>
                <table width="100%" cellspacing="0" cellpadding="2">
                <tr>
                        <td class="bd1" colspan="2"></td>
                        <td class="bd1" align="right"><b>Basic Salary</b></td>
                        <td class="bd1" align="right"><b>LP Current</b></td>
                        <td class="bd1" align="right"><b>SA</b></td>
                        <td class="bd1" align="right"><b>LA</b></td>
                        <td class="bd1" align="right"><b>ITW</b></td>
                        <td class="bd1" align="right"><b>Deductions</b></td>
                        <td class="bd1" align="right"><b>HP</b></td>
                        <td class="bd1" align="right"><b>NET MC</b></td>
                    </tr>
                    <tr>
                    <td class="bd2" style="border-top:1px solid #000" colspan="2"><b>*GRANDTOTALS</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($grandtotal_basic).'</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($grandtotal_lp).'</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($grandtotal_sa).'</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($grandtotal_la).'</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($grandtotal_itw).'</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($granddeduc_total).'</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($grandtotal_hp).'</b></td>
                    <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($grandtotal_net).'</b></td>
                </tr></table>
                <br>
                <br>
                <br>
                <br>
                <table width="100%" cellspacing="0" cellpadding="2">
                <tr valign="top">
                    <td>
                        A. PREPARED BY: <br/><br/><br/><br/>
                        <b>ROMMEL V. VISPERAS</b><br>Administrative Assistant III<br/><br/><br/><br/>
                        C. CERTIFIED: Supporting documents complete<br/>and cash available in the amount of ________________________<br/><br/><br/><br/>
                        <b>ABEGAIL GRACE M. MARALIT</b><br>Accountant III<br/><br/><br/><br/><br/>
                        OBR No. ________________________<br/>
                        DATE ________________________<br/>
                        JEV No. ________________________<br/>
                        DATE    ________________________<br/>
                    </td>

                    <td>
                        B. CERTIFIED CORRECT: <br/><br/><br/><br/>
                        <b>GEORGIA M. LAWAS</b><br>Administrative Officer V<br/><br/><br/><br/>
                        D. Approved for payment:____________________________<br/><br/><br/><br/>
                        <b>REYNALDO V. EBORA</b><br>Executive Director<br/><br/><br/><br/>
                        E. CERTIFIED CORRECT Each employee whose name appears above has been paid the<br/>amount indicated opposite on his/her name:<br/><br/><br/><br/>
                        <b>HEIDELITA A. RAMOS</b><br>Cashier<br/><br/><br/><br/>
                        
                    </td>

                </tr>
                </table>
                <div class="page-break"></div>';


                $row .= '<table width="100%" cellspacing="0" cellpadding="2">
                <tr>
                <td style="border : 1px solid #FFF;font-size:12px;" align="center">
                        PAYMENT FOR MAGNA CARTA(MC) BENEFITS<br/>
                        per DBM-DOST Joint Circular No. 1, s. 2013, dated June 23, 2013 
                        <br/>
                </td>
                </tr>
                <tr>
                    <td><center><h5>(Deductions) For the month of '.$mon2.' '.request()->print_mc_year.'</h5></center></td>
                </tr>
            </table>
            <table width="100%" cellspacing="0" cellpadding="2">
            <tr>
                    <td class="bd1" colspan="2"><b></b></td>
                    <td class="bd1" align="right"><b>HMO</b></td>
                    <td class="bd1" align="right"><b>GSIS</b></td>
                    <td class="bd1" align="right"><b>PMPC</b></td>
                    <td class="bd1" align="right"><b>CDC</b></td>
                    <td class="bd1" align="right"><b>GFAL</b></td>
                    <td class="bd1" align="right"><b>Landbank</b></td>
                    <td class="bd1" align="right"><b>ITW</b></td>
                    <td class="bd1" align="right"><b>Total Deduction</b></td>
            </tr>
                <tr>
                <td class="bd2" style="border-top:1px solid #000" colspan="2"><b>*GRANDTOTALS</b></td>
                <td class="bd2" style="border-top:1px solid #000" align="right"><b>'.$this->formatCash($granddeduc_hmo).'</b></td>
                <td class="bd2" style="border-top:1px solid #000" align="right"><b>'.$this->formatCash($granddeduc_gsis).'</b></td>
                <td class="bd2" style="border-top:1px solid #000" align="right"><b>'.$this->formatCash($granddeduc_pmpc).'</b></td>
                <td class="bd2" style="border-top:1px solid #000" align="right"><b>'.$this->formatCash($granddeduc_cdc).'</b></td>
                <td class="bd2" style="border-top:1px solid #000" align="right"><b>'.$this->formatCash($granddeduc_gfal).'</b></td>
                <td class="bd2" style="border-top:1px solid #000" align="right"><b>'.$this->formatCash($granddeduc_landbank).'</b></td>
                <td class="bd2" style="border-top:1px solid #000" align="right"><b>'.$this->formatCash($granddeduc_itw).'</b></td>
                <td class="bd2" style="border-top:1px solid #000" align="right"><b>'.$this->formatCash($granddeduc_total).'</b></td>
            </tr></table>
            <div class="page-break"></div>';



        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML('<!DOCTYPE html>
                            <html>
                            <head>
                              <title>HRMIS - MC</title>
                              <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                            </head>
                            <style type="text/css">
                                body
                                {
                                    font-family:Helvetica;
                                }
                                th,td
                                {
                                    border:0px solid #555;
                                    font-size:11px;
                                }

                                .page-break {
                                    page-break-after: always;
                                   }
                                .bd1
                                {
                                    border-top:1px solid #000;
                                    border-bottom:1px solid #000;
                                    font-size:12px;
                                }

                                .bd2
                                {
                                    font-size:12px;
                                }
    
                            </style>
                            <body>
                                '.$row.'
                            
                            </body>
                            </html>')
        ->setPaper('legal', 'landscape');
        return $pdf->stream();
    }

    public function printMCTextfile($mon,$yr)
    {
        ini_set('memory_limit', '512M');

        $date = $mon .' '.$yr;

        $mon2 = date('F',mktime(0, 0, 0, $mon, 10));

        //GET ALL DIVISION
        $row = "";
        $division = getDivisionList();

        $grandtotal_basic = 0;
        $grandtotal_lp= 0;
        $grandtotal_sa = 0;
        $grandtotal_la = 0;
        $grandtotal_deduc = 0;
        $grandtotal_itw= 0;
        $grandtotal_hp = 0;

        $granddeduc_hmo = 0;
        $granddeduc_gsis = 0;
        $granddeduc_pmpc = 0;
        $granddeduc_cdc = 0;
        $granddeduc_gfal = 0;
        $granddeduc_landbank = 0;
        $granddeduc_itw = 0;
        $granddeduc_total = 0;
        $grandtotal_net = 0;


        $textfile = "";
        
        foreach ($division as $key => $value) 
        {

            //GET EMPLOYEE
            $emp = getStaffDivisionMC($value->division_id);
            

            $staff = "";
            $staff2 = "";

            $total_basic = 0;
            $total_lp = 0;
            $total_sa = 0;
            $total_la = 0;
            $total_deduc = 0;
            $total_itw = 0;
            $total_hp = 0;
            $total_net = 0;
            


            $deduc_hmo = 0;
            $deduc_gsis = 0;
            $deduc_pmpc = 0;
            $deduc_cdc = 0;
            $deduc_gfal = 0;
            $deduc_landbank = 0;
            $deduc_itw = 0;
            $deduc_total = 0;


            foreach ($emp as $ky => $vl) {

                //GET INFO
                $info = $this->getInfoMC($mon,$yr,$vl->username);
                // $info = App\Payroll\PrevInfo::where('fldMonth',request()->payrollmon)->where('fldYear',request()->payrollyear)->where('fldEmpCode',$vl->username)->first();

                foreach ($info as $k => $v) {

                    $plantilla = getPlantillaInfo($vl->username);

                    //SA
                    // $m = 0;
                    // $mcd = App\MCday::where('process_code',$v->process_code)->get();

                    // foreach($mcd as $key => $v3) {
                    //         $dt = date('M d, y',strtotime($v3->req_date_from));
                    //         if($v3->req_date_from != $v3->req_date_to)
                    //         {
                    //             $dt = date('M d, y',strtotime($v3->req_date_from))." - ".date('M d, y',strtotime($v3->req_date_to));
                    //         }
                    //         if($v3->req_type != 'Travel')
                    //         {
                    //             $m += $v3->req_deduc;
                    //         }
                            
                    // }

                    //GET LEAVE

                    //COUNT NO. LEAVES
                    if($mon == 1)
                    {
                        $m_mon = 12;
                        $m_year = $yr - 1;
                    }
                    else
                    {
                        $m_mon = $mon - 1;
                        $m_year = $yr;
                    }

                    //TO
                    //MULTIPLE DATE
                    // $t1_total = App\RequestTO::where('user_id',$vl->id)->whereNull('parent_leave')->whereNotNull('parent_leave_code')->whereNotIn('leave_id',[5,16])->where('leave_action_status','Approved')->whereMonth('leave_date_from',$m_mon)->whereYear('leave_date_from',$m_year)->count();

                    // //SINGLE DATE
                    // $t2_total = App\RequestTO::where('user_id',$vl->id)->where('parent','YES')->whereIn('leave_deduction',[1,0.5])->whereNotIn('leave_id',[5,16])->where('leave_action_status','Approved')->whereMonth('leave_date_from',$m_mon)->whereYear('leave_date_from',$m_year)->sum('leave_deduction');

                    // $t_total = $t1_total + $t2_total;


                    //LEAVE
                    //MULTIPLE DATE
                    // $l1_total = App\Request_leave::where('user_id',$vl->id)->whereNull('parent_leave')->whereNotNull('parent_leave_code')->whereNotIn('leave_id',[5,16])->where('leave_action_status','Approved')->whereMonth('leave_date_from',$m_mon)->whereYear('leave_date_from',$m_year)->count();

                    // //SINGLE DATE
                    // $l2_total = App\Request_leave::where('user_id',$vl->id)->where('parent','YES')->whereIn('leave_deduction',[1,0.5])->whereNotIn('leave_id',[5,16])->where('leave_action_status','Approved')->whereMonth('leave_date_from',$m_mon)->whereYear('leave_date_from',$m_year)->sum('leave_deduction');

                    // $l_total = $l1_total + $l2_total;


                    
                    //GET SA
                    // $sa = App\Employee_sala::where('process_code',$v->process_code)->first();
                    
                    // if($sa)
                    // {
                    //     //$t_sa = $sa['sa_amt'] - ($l_total * 150);
                    //     //$t_la = $sa['la_amt'] - (($sa['la_amt'] / 22) * $l_total);
                    //     $t_sa = $v->sa;
                    //     $t_la = $v->la;
                    // }
                    // else
                    // {
                    //     $t_sa = 0;
                    //     $t_la = 0;
                    // }

                    $t_sa = $v->sa;
                    $t_la = $v->la;

                    //HP
                    $t_hp = $v->salary * $v->hprate;
                    
                    if($vl->employment_id == 15)
                    {
                        $t_sa = 0;
                        $t_la = 0;
                        $t_hp = 0;
                    }
                

                    $total_basic += $v->salary;
                    $grandtotal_basic += $v->salary;

                    $total_lp += $v->lp;
                    $grandtotal_lp += $v->lp;

                    $total_sa += $t_sa;
                    $grandtotal_sa += $t_sa;

                    $total_la += $t_la;
                    $grandtotal_la += $t_la;
                    
                    $total_itw += $v->itw;
                    $grandtotal_itw += $v->itw;

                    $total_hp += $t_hp;
                    $grandtotal_hp += $t_hp;

                    //DEDUCTIONS
                    $total_deduc += getTotalMCDeduc('total',$vl->id,$mon,$yr) + $v->itw;
                    $grandtotal_deduc += getTotalMCDeduc('total',$vl->id,$mon,$yr) + $v->itw;

                    $net_mc = ($v->lp + $t_sa + $t_la + $t_hp) - (getTotalMCDeduc('total',$vl->id,$mon,$yr) + $v->itw);
                    $total_net += ($v->lp + $t_sa + $t_la + $t_hp) - (getTotalMCDeduc('total',$vl->id,$mon,$yr) + $v->itw);
                    $grandtotal_net += ($v->lp + $t_sa + $t_la + $t_hp) - (getTotalMCDeduc('total',$vl->id,$mon,$yr) + $v->itw);


                    $atm = App\Employee_addinfo::where('user_id',$vl->id)->first();
                    $atm = $atm['addinfo_atm'];

                    $txt1 = $atm.''.$vl->lname.','. $vl->fname.' '. $vl->mname[0].' '.$vl->exname;
                    $atmCode = "1890000";
                    
                    $net_mc = number_format($net_mc,2);
                    $net_mc = str_replace(array('.', ','), '' , $net_mc);

                    
                                $txtctr = strlen($txt1);

                                //SPACES IN BETWEEN TO REACH 73 characters
                                $chrs = 73 - ($txtctr + 23);
                                $spaces = str_repeat(' ', $chrs);

                                //GET MC

                                //ZERO BEFORE SALARY
                                $net_mc = str_replace(array('.', ','), '' , $net_mc);
                                $txt2 = $net_mc.$atmCode."1";
                                $txtctr = strlen($txt2);
                                $chrs = 23 - ($txtctr);
                                $zeros = str_repeat('0', $chrs);

                                $textfile .= $txt1.$spaces.$zeros.$txt2.str_repeat(' ', 7)."\n";

                //     $staff .= '
                //     <tr>
                //     <td class="bd2">'.$vl->username.'</td>
                //     <td class="bd2" align="left">'.strtoupper($vl->lname.', '.$vl->fname.' '.substr($vl->mname,0,1).".").'</td>
                //     <td class="bd2" align="right">'.$this->formatCash($v->salary).'</td>
                //     <td class="bd2" align="right">'.$this->formatCash($v->lp).'</td>
                //     <td class="bd2" align="right">'.$this->formatCash($t_sa).'</td>
                //     <td class="bd2" align="right">'.$this->formatCash($t_la).'</td>
                //     <td class="bd2" align="right">'.$v->hprate.'</td>
                //     <td class="bd2" align="right">'.$this->formatCash($t_hp).'</td>
                //     <td class="bd2" align="right">'.$this->formatCash(getTotalMCDeduc('total',$vl->id,request()->print_mc_mon,request()->print_mc_year) + $v->itw).'</td>
                //     <td class="bd2" align="right">'.$this->formatCash($net_mc).'</td>
                // </tr>';
                
                

                $deduc_hmo += $v->hmo;
                $granddeduc_hmo += $v->hmo;

                $deduc_gsis += $v->gsis;
                $granddeduc_gsis += $v->gsis;

                $deduc_pmpc += $v->pmpc;
                $granddeduc_pmpc += $v->pmpc;

                $deduc_cdc += $v->cdc;
                $granddeduc_cdc += $v->cdc;

                $deduc_gfal += $v->gfal;
                $granddeduc_gfal += $v->gfal;

                $deduc_landbank += $v->landbank;
                $granddeduc_landbank += $v->landbank;

                $deduc_itw += $v->itw;
                $granddeduc_itw += $v->itw;
                

                $deduc_total += $v->hmo + $v->gsis + $v->pmpc + $v->cdc + $v->gfal + $v->landbank + $v->itw;

                $granddeduc_total += $v->hmo + $v->gsis + $v->pmpc + $v->cdc + $v->gfal + $v->landbank + $v->itw;

                // $staff2 .= '
                //     <tr>
                //     <td class="bd2">'.$vl->username.'</td>
                //     <td class="bd2" align="left">'.strtoupper($vl->lname.', '.$vl->fname.' '.substr($vl->mname,0,1).".").'</td>
                //     <td class="bd2" align="right">'.$this->formatCash($v->hmo).'</td>
                //     <td class="bd2" align="right">'.$this->formatCash($v->gsis).'</td>
                //     <td class="bd2" align="right">'.$this->formatCash($v->pmpc).'</td>
                //     <td class="bd2" align="right">'.$this->formatCash($v->cdc).'</td>
                //     <td class="bd2" align="right">'.$this->formatCash($v->gfal).'</td>
                //     <td class="bd2" align="right">'.$this->formatCash($v->landbank).'</td>
                //     <td class="bd2" align="right">'.$this->formatCash($v->itw).'</td>
                //     <td class="bd2" align="right">'.$this->formatCash($v->hmo + $v->gsis + $v->pmpc + $v->cdc + $v->gfal + $v->landbank + $v->itw).'</td>
                // </tr>';
                }
                
            }

            // $staff .= '
            //         <tr>
            //         <td class="bd2" style="border-top:1px solid #000" colspan="2"><b>*DIVISION TOTALS*</b></td>
            //         <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($total_basic).'</b></td>
            //         <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($total_lp).'</b></td>
            //         <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($total_sa).'</b></td>
            //         <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($total_la).'</b></td>
            //         <td class="bd2" align="right" style="border-top:1px solid #000"><b></b></td>
            //         <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($total_hp).'</b></td>
            //         <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($total_deduc).'</b></td>
            //         <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($total_net).'</b></td>
            //     </tr>';


            // $row .= '
            //     <table width="100%" cellspacing="0" cellpadding="2">
            //         <tr>
            //         <td style="border : 1px solid #FFF;font-size:12px;" align="center">
            //                 PAYMENT FOR MAGNA CARTA(MC) BENEFITS<br/>
            //                 per DBM-DOST Joint Circular No. 1, s. 2013, dated June 23, 2013 
            //                 <br/>
            //         </td>
            //         </tr>
            //         <tr>
            //             <td><center><h5>For the month of '.$mon2.' '.request()->print_mc_year.'</h5></center></td>
            //         </tr>
            //     </table>
            //         <table width="100%" cellspacing="0" cellpadding="2">
            //         <tr>
            //             <td colspan="10"><b>Division : '.$value->division_desc.'</td>
            //         </tr>
            //         <tr>
            //             <td class="bd1"><b>ID</b></td>
            //             <td class="bd1" align="left"><b>Employee</b></td>
            //             <td class="bd1" align="right"><b>Basic Salary</b></td>
            //             <td class="bd1" align="right"><b>LP Current</b></td>
            //             <td class="bd1" align="right"><b>SA</b></td>
            //             <td class="bd1" align="right"><b>LA</b></td>
            //             <td class="bd1" align="right"><b>HP Rate</b></td>
            //             <td class="bd1" align="right"><b>HP</b></td>
            //             <td class="bd1" align="right"><b>Deductions</b></td>
            //             <td class="bd1" align="right"><b>Net MC</b></td>
            //         </tr>
            //         '.$staff.'
            //         </table>
            //         <div class="page-break"></div>';

            //FOR DEDUCTION NAMAN
            // $row .= '
            //     <table width="100%" cellspacing="0" cellpadding="2">
            //         <tr>
            //         <td style="border : 1px solid #FFF;font-size:12px;" align="center">
            //                 PAYMENT FOR MAGNA CARTA(MC) BENEFITS<br/>
            //                 per DBM-DOST Joint Circular No. 1, s. 2013, dated June 23, 2013 
            //                 <br/>
            //         </td>
            //         </tr>
            //         <tr>
            //             <td><center><h5>(Deductions) For the month of '.$mon2.' '.request()->print_mc_year.'</h5></center></td>
            //         </tr>
            //     </table>
            //         <table width="100%" cellspacing="0" cellpadding="2">
            //         <tr>
            //             <td colspan="10"><b>Division : '.$value->division_desc.'</td>
            //         </tr>
            //         <tr>
            //             <td class="bd1"><b>ID</b></td>
            //             <td class="bd1" align="left"><b>Employee</b></td>
            //             <td class="bd1" align="right"><b>HMO</b></td>
            //             <td class="bd1" align="right"><b>GSIS</b></td>
            //             <td class="bd1" align="right"><b>PMPC</b></td>
            //             <td class="bd1" align="right"><b>CDC</b></td>
            //             <td class="bd1" align="right"><b>GFAL</b></td>
            //             <td class="bd1" align="right"><b>Landbank</b></td>
            //             <td class="bd1" align="right"><b>ITW</b></td>
            //             <td class="bd1" align="right"><b>Total Deduction</b></td>
            //         </tr>
            //         '.$staff2.'
            //         <tr>
            //             <td class="bd2" style="border-top:1px solid #000" colspan="2"><b>*DIVISION TOTALS*</b></td>
            //             <td class="bd2" style="border-top:1px solid #000" align="right"><b>'.$this->formatCash($deduc_hmo).'</b></td>
            //             <td class="bd2" style="border-top:1px solid #000" align="right"><b>'.$this->formatCash($deduc_gsis).'</b></td>
            //             <td class="bd2" style="border-top:1px solid #000" align="right"><b>'.$this->formatCash($deduc_pmpc).'</b></td>
            //             <td class="bd2" style="border-top:1px solid #000" align="right"><b>'.$this->formatCash($deduc_cdc).'</b></td>
            //             <td class="bd2" style="border-top:1px solid #000" align="right"><b>'.$this->formatCash($deduc_gfal).'</b></td>
            //             <td class="bd2" style="border-top:1px solid #000" align="right"><b>'.$this->formatCash($deduc_landbank).'</b></td>
            //             <td class="bd2" style="border-top:1px solid #000" align="right"><b>'.$this->formatCash($deduc_itw).'</b></td>
            //             <td class="bd2" style="border-top:1px solid #000" align="right"><b>'.$this->formatCash($deduc_total).'</b></td>
            //         </tr>
            //         </table>
                    

            //         <div class="page-break"></div>';
                    
        }

        // $row .= '<table width="100%" cellspacing="0" cellpadding="2">
        //             <tr>
        //             <td style="border : 1px solid #FFF;font-size:12px;" align="center">
        //                     PAYMENT FOR MAGNA CARTA(MC) BENEFITS<br/>
        //                     per DBM-DOST Joint Circular No. 1, s. 2013, dated June 23, 2013 
        //                     <br/>
        //             </td>
        //             </tr>
        //             <tr>
        //                 <td><center><h5>For the month of '.$mon2.' '.request()->print_mc_year.'</h5></center></td>
        //             </tr>
        //         </table>
        //         <table width="100%" cellspacing="0" cellpadding="2">
        //         <tr>
        //                 <td class="bd1" colspan="2"></td>
        //                 <td class="bd1" align="right"><b>Basic Salary</b></td>
        //                 <td class="bd1" align="right"><b>LP Current</b></td>
        //                 <td class="bd1" align="right"><b>SA</b></td>
        //                 <td class="bd1" align="right"><b>LA</b></td>
        //                 <td class="bd1" align="right"><b>ITW</b></td>
        //                 <td class="bd1" align="right"><b>Deductions</b></td>
        //                 <td class="bd1" align="right"><b>HP</b></td>
        //                 <td class="bd1" align="right"><b>NET MC</b></td>
        //             </tr>
        //             <tr>
        //             <td class="bd2" style="border-top:1px solid #000" colspan="2"><b>*GRANDTOTALS</b></td>
        //             <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($grandtotal_basic).'</b></td>
        //             <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($grandtotal_lp).'</b></td>
        //             <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($grandtotal_sa).'</b></td>
        //             <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($grandtotal_la).'</b></td>
        //             <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($grandtotal_itw).'</b></td>
        //             <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($granddeduc_total).'</b></td>
        //             <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($grandtotal_hp).'</b></td>
        //             <td class="bd2" align="right" style="border-top:1px solid #000"><b>'.$this->formatCash($grandtotal_net).'</b></td>
        //         </tr></table>
        //         <br>
        //         <br>
        //         <br>
        //         <br>
        //         <table width="100%" cellspacing="0" cellpadding="2">
        //         <tr valign="top">
        //             <td>
        //                 A. PREPARED BY: <br/><br/><br/><br/>
        //                 <b>ROMMEL V. VISPERAS</b><br>Administrative Assistant III<br/><br/><br/><br/>
        //                 C. CERTIFIED: Supporting documents complete<br/>and cash available in the amount of ________________________<br/><br/><br/><br/>
        //                 <b>ABEGAIL GRACE M. MARALIT</b><br>Accountant III<br/><br/><br/><br/><br/>
        //                 OBR No. ________________________<br/>
        //                 DATE ________________________<br/>
        //                 JEV No. ________________________<br/>
        //                 DATE    ________________________<br/>
        //             </td>

        //             <td>
        //                 B. CERTIFIED CORRECT: <br/><br/><br/><br/>
        //                 <b>GEORGIA M. LAWAS</b><br>Administrative Officer V<br/><br/><br/><br/>
        //                 D. Approved for payment:____________________________<br/><br/><br/><br/>
        //                 <b>REYNALDO V. EBORA</b><br>Executive Director<br/><br/><br/><br/>
        //                 E. CERTIFIED CORRECT Each employee whose name appears above has been paid the<br/>amount indicated opposite on his/her name:<br/><br/><br/><br/>
        //                 <b>HEIDELITA A. RAMOS</b><br>Cashier<br/><br/><br/><br/>
                        
        //             </td>

        //         </tr>
        //         </table>
        //         <div class="page-break"></div>';


        //         $row .= '<table width="100%" cellspacing="0" cellpadding="2">
        //         <tr>
        //         <td style="border : 1px solid #FFF;font-size:12px;" align="center">
        //                 PAYMENT FOR MAGNA CARTA(MC) BENEFITS<br/>
        //                 per DBM-DOST Joint Circular No. 1, s. 2013, dated June 23, 2013 
        //                 <br/>
        //         </td>
        //         </tr>
        //         <tr>
        //             <td><center><h5>(Deductions) For the month of '.$mon2.' '.request()->print_mc_year.'</h5></center></td>
        //         </tr>
        //     </table>
        //     <table width="100%" cellspacing="0" cellpadding="2">
        //     <tr>
        //             <td class="bd1" colspan="2"><b></b></td>
        //             <td class="bd1" align="right"><b>HMO</b></td>
        //             <td class="bd1" align="right"><b>GSIS</b></td>
        //             <td class="bd1" align="right"><b>PMPC</b></td>
        //             <td class="bd1" align="right"><b>CDC</b></td>
        //             <td class="bd1" align="right"><b>GFAL</b></td>
        //             <td class="bd1" align="right"><b>Landbank</b></td>
        //             <td class="bd1" align="right"><b>ITW</b></td>
        //             <td class="bd1" align="right"><b>Total Deduction</b></td>
        //     </tr>
        //         <tr>
        //         <td class="bd2" style="border-top:1px solid #000" colspan="2"><b>*GRANDTOTALS</b></td>
        //         <td class="bd2" style="border-top:1px solid #000" align="right"><b>'.$this->formatCash($granddeduc_hmo).'</b></td>
        //         <td class="bd2" style="border-top:1px solid #000" align="right"><b>'.$this->formatCash($granddeduc_gsis).'</b></td>
        //         <td class="bd2" style="border-top:1px solid #000" align="right"><b>'.$this->formatCash($granddeduc_pmpc).'</b></td>
        //         <td class="bd2" style="border-top:1px solid #000" align="right"><b>'.$this->formatCash($granddeduc_cdc).'</b></td>
        //         <td class="bd2" style="border-top:1px solid #000" align="right"><b>'.$this->formatCash($granddeduc_gfal).'</b></td>
        //         <td class="bd2" style="border-top:1px solid #000" align="right"><b>'.$this->formatCash($granddeduc_landbank).'</b></td>
        //         <td class="bd2" style="border-top:1px solid #000" align="right"><b>'.$this->formatCash($granddeduc_itw).'</b></td>
        //         <td class="bd2" style="border-top:1px solid #000" align="right"><b>'.$this->formatCash($granddeduc_total).'</b></td>
        //     </tr></table>
        //     <div class="page-break"></div>';


            $folder = "MC-PAYROLL";

            Storage::disk('payroll')->makeDirectory($folder);

            $fsMgr = new FilesystemManager(app());
            // local disk
            $localDisk = $fsMgr->createLocalDriver(['root' => storage_path('app/payroll/'.$folder)]);

            $filename = 'MC-'.time().'.txt';
            $localDisk->put($filename, $textfile);
          
            $myFile = storage_path('app/payroll/'.$folder."/".$filename);
            $headers = ['Content-Type: text/plain'];
            $newName = $filename;
              
            return response()->download($myFile, $newName, $headers);

            echo "Your download will start shortly..";
    }

    public function getInfo($mon,$yr,$empcode)
    {
        $info = collect(App\Payroll\PrevInfo::where('fldMonth',$mon)->where('fldYear',$yr)->where('fldEmpCode',$empcode)->get());
        return $info->all();
    }


    public function PrevTbl($mon,$yr,$empcode,$tbl)
    {
        switch($tbl)
        {
            case "mandadeduc":

            break;
        }
    }

    public function getInfoMC($mon,$yr,$empcode)
    {
        $info = collect(App\Payroll\MC::where('payroll_mon',$mon)->where('payroll_yr',$yr)->where('empcode',$empcode)->get());
        return $info->all();
    }

    public function checkNull($val)
    {
        if($val)
        {
            return $val;
        }
        else
        {
            return "-";
        }
    }

    public function formatCash($val)
    {
        if($val == 0 || $val == null || $val == "")
        {
            return "-";
        }
        else
        {
            return number_format($val,2);
        }
    }


    public function getDeduc($type,$username)
    {
        if($type == 'manda')
        {
            $deductions = getDeductions($username);
        }
    }

    public function printRemittance()
    {
        ini_set('memory_limit', '512M');

        $layout = "portrait";
        $paper = "A4";
        $maxrow = 46;

        $date = request()->payroll_mon .' '.request()->payroll_year;

        $mon2 = date('F',mktime(0, 0, 0, request()->payroll_mon, 10));

        $title = "";
        $head = "";
        $foot = "";
        $rows = "";
        //FOR SALARY
        if(request()->report_type == 1)
        {
                        //TITLE
                        switch(request()->remit_report)
                        {
                            case 1:
                                $title = "Government Service Insurance System";
                                $head = "<tr><td align='center' class='hd' style='width:5%;'><b>ID</b></td><td class='hd' align='center' style='width:30%;'><b>Employee</b></td><td class='hd' align='right'><b>Basic Salary</b></td><td class='hd' align='right'><b>PS</b></td><td class='hd' align='right'><b>GS</b></td><td class='hd' align='right'><b>EC</b></td><td class='hd' align='right'><b>Policy</b></td><td class='hd' align='right'><b>Conso</b></td><td class='hd' align='right'><b>EML</b></td><td class='hd' align='right'><b>Educ</b></td><td class='hd' align='right'><b>Opt Policy</b></td><td class='hd' align='right'><b>CP</b></td><td class='hd' align='right'><b>MP</b></td><td class='hd' align='right'><b>GFAL</b></td><td class='hd' align='right'><b>Opt Ins Prem</b></td><td class='hd' align='right'><b>Total</b></td></tr>";
                                $cols = 14;
                                $layout = "landscape";
                                $maxrow = 28;
                                $paper = "legal";
                            break;
            
                            case 2:
                                $title = "Home Development Mutual Fund";
                                $head = "<tr><td align='center' class='hd' style='width:5%;'><b>ID</b></td><td class='hd' align='center' style='width:30%;'><b>Employee</b></td><td class='hd' align='right'><b>HMDF(PS)</b></td><td class='hd' align='right'><b>HMDF(GS)</b></td><td class='hd' align='right'><b>HMDF II</b></td><td class='hd' align='right'><b>HMDF MPL</b></td><td class='hd' align='right'><b>HMDF Housing</b></td><td class='hd' align='right'><b>Total</b></td></tr>";
                                $cols = 6;
                            break;
            
                            case 3:
                                $title = "PCAARRD Multipurpose Cooperative";
                                $head = "<tr><td align='center' class='hd' style='width:5%;'><b>ID</b></td><td class='hd' align='center' style='width:30%;'><b>Employee</b></td><td class='hd' align='right'><b>PMPC HMO</b></td><td class='hd' align='right'><b>PMPC FD</b></td><td class='hd' align='right'><b>PMPC SD</b></td><td class='hd' align='right'><b>PMPC Loan</b></td></td><td class='hd' align='right'><b>Total</b></td></tr>";
                                $cols = 5;
                            break;
            
                            case 4:
                                $title = "Laguna Prime Multipurpose Cooperative";
                                $head = "<tr><td align='center' class='hd' style='width:5%;'><b>ID</b></td><td class='hd' align='center' style='width:30%;'><b>Employee</b></td><td class='hd' align='right'><b>LPMC FD</b></td><td class='hd' align='right'><b>LPMC SD</b></td><td class='hd' align='right'><b>LPMC Loan</b></td></td><td class='hd' align='right'><b>Total</b></td></tr>";
                                $cols = 4;
                            break;
            
                            case 5:
                                $title = "Land Bank of the Philippines";
                                $head = "<tr><td align='center' class='hd' style='width:5%;'><b>ID</b></td><td class='hd' align='center' style='width:30%;'><b>Employee</b></td><td class='hd' align='right'><b>Loan</b></td></tr>";
            
                                $cols = 1;
                            break;

                            case 7:
                                $title = "BIR/TAX";
                                $head = "<tr><td align='center' class='hd' style='width:5%;'><b>ID</b></td><td class='hd' align='center' style='width:30%;'><b>Employee</b></td><td class='hd' align='right'><b>Amount</b></td></tr>";
            
                                $cols = 1;
                            break;
            
                            case 6:
                                $title = "OTHERS";
                                $head = "<tr><td align='center' class='hd' style='width:5%;'><b>ID</b></td><td class='hd' align='center' style='width:30%;'><b>Employee</b></td><td class='hd' align='right'><b>PCAARRD Housing</b></td></tr>";
                                $cols = 1;
                            break;
                        }
            
                        if(request()->remit_deduc == 1)
                        {
                            $emp = App\Payroll\RemitPrevSalary::where('fldMonth',request()->payroll_mon)->where('fldYear',request()->payroll_year)->orderBy('lname')->orderBy('fname')->get();
                            $rows = "";
                            if($emp)
                            {
                                //$loan = $this->getRemittance($users->username,2,request()->payroll_mon,request()->payroll_year,13);
                                if($cols > 1)
                                {
                                    //GSIS
                                    $grand_gsis_salary = 0;
                                    $grand_gsis_gs = 0;
                                    $grand_gsis_ps = 0;
                                    $grand_gsis_ec = 0;
                                    $grand_gsis_policy = 0;
                                    $grand_gsis_conso = 0;
                                    $grand_gsis_eml = 0;
                                    $grand_gsis_educ = 0;
                                    $grand_gsis_policy2 = 0;
                                    $grand_gsis_cp = 0;
                                    $grand_gsis_mp = 0;
                                    $grand_gsis_gfal = 0;
                                    $grand_gsis_ins_prem = 0;
                                    $grand_gsis_total = 0;
            
                                    //HMDF
                                    $grand_hmdf_ps = 0;
                                    $grand_hmdf_gs = 0;
                                    $grand_hmdf_2 = 0;
                                    $grand_hmdf_mpl = 0;
                                    $grand_hmdf_hs = 0;
                                    $grand_hmdf_total = 0;
                    
                                    //PMPC
                                    $grand_pmpc_hmo = 0;
                                    $grand_pmpc_fd = 0;
                                    $grand_pmpc_sd = 0;
                                    $grand_pmpc_loan = 0;
                                    $grand_pmpc_total = 0;
                    
                                    //LPMC
                                    $grand_lpmc_hmo = 0;
                                    $grand_lpmc_fd = 0;
                                    $grand_lpmc_sd = 0;
                                    $grand_lpmc_loan = 0;
                                    $grand_lpmc_total = 0;

                                    //PCAARRD HOUSING
                                    $grand_pcaarrd_housing = 0;
                                    
                                    $ctr = 1;
                                    $pgbreak = 1;
                                    foreach ($emp as $key => $users) 
                                    {
            
                                        $total_row = 0;
                                        $rows2 = "";
            
                                        $basic = getPlantillaInfo($users->username);
            
            
                                        for ($i=1; $i <= $cols; $i++)
                                        {
                                            //$loan = 0;
                                            switch(request()->remit_report)
                                            {
                                                case 1:
                                                    switch ($i) {
                                                        case 1:
                                                            //BASIC SALARY
                                                            $loan = $basic['plantilla_salary'];
                                                            $grand_gsis_salary += $loan;
                                                        break;
                                                        case 2:
                                                            //PS
                                                            $loan = $basic['plantilla_salary'] * 0.09;
                                                            $total_row += $loan;
                                                            $grand_gsis_ps+= $loan;
                                                        break;
                                                        case 3:
                                                            //GS
                                                            $loan = $basic['plantilla_salary'] * 0.12;
                                                            $total_row += $loan;
                                                            $grand_gsis_gs += $loan;
                                                        break;
            
                                                        case 4:
                                                            //EC
                                                            $loan = 100;
                                                            $total_row += $loan;
                                                            $grand_gsis_ec += $loan;
                                                        break;
            
                                                        case 5:
                                                            //Policy
                                                            $loan = $this->getRemittance($users->username,2,request()->payroll_mon,request()->payroll_year,1,"305");
                                                            $total_row += $loan;
                                                            $grand_gsis_policy += $loan;
                                                        break;
                                                        case 6:
                                                            //Conso
                                                            $loan = $this->getRemittance($users->username,2,request()->payroll_mon,request()->payroll_year,1,"319");
                                                            $total_row += $loan;
                                                            $grand_gsis_conso += $loan;
                                                        break;
                                                        case 7:
                                                            //EML
                                                            $loan = $this->getRemittance($users->username,2,request()->payroll_mon,request()->payroll_year,1,"316");
                                                            $total_row += $loan;
                                                            $grand_gsis_eml += $loan;
                                                        break;
                                                        case 8:
                                                            //Educ
                                                            $loan = $this->getRemittance($users->username,2,request()->payroll_mon,request()->payroll_year,1,"315");
                                                            $total_row += $loan;
                                                            $grand_gsis_educ += $loan;
                                                        break;
                                                        case 9:
                                                            //Opt Policy
                                                            $loan = $this->getRemittance($users->username,2,request()->payroll_mon,request()->payroll_year,1,"305A");
                                                            $total_row += $loan;
                                                            $grand_gsis_policy2 += $loan;
                                                        break;
                                                        case 10:
                                                            //CP
                                                            $loan = $this->getRemittance($users->username,2,request()->payroll_mon,request()->payroll_year,1,"319C");
                                                            $total_row += $loan;
                                                            $grand_gsis_cp += $loan;
                                                        break;
                                                        case 11:
                                                            //MP
                                                            $loan = $this->getRemittance($users->username,2,request()->payroll_mon,request()->payroll_year,1,"319B");
                                                            $total_row += $loan;
                                                            $grand_gsis_mp += $loan;
                                                        break;
                                                        case 12:
                                                            //GFAL
                                                            $loan = $this->getRemittance($users->username,2,request()->payroll_mon,request()->payroll_year,1,"319A");
                                                            $total_row += $loan;
                                                            $grand_gsis_gfal += $loan;
                                                        break;
                                                        case 13:
                                                            //INS PREMIUM
                                                            $loan = $this->getRemittance($users->username,2,request()->payroll_mon,request()->payroll_year,1,"303");
                                                            $total_row += $loan;
                                                            $grand_gsis_ins_prem += $loan;
                                                        break;
                                                        
                                                        case 14:
                                                            //TOTAL
                                                            $loan = $total_row;
                                                            $grand_gsis_total += $total_row;
                                                        break;
                                                    }
                    
                                                    
                                                break;
                                                
                                                case 2:
                                                    switch ($i) {
                                                        case 1:
                                                            //PS
                                                            $loan = $this->getRemittance($users->username,1,request()->payroll_mon,request()->payroll_year,4);
                                                            $total_row += $loan;
                                                            $grand_hmdf_ps += $loan;
                                                        break;
                                                        case 2:
                                                            //GS
                                                            $loan = 100;
                                                            $total_row += $loan;
                                                            $grand_hmdf_gs += $loan;
                                                        break;
                                                        case 3:
                                                            //HMDF II
                                                            $loan = $this->getRemittance($users->username,2,request()->payroll_mon,request()->payroll_year,12,"302C");
                                                            $total_row += $loan;
                                                            $grand_hmdf_2 += $loan;
                                                        break;
                                                        case 4:
                                                            //MPL
                                                            $loan = $this->getRemittance($users->username,2,request()->payroll_mon,request()->payroll_year,2,"302A");
                                                            $total_row += $loan;
                                                            $grand_hmdf_mpl += $loan;
                                                        break;
                                                        case 5:
                                                            //HOUSING
                                                            $loan = $this->getRemittance($users->username,2,request()->payroll_mon,request()->payroll_year,2,"302B");
                                                            $total_row += $loan;
                                                            $grand_hmdf_hs += $loan;
                                                        break;
                                                        case 6:
                                                            //TOTAL
                                                            $loan = $total_row;
                                                            $grand_hmdf_total += $total_row;
                                                        break;
                                                    }
                    
                                                    
                                                break;
                    
                                                case 3:
                                                    switch ($i) {
                                                        case 1:
                                                            //HMO
                                                            $loan = $this->getRemittance($users->username,2,request()->payroll_mon,request()->payroll_year,6,933);
                                                            $total_row += $loan;
                                                            $grand_pmpc_hmo += $loan;
                                                        break;
                                                        case 2:
                                                            //FD
                                                            $loan = $this->getRemittance($users->username,2,request()->payroll_mon,request()->payroll_year,6,930);
                                                            $total_row += $loan;
                                                            $grand_pmpc_fd += $loan;
                                                        break;
                                                        case 3:
                                                            //SD
                                                            $loan = $this->getRemittance($users->username,2,request()->payroll_mon,request()->payroll_year,6,932);
                                                            $total_row += $loan;
                                                            $grand_pmpc_sd += $loan;
                                                        break;
                                                        case 4:
                                                            //SD
                                                            $loan = $this->getRemittance($users->username,2,request()->payroll_mon,request()->payroll_year,6,931);
                                                            $total_row += $loan;
                                                            $grand_pmpc_loan += $loan;
                                                        break;
                                                        case 5:
                                                            //TOTAL
                                                            $loan = $total_row;
                                                            $grand_pmpc_total += $total_row;
                                                        break;
                                                    }
                    
                                                    
                                                break;
                    
                                                case 4:
                                                    switch ($i) {
                                                        case 1:
                                                            //FD
                                                            $loan = $this->getRemittance($users->username,2,request()->payroll_mon,request()->payroll_year,5,920);
                                                            $total_row += $loan;
                                                            $grand_lpmc_fd += $loan;
                                                        break;
                                                        case 2:
                                                            //SD
                                                            $loan = $this->getRemittance($users->username,2,request()->payroll_mon,request()->payroll_year,5,922);
                                                            $total_row += $loan;
                                                            $grand_lpmc_sd += $loan;
                                                        break;
                                                        case 3:
                                                            //LOAN
                                                            $loan = $this->getRemittance($users->username,2,request()->payroll_mon,request()->payroll_year,5,921);
                                                            $total_row += $loan;
                                                            $grand_lpmc_loan += $loan;
                                                        break;
                                                        case 4:
                                                            //TOTAL
                                                            $loan = $total_row;
                                                            $grand_lpmc_total += $total_row;
                                                        break;
                                                    }
                    
                                                    
                                                break;
                                            }
                                            
                                            $rows2 .= "<td align='right'>".formatCash($loan)."</td>";
                                            
                                        }
                    
                                        if($users->lname)
                                        {
                                            $lname = $users->lname." ".$users->exname;
                                        }
                                        else
                                        {
                                            $lname = $users->lname;
                                        }
                                        
                                        if($total_row == 0)
                                        {
                                            $rows .= "<tr style='display:none'><td></td><td>".$users->lname." ".$users->fname." ".$users->mname.".</td>".$rows2;
                                        }
                                        else
                                        {
                                            if($pgbreak == $maxrow)
                                            {
            
                                                $rows .= "</tr></table><div class='page-break'></div><table width='100%' cellspacing='0' cellpadding='2'>".$head."<tr><td>".$ctr."</td><td>".$users->lname." ".$users->fname." ".$users->mname.".</td>".$rows2;
                                                $pgbreak = 1;
                                                $ctr++;
                                                
                                            }
                                            else
                                            {
                                                $rows .= "<tr><td>".$ctr."</td><td>".$users->lname." ".$users->fname." ".$users->mname.".</td>".$rows2;
                                                $pgbreak++;
            
                                                $ctr++;
                                            }
                                            
                                        }
                                        
                                        
                                        //GRANDTOTAL
                                        switch(request()->remit_report)
                                        {
                                            case 1:
                                                $foot = "<tr><td align='center' class='ft' style='width:5%;'><b></b></td><td class='ft' align='center' style='width:30%;'><b>GRAND TOTAL</b></td><td class='ft' align='right'><b>".formatCash($grand_gsis_salary)."</b></td><td class='ft' align='right'><b>".formatCash($grand_gsis_ps)."</b></td><td class='ft' align='right'><b>".formatCash($grand_gsis_gs)."</b></td><td class='ft' align='right'><b>".formatCash($grand_gsis_ec)."</b></td><td class='ft' align='right'><b>".formatCash($grand_gsis_policy)."</b></td><td class='ft' align='right'><b>".formatCash($grand_gsis_conso)."</b></td><td class='ft' align='right'><b>".formatCash($grand_gsis_eml)."</b></td><td class='ft' align='right'><b>".formatCash($grand_gsis_educ)."</b></td><td class='ft' align='right'><b>".formatCash($grand_gsis_policy2)."</b></td><td class='ft' align='right'><b>".formatCash($grand_gsis_cp)."</b></td><td class='ft' align='right'><b>".formatCash($grand_gsis_mp)."</b></td><td class='ft' align='right'><b>".formatCash($grand_gsis_gfal)."</b></td><td class='ft' align='right'><b>".formatCash($grand_gsis_ins_prem)."</b></td><td class='ft' align='right'><b>".formatCash($grand_gsis_total)."</b></td></tr>";
                                            break;
                                
                                            case 2:
                                                $foot = "<tr><td align='center' class='ft' style='width:5%;'><b></b></td><td class='ft' align='center' style='width:30%;'><b>GRAND TOTAL</b></td><td class='ft' align='right'><b>".formatCash($grand_hmdf_ps)."</b></td><td class='ft' align='right'><b>".formatCash($grand_hmdf_gs)."</b></td><td class='ft' align='right'><b>".formatCash($grand_hmdf_2)."</b></td><td class='ft' align='right'><b>".formatCash($grand_hmdf_mpl)."</b></td><td class='ft' align='right'><b>".formatCash($grand_hmdf_hs)."</b></td><td class='ft' align='right'><b>".formatCash($grand_hmdf_total)."</b></td></tr>";
                                            break;
                                
                                            case 3:
                                                $foot = "<tr><td align='center' class='ft' style='width:5%;'><b></b></td><td class='ft' align='center' style='width:30%;'><b>GRAND TOTAL</b></td><td class='ft' align='right'><b>".formatCash($grand_pmpc_hmo)."</b></td><td class='ft' align='right'><b>".formatCash($grand_pmpc_fd)."</b></td><td class='ft' align='right'><b>".formatCash($grand_pmpc_sd)."</b></td><td class='ft' align='right'><b>".formatCash($grand_pmpc_loan)."</b></td><td class='ft' align='right'><b>".formatCash($grand_pmpc_total)."</b></td></tr>";
                                            break;
                                
                                            case 4:
                                                $foot = "<tr><td align='center' class='ft' style='width:5%;'><b></b></td><td class='ft' align='center' style='width:30%;'><b>GRAND TOTAL</b></td><td class='ft' align='right'><b>".formatCash($grand_lpmc_fd)."</b></td><td class='ft' align='right'><b>".formatCash($grand_lpmc_sd)."</b></td><td class='ft' align='right'><b>".formatCash($grand_lpmc_loan)."</b></td><td class='ft' align='right'><b>".formatCash($grand_lpmc_total)."</b></td></tr>";
                                            break;
                                        }
                                        
                                    }
                                
                                    $rows .= "</tr>";
                                }
                                else
                                {
                                    $ctr = 1;
                                    $grand = 0;
                                    foreach ($emp as $key => $users) 
                                    {
                                        if($users->lname)
                                        {
                                            $lname = $users->lname." ".$users->exname;
                                        }
                                        else
                                        {
                                            $lname = $users->lname;
                                        }
                                        
                                        $type = 2;
                                        if(request()->remit_report == 5)
                                        {
                                            $org = 13;
                                            $serv = "321";
                                        }
                                        elseif(request()->remit_report == 6)
                                        {
                                            $org = 9;
                                            $serv = "907";
                                        }
                                        else
                                        {
                                            $org = null;
                                            $serv = null;
                                            $type = 3;
                                        }
                                            
                    
                                        $loan = $this->getRemittance($users->username,$type,request()->payroll_mon,request()->payroll_year,$org,$serv);
                    
                                        if($loan > 0)
                                        {
                                            $rows .= "<tr><td>".$ctr."</td><td>".$users->lname." ".$users->fname." ".$users->mname.".</td><td align='right'>".formatCash($loan)."</td></td>";
                                            $grand += $loan;
                                            $ctr++;
                                        }
                                            
                                    }
                    
                                    $foot = "<tr><td align='center' class='ft' style='width:5%;'><b></b></td><td class='ft' align='center' style='width:30%;'><b>GRAND TOTAL</b></td><td class='ft' align='right'><b>".formatCash($grand)."</b></td></tr>";
                                    
                                }
                            }
                            else
                            {
                                $rows = "<center><h1>MONTH NOT YET PROCCESS</h1></center>";
                            }
                        }
        }
        else
        {

            switch(request()->remit_report)
                        {
                            case 1:
                                $title = "Government Service Insurance System";
                                $head = "<tr><td align='center' class='hd' style='width:5%;'><b>ID</b></td><td class='hd' align='center' style='width:30%;'><b>Employee</b></td><td class='hd' align='right'><b>Basic Salary</b></td><td class='hd' align='right'><b>PS</b></td><td class='hd' align='right'><b>GS</b></td><td class='hd' align='right'><b>EC</b></td><td class='hd' align='right'><b>Policy</b></td><td class='hd' align='right'><b>Conso</b></td><td class='hd' align='right'><b>EML</b></td><td class='hd' align='right'><b>Educ</b></td><td class='hd' align='right'><b>Opt Policy</b></td><td class='hd' align='right'><b>CP</b></td><td class='hd' align='right'><b>MP</b></td><td class='hd' align='right'><b>GFAL</b></td><td class='hd' align='right'><b>Total</b></td></tr>";
                                $cols = 13;
                                $layout = "landscape";
                                $maxrow = 28;
                                $paper = "legal";
                            break;
            
                            case 3:
                                $title = "PCAARRD Multipurpose Cooperative";
                                $head = "<tr><td align='center' class='hd' style='width:5%;'><b>ID</b></td><td class='hd' align='center' style='width:30%;'><b>Employee</b></td><td class='hd' align='right'><b>HMO</b></td><td class='hd' align='right'><b>Loan</b></td></td><td class='hd' align='right'><b>Total</b></td></tr>";
                                $cols = 3;
                            break;
            
                            case 4:
                                $title = "Laguna Prime Multipurpose Cooperative";
                                // $head = "<tr><td align='center' class='hd' style='width:5%;'><b>ID</b></td><td class='hd' align='center' style='width:30%;'><b>Employee</b></td><td class='hd' align='right'><b>Loan</b></td></td><td class='hd' align='right'><b>Total</b></td></tr>";
                                // $cols = 2;

                                $head = "<tr><td align='center' class='hd' style='width:5%;'><b>ID</b></td><td class='hd' align='center' style='width:30%;'><b>Employee</b></td><td class='hd' align='right'><b>Loan</b></td></tr>";
                                $cols = 1;
                            break;
            
                            case 5:
                                $title = "Land Bank of the Philippines";
                                $head = "<tr><td align='center' class='hd' style='width:5%;'><b>ID</b></td><td class='hd' align='center' style='width:30%;'><b>Employee</b></td><td class='hd' align='right'><b>Loan</b></td></tr>";
            
                                $cols = 1;
                            break;
            
                            case 6:
                                $title = "OTHERS";
                                $head = "<tr><td align='center' class='hd' style='width:5%;'><b>ID</b></td><td class='hd' align='center' style='width:30%;'><b>Employee</b></td><td class='hd' align='right'><b>PCAARRD Housing</b></td></tr>";
                                $cols = 1;
                            break;
                        }

            //GET REMITTANCE
            $emp = App\Payroll\MCView::where('payroll_mon',request()->payroll_mon)->where('payroll_yr',request()->payroll_year)->orderBy('fullname')->get();

            if($emp)
                            {
                                //$loan = $this->getRemittance($users->username,2,request()->payroll_mon,request()->payroll_year,13);
                                if($cols > 1)
                                {
                                    //GSIS
                                    $grand_gsis_salary = 0;
                                    $grand_gsis_gs = 0;
                                    $grand_gsis_ps = 0;
                                    $grand_gsis_ec = 0;
                                    $grand_gsis_policy = 0;
                                    $grand_gsis_conso = 0;
                                    $grand_gsis_eml = 0;
                                    $grand_gsis_educ = 0;
                                    $grand_gsis_policy2 = 0;
                                    $grand_gsis_cp = 0;
                                    $grand_gsis_mp = 0;
                                    $grand_gsis_gfal = 0;
                                    $grand_gsis_total = 0;
            
                                    //HMDF
                                    $grand_hmdf_ps = 0;
                                    $grand_hmdf_gs = 0;
                                    $grand_hmdf_2 = 0;
                                    $grand_hmdf_mpl = 0;
                                    $grand_hmdf_hs = 0;
                                    $grand_hmdf_total = 0;
                    
                                    //PMPC
                                    $grand_pmpc_hmo = 0;
                                    $grand_pmpc_fd = 0;
                                    $grand_pmpc_sd = 0;
                                    $grand_pmpc_loan = 0;
                                    $grand_pmpc_total = 0;
                    
                                    //LPMC
                                    $grand_lpmc_hmo = 0;
                                    $grand_lpmc_fd = 0;
                                    $grand_lpmc_sd = 0;
                                    $grand_lpmc_loan = 0;
                                    $grand_lpmc_total = 0;
                                    
                                    $ctr = 1;
                                    $pgbreak = 1;
                                    foreach ($emp as $key => $users) 
                                    {
            
                                        $total_row = 0;
                                        $rows2 = "";
            
                                        $basic = getPlantillaInfo($users->empcode);

                                        // if(!$basic)
                                        //     return "TEST ".$users->username;
            
            
                                        for ($i=1; $i <= $cols; $i++)
                                        {
                                            //$loan = 0;
                 
                                            switch(request()->remit_report)
                                            {
                                                case 1:
                                                    switch ($i) {
                                                        case 1:
                                                            //BASIC SALARY
                                                            if(isset($basic['plantilla_salary']))
                                                            {
                                                                $loan = $basic['plantilla_salary'];
                                                                $grand_gsis_salary += $loan;
                                                            }
                                                            else
                                                            {
                                                                return "ERROR  ID : ".$users->id;
                                                            }
                                                            
                                                        break;
                                                        case 2:
                                                            //PS
                                                            $loan = $basic['plantilla_salary'] * 0.09;
                                                            $total_row += $loan;
                                                            $grand_gsis_ps+= $loan;
                                                        break;
                                                        case 3:
                                                            //GS
                                                            $loan = $basic['plantilla_salary'] * 0.12;
                                                            $total_row += $loan;
                                                            $grand_gsis_gs += $loan;
                                                        break;
            
                                                        case 4:
                                                            //EC
                                                            $loan = 100;
                                                            $total_row += $loan;
                                                            $grand_gsis_ec += $loan;
                                                        break;
            
                                                        case 5:
                                                            //Policy
                                                            $loan = $this->getRemittanceMC($users->empcode,2,request()->payroll_mon,request()->payroll_year,1,"305");
                                                            $total_row += $loan;
                                                            $grand_gsis_policy += $loan;
                                                        break;
                                                        case 6:
                                                            //Conso
                                                            $loan = $this->getRemittanceMC($users->empcode,2,request()->payroll_mon,request()->payroll_year,1,"319");
                                                            $total_row += $loan;
                                                            $grand_gsis_conso += $loan;
                                                        break;
                                                        case 7:
                                                            //EML
                                                            $loan = $this->getRemittanceMC($users->empcode,2,request()->payroll_mon,request()->payroll_year,1,"316");
                                                            $total_row += $loan;
                                                            $grand_gsis_eml += $loan;
                                                        break;
                                                        case 8:
                                                            //Educ
                                                            $loan = $this->getRemittanceMC($users->empcode,2,request()->payroll_mon,request()->payroll_year,1,"315");
                                                            $total_row += $loan;
                                                            $grand_gsis_educ += $loan;
                                                        break;
                                                        case 9:
                                                            //Opt Policy
                                                            $loan = $this->getRemittanceMC($users->empcode,2,request()->payroll_mon,request()->payroll_year,1,"305A");
                                                            $total_row += $loan;
                                                            $grand_gsis_policy2 += $loan;
                                                        break;
                                                        case 10:
                                                            //CP
                                                            $loan = $this->getRemittanceMC($users->empcode,2,request()->payroll_mon,request()->payroll_year,1,"319C");
                                                            $total_row += $loan;
                                                            $grand_gsis_cp += $loan;
                                                        break;
                                                        case 11:
                                                            //MP
                                                            $loan = $this->getRemittanceMC($users->empcode,2,request()->payroll_mon,request()->payroll_year,1,"319B");
                                                            $total_row += $loan;
                                                            $grand_gsis_mp += $loan;
                                                        break;
                                                        case 12:
                                                            //GFAL
                                                            $loan = $this->getRemittanceMC($users->empcode,2,request()->payroll_mon,request()->payroll_year,1,"319A");
                                                            $total_row += $loan;
                                                            $grand_gsis_gfal += $loan;
                                                        break;
                                                        
                                                        case 13:
                                                            //TOTAL
                                                            $loan = $total_row;
                                                            $grand_gsis_total += $total_row;
                                                        break;
                                                    }
                    
                                                    
                                                break;
                                                
                    
                                                case 3:
                                                    switch ($i) {
                                                        case 1:
                                                            $loan = $this->getRemittanceMC($users->empcode,2,request()->payroll_mon,request()->payroll_year,'hmo');
                                                            $total_row += $loan;
                                                            $grand_pmpc_hmo += $loan;
                                                        break;
                                                        case 2:
                                                            $loan = $this->getRemittanceMC($users->empcode,2,request()->payroll_mon,request()->payroll_year,'pmpc');
                                                            $total_row += $loan;
                                                            $grand_pmpc_loan += $loan;
                                                        break;
                                                        case 3:
                                                            $loan = $total_row;
                                                            $grand_pmpc_total += $total_row;
                                                        break;
                                                    }
                    
                                                    
                                                break;
                    
                                                case 4:
                                                    switch ($i) {
                                                        case 1:
                                                            //LOAN
                                                            $loan = $this->getRemittanceMC($users->empcode,2,request()->payroll_mon,request()->payroll_year,'cdc');
                                                            $total_row += $loan;
                                                            $grand_lpmc_loan += $loan;
                                                        break;
                                                        case 2:
                                                            //TOTAL
                                                            $loan = $total_row;
                                                            $grand_lpmc_total += $total_row;
                                                        break;
                                                    }
                    
                                                    
                                                break;
                                            }
                                            
                                            $rows2 .= "<td align='right'>".formatCash($loan)."</td>";
                                            
                                        }
                    
                                        if($users->lname)
                                        {
                                            $lname = $users->lname." ".$users->exname;
                                        }
                                        else
                                        {
                                            $lname = $users->lname;
                                        }
                                        
                                        if($total_row == 0)
                                        {
                                            $rows .= "<tr style='display:none'><td></td><td>".$users->fullname."</td>".$rows2;
                                        }
                                        else
                                        {
                                            if($pgbreak == $maxrow)
                                            {
            
                                                $rows .= "</tr></table><div class='page-break'></div><table width='100%' cellspacing='0' cellpadding='2'>".$head."<tr><td>".$ctr."</td><td>".$users->lname." ".$users->fname." ".$users->mname.".</td>".$rows2;
                                                $pgbreak = 1;
                                                $ctr++;
                                                
                                            }
                                            else
                                            {
                                                $rows .= "<tr><td>".$ctr."</td><td>".$users->lname." ".$users->fname." ".$users->mname.".</td>".$rows2;
                                                $pgbreak++;
            
                                                $ctr++;
                                            }
                                            
                                        }
                                        
                                        
                                        //GRANDTOTAL
                                        switch(request()->remit_report)
                                        {
                                            case 1:
                                                $foot = "<tr><td align='center' class='ft' style='width:5%;'><b></b></td><td class='ft' align='center' style='width:30%;'><b>GRAND TOTAL</b></td><td class='ft' align='right'><b>".formatCash($grand_gsis_salary)."</b></td><td class='ft' align='right'><b>".formatCash($grand_gsis_ps)."</b></td><td class='ft' align='right'><b>".formatCash($grand_gsis_gs)."</b></td><td class='ft' align='right'><b>".formatCash($grand_gsis_ec)."</b></td><td class='ft' align='right'><b>".formatCash($grand_gsis_policy)."</b></td><td class='ft' align='right'><b>".formatCash($grand_gsis_conso)."</b></td><td class='ft' align='right'><b>".formatCash($grand_gsis_eml)."</b></td><td class='ft' align='right'><b>".formatCash($grand_gsis_educ)."</b></td><td class='ft' align='right'><b>".formatCash($grand_gsis_policy2)."</b></td><td class='ft' align='right'><b>".formatCash($grand_gsis_cp)."</b></td><td class='ft' align='right'><b>".formatCash($grand_gsis_mp)."</b></td><td class='ft' align='right'><b>".formatCash($grand_gsis_gfal)."</b></td><td class='ft' align='right'><b>".formatCash($grand_gsis_total)."</b></td></tr>";
                                            break;
                                
                                            case 2:
                                                $foot = "<tr><td align='center' class='ft' style='width:5%;'><b></b></td><td class='ft' align='center' style='width:30%;'><b>GRAND TOTAL</b></td><td class='ft' align='right'><b>".formatCash($grand_hmdf_ps)."</b></td><td class='ft' align='right'><b>".formatCash($grand_hmdf_gs)."</b></td><td class='ft' align='right'><b>".formatCash($grand_hmdf_2)."</b></td><td class='ft' align='right'><b>".formatCash($grand_hmdf_mpl)."</b></td><td class='ft' align='right'><b>".formatCash($grand_hmdf_hs)."</b></td><td class='ft' align='right'><b>".formatCash($grand_hmdf_total)."</b></td></tr>";
                                            break;
                                
                                            case 3:
                                                $foot = "<tr><td align='center' class='ft' style='width:5%;'><b></b></td><td class='ft' align='center' style='width:30%;'><b>GRAND TOTAL</b></td><td class='ft' align='right'><b>".formatCash($grand_pmpc_hmo)."</b></td><td class='ft' align='right'><b>".formatCash($grand_pmpc_loan)."</b></td><td class='ft' align='right'><b>".formatCash($grand_pmpc_total)."</b></td></tr>";
                                            break;
                                
                                            case 4:
                                                $foot = "<tr><td align='center' class='ft' style='width:5%;'><b></b></td><td class='ft' align='center' style='width:30%;'><b>GRAND TOTAL</b></td><td class='ft' align='right'><b>".formatCash($grand_lpmc_loan)."</b></td><td class='ft' align='right'><b>".formatCash($grand_lpmc_total)."</b></td></tr>";
                                            break;
                                        }
                                        
                                    }
                                
                                    $rows .= "</tr>";
                                }
                                else
                                {
                                    $ctr = 1;
                                    $grand = 0;
                                    foreach ($emp as $key => $users) 
                                    {
                                        if($users->lname)
                                        {
                                            $lname = $users->lname." ".$users->exname;
                                        }
                                        else
                                        {
                                            $lname = $users->lname;
                                        }
                    
                                        if(request()->remit_report == 5)
                                            $org = "landbank";
                                        elseif(request()->remit_report == 4) 
                                            $org = "cdc";
                                        else   
                                            $org = 'others';
                    
                                        $loan = $this->getRemittanceMC($users->empcode,2,request()->payroll_mon,request()->payroll_year,$org);
                    
                                        if($loan > 0)
                                        {
                                            $rows .= "<tr><td>".$ctr."</td><td>".$users->lname." ".$users->fname." ".$users->mname.".</td><td align='right'>".formatCash($loan)."</td></td>";
                                            $grand += $loan;
                                            $ctr++;
                                        }
                                            
                                    }
                    
                                    $foot = "<tr><td align='center' class='ft' style='width:5%;'><b></b></td><td class='ft' align='center' style='width:30%;'><b>GRAND TOTAL</b></td><td class='ft' align='right'><b>".formatCash($grand)."</b></td></tr>";
                                    
                                }
                            }
                            else
                            {
                                $rows = "<center><h1>MONTH NOT YET PROCCESS</h1></center>";
                            }

            $title .= " (Magna Carta)";
        }


        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML('<!DOCTYPE html>
                            <html>
                            <head>
                              <title>HRMIS - REMITTANCE</title>
                              <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                            </head>
                            <style type="text/css">
                                body
                                {
                                    font-family:Helvetica;
                                }
                                th,td
                                {
                                    border:0px solid #555;
                                    font-size:12px;
                                }

                                .page-break {
                                    page-break-after: always;
                                   }
                                .bd1
                                {
                                    border-top:1px solid #000;
                                    border-bottom:1px solid #000;
                                    font-size:12px;
                                }

                                .hd
                                {
                                    border-bottom:#000 solid 3px;
                                    font-size:11px;
                                }

                                .ft
                                {
                                    border-top:#000 solid 3px;
                                    font-size:12px;
                                }

                                .bd2
                                {
                                    font-size:12px;
                                }
    
                            </style>
                            <body>
                            <table width="100%" cellspacing="0" cellpadding="2">
                                <tr>
                                <td style="border : 1px solid #FFF;font-size:12px;" align="center">
                                        DOST-PCAARRD<br/>
                                        Statement of Remittance '.$title.'
                                        <br/>
                                </td>
                                </tr>
                                <tr>
                                <td style="border : 1px solid #FFF;font-size:12px;" align="center">For the month of '.$mon2.' '.request()->payroll_year.'</td>
                                </tr>
                            </table>
                            <br/>
                            <br/>
                            <table width="100%" cellspacing="0" cellpadding="2">
                                '.$head.'
                                '.$rows.'
                                '.$foot.'
                            </table>
                            <br>
                            <br>
                            <table width="100%" cellspacing="0" cellpadding="2">
                                <tr>
                                    <td align="left">Prepared by:<br><br><br> <b>NIDA L. MANGALINDAN</b><br>Administrative Officer I<td>
                                    <td align="left"><td>
                                    <td align="left">Certified Correct:<br><br><br> <b>GEORGIA M. LAWAS</b><br>Administrative Officer V<td>
                                </tr>
                            </table>
                            
                            </body>
                            </html>')
        ->setPaper($paper, $layout);
        return $pdf->stream();
    }

    public function getRemittance($empcode,$type,$mon,$yr,$org,$serv = null)
    {
        if($type == 1)
        {
            $deduc = App\Payroll\Prevmandatbl::where('empCode',$empcode)->where('deductID',$org)->where('fldMonth',$mon)->where('fldYear',$yr)->first();
            if($deduc)
            {
                $amt = $deduc['deductAmount'];
            }
            else
            {
                $amt = 0;
            }
        }
        elseif($type == 2)
        {
            if($serv)
                $deduc = App\Payroll\Prevdeductbl::where('fldEmpCode',$empcode)->where('ORG_CODE',$org)->where('SERV_CODE',$serv)->where('fldMonth',$mon)->where('fldYear',$yr)->first();
            else
                $deduc = App\Payroll\Prevdeductbl::where('fldEmpCode',$empcode)->where('ORG_CODE',$org)->where('fldMonth',$mon)->where('fldYear',$yr)->first();

            if($deduc)
            {
                $amt = $deduc['DED_AMOUNT'];
            }
            else
            {
                $amt = 0;
            }
        }
        else
        {
            $deduc = App\Payroll\PrevInfotbl::where('fldEmpCode',$empcode)->where('fldMonth',$mon)->where('fldYear',$yr)->first();
            if($deduc)
            {
                $amt = $deduc['BIR'];
            }
            else
            {
                $amt = 0;
            }
        }

        return $amt;
    }


    public function getRemittanceMC($empcode,$type,$mon,$yr,$col)
    {
        $deduc = App\Payroll\MC::where('empCode',$empcode)->where('payroll_mon',$mon)->where('payroll_yr',$yr)->first();

        return $deduc[$col];
    }

}
