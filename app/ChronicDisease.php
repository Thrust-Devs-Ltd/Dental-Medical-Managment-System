<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChronicDisease extends Model
{
    use SoftDeletes;
    protected $fillable = ['disease', 'status', 'patient_id', '_who_added'];
}
