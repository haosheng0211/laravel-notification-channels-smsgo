# SMSGo Notification Channel

📲 [SMSGo](https://www.smsgo.com.tw/) Notifications Channel for Laravel

## Contents

- [Installation](#installation)
	- [Setting up the SMSGo service](#setting-up-the-SMSGo-service)
- [Usage](#usage)
	- [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)

## Installation

```bash
composer require smsgo/smsgo laravel-notification-channels/smsgo
```

Add the configuration to your `services.php` config file:

```php
'smsgo' => [
    'username' => env('SMSGO_USERNAME'),
    'password' => env('SMSGO_PASSWORD')
]
```

### Setting up the SMSGo service

You'll need an SMSGo account. Head over to their [website](https://example.com/) and create or log in to your account.

Generate API credentials by navigating to the API section in your account settings.

## Usage

You can use the channel in your `via()` method inside the notification:

```php
use Carbon\Carbon;
use Illuminate\Notifications\Notification;
use NotificationChannels\SMSGo\SMSGoMessage;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return ['smsgo'];
    }

    public function toSMSGo($notifiable)
    {
        return (new SMSGoMessage)
            ->content("Task #{$notifiable->id} is complete!")->encoding('BIG5')->delay(Carbon::now()->addDays(3));
    }
}
```

In your notifiable model, make sure to include a `routeNotificationForSMSGo()` method, which returns a phone number in the appropriate format.

```php
public function routeNotificationForSMSGo()
{
    return $this->phone; // Example: +1234567890 , need to include country code
}
```

### Available methods

`content()`: Set the content of the notification message.

`encoding()`: Set the encoding of the notification message.

`delay()`: Set the delay of the notification message.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

```bash
$ composer test
```

## Security

If you discover any security-related issues, please contact support@example.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see the [License File](LICENSE.md) for more information.