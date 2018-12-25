<?php declare(strict_types=1);

namespace ApiClients\Tests\Tools\Rx;

use ApiClients\Tools\TestUtilities\TestCase;
use React\EventLoop\Factory;
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
        $loop = Factory::create();
        setAsyncScheduler($loop);
        $scheduler = Scheduler::getAsync();
        setAsyncScheduler($loop);
        self::assertSame($scheduler, Scheduler::getAsync());
    }
}
