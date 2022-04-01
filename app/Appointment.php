<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class  Appointment extends Model
{
    use SoftDeletes;
    protected $fillable = ['appointment_no', 'start_date', 'end_date', 'start_time', 'notes',
        'visit_information', 'status', 'doctor_id', 'branch_id',
        'patient_id', 'sort_by', '_who_added'];

    public function doctor()
    {
        return $this->belongsTo('App\User', 'doctor_id');
    }

    public function patient()
    {
        return $this->belongsTo('App\Patient', 'patient_id');
    }

    public static function AppointmentNo()
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
