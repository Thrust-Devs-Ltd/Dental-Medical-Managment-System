<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Allergy extends Model
{
    use SoftDeletes;
    protected $fillable = ['drug', 'body_reaction', 'status', 'patient_id', '_who_added'];
}
