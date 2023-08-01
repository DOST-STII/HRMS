<?php

namespace App\Payroll;

use Illuminate\Database\Eloquent\Model;

class PrevInfotbl extends Model
{
    protected $connection = 'payroll';
    public $primaryKey = 'fldPrevID';
    protected $table = 'tblprevempinfo';
}
