<?php

namespace App\Http\Controllers\PersonnelInformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Auth;

class ChildrenController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Request $request)
    {
    	$child = new App\Employee_children;
    	$child->user_id = Auth::user()->id;
        $child->children_name = request()->children_name;
        $child->children_birthdate = request()->children_birthdate;
    	$child->save();
    }


    public function delete(Request $request)
    {
    	$child = App\Employee_children::where('id',request()->tblid)->delete();
    }

    public function update(Request $request)
    {
    	$family = new App\Employee_children;
    	$family = $family
                        ->where('id',request()->tblid)
                        ->update([
                                    'children_name' => request()->children_name,
                                    'children_birthdate' => request()->children_birthdate,
                                ]);
    }

    public function json($id)
    {
        $child = new App\Employee_children;
        $child = $child
                        ->where('id',$id)
                        ->get();

        return json_encode($child);
    }
}
