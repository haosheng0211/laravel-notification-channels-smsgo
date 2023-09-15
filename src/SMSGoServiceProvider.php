<?php

namespace NotificationChannels\SMSGo;

use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;
use SMSGo\SMSGo;

class SMSGoServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->singleton(SMSGo::class, function ($app) {
            $username = $app['config']['services.smsgo.username'];
            $password = $app['config']['services.smsgo.password'];

            if (empty($username) || empty($password)) {
                throw new \InvalidArgumentException('Missing SMSGo config in services');
            }

            return new SMSGo($username, $password);
        });

        Notification::resolved(function (ChannelManager $service) {
            $service->extend('smsgo', function ($app) {
                return new SMSGoChannel($app->make(SMSGo::class));
            });
        });
    }

    public function provides(): array
    {
        return [SMSGo::class];
    }
}
