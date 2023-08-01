<?php

namespace App\Http\Controllers\PersonnelInformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App;

class PISLibraryPositionController extends Controller
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
        $position = new App\Position;
        $position = $position
                        ->where('id',request()->tbl_name_id)
                        ->delete();
    }
        public function update()
    {
        $position = new App\Position;
        $position = $position
                        ->where('id',request()->tbl_name_id)
                        ->update([
                                    'position_id' => request()->position_id,
                                    'position_abbr' => request()->abbreviation,
                                    'position_desc' => request()->description
                                ]);
    }
}
