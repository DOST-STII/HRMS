<?php

namespace App\Http\Controllers\PersonnelInformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Auth;

class AddressController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function check(Request $request)
    {
        //UPDATE EMAIL, NAHIWALAY SYA EH
        // App\User::where('id',Auth::user()->id)
        //                 ->update([
        //                             'email' => $request->email,
        //                         ]);

        //CHECK KUNG MAY LAMAN NG YUNG TABLE, 1:1 KASI, ISANG ROW ISANG STAFF
        

        //ADDRESS PERMANENT
        if(App\Employee_address_permanent::where('user_id',Auth::user()->id)->count() > 0)
        {
            $this->add_permanent_update($request);
        }
        else
        {
            $this->add_permanent_create($request);
        }

        //ADDRESS RESIDENTIAL
        if(App\Employee_address_residential::where('user_id',Auth::user()->id)->count() > 0)
        {
            $this->add_residential_update($request);
        }
        else
        {
            $this->add_residential_create($request);
        }
    }


    public function add_permanent_create(Request $request)
    {
        //CONTACT
        $address = new App\Employee_address_permanent;
        $address->user_id = Auth::user()->id;
        $address->permanent_add_street = $request->permanent_add_street;
        $address->permanent_add_subd = $request->permanent_add_subd;
        $address->permanent_add_no = $request->permanent_add_no;
        $address->permanent_add_zipcode = $request->permanent_add_zipcode;
        $address->permanent_add_brgy = $request->permanent_add_brgy;
        $address->permanent_add_mun = $request->permanent_add_mun;
        $address->permanent_add_prov = $request->permanent_add_prov;
        $address->save();
    }

    public function add_permanent_update(Request $request)
    {
        //CONTACT
        $address = new App\Employee_address_permanent;
        $address = $address
                        ->where('user_id',Auth::user()->id)
                        ->update([
                                    'permanent_add_street' => $request->permanent_add_street,
                                    'permanent_add_subd' => $request->permanent_add_subd,
                                    'permanent_add_no' => $request->permanent_add_no,
                                    'permanent_add_zipcode' => $request->permanent_add_zipcode,
                                    'permanent_add_brgy' => $request->permanent_add_brgy,
                                    'permanent_add_mun' => $request->permanent_add_mun,
                                    'permanent_add_prov' => $request->permanent_add_prov,
                                    'permanent_add_phone' => $request->contact_permanent,
                                ]);
    }

    public function add_residential_create(Request $request)
    {
        //CONTACT
        $address = new App\Employee_address_residential;
        $address->user_id = Auth::user()->id;
        $address->residential_add_street = $request->residential_add_street;
        $address->residential_add_subd = $request->residential_add_subd;
        $address->residential_add_no = $request->residential_add_no;
        $address->residential_add_zipcode = $request->residential_add_zipcode;
        $address->residential_add_brgy = $request->residential_add_brgy;
        $address->residential_add_mun = $request->residential_add_mun;
        $address->residential_add_prov = $request->residential_add_prov;
        $address->save();
    }

    public function add_residential_update(Request $request)
    {
        //CONTACT
        $address = new App\Employee_address_residential;
        $address = $address
                        ->where('user_id',Auth::user()->id)
                        ->update([
                                    'residential_add_street' => $request->residential_add_street,
                                    'residential_add_subd' => $request->residential_add_subd,
                                    'residential_add_no' => $request->residential_add_no,
                                    'residential_add_zipcode' => $request->residential_add_zipcode,
                                    'residential_add_brgy' => $request->residential_add_brgy,
                                    'residential_add_mun' => $request->residential_add_mun,
                                    'residential_add_prov' => $request->residential_add_prov,
                                    'residential_add_phone' => $request->contact_residential,
                                ]);
    }

    public function municipal($provid)
    {
        $municipal = App\Location_municipal::where('location_province_id',$provid)->orderBy('mun_desc')->get();
        return json_encode($municipal);
    }

    public function barangay($munid)
    {
        $municipal = App\Location_barangay::where('location_municipal_id',$munid)->orderBy('brgy_desc')->get();
        return json_encode($municipal);
    }


}
