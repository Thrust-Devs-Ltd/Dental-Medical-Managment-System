<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaySlip extends Model
{
    Use SoftDeletes;
    protected $fillable = ['payslip_month','employee_id','employee_contract_id','_who_added'];
}
