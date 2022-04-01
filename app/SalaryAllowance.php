<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalaryAllowance extends Model
{
    use SoftDeletes;
    protected $fillable = ['allowance', 'allowance_amount', 'pay_slip_id', '_who_added'];
}
