<?php

namespace NotificationChannels\SMSGo;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Notifications\Notification;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use NotificationChannels\SMSGo\Exceptions\CouldNotSendNotification;
use SMSGo\SMSGo;

class SMSGoChannel
{
    protected $client;

    public function __construct(SMSGo $client)
    {
        $this->client = $client;
    }

    /**
     * @throws CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        if (! $to = $notifiable->routeNotificationFor('smsgo')) {
            throw CouldNotSendNotification::missingTo();
        }

        $phone = $this->parsePhoneNumber($to);

        if ($phone->getCountryCode() === 886) {
            $to = $this->formatTaiwanPhoneNumber($phone);
        } else {
            $to = $this->formatGlobalPhoneNumber($phone);
        }

        if (! method_exists($notification, 'toSMSGo')) {
            throw new \InvalidArgumentException('Notification does not have a toSMSGo method');
        }

        $message = $notification->toSMSGo($notifiable);

        if (is_string($message)) {
            $message = new SMSGoMessage($message);
        }

        try {
            $response = $this->client->sendSMS($to, $message->content, $message->encoding, [
                'dlvtime' => $message->delay,
                'rtype'   => 'JSON',
            ]);

            $response = json_decode($response, true);

            if ((int) $response['result']['statuscode'] !== 0) {
                throw CouldNotSendNotification::serviceRespondedWithAnError($response['result']['statusstr']);
            }
        } catch (GuzzleException $exception) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($exception);
        }
    }

    /**
     * @throws CouldNotSendNotification
     */
    public function parsePhoneNumber(string $to): PhoneNumber
    {
        try {
            return PhoneNumberUtil::getInstance()->parse($to);
        } catch (NumberParseException $exception) {
            throw CouldNotSendNotification::invalidPhoneNumber();
        }
    }

    public function formatGlobalPhoneNumber(PhoneNumber $phone): string
    {
        $number = PhoneNumberUtil::getInstance()->format($phone, PhoneNumberFormat::E164);

        return str_replace('+', '%2b', $number);
    }

    public function formatTaiwanPhoneNumber(PhoneNumber $phone): string
    {
        $number = $phone->getNationalNumber();

        return "0{$number}";
    }
}
