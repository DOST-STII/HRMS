<?php

namespace App\Http\Controllers\PersonnelInformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App;

class PISLibraryDesignationController extends Controller
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
        $designation = new App\Designation;
        $designation = $designation
                        ->where('designation_id',request()->tbl_name_id)
                        ->delete();
    }

    public function update()
    {
        $designation = new App\Designation;
        $designation = $designation
                        ->where('designation_id',request()->tbl_name_id)
                        ->update([
                                    'designation_abbr' => request()->abbreviation,
                                    'designation_desc' => request()->description
                                ]);
    }
}
