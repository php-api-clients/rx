<?php declare(strict_types=1);

namespace ApiClients\Tools\Rx;

use Exception;
use React\EventLoop\LoopInterface;
use React\Promise\PromiseInterface;
use Rx\Observable;
use Rx\Scheduler;
use Throwable;

/**
 * Take an observable from a promise and return an new observable piping through the stream.
 *
 * @param  PromiseInterface $promise
 * @return Observable
 */
function unwrapObservableFromPromise(PromiseInterface $promise): Observable
{
    return Observable::fromPromise($promise)->mergeAll();
}

/**
 * Take an array and return an observable from it with an immediate scheduler for scheduling.
 *
 * @param  array      $array
 * @return Observable
 */
function observableFromArray(array $array): Observable
{
    return Observable::fromArray($array, Scheduler::getImmediate());
}

/**
 * @param  LoopInterface $loop
 * @throws Throwable
 */
function setAsyncScheduler(LoopInterface $loop): void
{
    try {
        Scheduler::setAsyncFactory(function () use ($loop) {
            return new Scheduler\EventLoopScheduler($loop);
        });
    } catch (Exception $e) {
        if ($e->getMessage() === 'The async factory can not be set after the scheduler has been created') {
            return;
        }

        throw $e;
    }
}
