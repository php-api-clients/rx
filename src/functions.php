<?php declare(strict_types=1);

namespace ApiClients\Tools\Rx;

use React\Promise\PromiseInterface;
use Rx\Observable;
use Rx\ObserverInterface;
use Rx\SchedulerInterface;

/**
 * Take an observable from a promise and return an new observable piping through the stream.
 *
 * @param PromiseInterface $promise
 * @return Observable
 */
function unwrapObservableFromPromise(PromiseInterface $promise): Observable
{
    return Observable::create(
        function (
            ObserverInterface $observer,
            SchedulerInterface $scheduler
        ) use ($promise) {
            $promise->then(function (Observable $observable) use ($observer, $scheduler) {
                $observable->subscribeCallback(
                    function ($next) use ($observer) {
                        $observer->onNext($next);
                    },
                    function ($error) use ($observer) {
                        $observer->onError($error);
                    },
                    function () use ($observer) {
                        $observer->onCompleted();
                    }
                );
            });
        }
    );
}
