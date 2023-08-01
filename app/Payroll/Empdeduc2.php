<?php

namespace App\Payroll;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empdeduc2 extends Model
{
    use SoftDeletes;
    protected $connection = 'payroll';
    protected $table = 'empdeduct';
    // protected $table = 'deduct_new';
}
