<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OnlineBooking extends Model
{
    protected $fillable = ['full_name', 'email', 'phone_no', 'start_date', 'end_date', 'start_time', 'message', 'visit_history', 'insurance_company_id', 'status'];
}
