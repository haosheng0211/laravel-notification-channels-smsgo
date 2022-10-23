<?php

namespace NotificationChannels\SMSGo;

use GuzzleHttp\Client;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class SMSGoServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->bind(SMSGo::class, function ($app) {
            return new SMSGo(new Client(), $app['config']['services.smsgo']);
        });

        Notification::resolved(function (ChannelManager $service) {
            $service->extend('smsgo', function ($app) {
                return new SMSGoChannel($app->make(SMSGo::class));
            });
        });
    }
}
