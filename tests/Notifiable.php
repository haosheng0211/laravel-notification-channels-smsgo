<?php

namespace NotificationChannels\SMSGo\Test;

class Notifiable
{
    use \Illuminate\Notifications\Notifiable;

    public function routeNotificationForSMSGo(): string
    {
        return '+8860900123456';
    }
}
