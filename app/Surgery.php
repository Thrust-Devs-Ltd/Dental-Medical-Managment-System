<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Surgery extends Model
{
    use SoftDeletes;
    protected $fillable = ['surgery', 'surgery_date', 'description', 'patient_id', '_who_added'];

    public function addedBy()
    {
        return $this->belongsTo('App\User', '_who_added');
    }
}
