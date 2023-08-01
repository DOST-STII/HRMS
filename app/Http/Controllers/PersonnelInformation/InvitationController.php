<?php

namespace App\Http\Controllers\PersonnelInformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Auth;

class InvitationController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['auth','admin']);
    }

    public function select($id)
    {
        $data = [
                    "detail" => App\View_vacant_plantilla::where('id',$id)->first(), 
                    "position" => App\Position::orderBy('position_desc')->get(),
                    "employee" => App\View_user::whereIn('employment_id',[1,13,14])->get(),
                ];
        return view('pis.admin.call-for-invitation')->with("data",$data); 
    }

    public function create()
    {
    	// return request()->selected;

    	$user = App\View_user::whereIn('id',request()->selected)->get();

    	foreach ($user as $users) {

    		$invitation = new App\Invitation;
        	$invitation->user_id = $users->id;
        	$invitation->vacant_plantilla_id = request()->plantilla_id;
       		$invitation->save();
    	}
        
        return redirect('recruitment/index');
    }

    public function update()
    {
        $eligibility = new App\Employee_eligibility;
        $eligibility = $eligibility
                        ->where('id',request()->tblid)
                        ->update([
                                    'cse_title' => request()->cse_title,
                                    'cse_rating' => request()->cse_rating,
                                    'cse_date' => request()->cse_date,
                                    'cse_place' => request()->cse_place,
                                    'cse_license_num' => request()->cse_license_num,
                                    'cse_license_date' => request()->cse_license_date,
                                ]);
    }

    public function delete()
    {
        $eligibility = new App\Employee_eligibility;
        $eligibility = $eligibility
                        ->where('id',request()->tblid)
                        ->delete();
    }

    public function json($id)
    {
        $eligibility = new App\Employee_eligibility;
        $eligibility = $eligibility
                        ->where('id',$id)
                        ->get();

        return json_encode($eligibility);
    }
}
