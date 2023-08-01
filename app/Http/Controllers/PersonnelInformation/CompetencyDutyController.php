<?php

namespace App\Http\Controllers\PersonnelInformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Auth;

class CompetencyDutyController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        $comp = new App\Employee_competencies_duty;
        $comp->user_id = Auth::user()->id;
        $comp->task = request()->task;
        $comp->task_percent = request()->task_percent;
        $comp->save();
    }

    public function update()
    {
        $comp = new App\Employee_competencies_duty;
        $comp = $comp
                        ->where('id',request()->tblid)
                        ->update([
                                    'task' => request()->task,
                                    'task_percent' => request()->task_percent,
                                ]);
    }

    public function delete()
    {
        $comp = new App\Employee_competencies_duty;
        $comp = $comp
                        ->where('id',request()->tblid)
                        ->delete();
    }

    public function json($id)
    {
        $comp = new App\Employee_competencies_duty;
        $comp = $comp
                        ->where('id',$id)
                        ->get();

        return json_encode($comp);
    }

}
