<?php

namespace App\Http\Controllers\PersonnelInformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Auth;

class SkillController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        $skill = new App\Employee_skill;
        $skill->user_id = Auth::user()->id;
        $skill->skill_desc = request()->skill_desc;
        $skill->save();
    }

    public function update()
    {
        $organization = new App\Employee_skill;
        $organization = $organization
                        ->where('id',request()->tblid)
                        ->update([
                                    'skill_desc' => request()->skill_desc,
                                ]);
    }

    public function delete()
    {
        $skill = new App\Employee_skill;
        $skill = $skill
                        ->where('id',request()->tblid)
                        ->delete();
    }

    public function json($id)
    {
        $skill = new App\Employee_skill;
        $skill = $skill
                        ->where('id',$id)
                        ->get();

        return json_encode($skill);
    }
}
