<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use App;
    use Auth;

    class NotificationController extends Controller
    {
        public function __construct()
        {
            $this->middleware('auth');
        }

        public function index()
        {
        	switch (Auth::user()->usertype) {
        		case 'Administrator':
        			  return $this->admin();
        			break;

                case 'Director':
                      return $this->director();
                    break;
        		
        		case 'Marshal':
        			  return $this->marshal();
        			break;

                case 'Staff':
                      return $this->staff();
                    break;
        	}
        }
        public function marshal()
        {
        	//CHECK CALL FOR SUBMISSION
        	$sub = App\Submission_list::where('submission_division',Auth::user()->division)->whereNull('submission_seen')->whereNull('deleted_at')->count();
        	$data[] = array("total" => $sub,"title" => "Call for Submission","url" => url('submission-list/division'));

        	//CHECK UPDATE OF HIRING
        	$req = App\Request_for_hiring::whereNull('request_seen')->where('request_division',Auth::user()->division)->count();
        	$data[] = array("total" => $req,"title" => "Request for Hiring","url" => url('letter-request'));

            //HRD PLAN
            // $req2 = App\HRD_plan_division::where('division_id',Auth::user()->division)->whereNull('submitted_at')->count();
            // $data[] = array("total" => $req2,"title" => "Call for Division HRD Plan","url" => url('letter-request'));

            //CHECK HRD PLAN
            //CHECK HRD PLAN
            $req2 = App\HRD_plan_staff::where('user_id',Auth::user()->id)->whereNull('submitted_at')->count();
            $req3 = App\HRD_plan_staff::where('user_id',Auth::user()->id)->whereNull('submitted_at')->first();

            $url = "";
            if(isset($req3))
            {
               $url = url('learning-development/hrd-plan/'.$req3->hrd_plan_division_id.'/'.$req3->hrd_plan_id); 
            }
            

            $data[] = array("total" => $req2,"title" => "Call for Division HRD Plan","url" => $url);

            //IPCR
            $req4 = App\Performance_ipcr_staff::where('user_id',Auth::user()->id)->whereNull('ipcr_submitted_at')->count();
            $data[] = array("total" => $req4,"title" => "Submit your IPCR","url" => url('personal-information/files'));

            //LEAVE

            //IF OED
            $ctr5 = 0;
            $ctr7 = 0;

            if(Auth::user()->division == 'O')
            {
                $ctr5 = App\Request_leave::where('director','YES')->where('leave_action_status','Pending')->whereIn('parent',['YES','NO'])->count();
                $ctr7 = App\RequestOT::where('director','YES')->where('ot_status','Pending')->count(); 
            }
                


            $req5 = App\Request_leave::where('user_div',Auth::user()->division)->where('leave_action_status','Pending')->whereIn('parent',['YES','NO'])->count();

            $total5 = $req5 + $ctr5;
            $data[] = array("total" => $total5,"title" => "Pending Request","url" => url('request-for-approval'));


            //TO
            $req6 = App\RequestTO::where('division',Auth::user()->division)->where('to_status','Pending')->count();
            $data[] = array("total" => $req6,"title" => "Pending T.O Request","url" => url('request-for-approval'));

            //OT
            $req7 = App\RequestOT::where('division',Auth::user()->division)->where('ot_status','Pending')->count();
            $total7 = $req7 + $ctr7;
            $data[] = array("total" => $total7,"title" => "Pending O.T Request","url" => url('request-for-approval'));


            //CLEARANCE
            switch (Auth::user()->division) {
                //BUDGET
                case 'q':
                        //BUDGET CLEARANCE
                        $req = App\Request_for_hiring::where('request_status','Cleared from FAD-Personnel')->count();
                        $data[] = array("total" => $req,"title" => "Letter for Approval","url" => url('recruitment/letter-approval'));
                    break;
                //OED-ARMMS
                case 'A':
                        //ARMSS CLEARANCE
                        $req = App\Request_for_hiring::where('request_status','Cleared from FAD-Budget')->count();
                        $data[] = array("total" => $req,"title" => "Letter for Approval","url" => url('recruitment/letter-approval'));

                    break;
                //OED
                case 'O':
                        //OED CLEARANCE
                        $req = App\Request_for_hiring::where('request_status','Received by OED-ARMSS')->count();
                        $data[] = array("total" => $req,"title" => "Letter for Approval","url" => url('recruitment/letter-approval'));

                        //HRD PLAN
                        $req3 = App\HRD_plan::where('hrd_status','Forwarded to OED')->count();
                        $data[] = array("total" => $req2,"title" => "HRD Plan Approval","url" => url('learning-development/list-hrd-approval'));  
                    break;
                
            }

        	return json_encode($data);
        }

        public function admin()
        {
        	//CHECK REQUEST LETTER FOR HIRING
        	$req = App\Request_for_hiring::where('request_status','Pending')->count();
            $req2 = App\Employee_training_temp::where('training_status','Pending')->count();

        	$data[] = array("total" => $req,"title" => "Letter for Hiring","url" => url('recruitment/index'));
            $data[] = array("total" => $req2,"title" => "Letter for Training","url" => url('letter-of-request-list'));

            

        	// //CHECK UPDATE OF HIRING
        	// $req = App\Request_for_hiring::whereNull('request_seen')->count();
        	// $data[] = array("total" => $req,"title" => "Request for Hiring","url" => url('request-for-hiring'));

        	return json_encode($data);
        }

        public function staff()
        {
            //CHECK REQUEST LETTER FOR HIRING
            $req = App\Invitation::where('user_id',Auth::user()->id)->where('interested','')->count();
            $data[] = array("total" => $req,"title" => "Call for Application","url" => url('invitation/list'));

            //CHECK HRD PLAN
            $req2 = App\HRD_plan_staff::where('user_id',Auth::user()->id)->whereNull('submitted_at')->count();
            $req3 = App\HRD_plan_staff::where('user_id',Auth::user()->id)->whereNull('submitted_at')->first();

            $url = "";
            if(isset($req3))
            {
               $url = url('learning-development/hrd-plan/'.$req3->hrd_plan_division_id.'/'.$req3->hrd_plan_id); 
            }
            

            $data[] = array("total" => $req2,"title" => "Call for Division HRD Plan","url" => $url);

            //IPCR
            $req4 = App\Performance_ipcr_staff::where('user_id',Auth::user()->id)->whereNull('ipcr_submitted_at')->count();
            //GET DEATILS
            if($req4 > 0)
            {
                $lst = "/";
                $detail = App\Performance_ipcr_staff::where('user_id',Auth::user()->id)->whereNull('ipcr_submitted_at')->get();
                foreach ($detail as $value) {
                    $dpcrid = $value->dpcr_id;
                    $dpcrs = App\Performance_dpcr::where('id',$dpcrid)->first();
                    $lst .= $dpcrs['dpcr_year']." - ".$dpcrs['dpcr_period'].";";
                }
            }

            $data[] = array("total" => $req4,"title" => "Submit your IPCR","url" => url('personal-information/files'));



            return json_encode($data);
        }

        public function director()
        {

            $req = App\HRD_hrdc::where('hrdc_member_id',Auth::user()->id)->whereNull('received_at')->count();
            $data[] = array("total" => $req,"title" => "HRD Plan","url" => url('learning-development/list-hrd-approval'));

            //CHECK HRD PLAN
            $req2 = App\HRD_plan_staff::where('user_id',Auth::user()->id)->whereNull('submitted_at')->count();

            $url = "";
            if($req2 > 0)
            {
                $req3 = App\HRD_plan_staff::where('user_id',Auth::user()->id)->whereNull('submitted_at')->first();
                $url = url('learning-development/hrd-plan/'.$req3->hrd_plan_division_id.'/'.$req3->hrd_plan_id); 
            }
            

            $data[] = array("total" => $req2,"title" => "Call for Division HRD Plan","url" => $url);

            //DPCR
            $req4 = App\Performance_dpcr::where('division_id',Auth::user()->division)->whereNull('submitted_at')->count();
            $data[] = array("total" => $req4,"title" => "Submit your DPCR","url" => url('performance/division'));



            if(Auth::user()->division == 'O')
            {
                //LEAVE
                $req5 = App\Request_leave::
                        where(function($q) {
                                  $q->where('division',Auth::user()->division)
                                    ->orWhere('director', 'YES');
                              })
                        ->where('leave_action_status','Pending')
                        ->whereIn('parent',['YES','NO'])
                        ->count();

                $data[] = array("total" => $req5,"title" => "Pending Request","url" => url('request-for-approval'));

                //TO
                $req6 = App\RequestTO::
                        where(function($q) {
                                  $q->where('division',Auth::user()->division)
                                    ->orWhere('director', 'YES');
                              })
                        ->where('to_status','Pending')
                        ->count();

                $data[] = array("total" => $req6,"title" => "Pending T.O Request","url" => url('request-for-approval'));

                //OT
                $req7 = App\RequestOT::
                        where(function($q) {
                                  $q->where('division',Auth::user()->division)
                                    ->orWhere('director', 'YES');
                              })
                        ->where('ot_status','Pending')
                        ->count();
                $data[] = array("total" => $req7,"title" => "Pending O.T Request","url" => url('request-for-approval'));
                }
            else
            {
               //LEAVE
                $req5 = App\Request_leave::where('director','NO')->where('user_div',Auth::user()->division)->where('leave_action_status','Pending')->whereIn('parent',['YES','NO'])->count();
                $data[] = array("total" => $req5,"title" => "Pending Request","url" => url('request-for-approval'));

                //TO
                $req6 = App\RequestTO::where('director','NO')->where('division',Auth::user()->division)->where('to_status','Pending')->count();
                $data[] = array("total" => $req6,"title" => "Pending T.O Request","url" => url('request-for-approval'));

                //OT
                $req7 = App\RequestOT::where('director','NO')->where('division',Auth::user()->division)->where('ot_status','Pending')->count();
                $data[] = array("total" => $req7,"title" => "Pending O.T Request","url" => url('request-for-approval')); 
            }
            



            // $data[] = array("total" => NotificationForTraining(Auth::user()->id),"title" => "Update Training/s","url" => url('trainings/update'));

            return json_encode($data);
        }
    }
