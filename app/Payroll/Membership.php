<?php

namespace App\Payroll;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $connection = 'payroll';
    protected $table = 'view_membership';
    protected $primaryKey = "EOID";
}
