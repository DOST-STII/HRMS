<?php

namespace App\Payroll;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empcomp extends Model
{
    use SoftDeletes;
    protected $connection = 'payroll';
    protected $table = 'empcompensations';
    // protected $table = 'deduct_new';
}
