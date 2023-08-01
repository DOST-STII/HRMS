<?php

namespace App\Http\Controllers\PersonnelInformation;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Auth;

class CaseController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Request $request)
    {
        //DELETE NA LANG PREVIOUS THEN INSERT PARA NO NEED PARA MAGUPDATE
        $chk = App\Employee_case::where('user_id',Auth::user()->id)->count();
        if($chk > 0)
            App\Employee_case::where('user_id',Auth::user()->id)->delete();

        $data = collect([]);

        //CASE 34a
        $data->push(['user_id' => Auth::user()->id,'case_admin' => 'case34a','case_ans' => request()->case34a,'case_remarks' => request()->case34a_remarks]);

        //CASE 34b
        $data->push(['user_id' => Auth::user()->id,'case_admin' => 'case34b','case_ans' => request()->case34b,'case_remarks' => request()->case34b_remarks]);

        //CASE 35a
        $data->push(['user_id' => Auth::user()->id,'case_admin' => 'case35a','case_ans' => request()->case35a,'case_remarks' => request()->case35a_remarks]);

        //CASE 36a
        $data->push(['user_id' => Auth::user()->id,'case_admin' => 'case36a','case_ans' => request()->case36a,'case_remarks' => request()->case36a_remarks]);

        //CASE 37a
        $data->push(['user_id' => Auth::user()->id,'case_admin' => 'case37a','case_ans' => request()->case37a,'case_remarks' => request()->case37a_remarks]);

        //CASE 38a
        $data->push(['user_id' => Auth::user()->id,'case_admin' => 'case38a','case_ans' => request()->case38a,'case_remarks' => request()->case38a_remarks]);

        //CASE 38b
         $data->push(['user_id' => Auth::user()->id,'case_admin' => 'case38b','case_ans' => request()->case38b,'case_remarks' => request()->case38b_remarks]);

        //CASE 39a
        $data->push(['user_id' => Auth::user()->id,'case_admin' => 'case39a','case_ans' => request()->case39a,'case_remarks' => request()->case39a_remarks]);

        //CASE 40a
        $data->push(['user_id' => Auth::user()->id,'case_admin' => 'case40a','case_ans' => request()->case40a,'case_remarks' => request()->case40a_remarks]);

        //CASE 40b
        $data->push(['user_id' => Auth::user()->id,'case_admin' => 'case40b','case_ans' => request()->case40b,'case_remarks' => request()->case40b_remarks]);

        //CASE 40c
        $data->push(['user_id' => Auth::user()->id,'case_admin' => 'case40c','case_ans' => request()->case40c,'case_remarks' => request()->case40c_remarks]);

        $lv = App\Employee_case::insert($data->all());

        return redirect('personal-information/other/cases');

    }

}
