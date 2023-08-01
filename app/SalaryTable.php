<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class SalaryTable extends Model
{
    // use SoftDeletes;

    protected $fillable = [
        'salary_grade','salary_1','salary_2','salary_3','salary_4', 'salary_5','salary_6','salary_7','salary_8'
    ];
}
