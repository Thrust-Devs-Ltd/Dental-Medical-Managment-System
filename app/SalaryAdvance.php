<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalaryAdvance extends Model
{
    use SoftDeletes;
    protected $fillable = ['payment_classification', 'employee_id', 'advance_month',
        'advance_amount', 'payment_method', 'payment_date', '_who_added'];
}
