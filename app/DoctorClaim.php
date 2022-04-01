<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DoctorClaim extends Model
{
    use SoftDeletes;
    protected $fillable = ['claim_amount', 'insurance_amount', 'cash_amount', 'claim_rate_id', 'appointment_id', '_who_added'];
}
