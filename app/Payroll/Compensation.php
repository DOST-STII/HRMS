<?php

namespace App\Payroll;

use Illuminate\Database\Eloquent\Model;

class Compensation extends Model
{
    protected $connection = 'payroll';
    protected $table = 'view_compensation';
    protected $primaryKey = null;
}
