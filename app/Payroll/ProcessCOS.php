<?php

namespace App\Payroll;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProcessCOS extends Model
{
    use SoftDeletes;
    protected $connection = 'payroll';
    protected $table = 'cos_process';
}
