<?php

namespace App\Payroll;

use Illuminate\Database\Eloquent\Model;

class COS_Payroll extends Model
{
    protected $connection = 'payroll';
    protected $table = "cos_salary";
}
