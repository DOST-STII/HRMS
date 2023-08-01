<?php

namespace App\Http\Controllers\AttendanceMonitoring;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App;
use Carbon\Carbon;
use Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function report()
    {
        return view("dtr.admin.report");
    }

    public function dtrprocess($userid)
    {
        $dtr = App\DTRProcessed::where('userid',$userid)->orderBy('id','DESC')->first();

        $mon = date('F',mktime(0, 0, 0, $dtr['dtr_mon'], 10));
        $dt = "(".$mon." ".$dtr['dtr_year'].")";
        $code = $dtr['process_code'];

        $collect = collect([]);

        $collect->push(["date" => $dt,"process_code" => $code]);

        return json_encode($collect->all());
    }

    public function dtrReverse()
    {
        return view("dtr.admin.reverse");
    }
    
}
