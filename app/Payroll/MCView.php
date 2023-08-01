<?php

namespace App\Payroll;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MCView extends Model
{
    use SoftDeletes;
    protected $table = 'view_mcs';
}
