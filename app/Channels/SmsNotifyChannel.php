<?php


namespace App\Channels;


use App\Jobs\SendAppointmentSms;
use Illuminate\Notifications\Notification;

class SmsNotifyChannel
{
    public function send($notifiable, Notification $notification)
    {

        if (method_exists($notifiable, 'routeNotificationForSms')) {
            $id = $notifiable->routeNotificationForSms($notifiable);
        } else {
            $id = $notifiable->getKey();
        }

        $data = method_exists($notification, 'toSms')
            ? $notification->toSms($notifiable)
            : $notification->toArray($notifiable);
        if (empty($data)) {
            return;
        }
        //send out notification sms
        dispatch(new SendAppointmentSms($data['phone_no'], $data['message'],"Reminder"));

        return true;
    }
}
