<?php

namespace App\Payroll;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deduction extends Model
{
    use SoftDeletes;
    protected $connection = 'payroll';
    protected $table = 'view_deduction';
    protected $primaryKey = null;
}
