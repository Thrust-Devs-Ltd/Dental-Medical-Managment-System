<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use SoftDeletes;
    protected $fillable = ['purchase_no', 'supplier_id', 'purchase_date', 'branch_id', '_who_added'];

    public function addedBy()
    {
        return $this->belongsTo('App\User', '_who_added');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Supplier', 'supplier_id');
    }

    public static function PurchaseNo()
    {
        $latest = self::latest()->first();
        if (!$latest) {
            return date('Y') . "" . '0001';
        } else if ($latest->deleted_at != "null") {
            //now use the time stamp
            return time() + $latest->id + 1;
        } else {
            return date('Y') . "" . sprintf('%04d', $latest->id + 1);
        }
    }
}
