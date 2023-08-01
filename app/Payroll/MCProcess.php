<?php

namespace App\Payroll;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MCProcess extends Model
{
    use SoftDeletes;
    protected $table = 'mc_processeds';
}
