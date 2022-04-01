<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Thomasjohnkane\Snooze\Traits\SnoozeNotifiable;

class Patient extends Model
{
    use Notifiable, SoftDeletes, SnoozeNotifiable;
    protected $fillable = ['patient_no', 'surname', 'othername', 'gender', 'dob', 'email', 'phone_no',
        'alternative_no', 'address', 'nin', 'photo', 'profession', 'next_of_kin', 'next_of_kin_no',
        'next_of_kin_address', 'has_insurance', 'insurance_company_id', '_who_added'];

    public function routeNotificationForSms($notifiable)
    {
        return 'identifier-from-notification-for-sms: ' . $this->id;
    }


    public function InsuranceCompany()
    {
        return $this->belongsTo('App\InsuranceCompany', 'insurance_company_id');
    }

    public static function PatientNumber()
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
