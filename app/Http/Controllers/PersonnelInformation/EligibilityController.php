<?php

namespace App\Http\Controllers\PersonnelInformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Auth;

class EligibilityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        $eligibility = new App\Employee_eligibility;
        $eligibility->user_id = Auth::user()->id;
        $eligibility->cse_title = request()->cse_title;
        $eligibility->cse_rating = request()->cse_rating;
        $eligibility->cse_date = request()->cse_date;
        $eligibility->cse_place = request()->cse_place;
        $eligibility->cse_license_num = request()->cse_license_num;
        $eligibility->cse_license_date = request()->cse_license_date;
        $eligibility->save();
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
