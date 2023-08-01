<?php

namespace App\Payroll;

use Illuminate\Database\Eloquent\Model;

class COS_list extends Model
{
    protected $connection = 'payroll';
    protected $table = "cos_charging";
    protected $primaryKey = null;
}
