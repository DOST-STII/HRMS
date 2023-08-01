<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Request_leave extends Model
{
    use SoftDeletes;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function leave_type()
    {
        return $this->belongsTo(Leave_type::class, 'leave_id');
    }

    public function employeeLeave()
    {
        return $this->belongsTo(Employee_leave::class, 'leave_id', 'leave_id')
            ->where('user_id', $this->user_id);
    }
}
