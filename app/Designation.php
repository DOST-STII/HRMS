<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Designation extends Model
{
    //
    use SoftDeletes;
    protected $primaryKey = 'designation_id';
}
