<?php

namespace App\Http\Controllers\PersonnelInformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Auth;


class EducationController extends Controller
{
   public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        $education = new App\Employee_education;
        $education->user_id = Auth::user()->id;
        $education->educ_level = request()->educ_level;
        $education->educ_level_desc = request()->educ_level;
        $education->educ_school = request()->educ_school;
        $education->educ_course = request()->educ_course;
        $education->educ_highest = request()->educ_highest;
        $education->educ_date_from = request()->educ_date_from;
        $education->educ_date_to = request()->educ_date_to;
        $education->educ_awards = request()->educ_awards;
        $education->save();
    }

    public function update()
    {
        $education = new App\Employee_education;
        $education = $education
                        ->where('id',request()->tblid)
                        ->update([
                                    'educ_level' => request()->educ_level,
                                    'educ_level_desc' => request()->educ_level,
                                    'educ_school' => request()->educ_school,
                                    'educ_course' => request()->educ_course,
                                    'educ_date_from' => request()->educ_date_from,
                                    'educ_date_to' => request()->educ_date_to,
                                    'educ_highest' => request()->educ_highest,
                                    'educ_awards' => request()->educ_awards,
                                ]);
    }

    public function delete()
    {
        $education = new App\Employee_education;
        $education = $education
                        ->where('id',request()->tblid)
                        ->delete();
    }

    public function json($id)
    {
        $education = new App\Employee_education;
        $education = $education
                        ->where('id',$id)
                        ->get();

        return json_encode($education);
    }
}
