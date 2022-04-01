<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use smsNotify;

class ReminderNotification extends Notification
{
    use Queueable;
    protected $message;

    public function __construct($message)
    {
        return $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['smsNotify'];
    }

    public function toSms($notifiable)
    {
        $message = $this->message;
        return [
            'phone_no' => $notifiable->phone_no,
            'receiver' => $notifiable->othername,
            'message' => $message
        ];
    }

    public function toArray($notifiable)
    {
        return [
            'from' => 'to-array',
            'notifiable-id' => $notifiable->id,
        ];
    }
}
