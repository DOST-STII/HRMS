<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemManager;
use Response;
use File;
use App;

class Ledger extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $data = 
                [
                    "nav" => nav("payrollledger"),
                ];
                
        return view('payroll.ledger')->with("data",$data);
    }

    public function print()
    {
        ini_set('memory_limit', '512M');

        $maxrow = 46;

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

        //DATE TITLE
        if(request()->payroll_mon == request()->payroll_mon2 && request()->payroll_year == request()->payroll_year2)
        {
            $mon1 = date('F',mktime(0, 0, 0, request()->payroll_mon, 10));
            $date = $mon1." ".request()->payroll_year;

            $from = request()->payroll_year.'-'.request()->payroll_mon.'-01';
            $to = request()->payroll_year.'-'.request()->payroll_mon.'-01';

            $title = date('F Y',strtotime($from));
        }
        else
        {
            $mon1 = date('F',mktime(0, 0, 0, request()->payroll_mon, 10));
            $mon2 = date('F',mktime(0, 0, 0, request()->payroll_mon, 10));
            $date = $mon1." ".request()->payroll_year." - ".$mon2." ".request()->payroll_year2;

            $from = request()->payroll_year.'-'.request()->payroll_mon.'-01';
            $to = request()->payroll_year2.'-'.request()->payroll_mon2.'-01';

            $title = date('F Y',strtotime($from))." - ".date('F Y',strtotime($to));
        }

        //GET USER INFO
        $fullname = getStaffInfo(request()->userid);
        $empcode = getStaffInfo(request()->userid,'empcode');

        //GET ALL REMMITANCE
        $prevempinfo = App\Payroll\View_PrevInfotbl::where('fldEmpCode',$empcode)->whereBetween('payroll_date',[$from, $to])->whereNull('deleted_at')->orderBy('fldYear','ASC')->orderBy('fldMonth','ASC')->get();

        $tr = "";

        //MANDA
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

        foreach($prevempinfo as $key => $value)
        {
            $d_total = 0;

            //MANDATORY
            $deductions = getDeductionsPrev($empcode,$value->fldMonth,$value->fldYear);

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
                                            $d_sic = $value->M_BASIC * 0.09;
                                            $total_deduc_sic += $d_sic;

                                            $total_deduc_total += $d_sic;
                                            $d_total += $d_sic;
                                        break;
                                    case 3:
                                            $d_ph = $vd->deductAmount;
                                            $total_deduc_ph += $vd->deductAmount;

                                            $total_deduc_total += $vd->deductAmount;
                                            $d_total += $vd->deductAmount;
                                        break;
                                    case 4:
                                            $d_hmdf = $vd->deductAmount;
                                            $total_deduc_hdmf += $vd->deductAmount;

                                            $total_deduc_total += $vd->deductAmount;
                                            $d_total += $vd->deductAmount;
                                        break;
                                }
                        }


            //LOANS        
            $loans = getPersonalPrevLoans($empcode,$value->fldMonth,$value->fldYear);
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
           $tr .= 
                '<tr>
                    <td class="bd4" ><b>'.date('F',mktime(0, 0, 0, $value->fldMonth, 10)).'</b></td>
                    <td class="bd4" ><b>'.$value->fldYear.'</b></td>
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

        //TOTAL
        $tr .= '<tr>
                    <td class="bd3" colspan="2"><b>GRANDTOTAL</b></td>
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
                </tr>';


        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML('<!DOCTYPE html>
                            <html>
                            <head>
                              <title>HRMIS - SALARY LEDGER</title>
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

                                .bd3
                                {
                                    border-top:1px solid #000;
                                    border-bottom:1px solid #000;
                                    font-size:9px;
                                }

                                .bd4
                                {
                                    font-size:9px;
                                }
    
                            </style>
                            <body>
                                <table width="100%" cellspacing="0" cellpadding="2">
                                    <tr><td style="border : 1px solid #FFF;width:15%" align="right">
                                    <img src="'.asset('img/DOST2.png').'" style="width:50px">
                                        </td>
                                        <td style="border : 1px solid #FFF;font-size:11px;" align="center">
                                                Republic of the Philippines<br/>
                                                PHILIPPINE COUNCIL FOR AGRICULTURE, AQUATIC AND NATURAL RESOURCES
                                                RESEARCH AND DEVELOPMENT<br/>
                                                Los Baños, Laguna
                                        </td>
                                        <td style="border : 1px solid #FFF;font-size:12px;width:15%" >
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border : 1px solid #FFF;font-size:12px;" align="center" colspan="3">SALARY LEDGER<br/>'.$title.'</td>
                                    </tr>
                                </table>
                            <br/>
                            <table width="100%" cellspacing="0" cellpadding="2">
                                <tr><td>Employee : <b>'.$fullname.'</td></tr>
                            </table>

                            <table width="100%" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td class="bd3"><b>Month</b></td>
                                        <td class="bd3"><b>Year</b></td>
                                        <td class="bd3" align="center"><b>ITW</b></td>
                                        <td class="bd3" align="center"><b>SIC</b></td>
                                        <td class="bd3" align="center"><b>Phil-Health</b></td>
                                        <td class="bd3" align="center"><b>HDMF</b></td>
                                        <td class="bd3" align="center"><b>GSIS Policy</b></td>
                                        <td class="bd3" align="center"><b>GSIS Conso</b></td>
                                        <td class="bd3" align="center"><b>GSIS EML</b></td>
                                        <td class="bd3" align="center"><b>GSIS Educ</b></td>
                                        <td class="bd3" align="center"><b>GSIS Opt Policy</b></td>
                                        <td class="bd3" align="center"><b>GSIS CP</b></td>
                                        <td class="bd3" align="center"><b>GSIS MP</b></td>
                                        <td class="bd3" align="center"><b>GSIS GFAL</b></td>
                                        <td class="bd3" align="center"><b>HDMF MP</b></td>
                                        <td class="bd3" align="center"><b>HDMF Housing</b></td>
                                        <td class="bd3" align="center"><b>CDC FD</b></td>
                                        <td class="bd3" align="center"><b>CDC SD</b></td>
                                        <td class="bd3" align="center"><b>CDC Loans</b></td>
                                        <td class="bd3" align="center"><b>PMPC HMO</b></td>
                                        <td class="bd3" align="center"><b>PMPC FD</b></td>
                                        <td class="bd3" align="center"><b>PMPC SD</b></td>
                                        <td class="bd3" align="center"><b>PMPC Loans</b></td>
                                        <td class="bd3" align="center"><b>LBP</b></td>
                                        <td class="bd3" align="center"><b>Others</b></td>
                                        <td class="bd3" align="center"><b>Total Deductions</b></td>
                                    </tr>
                                    '.$tr.'
                                </table>
                            </body>
                            </html>')
        ->setPaper('legal', 'landscape');
        return $pdf->stream();
    }

    public function getPrevDeduc($empcode,$servcode,$mon,$yr)
    {
        switch($servcode)
        {
            case "305":
            case "305":
                $deduc = App\Payroll\Prevdeductbl::where('fldEmpCode',$empcode)->where('SERV_CODE',$servcode)->where('fldMonth',$mon)->where('fldYear',$yr)->first();
                return $deduc['DED_AMOUNT'];
            break;
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

    public function textfile($mon,$yr,$wk)
    {

        $user = App\View_user::whereIn('employment_id',[1,11,13,14,15])->where('payroll',1)->orderBy('lname')->orderBy('fname')->get();

        //$user = App\View_user::whereIn('employment_id',[1,11,13,14,15])->where('username','ABU002')->where('payroll',1)->get();

        //return $user;
        $text = "";
        $text2 = "";
        //CREATE FILE
        //MAX CHARACTER
        $max = 73;
        $max2 = 23;

        //CONST FOR LANDBANK
        $atm = "1890000";

        $mon2 = date('F',mktime(0, 0, 0, $mon, 10));

        $folder = $yr."-".date('m',strtotime($mon2))."_".strtoupper(date('M',strtotime($mon2)));

        

        //CHECK IF EXISTING
        // $filename = 'PC'.strtoupper(date('M',strtotime($mon2))).'WK'.$wk.'.txt';
        // $exists = Storage::disk('payroll')->exists($folder.'/'.$filename);
        // if($exists)
        // {
        //     //return "Meron na";
        //     $myFile = storage_path('app/payroll/'.$folder."/".$filename);
        //     $headers = ['Content-Type: text/plain'];
        //     $newName = $filename;
              
        //     return response()->download($myFile, $newName, $headers);

        //     echo "Your download will start shortly..";
        // }
        // else
        // {

        //return "Tumuloy";
        //Storage::disk('payroll')->makeDirectory('2022-06_JUN');

        //return $folder;

        Storage::disk('payroll')->makeDirectory($folder);

        $fsMgr = new FilesystemManager(app());
        // local disk
        $localDisk = $fsMgr->createLocalDriver(['root' => storage_path('app/payroll/'.$folder)]);


        foreach ($user as $key => $users) {

                          $plantilla = getPlantillaInfo($users->username);

                          $previnfo = App\Payroll\PrevInfo::where('fldEmpCode',$users->username)->where('fldMonth',$mon)->where('fldYear',$yr)->first();
                          
                          if($plantilla)
                          {
                            //return "TEST";
                            $text2 .= "NAME : ".$users->lname.", ".$users->fname."<br/>";
                            //COMP
                            $ra = 0;
                            $ta = 0;
                            $pera = 0;
                            $addcom = 0;

                            $comp = App\Payroll\Prevcomptbl::where('empCode',$users->username)->where('fldMonth',$mon)->where('fldYear',$yr)->get();
                            foreach ($comp as $c => $comps) {
                                switch ($comps->compID) {
                                    case 1:
                                            $pera = $comps->compAmount;

                                            $text2 .= "PERA : ".$pera."</br>";
                                        break;
                                     case 2:
                                            $addcom = $comps->compAmount;
                                        break;
                                    case 3:
                                            $ra = $comps->compAmount;
                                        break;
                                    case 4:
                                            $ta = $comps->compAmount;
                                        break;
                                }

                                
                            }
                            if(isset($previnfo['M_BASIC']))
                            {
                                //return $users->username;
                            }
                            else
                            {
                                return $users->username;
                            }

                            $salary = $previnfo['M_BASIC']  + $pera + $addcom;
                            $basic = $previnfo['M_BASIC'];

                            $text2 .= "BASIC : ".$basic ."</br>";
                            $text2 .= "GROSS : ".$salary ."</br>";
                          }
                          else
                          {

                            $salary = 0;
                          }

                          //return $text;

                          
                          $d_total = 0;

                          //MANDA
                          $d_itw = 0;
                          $d_sic = 0;
                          //$d_ph = 0;
                          $d_hmdf = 0;

                          //PHILHEALTH
                          $d_ph = computePhil($basic);
                                              
                        if($d_ph >= 1600)
                            {
                                $d_ph = 1600;
                                $d_total += $d_ph;
                            }
                            else
                            {
                                $d_total += $d_ph;
                            }
                          $text2 .= "---DEDCUTIONS---</br>";

                          $text2 .= "PH : ".$d_ph."</br>";

                          $deductions = getDeductions($users->username);
                          
                          foreach ($deductions as $kd => $vd) {
                            
                                  switch ($vd->deductID) {
                                      case 1:
                                              $d_itw = $vd->deductAmount;
                                              $d_total += $vd->deductAmount;

                                              $text2 .= "ITW : ".$d_itw."</br>";
                                          break;
                                      case 2:
                                              //$d_sic = $vd->deductAmount;
                                            //   $d_sic = $basic * 0.09;
                                            //   $d_total += $d_sic;

                                              $text2 .= "SIC : ".$d_sic."</br>";
                                          break;
                                      case 3:
                                              //$d_ph = $vd->deductAmount;
                                              //$d_ph = $basic * 0.015;
                                            //   $d_ph = computePhil($basic);
                                              
                                            //   if($d_ph >= 1600)
                                            //   {
                                            //     $d_ph = 1600;
                                            //     $d_total += $d_ph;
                                            //   }
                                            //   else
                                            //   {
                                            //     $d_total += $d_ph;
                                            //   }

                                              //$text .= "PH : ".$d_ph."</br>";
                                                
                                          break;
                                      case 4:
                                              $d_hmdf = $vd->deductAmount;
                                              $d_total += $vd->deductAmount;

                                              $text2 .= "HMDF : ".$d_hmdf."</br>";
                                          break;
                                  }
                            }

                            //SIC
                            $d_sic = $basic * 0.09;
                            $d_total += $d_sic;

                            $text2 .= "SIC : ".$d_sic."</br>";

                            //return $text;

                            // $d_sic = $basic * 0.09;
                            // if($d_sic > 0)
                            // {
                            //   $d_total += $d_sic;
                            // }

                            

                            $other_txt = "";

                            $text2 .= "---LOANS---</br>";

                            $loans = getPersonalLoans($users->username);

                            foreach ($loans as $kls => $vls) 
                            {
                                
                                switch ($vls->SERV_CODE) {
                                    case "302C":
                                            $d_hmdf2 = $vls->DED_AMOUNT;
                                            $d_total += $vls->DED_AMOUNT;

                                            $text2 .= "SERV_CODE : ".$vls->DED_AMOUNT."</br>";
                                    break;
                                    case "305":
                                            $d_gspol = $vls->DED_AMOUNT;
                                            $d_total += $vls->DED_AMOUNT;

                                            $text2 .= "SERV_CODE : ".$vls->DED_AMOUNT."</br>";
                                        break;
                                    case "319":
                                            $d_gscon = $vls->DED_AMOUNT;
                                            $d_total += $vls->DED_AMOUNT;

                                            $text2 .= "SERV_CODE : ".$vls->DED_AMOUNT."</br>";
                                        break;
                                    case "316":
                                            $d_gseml = $vls->DED_AMOUNT;
                                            $d_total += $vls->DED_AMOUNT;

                                            $text2 .= "SERV_CODE : ".$vls->DED_AMOUNT."</br>";
                                        break;
                                    case "315":
                                            $d_gseduc = $vls->DED_AMOUNT;
                                            $d_total += $vls->DED_AMOUNT;

                                            $text2 .= "SERV_CODE : ".$vls->DED_AMOUNT."</br>";
                                        break;
                                    case "305A":
                                            $d_gsopt= $vls->DED_AMOUNT;
                                            $d_total += $vls->DED_AMOUNT;

                                            $text2 .= "SERV_CODE : ".$vls->DED_AMOUNT."</br>";
                                        break;
                                    case "319C":
                                            $d_gscp= $vls->DED_AMOUNT;
                                            $d_total += $vls->DED_AMOUNT;

                                            $text2 .= "SERV_CODE : ".$vls->DED_AMOUNT."</br>";
                                        break;
                                    case "319B":
                                            $d_gsmp= $vls->DED_AMOUNT;
                                            $d_total += $vls->DED_AMOUNT;

                                            $text2 .= "SERV_CODE : ".$vls->DED_AMOUNT."</br>";
                                        break;
                                    case "319A":
                                            $d_gsgfal= $vls->DED_AMOUNT;
                                            $d_total += $vls->DED_AMOUNT;

                                            $text2 .= "SERV_CODE : ".$vls->DED_AMOUNT."</br>";
                                        break;
                                    case "302A":
                                            $d_hmdfmp= $vls->DED_AMOUNT;
                                            $d_total += $vls->DED_AMOUNT;

                                            $text2 .= "SERV_CODE : ".$vls->DED_AMOUNT."</br>";
                                        break;
                                    case "302B":
                                            $d_hmdfhouse= $vls->DED_AMOUNT;
                                            $d_total += $vls->DED_AMOUNT;

                                            $text2 .= "SERV_CODE : ".$vls->DED_AMOUNT."</br>";
                                        break;
                                    case "920":
                                            //$d_cdcfd = $vls->DED_AMOUNT;
                                            if($vls->DED_AMOUNT > 0)
                                              $d_cdcfd = $basic * 0.02;
                                            else 
                                              $d_cdcfd = 0;

                                              $text2 .= "SERV_CODE : ".$d_cdcfd."</br>";

                                              $d_total += $d_cdcfd;
                                        break;
                                    case "922":
                                            $d_cdcsd= $vls->DED_AMOUNT;
                                            $d_total += $vls->DED_AMOUNT;

                                            $text2 .= "SERV_CODE : ".$vls->DED_AMOUNT."</br>";
                                        break;
                                    case "921":
                                            $d_cdcloan= $vls->DED_AMOUNT;
                                            $d_total += $vls->DED_AMOUNT;

                                            $text2 .= "SERV_CODE : ".$vls->DED_AMOUNT."</br>";
                                        break;
                                    case "933":
                                            $d_pmpchmo= $vls->DED_AMOUNT;
                                            $d_total += $vls->DED_AMOUNT;

                                            $text2 .= "SERV_CODE : ".$vls->DED_AMOUNT."</br>";
                                        break;
                                    case "930":
                                            //$d_pmpcfd = $vls->DED_AMOUNT;
                                            //$d_pmpcfd = $basic * 0.02;
                                            if($vls->DED_AMOUNT > 0)
                                              $d_pmpcfd = $basic * 0.02;
                                            else 
                                              $d_pmpcfd = 0;

                                              $text2 .= "SERV_CODE : ".$d_pmpcfd."</br>";

                                              $d_total += $d_pmpcfd;
                                        break;
                                    case "932":
                                            $d_pmpcsd= $vls->DED_AMOUNT;
                                            $d_total += $vls->DED_AMOUNT;

                                            $text2 .= "SERV_CODE : ".$vls->DED_AMOUNT."</br>";
                                        break;
                                    case "931":
                                            $d_pmpcloan= $vls->DED_AMOUNT;
                                            $d_total += $vls->DED_AMOUNT;

                                            $text2 .= "SERV_CODE : ".$vls->DED_AMOUNT."</br>";
                                        break;
                                    case "321":
                                            $d_ldp = $vls->DED_AMOUNT;
                                            $d_total += $vls->DED_AMOUNT;

                                            $text2 .= "SERV_CODE : ".$vls->DED_AMOUNT."</br>";
                                        break;
                                    default:
                                            $d_others = $vls->DED_AMOUNT;
                                            $d_total += $vls->DED_AMOUNT;

                                            $text2 .= "SERV_CODE : ".$vls->DED_AMOUNT."</br>";

                                            $other_txt .= $vls->SERV_CODE.",";
                                    break;
                                }
                            }
                            $lwopprevyear = $yr - 1;
                            $prevlwop = collect(App\Payroll\LWOP::where('empcode',$users->username)->whereYear('leave_date',$lwopprevyear)->get());
                            if(isset($prevlwop))
                            {
                                $prevlwop = $prevlwop->all();
                                        foreach ($prevlwop as $prvyr => $prevlwops) {
                                                $d_total += $prevlwops->amt;
                                        }
                            }

                            $netsalary = $salary - $d_total;


                            $text2 .= "NET SALARY : ".$netsalary."</br>";

                            //return $text;
                        
                            
                            //FOR DB
                            $wksal = 0;
                            for ($i=1; $i <= 4 ; $i++) 
                            {
                                if($i == $wk)
                                {
                                    $sal = getWeekSalary($users->username,$netsalary,$i);
                                    switch($i)
                                    {
                                        case 1:
                                            $wksal= $sal;
                                        break;

                                        case 2:
                                        case 3:
                                            $wksal = $sal;
                                        break;

                                        case 4:
                                            $wksal = $sal;
                                        break;
                                    }

                                    $text2 .= "WEEK SALARY : ".$wksal."</br>";
                                } 
                                
                            }

                            $txt1 = $users->addinfo_atm.''.$users->lname.','.$users->fname.' '.$users->mname[0];
                            $txtctr = strlen($txt1);

                            $wksal = str_replace(array('.', ','), '' , $wksal);

                            $chrs = 73 - ($txtctr + 23);
                            $spaces = str_repeat(' ', $chrs);

                            //ZERO BEFORE SALARY
                            $txt2 = $wksal.$atm.$wk;
                            $txtctr = strlen($txt2);
                            $chrs = 23 - ($txtctr);
                            $zeros = str_repeat('0', $chrs);

                            $text .= $users->addinfo_atm.''.$users->lname.','.$users->fname.' '.$users->mname[0].$spaces.$zeros.$txt2.str_repeat(' ', 7)."\n";
                            //$text2 .= $users->addinfo_atm.''.$users->lname.','.$users->fname.' '.$users->mname[0].$spaces.$zeros.$txt2.str_repeat(' ', 7)."<br/>";

                            //$text2 .= $users->addinfo_atm.''.$users->lname.','.$users->fname.' ----- '.$wksal."<br/>";

                            //$text .= " -- ".$netsalary." WEEK : ".$wksal."</br>";
                             
                          }

                          //return $text;

                          $filename = 'PC'.strtoupper(date('M',strtotime($mon2))).'WK'.$wk.'.txt';
                          $localDisk->put($filename, $text);
          
                          $myFile = storage_path('app/payroll/'.$folder."/".$filename);
                          $headers = ['Content-Type: text/plain'];
                          $newName = $filename;
              
                          return response()->download($myFile, $newName, $headers);

                          echo "Your download will start shortly..";
        // }
    }
}
