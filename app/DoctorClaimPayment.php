<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DoctorClaimPayment extends Model
{
    use SoftDeletes;
    protected $fillable = ['amount', 'payment_date', 'doctor_claim_id', '_who_added'];
}
