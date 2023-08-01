<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App;
use Carbon\Carbon;
use Auth;

class LeaveForm extends Controller
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
                        <center><h4>APPLICATION FOR LEAVE</h4></center>
                        <br>
                        <table border="1" width="100%" style="font-size:11px;" cellpadding="2" cellspacing="0">
                            <tr>
                                <table width="100%" style="font-size:11px;" cellpadding="2" cellspacing="0">
                                </table>
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
