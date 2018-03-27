<?php

namespace Muchrm\Graylog;

use Illuminate\Support\Facades\Log;
use Monolog\Handler\AbstractHandler;

class GraylogHandler extends AbstractHandler
{
    /**
     * {@inheritdoc}
     */
    public function handle(array $record)
    {
        // Check if we should send the message to Graylog
        if (!config('graylog.enabled')) {
            return false;
        }

        // Handle a log from Laravel
        /** @var Graylog $graylog */
        $graylog = app('graylog');

        try {
            $graylog->log(
                strtolower($record['level_name']),
                $record['message'],
                $record['context']
            );

            return false;
        } catch (\Exception $e) {
            Log::info('Could not log to Graylog.');

            return false;
        }
    }
}
