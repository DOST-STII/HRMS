<?php

namespace App\Http\Controllers\Submission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Auth;

class Submission extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
    	$coach = new App\Succession\Coaching;
        $coach->save(); 
    }

    public function update()
    {
        $submission = new App\Submission;
        $submission = $submission
                        ->where('id',request()->tblid)
                        ->update([
                                    'sub_report' => request()->update_report_type,
                                    'sub_remarks' => request()->update_report_remarks,
                                    'sub_deadline' => request()->update_report_deadline
                                ]);
    }

    public function delete()
    {
        $submission = new App\Submission;
        $submission = $submission
                        ->where('id',request()->tblid)
                        ->delete();

        //SUB
        $submission_list = new App\Submission_list;
        $submission_list = $submission_list
                        ->where('submission_id',request()->tblid)
                        ->delete();
    }

    public function json($id)
    {
        $submission = new App\Submission;
        $submission = $submission
                        ->where('id',$id)
                        ->get();

        return json_encode($submission);
    }

}
