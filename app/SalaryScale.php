<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalaryScale extends Model
{
    use SoftDeletes;
    protected $fillable = ['employee_id', 'amount', '_who_added'];

    public function AddedBy()
    {
        return $this->belongsTo('App\User', '_who_added');
    }

    public function employee()
    {
        return $this->belongsTo('App\User', 'employee_id');
    }
}
