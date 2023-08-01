<?php
//include("includes/connect_local.php");
include("connect_local.php");
include_once("functions.php");

Class ComputeDTR{
	public $total_week = 0;
	public $total_total = 0;
  public $total_tar_under = 0;
  public $total = 0;
	public $week_tar = 0;
	public $week_tar2 = 0;
  public $week_ua = 0;
	public $tot = 0;
	public $tot_tar_under = 0;
	public $tally_tar = 0;
  public $tally_tardy = 0;
  public $tally_undertime = 0;
  public $leave_minutes = 0;
	public $excess = 0;

 function getTotal($amIn, $amOut, $pmIn, $pmOut, $month1, $day, $year1, $opt_id, $empCode){
  $minusAM = 0;
  $minusPM = 0;
  $amtot = 0;
  $pmtot = 0;
  $tardy = 0;
  $undertime = 0;
  $overtime = 0;
  $total_wk = 0;
  $absent = 0;
  $ualeave=0;
  $tally_tardy = 0;
  $tally_undertime=0;
  $leave_minutes=0;
  $excess = 0;

  //get time difference

  $date = date("Y-m-d", mktime(0,0,0,$month1, $day, $year1));

  //FLAG
  $isForDebugging = 0;  //if ($_SESSION['userID'] == 197 && $isForDebugging) 
  $dateArray = array("2020-08-26", "2020-08-27");
  $isInDateForDebugging = (in_array($date, $dateArray)) ? 1 : 0 ; 
  
  if (getFirstDay($empCode) <= $date){

  $offset = getOffset(date("w", mktime(0,0,0,$month1, $day, $year1)), $opt_id);
  $row = getQRes($offset);
  $al = date("G:i",strtotime($row['fldOffAMLate']));
  $ai = date("G:i",strtotime($row['fldOffAMin']));
  $ali = date("G:i",strtotime($row['fldLOffAMin']));
  $ao = date("G:i",strtotime($row['fldOffAMout']));
    $origAO = $ao;
  $pl = date("G:i",strtotime($row['fldOffPMLate']));
  $pi = date("G:i",strtotime($row['fldOffPMin']));
  $pli = date("G:i",strtotime($row['fldLOffPMin']));
  $po = date("G:i",strtotime($row['fldOffPMout']));
    $origPO = $po;
  $plo = date("G:i",strtotime($row['fldLOffPMout']));
    
  $q = mysql_query("select fldSLBreak, fldELBreak, fldMinHrsWk, isSWAllowed from tblDTROptions where fldDTROptID='$opt_id'");
  $r = mysql_fetch_array($q);
  $sbreak = $r[0];
  $ebreak = $r[1];
  $minhrs = $r[2];
  $isSWAllowed = $r[3];

  if (WorkSuspended($date) != "no"){
  	$sus = WorkSuspended($date);
    $sus_out = $sus[0];
    
  	if ($sus_out > "12:00:00") {
  		$po = $sus_out;
      $plo = $sus[0];
    }
  	else
  		$ao = $sus_out;
		
    $minhrs -= $sus[2];
  }
  
  if(isHoliday($date) && (holidayTime($date) == 2)){
  	$ao = "12:30:00";
  }
  
  //FLAG
  $dateFlag = 0;
  
  if ($amIn != " " && $amOut != " " && !$isSWAllowed){
    //if earliest time in is not the same as the latest time in
    if (($amIn != " " && $amOut != " ")){
      //if earlier than earliest time in
      if (strtotime($amIn) < strtotime($ai)){
        $minusAM += strtotime($ai) - strtotime($amIn);
	      $excess += $minusAM/60;
      }

      //if late
      if (strtotime($amIn) > strtotime($ali)) {
        /*
        //	 if (strtotime($amIn) == strtotime($al)){
        	  $tardy += strtotime($amIn) - strtotime($ali);// + strtotime("00:01:00");
        //	 }
        //	 else{
        //	  $tardy += strtotime($amIn) - strtotime($al);
        //	 }
        	 $tally_tardy += 1;
        	 #if($empCode == "LAT002") echo "A! $date T T:$tally_tardy<br/>";
        	 //set FLAG - for checking tardiness occurring twice on the same date 
        	 $dateFlag = $date;*/

        //has work suspension; suspension is less than equal to latest amIn
        if (WorkSuspended($date) != "no" && (strtotime($ao) <= strtotime($ali))) {
          //no tardy

        //no work suspension; default late
        } else {
          $tardy += strtotime($amIn) - strtotime($ali);// + strtotime("00:01:00");
          $tally_tardy += 1;
        } 

        //set FLAG - for checking tardiness occurring twice on the same date 
        $dateFlag = $date;
      }
    }//if (strtotime($ai) != (strtotime($ali)) && ($amIn != " " && $amOut != " ")){
   	
    if ((strtotime($amOut) >= strtotime($sbreak)) && ($amOut != " ")){    
    //start, added due to 2014-10-31 issue with Boss Lea.
      if(isHoliday($date) && (holidayTime($date) == 2)){
		    if(strtotime($amOut) > strtotime('+30 minutes', strtotime($sbreak)))				
			    $minusAM += (strtotime($amOut) - strtotime('+30 minutes', strtotime($sbreak)));
		    else 
			    $minusAM *= -1;			
	    } else {
        // end
		    $minusAM += (strtotime($amOut) - strtotime($sbreak));			
      }	    
    }
    else {
      if (holidayTime($date) == 1 || holidayTime($date) == 3){
        $undertime = 0;
      }
      else {
        $undertime = strtotime($sbreak) - strtotime($amOut);
        $tally_under+=1;
      }
      $tally_tardy += 1;
      
      if ($isForDebugging && $isInDateForDebugging) echo "B! $date T T:$tally_tardy<br/>";
    }

    #if ($empCode == "DAL002" && $date == "2018-10-31") 
    if ($isForDebugging && $isInDateForDebugging) echo WorkSuspended($date)." != \"no\" && ((".strtotime($ao)." >= ".strtotime($ai).") && (".strtotime($ao)." <= ".strtotime($origAO)."))) || WorkSuspended($date) != \"no\" && ((strtotime($ao) >= strtotime($ai)) && (strtotime($ao) <= strtotime($origAO)))) <br/>";
    //has work suspension
    if (WorkSuspended($date) != "no" && ((strtotime($ao) >= strtotime($ai)) && (strtotime($ao) < strtotime($origAO)))) {
      $amCoreIn = 0;
      $amCoreOut = 0;
      //get ambottomline
      $amCoreIn = (strtotime($amIn) < strtotime($ai)) ? $ai : $amIn;
      $amCoreOut = (strtotime($amOut) > strtotime($ao)) ? $ao : $amOut;

      $minusAM += (strtotime($amCoreOut) - strtotime($amCoreIn));
    }
  }//if ($amIn != " " && $amOut != " "){
  
  if ($pmIn != " " && $pmOut != " " && !$isSWAllowed){
    //if time in (pm) is earlier than latest time in
    if (strtotime($pmIn) <= strtotime($ebreak)){
     $minusPM += strtotime($ebreak) - strtotime($pmIn);
    }

    //if late
    if (strtotime($pmIn) > strtotime($pli)) {
      /*
      $tardy += strtotime($pmIn) - strtotime($pli);// + strtotime("00:01:00");
      $tally_tardy += 1;
      #if($empCode == "LAT002") echo "C! $date T T:$tally_tardy<br/>";*/

      //has work suspension; suspension is less than equal to latest pmIn
      if (WorkSuspended($date) != "no" && (strtotime($po) <= strtotime($pli))) {
        //no tardy

      //no work suspension; default late
      } else {
        $tardy += strtotime($pmIn) - strtotime($pli);// + strtotime("00:01:00");
        $tally_tardy += 1;
      } 
    }
   
    if (($pmOut != " ")){
    //if earlier than earliest time out (pm)
	
      if (strtotime($pmOut) < strtotime($po)){
        if (holidayTime($date) == 2 || holidayTime($date) == 3){
          $undertime += 0;
        }
        else {
		      $undertime += strtotime($po) - strtotime($pmOut);
		      if($dateFlag != $date) $tally_tardy += 1;
		      if ($isForDebugging && $isInDateForDebugging) echo "D! $date T T:$tally_tardy<br/>";
        }
      }
      
      if (strtotime($pmOut) > strtotime($plo)){
        $overtime += strtotime($pmOut) - strtotime($plo);
        $minusPM += strtotime($pmOut) - strtotime($plo);
        $excess += (strtotime($pmOut) - strtotime($plo))/60;
        // echo " ".(strtotime($pmOut) - strtotime($plo))/60 ." ";
      }
    }//if (strtotime($po) != strtotime($plo) && ($pmOut != " ")){
  }//if ($pmIn != " " && $pmOut != " "){
  
  $dateFlag = ""; //FOR DUPLICATE ITERATION OF TALLY_TAR FOR DAYS WITH FULL DAY ABSENCE
  
  //PM HALF DAY ABSENT WITHOUT CTO		
  if ($pmIn == " " && $pmOut == " " && date("N", strtotime($date)) < 6 && isOnCTO($date, $empCode) == 0 
  && !isOnLeave2($date, $empCode) && !isHoliday($date) && isOnTOTime($date, $empCode) != 2 && isOnTOTime($date, $empCode) != 3 && !(WorkSuspended($date) != "no" && (strtotime($po) <= strtotime($pli))) && !$isSWAllowed) {
    $undertime += 4*60*60;
	  $tally_undertime += 1;
	  if ($isForDebugging && $isInDateForDebugging) echo "E! $date T T:$tally_tardy<br/>";
	  $dateFlag = $date;
  } 

  //AM HALF DAY ABSENT WITHOUT CTO
  if ($amIn == " " && $amOut == " "  && date("N", strtotime($date)) < 6 && isOnCTO($date, $empCode) == 0 && !isOnLeave2($date, $empCode) && !isHoliday($date) && isOnTOTime($date, $empCode) != 1 && isOnTOTime($date, $empCode) != 3 && !(WorkSuspended($date) != "no" && (strtotime($ao) <= strtotime($ali))) && !$isSWAllowed) {
  	$undertime += 4*60*60;
	  if($date != $dateFlag) $tally_tardy+=1;
  	if ($isForDebugging && $isInDateForDebugging) echo "F! $date T T:$tally_tardy<br/>";
  }

  //start: maine08092017 unauthorized whole day absent not counted as undertime/tardy
  if (($pmIn == " " && $pmOut == " ") && ($amIn == " " && $amOut == " ") && date("N", strtotime($date)) < 6 && isOnCTO($date, $empCode) == 0 && !isOnLeave2($date, $empCode) && !isHoliday($date) && isOnTOTime($date, $empCode) != 1 && isOnTOTime($date, $empCode) != 3 && !(WorkSuspended($date) != "no" && (strtotime($po) <= strtotime($pli))) && !(WorkSuspended($date) != "no" && (strtotime($ao) <= strtotime($ali))) && !$isSWAllowed) {    
  	$undertime = 0;
    $ualeave+=1;
    if ($isForDebugging && $isInDateForDebugging) echo "F.0! $date T T:$tally_tardy<br/>";
  }  
  //end: maine08092017 unauthorized whole day absent not counted as undertime/tardy

  if ((($pmIn == " " && $pmOut == " ") || ($amIn == " " && $amOut == " ")) && date("N", strtotime($date)) < 6 
  && isOnCTO($date, $empCode) == 0 && !isOnLeave2($date, $empCode) && isHoliday($date) && !$isSWAllowed){
   if ($pmIn == " " && $pmOut == " " && holidayTime($date) == 1){
    $undertime += 4*60*60;
    if ($isForDebugging && $isInDateForDebugging) echo "F.1! $date T T:$tally_tardy<br/>";
   }
   if ($amIn == " " && $amOut == " " && holidayTime($date) == 2){
    $undertime += 4*60*60;
    if ($isForDebugging && $isInDateForDebugging) echo "F.2! $date T T:$tally_tardy<br/>";
   }
  }
  
  if ((($pmIn == " " && $pmOut == " ") && ($amIn == " " && $amOut == " ")) && isOnLeave2($date, $empCode) == 0.5 && !isHoliday($date) && !$isSWAllowed){  	
	//CHECKS HALF DAY CTO
	if($amIn = " " && $amOut == " " && isOnCTOTime($date, $empCode) == 2){
		#AM
		if(LeaveTime($date, $empCode) == "(PM)"){
			#continue;
			if ($isForDebugging && $isInDateForDebugging) echo "G.1! $date T T:$tally_tardy<br/>";
		} else {
			$undertime += 4*60*60;
			$tally_tardy+=1;
			if ($isForDebugging && $isInDateForDebugging) echo "G.2! $date T T:$tally_tardy<br/>";
		}
		
	} else if($pmIn = " " && $pmOut == " " && isOnCTOTime($date, $empCode) == 3 && !$isSWAllowed){
		#PM
		#echo "$date leave time:".LeaveTime($date, $empCode)."<br/>";
		if(LeaveTime($date, $empCode) == "(AM)"){
			#continue;
			if ($isForDebugging && $isInDateForDebugging) echo "H.1! $date T T:$tally_tardy<br/>";
		} else {
			$undertime += 4*60*60;
			$tally_tardy+=1;
			if ($isForDebugging && $isInDateForDebugging) echo "H.2! $date T T:$tally_tardy<br/>";
		}
	}  
  }

  //computes the total number of hours rendered (am & pm) without the constraints
  if ($amIn != " " && $amOut != " " && !$isSWAllowed){  	  
   $amtot = (strtotime($amOut) - strtotime($amIn) - $minusAM);// + (strtotime($amOut) - strtotime($ao));   
  }
//  else{
//   $absent += 0.5;
//  }
  if ($pmIn != " " && $pmOut != " " && !$isSWAllowed){  	
   $pmtot = (strtotime($pmOut) - strtotime($pmIn) - $minusPM);// + (strtotime($amOut) - strtotime($ao));   
  }
//  else{
//   $absent += 0.5;
//  }

  // if (($amtot + $pmtot)/60 < 10){
    $mins = (($amtot + $pmtot)/60)%60;
	//$mins = $mins[1];
	//echo " a".$mins." ";
  // }
 //  else{
  //  $mins = date("i", $amtot + $pmtot);
  // }/*
  switch (isOnTOTime($date, $empCode)){
  	case 1 : $amtot = 0;
			 $pmtot = 0;
			 break;
  	case 2 : $amtot = 0;
			 break;
  	case 3 : $pmtot = 0;
			 break;
  }
#if (($undertime + $tardy) > 0){
if (($undertime > 0) || ($tardy > 0)){
   $total_tar_under = floor(($undertime + $tardy)/60/60) . " h " . (($undertime + $tardy)/60)%60 . " m";   
   	#PRINTS DATES WITH TARDINESS
     if ($isForDebugging && $isInDateForDebugging) echo $date." Undertime: $undertime || Tardy: $tardy || Tally Tardy: $tally_tardy || Total: ".$total_tar_under."<br/>";
}
else{
 $total_tar_under = "";
}
if (($amtot + $pmtot) > 0){
   $total = floor(($amtot + $pmtot)/60/60) . " h " . $mins . " m";
}
else{
 $total = "";
}

   $total_overtime = floor($overtime/60/60) . " h " . ($overtime/60) . " m";
//  if (date("H:i", $amtot + $pmtot) == "00:00"){
//   $total = " ";
//  }
//  if (date("H:i", $undertime + $tardy) == "00:00"){
//   $total_tar_under = " ";
//  }
//  if (date("H:i", $overtime) == "00:00"){
//   $total_overtime = " ";
//  }

// if (){
 	 $total_wk = ($amtot + $pmtot)/60;
	 //echo $total_wk;
 //}
 //else{
// 	$total_wk = 0;
// }
  //echo ($amtot + $pmtot)/60;
 // echo $total_wk." d ";
 
	//CHECK
	if ($isForDebugging && $isInDateForDebugging) echo "CLASSES: Date: $date Undertime: $undertime <br/>";
	if ($isForDebugging && $isInDateForDebugging) echo "CLASSES: Date: $date Tardy: $tardy <br/>";

  $this->total = $total;
  $this->total_tar_under = $total_tar_under;
  //$this->total_ot = $total_overtime;
  $this->total_week = $total_wk;
  //$this->week_ot = hoursToMins(date("G", $overtime)) + date("i", $overtime);
// if (($undertime + tardy) > 0){

  $this->week_tar = ($undertime + $tardy)/60;

  if (($undertime + $tardy) > 0){
  	$this->week_tar2 = floor(($undertime + $tardy)/60/60). " h ". (($undertime + $tardy)/60)%60 . " m";
  }
  else if ($undertime==0){
	$this->week_ua = 0;
  }	  
  else{
  	$this->week_tar2 = 0;
  }
// }
// else{
//  $this->week_tar = 0;
// }
  $this->tot = ($amtot + $pmtot)/60;
  //echo floor(($amtot + $pmtot)/60/60) . " h ". $mins . " m";
  //$this->tot_ot = hoursToMins(date("G", $overtime)) + date("i", $overtime);
  //$this->tot_tar_under = ($undertime + $tardy)/60;
  $this->tot_tardy = $tardy/60;
  $this->tot_undertime = $undertime/60;
  $this->tally_tardy = $tally_tardy;
  $this->tally_tar = $tally_tardy;
  $this->tally_undertime = $tally_undertime;
  if ($isForDebugging && $isInDateForDebugging) echo "CLASSES :: Date: $date Tally Tardy: $tally_tardy <br/>";
  $this->excess = $excess;

  //gets daily leave minutes
  $this->leave_minutes = isOnLeave2($date, $empCode)*60;

  if ($_SESSION['userID'] == 197 && $isForDebugging && $isInDateForDebugging) {
    echo "total: ".$this->total."<br/>";
    echo "total_tar_under: ".$this->total_tar_under."<br/>";
    echo "total_week: ".$this->total_week."<br/>";
    echo "week_tar: ".$this->week_tar."<br/>";
    echo "week_tar2: ".$this->week_tar2."<br/>";
    echo "week_ua: ".$this->week_ua."<br/>";
    echo "tot: ".$this->tot."<br/>";
    echo "tot_tardy: ".$this->tot_tardy."<br/>";
    echo "tot_undertime: ".$this->tot_undertime."<br/>";
    echo "tally_tardy: ".$this->tally_tardy."<br/>";
    echo "tally_tar: ".$this->tally_tar."<br/>";
    echo "excess: ".$this->excess."<br/>";
    echo "leave_minutes: ".$this->leave_minutes."<br/>";
  }

  if ($isForDebugging && $isInDateForDebugging) echo "$empCode :: $date :: isOnLeave:".isOnLeave($date, $empCode)." :: isOnLeave2:".isOnLeave2($date, $empCode)." :: leaveTime:".leaveTime($date,$empCode)." leave_minutes:".$this->leave_minutes."<br/>";

  //$total_wk = 0;
//  $this->absent = $absent;
//echo ($undertime + $tardy)/60 . " ".$day;
 }
  }#end of if first date of service is less than or equal to current date
}

Class Employee{
 function getEmployeeInfo($empCode){
  $date = date("Y-m-d");
  $query = mysql_query("select * from employeedb2.tblEmpDivDes where fldEmpCode='$empCode' AND (fldToDate='0000-00-00' OR fldToDate>='$date')");
  $row = mysql_fetch_array($query);

  $query2 = mysql_query("select * from employeedb2.tblEmployees where fldEmpCode='$empCode'");
  $row2 = mysql_fetch_array($query2);
  
  $query3 = mysql_query("select * from employeedb2.tblEmpPos where fldEmpCode='$empCode' AND (fldToDate='0000-00-00') order by fldFromDate desc");
  $row3 = mysql_fetch_array($query3);
  
  $div_id = $row['fldDivID'];
  $pos_id = $row3['fldPosID'];
  
  $q = mysql_query("select fldDivAcro from employeedb2.tblDivisions where fldDivID='$div_id'");
  $r = mysql_fetch_array($q);
  
  $qq = mysql_query("select fldPosDesc from employeedb2.tblPositions where fldPosID='$pos_id'");
  $rr = mysql_fetch_array($qq);

  $this->fname = $row2['fldEmpFName'];
  $this->lname = $row2['fldEmpLName'];
  $this->mi = $row2['fldEmpMName'][0].".";
  $this->ext = $row2['fldEmpEName'];
  $this->div_id = $div_id;
  $this->division = $r[0];
  $this->position = $rr[0];
  $this->employ = @$row['fldEmployID'];
 }
}
Class Director{
 function getDirectorInfo($div_id){
 $curr_date = date("Y-m-d");
 $q = mysql_query("select fldEmpCode, fldDesigID from employeedb2.tblEmpDivDes 
 where fldDivID='$div_id' AND ( ((fldDesigID between '1' AND '5') OR fldDesigID = 92)  OR fldDesigID = 92)  
 AND (fldToDate='0000-00-00' OR fldToDate>='$curr_date')");
 $r = mysql_fetch_array($q);
 $des = $r[1];

  $query = mysql_query("select * from employeedb2.tblDesignations where DesignationID='$des'");
  $row = mysql_fetch_array($query);
  
  $empCode = $r['fldEmpCode'];
  
  $query3 = mysql_query("select p.fldPosDesc from employeedb2.tblPositions as p, employeedb2.tblEmpPos as ep where ep.fldEmpCode='$empCode' AND p.fldPosID=ep.fldPosID AND (fldToDate='0000-00-00' OR NULL)");
  $row3 = mysql_fetch_array($query3);
  
  $query2 = mysql_query("select * from employeedb2.tblEmployees where fldEmpCode='$empCode'");
  $row2 = mysql_fetch_array($query2);

  $this->fname = $row2['fldEmpFName'];
  $this->lname = $row2['fldEmpLName'];
  $this->mi = $row2['fldEmpMName'][0];
  $this->ext = $row2['fldEmpEName'];
  $this->des = $row['Designation'];
  $this->position = $row3[0];
 }
}

Class Leaves{
 function getEmpLeaves($empCode, $date){
 	$leave = "";
	$hours = 0;
	$vl = 0;
	$sl = 0;
	$fl = 0;
	$ua = 0;
	$fl2 = 0;
	$id = 0;
	$array = array();
	
  $query = mysql_query("select * from tblEmpLeave where fldEmpCode='$empCode' AND fldAppFtL='1' AND (fldFromDate<='$date' AND fldToDate>='$date') AND fldAppFtL='1' AND fldFlagCaRe='0' AND fldLeaveTypeID!='13' and fldLeaveTypeID!='14'");
  $query2 = mysql_query("select * from tblEmpLeave where fldEmpCode='$empCode' AND (fldFromDate<='$date' AND fldToDate>='$date') AND fldAppFtL='0' AND fldAppFtLDate!='0000-00-00 00:00:00' AND fldLeaveTypeID='6' AND fldFlagCaRe='0'");
  //echo mysql_num_rows($query2)."select * from tblEmpLeave where fldEmpCode='$empCode'AND (fldFromDate<='$date' AND fldToDate>='$date') AND fldAppFtL='0' AND fldAppFtLDate!='0000-00-00 00:00:00' AND fldLeaveTypeID='6' AND fldFlagCaRe='0'";
  if (mysql_num_rows($query) > 0){
   while ($row = mysql_fetch_array($query)){
	   $id = $row['fldLeaveTypeID'];
	   $dur = $row['fldAM_PM_WD'];
	   if ($dur == 1 || $dur == 0){
		$hours += 8;
		if ($id == 1) $vl += 8;
		if ($id == 2) $sl += 8;
		if ($id == 6) $fl += 8;
		if ($id == 14) $ua += 8;
	   }
	   if ($dur == 2 || $dur == 3){
		$hours += 4;
		if ($id == 1) $vl += 4;
		if ($id == 2) $sl += 4;
		if ($id == 6) $fl += 4;
	   }
	   
	   if (date("N", strtotime($date)) < 6){
		   $q = mysql_query("select fldLeaveTypeDesc from tblLeaveType where fldLeaveTypeID='$id'");
		   $r = mysql_fetch_array($q);
		   $leave .= $r[0]."<br>";
		   $array = $hours/8;
	   }
	   else{
		$leave = "";
	   }
   }
  }
  else if (mysql_num_rows($query2) > 0){
   while ($row = mysql_fetch_array($query2)){
	   $id = $row['fldLeaveTypeID'];
	   $dur = $row['fldAM_PM_WD'];
	   if ($dur == 1 || $dur == 0){
		$hours += 8;
		if ($id == 1) $vl += 8;
		if ($id == 2) $sl += 8;
		if ($id == 6) $fl2 += 8;
		if ($id == 14) $ua += 8;
	   }
	   if ($dur == 2 || $dur == 3){
		$hours += 4;
		if ($id == 1) $vl += 4;
		if ($id == 2) $sl += 4;
		if ($id == 6) $fl2 += 4;
	   }
	   
	   if (date("N", strtotime($date)) < 6){
		   $q = mysql_query("select fldLeaveTypeDesc from tblLeaveType where fldLeaveTypeID='$id'");
		   $r = mysql_fetch_array($q);
		   $leave .= $r[0]."<br>";
		   $array = $hours/8;
	   }
	  // else{
		$leave = "";
	  // }*/
   }
  }
  else{
  //if same week
   
   $leave = "";
   $hours = 0;
   $array = array();
  }
 // echo $sl;
  $this->leave = $leave;
  $this->hours = $hours;
  $this->type = $id;
  $this->larray = $array;
  $this->id = $id;
  $this->sl = $sl;
  $this->vl = $vl;
  $this->ua = $ua;
  $this->fl = $fl;
  $this->fl2 = $fl2;
 }
}

Class Holidays{
 function getHolidays($date){
  $query = mysql_query("select * from holidays where holidayDate='$date'");
  if (mysql_num_rows($query) > 0){
   $row = mysql_fetch_array($query);
   $desc = $row['holidayDesc'];
   $time = $row['holidayTime'];
   
   if ($time == 3){
    $hours = 8;
   }
   if ($time == 2 || $time == 1){
    $hours = 4;
   }
  }
  else{
   $hours = 0;
   $desc = "";
  }
  $this->holiday = $desc;
  $this->hours = $hours;
 }
}

Class Marshal{
 function getMarshal($div_id){
  $curr_date = date("Y-m-d");
  $q = mysql_query("select U.fldEmpCode from employeedb2.tblEmpDivDes As D, employeedb2.tblUsers As U where D.fldEmpCode=U.fldEmpCode AND D.fldDivID='$div_id' AND (U.fldCAMS='1' OR U.fldCAMS='3') AND (fldToDate='0000-00-00' OR fldToDate>='$curr_date')");
  $r = mysql_fetch_array($q);
  $code = $r[0];
  
  $query2 = mysql_query("select * from employeedb2.tblEmployees where fldEmpCode='$code'");
  $row2 = mysql_fetch_array($query2);

  $this->fname = $row2['fldEmpFName'];
  $this->lname = $row2['fldEmpLName'];
  $this->mi = $row2['fldEmpMName'][0].".";
  $this->ext = $row2['fldEmpEName'];
 }
}


?>