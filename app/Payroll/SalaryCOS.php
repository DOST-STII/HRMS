<?php

namespace App\Payroll;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalaryCOS extends Model
{
    use SoftDeletes;
    protected $connection = 'payroll';
    protected $table = 'cos_salary';
}
