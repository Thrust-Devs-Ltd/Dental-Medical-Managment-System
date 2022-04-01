<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicalCardItem extends Model
{
    use SoftDeletes;
    protected $fillable = ['card_photo', 'medical_card_id', '_who_added'];
}
