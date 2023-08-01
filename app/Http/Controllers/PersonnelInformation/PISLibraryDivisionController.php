<?php

namespace App\Http\Controllers\PersonnelInformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App;

class PISLibraryDivisionController extends Controller
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
        $division = new App\Division;
        $division = $division
                        ->where('id',request()->tbl_name_id)
                        ->delete();
    }

    public function update()
    {
        $division = new App\Division;
        $division = $division
                        ->where('id',request()->tbl_name_id)
                        ->update([
                                    'division_acro' => request()->acronym,
                                    'division_desc' => request()->description
                                ]);
    }
}
