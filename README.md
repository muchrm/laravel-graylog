##Notic
This is forcked version of [swisnl/laravel-graylog2](https://github.com/swisnl/laravel-graylog2)
i'm using it on my project 
Don't use me yet.
# Graylog Logging for Laravel 5.x
[![Latest Stable Version](https://poser.pugx.org/muchrm/laravel-graylog/v/stable)](https://packagist.org/packages/muchrm/laravel-graylog)
[![Build Status](https://travis-ci.org/muchrm/laravel-graylog.svg?branch=master)](https://travis-ci.org/muchrm/laravel-graylog)

## Installation

1. Run composer require for this package: `composer require muchrm/laravel-graylog`
2. Add the service provider to app.php if you don't like auto discovery: `Muchrm\Graylog\GraylogServiceProvider`
3. Run `php artisan vendor:publish` to publish the config file to ./config/graylog.php.
4. Configure it to your liking
5. Done!

## Logging exceptions
The default settings enable logging of exceptions. It will add the HTTP request to the GELF message, but it will not add POST values. Check the graylog2.log-requests config to enable or disable this behavior.

## Message Processors 
Processors add extra functionality to the handler. You can register processors by modifying the AppServiceProvider:
```php
public function register()
{
    //...
    Graylog::registerProcessor(new \Muchrm\Graylog\Processor\ExceptionProcessor());
    Graylog::registerProcessor(new \Muchrm\Graylog\Processor\RequestProcessor());
    Graylog::registerProcessor(new MyCustomProcessor());
    //...
}
```

The following processors are available by default:

**ExceptionProcessor**

Adds exception data to the message if there is any.

**RequestProcessor**

Adds the current Laravel Request to the message. It adds the url, method and ip by default.

## Custom processors
You can define a custom processor by implementing `Muchrm\Graylog\Processor\ProcessorInterface`. The result should look something like this:

```php
<?php

namespace App\Processors;

use Auth;
use Muchrm\Graylog\Processor\ProcessorInterface;

class MyCustomProcessor implements ProcessorInterface
{
    public function process($message, $exception, $context)
    {
        $message->setAdditional('domain', config('app.url'));

        if (Auth::user()) {
            $message->setAdditional('user_id', Auth::id());
        }

        return $message;
    }
}

```

## Don't report exceptions
In `app/Exceptions/Handler.php` you can define the $dontReport array with Exception classes that won't be reported to the logger. For example, you can blacklist the \Illuminate\Database\Eloquent\ModelNotFoundException. Check the [Laravel Documentation](https://laravel.com/docs/master/errors#the-exception-handler) about errors for more information.

## Logging arbitrary data
You can instantiate the Graylog class to send additional GELF messages:

```php
// Send default log message
Graylog::log('emergency', 'Dear Sir/Madam, Fire! Fire! Help me!. 123 Cavendon Road. Looking forward to hearing from you. Yours truly, Maurice Moss.', ['facility' => 'ICT']);

// Send custom GELF Message
$message = new \Gelf\Message();
$message->setLevel('emergency');
$message->setShortMessage('Fire! Fire! Help me!');
$message->setFullMessage('Dear Sir/Madam, Fire! Fire! Help me!. 123 Cavendon Road. Looking forward to hearing from you. Yours truly, Maurice Moss.');
$message->setFacility('ICT');
$message->setAdditional('employee', 'Maurice Moss');
Graylog::logMessage($message);
```

## Troubleshooting

### Long messages (or exceptions) won't show up in Graylog
You might need to increase the size of the UDP chunks in the UDP Transport (see the config file). Otherwise, you can send packets in TCP mode.