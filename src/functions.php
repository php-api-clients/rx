<?php

declare(strict_types=1);

namespace ApiClients\Tools\Rx;

use React\EventLoop\LoopInterface;
use React\Promise\PromiseInterface;
use Rx\Observable;
use Rx\Scheduler;
use Rx\SchedulerInterface;
use Throwable;

/**
 * Take an observable from a promise and return an new observable piping through the stream.
 */
function unwrapObservableFromPromise(PromiseInterface $promise): Observable
{
    return Observable::fromPromise($promise)->mergeAll();
}

/**
 * Take an array and return an observable from it with an immediate scheduler for scheduling.
 *
 * @param  array<mixed> $array
 */
function observableFromArray(array $array): Observable
{
    return Observable::fromArray($array, Scheduler::getImmediate());
}

/**
 * @throws Throwable
 */
function setAsyncScheduler(LoopInterface $loop): void
{
    try {
        Scheduler::setAsyncFactory(static fn (): SchedulerInterface => new Scheduler\EventLoopScheduler($loop));
    } catch (Throwable $e) {
        if ($e->getMessage() === 'The async factory can not be set after the scheduler has been created') {
            return;
        }

        throw $e;
    }
}
