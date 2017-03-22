<?php declare(strict_types=1);

namespace ApiClients\Tests\Tools\Rx;

use ApiClients\Tools\TestUtilities\TestCase;
use Exception;
use Rx\Observable;
use Rx\React\Promise;
use function ApiClients\Tools\Rx\unwrapObservableFromPromise;
use function React\Promise\reject;
use function React\Promise\resolve;

final class FunctionsTest extends TestCase
{
    public function testUnwrapObservableFromPromise()
    {
        $completed = false;
        $currentI = null;

        unwrapObservableFromPromise(
            resolve(
                Observable::fromArray(range(0, 1337))
            )
        )->subscribeCallback(
            function ($i) use (&$currentI) {
                $currentI = $i;
            },
            null,
            function () use (&$completed) {
                $completed = true;
            }
        );

        self::assertTrue($completed);
        self::assertSame(1337, $currentI);
    }

    public function testUnwrapObservableFromPromiseOnError()
    {
        $completed = false;
        $currentException = null;

        $exception = new Exception('boom');

        unwrapObservableFromPromise(
            resolve(
                Promise::toObservable(
                    reject(
                        $exception
                    )
                )
            )
        )->subscribeCallback(
            null,
            function ($exception) use (&$currentException) {
                $currentException = $exception;
            },
            function () use (&$completed) {
                $completed = true;
            }
        );

        self::assertFalse($completed);
        self::assertSame($exception, $currentException);
    }

    /**
     * @expectedException Exception
     */
    public function testUnwrapObservableFromPromiseDoesNotSwallowException()
    {
        unwrapObservableFromPromise(
            resolve(
                Observable::just(1)
            )
        )->subscribeCallback(
            function () {
                throw new Exception('boom');
            }
        );
    }
}
