<?php

namespace App\Http\Controllers\PersonnelInformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Auth;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        $contact = new App\Employee_contact;
        $contact->user_id = Auth::user()->id;
        $contact->contact_residential = request()->contact_residential;
        $contact->contact_permanent = request()->contact_permanent;
        $contact->contact_cellnum = request()->contact_cellnum;
        $contact->save();
    }

    public function update()
    {
        $contact = new App\Employee_contact;
        $contact = $contact
                        ->where('user_id',Auth::user()->id)
                        ->update([
                                    'contact_residential' => request()->contact_residential,
                                    'contact_permanent' => request()->contact_permanent,
                                    'contact_cellnum' => request()->contact_cellnum,
                                ]);
    }

    public function delete()
    {
        $contact = new App\Employee_contact;
        $contact = $contact
                        ->where('user_id',Auth::user()->id)
                        ->delete();
    }

    public function json($id)
    {
        $contact = new App\Employee_contact;
        $contact = $contact
                        ->where('user_id',Auth::user()->id)
                        ->get();

        return json_encode($contact);
    }
}
