<?php

namespace App\Http\Controllers\Submission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Auth;

class PageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
    	$data = 
    			[
    				"nav" => nav("submission"),
    				"list" => App\Submission::get(),
    			];
        return view('submission.admin.index')->with("data",$data);
    }

   	public function index2()
    {
    	$data = 
    			[
    				"nav" => nav("submission"),
    				"list" => App\View_submission_list::where('submission_division',Auth::user()->division)->whereNull('deleted_at')->get()
    			];
        return view('submission.marshal.index')->with("data",$data);
    }

    public function trainingreport()
    {
        $data = 
                [
                    "nav" => nav("submission"),
                    "list" => App\View_training_temp::whereIn('training_completed',[null,'Yes'])->get()
                ];
        return view('submission.admin.training-report-list')->with("data",$data);
    }

    public function trainingcertificate()
    {
        $data = 
                [
                    "nav" => nav("submission"),
                    "list" => App\View_training_temp::whereIn('training_completed',[null,'Yes'])->get()
                ];
        return view('submission.admin.training-certificate-list')->with("data",$data);
    }
}
