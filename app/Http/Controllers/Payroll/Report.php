<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;

class Report extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $data = 
                [
                    "nav" => nav("payrollreport"),
                ];
        return view('payroll.reports')->with("data",$data);
    }
    
    public function remittance()
    {
        $data = 
                [
                    "nav" => nav("payrollremittance"),
                ];
        return view('payroll.remittance')->with("data",$data);
    }

    public function mc($mon,$yr)
    {
        $data = 
                [
                    "nav" => nav("payrollmc"),
                    "mon" => $mon,
                    "yr" => $yr
                ];
        return view('payroll.mc')->with("data",$data);
    }

    public function mcpending($mon,$yr)
    {
        $emp = App\User::where('id','!=',374)->whereIn('employment_id',[1,11,13,14])->orderBy('division')->orderBy('lname')->orderBy('fname')->get();
        $arr = array();
        foreach ($emp as $key => $value) {
            $mc = App\Payroll\MC::where('payroll_mon',$mon)->where('payroll_yr',$yr)->where('userid',$value->id)->count();
            if($mc == 0)
            {
                $name = $value->lname . ", ". $value->fname . " ". $value->mname ." - ".getDivision($value->division);
                array_push($arr,$name);
            }
        }

        return json_encode($arr);
    }

    public function mcdeduc($id,$col)
    {
        $mc = App\Payroll\MC::where('id',$id)->first();

        return json_encode($mc[$col]);
    }

    public function mcdeducedit()
    {
        $col = request()->mcvalcol;
        $userid = request()->mcuser;

        $empcode = getStaffInfo(request()->mcuser,'empcode');

        $mc = App\Payroll\MC::where('id',request()->mcvalid)
                            ->update([
                                $col => request()->mcval
                            ]);

        //UPDATE LP
        if($col == 'lp')
        {
            //DELETE OLD
            App\Payroll\LP::where('userid',$userid)->orderBy('id','DESC')->limit(1)->delete();

            //ADD NEW
            $new_lp = new App\Payroll\LP;
            $new_lp->userid = $userid;
            $new_lp->empcode = $empcode;
            $new_lp->lp = request()->mcval;
            $new_lp->save();
        }

        //UPDATE ITW
        if($col == 'itw')
        {
            //DELETE OLD
            App\Payroll\ITW::where('userid',$userid)->orderBy('id','DESC')->limit(1)->delete();

            //ADD NEW
            $new_itw = new App\Payroll\ITW;
            $new_itw->userid = $userid;
            $new_itw->empcode = $empcode;
            $new_itw->itw = request()->mcval;
            $new_itw->save();
        }

        //UPDATE LOANS
        $serv = null;

        if($col != 'lp' && $col != 'itw' && $col != 'sa' && $col != 'la' && $col != 'hprate')
        {
            switch($col)
            {
                case "hmo":
                    $serv = "933";
                    $org = 6;
                break;
                case "gsis":
                    $org = 1;
                break;
                case "gfal":
                    $serv = "319A";
                    $org = 1;
                break;
                case "pmpc":
                    $serv = "931";
                    $org = 6;
                break;
                case "cdc":
                    $serv = "921";
                    $org = 5;
                break;
                case "landbank":
                    $serv = "321";
                    $org = 13;
                break;
            }

            //UPDATE ORIG DATA

            //DELETE FIRST OLD DATA
            if($col == 'gsis')
            {
                $mc = App\Payroll\MCDeduc::where('fldEmpCode',$empcode)->where('ORG_CODE',1)->where('SERV_CODE','!=','319A')->delete();
            }
            else
            {
                $mc = App\Payroll\MCDeduc::where('fldEmpCode',$empcode)->where('ORG_CODE',$org)->where('SERV_CODE',$serv)->delete();
            }

            //ADD UPDATED DATA
            $mc = new App\Payroll\MCDeduc;
            $mc->fldEmpCode = $empcode;
            $mc->ORG_CODE = $org;
            $mc->SERV_CODE = $serv;
            $mc->MC_AMOUNT = request()->mcval;
            $mc->save();
        }



        return redirect('payroll/mc/'.request()->mcmon.'/'.request()->mcyr);
    }

    public function deducjson($empcode,$id)
    {
        $deduc = App\Payroll\Deduction::where('empCode',$empcode)->where('deductID',$id)->first();

        return json_encode($deduc['deductAmount']);
    }

    public function loanjson($empcode,$id)
    {
        $deduc = App\Payroll\Empdeduc::where('fldEmpCode',$empcode)->where('SERV_CODE',$id)->first();

        return json_encode($deduc['DED_AMOUNT']);
    }

    public function compjson($empcode,$id)
    {
        $comp = App\Payroll\Empcomp::where('empCode',$empcode)->where('compID',$id)->first();
        if($comp)
            return json_encode($comp['compAmount']);
        else
            return 0;
    }


    public function deducloan()
    {
        
    }


    public function deducmandaloan()
    {

        if(request()->deduc_type == 1)
        {
            //DELETE LAST ENTRY
            App\Payroll\Empdeduc2::where('empCode',request()->deduc_username)->where('deductID',request()->org_serv)->orderBy('id','desc')->limit(1)->delete();

            //ADD NEW ENTRY
            $manda = new App\Payroll\Empdeduc2;
            $manda->empCode = request()->deduc_username;
            $manda->deductID = request()->org_serv;
            $manda->deductAmount = request()->deduc_val;
            $manda->save();
        }
        elseif(request()->deduc_type == 2)
        {
            $info = App\Payroll\Empdeduc::where('fldEmpCode',request()->deduc_username)->where('SERV_CODE',request()->org_serv)->orderBy('id','desc')->limit(1)->first();
        
            //DELETE LAST ENTRY
            App\Payroll\Empdeduc::where('fldEmpCode',request()->deduc_username)->where('SERV_CODE',request()->org_serv)->orderBy('id','desc')->limit(1)->delete();

            //ADD NEW ENTRY
            $loan = new App\Payroll\Empdeduc;
            $loan->fldEmpCode = request()->deduc_username;
            $loan->ORG_CODE = $info['ORG_CODE'];
            $loan->SERV_CODE = $info['SERV_CODE'];
            $loan->SERV_NO = $info['SERV_NO'];
            $loan->DED_AMOUNT = request()->deduc_val;
            $loan->save();
            
        }
        elseif(request()->deduc_type == 3)
        {
            $info = App\Payroll\OrgServ::where('SERV_CODE',request()->org_serv)->first();

            //ADD NEW ENTRY
            $loan = new App\Payroll\Empdeduc;
            $loan->fldEmpCode = request()->deduc_username;
            $loan->ORG_CODE = $info['ORG_CODE'];
            $loan->SERV_CODE = $info['SERV_CODE'];
            $loan->SERV_NO = $info['SERV_NO'];
            $loan->DED_AMOUNT = request()->deduc_val;
            $loan->save();
        }
        elseif(request()->deduc_type == 4)
        {
            //DELETE LAST ENTRY
            App\Payroll\Empcomp::where('empCode',request()->deduc_username)->where('compID',request()->comp_id)->orderBy('id','desc')->limit(1)->delete();

            //ADD NEW ENTRY
            $comp = new App\Payroll\Empcomp;
            $comp->empCode = request()->deduc_username;
            $comp->compID = request()->comp_id;
            $comp->compAmount = request()->deduc_val;
            $comp->save();
        }
        
        return redirect('payroll/process');
    }

}
