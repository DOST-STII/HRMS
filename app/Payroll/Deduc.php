<?php

namespace App\Payroll;

use Illuminate\Database\Eloquent\Model;

class Deduc extends Model
{
    protected $connection = 'payroll';
    protected $table = "deductmanda";
    protected $primaryKey = null;
}
