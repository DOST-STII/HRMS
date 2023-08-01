<?php

namespace App\Payroll;

use Illuminate\Database\Eloquent\Model;

class OrganizationService extends Model
{
    protected $connection = 'payroll';
    protected $table = 'view_org_service';
    protected $primaryKey = null;
}
