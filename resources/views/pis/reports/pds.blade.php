
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="stylesheets/style2.css" type="text/css" rel=stylesheet>
<style type="text/css">
  .select {
 font-family:tahoma;
 width:8px;
}

table{
  table-layout:fixed;
}

td.ruler{
  overflow:hidden;
  display:table-cell;
  width:32px;
}

td.text {
 font-size:8px;
 background:#F5FAFA;
 font-family: Geneva, Arial, Helvetica, sans-serif;
}

td.text2 {
 font-size:11px;
 font-family: Geneva, Arial, Helvetica, sans-serif;
 padding-left:5px;
}

td.text3 {
 font-size:8px;
 background:#FFF;
 font-family: Geneva, Arial, Helvetica, sans-serif;
}

td.header {
 font-family: Geneva, Arial, Helvetica, sans-serif;
 font-style:italic;
 font-size:12px;
 font-weight:bold;
 color:#FFFFFF;
 border:1px black solid;
 background-color:#999999;
}

td.end {
 border-left: 0;
 border-right: 0;
}

td.noBot {
 border-bottom: 0;
}
td.noTopBot {
 border-top: 0;
 border-bottom: 0;
}
td.noTop {
 border-top: 0;
}
td.noRight{
 border-right: 0;
}
td.noLeft{
 border-left: 0;
}
td.noRightTopBot {
 border-right: 0;
 border-bottom: 0;
 border-top: 0;
}
td.noRightBot{
 border-right: 0;
 border-bottom: 0;
}
td.noRightTop{
 border-right: 0;
 border-top: 0;
}
td.noLeftBot{
 border-left: 0;
 border-bottom: 0;
}
td.noLeftTop{
 border-left: 0;
 border-top: 0;
}

@media print{
  td.header {
   font-family: Geneva, Arial, Helvetica, sans-serif;
   font-style:italic;
   font-size:12px;
   font-weight:bold;
   color:#FFFFFF;
   border:1px black solid;
   background-color:#999999;
  } 
}
</style>
<title>Personal Data Sheet</title>
<STYLE>
    br.page { page-break-after: always }
    
    div.page{
    	page-break-inside:avoid;
    	page-break-after:always;
    }
</STYLE>
</head>

<body>
<center>
<div class="page">
<table border="1" style="border-collapse:collapse; border-color:#000000; font-size:10px; font-family:arial" width="672" id="table2">
<tr>
 <td colspan="21" width="672" align="left" style="border-bottom:0;"><font style="font-weight:bold">Form No. 212 ( )<br/>
 	<span style="font-size:8px">Revised 2017</span></font> </td>
</tr>
<tr height="50">
 <td colspan="21" width="672" align="center" style="border-top:0; border-bottom:0; font-size:24px;"><strong>PERSONAL DATA SHEET</strong></td>
</tr>
<tr>
 <td colspan="21" class="noTop noBot">
  <span style="font-size:8px"><font style="font-weight:bold; font-style:italic">WARNING: Any misinterpretation made in the Personal Data Sheet and the Work Experience 
  	Sheet shall cause the filing of administrative/criminal case/s against the person concerned. </font></span>
 </td>	
</tr>
<tr>
 <td colspan="21" class="noTop noBot">
  <span style="font-size:8px"><font style="font-weight:bold; font-style:italic">READ THE ATTACHED GUIDE TO FILLING OUT THE PERSONAL DATA SHEET (PDS) BEFORE ACCOMPLISHING THE PDS FORM.</font></span>
 </td>	
</tr>
<tr>
 <td colspan="15" align="left" style="border-top:0;">
 	<span style="font-size:8px">Print legibly. Tick appropriate boxes
 		and use separate sheet if necessary. Indicate N/A if not applicable. </span>
 	<span style="font-size:8px"><font style="font-weight:bold; font-style:italic">DO NOT ABBREVIATE.</font></span>	
 </td>
 <td colspan="2" class="header">
	<span style="font-size:8px"><font style="font-weight:bold; font-style:italic">1.CS ID No. </font></span>
 </td>
 <td colspan="4" align="right">
 	<span style="font-size:7px"><font style="font-size:6px; font-weight:bold; font-style:italic">(Do not fill up. For CSC use only)</font><br/><br/></span>
 </td>
</tr>
<tr>
 <td class="header" colspan="21" ><b><i>I. PERSONAL INFORMATION</i></b></td>
</tr>
<tr>
 <td colspan="4" class="text" valign="top">2. SURNAME</td>
 <td colspan="17" valign="top" class="text2">{{ strtoupper($data['empinfo']['lname']) }}</td>
</tr>
<tr>
 <td class="text" colspan="4" valign="top">&nbsp;&nbsp;&nbsp;&nbsp;FIRST NAME</td>
 <td class="text2" colspan="13" valign="top">{{ strtoupper($data['empinfo']['fname']) }}</td>
 <td class="text" colspan="4">NAME EXTENSION<span style="font-size:8px">(JR., SR)</span> {{ strtoupper($data['empinfo']['exname']) }}
 	<br/>
 	 </td>
</tr>
<tr>
 <td colspan="4" class="text" valign="top">&nbsp;&nbsp;&nbsp;&nbsp;MIDDLE NAME</td>
 <td colspan="17" valign="top" class="text2">{{ strtoupper($data['empinfo']['mname']) }}</td>
</tr>
<tr>
 <td colspan="4" class="text" valign="top">3. DATE OF BIRTH <span style="font-size:8px"><br/>(mm/dd/yyyy)</span></td>
 <td colspan="6" valign="top" class="text2">{{ date('m-d-Y',strtotime($data['empinfo']['birthdate'])) }}</td>
 <td colspan="5" rowspan="3" class="text" valign="top">16. CITIZENSHIP <br/><br/><br/><br/>
 	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:8px">If holder of dual citizenship, <br/> 
 	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;please indicate the details.</span><br/><br/>
 </td>
 <td colspan="7" rowspan="3" valign="top" class="text2">
 	<input type="checkbox" checked='checked' class="select"/><font style='font-size:9px'>Filipino</font>
 	&nbsp;<input type="checkbox"  class="select"/><font style='font-size:9px'>Dual Citizenship</font>
 	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 	<br/>
 	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 	<input type="checkbox" checked='checked' class="select"/><font style='font-size:9px'>by birth</font>
 	<input type="checkbox"  class="select"/><font style='font-size:9px'>by naturalization</font>
 	<br/>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font style='font-size:9px'>Pls. indicate country:</font> 
	<br/><br/>
	<center></center> 
 </td>
</tr>
<tr>
 <td colspan="4" class="text" valign="top">4. PLACE OF BIRTH</td>
 <td colspan="6" valign="top" class="text2">{{ strtoupper($data['basicinfo']['basicinfo_placeofbirth']) }}</td>
</tr>
<tr>
 <td colspan="4" class="text" valign="middle">5. SEX</td>
 <td colspan="6" valign="top" class="text2">
  <table cellspacing="0" border="0" cellpadding="0" width="100%">
  <tr><td colspan="2" width="64" valign="top" class="text2"><input type="checkbox" class="select"/>&nbsp;
      @if(Auth::user()->sex == 'Male')
   		<font style='font-size:10px'>Male</font></td><td colspan="2" width="64" valign="top" class="text2"><input type="checkbox" class="select" checked/>&nbsp;
   		<font style='font-size:10px'>Female</font></td></tr> </table>
      @else
      <font style='font-size:10px'>Male</font></td><td colspan="2" width="64" valign="top" class="text2"><input type="checkbox" class="select" checked/>&nbsp;
   		<font style='font-size:10px'>Female</font></td></tr>  </table>
      @endif
  </td>
</tr>
<tr>
 <td colspan="4" valign="top" rowspan="2" class="text">6. CIVIL STATUS</td>
 <td colspan="6" rowspan="2" valign="top">
  <table cellspacing="0" border="0" cellpadding="0" width="100%">
  <tr><td colspan="2" width="64" valign="top" class="text2"><input type="checkbox" id="civil_Single" class="select" value="Single"/><font style='font-size:9px'>&nbsp;Single</font></td><td colspan="2" width="64" valign="top" class="text2"><input type="checkbox" id="civil_Married" class="select" value="Married"/><font style='font-size:9px'>&nbsp;Married</font></td></tr><tr><td colspan="2" width="64" valign="top" class="text2"><input type="checkbox" class="select" id="civil_Widowed"  value="Widowed"/><font style='font-size:9px'>&nbsp;Widowed</font></td><td colspan="2" width="64" valign="top" class="text2"><input type="checkbox" class="select" id="civil_Separated"  value="Separated"/><font style='font-size:9px'>&nbsp;Separated</font></td></tr><tr><td colspan="2" width="64" valign="top" class="text2"><input type="checkbox" class="select" id="civil_Annuled" value="Annuled"/><font style='font-size:9px'>&nbsp;Annuled</font></td><td colspan="2" width="64" valign="top" class="text2"><input type="checkbox" class="select" id="civil_Divorced value="Divorced"/><font style='font-size:9px'>&nbsp;Divorced</font></td></tr>  </table>  
 </td>
 <td class="text noBot" colspan="4" rowspan="3" valign="top">17. RESIDENTIAL ADDRESS
 <td class="text2 noRight" colspan="3" align="center" ><span class="text2">{{$data['residential']['residential_add_no']}}</span><font style='font-size:8px'><br/>House/Block/Lot No.</font></td>
 <td class="text2 noLeft" colspan="4" align="center" ><span class="text2">{{$data['residential']['residential_add_street']}}</span><font style='font-size:8px'><br/>Street</font></td>
</tr>
<tr>
 <td class="text2 noRight" colspan="3" align="center"><span class="text2">{{$data['residential']['residential_add_subd']}}</span><font style='font-size:8px'><br/>Subdivision/Village</font></td>
 <td class="text2 noLeft" colspan="4" align="center"><span class="text2">{{$data['residential']['brgy_desc']}}</span><font style='font-size:8px'><br/>Barangay</font></td>
</tr>
<tr>
 <td colspan="4" class="text" valign="top">7. HEIGHT (m)</td>
 <td colspan="6" valign="top" class="text2">{{ strtoupper($data['basicinfo']['basicinfo_height']) }}</td>
 <td class="text2 noRight" colspan="3" align="center"><span class="text2">{{$data['residential']['mun_desc']}}</span><font style='font-size:8px'><br/>City/Municipality</font></td>
 <td class="text2 noLeft" colspan="4" align="center"><span class="text2">{{$data['residential']['prov_desc']}}</span><font style='font-size:8px'><br/>Province</font></td>
</tr>
<tr>
 <td colspan="4" class="text" valign="top">8. WEIGHT (kg)</td>
 <td class="text2" colspan="6" valign="top" >{{ strtoupper($data['basicinfo']['basicinfo_weight']) }}</td>
 <td class="text noTop" colspan="4" class="text2" valign="top" align="center">ZIP CODE</td>
 <td class="text2" colspan="7" valign="top" style="padding-left:5px;"><span class="text2">{{$data['residential']['residential_add_zipcode']}}</span></td>
</tr>
<tr>
 <td colspan="4" class="text" valign="top">9. BLOOD TYPE</td>
 <td colspan="6" valign="top" class="text2">{{ strtoupper($data['basicinfo']['basicinfo_bloodtype']) }}</td>
 <td class="text noBot" colspan="4" rowspan="3" valign="top">18. PERMANENT ADDRESS </td>
 <td class="text2 noRight" colspan="3" align="center" ><span class="text2">{{$data['permanent']['permanent_add_no']}}</span><font style='font-size:8px'><br/>House/Block/Lot No.</font></td>
 <td class="text2 noLeft" colspan="4" align="center" ><span class="text2">{{$data['permanent']['permanent_add_street']}}</span><font style='font-size:8px'><br/>Street</font></td>
</tr>
<tr>
 <td colspan="4" class="text" valign="top">10. GSIS ID NO.</td>
 <td colspan="6" valign="top" class="text2">{{ strtoupper($data['addinfo']['addinfo_gsis_id']) }}</td>
 <td class="text2 noRight" colspan="3" align="center"><span class="text2">{{$data['permanent']['permanent_add_subd']}}</span><font style='font-size:8px'><br/>Subdivision/Village</font></td>
 <td class="text2 noLeft" colspan="4" align="center"><span class="text2">{{$data['permanent']['brgy_desc']}}</span><font style='font-size:8px'><br/>Barangay</font></td>
</tr>
<tr>
 <td colspan="4" class="text" valign="top">11. PAG-IBIG ID NO.</td>
 <td colspan="6" valign="top" class="text2">{{ strtoupper($data['addinfo']['addinfo_pagibig']) }}</td>
 <td class="text2 noRight" colspan="3" align="center"><span class="text2">{{$data['permanent']['mun_desc']}}</span><font style='font-size:8px'><br/>City/Municipality</font></td>
 <td class="text2 noLeft" colspan="4" align="center"><span class="text2">{{$data['permanent']['prov_desc']}}</span><font style='font-size:8px'><br/>Province</font></td>
</tr>
<tr>
 <td colspan="4" class="text" valign="top">12. PHILHEALTH NO.</td>
 <td colspan="6" valign="top" class="text2">{{ strtoupper($data['addinfo']['addinfo_philhealth']) }}</td>
 <td class="text noTop" colspan="4" class="text2" valign="top" align="center">ZIP CODE</td>
 <td class="text2" colspan="7" valign="top" ></td>
</tr>
<tr>
 <td colspan="4" class="text" valign="top">13. SSS NO.</td>
 <td colspan="6" valign="top" class="text2">{{ strtoupper($data['addinfo']['addinfo_sss']) }}</td>
 <td colspan="4" class="text" valign="top">19. TELEPHONE NO.</td>
 <td colspan="7" valign="top" class="text2"></td>
</tr>
<tr>
 <td colspan="4" class="text" valign="top">14. TIN NO.</td>
 <td colspan="6" valign="top" class="text2">{{ strtoupper($data['addinfo']['addinfo_tin']) }}</td>
 <td colspan="4" class="text" valign="top">20. MOBILE NO.</td>
 
</tr>
<tr>
 <td colspan="4" class="text" valign="top">15. AGENCY EMPLOYEE NO.</td>
 <td colspan="6" valign="top" class="text2">{{ Auth::user()->username }}</td>
 <td colspan="4" class="text" valign="top">21. EMAIL ADDRESS (if any)</td>
 <td colspan="7" valign="top" class="text2">{{ Auth::user()->email }}</td>
</tr>
<tr>
 <td class="header" colspan="21"><b><i>II. FAMILY BACKGROUND</i></b></td>
</tr>
<tr>
 <td class="text noBot" colspan="4" valign="top">22. SPOUSE'S SURNAME.</td>
 <td colspan="10" valign="top" class="text2">{{ strtoupper($data['family']['fam_spouse_lname']) }}</td>
 <td colspan="7" rowspan="13" valign="top" class="text3">
  <table border="1" style="border-collapse:collapse; border-color:#000000; font-size:10px; font-family:arial" width="100%">
   <tr>
    <td class="text" width="50%">23. NAME OF CHILDREN<br />(Write full name and list all)</td>
	<td class="text" width="50%" align="center">DATE OF BIRTH (mm/dd/yyyy)</td>
  </tr>

  <?php
  foreach ($data['child'] as $childs) {
    # code...

      echo '<tr>
              <td class="text3" >'.$childs->children_name.'</td>
              <td class="text3" align="center">'.date('m-d-Y',strtotime($childs->children_birthdate)).'</td>
            </tr>';
  }
  ?>
  
     </table>
 </td>
 </tr>
<tr>
 <td class="text noTop noBot" colspan="4" valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;FIRST NAME</td>
 <td colspan="6" valign="top" class="text2">{{ strtoupper($data['family']['fam_spouse_fname']) }}</td>
 <td colspan="4" valign="top" class="text"><span style="font-size:6px">NAME EXTENSION&nbsp;(JR., SR)<br>{{ strtoupper($data['family']['fam_spouse_exname']) }}</span>
 	<br/>
 	 </td>
 </tr>
<tr>
 <td class="text noTop" colspan="4" valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MIDDLE NAME</td>
 <td colspan="10" valign="top" class="text2">{{ strtoupper($data['family']['fam_spouse_mname']) }}</td>
 </tr>
<tr>
 <td colspan="4" class="text" valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;OCCUPATION</td>
 <td colspan="10" valign="top" class="text2">{{ strtoupper($data['family']['fam_spouse_occ']) }}</td>
 </tr>
<tr>
 <td colspan="4" class="text" valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;EMPLOYER/BUSINESS NAME</td>
 <td colspan="10" valign="top" class="text2">{{ strtoupper($data['family']['fam_spouse_emp']) }}</td>
 </tr>
<tr>
 <td colspan="4" class="text" valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;BUSINESS ADDRESS</td>
 <td colspan="10" valign="top" class="text2">{{ strtoupper($data['family']['fam_spouse_emp_add']) }}</td>
 </tr>
<tr>
 <td colspan="4" class="text" valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TELEPHONE NO.</td>
 <td colspan="10" valign="top" class="text2">{{ strtoupper($data['family']['fam_spouse_tel']) }}</td>
 </tr>
<tr>
 <td class="text noBot" colspan="4" valign="top">24. FATHER'S SURNAME</td>
 <td colspan="10" valign="top" class="text2">{{ strtoupper($data['family']['fam_father_lname']) }}</td>
 </tr>
<tr>
 <td class="text noTop noBot" colspan="4" valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;FIRST NAME</td>
 <td colspan="6" valign="top" class="text2">{{ strtoupper($data['family']['fam_father_fname']) }}</td>
 <td colspan="4" valign="top" class="text"><span style="font-size:6px">NAME EXTENSION&nbsp;(JR., SR)<br>{{ strtoupper($data['family']['fam_father_exsname']) }}</span>
 	<br/>
 	 </td>
 </tr>
<tr>
 <td class="text noTop" colspan="4" valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MIDDLE NAME</td>
 <td colspan="10" valign="top" class="text2">{{ strtoupper($data['family']['fam_father_mname']) }}</td>
 </tr>
<tr>
 <td class="text noBot" colspan="4" valign="top">25. MOTHER'S MAIDEN NAME</td>
 <td colspan="10" valign="top" class="text2"></td>
</tr>
<tr>
 <td class="text noTop noBot" colspan="4" valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SURNAME</td>
 <td colspan="10" valign="top" class="text2">{{ strtoupper($data['family']['fam_mother_lname']) }}</td>
</tr>
<tr>
 <td class="text noTop noBot" colspan="4" valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;FIRST NAME</td>
 <td colspan="10" valign="top" class="text2">{{ strtoupper($data['family']['fam_mother_fname']) }}</td>
</tr>
<tr>
 <td class="text noTop" colspan="4" valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MIDDLE NAME</td>
 <td colspan="10" valign="top" class="text2">{{ strtoupper($data['family']['fam_mother_mname']) }}</td>
 <td colspan="7" class="text" valign="top" align="center"><span style="color:#990000; font-style:italic">(Continue on separate sheet if necessary)</span></td>
</tr>
<tr>
 <td class="header" colspan="21"><b><i>III. EDUCATIONAL BACKGROUND</i></b></td>
</tr>
<tr>
 <td colspan="4" class="text" align="center" rowspan="2">26. LEVEL</td>
 <td colspan="5" class="text" align="center" rowspan="2">NAME OF SCHOOL<br />(Write in full)</td>
 <td colspan="4" class="text" align="center" rowspan="2">BASIC EDUCATION/DEGREE/COURSE<br />(Write in full)</td>
 <td colspan="2" align="center" class="text">PERIOD OF ATTENDANCE</td>
 <td colspan="2" class="text" align="center" rowspan="2">HIGHEST LEVEL/ UNITS EARNED<br />(if not graduated)</td>
 <td colspan="2" class="text" align="center" rowspan="2">YEAR GRADUATED<br /></td>
 <td colspan="2" align="center" class="text" rowspan="2">SCHOLARSHIP/<br/>ACADEMIC<br/> HONORS<br/> RECEIVED</td>
</tr> 
<tr>
 <td colspan="1" align="center" class="text">From</td>
 <td colspan="1" align="center" class="text">To</td> 
</tr>

<?php
  foreach ($data['education'] as $education) {
    # code...
    // if($education->approved == 1)
    echo '<tr>
           <td colspan="4" valign="top" class="text3" style="padding-left:5px;">'.$education->educ_level_desc.'</td>
           <td colspan="5" valign="top" class="text3">'.$education->educ_school.'</td>
           <td colspan="4" valign="top" class="text3">'.$education->educ_course.'</td>
           <td colspan="1" align="center" valign="top" class="text3">'.$education->educ_date_from.'</td>
           <td colspan="1" align="center" valign="top" class="text3">'.$education->educ_date_to.'</td> 
           <td colspan="2" valign="top" class="text3">'.$education->educ_highest.'</td>
           <td colspan="2" valign="top" class="text3" align="center">'.$education->educ_date_to.'</td>
           <td colspan="2" valign="top" class="text3">'.$education->educ_awards.'</td>
          </tr>';
  }
?>

<tr>
 <td class="end">&nbsp;</td>
 <td class="end">&nbsp;</td>
 <td class="end">&nbsp;</td>
 <td class="end">&nbsp;</td>
 <td class="end">&nbsp;</td>
 <td class="end">&nbsp;</td>
 <td class="end">&nbsp;</td>
 <td class="end">&nbsp;</td>
 <td class="text noLeft noRight" colspan="9"><span style="color:#990000; font-style:italic">(Continue on separate sheet if necessary)</span></td>
 <td class="end">&nbsp;</td>
 <td class="end">&nbsp;</td>
 <td class="end">&nbsp;</td>
 <td class="end">&nbsp;</td>
 <td class="end">&nbsp;</td>
</tr>
<tr>
	<td colspan="21" style="border-collapse:collapse; padding:0px 0px 0px 0px;">
		<table width="100%" border="1" style="border-collapse:collapse; padding:0px 0px 0px 0px;">
			<tr>
				<td class="text2" width="20%%" colspan="3" align="center"><font style="font-weight:bold;">SIGNATURE<br/><br/></font></td>
				<td width="35%" colspan="10">&nbsp;</td>
				<td class="text2" width="20%" colspan="2" align="center"><font style="font-weight:bold;">DATE<br/><br/></font></td>
				<td width="25%" colspan="6">&nbsp;</td>
			</tr>
		</table>
	</td>
</tr>
</table>
</div>

<!--br class="page" /--><br/>

<div class="page">
<table border="1" style="border-collapse:collapse; border-color:#000000; font-size:10px; font-family:arial" width="672">
<tr>
 <td class="header" colspan="21" width="672"><b><i>IV. CIVIL SERVICE ELIGIBILTY</i></b></td>
</tr>
<tr>
 <td colspan="7" width="160" class="text" align="center" rowspan="2">27. CAREER SERVICE/RA 1080 (BOARD/BAR) UNDER SPECIAL LAWS/CES/CSEE/ <br/> BARANGAY ELIGIBILITY/DRIVER'S LICENSE</td>
 <td colspan="2" width="64" class="text" align="center" rowspan="2">RATING <br/> (If Applicable)</td>
 <td colspan="2" width="96" class="text" align="center" rowspan="2">DATE OF EXAMINATION/ CONFERMENT</td>
 <td colspan="6" width="160" class="text" align="center" rowspan="2">PLACE OF EXAMINATION/CONFERMENT</td>
 <td colspan="4" width="192" class="text" align="center">LICENSE (if applicable)</td>
</tr>
<tr>
 <td colspan="2" width="96" class="text" align="center">NUMBER</td>
 <td colspan="2" width="96" class="text" align="center">DATE OF VALIDITY</td>
</tr>
<?php
  foreach ($data['eligibility'] as $eligibilities) {
    # code...

      echo '<tr>
             <td colspan="7" width="160" class="text3">&nbsp;'.$eligibilities->cse_title.'</td>
             <td colspan="2" width="64" class="text3">&nbsp;'.$eligibilities->cse_rating.'</td>
             <td colspan="2" width="96" class="text3">&nbsp;'.$eligibilities->cse_date.'</td>
             <td colspan="6" width="160" class="text3">&nbsp;'.$eligibilities->cse_place.'</td>
             <td colspan="2" width="96" class="text3">&nbsp;'.$eligibilities->cse_license_num.'</td>
             <td colspan="2" width="96" class="text3">&nbsp;'.$eligibilities->cse_license_date.'</td>
            </tr>';
  }
?>


<tr>
 <td colspan="21" width="672" align="center" class="text"><span style="color:#990000; font-style:italic">(Continue on separate sheet if necessary)</span></td>
</tr>
<tr>
 <td class="header" colspan="21" width="672"><b><i>V. WORK EXPERIENCE <br/> 
 	<font style="font-size:8px">
 		(Include private employment. Start from your recent work) Description of duties should be indicated in the attached Work Experience sheet. 
 	</font></i></b>
 </td>
</tr>
<tr>
 <td colspan="4" width="128" class="text" align="center">28. INCLUSIVE DATES (mm/dd/yyyy)</td>
 <td colspan="5" width="160" class="text" align="center" rowspan="2">POSITION TITLE <br/>(Write in full/Do not abbreviate)</td>
 <td colspan="4" width="160" class="text" align="center" rowspan="2">DEPARTMENT/AGENCY/ OFFICE/COMPANY <br/>(Write in full/Do not abbreviate)</td>
 <td colspan="2" width="64" class="text" align="center" rowspan="2">MONTHLY SALARY</td>
 <td colspan="2" width="64" class="text" align="center" rowspan="2"><font style="font-size:7px">SALARY/JOB/PAY GRADE (if applicable) & STEP (Format "00-0")/ <br/>INCREMENT</font></td>
 <td colspan="2" width="64" class="text" align="center" rowspan="2">STATUS OF APPOINTMENT</td>
 <td colspan="2" width="32" class="text" align="center" rowspan="2">GOV'T SERVICE (Y/N)</td>
</tr>
<tr>
 <td colspan="2" width="64" class="text" align="center">FROM</td>
 <td colspan="2" width="64" class="text" align="center">TO</td>
</tr>
<?php
  foreach ($data['work'] as $works) {
    # code...
    echo '<tr>
           <td d colspan="2" width="64" class="text3" align="center">&nbsp;'.$works->workexp_date_from.'</td>
           <td d colspan="2" width="64" class="text3" align="center">&nbsp;'.$works->workexp_date_to.'</td>
           <td colspan="5" width="160" class="text3">&nbsp;'.$works->workexp_title.'</td>
           <td colspan="4" width="160" class="text3">&nbsp;'.$works->workexp_company.'</td>
           <td colspan="2" width="64" class="text3">&nbsp;P '.formatNumber('currency',$works->workexp_salary).'</td>
           <td colspan="2" width="64" class="text3">&nbsp;'.$works->workexp_salary_grade.'</td>
           <td colspan="2" width="64" class="text3">&nbsp;'.$works->workexp_empstatus.'</td>
           <td colspan="2" width="32" class="text3">&nbsp;'.$works->workexp_gov_desc.'</td>
          </tr>';
  }

  foreach ($data['work_agency'] as $works2) {
    # code...
                          
    echo '<tr>
           <td d colspan="2" width="64" class="text3" align="center">&nbsp;'.$works2->plantilla_date_from.'</td>
           <td d colspan="2" width="64" class="text3" align="center">&nbsp;'.$works2->plantilla_date_to.'</td>
           <td colspan="5" width="160" class="text3">&nbsp;'.$works2->position_desc.'</td>
           <td colspan="4" width="160" class="text3">&nbsp;PCAARRD</td>
           <td colspan="2" width="64" class="text3">&nbsp;P '.formatNumber('currency',$works2->plantilla_salary).'</td>
           <td colspan="2" width="64" class="text3">&nbsp;'.$works->salary_grade.'</td>
           <td colspan="2" width="64" class="text3">&nbsp;Permanent</td>
           <td colspan="2" width="32" class="text3">&nbsp;YES</td>
          </tr>';
  }
?>
<tr>
 <td colspan="2" width="64" class="text2">&nbsp;</td>
 <td colspan="2" width="64" class="text2">&nbsp;</td>
 <td colspan="5" width="160" class="text2">&nbsp;</td>
 <td colspan="4" width="160" class="text2">&nbsp;</td>
 <td colspan="2" width="64" class="text2">&nbsp;</td>
 <td colspan="2" width="64" class="text2">&nbsp;</td>
 <td colspan="2" width="64" class="text2">&nbsp;</td>
 <td colspan="2" width="32" class="text2">&nbsp;</td>
</tr>
<tr>
 <td colspan="2" width="64" class="text2">&nbsp;</td>
 <td colspan="2" width="64" class="text2">&nbsp;</td>
 <td colspan="5" width="160" class="text2">&nbsp;</td>
 <td colspan="4" width="160" class="text2">&nbsp;</td>
 <td colspan="2" width="64" class="text2">&nbsp;</td>
 <td colspan="2" width="64" class="text2">&nbsp;</td>
 <td colspan="2" width="64" class="text2">&nbsp;</td>
 <td colspan="2" width="32" class="text2">&nbsp;</td>
</tr>
<tr>
 <td colspan="21" width="672" align="center" class="text"><span style="color:#990000; font-style:italic">(Continue on separate sheet if necessary)</span></td>
</tr>
<tr>
	<td colspan="21" style="border-collapse:collapse; padding:0px 0px 0px 0px;">
		<table width="100%" border="1" style="border-collapse:collapse; padding:0px 0px 0px 0px;">
			<tr>
				<td class="text2" width="20%%" colspan="3" align="center"><font style="font-weight:bold;">SIGNATURE <br/><br/></font></td>
				<td width="35%" colspan="10">&nbsp;</td>
				<td class="text2" width="20%" colspan="2" align="center"><font style="font-weight:bold;">DATE<br/><br/></font></td>
				<td width="25%" colspan="6">&nbsp;</td>
			</tr>
		</table>
	</td>
</tr>
</table>
</div>

<!--br class="page" /--><br/>




<div class="page">
<table border="1" style="border-collapse:collapse; border-color:#000000; font-size:10px; font-family:arial" width="672">
<tr>
 <td class="header" colspan="21" width="672"><b><i>VI. VOLUNTARY WORK OR INVOLVEMENT IN CIVIC/NON-GOVERNMENT/PEOPLE/VOLUNTARY ORGANIZATION/S</i></b></td>
</tr>
<tr>
 <td colspan="8" width="288" class="text" align="center" rowspan="2">29. NAME & ADDRESS OF ORGANIZATION <br/>(Write in full)</td>
 <td colspan="4" width="64" class="text" align="center">INCLUSIVE DATES<br />(mm/dd/yyyy)</td>
 <td colspan="2" width="32" class="text" align="center" rowspan="2">NUMBER OF HOURS</td>
 <td colspan="7" width="288" class="text" align="center" rowspan="2">POSITION / NATURE OF WORK</td>
</tr>
<tr>
 <td colspan="2" width="32" class="text" align="center">FROM</td>
 <td colspan="2" width="32" class="text" align="center">TO</td>
</tr>
<?php
  foreach ($data['organization'] as $organizations) {
    # code...
    echo '<tr>
           <td colspan="8" width="288" class="text3">'.$organizations->org_name.'<br>'.$organizations->org_add.'</td>
           <td colspan="2" width="32" class="text3">&nbsp;'.$organizations->org_date_from.'</td>
           <td colspan="2" width="32" class="text3">&nbsp;'.$organizations->org_date_to.'</td>
           <td colspan="2" width="32" class="text3">&nbsp;'.$organizations->org_hours.'</td>
           <td colspan="7" width="288" class="text3">&nbsp;'.$organizations->org_position.'</td>
          </tr>';
  }
?>
<tr>
 <td colspan="8" width="288" class="text2">&nbsp;</td>
 <td colspan="2" width="32" class="text2">&nbsp;</td>
 <td colspan="2" width="32" class="text2">&nbsp;</td>
 <td colspan="2" width="32" class="text2">&nbsp;</td>
 <td colspan="7" width="288" class="text2">&nbsp;</td>
</tr>


<tr>
 <td colspan="21" width="672" align="center" class="text"><span style="color:#990000; font-style:italic">(Continue on separate sheet if necessary)</span></td>
</tr>
<tr>
 <!--td class="header" colspan="21" width="672"><b><i>VII. LEARNING AND DEVELOPMENT (L&D) INTERVENTIONS/TRAINING PROGRAMS ATTENDED</i></b></td-->
 <td class="header" colspan="21" width="672"><b><i>VII. LEARNING AND DEVELOPMENT (L&D) INTERVENTIONS/TRAINING PROGRAMS ATTENDED <br/> 
 	<font style="font-size:8px">
 		(Start from the recent L&D/training program and include only the relevant L&D/training taken for the last five (5) years for Division Chief/Executive/Managerial poistions)
 	</font></i></b>
 </td>
</tr>
<tr>
 <td colspan="8" width="288" class="text" align="center" rowspan="2">30. TITLE OF LEARNING AND DEVELOPMENT INTERVENTIONS/TRAINING PROGRAMS <br/> (Write in full)</td>
 <td colspan="4" width="64" class="text" align="center">INCLUSIVE DATES OF ATTENDANCE <br />(mm/dd/yyyy)</td>
 <td colspan="2" width="32" class="text" align="center" rowspan="2">NUMBER OF HOURS</td>
 <td colspan="2" width="32" class="text" align="center" rowspan="2">Type of LD <br/> (Managerial/ <br/> Supervisory/ <br/> Technical/etc)</td>
 <td colspan="5" width="256" class="text" align="center" rowspan="2">CONDUCTED/SPONSORED BY<br />(Write in full)</td>
</tr>
<tr>
 <td colspan="2" width="32" class="text" align="center">From</td>
 <td colspan="2" width="32" class="text" align="center">To</td>
</tr>
<?php
  foreach ($data['training'] as $trainings) {
    # code...
    //DURATION
    $dur  = explode(',', $trainings->training_inclusive_dates);
    $ctr = count($dur);
    usort($dur, "date_sort");

    echo '<tr>
           <td colspan="8" width="288" valign="top" class="text3">&nbsp'.$trainings->training_title.'</td>
           <td colspan="2" width="32" valign="top" class="text3">&nbsp'.$dur[0].'</td>
           <td colspan="2" width="32" valign="top" class="text3">&nbsp'.$dur[--$ctr].'</td>
           <td colspan="2" width="32" valign="top" class="text3" align="center">&nbsp'.$trainings->training_hours.'</td>
           <td colspan="2" width="32" valign="top" class="text3" style="font-size:8px;text-align:center;">&nbsp'.$trainings->training_ld.'</td>
           <td colspan="5" width="256" valign="top" class="text3">&nbsp'.$trainings->training_conducted_by.'</td>
          </tr>';
  }
?>
<tr>
 <td colspan="8" width="288" valign="top" class="text2">&nbsp</td>
 <td colspan="2" width="32" valign="top" class="text2">&nbsp</td>
 <td colspan="2" width="32" valign="top" class="text2">&nbsp</td>
 <td colspan="2" width="32" valign="top" class="text2" align="center">&nbsp</td>
 <td colspan="2" width="32" valign="top" class="text2" style="font-size:8px;text-align:center;">&nbsp</td>
 <td colspan="5" width="256" valign="top" class="text2">&nbsp</td>
</tr>

<tr>
 <td colspan="21" width="672" align="center" class="text"><span style="color:#990000; font-style:italic">(Continue on separate sheet if necessary)</span></td>
</tr>
<tr>
 <td class="header" colspan="21" width="672"><b><i>VIII. OTHER INFORMATION</i></b></td>
</tr>
<tr>
  <td colspan="8" class="text" align="center">31. SPECIAL SKILLS and HOBBIES</td>
  <td colspan="6" class="text" align="center">32. NON-ACADEMIC DISTINCTIONS/RECOGNITON (Write in full)</td>
  <td colspan="7" class="text" align="center">33. MEMBERSHIP IN ASSOCIATION/ORGANIZATION (Write in full)</td>
</tr>
<?php
  for($i = 0;$i < $data['total_row'][0]; $i++)
  {
    echo '<tr>
            <td colspan="8" valign="top" class="text3">';
            if(isset($data['skill'][$i]))
            {
              echo $data['skill'][$i];
            }
            else
            {
              '&nbsp';
            }
    echo '</td>
            <td colspan="6" valign="top" class="text3">';
            if(isset($data['recognition'][$i]))
            {
              echo $data['recognition'][$i];
            }
            else
            {
              '&nbsp';
            }
    echo '</td>
            <td colspan="7" valign="top" class="text3">';
            if(isset($data['association'][$i]))
            {
              echo $data['association'][$i];
            }
            else
            {
              '&nbsp';
            }
    echo '</td>
          </tr>';
  }
?>
<tr>
  <td colspan="8" class="text2" align="center">&nbsp</td>
  <td colspan="6" class="text2" align="center">&nbsp</td>
  <td colspan="7" class="text2" align="center">&nbsp</td>
</tr>


<tr>
 <td colspan="21" width="672" align="center" class="text"><span style="color:#990000; font-style:italic">(Continue on separate sheet if necessary)</span></td>
</tr>
<tr>
	<td colspan="21" style="border-collapse:collapse; padding:0px 0px 0px 0px;">
		<table width="100%" border="1" style="border-collapse:collapse; padding:0px 0px 0px 0px;">
			<tr>
				<td class="text2" width="20%%" colspan="3" align="center"><font style="font-weight:bold;">SIGNATURE<br/><br/></font></td>
				<td width="35%" colspan="10">&nbsp;</td>
				<td class="text2" width="20%" colspan="2" align="center"><font style="font-weight:bold;">DATE<br/><br/></font></td>
				<td width="25%" colspan="6">&nbsp;</td>
			</tr>
		</table>
	</td>
</tr>
</table>
</div>



<!--br class="page" /--><br/>

<div class="page">
<table border="1" style="border-collapse:collapse; border-color:#000000; font-size:10px; font-family:arial" width="672">
<tr>
 <td class="text noBot" colspan="13" width="416" style="padding-left:5px">34. Are you related by consanguinity or affinity to the appointing or recommending authority, or to the 
 	chief of bureau or office or to the person who has immediate supervision over you in the Office, 
 	Bureau or Department where you will be appointed, </td>
 <td colspan="8" width="256" class="noBot">&nbsp;</td>
</tr>
<tr>
 <td class="text noTop noBot" colspan="13" width="416" style="padding-left:5px">
	&nbsp;&nbsp;a. Within the third degree? <br />
	<!--&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;appointing authority, recommending authority, chief of office/bureau/department or<br />
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;person who has immediatesupervision over you in the Office, Bureau or Department<br />
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;where you will be appointed? -->
 </td>
 <td colspan="8" width="256" class="noTopBot" valign="top">
  <table>
   <tr>
    <td class="text2">
 <input type="checkbox">&nbsp;YES&nbsp;&emsp;&nbsp;<input type="checkbox">&nbsp;NO	</td>
   </tr>
   <tr>
    <td class="text2"> If Yes, give details:</td>
   </tr>
   <tr>
    <td class="text2"></td>
   </tr>
  </table>
 </td>
</tr>
<tr>
 <td class="text noTop" colspan="13" width="416" style="padding-left:5px">
	&nbsp;&nbsp;b. Within the fourth degree (for Local Government Unit - Career Employees)?<!--<br />
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;appointing authority or recommending where you will be appointed? -->
 </td>
 <td colspan="8" width="256" class="noTop">
  <table>
   <tr>
    <td class="text2">
 <input type="checkbox">&nbsp;YES&nbsp;&emsp;&nbsp;<input type="checkbox">&nbsp;NO	</td>
   </tr>
   <tr>
    <td class="text2"> If Yes, give details:</td>
   </tr>
   <tr>
    <td class="text2"></td>
   </tr>
  </table>
 </td>
</tr>
<tr>
 <td class="text noBot" colspan="13" width="416" style="padding-left:5px">35. a. Have you ever been found guilty of any administrative offense?</td>
 <td colspan="8" width="256" class="noBot">
  <table>
   <tr>
    <td class="text2">
 <input type="checkbox">&nbsp;YES&nbsp;&emsp;&nbsp;<input type="checkbox">&nbsp;NO	</td>
   </tr>
   <tr>
    <td class="text2"> If Yes, give details:</td>
   </tr>
   <tr>
    <td class="text2"></td>
   </tr>
  </table>
 </td>
</tr>
<tr>
 <td class="text noTop" colspan="13" width="416" style="padding-left:5px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;b. Have you ever been criminally charged before any court?</td>
 <td colspan="8" width="256" class="noTop">
  <table>
   <tr>
    <td class="text2">
 <input type="checkbox">&nbsp;YES&nbsp;&emsp;&nbsp;<input type="checkbox">&nbsp;NO	</td>
   </tr>
   <tr>
    <td class="text2"> If Yes, give details:</td>
   </tr>
   <tr>
    <td class="text2"></td>
   </tr>
  </table>
</td>
</tr>
<tr>
 <td class="text" colspan="13" width="416" style="padding-left:5px">36. Have you ever been convicted of any crime or violation of any law, decree, ordinance or regulation by any court or tribunal?</td>
 <td  class="text2" colspan="8" width="256">
  <table>
   <tr>
    <td class="text2">
 <input type="checkbox">&nbsp;YES&nbsp;&emsp;&nbsp;<input type="checkbox">&nbsp;NO	</td>
   </tr>
   <tr>
    <td class="text2"> If Yes, give details:</td>
   </tr>
   <tr>
    <td class="text2"></td>
   </tr>
  </table>
 </td>
</tr>
<tr>
 <td class="text" colspan="13" width="416" style="padding-left:5px">37. Have you ever been separated from the service in any of the following modes: 
 	resignation, retirement, dropped from the rolls, dismissal, termination, end of term, finished contract or phased out (abolition) in the public or private sector?</td>
 <td colspan="8" width="256" class="text2">
  <table>
   <tr>
    <td class="text2">
 <input type="checkbox">&nbsp;YES&nbsp;&emsp;&nbsp;<input type="checkbox">&nbsp;NO	</td>
   </tr>
   <tr>
    <td class="text2"> If Yes, give details:</td>
   </tr>
   <tr>
    <td class="text2"></td>
   </tr>
  </table>
 </td>
</tr>
<tr>
 <td colspan="13" width="416" class="text noBot" style="padding-left:5px">38. a. Have you ever  been a candidate in a national or local election held within the last year (except Barangay election)?</td>
 <td colspan="8" width="256" class="noBot">
  <table>
   <tr>
    <td class="text2">
 <input type="checkbox">&nbsp;YES&nbsp;&emsp;&nbsp;<input type="checkbox">&nbsp;NO	</td>
   </tr>
   <tr>
    <td class="text2"> If Yes, give details:</td>
   </tr>
   <tr>
    <td class="text2"></td>
   </tr>
  </table>
 </td>
</tr>
<tr>
 <td colspan="13" width="416" class="text noTop" style="padding-left:5px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;b. 
	Have you resigned from the government service during the three (3)-month period before the last election to promote/actively campaign for a national 
	or local candidate?
 </td>
 <td colspan="8" width="256" class="noTop">
  <table>
   <tr>
    <td class="text2">
 <input type="checkbox">&nbsp;YES&nbsp;&emsp;&nbsp;<input type="checkbox">&nbsp;NO	</td>
   </tr>
   <tr>
    <td class="text2"> If Yes, give details:</td>
   </tr>
   <tr>
    <td class="text2"></td>
   </tr>
  </table>
 </td>
</tr>
<tr>
 <td colspan="13" width="416" class="text" style="padding-left:5px">39. 
 	Have you acquired the status of an immigrant or permanent resident of another country?	
 </td>
 <td colspan="8" width="256" class="noTop">
  <table>
   <tr>
    <td class="text2">
 <input type="checkbox">&nbsp;YES&nbsp;&emsp;&nbsp;<input type="checkbox">&nbsp;NO	</td>
   </tr>
   <tr>
    <td class="text2"> If Yes, give details (country):</td>
   </tr>
   <tr>
    <td class="text2"></td>
   </tr>
  </table>
 </td>
</tr>
<tr>
 <td colspan="13" width="416" class="noBot text" style="padding-left:5px">40. Pursuant to: (a) Indigenous People's Act (RA 8371); (b) Magna Carta for Disabled Persons (RA 7277); and (c) Solo Parents Welfare Act of 2000 (RA 8972), please answer the following items:</td>
 <td colspan="8" width="256" class="noBot">&nbsp;</td>
</tr>
<tr>
 <td colspan="13" width="416" class="noTopBot text" style="padding-left:5px">&nbsp;&nbsp;a. Are you a member of any indigenous group?</td>
 <td colspan="8" width="256" class="noTopBot">
  <table>
   <tr>
    <td class="text2">
 <input type="checkbox">&nbsp;YES&nbsp;&emsp;&nbsp;<input type="checkbox">&nbsp;NO	</td>
   </tr>
   <tr>
    <td class="text2"> If Yes, please specifiy:</td>
   </tr>
   <tr>
    <td class="text2"></td>
   </tr>
  </table>
 </td>
</tr>
<tr>
 <td colspan="13" width="416" class="noTopBot text" style="padding-left:5px">&nbsp;&nbsp;b. Are you a person with disability?</td>
 <td colspan="8" width="256" class="noTopBot">
  <table>
   <tr>
    <td class="text2">
 <input type="checkbox">&nbsp;YES&nbsp;&emsp;&nbsp;<input type="checkbox">&nbsp;NO	</td>
   </tr>
   <tr>
    <td class="text2"> If Yes, please specifiy:</td>
   </tr>
   <tr>
    <td class="text2"></td>
   </tr>
  </table>
 </td>
</tr>
<tr>
 <td colspan="13" width="416" class="noTop text" style="padding-left:5px">&nbsp;&nbsp;c. Are you a solo parent?</td>
 <td colspan="8" width="256" class="noTop">
  <table>
   <tr>
    <td class="text2">
 <input type="checkbox">&nbsp;YES&nbsp;&emsp;&nbsp;<input type="checkbox">&nbsp;NO	</td>
   </tr>
   <tr>
    <td class="text2"> If Yes, please specifiy:</td>
   </tr>
   <tr>
    <td class="text2"></td>
   </tr>
  </table>
 </td>
</tr>
<tr>
 <td class="text" colspan="16" width="" style="padding-left:5px">41. REFERENCES <span style="color:#990000">(Person not related by consanguinity or affinity to applicant / appointee)</span></td>
 <td class="text noBot" colspan="5" width="">&nbsp;</td>
</tr>
<tr>
 <td colspan="7" width="192" class="text" align="center">NAME</td>
 <td colspan="6" width="192" class="text" align="center">ADDRESS</td>
 <td colspan="3" width="128" class="text" align="center">TEL NO.</td>
 <td class="noTop" width="160" colspan="5" rowspan="5" valign="middle" align="center" class="text">
  <table border="1" style="border-collapse:collapse; border-color:#000000; font-size:10px; font-family:arial" width="90%">
   <tr>
    <td class="text">
    	<center>
		ID picture taken within <br /> the last 6 months<br />3.5 cm X 4.5 cm<br />(passport size)
		<br /><br />
		With full and handwritten name tag and signature over printed name. <br/><br/>
		
		Computer generated<br />
		or xerox copy of picture<br />
		is not acceptable
		</center>	
	</td>
   </tr>
  </table>
  PHOTO </td>
</tr>

<?php
foreach ($data['reference'] as $references) {

  echo '<tr>
          <td colspan="7" width="192" class="text3">'.$references->reference_name.'</td>
          <td colspan="6" width="192" class="text3">'.$references->reference_add.'</td>
        <td colspan="3" width="128" class="text3">'.$references->reference_telno.'</td>
    </tr>';
}
?>
<tr>
 <td colspan="7" width="192" class="text2">&nbsp;</td>
 <td colspan="6" width="192" class="text2">&nbsp;</td>
 <td colspan="3" width="128" class="text2">&nbsp;</td>
 </tr>
<tr>
 <td colspan="7" width="192" class="text2">&nbsp;</td>
 <td colspan="6" width="192" class="text2">&nbsp;</td>
 <td colspan="3" width="128" class="text2">&nbsp;</td>
 </tr>
<tr>
 <td colspan="16" width="512" class="text">
 <p style="padding-left:5px;"> 42. I declare under oath that I have personally accomplished this Personal Data Sheet which is true, correct and <br/>
 complete statement pursuant to the provisions of pertinent laws, rules and regulations of the Republic of the <br/>
 Philippines. I authorize the agency head/authorized representative to verify/validate the contents stated herein. <br/>
 I agree that any misinterpretation made in this document and its attachments shall cause the filing of administrative/criminal case/s against me.  
 </p>
 </td>
</tr>
<tr>
	 <td class="noRightBot" colspan="8" align="center" style="padding:5px 5px 5px 5px;">
   <table border="1" style="cellspacing:0px; padding:0px 0px 0px 0px; border-collapse:collapse; border-color:#000000; font-size:10px; font-family:arial" width="100%" >
   <tr>
    <td class="text" align="left" colspan="2">
    	<font style="font-size:7px">Government Issued ID</font>
    	<span style="color:#990000; font-size:7px">(i.e. Passport, GSIS, SSS, PRC, Driver's License, etc.)</span><br/>
    	<font style="font-style:italic">PLESE INDICATE ID Number and Date of Issuance</font>
    </td>
   </tr>
   <tr>
    <td class="text2 noRight" align="left" valign="center">
    	<span style="font-size:8px">Government Issued ID </span> <br/><br/>
      {{$data['addinfo']['addinfo_gov']}}
    </td>
    <td  class="text2 noLeft" align="left" valign="center">
    	<span style="font-size:9px"></span> <br/><br/>
    </td>
   </tr>
   <tr>
    <td class="text2 noRight" align="left" valign="center">
    	<span style="font-size:8px">ID/License/Passport No.: </span> <br/><br/>
      {{$data['addinfo']['addinfo_gov_id']}}
    </td>
    <td  class="text2 noLeft" align="left" valign="center">
    	<span style="font-size:9px"></span><br/><br/>
    </td>
   </tr>
   <tr>
    <td class="text2 noRight" align="left" valign="center">
    	<span style="font-size:8px">Date/Place of Issuance: </span> <br/><br/>
      {{$data['addinfo']['addinfo_gov_place_date']}}
    </td>
    <td  class="text2 noLeft" align="left" valign="center">
    	<span style="font-size:9px"></span><br/><br/>
    </td>
   </tr>
  </table> 
 </td>
 <td colspan="8" width="320" class="noLeftBot noRight " align="center">
  <table border="1" style="cellspacing:0px; padding:0px 0px 0px 0px; border-collapse:collapse; border-color:#000000; font-size:10px; font-family:arial" width="100%">
   <tr>
    <td><br /><br /><br />&nbsp;</td>
   </tr>
   <tr>
    <td class="text" align="center">Signature(Sign inside the box)</td>
   </tr>
  </table> 
  <table border="1" style="border-collapse:collapse; border-color:#000000; font-size:10px; font-family:arial" width="100%">
   <tr>
    <td><br /><br /><br /></td>
   </tr>
   <tr>
    <td class="text" align="center">Date Accomplished</span></td>
   </tr>
  </table> 
 </td> 
 <td class=" noLeft noTop noBot"  colspan="5" width="160" align="center">
  <table border="1" style="border-collapse:collapse; border-color:#000000; font-size:10px; font-family:arial" width="90%">
   <tr>
    <td><br /><br /><br /><br /><br /><br /><br />&nbsp;</td>
   </tr>
  </table>
  Right Thumbmark
 </td>
</tr>
<tr>
 <td class="noBot" colspan="21" width="672" align="center">
 	<br/>
	<font style="font-size:9px">
		SUBSCRIBED AND SWORN to me before this ________________________, affiant exhibiting his/her validly issued government ID as indicated above.
	</font>
 </td>
</tr>
<tr>
 <td class="noTop" colspan="21" width="672" align="center" style="padding:5px 0px 5px 0px">
 	<table border="1" style="cellspacing:0px; padding:0px 0px 0px 0px; border-collapse:collapse; border-color:#000000; font-size:10px; font-family:arial" width="40%">
   		<tr>
	    	<td><br /><br /><br />&nbsp;</td>
   		</tr>
   		<tr>
	    <td class="text" align="center">Person Administering Oath</td>
	   </tr>
  	</table> 
 </td>
</tr>
<tr style="display: none;">
 <td width="32" class="end">&nbsp;</td>
 <td width="32" class="end">&nbsp;</td>
 <td width="32" class="end">&nbsp;</td>
 <td width="32" class="end">&nbsp;</td>
 <td width="32" class="end">&nbsp;</td>
 <td width="32" class="end">&nbsp;</td>
 <td width="32" class="end">&nbsp;</td>
 <td width="32" class="end">&nbsp;</td>
 <td width="32" class="end">&nbsp;</td>
 <td width="32" class="end">&nbsp;</td>
 <td width="32" class="end">&nbsp;</td>
 <td width="32" class="end">&nbsp;</td>
 <td width="32" class="end">&nbsp;</td>
 <td width="32" class="end">&nbsp;</td>
 <td width="32" class="end">&nbsp;</td>
 <td width="32" class="end">&nbsp;</td>
 <td width="32" class="end">&nbsp;</td>
 <td width="32" class="end">&nbsp;</td>
 <td width="32" class="end">&nbsp;</td>
 <td width="32" class="end">&nbsp;</td>
 <td width="32" class="end">&nbsp;</td>
</tr>
<!--tr>
 <td colspan="21" width="672" align="right" class="text">CS FORM 212 (Revised 2005)&nbsp;&nbsp;</td>
</tr-->
</table>
</div>
<!-- jQuery -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/jquery/jquery.min.js') }}"></script>
<script>
  $('#civil_{{$data['basicinfo']['basicinfo_civilstatus']}}').prop('checked', true);
</script>
</center>
</body>
</html>

