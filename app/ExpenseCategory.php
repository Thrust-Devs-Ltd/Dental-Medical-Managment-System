<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpenseCategory extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'chart_of_account_item_id', '_who_added'];

    public function ExpenseAccount()
    {
        return $this->belongsTo('App\ChartOfAccountItem', 'chart_of_account_item_id');
    }

    public function addedBy()
    {
        return $this->belongsTo('App\User', '_who_added');
    }
}
