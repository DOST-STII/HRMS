<?php

namespace App\Http\Controllers\PersonnelInformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Auth;

class RecognitionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        $recognition = new App\Employee_nonacademic;
        $recognition->user_id = Auth::user()->id;
        $recognition->academic_desc = request()->recognition_desc;
        $recognition->save();
    }

    public function update()
    {
        $recognition = new App\Employee_nonacademic;
        $recognition = $recognition
                        ->where('id',request()->tblid)
                        ->update([
                                    'academic_desc' => request()->recognition_desc,
                                ]);
    }

    public function delete()
    {
        $recognition = new App\Employee_nonacademic;
        $recognition = $recognition
                        ->where('id',request()->tblid)
                        ->delete();
    }

    public function json($id)
    {
        $recognition = new App\Employee_nonacademic;
        $recognition = $recognition
                        ->where('id',$id)
                        ->get();

        return json_encode($recognition);
    }
}
