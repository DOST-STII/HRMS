<?php

namespace App\Payroll;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MCDeduc extends Model
{
    use SoftDeletes;
    protected $connection = 'payroll';
    protected $table = 'deductmc';
}
