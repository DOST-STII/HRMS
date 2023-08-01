<?php

namespace App\Payroll;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MCDays extends Model
{
    use SoftDeletes;
    protected $table = 'employee_mc_days';
}
