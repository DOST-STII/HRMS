<?php

namespace App\Http\Controllers\PersonnelInformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Auth;

class CompetencyController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        $comp = new App\Employee_competency;
        $comp->user_id = Auth::user()->id;
        $comp->competency_desc = request()->competency_desc;
        $comp->competency_job = request()->competency_job;
        $comp->competency_skill = request()->competency_skill;
        $comp->division = Auth::user()->division;
        $comp->save();
    }

    public function update()
    {
        $comp = new App\Employee_competency;
        $comp = $comp
                        ->where('id',request()->tblid)
                        ->update([
                                    'competency_desc' => request()->competency_desc,
                                    'competency_job' => request()->competency_job,
                                    'competency_skill' => request()->competency_skill,
                                ]);
    }

    public function delete()
    {
        $comp = new App\Employee_competency;
        $comp = $comp
                        ->where('id',request()->tblid)
                        ->delete();
    }

    public function json($id)
    {
        $comp = new App\Employee_competency;
        $comp = $comp
                        ->where('id',$id)
                        ->get();

        return json_encode($comp);
    }

    public function core()
    {
       $retiree = App\View_users_with_age::where('age','>=',60)->where('division',Auth::User()->division)->get();
       $comp = App\View_core_division_competency::where('division',Auth::User()->division)->orderBy('core_desc')->get();

       $data = [
                    'retiree' => $retiree,
                    'competency' => $comp
                ];
       return view('pis.competency.index')->with('data',$data); 
    }

    public function create2()
    {
        $comp = new App\Core_competency;
        $comp->core_desc = request()->competency_desc;
        $comp->division = Auth::user()->division;
        $comp->save();

        return redirect('core-competency');
    }

    public function update2()
    {
        $comp = new App\Core_competency;
        $comp = $comp
                        ->where('id',request()->tblid)
                        ->update([
                                    'competency_desc' => request()->competency_desc,
                                ]);
    }

    public function delete2()
    {
        $comp = new App\Core_competency;
        $comp = $comp
                        ->where('id',request()->tblid)
                        ->delete();
    }

    public function json2($id)
    {
        $comp = new App\Core_competency;
        $comp = $comp
                        ->where('id',$id)
                        ->get();

        return json_encode($comp);
    }
}
