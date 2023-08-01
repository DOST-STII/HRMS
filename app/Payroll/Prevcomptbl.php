<?php

namespace App\Payroll;

use Illuminate\Database\Eloquent\Model;

class Prevcomptbl extends Model
{
    protected $connection = 'payroll';
    protected $table = 'tblprevcompen';
}
