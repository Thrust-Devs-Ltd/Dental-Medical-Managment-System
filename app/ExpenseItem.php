<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpenseItem extends Model
{
    use SoftDeletes;
    protected $fillable = ['expense_category_id','description', 'qty', 'price', 'expense_id', '_who_added'];

    public function AddedBy()
    {
        return $this->belongsTo('App\User', '_who_added');
    }
}
