<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employment extends Model
{
    //
    use SoftDeletes;
    protected $primaryKey = 'employment_id';
}
