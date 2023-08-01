<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemManager;
use File;
use App;
use App\Imports\SalaryImport;

use Maatwebsite\Excel\Facades\Excel;

class Process extends Controller
{
    // private $info = "";

    public function __construct()
    {
        $this->middleware('auth');
        
    }

    private function getInfo($id)
    {
        $emp = 
        $emp = $this->info->where('user_id',$id)->orderBy('plantilla_date_to')->first();
        return $emp['plantilla_salary'];
    }

    public function index()
    {
        $data = 
                [
                    "nav" => nav("payrollprocess"),
                ];
        return view('payroll.process')->with("data",$data);
    }

    public function create()
    {
       // return request()->path;

        //ACTIVE USER
        $emp = App\View_user::whereNotNull('payroll')->get();

        //GET MONTH YEAR
        $dt = explode("-",request()->path);
        $p_year = $dt[0];
        $p_mon = $dt[1];

        $total_deduc_total = 0;
        $d_others = 0;

        $total_week_1 = 0;
        $total_week_2 = 0;
        $total_week_3 = 0;
        $total_week_4 = 0;


        $text = "";

        foreach ($emp as $ky => $vl) 
        {
            $text .= "STAFF : ".$vl->lname.", ".$vl->fname;
            //SAVE INFO
            $plantilla = getPlantillaInfo($vl->username);
            $sl = $plantilla['plantilla_salary'];

            $sic = 0;
            $bir = 0;
            $hmdf = 0;

            //MANDA
            $deductions = getDeductions($vl->username);
                   
            foreach ($deductions as $kd => $vd) 
                {
                    //if($vd->deductAmount > 0)
                    //{
                        switch ($vd->deductID) {
                            case 1:
                                    //$d_itw = $vd->deductAmount;
                                    //$total_deduc_itw += $vd->deductAmount;

                                    $d = $vd->deductAmount;
                                    $bir = $d;
                                    $total_deduc_total += $d;

                                break;
                            case 2:
                                    //$d_sic = $vd->deductAmount;
                                    $d = $plantilla['plantilla_salary'] * 0.09;
                                    //$total_deduc_sic += $d_sic;
                                    $sic = $d;
                                    $total_deduc_total += $d;
                                    //$d_total += $d_sic;
                                break;
                            case 3:
                                    //$d_ph = $vd->deductAmount;
                                    //$d = $plantilla['plantilla_salary'] * 0.015;
                                    $d = computePhil($plantilla['plantilla_salary']);

                                    if($d >= 1600)
                                    {
                                        $d = 1600;
                                    }

                                    $total_deduc_total += $d;
                                break;
                            case 4:
                                    $d = $vd->deductAmount;
                                    //$total_deduc_hdmf += $vd->deductAmount;
                                    $hmdf = $d;
                                    $total_deduc_total += $d;
                                    //$d_total += $vd->deductAmount;
                                break;
                        }

                        //SAVE
                        $d1 = new App\Payroll\Prevmandatbl;
                        $d1->empCode = $vl->username;
                        $d1->fldMonth = $p_mon;
                        $d1->fldYear = $p_year;
                        $d1->deductID = $vd->deductID; 
                        $d1->deductAmount = $d;
                        $d1->save();
                   // }
                }

            // $manda = getDeductions($vl->username);
            // foreach ($manda as $km => $vm) 
            //     {
            //         if($vm->deductAmount > 0)
            //         {
            //             $d = new App\Payroll\Prevmandatbl;
            //             $d->empCode = $vl->username;
            //             $d->fldMonth = $p_mon;
            //             $d->fldYear = $p_year;
            //             $d->deductID = $vm->deductID; 
            //             $d->deductAmount = $vm->deductAmount;
            //             $d->save();

            //             switch($d->deductID)
            //             {
            //                 case 1:
            //                     $bir = $vm->deductAmount;
            //                     $total_deduc += $vm->deductAmount;
            //                 break;

            //                 case 2:
            //                     $sic = $sl * 0.09;
            //                     $total_deduc += $sic;
            //                 break;

            //                 case 3:
            //                     $ph = $vm->deductAmount;
            //                     $total_deduc += $vm->deductAmount;
            //                 break;
                            
            //                 case 4:
            //                     $hmdf = $vm->deductAmount;
            //                     $total_deduc += $vm->deductAmount;
            //                 break;
            //             }
            //         }
            //     }
            
                
            
            //SAVE LOANS/OTHERS
            $loans = getPersonalLoans($vl->username);

                    foreach ($loans as $kls => $vls) 
                        {
                            switch ($vls->SERV_CODE) {
                                case "920":
                                case "930":
                                //case "930":
                                        //$d_cdcfd= $vls->DED_AMOUNT;
                                        //$d_cdcfd = $plantilla['plantilla_salary'] * 0.02;
                                        //$total_deduc_cdcfd += $d_cdcfd;
                                        if($vls->DED_AMOUNT > 0)
                                              $ddd = $plantilla['plantilla_salary'] * 0.02;
                                            else 
                                              $ddd = 0;
                                break;
                                case "303":
                                case "305":
                                case "319":
                                case "316":
                                case "315":
                                case "305A":
                                case "302C":    
                                case "319C":
                                case "319B":
                                case "319A":
                                case "302A":
                                case "302B":
                                case "907":
                                case "922":
                                case "921":
                                case "921A":
                                case "923":
                                case "933":
                                case "932":
                                case "931":
                                case "321":
                                    $ddd = $vls->DED_AMOUNT;
                                break;
                                    $d_others += $vls->DED_AMOUNT;
                                    $ddd = $vls->DED_AMOUNT;
                                default:
                                    
                            }

                            $total_deduc_total += $ddd;

                            $d2 = new App\Payroll\Prevdeductbl;
                            $d2->fldEmpCode = $vl->username;
                            $d2->fldMonth = $p_mon;
                            $d2->fldYear = $p_year;
                            $d2->ORG_CODE = $vls->ORG_CODE; 
                            $d2->SERV_CODE = $vls->SERV_CODE; 
                            $d2->SERV_NO = $vls->SERV_NO; 
                            $d2->DED_AMOUNT = $ddd;
                            $d2->save();
                        }
            // $loans = getPersonalLoans($vl->username);
            // $other_d = 0;
            
                    
            // foreach ($loans as $kd => $vd) 
            //     {
            //         if($vd->DED_AMOUNT > 0)
            //         {
            //             //CDC PMPC
            //             if($vd->SERV_CODE == '920' || $vd->SERV_CODE == '930')
            //                 $ddd = $sl * 0.02;
            //             else
            //                 $ddd = $vd->DED_AMOUNT;


            //             $other_d += $ddd;
            //             $total_deduc += $ddd;

            //             $d = new App\Payroll\Prevdeductbl;
            //             $d->fldEmpCode = $vl->username;
            //             $d->fldMonth = $p_mon;
            //             $d->fldYear = $p_year;
            //             $d->ORG_CODE = $vd->ORG_CODE; 
            //             $d->SERV_CODE = $vd->SERV_CODE; 
            //             $d->SERV_NO = $vd->SERV_NO; 
            //             $d->DED_AMOUNT = $ddd;
            //             $d->save();
                        
            //         }
                   
            //     }


            //SAVE COMPENSATION
            //RATA
            $ra = 0;
            $ta = 0;
            $pera = 0;
            $addcom = 0;

            $comp = collect(App\Payroll\Compensation::where('empCode',$vl->username)->get());
                        $comp = $comp->all();
                        foreach ($comp as $c => $comps) {
                            if($comps->compAmount > 0)
                            {
                                switch ($comps->compID) {
                                    case 1:
                                            $pera = $comps->compAmount;
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

                                $d3 = new App\Payroll\Prevcomptbl;
                                $d3->empCode = $vl->username;
                                $d3->compID = $comps->compID;
                                $d3->compAmount = $comps->compAmount;
                                $d3->fldMonth = $p_mon;
                                $d3->fldYear = $p_year;
                                $d3->save();
                            }
                            
                        }
            //PREV YEAR LWOP
            $lwopprevyear = $p_year - 1;

            if($vl->username == 'LIB002')
            {
                $prevlwop = new App\Payroll\LWOP;
                $prevlwop->empcode = $vl->username;
                $prevlwop->leave_id = $lwopprevyear;
                $d3->save();
            }

            // $prevlwop = collect(App\Payroll\LWOP::where('empcode',$vl->username)->whereYear('leave_date',$lwopprevyear)->whereNull('process_at')->get());
            // if(isset($prevlwop))
            // {
            //     $prevlwop = $prevlwop->all();
            //             foreach ($prevlwop as $prvyr => $prevlwops) {
            //                     $total_deduc_total += $prevlwops->amt;
            //                     App\Payroll\LWOP::where('id',$prevlwops->id)
            //                             ->update([
            //                                 'payroll_mon' => $p_mon,
            //                                 'payroll_year' => $p_year,
            //                                 'process_at' => date('Y-m-d H:i:s'),
            //                             ]);
                            
            //             }
            // }
                        

            // $comps = getCompensation($vl->username);
                    
            // foreach ($comps as $kc => $vc) 
            //     {
            //         if($vc->compAmount > 0)
            //         {
            //             $d = new App\Payroll\Prevcomptbl;
            //             $d->empCode = $vl->username;
            //             $d->compID = $vc->compID;
            //             $d->compAmount = $vc->compAmount;
            //             $d->fldMonth = $p_mon;
            //             $d->fldYear = $p_year;
            //             $d->save();
            //         }
                   
            //     }


            
            $wk = 0;
            $wk1 = 0;
            $wk4 = 0;

            $net = ($sl + 2000 + $addcom) - $total_deduc_total;

            //FOR DB
            for ($i=1; $i <= 4 ; $i++) 
            { 
                $sal = getWeekSalary($vl->username,$net,$i,1);
                switch($i)
                {
                    case 1:
                        $wk1 = $sal;
                    break;

                    case 2:
                    case 3:
                        $wk = $sal;
                    break;

                    case 4:
                        $wk4 = $sal;
                    break;
                }
            }
            

             $info = new App\Payroll\PrevInfo;
             $info->fldEmpCode = $vl->username;
             $info->fldMonth = $p_mon;
             $info->fldYear = $p_year;
             $info->M_BASIC = $sl;
             $info->M_PERA = 2000;
             $info->M_REPN = $ra;
             $info->M_TRANS = $ta;
             $info->SIC = $sic;
             $info->BIR = $bir;
             $info->HDMF = $hmdf;
             $info->OTH_DED = $d_others;
             $info->AMOUNT1 = $wk1;
             $info->AMOUNT2 = $wk;
             $info->AMOUNT3 = $wk;
             $info->AMOUNT4 = $wk4;
             $info->netSalary = $net;
             $info->save();
             
            
        }

        //CREATE FILE
        //MAX CHARACTER
        $max = 73;
        $max2 = 23;

        //CONST FOR LANDBANK
        $atm = "1890000";

        $folder = request()->path;

        //Storage::disk('payroll')->makeDirectory('2022-06_JUN');

        Storage::disk('payroll')->makeDirectory($folder);

        $fsMgr = new FilesystemManager(app());
        // local disk
        $localDisk = $fsMgr->createLocalDriver(['root' => storage_path('app/payroll/'.$folder)]);

            for ($i=1; $i <= 4 ; $i++) 
            { 

                $txt = "";

                foreach ($emp as $ky => $vl) 
                {
                        //SAVE INFO
                        $plantilla = getPlantillaInfo($vl->username);
                        $sl = $plantilla['plantilla_salary'];

                        $total_deduc = 0;

                        //SAVE MANDATORY
                        $manda = getDeductions($vl->username);
                        foreach ($manda as $kd => $vd) 
                            {
                                //if($vd->deductAmount > 0)
                                //{
                                    switch ($vd->deductID) {
                                        case 1:
                                                $d = $vd->deductAmount;
                                                $total_deduc += $d;

                                            break;
                                        case 2:
                                                $d = $plantilla['plantilla_salary'] * 0.09;

                                                $total_deduc += $d;
                                            break;
                                        case 3:
                                                // $d = $plantilla['plantilla_salary'] * 0.015;

                                                // if($d >= 900)
                                                // {
                                                //     $d = 900;
                                                // }
                                                
                                                $d = computePhil($plantilla['plantilla_salary']);

                                                if($d >= 1600)
                                                {
                                                    $d = 1600;
                                                }

                                                $total_deduc += $d;
                                                
                                            break;
                                        case 4:
                                                $d = $vd->deductAmount;

                                                $total_deduc += $d;
                                            break;
                                    }
                                //}
                            } 
                        
                    //SAVE LOANS/OTHERS
                    $loans = getPersonalLoans($vl->username);

                    foreach ($loans as $kls => $vls) 
                        {
                            switch ($vls->SERV_CODE) {
                                case "920":
                                case "930":
                                        //$d_cdcfd= $vls->DED_AMOUNT;
                                        //$d_cdcfd = $plantilla['plantilla_salary'] * 0.02;
                                        //$total_deduc_cdcfd += $d_cdcfd;
                                        if($vls->DED_AMOUNT > 0)
                                              $ddd = $plantilla['plantilla_salary'] * 0.02;
                                            else 
                                              $ddd = 0;
                                    break;
                                
                                default:
                                    $ddd = $vls->DED_AMOUNT;
                                break;
                                
                                    
                            }

                            $total_deduc += $ddd;
                        }


                        $net = ($sl + 2000) - $total_deduc;

                        $txt1 = $vl->addinfo_atm.''.$vl->lname.','.$vl->fname.' '.$vl->mname[0];

                        $salary = getWeekSalary($vl->username,$net,$i);
                        

                        //$tbl = new App\Payroll\TestTable;
                        //$tbl->empcode = $vl->username;
                        //$tbl->basic = $sl;
                        //$tbl->deduc = $total_deduc;
                        //$tbl->net = $net;
                        

                        $txtctr = strlen($txt1);

                        $salary = str_replace(array('.', ','), '' , $salary);

                        
                        
                        //SPACES IN BETWEEN TO REACH 73 characters
                        $chrs = 73 - ($txtctr + 23);
                        $spaces = str_repeat(' ', $chrs);

                        //ZERO BEFORE SALARY
                        $txt2 = $salary.$atm.$i;
                        $txtctr = strlen($txt2);
                        $chrs = 23 - ($txtctr);
                        $zeros = str_repeat('0', $chrs);

                        // $txt .= $vl->addinfo_atm.''.$vl->lname.','.$vl->fname.' '.$vl->mname[0].$spaces.$zeros.$txt2.str_repeat(' ', 7)." --- Total Deduc : ".$total_deduc."<hr/>";
                        $txt .= $vl->addinfo_atm.''.$vl->lname.','.$vl->fname.' '.$vl->mname[0].$spaces.$zeros.$txt2.str_repeat(' ', 7)."\n";

                        //$tbl->val = $vl->addinfo_atm.''.$vl->lname.','.$vl->fname.' '.$vl->mname[0].$spaces.$zeros.$txt2.str_repeat(' ', 7)."</br>";
                        //$tbl->save();

                }

                //echo $txt."<br>----------------------------------------<br/>";

                $localDisk->put('PC'.request()->payrollmon.'WK'.$i.'_'.time().'.txt', $txt); 
            } 
        

        return redirect('payroll/process');
    }

    public function test()
    {

    $salarytbl = App\SalaryTable::first();

    $array = Excel::toArray(new SalaryImport, storage_path('app/salarysched/'.$salarytbl['salary_filename'].'.xlsx'));

    return $array[0];

    // $collection = Excel::toCollection(new SalaryImport, );

        foreach($array AS $index => $val)
        {
            echo $val[$index][0];
        }
    
    }

    public function mcprocess()
    {
        //return request()->mc_mon;

        //MAX CHARACTER
        $max = 73;
        $max2 = 23;

        //CONST FOR LANDBANK
        $atm = "1890000";

        $folder = request()->mcpath;

        //return $folder;

        if (!file_exists($folder)) 
        {
            Storage::disk('mc')->makeDirectory($folder);

            $fsMgr = new FilesystemManager(app());
            // local disk
            $localDisk = $fsMgr->createLocalDriver(['root' => storage_path('app/mc/')]);

                //GET ALL ACTIVE EMPLOYEE
                $emp = App\View_user::whereNull('payroll')->get();
                $txt = "";


                //GET MC
                $mc = App\Payroll\MCView::where('payroll_mon',request()->mc_mon)->where('payroll_yr',request()->mc_year)->whereNull('deleted_at')->orderBy('lname')->orderBy('fname')->get();
                $totalmc = 0;
                
                foreach ($mc as $key => $value)
                {

                        switch($value->employment_id)
                        {
                            case 1:
                            case 11:
                            case 13:
                            case 14:
                            case 15:
                                ++$totalmc;
                                $hp = $value->salary * $value->hprate;

                                //GET SALA
                                $sala = App\Employee_sala::where('user_id',$value->userid)->where('sala_mon',request()->mc_mon)->where('sala_year',request()->mc_year)->first();

                                if($sala)
                                {
                                    //GET MON LESS 1
                                    $m = request()->mc_mon;
                                    $y = request()->mc_year;
                                    if($m == 1)
                                    {
                                        $m = 12;
                                        $y = $y - 1;
                                    }
                                    else
                                    {
                                        --$m;
                                    }

                                }
                                else
                                {
                                    $sa = 0;
                                    $la = 0;
                                }
                                

                                if($value->employment_id == 15)
                                {
                                    $sa = 0;
                                    $la = 0;
                                    $hp = 0;
                                }
                    
                                $txt1 = $value->addinfo_atm.''.$value->lname.','. $value->fname.' '. $value->mname[0].' '.$value->exname;

                                $txtctr = strlen($txt1);

                                //SPACES IN BETWEEN TO REACH 73 characters
                                $chrs = 73 - ($txtctr + 23);
                                $spaces = str_repeat(' ', $chrs);

                                //GET MC

                                //ZERO BEFORE SALARY
                                $net_mc = number_format(($value->sa + $value->la + $value->lp + $hp) - ($value->hmo + $value->gsis + $value->pmpc + $value->gfal + $value->cdc + $value->landbank + $value->itw),2);
                                $net_mc = str_replace(array('.', ','), '' , $net_mc);
                                $txt2 = $net_mc.$atm."1";
                                $txtctr = strlen($txt2);
                                $chrs = 23 - ($txtctr);
                                $zeros = str_repeat('0', $chrs);

                                $txt .= $txt1.$spaces.$zeros.$txt2.str_repeat(' ', 7)."\n";

                                //echo $txt1." ----- LP : ".$value->lp." SA : ".$value->sa." LA :".$value->la." HP : ".$hp." HMO : ".$value->hmo." GSIS : ".$value->gsis." PMPC : ".$value->pmpc." GFAL : ".$value->gfal." CDC : ".$value->cdc." LANDBANK : ".$value->landbank." ITW : ".$value->itw." = ".$net_mc."<br/>";
                            
                            }
                    
                    
                }
            //$filename = 'MC'.$folder.'.txt';
            $filename = time();
            $localDisk->put($filename, $txt);

            $mc = new App\Payroll\MCProcess;
            $mc->payroll_mon = request()->mc_mon;
            $mc->payroll_year = request()->mc_year;
            $mc->txt_file = $filename;
            $mc->save();
        }
        else
        {
            return "folder already exist";
        }

        return redirect('payroll/mc/'.request()->mc_mon.'/'.request()->mc_year);
    }

}
