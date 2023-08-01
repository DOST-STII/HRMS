<?php

namespace App\Http\Controllers\PersonnelInformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Auth;

class RewardsRecognitionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $yrservice = collect(App\View_users_years_service::get());
        $data = [
                    "nav" => nav("award"),
                    "yrservice" => $yrservice->all(),
                ];

    	return view('pis.awardsrecognition.index')->with("data",$data);
    }
}
