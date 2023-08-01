<?php

namespace App\Payroll;

use Illuminate\Database\Eloquent\Model;

class View_PrevInfotbl extends Model
{
    protected $connection = 'payroll';
    public $primaryKey = 'fldPrevID';
    protected $table = 'view_prevempinfo';
}
