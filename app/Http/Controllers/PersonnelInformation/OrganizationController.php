<?php

namespace App\Http\Controllers\PersonnelInformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Auth;

class OrganizationController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        $organization = new App\Employee_organization;
        $organization->user_id = Auth::user()->id;
        $organization->org_name = request()->org_name;
        $organization->org_add = request()->org_add;
        $organization->org_date_from = request()->org_date_from;
        $organization->org_date_to = request()->org_date_to;
        $organization->org_hours = request()->org_hours;
        $organization->org_position = request()->org_position;
        $organization->org_nature = request()->org_nature;
        $organization->save();
    }

    public function update()
    {
        $organization = new App\Employee_organization;
        $organization = $organization
                        ->where('id',request()->tblid)
                        ->update([
                                    'org_name' => request()->org_name,
                                    'org_add' => request()->org_add,
                                    'org_date_from' => request()->org_date_from,
                                    'org_date_to' => request()->org_date_to,
                                    'org_hours' => request()->org_hours,
                                    'org_position' => request()->org_position,
                                    'org_nature' => request()->org_nature,
                                ]);
    }

    public function delete()
    {
        $organization = new App\Employee_organization;
        $organization = $organization
                        ->where('id',request()->tblid)
                        ->delete();
    }

    public function json($id)
    {
        $organization = new App\Employee_organization;
        $organization = $organization
                        ->where('id',$id)
                        ->get();

        return json_encode($organization);
    }
}
