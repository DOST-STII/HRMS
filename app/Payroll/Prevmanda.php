<?php

namespace App\Payroll;

use Illuminate\Database\Eloquent\Model;

class Prevmanda extends Model
{
    protected $connection = 'payroll';
    public $timestamps = false;
    protected $table = 'view_prev_manda_deduction';
}
