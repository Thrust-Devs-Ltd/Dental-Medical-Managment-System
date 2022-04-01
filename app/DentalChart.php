<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DentalChart extends Model
{
    use SoftDeletes;
    protected $fillable = ['tooth', 'position', 'color','kind',  'appointment_id', '_who_added'];
}
