<?php declare(strict_types=1);

namespace ApiClients\Tools\Rx;

use React\Promise\PromiseInterface;
use Rx\Observable;
use Rx\ObserverInterface;
use Rx\SchedulerInterface;
use function React\Promise\resolve;

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
            ObserverInterface $observer
        ) use ($promise) {
            resolve($promise)->done(function (Observable $observable) use ($observer) {
                $observable->subscribe(
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
