<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicalService extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'price', '_who_added'];
}
