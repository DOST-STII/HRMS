<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;

class JSON extends Controller
{
    public function index()
    {
    	return view('sample');
    }

    public function list($yr,$mon)
    {
    	if($mon == 'all')
    	{
    		$test = App\Plantilla::whereYear('plantilla_date_from',$yr)->get();
    	}
    	else
    	{
    		$test = App\Plantilla::whereYear('plantilla_date_from',$yr)->whereMonth('plantilla_date_from',$mon)->get();	
    	}
    	
    	return json_encode($test);
    }

    public function leave($id)
    {
        $lv = App\Leave_type::where('id',$id)->first();
        return json_encode($lv);
    }

    public function dtr($userid,$type,$col,$t)
    {

        if($type == 'icos')
        {
            $dtr = App\Employee_icos_dtr::where('id',$col)->first();  
        }
        else
        {
            $dtr = App\Employee_dtr::where('id',$col)->first();
        }
        

        if($dtr)
        {
            switch ($t) {
                case 1:
                        // return json_encode(date('H:i',strtotime($dtr['fldEmpDTRamIn'])));
                        return $this->checkNull($dtr['fldEmpDTRamIn']);
                    break;
                case 2:
                        // return json_encode(date('H:i',strtotime($dtr['fldEmpDTRamOut'])));
                        return $this->checkNull($dtr['fldEmpDTRamOut']);
                    break;
                case 3:
                        // return json_encode(date('H:i',strtotime($dtr['fldEmpDTRpmIn'])));
                        return $this->checkNull($dtr['fldEmpDTRpmIn']);
                    break;
                case 4:
                        // return json_encode(date('H:i',strtotime($dtr['fldEmpDTRpmOut'])));
                        return $this->checkNull($dtr['fldEmpDTRpmOut']);
                    break;
                case 5:
                        // return json_encode(date('H:i',strtotime($dtr['fldEmpDTRotIn'])));
                        return $this->checkNull($dtr['fldEmpDTRotIn']);
                    break;
                case 6:
                        // return json_encode(date('H:i',strtotime($dtr['fldEmpDTRotOut'])));
                        return $this->checkNull($dtr['fldEmpDTRotOut']);
                    break;
            }
        }
        else
        {
            return null;
        }
        
        
    }

    private function checkNull($t)
    {
        if(isset($t))
        {
            return json_encode(date('H:i',strtotime($t)));
        }
        else
        {
            return null;
        }
    }
}
