<?php

use Muchrm\Graylog\GraylogHandler;

class HandlerTest extends AbstractTest
{
    /**
     * Test enabling and disabling of
     * Graylog error reporting.
     */
    public function testEnabling()
    {
        $handler = new GraylogHandler();
        $this->assertFalse($handler->handle([]));

        $this->app['config']->set('graylog.enabled', false);
        $this->assertFalse($handler->handle([]));
    }
}
