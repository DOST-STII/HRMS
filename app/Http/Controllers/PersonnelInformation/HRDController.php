<?php

namespace App\Http\Controllers\PersonnelInformation;

use Illuminate\Http\Request;
use App;
use Auth;

class HRDController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    
}
