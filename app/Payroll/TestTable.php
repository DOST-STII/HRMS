<?php

namespace App\Payroll;

use Illuminate\Database\Eloquent\Model;

class TestTable extends Model
{
    protected $connection = 'payroll';
    protected $table = 'testtbl';
}
