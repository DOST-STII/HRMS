<?php

namespace App\Payroll;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prevdeduc extends Model
{
    use SoftDeletes;
    protected $connection = 'payroll';
    public $timestamps = false;
    protected $table = 'view_prev_personal_loan';
}
