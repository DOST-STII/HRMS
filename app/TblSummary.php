<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TblSummary extends Model
{
    use SoftDeletes;
   	protected $table = "tblsummary"; 
}
