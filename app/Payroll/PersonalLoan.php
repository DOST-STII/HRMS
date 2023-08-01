<?php

namespace App\Payroll;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PersonalLoan extends Model
{
    use SoftDeletes;
    protected $connection = 'payroll';
    protected $table = 'view_personal_loan';
}
