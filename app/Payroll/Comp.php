<?php

namespace App\Payroll;

use Illuminate\Database\Eloquent\Model;

class Comp extends Model
{
    protected $connection = 'payroll';
    protected $table = "compensations";
    protected $primaryKey = null;
}
