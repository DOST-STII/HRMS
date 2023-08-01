<?php

namespace App\Payroll;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empdeduc extends Model
{
    use SoftDeletes;
    protected $connection = 'payroll';
    protected $table = 'deduct';
    // protected $table = 'deduct_new';
}
