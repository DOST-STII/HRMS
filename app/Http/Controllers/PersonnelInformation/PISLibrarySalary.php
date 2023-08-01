<?php

namespace App\Http\Controllers\PersonnelInformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Imports\SalaryImport;
use Maatwebsite\Excel\Facades\Excel;

use App;

class PISLibrarySalary extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','admin']);
    }

    public function upload()
    {
        //SAVE FILE FIRST FOR HISTORY
        $path = null;
        if(request()->hasFile('salary_file'))
        {
            $path = request()->file('salary_file')->store('salarysched');
        }
        //DELETE FILE
        $file = App\SalaryFile::where('id','>',0);
        $file->delete();

        //ADD FILE
        $file = new App\SalaryFile;
        $file->salary_date = request()->salary_date;
        $file->salary_path = $path;
        $file->save();

        //CLEAR TABLE
        App\SalaryTable::truncate();

        Excel::import(new SalaryImport, request()->file('salary_file'));   
    }

}
