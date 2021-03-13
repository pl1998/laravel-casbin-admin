<?php

namespace Casbin\Bridge\Logger\Tests;

use Mockery;
use Casbin\Bridge\Logger\LoggerBridge;
use PHPUnit\Framework\TestCase;

class LoggerTest extends TestCase
{
    public function testEnableLogger()
    {
        $testLog = Mockery::mock(TestLogger::class);

        $defaultLevel = 'info';

        $logger = new LoggerBridge($testLog, $defaultLevel);
        $this->assertFalse($logger->isEnabled());

        $logger->enableLog(true);
        $this->assertTrue($logger->isEnabled());

        $testLog->shouldReceive('log')->once()->with($defaultLevel, 'foo');
        $logger->write('foo');

        $testLog->shouldReceive('log')->once()->with($defaultLevel, 'foo1foo2');
        $logger->write('foo1', 'foo2');

        $testLog->shouldReceive('log')->once()->with($defaultLevel, json_encode(['foo1', 'foo2']));
        $logger->write(['foo1', 'foo2']);

        $testLog->shouldReceive('log')->once()->with($defaultLevel, sprintf('There are %u million cars in %s.', 2, 'Shanghai'));
        $logger->writef('There are %u million cars in %s.', 2, 'Shanghai');

        $testLog = Mockery::mock(TestLogger::class);

        $logger = new LoggerBridge($testLog);

        $logger->enableLog(false);

        $testLog->shouldNotHaveReceived('log');
        $logger->write(['foo1', 'foo2']);

        $testLog->shouldNotHaveReceived('log');
        $logger->writef('There are %u million cars in %s.', 2, 'Shanghai');
    }

    public function testDisableLogger()
    {
        $testLog = Mockery::mock(TestLogger::class);

        $defaultLevel = 'info';

        $logger = new LoggerBridge($testLog, $defaultLevel);

        $logger->enableLog(false);
        $this->assertFalse($logger->isEnabled());

        $logger->enableLog(false);

        $testLog->shouldNotHaveReceived('log');
        $logger->write(['foo1', 'foo2']);

        $testLog->shouldNotHaveReceived('log');
        $logger->writef('There are %u million cars in %s.', 2, 'Shanghai');
    }
}
