<?php

namespace App\Http\Controllers\PersonnelInformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;

class RecruitmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {

        $data = [
                    "nav" => nav("recruit"),
                    "division" => App\Division::orderBy('division_acro')->get(),
                    "position" => App\Position::orderBy('position_abbr')->get(),
                    "request_list" => App\Request_for_hiring::whereNotIn('request_status',['Disapproved','Closed'])->get(),
                ];

    	return view('pis.recruitment.index')->with("data",$data);
    }

    public function vacant()
    {

        $data = [
                    "nav" => nav("vacant"),
                    "employee" => App\View_all_users::orderBy('username')->get(),
                    'plantilla' => App\View_vacant_plantilla::orderBy('plantilla_item_number')->get(),
                    "position" => App\Position::orderBy('position_abbr')->get(),
                    "designation" => App\Designation::orderBy('designation_desc')->get(),
                    "division" => App\Division::orderBy('division_acro')->get(),
                ];

        return view('pis.recruitment.list-vacant-position')->with("data",$data);
    }
}
