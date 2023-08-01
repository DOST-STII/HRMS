<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Camsdtr extends Model
{
	protected $connection = 'cams';
    protected $table = 'tblEmpDTR';
    protected $primaryKey = 'fldEmpDTRID';
}
