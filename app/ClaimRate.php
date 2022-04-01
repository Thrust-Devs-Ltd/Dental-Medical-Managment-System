<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClaimRate extends Model
{
    use SoftDeletes;
    protected $fillable = ['cash_rate', 'insurance_rate', 'status', 'doctor_id', '_who_added'];
}
