<?php

namespace App\Payroll;

use Illuminate\Database\Eloquent\Model;

class RemitPrevSalary extends Model
{
    protected $connection = 'payroll';
    protected $table = 'view_prevsalary';
}
