<?php

namespace App\Http\Controllers\PersonnelInformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App;

class PISLibraryEmploymentController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['auth','admin']);
    }

    public function index()
    {

    }

    public function create()
    {

    }

    public function delete()
    {
        $employment = new App\Employment;
        $employment = $employment
                        ->where('employment_id',request()->tbl_name_id)
                        ->delete();
    }
    
    public function update()
    {
        $employment = new App\Employment;
        $employment = $employment
                        ->where('employment_id',request()->tbl_name_id)
                        ->update([
                                    'employment_desc' => request()->description
                                ]);
    }
}
