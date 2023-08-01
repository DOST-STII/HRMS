<?php

namespace App\Http\Controllers\PersonnelInformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App;

class MarshalController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','marshal']);
    }

    public function requestHiring()
    {
    	$data = [
                    "nav" => nav("hiring"),
                    "vacant" => App\View_vacant_plantilla::whereNull('plantilla_posted')->get(),
                    "request_list" => App\Request_for_hiring::where('request_division',Auth::user()->division)->whereNotIn('request_status',['Disapproved','Closed'])->get(),
                    "training_list" => App\Employee_training::where('division_id',Auth::user()->division)->orderBy('id','desc')->get(),
                    "training_request" => App\Employee_training_temp::where('division_id',Auth::user()->division)->orderBy('id','desc')->get(),
                    "hrd_list" => App\View_hrd_division::where('division_id',Auth::user()->division)->get(),
                ];

        return view('pis.marshal.request-for-hiring')->with("data",$data);
    }

    public function divuploadhrd()
    {  
        $path = null;
        if(request()->hasFile('file_hrd'))
        {
            $path = request()->file('file_hrd')->store('hrd_file_division');
        }

        App\HRD_plan_division::where('id',request()->tbl_id)
                            ->update([
                                        'hrd_file_path' => $path,
                                        'hrd_file_uploaded' => date('Y-m-d H:i:s'),
                                    ]);

        return redirect('letter-request');
    }

    public function downloadFile($url,$file)
    {
        //$file = explode('/',$url);
        $myFile = storage_path('app/'.$url.'/'.$file);
        $headers = ['Content-Type: text/plain,application/pdf,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document,image/jpeg,image/png'];
        $newName = $file;
              
        return response()->download($myFile, $newName, $headers);

        echo "Your download will start shortly..";
    }

}
