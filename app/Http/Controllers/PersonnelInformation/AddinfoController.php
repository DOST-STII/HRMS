<?php

namespace App\Http\Controllers\PersonnelInformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Auth;

class AddinfoController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function check(Request $request)
    {
        if(App\Employee_addinfo::where('user_id',Auth::user()->id)->count() > 0)
        {
       		$this->update($request);
        }
        else
        {
        	$this->create($request);
        }
    }

    public function create(Request $request)
    {
    	$addinfo = new App\Employee_addinfo;
    	$addinfo->user_id = Auth::user()->id;
    	$addinfo->addinfo_pagibig = $request->pagibig;
    	$addinfo->addinfo_philhealth = $request->philhealth;
    	$addinfo->addinfo_sss = $request->sss;
    	$addinfo->addinfo_tin = $request->tin;
    	$addinfo->addinfo_gsis_id = $request->gsis_id;
    	$addinfo->addinfo_gsis_policy = $request->gsis_policy;
    	$addinfo->addinfo_gsis_bp = $request->gsis_bp;
    	// $addinfo->addinfo_partner = $request->partner;
        $addinfo->addinfo_atm = $request->landbank_atm;
    	$addinfo->addinfo_gov = $request->gov;
    	$addinfo->addinfo_gov_id = $request->gov_id;
    	$addinfo->addinfo_gov_place_date = $request->gov_place_date;
    	$addinfo->addinfo_ctc = $request->ctc;
    	$addinfo->addinfo_ctc_date = $request->ctc_date;
    	$addinfo->addinfo_ctc_place = $request->ctc_place;
    	$addinfo->save();
    }


    public function update(Request $request)
    {
    	$addinfo = new App\Employee_addinfo;
    	$addinfo = $addinfo
                        ->where('user_id',Auth::user()->id)
                        ->update([
                                    'addinfo_pagibig' => $request->pagibig,
                                    'addinfo_philhealth' => $request->philhealth,
                                    'addinfo_sss' => $request->sss,
                                    'addinfo_tin' => $request->tin,
                                    'addinfo_gsis_id' => $request->gsis_id,
                                    'addinfo_gsis_policy' => $request->gsis_policy,
                                    'addinfo_gsis_bp' => $request->gsis_bp,
                                    // 'addinfo_partner' => $request->partner,
                                    'addinfo_atm' => $request->landbank_atm,
                                    'addinfo_gov' => $request->gov,
                                    'addinfo_gov_id' => $request->gov_id,
                                    'addinfo_gov_place_date' => $request->gov_place_date,
                                    'addinfo_ctc' => $request->ctc,
                                    'addinfo_ctc_date' => $request->ctc_date,
                                    'addinfo_ctc_place' => $request->ctc_place,
                                ]);
    }

    public function json($id)
    {
        $addinfo = new App\Employee_addinfo;
        $addinfo = $addinfo
                        ->where('user_id',$id)
                        ->get();

        return json_encode($addinfo);
    }
}
