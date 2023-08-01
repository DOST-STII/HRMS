<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Request_leave_old extends Model
{
    protected $table = "request_leaves_old";
    use SoftDeletes;
}
