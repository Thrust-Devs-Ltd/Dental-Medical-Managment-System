<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppointmentHistory extends Model
{
    use SoftDeletes;
    protected $fillable = ['start_date', 'end_date', 'start_time', 'status', 'message', 'appointment_id'];
}
