<?php

namespace App\Http\Controllers\PersonnelInformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Auth;


class RequestForHiringController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
       //attachment
        $path = null;
        if(request()->hasFile('request_attachment'))
        {
            $path = request()->file('request_attachment')->store('request_attachment');
        }


        $path2 = null;
        if(request()->hasFile('request_vacancy'))
        {
            $path2 = request()->file('request_vacancy')->store('request_vacancy');
        }

        $req = new App\Request_for_hiring;
        $req->request_desc = request()->request_desc;
        $req->request_attachment = $path;
        $req->request_by = Auth::user()->id;
        $req->request_division = Auth::user()->division;
        $req->plantilla_id= request()->plantilla_id;
        $req->save();
        $req_id = $req->id;

        //LINK VACANT PLANTILLA TO THE LETTER
        $plantilla = new App\Vacant_plantilla;
        $plantilla = $plantilla
                        ->where('id',request()->plantilla_id)
                        ->update([
                                    'request_for_hiring_id' => $req->id,
                                    'division_id' => Auth::user()->division
                                ]);
        
        // if(request()->hiring_type == 2)
        // {   
        //     $req = new App\Request_for_hiring;
        //     $req->request_desc = request()->request_desc;
        //     $req->request_attachment = $path;
        //     $req->request_by = Auth::user()->id;
        //     $req->request_division = Auth::user()->division;
        //     $req->plantilla_id= request()->plantilla_id;
        //     $req->save();
        //     $req_id = $req->id;

        //     //LINK VACANT PLANTILLA TO THE LETTER
        //     $plantilla = new App\Vacant_plantilla;
        //     $plantilla = $plantilla
        //                 ->where('id',request()->plantilla_id)
        //                 ->update([
        //                             'request_for_hiring_id' => $req->id,
        //                         ]);
        // }
        // else
        // {
        //     $req = new App\Request_for_hiring;
        //     $req->request_desc = request()->request_desc;
        //     $req->request_attachment = $path;
        //     $req->request_by = Auth::user()->id;
        //     $req->request_division = Auth::user()->division;
        //     $req->save();
        //     $req_id = $req->id;
        // }
        
        

        //FILE HISTORY FOR RECRUITMENT
        $file = new App\Recruitment_file_history;
        $file->request_id = $req_id;
        $file->file_type = "Letter of Request";
        $file->file_path = $path;
        $file->save();

        $file = new App\Recruitment_file_history;
        $file->request_id = $req_id;
        $file->file_type = "Vacancy Advice";
        $file->file_path = $path2;
        $file->save();

        //UPDATE HISTORY
        $history = new App\Recruitment_history;
        $history->request_id = $req_id;
        $history->request_status = 'Division sent letter of request';
        $history->created_by = getDivision(Auth::user()->division);
        $history->userid = Auth::user()->id;
        $history->save();
    }
    
    public function update()
    {
    	$req = new App\Request_for_hiring;

        if(request()->hasFile('request_attachment'))
        {
            $path = request()->file('request_attachment')->store('request_attachment');
        	$req = $req->where('id',request()->tblid)
                        ->update([
                                    'request_desc' => request()->request_desc,
                                    'request_attachment' => $path
                                ]);
        }
        else
        {
        	$req = $req->where('id',request()->tblid)
                        ->update([
                                    'request_desc' => request()->request_desc,
                                ]);
        }

        
    }

    public function delete()
    {
        $req = new App\Request_for_hiring;
        $req = $req->where('id',request()->tblid)
                        ->delete();
    }

    public function json($id)
    {
        $req = new App\Request_for_hiring;
        $req = $req->where('id',$id)
                        ->get();

        return json_encode($req);
    }

    public function alert()
    {
        $req = new App\Request_for_hiring;
        $req = $req->whereNull('request_seen')->count();
        $arr = ['total' => $req];
        return json_encode($arr);
    }

    public function clear()
    {
        $req = new App\Request_for_hiring;
        $req = $req->where('request_division',Auth::user()->division)
        				->update([
                                    'request_seen' => 1,
                                ]);
    }

    public function upload()
    {
        // return request()->letterid;
        $path = null;
        $path2 = null;
        switch (request()->letterstatus) 
        {
                case 'upload-vacancy':

                    if(request()->hasFile('request_vacancy'))
                    {
                        $path = request()->file('request_vacancy')->store('request_vacancy');
                    }

                    if(request()->hasFile('request_letter'))
                    {
                        $path2 = request()->file('request_letter')->store('request_attachment');
                    }

                    break;
        }
        //UPDATE REQUEST
        App\Request_for_hiring::where('id',request()->letterid)
                        ->update([
                                    'request_status' => "Vacancy Posted'",
                                    'request_attachment' => $path2,
                                ]);

        //FILE HISTORY FOR RECRUITMENT
        $file = new App\Recruitment_file_history;
        $file->request_id = request()->letterid;
        $file->file_type = "Vacancy Advice";
        $file->file_path = $path;
        $file->save();

        $file = new App\Recruitment_file_history;
        $file->request_id = request()->letterid;
        $file->file_type = "Letter of Request";
        $file->file_path = $path2;
        $file->save();

        //UPDATE HISTORY
        $history = new App\Recruitment_history;
        $history->request_id = request()->letterid;
        $history->request_status = 'FAD-Personnel Uploaded Signed Letter Request/Vacancy Advice';
        $history->created_by = getDivision(Auth::user()->division);
        $history->userid = Auth::user()->id;
        $history->save();  

        return redirect('recruitment/index');
    }

    public function repost()
    {
        $plantilla = new App\Vacant_plantilla;
        $plantilla = $plantilla
                        ->where('id',request()->plantillaid)
                        ->update([
                                    'plantilla_posted' => null,
                                ]);
        //RESET LETTER
        App\Request_for_hiring::where('id',request()->letterid)
                                ->update([
                                            'request_status' => 'Re-upload Vacancy Advise for Reposting',
                                            'request_seen' => null,
                                        ]);

        $history = new App\Recruitment_history;
        $history->request_id = request()->letterid;
        $history->request_status = "Remove posted item";
        $history->created_by = getDivision(Auth::user()->division);
        $history->save();

        sleep(1);

        $history = new App\Recruitment_history;
        $history->request_id = request()->letterid;
        $history->request_status = "Re-upload Vacancy Advise for Reposting";
        $history->created_by = getDivision(Auth::user()->division);
        $history->save();

        //RESET APPLICANTS
        App\Applicant_position_apply::where('vacant_plantilla_id',request()->plantillaid)->delete();

        //RESET Invitation
        App\Invitation::where('vacant_plantilla_id',request()->plantillaid)->delete();
        
    }

    public function approval()
    {
        switch (Auth::user()->division) {
            //BUDGET
            case 'q':
                    $list = App\Request_for_hiring::where('request_status','Cleared from FAD-Personnel')->get();
                break;
            //OED-ARMMS
            case 'A':
                    $list = App\Request_for_hiring::where('request_status','Cleared from FAD-Budget')->get();
                break;
            //OED
            case 'O':
                    $list = App\Request_for_hiring::where('request_status','Received by OED-ARMSS')->get();
                break;
            
        }

        $data = 
            [
                'list' => $list,
            ];

        return view('pis.recruitment.letter-approval')->with('data',$data);
    }

    public function clearance()
    {
        switch (Auth::user()->division) {
            //BUDGET
            case 'q':
                    $history_status = "Cleared from FAD-Budget, forwarded to OED-ARMSS";
                    $status = 'Cleared from FAD-Budget';
                break;
            //OED-ARMMS
            case 'A':
                    $history_status = "Received by OED-ARMSS, forwarded to OED";
                    $status = 'Received by OED-ARMSS';
                break;
            //OED
            case 'O':
                    $history_status = "Cleared from OED, upload Vacancy Advice";
                    $status = 'Cleared from OED';
                break;
            
        }
        //UPDATE HISTORY
        $history = new App\Recruitment_history;
        $history->request_id = request()->letterid;
        $history->request_status = $history_status;
        $history->created_by = getDivision(Auth::user()->division);
        $history->userid = Auth::user()->id;
        $history->save();

        App\Request_for_hiring::where('id',request()->letterid)
            ->update([
                        'request_status' => $status,
                            'request_seen' => null,
                    ]);
    }
}
