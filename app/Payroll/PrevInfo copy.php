<?php

namespace App\Payroll;

use Illuminate\Database\Eloquent\Model;

class PrevInfo extends Model
{
    protected $connection = 'payroll';
    public $primaryKey = 'fldPrevID';
    public $timestamps = false;
    protected $table = 'tblprevempinfo';
}
