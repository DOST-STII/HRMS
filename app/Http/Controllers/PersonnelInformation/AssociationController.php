<?php

namespace App\Http\Controllers\PersonnelInformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Auth;

class AssociationController extends Controller
{
        public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        $association = new App\Employee_association;
        $association->user_id = Auth::user()->id;
        $association->assoc_desc = request()->association_desc;
        $association->save();
    }

    public function update()
    {
        $association = new App\Employee_association;
        $association = $association
                        ->where('id',request()->tblid)
                        ->update([
                                    'assoc_desc' => request()->association_desc,
                                ]);
    }

    public function delete()
    {
        $association = new App\Employee_association;
        $association = $association
                        ->where('id',request()->tblid)
                        ->delete();
    }

    public function json($id)
    {
        $association = new App\Employee_association;
        $association = $association
                        ->where('id',$id)
                        ->get();

        return json_encode($association);
    }
}
