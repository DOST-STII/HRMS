<?php

namespace App\Payroll;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Benefit_remove extends Model
{
    use SoftDeletes;
    protected $connection = 'payroll';
}
