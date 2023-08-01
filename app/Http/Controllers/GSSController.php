<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GSSController extends Controller
{
    public function index()
    {
        $data = [
                    "mon" => date('m'),
                    "yr" => date('Y'),
                ];
        return view('gss')->with("data",$data);
    }

    public function index2($mon,$yr)
    {
        $data = [
                    "mon" => $mon,
                    "yr" => $yr,
                ];
        return view('gss')->with("data",$data);
    }
}
