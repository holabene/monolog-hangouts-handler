<?php

namespace Tests\Unit\Log;

use Mockery;
use Mockery\MockInterface;
use Monolog\Logger;
use Nowyn\Monolog\HangoutsChatHandler;
use PHPUnit\Framework\TestCase;

class HangoutsLogHandlerTest extends TestCase
{

    public function testCanSendLogMessagesToHangouts()
    {
        $logger = new Logger('test');


        /** @var MockInterface|HangoutsChatHandler $hangoutsDriver */
        $hangoutsDriver = Mockery::mock(
            HangoutsChatHandler::class . '[write]',
            ['https://test.example.com', null, Logger::INFO]
        )->shouldAllowMockingProtectedMethods();

        $hangoutsDriver->shouldReceive('write')->once();

        $logger->pushHandler($hangoutsDriver);

        $logger->critical('test message');

        $this->assertTrue(true);
    }
}
