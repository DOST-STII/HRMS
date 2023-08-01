<?php

namespace App\Http\Controllers\PersonnelInformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Auth;

class WorkController extends Controller
{
   
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        $work = new App\Employee_work;
        $work->user_id = Auth::user()->id;
        $work->workexp_date_from = request()->workexp_date_from;
        $work->workexp_date_to = request()->workexp_date_to;
        $work->workexp_title = request()->workexp_title;
        $work->workexp_company = request()->workexp_company;
        $work->workexp_salary = request()->workexp_salary;
        $work->workexp_salary_grade = request()->workexp_salary_grade;
        $work->workexp_empstatus = request()->workexp_empstatus;
        $work->workexp_gov = request()->workexp_gov;
        $work->save();
    }

    public function update()
    {
        
    }

    public function delete()
    {
        $work = new App\Employee_work;
        $work = $work
                        ->where('id',request()->tblid)
                        ->delete();
    }

    public function json($id)
    {
        $work = new App\Employee_work;
        $work = $work
                        ->where('id',$id)
                        ->get();

        return json_encode($work);
    }
}
