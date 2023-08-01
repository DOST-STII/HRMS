<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee_dtr extends Model
{
    use SoftDeletes;
    protected $table = "employee_dtrs";
}
