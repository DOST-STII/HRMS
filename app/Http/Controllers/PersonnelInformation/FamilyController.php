<?php

namespace App\Http\Controllers\PersonnelInformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Auth;

class FamilyController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function check(Request $request)
    {
        if(App\Employee_family::where('user_id',Auth::user()->id)->count() > 0)
        {
       		$this->update($request);
        }
        else
        {
        	$this->create($request);
        }
    }

    public function create(Request $request)
    {
    	$family = new App\Employee_family;
    	$family->user_id = Auth::user()->id;
    	$family->fam_spouse_lname = $request->spouse_lname;
    	$family->fam_spouse_fname = $request->spouse_fname;
    	$family->fam_spouse_mname = $request->spouse_mname;
    	$family->fam_spouse_exname = $request->spouse_exname;
    	$family->fam_spouse_occ = $request->spouse_occ;
    	$family->fam_spouse_emp = $request->spouse_emp;
    	$family->fam_spouse_emp_add = $request->spouse_emp_add;
    	$family->fam_spouse_tel = $request->spouse_tel;
    	$family->fam_father_lname = $request->father_lname;
    	$family->fam_father_fname = $request->father_fname;
    	$family->fam_father_mname = $request->father_mname;
    	$family->fam_father_exname = $request->father_exname;
    	$family->fam_mother_lname = $request->mother_lname;
    	$family->fam_mother_fname = $request->mother_fname;
    	$family->fam_mother_mname = $request->mother_mname;
    	$family->save();
    }


    public function update(Request $request)
    {
    	$family = new App\Employee_family;
    	$family = $family
                        ->where('user_id',Auth::user()->id)
                        ->update([
                                    'fam_spouse_lname' => $request->spouse_lname,
                                    'fam_spouse_fname' => $request->spouse_fname,
                                    'fam_spouse_mname' => $request->spouse_mname,
                                    'fam_spouse_exname' => $request->spouse_exname,
                                    'fam_spouse_occ' => $request->spouse_occ,
                                    'fam_spouse_emp' => $request->spouse_emp,
                                    'fam_spouse_emp_add' => $request->spouse_emp_add,
                                    'fam_spouse_tel' => $request->spouse_tel,
                                    'fam_father_lname' => $request->father_lname,
                                    'fam_father_fname' => $request->father_fname,
                                    'fam_father_mname' => $request->father_mname,
                                    'fam_father_exname' => $request->father_exname,
                                    'fam_mother_lname' => $request->mother_lname,
                                    'fam_mother_fname' => $request->mother_fname,
                                    'fam_mother_mname' => $request->mother_mname
                                ]);
    }

    public function json($id)
    {
        $family = new App\Employee_family;
        $family = $family
                        ->where('user_id',$id)
                        ->get();

        return json_encode($family);
    }
}
