<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App;
use Carbon\Carbon;
use Auth;

class TravelOrder extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $mon = date('F',mktime(0, 0, 0, request()->mon1, 10));

        //GET ALL STAFF

        $tr = "";
        $tos = $this->getTO(request()->division,request()->mon1,request()->year);

        foreach ($tos as $key => $value) {

            $dt = $value->to_date_from;
            if($value->parent == 'YES')
            {
                if($value->to_date_from != $value->to_date_to)
                    $dt = $value->to_date_from." - ".$value->to_date_to;
            } 

            $tr .= "<tr>
                        <td>".$value->employee_name."</td>
                        <td align='center'>".$dt."</td>
                        <td>".$value->to_place."</td>
                        <td>".$value->to_purpose."</td>
                    </tr>";
        }

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML('<!DOCTYPE html>
                            <html>
                            <head>
                              <title>HRMIS - REPORT</title>
                              <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                            </head>
                            <style type="text/css">
                               @page { margin: 20px; }
                                body
                                {
                                    font-family:Helvetica;
                                    margin: 0px; 
                                }
                                th,td
                                {
                                    border:1px solid #555;
                                    font-size:11px;
                                }
                            </style>
                            <body>
                                <p align="right" border="0" style="font-size:8px">Date Printed : '.Carbon::now().'</p>
                                <center>
                                    <h4 style="font-size:12px">
                                        Republic of the Philippines<br/>
                                        PHILIPPINE COUNCIL FOR AGRICULTURE, AQUATIC AND NATURAL RESOURCES<br/>
                                        RESEARCH AND DEVELOPMENT<br/>
                                        Los Baños, Laguna
                                        <br>
                                        <br>
                                        <b>TRAVEL ORDER</b> 
                                        '.$mon.' '.request()->year.'
                                    </h4>
                                </center>
                                <table width="100%" cellspacing="0" cellpadding="5">
                                <tr>
                                  <td align="center" style="width:30%"><b>EMPLOYEE NAME</b></td>
                                  <td align="center"><b>DATE</b></td>
                                  <td align="center"><b>PLACE</b></td>
                                  <td align="center"><b>PURPOSE</b></td>
                                </tr>
                                <tr>
                                    <td colspan="4"><b>Division : '.getDivision(request()->division).'</b></td>
                                </tr>
                                '.$tr.'
                                </table>
                                <br>
                                <br>
                                <br>

                                <table width="100%" cellspacing="0" cellpadding="5" style="font-size:15px!important">
                                <tr>
                                  <td style="width:50%;border: 1px solid #FFF">
                                    Prepared by : <br/>
                                    <b>Marshal</b>
                                  </td>
                                  <td style="border: 1px solid #FFF">
                                    Verified Correct : <br/>
                                    <b>Director</b>
                                  </td>
                                </tr>
                                </table>
                            </body>
                            </html>')
        ->setPaper('legal', 'landscape');
        return $pdf->stream();
    }

    private function getTO($div,$mon,$yr)
    {
        $collection = collect(App\RequestTO::where('division',$div)->whereMonth('to_date_from',$mon)->whereYear('to_date_from',$yr)->whereIn('to_status',['Approved','Processed'])->orderBy('employee_name')->orderBy('id','DESC')->orderBy('to_date_from')->get());
        return $collection->all();
    }
}
