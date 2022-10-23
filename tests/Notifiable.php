<?php

namespace NotificationChannels\Smsgo\Test;

class Notifiable
{
    use \Illuminate\Notifications\Notifiable;

    public function routeNotificationForSMSGo(): string
    {
        return '0900123456';
    }
}
