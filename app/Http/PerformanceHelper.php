<?php

function countIPCR($div,$dpcr,$period,$deadline)
{
	//GET TOTAL
	$total = App\Performance_ipcr_staff::where('division_id',$div)->where('dpcr_id',$dpcr)->count();

	//SUBMITTED
	$sub = App\Performance_ipcr_staff::where('division_id',$div)->where('dpcr_id',$dpcr)->whereNotNull('ipcr_submitted_at')->count();

	echo "<span class='badge badge-success'>$sub/$total</span>";
}

function countDPCR($yr,$period,$deadline)
{
	//GET TOTAL
	$total = App\Performance_dpcr::where('dpcr_year',$yr)->where('dpcr_period',$period)->where('dpcr_deadline',$deadline)->count();

	//SUBMITTED
	$sub = App\Performance_dpcr::where('dpcr_year',$yr)->where('dpcr_period',$period)->where('dpcr_deadline',$deadline)->whereNotNull('submitted_at')->count();

	echo "<span class='badge badge-success'>$sub/$total</span>";
}