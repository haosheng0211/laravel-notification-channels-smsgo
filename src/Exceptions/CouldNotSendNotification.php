<?php

namespace NotificationChannels\SMSGo\Exceptions;

use Exception;

class CouldNotSendNotification extends Exception
{
    public static function missingTo(): self
    {
        return new static('Notification was not sent. Missing `to` number.');
    }

    public static function serviceRespondedWithAnError(string $exception): self
    {
        return new static('SMSGo responded with an error: ' . $exception);
    }
}
