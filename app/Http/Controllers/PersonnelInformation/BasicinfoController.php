<?php

namespace App\Http\Controllers\PersonnelInformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Auth;

use Illuminate\Support\Facades\Storage;

class BasicinfoController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function updatePhoto(Request $request)
    {
        $path = null;
        if(request()->hasFile('uploadphoto'))
        {
            $path = request()->file('uploadphoto')->store('photos');
            $path = explode('/',$path);
            $request->uploadphoto->move(public_path('storage/photos'), $path[1]);
        }

        //return $path;

        //DELETE PREVIOUS FILE
        $old_image =App\User::where('id',Auth::user()->id)->first();
        Storage::delete($old_image['image_path']);

        App\User::where('id',Auth::user()->id)
                ->update([
                            'image_path' => $path[1]
                        ]);

        return redirect('personal-information/info/na');
    }

    public function check(Request $request)
    {
        if(App\Employee_basicinfo::where('user_id',Auth::user()->id)->count() > 0)
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
    	//FOR CITIZENSHIP KASI ARRAY TO
    	$arr = implode(",", $request->citizenship);

    	$basicinfo = new App\Employee_basicinfo;
    	$basicinfo->user_id = Auth::user()->id;
    	$basicinfo->basicinfo_placeofbirth = $request->placeofbirth;
    	$basicinfo->basicinfo_sex = $request->sex;
    	$basicinfo->basicinfo_civilstatus = $request->civilstatus;
    	$basicinfo->basicinfo_citizenship = $arr;
    	$basicinfo->basicinfo_citizentype = $request->citizentype;
    	$basicinfo->basicinfo_height = $request->info_height;
    	$basicinfo->basicinfo_weight = $request->info_weight;
    	$basicinfo->basicinfo_bloodtype = $request->bloodtype;
    	$basicinfo->save();
    }


    public function update(Request $request)
    {
    	//USER TABLE
    	$user = new App\User;
    	$user = $user
                        ->where('id',Auth::user()->id)
                        ->update([
                                    'lname' => $request->lname,
                                    'fname' => $request->fname,
                                    'mname' => $request->mname,
                                    'exname' => $request->exname,
                                    'birthdate' => $request->birthdate,
                                    'email' => $request->email,
                                ]);


    	//FOR CITIZENSHIP KASI ARRAY TO
    	$arr = implode(",", $request->citizenship);

    	$basicinfo = new App\Employee_basicinfo;
    	$basicinfo = $basicinfo
                        ->where('user_id',Auth::user()->id)
                        ->update([
                                    'basicinfo_placeofbirth' => $request->placeofbirth,
                                    'basicinfo_sex' => $request->sex,
                                    'basicinfo_civilstatus' => $request->civilstatus,
                                    'basicinfo_citizenship' => $arr,
                                    'basicinfo_citizentype' => $request->citizentype,
                                    'basicinfo_height' => $request->info_height,
                                    'basicinfo_weight' => $request->info_weight,
                                    'basicinfo_bloodtype' => $request->bloodtype,
                                    'basicinfo_bloodtype' => $request->bloodtype,
                                ]);

        //EMAIL
        $user = App\User::where('id',Auth::user()->id)
                        ->update([
                                    'email' => $request->email,
                                ]);

        //CONTACT
        if(App\Employee_contact::where('user_id',Auth::user()->id)->count() > 0)
        {
            $this->contact_update($request);
        }
        else
        {
            $this->contact_create($request);
        }
    }

    public function contact_create(Request $request)
    {
        //CONTACT
        $contact = new App\Employee_contact;
        $contact->user_id = Auth::user()->id;
        $contact->contact_residential = $request->contact_residential;
        $contact->contact_permanent = $request->contact_permanent;
        $contact->contact_cellnum = $request->contact_cellnum;
        $contact->save();
    }

    public function contact_update(Request $request)
    {
        //CONTACT
        $contact = new App\Employee_contact;
        $contact = $contact
                        ->where('user_id',Auth::user()->id)
                        ->update([
                                    'contact_residential' => $request->contact_residential,
                                    'contact_permanent' => $request->contact_permanent,
                                    'contact_cellnum' => $request->contact_cellnum,
                                ]);
    }

    public function json($id)
    {
        $basicinfo = new App\Employee_basicinfo;
        $basicinfo = $basicinfo
                        ->where('user_id',$id)
                        ->get();

        return json_encode($basicinfo);
    }
}
