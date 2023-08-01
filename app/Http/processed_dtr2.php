<?php
session_start();
 include("includes/functions.php");
 include("includes/classes.php");
 header('Content-Type: text/html; charset=utf-8');

 function checkEmployee ($employee = NULL){
    if (employeeToCheck == "ALL") 
	    return TRUE;
	else 
	    return (employeeToCheck == $employee) ? TRUE : FALSE ;
 }
 
 //FLAG
 #user_CAMS == 3, administrator
 #user_CAMS == 1, marshall
 #userID == 197, administrator
 $isForDebugging = FALSE;
 $displayWeeklyDebugging = FALSE;
 $displayDailyDebugging = FALSE;
 DEFINE("employeeToCheck", "CAR010");

 //FLAG
 if ($_SESSION['userID'] == 197 && $isForDebugging) {
    $isForDebugging = 1;
 } else $isForDebugging = 0;
 //if ($isForDebugging && checkEmployee($empCode))

 if (@$_SESSION['user_CAMS'] == 3 || ($_POST['from_month'] == 3 && $_POST['to_year'] == 2020)){	//exemption for March 2020 because of 2 DTR options in a month
 $month=date("m");
 $year=date("Y");
  $empCode = $_POST['empCode'];
  $month1 = $_POST['from_month'];
  $day1 = $_POST['from_day'];
  $year2 = $_POST['to_year'];
  $day2 = $_POST['to_day'];
  $dtrVer = $_POST['dtrVer'];
 }
 else{
 $month=date("m");
 $year=date("Y");
  $empCode = $_POST['empCode'];
  $month1 = $_POST['from_month'];
  $day1 = 1;
  $year2 = $_POST['to_year'];
  $day2 = date("t", mktime(0,0,0,$month1,1,$year2));
  $dtrVer = $_POST['dtrVer'];
 }
 
 $DTR = new ComputeDTR();
 $emp = new Employee();
 $emp->getEmployeeInfo($empCode);
 $fname = $emp->fname;
 $mi = $emp->mi;
 $lname = $emp->lname;
 $name = $fname." ".$mi." ".$lname;
 $division = $emp->division;
 $div_id = $emp->div_id;
 $tally_tardy = 0;
 $tally_undertime = 0;
// $dir = new Director();
// $dir->getDirectorInfo($div_id);
// $director = $dir->fname." ".$dir->lname;
 //$
 if (!isDirector($empCode)){
 $dir = new Director();
 $dir->getDirectorInfo($div_id);
 $director = strtoupper($dir->fname." ".$dir->mi.". ".$dir->lname);
// $position = $dir->des;
 $position = $dir->position;
 }
 else{
  $dd = date("Y-m-d");
  $q = mysql_query("select * from employeedb2.tblEmpDivDes where fldDesigID='1' AND fldFromDate<='$dd' AND (fldToDate>='$dd' OR fldToDate='0000-00-00' OR fldToDate='NULL')");
  $r = mysql_fetch_array($q);
  $dir = new Director();
  $dir->getDirectorInfo($r['fldDivID']);
  $director = $dir->fname." ".$dir->mi." ".$dir->lname;
// $position = $dir->des;
 $position = $dir->position;
 }

 $date2 = date("Y-m-d", mktime(0,0,0,$month1,$day2,$year2)); 
 
 $lv = new Leaves();
 $hol = new Holidays();

 $o = getOptID(date("Y-m-d", mktime(0,0,0,$month1, $day1, $year2)));

 $s = mysql_query("select fldDTROptDesc, isWFHAllowed, isSWAllowed, fldMinHrsWk from tblDTROptions where fldDTROptID='$o'");
 $sr = mysql_fetch_array($s);
 $scheme = $sr[0];
 $minhrs = $sr[3];
 $scheme = workingSchemes($month1, $day1, $day2, $year2);
  if ($day1 != $day2){
	$duration = date("F",mktime(0,0,0,$month1,1,$year2))." ".$day1."-".$day2." ".$year2;
  }
  else{
  	$duration = date("F",mktime(0,0,0,$month1,1,$year2))." ".$day1." ".$year2;
  }
  
?>
<html>
 <head>
  <title>PCAARRD/QSF.AD.90</title>
  <link href="interface/style.css" type="text/css" rel=stylesheet>
<SCRIPT LANGUAGE="JavaScript">
function myprint()
{
window.print();
}
//  End -->
</script>
</head>
<body>
	<table align="left">
		<tr><td colspan="2"><form><input type="button" value="Print DTR" onClick="myprint()"></form></td></tr>
	</table>
	<table align="center" class="print" cellspacing="0" cellpadding="0">
		<tr>
    	<td class="dtrHead1" align="left" colspan="2">PCAARRD/QSF.AD.90</td>
    </tr>
		<tr><td>&nbsp;</td></tr>
		<tr>
    	<td class="dtrHead" colspan="2">Philippine Council for Agriculture, Aquatic and Natural Resources Research and Development (PCAARRD)</td>
		</tr>
		<tr>
    	<td class="dtrHead" colspan="2">Los Baños, Laguna</td>
   	</tr>
  	<tr>
    	<td class="dtrHead" colspan="2">DAILY TIME RECORD</td>
   	</tr>
   	<tr>
    	<td align="left" class="dtrPrint"><span class="dtrHead2">Name:&nbsp;&nbsp;</span><?php echo $name; ?></td>
    	<td align="right" class="dtrPrint"><span class="dtrHead2">Pay Ending:&nbsp;&nbsp;</span><?php echo $duration; ?></td>
   	</tr>
   	<tr>
    	<td align="left" class="dtrPrint13"><span class="dtrHead2">Division:&nbsp;&nbsp;</span><?php echo $division; ?></td>
    	<td align="right" class="dtrPrint13"><span class="dtrHead2">Working Scheme:&nbsp;&nbsp;</span><?php echo $scheme; ?></td>
   	</tr>
   	<tr>
    	<td colspan="2" align="center" class="dtrPrint13">
	 			<table cellpadding="0" border="0">
					<tr>
				   	<td>&nbsp;</td>
				   	<td>&nbsp;</td>
				   	<td class="dtrPrint1" colspan="2" align="center">Morning</td>
				   	<td class="dtrPrint1" colspan="2" align="center">Afternoon</td>
				   	<td>&nbsp;</td>
				   	<td>&nbsp;</td>
				   	<td>&nbsp;</td>
				   	<td class="dtrPrint1" rowspan="2" valign="middle"><br>Tardy/<br>Undertime</td>
				   	<td>&nbsp;</td>
					</tr>
					<tr>
				   	<td class="dtrPrint1">Date</td>
				   	<td class="dtrPrint1">Weekday</td>
				   	<td class="dtrPrint1" align="center">In</td>
				   	<td class="dtrPrint1" align="center">Out</td>
				   	<td class="dtrPrint1" align="center">In</td>
				   	<td class="dtrPrint1" align="center">Out</td>
				   	<td class="dtrPrint1" align="center">Remarks</td>
				   	<td class="dtrPrint1" align="center">Total</td>
				   	<td width="3">&nbsp;</td>
				   	<td>&nbsp;</td>
				   	<td class="dtrPrint1">Deficit</td>
					<?php
        	if (existOT($month1, $day1, $day2, $year2, $empCode)){
						// echo "<td width=\"3\">&nbsp;</td>";
						// echo "<td class=\"dtrPrint1\">Overtime</td>";
      		}
	   			?>
					</tr>
					<?php
  				//putenv("TZ=Asia/Manila");
				 	//para maayos yung sa pag-process ng dtr, ayusin yung pagkuha ng option id.
					$a = 0;
					$total_wk = 0;
					$leave_array = array();
					$array_sus = array();
					$total_ua = 0;
					$total_ot = 0;
					$total_tar = 0;
					$total_def = 0;
					$total_absent = 0;
					$absent = 0;
					$ua = 0; //maine08072017 unauthorized absence
					$tally = 0;
					$month_req = 0;
					$holhours = 0;
					$in_vl = 0;
					$in_sl = 0;
					$xxx = 0;
					$excess = 0;
					$month_hours = 0;
					$month_tar = 0;
					$rem = "";
					$lea = 0;
					$weekly_leave_minutes = 0;
					$month_def = 0;
					$month_tar_def = 0;
					$month_ot = 0;
					$force_leave_days = 0;
					$kaltas_tardef=0;
					$tartar = 0;
					$swHoursPerWeek = 0;
					$swHoursPerMonth = 0;
					$wfhHoursPerWeek = 0;
					$wfhHoursPerMonth = 0;
					$isOnSW = 0;

					//START, LOOP PER DAY
					for ($i=$day1; $i<=$day2; $i++) {
						//check for dtr option per day
						$currentDate = date("Y-m-d", mktime(0,0,0,$month1,$i,$year2));
						$_s = mysql_query("select a.fldDTROptDesc, a.isWFHAllowed, a.isSWAllowed, a.fldMinHrsWk from tblDTROptions  a where EXISTS (
							SELECT * FROM tblDuration b WHERE a.fldDTROptID = b.fldDTROptionID AND '".$currentDate."' BETWEEN b.fldFromDate AND b.fldToDate
						)");
						$_sr = mysql_fetch_array($_s);
						$_optDesc = $_sr[0];
						$isWFHAllowed = $_sr[1];
						$isSWAllowed = $_sr[2];
						if ($isForDebugging && checkEmployee($empCode)) echo $optionInfoPerDay = "DTR Option Info: On ".$currentDate." is ".$_optDesc." WFHAllowed:".$isWFHAllowed." ".$isSWAllowed."<br/>";

						$c = getDTR($empCode, date("Y-m-d", mktime(0,0,0,$month1,$i,$year2)));
						$q = mysql_query("select * from tblEmpDTR where fldEmpDTRID='$c'");
						$r = mysql_fetch_array($q);
						$month=date("m");
						$year=date("Y");
						$dates = date("Y-m-d", mktime(0,0,0,$month1,$i,$year2));
						$lv->getEmpLeaves($empCode, $dates);
						$hol->getHolidays($dates);
			      		$opt_id = getOptID($dates);
		
	  					if ($isForDebugging && checkEmployee($empCode)) echo "isabsent: ".isAbsent($dates, getOptID($dates), $empCode)." ".$dates." || isoncto: ".isOnCTO($dates, $empCode)." ".$dates."<br/>";
						if (isAbsent($dates, getOptID($dates), $empCode) != 0 && !isDayOff($opt_id, $dates) && !isExempted($empCode) && getFirstDay($empCode) <= $dates){ # && isOnTO($dates, $empCode) == 0){	   		
							//start: maine08082017
							//if unauthorized absent/unfiled leave
							if (WorkSuspended($dates) == "no" && !isHoliday($dates) && !isOnLeave2($dates, $empCode) && !isDayOff($opt_id, $dates) && isOnTO($dates, $empCode) == 0 && isOnCTO($dates, $empCode) == 0 && !getOptID($dates) == 5){
								$ua += isAbsent($dates, getOptID($dates), $empCode);	
								
								if ($isForDebugging && checkEmployee($empCode)) echo $dates.") UA Leave: ".$ua."<br/>";				
							}
								//if filed leave
							else if (isOnLeave2($dates, $empCode)) {				
								$absent += isAbsent($dates, getOptID($dates), $empCode); //&& !getOptID($dates)==5;			
								
								if ($isForDebugging && checkEmployee($empCode)) echo "A) $dates IN_SL: ".$in_sl." IN_VL: ".$in_vl." FORCE LEAVE DAYS: ".$force_leave_days." ABSENT: ".isAbsent($dates, getOptID($dates), $empCode)." CTO: ".isOnCTO($dates, $empCode)."<br/>";				
								if ($isForDebugging && checkEmployee($empCode)) echo "A) $dates IN_SL: ".$in_sl." IN_VL: ".$in_vl."<br/>";				
								if ($isForDebugging && checkEmployee($empCode)) echo $dates.") ABSENT: ".$absent."<br/>";
								if ($isForDebugging && checkEmployee($empCode)) echo $dates.") ABSENT: ".isAbsent($dates, getOptID($dates), $empCode)."<br/>";
								if ($isForDebugging && checkEmployee($empCode)) echo "<br/><br/>";
							}
							//end: maine08082017
						}
	   
						//=======INCREMENTS LATES/UNDERTIME AFTER AUG12. MEMO=======================//
						/*
						$amIn = "00:00:00";
						$amOut = "00:00:00";
						$pmIn = "00:00:00";
						$pmOut = "00:00:00";	  
						$row = array(); 
						if (WorkSuspended($dates) == "no" && !isHoliday($dates) && !isOnLeave($dates, $empCode) && !isDayOff($opt_id, $dates)){// && isOnCTO($dates, $empCode) == 0){
							if ($_SESSION['userID'] == 197 && $isForDebugging) echo "A"." ".$dates;
							//if not on leave, holiday, or work suspended
							$query = mysql_query("select * from tblEmpDTR where fldEmpCode LIKE '$empCode' AND fldEmpDTRdate LIKE '$dates'");
							if (mysql_num_rows($query) > 0){
								if ($_SESSION['userID'] == 197 && $isForDebugging) echo "$dates [1.0]<br/>";

								$row = mysql_fetch_array($query);
						 		$amIn = $row['fldEmpDTRamIn'];
						  	$amOut = $row['fldEmpDTRamOut'];
						  	$pmIn = $row['fldEmpDTRpmIn'];
						  	$pmOut = $row['fldEmpDTRpmOut'];
						  
								if ($amIn != "00:00:00" && $pmOut != "00:00:00"){
									$ret =  0;
								}		  
							}		
						}*/
		
	   				//======================================================//
						echo "<tr>";
							echo "<td class=\"dtrPrint\">".$i."</td>";
	  					echo "<td class=\"dtrPrint\">".date("D", mktime(0,0,0,$month1,$i,$year2))."</td>";

		  			//START !isDayOff, weekdays
					if (date("j", strtotime($dates)) == $i && !isDayOff($opt_id, $dates)) {
						if (isExempted($empCode) && !isOnTO($dates, $empCode) && $lv->leave == "" && $hol->holiday == "" && !isOnCTO($dates, $empCode)){
							if ($isForDebugging && checkEmployee($empCode)) echo "$dates [1.1]<br/>";

							$r['fldEmpDTRamIn'] = "8:00";
							$r['fldEmpDTRamOut'] = "12:00";
							$r['fldEmpDTRpmIn'] = "13:00";
							$r['fldEmpDTRpmOut'] = "17:00";

							$DTR->getTotal(checkTime($r['fldEmpDTRamIn']),checkTime($r['fldEmpDTRamOut']),checkTime($r['fldEmpDTRpmIn']),checkTime($r['fldEmpDTRpmOut']), $month1, $i, $year2, getOptID($dates), $empCode);
		  				}
		  				if (isExempted($empCode) && !isOnTO($dates, $empCode) && $lv->leave == "" && holidayHours2($dates) == 4 && !isOnCTO($dates, $empCode)){
		  					switch (holidayTime($dates)){
								case 1 : 
									if ($isForDebugging && checkEmployee($empCode)) echo "$dates [1.2]<br/>";
									$r['fldEmpDTRamIn'] = "";
									$r['fldEmpDTRamOut'] = "";
									$r['fldEmpDTRpmIn'] = "13:00";
									$r['fldEmpDTRpmOut'] = "17:00";
									break;
								case 2 : 
									if ($isForDebugging && checkEmployee($empCode)) echo "$dates [1.3]<br/>";
									$r['fldEmpDTRamIn'] = "8:00";
									$r['fldEmpDTRamOut'] = "12:00";
									$r['fldEmpDTRpmIn'] = "";
									$r['fldEmpDTRpmOut'] = "";
									break;
								case 3 : 
									if ($isForDebugging && checkEmployee($empCode)) echo "$dates [1.4]<br/>";
									$r['fldEmpDTRamIn'] = "";
									$r['fldEmpDTRamOut'] = "";
									$r['fldEmpDTRpmIn'] = "";
									$r['fldEmpDTRpmOut'] = "";
									break;
							}

			           		$DTR->getTotal(checkTime($r['fldEmpDTRamIn']),checkTime($r['fldEmpDTRamOut']),checkTime($r['fldEmpDTRpmIn']),checkTime($r['fldEmpDTRpmOut']), $month1, $i, $year2, getOptID($dates), $empCode);
					  	}
					  	else if (isedited($c) && $_POST['dtrVer'] != "edited" && getFirstDay($empCode) <= $dates){
                            if ($isForDebugging && checkEmployee($empCode)) echo "$dates [1.5]<br/>";
					  		$qry = mysql_query("select * from tblEditDTR where fldEmpDTRId='$c'");
							  $rw = mysql_fetch_array($qry);
							  
			          		$DTR->getTotal(checkTime($rw['fldAMin']),checkTime($rw['fldAMout']),checkTime($rw['fldPMin']),checkTime($rw['fldPMout']), $month1, $i, $year2, getOptID($dates), $empCode);
					  	}
		  				else if (isOnTO($dates, $empCode) != 0 && getFirstDay($empCode) <= $dates){
		  					$qqq = mysql_query("select * from tblTravelOrder where fldEmpCode='$empCode' AND fldFromDate<='$dates' AND fldToDate>='$dates' AND fldApproved='1'");
		   					$rrr = mysql_fetch_array($qqq);
		   
					  		#echo "Here yih! ampmwd:".$rrr["fldAMPMWD"]." pmIn: ".$row['fldEmpDTRpmIn']." pmOut:".$row['fldEmpDTRpmOut']."<br/>";
							switch ($rrr['fldAMPMWD']){
								case 0 : 
									if ($isForDebugging && checkEmployee($empCode)) echo "$dates [1.6]<br/>";
									$r['fldEmpDTRamIn'] = "8:00";
									$r['fldEmpDTRamOut'] = "12:00";
									$r['fldEmpDTRpmIn'] = "13:00";
									$r['fldEmpDTRpmOut'] = "17:00";
									break;
								case 1 : 
									if ($isForDebugging && checkEmployee($empCode)) echo "$dates [1.7]<br/>";
									$r['fldEmpDTRamIn'] = "8:00";
									$r['fldEmpDTRamOut'] = "12:00";
									$r['fldEmpDTRpmIn'] = "13:00";
									$r['fldEmpDTRpmOut'] = "17:00";
									break;
								case 2 : 
									if ($isForDebugging && checkEmployee($empCode)) echo "$dates [1.8]<br/>";
									$r['fldEmpDTRamIn'] = "8:00";
									$r['fldEmpDTRamOut'] = "12:00";
									$r['fldEmpDTRpmIn'] = $r['fldEmpDTRpmIn'];
									$r['fldEmpDTRpmOut'] = $r['fldEmpDTRpmOut'];
									$DTR->getTotal(checkTime($r['fldEmpDTRamIn']),checkTime($r['fldEmpDTRamOut']),checkTime($r['fldEmpDTRpmIn']),checkTime($r['fldEmpDTRpmOut']), $month1, $i, $year2, getOptID($dates), $empCode);
									break;
								case 3 : 
									if ($isForDebugging && checkEmployee($empCode)) echo "$dates [1.9]<br/>";
									$r['fldEmpDTRamIn'] = $r['fldEmpDTRamIn'];
									$r['fldEmpDTRamOut'] = $r['fldEmpDTRamOut'];
									$r['fldEmpDTRpmIn'] = "13:00";
									$r['fldEmpDTRpmOut'] = "17:00";
									$DTR->getTotal(checkTime($r['fldEmpDTRamIn']),checkTime($r['fldEmpDTRamOut']),checkTime($r['fldEmpDTRpmIn']),checkTime($r['fldEmpDTRpmOut']), $month1, $i, $year2, getOptID($dates), $empCode);
									break;
					  		}
						} // for travel order. di pa gumagana
					  	else if (getFirstDay($empCode) > $dates){

					  	}
					  	else {
							//SETS amIn, amOut, pmIn, pmOut to "" for Holidays 	
							if(isHoliday($dates) && holidayTime($dates) == 3){
								if ($isForDebugging && checkEmployee($empCode)) echo "$dates [1.10]<br/>";
								$r['fldEmpDTRamIn'] = "";
								$r['fldEmpDTRamOut'] = "";
								$r['fldEmpDTRpmIn'] = "";
								$r['fldEmpDTRpmOut'] = "";
							}
			          
			          		$DTR->getTotal(checkTime($r['fldEmpDTRamIn']),checkTime($r['fldEmpDTRamOut']),checkTime($r['fldEmpDTRpmIn']),checkTime($r['fldEmpDTRpmOut']), $month1, $i, $year2, getOptID($dates), $empCode);
						}

					  	//check for leaves
					  	$total_wk += $DTR->total_week + isOnCTO($dates,$empCode)*8*60 + isOnTO($dates, $empCode)*60; 
					  	$total_tar += $DTR->week_tar;
					  	$total_ua += $DTR->week_ua;
					  	$total_ot += getOT($dates, $empCode); //getOT($dates);
					  
					  	#ITERATES UNDERTIME/TARDY COUNTER
					  	if($DTR->tot_tardy) {					  		
						  	$tally_tardy++;
								$DTR->tot_tardy = 0;

								if ($isForDebugging && checkEmployee($empCode)) 
					  			echo "$dates Tardy: 1 total:$tally_tardy <br/>";
						}
					  	if($DTR->tot_undertime) {					  		
						  	$tally_undertime++;
								$DTR->tot_undertime = 0;

								if ($isForDebugging && checkEmployee($empCode)) 
					  			echo "$dates Undertime: 1 total:$tally_undertime <br/>";
						}
					  	#$tally += $DTR->tally_tar;		  
					  	#if($DTR->tally_tar && ($empCode == "LAT002")) echo "PROCESSED :: Date: $dates Tally Tar: ".$DTR->tally_tar." Tally: $tally<br/>";
					  	#END
					  
					  	$excess += $DTR->excess;
					  	//echo $excess;
					  	$DTR->excess = 0;

					  	$month_hours += $DTR->total_week + isOnCTO($dates,$empCode)*8*60 + isOnTO($dates, $empCode)*60;

					  	$month_tar += $DTR->week_tar;
					  	$DTR->week_tar = 0;
					  
					  	$DTR->tally_tar = "";

					  	if (getOT($dates, $empCode) == 0 || getOT($dates, $empCode) == ""){
					   		$dailyt_ot = "";
					  	}
					  	else{
					   		$daily_ot = floor(getOT($dates, $empCode)/60). " h " . getOT($dates, $empCode)%60 . " m";
					   		$month_ot += getOT($dates, $empCode);
					  	}

					   	$total_wk -= otWeekend($dates, $empCode);

					  	//processes/allows the employee to see their original DTR
					  	if (isedited($c) && $_POST['dtrVer'] == "original" && getFirstDay($empCode) <= $dates) {
                            if ($isForDebugging && checkEmployee($empCode)) echo "$dates DTR [2.0] <br/>";
					   		$qr = mysql_query("select * from tblEditDTR where fldEmpDTRID='$c' order by fldDateChanged desc");
					   		$s = mysql_fetch_array($qr);
								
							if ($isSWAllowed) {
								echo "<td class=\"dtrPrint\">&nbsp;</td>";
								echo "<td class=\"dtrPrint\">&nbsp;</td>";
								echo "<td class=\"dtrPrint\">&nbsp;</td>";
								echo "<td class=\"dtrPrint\">&nbsp;</td>";	
								$isOnSW = 1;
							} else {
								echo "<td class=\"dtrPrint\">".checkTime2($s['fldAMin'])."</td>";
								echo "<td class=\"dtrPrint\">".checkTime2($s['fldAMout'])."</td>";
								echo "<td class=\"dtrPrint\">".checkTime2($s['fldPMin'])."</td>";
								echo "<td class=\"dtrPrint\">".checkTime2($s['fldPMout'])."</td>";
							}
						} else if (isedited($c) && $_POST['dtrVer'] == "edited" && getFirstDay($empCode) <= $dates && (checkTime2($r['fldEmpDTRamIn']) !== "" && checkTime2($r['fldEmpDTRamOut']) !== "" && checkTime2($r['fldEmpDTRpmIn']) !== "" && checkTime2($r['fldEmpDTRpmOut']) !== "")) {
							if ($isForDebugging && checkEmployee($empCode)) echo "$dates DTR [2.1] <br/>";

							$amInToDisplay = "";
							$amOutToDisplay = "";
							$pmInToDisplay = "";
							$pmOutToDisplay = "";

							$amInToDisplay = (isEdited2($c, 1, $r['fldEmpDTRamIn']) && !isOnTO($dates, $empCode)) ? "<td class=\"dtrPrint\"><span style=\"font-style: italic; font-weight: bold;\">".checkTime2($r['fldEmpDTRamIn'])."</span></td>" : "<td class=\"dtrPrint\">".checkTime2($r['fldEmpDTRamIn'])."</td>" ;
							
							$amOutToDisplay = (isEdited2($c, 2, $r['fldEmpDTRamOut']) && !isOnTO($dates, $empCode)) ? "<td class=\"dtrPrint\"><span style=\"font-style: italic; font-weight: bold;\">".checkTime2($r['fldEmpDTRamOut'])."</span></td>" : "<td class=\"dtrPrint\">".checkTime2($r['fldEmpDTRamOut'])."</td>" ;

							$pmInToDisplay = (isEdited2($c, 3, $r['fldEmpDTRpmIn']) && !isOnTO($dates, $empCode)) ? "<td class=\"dtrPrint\"><span style=\"font-style: italic; font-weight: bold;\">".checkTime2($r['fldEmpDTRpmIn'])."</span></td>" : "<td class=\"dtrPrint\">".checkTime2($r['fldEmpDTRpmIn'])."</td>" ;

							$pmOutToDisplay = (isEdited2($c, 4, $r['fldEmpDTRpmOut']) && !isOnTO($dates, $empCode)) ? "<td class=\"dtrPrint\"><span style=\"font-style: italic; font-weight: bold;\">".checkTime2($r['fldEmpDTRpmOut'])."<span></td>" : "<td class=\"dtrPrint\">".checkTime2($r['fldEmpDTRpmOut'])."</td>" ;
			
							if ($isSWAllowed) {
								echo "<td class=\"dtrPrint\">&nbsp;</td>";
								echo "<td class=\"dtrPrint\">&nbsp;</td>";
								echo "<td class=\"dtrPrint\">&nbsp;</td>";
								echo "<td class=\"dtrPrint\">&nbsp;</td>";	
								$isOnSW = 1;
							} else {
								echo $amInToDisplay;
								echo $amOutToDisplay;
								echo $pmInToDisplay;
								echo $pmOutToDisplay;
							}
						
						} else if (getFirstDay($empCode) > $dates) {
                            if ($isForDebugging && checkEmployee($empCode)) echo "$dates DTR [3] <br/>";
							if ($isSWAllowed) $isOnSW = 1;

							echo "<td class=\"dtrPrint\">&nbsp;</td>";
							echo "<td class=\"dtrPrint\">&nbsp;</td>";
							echo "<td class=\"dtrPrint\">&nbsp;</td>";
							echo "<td class=\"dtrPrint\">&nbsp;</td>";
							   
						} else {
							/*
							echo "<td class=\"dtrPrint\">".checkTime2($r['fldEmpDTRamIn'])."</td>";
					   		echo "<td class=\"dtrPrint\">".checkTime2($r['fldEmpDTRamOut'])."</td>";
					   		echo "<td class=\"dtrPrint\">".checkTime2($r['fldEmpDTRpmIn'])."</td>";
							echo "<td class=\"dtrPrint\">".checkTime2($r['fldEmpDTRpmOut'])."</td>";
							*/

							if ($isSWAllowed && (checkTime2($r['fldEmpDTRamIn']) != "" || checkTime2($r['fldEmpDTRamOut']) != "" || checkTime2($r['fldEmpDTRpmIn']) != "" || checkTime2($r['fldEmpDTRpmOut']) != "")) {
								if ($isForDebugging && checkEmployee($empCode)) echo "$dates DTR [4.1] ".$r['fldEmpDTRpmIn']." ".$r['fldEmpDTRpmOut']." <br/> ";
								echo "<td class=\"dtrPrint\">&nbsp;</td>";
								echo "<td class=\"dtrPrint\">&nbsp;</td>";
								echo "<td class=\"dtrPrint\">&nbsp;</td>";
								echo "<td class=\"dtrPrint\">&nbsp;</td>";	
								$isOnSW = 1;
							} else {
								if ($isForDebugging && checkEmployee($empCode)) echo "$dates DTR [4.2] ".$r['fldEmpDTRpmIn']." ".$r['fldEmpDTRpmOut']." <br/> ";
								echo "<td class=\"dtrPrint\">".checkTime2($r['fldEmpDTRamIn'])."</td>";
								echo "<td class=\"dtrPrint\">".checkTime2($r['fldEmpDTRamOut'])."</td>";
								echo "<td class=\"dtrPrint\">".checkTime2($r['fldEmpDTRpmIn'])."</td>";
								echo "<td class=\"dtrPrint\">".checkTime2($r['fldEmpDTRpmOut'])."</td>";
							}
					  	}

						  //dito pedeng isingit yung suspension of work. pero kelangan din ito ilagay sa absent counter ;)
						  //get remarks
					  
					  	//================================= LEAVES ====================================
					  	//DETERMINES ABSENCES 
					  
					  	//NO REMARKS
                        if (isExempted($empCode) && !isOnTO($dates, $empCode) && $lv->leave == "" && $hol->holiday == "" && !isOnCTO($dates, $empCode) && getFirstDay($empCode) <= $dates)  
                            $remarks = "&nbsp;";
					  
					  	//ON TRIP
					  	if (isOnTO($dates, $empCode) != 0 && getFirstDay($empCode) <= $dates){
					    	if ($lv->leave != "" || $lv->fl !=0){
									$dag = "<br>".$lv->leave;
						   		$lea += $lv->hours*60;
						   		if ($lv->type == 6 || $lv->fl!=0){
										$force_leave_days += $lv->fl/8;
						   		}
						   		/*if ($lv->type == 6 || $lv->fl2!=0){
										$force_leave_days += $lv->fl2/8;
						   		}*/ #feb 13 2013
					   			if (($lv->type == 1 || $lv->type == 6) || $lv->vl!=0){
					   				$in_vl += $lv->vl/8;
					   			}
					   			if ($lv->type == 2 || $lv->sl!=0){
										$in_sl += $lv->sl/8;
						   		}
								}
								else{
									$dag = "";
								}

			 		    	$rem .= "On Trip".$dag."<br>";
							$trip = isOnTO($dates, $empCode);
						
							if ($isForDebugging && checkEmployee($empCode)) echo ".1) $dates IN_SL: ".$in_sl." IN_VL: ".$in_vl." FORCE LEAVE DAYS: ".$force_leave_days." remarks: $rem <br/>";
					  	}
					  
                        // DATE < FIRST DAY
                        if (WorkSuspended($dates) != "no" && getFirstDay($empCode) <= $dates){
                            $array = WorkSuspended($dates);
                            $time = $array[0];
                            $remarks = $array[1];
                            //check if pumasok siya
                            $rem .= $remarks."<br>";
                            $array_sus[] = $array;
                            if ($isForDebugging && checkEmployee($empCode)) echo ".2) $dates IN_SL: ".$in_sl." IN_VL: ".$in_vl." FORCE LEAVE DAYS: ".$force_leave_days." remarks: $remarks <br/>";
                        }

                        if (($lv->leave != "" && isOnTO($dates, $empCode) == 0 && $hol->holiday == "") || $lv->fl2!=0 && getFirstDay($empCode) <= $dates) {
                            $rem .= $lv->leave;
                            $lea += $lv->hours*60;
                            @$leave_array[$lv->id] += $lv->larray;
                            #echo "[3]lv->type::".$lv->type." lv->fl::".$lv->fl." lv->fl2::".$lv->fl2." isAbsent::".isAbsent($dates, getOptID($dates), $empCode)."<br/>";
                            if ($lv->type == 6 && $lv->fl!=0) {
                                if ($isForDebugging && checkEmployee($empCode)) echo "A) lv->type:".$lv->type." lv->fl:: ".$lv->fl." [".isAbsent($dates, getOptID($dates), $empCode)."]<br/>";
                                    #$force_leave_days += $lv->fl/8;
                                    //LEAVE IS APPROVED BUT STILL CHECKS IF ABSENT OR NOT
                                    if(isAbsent($dates, getOptID($dates), $empCode) == 0.5) $force_leave_days += $lv->fl/8;
                                    else if(isAbsent($dates, getOptID($dates), $empCode) == 1) $force_leave_days += $lv->fl/8;				
                                    else if(isAbsent($dates, getOptID($dates), $empCode) == 0) $force_leave_days += 0;
                            }
                            else if ($lv->type == 6 && $lv->fl2!=0){
                                if ($isForDebugging && checkEmployee($empCode)) echo "B) lv->type:".$lv->type." lv->fl2:: ".$lv->fl2." [".isAbsent($dates, getOptID($dates), $empCode)."]<br/>";
                                    //$force_leave_days += $lv->fl2/8; #feb 14, 2013
                                    //LEAVE IS APPROVED BUT STILL CHECKS IF ABSENT OR NOT
                                    if(isAbsent($dates, getOptID($dates), $empCode) == 0.5) 
                                        $force_leave_days += $lv->fl2/8;
                                    else if(isAbsent($dates, getOptID($dates), $empCode) == 1) 
                                        $force_leave_days += $lv->fl2/8;				
                                    else if(isAbsent($dates, getOptID($dates), $empCode) == 0) 
                                        $force_leave_days += 0;
                            }

                            if (($lv->type == 1 || $lv->type == 6) || $lv->vl!=0){		   	
                                $in_vl += $lv->vl/8;			
                            }

                            if ($lv->type == 2 || $lv->sl!=0){
                                $in_sl += $lv->sl/8; #BAU006, SL DEC.2010
                                    #if(LeaveTime($dates, $empCode) == "(AM)") $in_sl += 0.5;
                                    #if(LeaveTime($dates, $empCode) == "(PM)") $in_sl += 0.5;
                                    #if(LeaveTime($dates, $empCode) == "") $in_sl += 1;		
                                    #if(isAbsent($dates, getOptID($dates), $empCode) == 0.5) $in_sl += 0.5;
                                    # else if(isAbsent($dates, getOptID($dates), $empCode) == 1) $in_sl += 1;
                            }
                            
                            //if(LeaveTime($dates, $empCode) == "(AM)") $rem .= "(AM)";
                            //else if(LeaveTime($dates, $empCode) == "(PM)") $rem .= "(PM)";
                            //else $rem .= "";

                            if ($isForDebugging && checkEmployee($empCode)) echo ".3) $dates IN_SL: ".$in_sl." IN_VL: ".$in_vl." isAbsent: ".isAbsent($dates, getOptID($dates), $empCode)." FORCE LEAVE DAYS: ".$force_leave_days." remarks: $rem <br/><br/>";
                        } else if((isHoliday($dates) == 1) && ((holidayTime($dates) == 1)||(holidayTime($dates) == 2))){
                    
                            if((isOnLeave2($dates, $empCode) == 1)){
                                $lea += $lv->hours*60;
                                $rem .= $lv->leave;
                                $leave_array[$lv->id] += $lv->larray;
                            
                            if ($lv->type == 6 || $lv->fl!=0){	
                                #$force_leave_days += $lv->fl/8;
                                //LEAVE IS APPROVED BUT STILL CHECKS IF ABSENT OR NOT
                                    if(isAbsent($dates, getOptID($dates), $empCode) == 0.5) $force_leave_days += $lv->fl/8;
                                    else if(isAbsent($dates, getOptID($dates), $empCode) == 1) $force_leave_days += $lv->fl/8;				
                                    else if(isAbsent($dates, getOptID($dates), $empCode) == 0) $force_leave_days += 0;
                            }

                            if ($lv->type == 6 || $lv->fl2!=0){
                                    $force_leave_days += $lv->fl2/8;
                            }

                            if ($lv->type == 1 || $lv->vl!=0){
                                    //$in_vl += 1;	 #Feb.25, 2013
                                    if(isHoliday($dates)){
                                        if(holidayTime($dates) == 1 || holidayTime($dates) == 2) $in_vl += 0.5;
                                    } else {
                                        $in_vl += 1;
                                    }	
                            }

                            if ($lv->type == 2 || $lv->sl!=0){
                                    if((LeaveTime($dates, $empCode) == "(AM)") && (holidayTime($dates) == 2)){
                                        #$in_sl += $lv->sl/8;
                                        $in_sl += 0.5;
                                    } else if((LeaveTime($dates, $empCode) == "(PM)") && (holidayTime($dates) == 1)){
                                        #$in_sl += $lv->sl/8;
                                        $in_sl += 0.5;
                                    } else if((LeaveTime($dates, $empCode) == "") && (holidayTime($dates) == 2 || holidayTime($dates) == 1)){
                                        #$in_sl += $lv->sl/8;
                                        $in_sl += 0.5;
                                    } else if((LeaveTime($dates, $empCode) == "") && (holidayTime($dates) == 3)){
                                        #$in_sl += $lv->sl/8;
                                        $in_sl += 0;
                                    }												
                                }
                                if ($isForDebugging && checkEmployee($empCode)) echo "4) $dates IN_SL: ".$in_sl." IN_VL: ".$in_vl." FORCE LEAVE DAYS: ".$force_leave_days." remarks: $rem <br/>";
                            }
                                
                            if(isOnLeave2($dates, $empCode) == 0.5) {
                                $lea += $lv->hours*60;
                                $rem .= $lv->leave;
                                $leave_array[$lv->id] += $lv->larray;
                            #if ($lv->type == 6 || $lv->fl!=0){
                                    #$force_leave_days += $lv->fl/8;
                            #}
                            #if ($lv->type == 6 || $lv->fl2!=0){
                                    #$force_leave_days2 += $lv->fl2/8;
                            #}
                            #if (($lv->type == 1 || $lv->type == 6) || $lv->vl!=0){
                                    #$in_vl += 1;			
                            #}
                            if ($lv->type == 2 || $lv->sl!=0){
                                    if((LeaveTime($dates, $empCode) == "(AM)") && (holidayTime($dates) == 2)) {
                                        #$in_sl += $lv->sl/8;
                                        $in_sl += 0.5;
                                    } else if((LeaveTime($dates, $empCode) == "(PM)") && (holidayTime($dates) == 1)){
                                        #$in_sl += $lv->sl/8;
                                        $in_sl += 0.5;
                                    }												
                            }	
                        
                            if ($lv->type == 1 || $lv->vl!=0) {
                                    if((LeaveTime($dates, $empCode) == "(AM)") && (holidayTime($dates) == 2)){
                                        #$in_sl += $lv->sl/8;
                                        $in_vl += 0.5;
                                    } else if((LeaveTime($dates, $empCode) == "(PM)") && (holidayTime($dates) == 1)){
                                        #$in_sl += $lv->sl/8;
                                        $in_vl += 0.5;
                                    }												
                            }
                            if ($isForDebugging && checkEmployee($empCode)) echo "5) $dates IN_SL: ".$in_sl." IN_VL: ".$in_vl." FORCE LEAVE DAYS: ".$force_leave_days." remarks: $rem <br/>";  
                            }		  		  		  
                        }   //END OF HOLIDAY AND LEAVES PROCESSING
                            
                        //====================================================================================
                             
                        $testFlag = "";
                        if ($hol->holiday != "" && getFirstDay($empCode) <= $dates && isOnTO($dates, $empCode) == 0){
                            if ($isForDebugging && checkEmployee($empCode)) $testFlag = "Remark 1 <br/>";
                            $rem = $hol->holiday."<br>";
                            $holhours += holidayHours2($dates);
                            //echo $holhours;
                        }	

                        if (isedited($c) && $_POST['dtrVer'] == "edited" && isset($_POST['initial']) && getFirstDay($empCode) <= $dates && (checkTime2($r['fldEmpDTRamIn']) !== "" && checkTime2($r['fldEmpDTRamOut']) !== "" && checkTime2($r['fldEmpDTRpmIn']) !== "" && checkTime2($r['fldEmpDTRpmOut']) !== "") && isOnTO($dates, $empCode) == 0) {
                            if ($isForDebugging && checkEmployee($empCode)) $testFlag = "Remark 2 <br/>";
                            //$rem .= getRemarks($c)."<br>";
                            $rem = ($isOnSW) ? "Skeletal WF" : getRemarks($c)."<br>" ;
                        } 
                    
                        if (isAbsent($dates, getOptID($dates), $empCode) != 0 && holidayHours2($dates) != 4 && $lv->leave == "" && getFirstDay($empCode) <= $dates){
                            //check if may balance ng vl
                            $qs = mysql_query("select * from tblLeaveBalance where fldEmpCode='$empCode' AND fldLeaveTypeID='1' order by fldDate");
                            $qr = mysql_fetch_array($qs);
                            $aaaa = $qr['fldBalance'];
                            if (isAbsent($dates, getOptID($dates), $empCode) == 0.5 && $aaaa >= 0.5){
                                // $rem .= "&nbsp;";
                                //$rem .= "wow1";
                                if ($isForDebugging && checkEmployee($empCode)) $testFlag = "Remark 3 <br/>";
                            }
                            else if (isAbsent($dates, getOptID($dates), $empCode) >= 0.5 && $aaaa < 0.5 && !isExempted($empCode) && !getOptID($dates)==5){
                                //$rem .= "wow2";
                                // $rem = "Unauthorized Absence";
                                if ($isForDebugging && checkEmployee($empCode)) $testFlag = "Remark 4 <br/>";
                            }
                            else if (isExempted($empCode)){
                                // $rem .= "&nbsp;";
                                //	$rem .= "wow3";
                                if ($isForDebugging && checkEmployee($empCode)) $testFlag = "Remark 5 <br/>";
                            }
                            else if (getFirstDay($empCode) > $dates){
                                // $rem .= "&nbsp;";
                                //	$rem .= "wow4";
                                if ($isForDebugging && checkEmployee($empCode)) $testFlag = "Remark 6 <br/>";
                            }
                            else if (isAbsent($dates, getOptID($dates), $empCode) >= 0.5 && $aaaa < 0.5 && !isExempted($empCode) && getOptID($dates)==5){
                                //$rem = getDTRoptDesc($opt_id);
                                if ($isForDebugging && checkEmployee($empCode)) $testFlag = "Remark 7 <br/>";
                            } 
                            else if ($isWFHAllowed) { 
                                if ($isOnSW) {
                                    $rem = "Skeletal WF";
                                    if ($isForDebugging && checkEmployee($empCode)) $testFlag = "Remark 8 <br/>";
                                } else {
                                    $rem = "WFH";
                                    if ($isForDebugging && checkEmployee($empCode)) $testFlag = "Remark 9 <br/>";
                                }
                            } 
                            else{
                                $rem = "Unauthorized Absence";
                                if ($isForDebugging && checkEmployee($empCode)) $testFlag = "Remark 10 <br/>";
                            }
                        }

                        //unremarkable DTR entry should be set to Skeletal WF
                        if ($isSWAllowed && $isOnSW) {
                            //$rem .= "Skeletal WF";
                            //if ($_SESSION['userID'] == 197 && $isForDebugging) $testFlag = "Remark: 11 <br/>";
                        }
		  
                        //REMARKS
                        echo "<td class=\"dtrPrint\" align=\"center\">";
                        if ($isForDebugging && checkEmployee($empCode)) echo $testFlag;
                        echo $rem."</td>";
                        
                        $rem = "";
                        $testFlag = "";
        
                        //TOTAL, HOURS PER DAY
                        if (isOnCTO($dates, $empCode) != 0 && getFirstDay($empCode) <= $dates){
                            $pot = $DTR->tot + isOnCTO($dates, $empCode)*8*60 + isonTO($dates, $empCode)*60;
                            echo "<td class=\"dtrPrint\">".floor($pot/60). " h " . $pot%60 . " m"."</td>";
                        }
                        else if (isOnTO($dates, $empCode) != 0 && getFirstDay($empCode) <= $dates){
                            $xot = $DTR->total_week + isOnTO($dates, $empCode)*60 + isOnCTO($dates,$empCode)*8*60;
                            echo "<td class=\"dtrPrint\">".floor($xot/60). " h " . $xot%60 . " m"."</td>";
                        }
                        else{
                            echo "<td class=\"dtrPrint\">".$DTR->total."</td>";
                        }
                
                        echo "<td width=\"3\">&nbsp;</td>";
                
                        //TARDY
                        echo "<td class=\"dtrPrint\">".(($DTR->week_tar2 <= 0) ? "" : $DTR->week_tar2)."</td>";   
                
                        //DEFICIT
                        echo "<td width=\"3\">&nbsp;</td>";
                        echo "<td>&nbsp;</td>";
                        echo "<td width=\"3\">&nbsp;</td>";
                        #echo "<td width=\"3\">";
                            #echo "&nbsp;";
                            #echo "<pre>"; 
                            #print_r($lv); 
                            #echo "</pre>";
                        #echo "</td>";

                
                        if (existOT($month1, $day1, $day2, $year2, $empCode)){
                            // echo "<td class=\"dtrPrint\">".$daily_ot."</td>";
                        }
	
					  	$a++;
					  	$daily_ot = "";
					  	//$zzz += $xxx;
					  	$xxx = 0;
					 	 	//echo $total_wk." d";
					  	$DTR->total_tar_under = "";
					  	$DTR->week_tar2 = "";

                    }	
                    //END !isDayOff, weekdays

                    $DTR->total_week = 0;
                    //echo $total_wk;
                    //pano pag di kumpleto yung week? wala pa din
						   
					   //START, WEEKENDS
						if ((date("w", strtotime($dates)) == 0 || lastDay($dates))) {
			       			$q2 = mysql_query("select fldMinHrsWk from tblDTROptions where fldDTROptID='$opt_id'");
			       			$r2 = mysql_fetch_array($q2);
							$minhrs = $r2[0];
							
							//get weekday count for the current week
							$_query = 'SELECT COUNT(id) FROM commonlibrariesdb.dimension_date WHERE year = '.date("Y", strtotime($dates)).' AND month = '.date("n", strtotime($dates)).' AND week = '.date("W", strtotime($dates)).' AND weekend_flag IS FALSE';
							$_sql = mysql_query($_query);
							$_result = mysql_fetch_array($_sql);
							$dayCount = $_result[0];
							$hourCount = $dayCount * 8;

							//get minimum of two counts
							$minhrs = ($hourCount < $minhrs) ? $hourCount : $minhrs ;
							if ($isForDebugging && checkEmployee($empCode)) echo "A[minhrs] $dates $minhrs<br/>";
							
							//deduct holidays
							if (holidayExistsWeek($month1, $i, $year2) && holidayIsWithinMonth($month1, $year2)){
								//$minhrs -= holidayHours($month1, $i, $year2);
								$minhrs -= holidayHours3($month1, $i, $year2, $isForDebugging);
								if ($isForDebugging && checkEmployee($empCode)) echo "D[minhrs] $dates $minhrs<br/>";
								//$tempMin++;
							}

							//deduct suspensions
							if (count($array_sus)){
								$sushoursforfday = 0;
								while ($ap_as = array_pop($array_sus)){
									//print_r($ap_as);
									$susus = array_pop($ap_as);
									$minhrs -= 8 - $susus;
									if ($isForDebugging && checkEmployee($empCode)) echo "E[minhrs] $dates $minhrs<br/>";
									$sushoursforfday += 8-$susus;
								}
							} 
		  	
					  		$firstday = getFirstDay($empCode);

							if (($firstday > $dates || FirstDayWithinWeek($month1, $i, $year2, $firstday)) && $firstday[5].$firstday[6] == $month1) {		   				 		
					   		//uncommented due to Tamban, Karl. April, 2012
                               if ($isForDebugging && checkEmployee($empCode)) echo "E.5[minhrs] $dates $minhrs<br/>";
								$minhrs = $minhrs - diffFirstDayMonth($month1, $i, $year2, $firstday, isIncompleteWeek($month1, $i, $year2), holidayHours($month1, $i, $year2), $sushoursforfday);
								if ($isForDebugging && checkEmployee($empCode)) echo "$minhrs -= ".diffFirstDayMonth($month1, $i, $year2, $firstday, isIncompleteWeek($month1, $i, $year2), holidayHours($month1, $i, $year2), $sushoursforfday)." || minhrs -= diffFirstDayMonth($month1, $i, $year2, $firstday, isIncompleteWeek($month1, $i, $year2), holidayHours($month1, $i, $year2), $sushoursforfday); <br/>";
								if ($isForDebugging && checkEmployee($empCode)) echo "F[minhrs] $dates $minhrs<br/>";
								$tempT = diffFirstDayMonth($month1, $i, $year2, $firstday, isIncompleteWeek($month1, $i, $year2), holidayHours($month1, $i, $year2), $sushoursforfday);
								//echo "$tempT = ".diffFirstDayMonth($month1, $i, $year2, $firstday, isIncompleteWeek($month1, $i, $year2), holidayHours($month1, $i, $year2), $sushoursforfday)." || tempT = diffFirstDayMonth($month1, $i, $year2, $firstday, isIncompleteWeek($month1, $i, $year2), holidayHours($month1, $i, $year2), $sushoursforfday) <br/>";
								//echo "$dates >= $firstday || dates >= firstday <br/><br/>";
								if($dates >= $firstday) {
									if($tempT < 0){
										$minhrs = abs($minhrs + $tempT);
										if ($isForDebugging && checkEmployee($empCode)) echo "G1[minhrs] $dates $minhrs<br/>";
									} else {
										$minhrs -= $tempT;
										if ($isForDebugging && checkEmployee($empCode)) echo "G2[minhrs] $dates $minhrs<br/>";
									}   			  			
								} else if($dates < $firstday) {
							 		$minhrs = 0;  
							 		if ($isForDebugging && checkEmployee($empCode)) echo "[8] $minhrs; <br/>";
								}
								if ($isForDebugging && checkEmployee($empCode)) echo "G.5[minhrs] $dates $minhrs<br/><br/>";
			
								$tyear_dates = $dates[0]."".$dates[1]."".$dates[2]."".$dates[3];
								$tmonth_dates = $dates[5]."".$dates[6];
								$tmonth_dates *= 1;
								$tday_dates = $dates[8]."".$dates[9];
								$tday_dates *= 1;
						
								$tyear_firstday = $firstday[0]."".$firstday[1]."".$firstday[2]."".$firstday[3];
								$tmonth_firstday = $firstday[5]."".$firstday[6];
								$tmonth_firstday *= 1;
								$tday_firstday = $firstday[8]."".$firstday[9];
								$tday_firstday *= 1;
						
								$tdates = mktime(0,0,0,$tmonth_dates,$tday_dates,$tyear_dates);
								$tfirstday = mktime(0,0,0,$tmonth_firstday,$tday_firstday,$tyear_firstday);					
						
								//0 for Sunday, 6 for Saturday)
								$weekday_dates = date("w", $tdates);
								$latestMonday = "";
								switch($weekday_dates){
									case 0: $latestMonday = $tday_dates - 6; break;
									case 1: $latestMonday = $tday_dates; break;
									case 2: $latestMonday = $tday_dates - 1; break;
									case 3: $latestMonday = $tday_dates - 2; break;
									case 4: $latestMonday = $tday_dates - 3; break;
									case 5: $latestMonday = $tday_dates - 4; break;
									case 6: $latestMonday = $tday_dates - 5; break;				
								}

								//$latestMonday < 0 ? $latestMonday += 7 : $latestMonday;
								//echo "latestMonday: $latestMonday <br/>";
								//$flag = 0; 
								//$var = "";
								//$tdates > $tfirstday ? $var = ">" : $var = "<";
								//echo "$tyear_dates $tmonth_dates $tday_dates || $tyear_firstday $tmonth_firstday $tday_firstday <br/>";
								//echo "tdates $var tfirstday <br/><br/>";
						
								if((($latestMonday < $tday_firstday) && ($tday_firstday < $tday_dates)) && ($latestMonday > 0)){
									$diff = $tday_firstday - $latestMonday;
								} else {
							
								}	

								$minhrs -= $diff * 8;
							}
					   
							if (leaveExistsWeek($month1, $i, $year2, $empCode)){
								#$lea = leaveMinutesPerWeek($month1, $i, $year2, $empCode);
							#$a += hoursToMins(leaveHours($month1, $i, $year2, $empCode));
							}

					   		$month_req += $minhrs;
		   
				   			if ($isForDebugging && checkEmployee($empCode)) echo "H[minhrs] $dates $minhrs<br/>";		    
							#$total_def = ($minhrs*60) - ($total_wk + $total_tar + $lea);// - $zzz*60;
							#echo "$total_def = ($minhrs*60) - ($total_wk + $total_tar + $lea); || total_def = (minhrs*60) - (total_wk + total_tar + lea);<br/>";
							#echo "[TOTAL DEF] $total_def $dates<br/><br/>";
						
								//OVERTIME
							if ($total_ot <= 0){
								$ot = "";
							}
							else{
								$ot = floor($total_ot/60). " h " .$total_ot%60 . " m";
							}

							//TARDY
							if ($total_tar <= 0) {
								$tar = "";
							}
							else{
								$tar = floor($total_tar/60). " h " .$total_tar%60 . " m";
							}

							//HOURS PER WEEK
							if ($total_wk <= 0) {
								$hrs_wk = "";
							} 
							else {
								//echo " ".$total_wk/60;
								$hrs_wk = floor($total_wk/60). " hrs " .$total_wk%60 . " m";
							}

							//DEFICIT
							#if ($total_def <= 0){		   	
							# $def = "";
							#}
							#else{
								#$def = floor($total_def/60). " h " . $total_def%60 . " m";
								#$month_def += $total_def;
							#}
							# echo $dates." ".$total_wk." w";

								/*$wktar = $total_wk + $total_tar;
							$true_def = (($minhrs*60)-$wktar);
							$month_def += ($true_def > 0) ? $true_def : 0 ;
							$month_def -= (leaveExistsWeek($month1, $i, $year2, $empCode)) ? leaveHours($month1, $i, $year2, $empCode)*60 : 0 ;
							$def = ($true_def > 0) ? (floor($true_def/60)." hrs ".($true_def%60)." m") : "";
								*/
							$weekly_leave_minutes += $DTR->leave_minutes;

							$wktar = $total_wk + $total_tar;
							#$minhrs += (leaveExistsWeek($month1, $i, $year2, $empCode)) ? (leaveExistsWeek($month1, $i, $year2, $empCode)) : 0 ;

							$true_def = (($minhrs*60)-$wktar);
							#$true_def = (leaveExistsWeek($month1, $i, $year2, $empCode)) ? (($minhrs*60)-$wktar) : (($minhrs*60)-$wktar) ;

							//workfromhome, removed monthly deficit
							if ($isWFHAllowed) 
								$month_def = 0;
							else 
								$month_def += ($true_def > 0) ? $true_def : 0 ;


							$month_def -= (leaveExistsWeek($month1, $i, $year2, $empCode)) ? leaveHours($month1, $i, $year2, $empCode)*60 : 0 ;
							$def = ($true_def > 0) ? (floor($true_def/60)." hrs ".($true_def%60)." m") : "";
							//END, WEEKENDS	

							echo "</tr>";
                            
							if ($isForDebugging && checkEmployee($empCode) && $displayWeeklyDebugging) {
								echo "<tr><td colspan='9'>WEEKLY ".date("Y-m-d", mktime(0,0,0,$month1,$i,$year2))." <br/>";
									echo "minhrs:$minhrs <br/>";
									echo "wfhHoursPerWeek:".$wfhHoursPerWeek." hrs"." <br/>";
									echo "wfhHoursPerMonth:".$wfhHoursPerMonth." hrs"." <br/>";
									echo "total_wk:".(floor($total_wk/60)." hrs ".($total_wk%60)." m")." <br/>";
									echo "total_tar:".(floor($total_tar/60)." hrs ".($total_tar%60)." m")." <br/>";
									echo "weekly_leave_minutes:$weekly_leave_minutes <br/>";
									echo "total_week:".$DTR->total_week." <br/>";
									echo "total_total:".$DTR->total_total." <br/>";
									echo "total_tar_under:".$DTR->total_tar_under." <br/>";
									echo "week_tar:".$DTR->week_tar." <br/>";
									echo "week_tar2:".$DTR->week_tar2." <br/>";
									echo "week_ua:".$DTR->week_ua." <br/>";
									echo "tot:".$DTR->tot." <br/>";
									echo "tot_tar_under:".$DTR->tot_tar_under." <br/>";
									echo "tally_tar:".$DTR->tally_tar." <br/>";
									echo "tally_tardy:".$DTR->tally_tardy." <br/>";
									echo "tally_undertime:".$DTR->tally_undertime." <br/>";
									echo "isOnLeave2(date(\"Y-m-d\", mktime(0,0,0,$month1,$i,$year2)), $empCode)*60: ".(isOnLeave2(date("Y-m-d", mktime(0,0,0,$month1,$i,$year2)), $empCode)*60)." <br/>";
									echo "DEF:".(floor($month_def/60)." hrs ".($month_def%60)." m")."";
								echo "</td></tr>";
							}

							echo "<tr>";
							echo "<td colspan=\"6\" align=\"right\" class=\"dtrPrint3\">Required Hours:</td>";
							echo "<td class=\"dtrPrint3\">".(($minhrs < 0) ? 0 : $minhrs)."</td>";
							echo "<td class=\"dtrPrint3\">".$hrs_wk. "</td>";
							echo "<td width=\"3\">&nbsp;</td>";
						
							//total ng week
							echo "<td class=\"dtrPrint3\">".$tar."</td>";
							echo "<td class=\"dtrPrint3\">&nbsp;</td>";
							echo "<td class=\"dtrPrint3\" width=\"3\" colspan='2'>".$def."</td>";
						
							#if (existOT($month1, $day1, $day2, $year2, $empCode)){
							// echo "<td class=\"dtrPrint3\">".$ot."</td>";
							#}

							$total_wk = 0;
							$total_ot = 0;
							$total_tar = 0;
							$lea = 0;
							$weekly_leave_minutes = 0;
							$wfhHoursPerWeek = 0;
						}

				  echo "</tr>";
                  
						if ($isForDebugging && checkEmployee($empCode) && $displayDailyDebugging) {
							echo "<tr><td colspan='9'> DAILY ".date("Y-m-d", mktime(0,0,0,$month1,$i,$year2))." <br/>";
							echo "minhrs:$minhrs <br/>";
							echo "wfhHoursPerWeek:".$wfhHoursPerWeek." hrs"." <br/>";
							echo "wfhHoursPerMonth:".$wfhHoursPerMonth." hrs"." <br/>";
							echo "total_wk:".(floor($total_wk/60)." hrs ".($total_wk%60)." m")." <br/>";
							echo "total_tar:".(floor($total_tar/60)." hrs ".($total_tar%60)." m")." <br/>";
							echo "weekly_leave_minutes:$weekly_leave_minutes <br/>";
							echo "total_week:".$DTR->total_week." <br/>";
							echo "total_total:".$DTR->total_total." <br/>";
							echo "total_tar_under:".$DTR->total_tar_under." <br/>";
							echo "week_tar:".$DTR->week_tar." <br/>";
							echo "week_tar2:".$DTR->week_tar2." <br/>";
							echo "week_ua:".$DTR->week_ua." <br/>";
							echo "tot:".$DTR->tot." <br/>";
							echo "tot_tar_under:".$DTR->tot_tar_under." <br/>";
							echo "tally_tar:".$DTR->tally_tar." <br/>";
							echo "tally_tardy:".$DTR->tally_tardy." <br/>";
							echo "tally_undertime:".$DTR->tally_undertime." <br/>";
							echo "isOnLeave2(date(\"Y-m-d\", mktime(0,0,0,$month1,$i,$year2)), $empCode)*60: ".(isOnLeave2(date("Y-m-d", mktime(0,0,0,$month1,$i,$year2)), $empCode)*60)." <br/>";
							echo "DEF:".(floor($month_def/60)." hrs ".($month_def%60)." m")."";
							echo "</td></tr>";
						}
					$isOnSW = 0;
				  }	//END, LOOP PER DAY

                    if ($isForDebugging && checkEmployee($empCode)) {
				  		echo "<tr><td colspan='12'> 
				  			month_tar: $month_tar <br/> 
				  			month_def: $month_def <br/>
				  		</td></tr>";
				  	}

				  	$month_def = ($month_def > 0) ? $month_def : 0;
					  $month_tar_def += $month_tar + $month_def;

				    if ($month_hours < 0){
				   	  $month_hours = "";
				    }
				    else{
							$month_hours = floor($month_hours/60). " h " . $month_hours%60 . " m";
				    }
				    
				    if ($month_ot <= 0){
							$month_ot = "";
				    }
						else{
				    	$proc_ot = $month_ot/60;
							$month_ot = floor($month_ot/60). " h " . $month_ot%60 . " m";
				    }
				   
				   	if ($month_tar < 0){
							$month_tar = "";
				    }
				    else{
							$tartar = $month_tar;
							$month_tar = floor($month_tar/60). " h " . $month_tar%60 . " m";
				    }

				    $total_def = $month_def;
				    if ($total_def < 0){
							$month_def = "";
				    }
				    else{
				    	$defdef = $total_def;
							$month_def = floor($total_def/60). " h " . $total_def%60 . " m";
				    }
				   
				    if ($month_tar_def < 0){
					 		$month_tar_def = 0;
				    }
				    else{
					  	$month_tar_def = floor($month_tar_def/60). " h " . $month_tar_def%60 . " m";
				    }
				  ?>

					<tr>
			   		<td colspan="7">&nbsp;</td>
			   		<td class="dtrPrint13">Total Hours</td>
			   		<td width="3">&nbsp;</td>
			   		<td class="dtrPrint13">Total Tardy/Under</td>
			   		<td width="3">&nbsp;</td>
			   		<td class="dtrPrint13">Total Deficit</td>
			   		<td width="3">&nbsp;</td>
			   		<?php
		 	    	if (existOT($month1, $day1, $day2, $year2, $empCode)){
			   		?>
				   	<!--td class="dtrPrint13">Total Overtime</td>
				   	<td width="3">&nbsp;</td--> 
			   		<?php
			    	}
			   		?>
					</tr>

					<tr>
	   				<td colspan="5" align="right" class="dtrPrint4">REQUIRED HOURS:</td>
			   		<td class="dtrPrint4"><?php echo (($month_req < 0) ? 0 : $month_req); ?></td>
			   		<td class="dtrPrint4" align="right">TOTAL:&nbsp;&nbsp;</td>
			   		<td class="dtrPrint5"><?php echo $month_hours; ?></td>
			   		<td width="3">&nbsp;</td>
			   		<td class="dtrPrint5"><?php echo $month_tar; ?></td>
		   			<td width="3">&nbsp;</td>
			   		<td class="dtrPrint5"><?php echo $month_def; ?></td>
			   		<td width="3">&nbsp;</td>
					<?php
    			if (existOT($month1, $day1, $day2, $year2, $empCode)){ 
	   			?>
		   		<!--td class="dtrPrint5"><?php echo $month_ot; ?></td>
	   			<td width="3">&nbsp;</td--> 
	   			<?php
	    		}
	  			?>
					</tr>

			  	<tr>
			   		<td colspan="5" align="right" class="dtrPrint4">TOTAL HOLIDAY HOURS:</td>
			   		<td class="dtrPrint4"><?php echo $holhours; ?></td>
			  	</tr>
	  			<tr>
						<td colspan="9" align="right" class="dtrPrint4">Total TARDY/UNDERTIME during core hours + Deficit hours:&nbsp;&nbsp;</td>
	   				<td class="dtrPrint4"><?php echo $month_tar_def; ?></td>
	  			</tr>
		  		<tr>
				   	<td colspan="9" align="right" class="dtrPrint4">Total Number of LATES:&nbsp;&nbsp;</td>
				   	<td class="dtrPrint4"><?php echo $tally_tardy; ?></td>
				  </tr>
				  <tr>
				   	<td colspan="9" align="right" class="dtrPrint4">Total Number of UNDERTIME:&nbsp;&nbsp;</td>
				   	<td class="dtrPrint4"><?php echo $tally_undertime; ?></td>
				  </tr>
			  	<!--maine08082017 number of unfiled/unauthorized absences/leaves separated from filed leaves/absences -->
			  	<tr>
			   		<td colspan="9" align="right" class="dtrPrint4">Total Number of UNAUTHORIZED ABSENCES:&nbsp;&nbsp;</td>
			   		<td class="dtrPrint4"><?php echo $ua; ?></td>
			  	</tr>
			  	<tr>
				   	<td colspan="9" align="right" class="dtrPrint4">Total Number of FILED LEAVES:&nbsp;&nbsp;</td>
				   	<td class="dtrPrint4"><?php echo $absent; ?></td>
			  	</tr>
				</table><br>
			</td>
		</tr>
   	<?php
  	// echo $in_vl." ".$in_sl; echo $excess;
  
  	//FINAL PROCESSING
   	if (isset($_GET['i'])) { ?>
		<tr>
    	<td colspan="2" align="center" class="dtrHead1"><font style="font-style:italic">I hereby certify that the above records are true and correct.</font></td>
   	</tr>
   	<tr><td>&nbsp;</td></tr>
		<tr>
    	<td colspan="2">
				<table width="100%">
	  			<tr>
	   				<td width="10">&nbsp;</td>
	   				<td>
	    				<table>
	     					<tr>
	      					<td align="center" class="dtrPrint132">&emsp;<?php echo strtoupper($director); ?>&nbsp;&nbsp;&nbsp;</td>
	     					</tr>
	     					<tr>
	      					<td align="center" class="dtrHead1"><?php echo $position; ?></td>
	     					</tr>
	    				</table>
						</td>
						<td align="right">
	    				<table>
		 						<tr>
		  						<td class="dtrPrint13" align="center">&nbsp;</td>
		 						</tr>
		 						<tr>
		  						<td class="dtrHead1" align="center">&emsp;Employee's Signature&nbsp;&nbsp;&nbsp;</td>
							 </tr>
							</table>
	  				</td>
	   				<td width="10">&nbsp;</td>
	  			</tr>
				</table>
			</td>
   	</tr>
		<?php
    }
		?>
	</table>
</body>

<?php
	$to = date("Y-m-d", mktime(0,0,0,$month1,$day2,$year2));
  $from = date("Y-m-d", mktime(0,0,0,$month1,$day1,$year2));
  $first_day = getFirstDay($empCode);      	
  $processDate = date("Y-m-d", mktime(0,0,0,$month1,$day1,$year2));
  
  //WILL ALLOW CHANGES TO tblEmpLeave, tblSummary and other tables if date of processing is (month to be processed + 1)
  $compareTo = date("Y-m-d", mktime(0,0,0,$month1,date("t",strtotime($from)),$year2));
  $today = date("Y-m-d");
  //echo "$from-$to-$first_day-$processDate-$compareTo-$today";
  //FINAL PROCESSING
  //START OF 1ST SET
 	if (isset($_GET['i']) && $first_day < $from && $today > $compareTo) {
 		//if (isset($_GET['i']) && $today > $compareTo){
		//if(isset($_GET['i'])){
 		//echo "[1st Set] <br/>";
		//check muna kung na-process na to dati. kapag oo, di na magpoproceed
		//update tblEmpDTR where the records are affected and change fldProcessed=1

  $query = mysql_query("select * from tblSummary where fldEmpCode='$empCode' AND fldMonth='$month1' AND fldYear='$year2'");
  $num = mysql_num_rows($query);
	//echo "NUM: $num <br/><br/>";
  $query = mysql_query("select fldEmpDTRID from tblEmpDTR where fldEmpCode='$empCode' AND (fldEmpDTRdate between '$from' AND '$to') AND fldProcessed='0' order by fldEmpDTRdate");
  if ($num == 0){
   while ($row = mysql_fetch_array($query)){
    $id = $row[0];	
	mysql_query("update tblEmpDTR set fldProcessed='1' where fldEmpDTRID='$id'") or die("g) ".mysql_error());
   }
   
   $suID = $_SESSION['userID'];
   $curr_date = date("Y-m-d H:i:s");
   $curr_month = date("m");
   $curr_year = date("y");
//print_r($leave_array);
   	mysql_query("insert into tblAuditDTRProcess (fldEmpCode, fldUserID, fldDateProcessed, fldFromDate, fldToDate) values ('$empCode', '$suID', '$curr_date', '$from', '$to')");
	/*
	*	tardy/undertime = $tartar
	*	excess hours = $excess
	*	tally of lates = $tally
	*	tally of absences = $absent
	*	VL used for tardy = $VLUsedTardy
	*	VL used for leave = $VLUsedLeave
	*	VL without pay tardy = $VLNoTardy
	*	VL without pay leave = $VLNoLeave
	*	VL earned for the month = $VLEarned
	*	VL balance after processing = $VLBalance
	*	SL used for leave = $SLUsedLeave
	*	SL without pay leave = $SLNoLeave
	*	SL earned for the month = $SLEarned
	*	SL balance after processing = $SLBalance
	*/
   
   //echo $kaltas_tardef;
   if ($month1 == 9 && $year2 == 2008){
   	$query = mysql_query("select * from tblLeaveBalance where fldEmpCode='$empCode' AND fldLeaveTypeID='1' order by fldDate asc");
	$row = mysql_fetch_array($query);
	$VLBegin = $row['fldBalance'];
	
   	$query = mysql_query("select * from tblLeaveBalance where fldEmpCode='$empCode' AND fldLeaveTypeID='2' order by fldDate asc");
	$row = mysql_fetch_array($query);
	$SLBegin = $row['fldBalance'];
   }
   else{
    $pm = $month1-1;
	$py = $year2;
		if ($pm <= 0){
			$pm = 12;
			$py = $year2-1;
		}
	
		$query = mysql_query("select * from tblSummary where fldEmpCode='$empCode' AND fldMonth='$pm' AND fldYear='$py'");
		$result_count = mysql_num_rows($query);
	
		if($result_count == 0){		//NO previous entry for tblSummary meaning, new employee
			$query = mysql_query("select * from tblLeaveBalance where fldEmpCode='$empCode' AND fldLeaveTypeID='1' order by fldDate asc");
			$row = mysql_fetch_array($query);
			$VLBegin = $row['fldBalance'];
						
			$query = mysql_query("select * from tblLeaveBalance where fldEmpCode='$empCode' AND fldLeaveTypeID='2' order by fldDate asc");
			$row = mysql_fetch_array($query);
			$SLBegin = $row['fldBalance'];	
		} else if($result_count){		//AVAILABLE previous entry for tblSummary meaning
			$query = mysql_query("select * from tblSummary where fldEmpCode='$empCode' AND fldMonth='$pm' AND fldYear='$py'");
			$row = mysql_fetch_array($query);
			$VLBegin = $row['fldVLBalance'];
			$SLBegin = $row['fldSLBalance'];
		}	
   }
	$vl_temp = $VLBegin;
	$sl_temp = $SLBegin;
   
   #echo "START VL_temp: $vl_temp SL_temp: $sl_temp VLBegin ;$VLBegin SLBegin: $SLBegin<br/>";
   #echo "in_vl :$in_vl in_sl:$in_sl <br/>";
   
   //start: deduction for unfiled halfday absences/leaves/tary/undertime
   //$total_kaltas = $tartar + $defdef; 
   $total_kaltas = $tartar; 
   
   //ibawas muna yung tardy/undertime from the leave balance
   $minutes = $total_kaltas%60;
   $hours = floor($total_kaltas/60);
   
   $kaltas_tardef = 0;	
   if ($minutes > 0 && !isExempted($empCode)){
	$q = mysql_query("select * from tblConversionWorkHours where fldMinutes='$minutes'");
	$r = mysql_fetch_array($q);
	$kaltas_tardef += $r['fldEquivDay'];
   }
   if ($hours > 0 && !isExempted($empCode)){
	$q = mysql_query("select * from tblConversionWorkHours where fldMinutes='60'");
	$r = mysql_fetch_array($q);
	$kaltas_tardef += $r['fldEquivDay'] * $hours;
   }   
 //end: deduction for unfiled halfday absences/leaves/tary/undertime
   
  if ($vl_temp - $in_vl >= 0){
   	$VLULeave = $in_vl;
	$VLNLeave = 0;
	$vl_temp = $vl_temp - $VLULeave;
	$q = mysql_query("select * from tblConversionLeaveEarned where fldNumDays='30'");
	$r = mysql_fetch_array($q);
	$VLEarned = $r['fldEarned'];
	$SLEarned = $r['fldEarned'];   
   }
   else{
   	//$VLNLeave  = abs(floor($vl_temp) - $in_vl);
	 $div = $in_vl/0.5;	 
	 $VLNLeave = 0;
	 for ($i=1;$i<=$div;$i++){
	  if (($vl_temp - 0.5) > 0){
	   $vl_temp -= 0.5;
	  }
	  else{
	   $VLNLeave += 0.5;
	  }
	 }
	$VLULeave = $in_vl - $VLNLeave;
	//$vl_temp = $vl_temp - $VLULeave;
	//$days = 30 - $VLNLeave;
	//$q = mysql_query("select * from tblConversionLeaveEarned where fldNumDays='$days'");
	$q = mysql_query("select * from tblConversionWoPay where fldDaysWOP='$VLNLeave'"); 
	$r = mysql_fetch_array($q);
	$VLEarned = $r['fldEarned']; 
	
	//SL
	$q2 = mysql_query("select * from tblConversionLeaveEarned where fldNumDays='30'");
	$r2 = mysql_fetch_array($q2);
	$SLEarned = $r2['fldEarned']; 
   }
   
   //start: maine08092017 deduction for unfiled wholeday leave/unauthorized absence
   //counted as unauthorized absence not as undertime/tardy 
   if ($ua > 0) {
	 $uanvl=$VLNLeave+$ua;
	 $q1 = mysql_query("select * from tblConversionWoPay where fldDaysWOP='$uanvl'");
	 $r1 = mysql_fetch_array($q1);
	 $VLEarned=$r1['fldEarned']; 	 
   }
   //end: maine08092017 deduction for unfiled wholeday leave/unauthorized absence 
    
   //echo "AFTER DEDUCTION OF VL VL_temp: $vl_temp SL_temp: $sl_temp VLBegin ;$VLBegin SLBegin: $SLBegin<br/>";
   //echo "VL_temp: $vl_temp | VLULEAVE: $VLULeave | VLNLeave: $VLNLeave | TARDY: $VLUTardy | NPTardy: $VLNoTardy <br/>";
   //echo "in_vl: $in_vl in_sl: $in_sl ua: $ua<br/>";
   //echo "$sl_temp";
   if ($sl_temp - $in_sl >= 0){
   	$SLULeave = $in_sl;
	$sl_temp -= $SLULeave;
	$SLNLeave = 0;
   }
   else{
	if ($vl_temp - ($in_sl - floor($sl_temp)) > 0){
		$SLNLeave = 0;
		$squeezed = (int)($sl_temp/.5);
		$rem = ($sl_temp - ($squeezed*.5));
		$SLULeave = ($squeezed*.5);				
		$sl_temp = $rem;
		
		$VLULeave += $in_sl - $SLULeave;
		$vl_temp -= ($in_sl - ($squeezed*.5));
	}
	else{		
		$squeezeSLB = (int)($sl_temp/.5);	
		if($squeezeSLB >= 1){ //3
			#$rem = ($sl_temp - ($squeeze*.5));
			$temp = ($squeezeSLB*.5); //1.5
			//echo "$temp = ($squeezeSLB*.5);";
			$sl_temp = ($sl_temp - ($squeezeSLB*.5)); //0
			//echo "$sl_temp = ($sl_temp - ($squeezeSLB*.5));";
			$SLULeave = ($squeezeSLB*.5);//1.5
			//echo "$SLULeave = ($squeezeSLB*.5);	";				
			$SLNLeave = $in_sl - ($squeezeSLB*.5);	//.5	
			//echo "$SLNLeave = $in_sl - ($squeezeSLB*.5);";	
			$squeezeVLB = (int)($vl_temp/.5); //1.538
			#echo "SqueezeVLB: ".$squeezeVLB."<br/>";
			if($squeezeVLB >= 1){
				$VLULeave += ($squeezeVLB*.5); //.769
				$vl_temp = $vl_temp - ($squeezeVLB*.5); 				
				$SLNLeave -= ($squeezeVLB*.5);
				//echo "12";	
			}
			//echo "1";										
		} else {
			$SLNLeave = abs(floor($vl_temp) - floor($SLBegin) - $in_sl);
			$VLULeave  += floor($vl_temp);
			$SLULeave += floor($sl_temp);	
		}
		//echo "$squeezeSLB = (int)($sl_temp/.5);";
	}
		//$SLULeave = $in_sl - $SLNLeave;
   }	
       	
   #$VLEnding = $vl_temp - $VLULeave - $kaltas_tardef + $VLEarned;   
   #$vl_temp -= $kaltas_tardef;	~ delayed the deduction of VLUTardy or kaltas_tardef
   #$vl_temp += $VLEarned;		~ delayed the addition of VLEarned
   $VLEnding = $vl_temp;
   
   #echo "$vl_temp -= $kaltas_tardef :: vl_temp -= kaltas_tardef<br/> $vl_temp += $VLEarned :: vl_temp += VLEarned<br/> $VLEnding = $vl_temp :: VLEnding = vl_temp<br/>";
   
   //echo "$VLEnding = $vl_temp - $VLULeave - $kaltas_tardef + $VLEarned ::  VLEnding = vl_temp - VLULeave - kaltas_tardef + VLEarned<br/>";
   //echo "AFTER DEDUCTION OF SL VL_temp: $vl_temp SL_temp: $sl_temp VLBegin ;$VLBegin SLBegin: $SLBegin<br/>"; 
   //echo "VLULeave: $VLULeave SLULeave: $SLULeave<br/>";       
      
   $SLEnding = $SLBegin - $SLULeave;
   if ($SLEnding < 0){
   	$SLEnding = $SLEarned;
   }
   else{
   	$SLEnding += $SLEarned;
   }

	#echo "AFTER VLEnding AND SLEnding VL_temp: $vl_temp SL_temp: $sl_temp VLBegin ;$VLBegin SLBegin: $SLBegin<br/>"; 
    #echo "VLULeave: $VLULeave SLULeave: $SLULeave VLEnding: $VLEnding SLEnding: $SLEnding<br/><br/>";
	
	//CHECKING FOR APPROVED WHOLE DAY PRIVILEGE LEAVES
	$prCount = 0;
	$index = 0;
	$rawDate = array();
	$_query = "SELECT * FROM camsonline2.tblEmpLeave 
	WHERE fldEmpCode LIKE '$empCode' AND fldLeaveTypeID = 3 
	AND YEAR(fldAppDate) = $year2 AND MONTH(fldAppDate) <= $month1 
	AND fldAM_PM_WD = 1 AND fldAppFtL='1' AND fldFlagCaRe='0'";
	$sql = mysql_query($_query) or die("1) ".mysql_error());
	while($resultT = mysql_fetch_array($sql)){		
		$date1 = $resultT['fldFromDate'];
		$date2 = $resultT['fldToDate'];
		
		if($date1 == $date2){
			$rawDate[$index++] = $date1;			
		} else {				
				$dateA1 = explode("-",$date1);
				$dateA2 = explode("-",$date2);
				$date1 = mktime(0,0,0,$dateA1[1],$dateA1[2],$dateA1[0]);
				$date2 = mktime(0,0,0,$dateA2[1],$dateA2[2],$dateA2[0]);
				$diff = $date2 - $date1;
				$diff = ($diff/3600)/24;
				
				for($ab = 0;$ab <= $diff; $ab++){
					$toadd = "+".$ab." day";
					$newdate = strtotime($toadd, $date1);
					$date = date("Y-m-d",$newdate);
					
					if(!in_array($date,$rawDate)) $rawDate[$index++] = $date;
				}					
		}
	}
	$rawDate = array_unique($rawDate);
	#echo "WHOLE PR LEAVES: "; print_r($rawDate); echo "<br/><br/>";
	
	foreach($rawDate as $valT){
		$val = $valT;
		$dateA1 = explode("-",$val);		
		$date1 = mktime(0,0,0,$dateA1[1],$dateA1[2],$dateA1[0]);
		$dayFlag = date("w",$date1);
		if (isOnLeave($val, $empCode) && ($dayFlag >= 1 && $dayFlag <= 5)){		
		#on leave on a weekday		
			$ht = holidayTime($val);
			#echo "$val $empCode dayFlag: $dayFlag LeaveTime($val, $empCode): $lt holidayTime($val): $ht <br/>";
			switch($ht){
				case 1:	$prCount += 0.5;					
						break; 
				case 2:	$prCount += 0.5;					
						break; 
				case 3:	$prCount += 0;
						break; 
				default:$prCount += 1;
						break;
			}
		} else {
		#not on leave
			$prCount += 0;
		}		
	}
	#echo "<br/>prCount:$prCount; <br/>";
	
	$rawDate = array();
	$index = 0;
	//CHECKING FOR APPROVED HALF DAY PRIVILEGE LEAVES FOR PREVIOUS MONTHS
	$_query = "SELECT * FROM camsonline2.tblEmpLeave 
	WHERE fldEmpCode LIKE '$empCode' AND fldLeaveTypeID = 3 
	AND YEAR(fldAppDate) = $year2 AND MONTH(fldAppDate) < $month1 
	AND (fldAM_PM_WD = 2 OR fldAM_PM_WD = 3) AND fldAppFtL='1' AND fldFlagCaRe='0'";
	$sql = mysql_query($_query) or die("2) ".mysql_error());
	while($resultT = mysql_fetch_array($sql)){		
		$date1 = $resultT['fldFromDate'];
		$date2 = $resultT['fldToDate'];
		
		if($date1 == $date2){
			$rawDate[$index++] = $date1;			
		} else {				
				$dateA1 = explode("-",$date1);
				$dateA2 = explode("-",$date2);
				$date1 = mktime(0,0,0,$dateA1[1],$dateA1[2],$dateA1[0]);
				$date2 = mktime(0,0,0,$dateA2[1],$dateA2[2],$dateA2[0]);
				$diff = $date2 - $date1;
				$diff = ($diff/3600)/24;
				
				for($ab = 0;$ab <= $diff; $ab++){
					$toadd = "+".$ab." day";
					$newdate = strtotime($toadd, $date1);
					$date = date("Y-m-d",$newdate);
					
					if(!in_array($date,$rawDate)) $rawDate[$index++] = $date;
				}					
		}
	}
	$rawDate = array_unique($rawDate);
	#echo "HALF PR LEAVES: ";print_r($rawDate); echo "<br/><br/>";
	
	foreach($rawDate as $val){
		$dateA1 = explode("-",$val);
		$date1 = mktime(0,0,0,$dateA1[1],$dateA1[2],$dateA1[0]);
		$dayFlag = date("w",$date1);
		#echo "".isOnLeave($val, $empCode)." && ($dayFlag >= 1 && $dayFlag <= 5) || isOnLeave($val, $empCode) && ($dayFlag >= 1 && $dayFlag <= 5)<br/>";
		if (isOnLeave($val, $empCode) && ($dayFlag >= 1 && $dayFlag <= 5)){
		#on leave on a weekday
			$lt = LeaveTime($val, $empCode);
			$ht = holidayTime($val);
			#echo "LeaveTime($val, $empCode): $lt holidayTime($val): $ht <br/>";
			if($lt == "(AM)"){
				switch($ht){
					case 1:	$prCount += 0;					
							break; 
					case 2:	$prCount += 0.5;					
							break; 
					case 3:	$prCount += 0;
							break; 
					default:$prCount += 0.5;
							break;
				}					
			} else if($lt == "(PM)"){
				switch($ht){
					case 1:	$prCount += 0.5;					
							break; 
					case 2:	$prCount += 0;					
							break; 
					case 3: $prCount += 0;
							break; 
					default:$prCount += 0.5;
							break;
				}
			}
		} else {
		#not on leave
			$prCount += 0;
		}
	}
	
	$rawDate = array();
	$index = 0;
	$prCount2 = 0;
	//CHECKING FOR APPROVED HALF DAY PRIVILEGE LEAVES FOR CURRENT MONTH
	$_query = "SELECT * FROM camsonline2.tblEmpLeave 
	WHERE fldEmpCode LIKE '$empCode' AND fldLeaveTypeID = 3 
	AND YEAR(fldAppDate) = $year2 AND MONTH(fldAppDate) = $month1 
	AND (fldAM_PM_WD = 2 OR fldAM_PM_WD = 3) AND fldAppFtL='1' AND fldFlagCaRe='0'";
	$sql = mysql_query($_query) or die("3) ".mysql_error());
	while($resultT = mysql_fetch_array($sql)){		
		$date1 = $resultT['fldFromDate'];
		$date2 = $resultT['fldToDate'];
		
		if($date1 == $date2){
			$rawDate[$index++] = $date1;			
		} else {				
				$dateA1 = explode("-",$date1);
				$dateA2 = explode("-",$date2);
				$date1 = mktime(0,0,0,$dateA1[1],$dateA1[2],$dateA1[0]);
				$date2 = mktime(0,0,0,$dateA2[1],$dateA2[2],$dateA2[0]);
				$diff = $date2 - $date1;
				$diff = ($diff/3600)/24;
				
				for($ab = 0;$ab <= $diff; $ab++){
					$toadd = "+".$ab." day";
					$newdate = strtotime($toadd, $date1);
					$date = date("Y-m-d",$newdate);
					
					if(!in_array($date,$rawDate)) $rawDate[$index++] = $date;
				}					
		}
	}
	$rawDate = array_unique($rawDate);
	#echo "HALF PR LEAVES: ";print_r($rawDate); echo "<br/><br/>";
	
	foreach($rawDate as $val){
		$dateA1 = explode("-",$val);
		$date1 = mktime(0,0,0,$dateA1[1],$dateA1[2],$dateA1[0]);
		$dayFlag = date("w",$date1);
		#echo "".isOnLeave($val, $empCode)." && ($dayFlag >= 1 && $dayFlag <= 5) || isOnLeave($val, $empCode) && ($dayFlag >= 1 && $dayFlag <= 5)<br/>";
		if (isOnLeave($val, $empCode) && ($dayFlag >= 1 && $dayFlag <= 5)){
		#on leave on a weekday
			$lt = LeaveTime($val, $empCode);
			$ht = holidayTime($val);
			#echo "LeaveTime($val, $empCode): $lt holidayTime($val): $ht <br/>";
			if($lt == "(AM)"){
				switch($ht){
					case 1:	$prCount2 += 0;					
							break; 
					case 2:	$prCount2 += 0.5;					
							break; 
					case 3:	$prCount2 += 0;
							break; 
					default:$prCount2 += 0.5;
							break;
				}					
			} else if($lt == "(PM)"){
				switch($ht){
					case 1:	$prCount2 += 0.5;					
							break; 
					case 2:	$prCount2 += 0;					
							break; 
					case 3: $prCount2 += 0;
							break; 
					default:$prCount2 += 0.5;
							break;
				}
			}
		} else {
		#not on leave
			$prCount2 += 0;
		}
	}

	#echo "<br/>PRCOUNT: $prCount PRCOUNT2: $prCount2<br/>";
	//UPDATES VL BALANCE, USED VL, FL BALANCE, USED FL
	if(!isSoloParent($empCode)){
		if($prCount > 3 && $prCount2 > 0){
			$temp = $prCount - 3;
			$VLULeave += $temp;
			$VLEnding -= $temp;
		}	
	} else {
		if($prCount > 10 && $prCount2 > 0){
			$temp = $prCount - 10;
			$VLULeave += $temp;
			$VLEnding -= $temp;
		}
	}
	// end of Privilege leave checking 		
	
	#VL used for tardy/deficit
	#$query = mysql_query("select * from tblLeaveBalance where fldEmpCode='$empCode' AND fldLeaveTypeID='1' order by fldDate desc");
	#$row = mysql_fetch_array($query);
	#$bal = $row['fldBalance'] + $VLEarned;
	#$VLPrevBalance = $row['fldBalance'];
	#$neg = $row['fldNegative'];
	
	#if (($bal - $kaltas_tardef) >= 0) $VLBalance = $bal - $kaltas_tardef;
	#	else $VLBalance = 0;
	#echo "MONTH: $month1 FORCE LEAVE DAYS: $force_leave_days <br/>";	
	if ($month1 != 12 && !FLExempt($empCode) && $force_leave_days != 0){			
		if ($VLEnding - $force_leave_days >= 0){
			#$VLBalance -= $force_leave_days;
			$VLEnding -= $force_leave_days;		
			$VLULeave += $force_leave_days;		
		}
		else{
			$VLNLeave += $force_leave_days - floor($VLEnding);
			#$VLNLeave += $r[0] - floor($VLBalance);
			$VLULeave += $force_leave_days;
		}		
	}		
				
	#echo "<br/>GEN.INFO<br/>BEFORE DECEMBER DEDUCTIONS<br/>";
	#echo "vl_temp: $vl_temp			|| sl_temp: $sl_temp <br/>";
	#echo "VLULeave: $VLULeave		|| SLULeave: $SLULeave<br/>";
	#echo "VLNTardy: $VLNTardy		|| SLNTardy: $SLNTardy<br/>";
	#echo "VLUTardy: $VLUTardy		|| SLUTardy: $SLUTardy<br/>";
	#echo "VLNLeave: $VLNLeave		|| SLNLeave: $SLNLeave<br/>";
	#echo "VLEarned: $VLEarned		|| SLEarned: $SLEarned<br/>";
	#echo "VLEnding: $VLEnding		|| SLEnding: $SLEnding<br/>";
	#echo "EMPCODE: $empCode | TOTAL_KALTAS: $total_kaltas | EXCESS: $excess | TALLY: $tally | ABSENT: $absent <br/>";
	
	if ($month1 == 12 && !FLExempt($empCode)){
		#echo "$month1 == 12 && !FLExempt($empCode)<br/>";
		#echo "VLULeave: $VLULeave <br/>";
		#$to_year = $_POST['to_year'];
		
		$flcount = 0;
		$count = 0;
		//OBTAINS CORRECT REMAINING FL FOR DEC.
			//UPDATES FORCE LEAVE BALANCE, APPROVED OR DISAPPROVED VACATION LEAVE
			$textT = "SELECT fldFromDate, fldToDate FROM tblEmpLeave 
			WHERE fldEmpCode LIKE '$empCode' AND fldLeaveTypeID = 1 
			AND fldFromDate >= '$year2-01-01' AND fldFromDate <= '$year2-11-30' 
			AND ((fldAppFtL='1' AND fldFlagCaRe='0') OR (fldAppFtL='0' AND fldAppFtLDate!='0000-00-00 00:00:00' AND fldFlagCaRe='0'))";
			$queryT = mysql_query($textT) or die("4) ".mysql_error());
			$rawDate = array();
			$index = 0;
			while($resultT = mysql_fetch_array($queryT)){
				$date1 = $resultT['fldFromDate'];
				$date2 = $resultT['fldToDate'];
				
				if($date1 == $date2){
					$rawDate[$index++] = $date1;			
				} else {				
						$dateA1 = explode("-",$date1);
						$dateA2 = explode("-",$date2);
						$date1 = mktime(0,0,0,$dateA1[1],$dateA1[2],$dateA1[0]);
						$date2 = mktime(0,0,0,$dateA2[1],$dateA2[2],$dateA2[0]);
						$diff = $date2 - $date1;
						$diff = ($diff/3600)/24;
						
						for($ab = 0;$ab <= $diff; $ab++){
							$toadd = "+".$ab." day";
							$newdate = strtotime($toadd, $date1);
							$date = date("Y-m-d",$newdate);
							
							if(!in_array($date,$rawDate)) $rawDate[$index++] = $date;
						}					
				}
			}
			$rawDate = array_unique($rawDate);
			#echo "<strong>Approved/disapproved vacation leave JAN-NOV</strong><br/>";
			#print_r($rawDate); echo "<br/>";
			//$flcount = count($rawDate);	#feb. 27, 2013
			#echo "<strong>FLCount: $flcount </strong><br/><br/>";
			
			//LEAVE CHECKING
			foreach($rawDate as $tempDate){			
				if(isHoliday($tempDate)){ #date of approved/disapproved leave is a holiday		
					if(holidayTime($tempDate) == 3){ #whole day holiday
						if(LeaveTime($tempDate, $empCode) == "(AM)" || LeaveTime($tempDate, $empCode) == "(PM)"){
							$count += 0.5;
							#echo "WD Holiday, HF Leave $tempDate COUNT: $count <br/>";
						} else {
							$count += 1;
							#echo "WD Holiday, WD Leave $tempDate COUNT: $count <br/>";
						}
					} else { #half day holiday
						if(holidayTime($tempDate) == 1 && LeaveTime($tempDate, $empCode) == "(PM)"){
							$count += 0.5;
							#echo "HD Holiday, PM Leave $tempDate COUNT: $count <br/>";
						} else if (holidayTime($tempDate) == 2 && LeaveTime($tempDate, $empCode) == "(AM)"){
							$count += 0.5;
							#echo "HD Holiday, AM Leave $tempDate COUNT: $count <br/>";
						} else if ((holidayTime($tempDate) == 1 || holidayTime($tempDate) == 2) && LeaveTime($date, $empCode) == ""){
							$count += 0.5;
							#echo "HD Holiday, WD Leave $tempDate COUNT: $count <br/>";
						} else continue;					
					}
				} else { #regular day
					if(isAbsent($tempDate, getOptID($tempDate), $empCode) == 0.5){
						$count += 0.5;
						#echo "absent 0.5 $tempDate COUNT: $count <br/>";
					} else if(isAbsent($tempDate, getOptID($tempDate), $empCode) == 1){
						$count++;
						#echo "absent 1 $tempDate COUNT: $count <br/>";
					} else if(isAbsent($tempDate, getOptID($tempDate), $empCode) == 0) continue;
				}
			}
			#echo "COUNT: $count <br/><br/>";
		
			//COUNTS FORCE LEAVES, APPROVED FORCE LEAVE
			$textT = "SELECT fldFromDate, fldToDate FROM tblEmpLeave 
			WHERE fldEmpCode LIKE '$empCode' 
			AND fldLeaveTypeID = 6 
			AND fldFromDate >= '$year2-01-01' 
			AND fldToDate <= '$year2-11-30' 
			AND fldAppFtL='1' AND fldFlagCaRe='0'";	#echo "<br/>";			
			$queryT = mysql_query($textT) or die("5) ".mysql_error());
			$rawDate = array();
			$index = 0;
			while($resultT = mysql_fetch_array($queryT)){
				$date1 = $resultT['fldFromDate'];
				$date2 = $resultT['fldToDate'];
				
				if($date1 == $date2){
					$rawDate[$index++] = $date1;			
				} else {				
						$dateA1 = explode("-",$date1);
						$dateA2 = explode("-",$date2);
						$date1 = mktime(0,0,0,$dateA1[1],$dateA1[2],$dateA1[0]);
						$date2 = mktime(0,0,0,$dateA2[1],$dateA2[2],$dateA2[0]);
						$diff = $date2 - $date1;
						$diff = ($diff/3600)/24;
						
						for($ab = 0;$ab <= $diff; $ab++){
							$toadd = "+".$ab." day";
							$newdate = strtotime($toadd, $date1);
							$date = date("Y-m-d",$newdate);
							
							if(!in_array($date,$rawDate)) $rawDate[$index++] = $date;
						}					
				}
			}
			$rawDate = array_unique($rawDate);	
			#echo "<strong>Approved force leave JAN-NOV </strong><br/>";
			#print_r($rawDate);  echo "<br/>";	
			
			//LEAVE CHECKING
			foreach($rawDate as $tempDate){			
				if(isHoliday($tempDate)){ #date of approved/disapproved leave is a holiday		
					if(holidayTime($tempDate) == 3){ #whole day holiday
						if(LeaveTime($tempDate, $empCode) == "(AM)" || LeaveTime($tempDate, $empCode) == "(PM)"){
							$count += 0.5;
							#echo "WD Holiday, HF Leave $tempDate COUNT: $count <br/>";
						} else {
							$count += 1;
							#echo "WD Holiday, WD Leave $tempDate COUNT: $count <br/>";
						}
					} else { #half day holiday
						if(holidayTime($tempDate) == 1 && LeaveTime($tempDate, $empCode) == "(PM)"){
							$count += 0.5;
							#echo "HD Holiday, PM Leave $tempDate COUNT: $count <br/>";
						} else if (holidayTime($tempDate) == 2 && LeaveTime($tempDate, $empCode) == "(AM)"){
							$count += 0.5;
							#echo "HD Holiday, AM Leave $tempDate COUNT: $count <br/>";
						} else if ((holidayTime($tempDate) == 1 || holidayTime($tempDate) == 2) && LeaveTime($date, $empCode) == ""){
							$count += 0.5;
							#echo "HD Holiday, WD Leave $tempDate COUNT: $count <br/>";
						} else continue;					
					}
				} else { #regular day
					if(isAbsent($tempDate, getOptID($tempDate), $empCode) == 0.5){
						$count += 0.5;
						#echo "absent 0.5 $tempDate COUNT: $count <br/>";
					} else if(isAbsent($tempDate, getOptID($tempDate), $empCode) == 1){
						$count++;
						#echo "absent 1 $tempDate COUNT: $count <br/>";
					} else if(isAbsent($tempDate, getOptID($tempDate), $empCode) == 0) continue;
				}
			}
			#echo "COUNT: $count <br/><br/>";
		
			//COUNTS FORCE LEAVES, DISAPPROVED FORCE LEAVE
			$textT = "SELECT fldFromDate, fldToDate FROM tblEmpLeave 
			WHERE fldEmpCode LIKE '$empCode' 
			AND fldLeaveTypeID = 6 
			AND fldFromDate >= '$year2-01-01' 
			AND fldToDate <= '$year2-11-30' 
			AND fldAppFtL='0' AND fldAppFtLDate!='0000-00-00 00:00:00' AND fldFlagCaRe='0'";echo "<br/>";		
			$queryT = mysql_query($textT) or die("6) ".mysql_error());
			$rawDate = array();
			$index = 0;
			while($resultT = mysql_fetch_array($queryT)){
				$date1 = $resultT['fldFromDate'];
				$date2 = $resultT['fldToDate'];
							
				if($date1 == $date2){
					$rawDate[$index++] = $date1;			
				} else {				
						$dateA1 = explode("-",$date1);
						$dateA2 = explode("-",$date2);
						$date1 = mktime(0,0,0,$dateA1[1],$dateA1[2],$dateA1[0]);
						$date2 = mktime(0,0,0,$dateA2[1],$dateA2[2],$dateA2[0]);
						$diff = $date2 - $date1;
						$diff = ($diff/3600)/24;
						
						for($ab = 0;$ab <= $diff; $ab++){
							$toadd = "+".$ab." day";
							$newdate = strtotime($toadd, $date1);
							$date = date("Y-m-d",$newdate);
							
							if(!in_array($date,$rawDate)) $rawDate[$index++] = $date;
						}					
				}
			}
			$rawDate = array_unique($rawDate);
			#echo "<strong>Disapproved force leave JAN-NOV </strong><br/>";
			#print_r($rawDate); echo "<br/>";
			#echo "<strong>$flcount += count($rawDate) </strong><br/>";	
			//$flcount += count($rawDate);		#feb.27, 2013
			#echo "<strong>flcount: $flcount </strong><br/>";
			
			//LEAVE CHECKING
			foreach($rawDate as $tempDate){			
				if(isHoliday($tempDate)){ #date of approved/disapproved leave is a holiday		
					if(holidayTime($tempDate) == 3){ #whole day holiday
						if(LeaveTime($tempDate, $empCode) == "(AM)" || LeaveTime($tempDate, $empCode) == "(PM)"){
							$count += 0.5;
							#echo "WD Holiday, HF Leave $tempDate COUNT: $count <br/>";
						} else {
							$count += 1;
							#echo "WD Holiday, WD Leave $tempDate COUNT: $count <br/>";
						}
					} else { #half day holiday
						if(holidayTime($tempDate) == 1 && LeaveTime($tempDate, $empCode) == "(PM)"){
							$count += 0.5;
							#echo "HD Holiday, PM Leave $tempDate COUNT: $count <br/>";
						} else if (holidayTime($tempDate) == 2 && LeaveTime($tempDate, $empCode) == "(AM)"){
							$count += 0.5;
							#echo "HD Holiday, AM Leave $tempDate COUNT: $count <br/>";
						} else if ((holidayTime($tempDate) == 1 || holidayTime($tempDate) == 2) && LeaveTime($date, $empCode) == ""){
							$count += 0.5;
							#echo "HD Holiday, WD Leave $tempDate COUNT: $count <br/>";
						} else continue;					
					}
				} else { #regular day
					if(isAbsent($tempDate, getOptID($tempDate), $empCode) == 0.5){
						$count += 0.5;
						#echo "absent 0.5 $tempDate COUNT: $count <br/>";
					} else if(isAbsent($tempDate, getOptID($tempDate), $empCode) == 1){
						$count++;
						#echo "absent 1 $tempDate COUNT: $count <br/>";
					} else if(isAbsent($tempDate, getOptID($tempDate), $empCode) == 0) {
						if(LeaveTime($tempDate, $empCode) == "(AM)" || LeaveTime($tempDate, $empCode) == "(PM)"){
							$count += 0.5;
							#echo "WD Holiday, HF Leave $tempDate COUNT: $count <br/>";
						} else {
							$count += 1;
							#echo "WD Holiday, WD Leave $tempDate COUNT: $count <br/>";
						}
					}
				}
			}
		#echo "COUNT: $count <br/><br/>";
			
		$flcount = $count;
			
		$FLPrevBalance = 0;
		if($year2 == 2008){
		//UPDATES FORCE LEAVES BEFORE DEDUCTION	
		$queryT = mysql_query("select * from tblLeaveBalance where fldEmpCode='$empCode' AND fldLeaveTypeID='6' order by fldDate desc");
			$rowT = mysql_fetch_array($queryT);
			$FLPrevBalance = $rowT['fldBalance'];
		} else {
			$FLPrevBalance = 5 - $flcount;	
			#echo "<strong>$FLPrevBalance = 5 - $flcount; || FLPrevBalance = 5 - $flcount;</strong><br/>";
			#if($FLPrevBalance <= 0) $FLPrevBalance = 0;
		} 
		#echo "<strong>FLPrevBalance: $FLPrevBalance </strong><br/><br/>";	
		
		$flbalancededuction = 0;
		
		//UPDATES FORCE LEAVE BALANCE, APPROVED OR DISAPPROVED VACATION LEAVE
		$textT = "SELECT fldFromDate, fldToDate FROM tblEmpLeave 
		WHERE fldEmpCode LIKE '$empCode' AND fldLeaveTypeID = 1 
		AND fldFromDate >= '$year2-12-01' AND fldFromDate <= '$year2-12-31' 
		AND ((fldAppFtL='1' AND fldFlagCaRe='0') OR (fldAppFtL='0' 
		AND fldAppFtLDate!='0000-00-00 00:00:00' AND fldFlagCaRe='0'))"; 
		#echo "<br/>";
		$queryT = mysql_query($textT) or die("7) ".mysql_error());
		$rawDate = array();
		$index = 0;
		while($resultT = mysql_fetch_array($queryT)){
			$date1 = $resultT['fldFromDate'];
			$date2 = $resultT['fldToDate'];
			
			if($date1 == $date2){
				$rawDate[$index++] = $date1;			
			} else {				
					$dateA1 = explode("-",$date1);
					$dateA2 = explode("-",$date2);
					$date1 = mktime(0,0,0,$dateA1[1],$dateA1[2],$dateA1[0]);
					$date2 = mktime(0,0,0,$dateA2[1],$dateA2[2],$dateA2[0]);
					$diff = $date2 - $date1;
					$diff = ($diff/3600)/24;
					
					for($ab = 0;$ab <= $diff; $ab++){
						$toadd = "+".$ab." day";
						$newdate = strtotime($toadd, $date1);
						$date = date("Y-m-d",$newdate);
						
						if(!in_array($date,$rawDate)) $rawDate[$index++] = $date;
					}					
			}
		}
		$rawDate = array_unique($rawDate);
		#echo "<strong>Approved/disapproved vacation leave DEC </strong><br/>";
		#print_r($rawDate); echo "<br/>";
		
		$count = 0;
		#echo "<strong>COUNT: $count </strong><br/><br/>";
		//LEAVE CHECKING
		foreach($rawDate as $tempDate){			
			if(isHoliday($tempDate)){ #date of approved/disapproved leave is a holiday		
				if(holidayTime($tempDate) == 3){ #whole day holiday
					if(LeaveTime($tempDate, $empCode) == "(AM)" || LeaveTime($tempDate, $empCode) == "(PM)"){
						$count += 0.5;
						$flbalancededuction += 0.5;
						#echo "WD Holiday, HF Leave $tempDate COUNT: $count <br/>";
					} else {
						$count += 1;
						$flbalancededuction += 1;
						#echo "WD Holiday, WD Leave $tempDate COUNT: $count <br/>";
					}
				} else { #half day holiday
					if(holidayTime($tempDate) == 1 && LeaveTime($tempDate, $empCode) == "(PM)"){
						$count += 0.5;
						$flbalancededuction += 0.5;
						#echo "HD Holiday, PM Leave $tempDate COUNT: $count <br/>";
					} else if (holidayTime($tempDate) == 2 && LeaveTime($tempDate, $empCode) == "(AM)"){
						$count += 0.5;
						$flbalancededuction += 0.5;
						#echo "HD Holiday, AM Leave $tempDate COUNT: $count <br/>";
					} else if ((holidayTime($tempDate) == 1 || holidayTime($tempDate) == 2) && LeaveTime($date, $empCode) == ""){
						$count += 0.5;
						$flbalancededuction += 0.5;
						#echo "HD Holiday, WD Leave $tempDate COUNT: $count <br/>";
					} else continue;					
				}
			} else { #regular day
				if(isAbsent($tempDate, getOptID($tempDate), $empCode) == 0.5){
					$count += 0.5;
					$flbalancededuction += 0.5;
					#echo "absent 0.5 $tempDate COUNT: $count <br/>";
				} else if(isAbsent($tempDate, getOptID($tempDate), $empCode) == 1){
					$count++;
					$flbalancededuction += 1;
					#echo "absent 1 $tempDate COUNT: $count <br/>";
				} else if(isAbsent($tempDate, getOptID($tempDate), $empCode) == 0) continue;
			}
		}						
		//$count = count($rawDate);	#Feb. 2013
		#echo "<strong>APPROVED OR DISAPPROVED VL DEC ($count)</strong>: ";
		#print_r($rawDate); echo "<br/>";
		#echo "COUNT: $count <br/><br/>";
		
		//UPDATES CURRENT FORCE LEAVE BALANCE
		$FLPrevBalance -= $count;
		#echo "<strong>$FLPrevBalance -= $count || FLPrevBalance -= count </strong><br/><br/>";
		
		#echo "=========> FORCE LEAVE <==========<br/>";
		#echo "=========> APPROVED FORCE LEAVE <==========<br/>";
		$flbalancededuction = 0;
		//COUNTS FORCE LEAVES, APPROVED FORCE LEAVE
		$textT = "SELECT fldFromDate, fldToDate FROM tblEmpLeave 
		WHERE fldEmpCode LIKE '$empCode' 
		AND fldLeaveTypeID = 6 
		AND fldFromDate >= '$year2-12-01' 
		AND fldToDate <= '$year2-12-31' 
		AND fldAppFtL='1' AND fldFlagCaRe='0'";
		#echo "<br/>";		
		$queryT = mysql_query($textT) or die("8) ".mysql_error());
		$rawDate = array();
		$index = 0;
		while($resultT = mysql_fetch_array($queryT)){
			$date1 = $resultT['fldFromDate'];
			$date2 = $resultT['fldToDate'];
			
			if($date1 == $date2){
				$rawDate[$index++] = $date1;			
			} else {				
					$dateA1 = explode("-",$date1);
					$dateA2 = explode("-",$date2);
					$date1 = mktime(0,0,0,$dateA1[1],$dateA1[2],$dateA1[0]);
					$date2 = mktime(0,0,0,$dateA2[1],$dateA2[2],$dateA2[0]);
					$diff = $date2 - $date1;
					$diff = ($diff/3600)/24;
					
					for($ab = 0;$ab <= $diff; $ab++){
						$toadd = "+".$ab." day";
						$newdate = strtotime($toadd, $date1);
						$date = date("Y-m-d",$newdate);
						
						if(!in_array($date,$rawDate)) $rawDate[$index++] = $date;
					}					
			}
		}
		$count = 0;
		$rawDate = array_unique($rawDate);
		#echo "<strong>Approved force leave DEC </strong><br/>";
		#print_r($rawDate); echo "<br/>";
		
		foreach($rawDate as $tempDate){			
			if(isHoliday($tempDate)){ #date of approved/disapproved leave is a holiday		
				if(holidayTime($tempDate) == 3){ #whole day holiday
					if(LeaveTime($tempDate, $empCode) == "(AM)" || LeaveTime($tempDate, $empCode) == "(PM)"){
						$count += 0.5;
						$flbalancededuction += 0.5;
						#echo "WD Holiday, HF Leave $tempDate COUNT: $count <br/>";
					} else {
						$count += 1;
						$flbalancededuction += 1;
						#echo "WD Holiday, WD Leave $tempDate COUNT: $count <br/>";
					}
				} else { #half day holiday
					if(holidayTime($tempDate) == 1 && LeaveTime($tempDate, $empCode) == "(PM)"){
						$count += 0.5;
						$flbalancededuction += 0.5;
						#echo "HD Holiday, PM Leave $tempDate COUNT: $count <br/>";
					} else if (holidayTime($tempDate) == 2 && LeaveTime($tempDate, $empCode) == "(AM)"){
						$count += 0.5;
						$flbalancededuction += 0.5;
						#echo "HD Holiday, AM Leave $tempDate COUNT: $count <br/>";
					} else if ((holidayTime($tempDate) == 1 || holidayTime($tempDate) == 2) && LeaveTime($date, $empCode) == ""){
						$count += 0.5;
						$flbalancededuction += 0.5;
						#echo "HD Holiday, WD Leave $tempDate COUNT: $count <br/>";
					} else continue;					
				}
			} else { #regular day
				if(isAbsent($tempDate, getOptID($tempDate), $empCode) == 0.5){
					$count += 0.5;
					$flbalancededuction += 0.5;
					#echo "absent 0.5 $tempDate COUNT: $count <br/>";
				} else if(isAbsent($tempDate, getOptID($tempDate), $empCode) == 1){
					$count++;
					$flbalancededuction += 1;
					#echo "absent 1 $tempDate COUNT: $count <br/>";
				} else if(isAbsent($tempDate, getOptID($tempDate), $empCode) == 0) continue;
			}
		}
		#echo " COUNT: $count || flbalancededuction: $flbalancededuction<br/><br/>";
		
		if($count > 0){
			#echo "PRE: VLULeave: $VLULeave & VLEnding: $VLEnding <br/>";
			#echo "1APPROVED: $count <br/>";	
			if(($VLEnding - $count) > 0){
				$VLULeave += $count;
				$VLEnding -= $count;		
			} else {
				$VLNLeave += $count;
				$VLULeave += $count;				
			}	
			#echo "1VLULeave: $VLULeave & VLEnding: $VLEnding <br/>";
			$FLPrevBalance -= $count;				
		}
		
		#echo "<strong>AFTER APPROVED FL) vlending: $VLEnding vltemp: $vl_temp vlused:$VLULeave FLPrevBalance: $FLPrevBalance </strong><br/><br/>";
		
		#echo "=========> DISAPPROVED FORCE LEAVE <==========<br/>";
		$rawDate = "";
		$index = 0;
		$count = 0;			
		$flbalancededuction = 0;	
		//COUNTS FORCE LEAVES, DISAPPROVED FORCE LEAVE
		$textT = "SELECT fldFromDate, fldToDate FROM tblEmpLeave 
		WHERE fldEmpCode LIKE '$empCode' 
		AND fldLeaveTypeID = 6 
		AND fldFromDate >= '$year2-12-01' 
		AND fldToDate <= '$year2-12-31' 
		AND fldAppFtL='0' AND fldAppFtLDate!='0000-00-00 00:00:00' AND fldFlagCaRe='0'";
		$queryT = mysql_query($textT) or die("9) ".mysql_error());
		$rawDate = array();
		$index = 0;
		while($resultT = mysql_fetch_array($queryT)){
			$date1 = $resultT['fldFromDate'];
			$date2 = $resultT['fldToDate'];
						
			if($date1 == $date2){
				//if(!isHoliday($date1) && !isDayOff($opt_id, $date)) $rawDate[$index++] = $date1;			
				$rawDate[$index++] = $date1;
			} else {				
					$dateA1 = explode("-",$date1);
					$dateA2 = explode("-",$date2);
					$date1 = mktime(0,0,0,$dateA1[1],$dateA1[2],$dateA1[0]);
					$date2 = mktime(0,0,0,$dateA2[1],$dateA2[2],$dateA2[0]);
					$diff = $date2 - $date1;
					$diff = ($diff/3600)/24;
					
					for($ab = 0;$ab <= $diff; $ab++){
						$toadd = "+".$ab." day";
						$newdate = strtotime($toadd, $date1);
						$date = date("Y-m-d",$newdate);
						
						$temp_opt_id = getOptID($date);
						if(!in_array($date,$rawDate) && !isDayOff($temp_opt_id, $date)) $rawDate[$index++] = $date;
					}					
			}
		}
		$rawDate = array_unique($rawDate);
		//$count += count($rawDate);
		
		#echo "<br/><strong>Disapproved force leave DEC </strong><br/>";
		#print_r($rawDate); echo "<br/>";
		
		//LEAVE CHECKING
		foreach($rawDate as $tempDate){			
			if(isHoliday($tempDate)){ #date of approved/disapproved leave is a holiday		
				if(holidayTime($tempDate) == 3){ #whole day holiday
					if(LeaveTime($tempDate, $empCode) == "(AM)" || LeaveTime($tempDate, $empCode) == "(PM)"){
						$count += 0.5;
						$flbalancededuction += 0.5;
						#echo "WD Holiday, HF Leave $tempDate COUNT: $count <br/>";
					} else {
						$count += 1;
						$flbalancededuction += 1;
						#echo "WD Holiday, WD Leave $tempDate COUNT: $count <br/>";
					}
				} else { #half day holiday
					if(holidayTime($tempDate) == 1 && LeaveTime($tempDate, $empCode) == "(PM)"){
						$count += 0.5;
						$flbalancededuction += 0.5;
						#echo "HD Holiday, PM Leave $tempDate COUNT: $count <br/>";
					} else if (holidayTime($tempDate) == 2 && LeaveTime($tempDate, $empCode) == "(AM)"){
						$count += 0.5;
						$flbalancededuction += 0.5;
						#echo "HD Holiday, AM Leave $tempDate COUNT: $count <br/>";
					} else if ((holidayTime($tempDate) == 1 || holidayTime($tempDate) == 2) && LeaveTime($date, $empCode) == ""){
						$count += 0.5;
						$flbalancededuction += 0.5;
						#echo "HD Holiday, WD Leave $tempDate COUNT: $count <br/>";
					} else continue;					
				}
			} else { #regular day
				if(isAbsent($tempDate, getOptID($tempDate), $empCode) == 0.5){
					$count += 0.5;
					$flbalancededuction += 0.5;
					#echo "absent 0.5 $tempDate COUNT: $count <br/>";
				} else if(isAbsent($tempDate, getOptID($tempDate), $empCode) == 1){
					$count++;
					$flbalancededuction += 1;
					#echo "absent 1 $tempDate COUNT: $count <br/>";
				} else if(isAbsent($tempDate, getOptID($tempDate), $empCode) == 0) $count++;
			}
		}
		#echo "COUNT: $count || flbalancededuction: $flbalancededuction<br/><br/>";		
		
		//$count += count($rawDate); #feb.18,2013
		
		#echo "DISAPPROVED FL(".count($rawDate)."): ";
		#print_r($rawDate);
		#echo "<br/>";
		#if($count > 0)
		#echo "$FLPrevBalance -= $count || FLPrevBalance -= count";
		$FLPrevBalance -= $count;
				
		//$FLBalance = $FLPrevBalance - $count;	 #feb. 27, 2013
		#echo "<strong>$FLBalance = $FLPrevBalance - $count :: FLBalance = FLPrevBalance - count</strong><br/>";
		#echo "$FLBalance = $FLPrevBalance || FLBalance = FLPrevBalance";
		$FLBalance = $FLPrevBalance;
		#echo "<strong>$FLBalance = 5 - $FLPrevBalance; :: FLBalance = 5 - FLPrevBalance;</strong><br/>";
				
		
		if($VLEnding > 10){
			if($FLBalance <= 0){
				$FLBalance = 0;					
			} else {								
				if(($VLEnding - $FLBalance) < 0){
					#$VLEnding = $VLEnding - floor($VLEnding);
					#$VLNLeave = abs(floor($VLEnding - $FLBalance));
										
					#$VLEnding = $VLEnding - $VLUTardy; 			#$VLEnding = $VLEnding + ($VLEarned - $VLUTardy);
					
					$VLNLeave = $FLBalance - floor($VLEnding);
					$VLEnding = $VLEnding - floor($VLEnding);
					
				} else {
					$VLULeave += $FLBalance;
					$VLEnding -= $FLBalance;	
				}
				#echo "2VLULeave: $VLULeave & VLEnding: $VLEnding <br/>";							
			}
		}						
		
		#echo "<strong>AFTER DISAPPROV FL) vlending: $VLEnding vltemp: $vl_temp vlused:$VLULeave FLPrevBalance: $FLPrevBalance</strong><br/>";
		
		#CORRECTS PRIVILEGE LEAVES
		#selects latest privilege leave balance
		$quickSQL = mysql_query("SELECT fldLeaveBalID FROM tblLeaveBalance WHERE fldEmpCode LIKE '$empCode' AND fldLeaveTypeID = 3 ORDER BY fldLeaveBalID DESC") or die("10) ".mysql_error());
		$quickResult = mysql_fetch_array($quickSQL);
		$fldLeaveBalID = $quickResult[0];		
		#resets previous privilege balance to zero
		$quickSQL = mysql_query("UPDATE tblLeaveBalance SET fldBalance=0 WHERE fldLeaveBalID = $fldLeaveBalID") or die("11) ".mysql_error());
		#stores the new privilege leave balance
		mysql_query("INSERT into tblLeaveBalance (fldLeaveTypeID, fldEmpCode, fldPrevBalance, fldBalance, fldNegative, fldDate) VALUES ('3', '$empCode', 0, 3, 0, '$curr_date')") or die("12) ".mysql_error());
		
		mysql_query("INSERT into tblLeaveBalance (fldLeaveTypeID, fldEmpCode, fldPrevBalance, fldBalance, fldNegative, fldDate) VALUES ('6', '$empCode', '$FLPrevBalance', 5, 0, '$curr_date')") or die("13) ".mysql_error());
		//END OF UPDATING FORCE LEAVE BALANCE																						
	}

	#ADDITION OF VLEarned subtraced by VLUTardy
	$VLEnding += $VLEarned;
	
	#DEDUCTION OF VLUTardy or kaltas_tardef
	$VLDeduct=$kaltas_tardef; //+$UADeduct;
	//$VLEnding -= $kaltas_tardef;
	$VLEnding -=$VLDeduct;
	if ($VLEnding >= 0){
		#$VLUTardy = $total_kaltas;
		$VLUTardy = $kaltas_tardef;
		$VLNTardy = 0;
	} else {
		$VLNTardy = abs($VLEnding);
		$VLUTardy = $kaltas_tardef;
		$VLEnding = 0;	
		#$VLNTardy = abs($VLEnding) + $VLBegin;
		#$VLEnding = $VLBegin;	
		#$VLUTardy already set
		#$VLUTardy = $kaltas_tardef;
		
	}
	#END OF DEDUCTION OF VLUTardy
	
	//DEDUCT MONETIZATION TO VL AND SL IF PRESENT
	$monetizeVL = 0;
	$monetizeSL = 0;
	$query = "SELECT a.* FROM tblmonetization a 
	WHERE EXISTS (
		SELECT b.fldEmpLeaveID FROM tblEmpLeave b WHERE b.fldEmpCode LIKE '$empCode' 
		AND MONTH(b.fldAppDate) = $month1 
		AND YEAR(b.fldAppDate) = $year2 
		AND a.fldEmpLeaveID = b.fldEmpLeaveID 
		AND b.fldFlagCaRe = 0
	)";
	$sql = mysql_query($query) or die("14) "."ERROR on 'Deduction of monetized leaves': ".mysql_error());
	while($result = mysql_fetch_array($sql)){
		$monetizeVL += $result['fldDeductToVL'];
		$monetizeSL += $result['fldDeductToSL'];
	}
	$VLEnding -= $monetizeVL;
	$SLEnding -= $monetizeSL;
	//END OF DEDUCTION OF MONETIZATIONS	
	
	//start: maine08082017 inserting of unauthorized absence or unfiled leave/absence to tblEmpLeave
	for ($i=$day1; $i<=$day2; $i++){
		$c = getDTR($empCode, date("Y-m-d", mktime(0,0,0,$month1,$i,$year2))); 
		$q = mysql_query("select * from tblEmpDTR where fldEmpDTRID='$c'");
		$r = mysql_fetch_array($q);
		$month=date("m");
		$year=date("Y");
		$dates = date("Y-m-d", mktime(0,0,0,$month1,$i,$year2));
		$lv->getEmpLeaves($empCode, $dates);
		$hol->getHolidays($dates);
        $opt_id = getOptID($dates);
	if (isAbsent($dates, getOptID($dates), $empCode) != 0 && !isDayOff($opt_id, $dates) && !isExempted($empCode) 
		   && getFirstDay($empCode) <= $dates && !$isSWAllowed && !$isWFHAllowed) {//!getOptID($dates)==5){
			if (WorkSuspended($dates) == "no" && !isHoliday($dates) && !isOnLeave2($dates, $empCode) && !isDayOff($opt_id, $dates)
			&& isOnTO($dates, $empCode) == 0 && isOnCTO($dates, $empCode) == 0){				
				//echo $dates.") UA Leave: ".$ua."<br/>";							
				$leave_id=14;
				$appftl=1;
				$dur=1;
				if(isset($dates)){		
					$query = "insert into tblEmpLeave (fldEmpCode, fldLeaveTypeID, fldAppDate, fldFromDate, 
						fldToDate, fldAM_PM_WD, fldAppFtl, fldAppFtLDate)
						values('$empCode', '$leave_id', '$dates', '$dates', '$dates', '$dur', '$appftl', '$curr_date')"; //echo "<br/>";
					mysql_query($query) or die("15) ".mysql_error());
				}				
			}
	   }	
	}
	//end: maine08082017 inserting of unauthorized absence or unfiled leave/absence to tblEmpLeave
	
	//start: maine08082017 computation of lwp, lwop, ua
	//inserting of all filed leaves from tblempleave to payroll.tblLWOP
	$UANoPay=$ua;
	$query = "insert into tblSummary 
	(fldEmpCode, fldMonth, fldYear, fldTardyUnder, fldExcessHours, fldTallyLates, fldTallyAbsent, fldVLUsedTardy, 
	fldVLUsedLeave, fldVLNoPayTardy, fldVLNoPayLeave, fldVLEarned, fldVLBalance, fldSLUsed, fldSLNoPay, fldSLEarned, 
	fldSLBalance, fldUANoPay, fldDateProcessed) 
	values 
	('$empCode', '$month1', '$year2', '$total_kaltas', '$excess', '$tally_tardy', '$absent', '$VLUTardy', 
	'$VLULeave', '$VLNTardy', '$VLNLeave', '$VLEarned', '$VLEnding', '$SLULeave', '$SLNLeave', '$SLEarned', 
	'$SLEnding', '$UANoPay', '$curr_date')";
	mysql_query($query) or die("16) ".mysql_error());   
	$date3=$year2."-".$month1."-01";
	$date4=$year2."-".$month1."-31";
	//$q3=mysql_query("select m_basic from payroll.tblempinfo where fldEmpCode='$empCode'");
	include("loadBasic.php");	 
	//echo "SQL: ".$sql."<br/>"; 
	$query1 = mysql_query($sql) or trigger_error(mysql_error()." in ".$query1);
	while ($r3=mysql_fetch_array($query1)){		 
		//$empsal=$r3['m_basic'];				
		$empsal=$r3['salary'];
		//echo "$empsal="; die();
		$query = "select fldEmpLeaveID, fldLeaveTypeID, fldEmpCode, fldAppDate, fldFromDate, fldToDate, 
		fldAM_PM_WD from camsonline2.tblEmpLeave where fldEmpCode LIKE '$empCode' and fldAppFtLDate is not null and fldFlagCaRe!=1 and fldFlagCaRe!=2 and fldAppFtL=1 and fldEmpLeaveID not in
			 (select fldEmpLeaveID from payroll.tblLWOP) and month(fldFromDate)=$month1 and year(fldFromDate)=$year2 order by fldEmpLeaveID asc"; //echo "<br/>";
		//echo "query: ".$query."<br/>"; 

		$lwopdeduction = 0;
		$q4=mysql_query($query);		
		while($r4=mysql_fetch_array($q4)){		
			//print_r($r4); echo "<br/>";	
			$empleaveid = $r4['fldEmpLeaveID'];		
			$q5 = mysql_query("select * from tblEmpLeave where fldEmpLeaveID='$empleaveid'");
			$empCode = $r4['fldEmpCode'];
			$empleaveid=$r4['fldEmpLeaveID'];
			$leave_id=$r4['fldLeaveTypeID'];
			$date = date("F j, Y", strtotime($r4['fldAppDate']));
			$datefiled=$r4['fldAppDate'];
			$from=$r4['fldFromDate'];
			$to=$r4['fldToDate'];
			$dur = $r4['fldAM_PM_WD'];				
			$days = getDays($from, $to, ComputeDateDifference($from, $to) + 1);
			$duration = duration($from, $to);
			if (($dur == 2 || $dur == 3) && $days == 1){
				$days = 0.5;
			}	
			$ret =  DeductLBalDTRprocess($empleaveid, $leave_id, $empCode, $days, $datefiled, $dur);
			parse_str($ret);	
			if ($leave_id == 7 || $leave_id == 12){
				$weekends = " (Includes Weekends)";
				$days = ComputeDateDifference($from, $to) + 1;
			}
			else{
				$weekends = "";
			}
			$dayslwop = 0;
			$dayslwp = 0;
			$daysul = 0;
			if ($leave_id == 2){				
				$dayslwop = $neg;
				$dayslwp = $pos;
			}
			else if ($leave_id == 1){
				$dayslwop = $neg;
				$dayslwp = $pos;
			}
			else if($leave_id==14){
				$daysul=1;
				$dayslwp=0;
				$dayslwop=0;
			}
			else if($leave_id==15){
				$daysul=0;
				$dayslwp=0;
				$dayslwop=$pos;
			}
			else{
				$dayslwp = $days;				 
			}		
			//echo "$daysul-$dayslwp";
			$deduct=($empsal / 22) * ($dayslwop + $daysul);
			$deduction = round($deduct, 2);
			$deductstat=0;	
			//echo "q5: $q5 || dayslwop: $dayslwop || daysul: $daysul <br/>";	
			if(isset($q5)){
				if ($dayslwop!=0 || $daysul!=0){
					$query = "insert into payroll.tblLWOP 
					(fldEmpLeaveID, fldLeaveTypeID, fldEmpCode, fldDateFiled, fldFromDate, fldToDate, fldDaysLWP, fldDaysLWOP, 
					fldDaysUL, fldDeduction, fldMonthDTR, fldYearDTR, fldDeductStatus) 
					values('$empleaveid', '$leave_id', '$empCode', '$datefiled', '$from','$to',$dayslwp,$dayslwop,
					$daysul,$deduction,'$month1','$year2','$deductstat')"; //echo "<br/>";		
					mysql_query($query) or die("17) $query :: ".mysql_error());			
				}
			}			
			$lwopdeduction+=$deduction;
			//echo "$deduction*$lwopdeduction";
			$query = "update payroll.tblEmpInfo set LWOP='$lwopdeduction' where fldEmpCode='$empCode'"; //echo "<br/>";
			mysql_query($query) or die("18) $query :: ".mysql_error());
		}
	}		
	//echo $month1;
	//echo $year2;
	//end: maine08082017 computation of lwp, lwop, ua
	//inserting of all filed leaves from tblempleave to payroll.tblLWOP
	
	//UPDATES VACATION LEAVE BALANCES
	$query = mysql_query("select * from tblLeaveBalance where fldEmpCode='$empCode' AND fldLeaveTypeID='1' order by fldDate desc");
	$row = mysql_fetch_array($query);
	$VLPrevBalance = ($row['fldBalance']) ? $row['fldBalance'] : 0 ; //12102018, initialize vlprevbalance
	$VLBalance = $VLEnding;
	$query = "insert into tblLeaveBalance (fldLeaveTypeID, fldEmpCode, fldPrevBalance, fldBalance, fldNegative, fldDate) values ('1', '$empCode', $VLPrevBalance, $VLBalance, 0, '$curr_date')"; //echo "<br/>";
	mysql_query($query) or die("19) $query :: ".mysql_error());
	
	 //UPDATES FORCE LEAVE BALANCES		
	if($month1 != 12){
		$queryT = mysql_query("select * from tblLeaveBalance where fldEmpCode='$empCode' AND fldLeaveTypeID='6' order by fldDate desc");
		$rowT = mysql_fetch_array($queryT);
		$FLPrevBalance = $rowT['fldBalance'];
		
		$textT = "SELECT fldFromDate, fldToDate FROM tblEmpLeave WHERE fldEmpCode LIKE '$empCode' AND 
		(fldLeaveTypeID = 6 OR fldLeaveTypeID = 1) AND fldFromDate >= '$from' AND fldToDate <= '$to' AND 
		((fldAppFtL='1' AND fldFlagCaRe='0') OR (fldAppFtL='0' AND fldAppFtLDate!='0000-00-00 00:00:00' AND fldFlagCaRe='0'))";
		$queryT = mysql_query($textT) or die("20) ".mysql_error());
		$rawDate = array();
		$index = 0;
		while($resultT = mysql_fetch_array($queryT)){
			$date1 = $resultT['fldFromDate'];
			$date2 = $resultT['fldToDate'];
						
			if($date1 == $date2){
				$rawDate[$index++] = $date1;			
			} else {				
					$dateA1 = explode("-",$date1);
					$dateA2 = explode("-",$date2);
					$date1 = mktime(0,0,0,$dateA1[1],$dateA1[2],$dateA1[0]);
					$date2 = mktime(0,0,0,$dateA2[1],$dateA2[2],$dateA2[0]);
					$diff = $date2 - $date1;
					$diff = ($diff/3600)/24;
					
					for($ab = 0;$ab <= $diff; $ab++){
						$toadd = "+".$ab." day";
						$newdate = strtotime($toadd, $date1);
						$date = date("Y-m-d",$newdate);
						
						if(!in_array($date,$rawDate)) $rawDate[$index++] = $date;
					}					
			}
		}
		$rawDate = array_unique($rawDate);			
		$count = count($rawDate);
		#echo "FL: (".count($rawDate)."): ";
		#print_r($rawDate);
		#echo "<br/>";
					
		$FLBalance = $FLPrevBalance - $count;		
		if($FLBalance < 0) $FLBalance = 0;	
		$query = "insert into tblLeaveBalance (fldLeaveTypeID, fldEmpCode, fldPrevBalance, fldBalance, fldNegative, fldDate) values ('6', '$empCode', $FLPrevBalance, $FLBalance, 0, '$curr_date')"; //echo "<br/>";
		mysql_query($query) or die("21) $query :: ".mysql_error());
	}
		
	//UPDATES SICK LEAVE BALANCES
	$query = mysql_query("select * from tblLeaveBalance where fldEmpCode='$empCode' AND fldLeaveTypeID='2' order by fldDate desc");
	$row = mysql_fetch_array($query);
	$SLPrevBalance = $row['fldBalance'];
	$SLBalance = $SLEnding;
	$query = "insert into tblLeaveBalance (fldLeaveTypeID, fldEmpCode, fldPrevBalance, fldBalance, fldNegative, fldDate) values ('2', '$empCode', $SLPrevBalance, $SLBalance, 0, '$curr_date')"; //echo "<br/>";
	mysql_query($query) or die("22) $query :: ".mysql_error());
	/*
	echo $kaltas_tardef ." tardy<br>";
	echo $excess." excess<br>";
	echo $tally." tally<br>";
	echo $absent." absent<br>";
	echo $VLUTardy." vl t<br>";
	echo $VLULeave." vl l<br>";
	echo $VLNTardy." vl no t<br>";
	echo $VLNLeave." vl no l<br>";
	echo $VLEarned." vl earned<br>";
	echo $VLEnding." vl bal<br>";
	echo $SLULeave." sl w<br>";
	echo $SLNLeave." sl no<br>";
	echo $SLEarned." sl earned<br>";
	echo $SLEnding." sl bal<br>"; 
	echo $VLBalance." bal ";*/
  }
 }
 //END OF FIRST SET
//START OF 2ND SET
 else if (isset($_GET['i']) && $first_day >= $from && $first_day <= $to){
	//echo "[2nd Set] <br/>";
 	//echo $first_day;
  $query = mysql_query("select * from tblSummary where fldEmpCode='$empCode' AND fldMonth='$month1' AND fldYear='$year2'");
  $num = mysql_num_rows($query);

  $query = mysql_query("select fldEmpDTRID from tblEmpDTR where fldEmpCode='$empCode' AND (fldEmpDTRdate between '$from' AND '$to') AND fldProcessed='0' order by fldEmpDTRdate");
  if ($num == 0){
   while ($row = mysql_fetch_array($query)){
    $id = $row[0];
	
	mysql_query("update tblEmpDTR set fldProcessed='1' where fldEmpDTRID='$id'") or die("23) $query :: ".mysql_error());
   }
   
   $suID = $_SESSION['userID'];
   $curr_date = date("Y-m-d H:i:s");

   	mysql_query("insert into tblAuditDTRProcess (fldEmpCode, fldUserID, fldDateProcessed, fldFromDate, fldToDate) values ('$empCode', '$suID', '$curr_date', '$from', '$to')") or die("24) $query :: ".mysql_error());
	/*
	#echo "
		tardy/undertime = $tartar
		excess hours = $excess
		tally of lates = $tally
		tally of absences = $absent
		VL used for tardy = $VLUsedTardy
		VL used for leave = $VLUsedLeave
		VL without pay tardy = $VLNoTardy
		VL without pay leave = $VLNoLeave
		VL earned for the month = $VLEarned
		VL balance after processing = $VLBalance
		SL used for leave = $SLUsedLeave
		SL without pay leave = $SLNoLeave
		SL earned for the month = $SLEarned
		SL balance after processing = $SLBalance
	#";
	*/
   //tardy/undertime
   $total_kaltas = $tartar + $defdef; 
	//echo "$total_kaltas = $tartar + $defdef"; 
   //ibawas muna yung tardy/undertime from the leave balance
   $minutes = $total_kaltas%60;
   $hours = floor($total_kaltas/60);
  
   $kaltas_tardef = 0;
   if ($minutes > 0){
    $q = mysql_query("select * from tblConversionWorkHours where fldMinutes='$minutes'");
    $r = mysql_fetch_array($q);
    $kaltas_tardef += $r['fldEquivDay'];
   }
   if ($hours > 0){
    $q = mysql_query("select * from tblConversionWorkHours where fldMinutes='60'");
    $r = mysql_fetch_array($q);
    $kaltas_tardef += $r['fldEquivDay'] * $hours;	
   }
  // echo $kaltas_tardef;
	/*echo $kaltas_tardef ." tardy<br>";
	echo $excess." excess<br>";
	echo $tally." tally<br>";
	echo $absent." absent<br>";
	echo $VLUTardy." vl t<br>";
	echo $VLULeave." vl l<br>";
	echo $VLNTardy." vl no t<br>";
	echo $VLNLeave." vl no l<br>";
	echo $VLEarned." vl earned<br>";
	echo $VLEnding." vl bal<br>";
	echo $SLULeave." sl w<br>";
	echo $SLNLeave." sl no<br>";
	echo $SLEarned." sl earned<br>";
	echo $SLEnding." sl bal<br>"; 
	echo $VLBalance." bal ";*/
	
	//echo $kaltas_tardef;
   if ($month1 == 9 && $year2 == 2008){
   	$query = mysql_query("select * from tblLeaveBalance where fldEmpCode='$empCode' AND fldLeaveTypeID='1' order by fldDate asc");
	$row = mysql_fetch_array($query);
	$VLBegin = $row['fldBalance'];
	
   	$query = mysql_query("select * from tblLeaveBalance where fldEmpCode='$empCode' AND fldLeaveTypeID='2' order by fldDate asc");
	$row = mysql_fetch_array($query);
	$SLBegin = $row['fldBalance'];
   }
   else{
    $pm = $month1-1;
	$py = $year2;
	if ($pm <= 0){
		$pm = 12;
		$py = $year2-1;
	}
	
	$query = mysql_query("select * from tblSummary where fldEmpCode='$empCode' AND fldMonth='$pm' AND fldYear='$py'");
	$result_count = mysql_num_rows($query);

	if($result_count == 0){		//NO previous entry for tblSummary meaning, new employee
		$query = mysql_query("select * from tblLeaveBalance where fldEmpCode='$empCode' AND fldLeaveTypeID='1' order by fldDate asc");
		$row = mysql_fetch_array($query);
		$VLBegin = $row['fldBalance'];
					
		$query = mysql_query("select * from tblLeaveBalance where fldEmpCode='$empCode' AND fldLeaveTypeID='2' order by fldDate asc");
		$row = mysql_fetch_array($query);
		$SLBegin = $row['fldBalance'];	
	} else if($result_count){		//AVAILABLE previous entry for tblSummary meaning
		$query = mysql_query("select * from tblSummary where fldEmpCode='$empCode' AND fldMonth='$pm' AND fldYear='$py'");
		$row = mysql_fetch_array($query);
		$VLBegin = $row['fldVLBalance'];
		$SLBegin = $row['fldSLBalance'];
	}
	
   }
	$kaltas_tardef;
	$sl_temp = $SLBegin;
   //echo "START VL_temp: $vl_temp SL_temp: $sl_temp VLBegin ;$VLBegin SLBegin: $SLBegin<br/>";
  // echo "in_vl :$in_vl in_sl:$in_sl <br/>";
   
   $t = $to[8].$to[9];
   //echo "<br/>";
   $f = $first_day[8].$first_day[9];
   //echo "<br/>";
   $days =  $t - $f + 1;
   
   if ($vl_temp - $in_vl >= 0){
   	$VLULeave = $in_vl;
	$VLNLeave = 0;
	$vl_temp = $vl_temp - $VLULeave;
	
	$query = "select * from tblConversionLeaveEarned where fldNumDays='$days'";
	$q = mysql_query($query);
	$r = mysql_fetch_array($q);
	$VLEarned = $r['fldEarned'];
	$SLEarned = $r['fldEarned'];   
   }
   // else{
   	// $VLNLeave  = abs(floor($vl_temp) - $in_vl);
	// $VLULeave = $in_vl - $VLNLeave;
	// $vl_temp -= $VLULeave;
	// $days = $days - $VLNLeave;
	// $q = mysql_query("select * from tblConversionLeaveEarned where fldNumDays='$days'");
	// $r = mysql_fetch_array($q);
	// $VLEarned = $r['fldEarned'];
	// $SLEarned = $r['fldEarned']; 
   // }
   else{
	 $div = $in_vl/0.5;
	 $VLNLeave = 0;
	 for ($i=1;$i<=$div;$i++){
	  if (($vl_temp - 0.5) > 0){
	   $vl_temp -= 0.5;
	  }
	  else{
	   $VLNLeave += 0.5;
	  }
	 }
	$VLULeave = $in_vl - $VLNLeave;
	$vl_temp = $vl_temp - $VLULeave;
	//$days = 30 - $VLNLeave;
	//$q = mysql_query("select * from tblConversionLeaveEarned where fldNumDays='$days'");
	$q = mysql_query("select * from tblConversionWoPay where fldDaysWOP='$VLNLeave'");
	$r = mysql_fetch_array($q);
	$VLEarned = $r['fldEarned'];
	
	//SL
	$q2 = mysql_query("select * from tblConversionLeaveEarned where fldNumDays='30'");
	$r2 = mysql_fetch_array($q1);
	$SLEarned = $r2['fldEarned']; 
   }
   
   //start: maine08092017 deduction for unfiled wholeday leave/unauthorized absence
   //counted as unauthorized absence not as undertime/tardy 
   if ($ua > 0) {
	 $q1 = mysql_query("select * from tblConversionLeaveEarned where fldNumDays='$ua'");
	 $r1 = mysql_fetch_array($q1);
	 $uaded=$r1['fldEarned'];
	 $VLEarned=$VLEarned-$uaded; 
   }
   //end: maine08092017 deduction for unfiled wholeday leave/unauthorized absence 
   
   //echo "AFTER DEDUCTION OF VL VL_temp: $vl_temp SL_temp: $sl_temp VLBegin ;$VLBegin SLBegin: $SLBegin<br/>";
   //echo "in_vl: $in_vl in_sl: $in_sl <br/>";
   //echo $in_sl;
   
   if ($sl_temp - $in_sl >= 0){
   	$SLULeave = $in_sl;
	$sl_temp -= $SLULeave;
	$SLNLeave = 0;
   }
   else{
	if ($vl_temp - ($in_sl - floor($sl_temp)) > 0){
		/*OLD CODE
		$SLNLeave = 0;
		$squeezed = (int)($sl_temp/.5);
		$rem = ($sl_temp - ($squeezed*.5));
		$SLULeave = ($squeezed*.5);		
		$VLULeave += $in_sl - $SLULeave;
		$sl_temp = $rem;
		*/
		$SLNLeave = 0;
		$squeezed = (int)($sl_temp/.5);
		$rem = ($sl_temp - ($squeezed*.5));
		$SLULeave = ($squeezed*.5);				
		$sl_temp = $rem;
		
		$VLULeave += $in_sl - $SLULeave;
		$vl_temp -= ($in_sl - ($squeezed*.5));
	}
	else{
		/*OLD CODE
		$SLNLeave = abs(floor($vl_temp) - floor($SLBegin) - $in_sl);
		$VLULeave  += floor($vl_temp);
		$SLULeave += floor($sl_temp);
		*/
		$squeezeSLB = (int)($sl_temp/.5);			
		if($squeezeSLB >= 1){
			#$rem = ($sl_temp - ($squeeze*.5));
			$temp = ($squeezeSLB*.5);
			$sl_temp = ($sl_temp - ($squeezeSLB*.5));
			$SLULeave = ($squeezeSLB*.5);						
			$SLNLeave = $in_sl - ($squeezeSLB*.5);
			
			$squeezeVLB = (int)($vl_temp/.5);
			#echo "SqueezeVLB: ".$squeezeVLB."<br/>";
			if($squeezeVLB >= 1){
				$VLULeave += ($squeezeVLB*.5);
				$vl_temp = $vl_temp - ($squeezeVLB*.5);
				
				$SLNLeave -= ($squeezeVLB*.5);
			}										
		} else {
			$SLNLeave = abs(floor($vl_temp) - floor($SLBegin) - $in_sl);
			$VLULeave  += floor($vl_temp);
			$SLULeave += floor($sl_temp);	
		}
	}
		//$SLULeave = $in_sl - $SLNLeave;
   }	
       	
   #$VLEnding = $vl_temp - $VLULeave - $kaltas_tardef + $VLEarned;   
   #$vl_temp -= $kaltas_tardef;	~ delayed the deduction of VLUTardy or kaltas_tardef
   #$vl_temp += $VLEarned;		~ delayed the addition of VLEarned
   $VLEnding = $vl_temp;
   
   #echo "$vl_temp -= $kaltas_tardef :: vl_temp -= kaltas_tardef<br/>
   #$vl_temp += $VLEarned :: vl_temp += VLEarned<br/>
   #$VLEnding = $vl_temp :: VLEnding = vl_temp<br/>";
   
   #echo "$VLEnding = $vl_temp - $VLULeave - $kaltas_tardef + $VLEarned ::  VLEnding = vl_temp - VLULeave - kaltas_tardef + VLEarned<br/>";
   #echo "AFTER DEDUCTION OF SL VL_temp: $vl_temp SL_temp: $sl_temp VLBegin ;$VLBegin SLBegin: $SLBegin<br/>"; 
   #echo "VLULeave: $VLULeave SLULeave: $SLULeave<br/>";       
      
   $SLEnding = $SLBegin - $SLULeave;
   if ($SLEnding < 0){
   	$SLEnding = $SLEarned;
   }
   else{
   	$SLEnding += $SLEarned;
   }

	#echo "AFTER VLEnding AND SLEnding VL_temp: $vl_temp SL_temp: $sl_temp VLBegin ;$VLBegin SLBegin: $SLBegin<br/>"; 
    #echo "VLULeave: $VLULeave SLULeave: $SLULeave VLEnding: $VLEnding SLEnding: $SLEnding<br/><br/>";
	
	//CHECKING FOR APPROVED WHOLE DAY PRIVILEGE LEAVES
	$prCount = 0;
	$index = 0;
	$rawDate = array();
	$_query = "SELECT * FROM camsonline2.tblEmpLeave 
	WHERE fldEmpCode LIKE '$empCode' AND fldLeaveTypeID = 3 
	AND YEAR(fldAppDate) = $year2 AND MONTH(fldAppDate) <= $month1 
	AND fldAM_PM_WD = 1 AND fldAppFtL='1' AND fldFlagCaRe='0'";
	$sql = mysql_query($_query) or die("16) ".mysql_error());
	while($resultT = mysql_fetch_array($sql)){		
		$date1 = $resultT['fldFromDate'];
		$date2 = $resultT['fldToDate'];
		
		if($date1 == $date2){
			$rawDate[$index++] = $date1;			
		} else {				
				$dateA1 = explode("-",$date1);
				$dateA2 = explode("-",$date2);
				$date1 = mktime(0,0,0,$dateA1[1],$dateA1[2],$dateA1[0]);
				$date2 = mktime(0,0,0,$dateA2[1],$dateA2[2],$dateA2[0]);
				$diff = $date2 - $date1;
				$diff = ($diff/3600)/24;
				
				for($ab = 0;$ab <= $diff; $ab++){
					$toadd = "+".$ab." day";
					$newdate = strtotime($toadd, $date1);
					$date = date("Y-m-d",$newdate);
					
					if(!in_array($date,$rawDate)) $rawDate[$index++] = $date;
				}					
		}
	}
	$rawDate = array_unique($rawDate);
	#echo "WHOLE PR LEAVES: "; print_r($rawDate); echo "<br/><br/>";
	
	foreach($rawDate as $valT){
		$val = $valT;
		$dateA1 = explode("-",$val);		
		$date1 = mktime(0,0,0,$dateA1[1],$dateA1[2],$dateA1[0]);
		$dayFlag = date("w",$date1);
		if (isOnLeave($val, $empCode) && ($dayFlag >= 1 && $dayFlag <= 5)){		
		#on leave on a weekday		
			$ht = holidayTime($val);
			#echo "$val $empCode dayFlag: $dayFlag LeaveTime($val, $empCode): $lt holidayTime($val): $ht <br/>";
			switch($ht){
				case 1:	$prCount += 0.5;					
						break; 
				case 2:	$prCount += 0.5;					
						break; 
				case 3:	$prCount += 0;
						break; 
				default:$prCount += 1;
						break;
			}
		} else {
		#not on leave
			$prCount += 0;
		}		
	}
	#echo "<br/>prCount:$prCount; <br/>";
	
	$rawDate = array();
	$index = 0;
	//CHECKING FOR APPROVED HALF DAY PRIVILEGE LEAVES FOR PREVIOUS MONTHS
	$_query = "SELECT * FROM camsonline2.tblEmpLeave 
	WHERE fldEmpCode LIKE '$empCode' AND fldLeaveTypeID = 3 
	AND YEAR(fldAppDate) = $year2 AND MONTH(fldAppDate) < $month1 
	AND (fldAM_PM_WD = 2 OR fldAM_PM_WD = 3) AND fldAppFtL='1' AND fldFlagCaRe='0'";
	$sql = mysql_query($_query) or die("17) ".mysql_error());
	while($resultT = mysql_fetch_array($sql)){		
		$date1 = $resultT['fldFromDate'];
		$date2 = $resultT['fldToDate'];
		
		if($date1 == $date2){
			$rawDate[$index++] = $date1;			
		} else {				
				$dateA1 = explode("-",$date1);
				$dateA2 = explode("-",$date2);
				$date1 = mktime(0,0,0,$dateA1[1],$dateA1[2],$dateA1[0]);
				$date2 = mktime(0,0,0,$dateA2[1],$dateA2[2],$dateA2[0]);
				$diff = $date2 - $date1;
				$diff = ($diff/3600)/24;
				
				for($ab = 0;$ab <= $diff; $ab++){
					$toadd = "+".$ab." day";
					$newdate = strtotime($toadd, $date1);
					$date = date("Y-m-d",$newdate);
					
					if(!in_array($date,$rawDate)) $rawDate[$index++] = $date;
				}					
		}
	}
	$rawDate = array_unique($rawDate);
	#echo "HALF PR LEAVES: ";print_r($rawDate); echo "<br/><br/>";
	
	foreach($rawDate as $val){
		$dateA1 = explode("-",$val);
		$date1 = mktime(0,0,0,$dateA1[1],$dateA1[2],$dateA1[0]);
		$dayFlag = date("w",$date1);
		#echo "".isOnLeave($val, $empCode)." && ($dayFlag >= 1 && $dayFlag <= 5) || isOnLeave($val, $empCode) && ($dayFlag >= 1 && $dayFlag <= 5)<br/>";
		if (isOnLeave($val, $empCode) && ($dayFlag >= 1 && $dayFlag <= 5)){
		#on leave on a weekday
			$lt = LeaveTime($val, $empCode);
			$ht = holidayTime($val);
			#echo "LeaveTime($val, $empCode): $lt holidayTime($val): $ht <br/>";
			if($lt == "(AM)"){
				switch($ht){
					case 1:	$prCount += 0;					
							break; 
					case 2:	$prCount += 0.5;					
							break; 
					case 3:	$prCount += 0;
							break; 
					default:$prCount += 0.5;
							break;
				}					
			} else if($lt == "(PM)"){
				switch($ht){
					case 1:	$prCount += 0.5;					
							break; 
					case 2:	$prCount += 0;					
							break; 
					case 3: $prCount += 0;
							break; 
					default:$prCount += 0.5;
							break;
				}
			}
		} else {
		#not on leave
			$prCount += 0;
		}
	}
	
	$rawDate = array();
	$index = 0;
	$prCount2 = 0;
	//CHECKING FOR APPROVED HALF DAY PRIVILEGE LEAVES FOR CURRENT MONTH
	$_query = "SELECT * FROM camsonline2.tblEmpLeave 
	WHERE fldEmpCode LIKE '$empCode' AND fldLeaveTypeID = 3 
	AND YEAR(fldAppDate) = $year2 AND MONTH(fldAppDate) = $month1 
	AND (fldAM_PM_WD = 2 OR fldAM_PM_WD = 3) AND fldAppFtL='1' AND fldFlagCaRe='0'";
	$sql = mysql_query($_query) or die("18) ".mysql_error());
	while($resultT = mysql_fetch_array($sql)){		
		$date1 = $resultT['fldFromDate'];
		$date2 = $resultT['fldToDate'];
		
		if($date1 == $date2){
			$rawDate[$index++] = $date1;			
		} else {				
				$dateA1 = explode("-",$date1);
				$dateA2 = explode("-",$date2);
				$date1 = mktime(0,0,0,$dateA1[1],$dateA1[2],$dateA1[0]);
				$date2 = mktime(0,0,0,$dateA2[1],$dateA2[2],$dateA2[0]);
				$diff = $date2 - $date1;
				$diff = ($diff/3600)/24;
				
				for($ab = 0;$ab <= $diff; $ab++){
					$toadd = "+".$ab." day";
					$newdate = strtotime($toadd, $date1);
					$date = date("Y-m-d",$newdate);
					
					if(!in_array($date,$rawDate)) $rawDate[$index++] = $date;
				}					
		}
	}
	$rawDate = array_unique($rawDate);
	#echo "HALF PR LEAVES: ";print_r($rawDate); echo "<br/><br/>";
	
	foreach($rawDate as $val){
		$dateA1 = explode("-",$val);
		$date1 = mktime(0,0,0,$dateA1[1],$dateA1[2],$dateA1[0]);
		$dayFlag = date("w",$date1);
		#echo "".isOnLeave($val, $empCode)." && ($dayFlag >= 1 && $dayFlag <= 5) || isOnLeave($val, $empCode) && ($dayFlag >= 1 && $dayFlag <= 5)<br/>";
		if (isOnLeave($val, $empCode) && ($dayFlag >= 1 && $dayFlag <= 5)){
		#on leave on a weekday
			$lt = LeaveTime($val, $empCode);
			$ht = holidayTime($val);
			#echo "LeaveTime($val, $empCode): $lt holidayTime($val): $ht <br/>";
			if($lt == "(AM)"){
				switch($ht){
					case 1:	$prCount2 += 0;					
							break; 
					case 2:	$prCount2 += 0.5;					
							break; 
					case 3:	$prCount2 += 0;
							break; 
					default:$prCount2 += 0.5;
							break;
				}					
			} else if($lt == "(PM)"){
				switch($ht){
					case 1:	$prCount2 += 0.5;					
							break; 
					case 2:	$prCount2 += 0;					
							break; 
					case 3: $prCount2 += 0;
							break; 
					default:$prCount2 += 0.5;
							break;
				}
			}
		} else {
		#not on leave
			$prCount2 += 0;
		}
	}

	#echo "<br/>PRCOUNT: $prCount PRCOUNT2: $prCount2<br/>";
	//UPDATES VL BALANCE, USED VL, FL BALANCE, USED FL
	if(!isSoloParent($empCode)){
		if($prCount > 3 && $prCount2 > 0){
			$temp = $prCount - 3;
			$VLULeave += $temp;
			$VLEnding -= $temp;
		}	
	} else {
		if($prCount > 10 && $prCount2 > 0){
			$temp = $prCount - 10;
			$VLULeave += $temp;
			$VLEnding -= $temp;
		}
	}
	// end of Privilege leave checking 		
	
	#VL used for tardy/deficit
	#$query = mysql_query("select * from tblLeaveBalance where fldEmpCode='$empCode' AND fldLeaveTypeID='1' order by fldDate desc");
	#$row = mysql_fetch_array($query);
	#$bal = $row['fldBalance'] + $VLEarned;
	#$VLPrevBalance = $row['fldBalance'];
	#$neg = $row['fldNegative'];
	
	#if (($bal - $kaltas_tardef) >= 0) $VLBalance = $bal - $kaltas_tardef;
	#	else $VLBalance = 0;
	#echo "MONTH: $month1 FORCE LEAVE DAYS: $force_leave_days <br/>";	
	if ($month1 != 12 && !FLExempt($empCode) && $force_leave_days != 0){			
		/*if ($VLBalance - $force_leave_days >= 0){
			#$VLBalance -= $force_leave_days;
			$VLEnding -= $force_leave_days;		
			$VLULeave += $force_leave_days;		
		}
		else{
			$VLNLeave += $force_leave_days - floor($VLEnding);
			#$VLNLeave += $r[0] - floor($VLBalance);
			$VLULeave += $force_leave_days;
		}*/		
		if ($VLEnding - $force_leave_days >= 0){
			#$VLBalance -= $force_leave_days;
			$VLEnding -= $force_leave_days;		
			$VLULeave += $force_leave_days;		
		}
		else{
			$VLNLeave += $force_leave_days - floor($VLEnding);
			#$VLNLeave += $r[0] - floor($VLBalance);
			$VLULeave += $force_leave_days;
		}		
	}		
				
	#echo "<br/>GEN.INFO<br/>BEFORE DECEMBER DEDUCTIONS<br/>";
	#echo "vl_temp: $vl_temp			|| sl_temp: $sl_temp <br/>";
	#echo "VLULeave: $VLULeave		|| SLULeave: $SLULeave<br/>";
	#echo "VLNTardy: $VLNTardy		|| SLNTardy: $SLNTardy<br/>";
	#echo "VLUTardy: $VLUTardy		|| SLUTardy: $SLUTardy<br/>";
	#echo "VLNLeave: $VLNLeave		|| SLNLeave: $SLNLeave<br/>";
	#echo "VLEarned: $VLEarned		|| SLEarned: $SLEarned<br/>";
	#echo "VLEnding: $VLEnding		|| SLEnding: $SLEnding<br/>";
	#echo "EMPCODE: $empCode | TOTAL_KALTAS: $total_kaltas | EXCESS: $excess | TALLY: $tally | ABSENT: $absent <br/>";
	
	if ($month1 == 12 && !FLExempt($empCode)){
	#echo "$month1 == 12 && !FLExempt($empCode)<br/>";
	#echo "VLULeave: $VLULeave <br/>";
	#$to_year = $_POST['to_year'];
			
			$flcount = 0;
			$count = 0;
			//OBTAINS CORRECT REMAINING FL FOR DEC.
			//UPDATES FORCE LEAVE BALANCE, APPROVED OR DISAPPROVED VACATION LEAVE
			$textT = "SELECT fldFromDate, fldToDate FROM tblEmpLeave 
			WHERE fldEmpCode LIKE '$empCode' AND fldLeaveTypeID = 1 
			AND fldFromDate >= '$year2-01-01' AND fldFromDate <= '$year2-11-30' 
			AND ((fldAppFtL='1' AND fldFlagCaRe='0') OR (fldAppFtL='0' AND fldAppFtLDate!='0000-00-00 00:00:00' AND fldFlagCaRe='0'))";echo "<br/>";
			$queryT = mysql_query($textT) or die("19) ".mysql_error());
			$rawDate = array();
			$index = 0;
			while($resultT = mysql_fetch_array($queryT)){
				$date1 = $resultT['fldFromDate'];
				$date2 = $resultT['fldToDate'];
				
				if($date1 == $date2){
					$rawDate[$index++] = $date1;			
				} else {				
						$dateA1 = explode("-",$date1);
						$dateA2 = explode("-",$date2);
						$date1 = mktime(0,0,0,$dateA1[1],$dateA1[2],$dateA1[0]);
						$date2 = mktime(0,0,0,$dateA2[1],$dateA2[2],$dateA2[0]);
						$diff = $date2 - $date1;
						$diff = ($diff/3600)/24;
						
						for($ab = 0;$ab <= $diff; $ab++){
							$toadd = "+".$ab." day";
							$newdate = strtotime($toadd, $date1);
							$date = date("Y-m-d",$newdate);
							
							if(!in_array($date,$rawDate)) $rawDate[$index++] = $date;
						}					
				}
			}
			$rawDate = array_unique($rawDate);
			#echo "<strong>Approved/disapproved vacation leave JAN-NOV</strong><br/>";
			#print_r($rawDate); echo "<br/>";
			
			//LEAVE CHECKING
			foreach($rawDate as $tempDate){			
				if(isHoliday($tempDate)){ #date of approved/disapproved leave is a holiday		
					if(holidayTime($tempDate) == 3){ #whole day holiday
						if(LeaveTime($tempDate, $empCode) == "(AM)" || LeaveTime($tempDate, $empCode) == "(PM)"){
							$count += 0.5;
							#echo "WD Holiday, HF Leave $tempDate COUNT: $count <br/>";
						} else {
							$count += 1;
							#echo "WD Holiday, WD Leave $tempDate COUNT: $count <br/>";
						}
					} else { #half day holiday
						if(holidayTime($tempDate) == 1 && LeaveTime($tempDate, $empCode) == "(PM)"){
							$count += 0.5;
							#echo "HD Holiday, PM Leave $tempDate COUNT: $count <br/>";
						} else if (holidayTime($tempDate) == 2 && LeaveTime($tempDate, $empCode) == "(AM)"){
							$count += 0.5;
							#echo "HD Holiday, AM Leave $tempDate COUNT: $count <br/>";
						} else if ((holidayTime($tempDate) == 1 || holidayTime($tempDate) == 2) && LeaveTime($date, $empCode) == ""){
							$count += 0.5;
							#echo "HD Holiday, WD Leave $tempDate COUNT: $count <br/>";
						} else continue;					
					}
				} else { #regular day
					if(isAbsent($tempDate, getOptID($tempDate), $empCode) == 0.5){
						$count += 0.5;
						#echo "absent 0.5 $tempDate COUNT: $count <br/>";
					} else if(isAbsent($tempDate, getOptID($tempDate), $empCode) == 1){
						$count++;
						#echo "absent 1 $tempDate COUNT: $count <br/>";
					} else if(isAbsent($tempDate, getOptID($tempDate), $empCode) == 0) continue;
				}
			}
			#echo "COUNT: $count <br/><br/>";
									
		
			//COUNTS FORCE LEAVES, APPROVED FORCE LEAVE
			$textT = "SELECT fldFromDate, fldToDate FROM tblEmpLeave 
			WHERE fldEmpCode LIKE '$empCode' 
			AND fldLeaveTypeID = 6 
			AND fldFromDate >= '$year2-01-01' 
			AND fldToDate <= '$year2-11-30' 
			AND fldAppFtL='1' AND fldFlagCaRe='0'";	#echo "<br/>";			
			$queryT = mysql_query($textT) or die("20) ".mysql_error());
			$rawDate = array();
			$index = 0;
			while($resultT = mysql_fetch_array($queryT)){
				$date1 = $resultT['fldFromDate'];
				$date2 = $resultT['fldToDate'];
				
				if($date1 == $date2){
					$rawDate[$index++] = $date1;			
				} else {				
						$dateA1 = explode("-",$date1);
						$dateA2 = explode("-",$date2);
						$date1 = mktime(0,0,0,$dateA1[1],$dateA1[2],$dateA1[0]);
						$date2 = mktime(0,0,0,$dateA2[1],$dateA2[2],$dateA2[0]);
						$diff = $date2 - $date1;
						$diff = ($diff/3600)/24;
						
						for($ab = 0;$ab <= $diff; $ab++){
							$toadd = "+".$ab." day";
							$newdate = strtotime($toadd, $date1);
							$date = date("Y-m-d",$newdate);
							
							if(!in_array($date,$rawDate)) $rawDate[$index++] = $date;
						}					
				}
			}
			$rawDate = array_unique($rawDate);	
			#echo "<strong>Approved force leave JAN-NOV </strong><br/>";
			#print_r($rawDate);  echo "<br/>";	
			
			//LEAVE CHECKING
			foreach($rawDate as $tempDate){			
				if(isHoliday($tempDate)){ #date of approved/disapproved leave is a holiday		
					if(holidayTime($tempDate) == 3){ #whole day holiday
						if(LeaveTime($tempDate, $empCode) == "(AM)" || LeaveTime($tempDate, $empCode) == "(PM)"){
							$count += 0.5;
							#echo "WD Holiday, HF Leave $tempDate COUNT: $count <br/>";
						} else {
							$count += 1;
							#echo "WD Holiday, WD Leave $tempDate COUNT: $count <br/>";
						}
					} else { #half day holiday
						if(holidayTime($tempDate) == 1 && LeaveTime($tempDate, $empCode) == "(PM)"){
							$count += 0.5;
							#echo "HD Holiday, PM Leave $tempDate COUNT: $count <br/>";
						} else if (holidayTime($tempDate) == 2 && LeaveTime($tempDate, $empCode) == "(AM)"){
							$count += 0.5;
							#echo "HD Holiday, AM Leave $tempDate COUNT: $count <br/>";
						} else if ((holidayTime($tempDate) == 1 || holidayTime($tempDate) == 2) && LeaveTime($date, $empCode) == ""){
							$count += 0.5;
							#echo "HD Holiday, WD Leave $tempDate COUNT: $count <br/>";
						} else continue;					
					}
				} else { #regular day
					if(isAbsent($tempDate, getOptID($tempDate), $empCode) == 0.5){
						$count += 0.5;
						#echo "absent 0.5 $tempDate COUNT: $count <br/>";
					} else if(isAbsent($tempDate, getOptID($tempDate), $empCode) == 1){
						$count++;
						#echo "absent 1 $tempDate COUNT: $count <br/>";
					} else if(isAbsent($tempDate, getOptID($tempDate), $empCode) == 0) continue;
				}
			}
			#echo "COUNT: $count <br/><br/>";
		
			//COUNTS FORCE LEAVES, DISAPPROVED FORCE LEAVE
			$textT = "SELECT fldFromDate, fldToDate FROM tblEmpLeave 
			WHERE fldEmpCode LIKE '$empCode' 
			AND fldLeaveTypeID = 6 
			AND fldFromDate >= '$year2-01-01' 
			AND fldToDate <= '$year2-11-30' 
			AND fldAppFtL='0' AND fldAppFtLDate!='0000-00-00 00:00:00' AND fldFlagCaRe='0'";echo "<br/>";		
			$queryT = mysql_query($textT) or die("21) ".mysql_error());
			$rawDate = array();
			$index = 0;
			while($resultT = mysql_fetch_array($queryT)){
				$date1 = $resultT['fldFromDate'];
				$date2 = $resultT['fldToDate'];
							
				if($date1 == $date2){
					$rawDate[$index++] = $date1;			
				} else {				
						$dateA1 = explode("-",$date1);
						$dateA2 = explode("-",$date2);
						$date1 = mktime(0,0,0,$dateA1[1],$dateA1[2],$dateA1[0]);
						$date2 = mktime(0,0,0,$dateA2[1],$dateA2[2],$dateA2[0]);
						$diff = $date2 - $date1;
						$diff = ($diff/3600)/24;
						
						for($ab = 0;$ab <= $diff; $ab++){
							$toadd = "+".$ab." day";
							$newdate = strtotime($toadd, $date1);
							$date = date("Y-m-d",$newdate);
							
							if(!in_array($date,$rawDate)) $rawDate[$index++] = $date;
						}					
				}
			}
			$rawDate = array_unique($rawDate);
			#echo "<strong>Disapproved force leave JAN-NOV </strong><br/>";
			#print_r($rawDate); echo "<br/>";
			#echo "<strong>$flcount += count($rawDate) </strong><br/>";	
			//$flcount += count($rawDate);		#feb.27, 2013
			#echo "<strong>flcount: $flcount </strong><br/>";
			
			//LEAVE CHECKING
			foreach($rawDate as $tempDate){			
				if(isHoliday($tempDate)){ #date of approved/disapproved leave is a holiday		
					if(holidayTime($tempDate) == 3){ #whole day holiday
						if(LeaveTime($tempDate, $empCode) == "(AM)" || LeaveTime($tempDate, $empCode) == "(PM)"){
							$count += 0.5;
							#echo "WD Holiday, HF Leave $tempDate COUNT: $count <br/>";
						} else {
							$count += 1;
							#echo "WD Holiday, WD Leave $tempDate COUNT: $count <br/>";
						}
					} else { #half day holiday
						if(holidayTime($tempDate) == 1 && LeaveTime($tempDate, $empCode) == "(PM)"){
							$count += 0.5;
							#echo "HD Holiday, PM Leave $tempDate COUNT: $count <br/>";
						} else if (holidayTime($tempDate) == 2 && LeaveTime($tempDate, $empCode) == "(AM)"){
							$count += 0.5;
							#echo "HD Holiday, AM Leave $tempDate COUNT: $count <br/>";
						} else if ((holidayTime($tempDate) == 1 || holidayTime($tempDate) == 2) && LeaveTime($date, $empCode) == ""){
							$count += 0.5;
							#echo "HD Holiday, WD Leave $tempDate COUNT: $count <br/>";
						} else continue;					
					}
				} else { #regular day
					if(isAbsent($tempDate, getOptID($tempDate), $empCode) == 0.5){
						$count += 0.5;
						#echo "absent 0.5 $tempDate COUNT: $count <br/>";
					} else if(isAbsent($tempDate, getOptID($tempDate), $empCode) == 1){
						$count++;
						#echo "absent 1 $tempDate COUNT: $count <br/>";
					} else if(isAbsent($tempDate, getOptID($tempDate), $empCode) == 0) continue;
				}
			}
		#echo "COUNT: $count <br/><br/>";
			
		$flcount = $count;
			
		$FLPrevBalance = 0;
		if ($year2 == 2008) {
		//UPDATES FORCE LEAVES BEFORE DEDUCTION	
		$queryT = mysql_query("select * from tblLeaveBalance where fldEmpCode='$empCode' AND fldLeaveTypeID='6' order by fldDate desc");
			$rowT = mysql_fetch_array($queryT);
			$FLPrevBalance = $rowT['fldBalance'];
		} else {
			$FLPrevBalance = 5 - $flcount;	
			#echo "<strong>$FLPrevBalance = 5 - $flcount; || FLPrevBalance = 5 - $flcount;</strong><br/>";
			#if($FLPrevBalance <= 0) $FLPrevBalance = 0;
		} 
		#echo "<strong>FLPrevBalance: $FLPrevBalance </strong><br/><br/>";	
		
		$flbalancededuction = 0;
		
		//UPDATES FORCE LEAVE BALANCE, APPROVED OR DISAPPROVED VACATION LEAVE
		$textT = "SELECT fldFromDate, fldToDate FROM tblEmpLeave 
		WHERE fldEmpCode LIKE '$empCode' AND fldLeaveTypeID = 1 
		AND fldFromDate >= '$year2-12-01' AND fldFromDate <= '$year2-12-31' 
		AND ((fldAppFtL='1' AND fldFlagCaRe='0') OR (fldAppFtL='0' 
		AND fldAppFtLDate!='0000-00-00 00:00:00' AND fldFlagCaRe='0'))";echo "<br/>";
		$queryT = mysql_query($textT) or die("22) ".mysql_error());
		$rawDate = array();
		$index = 0;
		while($resultT = mysql_fetch_array($queryT)){
			$date1 = $resultT['fldFromDate'];
			$date2 = $resultT['fldToDate'];
			
			if($date1 == $date2){
				$rawDate[$index++] = $date1;			
			} else {				
					$dateA1 = explode("-",$date1);
					$dateA2 = explode("-",$date2);
					$date1 = mktime(0,0,0,$dateA1[1],$dateA1[2],$dateA1[0]);
					$date2 = mktime(0,0,0,$dateA2[1],$dateA2[2],$dateA2[0]);
					$diff = $date2 - $date1;
					$diff = ($diff/3600)/24;
					
					for($ab = 0;$ab <= $diff; $ab++){
						$toadd = "+".$ab." day";
						$newdate = strtotime($toadd, $date1);
						$date = date("Y-m-d",$newdate);
						
						if(!in_array($date,$rawDate)) $rawDate[$index++] = $date;
					}					
			}
		}
		$rawDate = array_unique($rawDate);
		#echo "<strong>Approved/disapproved vacation leave DEC </strong><br/>";
		#print_r($rawDate); echo "<br/>";
		$count = 0;
		#echo "<strong>COUNT: $count </strong><br/><br/>";
		//LEAVE CHECKING
		foreach($rawDate as $tempDate){			
			if(isHoliday($tempDate)){ #date of approved/disapproved leave is a holiday		
				if(holidayTime($tempDate) == 3){ #whole day holiday
					if(LeaveTime($tempDate, $empCode) == "(AM)" || LeaveTime($tempDate, $empCode) == "(PM)"){
						$count += 0.5;
						$flbalancededuction += 0.5;
						#echo "WD Holiday, HF Leave $tempDate COUNT: $count <br/>";
					} else {
						$count += 1;
						$flbalancededuction += 1;
						#echo "WD Holiday, WD Leave $tempDate COUNT: $count <br/>";
					}
				} else { #half day holiday
					if(holidayTime($tempDate) == 1 && LeaveTime($tempDate, $empCode) == "(PM)"){
						$count += 0.5;
						$flbalancededuction += 0.5;
						#echo "HD Holiday, PM Leave $tempDate COUNT: $count <br/>";
					} else if (holidayTime($tempDate) == 2 && LeaveTime($tempDate, $empCode) == "(AM)"){
						$count += 0.5;
						$flbalancededuction += 0.5;
						#echo "HD Holiday, AM Leave $tempDate COUNT: $count <br/>";
					} else if ((holidayTime($tempDate) == 1 || holidayTime($tempDate) == 2) && LeaveTime($date, $empCode) == ""){
						$count += 0.5;
						$flbalancededuction += 0.5;
						#echo "HD Holiday, WD Leave $tempDate COUNT: $count <br/>";
					} else continue;					
				}
			} else { #regular day
				if(isAbsent($tempDate, getOptID($tempDate), $empCode) == 0.5){
					$count += 0.5;
					$flbalancededuction += 0.5;
					#echo "absent 0.5 $tempDate COUNT: $count <br/>";
				} else if(isAbsent($tempDate, getOptID($tempDate), $empCode) == 1){
					$count++;
					$flbalancededuction += 1;
					#echo "absent 1 $tempDate COUNT: $count <br/>";
				} else if(isAbsent($tempDate, getOptID($tempDate), $empCode) == 0) continue;
			}
		}						
		//$count = count($rawDate);	#Feb. 2013
		#echo "<strong>APPROVED OR DISAPPROVED VL DEC ($count)</strong>: ";
		#print_r($rawDate); echo "<br/>";
		#echo "COUNT: $count <br/><br/>";
		
		//UPDATES CURRENT FORCE LEAVE BALANCE
		$FLPrevBalance -= $count;
		#echo "<strong>$FLPrevBalance -= $count || FLPrevBalance -= count </strong><br/><br/>";
		
		$flbalancededuction = 0;
		//COUNTS FORCE LEAVES, APPROVED FORCE LEAVE
		$textT = "SELECT fldFromDate, fldToDate FROM tblEmpLeave 
		WHERE fldEmpCode LIKE '$empCode' 
		AND fldLeaveTypeID = 6 
		AND fldFromDate >= '$year2-12-01' 
		AND fldToDate <= '$year2-12-31' 
		AND fldAppFtL='1' AND fldFlagCaRe='0'";echo "<br/>";
		$queryT = mysql_query($textT) or die("23) ".mysql_error());
		$rawDate = array();
		$index = 0;
		while($resultT = mysql_fetch_array($queryT)){
			$date1 = $resultT['fldFromDate'];
			$date2 = $resultT['fldToDate'];
			
			if($date1 == $date2){
				$rawDate[$index++] = $date1;			
			} else {				
					$dateA1 = explode("-",$date1);
					$dateA2 = explode("-",$date2);
					$date1 = mktime(0,0,0,$dateA1[1],$dateA1[2],$dateA1[0]);
					$date2 = mktime(0,0,0,$dateA2[1],$dateA2[2],$dateA2[0]);
					$diff = $date2 - $date1;
					$diff = ($diff/3600)/24;
					
					for($ab = 0;$ab <= $diff; $ab++){
						$toadd = "+".$ab." day";
						$newdate = strtotime($toadd, $date1);
						$date = date("Y-m-d",$newdate);
						
						if(!in_array($date,$rawDate)) $rawDate[$index++] = $date;
					}					
			}
		}
		$count = 0;
		$rawDate = array_unique($rawDate);
		#echo "<strong>Approved/disapproved vacation leave DEC </strong><br/>";
		#print_r($rawDate); echo "<br/>";
		
		//LEAVE CHECKING
		foreach($rawDate as $tempDate){			
			if(isHoliday($tempDate)){ #date of approved/disapproved leave is a holiday		
				if(holidayTime($tempDate) == 3){ #whole day holiday
					if(LeaveTime($tempDate, $empCode) == "(AM)" || LeaveTime($tempDate, $empCode) == "(PM)"){
						$count += 0.5;
						$flbalancededuction += 0.5;
						#echo "WD Holiday, HF Leave $tempDate COUNT: $count <br/>";
					} else {
						$count += 1;
						$flbalancededuction += 1;
						#echo "WD Holiday, WD Leave $tempDate COUNT: $count <br/>";
					}
				} else { #half day holiday
					if(holidayTime($tempDate) == 1 && LeaveTime($tempDate, $empCode) == "(PM)"){
						$count += 0.5;
						$flbalancededuction += 0.5;
						#echo "HD Holiday, PM Leave $tempDate COUNT: $count <br/>";
					} else if (holidayTime($tempDate) == 2 && LeaveTime($tempDate, $empCode) == "(AM)"){
						$count += 0.5;
						$flbalancededuction += 0.5;
						#echo "HD Holiday, AM Leave $tempDate COUNT: $count <br/>";
					} else if ((holidayTime($tempDate) == 1 || holidayTime($tempDate) == 2) && LeaveTime($date, $empCode) == ""){
						$count += 0.5;
						$flbalancededuction += 0.5;
						#echo "HD Holiday, WD Leave $tempDate COUNT: $count <br/>";
					} else continue;					
				}
			} else { #regular day
				if(isAbsent($tempDate, getOptID($tempDate), $empCode) == 0.5){
					$count += 0.5;
					$flbalancededuction += 0.5;
					#echo "absent 0.5 $tempDate COUNT: $count <br/>";
				} else if(isAbsent($tempDate, getOptID($tempDate), $empCode) == 1){
					$count++;
					$flbalancededuction += 1;
					#echo "absent 1 $tempDate COUNT: $count <br/>";
				} else if(isAbsent($tempDate, getOptID($tempDate), $empCode) == 0) continue;
			}
		}
		#echo "COUNT: $count <br/><br/>";
		
		//$count = count($rawDate);	#feb. 27, 2013
		#echo "<strong>APPROVED FL ($count): </strong><br/>";
		#print_r($rawDate); echo "<br/>";
		#echo "<strong>COUNT: $count </strong><br/><br/>";
		
		if($count > 0){
			#echo "PRE: VLULeave: $VLULeave & VLEnding: $VLEnding <br/>";
			#echo "1APPROVED: $count <br/>";	
			if(($VLEnding - $count) > 0){
				$VLULeave += $count;
				$VLEnding -= $count;		
			} else {
				$VLNLeave += $count;
				$VLULeave += $count;				
			}	
			#echo "1VLULeave: $VLULeave & VLEnding: $VLEnding <br/>";
			$FLPrevBalance -= $count;				
		}
		
		#echo "<strong>AFTER APPROVED FL) vlending: $VLEnding vltemp: $vl_temp vlused:$VLULeave FLPrevBalance: $FLPrevBalance :: after approv/disapprov VL, approv. FL</strong><br/>";
		
		$rawDate = "";
		$index = 0;
		$count = 0;			
		$flbalancededuction = 0;	
		//COUNTS FORCE LEAVES, DISAPPROVED FORCE LEAVE
		$textT = "SELECT fldFromDate, fldToDate FROM tblEmpLeave 
		WHERE fldEmpCode LIKE '$empCode' 
		AND fldLeaveTypeID = 6 
		AND fldFromDate >= '$year2-12-01' 
		AND fldToDate <= '$year2-12-31' 
		AND fldAppFtL='0' AND fldAppFtLDate!='0000-00-00 00:00:00' AND fldFlagCaRe='0'";
		$queryT = mysql_query($textT) or die("24) ".mysql_error());
		$rawDate = array();
		$index = 0;
		while($resultT = mysql_fetch_array($queryT)){
			$date1 = $resultT['fldFromDate'];
			$date2 = $resultT['fldToDate'];
						
			if($date1 == $date2){
				//if(!isHoliday($date1) && !isDayOff($opt_id, $date)) $rawDate[$index++] = $date1;			
				$rawDate[$index++] = $date1;
			} else {				
					$dateA1 = explode("-",$date1);
					$dateA2 = explode("-",$date2);
					$date1 = mktime(0,0,0,$dateA1[1],$dateA1[2],$dateA1[0]);
					$date2 = mktime(0,0,0,$dateA2[1],$dateA2[2],$dateA2[0]);
					$diff = $date2 - $date1;
					$diff = ($diff/3600)/24;
					
					for($ab = 0;$ab <= $diff; $ab++){
						$toadd = "+".$ab." day";
						$newdate = strtotime($toadd, $date1);
						$date = date("Y-m-d",$newdate);
						
						$temp_opt_id = getOptID($date);
						if(!in_array($date,$rawDate) && !isDayOff($temp_opt_id, $date)) $rawDate[$index++] = $date;
					}					
			}
		}
		$rawDate = array_unique($rawDate);
		//$count += count($rawDate);
		
		#echo "<br/><strong>DISAPPROVED FORCE LEAVE DEC</strong><br/>";
		#print_r($rawDate); echo "<br/>";
		
		//LEAVE CHECKING
		foreach($rawDate as $tempDate){			
			if(isHoliday($tempDate)){ #date of approved/disapproved leave is a holiday		
				if(holidayTime($tempDate) == 3){ #whole day holiday
					if(LeaveTime($tempDate, $empCode) == "(AM)" || LeaveTime($tempDate, $empCode) == "(PM)"){
						$count += 0.5;
						$flbalancededuction += 0.5;
						#echo "WD Holiday, HF Leave $tempDate COUNT: $count <br/>";
					} else {
						$count += 1;
						$flbalancededuction += 0.5;
						#echo "WD Holiday, WD Leave $tempDate COUNT: $count <br/>";
					}
				} else { #half day holiday
					if(holidayTime($tempDate) == 1 && LeaveTime($tempDate, $empCode) == "(PM)"){
						$count += 0.5;
						$flbalancededuction += 0.5;
						#echo "HD Holiday, PM Leave $tempDate COUNT: $count <br/>";
					} else if (holidayTime($tempDate) == 2 && LeaveTime($tempDate, $empCode) == "(AM)"){
						$count += 0.5;
						$flbalancededuction += 0.5;
						#echo "HD Holiday, AM Leave $tempDate COUNT: $count <br/>";
					} else if ((holidayTime($tempDate) == 1 || holidayTime($tempDate) == 2) && LeaveTime($date, $empCode) == ""){
						$count += 0.5;
						$flbalancededuction += 0.5;
						#echo "HD Holiday, WD Leave $tempDate COUNT: $count <br/>";
					} else continue;					
				}
			} else { #regular day
				if(isAbsent($tempDate, getOptID($tempDate), $empCode) == 0.5){
					$count += 0.5;
					$flbalancededuction += 0.5;
					#echo "absent 0.5 $tempDate COUNT: $count <br/>";
				} else if(isAbsent($tempDate, getOptID($tempDate), $empCode) == 1){
					$count++;
					$flbalancededuction += 1;
					#echo "absent 1 $tempDate COUNT: $count <br/>";
				} else if(isAbsent($tempDate, getOptID($tempDate), $empCode) == 0) $count++;
			}
		}
		#echo "COUNT: $count <br/><br/>";		
		
		//$count += count($rawDate); #feb.18,2013
		
		#echo "DISAPPROVED FL(".count($rawDate)."): ";
		#print_r($rawDate);
		#echo "<br/>";
		#if($count > 0)
		$FLPrevBalance -= $count;
				
		//$FLBalance = $FLPrevBalance - $count;	 #feb. 27, 2013
		#echo "<strong>$FLBalance = $FLPrevBalance - $count :: FLBalance = FLPrevBalance - count</strong><br/>";
		$FLBalance = $FLPrevBalance;
		#echo "<strong>$FLBalance = 5 - $FLPrevBalance; :: FLBalance = 5 - FLPrevBalance;</strong><br/>";
				
		
		if($VLEnding > 10){
			if($FLBalance <= 0){
				$FLBalance = 0;					
			} else {								
				if(($VLEnding - $FLBalance) < 0){
					#$VLEnding = $VLEnding - floor($VLEnding);
					#$VLNLeave = abs(floor($VLEnding - $FLBalance));
										
					#$VLEnding = $VLEnding - $VLUTardy; 			#$VLEnding = $VLEnding + ($VLEarned - $VLUTardy);
					
					$VLNLeave = $FLBalance - floor($VLEnding);
					$VLEnding = $VLEnding - floor($VLEnding);
					
				} else {
					$VLULeave += $FLBalance;
					$VLEnding -= $FLBalance;	
				}
				#echo "2VLULeave: $VLULeave & VLEnding: $VLEnding <br/>";							
			}
		}						
		
		#echo "AFTER DISAPPROV FL) vlending: $VLEnding vltemp: $vl_temp vlused:$VLULeave FLPrevBalance: $FLPrevBalance :: after disapprov FL<br/>";
		
		#CORRECTS PRIVILEGE LEAVES
		#selects latest privilege leave balance
		$quickSQL = mysql_query("SELECT fldLeaveBalID FROM tblLeaveBalance WHERE fldEmpCode LIKE '$empCode' AND fldLeaveTypeID = 3 ORDER BY fldLeaveBalID DESC") or die("25) ".mysql_error());
		$quickResult = mysql_fetch_array($quickSQL);
		$fldLeaveBalID = $quickResult[0];		
		#resets previous privilege balance to zero
		$quickSQL = mysql_query("UPDATE tblLeaveBalance SET fldBalance=0 WHERE fldLeaveBalID = $fldLeaveBalID") or die("55) ".mysql_error());
		#stores the new privilege leave balance
		mysql_query("INSERT into tblLeaveBalance (fldLeaveTypeID, fldEmpCode, fldPrevBalance, fldBalance, fldNegative, fldDate) VALUES ('3', '$empCode', 0, 3, 0, '$curr_date')") or die("56) ".mysql_error());
		
		mysql_query("INSERT into tblLeaveBalance (fldLeaveTypeID, fldEmpCode, fldPrevBalance, fldBalance, fldNegative, fldDate) VALUES ('6', '$empCode', $FLPrevBalance, 5, 0, '$curr_date')") or die("57) ".mysql_error());
		//END OF UPDATING FORCE LEAVE BALANCE																						
	}
		
		 
	#ADDITION OF VLEarned subtraced by VLUTardy
	$VLEnding += $VLEarned;
	
	#DEDUCTION OF VLUTardy or kaltas_tardef
	$VLDeduct=$kaltas_tardef+$UADeduct;
	//$VLEnding -= $kaltas_tardef;
	if ($VLEnding >= 0){
		#$VLUTardy = $total_kaltas;
		$VLUTardy = $kaltas_tardef;
		$VLNTardy = 0;
	} else {
		$VLNTardy = abs($VLEnding);
		$VLUTardy = $kaltas_tardef;
		$VLEnding = 0;	
		#$VLNTardy = abs($VLEnding) + $VLBegin;
		#$VLEnding = $VLBegin;	
		#$VLUTardy already set
		#$VLUTardy = $kaltas_tardef;
	}
	#END OF DEDUCTION OF VLUTardy
	
	//DEDUCT MONETIZATION TO VL AND SL IF PRESENT
	$monetizeVL = 0;
	$monetizeSL = 0;
	$query = "SELECT a.* FROM tblmonetization a 
	WHERE EXISTS (
		SELECT b.fldEmpLeaveID FROM tblEmpLeave b WHERE b.fldEmpCode LIKE '$empCode' 
		AND MONTH(b.fldAppDate) = $month1 
		AND YEAR(b.fldAppDate) = $year2 
		AND a.fldEmpLeaveID = b.fldEmpLeaveID 
		AND b.fldFlagCaRe = 0
	)";
	$sql = mysql_query($query) or die("29) "."ERROR on 'Deduction of monetized leaves': ".mysql_error());
	while($result = mysql_fetch_array($sql)){
		$monetizeVL += $result['fldDeductToVL'];
		$monetizeSL += $result['fldDeductToSL'];
	}
	$VLEnding -= $monetizeVL;
	$SLEnding -= $monetizeSL;
	//END OF DEDUCTION OF MONETIZATIONS	
	
	//start: maine08082017 inserting of unauthorized absence or unfiled leave/absence to tblEmpLeave
	for ($i=$day1; $i<=$day2; $i++){
		$c = getDTR($empCode, date("Y-m-d", mktime(0,0,0,$month1,$i,$year2))); 
		$q = mysql_query("select * from tblEmpDTR where fldEmpDTRID='$c'");
		$r = mysql_fetch_array($q);
		$month=date("m");
		$year=date("Y");
		$dates = date("Y-m-d", mktime(0,0,0,$month1,$i,$year2));
		$lv->getEmpLeaves($empCode, $dates);
		$hol->getHolidays($dates);
        $opt_id = getOptID($dates);
	if (isAbsent($dates, getOptID($dates), $empCode) != 0 && !isDayOff($opt_id, $dates) && !isExempted($empCode) 
		   && getFirstDay($empCode) <= $dates && !$isSWAllowed && !$isWFHAllowed) {//!getOptID($dates)==5){
			if (WorkSuspended($dates) == "no" && !isHoliday($dates) && !isOnLeave2($dates, $empCode) && !isDayOff($opt_id, $dates)
			&& isOnTO($dates, $empCode) == 0 && isOnCTO($dates, $empCode) == 0){				
				echo $dates.") UA Leave: ".$ua."<br/>";							
				$leave_id=14;
				$appftl=1;
				$dur=1;
				if(isset($dates)){		
						mysql_query("insert into tblEmpLeave (fldEmpCode, fldLeaveTypeID, fldAppDate, fldFromDate, 
						fldToDate, fldAM_PM_WD, fldAppFtl, fldAppFtLDate)
						values('$empCode', '$leave_id', '$dates', '$dates', '$dates', '$dur', '$appftl', '$curr_date')");
				}				
			}
	   }	
	}
	//end: maine08082017 inserting of unauthorized absence or unfiled leave/absence to tblEmpLeave
	
	//start: maine08082017 computation of lwp, lwop, ua
	//inserting of all filed leaves from tblempleave to payroll.tblLWOP	
	$UANoPay=$ua;
	$query = "insert into tblSummary 
	(fldEmpCode, fldMonth, fldYear, fldTardyUnder, fldExcessHours, fldTallyLates, fldTallyAbsent, fldVLUsedTardy, 
	fldVLUsedLeave, fldVLNoPayTardy, fldVLNoPayLeave, fldVLEarned, fldVLBalance, fldSLUsed, fldSLNoPay, fldSLEarned, 
	fldSLBalance, fldUANoPay, fldDateProcessed) 
	values 
	('$empCode', '$month1', '$year2', '$total_kaltas', '$excess', $tally_tardy, $absent, $VLUTardy, 
	$VLULeave, $VLNTardy, $VLNLeave, $VLEarned, $VLEnding, $SLULeave, $SLNLeave, $SLEarned, 
	$SLEnding, $UANoPay, '$curr_date')";
	mysql_query($query) or die("61) ".mysql_error());
	
	$date3=$year2."-".$month1."-01";
	$date4=$year2."-".$month1."-31";
	include("loadBasic.php");	 
	$query1 = mysql_query($sql) or trigger_error(mysql_error()." in ".$query1);
	while ($r3=mysql_fetch_array($query1)){
		//$empsal=$r3['m_basic'];				
		$empsal=$r3['salary'];
		//echo "$date1=$date2=$empsal=$empCode"; die();
		$query = "select fldEmpLeaveID, fldLeaveTypeID, fldEmpCode, fldAppDate, fldFromDate, fldToDate, 
		fldAM_PM_WD from tblEmpLeave where fldEmpCode='$empCode' and fldAppFtLDate is not null and fldFlagCaRe!=1 and fldFlagCaRe!=2 and fldAppFtL=1 and fldEmpLeaveID not in
			 (select fldEmpLeaveID from payroll.tblLWOP) and month(fldFromDate)='$month1' and year(fldFromDate)='$year2' order by fldEmpLeaveID asc";
		//echo "query: ".$query."<br/>";

		$lwopdeduction = 0;
		$q4=mysql_query($query);		
		while($r4=mysql_fetch_array($q4)){
			$empleaveid = $r4['fldEmpLeaveID'];
			$q5 = mysql_query("select * from tblEmpLeave where fldEmpLeaveID='$empleaveid'");
			$empCode = $r4['fldEmpCode'];
			$empleaveid=$r4['fldEmpLeaveID'];
			$leave_id=$r4['fldLeaveTypeID'];
			$date = date("F j, Y", strtotime($r4['fldAppDate']));
			$datefiled=$r4['fldAppDate'];
			$from=$r4['fldFromDate'];
			$to=$r4['fldToDate'];
			$dur = $r4['fldAM_PM_WD'];				
			$days = getDays($from, $to, ComputeDateDifference($from, $to) + 1);
			$duration = duration($from, $to);
			if (($dur == 2 || $dur == 3) && $days == 1){
				$days = 0.5;
			}			
			$ret =  DeductLBalDTRprocess($empleaveid, $leave_id, $empCode, $days, $datefiled, $dur);
			parse_str($ret);	
			if ($leave_id == 7 || $leave_id == 12){
				$weekends = " (Includes Weekends)";
				$days = ComputeDateDifference($from, $to) + 1;
			}
			else{
				$weekends = "";
			}
			$dayslwop = 0;
			$dayslwp = 0;
			if ($leave_id == 2){				
				$dayslwop = $neg;
				$dayslwp = $pos;
			}
			else if ($leave_id == 1){
				$dayslwop = $neg;
				$dayslwp = $pos;
			}
			else if($leave_id==14){
				$daysul=1;
				$dayslwp=0;
				$dayslwop=0;
			}
			else if($leave_id==15){
				$daysul=0;
				$dayslwp=0;
				$dayslwop=$pos;
			}
			else{
				$dayslwp = $days;				 
			}
			$deduct=($empsal / 22) * ($dayslwop + $daysul);
			$deduction = round($deduct, 2);
			$deductstat=0;
			if(isset($q5)){
				if ($dayslwop!=0 || $daysul!=0){		
					mysql_query("insert into payroll.tblLWOP 
					(fldEmpLeaveID, fldLeaveTypeID, fldEmpCode, fldDateFiled, fldFromDate, fldToDate, fldDaysLWP, fldDaysLWOP, 
					fldDaysUL, fldDeduction, fldMonthDTR, fldYearDTR, fldDeductStatus) 
					values('$empleaveid', '$leave_id', '$empCode', '$datefiled', '$from','$to',$dayslwp,$dayslwop,
					$daysul,$deduction,'$month1','$year2','$deductstat')") or die("65) ".mysql_error());				
				}
			}
			$lwopdeduction+=$deduction;
			mysql_query("update payroll.tblEmpInfo set LWOP=$lwopdeduction where fldEmpCode='$empCode'") or die("66) ".mysql_error());
		}
	}		
	//echo $month1;
	//echo $year2;
	//end: maine08082017 computation of lwp, lwop, ua
	//inserting of all filed leaves from tblempleave to payroll.tblLWOP
	
	//UPDATES VACATION LEAVE BALANCES
	$query = mysql_query("select * from tblLeaveBalance where fldEmpCode='$empCode' AND fldLeaveTypeID='1' order by fldDate desc") or die("31) ".mysql_error());
	$_c = mysql_num_rows($query);
	if ($_c) {
		$row = mysql_fetch_array($query);
		$VLPrevBalance = $row['fldBalance'];
		$VLBalance = $VLEnding;
	} else {
		$VLPrevBalance = 0;
		$VLBalance = $VLEnding;
	}
	
	$query = "insert into tblLeaveBalance (
		fldLeaveTypeID, 
		fldEmpCode, 
		fldPrevBalance, 
		fldBalance, 
		fldNegative, 
		fldDate 
	) values (
		'1', 
		'$empCode', 
		$VLPrevBalance, 
		$VLBalance, 
		0, 
		'$curr_date'
	)";
	mysql_query($query) or die("32) ".mysql_error());
	
	 //UPDATES FORCE LEAVE BALANCES		
	if($month1 != 12){
		$queryT = mysql_query("select * from tblLeaveBalance where fldEmpCode='$empCode' AND fldLeaveTypeID='6' order by fldDate desc") or die("33) ".mysql_error());
		$t_count = mysql_num_rows($queryT);
		if ($t_count) {
			$rowT = mysql_fetch_array($queryT);
			$FLPrevBalance = $rowT['fldBalance'];
		} else {
			$FLPrevBalance = 0;
		}
		
		$textT = "SELECT fldFromDate, fldToDate FROM tblEmpLeave WHERE fldEmpCode LIKE '$empCode' AND (fldLeaveTypeID = 6 
		OR fldLeaveTypeID = 1) AND fldFromDate >= '$from' AND fldToDate <= '$to' AND ((fldAppFtL='1' AND fldFlagCaRe='0') 
		OR (fldAppFtL='0' AND fldAppFtLDate!='0000-00-00 00:00:00' AND fldFlagCaRe='0'))";
		$queryT = mysql_query($textT) or die("34) ".mysql_error());
		$rawDate = array();
		$index = 0;
		while($resultT = mysql_fetch_array($queryT)){
			$date1 = $resultT['fldFromDate'];
			$date2 = $resultT['fldToDate'];
						
			if($date1 == $date2){
				$rawDate[$index++] = $date1;			
			} else {				
					$dateA1 = explode("-",$date1);
					$dateA2 = explode("-",$date2);
					$date1 = mktime(0,0,0,$dateA1[1],$dateA1[2],$dateA1[0]);
					$date2 = mktime(0,0,0,$dateA2[1],$dateA2[2],$dateA2[0]);
					$diff = $date2 - $date1;
					$diff = ($diff/3600)/24;
					
					for($ab = 0;$ab <= $diff; $ab++){
						$toadd = "+".$ab." day";
						$newdate = strtotime($toadd, $date1);
						$date = date("Y-m-d",$newdate);
						
						if(!in_array($date,$rawDate)) $rawDate[$index++] = $date;
					}					
			}
		}
		$rawDate = array_unique($rawDate);			
		$count = count($rawDate);
		#echo "FL: (".count($rawDate)."): ";
		#print_r($rawDate);
		#echo "<br/>";
					
		$FLBalance = $FLPrevBalance - $count;		
		if($FLBalance < 0) $FLBalance = 0;
		
		$t_query = "insert into tblLeaveBalance (fldLeaveTypeID, fldEmpCode, fldPrevBalance, fldBalance, fldNegative, fldDate) values ('6', '$empCode', $FLPrevBalance, $FLBalance, 0, '$curr_date')";	
		mysql_query($t_query) or die("71) Query: $t_query :: ".mysql_error());
	}
		
	//UPDATES SICK LEAVE BALANCES
	$query = mysql_query("select * from tblLeaveBalance where fldEmpCode='$empCode' AND fldLeaveTypeID='2' order by fldDate desc") or die("36) ".mysql_error());
	$_c = mysql_num_rows($query);
	if ($_c) {
		$row = mysql_fetch_array($query);
		$SLPrevBalance = $row['fldBalance'];
		$SLBalance = $SLEnding;
	} else {
		$SLPrevBalance = 0;
		$SLBalance = $SLEnding;
	}
	
	
	mysql_query("insert into tblLeaveBalance (fldLeaveTypeID, fldEmpCode, fldPrevBalance, fldBalance, fldNegative, fldDate) values ('2', '$empCode', $SLPrevBalance, $SLBalance, 0, '$curr_date')") or die("73) ".mysql_error());
  }#end of if num == 0
 }//END OF 2ND SET
 ?>
</html>
