<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalaryDeduction extends Model
{
    use SoftDeletes;
    protected $fillable = ['deduction','deduction_amount','pay_slip_id','_who_added'];
}
