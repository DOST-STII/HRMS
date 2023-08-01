<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;

class Dashboard extends Controller
{
    public function index()
    {
        //STAFF
        $total_staff = App\View_user::count();
        $total_icos = App\View_users_temp::count();
        $total_retiree = App\View_users_temp::count();
        $total_retiree = App\View_users_with_age::whereBetween('age',[60,65])->count();

    	//SEX
    	$sex = App\View_dashboard_sex::first();
    	$total_male = $sex['total_male'];
    	$total_female = $sex['total_female'];

    	//AGE RANGE
    	$age = App\View_dashboard_age_range::first();
    	$age_20_below_male = $age['20_below_male'];
    	$age_21_30_male = $age['21_30_male'];
    	$age_31_40_male = $age['31_40_male'];
    	$age_41_50_male = $age['41_50_male'];
    	$age_51_60_male = $age['51_60_male'];
    	$age_above_60_male = $age['above_60_male'];

    	$age_20_below_female = $age['20_below_female'];
    	$age_21_30_female = $age['21_30_female'];
    	$age_31_40_female = $age['31_40_female'];
    	$age_41_50_female = $age['41_50_female'];
    	$age_51_60_female = $age['51_60_female'];
    	$age_above_60_female = $age['above_60_female'];

    	$data = [
                    'total_staff' => $total_staff,
                    'total_icos' => $total_icos,
                    'total_retiree' => $total_retiree,

    				'total_male' => $total_male,
    				'total_female' => $total_female,

    				'age_20_below_male' => $age_20_below_male,
    				'age_21_30_male' => $age_21_30_male,
    				'age_31_40_male' => $age_31_40_male,
    				'age_41_50_male' => $age_41_50_male,
    				'age_51_60_male' => $age_51_60_male,
    				'age_above_60_male' => $age_above_60_male,

    				'age_20_below_female' => $age_20_below_female,
    				'age_21_30_female' => $age_21_30_female,
    				'age_31_40_female' => $age_31_40_female,
    				'age_41_50_female' => $age_41_50_female,
    				'age_51_60_female' => $age_51_60_female,
    				'age_above_60_female' => $age_above_60_female,
    			];
        return view('dashboard.index')->with('data',$data);
    }
}
