<?php

namespace App\Payroll;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MC extends Model
{
    use SoftDeletes;
    protected $table = 'employee_mcs';
}
