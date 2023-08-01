<?php

namespace App\Http\Controllers\PersonnelInformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Auth;

class AddressPermanentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        $address = new App\Employee_add_permanent;
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
