<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeContract extends Model
{
    use SoftDeletes;
    protected $fillable = ['employee_id', 'contract_type', 'payroll_type', 'gross_salary', 'commission_percentage',
        'start_date', 'contract_length', 'contract_period', '_who_added', 'status'];
}
