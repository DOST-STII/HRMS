<?php

namespace App\Http\Controllers\PersonnelInformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Auth;

class CompetencyTrainingController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        $comp = new App\Employee_competencies_training;
        $comp->user_id = Auth::user()->id;
        $comp->training_desc = request()->training_desc;
        $comp->save();
    }

    public function update()
    {
        $comp = new App\Employee_competencies_training;
        $comp = $comp
                        ->where('id',request()->tblid)
                        ->update([
                                    'training_desc' => request()->training_desc,
                                ]);
    }

    public function delete()
    {
        $comp = new App\Employee_competencies_training;
        $comp = $comp
                        ->where('id',request()->tblid)
                        ->delete();
    }

    public function json($id)
    {
        $comp = new App\Employee_competencies_training;
        $comp = $comp
                        ->where('id',$id)
                        ->get();

        return json_encode($comp);
    }

}
