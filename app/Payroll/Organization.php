<?php

namespace App\Payroll;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $connection = 'payroll';
    protected $primaryKey = null;
}
