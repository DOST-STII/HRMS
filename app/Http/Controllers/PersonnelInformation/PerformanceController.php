<?php

namespace App\Http\Controllers\PersonnelInformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Auth;
use Storage;

class PerformanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        $data = [
                    "nav" => nav("performance"),
                    "dpcr" => App\View_performance_group_dpcr::get(),
                ];

    	return view('pis.performance.index')->with("data",$data);
    }

    public function division()
    {

        $data = [
                    "nav" => nav("performance"),
                    "dpcr" => App\View_performance_group_dpcr_ipcr::where('division_id',Auth::user()->division)->whereNotNull('ipcr_submitted_at')->get(),
                ];

        return view('pis.performance.division')->with("data",$data);
    }

    public function ipcrcreate()
    {
        //CREATE IPCR
        // $ipcr = new App\Performance_ipcr;
        // $ipcr->ipcr_year = request()->ipcr_year;
        // $ipcr->ipcr_period= request()->ipcr_period;
        // $ipcr->ipcr_deadline = request()->ipcr_deadline;
        // $ipcr->save();
        // $ipcr_id = $ipcr->id;

        //SUBMIT REMINDER TO ALL STAFF
        $user = App\User::whereNotIn('usertype',['Administrator','Director'])->whereNotIn('employment_id',[9,10,12])->get();

        foreach ($user as $users) {
           $ipcr = new App\Performance_ipcr_staff;
           $ipcr->ipcr_year = request()->ipcr_year;
           $ipcr->ipcr_period= request()->ipcr_period;
           $ipcr->ipcr_deadline = request()->ipcr_deadline;
           $ipcr->user_id = $users->id;
           $ipcr->save();
        }

        // return redirect('performance/index');
    }

    public function dpcrsubmit()
    {
        //GET DPCR DETAIL
        $dpcrdetail = App\Performance_dpcr::where('division_id',Auth::user()->division)->whereNull('submitted_at')->first();

        $yr = $dpcrdetail['dpcr_year'];
        $period = $dpcrdetail['dpcr_period'];

        if(!Storage::exists('submission_file_dpcr/'.$yr.'/'.$period)) {

            Storage::makeDirectory('submission_file_dpcr/'.$yr.'/'.$period, 0775, true); //creates directory

        }

        //FILE
        $path = null;
        if(request()->hasFile('files_dpcr_file'))
            {
                $file = request()->file('files_dpcr_file')->getClientOriginalName();
                $path = request()->file('files_dpcr_file')->storeAs('submission_file_dpcr/'.$yr.'/'.$period,$file);
            }

        $dpcr = App\Performance_dpcr::where('division_id',Auth::user()->division)->whereNull('submitted_at')->get();
        foreach ($dpcr as $dpcrs) {
            # code...
            $up = App\Performance_dpcr::where('id',$dpcrs->id)
                    ->update([
                                'dpcr_score' => request()->dpcr_score,
                                'dpcr_file_path' => $path,
                                'submitted_at' => date('Y-m-d H:i:s')
                            ]);
        }
    }

    public function dpcrcreate()
    {
        $division = App\Division::where('type',1)->get();

        foreach ($division as $divisions) {

           $dpcr = new App\Performance_dpcr;
           $dpcr->dpcr_year = request()->ipcr_year;
           $dpcr->dpcr_period = request()->ipcr_period;
           $dpcr->dpcr_deadline = request()->ipcr_deadline;
           $dpcr->division_id = $divisions->division_id;
           $dpcr->division_acro = $divisions->division_acro;
           $dpcr->save();
           $dpcr_id = $dpcr->id;


           //SUBMIT REMINDER TO ALL STAFF
            $user = App\User::whereNotIn('usertype',['Administrator','Director'])->whereNotIn('employment_id',[9,10,12])->where('division',$divisions->division_id)->get();

            foreach ($user as $users) {
               $ipcr = new App\Performance_ipcr_staff;
               $ipcr->dpcr_id = $dpcr_id;
               $ipcr->user_id = $users->id;
               $ipcr->division_id = $divisions->division_id;
               $ipcr->save();
            }
        }

        

        // return redirect('performance/index');
    }

    public function ipcruploadstaff(Request $request)
    {

        $yr = request()->files_ipcr_year;
        $period = request()->files_ipcr_period;

        // if(!Storage::exists('submission_file_ipcr/'.$yr.'/'.$period)) {

        //     Storage::makeDirectory('submission_file_ipcr/'.$yr.'/'.$period, 0775, true); //creates directory

        // }

        $path_ipcr = null;
        if(request()->hasFile('files_ipcr_file'))
        {
            $path = request()->file('files_ipcr_file')->store('other_files');
            $path = explode('/',$path);
            $request->files_ipcr_file->move(public_path('storage/ipcr'), $path[1]);
            $path_ipcr = $path[1];
        }

        //FILE
        // $path = null;
        // if(request()->hasFile('files_ipcr_file'))
        //     {
        //         $file = request()->file('files_ipcr_file')->getClientOriginalName();
        //         $path = request()->file('files_ipcr_file')->storeAs('submission_file_ipcr/'.$yr.'/'.$period,$file);
        //     }

        //CHECK IF FILE EXIST
        $query = App\View_performance_group_dpcr_ipcr::where('dpcr_year',request()->files_ipcr_year)->where('dpcr_period',request()->files_ipcr_period)->where('user_id',Auth::user()->id)->first();
        // $ctr = count($query);

        if(isset($query))
        {
    
            $ipcr = App\Performance_ipcr_staff::where('id',$query['id'])->where('user_id',Auth::user()->id)
                    ->update([
                                'ipcr_year' => request()->files_ipcr_year,
                                'ipcr_period' => request()->files_ipcr_period,
                                'ipcr_score' => request()->files_ipcr_score,
                                'ipcr_file_path' => $path_ipcr,
                                'ipcr_submitted_at' => date("Y-m-d H:i:s")
                            ]);
        }
        else
        {
            $ipcr = new App\Performance_ipcr_staff;
            $ipcr->ipcr_year= request()->files_ipcr_year;
            $ipcr->ipcr_period= request()->files_ipcr_period;
            $ipcr->ipcr_score= request()->files_ipcr_score;
            $ipcr->ipcr_file_path = $path_ipcr;
            $ipcr->ipcr_submitted_at = date("Y-m-d H:i:s");
            $ipcr->user_id = Auth::user()->id;
            $ipcr->save();
        }
    }

    public function jsondpcr($yr,$period)
    {
        $dpcr = App\View_performance_dpcr_ipcr_count::where('dpcr_year',$yr)->where('dpcr_period',$period)->whereNotNull('submitted_at')->orderBy('dpcr_score')->get(); 
        return json_encode($dpcr);
    }

    public function ipcrdelete()
    {
        App\Performance_ipcr_staff::where('id',request()->tblid)->delete();
    }
}
