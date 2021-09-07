<?php

declare(strict_types=1);

namespace ApiClients\Tests\Tools\Rx;

use ApiClients\Tools\TestUtilities\TestCase;
use React\EventLoop\Loop;
use Rx\Scheduler;

use function ApiClients\Tools\Rx\setAsyncScheduler;

/**
 * @internal
 */
final class FunctionsSetAsyncSchedulerTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testSetAsyncScheduler(): void
    {
        setAsyncScheduler(Loop::get());
        $scheduler = Scheduler::getAsync();
        setAsyncScheduler(Loop::get());
        self::assertSame($scheduler, Scheduler::getAsync());
    }
}
