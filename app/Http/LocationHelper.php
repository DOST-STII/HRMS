<?php

function list_location($tbl)
{
	switch ($tbl) {
		case 'barangay':
				$list = App\Location_barangay::get();
			break;
		case 'municipal':
				$list = App\Location_municipal::get();
			break;
		case 'province':
				$list = App\Location_province::get();
			break;
	}
	return $list;
}