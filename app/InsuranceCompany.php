<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InsuranceCompany extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'email', 'phone_no', '_who_added'];
}
