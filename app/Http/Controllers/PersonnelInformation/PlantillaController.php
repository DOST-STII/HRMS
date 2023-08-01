<?php

namespace App\Http\Controllers\PersonnelInformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App;
use Auth;

class PlantillaController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['auth','admin']);
    }

    public function index()
    {
        
        $data = [
                    "nav" => nav("vacant"),
                    "employee" => App\User::orderBy('username')->where('employment_id',[1,13,14])->get(),
                    'plantilla' => App\View_vacant_plantilla::orderBy('plantilla_item_number')->get(),
                    "position" => App\Position::orderBy('position_abbr')->get(),
                    "designation" => App\Designation::orderBy('designation_desc')->get(),
                    "division" => App\Division::orderBy('division_acro')->get(),
                ];

        return view('pis.admin.vacant-position')->with("data",$data);
    }

    public function create()
    {
        $plantilla = new App\Vacant_plantilla;
        $plantilla->plantilla_item_number = request()->item_number;
        $plantilla->division_id = request()->p_division;
        $plantilla->plantilla_salary = request()->salary;
        $plantilla->position_id = request()->position;
        $plantilla->plantilla_special = request()->special;
        $plantilla->plantilla_steps = request()->steps;
        $plantilla->save();
    }

    public function delete()
    {
        $plantilla = new App\Vacant_plantilla;
        $plantilla = $plantilla
                        ->where('id',request()->plantillaId)
                        ->delete();
    }

    public function repost()
    {
        $plantilla = new App\Vacant_plantilla;
        $plantilla = $plantilla
                        ->where('id',request()->plantillaid)
                        ->update([
                                    'request_for_hiring_id' => null,
                                    'plantilla_posted' => null,
                                ]);
        //RESET LETTER
        App\Request_for_hiring::where('id',request()->letterid)
                                ->update([
                                            'request_status' => 'Re-upload Vacancy Advise for Reposting',
                                            'request_seen' => null,
                                        ]);

        $history = new App\Recruitment_history;
        $history->request_id = request()->letterid;
        $history->request_status = "Re-upload Vacancy Advise for Reposting";
        $history->created_by = getDivision(Auth::user()->division);
        $history->save();

        //RESET APPLICANTS
        App\Applicant_position_apply::where('vacant_plantilla_id',request()->plantillaid)->delete();

        //RESET Invitation
        App\Invitation::where('vacant_plantilla_id',request()->plantillaid)->delete();
        
    }
    
    public function update()
    {
        $plantilla = new App\Vacant_plantilla;
        $plantilla = $plantilla
                        ->where('id',request()->plantillaId)
                        ->update([
                                    'plantilla_item_number' => request()->item_number,
                                    'plantilla_salary' => request()->salary,
                                    'position_id' => request()->position,
                                    'division_id' => request()->p_division,
                                    'plantilla_special' => request()->special,
                                    'plantilla_steps' => request()->steps,
                                ]);
    }

    public function assign()
    {
        //GET PLANTILLA DETAILS
        $plantilla = App\Vacant_plantilla::where('id',request()->plantillaId)->first();

        //CHECK IF THERES AN EXISTING PLANTILLA
        $ctr = App\Plantilla::where('user_id',request()->user_id)->count();

        //UPDATE DIVISION IN CASE NA MALIPAT BASE SA item_number
        $users = App\User::where('id',request()->user_id)
                 ->update([
                            'division' => request()->division,
                            'employment_id' => 1,
                          ]);
        if($ctr > 0)
        {
            $plantilla_update = new App\Plantilla;
            $plantilla_update = $plantilla_update
                        ->where('user_id',request()->user_id)
                        ->update([
                                    'plantilla_item_number' => $plantilla['plantilla_item_number'],
                                    'plantilla_salary' => $plantilla['plantilla_salary'],
                                    'position_id' => $plantilla['position_id'],
                                    'employment_id' => 1,
                                    'plantilla_special' => $plantilla['plantilla_special'],
                                    'plantilla_step' => $plantilla['plantilla_steps'],
                                    'designation_id' => request()->designation,
                                    'plantilla_date_from' => request()->date_from,
                                    'plantilla_division' => request()->division,
                                ]);

            //UPDATE PLANTILLA HISTORY, GET THE LAST ENTRY
            $plantilla_history = new App\Plantillas_history;
            $plantilla_history = $plantilla_history
                        ->where('user_id',request()->user_id)
                        ->orderBy('id','desc')
                        ->take(1)
                        ->update([
                                    'plantilla_date_to' => request()->date_from,
                                ]);
        }
        else
        {
            $plantilla_new = new App\Plantilla;
            $plantilla_new->user_id = request()->user_id;
            $plantilla_new->plantilla_item_number = $plantilla['plantilla_item_number'];
            $plantilla_new->plantilla_salary = $plantilla['plantilla_salary'];
            $plantilla_new->position_id = $plantilla['position_id'];
            $plantilla_new->employment_id = 1;
            $plantilla_new->plantilla_special = $plantilla['plantilla_special'];
            $plantilla_new->plantilla_step = $plantilla['plantilla_steps'];
            $plantilla_new->designation_id = request()->designation;
            $plantilla_new->plantilla_date_from = request()->date_from;
            $plantilla_new->plantilla_division = request()->division;
            $plantilla_new->save();
        }

        //ADD PLANTILLA HISTORY
        $plantilla_history = new App\Plantillas_history;
        $plantilla_history->user_id = request()->user_id;
        $plantilla_history->plantilla_item_number = $plantilla['plantilla_item_number'];
        $plantilla_history->plantilla_salary = $plantilla['plantilla_salary'];
        $plantilla_history->position_id = $plantilla['position_id'];
        $plantilla_history->employment_id = 1;
        $plantilla_history->plantilla_special = $plantilla['plantilla_special'];
        $plantilla_history->plantilla_step = $plantilla['plantilla_steps'];
        $plantilla_history->designation_id = request()->designation;
        $plantilla_history->plantilla_date_from = request()->date_from;
        $plantilla_history->plantilla_division = request()->division;
        $plantilla_history->save();

        //DELETE PLANTILLA
        App\Vacant_plantilla::where('id',request()->plantillaId)->delete();


        //CHECK IF THERES AN EXISTING JO I-KO-CLOSE LANG
        $ctr2 = App\Employee_temp_position::where('user_id',request()->user_id)->whereNull('workexp_date_to')->count();

        if($ctr2 > 0)
        {
            $workemp = new App\Employee_temp_position;
            $workemp = $workemp
                        ->where('user_id',request()->user_id)
                        ->update([
                                    'workexp_date_to' => request()->date_from,
                                ]);

            $workemp_history = new App\Employee_temp_position_history;
            $workemp_history = $workemp_history
                        ->where('user_id',request()->user_id)
                        ->orderBy('id','desc')
                        ->take(1)
                        ->update([
                                    'workexp_date_to' => request()->date_from,
                                ]);

        }
    }

    public function json($id)
    {
        $plantilla = new App\Vacant_plantilla;
        $plantilla = $plantilla
                        ->where('id',$id)
                        ->get();

        return json_encode($plantilla);
    }
}
