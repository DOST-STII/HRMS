<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee_leave extends Model
{
    //
    public function requestLeave()
    {
        return $this->hasOne(Request_leave::class, 'leave_id', 'leave_id')
            ->where('user_id', $this->user_id);
    }

protected $fillable = ['leave_bal', /* other fillable columns */];
}
