<?php

namespace App\PIS;

use Illuminate\Database\Eloquent\Model;

class EmpPos extends Model
{
    public $primaryKey = 'fldEmpPosID';
    public $timestamps = false;
    protected $table = 'tblemppos';
}
