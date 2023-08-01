<?php

function getRequestForApproval($userid = null)
{
	if(!$userid)
	{
		if(Auth::user()->usertype == 'Administrator')
			$list = App\View_request_leave::where('parent','YES')->whereIn('leave_action_status',['Pending','Monetized'])->get();
		else
			/* $list = App\Request_leave::where('parent','YES')
			->whereIn('leave_action_status',['Pending','Monetized'])
			->join('users', 'request_leaves.user_id', '=', 'users.id')
			->join('leave_types', 'request_leaves.leave_id', '=', 'leave_types.id')
			->get(['request_leaves.*', 'users.*', 'leave_types.*']); */

			$list = App\Request_leave::where('parent', 'YES')
			->whereIn('leave_action_status', ['Pending', 'Monetized'])
			->with('user') // Eager loading the 'user' relationship
			->with('leave_type') // Eager loading the 'user' relationship
			->get(); // Selecting only the relevant columns from 'request_leaves' table
			
	}
	else
	{
		if(Auth::user()->usertype == 'Marshal')
			//$list = App\View_request_leave::where('division',Auth::user()->division)->where('parent','YES')->whereIn('leave_action_status',['Pending','Monetized'])->get();
			$list = App\Request_leave::where('id',$id)->where('parent','YES')->whereIn('leave_action_status',['Pending','Monetized'])
			->join('users', 'request_leaves.user_id', '=', 'users.id')
			->join('leave_types', 'request_leaves.leave_id', '=', 'leave_types.id')
			->get(['request_leaves.*', 'users.*', 'leave_types.*']);
		else
			$list = App\Request_leave::where('user_id',$userid)->where('parent','YES')->whereIn('leave_action_status',['Pending','Monetized'])
			->join('users', 'request_leaves.user_id', '=', 'users.id')
			->join('leave_types', 'request_leaves.leave_id', '=', 'leave_types.id')
			->get(['request_leaves.*', 'users.*', 'leave_types.*']);
	}
	
	return $list;
}

function getRequestForApprovalDirector()
{
	$list = App\View_request_leave::where('director', 'YES')->where('parent','YES')->where('leave_action_status','Pending')->get();
	return $list;
}

function getRequestForApproval15()
{
	$list = App\View_request_leave::where('leave_deduction','>=',15)->where('parent','YES')->where('leave_action_status','Pending')->get();
	return $list;
}

function getRequestForTOApproval()
{

	if(Auth::user()->usertype == 'Director' && Auth::user()->division == 'O')
	{
		if(Auth::user()->usertype == 'Director')
			{
				$list = App\RequestTO::where('parent','YES')->where('to_status','Pending')
						->where(function($q) {
						          $q->where('division',Auth::user()->division)
						            ->orWhere('director', 'YES');
						      })
						->get();
			}
			else
			{
				$list = App\RequestTO::where('parent','YES')->where('to_status','Pending')
					->where(function($q) {
						          $q->where('division',Auth::user()->division)
						            ->orWhere('director', 'YES');
						      })
					->get();
			}
	}
	elseif(Auth::user()->usertype == 'Administrator')
	{
		$list = App\RequestTO::where('parent','YES')->where('to_status','Pending')->get();
	}
	elseif(Auth::user()->usertype == 'Staff')
	{
		$list = App\RequestTO::where('parent','YES')->where('to_status','Pending')->where('userid',Auth::user()->id)->get();
	}
	else
	{
		if(Auth::user()->usertype == 'Director')
			{
				$list = App\RequestTO::where('to_status','Pending')->get();
			}
		else
			{
				$list = App\RequestTO::where('to_status','Pending')->get();
			}
	}

	return $list;
}

function getRequestForOTApproval()
{
	if(Auth::user()->usertype == 'Marshal' && Auth::user()->division == 'O')
	{
		$list = App\RequestOT::where('director', 'YES')->orWhere('division','O')->where('ot_status','Pending')->get();
	}
	elseif(Auth::user()->usertype == 'Administrator')
	{
		$list = App\RequestOT::whereIn('ot_status',['Pending','OED Approved','Time Edited'])->get();
	}
	elseif(Auth::user()->usertype == 'Staff' || Auth::user()->usertype == 'Director')
	{
		$list = App\RequestOT::where('userid',Auth::user()->id)->whereIn('ot_status',['Pending','OED Approved','Time Edited'])->get();
	}
	elseif(Auth::user()->usertype == 'Marshal')
	{
		$list = App\RequestOT::whereIn('ot_status',['Pending','OED Approved','Time Edited'])->get();
	}
	
	
	return $list;
}





