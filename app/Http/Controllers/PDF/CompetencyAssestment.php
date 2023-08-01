<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App;
use Carbon\Carbon;
use Auth;

class CompetencyAssestment extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML('<!DOCTYPE>
                        <html>
                        <head>
                            <title>Leave Form</title>
                        </head>
                        <style type="text/css">
                                body
                                {
                                    font-family: DejaVu Sans;
                                }
                            </style>
                        <body>
                        <table border="1" width="100%" style="font-size:11px;" cellpadding="2" cellspacing="0">
                            <tr>
                                <td align="center" rowspan="3"><img src="'.asset('img/DOST.png').'" style="width:70px"></td>
                                <td style="font-size:10px"><center><b>PHILIPPINE COUNCIL FOR AGRICULTURE, AQUATIC AND NATURAL RESOURCES RESEARCH AND DEVELOPMENT</b></center></td>
                                <td style="font-size:10px">DOCUMENT NUMBER</td>
                                <td style="font-size:10px">QMSF-FADPS-07-01-10</td>
                            </tr>
                            <tr>
                                <td rowspan="3" style="font-size:15px"><center><b>COMPETENCY ASSESMENT</b></center></td>
                                <td style="font-size:10px">REVISION NUMBER</td>
                                <td><center>0</center></td>
                            </tr>
                            <tr>
                                <td style="font-size:10px">PAGE NUMBER</td>
                                <td><center>1/2</center></td>
                            </tr>
                            <tr>
                                <td><center><b>TITLE</b></center></td>
                                <td style="font-size:10px">EFFECTIVITY DATE</td>
                                <td><center>May 2, 2018</center></td>
                            </tr>
                        </table>
                            
                        </body>
                        </html>')
        ->setPaper('legal', 'portrait');
        return $pdf->stream();
    }

    private function getTO($div)
    {
        $collection = collect(App\RequestTO::where('division',$div)->where('to_status','!=','Pending')->get());
        return $collection->all();
    }
}
