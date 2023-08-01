<?php
include("connect_local.php");

 function isDayOff($opt_id, $date){
  $day = date("w", strtotime($date));
  
  $query = mysql_query("select * from tblDTROptions where fldDTROptID='$opt_id'");
  $row = mysql_fetch_array($query);
  
  switch ($day){
   case 1 : $off = $row['fldMonOffset'];
   			break;
   case 2 : $off = $row['fldTueOffset'];
            break;
   case 3 : $off = $row['fldWedOffset'];
            break;
   case 4 : $off = $row['fldThuOffset'];
            break;
   case 5 : $off = $row['fldFriOffset'];
            break;
   case 6 : $off = $row['fldSatOffset'];
            break;
   case 0 : $off = $row['fldSunOffset'];
            break;
  }
  
  if ($off == 0){
   return true;
  }
  else{
   return false;
  }
 }
 
 function isAbsent($date, $opt_id, $empCode){
 	//for troubleshooting employees, update empCode and change flag to TRUE
 	$checkCode = "CAR010";
 	$troubleshootFlag = FALSE;

  //check kung holiday, suspended work, on leave, or on CTO
  $ret = 0;
  //echo $date." <br>";
  //echo WorkSuspended($date)."a ".isHoliday($date)."<br>b ".isOnLeave($date, $empCode)."<br>c ".isDayOff($opt_id, $date)."d ".isOnTO($date, $empCode)."e ".isOnCTO($date, $empCode)."f ".$date;
  
  /*if((unAuthorizedLeave($date, $empCode) == 1) && ((WorkSuspended($date) == "no")) && (!isHoliday($date)) && (!isDayOff($opt_id, $date)) && (isOnTO($date, $empCode) == 0) && (isOnCTO($date, $empCode) == 0)){
  	$ret = 1;
  } 
  else */
  if (WorkSuspended($date) == "no" && !isHoliday($date) && !isOnLeave2($date, $empCode) && !isDayOff($opt_id, $date)
  && isOnTO($date, $empCode) == 0 && isOnCTO($date, $empCode) == 0){
  	if ($empCode == $checkCode && $troubleshootFlag) echo "a"." ".$date."<br/>";
   //if not on leave, holiday, or work suspended
     $query = mysql_query("select * from tblEmpDTR where fldEmpCode='$empCode' AND fldEmpDTRdate='$date' AND ((fldEmpDTRamIn != '00:00:00' && fldEmpDTRamOut != '00:00:00') && (fldEmpDTRpmIn != '00:00:00' && fldEmpDTRpmOut != '00:00:00'))");
	 if (mysql_num_rows($query) > 0){
	  $row = mysql_fetch_array($query);
	  $amIn = $row['fldEmpDTRamIn'];
	  $amOut = $row['fldEmpDTRamOut'];
	  $pmIn = $row['fldEmpDTRpmIn'];
	  $pmOut = $row['fldEmpDTRpmOut'];	
	  #echo "A)".$date." AMin:$amIn AMout:$amOut PMin:$pmIn PMout:$pmOut<br/>";
	  
	  if ($amIn != "00:00:00" && $pmOut != "00:00:00"){
		$ret =  0;
	  }	 
	  else{	  
	  	$compT = date("Y-m-d", mktime(0,0,0,8,12,2010));						
		if($date < $compT){
		   if ($amIn == "00:00:00" && $amOut == "00:00:00"){
			$ret += 0.5;
			$ret = $ret;//.$date;
		   }
		   if ($pmIn == "00:00:00" && $pmOut == "00:00:00"){
			$ret += 0.5;
			$ret = $ret;//.$date;
		   }
		}
	  }
	 }	 
	 else{	 	
		//echo "UA Leave ".$date."<br/>";
		$today = date("Y-m-d"); #CHECK FOR CONSISTENCY
		if($date <= $today){	#CHECK FOR CONSISTENCY
			$compT = date("Y-m-d", mktime(0,0,0,8,12,2010));			
			if($date < $compT) {$ret = 0.5;	}
				else if($date >= $compT) {$ret = 1; }//ret=1 maine08082017 remarks if unauthorized leave				
		} else $ret = 1;	#CHECK FOR CONSISTENCY
	 }
  }
  else if (WorkSuspended($date) == "no" && isHoliday($date) && !isOnLeave($date, $empCode) && !isDayOff($opt_id, $date) && isOnTO($date, $empCode) == 0 && isOnCTO($date, $empCode) == 0){
  	if ($empCode == $checkCode && $troubleshootFlag) echo "b"." ".$date."<br/>";
     $query = mysql_query("select * from tblEmpDTR where fldEmpCode='$empCode' AND fldEmpDTRdate='$date'");
	 if (mysql_num_rows($query) > 0){
	  $row = mysql_fetch_array($query);
	  $amIn = $row['fldEmpDTRamIn'];
	  $amOut = $row['fldEmpDTRamOut'];
	  $pmIn = $row['fldEmpDTRpmIn'];
	  $pmOut = $row['fldEmpDTRpmOut'];
	  
	  if ($amIn != "00:00:00" && $pmOut != "00:00:00"){
	   $ret =  0;
	  }
	  else{
	   if ($amIn == "00:00:00" && $amOut == "00:00:00" && holidayTime($date) == 2){
	    $ret += 0.5;
		$ret = $ret.$date;
	   }
	   else if ($pmIn == "00:00:00" && $pmOut == "00:00:00" && holidayTime($date) == 1){
	    $ret += 0.5;
		$ret = $ret.$date;
	   }
	  }
	 }
	 #Removed due to double counting i.e. counted as tardy/undertime + deficit and as absence
	 /*
	 else{
	  if (holidayTime($date) != 3){
	  	$ret += 0.5;
	  }
	 }
	 */
  }
  //DITO LAHAT NG MAY KINALAMAN SA NUMBER FILED LEAVES NA HINDI NAGAAPPEAR SA DTR PROCESSING
  else if (WorkSuspended($date) == "no" && !isHoliday($date) && isOnLeave($date, $empCode) && !isDayOff($opt_id, $date) && isOnTO($date, $empCode) == 0 && isOnCTO($date, $empCode) == 0){
  	if ($empCode == $checkCode && $troubleshootFlag) echo "c1"." ".$date."<br/>";
     $query = mysql_query("select * from tblEmpDTR where fldEmpCode='$empCode' AND fldEmpDTRdate='$date'"); 
		//echo "select * from tblEmpDTR where fldEmpCode='$empCode' AND fldEmpDTRdate='$date'<br>";
    if (mysql_num_rows($query) > 0) {		 
		  $row = mysql_fetch_array($query);
		  $amIn = $row['fldEmpDTRamIn'];
		  $amOut = $row['fldEmpDTRamOut'];
		  $pmIn = $row['fldEmpDTRpmIn'];
		  $pmOut = $row['fldEmpDTRpmOut'];
	  	#echo "$date = $amIn, $amOut, $pmIn, $pmOut  <br> ";
			//check kung anong time ng day
	  	
            
	  	if ($amIn == "00:00:00" && $amOut == "00:00:00" && $pmIn == "00:00:00" && $pmOut == "00:00:00"){
            /* start of feb 12 2013*/
              if (($amIn == "00:00:00" && $amOut == "00:00:00") && ($pmIn == "00:00:00" && $pmOut == "00:00:00") && (isOnCTOTime($date, $empCode) != 2 && isOnCTOTime($date, $empCode) != 3)){
                  $ret += 1;
              /* end of feb 12 2013*/
              } else if (($amIn == "00:00:00" && $amOut == "00:00:00" && LeaveTime($date, $empCode) == "(PM)") || ($pmIn == "00:00:00" && $pmOut == "00:00:00" && LeaveTime($date, $empCode) == "(AM)")){
                  $ret += 0.5;
            } else {			
                  $ret = 1;	
              }  
        } else if (($amIn != NULL) && ($pmOut != "00:00:00" || $pmOut != NULL)) {
		  	//start maine 08022018: after check kung may dtr am login in. check din kung null ung pmout. para macheck kung half day
		    if ($amIn != "00:00:00" && $pmOut == NULL && (isOnLeave2($date, $empCode) == 0.5)){
					//echo "$date = how";
				  $ret =  0.5;
				}	
				else if (($amIn == "00:00:00" && $amOut == "00:00:00") || ($pmIn == "00:00:00" && $pmOut == "00:00:00")) {
					$ret =  0.5;
				} else{
					$ret =  0;
				}		
	  	} 
	  	else if (($amIn == NULL) && ($pmOut != "00:00:00" || $pmOut != NULL)){
	 			$ret =  0.5;
	  	} 
        else {		
				if ($amIn == "00:00:00" && $amOut == "00:00:00" && LeaveTime($date, $empCode) == "(PM)"){
					#echo "1";
					$ret += 0.5;
				} else  if ($pmIn == "00:00:00" && $pmOut == "00:00:00" && LeaveTime($date, $empCode) == "(AM)"){
					#echo "2";
					$ret += 0.5;
				} else if ($amIn != "00:00:00" && $amOut != "00:00:00" && LeaveTime($date, $empCode) == "(PM)"){
				  #echo "3";
					$ret += 0.5;  
				} else if ($pmIn != "00:00:00" && $pmOut != "00:00:00" && LeaveTime($date, $empCode) == "(AM)"){	
					#echo "4";	   
					$ret += 0.5;
				} else if(($amIn == "00:00:00" && $amOut == "00:00:00")||($pmIn == "00:00:00" && $pmOut == "00:00:00")&&(LeaveTime($date, $empCode) != "(AM)" || LeaveTime($date, $empCode) != "(PM)") && isOnLeave($date, $empCode)){
					#echo "5";			   
					$ret += 0.5;
				} else if(($amIn == "00:00:00" && $amOut == "00:00:00")||($pmIn == "00:00:00" && $pmOut == "00:00:00") && !isOnLeave($date, $empCode)) {
					 #echo "6";
					 //CSC MC 16 & 17. if earlier than 08122010 half day +=.5 else late/undertime++
					$compT = date("Y-m-d", mktime(0,0,0,8,12,2010));						
					if($date < $compT){
					   if ($amIn == "00:00:00" && $amOut == "00:00:00"){
						  echo "7";
						$ret += 0.5;
					   }
					   if ($pmIn == "00:00:00" && $pmOut == "00:00:00"){
						   //echo "8";
						$ret += 0.5;				
					   }
					}  		
				}// END OF CSC MC 16 & 17 REVISION
			}
	  //echo "$date = $amIn, $amOut, $pmIn, $pmOut  <br> ";
		}
	 	else {
		 	//$ret += 1;
			//echo "c1.11 isOnLeave2: ".isOnLeave2($date, $empCode)." isOnCTOTime: ".isOnCTOTime($date, $empCode)." $date $empCode <br/>";
		 	if(isOnLeave2($date, $empCode) == 1){			
				//echo "c1.11 isOnLeave2: ".isOnLeave2($date, $empCode)." isOnCTOTime: ".isOnCTOTime($date, $empCode)." $date $empCode <br/>";			
				$ret += 1;	
				//echo "$ret<br>";
			} 
			else if((isOnLeave2($date, $empCode) == 0.5) || (isOnCTOTime($date, $empCode) == 2) || (isOnCTOTime($date, $empCode) == 3)){
				//echo "11<br>";
				$ret += 0.5;
			}
			
		}#END OF ELSE, mysql_num_rows = 0
  }
  else if (WorkSuspended($date) == "no" && !isHoliday($date) && isOnLeave($date, $empCode) && !isDayOff($opt_id, $date) && isOnTO($date, $empCode) == 4 && isOnCTO($date, $empCode) == 0){
		if ($empCode == $checkCode && $troubleshootFlag) echo "c2"." ".$date." ".isOnLeave($date, $empCode)."<br/>";
	$query = mysql_query("select fldAMPMWD from tblTravelOrder where fldEmpCode='$empCode' AND fldFromDate='$date' AND fldApproved='1'");
	$row = mysql_fetch_array($query);
	if (LeaveTime($date, $empCode) == "(AM)" && $row[0] == 3){
		$ret += 0.5;
	}
	else if (LeaveTime($date, $empCode) == "(PM)" && $row[0] == 2){
		$ret += 0.5;
	}
	else {
	 $ret += 0.5;
	}
  }
  else if (WorkSuspended($date) == "no" && !isHoliday($date) && !isOnLeave($date, $empCode) && !isDayOff($opt_id, $date) && isOnTO($date, $empCode) == 4 && isOnCTO($date, $empCode) == 0){
		if ($empCode == $checkCode && $troubleshootFlag) echo "d"." ".$date."<br/>";
     $query = mysql_query("select * from tblEmpDTR where fldEmpCode='$empCode' AND fldEmpDTRdate='$date'");
	  //echo isOnLeave2($date, $empCode)." ".$date;
	 if (mysql_num_rows($query) > 0){
	  $row = mysql_fetch_array($query);
	  $amIn = $row['fldEmpDTRamIn'];
	  $amOut = $row['fldEmpDTRamOut'];
	  $pmIn = $row['fldEmpDTRpmIn'];
	  $pmOut = $row['fldEmpDTRpmOut'];
	  
	  //check kung anong time ng day
	  if ($amIn != "00:00:00" && $pmOut != "00:00:00"){
	   $ret =  0;
	  }
	  else{
	   if ($amIn == "00:00:00" && $amOut == "00:00:00" && isOnTOTime($date, $empCode) == 3){
	    $ret += 0.5;
	   }
	   if ($pmIn == "00:00:00" && $pmOut == "00:00:00" && isOnTOTime($date, $empCode) == 2){
	    $ret += 0.5;
		//echo "www";
	   }
	  }
	 }
	 else{
	 	$ret += 0.5;
	 }
  }
  else if (WorkSuspended($date) == "no" && !isHoliday($date) && isOnLeave($date, $empCode) && !isDayOff($opt_id, $date) && isOnTO($date, $empCode) == 0 && isOnCTO($date, $empCode) == 0.5){
		if ($empCode == $checkCode && $troubleshootFlag) echo "e1"." ".$date."<br/>";
     $query = mysql_query("select * from tblEmpDTR where fldEmpCode='$empCode' AND fldEmpDTRdate='$date'");
	 //echo isOnCTOTime($date, $empCode)." ".$date;
	 if (mysql_num_rows($query) > 0){	 	
	  $row = mysql_fetch_array($query);
	  $amIn = $row['fldEmpDTRamIn'];
	  $amOut = $row['fldEmpDTRamOut'];
	  $pmIn = $row['fldEmpDTRpmIn'];
	  $pmOut = $row['fldEmpDTRpmOut'];
	  
	  //check kung anong time ng day
	  if ($amIn != "00:00:00" && $amOut != "00:00:00" && $pmIn != "00:00:00" && $pmOut != "00:00:00"){
	   $ret =  0;
	  }
	  else{
	   if ($amIn == "00:00:00" && $amOut == "00:00:00" && isOnCTOTime($date, $empCode) == 3){
	    $ret += 0.5;		
	   }
	   if ($pmIn == "00:00:00" && $pmOut == "00:00:00" && isOnCTOTime($date, $empCode) == 2){
	    $ret += 0.5;		
	   }
	    
		#WITH CTO
		#if(isOnCTO($date, $empCode) > 1)
		#	$ret += 0.5;		     
	  }
	 }
	 else{
	 	#echo "ELSE E1 <br/>";
		# worksuspended no, isholiday no, isonleave 1, isdayoff no, isonto == 0, isoncto == 0.5
		# WITHOUT DTR ENTRY
	 #	$ret += 0.5;
		#if(isOnLeave($date, $empCode) && !isOnCTO($date, $empCode)) $ret += 0.5;
		if((isOnCTOTime($date, $empCode) == 3 || isOnCTOTime($date, $empCode) == 2) && (LeaveTime($date, $empCode) == "(PM)" || LeaveTime($date, $empCode) == "(AM)")) $ret += 0.5;
	 }
  }
  else if (WorkSuspended($date) && !isHoliday($date) && isOnLeave($date, $empCode) && !isDayOff($opt_id, $date) && isOnTO($date, $empCode) == 0 && isOnCTO($date, $empCode) == 0){
     $query = mysql_query("select * from tblEmpDTR where fldEmpCode='$empCode' AND fldEmpDTRdate='$date'");
		if ($empCode == $checkCode && $troubleshootFlag) echo "e1.5 ".$date."<br/>";
	 // echo isOnCTOTime($date, $empCode)." ".$date;
	 if (mysql_num_rows($query) > 0){
	  $row = mysql_fetch_array($query);
	  $amIn = $row['fldEmpDTRamIn'];
	  $amOut = $row['fldEmpDTRamOut'];
	  $pmIn = $row['fldEmpDTRpmIn'];
	  $pmOut = $row['fldEmpDTRpmOut'];
	  
	  //check kung anong time ng day
	  if ($amIn != "00:00:00" && $pmOut != "00:00:00"){
	   $ret =  0;
	  }
	  else{
	   if ($amIn == "00:00:00" && $amOut == "00:00:00" && LeaveTime($date, $empCode) == "(PM)"){
	    $ret += 0.5;
		//echo "ttt";
	   }
	   if ($pmIn == "00:00:00" && $pmOut == "00:00:00" && LeaveTime($date, $empCode) == "(AM)"){
	    $ret += 0.5;
		//echo "www";
	   }
	  }
	 }
	 else{
	 		//$suspendedArray = WorkSuspended($date);
	 		if (LeaveTime($date, $empCode) == "(AM)" || LeaveTime($date, $empCode) == "(PM)") {
				$ret += 0.5;
			} else {
				$ret += 1;
			}
	 }
  }
  else if (WorkSuspended($date) && !isHoliday($date) && isOnLeave($date, $empCode) && !isDayOff($opt_id, $date) && isOnTO($date, $empCode) == 0 && isOnCTO($date, $empCode) == 0.5){
    if ($empCode == $checkCode && $troubleshootFlag) echo "e2"." ".$date."<br/>";
     $query = mysql_query("select * from tblEmpDTR where fldEmpCode='$empCode' AND fldEmpDTRdate='$date'");
	 // echo isOnCTOTime($date, $empCode)." ".$date;
	 if (mysql_num_rows($query) > 0){
	  $row = mysql_fetch_array($query);
	  $amIn = $row['fldEmpDTRamIn'];
	  $amOut = $row['fldEmpDTRamOut'];
	  $pmIn = $row['fldEmpDTRpmIn'];
	  $pmOut = $row['fldEmpDTRpmOut'];
	  
	  //check kung anong time ng day
	  if ($amIn != "00:00:00" && $pmOut != "00:00:00"){
	   $ret =  0;
	  }
	  else{
	   if ($amIn == "00:00:00" && $amOut == "00:00:00" && isOnCTOTime($date, $empCode) == 3){
	    $ret += 0.5;
	   }
	   if ($pmIn == "00:00:00" && $pmOut == "00:00:00" && isOnCTOTime($date, $empCode) == 2){
	    $ret += 0.5;
		//echo "www";
	   }
	  }
	 }
	 else{
	 	$ret += 0.5;
	 }
  }
   else if (WorkSuspended($date) && holidayTime($date) < 3 && isHoliday($date) && isOnLeave($date, $empCode) && !isDayOff($opt_id, $date) && isOnTO($date, $empCode) == 0 && isOnCTO($date, $empCode) == 0){
    if ($empCode == $checkCode && $troubleshootFlag) echo "e3"." ".$date."<br/>";
     $query = mysql_query("select * from tblEmpDTR where fldEmpCode='$empCode' AND fldEmpDTRdate='$date'");
	 // echo isOnCTOTime($date, $empCode)." ".$date;
	 if (mysql_num_rows($query) > 0){
	  $row = mysql_fetch_array($query);
	  $amIn = $row['fldEmpDTRamIn'];
	  $amOut = $row['fldEmpDTRamOut'];
	  $pmIn = $row['fldEmpDTRpmIn'];
	  $pmOut = $row['fldEmpDTRpmOut'];
	  
	  //check kung anong time ng day
	  if ($amIn != "00:00:00" && $pmOut != "00:00:00"){
	   $ret =  0;
	  }
	  else{
	   if ($amIn == "00:00:00" && $amOut == "00:00:00" && LeaveTime($date, $empCode) == "(PM)"){
	    $ret += 0.5;
	   }
	   if ($pmIn == "00:00:00" && $pmOut == "00:00:00" && LeaveTime($date, $empCode) == "AM"){
	    $ret += 0.5;
		//echo "www";
	   }
	  }
	 }
	 else{
	 	$ret += 0.5;
	 }
  }
 else{
  if ($empCode == $checkCode && $troubleshootFlag) echo "f"." ".$date."<br/>";
   $ret = 0;
  }
 //echo isOnLeave($date, $empCode)." ".$date;
  return $ret;
 }

 function isAbsent22($date, $opt_id, $empCode){
  //check kung holiday, suspended work, on leave, or on CTO
  $ret = 0;
  //echo $date." ";
  if (WorkSuspended($date) == "no" && !isHoliday($date) && !isOnLeave2($date, $empCode) && !isDayOff($opt_id, $date) && isOnTO($date, $empCode) == 0 && isOnCTO($date, $empCode) == 0){
  #echo "a"." ".$date."<br/>";
   //if not on leave, holiday, or work suspended
     $query = mysql_query("select * from tblEmpDTR where fldEmpCode='$empCode' AND fldEmpDTRdate='$date'");
	 if (mysql_num_rows($query) > 0){
	  $row = mysql_fetch_array($query);
	  $amIn = $row['fldEmpDTRamIn'];
	  $amOut = $row['fldEmpDTRamOut'];
	  $pmIn = $row['fldEmpDTRpmIn'];
	  $pmOut = $row['fldEmpDTRpmOut'];
	  #echo "AM:: $amIn $amOut <br/>PM:: $pmIn $pmOut <br/><br/>";
	  
	  if ($amIn != "00:00:00" && $amOut != "0000-00-00" && $pmIn != "0000-00-00" && $pmOut != "00:00:00"){
	   $ret =  0;
	  }
	  else{
	   if ($amIn == "00:00:00" && $amOut == "00:00:00"){
	    $ret += 0.5;
	   }
	   if ($pmIn == "00:00:00" && $pmOut == "00:00:00"){
	    $ret += 0.5;
	   }
	  }
	 }
  }
  else if (WorkSuspended($date) == "no" && !isHoliday($date) && !isOnLeave($date, $empCode) && !isDayOff($opt_id, $date) && isOnTO($date, $empCode) == 4 && isOnCTO($date, $empCode) == 0){
  #echo "d"." ".$date."<br/>";
     $query = mysql_query("select * from tblEmpDTR where fldEmpCode='$empCode' AND fldEmpDTRdate='$date'");
	  //echo isOnLeave2($date, $empCode)." ".$date;
	 if (mysql_num_rows($query) > 0){
	  $row = mysql_fetch_array($query);
	  $amIn = $row['fldEmpDTRamIn'];
	  $amOut = $row['fldEmpDTRamOut'];
	  $pmIn = $row['fldEmpDTRpmIn'];
	  $pmOut = $row['fldEmpDTRpmOut'];
	  #echo "AM:: $amIn $amOut <br/>PM:: $pmIn $pmOut <br/><br/>";
	   
	  //check kung anong time ng day
	  if ($amIn != "00:00:00" && $pmOut != "00:00:00"){
	   $ret =  0;
	  }
	  else{
	   if ($amIn == "00:00:00" && $amOut == "00:00:00" && isOnTOTime($date, $empCode) == 3){
	    $ret += 0.5;
	   }
	   if ($pmIn == "00:00:00" && $pmOut == "00:00:00" && isOnTOTime($date, $empCode) == 2){
	    $ret += 0.5;
		//echo "www";
	   }
	  }
	 }
	 else{
	 	$ret += 0.5;
	 }
  }
  else if (WorkSuspended($date) == "no" && !isHoliday($date) && isOnLeave($date, $empCode) && !isDayOff($opt_id, $date) && isOnTO($date, $empCode) == 0 && isOnCTO($date, $empCode) == 0.5){
  #echo "e"." ".$date."<br/>";
     $query = mysql_query("select * from tblEmpDTR where fldEmpCode='$empCode' AND fldEmpDTRdate='$date'");
	 // echo isOnCTOTime($date, $empCode)." ".$date;
	 if (mysql_num_rows($query) > 0){
	  $row = mysql_fetch_array($query);
	  $amIn = $row['fldEmpDTRamIn'];
	  $amOut = $row['fldEmpDTRamOut'];
	  $pmIn = $row['fldEmpDTRpmIn'];
	  $pmOut = $row['fldEmpDTRpmOut'];
	  #echo "AM:: $amIn $amOut <br/>PM:: $pmIn $pmOut <br/><br/>";
	   
	  //check kung anong time ng day
	  if ($amIn != "00:00:00" && $pmOut != "00:00:00"){
	   $ret =  0;
	  }
	  else{
	   if ($amIn == "00:00:00" && $amOut == "00:00:00" && isOnCTOTime($date, $empCode) == 3){
	    $ret += 0.5;
	   }
	   if ($pmIn == "00:00:00" && $pmOut == "00:00:00" && isOnCTOTime($date, $empCode) == 2){
	    $ret += 0.5;
		//echo "www";
	   }
	  }
	 }
	 else{
	 	$ret += 0.5;
	 }
  }

  return $ret;
 }

 function isAbsent44($date, $opt_id, $empCode){
  //check kung holiday, suspended work, on leave, or on CTO
  $ret = 0;
  //echo $date." ";
  if (WorkSuspended($date) == "no" && !isHoliday($date) && !isOnLeave($date, $empCode) && !isDayOff($opt_id, $date) && isOnTO($date, $empCode) == 0 && isOnCTO($date, $empCode) == 0){
//  echo "a"." ".$date;
   //if not on leave, holiday, or work suspended
     $query = mysql_query("select * from tblEmpDTR where fldEmpCode='$empCode' AND fldEmpDTRdate='$date'");
	 if (mysql_num_rows($query) > 0){
	  $row = mysql_fetch_array($query);
	  $amIn = $row['fldEmpDTRamIn'];
	  $amOut = $row['fldEmpDTRamOut'];
	  $pmIn = $row['fldEmpDTRpmIn'];
	  $pmOut = $row['fldEmpDTRpmOut'];
	  
	  if ($amIn != "00:00:00" && $amOut != "0000-00-00" && $pmIn != "0000-00-00" && $pmOut != "00:00:00"){
	   $ret =  0;
	  }
	  else{
	   if ($amIn == "00:00:00" && $amOut == "00:00:00"){
	    $ret += 0.5;
	   }
	   if ($pmIn == "00:00:00" && $pmOut == "00:00:00"){
	    $ret += 0.5;
	   }
	  }
	 }
	 else{ $ret += 1; }
  }

  return $ret;
 }

 function isAbsent2($date, $opt_id, $empCode){
  //check kung holiday, suspended work, on leave, or on CTO
  $ret = 0;
  //echo $date." ";
  if (WorkSuspended($date) == "no" && !isHoliday($date) && !isOnLeave($date, $empCode) && !isDayOff($opt_id, $date) && isOnTO($date, $empCode) == 0 && isOnCTO($date, $empCode) == 0){
   //if not on leave, holiday, or work suspended
     $query = mysql_query("select * from tblEmpDTR where fldEmpCode='$empCode' AND fldEmpDTRdate='$date'");
	 if (mysql_num_rows($query) > 0){
	  $row = mysql_fetch_array($query);
	  $amIn = $row['fldEmpDTRamIn'];
	  $amOut = $row['fldEmpDTRamOut'];
	  $pmIn = $row['fldEmpDTRpmIn'];
	  $pmOut = $row['fldEmpDTRpmOut'];
	  
	  if ($amIn != "00:00:00" && $pmOut != "00:00:00"){
	   $ret =  0;
	  }
	  else{
	   if ($amIn == "00:00:00" && $amOut == "00:00:00"){
	    $ret += 0.5;
	   }
	   if ($pmIn == "00:00:00" && $pmOut == "00:00:00"){
	    $ret += 0.5;
	   }
	  }
	 }
	 else{
	  $ret = 1;
	 }
  }
  else if (WorkSuspended($date) == "no" && isHoliday($date) && !isOnLeave($date, $empCode) && !isDayOff($opt_id, $date) && isOnTO($date, $empCode) == 0 && isOnCTO($date, $empCode) == 0){
     $query = mysql_query("select * from tblEmpDTR where fldEmpCode='$empCode' AND fldEmpDTRdate='$date'");
	 if (mysql_num_rows($query) > 0){
	  $row = mysql_fetch_array($query);
	  $amIn = $row['fldEmpDTRamIn'];
	  $amOut = $row['fldEmpDTRamOut'];
	  $pmIn = $row['fldEmpDTRpmIn'];
	  $pmOut = $row['fldEmpDTRpmOut'];
	  
	  if ($amIn != "00:00:00" && $pmOut != "00:00:00"){
	   $ret =  0;
	  }
	  else{
	   if ($amIn == "00:00:00" && $amOut == "00:00:00" && holidayTime($date) == 2){
	    $ret += 0.5;
	   }
	   else if ($pmIn == "00:00:00" && $pmOut == "00:00:00" && holidayTime($date) == 1){
	    $ret += 0.5;
	   }
	  }
	 }
	 else{
	  if (holidayTime($date) != 3){
	  	$ret += 1;
	  }
	 }
  }
  else{
   $ret = 0;
  }
//	 echo $ret;
  
  return $ret;
 }
 
 function isHoliday($dates){
  $query = mysql_query("select * from holidays where holidayDate='$dates'");
  if (mysql_num_rows($query) > 0){
   return true;
  }
  else{
   return false;
  }
 }
 
 function holidayTime($date){
  $query = mysql_query("select holidayTime from holidays where holidayDate='$date'");
  $row = mysql_fetch_array($query);
   return $row['holidayTime'];
 }

 function WorkSuspended($date){
  $query = mysql_query("select * from tblSuspensions where fldSuspensionDate='$date'");
  if (mysql_num_rows($query) > 0){
   $row = mysql_fetch_array($query);
   $time = $row['fldSuspensionTime'];
   $remarks = $row['fldSuspensionRemarks'];
   $minhrs = $row['fldMinHrs'];
   $array = array($time, $remarks, $minhrs);
   return $array;
  }
  else{
   return "no";
  }
 }

 function isOnLeave($date, $empCode){
 	#TAKE NOTEEEEE
  $query = mysql_query("select count(fldEmpLeaveID) 
  from tblEmpLeave 
  where fldEmpCode='$empCode' 
  	AND fldFromDate<='$date' 
  	AND fldToDate>='$date' 
  	AND fldAppFtL='1' 
  	AND fldLeaveTypeID!='5' 
  	AND fldLeaveTypeID!='13' 
  	AND fldLeaveTypeID!='14' 
  	AND fldFlagCaRe NOT IN (1,2)");
  $result = mysql_fetch_array($query);
  
  if ($result[0]){
   return true;
  }
  else{
   return false;
  }
  
 }

 function isOnLeave2($date, $empCode){
  $ret = 0;
  $query = mysql_query("select * from tblEmpLeave where fldEmpCode='$empCode' AND fldFromDate<='$date' AND 
  fldToDate>='$date' AND fldAppFtL='1' AND fldFlagCaRe='0' AND fldLeaveTypeID!='5' AND fldLeaveTypeID!='13' AND fldLeaveTypeID!='14'"); 
  if (mysql_num_rows($query) > 0){
   while ($row = mysql_fetch_array($query)){
   
    $AMPMWD = $row['fldAM_PM_WD'];
    switch($AMPMWD){
     case 0 : $ret += 1;
	 		 break;
	 case 1 : $ret += 1;
			 break;
	 case 2 : $ret += 0.5;
			 break;
	 case 3 : $ret += 0.5;
			 break;
    }
    if (date("N", strtotime($date)) >=6){
     $ret += 0;
    }
   }
    //echo "$date = maine<br>";
   }
   else{
	    //echo "$date = mark<br>";
    $ret += 0;
   }
 // }
    return $ret;
 }
 
 function isOnCTO($date, $empCode){
  $query = mysql_query("select * 
  from tblEmpLeave 
  where fldEmpCode='$empCode' 
  AND fldFromDate<='$date' 
  AND fldToDate>='$date' 
  AND fldAppFtL='1' 
  AND fldLeaveTypeID='5' 
  AND fldFlagCaRe='0'") or die(mysql_error());
  if (mysql_num_rows($query) > 0){
	$ret = 0;
   while ($row = mysql_fetch_array($query)){
   
   $AMPMWD = $row['fldAM_PM_WD'];
   
   switch($AMPMWD){
    case 0 : $ret += 1;
	 		 break;
	case 1 : $ret += 1;
			 break;
	case 2 : $ret += 0.5;
			 break;
	case 3 : $ret += 0.5;
			 break;
   }
   }
   return $ret;
  }
  else{
   $ret = 0;
   return 0;
  }
 }

 function isOnCTOTime($date, $empCode){
  $query = mysql_query("select * from tblEmpLeave where fldEmpCode='$empCode' AND fldFromDate<='$date' AND fldToDate>='$date' AND fldAppFtL='1' AND fldLeaveTypeID='5' AND fldFlagCaRe='0'");
  if (mysql_num_rows($query) > 0){
   while ($row = mysql_fetch_array($query)){
   
   	$AMPMWD = $row['fldAM_PM_WD'];
    
	return $AMPMWD;
   }
  }
  else{
   $ret = 0;
   return 0;
  }
 }
 
 function isOnTO($date, $empCode){
 //echo $date." ".$empCode;
  if (date("N", strtotime($date)) < 6){
	  $query = mysql_query("select * from tblTravelOrder where fldEmpCode='$empCode' AND fldFromDate<='$date' AND fldToDate>='$date' AND fldApproved='1'");
	  $ret = 0;
	  if (mysql_num_rows($query) > 0){
	   $row = mysql_fetch_array($query);
	   
	   $AMPMWD = $row['fldAMPMWD'];
	   
	   switch($AMPMWD){
		case 0 : $ret = 8;
				 break;
		case 1 : $ret = 8;
				 break;
		case 2 : $ret = 4;
				 break;
		case 3 : $ret = 4;
				 break;
	   }
	   return $ret;
	  }
	  else{
	   return $ret;
	  }
  }
 }

 function isOnTOTime($date, $empCode){
  if (date("N", strtotime($date)) < 6){
	  $query = mysql_query("select * from tblTravelOrder where fldEmpCode='$empCode' AND fldFromDate<='$date' AND fldToDate>='$date' AND fldApproved='1'");
	  $ret = 0;
	  if (mysql_num_rows($query) > 0){
	   $row = mysql_fetch_array($query);
	   
	   $AMPMWD = $row['fldAMPMWD'];
	   
	   switch($AMPMWD){
		case 0 : $ret = 1;
				 break;
		case 1 : $ret = 1;
				 break;
		case 2 : $ret = 2;
				 break;
		case 3 : $ret = 3;
				 break;
	   }
	   return $ret;
	  }
	  else{
	   return $ret;
	  }
  }
  
 }


 function isOnTOTime2($date, $empCode){
 //echo $date." ".$empCode;
  if (date("N", strtotime($date)) < 6){
	  $query = mysql_query("select * from tblTravelOrder where fldEmpCode='$empCode' AND fldFromDate<='$date' AND fldToDate>='$date' AND fldApproved='1'");
	  $ret = 0;
	  if (mysql_num_rows($query) > 0){
	   $row = mysql_fetch_array($query);
	   
	   $AMPMWD = $row['fldAMPMWD'];
	   
	   switch($AMPMWD){
		case 0 : $ret = "";
				 break;
		case 1 : $ret = "";
				 break;
		case 2 : $ret = "(AM)";
				 break;
		case 3 : $ret = "(PM)";
				 break;
	   }
	   return $ret;
	  }
	  else{
	   return $ret;
	  }
  }
 }

 function LeaveTime($date, $empCode){
  $query = mysql_query("select * from tblEmpLeave where fldEmpCode='$empCode' AND ((fldFromDate between '$date' AND '$date') 
  OR (fldToDate between '$date' AND '$date')) AND fldAppFtL='1' AND fldFlagCaRe='0' AND fldLeaveTypeID!='5' AND fldLeaveTypeID!='13' 
  AND fldLeaveTypeID!='14'"); 
  if (mysql_num_rows($query) > 0){
   $row = mysql_fetch_array($query);
   
   $AMPMWD = $row['fldAM_PM_WD'];

   switch($AMPMWD){
    case 0 : $ret = "";
	 		 break;
	case 1 : $ret = "";
			 break;
	case 2 : $ret = "(AM)";
			 break;
	case 3 : $ret = "(PM)";
			 break;
   }
   if (date("N", strtotime($date)) >=6){
    $ret = "";
   }
   return $ret;
  }
  else{  	
   return "";
  }
 }

 function isExempted($empCode){
 	$query = mysql_query("select * from tblDTRExemptions where fldEmpCode='$empCode'");
	return mysql_num_rows($query);
 }
 
 function isExempted2($empCode, $date){
 	
	$flag = isExempted($empCode);
	
	if($flag){
		mysql_select_db("employeedb2");
		
	 	$query = "SELECT * FROM `tblEmpPos` 
	 	WHERE fldEmpCode 
	 	LIKE '$empCode' 
	 	AND (('$date' BETWEEN `fldFromDate` AND `fldToDate`) 
	 		OR (`fldToDate` LIKE '0000-00-00' AND '$date' > fldToDate)) 
	 	ORDER BY `tblEmpPos`.`fldToDate` ASC";
	 	$sql = mysql_query($query) or die(mysql_error());
	 	
	 	mysql_select_db("camsonline2");
		
		return mysql_num_rows($sql);
	} else {
		return 0;
	}
 }
 
 function getUpdatingDTRLimit(){
 	$query = mysql_query("select * from tblDTRUpdating order by fldDate") or die(mysql_error());
	$row = mysql_fetch_array($query);
	return $row['fldDaysAfter'];
 }
 
 function canUpdate($month, $year){
 	$date = date("Y-m-d");
	$cmonth = date("m");
	$cday = date("j");
	$cyear = date("Y");
	$limit = getUpdatingDTRLimit();
	/*
	if (($year == $cyear && (($month == $cmonth-1 && $cday <= $limit) || $month >= $cmonth)) || ($year == $cyear-1 && $month == 12 && $cmonth == 1 && $cday <= $limit)){
		return true;
	}
	else{
		return false;
	}*/
	return true;
 }

 function FLExempt($empCode){
 	$query = mysql_query("select * from tblDTRExemptions where fldEmpCode='$empCode' AND fldFLExempt='1'");
	return mysql_num_rows($query);
 }
 
 function unAuthorizedLeave($date, $empCode){
 	$query = "SELECT * FROM camsonline2.tblEmpDTR WHERE fldEmpCode LIKE '$empCode' AND fldEmpDTRdate = '$date'";
 	$sql = mysql_query($query);
	$count = mysql_num_rows($sql);
	
	$flag1 = 0;
	if($count) $flag1 = 1;
		else $flag1 = 0;
		
	$array1 = array();
	$array2 = array();
	
	$query2 = "SELECT *  FROM tblempdtr WHERE fldEmpCode LIKE '$empcode' AND year(fldEmpDTRdate)='$year' and  
	month(fldEmpDTRdate)='$month' ORDER BY fldEmpDTRdate DESC";
 	$sql2 = mysql_query($query2);
	$count2 = mysql_num_rows($sql2);
	$i = 0;	
	if($count2){
		while($result2 = mysql_fetch_array($sql2)){
			$array1[$i++] = $result2['fldEmpLeaveID'];	
		}
	}
		
	$query3 = "SELECT *  FROM tblempdtr WHERE fldEmpCode LIKE '$empcode' AND year(fldEmpDTRdate)='$year' and  
	month(fldEmpDTRdate)='$month' ORDER BY fldEmpDTRdate DESC";
 	$sql3 = mysql_query($query3);
	$count3 = mysql_num_rows($sql3);
	$i = 0;	
	if($count3){
		while($result3 = mysql_fetch_array($sql3)){
			$array2[$i++] = $result3['fldEmpLeaveID'];	
		}
	}
	
	$array3 = array_intersect($array1,$array2);
	$countArray3 = count($array3);
	/*if($countArray3){
		echo $date." - ";
		print_r($array3);
		echo "<br/>";
	}*/

	
	$flag2 = 0;
	if($countArray3) $flag2 = 1;
		else $flag2 = 0;
	
	if(($flag1 == 0)&&($flag2 == 0)){
		//echo "A".$date."<br/>";
		return 1;
	} else {
		//echo "B".$date."<br/>";
		return 0;
	}
 }

function isSWAllowedOnDate($opt_id, $date){
	$day = date("w", strtotime($date));
	
	$query = mysql_query("select * from tblDTROptions where fldDTROptID='$opt_id'");
	$row = mysql_fetch_array($query);
	
	switch ($day){
	 case 1 : $off = $row['fldMonOffset'];
				 break;
	 case 2 : $off = $row['fldTueOffset'];
			  break;
	 case 3 : $off = $row['fldWedOffset'];
			  break;
	 case 4 : $off = $row['fldThuOffset'];
			  break;
	 case 5 : $off = $row['fldFriOffset'];
			  break;
	 case 6 : $off = $row['fldSatOffset'];
			  break;
	 case 0 : $off = $row['fldSunOffset'];
			  break;
	}
	
	if ($off == 0){
	 return true;
	}
	else{
	 return false;
	}
}
?>