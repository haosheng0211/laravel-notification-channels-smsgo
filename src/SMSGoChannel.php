<?php

namespace NotificationChannels\SMSGo;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Notifications\Notification;
use InvalidArgumentException;
use NotificationChannels\SMSGo\Exceptions\CouldNotSendNotification;

class SMSGoChannel
{
    protected $client;

    public function __construct(SMSGo $client)
    {
        $this->client = $client;
    }

    public function send($notifiable, Notification $notification)
    {
        if (! $to = $notifiable->routeNotificationFor('smsgo')) {
            throw CouldNotSendNotification::missingTo();
        }

        if (! method_exists($notification, 'toSMSGo')) {
            throw new InvalidArgumentException('Notification does not have a toSMSGo method');
        }

        $message = $notification->toSMSGo($notifiable);

        if (is_string($message)) {
            $message = new SMSGoMessage($message);
        }

        try {
            $response = $this->client->send($message, (string) $to);

            if ((int) $response['result']['statuscode'] !== 0) {
                throw CouldNotSendNotification::serviceRespondedWithAnError($response['result']['statusstr']);
            }

            return $response;
        } catch (GuzzleException $exception) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($exception);
        }
    }
}
