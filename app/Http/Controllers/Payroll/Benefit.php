<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App;
use Facade\FlareClient\Api;

class Benefit extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $data = 
                [
                    "nav" => nav("payrollbenefit"),
                ];
        return view('payroll.benefits')->with("data",$data);
    }

    public function create()
    {
        switch (request()->deduc_type) {
            case 1:
                    $benefit_type = "MID YEAR";
                break;
            case 2:
                    $benefit_type = "YEAR END";
                break;
            case 3:
                    $benefit_type = "PBB";
                break;
            
        }

        //CHECK IF EXIST
        $d = App\Payroll\Benefit_deduc::where('userid',request()->deduc_userid)->where('benefit_year',date('Y'))->where('benefit_type',$benefit_type)->first();
        if(isset($d))
        {
            App\Payroll\Benefit_deduc::where('userid',request()->deduc_userid)->where('benefit_year',date('Y'))->where('benefit_type',$benefit_type)->delete();
        }
        
        $deduc = new App\Payroll\Benefit_deduc;
        $deduc->benefit_year = date('Y');
        $deduc->benefit_type = $benefit_type;
        $deduc->deduc_amt = request()->deduc_val;
        $deduc->userid = request()->deduc_userid;
        $deduc->save();

        
        
        $data = 
                [
                    "nav" => nav("payrollbenefit"),
                ];
        return redirect('payroll/benefits')->with("data",$data);
    }

    public function removeBenefit()
    {
        switch (request()->remove_type) {
            case 1:
                    $benefit_type = "MID YEAR";
                break;
            case 2:
                    $benefit_type = "YEAR END";
                break;
            case 3:
                    $benefit_type = "PBB";
                break;
            
        }

        $remove = new App\Payroll\Benefit_remove;
        $remove->benefit_year = date('Y');
        $remove->benefit_type = $benefit_type;
        $remove->userid = request()->remove_userid;
        $remove->save();

        $data = 
                [
                    "nav" => nav("payrollbenefit"),
                ];

        return redirect('payroll/benefits')->with("data",$data);
    }

    public function process()
    {
        $insert = collect([]);

        switch (request()->proc_benefit_type) {
            case 1:
                    $benefit_type = "MID YEAR";
                break;
            case 2:
                    $benefit_type = "YEAR END";
                break;
            case 3:
                    $benefit_type = "PBB";
                break;
            
        }

        
        $user = App\User::whereIn('employment_id',[1,11,13,14,15])->get();
        foreach ($user as $key => $users) {

        if(!checkBenefitRemove('MID YEAR',date('Y'),$users->id))
          {
            $division = getDivision($users->division);

            $plantilla = getPlantillaInfo($users->username);
            
            if($plantilla)
            {
              $basic = $plantilla['plantilla_salary'];
            }
            else
            {
              $salary = 0;
            }

            //DEDUCTION
            $mid_deduc = App\Payroll\Benefit_deduc::where('userid',$users->id)->where('benefit_year',date('Y',))->where('benefit_type','MID YEAR')->first();
            if(isset($mid_deduc))
              $mid_deduc = $mid_deduc['deduc_amt'];
            else
              $mid_deduc = 0;
            
            $benefit_amt = $basic - $mid_deduc;

            $insert->push(['benefit_year' => date('Y'),'userid' => $users->id,'benefit_type' => $benefit_type,'benefit_amt' => $benefit_amt,'created_at' => date('Y-m-d H:i:s')]);
          }

          
        }

        App\Payroll\Benefit::insert($insert->all());

        $benefit = new App\Payroll\Benefit_process;
        $benefit->benefit_year = date('Y');
        $benefit->benefit_type = $benefit_type;
        $benefit->save();


        $data = 
                [
                    "nav" => nav("payrollbenefit"),
                ];

        return redirect('payroll/benefits')->with("data",$data);
    }


    public function print()
    {
        $pdf = App::make('dompdf.wrapper');
            $pdf->loadHTML('<!DOCTYPE html>
                                <html>
                                <head>
                                <title>HRMIS - COS PAYROLL</title>
                                <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                                </head>
                                <style type="text/css">
                                        @page {
                                        margin: 10;
                                        }
                                    body
                                    {
                                        font-family:Helvetica;
                                    }
                                    td
                                    {
                                        border:1px solid #555;
                                        font-size:11px;
                                    }
                                    .page-break {
                                        page-break-after: always;
                                    }
                                </style>
                                <body>
                                    <center><h4>Department of Science and Technology<br/><b>PHILIPPINE COUNCIL FOR AGRICULTURE, AQUATIC AND NATURAL RESOURCES
                                    RESEARCH AND DEVELOPMENT<br/></b> PAYMENT of 2023 Mid Year Bonus</h4></center>
                                    
                                    <table width="100%" cellspacing="0" cellpadding="0" border="0" style="font-size : 9px">
                                    <tr valign="top">
                                        <td style="border:0px solid #FFF">
                                            A. PREPARED BY: <br/><br/><br/><br/>
                                            <b>ROMMEL V. VISPERAS</b><br>Administrative Assistant III<br/><br/><br/><br/>
                                            C. CERTIFIED: Supporting documents complete<br/>and cash available in the amount of ________________________<br/><br/><br/><br/>
                                            <b>ABEGAIL GRACE M. MARALIT</b><br>Accountant III<br/><br/><br/><br/><br/>
                                            OBR No. ________________________<br/>
                                            DATE ________________________<br/>
                                            JEV No. ________________________<br/>
                                            DATE    ________________________<br/>
                                        </td>
                            
                                        <td style="border:0px solid #FFF">
                                            B. CERTIFIED CORRECT:  Positions exist with fixed compensation<br/><br/><br/><br/>
                                            <b>GEORGIA M. LAWAS</b><br>Administrative Officer V-HRMO<br/><br/><br/><br/>
                                            D. Approved for payment:____________________________<br/><br/><br/><br/>
                                            <b>REYNALDO V. EBORA</b><br>Executive Director<br/><br/><br/><br/>
                                            E. CERTIFIED CORRECT Each employee whose name appears above has been paid the<br/>amount indicated opposite on his/her name:<br/><br/><br/><br/>
                                            <b>HEIDELITA A. RAMOS</b><br>Cashier<br/><br/><br/><br/>
                                            
                                        </td>
                            
                                    </tr>
                                    </table>

                                    <div class="page-break"></div>
                                    <center><h4>Department of Science and Technology<br/><b>PHILIPPINE COUNCIL FOR AGRICULTURE, AQUATIC AND NATURAL RESOURCES
                                    RESEARCH AND DEVELOPMENT<br/></b> PAYMENT of 2023 Mid Year Bonus</h4></center>
                                </body>
                                </html>')
            ->setPaper('legal', 'landscape');
            return $pdf->stream();
    }
    
}
