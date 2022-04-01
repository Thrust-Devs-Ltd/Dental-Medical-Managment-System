<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prescription extends Model
{
    use SoftDeletes;
    protected $fillable = ['drug', 'qty', 'directions', 'appointment_id', '_who_added'];
}
