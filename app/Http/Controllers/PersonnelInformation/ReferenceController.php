<?php

namespace App\Http\Controllers\PersonnelInformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Auth;

class ReferenceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        $reference = new App\Employee_reference;
        $reference->user_id = Auth::user()->id;
        $reference->reference_name = request()->reference_name;
        $reference->reference_add = request()->reference_add;
        $reference->reference_telno = request()->reference_telno;
        $reference->save();
    }

    public function update()
    {
        $reference = new App\Employee_reference;
        $reference = $reference
                        ->where('id',request()->tblid)
                        ->update([
                                    'reference_name' => request()->reference_name,
                                    'reference_add' => request()->reference_add,
                                    'reference_telno' => request()->reference_telno,
                                ]);
    }

    public function delete()
    {
        $reference = new App\Employee_reference;
        $reference = $reference
                        ->where('id',request()->tblid)
                        ->delete();
    }

    public function json($id)
    {
        $reference = new App\Employee_reference;
        $reference = $reference
                        ->where('id',$id)
                        ->get();

        return json_encode($reference);
    }
}
