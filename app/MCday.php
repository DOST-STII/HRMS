<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MCday extends Model
{
	use SoftDeletes;
    protected $table = 'employee_mc_days';
}
