<?php

namespace App\Http\Controllers\PersonnelInformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Auth;

class BudgetController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

}
