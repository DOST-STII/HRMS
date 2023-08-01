<?php

namespace App\Payroll;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LWOP extends Model
{
    use SoftDeletes;
    protected $connection = 'payroll';
    protected $table = 'emp_lwop';
}
