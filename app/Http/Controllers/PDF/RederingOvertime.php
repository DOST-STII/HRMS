<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App;
use Carbon\Carbon;
use Auth;

class RederingOvertime extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        //GET ALL DIVISION

        $tr = "";
        foreach (getDivisionList() as $key => $value) {
          $tr .= "<tr><td>".$values."</td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
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
                                    <h4 style="font-size:15px">
                                        PHILIPPINE COUNCIL FOR AGRICULTURE, AQUATIC AND NATURAL RESOURCES<br/>
                                        RESEARCH AND DEVELOPMENT<br/>
                                        Los Baños, Laguna
                                        <br>
                                        <br>
                                        <b>NUMBER OF STAFF RENDERING OVERTIME</b>
                                        <br>
                                        '.request()->year.'
                                    </h4>
                                </center>
                                <table width="100%" cellspacing="0" cellpadding="5">
                                <tr>
                                  <td align="center"><b>Division</b></td>
                                  <td align="center"><b>JAN</b></td>
                                  <td align="center"><b>FEB</b></td>
                                  <td align="center"><b>MAR</b></td>
                                  <td align="center"><b>APR</b></td>
                                  <td align="center"><b>MAY</b></td>
                                  <td align="center"><b>JUN</b></td>
                                  <td align="center"><b>JUL</b></td>
                                  <td align="center"><b>AUG</b></td>
                                  <td align="center"><b>SEP</b></td>
                                  <td align="center"><b>OCT</b></td>
                                  <td align="center"><b>NOV</b></td>
                                  <td align="center"><b>DEC</b></td>
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

    
}
