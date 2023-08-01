<?php

namespace App\Payroll;

use Illuminate\Database\Eloquent\Model;

class OrgServ extends Model
{
    protected $connection = 'payroll';
    protected $table = 'org_serv';
}