<?php

namespace Muchrm\Graylog\Facades;

use Illuminate\Support\Facades\Facade;

class Graylog extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'graylog';
    }
}
