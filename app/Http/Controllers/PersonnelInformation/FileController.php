<?php

namespace App\Http\Controllers\PersonnelInformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Auth;

class FileController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Request $request)
    {
    	$path = "";
        $filename = "";


        if(request()->hasFile('files_other_attach'))
        {
            $path = request()->file('files_other_attach')->store('other_files');
            $path = explode('/',$path);
            $filename = request()->file('files_other_attach')->getClientOriginalName();
            $request->files_other_attach->move(public_path('storage/other_files'), $path[1]);
            $path = $path[1];
        }

        $file = new App\Employee_file;
        $file->user_id = Auth::user()->id;
        $file->file_desc = $filename;
        // $file->file_desc = request()->files_other_desc;
        $file->file_path = $path;
        $file->save();
    }


    public function update()
    {
    	
    }

    public function delete()
    {
        App\Employee_file::where('id',request()->tblid)->delete();
    }

    public function json($id)
    {
        
    }
}
